<?php

use App\Mail\FreeAnalysisLeadSubmitted;
use App\Services\RestaurantAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$supportedLocales = [
    'id' => 'id',
    'en' => 'en',
    'jp' => 'ja',
    'ja' => 'ja',
];

Route::get('/', function () {
    app()->setLocale('id');

    return view('landing', [
        'localeSlug' => 'id',
    ]);
})->name('landing.home');

Route::get('/{locale}', function (string $locale) use ($supportedLocales) {
    abort_unless(array_key_exists($locale, $supportedLocales), 404);

    app()->setLocale($supportedLocales[$locale]);

    return view('landing', [
        'localeSlug' => $locale === 'ja' ? 'jp' : $locale,
    ]);
})->whereIn('locale', array_keys($supportedLocales))->name('landing.locale');

Route::post('/contact', function (Request $request, RestaurantAnalysisService $analysisService) use ($supportedLocales) {
    $locale = (string) $request->input('locale', 'id');
    app()->setLocale($supportedLocales[$locale] ?? 'id');

    $validated = $request->validate([
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

    return redirect()
        ->to(Str::before(url()->previous(), '#').'#analysis')
        ->with('status', __('landing.form.success'));
})->name('landing.contact');
