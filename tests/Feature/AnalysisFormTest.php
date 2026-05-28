<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AnalysisFormTest extends TestCase
{
    public function test_contact_form_stores_ai_analysis_in_session(): void
    {
        config()->set('services.ai.base_url', 'https://example.test/v1');
        config()->set('services.ai.api_key', 'test-key');
        config()->set('services.ai.model', 'test-model');

        Http::fake([
            'example.test/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Draft analisis restoran berhasil dibuat.',
                        ],
                    ],
                ],
            ]),
        ]);

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
            ->assertRedirect('http://localhost/#contact')
            ->assertSessionHas('status')
            ->assertSessionHas('ai_analysis', 'Draft analisis restoran berhasil dibuat.');

        Http::assertSent(fn ($request) => $request->url() === 'https://example.test/v1/chat/completions'
            && $request['model'] === 'test-model'
            && $request['messages'][1]['content'] !== ''
        );
    }
}
