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
     * get order limits .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function get_order_limits(Request $request){
        try {
            $order_limit_amount_data = BusinessSetting::where(['key' => 'order_limit_amount'])->first();
            $order_limit_amount = (isset($order_limit_amount_data) && !empty($order_limit_amount_data)) ? $order_limit_amount_data->value : '';

            $order_installment_amounts_data = BusinessSetting::whereIn('key', ['order_installment_amount_1', 'order_installment_amount_2', 'order_installment_amount_3'])->get();

            $order_installement_amounts = [];

            foreach ($order_installment_amounts_data as $amount_data) {
                
                $order_installement_amounts[][$amount_data->key] = (isset($amount_data->value) && !empty($amount_data->value)) ?  $amount_data->value: '';
            }

            return response()->json(['status' => 'success', 'code' => 200, 'data' => ['order_limit_amount' => $order_limit_amount, 'order_installement_amounts' => $order_installement_amounts]]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

     

}
