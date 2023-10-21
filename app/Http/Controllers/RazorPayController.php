<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Redirect;
use Session;

class RazorPayController extends Controller
{
    public function payWithRazorpay()
    {
        return view('razor-pay');
    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);


        if (count($input) && !empty($input['razorpay_payment_id'])) {

            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $order = Order::where(['id' => $response->description])->first();
                $tr_ref = $input['razorpay_payment_id'];
                
                $user = User::find($order->user_id);

                if(strlen($order->transaction_reference) === 0){

                      $order->transaction_reference = $tr_ref;
                      $order->payment_method = 'razor_pay';
                      $order->payment_status = 'paid';
                      $order->order_status = 'pending';
                      // $order->confirmed = now();
                      $order->save();
                      
                      Helpers::send_order_notification($order);       
                     
                     if($user->wallet_balance < $order->wallet_amount) 
                        throw new \Exception("Error Processing Request", 1);

                     if($user) $user->decrement('wallet_balance', $order->wallet_amount);

                }else {

                     $order->order_amount = $order->order_amount + $order->due_amount;
                    $order->due_amount = 0;
                    $order->payment_type = 'full';
                    $order->due_amount_transaction_reference = $tr_ref;
                    $order->save();
                
                }

               
                             

            } catch (\Exception $e) {
    
                DB::table('orders')
                ->where('id', $order['id'])
                ->update([
                    'payment_method' => 'razor_pay',
                    'order_status' => 'failed',
                    'failed'=>now(),
                    'updated_at' => now(),
                ]);

                if ($order->callback != null) {
                    return redirect($order->callback . '&status=fail');
                }else{
                    return \redirect()->route('payment-fail');
                }
            }

        }

        if ($order->callback != null) {
            return redirect($order->callback . '&status=success');
        }else{
            return \redirect()->route('payment-success');
        }
    }

}
