<?php

use App\Http\Middleware\SetLocaleMiddleware;
use App\Livewire\SymptomForm;
use App\Livewire\Questions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/questions', Questions::class);

Route::get('/symptoms', SymptomForm::class)->name('symptom.form')->middleware(SetLocaleMiddleware::class);

Route::get('/result/{category}', function ($category) {
    return view('result', ['category' => $category]);
})->name('result');

// Language switcher route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, array_keys(config('app.available_locales')))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

require __DIR__.'/auth.php';
