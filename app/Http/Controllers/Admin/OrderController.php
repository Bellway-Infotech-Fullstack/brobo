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
use Razorpay\Api\Api;
use App\Models\User;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Cart;
use App\Models\ProductColoredImage;
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


        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orderstatus = isset($request->orderStatus)?$request->orderStatus:[];
        $scheduled =isset($request->scheduled)?$request->scheduled:0;
        $vendor_ids =isset($request->vendor)?$request->vendor:[];
        $zone_ids =isset($request->zone)?$request->zone:[];
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
        
        
   
        

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

    
        
      
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('created_at', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })

        ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
           
            if($request->orderStatus[0] == 'refunded'){
                return $query->where('refunded','yes');
               
            } else {
                return $query->whereIn('status', $request->orderStatus);
            }
            
        })
        
        ->orderBy('id', 'desc')
        ->paginate(config('default_pagination'));


       
        if($status == 'all'){
          $total = Order::All()->count();  
        }
        
        if($status == 'ongoing'){
          $total = Order::ServiceOngoing()->count();  
        }
        
        if($status == 'completed'){
          $total = Order::Completed()->count();  
        }
        
        if($status == 'cancelled'){
          $total = Order::Cancelled()->count();  
        }
        
        if($status == 'refunded'){
          $total = Order::Refunded()->count();  
        }
        
        if($status == 'failed'){
          $total = Order::failed()->count();  
        }

        $total = $orders->total();
        


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
        /*session()->forget('order_cart');
        $editing = 0;*/

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
           

                
                if(count($cart)>0 && $cart[0]['order_id'] == $order->id)
                {
                     $editing=true;
                }
                else
                {
                    session()->forget('order_cart');
                }
                
            }

            $todayDate = date("Y-m-d");

            $couponData = Coupon::where(['status' => 1])
            ->whereDate('start_date', '<=', $todayDate)
            ->whereDate('expire_date', '>=', $todayDate)
            ->orderBy('created_at', 'desc')->get();  
            $allData = array();
            if(count($couponData) > 0) {         
                    
                foreach($couponData as $key => $value){
                    $data = array(
                        
                            'id' => $value->id,
                            'title' => $value->title,
                            'code' => $value->code,
                            'start_date' => $value->start_date,
                            'expire_date' => $value->expire_date,
                            'min_purchase' => $value->min_purchase,
                            'max_discount' => $value->max_discount,
                            'discount' => $value->discount,
                            'discount_type' => $value->discount_type,
                            'coupon_type' => $value->coupon_type,
                            'limit' => $value->limit,
                            'background_image' => (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/' . $value->background_image) : asset('storage/app/public/coupon_background_image/' . $value->background_image),
                            'status' => $value->status,
                            'created_at' => $value->created_at,
                            'updated_at' => $value->updated_at
                                            
                        );
    
                        array_push($allData,$data);
                    
                }
            }

            $available_offers = $allData;
            
            return view('admin-views.order.order-view', compact('order','categories', 'products','category', 'keyword', 'editing','available_offers'));
        } else {
            Toastr::info(trans('messages.no_more_orders'));
            return back();
        }
    }
    
     public function detailstest(Request $request, $id){
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
            return view('admin-views.order.order-view-test', compact('order','categories', 'products','category', 'keyword', 'editing'));
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

        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->Where('order_id', 'like', "%{$value}%");
                // Add the condition to search by customer name
                $q->orWhereHas('customer', function ($subQuery) use ($value) {
                    $subQuery->where('name', 'like', "%{$value}%");
                    $subQuery->orwhere('mobile_number', $value);
                });
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

        if($request->status == $order->status){
            Toastr::warning('Order is already '.$request->status);
                return back(); 

        }
        
        
        
        
        $order_description = '';
        
        if($request->status == 'completed'){
            if($order->pending_amount > 0){
                Toastr::warning('You cannot change status of order as completed until customer have not paid pending amount');
                return back(); 
            }
            
            $order_description = "Your order has been completed. We hope you enjoy our services. If you have any questions or concerns, feel free to reach out. We appreciate your business and look forward to serving you again in the future";
            
        }
        
        if($order->status == 'cancelled'){
             if($request->status ==  'ongoing' || $request->status ==  'completed'){
                Toastr::warning('You cannot change status of cancelled order');
                return back(); 
            }

        }

        if($request->status == 'cancelled'){
            
           // send push notification 

        

          $bookingUserData = User::find($order->user_id);

          $bookingUserFcmToken = $bookingUserData->fcm_token ?? '';

         

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

    

          $data = [
              'title' => 'Order Cancelled',
              'description' => 'Your order has been cancelled by admin. Please contact admin to refund your amount',
              'order_id' => $order->order_id,
              'image' => '',
              'type'=> 'order_status'
          ];

          Helpers::send_push_notif_to_device($bookingUserFcmToken,$data);


          // send system notification
     
           DB::table('notifications')->insert([
              'title' => "Order Cancelled",
              'description' => "Order No. #$order->order_id has been cancelled by admin. Please contact admin to refund your amount",
              'coupon_id' => NULL,
              'from_user_id' => auth('admin')->user()->id,
              'to_user_id' =>  $order->user_id,
              'created_at' => now(),
              'updated_at' => now()
           ]);
           
           $order_description = "Your order has been cancelled";
       }
        
        

        
        $order->status = $request->status;
        $order->description = $order_description;
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

        $product =  Product::findOrFail($request->product_id);
        $item_type = 'product';
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


  public function initiateRefund(Request $request){
    try {
        $orderId = $request->post('order_id');
        $payment_keys_data = BusinessSetting::where(['key' => 'razor_pay'])->first();
        $payment_keys_data = (isset($payment_keys_data) && !empty($payment_keys_data)) ? json_decode($payment_keys_data->value, true) : '';
        $orderData = Order::where('id', $orderId)->first();
        $transactionId = $orderData->transaction_id;
        
       // $transactionId = "pay_NMOBUtNolVhGHN";
        
      //  echo  $transactionId;
        
        
     
 


        $refundedData = BusinessSetting::where(['key' => 'refunded_amount'])->first();

        // Check if 'refund' index exists in $refundedData
        if (isset($refundedData['refund'])) {
            $refundedDiscount = $refundedData['refund'];
        } else {
            // Handle the case when 'refund' index is not present
            $refundedDiscount = 0; // Set a default value or handle it accordingly
        }

        $paidAmount = $orderData->paid_amount;

        $orderStartDate = $orderData->start_date;
        $startDate = new \DateTime($orderStartDate);
        $currentDate = new \DateTime();
        $dateDifference = $startDate->diff($currentDate)->days;

        if ($dateDifference > 0) {
            $refundAmount = $paidAmount;
        } else {
            $refundAmount = number_format(($refundedDiscount / 100) * $paidAmount, 2);
            $refundAmount = str_replace(',', '', $refundAmount);
            $refundAmount = number_format(($paidAmount - $refundAmount), 2);
            $refundAmount = str_replace(',', '', $refundAmount);
        }

        // Check if payment status is captured
       /* if (!$orderData->payment_status || $orderData->payment_status != 'captured') {
            return response()->json(['status' => 'error', 'message' => 'The payment status should be captured for action to be taken']);
        }*/

        $razorpayKey = $payment_keys_data['razor_key'];
        $razorpaySecret = $payment_keys_data['razor_secret'];
        
       $api = new Api($razorpayKey,  $razorpaySecret);
       
       $payment = $api->payment->fetch($transactionId);
       
     //  echo "amount". $payment->amount;
       
       
       // Check if the payment is authorized
        if ($payment->status === 'authorized') {
            // Capture the payment
            $payment->capture(array('amount' => $payment->amount));
        }
     // echo "refund amount". $refundAmount*100;
      
      
     // die;
       
        // Initiate refund using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/$transactionId/refund");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'amount' => $refundAmount*100,
            'speed' => 'normal',
        ]));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$razorpayKey:$razorpaySecret");

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return response()->json(['status' => 'error', 'message' => 'Refund failed: ' . curl_error($ch)]);
        }
        curl_close($ch);
        
        

        $refundResult = json_decode($result, true);
        
       // echo "<pre>";
        
        //print_r($refundResult);
        //die;
       
        

        // Handle the refund response
        if (isset($refundResult['status'])) {
           Order::where('id', $orderId)->update(['refunded' => 'yes']);

            // send push notification 

            $bookingUserData = User::find($orderData->user_id);

            $bookingUserFcmToken = $bookingUserData->fcm_token ?? '';

            $data = [
                'title' => 'Order Cancelled',
                'description' => 'Your amount has been refunded in your account.',
                'order_id' => $orderData->order_id,
                'image' => '',
                'type'=> 'order_status'
            ];

            Helpers::send_push_notif_to_device($bookingUserFcmToken,$data);


            // send system notification

            DB::table('notifications')->insert([
                'title' => "Order Refunded",
                'description' => "Amount of Order No. #$orderData->order_id has been refunded by admin.",
                'coupon_id' => NULL,
                'from_user_id' => auth('admin')->user()->id,
                'to_user_id' =>  $orderData->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
                        

            
            // Refund processed successfully
            return response()->json(['status' => 'success', 'message' => 'Refund processed successfully']);
        } else {
            // Refund failed
            return response()->json(['status' => 'error', 'message' => $refundResult['error']['description']]);
        }
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }

  public function exportOrderList(Request $request){
        
    // Get all orders data   

    $status = $request->query('order_status');
   

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

    
        
      
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('created_at', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })
        
        ->orderBy('id', 'desc')->get();


        $orderstatus = isset($request->orderStatus)?$request->orderStatus:[];
        $scheduled =isset($request->scheduled)?$request->scheduled:0;
        $vendor_ids =isset($request->vendor)?$request->vendor:[];
        $zone_ids =isset($request->zone)?$request->zone:[];
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
        if($status == 'all'){
          $total = Order::All()->count();  
        }
        
        if($status == 'ongoing'){
          $total = Order::ServiceOngoing()->count();  
        }
        
        if($status == 'completed'){
          $total = Order::Completed()->count();  
        }
        
        if($status == 'cancelled'){
          $total = Order::Cancelled()->count();  
        }
        
        if($status == 'refunded'){
          $total = Order::Refunded()->count();  
        }
        
        if($status == 'failed'){
          $total = Order::failed()->count();  
        }
        

    // Check the requested format (pdf or csv)
    $format = $request->query('format');
    

    // Export to PDF format
    if ($format === 'pdf') {
        $pdf = PDF::loadView('admin-views.order.order-list-pdf',compact('orders'));
        return $pdf->download('order_list.pdf');
    }

    // Export to CSV format
    if ($format === 'csv') {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="order_list.csv"',
        ];

        // Using Symfony's StreamedResponse to efficiently stream large CSV files
        return response()->stream(function () use ($orders) {
          
            $handle = fopen('php://output', 'w');
            // Write CSV headers
            $headers = ['#', 'Booking ID', 'Booking Date', 'Customer Name', 'Customer Mobile Number', 'Delivery Address', 'Pin Location', 'Product Names', 'Start Date', 'End Date', 'Time Slot', 'Paid Amount','GST Number', 'Payment Status', 'Booking Status'];

            // Write headers to CSV
            fputcsv($handle, $headers);

            // Write CSV rows
            foreach ($orders as $k => $order) {
                $addressData = \App\Models\UsersAddress::where('id', $order->delivery_address_id)->first();

                $floorNumber = '';
                $deliveryAddress = '';
                if (isset($addressData) && !empty($addressData)) {
                    $floorNumber = $addressData->floor_number;
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

                    $deliveryAddress = $addressData->house_name . "," . $floorNumber . "" . $suffix . "" . " floor " . "," . $addressData->landmark . "," . $addressData->area_name . "," . $addressData->pin_code;
                } else {
                    $deliveryAddress = 'N/A';
                }

                $productNames = '';
                $cartItems = json_decode($order->cart_items, true);
                if (isset($cartItems) && !empty($cartItems)) {
                    foreach ($cartItems as $item) {
                        $productNames .= "," . $item['item_name'];
                    }
                    $productNames = trim($productNames, ',');
                }
                $rowData = [
                    $k + 1,
                    $order['order_id'],
                    date('d M Y', strtotime($order['created_at'])),
                    $order->customer ? $order->customer['name'] : __('messages.invalid') . ' ' . __('messages.customer') . ' ' . __('messages.data'),
                    $order->customer['mobile_number'] ?? '',
                    $deliveryAddress,
                    $order->pin_location ?? 'N/A',
                    $productNames,
                    date('d M Y', strtotime($order['start_date'])),
                    (!empty($order['end_date'])) ? date('d M Y', strtotime($order['end_date'])) : 'N/A',
                    $order['time_duration'],
                    'Rs. ' . ($order['paid_amount'] ?? ''),
                    $order['gst_number'] ?? 'N/A',
                    'Paid',
                    $order['status']
                ];
            
                // Write the row to CSV
                fputcsv($handle, $rowData);
            }

            fclose($handle);
        }, 200, $headers);
    }

    if ($format == 'excel') {    

        return (new FastExcel(Helpers::format_export_data($orders,'order_list')))->download('order_list.xlsx');


    }

    // If the requested format is not supported or not specified, return an error response
    return response()->json(['error' => 'Unsupported format'], 400);
}

    public function edit(Request $request, Order $order){
        $order = Order::where('id', $order->id)->first();



        // Load relationships
        $order->load([
            'customer' => function ($query) {
                $query->withCount('orders');
            },
        ]);
        
        if ($request->cancel) {
            if ($request->session()->has('order_cart')) {
                $request->session()->forget('order_cart');
            }
            return back();
        }
        
        $cart = collect([]);
        $cartItems = json_decode($order->cart_items,true);  
        
        foreach ($cartItems as $details) {
            unset($details['item_details']);
            $details['status'] = true;
            $details['order_id'] = $order->id;
            $cart->push($details);
        }
     
        if ($request->session()->has('order_cart')) {
            session()->forget('order_cart');
        } else {
            $request->session()->put('order_cart', $cart);
        }
        return back();
        
    }

    public function update(Request $request, Order $order){
        $order = Order::with(['details'=> function ($query) {
        }, 'details.item' => function ($query) {
            return $query->withoutGlobalScope(StoreScope::class);
        }, 'details.campaign' => function ($query) {
            return $query->withoutGlobalScope(StoreScope::class);
        }])->where(['id' => $order->id])->first();

        if (!$request->session()->has('order_cart')) {
            Toastr::error(__('messages.order_data_not_found'));
            return back();
        }
        $cart = $request->session()->get('order_cart', collect([]));
        $store = $order->store;
        $coupon = null;
        $total_addon_price = 0;
        $product_price = 0;
        $store_discount_amount = 0;
        if ($order->coupon_code) {
            $coupon = Coupon::where(['code' => $order->coupon_code])->first();
        }
        foreach ($cart as $c) {
            if ($c['status'] == true) {
                unset($c['status']);
                //  echo "<pre>";
                // print_r($c);
                    $product = Product::find($c['item_id']);
                    if ($product) {

                        $price = $c['item_price'];

                    //    $product = Helpers::product_data_formatting($product);

                        //$c->item_details = '';
                    //  $c->updated_at = now();
                        if (isset($c->id)) {
                            OrderDetail::where('id', $c->id)->update(
                                [
                                    'item_id' => $c->item_id,
                                    'item_details' => $c->item_details,
                                    'quantity' => $c->quantity,
                                    'price' => $c->price,
                                    'tax_amount' => $c->tax_amount,
                                    'discount_on_item' => $c->discount_on_item,
                                    'discount_type' => $c->discount_type,
                                
                                    'updated_at' => $c->updated_at
                                ]
                            );
                        } else {
                        //    $c->save();
                        }

                        $product_price += $price * $c['quantity'];
                    //  $store_discount_amount += $c['discount_on_item'] * $c['quantity'];
                    } else {
                        Toastr::error(__('messages.item_not_found'));
                        return back();
                    }
                
            } else {
                $c->delete();
            }
        }


        $order->delivery_charge = $order->original_delivery_charge;
        



        $coupon_discount_amount = $coupon ? CouponLogic::get_discount($coupon, $product_price + $total_addon_price - $store_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price - $store_discount_amount - $coupon_discount_amount;



        $total_order_ammount = $total_price;
        $adjustment = $order->order_amount - $total_order_ammount;

        $order->paid_amount = $total_order_ammount;
        $order->edited = true;
        $order->save();
        session()->forget('order_cart');
        Toastr::success(__('messages.order_updated_successfully'));
        return back();
    }

    public function quick_view_cart_item(Request $request){
        $cart_item = session('order_cart')[$request->key];
        $order_id = $request->order_id;
        $item_key = $request->key;

        $product_id = $cart_item['item_id'];

        $product =  Product::find($cart_item['item_id']);
        $item_type =  'item';

        $cartData = session()->get('order_cart');

       


      
        $item_color_image_id = $cartData[$item_key]['item_color_image_id'];
        $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product['image']) : asset('storage/app/public/product/' . $product['image']);  

        $itemColorImageId = $item_color_image_id;
        $itemColorImageData = \App\Models\ProductColoredImage::where('id',$itemColorImageId)->first();
        $itemColorImage = (isset($itemColorImageData) && !empty($itemColorImageData)) ? $itemColorImageData->image : '';
        $itemColorImage = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $itemColorImage) : asset('storage/app/public/product/colored_images/' . $itemColorImage); 
        $itemImage = (isset($itemColorImageData) && !empty($itemColorImageData)) ? $itemColorImage : $productImagePath;



        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view-cart-item', compact('order_id', 'product', 'cart_item', 'item_key', 'item_type','itemImage','item_color_image_id','product_id'))->render(),
        ]);
    }

    public function add_to_cart(Request $request){
        $product = Product::find($request->id);

        $data = [];
    

        $data['item_id'] =  $request->id;
        $data['order_id'] = $request->order_id;
        $orderData = Order::find($request->order_id);
        $str = '';
        $price = 0;


        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        
        if ($request->session()->has('order_cart') && !isset($request->cart_item_key)) {
            if (count($request->session()->get('order_cart')) > 0) {
                foreach ($request->session()->get('order_cart') as $key => $cartItem) {
                    if ($cartItem['item_id'] == $request['id'] && $cartItem['status'] == true) {
                        return response()->json([
                            'data' => 1
                        ]);
                    }
                }
            }
        }

        $price = $request->item_price*$request->quantity;   
   

        $customerId = $orderData->user_id;
        $itemId = $request->id;

        $data['quantity'] = $request->quantity;
        $data['item_price'] = $price;
        $data['status'] = true;
        $data['item_color_image_id'] = $request->item_color_image_id;
        $data['category_id'] = $request->category_id;
        $data['customer_id'] = $customerId;
    
        
        $existingCartItem = Cart::where('customer_id', $customerId)->where('item_id', $itemId)->first();
     
        if($existingCartItem) {
            // If the cart item already exists, update the quantity
            $existingCartItem->quantity = $request->quantity;
            $existingCartItem->save();
            $data['id'] = $existingCartItem->id;            

        } else {
            $newCartItem = Cart::create($data);
            $data['id'] = $newCartItem->id;            
        }

        $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product->image) : asset('storage/app/public/product/' . $product->image);   
        $data['item_image'] = $productImagePath;
        $data['item_name'] = $product->name;


       

        $cart = $request->session()->get('order_cart', collect([]));

        if (isset($request->cart_item_key)) {
            $cart[$request->cart_item_key] = $data;
            return response()->json([
                'data' => 2
            ]);
        } else {
            
            $cart->push($data);
        }

        return response()->json([
            'data' => 0
        ]);
    }

    public function remove_from_cart(Request $request){
        $cart = $request->session()->get('order_cart'); 
        if ($cart instanceof \Illuminate\Support\Collection) {
            $cart->forget($request->key);
            $request->session()->put('order_cart', $cart);
            return response()->json([], 200);
        }       
       
    }


    public function getProductColorImageDetail(Request $request){
        $colorImageId = $request->color_image_id;
        $productId = $request->product_id;
        $coloreImageData = ProductColoredImage::find($colorImageId);
        if($colorImageId > 0){
            $all_item_images = array();
            
            $coloreImageData->image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $coloreImageData->image) : asset('storage/app/public/product/colored_images/' . $coloreImageData->image);
            if ($coloreImageData->image === null) {
                $coloreImageData->image = '';
            }
            if (isset($coloreImageData->images) && !empty($coloreImageData->images)) {
                array_push($all_item_images, $coloreImageData->image);
                foreach ($coloreImageData->images as $key => $val) {
                    $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $val) : asset('storage/app/public/product/colored_images/' . $val);
                    array_push($all_item_images, $item_image);
                }
                $coloreImageData->images = $all_item_images;
            }
        
            if ($coloreImageData->images === null) {
                $coloreImageData->images = [];
            }

        } else {
            

            $coloreImageData = Product::find($productId);
            $all_item_images = array();
          
            $coloreImageData->image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $coloreImageData->image) : asset('storage/app/public/product/' . $coloreImageData->image);
            if ($coloreImageData->image === null) {
                $coloreImageData->image = '';
            }
            if (isset($coloreImageData->images) && !empty($coloreImageData->images)) {
                array_push($all_item_images, $coloreImageData->image);
                foreach ($coloreImageData->images as $key => $val) {
                    $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
                    array_push($all_item_images, $item_image);
                }
                $coloreImageData->images = $all_item_images;
            }
        
            if ($coloreImageData->images === null) {
                $coloreImageData->images = [];
            }


        }
        
        return response()->json(['data' => $coloreImageData]);

    }


    public function getCouponDetail(Request $request)
     {
         try {            
        
             // get coupon data 

             $code = $request->code;
             $todayDate = date("Y-m-d");

        

            
             
            $couponData = Coupon::where(['status' => 1])->where('code', $code)
            
            ->whereDate('start_date', '<=', $todayDate)
            ->whereDate('expire_date', '>=', $todayDate)->first();  

            if (isset($couponData) && !empty($couponData)) {
                $couponLimit = $couponData->limit;

                $couponId = $couponData->id;

                $customerId = $request->customer_id;
    
                $userOrderedCouponIdsCount = Order::where(array('user_id' => $customerId , 'coupon_id' => $couponId))->count();

                if($userOrderedCouponIdsCount >= $couponLimit){
                    return response()->json(['status' => 'error', 'message' => 'You can not apply this coupon', 'data' => null]);
                }
          
    
                
    
                
                 $data = array(
                    "status"=> "success",
                    "data" => array(
                     'id' => $couponData->id,
                     'title' => $couponData->title,
                     'code' => $couponData->code,
                     'start_date' => $couponData->start_date,
                     'expire_date' => $couponData->expire_date,
                     'min_purchase' => $couponData->min_purchase,
                     'max_discount' => $couponData->max_discount,
                     'discount' => $couponData->discount,
                     'discount_type' => $couponData->discount_type,
                     'coupon_type' => $couponData->coupon_type,
                     'limit' => $couponData->limit,
                     'background_image' => (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/' . $couponData->background_image) : asset('storage/app/public/coupon_background_image/' . $couponData->background_image),
                     'status' => $couponData->status,
                     'created_at' => $couponData->created_at,
                     'updated_at' => $couponData->updated_at
                    )                     
                 );
             
                 // Ensure that $data is not already encoded before calling json_encode
                 $jsonData = json_encode($data);
                 
               // Use str_replace to remove backslashes
                $withoutBackslashes = str_replace('\\', '', $jsonData);

                return $withoutBackslashes;
             } else {
                 return response()->json(['status' => 'error','message' => 'Coupon code is either expired or invalid']);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
         }
     }


}
