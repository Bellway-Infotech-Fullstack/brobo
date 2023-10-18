<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use App\Models\Restaurant;
use App\Models\Vendor;
use App\Models\Review;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Scopes\VendorScope;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = Category::where(['position' => 0])->get();
        $vendors = Vendor::all();
        return view('admin-views.product.index', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required',
            'price' => 'required|numeric|min:.01',
            'discount' => 'required|numeric|min:0',
            'vendor_id' => 'required',
            'description' => 'max:1000',
            // 'veg'=>'required'
        ], [
            'name.required' => trans('messages.item_name_required'),
            'category_id.required' => trans('messages.category_required'),
            // 'veg.required'=>trans('messages.item_type_is_required')
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', 'Discount can not be more or equal to the price!');
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $service = new Service;
        $service->name = $request->name;

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $service->category_ids = json_encode($category);
        $service->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $service->description = $request->description;

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                if ($request[$str][0] == null) {
                    $validator->getMessageBag()->add('name', 'Attribute choice option values can not be null!');
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
                array_push($choice_options, $item);
            }
        }
        $service->choice_options = json_encode($choice_options);
        $variations = [];
        $options = [];
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($request['price_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }
        //combinations end
        $service->variations = json_encode($variations);
        $service->price = $request->price;
        $service->image = Helpers::upload('product/', 'png', $request->file('image'));
        $service->available_time_starts = $request->available_time_starts;
        $service->available_time_ends = $request->available_time_ends;
        $service->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $service->discount_type = $request->discount_type;

        // $service->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        // $service->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $service->vendor_id = $request->vendor_id;
        $service->attributes = json_encode([]);
        $service->add_ons = json_encode([]);
        // $service->veg = $request->veg;
        $service->save();
        // return back();
        return response()->json([], 200);
    }

    public function view($id)
    {
        $product = Service::withoutGlobalScope(RestaurantScope::class)->where(['id' => $id])->first();
        $reviews=Review::where(['service_id'=>$id])->latest()->paginate(config('default_pagination'));
        return view('admin-views.product.view', compact('product','reviews'));
    }

    public function edit($id)
    {
        $product = Service::withoutGlobalScope(RestaurantScope::class)->find($id);
        if(!$product)
        {
            Toastr::error(trans('messages.service').' '.trans('messages.not_found'));
            return back();
        }
        $product_category = json_decode($product->category_ids);
        $categories = Category::where(['parent_id' => 0])->get();
        $vendors = Vendor::all();
        return view('admin-views.product.edit', compact('vendors','product', 'product_category', 'categories'));
    }

    public function status(Request $request)
    {
        $product = Service::withoutGlobalScope(RestaurantScope::class)->find($request->id);
        $product->status = $request->status;
        $product->save();
        Toastr::success(trans('messages.food_status_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:.01',
            'vendor_id' => 'required',
            // 'veg' => 'required',
            'description' => 'max:1000',
            'discount' => 'required|numeric|min:0',
        ], [
            'name.required' => trans('messages.item_name_required'),
            'category_id.required' => trans('messages.category_required'),
            // 'veg.required'=>trans('messages.item_type_is_required')
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', 'Discount can not be more or equal to the price!');
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $p = Service::withoutGlobalScope(RestaurantScope::class)->find($id);

        $p->name = $request['name'];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $p->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $p->category_ids = json_encode($category);
        $p->description = $request->description;

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                if ($request[$str][0] == null) {
                    $validator->getMessageBag()->add('name', 'Attribute choice option values can not be null!');
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
                array_push($choice_options, $item);
            }
        }
        $p->choice_options = json_encode($choice_options);
        $variations = [];
        $options = [];
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($request['price_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }
        //combinations end
        $p->variations = json_encode($variations);
        $p->price = $request->price;
        $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
        $p->available_time_starts = $request->available_time_starts;
        $p->available_time_ends = $request->available_time_ends;

        $p->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $p->discount_type = $request->discount_type;

        // $p->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        // $p->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $p->attributes = json_encode([]);
        $p->add_ons = json_encode([]);
        $p->vendor_id = $request->vendor_id;
        // $p->veg = $request->veg;
        $p->save();

        return response()->json([], 200);
    }

    public function delete(Request $request)
    {
        $product = Service::withoutGlobalScope(RestaurantScope::class)->find($request->id);

        if($product->image)
        {
            if (Storage::disk('public')->exists('product/' . $product['image'])) {
                Storage::disk('public')->delete('product/' . $product['image']);
            }
        }

        $product->delete();
        Toastr::success(trans('messages.product_deleted_successfully'));
        return back();
    }

    public function variant_combination(Request $request)
    {
        $options = [];
        $price = $request->price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $result = [[]];
        foreach ($options as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        $combinations = $result;
        return response()->json([
            'view' => view('admin-views.product.partials._variant-combinations', compact('combinations', 'price', 'product_name'))->render(),
        ]);
    }

    public function variant_price(Request $request)
    {
        if($request->item_type=='food')
        {
            $product = Service::withoutGlobalScope(RestaurantScope::class)->find($request->id);
        }
        else
        {
            $product = ItemCampaign::find($request->id);
        }
        // $product = Service::withoutGlobalScope(RestaurantScope::class)->find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;
        $addon_price = 0;

        foreach (json_decode($product->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if($request['addon_id'])
        {
            foreach($request['addon_id'] as $id)
            {
                $addon_price+= $request['addon-price'.$id]*$request['addon-quantity'.$id];
            } 
        }

        if ($str != null) {
            $count = count(json_decode($product->variations));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variations)[$i]->type == $str) {
                    $price = json_decode($product->variations)[$i]->price - Helpers::product_discount_calculate($product, json_decode($product->variations)[$i]->price,$product->restaurant);
                }
            }
        } else {
            $price = $product->price - Helpers::product_discount_calculate($product, $product->price,$product->restaurant);
        }

        return array('price' => Helpers::format_currency(($price * $request->quantity)+$addon_price));
    }
    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function get_foods(Request $request)
    {
        $services = Service::with('vendor')->whereHas('vendor', function($query)use($request){
            $query->where('zone_id', $request->zone_id);
        })->get();
        $res = '';
        if(count($services)>0 && !$request->data)
        {
            $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        }

        foreach ($services as $row) {
            $res .= '<option value="'.$row->id.'" ';
            if($request->data)
            {
                $res .= in_array($row->id, $request->data)?'selected ':'';
            }
            $res .= '>'.$row->name.' ('.$row->vendor->names().')'. '</option>';
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function list(Request $request)
    {
        $vendor_id = $request->query('vendor_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $type = $request->query('type', 'all');
        $services = Service::withoutGlobalScope(RestaurantScope::class)
        ->when(is_numeric($vendor_id), function($query)use($vendor_id){
            return $query->where('vendor_id', $vendor_id);
        })
        ->when(is_numeric($category_id), function($query)use($category_id){
            return $query->whereHas('category',function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->type($type)
        ->latest()->paginate(config('default_pagination'));
        $restaurant =$vendor_id !='all'? Restaurant::findOrFail($vendor_id):null;
        $category =$category_id !='all'? Category::findOrFail($category_id):null;
        return view('admin-views.product.list', compact('services','restaurant','category', 'type'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $services=Service::withoutGlobalScope(RestaurantScope::class)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json(['count'=>count($services),
            'view'=>view('admin-views.product.partials._table',compact('services'))->render()
        ]);
    }

    public function reviews_status(Request $request)
    {
        $review = Review::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(trans('messages.review_visibility_updated'));
        return back();
    }

    public function bulk_import_index()
    {
        return view('admin-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(trans('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        $data = [];
        $skip = ['youtube_video_url'];
        foreach ($collections as $collection) {
                if ($collection['name'] === "" || $collection['category_id'] === "" || $collection['sub_category_id'] === "" || $collection['price'] === "" || empty($collection['available_time_starts']) === "" || empty($collection['available_time_ends']) || $collection['vendor_id'] === "") {
                    Toastr::error(trans('messages.please_fill_all_required_fields'));
                    return back();
                }


            array_push($data, [
                'name' => $collection['name'],
                'category_id' => $collection['sub_category_id']?$collection['sub_category_id']:$collection['category_id'],
                'category_ids' => json_encode([['id' => $collection['category_id'], 'position' => 0], ['id' => $collection['sub_category_id'], 'position' => 1]]),
                // 'set_menu' => 0,  //$request->item_type;
                'price' => $collection['price'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'description' => $collection['description'],
                'available_time_starts' => $collection['available_time_starts'],
                'available_time_ends' => $collection['available_time_ends'],
                'image' => $collection['image'],
                'vendor_id' => $collection['vendor_id'],
                'add_ons' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variations' => json_encode([]),
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }
        DB::table('services')->insert($data);
        Toastr::success(trans('messages.product_imported_successfully', ['count'=>count($data)]));
        return back();
    }

    public function bulk_export_index()
    {
        return view('admin-views.product.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        $request->validate([
            'type'=>'required',
            'start_id'=>'required_if:type,id_wise',
            'end_id'=>'required_if:type,id_wise',
            'from_date'=>'required_if:type,date_wise',
            'to_date'=>'required_if:type,date_wise'
        ]);
        $products = Service::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->withoutGlobalScope(VendorScope::class)->get();


        return (new FastExcel(ProductLogic::format_export_foods($products)))->download('services.xlsx');
    }
}
