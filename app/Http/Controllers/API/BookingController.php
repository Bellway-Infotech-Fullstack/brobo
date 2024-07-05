<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\UsersAddress;
use App\Models\BusinessSetting;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\User;
use App\CentralLogics\Helpers;
use DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    //

     /**
     * book items in cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function bookItems(Request $request){
        try {

            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';

            // Get requested data      
            $starDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $timeDuration = $request->input('time_duration');
            $cartItems = $request->input('cart_items');
            $addressId = $request->input('address_id');
            $couponId = $request->input('coupon_id');
            $orderInstallmentPercent = $request->input('order_installment_percent');
            $transactionId = $request->input('transaction_id');
            $finalItemPrice = $request->input('final_item_price');
            $isBuildingHaveLift = $request->input('is_building_have_lift');
            $deliveryCharge = $request->input('delivery_charge');
            $todayDate = date("Y-m-d");
            $pinLocation = $request->input('pin_location');
            $referralDiscount = $request->input('referral_discount');
            $couponDiscount = $request->input('coupon_discount');
            $gstAmount = $request->input('gst_amount');
            $isOrderPos = $request->input('is_order_pos') ?? "0";
            

            // Define the validation rules
            $validationRules = [
                'start_date' => 'required',
               // 'end_date' => 'required',
                'address_id' => 'required',
                'transaction_id' => 'required',
                'final_item_price'=> 'required',
                'is_building_have_lift' => 'required',
            ];

            // Validate the input data
            $validation = Validator::make($request->all(), $validationRules);

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            
            $todayDate1 = date("Y-m-d", strtotime($starDate));

            /*
            if($todayDate1 <= $todayDate){
                return response()->json(['status' => 'error', 'code' => 422, 'message' => 'Start date should be greater than or equal to current date']);
            }*/
              

            if($couponId!=''){
                $couponData = Coupon::where(['status' => 1,'id' => $couponId])->first();   
                if (empty($couponData)) {
                    return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Invalid coupon id']);
                }
                 $orderCouponDataCount = Order::where(['coupon_id' => $couponId,'user_id' => $customerId])->count();  
              
                $couponLimit = $couponData->limit;
           
                if($orderCouponDataCount == $couponLimit){
                    return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You have crossed apply coupon limit']);
                }
            }
            $cartTotalItemAmount = 0;

            
            $itemNames = "";
            if(isset($_COOKIE) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                    $itemName = $val['item_name'];
                    $itemId = $val['item_id'];
                    $quantity = $val['quantity'];
                    $productData =  Product::find($itemId);
                    $productName = $productData->name;
                     $productStock = $productData->total_stock;
                    if($productStock <= 0){
                        return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Product ' .  $productName . ' is out of stock']);
                    }
                    $remainingStock = $productStock - $quantity;
                    Product::where('id', $itemId)->update(['total_stock' => $remainingStock]);
                    $itemNames = $itemNames." ".$itemName;
                    $cartTotalItemAmount = $cartTotalItemAmount + $val['item_price'];
                }
            }            
                
            $description = "Thank you for booking with us.You booked <b>".$itemNames."</b> We'll send a confirmation when your items delivered";   
            

            $orderMinAmountData  = BusinessSetting::where('key','mininum_order_amount')->first();

            $orderMinAmount  = $orderMinAmountData->value;
          //  echo $finalItemPrice;
            
            if($orderMinAmount >  $finalItemPrice){
                return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You can not order less than '.$orderMinAmount]);
            }


            $totalAmount = $finalItemPrice;

            if($orderInstallmentPercent == ''){
                $paidAmount = $totalAmount;
                
            } else {
                $paidAmount =   ($orderInstallmentPercent / 100) * $totalAmount;
                
            }
               

            if($couponId!=''){
                $couponMinimumPurchase = $couponData->min_purchase;
                if($couponMinimumPurchase > $paidAmount){
                  //  return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Minimum amount to apply this coupon is Rs. '.$couponMinimumPurchase]);
                } else {

                        // Calculate discount price
                    if ($couponData->discount_type == 'amount') {
                        $discountedPrice = number_format($paidAmount - $couponData->discount, 2);
                    } else {
                        $discountedPrice = number_format(($couponData->discount / 100) * $paidAmount, 2);
                        $discountedPrice = number_format(($paidAmount - $discountedPrice),2);

                    }
                  //  $paidAmount = $discountedPrice;
                }
            }

            
                $allCustomers = User::where('role_id','2')->get();
                $loginUserData = User::find( $customerId);
                $loginUserReferralCode = $loginUserData->referral_code ?? '';



              $allCustomersReferralCodes = User::where('referred_code', $loginUserReferralCode)
              ->orderBy('created_at', 'desc')
              ->pluck('referral_code') 
              ->toArray();
          
          
           $usedReferredCodes = Order::where('user_id', $customerId)
              ->pluck('referred_code') 
              ->toArray();
          
              $unusedReferredCodes = array_diff($allCustomersReferralCodes, $usedReferredCodes);

              if(isset($unusedReferredCodes) && !empty($unusedReferredCodes)){
                $referredCode = reset($unusedReferredCodes);
              } else {
                $referredCode = NULL;
              }
              
              


            
      

             $userOrderData = Order::join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.user_id', $customerId)
                ->pluck('orders.referred_code')->toArray();

               

               /* if(!in_array($referredCode,$userOrderData)){
                    if($isReferred == '1'){
                        $discountData  = BusinessSetting::where('key','referred_discount')->first();
                        $referredDiscount =  $discountData->value;                  
    
                        $discountedPrice = number_format(($referredDiscount / 100) * $paidAmount, 2);
                        $discountedPrice = number_format(($paidAmount - $discountedPrice),2);
                       // $paidAmount =  $discountedPrice;
    
                    } 
                } else {
                    $referredCode = NULL;
                }   */


                if($orderInstallmentPercent == ''){
                    $pendingAmount =  0;
                } else {
                    
                    $pendingAmount = $totalAmount - $paidAmount;
                }

               // echo "referred_code".$referredCode;
            //    die;

               
                
                $requestData = [
                    'start_date' => $starDate,
                    'end_date' => $endDate,
                    'time_duration' => $timeDuration,
                    'user_id' => $customerId,
                    'status' => 'ongoing',
                    'paid_amount' => $paidAmount,
                    'pending_amount' => $pendingAmount,
                    'cart_items' => json_encode($cartItems, true),
                    'delivery_address_id' => $addressId,
                    'coupon_id' => $couponId,
                    'delivery_charge' => $deliveryCharge,
                    'order_installment_percent' => $orderInstallmentPercent,
                    'transaction_id' => $transactionId,
                    'status' => 'ongoing',
                    'description' => $description,
                    'final_item_price' => $finalItemPrice,
                    'is_building_have_lift' => $isBuildingHaveLift,
                    'referred_code' => $referredCode,
                    'referral_code' => $loginUserReferralCode,
                    'pin_location' => $pinLocation,
                    'referral_discount' => $referralDiscount,
                    'coupon_discount' => $couponDiscount,
                    'gst_amount' => $gstAmount,
                    'is_order_pos' => $isOrderPos
                ];

                $newOrder = Order::create($requestData);

               

                $orderId = "BRO".$newOrder->id;

                Mail::to("broboyouchoose@gmail.com")->send(new \App\Mail\OrderPlaced($newOrder->id));


                Order::where('id', $newOrder->id)->update(['order_id' => $orderId]);

                // clear cart

                Cart::where('customer_id',$customerId)->delete();
                


                // send push notification 

                $loginUserFcmToken = $loginUserData->fcm_token ?? '';

                $data = [
                    'title' => 'Order Placed',
                    'description' => 'Order placed successfully',
                    'order_id' => $orderId,
                    'image' => '',
                    'type'=> 'order_status'
                ];

                Helpers::send_push_notif_to_device($loginUserFcmToken,$data);


                // send system notification

                DB::table('notifications')->insert([
                    'title' => "Order Placed",
                    'description' => "Order No. #$orderId  placed successfully",
                    'coupon_id' => NULL,
                    'from_user_id' => $customerId,
                    'to_user_id' => $customerId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

               


            return response()->json(['status' => 'success','message' => 'Order placed successfully', 'code' => 200]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

       
    public function getBookings(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';

        $status = $request->get('status');
        $page = $request->get('page');
        $orderBy = 'desc';
        $orderColumn = 'created_at';
        $perPage = 10;

        // Define the validation rules
        $validationRules = [
            'page' => 'required',
            'status' => 'required',
        ];

        // Validate the input data
        $validation = Validator::make($request->all(), $validationRules, [
            'page.required' => 'page is required.',
            'status.required' => 'status is required.',
        ]);

        // Check for validation errors and return error response if any
        if ($validation->fails()) {
            return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
        }

        $bookingData = Order::select('*')
            ->where(array('status' => $status,'user_id' => $customerId))
            ->orderBy($orderColumn, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);

        $bookingData = $bookingData->map(function ($item) {
            $description = $item->description;
            $finalItemPrice   = $item->final_item_price; 
            $cartItems = json_decode($item->cart_items);
            $cartTotalItemAmount = 0;

            if (isset($cartItems) && !empty($cartItems)) {
                foreach ($cartItems as $key => $val) {
                    $cartTotalItemAmount = $cartTotalItemAmount + $val->item_price;
                }
            }

            $orderId = $item->order_id;
            $cartTotalItemAmount = number_format(($cartTotalItemAmount), 0);



            $deliveryCharge = $item->delivery_charge; 

            return [
                'description' => $description,
                'order_id' => $orderId,
                'arriving_date' => date("D d M Y", strtotime($item->start_date)),
                'total_items_price' => $cartTotalItemAmount,
                'final_item_price' => $finalItemPrice - $deliveryCharge,
                'delivery_charge' => $deliveryCharge,
            ];
        });

        if ($bookingData->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No bookings found', 'code' => 404]);
        }

        return response()->json(['status' => 'success', 'message' => 'Data found successfully', 'code' => 200, 'data' => $bookingData->all()]);
    }


    public function getBookingDetail(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';


        $bookingId = $request->get('booking_id');
        $bookingData = Order::where(array('order_id' => $bookingId,'user_id' => $customerId))->get();

       
    
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }

    

        $bookingData = $bookingData->map(function ($item)  {
            $allData = array();
            $description = $item->description; 
            $cartItems = json_decode($item->cart_items);
            $cartTotalItemAmount = 0;
            $totalOrderPrice = $item->paid_amount; 
            $pendingAmount = $item->pending_amount; 
            
            if(isset($cartItems) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                    $cartTotalItemAmount = $cartTotalItemAmount + $val->item_price;
                }
            }            

            $orderId   = $item->order_id; 
            $cartTotalItemAmount = $cartTotalItemAmount;

            $deliveryCharge = $item->delivery_charge; 

            $addressId = $item->delivery_address_id;

            $shippingAddressData = UsersAddress::find($addressId);

            if(isset($shippingAddressData) && !empty($shippingAddressData)){

                $shippingAddress = $shippingAddressData->house_name . ",";


                // Add floor number with suffix
                $floorNumber = $shippingAddressData->floor_number;
                if ($floorNumber % 100 >= 11 && $floorNumber % 100 <= 13) {
                    $suffix = 'th';
                    } else {
                    switch ($floorNumber % 10) {
                    case 1:
                        $suffix = 'st';
                        break;
                    case 2:
                        $suffix = 'nd';
                        break;
                    case 3:
                        $suffix = 'rd';
                        break;
                    default:
                        $suffix = 'th';
                        break;
                    }
                }

                $shippingAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". " floor " . "," . $shippingAddressData->landmark . "," . $shippingAddressData->area_name;
            } else {
                $shippingAddress = '';
            }

            $finalItemPrice   = $item->final_item_price; 

            

           

            array_push($allData,
                array(
                    'description' => $description,
                    'order_id' => $orderId,
                    'arriving_date' => date("M d,Y",strtotime($item->start_date)),
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'time_duration' => $item->time_duration,
                    'total_items_price' => $cartTotalItemAmount,
                    'delivery_charge' => $deliveryCharge,
                    'total_order_price' => $totalOrderPrice,
                    'shipping_address' => $shippingAddress,
                    'pending_amount' => $pendingAmount,
                    'item_details'  => json_decode($item->cart_items,true),
                    'final_item_price' => $finalItemPrice - $deliveryCharge, 
                    'referral_discount' =>   $item->referral_discount,
                    'coupon_discount' =>   $item->coupon_discount,
                    'gst_amount' => $item->gst_amount

                    )
                );
            return $allData;
        });
                    
        if (count($bookingData) == 0) {
        return response()->json(['status' => 'error', 'message' => 'No data found', 'code' => 404]);
        }
        return response()->json(['status' => 'success', 'message' => 'Data found successfully', 'code' => 200, 'data' => $bookingData[0][0]]);
    }

    public function extendOrder(Request $request){
        $bookingId = $request->post('booking_id');
        $endDate = $request->post('end_date');
        $transactionId = $request->post('extended_order_transaction_id');
        $bookingData = Order::where('order_id',$bookingId)->get();
        $amount = $request->post('amount');
        $gstAmount = $request->post('gst_amount');
    
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }

        $paidAmount = $bookingData[0]->final_item_price;
        $totalAmount = $paidAmount + $amount;
        
        $gstAmount =  $bookingData[0]->gst_amount + $gstAmount;
    
           Order::where('order_id', $bookingId)->update(['extended_order_transaction_id' => $transactionId , 'end_date' => $endDate,'final_item_price' => $totalAmount,'paid_amount' => $totalAmount,'extend_amount' => $amount,'gst_amount' => $gstAmount]);

           $bookingData = Order::where('order_id',$bookingId)->get();

    
        return response()->json(['status' => 'success', 'message' => 'Order extended successfully', 'code' => 200, 'data' => $bookingData]);
    }

  

    public function cancelOrder(Request $request){
        $bookingId = $request->post('booking_id');
      
        $bookingData = Order::where('order_id',$bookingId)->get();

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';

        $loginUserData = User::find( $customerId);
    
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
        
        
        
    


        Order::where('order_id', $bookingId)->update(['status' => 'cancelled', 'description' => 'Your order has been cancelled']);
          
          
           $order = Order::find($bookingData[0]->id);

          $cartItems = json_decode($order->cart_items,true);  

          if(isset($cartItems) && !empty($cartItems)){
            foreach ($cartItems as $key => $value){
                $productData = Product::find($value['item_id']);   
                $productCartQuantity = $value['quantity'];
                $totalStock = $productData->total_stock;
                $productData->total_stock =  $totalStock + $productCartQuantity; 
                $productData->save();           
            }
          }
    

           // send push notification 

           $loginUserFcmToken = $loginUserData->fcm_token ?? '';

           $data = [
               'title' => 'Order Cancelled',
               'description' => 'Order cancelled successfully. Please contact admin to refund your amount',
               'order_id' => $bookingId,
               'image' => '',
               'type'=> 'order_status'
           ];

           Helpers::send_push_notif_to_device($loginUserFcmToken,$data);


           // send system notification

           DB::table('notifications')->insert([
               'title' => "Order Cancelled",
               'description' => "Order No. #$bookingId cancelled successfully . Please contact admin to refund your amount",
               'coupon_id' => NULL,
               'from_user_id' => $customerId,
               'to_user_id' => $customerId,
               'created_at' => now(),
               'updated_at' => now()
           ]);


        return response()->json(['status' => 'success', 'message' => 'Order cancelled successfully', 'code' => 200]);
    }

 


    public function payForDamage(Request $request){
        $bookingId = $request->post('booking_id');
        $transactionId = $request->post('damage_order_transaction_id');
        $damageAmount = $request->post('damage_amount');

         // Define the validation rules
         $validationRules = [
            'booking_id' => 'required',
            'damage_order_transaction_id' => 'required',
            'damage_amount' => 'required',
        ];

        // Validate the input data
        $validation = Validator::make($request->all(), $validationRules, [
            'booking_id.required' => 'booking id is required.',
            'damage_order_transaction_id.required' => 'damage_order_transaction_id is required.',
            'damage_amount.required' => 'damage_amount is required.',
        ]);

        // Check for validation errors and return error response if any
        if ($validation->fails()) {
            return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
        }
      
        $bookingData = Order::where('order_id',$bookingId)->get();
        
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
    


        Order::where('order_id', $bookingId)->update(['damage_order_transaction_id' => $transactionId, 'damage_amount' => $damageAmount]);
    
        return response()->json(['status' => 'success', 'message' => 'Amount paid successfully', 'code' => 200]);
    }


    public function getMostOrderedProducts(Request $request)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = $user ? $user->id : '';
    
        $orders = Order::get();
    
        $productCounts = [];
    
        foreach ($orders as $order) {
            $cartItems = json_decode($order->cart_items);
            foreach ($cartItems as $item) {
                $productId = $item->item_id;

    
                // Increment the count for each product
                $productCounts[$productId] = ($productCounts[$productId] ?? 0) + 1;
            }
        }
    
        // Filter products with counts greater than 2
        $mostOrderedProducts = collect($productCounts)
                    ->filter(function ($count) {
                        return $count > 2;
                    })
                    ->keys()
            ->toArray();  // Convert the collection to an array

        $mostOrderedProducts = array_unique($mostOrderedProducts);

   
        $mostOrderedProductDetails = Product::whereIn('id', $mostOrderedProducts)->get();

        $mostOrderedProductDetails = $mostOrderedProductDetails->map(function ($item) use ($customerId) {

            $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
            
            $item->image = $imagePath;

            $itemId = $item->id;
            $wishlistItem = Wishlist::where('item_id', $itemId)->where('user_id', $customerId)->first();




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

        if(count($mostOrderedProductDetails) > 0){
            return response()->json(['status' => 'success', 'message' => 'Data found', 'code' => 200,'data' => $mostOrderedProductDetails]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No Data found', 'code' => 404,'data' => $mostOrderedProductDetails]);
        }
    }
    
    public function getMostOrderedProductsForWeb(Request $request)
    {
        
        $token = JWTAuth::getToken();
        
        
        if(isset($token) && !empty($token)){
            $user = JWTAuth::toUser($token);
            $customerId = $user ? $user->id : '';
            
            $orders = Order::get();
            
            $productCounts = [];
            
            foreach ($orders as $order) {
            $cartItems = json_decode($order->cart_items);
            foreach ($cartItems as $item) {
            $productId = $item->item_id;
            
            
            // Increment the count for each product
            $productCounts[$productId] = ($productCounts[$productId] ?? 0) + 1;
            }
            }
            
            // Filter products with counts greater than 2
            $mostOrderedProducts = collect($productCounts)
            ->filter(function ($count) {
            return $count > 2;
            })
            ->keys()
            ->toArray();  // Convert the collection to an array
            
            $mostOrderedProducts = array_unique($mostOrderedProducts);
            
            
            $mostOrderedProductDetails = Product::whereIn('id', $mostOrderedProducts)->get();
            
            $mostOrderedProductDetails = $mostOrderedProductDetails->map(function ($item) use ($customerId) {
            
            $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
            
            $item->image = $imagePath;
            
            $itemId = $item->id;
            $wishlistItem = Wishlist::where('item_id', $itemId)->where('user_id', $customerId)->first();
            
            
            
            
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
            
            if(count($mostOrderedProductDetails) > 0){
            return response()->json(['status' => 'success', 'message' => 'Data found', 'code' => 200,'data' => $mostOrderedProductDetails]);
            } else {
            return response()->json(['status' => 'error', 'message' => 'No Data found', 'code' => 404,'data' => $mostOrderedProductDetails]);
            }
        
            
            
        } else {
         
            
            $orders = Order::get();
            
            $productCounts = [];
            
            foreach ($orders as $order) {
            $cartItems = json_decode($order->cart_items);
            foreach ($cartItems as $item) {
              $productId = $item->item_id;
            
            
            // Increment the count for each product
              $productCounts[$productId] = ($productCounts[$productId] ?? 0) + 1;
            }
            }
            
            // Filter products with counts greater than 2
            $mostOrderedProducts = collect($productCounts)
            ->filter(function ($count) {
            return $count > 2;
            })
            ->keys()
            ->toArray();  // Convert the collection to an array
            
            $mostOrderedProducts = array_unique($mostOrderedProducts);
            
            
            $mostOrderedProductDetails = Product::whereIn('id', $mostOrderedProducts)->get();
            
            $mostOrderedProductDetails = $mostOrderedProductDetails->map(function ($item)  {
            
            $imagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
            
            $item->image = $imagePath;
            
            $itemId = $item->id;
            
            
          
           
            
            
            
            
            // Set is_item_in_wishlist to 1 if it's not NULL, otherwise set it to 0
            $item->is_item_in_wishlist =  0;
            
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
            
            if(count($mostOrderedProductDetails) > 0){
            return response()->json(['status' => 'success', 'message' => 'Data found', 'code' => 200,'data' => $mostOrderedProductDetails]);
            } else {
            return response()->json(['status' => 'error', 'message' => 'No Data found', 'code' => 404,'data' => $mostOrderedProductDetails]);
            }
        
        }
        
      
        
    }
    


    public function payForDueAmount(Request $request){
        $bookingId = $request->post('booking_id');
        $transactionId = $request->post('due_amount_transaction_id');
        $dueAmount = $request->post('due_amount');

         // Define the validation rules
         $validationRules = [
            'booking_id' => 'required',
            'due_amount_transaction_id' => 'required',
            'due_amount' => 'required',
        ];

        // Validate the input data
        $validation = Validator::make($request->all(), $validationRules, [
            'booking_id.required' => 'booking id is required.',
            'due_amount_transaction_id.required' => 'due_amount_transaction_id is required.',
            'due_amount.required' => 'due_amount is required.',
        ]);

        // Check for validation errors and return error response if any
        if ($validation->fails()) {
            return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
        }
      
        $bookingData = Order::where('order_id',$bookingId)->get();
        
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
        
        $oldDueAmount = $bookingData[0]['pending_amount'];
        $oldPaidAmount = $bookingData[0]['paid_amount'];
    
        $dueAmount = $oldDueAmount - $dueAmount;
        $paidAmount = $oldPaidAmount + $request->post('due_amount');


        Order::where('order_id', $bookingId)->update(['due_amount_transaction_id' => $transactionId, 'pending_amount' => $dueAmount, 'paid_amount' => $paidAmount]);
    
        return response()->json(['status' => 'success', 'message' => 'Amount paid successfully', 'code' => 200]);
    }


    public function getReferrallDiscount(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = ($user) ? $user->id : '';
    
        $loginUserData = User::find($customerId);    
        $loginUserReferralCode = $loginUserData->referral_code ?? '';
     
       
       $allCustomersReferralCodes = User::where('referred_code', $loginUserReferralCode)
           ->orderBy('created_at', 'desc')
           ->pluck('referral_code') 
           ->toArray();
       
       
        $usedReferredCodes = Order::where('user_id', $customerId)
           ->pluck('referred_code') 
           ->toArray();
       
        $unusedReferralCodes = array_diff($allCustomersReferralCodes, $usedReferredCodes);
 
        $discountData = BusinessSetting::where('key', 'referred_discount')->first();
        $referredDiscount = $discountData->value;
     

        if (count($unusedReferralCodes) > 0 ) {
            $data = ['referred_discount' => $referredDiscount];
            return response()->json(['status' => 'success', 'message' => 'Referral discount available', 'code' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Referral discount already used', 'code' => 200, 'data' => null]);
        }
    }

    public function getTimeSlots(Request $request){
       
        $slot_date = date("Y-m-d",strtotime($request->slot_date));


        $order_time_slot_data = DB::table('order_time_slots')->get();  
        if(isset($order_time_slot_data) && !empty($order_time_slot_data)){
            foreach($order_time_slot_data as $key => $value){
                $day_wise_order_time_slot_data = DB::table('day_wise_order_time_slots')->where(array('time_slot_id' => $value->id, 'slot_date' => $slot_date))->first(); 

                $from_time_slot = $value->from_time;
                $to_time_slot =$value->to_time;
                if(isset($day_wise_order_time_slot_data) && !empty($day_wise_order_time_slot_data)){
                    $order_time_slot_data[$key]->is_time_slot_enabled = $day_wise_order_time_slot_data->is_enabled;
                    $order_time_slot_data[$key]->time_slot  =  date("h:i A", strtotime($from_time_slot)) . " to " . date("h:i A", strtotime($to_time_slot));

                    unset($order_time_slot_data[$key]->id);
                    unset($order_time_slot_data[$key]->from_time);
                    unset($order_time_slot_data[$key]->to_time);
                } else {
                    $order_time_slot_data[$key]->is_time_slot_enabled = "yes";
                    $order_time_slot_data[$key]->time_slot  =  date("h:i A", strtotime($from_time_slot)) . " to " . date("h:i A", strtotime($to_time_slot));

                    unset($order_time_slot_data[$key]->id);
                    unset($order_time_slot_data[$key]->from_time);
                    unset($order_time_slot_data[$key]->to_time);
                } 
            }
        }
     
       
       
     

        if (count($order_time_slot_data) > 0 ) {
            return response()->json(['status' => 'success', 'message' => 'Data Found', 'code' => 200, 'data' => $order_time_slot_data]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No Data Found', 'code' => 200, 'data' => []]);
        }
    }

    public function addTotalOrdersInGgoogleAnalytics(Request $request){
        // Google Analytics Measurement Protocol endpoint
            $endpoint = 'https://www.google-analytics.com/collect';

            $measurementId = 'G-WQ6WQVYZDZ'; // Your Google Analytics tracking ID
            $clientId = '1234567890'; // Unique ID for the user/session

            $orderCount = Order::count();
    
 

                 // Data to be sent
                $data = array(
                    'measurement_id' => $measurementId, // Measurement ID
                    'client_id' => $clientId, // Client ID
                    'events' => json_encode(array(
                        array(
                            'name' => 'add_new_transaction', // Event name for viewing a list of items
                            'params' => array(
                                'items' => array(
                                    array(
                                        'item_id' => 'total_transactions', // Unique ID for the total transactions item
                                        'item_name' => 'Total Transactions', // Name of the item
                                        'quantity' => $orderCount, // Quantity (total transactions)
                                        'item_category' => 'Total', // Category of the item
                                    )
                                )
                            )
                        )
                    ))
                );

            // Build query string
            $payload = http_build_query($data);

            // Initialize cURL session
            $ch = curl_init($endpoint);

            // Set cURL options
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response = curl_exec($ch);

            // Close cURL session
            curl_close($ch);

          


     
    
            // Check response
            if ($response === false) {
                echo 'Failed to send data to Google Analytics.';
            } else {
                echo 'Data sent to Google Analytics successfully.';
            }
        

        // Example usage
       
    
        return response()->json(['status' => 'success', 'message' => 'Transactions added successfully', 'code' => 200]);
    }

    //Send Mail
    public function send_mail(Request $request){
        
        $response_flag = 0;
        try {
            Mail::to($request->email)->send(new \App\Mail\OrderPlaced("333"));
            $response_flag = 1;
        } catch (\Exception $exception) {
            print_r($exception);
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }


    public function getBookingDetailAddedByAdmin(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $customerId = (isset($user) && !empty($user)) ? $user->id : '';


        $bookingId = $request->get('booking_id');
        $bookingData = DB::table('temp_orders')
        ->where('id', $bookingId)
        ->get();


    

       
    
        // Check if the order exists
        if (count($bookingData) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }

    

        $bookingData = $bookingData->map(function ($item)  {
            $allData = array();
      
            $orderData = Order::find($item->order_id);
         

          
            $description = $orderData->description; 
            $cartItems = json_decode($item->item_details);
            $cartTotalItemAmount = 0;
            $totalOrderPrice = $item->paid_amount; 
            $pendingAmount = $item->pending_amount; 
            
            if(isset($cartItems) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                    $cartTotalItemAmount = $cartTotalItemAmount + $val->item_price;
                }
            }            

            $orderId   = $item->order_id; 
            $cartTotalItemAmount = $cartTotalItemAmount;

            $deliveryCharge = $orderData->delivery_charge; 

            $addressId = $orderData->delivery_address_id;

            $shippingAddressData = UsersAddress::find($addressId);

            if(isset($shippingAddressData) && !empty($shippingAddressData)){

                $shippingAddress = $shippingAddressData->house_name . ",";


                // Add floor number with suffix
                $floorNumber = $shippingAddressData->floor_number;
                if ($floorNumber % 100 >= 11 && $floorNumber % 100 <= 13) {
                    $suffix = 'th';
                    } else {
                    switch ($floorNumber % 10) {
                    case 1:
                        $suffix = 'st';
                        break;
                    case 2:
                        $suffix = 'nd';
                        break;
                    case 3:
                        $suffix = 'rd';
                        break;
                    default:
                        $suffix = 'th';
                        break;
                    }
                }

                $shippingAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". " floor " . "," . $shippingAddressData->landmark . "," . $shippingAddressData->area_name;
            } else {
                $shippingAddress = '';
            }

            $finalItemPrice   = $item->final_item_price; 

            

           

            array_push($allData,
                array(
                    'description' => $description,
                    'order_id' => $orderId,
                    'arriving_date' => date("M d,Y",strtotime($orderData->start_date)),
                    'start_date' => $orderData->start_date,
                    'end_date' => $orderData->end_date,
                    'time_duration' => $orderData->time_duration,
                    'total_items_price' => $cartTotalItemAmount,
		    'delivery_charge' => $item->delivery_charge,
                    'total_order_price' => $totalOrderPrice,
                    'shipping_address' => $shippingAddress,
                    'pending_amount' => $pendingAmount,
                    'item_details'  => json_decode($item->item_details,true),
                    'final_item_price' => $finalItemPrice - $deliveryCharge, 
                    'referral_discount' =>   $orderData->referral_discount,
                    'coupon_discount' =>   $item->coupon_discount,
                    'gst_amount' => $item->gst_amount

                    )
                );
            return $allData;
        });
                    
        if (count($bookingData) == 0) {
        return response()->json(['status' => 'error', 'message' => 'No data found', 'code' => 404]);
        }
        return response()->json(['status' => 'success', 'message' => 'Data found successfully', 'code' => 200, 'data' => $bookingData[0][0]]);
    }


      /**
     * book items in cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
     public function bookItemsAddedByAdmin(Request $request){
        try {

            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';

            $bookingId =  $request->input('booking_id');

            $bookingData = DB::table('temp_orders')->where('id', $bookingId)->first();

            $orderData = Order::find($bookingData->order_id);

            // Get requested data      
            $starDate = $orderData->start_date;
            $endDate = $orderData->end_date;
            $timeDuration = $orderData->time_duration;
            $cartItems = json_decode($bookingData->item_details);
            $addressId = $orderData->address_id;
            $couponId = $orderData->coupon_id;
            $orderInstallmentPercent = $orderData->order_installment_percent;
            $transactionId = $orderData->transaction_id;
            $finalItemPrice = $bookingData->final_item_price;
            $isBuildingHaveLift = $orderData->is_building_have_lift;
            $deliveryCharge = $orderData->delivery_charge;
            $todayDate = date("Y-m-d");
            $pinLocation = $orderData->pin_location;
            $referralDiscount = $orderData->referral_discount;
            $couponDiscount = $bookingData->coupon_discount;
            $gstAmount = $bookingData->gst_amount;

       

    
              

          /*  if($couponId!=''){
                $couponData = Coupon::where(['status' => 1,'id' => $couponId])->first();   
                if (empty($couponData)) {
                    return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Invalid coupon id']);
                }
                 $orderCouponDataCount = Order::where(['coupon_id' => $couponId,'user_id' => $customerId])->count();  
              
                $couponLimit = $couponData->limit;
           
                if($orderCouponDataCount == $couponLimit){
                    return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You have crossed apply coupon limit']);
                }
            }*/
            $cartTotalItemAmount = 0;

            
            $itemNames = "";
            if(isset($cartItems) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                   
                    $itemName = $val->item_name;
                    $itemId = $val->item_id;
                    $quantity = $val->quantity;
                    $productData =  Product::find($itemId);
                    $productName = $productData->name;
                     $productStock = $productData->total_stock;
                    if($productStock <= 0){
                        return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Product ' .  $productName . ' is out of stock']);
                    }
                    $remainingStock = $productStock - $quantity;
                    Product::where('id', $itemId)->update(['total_stock' => $remainingStock]);
                    $itemNames = $itemNames." ".$itemName;
                    $cartTotalItemAmount = $cartTotalItemAmount + $val->item_price;
                }
            }            
                
            $description = "Thank you for booking with us.You booked <b>".$itemNames."</b> We'll send a confirmation when your items delivered";   
            

            $orderMinAmountData  = BusinessSetting::where('key','mininum_order_amount')->first();

            $orderMinAmount  = $orderMinAmountData->value;
          //  echo $finalItemPrice;
            
            if($orderMinAmount >  $finalItemPrice){
                return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You can not order less than '.$orderMinAmount]);
            }


            $totalAmount = $finalItemPrice;

            if($orderInstallmentPercent == ''){
                $paidAmount = $totalAmount;
                
            } else {
                $paidAmount =   ($orderInstallmentPercent / 100) * $totalAmount;
                
            }
               

          /*  if($couponId!=''){
                $couponMinimumPurchase = $couponData->min_purchase;
                if($couponMinimumPurchase > $paidAmount){
                  //  return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Minimum amount to apply this coupon is Rs. '.$couponMinimumPurchase]);
                } else {

                        // Calculate discount price
                    if ($couponData->discount_type == 'amount') {
                        $discountedPrice = number_format($paidAmount - $couponData->discount, 2);
                    } else {
                        $discountedPrice = number_format(($couponData->discount / 100) * $paidAmount, 2);
                        $discountedPrice = number_format(($paidAmount - $discountedPrice),2);

                    }
                  //  $paidAmount = $discountedPrice;
                }
            }*/

            
                $allCustomers = User::where('role_id','2')->get();
                $loginUserData = User::find( $customerId);
                $loginUserReferralCode = $loginUserData->referral_code ?? '';



              $allCustomersReferralCodes = User::where('referred_code', $loginUserReferralCode)
              ->orderBy('created_at', 'desc')
              ->pluck('referral_code') 
              ->toArray();
          
             

          
           $usedReferredCodes = Order::where('user_id', $customerId)
              ->pluck('referred_code') 
              ->toArray();
          
              $unusedReferredCodes = array_diff($allCustomersReferralCodes, $usedReferredCodes);

              if(isset($unusedReferredCodes) && !empty($unusedReferredCodes)){
                $referredCode = reset($unusedReferredCodes);
              } else {
                $referredCode = NULL;
              }
              
              


            
      

             $userOrderData = Order::join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.user_id', $customerId)
                ->pluck('orders.referred_code')->toArray();

               

        


                if($orderInstallmentPercent == ''){
                    $pendingAmount =  0;
                } else {
                    
                    $pendingAmount = $totalAmount - $paidAmount;
                }

         
               
                
                $requestData = [
                    'start_date' => $starDate,
                    'end_date' => $endDate,
                    'time_duration' => $timeDuration,
                    'user_id' => $customerId,
                    'status' => 'ongoing',
                    'paid_amount' => $paidAmount,
                    'pending_amount' => $pendingAmount,
                    'cart_items' => json_encode($cartItems, true),
                    'delivery_address_id' => $addressId,
                    'coupon_id' => $couponId,
                    'delivery_charge' => $deliveryCharge,
                    'order_installment_percent' => $orderInstallmentPercent,
                    'transaction_id' => $transactionId,
                    'status' => 'ongoing',
                    'description' => $description,
                    'final_item_price' => $finalItemPrice,
                    'is_building_have_lift' => $isBuildingHaveLift,
                    'referred_code' => $referredCode,
                    'referral_code' => $loginUserReferralCode,
                    'pin_location' => $pinLocation,
                    'referral_discount' => $referralDiscount,
                    'coupon_discount' => $couponDiscount,
                    'gst_amount' => $gstAmount
                ];



                $orderCartItems = json_decode($orderData->cart_items);


                $cartItems =  json_encode($cartItems, true);

                $tempOrderCartItems = json_decode($cartItems);
            
              

                $finalArray = array_merge($orderCartItems,$tempOrderCartItems);
     

                DB::table('orders')
                ->where('id', $bookingData->order_id)->update([
                    'cart_items' => json_encode($finalArray, true),
                    'paid_amount' => $bookingData->paid_amount,
                    'final_item_price' => $bookingData->paid_amount,
                    'coupon_discount' => $bookingData->coupon_discount,
                    
                ]);


                DB::table('temp_orders')
                ->where('order_id', $bookingData->order_id)->update([
                   'payment_status' => 'paid',
                   'transaction_id' => $transactionId
                 
                ]);

               // $newOrder = Order::create($requestData);

               

                $orderId = "BRO".$bookingData->order_id;

               // Mail::to("broboyouchoose@gmail.com")->send(new \App\Mail\OrderPlaced($newOrder->id));


         //       Order::where('id', $newOrder->id)->update(['order_id' => $orderId]);

                // clear cart

                Cart::where('customer_id',$customerId)->delete();


                // send push notification 

                $loginUserFcmToken = $loginUserData->fcm_token ?? '';

                $data = [
                    'title' => 'Order Payment Done',
                    'description' => "Order No. #$orderId  payment done successfully",
                    'order_id' => $orderId,
                    'image' => '',
                    'type'=> 'order_status'
                ];

                Helpers::send_push_notif_to_device($loginUserFcmToken,$data);


                // send system notification

                DB::table('notifications')->insert([
                    'title' => "Order Payment Done",
                    'description' => "Order No. #$orderId  payment done successfully",
                    'coupon_id' => NULL,
                    'from_user_id' => $customerId,
                    'to_user_id' => $customerId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

               


            return response()->json(['status' => 'success','message' => 'Order payment done successfully', 'code' => 200]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

}

