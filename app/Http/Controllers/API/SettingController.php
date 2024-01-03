<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;
use App\Models\User;

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
            // Get customer_id from the request
            $customerId = $request->post('customer_id');           
            $isNotificationSettingOn = $request->post('is_notification_setting_on');  
            

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'is_notification_setting_on' => 'required',

            ], [
                'customer_id.required' => 'Please enter a customer id.',
                'is_notification_setting_on.required' => 'Please enter a starus.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            $userData   = User::find($customerId);
             
             if ($userData) {
                 // Update notification setting details
                 $userData->is_notification_setting_on = $isNotificationSettingOn; 
                 $userData->save();
             }          
            
             return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Setting updated successfully']);
            
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

            $order_time_slot_data = explode(",",$order_time_slot_data->value);

            $order_installment_percents = [];

            if(isset($order_installment_percents_data) && !empty($order_installment_percents_data)){
                foreach ($order_installment_percents_data as $amount_data) {
                    $order_installment_percents[] = (isset($amount_data->value) && !empty($amount_data->value)) ?  $amount_data->value: '';
                 }
            }

            // Convert time slots to 12-hour format with AM and PM
            $formatted_time_slots = [];
            if(isset($order_time_slot_data) && !empty($order_time_slot_data)){
                foreach ($order_time_slot_data as $time_slot) {               
                    $time_slot = explode("-",$time_slot);
                    $from_time_slot = $time_slot[0];
                    $to_time_slot = $time_slot[1];
                    $formatted_time_slots[] = date("h:i A", strtotime($from_time_slot)) . " - " . date("h:i A", strtotime($to_time_slot));
                }
            }

            return response()->json(['status' => 'success', 'code' => 200, 'data' => ['mininum_order_amount' => $mininum_order_amount, 'order_installment_percents' => $order_installment_percents,'order_time_slot_data' => $formatted_time_slots]]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

     

}
