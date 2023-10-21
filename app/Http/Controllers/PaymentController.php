<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        if ($request->has('callback')) {
            Order::where(['id' => $request->order_id])->update(['callback' => $request['callback']]);
        }

        session()->put('customer_id', $request['customer_id']);
        session()->put('order_id', $request->order_id);

        $customer = User::find($request['customer_id']);

        $order = Order::where(['id' => $request->order_id, 'user_id' => $request['customer_id']])->first();

        if (isset($customer) && isset($order)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);

            if($order->order_amount == 0){

                $user = User::find($order->user_id);
                if($user->wallet_balance < $order->wallet_amount){
                    return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Insufficient wallet balance']], 403);
                } 

                $order->transaction_reference = 'wallet_'.Str::random(12);
                $order->payment_method = 'wallet';
                $order->payment_status = 'paid';
                $order->order_status = 'pending';
                // $order->confirmed = now();
                $order->save();      
                
                if($user) $user->decrement('wallet_balance', $order->wallet_amount);

                return redirect('payment-success' . '&status=success');

            }

            if ($request->has('payment_type')) { 
                if($request->payment_type == 'part') return view('payment-view', ['payment_type' => 'part']);
            }

            return view('payment-view', ['payment_type' => 'full']);
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }

    public function success()
    {
        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        if ($order->callback != null) {
            return redirect($order->callback . '&status=success');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        
        if ($order->callback != null) {
            return redirect($order->callback . '&status=fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
