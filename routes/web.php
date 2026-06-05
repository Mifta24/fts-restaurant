<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'home'])->name('landing.home');
Route::get('/{locale}', [LandingController::class, 'locale'])
    ->whereIn('locale', ['id', 'en', 'jp', 'ja'])
    ->name('landing.locale');
Route::post('/contact', [LandingController::class, 'contact'])->name('landing.contact');
