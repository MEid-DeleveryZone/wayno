<?php

namespace App\Http\Controllers\Api\v1;
use DB;
use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class RevenueController extends Controller
{
    use ApiResponser;
	public function getRevenueDetails(Request $request){
		try {
			$order_details = OrderVendor::select(DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
				->groupby('month')
				->get();
			return $this->successResponse($order_details, '', 201);
		} catch (Exception $e) {
			
		}
	}
}
