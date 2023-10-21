<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorNotification;

class NotificationController extends Controller
{

    public function index(){

        $notifications = VendorNotification::where('vendor_id', auth('vendor')->id())->orderBy('id', 'desc')->paginate(10);

        return view('vendor-views.notification.index', compact('notifications'));

    }

    public function show($id){
        $notification = VendorNotification::where('vendor_id', auth('vendor')->id())->where('id', $id)->firstOrFail();
        $notification->update(['is_seen' => 1]);

        return view('vendor-views.notification.show', compact('notification'));
    }
    
}
