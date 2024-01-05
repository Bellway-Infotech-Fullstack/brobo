<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Notification;
use App\Models\Coupon;
use Carbon\Carbon;


class NotificationController extends Controller
{
    //

     /**
     * It will get all notifications .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request){
        try {
            
             
             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $customerId = (isset($user) && !empty($user)) ? $user->id : '';

            // Get requested data

             $page = $request->get('page');
             $orderBy =  'desc';
             $orderColumn = 'created_at';
             $perPage = 10; // Number of items to load per page


            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
            
             $data = Notification::where('to_user_id',$customerId)
                ->orderBy($orderColumn, $orderBy)
                ->paginate($perPage, ['*'], 'page', $page);

            $data = $data->map(function ($notification) {

                if($notification->coupon_id == ''){
                $notification->description = $notification->description;
                } else {

                $couponData = Coupon::find($notification->coupon_id);                            
                $couponDiscountType = $couponData->discount_type;
                $couponCode = $couponData->code;

                if($couponDiscountType == "discount"){
                    $couponDiscount = $couponData->discount. " %";
                } else {
                    $couponDiscount = "Rs. ".$couponData->discount;
                }

                $description = "Get upto $couponDiscount off using code $couponCode";
                }

                
                $notification->description = $description;
                 // Calculate time ago
                $notification->time_ago = Carbon::parse($notification->created_at)->diffForHumans();

                return $notification;
             });

            if(count($data) > 0){

                // read all notifications 

                Notification::where('to_user_id',$customerId)->update(['is_read'=>'1']);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $data]);
            }  else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No Data found','data' => $data]);
            }
        } catch (\Exception $e) {
           
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
    }


    public function getUnreadNotificationsCount(Request $request){
        try {            
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
            $count = Notification::where(array('to_user_id'=> $customerId,'is_read' =>'0'))->count();
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $count]);

        } catch (\Exception $e) {
           
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }


}


    
  

