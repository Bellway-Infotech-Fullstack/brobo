<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use DB;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
   


     public function index(Request $request)
     {
         try {            
        
             // get coupon data 

             $todayDate = date("Y-m-d");
             
             $couponData = Coupon::where(['status' => 1])->orderBy('created_at', 'desc')
             ->whereDate('start_date', '<=', $todayDate)
             ->whereDate('expire_date', '>=', $todayDate)
             ->first();            

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

     public function get_all_coupons(){

        $todayDate = date("Y-m-d");

        $couponData = Coupon::where(['status' => 1])
        ->whereDate('start_date', '<=', $todayDate)
        ->whereDate('expire_date', '>=', $todayDate)
        ->orderBy('created_at', 'desc')->get();   



                           
        if(count($couponData) > 0) {
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'data found','data' => $couponData], 200);
            
        } else {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
        }
     }

     public function getCouponDetail(Request $request)
     {
         try {            
        
             // get coupon data 

             $code = $request->code;

               // Define the validation rules
               $validationRules = [
                'code' => 'required',
            ]; 
        
            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules, [
                'code.required' => 'code is required.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            
             
             $couponData = Coupon::where(['status' => 1])->where('code', '<=', $code)->first();            

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
