<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/payment.php';


Route::get('/', [HomeController::class, 'index'])->name('landingPage');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
