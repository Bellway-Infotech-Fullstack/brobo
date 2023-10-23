<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

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
                 return response()->json(['status' => 'success', 'code' => 200,'data' => $bannerData,
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
