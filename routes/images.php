<?php

Route::group(['prefix' => '/showImage/small'], function () {

	Route::get('/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url($folder . '/' . $img);
		return \Image::make($image)->fit(90, 50)->response('jpg');
	});

	Route::get('/category/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url('category/' . $folder . '/' . $img);
		return \Image::make($image)->fit(90, 50)->response('jpg');
	});
});

Route::group(['prefix' => '/showImage/medium'], function () {

	Route::get('/banner/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url($folder . '/' . $img);
		return \Image::make($image)->fit(460, 120)->response('jpg');
	});

	Route::get('/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url($folder . '/' . $img);
		return \Image::make($image)->fit(360, 240)->response('jpg');
	});

	Route::get('/category/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url('category/' . $folder . '/' . $img);
		return \Image::make($image)->fit(360, 240)->response('jpg');
	});
	
});

Route::group(['prefix' => '/showImage/large'], function () {

	Route::get('/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url($folder . '/' . $img);
		return \Image::make($image)->response('jpg');
	});

	Route::get('/category/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url('category/' . $folder . '/' . $img);
		return \Image::make($image)->response('jpg');
	});
	
});
