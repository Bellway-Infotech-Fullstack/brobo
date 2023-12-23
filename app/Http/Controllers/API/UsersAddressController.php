<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UsersAddress;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersAddressController extends Controller
{
    //


    /**
     * Add / update delivery address by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function manageAddress(Request $request)
     {
         try {
             // Get requested data
             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $customerId = (isset($user) && !empty($user)) ? $user->id : '';
             $addressId = $request->input('address_id'); // Assume you pass an 'address_id' for updating
     
             // Define the validation rules
             $validationRules = [
                 'house_name' => 'required',
                 'zip_code' => 'required',
                 'floor_number' => 'required',
             ];
     
             // If it's an update (address_id is provided), validate and update the existing address
             if ($addressId) {
                 $validationRules['address_id'] = 'required|exists:users_addresses,id,customer_id,' . $customerId;
             }
     
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'house_name.required' => 'Please enter a house name.',
                 'zip_code.required' => 'Please enter a zip code.',
                 'floor_number.required' => 'Please enter a floor number.',
                 'address_id.required' => 'Address ID is required for updates.',
                 'address_id.exists' => 'The provided address ID does not exist for this customer.',
             ]);
     
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
     
             // Create or update the address based on whether 'address_id' is provided
             if ($addressId) {
                 // Update an existing address
                 $address = UsersAddress::where('customer_id', $customerId)->find($addressId);
     
                 if ($address) {
                     $address->update([
                         'house_name' => $request->house_name,
                         'floor_number' => $request->floor_number,
                         'landmark' => $request->landmark,
                         'area_name' => $request->area_name,
                         'zip_code' => $request->zip_code,
                         'customer_id' => $customerId
                     ]);
     
                     return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address updated successfully']);
                 } else {
                     return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Address not found']);
                 }
             } else {
                 // Add a new address
                 $addressRecord = UsersAddress::where('customer_id', $customerId);
     
                 if ($addressRecord->exists()) {
                     $addressRecord->update([
                         'house_name' => $request->house_name,
                         'floor_number' => $request->floor_number,
                         'landmark' => $request->landmark,
                         'area_name' => $request->area_name,
                         'zip_code' => $request->zip_code,
                     ]);
     
                     return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address updated successfully']);
                 } else {
                     // Add a new address
                     UsersAddress::create([
                         'house_name' => $request->house_name,
                         'floor_number' => $request->floor_number,
                         'landmark' => $request->landmark,
                         'zip_code' => $request->zip_code,
                         'area_name' => $request->area_name,
                         'customer_id' => $customerId
                     ]);
     
                     return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address added successfully']);
                 }
             }
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }
     
     /**
     * get delivery address by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

    public function getAddress(Request $request)
    {
        try {
            // Get customer_id and verification_code from the request
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = $user->id;
           

            // Find the user's address by user_id
            $addressData = UsersAddress::where('customer_id', $customerId)->first();

            if ($addressData) {
                return response()->json(['status' => 'success', 'code' => 200,'data' => [
                    'house_name' => $addressData->house_name,
                    'floor_number' => (int) $addressData->floor_number,
                    'landmark' => $addressData->landmark,
                    'zip_code' => $addressData->zip_code,
                    'area_name' => $addressData->area_name,
                    'address_id' => $addressData->id
                ],
            ]);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User has no address'], 404);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }


}
