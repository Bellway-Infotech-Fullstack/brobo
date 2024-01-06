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

        DB::table('users')->insert([
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

        Toastr::success(trans('messages.customer_added_successfully'));
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


     


        DB::table('users')->where(['id' => $id])->update([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'address' => $request->address,
            'updated_at' => now(),
        ]);

        Toastr::success(trans('messages.customer_updated_successfully'));
        return redirect()->route('admin.customer.list');
    }

    public function distroy($id)
    {
         User::where(['id'=>$id])->delete();
        Toastr::info(trans('messages.customer_deleted_successfully'));
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
        $userList = Order::where('orders.is_reffered', '=', '1')
            ->latest()
            ->join('users', 'orders.user_id', '=', 'users.id') // Assuming 'user_id' is the foreign key in the 'orders' table
            ->select('users.*') // Select the columns from the 'users' table that you need
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('users.name', 'like', "%{$value}%");
                    $q->orWhere('users.mobile_number', 'like', "%{$value}%");
                    $q->orWhere('users.email', 'like', "%{$value}%");
                }
            })
            ->distinct() // Ensure unique results based on the users.id column
            ->paginate(config('default_pagination'));

        return response()->json([
            'view'=>view('admin-views.customer.partials._rtable',compact('userList'))->render(),
            'count'=>$userList->count()
        ]);
    }

    function refereed_list()
    {
        $user_list = Order::where('orders.is_reffered', '=', '1')
        ->latest()
        ->join('users', 'orders.user_id', '=', 'users.id') // Assuming 'user_id' is the foreign key in the 'orders' table
        ->select('users.*') // Select the columns from the 'users' table that you need
        ->distinct() // Ensure unique results based on the users.id column

        ->paginate(config('default_pagination'));
        
        return view('admin-views.customer.referedd-list', compact('user_list'));
    }

}
