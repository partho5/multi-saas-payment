<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Payment\Paypal\PayPalController;
use Illuminate\Support\Facades\Route;


Route::get('{serviceIdentifier}/pricing', \App\Livewire\Packages\PackageSelection::class);
Route::get('{serviceIdentifier}/{packageSlug}/buy', [\App\Http\Controllers\Payment\PackageController::class, 'showBuyPackage']);

require __DIR__.'/payment.php';


Route::get('/', [HomeController::class, 'index'])->name('landingPage');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';



Route::get('create-transaction', [PayPalController::class, 'createTransaction'])->name('createTransaction');
Route::post('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');
Route::get('callback', [PayPalController::class, 'callback'])->name('callbackURL');

Route::get('/paypal/subscription', [PayPalController::class, 'createSubscription'])->name('paypal.subscription');
Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

