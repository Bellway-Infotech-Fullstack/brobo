<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Order;
use DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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

     public function getAllCoupons(){

        $todayDate = date("Y-m-d");

        $couponData = Coupon::where(['status' => 1])
        ->whereDate('start_date', '<=', $todayDate)
        ->whereDate('expire_date', '>=', $todayDate)
        ->orderBy('created_at', 'desc')->get();  
        if(count($couponData) > 0) {         
            $allData = array();    
            foreach($couponData as $key => $value){
                $data = array(
                    
                        'id' => $value->id,
                        'title' => $value->title,
                        'code' => $value->code,
                        'start_date' => $value->start_date,
                        'expire_date' => $value->expire_date,
                        'min_purchase' => $value->min_purchase,
                        'max_discount' => $value->max_discount,
                        'discount' => $value->discount,
                        'discount_type' => $value->discount_type,
                        'coupon_type' => $value->coupon_type,
                        'limit' => $value->limit,
                        'background_image' => (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/' . $value->background_image) : asset('storage/app/public/coupon_background_image/' . $value->background_image),
                        'status' => $value->status,
                        'created_at' => $value->created_at,
                        'updated_at' => $value->updated_at
                                        
                    );

                    array_push($allData,$data);
                
            }
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found','data' => $allData], 200);
            
        } else {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
        }
     }

     public function getCouponDetail(Request $request)
     {
         try {            
        
             // get coupon data 

             $code = $request->code;
             $todayDate = date("Y-m-d");

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

            
             
            $couponData = Coupon::where(['status' => 1])->where('code', $code)
            
            ->whereDate('start_date', '<=', $todayDate)
            ->whereDate('expire_date', '>=', $todayDate)->first();  

            if (isset($couponData) && !empty($couponData)) {
                $couponLimit = $couponData->limit;

                $couponId = $couponData->id;

                $token = JWTAuth::getToken();
                $user = JWTAuth::toUser($token);
                $customerId = (isset($user) && !empty($user)) ? $user->id : '';
    
                $userOrderedCouponIdsCount = Order::where(array('user_id' => $customerId , 'coupon_id' => $couponId))->count();

                if($userOrderedCouponIdsCount >= $couponLimit){
                    return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You can not apply this coupon', 'data' => null], 400);
                }
          
    
                
    
                
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
