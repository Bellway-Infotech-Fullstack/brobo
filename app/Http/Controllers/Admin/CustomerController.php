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
            'address' =>'required'

        ]);

       $userId =  DB::table('users')->insertGetId([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'email' => $request->email,
            'address' => $request->address,
            'role_id' => 2,
            'password' => bcrypt($request->password),
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
            'address' =>'required'

        ]);


     


        DB::table('users')->where(['id' => $id])->update([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'address' => $request->address,
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
            $orders = Order::latest()->where(['user_id' => $id])->paginate(config('default_pagination'));
            return view('admin-views.customer.customer-view', compact('customer', 'orders'));
        }
        Toastr::error(__('messages.customer_not_found'));
        return back();
    }

}
