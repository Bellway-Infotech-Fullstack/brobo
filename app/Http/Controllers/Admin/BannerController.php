<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;

class BannerController extends Controller
{
    function index()
    {
        $products = Product::where('status', '1')->orderBy('id','desc')->get();
        $banners = Banner::latest()->paginate(config('default_pagination'));
        return view('admin-views.banner.index', compact('banners','products'));
    }

    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ], [
            'image.required_if'=> "Iage is required!",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $banner = new Banner;
        $banner->image = Helpers::upload('banner/', 'png', $request->file('image'));
        $banner->save();
 
        return response()->json([], 200);
    }

    public function edit(Banner $banner)
    {
        $banner = Banner::find($banner->id);
        return view('admin-views.banner.edit', compact('banner'));
    }

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
       $banner->image = $request->has('image') ?  Helpers::upload('banner/', 'png', $request->file('image')) : $banner->image;
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
