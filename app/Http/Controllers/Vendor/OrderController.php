<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Models\Order;
use App\Models\Category;
use App\Models\Food;
use App\Models\OrderDetail;
use App\Models\Admin;
use App\Models\RestaurantWallet;
use App\Models\AdminWallet;
use App\Models\ItemCampaign;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function list($status)
    {
        Order::where(['checked' => 0])->where('vendor_id',Helpers::get_restaurant_id())->update(['checked' => 1]);
        
        $orders = Order::with(['customer'])
        ->when($status == 'pending', function($query){
            return $query->Pending();
        })
        ->when($status == 'accepted', function($query){
            return $query->Accepted();
        })
        ->when($status == 'processing', function($query){
            return $query->Preparing();
        })
        ->when($status == 'services_ongoing', function($query){
            return $query->ServiceOngoing();
        })
        ->when($status == 'completed', function($query){
            return $query->Delivered();
        })
        ->when($status == 'canceled', function($query){
            return $query->Canceled();
        })
        ->when($status == 'failed', function($query){
            return $query->failed();
        })
        ->when($status == 'refunded', function($query){
            return $query->Refunded();
        })
        ->when($status == 'scheduled', function($query){
            return $query->Scheduled();
        })
        ->when($status == 'all', function($query){
            return $query->whereIn('order_status',['pending', 'canceled', 'services_ongoing', 'picked_up', 'service_ongoing', 'processing', 'accepted', 'delivered', 'completed', 'refunded']);
        })
        ->Notpos()
        ->where('vendor_id',\App\CentralLogics\Helpers::get_restaurant_id())
        ->orderBy('id', 'desc')
        ->paginate(config('default_pagination'));

        return view('vendor-views.order.list', compact('orders', 'status'));
    }

    public function search(Request $request){


        $key = explode(' ', $request['search']);
        $status = $request->status;
        $search = $request->search;

        $orders= Order::query()
        ->where(['vendor_id'=> Helpers::get_restaurant_id()])
        ->when($status == 'pending', function($query){
            return $query->Pending();
        })
        ->when($status == 'accepted', function($query){
            return $query->Accepted();
        })
        ->when($status == 'processing', function($query){
            return $query->Preparing();
        })
        ->when($status == 'services_ongoing', function($query){
            return $query->ServiceOngoing();
        })
        ->when($status == 'completed', function($query){
            return $query->Delivered();
        })
        ->when($status == 'canceled', function($query){
            return $query->Canceled();
        })
        ->when($status == 'failed', function($query){
            return $query->failed();
        })
        ->when($status == 'refunded', function($query){
            return $query->Refunded();
        })
        ->when($status == 'scheduled', function($query){
            return $query->Scheduled();
        })
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->Where('id', 'like', "%{$value}%")
                // ->orWhere('order_status', 'like', "%{$value}%")
                ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })
        ->Notpos()
        ->limit(50)->get();


        return response()->json([
            'view'=>view('vendor-views.order.partials._table',compact('orders'))->render()
        ]);
    }

    public function details(Request $request,$id)
    {
        $order = Order::with('details')->where(['id' => $id, 'vendor_id' => Helpers::get_restaurant_id()])->first();
        if (isset($order)) {
            return view('vendor-views.order.order-view', compact('order'));
        } else {
            Toastr::info('No more orders!');
            return back();
        }
    }
 
    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'order_status' => 'required|in:pending,confirmed,processing,accepted,services_ongoing,delivered,canceled,completed'
        ],[
            'id.required' => 'Booking id is required!'
        ]);

        $order = Order::where(['id' => $request->id, 'vendor_id' => Helpers::get_restaurant_id()])->first();

        if($order->delivered != null)
        {
            Toastr::warning(trans('messages.cannot_change_status_after_delivered'));
            return back();
        }

        if($request['order_status']=='canceled' && !config('canceled_by_restaurant'))
        {
            Toastr::warning(trans('messages.you_can_not_cancel_a_order'));
            return back();
        }

        if($request['order_status']=='canceled' && $order->confirmed)
        {
            Toastr::warning(trans('messages.you_can_not_cancel_after_confirm'));
            return back();
        }



        // if($request['order_status']=='delivered' && $order->order_type != 'take_away' && !Helpers::get_restaurant_data()->self_delivery_system)
        // {
        //     Toastr::warning(trans('messages.you_can_not_delivered_delivery_order'));
        //     return back();
        // }

        if($request['order_status'] =="confirmed")
        {
            if(!Helpers::get_restaurant_data()->self_delivery_system && config('order_confirmation_model') == 'deliveryman' && $order->order_type != 'take_away')
            {
                Toastr::warning(trans('messages.order_confirmation_warning'));
                return back();
            }
        }

        if($request->order_status == 'completed' || $request->order_status == 'delivered'){

            if($order->order_status != 'services_ongoing'){

                 Toastr::warning("Order has not been verified");
                 return back();

            }

        }

        if ($request->order_status == 'delivered') {
            $order_delivery_verification = (boolean)\App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value;
            // if($order_delivery_verification)
            // {
            //     if($request->otp)
            //     {
            //         if($request->otp != $order->otp)
            //         {
            //             Toastr::warning(trans('messages.order_varification_code_not_matched'));
            //             return back();
            //         }
            //     }
            //     else
            //     {
            //         Toastr::warning(trans('messages.order_varification_code_is_required'));
            //         return back();
            //     }
            // }

            if($order->transaction  == null)
            {
                $ol = OrderLogic::create_transaction($order,'vendor', null);

                if(!$ol)
                {
                    Toastr::warning(trans('messages.faield_to_create_order_transaction'));
                    return back();
                }
            }

            $order->payment_status = 'paid';

            $order->details->each(function($item, $key){
                if($item->service)
                {
                    $item->service->increment('order_count');
                }
            });
            $order->customer->increment('order_count');
        } 

        if($request->order_status == 'canceled' || $request->order_status == 'delivered')
        {
            if($order->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }                   
        }

        $order->order_status = $request->order_status;
        // $order[$request['order_status']] = now();

        /*START OTP*/
        if($request->order_status === 'accepted'){
            // $order->otp = $otp = rand(1000, 9999);
            OrderLogic::sendOTP($order->customer->phone, $order->otp, $order->id);
            Toastr::success("OTP has been sent to customer");
        }

        if($request->order_status === 'services_ongoing'){

            if(!$request->has('otp')){

                 Toastr::warning("Invalid OTP");
                 return back();
                 
            }else{

                if($order->otp != $request->otp){

                    Toastr::warning("Invalid OTP");
                    return back();

                }
            }

        }
        /*END OTP*/
        $order->save();
        // if(!Helpers::send_order_notification($order))
        // {
        //     Toastr::warning(trans('messages.push_notification_faild'));
        // }

        Toastr::success(trans('messages.order').' '.trans('messages.status_updated'));
        return back();
    }

    public function update_shipping(Request $request, $id)
    {
        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required'
        ]);

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('customer_addresses')->where('id', $id)->update($address);
        Toastr::success('Delivery address updated!');
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::where(['id' => $id, 'vendor_id' => Helpers::get_restaurant_id()])->first();
        return view('vendor-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        Order::where(['id' => $id, 'vendor_id' => Helpers::get_restaurant_id()])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success('Payment reference code is added!');
        return back();
    }
}
