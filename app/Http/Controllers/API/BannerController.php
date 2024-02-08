<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;


class BannerController extends Controller
{
    //

    /**
     * get all banners.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         try {            
        
             // get banner data 
             
             $bannerData   = Banner::where(['status' => 1])->get();
         
             
             if (count($bannerData) > 0) {
                 $data = [];   
                 foreach($bannerData as $key => $value){
                    $productData = Product::where('id' , $value->product_id)->first();
                    array_push($data,
                    array( 
                            'id' => $value->id,
                           'image' => (env('APP_ENV') == 'local') ?  asset('storage/banner/' . $value->image) : asset('storage/app/public/banner/' . $value->image), 
                           'product_id' => $value->product_id,
                           'status' => $value->status,
                           'created_at' => $value->created_at,
                           'updated_at'=> $value->updated_at
                        ));                
                 }

                 return response()->json(['status' => 'success', 'code' => 200,'data' => $data,
             ]);
             } else {
                 return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }
}
