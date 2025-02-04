<?php

use App\Livewire\Questions;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/questions', Questions::class);

Route::get('/result/{category}', function ($category) {
    return view('result', ['category' => $category]);
})->name('result');

require __DIR__.'/auth.php';
