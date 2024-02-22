<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    public function __construct()
    {
      //  $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function login()
    {      
      
       
        $settingData = BusinessSetting::where(['key'=>'logo'])->first();
        $systemLogo = (isset($settingData) && !empty($settingData)) ? $settingData->value : '';
       
        return view('admin-views.auth.login', compact('systemLogo'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        }

        /*if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('admin.dashboard');
        } else {
           
        }*/
        

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}
