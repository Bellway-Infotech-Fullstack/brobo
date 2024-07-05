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
use \PDF;

// use Barryvdh\DomPDF\Facade\Pdf;

// use Barryvdh\DomPDF\Facade\Pdf as PDF;


use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Mail;
use ZipArchive;



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
                'home_city' => $request->home_city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
         // Save password in user's password table

        UserPassword::create([
            "customer_id" =>  $userId,
            "password" =>  bcrypt($request->password),

        ]);



        Mail::to($request->email)->send(new \App\Mail\NewCustomerRegistration($userId,$request->email,$request->password));

        


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
            'home_city' => $request->home_city,
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
        $user_list = User::select('users.id', 'users.name', 'users.referral_code', 'users.referred_code','users.mobile_number')
            ->join('users as referrer', 'users.referred_code', '=', 'referrer.referral_code')
            ->whereNotNull('users.referred_code')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('referrer.name', 'like', "%{$value}%")
                    ->orWhere('users.name', 'like', "%{$value}%")
                    ->orWhere('referrer.mobile_number', '=', "$value")
                    ->orWhere('users.mobile_number', '=', "$value");
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
        $user_list  =  User::select('id', 'name', 'referral_code', 'referred_code','mobile_number')
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

    public function export(Request $request)
    {
        // Get all users data
        $users = User::where('role_id', '!=', '1')->latest()->get();

        // Check the requested format (pdf or csv)
        $format = $request->query('format');
        

        // Export to PDF format
        if ($format === 'pdf') {
            $pdf = PDF::loadView('admin-views.customer.customer-list-pdf',compact('users'));


            return $pdf->download('customer_list.pdf');
        }

        // Export to CSV format
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="customer_list.csv"',
            ];

            // Using Symfony's StreamedResponse to efficiently stream large CSV files
            return response()->stream(function () use ($users) {
                $handle = fopen('php://output', 'w');
                // Write CSV headers
                fputcsv($handle, ['#','Name', 'Email', 'Phone','Home City']);

                // Write CSV rows
                foreach ($users as $k => $user) {
                    fputcsv($handle, [$k+1,$user->name, $user->email ?? 'N/A', $user->mobile_number,$user->home_city ?? 'N/A']);
                }

                fclose($handle);
            }, 200, $headers);
        }

        if ($format == 'excel') {    
//dd("here");
          

try{
//echo "<pre>";

$formatted_data = Helpers::format_export_data($users,'customer_list');

//print_r($formated_data);
//die;

/*
$formatted_data = array(
    array(
        '#' => 1,
        'Name' => 'Ahmed',
        'Email' => 'N/A',
        'Phone' => '+919741655376',
        'Home City' => 'Bangalore',
    ),
    array(
        '#' => 2,
        'Name' => 'Ankit Bhagat',
        'Email' => 'N/A',
        'Phone' => '+918092000108',
        'Home City' => 'Giridih',
    ),
);

*/

// Create a temporary file path
$filePath = 'exports/customer_list.xlsx';

// Generate the Excel file and store it temporarily
(new FastExcel($formatted_data))->export(storage_path("app/public/{$filePath}"));

// Return the file as a download response
return response()->download(storage_path("app/public/{$filePath}"))->deleteFileAfterSend(true);


//print_r($formated_data);
//die;


//dd("here");
 // return (new FastExcel($formated_data));

}catch(Exception $e){

//dd("here");
//print_r($e->getMessage());  
        return response()->json(['error' => $e->getMessage()], 400);


}
  
        }

        // If the requested format is not supported or not specified, return an error response
        return response()->json(['error' => 'Unsupported format'], 400);
    }


    public function exportRefereedList(Request $request){
        
        // Get all users data   

        $users  =  User::select('id', 'name', 'referral_code', 'referred_code','mobile_number')
        ->whereNotNull('referred_code')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('users as referrer')
                ->whereColumn('referrer.referral_code', '=', 'users.referred_code');
        })->get();

        // Check the requested format (pdf or csv)
        $format = $request->query('format');
        

        // Export to PDF format
        if ($format === 'pdf') {
            $pdf = PDF::loadView('admin-views.customer.refer-list-pdf',compact('users'));
            return $pdf->download('referred_list.pdf');
        }

        // Export to CSV format
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="referred_list.csv"',
            ];

            // Using Symfony's StreamedResponse to efficiently stream large CSV files
            return response()->stream(function () use ($users) {
              
                $handle = fopen('php://output', 'w');
                // Write CSV headers
                fputcsv($handle, ['#','Refer By', 'Refer To']);

                // Write CSV rows
                foreach ($users as $k => $user) {
                    $refer_to_mobile_number = $user['mobile_number'] ?? 'N/A';
                    $referrer = \APP\Models\User::select('id', 'name','mobile_number')
                    ->where('referral_code', $user->referred_code)
                    ->first();
                    fputcsv($handle, [$k+1, $referrer->name. " (" .$referrer->mobile_number . ")" , ($user['name'] ?? 'N/A') . " (" . $refer_to_mobile_number  .  ")"]);
                }

                fclose($handle);
            }, 200, $headers);
        }

        if ($format == 'excel') {    

            return (new FastExcel(Helpers::format_export_data($users,'refereed_list')))->download('referred_list.xlsx');


        }

        // If the requested format is not supported or not specified, return an error response
        return response()->json(['error' => 'Unsupported format'], 400);
    }

    

}
