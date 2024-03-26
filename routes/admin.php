<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });
    /*authentication*/

    Route::group(['middleware' => ['admin']], function () {

        Route::get('settings', 'SystemController@settings')->name('settings');
        Route::post('settings', 'SystemController@settings_update');
        Route::post('settings-password', 'SystemController@settings_password_update')->name('settings-password');
        Route::get('/get-restaurant-data', 'SystemController@restaurant_data')->name('get-restaurant-data');

        //dashboard
        Route::get('/', 'DashboardController@dashboard')->name('dashboard');

        Route::resource('account-transaction', 'AccountTransactionController')->middleware('module:account');

        Route::resource('provide-deliveryman-earnings', 'ProvideDMEarningController')->middleware('module:provide_dm_earning');

        Route::get('maintenance-mode', 'SystemController@maintenance_mode')->name('maintenance-mode');
        
        Route::group(['prefix' => 'dashboard-stats', 'as' => 'dashboard-stats.'], function () {
            Route::post('order', 'DashboardController@order')->name('order');
            Route::post('zone', 'DashboardController@zone')->name('zone');
            Route::post('user-overview', 'DashboardController@user_overview')->name('user-overview');
            Route::post('business-overview', 'DashboardController@business_overview')->name('business-overview');
        });

      /*  Route::group(['prefix' => 'employee-role', 'as' => 'custom-role.', 'middleware' => ['module:custom_role']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store');
            Route::get('edit/{id}', 'CustomRoleController@edit')->name('edit');
            Route::post('update/{id}', 'CustomRoleController@update')->name('update');
            Route::delete('delete/{id}', 'CustomRoleController@distroy')->name('delete');
            Route::post('search', 'CustomRoleController@search')->name('search');
        });*/

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.', 'middleware' => ['module:custom_role']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store');
            Route::get('edit/{id}', 'CustomRoleController@edit')->name('edit');
            Route::post('update/{id}', 'CustomRoleController@update')->name('update');
            Route::delete('delete/{id}', 'CustomRoleController@distroy')->name('delete');
            Route::post('search', 'CustomRoleController@search')->name('search');
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.', 'middleware' => ['module:employee']], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('update/{id}', 'EmployeeController@edit')->name('edit');
            Route::post('update/{id}', 'EmployeeController@update')->name('update');
            Route::delete('delete/{id}', 'EmployeeController@distroy')->name('delete');
            Route::post('search', 'EmployeeController@search')->name('search');
        });

        Route::group(['prefix' => 'pos', 'as' => 'pos.', 'middleware' => ['module:pos']], function () {
            Route::get('create', 'POSController@create')->name('create');
            Route::post('create', 'POSController@store');
            Route::post('getsubcategories', 'POSController@getSubCategories')->name('getsubcategories');
            Route::post('getproducts', 'POSController@getProductList')->name('getproducts');
            Route::post('addproductsincart', 'POSController@addProductInCart')->name('addproductsincart');
            Route::get('booking-list', 'POSController@list')->name('booking-list');
            
        });

        Route::post('item/variant-price', 'ItemController@variant_price')->name('item.variant-price');

        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['module:customer']], function () {
            Route::get('add-new', 'CustomerController@add_new')->name('add-new');
            Route::get('export', 'CustomerController@export')->name('export');
            Route::get('export-refereed-list', 'CustomerController@exportRefereedList')->name('export-refereed-list');
            Route::post('add-new', 'CustomerController@store');
            Route::get('list', 'CustomerController@list')->name('list');
            Route::get('update/{id}', 'CustomerController@edit')->name('edit');
            Route::post('update/{id}', 'CustomerController@update')->name('update');
            Route::delete('delete/{id}', 'CustomerController@distroy')->name('delete');
            Route::post('search', 'CustomerController@search')->name('search');
            Route::post('refereddsearch', 'CustomerController@refereddsearch')->name('refereddsearch');
            Route::get('refereed-list', 'CustomerController@refereed_list')->name('refereed-list');
            Route::get('filter/reset', 'CustomerController@filter_reset');
            Route::post('filter', 'CustomerController@filter')->name('filter');
        });
        Route::post('food/variant-price', 'ProductController@variant_price')->name('food.variant-price');

        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::get('add-new', 'ProductController@index')->name('add-new');
            Route::post('variant-combination', 'ProductController@variant_combination')->name('variant-combination');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::get('list', 'ProductController@list')->name('list');
            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');
            Route::get('status/{id}/{status}', 'ProductController@status')->name('status');
            Route::get('review-status/{id}/{status}', 'ProductController@reviews_status')->name('reviews.status');
            Route::post('search', 'ProductController@search')->name('search');
            Route::get('remove-image', 'ProductController@remove_image')->name('remove-image');
            Route::get('remove-color-image', 'ProductController@remove_color_image')->name('remove-color-image');
            Route::get('view/{id}', 'ProductController@view')->name('view');
            //ajax request
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
            Route::get('get-foods', 'ProductController@get_foods')->name('getfoods');
            Route::get('get-products', 'ProductController@get_products')->name('get-products');
            
            //Import and export
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_index')->name('bulk-export-index');
            Route::post('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:banner']], function () {
            Route::get('add-new', 'BannerController@index')->name('add-new');
            Route::post('store', 'BannerController@store')->name('store');
            Route::get('edit/{banner}', 'BannerController@edit')->name('edit');
            Route::post('update/{banner}', 'BannerController@update')->name('update');
            Route::get('status/{id}/{status}', 'BannerController@status')->name('status');
            Route::delete('delete/{banner}', 'BannerController@delete')->name('delete');
            Route::post('search', 'BannerController@search')->name('search');
        });

       

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:coupon']], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('store', 'CouponController@store')->name('store');
            Route::get('update/{id}', 'CouponController@edit')->name('update');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
            Route::post('search', 'CouponController@search')->name('search');
        });

        Route::group(['prefix' => 'faq', 'as' => 'faq.', 'middleware' => ['module:coupon']], function () {
            Route::get('add-new', 'FAQController@add_new')->name('faq-add-new');
            Route::post('store', 'FAQController@store')->name('faq-store');
            Route::get('update/{id}', 'FAQController@edit')->name('faq-update');
            Route::post('update/{id}', 'FAQController@update');
            Route::get('status/{id}/{status}', 'FAQController@status')->name('faq-status');
            Route::delete('delete/{id}', 'FAQController@delete')->name('faq-delete');
            Route::post('search', 'FAQController@search')->name('search');
        });


        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('get-all', 'CategoryController@get_all')->name('get-all');
            Route::group(['middleware' => ['module:category']], function () {
                Route::get('add', 'CategoryController@index')->name('add');
                Route::get('add-sub-category', 'CategoryController@sub_index')->name('add-sub-category');
                Route::get('add-sub-sub-category', 'CategoryController@sub_sub_index')->name('add-sub-sub-category');
                Route::post('store', 'CategoryController@store')->name('store');
                Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
                Route::post('update/{id}', 'CategoryController@update')->name('update');
                Route::get('update-priority/{category}', 'CategoryController@update_priority')->name('priority');
                Route::post('store', 'CategoryController@store')->name('store');
                Route::get('status/{id}/{status}', 'CategoryController@status')->name('status');
                Route::delete('delete/{id}', 'CategoryController@delete')->name('delete');
                Route::post('search', 'CategoryController@search')->name('search');

                //Import and export
                Route::get('bulk-import', 'CategoryController@bulk_import_index')->name('bulk-import');
                Route::post('bulk-import', 'CategoryController@bulk_import_data');
                Route::get('bulk-export', 'CategoryController@bulk_export_index')->name('bulk-export-index');
                Route::post('bulk-export', 'CategoryController@bulk_export_data')->name('bulk-export');
            });
        });

        Route::group(['prefix' => 'booking', 'as' => 'order.', 'middleware' => ['module:order']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::get('export-order-list', 'OrderController@exportOrderList')->name('export-order-list');
            Route::get('status', 'OrderController@status')->name('status');
            // Route::put('status-update/{id}', 'OrderController@status')->name('status-update');
            Route::get('download-invoice/{id}', 'OrderController@downloadInvoice')->name('download-invoice');
            Route::get('view/{id}', 'OrderController@view')->name('view');
            Route::post('update-shipping/{order}', 'OrderController@update_shipping')->name('update-shipping');
            Route::delete('delete/{id}', 'OrderController@delete')->name('delete');

            Route::get('add-delivery-man/{order_id}/{delivery_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');
            Route::get('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('add-payment-ref-code/{id}', 'OrderController@add_payment_ref_code')->name('add-payment-ref-code');
            Route::get('restaurant-filter/{restaurant_id}', 'OrderController@restaurnt_filter')->name('restaurant-filter');
            Route::get('filter/reset', 'OrderController@filter_reset');
            Route::post('filter', 'OrderController@filter')->name('filter');
            Route::get('search', 'OrderController@search')->name('search');
            //order update
            Route::post('add-to-cart', 'OrderController@add_to_cart')->name('add-to-cart');
            Route::post('remove-from-cart', 'OrderController@remove_from_cart')->name('remove-from-cart');
            Route::get('update/{order}', 'OrderController@update')->name('update');
            Route::get('edit-order/{order}', 'OrderController@edit')->name('edit');
            Route::get('quick-view', 'OrderController@quick_view')->name('quick-view');
            Route::get('quick-view-cart-item', 'OrderController@quick_view_cart_item')->name('quick-view-cart-item');
            Route::post('initiate-refund', 'OrderController@initiateRefund')->name('initiate-refund');
            


        });

        Route::group(['prefix' => 'zone', 'as' => 'zone.', 'middleware' => ['module:zone']], function () {
            Route::get('/', 'ZoneController@index')->name('home');
            Route::post('store', 'ZoneController@store')->name('store');
            Route::get('edit/{id}', 'ZoneController@edit')->name('edit');
            Route::post('update/{id}', 'ZoneController@update')->name('update');
            Route::delete('delete/{zone}', 'ZoneController@destroy')->name('delete');
            Route::get('status/{id}/{status}', 'ZoneController@status')->name('status');
            Route::post('search', 'ZoneController@search')->name('search');
            Route::get('zone-filter/{id}', 'ZoneController@zone_filter')->name('zonefilter');
            Route::get('get-all-zone-cordinates/{id?}', 'ZoneController@get_all_zone_cordinates')->name('zoneCoordinates');
        });


        Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['module:notification']], function () {
            Route::get('add-new', 'NotificationController@index')->name('add-new');
            Route::post('store', 'NotificationController@store')->name('store');
            Route::get('edit/{id}', 'NotificationController@edit')->name('edit');
            Route::post('update/{id}', 'NotificationController@update')->name('update');
            Route::get('status/{id}/{status}', 'NotificationController@status')->name('status');
            Route::delete('delete/{id}', 'NotificationController@delete')->name('delete');
        });

        Route::group(['prefix' => 'web-app-settings', 'as' => 'business-settings.', 'middleware' => ['module:settings','actch']], function () {
            Route::get('business-setup', 'BusinessSettingsController@business_index')->name('business-setup');
            Route::get('apis', 'BusinessSettingsController@config_setup')->name('config-setup');
            Route::post('config-update', 'BusinessSettingsController@config_update')->name('config-update');
            Route::post('update-setup', 'BusinessSettingsController@business_setup')->name('update-setup');
            Route::get('app-settings', 'BusinessSettingsController@app_settings')->name('app-settings2');
            Route::POST('app-settings', 'BusinessSettingsController@update_app_settings')->name('app-settings');
            Route::get('landing-page-settings/{tab?}', 'BusinessSettingsController@landing_page_settings')->name('landing-page-settings');
            Route::POST('landing-page-settings/{tab}', 'BusinessSettingsController@update_landing_page_settings')->name('add-landing-page-settings');
            Route::DELETE('landing-page-settings/{tab}/{key}', 'BusinessSettingsController@delete_landing_page_settings')->name('landing-page-settings-delete');


            Route::get('pages/terms-and-conditions', 'BusinessSettingsController@terms_and_conditions')->name('terms-and-conditions');
            Route::post('pages/terms-and-conditions', 'BusinessSettingsController@terms_and_conditions_update');

            Route::get('pages/privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy');
            Route::post('pages/privacy-policy', 'BusinessSettingsController@privacy_policy_update');


            Route::get('pages/refunds-and-returns-policy', 'BusinessSettingsController@refunds_returns_policy')->name('refunds-and-returns-policy');
            Route::post('pages/refunds-and-returns-policy', 'BusinessSettingsController@refunds_returns_policy_update');

            Route::get('pages/shipping-policy', 'BusinessSettingsController@shipping_policy')->name('shipping-policy');
            Route::post('pages/shipping-policy', 'BusinessSettingsController@shipping_policy_update');

            Route::get('pages/about-us', 'BusinessSettingsController@about_us')->name('about-us');
            Route::post('pages/about-us', 'BusinessSettingsController@about_us_update');

             //recaptcha
            Route::get('recaptcha', 'BusinessSettingsController@recaptcha_index')->name('recaptcha_index');
            Route::post('recaptcha-update', 'BusinessSettingsController@recaptcha_update')->name('recaptcha_update');

        });


         Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['module:settings','actch']], function () {

            Route::get('toggle-settings/{key}/{value}', 'BusinessSettingsController@toggle_settings')->name('toggle-settings');

            Route::get('notification-setting', 'BusinessSettingsController@fcm_index')->name('fcm-index');
            Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm');

            Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');

            Route::get('mail-config', 'BusinessSettingsController@mail_index')->name('mail-config');
            Route::post('mail-config', 'BusinessSettingsController@mail_config');

            Route::get('payment-method', 'BusinessSettingsController@payment_index')->name('payment-method');
            Route::post('payment-method-update/{payment_method}', 'BusinessSettingsController@payment_update')->name('payment-method-update');

            Route::get('currency-add', 'BusinessSettingsController@currency_index')->name('currency-add');
            Route::post('currency-add', 'BusinessSettingsController@currency_store');
            Route::get('currency-update/{id}', 'BusinessSettingsController@currency_edit')->name('currency-update');
            Route::put('currency-update/{id}', 'BusinessSettingsController@currency_update');
            Route::delete('currency-delete/{id}', 'BusinessSettingsController@currency_delete')->name('currency-delete');

            Route::get('sms-module', 'SMSModuleController@sms_index')->name('sms-module');
            Route::post('sms-module-update/{sms_module}', 'SMSModuleController@sms_update')->name('sms-module-update');
            Route::post('remove-dynamic-time-slot', 'BusinessSettingsController@removeDynamicTimeSlot')->name('remove-dynamic-time-slot');
            Route::post('remove-dynamic-delivery-charge-slab', 'BusinessSettingsController@removeDynamicDeliveryChargeSlab')->name('remove-dynamic-delivery-charge-slab');

        });

        Route::group(['prefix' => 'message', 'as' => 'message.'], function () {
            Route::get('list', 'ConversationController@list')->name('list');
            Route::post('store/{user_id}', 'ConversationController@store')->name('store');
            Route::get('view/{user_id}', 'ConversationController@view')->name('view');
        });




        Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'middleware' => ['module:customerList']], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
            Route::post('search', 'ReviewsController@search')->name('search');
        });

        Route::group(['prefix' => 'report-and-analytics', 'as' => 'report.', 'middleware' => ['module:report']], function () {
            Route::get('order', 'ReportController@order_index')->name('order');
            Route::get('day-wise-report', 'ReportController@day_wise_report')->name('day-wise-report');
            Route::get('service-wise-report', 'ReportController@food_wise_report')->name('food-wise-report');
            Route::post('service-wise-report-search', 'ReportController@food_search')->name('food-wise-report-search');
            Route::get('order-transactions', 'ReportController@order_transaction')->name('order-transaction');
            Route::get('earning', 'ReportController@earning_index')->name('earning');
            Route::post('set-date', 'ReportController@set_date')->name('set-date');
        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['module:customerList']], function () {
            Route::get('list', 'CustomerController@list')->name('list');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
            Route::post('search', 'CustomerController@search')->name('search');
            Route::get('status/{customer}/{status}', 'CustomerController@status')->name('status');
        });


        Route::group(['prefix' => 'file-manager', 'as' => 'file-manager.'], function () {
            Route::get('/download/{file_name}', 'FileManagerController@download')->name('download');
            Route::get('/index/{folder_path?}', 'FileManagerController@index')->name('index');
            Route::post('/image-upload', 'FileManagerController@upload')->name('image-upload');
            Route::delete('/delete/{file_path}', 'FileManagerController@destroy')->name('destroy');
        });

        //social media login
        // Route::group(['prefix' => 'social-login', 'as' => 'social-login.','middleware'=>['module:business_settings']], function () {
        //     Route::get('view', 'BusinessSettingsController@viewSocialLogin')->name('view');
        //     Route::post('update/{service}', 'BusinessSettingsController@updateSocialLogin')->name('update');

        // });
    });

    Route::get('zone/get-coordinates/{id}', 'ZoneController@get_coordinates')->name('zone.get-coordinates');
});
