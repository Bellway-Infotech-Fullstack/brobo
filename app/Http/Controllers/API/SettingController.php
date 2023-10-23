<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;

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
}
