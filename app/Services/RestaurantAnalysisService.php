<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class RestaurantAnalysisService
{
    public function isConfigured(): bool
    {
        return filled(config('services.ai.api_key'))
            && filled(config('services.ai.base_url'))
            && filled(config('services.ai.model'));
    }

    public function analyze(array $lead): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('AI provider is not configured.');
        }

        $response = Http::withToken((string) config('services.ai.api_key'))
            ->acceptJson()
            ->asJson()
            ->withOptions($this->httpOptions())
            ->timeout((int) config('services.ai.timeout', 30))
            ->post($this->endpoint(), [
                'model' => config('services.ai.model'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->systemPrompt(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->userPrompt($lead),
                    ],
                ],
                'temperature' => 0.4,
                'max_tokens' => 900,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('AI request failed: '.$response->body());
        }

        $analysis = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

        if ($analysis === '') {
            throw new RuntimeException('AI response did not contain text.');
        }

        return $this->normalizeAnalysis($analysis, $lead);
    }

    private function endpoint(): string
    {
        return rtrim((string) config('services.ai.base_url'), '/').'/chat/completions';
    }

    private function httpOptions(): array
    {
        if (filled(config('services.ai.proxy'))) {
            return [
                'proxy' => config('services.ai.proxy'),
            ];
        }

        return [
            'proxy' => [
                'http' => null,
                'https' => null,
            ],
            'curl' => [
                CURLOPT_PROXY => '',
            ],
        ];
    }

    private function systemPrompt(): string
    {
        return implode("\n", [
            'Anda adalah konsultan marketing restoran.',
            'Buat analisa awal yang ringkas, praktis, dan spesifik untuk pemilik restoran.',
            'Jangan mengklaim sudah membuka URL atau mengambil data live.',
            'Jika URL tersedia, gunakan hanya sebagai konteks awal yang perlu dicek lagi.',
            'Fokus pada website, Instagram, Google Maps, positioning, dan rekomendasi prioritas.',
            'Balas hanya dengan JSON valid. Jangan gunakan markdown, code fence, atau teks pembuka.',
        ]);
    }

    private function userPrompt(array $lead): string
    {
        $language = match ($lead['locale'] ?? 'id') {
            'en' => 'English',
            'jp', 'ja' => 'Japanese',
            default => 'Indonesian',
        };

        return implode("\n", [
            'Buat draft analisa marketing awal berdasarkan data form berikut.',
            'Bahasa output: '.$language,
            '',
            'Format JSON wajib:',
            '{"title":"...","summary":"...","initial_condition":["...","..."],"opportunities":["...","..."],"blockers":["...","..."],"seven_day_plan":["...","...","..."],"team_checks":["...","..."]}',
            '',
            'Aturan:',
            '- Wajib isi semua key JSON.',
            '- Setiap array berisi 2 sampai 4 item.',
            '- Setiap item maksimal 22 kata.',
            '- Pakai bahasa yang natural, bukan terlalu kaku.',
            '',
            'Data form:',
            'Nama: '.($lead['name'] ?? '-'),
            'Restoran: '.($lead['store'] ?? '-'),
            'Email: '.($lead['email'] ?? '-'),
            'Website: '.($lead['store_url'] ?? '-'),
            'Instagram: '.($lead['instagram_url'] ?? '-'),
            'Google Maps: '.($lead['gmap_url'] ?? '-'),
            'Pesan: '.($lead['message'] ?? '-'),
        ]);
    }

    private function normalizeAnalysis(string $content, array $lead): array
    {
        $decoded = json_decode($this->extractJson($content), true);

        if (is_array($decoded)) {
            $fixedSections = $this->sectionsFromFixedKeys($decoded, $lead);

            if ($fixedSections !== []) {
                return [
                    'title' => $this->text($decoded['title'] ?? 'Draft Analisis AI'),
                    'summary' => $this->text($decoded['summary'] ?? ''),
                    'sections' => $fixedSections,
                ];
            }
        }

        if (is_array($decoded) && isset($decoded['sections']) && is_array($decoded['sections'])) {
            return [
                'title' => $this->text($decoded['title'] ?? 'Draft Analisis AI'),
                'summary' => $this->text($decoded['summary'] ?? ''),
                'sections' => collect($decoded['sections'])
                    ->map(fn ($section) => [
                        'heading' => $this->text($section['heading'] ?? ''),
                        'items' => collect($section['items'] ?? [])
                            ->map(fn ($item) => $this->text($item))
                            ->filter()
                            ->values()
                            ->all(),
                    ])
                    ->filter(fn ($section) => $section['heading'] !== '' && count($section['items']) > 0)
                    ->values()
                    ->all(),
            ];
        }

        $sections = $this->sectionsFromText($content);

        return [
            'title' => 'Draft Analisis AI',
            'summary' => '',
            'sections' => $sections !== [] ? $sections : [[
                'heading' => 'Hasil analisis awal',
                'items' => collect(preg_split('/\r\n|\r|\n/', $content))
                    ->map(fn ($line) => $this->cleanLine($line))
                    ->filter()
                    ->take(8)
                    ->values()
                    ->all(),
            ]],
        ];
    }

    private function sectionsFromFixedKeys(array $decoded, array $lead): array
    {
        $labels = $this->sectionLabels($lead);

        return collect([
            'initial_condition',
            'opportunities',
            'blockers',
            'seven_day_plan',
            'team_checks',
        ])
            ->map(fn ($key) => [
                'heading' => $labels[$key],
                'items' => collect($decoded[$key] ?? [])
                    ->map(fn ($item) => $this->text($item))
                    ->filter()
                    ->values()
                    ->all(),
            ])
            ->filter(fn ($section) => count($section['items']) > 0)
            ->values()
            ->all();
    }

    private function sectionLabels(array $lead): array
    {
        return match ($lead['locale'] ?? 'id') {
            'en' => [
                'initial_condition' => 'Initial condition',
                'opportunities' => 'Biggest opportunities',
                'blockers' => 'Possible blockers',
                'seven_day_plan' => '7-day priority plan',
                'team_checks' => 'Data the team should verify',
            ],
            'jp', 'ja' => [
                'initial_condition' => '初期状態',
                'opportunities' => '大きな機会',
                'blockers' => '想定される課題',
                'seven_day_plan' => '最初の7日間の優先施策',
                'team_checks' => 'チームが追加確認すべき情報',
            ],
            default => [
                'initial_condition' => 'Ringkasan kondisi awal',
                'opportunities' => 'Peluang terbesar',
                'blockers' => 'Masalah yang mungkin menghambat',
                'seven_day_plan' => 'Prioritas 7 hari pertama',
                'team_checks' => 'Data tambahan yang sebaiknya dicek tim',
            ],
        };
    }

    private function sectionsFromText(string $content): array
    {
        $sections = [];
        $current = null;

        foreach (preg_split('/\r\n|\r|\n/', $content) as $line) {
            $line = $this->cleanLine($line);

            if ($line === '' || str_contains(mb_strtolower($line), 'draft analisis')) {
                continue;
            }

            if ($this->looksLikeHeading($line)) {
                if ($current !== null && $current['items'] !== []) {
                    $sections[] = $current;
                }

                $current = [
                    'heading' => $line,
                    'items' => [],
                ];

                continue;
            }

            if ($current === null) {
                $current = [
                    'heading' => 'Hasil analisis awal',
                    'items' => [],
                ];
            }

            $current['items'][] = $line;
        }

        if ($current !== null && $current['items'] !== []) {
            $sections[] = $current;
        }

        return array_slice($sections, 0, 5);
    }

    private function looksLikeHeading(string $line): bool
    {
        $line = mb_strtolower($line);

        foreach ([
            'ringkasan',
            'peluang',
            'masalah',
            'rekomendasi',
            'data tambahan',
            'summary',
            'opportunit',
            'blocker',
            'priority',
            'additional data',
            '現状',
            '機会',
            '課題',
            '推奨',
            '確認',
        ] as $keyword) {
            if (str_contains($line, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function cleanLine(mixed $value): string
    {
        $line = $this->text($value);
        $line = (string) preg_replace('/^\s*(?:[-*•]|\d+[.)])\s*/u', '', $line);

        return trim($line, " \t\n\r\0\x0B*#");
    }

    private function extractJson(string $content): string
    {
        $content = trim($content);

        if (str_starts_with($content, '```')) {
            $content = trim((string) preg_replace('/^```(?:json)?|```$/m', '', $content));
        }

        $start = strpos($content, '{');
        $end = strrpos($content, '}');

        if ($start !== false && $end !== false && $end > $start) {
            return substr($content, $start, $end - $start + 1);
        }

        return $content;
    }

    private function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }
}
