<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FAQController extends Controller
{
    //

    /**
     * get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         try {            
        
             // get faq data              
             $faqData = Faq::where(['status' => 1])->orderBy('created_at', 'desc')->get();           

            return response()->json(['status' => 'success', 'code' => 200,'data' => $faqData]);
             
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }
}
