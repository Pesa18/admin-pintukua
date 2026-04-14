<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\api\OtpController;
use App\Http\Middleware\ForgotPassword;
use App\Http\Middleware\VerifyEmail;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('ceklogin', [AuthController::class, 'ceklogin']);
        Route::post('google-login', [AuthController::class, 'loginWithGoogle']);
        Route::post('register', [AuthController::class, 'registration']);
        Route::post('otp/request', [OtpController::class, 'requestOtp']);
        Route::post('otp/verify-email', [OtpController::class, 'verifyEmail'])->middleware(VerifyEmail::class);
        Route::post('otp/forgot-password', [OtpController::class, 'forgotPassword'])->middleware(ForgotPassword::class);
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware(ForgotPassword::class);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });
    Route::get('/beranda', [HomeController::class, 'index']);
});
