<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;


class ProductController extends Controller
{
    //

     /**
     * It will get items .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function getProductList(Request $request)
     {
         try {
             // Get requested data
             
             $cateogyId = $request->post('category_id');
             $page = $request->post('page');
             $orderBy = $request->post('order_by');
             $orderColumn = $request->post('order_column');
             $isDefaultSort = $request->post('is_default_sort');  
             $isHideOutOfStockItem = $request->post('is_hide_out_of_stock_items');   
             $perPage = 10; // Number of items to load per page
            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
                 'category_id' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
                 'category_id.required' => 'category ID is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
 
           
            // Query to retrieve items


            if($isDefaultSort == '1'){
                 $orderBy = 'desc';
                 $orderColumn = 'created_at';   
            }
            $stockCondition = '';
            if($isHideOutOfStockItem == '1'){
                $stockCondition = 'total_stock=0';
            }
            $items =  Product::whereHas('category', function ($query) use ($cateogyId) {
                            $query->where('parent_id', $cateogyId);
                        })->where($stockCondition)
                        ->orderBy($orderColumn, $orderBy)
                        ->paginate($perPage, ['*'], 'page', $page);

           
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $items]);
             
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }





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


     /**
     * It will get wishlist items of login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function getItemInWishList(Request $request)
     {
         try {
             // Get requested data
             
             $customerId = $request->post('customer_id');
             $page = $request->post('page');
             $perPage = 10; // Number of items to load per page
             // Define the validation rules
             $validationRules = [
                 'customer_id' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'customer_id.required' => 'customer ID is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
 
 
             // Retrieve wishlist items with their associated products for a specific user.
            // Query to retrieve wishlist items with associated products for the specific user
            $wishlistItems = Wishlist::where('user_id', $customerId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

            // Loop through the wishlist items and access the associated product details.

            $productImages = [];
            if(isset($wishlistItems) && !empty($wishlistItems)){
                foreach ($wishlistItems as $wishlistItem) {
                    $product = $wishlistItem->product;
                    $productImage = $product->image;
                    array_push($productImages,$productImage);
                }
            }
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $productImages]);
             
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }


     /**
     * It will get items of recommendation list .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function getProductRecommendationList(Request $request)
     {
         try {
             // Get requested data
             
             $cateogyId = $request->post('category_id');
             $productId = $request->post('product_id');

             $page = $request->post('page');
  
             $perPage = 10; // Number of items to load per page
            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
                 'category_id' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
                 'category_id.required' => 'category ID is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
 
           
            // Query to retrieve items         
           
            $items =  Product::whereHas('category', function ($query) use ($cateogyId) {
                            $query->where('parent_id', $cateogyId);
                        })->where('id','!=',$productId)
                        ->paginate($perPage, ['*'], 'page', $page);

           
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $items]);
             
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }
}
