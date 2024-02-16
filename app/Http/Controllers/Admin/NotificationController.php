<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class NotificationController extends Controller
{
    function index()
    {
        $notifications = Notification::latest()->paginate(config('default_pagination'));
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

     

        $notification = new Notification;
        $notification->title = $request->notification_title;
        $notification->description = $request->description;
        $notification->save();

    
        $userId = $request->target_user_id;
        $data = [
            'title' => $request->notification_title,
            'body' => $request->description
        ];

        if($userId != 'all'){
            
            $userData = User::where('id',$userId)->first();
            $userFcmToken = $userData->fcm_token;
            Helpers::sendPushNotificationToCustomer($data, $userFcmToken);

        } else {

            $allUserData = User::where('role_id', 2)->orderBy('id','desc')->get();
            if(isset($allUserData) && !empty($allUserData)){
                foreach ($allUserData as $user){
                    $userFcmToken = $user->fcm_token;
                    Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                }
            }
        }
        return response()->json([], 200);
    }

    public function edit($id)
    {
        $notification = Notification::find($id);
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

        $notification = Notification::find($id);

   

        $notification->title = $request->notification_title;
        $notification->description = $request->description;
        $notification->save();

       
        $userId = $request->target_user_id;
        $data = [
            'title' => $request->notification_title,
            'body' => $request->description
        ];

        if($userId != 'all'){
            
            $userData = User::where('id',$userId)->first();
            $userFcmToken = $userData->fcm_token;
            Helpers::sendPushNotificationToCustomer($data, $userFcmToken);

        } else {

            $allUserData = User::where('role_id', 2)->orderBy('id','desc')->get();
            if(isset($allUserData) && !empty($allUserData)){
                foreach ($allUserData as $user){
                    $userFcmToken = $user->fcm_token;
                    Helpers::sendPushNotificationToCustomer($data, $userFcmToken);
                }
            }
        }
        Toastr::success(trans('messages.notification').' '.trans('messages.updated_successfully'));
        return back();
    }


    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
    
        $notification->delete();
        Toastr::success(trans('messages.notification_deleted_successfully'));
        return back();
    }
}
