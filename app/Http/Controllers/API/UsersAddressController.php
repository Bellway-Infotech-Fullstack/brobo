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
     * Add customer details by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateAddress(Request $request)
    {
        // Try to update customer details
        try {
            // Get requested data
            $customerId = $request->post('customer_id');
            $houseName = $request->post('house_name');
            $floorNumber = $request->post('floor_number');
            $landmark = $request->post('landmark');
            $zipCode = $request->post('zip_code');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'house_name' => 'required',
                'zip_code' => 'required',
            ]);

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'errors' => $validation->errors()->all()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                // Update user details
    
                UsersAddress::create($request->toArray());


                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Address added successfully']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }
}
