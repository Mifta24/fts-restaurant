<?php

namespace App\Http\Controllers;

use App\Mail\FreeAnalysisLeadSubmitted;
use App\Services\RestaurantAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Throwable;

class LandingController extends Controller
{
    private const SUPPORTED_LOCALES = [
        'id' => 'id',
        'en' => 'en',
        'jp' => 'ja',
        'ja' => 'ja',
    ];

    public function home(): View
    {
        app()->setLocale('id');

        return view('landing', [
            'localeSlug' => 'id',
        ]);
    }

    public function locale(string $locale): View
    {
        abort_unless(array_key_exists($locale, self::SUPPORTED_LOCALES), 404);

        app()->setLocale(self::SUPPORTED_LOCALES[$locale]);

        return view('landing', [
            'localeSlug' => $locale === 'ja' ? 'jp' : $locale,
        ]);
    }

    public function contact(Request $request, RestaurantAnalysisService $analysisService): RedirectResponse
    {
        $locale = (string) $request->input('locale', 'id');
        app()->setLocale(self::SUPPORTED_LOCALES[$locale] ?? 'id');

        $validator = Validator::make($request->all(), [
            'locale' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:120'],
            'store' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'store_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'gmap_url' => ['nullable', 'url', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ]);

        if ($validator->fails()) {
            return redirect($this->analysisUrl($locale))
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        session()->flash('lead', $validated);

        $analysis = null;
        $analysisError = null;

        if ($analysisService->isConfigured()) {
            try {
                $analysis = $analysisService->analyze($validated);
                session()->flash('ai_analysis', $analysis);
            } catch (Throwable $exception) {
                Log::warning('Free analysis AI draft failed.', [
                    'email' => $validated['email'],
                    'message' => $exception->getMessage(),
                ]);

                $analysisError = __('landing.form.ai_error');
                session()->flash('ai_analysis_error', $analysisError);
            }
        } else {
            $analysisError = __('landing.form.ai_not_configured');
            session()->flash('ai_analysis_error', $analysisError);
        }

        if (filled(config('services.analysis.recipient_email'))) {
            try {
                Mail::to(
                    (string) config('services.analysis.recipient_email'),
                    (string) config('services.analysis.recipient_name')
                )->send(new FreeAnalysisLeadSubmitted($validated, $analysis, $analysisError));
            } catch (Throwable $exception) {
                Log::error('Free analysis lead notification failed.', [
                    'email' => $validated['email'],
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return redirect($this->analysisUrl($locale))
            ->with('status', __('landing.form.success'));
    }

    private function analysisUrl(string $locale): string
    {
        if (! array_key_exists($locale, self::SUPPORTED_LOCALES) || $locale === 'id') {
            return rtrim(url('/'), '/').'/#analysis';
        }

        return route('landing.locale', ['locale' => $locale === 'ja' ? 'jp' : $locale]).'#analysis';
    }
}
