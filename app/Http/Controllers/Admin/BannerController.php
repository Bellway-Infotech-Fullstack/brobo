<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Vendor;
use App\Models\Service;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;

class BannerController extends Controller
{
    function index()
    {
        $banners = Banner::latest()->paginate(config('default_pagination'));
        $vendors = Vendor::all();
        $services = Service::all();
        return view('admin-views.banner.index', compact('banners', 'vendors', 'services'));
    }

    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required',
            'banner_type' => 'required',
            'zone_id' => 'required',
            'vendor_id' => 'required_if:banner_type,vendor_wise',
            'service_id' => 'required_if:banner_type,service_wise',
        ], [
            'title.required' => 'Title is required!',
            'zone_id.required' => 'Zone is required!',
            'vendor_id.required_if'=> "Vendor is required when banner type is Vendor wise",
            'service_id.required_if'=> "Service is required when banner type is Service wise",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $banner = new Banner;
        $banner->title = $request->title;
        $banner->type = $request->banner_type;
        $banner->zone_id = $request->zone_id;
        $banner->image = Helpers::upload('banner/', 'png', $request->file('image'));
        $banner->data = ($request->banner_type == 'vendor_wise')?$request->vendor_id:$request->service_id;
        $banner->save();
 
        return response()->json([], 200);
    }

    public function edit(Banner $banner)
    {
        $vendors = Vendor::all();
        $services = Service::all();

        return view('admin-views.banner.edit', compact('banner', 'vendors', 'services'));
    }

    // public function view(Banner $banner)
    // {
    //     $restaurant_ids = json_decode($banner->restaurant_ids);
    //     $restaurants = Vendor::whereIn('id', $restaurant_ids)->paginate(10);
    //     return view('admin-views.banner.view', compact('banner', 'restaurants', 'restaurant_ids'));
    // }

    public function status(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->status = $request->status;
        $banner->save();
        Toastr::success(trans('messages.banner_status_updated'));
        return back();
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'banner_type' => 'required',
            'zone_id' => 'required',
            'vendor_id' => 'required_if:banner_type,vendor_wise',
            'service_id' => 'required_if:banner_type,service_wise',
        ], [
            'title.required' => 'Title is required!',
            'zone_id.required' => 'Zone is required!',
            'vendor_id.required_if'=> "Vendor is required when banner type is Vendor wise",
            'service_id.required_if'=> "Service is required when banner type is Service wise",
        ]);

   
        $banner->title = $request->title;
        $banner->type = $request->banner_type;
        $banner->zone_id = $request->zone_id;
        $banner->image = $request->has('image') ? Helpers::update('banner/', $banner->image, 'png', $request->file('image')) : $banner->image;
        $banner->data = ($request->banner_type == 'vendor_wise')?$request->vendor_id:$request->service_id;
        $banner->save();
        Toastr::success(trans('messages.banner_updated_successfully'));
        return redirect('admin/banner/add-new');
    }

    public function delete(Banner $banner)
    {
        if (Storage::disk('public')->exists('banner/' . $banner['image'])) {
            Storage::disk('public')->delete('banner/' . $banner['image']);
        }
        $banner->delete();
        Toastr::success(trans('messages.banner_beleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $banners=Banner::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.banner.partials._table',compact('banners'))->render(),
            'count'=>$banners->count()
        ]);
    }
}
