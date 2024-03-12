<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Order;
use App\Models\UserPassword;
use App\Models\UsersAddress;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

    public function add_new()
    {
        return view('admin-views.customer.add-new');
    }

    public function store(Request $request)
    {

        $emailValidation = '';
        if(!empty($request->post('email'))){
            $emailValidation = 'email|unique:users';
        }


   

        // Validate the input data
      

        $request->validate([
            'name' => 'required|regex:/^[A-Za-z\s]+$/',
            'email' => $emailValidation,
            'mobile_number' => 'required|regex:/\+91[0-9]{10}/|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],

        ]);

        $referralCode = Str::random(10);
        $userId =  DB::table('users')->insertGetId([
                'name' => $request->name,
                'mobile_number' => $request->mobile_number,
                'gender' => $request->gender,
                'email' => $request->email,
                'role_id' => 2,
                'password' => bcrypt($request->password),
                'referral_code' => $referralCode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
         // Save password in user's password table

        UserPassword::create([
            "customer_id" =>  $userId,
            "password" =>  bcrypt($request->password),

        ]);

        


        Toastr::success('Customer added succesfully');
        return redirect()->route('admin.customer.list');
    }

    function list()
    {
        $user_list = User::where('role_id', '!=','1')->latest()->paginate(config('default_pagination'));
        return view('admin-views.customer.list', compact('user_list'));
    }

    public function edit($id)
    {
        $user_record = User::where(['id' => $id])->first();
        if(isset($user_record) && !empty($user_record)){
            return view('admin-views.customer.edit', compact('user_record'));
        } else {
            Toastr::error('Invalid customer id');
            return redirect()->route('admin.customer.list');
        }
        
    }

    public function update(Request $request, $id)
    {
      
        $emailValidation = '';
        if(!empty($request->post('email'))){
            $emailValidation = 'email|unique:users,email,'.$id;
            
        }


   

        // Validate the input data
      

        $request->validate([
            'name' => 'required|regex:/^[A-Za-z\s]+$/',
            'email' => $emailValidation,
            'mobile_number' => 'required|regex:/\+91[0-9]{10}/|unique:users,mobile_number,'.$id,

        ]);


     


        DB::table('users')->where(['id' => $id])->update([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'updated_at' => now(),
        ]);
        
     
        Toastr::success('Customer updated succesfully');
        return redirect()->route('admin.customer.list');
    }

    public function distroy($id)
    {
         User::where(['id'=>$id])->delete();
         // delete user's booking data
         
         Order::where(['user_id'=>$id])->delete();
         
         // delete user's password data 
          UserPassword::where(['customer_id'=>$id])->delete();
         
          // delete user's address data 
          
         UsersAddress::where(['customer_id'=>$id])->delete();
         
        Toastr::info('Customer deleted succesfully');
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $customers =User::where('role_id', '!=','1')
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
                $q->orWhere('mobile_number', 'like', "%{$value}%");
                $q->orWhere('email', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.customer.partials._table',compact('customers'))->render(),
            'count'=>$customers->count()
        ]);
    }

    public function refereddsearch(Request $request){
        $key = explode(' ', $request['search']);
        $user_list = User::select('users.id', 'users.name', 'users.referral_code', 'users.referred_code')
            ->join('users as referrer', 'users.referred_code', '=', 'referrer.referral_code')
            ->whereNotNull('users.referred_code')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('referrer.name', 'like', "%{$value}%")
                    ->orWhere('users.name', 'like', "%{$value}%");
                }
            })
            ->paginate(config('default_pagination'));


        return response()->json([
            'view'=>view('admin-views.customer.partials._rtable',compact('user_list'))->render(),
            'count'=>$user_list->count()
        ]);
    }

 
   
    function refereed_list()
    {
        $user_list  =  User::select('id', 'name', 'referral_code', 'referred_code')
    ->whereNotNull('referred_code')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('users as referrer')
            ->whereColumn('referrer.referral_code', '=', 'users.referred_code');
    })
    ->paginate(config('default_pagination'));
       
                return view('admin-views.customer.referedd-list', compact('user_list'));

    }


    public function view($id)
    {
        $customer = User::find($id);
        if (isset($customer)) {

            if(session()->has('customer_order_filter'))
            {
                $request = json_decode(session('customer_order_filter'));
                $status = 'all';

          

                $orders = Order::latest()
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
            ->where(['user_id' => $id])
            ->orderBy('id', 'desc')
            ->paginate(config('default_pagination'));



        $orderstatus = isset($request->orderStatus)?$request->orderStatus:[];
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

    
    
    
              //  $orders = Order::latest()->where(['user_id' => $id])->paginate(config('default_pagination'));
                   return view('admin-views.customer.customer-view', compact('customer', 'orders','status','orderstatus','from_date', 'to_date'));
            } else {
                  $orders = Order::latest()->where(['user_id' => $id])->paginate(config('default_pagination'));
                  $status = 'all';
                  $orderstatus = [];
                  $from_date = null;
                  $to_date = null;
                return view('admin-views.customer.customer-view', compact('customer', 'orders','status','orderstatus','from_date', 'to_date'));
            } 

          

         
        }
        Toastr::error(__('messages.customer_not_found'));
        return back();
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
      
        session()->put('customer_order_filter', json_encode($request->all()));
        return back();
    }
    public function filter_reset(Request $request)
    {
       
        session()->forget('customer_order_filter');
        return back();
    }

    

}
