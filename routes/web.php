<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard1', 'dashboard1')
    ->middleware(['auth', 'verified'])
    ->name('dashboard1');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'dashboard')
        ->name('dashboard');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::view('pools', 'pools')
    ->middleware(['auth'])
    ->name('pools');

require __DIR__.'/auth.php';
