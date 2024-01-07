<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Zone;
use App\Models\Product;
use App\Models\DeliveryManWallet;
use App\Models\DeliveryMan;
use App\Models\Category;
use App\Models\Food;
use App\Models\BusinessSetting;
use App\Models\Coupon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends Controller
{
    public function list($status, Request $request)
    {
        if (session()->has('zone_filter') == false) {
            session()->put('zone_filter', 0);
        }

        if(session()->has('order_filter'))
        {
            $request = json_decode(session('order_filter'));
        }
        

        $orders = Order::with(['customer'])
        ->when(isset($request->zone), function($query)use($request){
            return $query->whereHas('vendor', function($q)use($request){
                return $q->whereIn('zone_id',$request->zone);
            });
        })
   
        
        ->when($status == 'ongoing', function($query){
            return $query->ServiceOngoing();
        })
        ->when($status == 'completed', function($query){
            return $query->Completed();
        })
        ->when($status == 'cancelled', function($query){
            return $query->Cancelled();
        })
        ->when($status == 'failed', function($query){
            return $query->failed();
        })
        ->when($status == 'refunded', function($query){
            return $query->Refunded();
        })

        ->when($status == 'all', function($query){
            return $query->All();
        })

        ->when(isset($request->vendor), function($query)use($request){
            return $query->whereHas('vendor', function($query)use($request){
                return $query->whereIn('id',$request->vendor);
            });
        })
        ->when(isset($request->orderStatus) && $status == 'all', function($query)use($request){
            return $query->whereIn('status',$request->orderStatus);
        })
        ->when(isset($request->scheduled) && $status == 'all', function($query){
            return $query->scheduled();
        })
      
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('created_at', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })
        
        ->orderBy('id', 'desc')
        ->paginate(config('default_pagination'));


        $orderstatus = isset($request->orderStatus)?$request->orderStatus:[];
        $scheduled =isset($request->scheduled)?$request->scheduled:0;
        $vendor_ids =isset($request->vendor)?$request->vendor:[];
        $zone_ids =isset($request->zone)?$request->zone:[];
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
        $total = $orders->count();


        return view('admin-views.order.list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total'));
    }
    
    public function dispatch_list($status, Request $request)
    {

        if(session()->has('order_filter'))
        {
            $request = json_decode(session('order_filter'));
            $zone_ids = isset($request->zone)?$request->zone:0;
        }
        
        Order::where(['checked' => 0])->update(['checked' => 1]);
        
        $orders = Order::with(['customer', 'restaurant'])
        ->when(isset($request->zone), function($query)use($request){
            return $query->whereHas('restaurant', function($query)use($request){
                return $query->whereIn('zone_id',$request->zone);
            });
        })
        ->when($status == 'searching_for_deliverymen', function($query){
            return $query->SearchingForDeliveryman();
        })
        ->when($status == 'ongoing', function($query){
            return $query->Ongoing();
        })
        ->when(isset($request->vendor), function($query)use($request){
            return $query->whereHas('restaurant', function($query)use($request){
                return $query->whereIn('id',$request->vendor);
            });
        })
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('created_at', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })
      
        ->OrderScheduledIn(30)
        ->orderBy('schedule_at', 'desc')
        ->paginate(config('default_pagination'));

        $orderstatus = isset($request->orderStatus)?$request->orderStatus:[];
        $scheduled =isset($request->scheduled)?$request->scheduled:0;
        $vendor_ids =isset($request->vendor)?$request->vendor:[];
        $zone_ids =isset($request->zone)?$request->zone:[];
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
        $total = $orders->total();

        return view('admin-views.order.distaptch_list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total'));
    }

    public function details(Request $request, $id)
    {
        $order = Order::where(['id' => $id])->first();

        if (isset($order)) {


            $category = $request->query('category_id', 0);
            // $sub_category = $request->query('sub_category', 0);
            $categories = Category::active()->get();
            $keyword = $request->query('keyword', false);
            $key = explode(' ', $keyword);
            $products = Product::
            when($category, function($query)use($category){
                $query->whereHas('category',function($q)use($category){
                    return $q->whereId($category)->orWhere('parent_id', $category);
                });
            })
            ->when($keyword, function($query)use($key){
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate(10);
            $editing=false;
            if($request->session()->has('order_cart'))
            {
                $cart = session()->get('order_cart');
                if(count($cart)>0 && $cart[0]->order_id == $order->id)
                {
                    $editing=true;
                }
                else
                {
                    session()->forget('order_cart');
                }
                
            }
            
            // $deliveryMen=Helpers::deliverymen_list_formatting($deliveryMen);
            return view('admin-views.order.order-view', compact('order','categories', 'products','category', 'keyword', 'editing'));
        } else {
            Toastr::info(trans('messages.no_more_orders'));
            return back();
        }
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $status = $request->status;
        $search = $request->search;

        $orders = Order::query()
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
        // ->Where('id', 100350)
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->Where('id', 'like', "%{$value}%")
                // ->orWhere('order_status', 'like', "%{$value}%")
                ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })
     
        ->limit(50)->get();

        return response()->json([
            'view'=>view('admin-views.order.partials._table',compact('orders'))->render()
        ]);
    }



    



    public function status(Request $request)
    {
        $order = Order::find($request->id);

        if($order->status == 'completed')
        {
            Toastr::warning('You cannot change status of a completed order');
            return back(); 
        }
        $order->status = $request->status;
        $order->save();        
      
        Toastr::success(trans('messages.order').trans('messages.status_updated'));
        return back();
    }

 



    public function generate_invoice($id)
    {
        $order = Order::where('id', $id)->first();
        return view('admin-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        Order::where(['id' => $id])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success(trans('messages.payment_reference_code_is_added'));
        return back();
    }

    public function restaurnt_filter($id)
    {
        session()->put('restaurnt_filter', $id);
        return back();
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('order_filter', json_encode($request->all()));
        return back();
    }
    public function filter_reset(Request $request)
    {
        session()->forget('order_filter');
        return back();
    }








    public function quick_view(Request $request)
    {

        $product = $product = Food::findOrFail($request->product_id);
        $item_type = 'food';
        $order_id = $request->order_id;
        
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view', compact('product', 'order_id','item_type'))->render(),
        ]);
    }



    function downloadInvoice(Request $request,$orderId){
         
        $order = Order::where('id',$orderId)->first();
        if (isset($order)) { 
           $businessSettingData = BusinessSetting::where('key','logo')->first(); 
           $businessSettingLogoPath = (isset($businessSettingData) && !empty($businessSettingData)) ? "/storage/app/public/business/".$businessSettingData->value : '';
           
          
           $path = base_path($businessSettingLogoPath);;
           $type = pathinfo($path,PATHINFO_EXTENSION);
          
           $data = file_get_contents($path);
           $logoPath = 'data:image/'. $type .';base64,'. base64_encode($data);
           $pdf = PDF::setOptions(['debugKeepTemp' => true,'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isPhpEnabled' => true])->loadView('admin-views.order.order-invoice',compact('order','logoPath'));
          return $pdf->download('Invoice'.'# '.$orderId.'.pdf');
         }
    } 
}
