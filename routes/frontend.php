<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/debug-sentry', function () {
	throw new Exception('My first Sentry error!');
});
Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
	return "Cache is cleared";
});

Route::group(['middleware' => ['domain']], function () {
	Route::get('testmial', 'Front\OrderController@mailTest');
	Route::get('dispatch-order-status-update/{id?}', 'Front\DispatcherController@dispatchOrderStatusUpdate')->name('dispatch-order-update'); // Order Status update Dispatch
	Route::get('dispatch-pickup-delivery/{id?}', 'Front\DispatcherController@dispatchPickupDeliveryUpdate')->name('dispatch-pickup-delivery'); // pickup delivery update from dispatch
	Route::post('update-dispatch-order', 'Front\DispatcherController@updateDispatchOrderDetails')->name('update-dispatch-order');
	Route::get('dispatch-order-status-update/{id?}', 'Front\DispatcherController@dispatchOrderStatusUpdate')->name('dispatch-order-update');
	Route::get('dispatch-order-refund', 'Front\DispatcherController@dispatchOrderRefundUpdate')->name('dispatch-order-refund');
	Route::get('demo', 'Front\CustomerAuthController@getTestHtmlPage');
	Route::get('cabbooking', 'Front\CustomerAuthController@getTestHtmlPage');
	Route::get('demo/cabBooking', 'Front\CustomerAuthController@getDemoCabBookingPage');
	Route::get('fcm', 'Front\CustomerAuthController@fcm');
	Route::get('send-notification', 'Front\CustomerAuthController@sendNotification');
	Route::get('test/email', function () {
		$data = [
			'email' => "harbans@yopmail.com",
			'code' => 'asd',
			'logo' => 'asd',
			'customer_name' => "Link from harbans",
			'code_text' => __('Register yourself using this referral code below to get bonus offer'),
			'link' => url('/') . "/user/register?refferal_code=",
		];
		$send_mail = 'test@yopmail.com';
		// App\Jobs\SendRefferalCodeEmailJob::dispatch($send_mail);
		dispatch(new App\Jobs\RefferalEmail($data));

		dd('send mail successfully !!');
	});
	Route::get('payment/gateway/returnResponse', 'Front\PaymentController@getGatewayReturnResponse')->name('payment.gateway.return.response');

	// Stripe
	Route::post('payment/stripe', 'Front\StripeGatewayController@postPaymentViaStripe')->name('payment.stripe');
	Route::post('user/subscription/payment/stripe', 'Front\StripeGatewayController@subscriptionPaymentViaStripe')->name('user.subscription.payment.stripe');

	// Paypal
	Route::post('payment/paypal', 'Front\PaypalGatewayController@paypalPurchase')->name('payment.paypalPurchase');
	Route::get('payment/paypal/CompletePurchase', 'Front\PaypalGatewayController@paypalCompletePurchase')->name('payment.paypalCompletePurchase');
	# for App side paypal payment
	Route::get('payment/paypal/completeCheckout/{token?}/{action?}/{address?}', 'Front\PaymentController@paypalCompleteCheckout')->name('payment.paypalCompleteCheckout');
	Route::get('payment/checkoutSuccess/{id}', 'Front\PaymentController@getCheckoutSuccess')->name('payment.getCheckoutSuccess');

	// Paystack
	Route::post('payment/paystack', 'Front\PaystackGatewayController@paystackPurchase')->name('payment.paystackPurchase');
	Route::post('payment/paystack/completePurchase', 'Front\PaystackGatewayController@paystackCompletePurchase')->name('payment.paystackCompletePurchase');

	// Payfast
	Route::post('payment/payfast', 'Front\PayfastGatewayController@payfastPurchase')->name('payment.payfastPurchase');
	Route::post('payment/payfast/notify', 'Front\PayfastGatewayController@payfastNotify')->name('payment.payfastNotify');
	Route::post('payment/payfast/notify/app', 'Front\PayfastGatewayController@payfastNotifyApp')->name('payment.payfastNotifyApp');
	Route::post('payment/payfast/completePurchase', 'Front\PayfastGatewayController@payfastCompletePurchase')->name('payment.payfastCompletePurchase');

	// Mobbex
	Route::post('payment/mobbex', 'Front\MobbexGatewayController@mobbexPurchase')->name('payment.mobbexPurchase');
	Route::post('payment/mobbex/notify', 'Front\MobbexGatewayController@mobbexNotify')->name('payment.mobbexNotify');

	Route::post('payment/yoco', 'Front\YocoGatewayController@yocoPurchase')->name('payment.yocoPurchase');


	Route::post('payment/paylink', 'Front\PaylinkGatewayController@paylinkPurchase')->name('payment.paylinkPurchase');
	Route::get('payment/paylink/notify', 'Front\PaylinkGatewayController@paylinkNotify')->name('payment.paylinkNotify');


	Route::post('payment/user/placeorder', 'Front\OrderController@postPaymentPlaceOrder')->name('user.postPaymentPlaceOrder');
	Route::post('payment/user/wallet/credit', 'Front\WalletController@postPaymentCreditWallet')->name('user.postPaymentCreditWallet');

	Route::get('user/login', [
		'as' => 'customer.login',
		'uses' => 'Front\CustomerAuthController@loginForm'
	]);
	Route::get('user/register', [
		'as' => 'customer.register',
		'uses' => 'Front\CustomerAuthController@registerForm'
	]);
	Route::get('user/forgotPassword', [
		'as' => 'customer.forgotPassword',
		'uses' => 'Front\ForgotPasswordController@getForgotPasswordForm'
	]);
	Route::get('user/resetPassword', [
		'as' => 'customer.resetPassword',
		'uses' => 'Front\CustomerAuthController@resetPasswordForm'
	]);
	Route::get('/autocomplete-search', 'Front\SearchController@postAutocompleteSearch')->name('autocomplete');
	Route::get('/search-all/{keyword}', 'Front\SearchController@showSearchResults')->name('showSearchResults');
	Route::get('/', 'Front\UserhomeController@index')->name('userHome');
	Route::get('/order-tracking', 'Front\UserhomeController@orderTracking')->name('order.tracking');
	Route::get('/order-tracking/{orderId}', 'Front\UserhomeController@orderTracking')->name('order.tracking.detail');
	Route::get('/courier', function () {
		return view('frontend.courier');
	})->name('courier');
	Route::get('/recovery', function () {
		return view('frontend.recovery');
	})->name('recovery');

	Route::get('/send-email', 'Front\CustomerAuthController@sendEmail');

	Route::get('/homeTemplateOne', 'Front\UserhomeController@indexTemplateOne')->name('indexTemplateOne');
	//Route::get('page/driver-registration', 'Front\UserhomeController@driverSignup')->name('page/driver-registration');
	Route::post('page/driverSignup', 'Front\OrderController@driverSignup')->name('page.driverSignup');
	Route::get('driver-documents', 'Front\UserhomeController@driverDocuments')->name('driver-documents');
	Route::get('page/{slug}/{lang?}', 'Front\UserhomeController@getExtraPage')->name('extrapage');

	Route::post('/homePageData', 'Front\UserhomeController@postHomePageData')->name('homePageData');
	Route::post('/theme', 'Front\UserhomeController@setTheme')->name('config.update');
	Route::get('/getConfig', 'Front\UserhomeController@getConfig')->name('config.get');
	Route::post('getClientPreferences', 'Front\UserhomeController@getClientPreferences')->name('getClientPreferences');
	Route::post('validateEmail', 'Front\CustomerAuthController@validateEmail')->name('validateEmail');
	Route::post('user/loginData', 'Front\CustomerAuthController@login')->name('customer.loginData');
	Route::post('user/register', 'Front\CustomerAuthController@register')->name('customer.register');
	Route::post('user/loginViaUsername', 'Front\CustomerAuthController@loginViaUsername')->name('customer.loginViaUsername');
	Route::post('user/verifyPhoneLoginOtp', 'Front\CustomerAuthController@verifyPhoneLoginOtp')->name('customer.verifyPhoneLoginOtp');
	Route::post('vendor/register', 'Front\CustomerAuthController@postVendorregister')->name('vendor.register');
	Route::post('user/forgotPassword', 'Front\ForgotPasswordController@postForgotPassword')->name('customer.forgotPass');
	Route::post('user/resetPassword', 'Front\CustomerAuthController@resetPassword')->name('customer.resetPass');
	Route::get('reset-password/{token}', 'Front\ForgotPasswordController@getResetPasswordForm');
	Route::post('reset-password', 'Front\ForgotPasswordController@postUpdateResetPassword')->name('reset-password');
	Route::get('reset-password-success', 'Front\ForgotPasswordController@getResetPasswordSuccess');
	Route::get('reset-vendor-password/{token}', 'Front\ForgotPasswordController@getResetVendorPasswordForm');
	Route::post('reset-vendor-password', 'Front\ForgotPasswordController@postUpdateVendorResetPassword')->name('reset-vendor-password');

	Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');
	Route::post('paginateValue', 'Front\UserhomeController@changePaginate')->name('changePaginate');
	Route::get('/product/{id?}', 'Front\ProductController@index')->name('productDetail');
	Route::post('/product/variant/{id}', 'Front\ProductController@getVariantData')->name('productVariant');
	Route::post('add/product/cart', 'Front\CartController@postAddToCart')->name('addToCart');
	Route::post('add/product/cart-addons', 'Front\CartController@postAddToCartAddons')->name('addToCartAddons');
	Route::post('add/wishlist/cart', 'Front\CartController@addWishlistToCart')->name('addWishlistToCart');
	Route::post('add/vendorTable/cart', 'Front\CartController@addVendorTableToCart')->name('addVendorTableToCart');
	Route::post('add/product/prescription', 'Front\CartController@uploadPrescription')->name('cart.uploadPrescription');
	Route::post('cart/schedule/update', 'Front\CartController@updateSchedule')->name('cart.updateSchedule');
	Route::post('cart/product-schedule/update', 'Front\CartController@updateProductSchedule')->name('cart.updateProductSchedule');
	Route::get('cartProducts', 'Front\CartController@getCartData')->name('getCartProducts');
	Route::get('cartDetails', 'Front\CartController@getCartProducts')->name('cartDetails');
	Route::post('cartDelete', 'Front\CartController@emptyCartData')->name('emptyCartData');
	Route::post('/product/updateCartQuantity', 'Front\CartController@updateQuantity')->name('updateQuantity');
	Route::post('/product/deletecartproduct', 'Front\CartController@deleteCartProduct')->name('deleteCartProduct');
	Route::get('userAddress', 'Front\UserController@getUserAddress')->name('getUserAddress');
	Route::get('category/{slug?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
	Route::get('category/{slug1}/{slug2}', 'Front\CategoryController@categoryVendorProducts')->name('categoryVendorProducts');
	Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');
	Route::get('vendor/all', 'Front\VendorController@viewAll')->name('vendor.all');
	Route::get('vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');
	Route::get('vendor/{slug1}/{slug2}', 'Front\VendorController@vendorCategoryProducts')->name('vendorCategoryProducts');
	Route::post('vendor/filters/{id}', 'Front\VendorController@vendorFilters')->name('vendorProductFilters');
	Route::post('vendor/products/searchResults', 'Front\VendorController@vendorProductsSearchResults')->name('vendorProductsSearchResults');
	Route::post('vendor/product/addons', 'Front\VendorController@vendorProductAddons')->name('vendorProductAddons');
	Route::get('brand/{id?}', 'Front\BrandController@brandProducts')->name('brandDetail');
	Route::post('brand/filters/{id}', 'Front\BrandController@brandFilters')->name('brandProductFilters');
	Route::get('celebrity/{slug?}', 'Front\CelebrityController@celebrityProducts')->name('celebrityProducts');
	Route::get('auth/{driver}', 'Front\FacebookController@redirectToSocial');
	Route::get('auth/callback/{driver}', 'Front\FacebookController@handleSocialCallback');
	Route::get('UserCheck', 'Front\UserController@checkUserLogin')->name('checkUserLogin');
	Route::get('stripe/showForm/{token}', 'Front\PaymentController@showFormApp')->name('stripe.formApp');
	Route::post('stripe/make', 'Front\PaymentController@makePayment')->name('stripe.makePayment');
	Route::post('inquiryMode/store', 'Front\ProductInquiryController@store')->name('inquiryMode.store');
	Route::get('viewcart', 'Front\CartController@showCart')->name('showCart');
	Route::post('/getTimeSlotsForOndemand', 'Front\CategoryController@getTimeSlotsForOndemand')->name('getTimeSlotsForOndemand');
	Route::post('checkIsolateSingleVendor', 'Front\CartController@checkIsolateSingleVendor')->name('checkIsolateSingleVendor');
	Route::get('viewcart', 'Front\CartController@showCart')->name('showCart');
	Route::post('/getTimeSlotsForOndemand', 'Front\CategoryController@getTimeSlotsForOndemand')->name('getTimeSlotsForOndemand');
	Route::post('checkIsolateSingleVendor', 'Front\CartController@checkIsolateSingleVendor')->name('checkIsolateSingleVendor');
	Route::get('firebase-messaging-sw.js', 'Front\FirebaseController@service_worker');
	Route::get('/redirect-store', 'Front\UserhomeController@redirectStore')->name('redirect.store');
});
Route::group(['middleware' => ['domain', 'webAuth']], function () {
	Route::get('user/orders', 'Front\OrderController@orders')->name('user.orders');
	Route::post('user/orders/tip-after-order', 'Front\OrderController@tipAfterOrder')->name('user.tip_after_order');
	Route::post('user/store', 'Front\AddressController@store')->name('address.store');
	Route::get('user/addAddress', 'Front\AddressController@add')->name('addNewAddress');
	Route::get('user/address/{id}', 'Front\AddressController@address')->name('user.address');
	Route::get('user/checkout', 'Front\UserController@checkout')->name('user.checkout');
	Route::get('user/profile', 'Front\ProfileController@profile')->name('user.profile');
	Route::get('user/logout', 'Front\CustomerAuthController@logout')->name('user.logout');
	Route::get('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::get('user/editAddress/{id}', 'Front\AddressController@edit')->name('editAddress');
	Route::post('user/update/{id?}', 'Front\AddressController@update')->name('address.update');
	Route::get('user/wishlists', 'Front\WishlistController@wishlists')->name('user.wishlists');
	Route::post('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::post('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInformation');
	Route::post('user/placeorder', 'Front\OrderController@placeOrder')->name('user.placeorder');
	Route::get('user/newsLetter', 'Front\ProfileController@newsLetter')->name('user.newsLetter');
	Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
	Route::post('wishlist/update', 'Front\WishlistController@updateWishlist')->name('addWishlist');
	Route::post('verifTokenProcess', 'Front\UserController@verifyToken')->name('user.verifyToken');
	Route::get('user/addressBook', 'Front\AddressController@index')->name('user.addressBook');
	Route::get('user/wallet', 'Front\WalletController@index')->name('user.wallet');
	Route::post('user/wallet/credit', 'Front\WalletController@creditWallet')->name('user.creditWallet');
	Route::get('user/loyalty', 'Front\LoyaltyController@index')->name('user.loyalty');
	Route::post('wallet/payment/option/list', 'Front\WalletController@paymentOptions')->name('wallet.payment.option.list');
	Route::get('user/deleteAddress/{id}', 'Front\AddressController@delete')->name('deleteAddress');
	Route::post('user/updateAccount', 'Front\ProfileController@updateAccount')->name('user.updateAccount');
	Route::post('user/updateTimezone', 'Front\ProfileController@updateTimezone')->name('user.updateTimezone');
	Route::get('user/editAccount', 'Front\ProfileController@editAccount')->name('user.editAccount');
	Route::get('user/sendRefferal', 'Front\ProfileController@showRefferal')->name('user.sendRefferal');
	Route::get('wishlist/remove/{sku}', 'Front\WishlistController@removeWishlist')->name('removeWishlist');
	Route::get('user/changePassword', 'Front\ProfileController@changePassword')->name('user.changePassword');
	Route::post('user/placeorder/make', 'Front\OrderController@makePayment')->name('placeorder.makePayment');
	Route::post('user/sendRefferalCode', 'Front\ProfileController@sendRefferalCode')->name('user.sendEmail');
	Route::get('user/resetSuccess', 'Front\CustomerAuthController@resetSuccess')->name('customer.resetSuccess');
	Route::post('verify/promocode', 'Front\PromoCodeController@postVerifyPromoCode')->name('verify.promocode');

	Route::post('remove/promocode', 'Front\PromoCodeController@postRemovePromoCode')->name('remove.promocode');
	Route::get('order/success/{order_id}', 'Front\OrderController@getOrderSuccessPage')->name('order.success');
	Route::get('order/return/success', 'Front\OrderController@getOrderSuccessReturnPage')->name('order.return.success');
	Route::post('promocode/list', 'Front\PromoCodeController@postPromoCodeList')->name('verify.promocode.list');
	Route::post('promocode/validate_code', 'Front\PromoCodeController@validate_code')->name('verify.promocode.validate_code');
	Route::post('payment/option/list', 'Front\PaymentController@index')->name('payment.option.list');
	Route::get('user/setPrimaryAddress/{id}', 'Front\AddressController@setPrimaryAddress')->name('setPrimaryAddress');
	Route::post('user/submitPassword', 'Front\ProfileController@submitChangePassword')->name('user.submitChangePassword');
	Route::get('user/wallet/history', 'Front\WalletController@index')->name('user.walletHistory');
	Route::get('user/subscription/plans', 'Front\UserSubscriptionController@getSubscriptionPlans')->name('user.subscription.plans');
	Route::get('user/subscription/select/{slug}', 'Front\UserSubscriptionController@selectSubscriptionPlan')->name('user.subscription.plan.select');
	Route::post('user/subscription/purchase/{slug}', 'Front\UserSubscriptionController@purchaseSubscriptionPlan')->name('user.subscription.plan.purchase');
	Route::post('user/subscription/cancel/{slug}', 'Front\UserSubscriptionController@cancelSubscriptionPlan')->name('user.subscription.plan.cancel');
	Route::get('user/subscription/checkActive/{slug}', 'Front\UserSubscriptionController@checkActiveSubscription')->name('user.subscription.plan.checkActive');
	Route::post('user/save_fcm_token', 'Front\ProfileController@save_fcm')->name('user.save_fcm');
	// Rating & review
	Route::group(['prefix' => 'rating'], function () {
		Route::post('update-product-rating', 'Front\RatingController@updateProductRating')->name('update.order.rating');
		Route::get('get-product-rating', 'Front\RatingController@getProductRating')->name('get-product-rating-details');
	});
	// Return product
	Route::group(['prefix' => 'return-order'], function () {
		Route::get('get-order-data-in-model', 'Front\ReturnOrderController@getOrderDatainModel')->name('getOrderDatainModel');
		Route::get('get-return-products', 'Front\ReturnOrderController@getReturnProducts')->name('get-return-products');
		Route::post('update-product-return', 'Front\ReturnOrderController@updateProductReturn')->name('update.order.return');
	});
	// Return product
	Route::group(['prefix' => 'looking'], function () {
		Route::get('/', 'Front\BookingController@index')->name('bookingIndex');
		Route::get('details/{id?}', 'Front\BookingController@bookingDetails')->name('front.booking.details');
		Route::post('orderPlaceDetails/{id}', 'Front\BookingController@orderPlaceDetails')->name('front.booking.orderplacedetails');


		Route::post('create-order', 'Front\PickupDeliveryController@createOrder');
		Route::post('cart/updateQuantity', 'Front\CartController@updateQuantity');
		Route::post('promo-code/list', 'Front\PickupDeliveryController@postPromoCodeList');
		Route::post('promo-code/remove', 'Front\PickupDeliveryController@postRemovePromoCode');
		Route::post('product-detail/{id}', 'Front\PickupDeliveryController@postCabProductById');
		Route::post('get-list-of-vehicles-old/{id}', 'Front\PickupDeliveryController@getListOfVehicles');
		Route::post('vendor/list/{category_id}', 'Front\PickupDeliveryController@postVendorListByCategoryId')->name('pickup-delivery-route');
		Route::post('get-list-of-vehicles/{id}', 'Front\PickupDeliveryController@productsByVendorInPickupDelivery');
		Route::post('order-tracking-details', 'Front\PickupDeliveryController@getOrderTrackingDetails')->name('bookingIndex');
		Route::post('promo-code/verify', 'Front\PickupDeliveryController@postVerifyPromoCode')->name('verify.cab.booking.promo-code');
	});
	Route::post('upload-file', 'Front\RatingController@uploadFile')->name('uploadfile');
});
