<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\BusinessSetting;

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
            // Get requested data
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
            $starDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $timeDuration = $request->input('time_duration');
            $cartItems = $request->input('cart_items');
            $addressId = $request->input('address_id');
            $couponId = $request->input('coupon_id');
            $orderInstallmentPercent = $request->input('order_installment_percent');
            $transactionId = $request->input('transaction_id');
            $todayDate = date("Y-m-d");
            

            // Define the validation rules
            $validationRules = [
                'start_date' => 'required',
                'end_date' => 'required',
                'address_id' => 'required',
                'transaction_id' => 'required',
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
            if(isset($cartItems) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                    $itemName = $val['item_name'];
                    $itemNames = $itemNames." ".$itemName;
                    $cartTotalItemAmount = $cartTotalItemAmount + $val['item_price'];
                }
            }            
                
                $description = "Thank you for booking with us.You booked <b>".$itemNames."</b> We'll send a confirmation when your items delivered";   
               
                $deliveryChargeData  = BusinessSetting::where('key','delivery_charge')->first();


                $deliveryCharge = (isset($deliveryChargeData)) ? $deliveryChargeData->value : 0; 
                $totalAmount = $cartTotalItemAmount + $deliveryCharge;


                if($orderInstallmentPercent == ''){
                    $paidAmount = $totalAmount;
                    $pendingAmount = 0;
                } else {
                    $paidAmount =   ($orderInstallmentPercent / 100) * $totalAmount;
                    $pendingAmount = $totalAmount - $paidAmount;
                }

                if($couponId!=''){
                    $couponMinimumPurchase = $couponData->min_purchase;
                    if($couponMinimumPurchase > $paidAmount){
                        return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Minimum amount to apply this coupon is Rs. '.$couponMinimumPurchase]);
                    }
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
                    'description' => $description
                ];

                $newOrder = Order::create($requestData);

               

                $orderId = "BRO".$newOrder->id;
                Order::where('id', $newOrder->id)->update(['order_id' => $orderId]);


            return response()->json(['status' => 'success','message' => 'Order placed successfully', 'code' => 200]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

       
    public function getBookings(Request $request){
        $status = $request->get('status');
        $page = $request->get('page') ;
        $orderBy =  'desc';
        $orderColumn =  'created_at';
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

            $bookingData =  Order::select('*')->where('status',$status)
                        ->orderBy($orderColumn, $orderBy)
                        ->paginate($perPage, ['*'], 'page', $page);

                    

            $bookingData = $bookingData->map(function ($item)  {
                $allData = array();
                $description = $item->description; 
                $orderId   = $item->order_id; 
                array_push($allData,array('description' => $description,'order_id' => $orderId, 'arriving_date' => date("D d M Y",strtotime($item->start_date))));
                return $allData;
            });
                        
        // Check if the order exists
        if (!$bookingData) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
      
        return response()->json(['status' => 'success','message' => 'Data found successfully', 'code' => 200, 'data' => $bookingData]);
    }

  

    public function cancelOrder(Request $request){
        $bookingId = $request->get('booking_id');
        $bookingData = Order::find($bookingId);
    
        // Check if the order exists
        if (!$bookingData) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
    
        // Update order status to "cancelled"
        $bookingData->status = 'cancelled';
        $bookingData->save();
    
        return response()->json(['status' => 'success', 'message' => 'Order cancelled successfully', 'code' => 200, 'data' => $bookingData]);
    }

    public function extendOrder(Request $request){
        $bookingId = $request->get('booking_id');
        $endDate = $request->get('end_date');
        $bookingData = Order::find($bookingId);
    
        // Check if the order exists
        if (!$bookingData) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
    
    
        $bookingData->end_date = $endDate ;
        $bookingData->save();
    
        return response()->json(['status' => 'success', 'message' => 'Order extended successfully', 'code' => 200, 'data' => $bookingData]);
    }

    public function orderDetail(Request $request){
        $bookingId = $request->get('booking_id');
        $bookingData = Order::find($bookingId);
    
        // Check if the order exists
        if (!$bookingData) {
            return response()->json(['status' => 'error', 'message' => 'Order not found', 'code' => 404]);
        }
    return response()->json(['status' => 'success', 'message' => 'Data found successfully', 'code' => 200, 'data' => $bookingData]);
    }
}
