<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Scopes\RestaurantScope;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class POSController extends Controller
{
    public function create(Request $request)
    {
       
        $customers = User::where('role_id',2)->orderBy('id','desc')->get();
        $categories = Category::where(array('status' => 1 , 'parent_id' => 0))->orderBy('id','asc')->get();
        return view('admin-views.pos.add-new', compact('customers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'restaurant_id' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Name is required!',
            'restaurant_id.required' => trans('messages.please_select_restaurant'),
        ]);

        $addon = new AddOn();
        $addon->name = $request->name;
        $addon->price = $request->price;
        $addon->restaurant_id = $request->restaurant_id;
        $addon->save();
        Toastr::success(trans('messages.addon_added_successfully'));
        return back();
    }

    public function getSubCategories(Request $request){
        $categoryId = $request->post('category_id');
        $subCategories = Category::where(array('status' => 1 , 'parent_id' => $categoryId ))->orderBy('id','desc')->get();
        return json_encode($subCategories);

    }

    public function getProductList(Request $request)
     {
         try {
             // Get requested data
             
             $categoryId = $request->get('category_id');
             $orderBy = $request->get('order_by') ?? 'desc';
             $orderColumn = $request->get('order_column') ?? 'created_at';
             $desiredCategoryId = $request->get('sub_category_id');

          
 
           
            // Query to retrieve items

              $items = Product::select('products.id','products.name')
                ->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('parent_id', $categoryId);
                })
              
               
                ->when(!empty($desiredCategoryId), function ($query) use ($desiredCategoryId) {
                    $query->where('products.category_id', '=', $desiredCategoryId);
                })

               
                ->orderBy($orderColumn, $orderBy)
                ->where('products.status',1)->get();

           

            
         

               // Remove duplicate items based on their IDs
            $uniqueItems = $items->unique('id')->values()->all();
            
            // Convert the collection to a plain PHP array
            $uniqueItemsArray = $uniqueItems;

            return json_encode($uniqueItemsArray);
            
    
             
         } catch (\Exception $e) {

             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
     }

     public function addProductInCart(Request $request){
        $productId = $request->input('product_id');
        $quantities  =  $request->input('quantity');
        $customerId  =  $request->input('customer_id');
        


       

        if(isset($productId) && !empty($productId)){
            foreach($productId as $key => $value){
                if(empty($value)){
                   
                    Toastr::error('Please select product');
                    return redirect()->back()->withInput();
                }

                if(empty($quantities[$key])){
                   
                    Toastr::error('Please enter quantity');
                    return redirect()->back()->withInput();
                }
               
                $existingCartItem = Cart::where('customer_id', $customerId)
                ->where(array('item_id' => $value,'is_pos' => '1'))
                ->first();
                if ($existingCartItem) {
    
                    $existingCartItem->quantity = $quantities[$key];
                    $existingCartItem->save();
                } else {
                      // If the cart item doesn't exist, create a new one
                $requestData = [
                    'item_id' => $value,
                    'quantity' => $quantities[$key],
                    'item_color_image_id' => 0,
                    'customer_id' => $customerId,
                    'is_pos' => '1'
                ];
                  Cart::create($requestData);
                }
            }

            DB::table('notifications')->insert([
                'title' => "New items for POS",
                'description' => "New items have been added by admin for POS",
                'coupon_id' => NULL,
                'from_user_id' => auth('admin')->user()->id,
                'to_user_id' =>  $customerId,
                'created_at' => now(),
                'updated_at' => now()
             ]);
            Toastr::success('POS added successfully');
        }

        
        return redirect()->back();

  
     }

     public function list(Request $request)
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
        
        
        $status = 'all';
        

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
        
        ->orderBy('id', 'desc')->where('is_order_pos','1')
        ->paginate(config('default_pagination'));


       
        if($status == 'all'){
          $total = Order::All()->count();  
        }
        
     
     

        $total = $orders->total();
        


        return view('admin-views.order.list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total'));
    }
    

  


}