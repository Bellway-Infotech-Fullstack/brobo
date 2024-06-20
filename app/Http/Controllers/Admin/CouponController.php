<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Models\User;

class CouponController extends Controller
{
    public function add_new()
    {
        
        $coupons = Coupon::latest()->paginate(config('default_pagination'));
        return view('admin-views.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons',
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:zone_wise,default',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'product_ids' => 'required_if:coupon_type,product_wise',
            'coupon_background_image' => 'mimes:jpeg,jpg,png,bmp,gif,svg,webp',

        ]);
        $data  = '';
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'product_wise')
        {
            $data = $request->product_ids;
        }


       


      

    

       $couponId =  DB::table('coupons')->insertGetId([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'background_image' => Helpers::upload('coupon_background_image/', 'png', $request->file('coupon_background_image')),
            'status' => 1,
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $customerData = User::where('role_id',2)->get();
        $adminData = User::where('role_id',1)->first();

        if(isset($customerData) && !empty($customerData)){
            foreach($customerData as $key => $value)
            {   
                DB::table('notifications')->insert([
                    'title' => "New offer",
                    'coupon_id' => $couponId,
                    'from_user_id' => 1,
                    'to_user_id' => $value->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
              
            }
        } 

        Toastr::success(trans('messages.coupon_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        $coupon = Coupon::where(['id' => $id])->first();
        // dd(json_decode($coupon->data));
        return view('admin-views.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$id,
            'title' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:zone_wise,default',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'product_ids' => 'required_if:coupon_type,product_wise',
            'coupon_background_image' => 'mimes:jpeg,jpg,png,bmp,gif,svg,webp',

        ]);
        $data  = '';
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'service_wise')
        {
            $data = $request->service_ids;
        }

        $couponData = Coupon::find($id);

       

        DB::table('coupons')->where(['id' => $id])->update([
            'title' => $request->title,
            'code' => $request->code,
            'limit' => $request->coupon_type=='first_order'?1:$request->limit,
            'coupon_type' => $request->coupon_type,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
            'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
            'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
            'discount_type' => $request->discount_type??'',
            'data' => json_encode($data),
            'background_image' => $request->file('coupon_background_image') ? Helpers::update('coupon_background_image/', $couponData->background_image, 'png', $request->file('coupon_background_image')) : $couponData->background_image,
            'updated_at' => now()
        ]);

        Toastr::success(trans('messages.coupon_updated_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success(trans('messages.coupon_status_updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->delete();
        Toastr::success(trans('messages.coupon_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $coupons=Coupon::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%")
                ->orWhere('code', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.coupon.partials._table',compact('coupons'))->render(),
            'count'=>$coupons->count()
        ]);
    }
}
