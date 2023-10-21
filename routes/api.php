<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::controller(CustomerAuthController::class)->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('forget-password', 'forgotPassword');            
            Route::post('verify-otp', 'verifyOTP');
            Route::post('resend-otp', 'resendOTP');
            Route::put('reset-password', 'resetPassword');
        });        
    });
});

Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth','middleware' => 'custom.jwt'], function () {
        Route::controller(CustomerAuthController::class)->group(function () {

            Route::get('view-profile', 'getCustomerDetails');
            Route::put('update-profile', 'updateCustomerDetails');
            Route::put('change-password', 'changePassword');
            Route::delete('delete-account', 'deleteAccount');
            Route::post('verify-password', 'verifyPassword');
            Route::post('logout', 'logout');           
        });      
        
        Route::controller(UsersAddressController::class)->group(function () {
            Route::post('manage-delivery-address', 'managaeAddress');
        });  
    });
});


