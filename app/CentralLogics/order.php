<?php

namespace App\CentralLogics;

use App\Models\Admin;
use App\Models\Order;
use App\CentralLogics\SMS_module;
use App\Models\AdminWallet;
use App\Models\RestaurantWallet;
use App\Models\DeliveryManWallet;
use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class OrderLogic
{
    public static function gen_unique_id()
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }
    
    public static function track_order($order_id)
    {
        return Helpers::order_data_formatting(Order::with(['details', 'delivery_man.rating'])->where(['id' => $order_id])->first(), false);
    }

    public static function sendOTP($phone, $otp, $orderId){


        $response = SMS_module::send($phone, $otp, '6337fea6d6fc053e9e07f7c4');

        // $curl = curl_init();

        // curl_setopt_array($curl, [
        //   CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=6336b143d6fc05659318d3c3&otp_expiry=7&mobile=$phone&authkey=381752AKZDxYXGt2i6317ae3eP1&otp=$otp",
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => "",
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 30,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => "GET",
        //   CURLOPT_POSTFIELDS => "{\"ORDERID\":\"$orderId\"}",
        //   CURLOPT_HTTPHEADER => [
        //     "Content-Type: application/json"
        //   ],
        // ]);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //   echo "cURL Error #:" . $err;
        // } else {
        //   echo $response;
        // }

    }


    public static function place_order($customer_id, $email, $customer_info, $cart, $payment_method, $discount, $coupon_code = null)
    {
        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $customer_id,
                'order_amount' => CartManager::cart_grand_total($cart) - $discount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $payment_method,
                'transaction_ref' => null,
                'discount_amount' => $discount,
                'coupon_code' => $coupon_code,
                'discount_type' => $discount == 0 ? null : 'coupon_discount',
                'shipping_address' => $customer_info['address_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($cart as $c) {
                $product = Food::where('id', $c['id'])->first();
                $or_d = [
                    'order_id' => $o_id,
                    'food_id' => $c['id'],
                    'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                    'product_details' => $product,
                    'qty' => $c['quantity'],
                    'price' => $c['price'],
                    'tax' => $c['tax'] * $c['quantity'],
                    'discount' => $c['discount'] * $c['quantity'],
                    'discount_type' => 'discount_on_product',
                    'variant' => $c['variant'],
                    'variation' => json_encode($c['variations']),
                    'delivery_status' => 'pending',
                    'shipping_method_id' => $c['shipping_method_id'],
                    'payment_status' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('order_details')->insert($or_d);
            }

            Mail::to($email)->send(new \App\Mail\OrderPlaced($o_id));
        } catch (\Exception $e) {

        }

        return $o_id;
    }

    public static function updated_order_calculation($order)
    {
        return true;
    }
    public static function create_transaction($order, $received_by=false, $status = null)
    {
        $comission = $order->vendor->comission==null?\App\Models\BusinessSetting::where('key','admin_commission')->first()->value:$order->vendor->comission;
        $order_amount = $order->order_amount - $order->delivery_charge - $order->total_tax_amount;
        $comission_amount = $comission?($order_amount/ 100) * $comission:0;
        try{
            OrderTransaction::insert([
                'vendor_id' =>$order->vendor->id,
                'delivery_man_id'=>$order->delivery_man_id,
                'order_id' =>$order->id,
                'order_amount'=>$order->order_amount,
                'restaurant_amount'=>$order_amount + $order->total_tax_amount - $comission_amount,
                'admin_commission'=>$comission_amount,
                'delivery_charge'=>$order->delivery_charge,
                'original_delivery_charge'=>$order->original_delivery_charge,
                'tax'=>$order->total_tax_amount,
                'received_by'=> $received_by?$received_by:'admin',
                'status'=> $status,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );

            $vendorWallet = RestaurantWallet::firstOrNew(
                ['vendor_id' => $order->vendor->id]
            );

            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning+$comission_amount;

            if($order->vendor->self_delivery_system)
            {
                $vendorWallet->total_earning = $vendorWallet->total_earning + $order->delivery_charge;
                if($order->payment_method == 'cash_on_delivery')
                {
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash + $order->delivery_charge;
                }
            }
            else{
                $adminWallet->delivery_charge = $adminWallet->delivery_charge+$order->delivery_charge;
            }
            

            $vendorWallet->total_earning = $vendorWallet->total_earning+($order_amount + $order->total_tax_amount - $comission_amount);
            try
            {
                DB::beginTransaction();
                if($received_by=='admin')
                {
                    $adminWallet->digital_received = $adminWallet->digital_received+$order->order_amount;
                }
                else if($received_by=='vendor')
                {
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash+$order->order_amount;
                }
                else if($received_by==false)
                {
                    $adminWallet->manual_received = $adminWallet->manual_received+$order->order_amount;
                }
                else if($received_by=='deliveryman' && $order->delivery_man->type == 'zone_wise')
                {
                    $dmWallet = DeliveryManWallet::firstOrNew(
                        ['delivery_man_id' => $order->delivery_man_id]
                    );
                    $dmWallet->collected_cash=$dmWallet->collected_cash+$order->order_amount;
                    $dmWallet->save();
                }
                else if($order->vendor->self_delivery_system)
                {
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash+$order->order_amount - $order->delivery_charge;
                }
                $adminWallet->save();
                $vendorWallet->save();
                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollBack();
                info($e);
                return false;
            }
        }
        catch(\Exception $e){
            info($e);
            return false;
        }

        return true;
    }

    public static function refund_order($order)
    {

        $order_transaction = $order->transaction;
        // if($order_transaction == null || $order->vendor == null)
        // {
        //     $order_transaction = null;
        // }
        $received_by = $order_transaction == null ? null : $order_transaction->received_by;

        

        if($order_transaction != null){

            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );

            $vendorWallet = RestaurantWallet::firstOrNew(
                ['vendor_id' => $order->vendor->id]
            );


            
            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning - $order_transaction->admin_commission;

            $vendorWallet->total_earning = $vendorWallet->total_earning - $order_transaction->restaurant_amount;
        }

        $refund_amount = $order->order_amount;

        $status = 'refunded_with_delivery_charge';
        if($order->order_status == 'delivered' || $order->order_status == 'completed')
        {
            $refund_amount = $order->order_amount - $order->delivery_charge;
            $status = 'refunded_without_delivery_charge';
        }
        else
        {
             if($order_transaction != null){
                 $adminWallet->delivery_charge = $adminWallet->delivery_charge - $order_transaction->delivery_charge;
            }
        }
        try
        {
            DB::beginTransaction();
            if($received_by=='admin')
            {
                if($order->delivery_man_id && $order->payment_method != "cash_on_delivery")
                {
                    $adminWallet->digital_received = $adminWallet->digital_received - $refund_amount;
                }
                else
                {
                    $adminWallet->manual_received = $adminWallet->manual_received - $refund_amount;
                }
                
            }
            else if($received_by=='vendor')
            {
                $vendorWallet->collected_cash = $vendorWallet->collected_cash - $refund_amount;
            }
            else if($received_by=='deliveryman')
            {
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                $dmWallet->collected_cash=$dmWallet->collected_cash - $refund_amount;
                $dmWallet->save();
            }


             // DB::table('account_transactions')->insert([
             //        'from_type'=>'customer',
             //        'from_id'=>$order->user_id,
             //        'current_balance'=> 0,
             //        'amount'=> $refund_amount,
             //        'method'=>'CASH',
             //        'created_at' => now(),
             //        'updated_at' => now()
             //    ]);

            $order->customer->increment('wallet_balance', $order->order_amount + $order->wallet_amount);

            if($order_transaction != null){

                $order_transaction->status = $status;
                $order_transaction->save();
                $adminWallet->save();
                $vendorWallet->save();

            }
            
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            info($e);
            return false;
        }
        return true;

    }

    // public static function increase_order_count($food, $user)
    // {
    //     try
    //     {
    //         $food->increment('order_count');
    //         $user->increment('order_count');
    //     }
    // }
}
