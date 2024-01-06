<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bank;
use Tymon\JWTAuth\Facades\JWTAuth;


class BankController extends Controller
{
    
    /**
     * Add / update bank details by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function manageBankDetails(Request $request)
    {
        try {
            // Get requested data
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';

            // Define the validation rules
            $validationRules = [
                'account_number' => 'required|numeric|digits_between:9,18', // Adjust the range based on your requirements
                'ifsc_code' => 'required|regex:/^[A-Za-z]{4}\d{7}$/',
                'bank_name' => 'required',
            ];
    
            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules, [
                'account_number.required' => 'Please enter the account number.',
                'account_number.numeric' => 'Account number must be numeric.',
                'account_number.digits_between' => 'Account number must be between 9 and 18 digits.', // Adjust the range based on your requirements
                'ifsc_code.required' => 'Please enter the IFSC code.',
                'ifsc_code.regex' => 'Invalid IFSC code format.',
                'bank_name.required' => 'Please enter the bank name.',
            ]);

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Update or create the bank details
            $bankDetails = Bank::where('customer_id', $customerId)->first();

            if ($bankDetails) {
                // Update existing bank details
                $bankDetails->update([
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                ]);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Bank details updated successfully']);
            } else {
                // Create new bank details
                Bank::create([
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                    'customer_id' => $customerId
                ]);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Bank details added successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get bank details by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBankDetails(Request $request)
    {
        try {
            // Get customer_id from the request
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = $user->id;

            // Find the user's bank details by user_id
            $bankDetails = Bank::where('customer_id', $customerId)->first();

            if ($bankDetails) {
                return response()->json(['status' => 'success', 'code' => 200, 'data' => [
                    'account_number' => $bankDetails->account_number,
                    'ifsc_code' => $bankDetails->ifsc_code,
                    'bank_name' => $bankDetails->bank_name,
                ],
                ]);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User has no bank details'], 404);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}
