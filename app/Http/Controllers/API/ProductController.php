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
             $isHideOutOfStockItem = $request->get('is_hide_out_of_stock_items');   
             $perPage =  10; // Number of items to load per page
             $desiredCategoryId = $request->get('sub_category_id');
             $searchKey = $request->get('search_key') ;  

            
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
                 $orderColumn = 'products.created_at';   
            }

            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = (isset($user) && !empty($user)) ? $user->id : '';
       
            $items = Product::select('products.*')
                ->leftJoin('product_colored_image', 'products.id', '=', 'product_colored_image.product_id')
                ->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('parent_id', $categoryId);
                })
               ->when(!empty($isHideOutOfStockItem) && $isHideOutOfStockItem == '1', function ($query) {
                    $query->where('products.total_stock', '>', 0);
                }, function ($query) use ($isHideOutOfStockItem) {
                    if (empty($isHideOutOfStockItem)) {
                        $query->where('products.total_stock', '>', 0);
                    } else {
                        $query->where('products.total_stock', '=', 0);
                    }
                })
                ->leftJoin('wishlists', function ($join) use ($userId) {
                    $join->on('products.id', '=', 'wishlists.item_id')
                        ->where('wishlists.user_id', '=', $userId);
                })
                ->when(!empty($desiredCategoryId), function ($query) use ($desiredCategoryId) {
                    $query->where('products.category_id', '=', $desiredCategoryId);
                })

                ->when(!empty($searchKey), function ($query) use ($searchKey) {
                    $query->where('products.name', 'like', '%' . $searchKey . '%')
                    ->orWhere('product_colored_image.color_name', $searchKey);

                })
                ->orderBy($orderColumn, $orderBy)
                ->where('products.status',1)
                ->paginate($perPage, ['*'], 'page', $page);

                $items = $items->map(function ($item) use ($userId) {

                    $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                    
                    $item->image = $imagePath;

                    $itemId = $item->id;
                    $wishlistItem = Wishlist::where('item_id', $itemId)->where('user_id', $userId)->first();

    


                    // Set is_item_in_wishlist to 1 if it's not NULL, otherwise set it to 0
                    $item->is_item_in_wishlist = ($wishlistItem !== null) ? 1 : 0;

                     // Modify the item's image property
                        $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                        if ($item_image === null) {
                            $item_image = '';
                        }

                    $all_item_images = array();
                    if (isset($item->images) && !empty($item->images)) {
                        array_push($all_item_images, $item->image);
                        foreach ($item->images as $key => $val) {
                            $item_images = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
                            array_push($all_item_images, $item_images);
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
                    if($item->discount > 0){
                    
                       $discounted_price = (($item->discount / 100) * $item->price);
                       $item->discounted_price = number_format(($item->price- $discounted_price),2);
                    } else {
                         $item->discounted_price = 0;
                    }
    
                }
                // Remove commas from discounted_price
                $item->discounted_price = str_replace(',', '', $item->discounted_price);

                    return $item;
            });
            
             // Remove duplicate items based on their IDs
     //   $items = $items->unique('id');
            
         

               // Remove duplicate items based on their IDs
            $uniqueItems = $items->unique('id')->values()->all();
            
            // Convert the collection to a plain PHP array
            $uniqueItemsArray = $uniqueItems;
            
           
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $uniqueItemsArray]);
             
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
             // Define the validation rules
             $validationRules = [
                 'item_id' => 'required',
             ];
 

 
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'item_id.required' => 'Item ID is required.',
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
             
                   // Query to retrieve product items with associated colored images
            $itemDetail = Product::where('id', $itemId)
                ->select('*')
                ->get();  

             // Check if the product item exists
             if (count($itemDetail) == 0) {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Product not found.']);
            }
 
              // Check if the item is already in the wishlist for the user
        $existingWishlistItem = Wishlist::where('item_id', $itemId)->where('user_id', $customerId)->first();

        // Create or update the wishlist based on whether 'wishlist_id' is provided

            // Update an existing wishlist item
            if ($existingWishlistItem) {
                $existingWishlistItem->delete();
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Item is removed from wishlist']);
            }
             else {
                $result = Wishlist::create([
                    'item_id' => $itemId,
                    'user_id' => $customerId,
                ]);
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Item is added to wishlist', 'data' => $result]);
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
    
     public function getItemInWishList(Request $request){
      try {
        // Get requested data
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';
        $page = $request->get('page') ?? 1;
        $perPage = 10; // Number of items to load per page

        // Query to retrieve wishlist items with associated products for the specific user
        $wishlistItems = Wishlist::where('user_id', $customerId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Prepare response data array
        $responseData = [];

        // Loop through the wishlist items and access the associated product details.
        if (isset($wishlistItems) && !empty($wishlistItems)) {
            foreach ($wishlistItems as $wishlistItem) {
                $product = $wishlistItem->product;

                // Build the images array for the response
                $productImages = [];
                foreach ($product->images as $image) {
                    $productImages[] = (env('APP_ENV') == 'local') ? asset('storage/product/' . $image) : asset('storage/app/public/product/' . $image);
                }
                $product_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product->image) : asset('storage/app/public/product/' . $product->image);
                 if ($product->image === null) {
                     $product_image = '';
                 }

                // Check and set description to blank if null
                if ($product->description === null) {
                    $product->description = '';
                }

                // Add product details to the response data array
                $responseData[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category_ids' => $product->category_ids,
                    'price' => $product->price,
                    'tax' => $product->tax,
                    'tax_type' => $product->tax_type,
                    'discount' => $product->discount,
                    'discount_type' => $product->discount_type,
                    'images' => $productImages,
                    'total_stock' => $product->total_stock,
                    'status' => $product->status,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                    'image' => $product_image,
                    'is_item_in_whishlist' => 1
                    
                ];
            }
        }

        if (count($responseData) > 0) {
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully', 'data' => $responseData]);
        } else {
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'No data found', 'data' => $responseData]);
        }

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
                        array_push($all_item_colored_images, $main_item_image);
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
                    if($item->discount > 0){
                       $discounted_price = (($item->discount / 100) * $item->price);
                       $item->discounted_price = number_format(($item->price- $discounted_price),2);
                    } else {
                         $item->discounted_price = 0;
                    }
                }
                // Remove commas from discounted_price
                $item->discounted_price = str_replace(',', '', $item->discounted_price);

                // get sub catefory name

                $item->sub_category_id = $item->category_id;

                $category_data = Category::find($item->category_id);
                if($category_data){
                     $item->category_id = $category_data->parent_id;

                $item->sub_category_name = $category_data->name ?? '';
                } else {
                    $item->category_id =  '';
                    $item->sub_category_name = '';
                }

               

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
             
               // Query to retrieve product items with associated colored images
            $itemDetail = Product::where('id', $productId)
                ->with('coloredImages')
                ->select('*')
                ->get();  

                 // Check if the product item exists
                 if (count($itemDetail) == 0) {
                    return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Product not found.']);
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
                    if($item->discount > 0){
                    
                       $discounted_price = (($item->discount / 100) * $item->price);
                       $item->discounted_price = number_format(($item->price- $discounted_price),2);
                    } else {
                         $item->discounted_price = 0;
                    }
    
                }
                // Remove commas from discounted_price
                $item->discounted_price = str_replace(',', '', $item->discounted_price);
           
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


     //

     /**
     * It will get all items of all categories .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
     public function getAllProductList(Request $request)
     {
         try {
             // Get requested data
             
             $page = $request->get('page');
             $perPage = 10; // Number of items to load per page
             $searchKey = $request->get('search_key') ;  

            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }
 
           
            // Query to retrieve items


            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = (isset($user) && !empty($user)) ? $user->id : '';
       
            $items = Product::select('products.*')
            ->leftJoin('product_colored_image', 'products.id', '=', 'product_colored_image.product_id')
                
                ->leftJoin('wishlists', function ($join) use ($userId) {
                    $join->on('products.id', '=', 'wishlists.item_id')
                        ->where('wishlists.user_id', '=', $userId);
                })
              
                ->when(!empty($searchKey), function ($query) use ($searchKey) {
                    $query->where('products.name', 'like', '%' . $searchKey . '%')
                    ->orWhere('product_colored_image.color_name', $searchKey);
                })
                ->orderBy('products.created_at', 'desc')
                ->where('products.status',1)
                ->where('products.total_stock','>',0)
                ->paginate($perPage, ['*'], 'page', $page);

                $items = $items->map(function ($item) use ($userId) {

                    $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                    
                    $item->image = $imagePath;

                    $itemId = $item->id;
                    $wishlistItem = Wishlist::where('item_id', $itemId)->where('user_id', $userId)->first();

    


                    // Set is_item_in_wishlist to 1 if it's not NULL, otherwise set it to 0
                    $item->is_item_in_wishlist = ($wishlistItem !== null) ? 1 : 0;

                     // Modify the item's image property
                        $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
                        if ($item_image === null) {
                            $item_image = '';
                        }

                    $all_item_images = array();
                    if (isset($item->images) && !empty($item->images)) {
                        array_push($all_item_images, $item->image);
                        foreach ($item->images as $key => $val) {
                            $item_images = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
                            array_push($all_item_images, $item_images);
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
                    if($item->discount > 0){
                    
                       $discounted_price = (($item->discount / 100) * $item->price);
                       
                   
                      $item->discounted_price = number_format(($item->price- $discounted_price),2);
                    } else {
                         $item->discounted_price = 0;
                    }
    
                }
                // Remove commas from discounted_price
                $item->discounted_price = str_replace(',', '', $item->discounted_price);

                    return $item;
                });

               
            
               // Remove duplicate items based on their IDs
            $uniqueItems = $items->unique('id')->values()->all();
            
            // Convert the collection to a plain PHP array
            $uniqueItemsArray = $uniqueItems;
            
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $uniqueItemsArray]);
             
         } catch (\Exception $e) {
           
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
    }
}

