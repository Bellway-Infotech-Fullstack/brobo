@extends('layouts.admin.app')

@section('title','Update Notification')

@push('css_or_js')

@endpush
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
@endphp
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-notifications"></i> {{__('messages.notification')}} {{__('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.notification.update',[$notification['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input type="text" value="{{$notification['title']}}" name="notification_title" class="form-control" placeholder="{{__('messages.new_notification')}}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label" for="tergat">{{__('messages.send')}} {{__('messages.to')}}</label>
                                <select name="target_user_id[]" id="tergat"  class="form-control js-select2-custom" multiple required>
                                    <option value="all" {{ $notification['user_ids'] == 'all' ? "selected=selected" : ''}}>All Users</option>
                                    <?php
                                    $notification_user_ids = $notification['user_ids'];
                                   // die;

                                   $notification_user_ids = explode(",",$notification_user_ids);

                                    foreach (\App\Models\User::where('role_id', 2)->orderBy('name')->get() as $user){
                                        
                                        if($notification_user_ids!='all'){
                                            
                                            foreach($notification_user_ids as $key => $value){
                                                if($user->id == $value){
                                                    $selected = "selected=selected";
                                                } else {
                                                    $selected = "";
                                                }
                                            
                                        
                                        

                                    ?>
                                        <option value="{{ $user->id }}" {{ $selected }}>{{ $user->name }} ( {{ $user->mobile_number }} )</option>

                                    <?php } } } ?>
                                   
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.description')}}</label>
                        <textarea name="description" class="form-control" required>{{$notification['description']}}</textarea>
                    </div>
                   
                    <hr>
                    <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('.js-select2-custom').select2();

$('.js-select2-custom').on('select2:close', function (e) {
    if ($(this).val().length === 0) {
        $(this).find('option').prop('disabled', false);
    }
});




$('.js-select2-custom').on('change', function() {
    if ($(this).val() && $(this).val().includes('all')) {
        // If "All users" is selected, deselect all other options
        $(this).val(['all']);
        $(this).find('option:not(:selected)').prop('disabled', true);
    } 
    
    
    else {
        // If no options selected or only "All users" selected, deselect all
        $(this).find('option[value="all"]').prop('disabled', true);
    }
});
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
