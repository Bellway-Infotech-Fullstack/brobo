<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Notification;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Services\FCMService;
use App\Models\Order;
use App\Models\User;
use App\Models\BusinessSetting;
//include 'cron_job.php';


class NotificationController extends Controller
{
    //

     /**
     * It will get all notifications .
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request){
        try {
            
             
             $token = JWTAuth::getToken();
             $user = JWTAuth::toUser($token);
             $customerId = (isset($user) && !empty($user)) ? $user->id : '';

            // Get requested data

             $page = $request->get('page');
             $orderBy =  'desc';
             $orderColumn = 'created_at';
             $perPage = 10; // Number of items to load per page


             $loginUserData = User::find($customerId);


             
           
            
             // Define the validation rules
             $validationRules = [
                 'page' => 'required',
             ]; 
         
             // Validate the input data
             $validation = Validator::make($request->all(), $validationRules, [
                 'page.required' => 'page is required.',
             ]);
             
 
             // Check for validation errors and return error response if any
             if ($validation->fails()) {
                 return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
             }


             if($loginUserData->is_notification_setting_on == "no"){
                $notificationOffTime = $loginUserData->notification_off_time; 
                $data = Notification::where('to_user_id', $customerId)
                ->where('created_at', '<', $notificationOffTime)
                ->orderBy($orderColumn, $orderBy)
                ->paginate($perPage, ['*'], 'page', $page);
             }
             else {
                $data = Notification::where('to_user_id', $customerId)
                ->orderBy($orderColumn, $orderBy)
                ->paginate($perPage, ['*'], 'page', $page);
             }
            
     
        
                
              

            $data = $data->map(function ($notification) {
                
         

                if($notification->coupon_id == ''){
                $description = $notification->description;
                } else {

                    $couponData = Coupon::find($notification->coupon_id);    

                    if(isset($couponData) && !empty($couponData)){
                        $couponDiscountType = $couponData->discount_type;
                        $couponCode = $couponData->code;
        
                        if($couponDiscountType == "percentage"){
                            $couponDiscount = $couponData->discount. " %";
                        } else {
                            $couponDiscount = "Rs. ".$couponData->discount;
                        }
    
                        $description = "Get upto $couponDiscount off using code $couponCode";
                    } else {
                        $description = '';
                    }
                
             
                
                }

                
                $notification->description = $description;
                 // Calculate time ago
              //=  $notification->time_ago = Carbon::parse($notification->created_at)->diffForHumans();

                return $notification;
             });

            if(count($data) > 0){

                // read all notifications 

                Notification::where('to_user_id',$customerId)->update(['is_read'=>'1']);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $data]);
            }  else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'No Data found','data' => $data]);
            }
        } catch (\Exception $e) {
           
             return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
         }
    }

 
    public function index_new(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = ($user) ? $user->id : '';

            // Validate the input data
            $validator = Validator::make($request->all(), [
                'page' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' =>  'error', 'code' => 422, 'message' => $validator->errors()->first()]);
            }

            $loginUserData = User::find($customerId);

            $notificationsQuery = Notification::where('to_user_id', $customerId)
                ->when($loginUserData->is_notification_setting_on == "no", function ($query) use ($loginUserData) {
                    $notificationOffTime = $loginUserData->notification_off_time;
                    return $query->where('created_at', '<', $notificationOffTime);
                })
                ->orderBy('created_at', 'desc');

            $perPage = 10;
            $notifications = $notificationsQuery->paginate($perPage);

            $data = $notifications->map(function ($notification) {
                $couponDescription = '';

                if (isset($notification->coupon_id)) {
                    $coupon = Coupon::find($notification->coupon_id);

                    if ($coupon) {
                        $couponDiscountType = $coupon->discount_type;
                        $couponCode = $coupon->code;

                        $couponDiscount = ($couponDiscountType == "percentage") ? $coupon->discount . " %" : "Rs. " . $coupon->discount;

                        $couponDescription = "Get upto $couponDiscount off using code $couponCode";
                    }

                    $description = $couponDescription;
                } else {
                    $description = $notification->description;
                }


                return [
                    'description' => $description,
                    'created_at' => $notification->created_at,
                  // 'time_ago' => Carbon::parse($notification->created_at)->diffForHumans()
                    // Add other fields as needed
                ];
            });

            $response = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Data found successfully',
                'data' => [
                    'data' => $data,
                    'paginator' => [
                        'totalDocs' => $notifications->total(),
                        'limit' => $notifications->perPage(),
                        'page' => $notifications->currentPage(),
                        'totalPages' => $notifications->lastPage(),
                        'slNo' => $notifications->firstItem(),
                        'hasPrevPage' => $notifications->previousPageUrl() !== null,
                        'hasNextPage' => $notifications->nextPageUrl() !== null,
                        'prevPage' => $notifications->previousPageUrl(),
                        'nextPage' => $notifications->nextPageUrl(),
                    ]
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' =>  'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }
    

    



    public function getUnreadNotificationsCount(Request $request){
        try {            
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
            
            $count = Notification::where(array('to_user_id'=> $customerId,'is_read' =>'0'))->count();
            
        $data = ['notification_count' => $count]; // Corrected the syntax of the array

            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Data found successfully','data' => $data]);

        } catch (\Exception $e) {
           
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function sendNotificationOfPeningDueAmountOrder(Request $request){
         $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
           $customerId = 94;
           
           $customerData = User::find($customerId);
           
           $fcmToken = $customerData->fcm_token;
           
       $data =  sendNotificationOfPendingDueAmountOrder();
       // Filter notifications based on $customerId
        $filteredNotifications = array_filter($data, function ($notification) use ($customerId) {
            return $notification['user_id'] == $customerId;
        });
        
        // Convert the filtered array to indexed array
        $filteredNotifications = array_values($filteredNotifications);
        
        
      
        
        
        $this->sendPushNotifcation($fcmToken,$filteredNotifications);
        
       
        return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Notification send successfully']);
    }
    
    
    public function sendNotificationOfCompletedOrder(Request $request){
         $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = (isset($user) && !empty($user)) ? $user->id : '';
           $customerId = 94;
           
           $customerData = User::find($customerId);
           
           $fcmToken = $customerData->fcm_token;
           
       $data =  sendNotificationOfCompletedOrder();
       // Filter notifications based on $customerId
        $filteredNotifications = array_filter($data, function ($notification) use ($customerId) {
            return $notification['user_id'] == $customerId;
        });
        
        // Convert the filtered array to indexed array
        $filteredNotifications = array_values($filteredNotifications);
        
        
      
        
        
        $this->sendPushNotifcation($fcmToken,$filteredNotifications);
        
       
        return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Notification send successfully']);
    }


    private static function sendPushNotifcation($fcmToken, $data){
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );
          if(isset($data) && !empty($data)){
            foreach($data as $key => $value){
                $postdata = '{
            "to" : "' . $fcmToken . '",
            "mutable_content": true,
            "data" : {
                "title":"' . $value['title'] . '",
                "body" : "' . $value['body'] . '",
                "order_id":"' . $value['order_id'] . '",
                "is_read": 0
            },
            "notification" : {
                "title" :"' . $value['title'] . '",
                "body" : "' . $value['body'] . '",
                "order_id":"' . $value['order_id'] . '",
                "title_loc_key":"' . $value['order_id'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
            }
        }';
        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);
                
            }
        }
        // return $result;
    }


}


    
  


