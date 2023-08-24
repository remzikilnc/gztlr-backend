<?php

use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SendEmailVerificationNotificationController;
use App\Http\Controllers\Auth\SendPasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:api')->group(function () {

Route::post('/register', [JwtAuthController::class, 'register'])->name('register')->middleware('throttle:auth');
Route::post('/login', [JwtAuthController::class, 'login'])->name('login')->middleware('throttle:auth');
Route::post('/token/refresh', [JwtAuthController::class, 'refresh'])->name('refresh')->middleware('throttle:auth');
Route::post('/forgot-password', SendPasswordResetLinkController::class)->middleware('throttle:auth');
Route::post('/reset-password', ResetPasswordController::class)->name('password.update')->middleware('throttle:auth');
});


Route::middleware('auth:api')->group(function () {
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile/password', [ProfileController::class, 'passwordChange']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    Route::post('/email/verification-notification', SendEmailVerificationNotificationController::class)->name('verification.send')->middleware(['throttle:auth']);
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify')->middleware(['signed', 'throttle:auth']);
    Route::post('/logout', [JwtAuthController::class, 'logout'])->name('logout');
});
