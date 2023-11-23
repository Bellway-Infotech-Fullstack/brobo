<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColoredImage;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Scopes\VendorScope;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id' , 0)->get();
      
        return view('admin-views.product.index', compact('categories'));
    }

    public function get_products()
    {
        $products = Product::where('status',1)->get();
       // echo "<pre>";
       // print_r($products);
        return $products;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required',
            'price' => 'required|numeric|min:.01',
            'discount' => 'required|numeric|min:0',
        ], [
            'name.required' => trans('messages.item_name_required'),
            'category_id.required' => trans('messages.category_required'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', 'Discount can not be more or equal to the price!');
        }

      /*  if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }*/

        $Product = new Product;
        $Product->name = $request->name;

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

        $Product->category_ids = json_encode($category);
        $Product->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $Product->description = $request->description;

      
        $Product->total_stock = $request->total_stock;
      
        $Product->price = $request->price;
      

        $img_names = [];
        $images = [];
     
       
        if (!empty($request->file('product_images'))) {
            foreach ($request->file('product_images') as $img) {
                $image_name = Helpers::upload('product/', 'png', $img);
                array_push($img_names, $image_name);
            }
            $images = $img_names;
        }
        $Product->image = Helpers::upload('product/', 'png', $request->file('image'));
       
        $Product->images = $images;

     
        
        $Product->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $Product->discount_type = $request->discount_type;
        $Product->save();

        $productId = $Product->id;

        $productColoredImage = new ProductColoredImage;
        $colored_images = [];
        $colored_img_names = [];

        if(count($request->input('colored_name')) > 0){
            for($i = 0; $i < count($request->input('colored_name')); $i++){
                if(!empty($request->input('colored_name')[$i]) && !empty($request->file('colored_image')[$i])){
                     $productColoredImage = new ProductColoredImage();
                    $productColoredImage->product_id = $productId;
                    $productColoredImage->color_name = $request->input('colored_name')[$i];           
                    $productColoredImage->image = Helpers::upload('product/colored_images/', 'png', $request->file('colored_image')[$i]);
                    $productColoredImages = $request->product_colored_images[$i];
                 
                    if (!empty($productColoredImages)) {
                        $existingImages = array(); // Assuming $existingImages is the first array
                        $colored_img_names = array(); // Initialize the array to store processed images
                    
                        foreach ($productColoredImages as $img2) {
                            // Process each image
                            $color_image_name = Helpers::upload('product/colored_images/', 'png', $img2);
                    
                            // Check if $color_image_name is not in $existingImages
                            if (!in_array($color_image_name, $existingImages)) {
                                array_push($colored_img_names, $color_image_name);
                                // Update $existingImages with the new image
                                $existingImages[] = $color_image_name;
                            }
                        }
                    
                   
                    
                        $productColoredImage->images = $colored_img_names;
                        $productColoredImage->save();
                    }                 
                }
               
            }
        }
           
      
        return response()->json([], 200);
    }

    public function view($id)
    {
        $product = Product::where(['id' => $id])->first();
        return view('admin-views.product.view', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::withoutGlobalScope(RestaurantScope::class)->find($id);
        if(!$product)
        {
            Toastr::error(trans('messages.Product').' '.trans('messages.not_found'));
            return back();
        }
        $product_color_image_data = ProductColoredImage::where('product_id',$id)->get()->toArray();
        $product_color_images = [];
        $all_product_colored_images = [];
        if(isset($product_color_image_data) && !empty($product_color_image_data)){
            foreach ($product_color_image_data as $key => $val){
                array_push($product_color_images,$val['images']);
                
            }
            $all_product_colored_images = $product_color_images[0];
        }
     

       
     
        $product_category = json_decode($product->category_ids);
        $product_category_id = $product->category_id;
        $product_category_data = Category::where(['id' => $product_category_id])->first();
        $product_category_parent_id = $product_category_data->parent_id;
     
        $categories = Category::where(['parent_id' => 0])->get();
        return view('admin-views.product.edit', compact('product', 'product_category', 'categories','product_color_image_data','product_category_parent_id'));
    }

    public function status(Request $request)
    {
        $product = Product::withoutGlobalScope(RestaurantScope::class)->find($request->id);
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

        $p = Product::withoutGlobalScope(RestaurantScope::class)->find($id);

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

        $p->total_stock = $request->total_stock;

        $item_images = $p['images'];
        $images = [];

     
        

        if ($request->has('product_images')){
            foreach ($request->product_images as $img) {
                $image = Helpers::upload('product/', 'png', $img);
                array_push($images, $image);
            }
        } 

  

        $p->images = array_merge($images,$item_images);

        $img_names = [];
        //combinations end

        $p->price = $request->price;
        $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
   

        $p->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $p->discount_type = $request->discount_type;

      //  $productColoredImage = new ProductColoredImage;
        $colored_img_names = [];
        $colored_images = [];
        $colored_img_names_arr = [];
        if(count($request->input('colored_name')) > 0){
            $all_item_colored_images = array();
            for($i = 0; $i < count($request->input('colored_name')); $i++){ 
                $colored_image_id = isset($request->input('colored_image_id')[$i]) ? $request->input('colored_image_id')[$i] : '';
                if(isset($colored_image_id) && !empty($colored_image_id)){
                    $pci = ProductColoredImage::find($colored_image_id);
                    $item_colored_images = $pci['images'];
                    array_push($all_item_colored_images,$item_colored_images);
                } else {
                    $item_colored_images = array();

                }
                
            }
                    if(count($item_colored_images) > 0) {
                     
                       


                       
                        for($i = 0; $i < count($request->input('colored_name')); $i++){  
                       
                            $productColoredImages1 = isset($request->product_colored_images[$i]) && !empty($request->product_colored_images[$i]) ? $request->product_colored_images[$i] : [];
                          
                        if (!empty($productColoredImages1)) {
                            $existingImages = array(); // Assuming $existingImages is the first array
                            $colored_img_names = array(); // Initialize the array to store processed images
                        
                            foreach ($productColoredImages1 as $img21) {
                                // Process each image
                                $color_image_name = Helpers::upload('product/colored_images/', 'png', $img21);
                        
                                // Check if $color_image_name is not in $existingImages
                                if (!in_array($color_image_name, $existingImages)) {
                                    array_push($colored_img_names, $color_image_name);
                                    // Update $existingImages with the new image
                                    $existingImages[] = $color_image_name;
                                }
                            }
                        
                       
                           
                            $pci->images = array_merge($colored_img_names,$item_colored_images);
                        }    
    
                       
                
                    
                        
                        $pci->color_name = $request->input('colored_name')[$i];
                        if(isset($request->file('colored_image')[$i]) && !empty($request->file('colored_image')[$i])){
                            $pci->image = Helpers::upload('product/colored_images/', 'png', $request->file('colored_image')[$i]);
                        }
                        
                       
                        $pci->save();


                        
                    }
                        
                    } else {

                        for($i = 0; $i < count($request->input('colored_name')); $i++){  


                
                        $productColoredImage = new ProductColoredImage();
                        $productColoredImage->product_id = $id;
                        $productColoredImage->color_name = $request->input('colored_name')[$i];     
                        
                        $cim = (isset($request->file('colored_image')[$i]) && !empty($request->file('colored_image')[$i])) ? $request->file('colored_image')[$i] : array();
                        $productColoredImage->image = Helpers::upload('product/colored_images/', 'png', $cim);
                        $pcims = (isset($request->file('product_colored_images')[$i]) && !empty($request->file('product_colored_images')[$i])) ? $request->file('product_colored_images')[$i] : array();

                        $productColoredImages = $pcims;
                    
                        if (!empty($productColoredImages)) {
                            $existingImages2 = array(); // Assuming $existingImages is the first array
                            $colored_img_names_arr = array(); // Initialize the array to store processed images
                        
                            foreach ($productColoredImages as $img2) {
                                // Process each image
                                $color_image_name = Helpers::upload('product/colored_images/', 'png', $img2);
                        
                                // Check if $color_image_name is not in $existingImages
                                if (!in_array($color_image_name, $existingImages2)) {
                                    array_push($colored_img_names_arr, $color_image_name);
                                    // Update $existingImages with the new image
                                    $existingImages2[] = $color_image_name;
                                }
                            }
                        
                    
                 
                            $productColoredImage->images = $colored_img_names_arr;
                            $productColoredImage->save();
                        }                 
                    }
                    }
                  
             
        }

        $p->save();
        

        return response()->json([], 200);
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);

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

    public function remove_image(Request $request)
    {
        if (Storage::disk('public')->exists('product/' . $request['name'])) {
            Storage::disk('public')->delete('product/' . $request['name']);
        }
        $item = Product::withoutGlobalScope(StoreScope::class)->find($request['id']);
        $array = [];
        /*if (count($item['images']) < 2) {
            Toastr::warning(__('all_image_delete_warning'));
            return back();
        }*/
        foreach ($item['images'] as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        Product::withoutGlobalScope(StoreScope::class)->where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        Toastr::success("Product image removed successfully");
        return back();
    }
   
    public function remove_color_image(Request $request)
    {
        if (Storage::disk('public')->exists('product/' . $request['name'])) {
            Storage::disk('public')->delete('product/' . $request['name']);
        }
        $item = ProductColoredImage::find($request['id']);
        $array = [];
        /*if (count($item['images']) < 2) {
            Toastr::warning(__('all_image_delete_warning'));
            return back();
        }*/
        foreach ($item['images'] as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        ProductColoredImage::where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        Toastr::success("Image removed successfully");
        return back();
    }

   
    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="">---Select---</option>';
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

  

    public function list(Request $request)
    {
      
        $category_id = $request->query('category_id', 'all');
        $products = Product::withoutGlobalScope(RestaurantScope::class)
       
        ->when(is_numeric($category_id), function($query)use($category_id){
            return $query->whereHas('category',function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })->latest()->paginate(config('default_pagination'));
        $category =$category_id !='all'? Category::findOrFail($category_id):null;
        return view('admin-views.product.list', compact('products','category'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $Products=Product::withoutGlobalScope(RestaurantScope::class)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json(['count'=>count($Products),
            'view'=>view('admin-views.product.partials._table',compact('Products'))->render()
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
        DB::table('Products')->insert($data);
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
        $products = Product::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->withoutGlobalScope(VendorScope::class)->get();


        return (new FastExcel(ProductLogic::format_export_foods($products)))->download('Products.xlsx');
    }
}
