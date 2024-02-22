<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    function index()
    {
        $notifications = AdminNotification::latest()->paginate(config('default_pagination'));
        return view('admin-views.notification.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_title' => 'required',
            'description' => 'required',
        ], [
            'notification_title.required' => 'Title is required!',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

     
        $userId = $request->target_user_id;

        $notification = new AdminNotification;
        $notification->title = $request->notification_title;
        $notification->description = $request->description;
        $notification->user_ids = ($userId == 'all') ? 'all' : implode(",",$userId);
        $notification->save();

    
        
        $data = [
            'title' => $request->notification_title,
            'body' => $request->description
        ];

        $adminData = User::where('role_id',1)->first();

        if($userId != 'all'){

           
            $all_user_id = $userId;
            foreach ($all_user_id as $user){
                $userData = User::where('id',$user)->first();
                $userFcmToken = $userData->fcm_token;
                Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                DB::table('notifications')->insert([
                    'title'        =>   $request->notification_title,
                    'description'  =>  $request->description,
                    'from_user_id' =>  $adminData->id,
                    'to_user_id'    => $user,
                    'created_at'    => now(),
                    'updated_at'   =>  now()
                ]);

            }
            
          

        } else {

            $allUserData = User::where('role_id', 2)->orderBy('id','desc')->get();
            

            if(isset($allUserData) && !empty($allUserData)){
                foreach ($allUserData as $user){
                    $userFcmToken = $user->fcm_token;
                    Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                    DB::table('notifications')->insert([
                        'title'        =>   $request->notification_title,
                        'description'  =>  $request->description,
                        'from_user_id' =>  $adminData->id,
                        'to_user_id'    => $user->id,
                        'created_at'    => now(),
                        'updated_at'   =>  now()
                    ]);
                }
            }
        }
        return response()->json([], 200);
    }

    public function edit($id)
    {
        $notification = AdminNotification::find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notification_title' => 'required',
            'description' => 'required',
        ], [
            'notification_title.required' => 'title is required!',
        ]);

        $notification = AdminNotification::find($id);

   
        $userId = $request->target_user_id;

     

        $notification->title = $request->notification_title;
        $notification->description = $request->description;
        $notification->user_ids = ($userId == 'all') ? 'all' : implode(",",$userId);
        $notification->save();

       
        
        $data = [
            'title' => $request->notification_title,
            'body' => $request->description
        ];

        $adminData = User::where('role_id',1)->first();

        if($userId != 'all'){
            
            $all_user_id = $userId;
            foreach ($all_user_id as $user){
                $userData = User::where('id',$user)->first();
                $userFcmToken = $userData->fcm_token;
                Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                DB::table('notifications')->insert([
                    'title'        =>   $request->notification_title,
                    'description'  =>  $request->description,
                    'from_user_id' =>  $adminData->id,
                    'to_user_id'    => $user,
                    'created_at'    => now(),
                    'updated_at'   =>  now()
                ]);

            }

        } else {

            $allUserData = User::where('role_id', 2)->orderBy('id','desc')->get();
            if(isset($allUserData) && !empty($allUserData)){
                foreach ($allUserData as $user){
                    $userFcmToken = $user->fcm_token;
                    Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                    DB::table('notifications')->insert([
                        'title'        =>   $request->notification_title,
                        'description'  =>  $request->description,
                        'from_user_id' =>  $adminData->id,
                        'to_user_id'    => $user->id,
                        'created_at'    => now(),
                        'updated_at'   =>  now()
                    ]);
                }
            }
        }
        Toastr::success(trans('messages.notification').' '.trans('messages.updated_successfully'));
        return back();
    }


    public function delete(Request $request)
    {
        $notification = AdminNotification::find($request->id);
    
        $notification->delete();
        Toastr::success(trans('messages.notification_deleted_successfully'));
        return back();
    }
}
