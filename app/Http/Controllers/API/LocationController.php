<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Location;
use App\Models\Zone;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocationController extends Controller
{


    public function index(Request $request)
     {
         try {            
        
             // get coupon data 


             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $userId = (isset($user) && !empty($user)) ? $user->id : '';
             
             $locationData = Location::where(['user_id' => $userId])->first();            

             if (isset($locationData) && !empty($locationData)) {
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found','data' => $locationData], 404);
             } else {
                 return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No data found'], 404);
             }
         } catch (\Exception $e) {
             // Handle exceptions, if any
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()], 500);
         }
     }



    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function manageLocationData(Request $request)
    {
        // Try to update customer details
        try {
            // Get customer_id from the token
            
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = (isset($user) && !empty($user)) ? $user->id : '';


            $locationName = $request->post('location_name');  
            $latitude = $request->post('latitude');  
            $longitude = $request->post('longitude');  
            

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'location_name' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',

            ], [
                'location_name.required' => 'Please select a location.',
                'latitude.required' => 'Please enter a latitude.',
                'longitude.required' => 'Please enter a longitude.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            $locationData = Location::where('user_id', $userId)->first();

            if ($locationData) {
                // Location exists, update its fields
                $locationData->location_name = $locationName;
                $locationData->latitude = $latitude;
                $locationData->longitude = $longitude;
                $locationData->save();
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Location updated sucessfully' ]);
            } else {
                // Location does not exist, create a new one
                $locationData = new Location();
                $locationData->user_id = $userId;
                $locationData->location_name = $locationName;
                $locationData->latitude = $latitude;
                $locationData->longitude = $longitude;
                $locationData->save();
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Location added sucessfully' ]);
            }
         
            
            
            
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function matchLocationDataold(Request $request){
    $latitudeToCheck = $request->post('latitude');  
    $longitudeToCheck = $request->post('longitude');  
    
    $zoneData = Zone::where('status',1)->get();
    
    if(isset($zoneData) && !empty($zoneData)){
        foreach($zoneData as $key => $value){
            $coordinates = $value->coordinates;
            // Initialize a flag to track if the given coordinates fall within the range
            $withinRange = false;
            
            // Parse coordinates into an array of latitude-longitude pairs
            $coordinatePairs = explode(",", $coordinates);
            
            // Iterate through each coordinate pair
            foreach ($coordinatePairs as $coordinatePair) {
                // Extract latitude and longitude from the coordinate pair
                $coordinates = explode(" ", $coordinatePair);
                $latitude = str_replace("(", "", $coordinates[1]);
                $longitude = str_replace("(", "", $coordinates[0]);
                
                // Check if the given latitude and longitude fall within the range
                if ($latitudeToCheck >= $latitude && $longitudeToCheck >= $longitude) {
                   /*echo "latitude".$latitude;
                    echo "latitudeToCheck".$latitudeToCheck;
                    echo "longitude".$longitude;
                    echo "longitudeToCheck".$longitudeToCheck;*/
                    $withinRange = true;
                    break; // No need to continue iteration if within range
                }
            }
            
            // Check if the given coordinates fall within the range
            if ($withinRange) {
                return response()->json(['status' => 'success','message' => 'Given coordinates are within the range.', 'code' => 200]);
            } else {
                return response()->json(['status' => 'error','message' => 'Given coordinates are not within the range.', 'code' => 200]);
            }
            
            /*   if ($isInside) {
                 return response()->json(['status' => 'error','message' => 'Inside Zone', 'code' => 200]);
                 } else {
                 return response()->json(['status' => 'error','message' => 'Out of zone', 'code' => 200]);
                 }*/
        }
    }
}

public function matchLocationData(Request $request)
{
      // Try to update customer details
        try {
             $latitudeToCheck = $request->post('latitude');  
    $longitudeToCheck = $request->post('longitude');  

    $zoneData = Zone::where('status', 1)->get();
    $vertices_x = array();
    $vertices_y = array();
    if(isset($zoneData) && !empty($zoneData)){
        foreach($zoneData as $key => $value){
            $coordinates = $value->coordinates;
            $coordinates = trim($coordinates, "()"); // Remove parentheses from start and end
            $coordinatePairs = explode(",", $coordinates);
            
      
            
            foreach ($coordinatePairs as $pair) {
                list($longitude, $latitude) = explode(" ", $pair);
                array_push($vertices_x,(float) $longitude);
                 array_push($vertices_y,(float) $latitude);
               
               }

            $points_polygon = count($vertices_x);  // number vertices = number of points in a self-closing polygon
            $longitude_x = $longitudeToCheck;  // x-coordinate of the point to test
            $latitude_y = $latitudeToCheck;    // y-coordinate of the point to test
          
    
            
        }
    }
      
         
    if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                return response()->json(['status' => 'success', 'message' => 'Given coordinates are within the range.', 'code' => 200]);
    } else {
                return response()->json(['status' => 'error', 'message' => 'Given coordinates are not within the range.', 'code' => 200]);
    }
            
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' =>  $e->getMessage()]);
        }
   
    
}





private function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $c = false;
  $j = $points_polygon - 1;

  for ($i = 0; $i < $points_polygon; $i++) {
    if (($vertices_y[$i] > $latitude_y) != ($vertices_y[$j] > $latitude_y) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
      $c = !$c;
    }
    $j = $i;
  }
  return $c;
}




}


