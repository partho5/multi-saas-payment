<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PackageController;

Route::get('/payment/plans/{appIdentifier}', [PackageController::class, 'showPackages'])->name('showPackages');
Route::post('/payment/checkout', [PackageController::class, 'checkout']);
