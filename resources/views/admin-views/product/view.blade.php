@extends('layouts.admin.app')

@section('title','Product Preview')

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
            <div class="row">
                <div class="col-6">
                    <h1 class="page-header-title">{{$product['name']}}</h1>
                </div>
                <div class="col-6">
                    <a href="{{route('admin.product.edit',[$product['id']])}}" class="btn btn-primary float-right">
                        <i class="tio-edit"></i> {{__('messages.edit')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">
                

                  
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-sm-4 col-12 pt-2">
                        <h4 class="border-bottom">{{$product['name']}}</h4>
                        <span>{{__('messages.price')}} :
                            <span>{{\App\CentralLogics\Helpers::format_currency($product['price'])}}</span>
                        </span><br>
                        <span>{{__('messages.tax')}} :
                            <span>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::tax_calculate($product,$product['price']))}}</span>
                        </span><br>
                        <span>{{__('messages.discount')}} :
                            <span>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product,$product['price']))}}</span>
                        </span><br>
                        <span>
                            Total Stock  {{ $product['total_stock'] }}
                        </span><br>
                      
                      
                    </div>

                    <div class="col-sm-8 col-12 pt-2 border-left">
                        <h4> {{__('messages.description')}} : </h4>
                        {!!$product['description'] !!}
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

  
    </div>
@endsection

@push('script_2')
<script>
    function status_form_alert(id, message, e) {
        e.preventDefault();
        Swal.fire({
            title: '{{__('messages.are_you_sure')}}',   
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    }
</script>
@endpush
