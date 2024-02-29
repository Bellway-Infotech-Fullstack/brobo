<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class SettingController extends Controller
{
    //

     /**
     * get setting data by key.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         try {
             // Get key from the request
             $key = $request->key;    
             
             // Validate the input data
             $validation = Validator::make($request->all(), [
                'key' => 'required',
            ], [
                'key.required' => 'Please enter a key.',
            ]);


            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            } 
             
        
             // get business setting data 
             
             $businessSettingData   = BusinessSetting::where(['key' => $key])->first();
             
             if ($businessSettingData) {
                 return response()->json(['status' => 'success', 'code' => 200,'data' => $businessSettingData,
             ]);
             } else {
                 return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }


      /**
     * Update status of notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateNotificationSetting(Request $request)
    {
        // Try to update customer details
        try {
            // Get customer_id from the token
            
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = (isset($user) && !empty($user)) ? $user->id : '';


            $isNotificationSettingOn = $request->post('is_notification_setting_on');  
            

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'is_notification_setting_on' => 'required',

            ], [
                'is_notification_setting_on.required' => 'Please enter a starus.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            $userData   = User::find($userId);
             
             if ($userData) {
                 // Update notification setting details
                if($isNotificationSettingOn == 'no'){
                  //  date_default_timezone_set("Asia/Kolkata");
                    $userData->notification_off_time = date("Y-m-d H:i:s"); 
                    $message = "Notification disabled successfully";
                } else {
                    $userData->notification_off_time = NULL; 
                    $message = "Notification enabled successfully";
                }


                 $userData->is_notification_setting_on = $isNotificationSettingOn; 
                 
                 $userData->save();
             }          
            
             return response()->json(['status' => 'success', 'code' => 200, 'message' =>  $message ]);
            
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }


    /**
     * get order settings .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

    public function getOrderSettings(Request $request){
        try {
            $mininum_order_amount_data = BusinessSetting::where(['key' => 'mininum_order_amount'])->first();
            $mininum_order_amount = (isset($mininum_order_amount_data) && !empty($mininum_order_amount_data)) ? $mininum_order_amount_data->value : '';

            $order_installment_percents_data = BusinessSetting::whereIn('key', ['order_installment_percent_1', 'order_installment_percent_2', 'order_installment_percent_3'])->get();

            $order_time_slot_data = BusinessSetting::where('key','order_time_slots')->first();
            
            

            

            $order_installment_percents = [];

            if(isset($order_installment_percents_data) && !empty($order_installment_percents_data)){
                foreach ($order_installment_percents_data as $amount_data) {
                    $order_installment_percents[] = (isset($amount_data->value) && !empty($amount_data->value)) ?  $amount_data->value: '';
                 }
            }

            // Convert time slots to 12-hour format with AM and PM
            $formatted_time_slots = [];
            if(isset($order_time_slot_data) && !empty($order_time_slot_data)){
                $order_time_slot_data = explode(",",$order_time_slot_data->value);
                foreach ($order_time_slot_data as $time_slot) {               
                    $time_slot = explode("-",$time_slot);
                    $from_time_slot = $time_slot[0];
                    $to_time_slot = $time_slot[1];
                    $is_time_slot_enabled = $time_slot[2];
                    if($is_time_slot_enabled == 'yes'){
                        $formatted_time_slots[] = date("h:i A", strtotime($from_time_slot)) . " to " . date("h:i A", strtotime($to_time_slot));
                    }
                   
                }
            }

            $slabs = [];

            $deliveryChargeSlabData  = BusinessSetting::where('key','delivery_charge_slabs')->first();
            $deliveryChargeSlabData = (isset($deliveryChargeSlabData)) ? $deliveryChargeSlabData->value : ''; 
            if(isset($deliveryChargeSlabData) && !empty($deliveryChargeSlabData)){
                $deliveryChargeSlabData = explode(",",$deliveryChargeSlabData);
                foreach ($deliveryChargeSlabData as $slab) {
                    $slab = explode("-",$slab);
                    $slab_data = array('delivery_charge' => $slab[0],'min_amount' => $slab[1],'max_amount' => $slab[2]);
                    array_push($slabs,$slab_data);
                }
            }


            $logo= BusinessSetting::where('key','logo')->first();

             $logo = $logo->value??'';
             
             $gst_percent_data = BusinessSetting::where('key','gst_percent')->first();
            $gst_percent = $gst_percent_data->value ?? '';
        
            $logoPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $logo) : asset('storage/app/public/business/' . $logo);     

             $whatsapp_number_data = BusinessSetting::where('key','whatsapp_number')->first();
            $whatsapp_number = $whatsapp_number_data->value ?? '';

            return response()->json(['status' => 'success', 'code' => 200, 'data' => ['mininum_order_amount' => $mininum_order_amount, 'order_installment_percents' => $order_installment_percents,'order_time_slot_data' => $formatted_time_slots,'delivery_charge_slabs' => $slabs,'logo' => $logoPath,'gst_percent' => $gst_percent,'whatsapp_number' => $whatsapp_number]]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function getPaymentKeys(Request $request){
        try {

            $payment_gateway_name = $request->get('payment_gateway_name');
            
            $payment_keys_data = BusinessSetting::where(['key' => $payment_gateway_name])->first();
            $payment_keys_data = (isset($payment_keys_data) && !empty($payment_keys_data)) ? json_decode($payment_keys_data->value,true) : '';
            $data = null;

            if($payment_gateway_name == 'razor_pay'){
                $data = array('key' => $payment_keys_data['razor_key'],'secret' => $payment_keys_data['razor_secret']);

            }

            if($payment_gateway_name == 'stripe'){
                $data = array('key' => $payment_keys_data['api_key'],'secret' => $payment_keys_data['published_key']);

            }

            if($payment_gateway_name == 'paypal'){
                $data = array('key' => $payment_keys_data['paypal_client_id'],'secret' => $payment_keys_data['paypal_secret']);

            }
            if($data == null){
                return response()->json(['status' => 'error', 'code' => 500, 'data' =>  $data ]);
            }

            return response()->json(['status' => 'success', 'code' => 200, 'data' =>  $data ]);
   


         } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }

    }

     

}


