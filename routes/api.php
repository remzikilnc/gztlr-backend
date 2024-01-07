<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SendEmailVerificationNotificationController;
use App\Http\Controllers\Auth\SendPasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group(function () {

    Route::middleware('guest:api')->group(function () {
        Route::post('/register', [JwtAuthController::class, 'register'])->name('register')->middleware('throttle:auth');
        Route::post('/login', [JwtAuthController::class, 'login'])->name('login')->middleware('throttle:auth', 'isSuspended');
        Route::post('/forgot-password', SendPasswordResetLinkController::class)->middleware('throttle:auth');
        Route::post('/reset-password', ResetPasswordController::class)->name('password.update')->middleware('throttle:auth');
    });

    Route::middleware(['auth:api', 'isSuspended'])->group(function () {
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::patch('/profile/password', [ProfileController::class, 'passwordChange']);
        Route::delete('/profile', [ProfileController::class, 'destroy']);
        Route::post('/email/verification-notification', SendEmailVerificationNotificationController::class)->name('verification.send')->middleware(['throttle:auth']);
        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify')->middleware(['signed', 'throttle:auth']);
        Route::post('/logout', [JwtAuthController::class, 'logout'])->name('logout');
    });

    Route::post('/token/refresh', [JwtAuthController::class, 'refresh'])->name('refresh');


    Route::middleware(['auth:api', 'verified', 'isSuspended'])->group(function () {

        Route::get('users/statistics', [UserController::class, 'statistics']);
        Route::apiResource('users', UserController::class);

        Route::apiResource('roles', RoleController::class);

        Route::apiResource('permission', PermissionController::class);
    });

    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{city}', [CityController::class, 'show']);

    Route::get('weathers/{city}', [WeatherController::class, 'index']);
    Route::get('weathers/{city}/{weather}', [WeatherController::class, 'show']);
})->middleware(['throttle-api:api', 'api']);
