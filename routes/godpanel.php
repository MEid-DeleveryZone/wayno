<?php
Route::group(['prefix' => '/godpanel'], function () {
	Route::get('login', function(){return view('godpanel/login');});
	Route::post('login','Godpanel\LoginController@Login')->name('god.login');
	Route::middleware(['middleware' => 'auth:admin'])->group(function () {

		Route::resource('client','Godpanel\ClientController');
		Route::resource('map','Godpanel\MapProviderController');
		Route::resource('sms','Godpanel\SmsProviderController');
		Route::resource('language','Godpanel\LanguageController');
		Route::resource('currency','Godpanel\CurrencyController');
		Route::post('delete/client/{id}', 'Godpanel\ClientController@remove');
		Route::get('map/destroy/{id}', 'Godpanel\MapProviderController@destroy');
		Route::get('sms/destroy/{id}', 'Godpanel\SmsProviderController@destroy');
		Route::post('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
		Route::get('dashboard','Godpanel\DashBoardController@index')->name('god.dashboard');
		
		Route::post('migrateDefaultData/{id}', 'Godpanel\ClientController@migrateDefaultData')->name('client.migrateDefaultData');
		Route::post('singleVendorSetting/{id}', 'Godpanel\ClientController@singleVendorSetting')->name('client.update_single_vendor');
		
	});
});