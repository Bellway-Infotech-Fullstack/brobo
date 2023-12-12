<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    //

    /**
     * get all coupons.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         try {            
        
             // get coupon data 
             
             $couponData = Coupon::where(['status' => 1])->orderBy('created_at', 'desc')->first();            

             if (isset($couponData) && !empty($couponData)) {
                 $data = array(
                    "status"=> "success",
                    "code"=> 200,
                    "data" => array(
                     'id' => $couponData->id,
                     'title' => $couponData->title,
                     'code' => $couponData->code,
                     'start_date' => $couponData->start_date,
                     'expire_date' => $couponData->expire_date,
                     'min_purchase' => $couponData->min_purchase,
                     'max_discount' => $couponData->max_discount,
                     'discount' => $couponData->discount,
                     'discount_type' => $couponData->discount_type,
                     'coupon_type' => $couponData->coupon_type,
                     'limit' => $couponData->limit,
                     'background_image' => (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/' . $couponData->background_image) : asset('storage/app/public/coupon_background_image/' . $couponData->background_image),
                     'status' => $couponData->status,
                     'created_at' => $couponData->created_at,
                     'updated_at' => $couponData->updated_at
                    )                     
                 );
             
                 // Ensure that $data is not already encoded before calling json_encode
                 $jsonData = json_encode($data);
                 
               // Use str_replace to remove backslashes
                $withoutBackslashes = str_replace('\\', '', $jsonData);

                return $withoutBackslashes;
             } else {
                 return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }
}
