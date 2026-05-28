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

    public function analyze(array $lead): string
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
                'max_tokens' => 700,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('AI request failed: '.$response->body());
        }

        $analysis = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

        if ($analysis === '') {
            throw new RuntimeException('AI response did not contain text.');
        }

        return $analysis;
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
            'Format jawaban:',
            '1. Ringkasan kondisi awal',
            '2. Peluang terbesar',
            '3. Masalah yang mungkin menghambat',
            '4. Rekomendasi prioritas 7 hari pertama',
            '5. Data tambahan yang sebaiknya dicek tim',
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
}
