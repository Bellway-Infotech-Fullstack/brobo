@extends('layouts.admin.app')

@section('title','SMS Module Setup')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{__('messages.sms')}} {{__('messages.gateway')}} {{__('messages.setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" style="padding-bottom: 20px">
 

        

    

            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">{{__('messages.msg91_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : Keep an OTP variable in your SMS providers OTP Template.</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{__('messages.msg91_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2 d-none">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.template_id')}} For Signup </label><br>
                                <input type="text" class="form-control" name="template_id_for_signup"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id_for_signup']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.template_id')}} For OTP </label><br>
                                <input type="text" class="form-control" name="template_id_for_otp"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id_for_otp']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.authkey')}}</label><br>
                                <input type="text" class="form-control" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
