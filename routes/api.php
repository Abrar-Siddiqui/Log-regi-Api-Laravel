<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;

// Public Route

Route::controller(UserController::class)->group(function(){
    Route::POST('/register','Register');
    Route::POST('/login','Login');

});


// Email On Password reset
Route::controller(PasswordResetController::class)->group(function(){
    Route::POST('/send-reset-password-email','send_rest_passwoed_email');
    Route::POST('/reset_password/{token}','Resest');
});

// Protected Route
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(UserController::class)->group(function(){
        Route::post('/logout','Logout');
        Route::get('/logeddata','Logged_data');
        Route::post('/changepassword','Change_Password');
    });
});
