<?php

namespace Tests\Feature;

use App\Mail\FreeAnalysisLeadSubmitted;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AnalysisFormTest extends TestCase
{
    public function test_contact_form_stores_ai_analysis_in_session(): void
    {
        config()->set('services.ai.base_url', 'https://example.test/v1');
        config()->set('services.ai.api_key', 'test-key');
        config()->set('services.ai.model', 'test-model');
        config()->set('services.analysis.recipient_email', 'owner@example.com');
        config()->set('services.analysis.recipient_name', 'FTS Owner');

        Http::fake([
            'example.test/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'title' => 'Draft Analisis Warung Bahagia',
                                'summary' => 'Restoran punya peluang memperkuat kanal digital.',
                                'sections' => [
                                    [
                                        'heading' => 'Ringkasan kondisi awal',
                                        'items' => ['Website dan media sosial perlu dirapikan.'],
                                    ],
                                ],
                            ]),
                        ],
                    ],
                ],
            ]),
        ]);
        Mail::fake();

        $response = $this
            ->from('/#contact')
            ->post('/contact', [
                'locale' => 'id',
                'name' => 'Budi Santoso',
                'store' => 'Warung Bahagia',
                'email' => 'budi@example.com',
                'store_url' => 'https://example.com',
                'instagram_url' => 'https://instagram.com/warungbahagia',
                'gmap_url' => 'https://maps.app.goo.gl/example',
                'message' => 'Followers Instagram belum naik.',
                'consent' => 'on',
            ]);

        $response
            ->assertRedirect('http://localhost/#analysis')
            ->assertSessionHas('status')
            ->assertSessionHas('ai_analysis.title', 'Draft Analisis Warung Bahagia')
            ->assertSessionHas('ai_analysis.sections.0.heading', 'Ringkasan kondisi awal');

        Http::assertSent(fn ($request) => $request->url() === 'https://example.test/v1/chat/completions'
            && $request['model'] === 'test-model'
            && $request['messages'][1]['content'] !== ''
        );

        Mail::assertSent(FreeAnalysisLeadSubmitted::class, fn (FreeAnalysisLeadSubmitted $mail) => $mail->hasTo('owner@example.com')
            && data_get($mail->lead, 'email') === 'budi@example.com'
            && data_get($mail->analysis, 'title') === 'Draft Analisis Warung Bahagia'
        );
    }

    public function test_free_analysis_buttons_point_to_analysis_form(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee('id="analysis"', false)
            ->assertSee('href="#analysis"', false);
    }
}
