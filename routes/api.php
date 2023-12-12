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
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'middleware' => 'custom.jwt'], function () {
        Route::controller(CustomerAuthController::class)->group(function () {
            Route::get('view-profile', 'getCustomerDetails');
            Route::put('update-profile', 'updateCustomerDetails');
            Route::put('change-password', 'changePassword');
            Route::delete('delete-account', 'deleteAccount');
            Route::post('verify-password', 'verifyPassword');
            Route::post('logout', 'logout');
        });
    });

    Route::group(['middleware' => 'custom.jwt'], function () {

        Route::controller(UsersAddressController::class)->group(function () {
            Route::post('manage-delivery-address', 'manageAddress');
            Route::get('get-delivery-address', 'getAddress');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::get('get-all-subcategories', 'getAllSubCategories');
            Route::get('get-popular-services', 'getPopularServices');
        });

        Route::controller(BannerController::class)->group(function () {
            Route::get('get-all-banners', 'index');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('get-setting-data', 'index');
            Route::get('update-notification-setting', 'updateNotificationSetting');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::get('get-product-list', 'getProductList');
            Route::get('product-detail', 'getProductDetail');
            Route::get('manage-item-in-whishlist', 'manageItemInWishList');
            Route::get('get-item-in-whishlist', 'getItemInWishList');
            Route::get('get-product-recommendation-list', 'getProductRecommendationList');
        });

        Route::controller(CouponController::class)->group(function () {
            Route::get('get-dashboard-coupon-data', 'index');
        });

        Route::controller(FAQController::class)->group(function () {
            Route::get('get-faq-data', 'index');
        });

        Route::controller(CartController::class)->group(function () {
            Route::post('add-item-in-cart', 'addItemInCart');
            Route::get('get-cart-items', 'getCartItems');
            Route::delete('delete-cart-item', 'removeItemFromCart');
            Route::delete('empty-cart', 'emptyCart');
        });
    });
});

