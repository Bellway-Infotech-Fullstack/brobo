<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function view()
    {
        return view('vendor-views.profile.index');
    }
    
    public function bank_view()
    {
        $data = Vendor::where('id', Helpers::get_restaurant_id())->first();
        return view('vendor-views.profile.bankView', compact('data'));
    }

    public function edit()
    {
        $data = Vendor::where('id',Helpers::get_restaurant_id())->first();
        return view('vendor-views.profile.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'email' => 'required|unique:vendors,email,'.Helpers::get_vendor_id(),
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:vendors,phone,'.Helpers::get_vendor_id(),
        ], [
            'f_name.required' => 'First name is required!',
        ]);
        $seller = auth('vendor')->check()?auth('vendor')->user():auth('vendor_employee')->user();
        $seller->f_name = $request->f_name;
        $seller->l_name = $request->l_name;
        $seller->phone = $request->phone;
        $seller->email = $request->email;
        if ($request->image) {
            $seller->image = Helpers::update('vendor/', $seller->image, 'png', $request->file('image'));
        }
        $seller->save();

        Toastr::info('Profile updated successfully!');
        return back();
    }

    public function settings_password_update(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required',
        ]);

        $seller = Vendor::find(Helpers::get_restaurant_id());
        $seller->password = bcrypt($request['password']);
        $seller->save();
        Toastr::success('Vendor password updated successfully!');
        return back();
    }

    public function bank_update(Request $request)
    {
        $bank = Vendor::find(auth('vendor')->id());
        $bank->bank_name = $request->bank_name;
        $bank->branch = $request->branch;
        $bank->holder_name = $request->holder_name;
        $bank->account_no = $request->account_no;
        $bank->ifsc_code = $request->ifsc_code;
        $bank->save();
        Toastr::success(trans('messages.bank_info_updated_successfully'));
        return redirect()->route('vendor.profile.bankView');
    }



    public function kyc_update(Request $request)
    {

         $validator = Validator::make($request->all(), [
            'aadhaar_card_number' => 'required|numeric|digits:12',
            'pan_card_number' => 'required|alpha_num|min:10|max:10',
        ]);


         if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $vendor = Vendor::find(auth('vendor')->id());
        // $vendor->kyc = $request->kyc;
        $vendor->aadhaar_card_number = $request->aadhaar_card_number;
        $vendor->pan_card_number = $request->pan_card_number;
        
        $vendor->aadhaar_front_image = $request->has('aadhaar_front_image') ? Helpers::update('restaurant/identity/', $vendor->aadhaar_front_image, 'png', $request->file('aadhaar_front_image')) : $vendor->aadhaar_front_image;

        $vendor->aadhaar_back_image = $request->has('aadhaar_back_image') ? Helpers::update('restaurant/identity/', $vendor->aadhaar_back_image, 'png', $request->file('aadhaar_back_image')) : $vendor->aadhaar_back_image;

        $vendor->pan_card_image = $request->has('pan_card_image') ? Helpers::update('restaurant/identity/', $vendor->pan_card_image, 'png', $request->file('pan_card_image')) : $vendor->pan_card_image;

        $vendor->save();
        Toastr::success('KYC Details Updated Successfully ');
        return redirect()->route('vendor.profile.bankView');
    }

    public function bank_edit()
    {
        $data = Vendor::where('id', Helpers::get_restaurant_id())->first();
        return view('vendor-views.profile.bankEdit', compact('data'));
    }

    public function kyc_edit()
    {
        $data = auth('vendor')->user();
        return view('vendor-views.profile.kycEdit', compact('data'));
    }

}
