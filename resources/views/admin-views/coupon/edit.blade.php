@extends('layouts.admin.app')

@section('title','Update Coupon')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{__('messages.coupon')}} {{__('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.coupon.update',[$coupon['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input type="text" name="title" value="{{$coupon['title']}}" class="form-control"
                                       placeholder="{{__('messages.new_coupon')}}" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.coupon')}} {{__('messages.type')}}</label>
                                <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    
                        
                                  
                                    <option value="default" {{$coupon['coupon_type']=='default'?'selected':''}}>{{__('messages.default')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                           
                                <div class="form-group" id="zone_wise" style="display: none">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.select')}} {{__('messages.zone')}}</label>
                                    <select name="zone_ids[]" id="choice_zones"
                                        class="form-control js-select2-custom"
                                        multiple="multiple" placeholder="{{__('messages.select_zone')}}">
                                    @foreach(\App\Models\Zone::all() as $zone)
                                        <option value="{{$zone->id}}" {{($coupon->coupon_type=='zone_wise'&&json_decode($coupon->data))?(in_array($zone->id, json_decode($coupon->data))?'selected':''):''}}>{{$zone->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.code')}}</label>
                                <input type="text" name="code" class="form-control" value="{{$coupon['code']}}"
                                       placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="limit">{{__('messages.limit')}} {{__('messages.for')}} {{__('messages.same')}} {{__('messages.user')}}</label>
                                <input type="number" name="limit" id="coupon_limit" value="{{$coupon['limit']}}" class="form-control"
                                       placeholder="EX: 10">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="">{{__('messages.start')}} {{__('messages.date')}}</label>
                                <input type="date" name="start_date" class="form-control" id="date_from" placeholder="{{__('messages.select_date')}}" value="{{date('Y-m-d',strtotime($coupon['start_date']))}}">
                            </div>
                        </div>
                       
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="date_to">{{__('messages.expire')}} {{__('messages.date')}}</label>
                                <input type="date" name="expire_date" class="form-control" placeholder="{{__('messages.select_date')}}" id="date_to" value="{{date('Y-m-d',strtotime($coupon['expire_date']))}}"
                                       data-hs-flatpickr-options='{
                                     "dateFormat": "Y-m-d"
                                   }'>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="discount_type">{{__('messages.discount')}} {{__('messages.type')}}</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="amount" {{$coupon['discount_type']=='amount'?'selected':''}}>{{__('messages.amount')}}
                                    </option>
                                    <option value="percent" {{$coupon['discount_type']=='percent'?'selected':''}}>
                                        {{__('messages.percent')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="discount">{{__('messages.discount')}}</label>
                                <input type="number" id="discount" min="1" max="10000" step="0.01" value="{{$coupon['discount']}}"
                                       name="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.min')}} {{__('messages.purchase')}}</label>
                                <input type="number" name="min_purchase" step="0.01" value="{{$coupon['min_purchase']}}"
                                       min="0" max="100000" class="form-control"
                                       placeholder="100">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Upload Background Image</label>
                                <input type="file" name="coupon_background_image" id="coupon_background_image">
                            </div>
                            <?php

                            $couponImagePath = (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/'.$coupon['background_image']) : asset('storage/app/public/coupon_background_image/' .$coupon['background_image']);    
                            ?>

                        <center style="display: block" id="image-viewer-section" class="pt-2">
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="{{$couponImagePath}}"
                                 alt="coupon image"/>
                        </center>
                        </div>  
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('messages.update')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
          $("#coupon_background_image").change(function () {
            handleFile(this);
            $('#image-viewer-section').show(1000);
        });
        $("#date_from").on("change", function () {
            $('#date_to').attr('min',$(this).val());
        });

        var isequal = 0;
        function handleFile(input){
            const file = input.files[0];

            // Check if the file is an image
            
           
                const reader = new FileReader();

                reader.onload = function (e) {
                   const img = new Image();
		 $('#viewer').attr('src', e.target.result);
                    

                };

                reader.readAsDataURL(file);
           
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#date_to").on("change", function () {
            $('#date_from').attr('max',$(this).val());
        });
        $(document).on('ready', function () {
            $('#date_from').attr('max','{{date("Y-m-d",strtotime($coupon["expire_date"]))}}');
            $('#date_to').attr('min','{{date("Y-m-d",strtotime($coupon["start_date"]))}}');
            $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/vendor/get-services',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

        function coupon_type_change(coupon_type) {
           if(coupon_type=='zone_wise')
            {
                $('#restaurant_wise').hide();
                $('#zone_wise').show();
            }
            else if(coupon_type=='restaurant_wise')
            {
                $('#restaurant_wise').show();
                $('#zone_wise').hide();
            }
            else if(coupon_type=='first_order')
            {
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val(1);
                $('#coupon_limit').attr("readonly","true");
            }
            else{
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val('');
                $('#coupon_limit').removeAttr("readonly");
            }

            if(coupon_type=='free_delivery')
            {
                $('#discount_type').attr("disabled","true");
                $('#discount_type').val("").trigger( "change" );
                $('#max_discount').val(0);
                $('#max_discount').attr("readonly","true");
                $('#discount').val(0);
                $('#discount').attr("readonly","true");
            }
            else{
                $('#max_discount').removeAttr("readonly");
                $('#discount_type').removeAttr("disabled");
                $('#discount').removeAttr("readonly");
            }
        }
    </script>
@endpush
