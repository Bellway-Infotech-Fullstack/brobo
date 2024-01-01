<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;

class CartController extends Controller
{
    //

     /**
     * Add item in cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function addItemInCart(Request $request){
        try {
            // Get requested data
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
            $itemId = $request->input('item_id');
            $quantity = $request->input('quantity');
            
            

            // Define the validation rules
            $validationRules = [
                'item_id' => 'required',
                'quantity' => 'required',
            ];

            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules, [
                'item_id.required' => 'Item id is required.',
                'quantity.required' => 'Quantity is required.',
            ]);

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }
            
              // Query to retrieve product items with associated colored images
            $itemDetail = Product::where('id', $itemId)
                ->select('*')
                ->get();  

             // Check if the product item exists
             if (count($itemDetail) == 0) {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Product not found.']);
            }

            // Check if the cart item already exists for the customer and item
            $existingCartItem = Cart::where('customer_id', $customerId)
                ->where('item_id', $itemId)
                ->first();

            if ($existingCartItem) {
                // If the cart item already exists, update the quantity
                $existingCartItem->quantity = $quantity;
                $existingCartItem->save();

                return response()->json(['status' => 'success','message' => 'Quantity updated in cart successfully', 'code' => 200, 'data' => $existingCartItem]);
            }

            // If the cart item doesn't exist, create a new one
            $requestData = [
                'item_id' => $itemId,
                'quantity' => $quantity,
                'customer_id' => $customerId,
            ];

            $cartItem = Cart::create($requestData);

            return response()->json(['status' => 'success','message' => 'Item added to cart successfully', 'code' => 201, 'data' => $cartItem]);

        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCartItems(){
        try {
            // Retrieve cart items for the authenticated user
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
          
            $cartItems = Cart::where('customer_id', $customerId)->get();

            // Additional information related to the cart items
            $formattedCartItems = $cartItems->map(function ($cartItem) use($customerId) {
                // Assuming there's a relationship between Cart and Product models
                $product = $cartItem->product; // Adjust this based on your actual relationship

                // Modify the item's image property
                $product->image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product->image) : asset('storage/app/public/product/' . $product->image);
                if ($product->image === null) {
                    $product->image = '';
                }
                if ($product->discount_type == 'amount') {
                    $product->discounted_price = number_format($product->price - $product->discount, 2);
                } else {
                    $product->discounted_price = number_format(($product->discount / 100) * $product->price, 2);
                    $product->discounted_price = number_format(($product->price- $product->discounted_price),2);

                }
                // Remove commas from discounted_price
                $product->discounted_price = str_replace(',', '', $product->discounted_price);
                $main_category_data = Category::find($product->category_id);
                
                return [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'item_id' => $product->id,
                    'category_id' => $main_category_data->parent_id,
                    'item_name' => $product->name,
                    'item_image' => $product->image,
                    'customer_id' => $customerId,
                    'item_price' => $cartItem->quantity*$product->discounted_price,
                ];
            });

            return response()->json(['status' => 'success', 'code' => 200, 'data' => $formattedCartItems]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }





    public function removeItemFromCart(Request $request)
    {
        try {
            // Delete the cart item based on cart_id
            $cartId = $request->input('cart_id');
            $cartItem = Cart::where('id', $cartId)->find($cartId);

            if (!$cartItem) {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Cart item not found']);
            }

            $cartItem->delete();
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Item removed from cart']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function emptyCart(Request $request){
    try {
        // Delete all items from the cart for the authenticated user
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';

        $cartItems = Cart::where('customer_id', $customerId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Cart items not found']);
        }

        // Delete all cart items
        $cartItems->each->delete();

        return response()->json(['status' => 'success', 'code' => 200, 'message' => 'All items removed from cart']);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
    }
}

}

