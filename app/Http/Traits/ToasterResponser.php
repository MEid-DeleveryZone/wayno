<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ToasterResponser{

    protected function successToaster($title, $message = null)
	{
		return [
			'type'=> 'success',
			'title' => $title,
			'body' => $message, 
			'color' => '#5ba035'
		];
	}

	protected function errorToaster($title, $message = null)
	{
		return [
			'type'=>'error',
			'title' => $title,
			'body' => $message, 
			'color' => '#bf441d'
		];
	}

	protected function warningToaster($title, $message = null)
	{
		return [
			'type'=>'warning',
			'title' => $title,
			'body' => $message, 
			'color' => '#da8609'
		];
	}

}