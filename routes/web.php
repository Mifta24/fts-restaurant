<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/contact', function (Request $request) use ($supportedLocales) {
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

    // Lead storage/email can be connected here. For now the form is validated and acknowledged.
    session()->flash('lead', $validated);

    return redirect()
        ->to(url()->previous().'#contact')
        ->with('status', __('landing.form.success'));
})->name('landing.contact');
