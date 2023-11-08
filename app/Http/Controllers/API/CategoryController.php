<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    //

     /**
     * get sub categories by parent_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function getAllSubCategories(Request $request)
     {
         try {
             // Get category_id from the request
             $categoryId = $request->category_id;
            
             // Validate the input data
             $validation = Validator::make($request->all(), [
                 'category_id' => 'required',
             ], [
                 'category_id.required' => 'Please enter a category id.',
             ]);

 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             } 

             // get sub category data 
             
             $subCategories   = Category::where(['parent_id' => $categoryId,'status' => 1])->get();
             
             if (count($subCategories) > 0) {
                 return response()->json(['status' => 'success', 'code' => 200,'data' => $subCategories,
             ]);
             } else {
                 return response()->json(['status' => 'error', 'code' => 200, 'message' => 'No data found'], 404);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }


     /**
     * get popular services.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */

     public function getPopularServices(Request $request)
{
    try {
        // Get popular service data
        $tentSubCategories = Category::where(['parent_id' => 1, 'status' => 1])->orderBy('created_at','desc')->get();
        $floarlSubCategories = Category::where(['parent_id' => 2, 'status' => 1])->orderBy('created_at','desc')->get();

        if (count($tentSubCategories) > 0 || count($floarlSubCategories) > 0) {
            // Merge the tentSubCategories and floarlSubCategories collections
            $mergedData = $floarlSubCategories->merge($tentSubCategories);

            // Map the image paths for each category
            $mergedData = $mergedData->map(function ($category) {
                $imagePath = (env('APP_ENV') == 'local') ? asset('storage/category/' . $category->image) : asset('storage/app/public/category/' . $category->image);

                $category->image = $imagePath;

                return $category;
            });

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $mergedData,
            ]);
        } else {
            // Handle the case when no data found.
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => [], // Return an empty array if no data is found
            ]);
        }
    } catch (\Exception $e) {
        // Handle exceptions, if any
        return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
    }
}

}
