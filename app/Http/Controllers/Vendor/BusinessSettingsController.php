<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Currency;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;

class BusinessSettingsController extends Controller
{

    private $vendor;

    public function restaurant_index()
    {
        $vendor = Helpers::get_restaurant_data();
        return view('vendor-views.business-settings.restaurant-index', compact('vendor'));
    }

    public function restaurant_setup(Vendor $vendor, Request $request)
    {

        // dd(33);
        // $request->validate([
        //     'gst' => 'required_if:gst_status,1',
        // ], [
        //     'gst.required_if' => trans('messages.gst_can_not_be_empty'),
        // ]);

        // dd($request->minimum_order);

        $vendor = Vendor::find(auth('vendor')->id());
        $off_day = $request->off_day?implode('',$request->off_day):'';
        $vendor->minimum_order = $request->minimum_order;
        $vendor->opening_time = $request->opening_time;
        $vendor->closeing_time = $request->closeing_time;
        $vendor->off_day = $off_day;
        // $vendor->gst = json_encode(['status'=>$request->gst_status, 'code'=>$request->gst]);
        $vendor->delivery_charge = $vendor->self_delivery_system?$request->delivery_charge??0: $vendor->delivery_charge;
        $vendor->save();
        Toastr::success(trans('messages.restaurant_settings_updated'));
        return back();
    }

    public function restaurant_status(Vendor $vendor, Request $request)
    {

        // if((($request->menu == "delivery" && $vendor->take_away==0) || ($request->menu == "take_away" && $vendor->delivery==0)) &&  $request->status == 0 )
        // {
        //     Toastr::warning(trans('messages.can_not_disable_both_take_away_and_delivery'));
        //     return back();
        // }
        
        auth('vendor')->user()->update([$request->menu => $request->status]);

        // $vendor = Vendor::find(auth()->id());
        // $vendor[$request->menu] = $request->status;
        // $vendor->save();
        Toastr::success('Vendor settings updated!');
        return back();
    }

    public function active_status(Request $request)
    {
        $vendor = Helpers::get_restaurant_data();
        $vendor->active = $vendor->active?0:1;
        $vendor->save();
        return response()->json(['message' => $vendor->active?trans('messages.restaurant_opened'):trans('messages.restaurant_temporarily_closed')], 200);
    }
}
