<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UsersAddress;

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
            
            $customerId = $request->post('customer_id');
            $addressId = $request->input('address_id'); // Assume you pass an 'address_id' for updating
            // Define the validation rules
            $validationRules = [
                'customer_id' => 'required',
                'house_name' => 'required',
                'zip_code' => 'required',
            ];

            // If it's an update (address_id is provided), validate and update the existing address
            if ($addressId) {
                $validationRules['address_id'] = 'required|exists:users_addresses,id,customer_id,' . $customerId;
            }

            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules, [
                'customer_id.required' => 'Please enter a customer ID.',
                'house_name.required' => 'Please enter a house name.',
                'zip_code.required' => 'Please enter a zip code.',
                'address_id.required' => 'Address ID is required for updates.',
                'address_id.exists' => 'The provided address ID does not exist for this customer.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if (!$userData) {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User does not exist']);
            }

            // Create or update the address based on whether 'address_id' is provided
            if ($addressId) {
                // Update an existing address
                $address = UsersAddress::find($addressId);
                if ($address) {
                    $address->update($request->all());
                    return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address updated successfully']);
                } else {
                    return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Address not found']);
                }
            } else {
                // Add a new address
                UsersAddress::create($request->all());
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address added successfully']);
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
            $customerId       = $request->customer_id;
            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
            ], [
                'customer_id.required' => 'Please enter a customer id.',
            ]);

            // Validate the input data
            $validation = Validator::make($request->all(), $validation);

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user's address by user_id
            $addressData = UserAddress::where('user_id', $customerId)->first();

            if ($addressData) {
                return response()->json(['status' => 'success', 'code' => 200,'data' => [
                    'houseName' => $addressData->house_name,
                    'floorNumber' => $addressData->floor_number,
                    'landmark' => $addressData->landmark,
                    'zipCode' => $addressData->zip_code,
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
