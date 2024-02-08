@extends('layouts.admin.app')

@section('title','Update Banner')

@push('css_or_js')
<style>
    .select2 .select2-container .select2-container--default .select2-container--above .select2-container--focus{
        width:100% !important;
    }
</style>
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
                    <h1 class="page-header-title"><i class="tio-edit"></i>{{__('messages.update')}} {{__('messages.banner')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.banner.update', [$banner->id])}}" method="post" id="banner_form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{__('messages.product')}} {{__('messages.name')}}</label>
                                    <small style="color: red">* </small>
                                    <select name="product_id" id="product_id" class="form-control js-select2-custom" required>
                                        <option value="">Select Product</option>
                                        @if(count($products) > 0)
                                            @foreach($products as $product)
                                                @php
                                                 $selected =  ($product->id == $banner['product_id']) ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $product->id }}" {{ $selected  }}>{{ $product->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('messages.banner')}} {{__('messages.image')}}</label>
                                        <small style="color: red">* ( {{__('messages.ratio')}} 3:1 )</small>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom:0%;">
                                        <center>
                                            <img style="border: 1px solid; border-radius: 10px;" id="viewer"  src="{{asset($assetPrefixPath .'/storage/banner')}}/{{$banner['image']}}" alt="campaign image"/>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
    function getRequest(route, id) {
        $.get({
            url: route,
            dataType: 'json',
            success: function (data) {
                $('#' + id).empty().append(data.options);
            },
        });
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

    $("#customFileEg1").change(function () {
        readURL(this);
    });
</script>
<script>
    $(document).on('ready', function () {
        var zone_id = {{$banner->zone_id}};
        banner_type_change('{{$banner->type}}');

        $('#zone').on('change', function(){
            if($(this).val())
            {
                zone_id = $(this).val();
            }
            else
            {
                zone_id = true;
            }
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/vendor/get-restaurants',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        zone_ids: [zone_id],
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



        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });   
    });

        function banner_type_change(order_type) {
           if(order_type=='service_wise')
            {
                $('#restaurant_wise').hide();
                $('#item_wise').show();
            }
            else if(order_type=='vendor_wise')
            {
                $('#restaurant_wise').show();
                $('#item_wise').hide();
            }
            else{
                $('#item_wise').hide();
                $('#restaurant_wise').hide();
            }
        }
        @if($banner->type == 'service_wise')
        getRequest('{{url('/')}}/admin/service/get-foods?zone_id={{$banner->zone_id}}&data[]={{$banner->data}}','choice_item');
        @endif 
        $('#banner_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.banner.update', [$banner['id']])}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('Banner uploaded successfully!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.banner.add-new')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
