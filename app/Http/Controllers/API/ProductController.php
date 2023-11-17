<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Category;


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
             
             $categoryId = $request->get('category_id');
             $page = $request->get('page');
             $orderBy = $request->get('order_by') ?? 'desc';
             $orderColumn = $request->get('order_column') ?? 'created_at';
             $isDefaultSort = $request->get('is_default_sort') ;  
             $isHideOutOfStockItem = $request->get('is_hide_out_of_stock_items') ?? 1;   
             $perPage = 10; // Number of items to load per page
             $desiredCategoryId = $request->get('sub_category_id');
            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
                 'category_id' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
                 'category_id.required' => 'category id is required.',
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
 

            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = (isset($user) && !empty($user)) ? $user->id : '';
       
            $items = Product::select('products.*')
                ->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('parent_id', $categoryId);
                })
                ->when($isHideOutOfStockItem == '1', function ($query) {
                    $query->where('total_stock', '>', 0);
                }, function ($query) {
                    $query->where('total_stock', '=', 0);
                })
                ->leftJoin('wishlists', function ($join) use ($userId) {
                    $join->on('products.id', '=', 'wishlists.item_id')
                        ->where('wishlists.user_id', '=', $userId);
                })
                ->when(!empty($desiredCategoryId), function ($query) use ($desiredCategoryId) {
                    $query->where('category_id', '=', $desiredCategoryId);
                })
                ->orderBy($orderColumn, $orderBy)
                ->where('status',1)
                ->paginate($perPage, ['*'], 'page', $page);

                $items = $items->map(function ($item) {
                    $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                    
                    $item->image = $imagePath;

                    // Set is_item_in_wishlist to 1 if it's not NULL, otherwise set it to 0
                    $item->is_item_in_wishlist = ($item->is_item_in_wishlist !== null) ? 1 : 0;

                    return $item;
                });
            
           
            
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
             
             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $customerId = (isset($user) && !empty($user)) ? $user->id : '';
             $itemId = $request->input('item_id'); 
             $wishListId = $request->input('wishlist_id');
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
             
             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $customerId = (isset($user) && !empty($user)) ? $user->id : '';
             $page = $request->get('page');
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
     * It will get product detail.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function getProductDetail(Request $request)
    {
        try {
            // Get requested data
            $itemId = $request->get('item_id');
            
            // Define the validation rules
            $validationRules = [
                'item_id' => 'required',
            ]; 
            
            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules, [
                'item_id.required' => 'item id is required.',
            ]);

            // Check for validation errors and return an error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Query to retrieve product items with associated colored images
            $itemDetail = Product::where('id', $itemId)
                ->with('coloredImages')
                ->select('*')
                ->get();  

                 // Check if the product item exists
                 if (count($itemDetail) == 0) {
                    return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Product not found.']);
                }

            $items = $itemDetail->map(function ($item) {
                 // Modify the item's image property
                 $main_item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                 if ($main_item_image === null) {
                     $main_item_image = '';
                 }
               
               
                // Update colored images paths
                $item->coloredImages->map(function ($coloredImage)use ($main_item_image) {
                 
                    // Add image path to colored_image
                    $coloredImage->image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $coloredImage->image) : asset('storage/app/public/product/colored_images/' . $coloredImage->image);

                    $all_item_colored_images = array();
                    if (isset($coloredImage->images) && !empty($coloredImage->images)) {
                        array_push($all_item_colored_images, $coloredImage->image);

                        foreach ($coloredImage->images as $key => $val) {
                            $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $val) : asset('storage/app/public/product/colored_images/' . $val);
                            array_push($all_item_colored_images, $item_image);
                        }
                       // array_push($all_item_colored_images, $main_item_image);
                        $coloredImage->images = $all_item_colored_images;
                    }

                    return $coloredImage;
                });

                 // Modify the item's image property
                 $item->image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                 if ($item->image === null) {
                     $item->image = '';
                 }

                $all_item_images = array();
                if (isset($item->images) && !empty($item->images)) {
                    array_push($all_item_images, $item->image);
                    foreach ($item->images as $key => $val) {
                        $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
                        array_push($all_item_images, $item_image);
                    }
                    $item->images = $all_item_images;
                }

                if ($item->images === null) {
                    $item->images = [];
                }

                // Check and set description to blank if null
                if ($item->description === null) {
                    $item->description = '';
                }

                // Calculate discount price
                if ($item->discount_type == 'amount') {
                    $item->discounted_price = number_format($item->price - $item->discount, 2);
                } else {
                    $item->discounted_price = number_format(($item->discount / 100) * $item->price, 2);
                    $item->discounted_price = number_format(($item->price- $item->discounted_price),2);

                }

                // get catefory name

                $category_data = Category::find($item->category_id);

                $item->category_name = $category_data->name ?? '';

                return $item;
            });

            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully', 'data' => $items[0]]);

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
             
             $cateogryId = $request->get('category_id');
             $productId = $request->get('product_id');

             $page = 1;
  
             $perPage = 10; // Number of items to load per page
            
             // Define the validation rules
             $validationRules = [
                 'category_id' => 'required',
                 'product_id' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'category_id.required' => 'category ID is required.',
                  'product_id.required' => 'product ID is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
 
           
            // Query to retrieve items         
           
            $items =  Product::whereHas('category', function ($query) use ($cateogryId) {
                            $query->where('category_id', $cateogryId);
                        })->where('id', '!=', $productId)
                          ->take(10)
                          ->get();
                          $items = $items->map(function ($item) {
                            // Modify the item's image property
                            $main_item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                            if ($main_item_image === null) {
                                $main_item_image = '';
                            }
                          
                          
           
                            // Modify the item's image property
                            $item->image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                            if ($item->image === null) {
                                $item->image = '';
                            }
           
                           $all_item_images = array();
                           if (isset($item->images) && !empty($item->images)) {
                               array_push($all_item_images, $item->image);
                               foreach ($item->images as $key => $val) {
                                   $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
                                   array_push($all_item_images, $item_image);
                               }
                               $item->images = $all_item_images;
                           }
           
                           if ($item->images === null) {
                               $item->images = [];
                           }
           
                           // Check and set description to blank if null
                           if ($item->description === null) {
                               $item->description = '';
                           }
           
                           // Calculate discount price
                           if ($item->discount_type == 'amount') {
                               $item->discounted_price = number_format($item->price - $item->discount, 2);
                           } else {
                               $item->discounted_price = number_format(($item->discount / 100) * $item->price, 2);
                           }
           
                           // get catefory name
           
                           $category_data = Category::find($item->category_id);
           
                           $item->category_name = $category_data->name ?? '';
           
                           return $item;
                       });
                        
           
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $items]);
             
         } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }
}
