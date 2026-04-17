<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['ApiLocalization']], function () {
    Route::group(['middleware' => ['checkAuth']], function () {
        Route::post('cart/add', 'Api\v1\CartController@add');
        Route::get('cart/list', 'Api\v1\CartController@index');
        Route::get('vendor/payOptions/{id?}', 'Api\v1\CartController@getVendorPayOptions');
        Route::post('homepage', 'Api\v1\HomeController@homepage');
        Route::post('delivery-homepage', 'Api\v1\HomeController@delivery_homepage');
        Route::post('header', 'Api\v1\HomeController@headerContent');
        Route::get('language', 'Api\v1\HomeController@getLanguageDetails');
        Route::get('active-languages', 'Api\v1\HomeController@getActiveLanguages');
        Route::get('product/{id}', 'Api\v1\ProductController@productById');
        Route::post('get-products', 'Api\v1\ProductController@productList');
        Route::get('cms/page/list', 'Api\v1\CMSPageController@getPageList');
        Route::get('cms/page/prohibited-items', 'Api\v1\CMSPageController@getProhibiteditems');
        Route::get('brand/{id?}', 'Api\v1\BrandController@productsByBrand');
        Route::get('category/{id?}', 'Api\v1\CategoryController@categoryData');
        Route::get('vendor/{id?}', 'Api\v1\VendorController@productsByVendor');
        Route::post('search/{type}/{id?}', 'Api\v1\HomeController@globalSearch');
        Route::post('cms/page/detail', 'Api\v1\CMSPageController@getPageDetail');
        Route::post('brand/filters/{id?}', 'Api\v1\BrandController@brandFilters');
        Route::get('celebrity/{all?}', 'Api\v1\CelebrityController@celebrityList');
        Route::post('vendor/filters/{id?}', 'Api\v1\VendorController@vendorFilters');
        Route::post('category/filters/{id?}', 'Api\v1\CategoryController@categoryFilters');
        Route::get('celebrityProducts/{id?}', 'Api\v1\CelebrityController@celebrityProducts');
        Route::post('celebrity/filters/{id?}', 'Api\v1\CelebrityController@celebrityFilters');
        Route::post('vendor/category/list', 'Api\v1\VendorController@postVendorCategoryList');
        // Route::post('vendor/category/list', 'Api\v1\VendorController@postVendorCategoryList');
        Route::get('vendor/{slug1}/{slug2}', 'Api\v1\VendorController@vendorCategoryProducts');
        Route::post('checkIsolateSingleVendor', 'Api\v1\CartController@checkIsolateSingleVendor');
        // Route::get('vendor/category/productsFilter/{slug1}/{slug2}', 'Api\v1\VendorController@vendorCategoryProductsFilter');
        Route::post('productByVariant/{id}', 'Api\v1\ProductController@getVariantData')->name('productVariant');
        Route::post('contact-us', 'Api\v1\HomeController@contactUs');
        Route::get('category-details/{id}', 'Api\v1\CategoryController@categoryVendorProductDetails');
    });
    Route::group(['middleware' => ['systemAuth']], function () {
        Route::get('cart/empty', 'Api\v1\CartController@emptyCart');
        Route::get('coupons/{id?}', 'Api\v1\CouponController@list');
        Route::post('cart/remove', 'Api\v1\CartController@removeItem');
        Route::get('cart/totalItems', 'Api\v1\CartController@getItemCount');
        Route::post('cart/updateQuantity', 'Api\v1\CartController@updateQuantity');
        Route::post('promo-code/list', 'Api\v1\PromoCodeController@postPromoCodeList');
        Route::post('promo-code/verify', 'Api\v1\PromoCodeController@postVerifyPromoCode');
        Route::post('promo-code/remove', 'Api\v1\PromoCodeController@postRemovePromoCode');
        Route::post('promo-code/validate_promo_code', 'Api\v1\PromoCodeController@validate_promo_code');
        Route::post('cart/product-schedule/update', 'Api\v1\CartController@updateProductSchedule');
        Route::post('cart/update-tip-amount', 'Api\v1\CartController@updateCartTipAmount');
        Route::post('cart/update-special-instruction', 'Api\v1\CartController@updateCartSpecialInstruction');
    });


    Route::post('cart/toggle-use-wallet', 'Api\v1\CartController@toggleWalletUse');
    Route::post('stripe-update', 'Api\v1\PaymentOptionController@StripeWebhook')->name('stripe-webhook');
    Route::get('get-app-force-update-status', 'Api\v1\ActivityController@getAppForceUpdateStatus');
    Route::get('check-service-availability', 'Api\v1\VendorController@checkServiceAvailability');
});
