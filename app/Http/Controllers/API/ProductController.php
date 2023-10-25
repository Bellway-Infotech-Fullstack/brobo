<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Wishlist;
use App\Models\User;

class ProductController extends Controller
{
    //

     /**
     * It will add and remove item from wish list.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function manageItemInWishList(Request $request)
     {
         try {
             // Get requested data
             
             $customerId = $request->post('customer_id');
             $itemId = $request->input('item_id'); 
             $wishListId = $request->post('wishlist_id');
             // Define the validation rules
             $validationRules = [
                 'customer_id' => 'required',
                 'item_id' => 'required',
             ];
 
             // If it's an update (wishlist_id is provided), validate and update the existing address
             if ($wishListId) {
                 $validationRules['wishlist_id'] = 'required|exists:wishlists,id,user_id,' . $customerId;
             }
 
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'customer_id.required' => 'customer ID is required.',
                 'item_id.required' => 'Item ID is required.',
                 'wishlist_id.required' => 'Whishlist ID is required for updates.',
                 'wishlist_id.exists' => 'The provided wishlist ID does not exist for this customer.',
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
             if ($wishListId) {
                 // Update an existing 
                 $wishList = Wishlist::find($wishListId);
                 if ($wishList) {
                     $wishList->delete();
                     return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Item  is removed from wishlist']);
                 } else {
                     return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Wishlist id not found']);
                 }
             } else {
                 // Add item to wishlist
                 Wishlist::create([
                'item_id' => $itemId,
                'user_id' => $customerId,

                 ]);
                 return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Item is added to wishlist']);
             }
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }
}