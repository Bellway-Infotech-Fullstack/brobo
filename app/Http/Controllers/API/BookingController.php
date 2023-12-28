<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Coupon;

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
                    echo "here";
                    return response()->json(['status' => 'error', 'code' => 400, 'message' => 'You have crossed apply coupon limit']);
                }
            }
            $cartTotalItemAmount = 0;
            
            if(isset($cartItems) && !empty($cartItems)){
                foreach($cartItems as $key => $val){
                    $item_data = $val['item_id'];
                    $cartTotalItemAmount = $cartTotalItemAmount + $val['item_price'];
                }
            }            
                
               
               
                
                $deliveryCharge = 0; // will come admin side
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
                ];

                Order::create($requestData);

            return response()->json(['status' => 'success','message' => 'Order placed successfully', 'code' => 201]);
            

           

           

        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

}
