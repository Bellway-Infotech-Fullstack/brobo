@extends('layouts.admin.app')
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
@endphp
@section('title','Add new coupon')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{__('messages.add')}} {{__('messages.new')}} {{__('messages.coupon')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.coupon.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                        <input type="text" name="title" class="form-control" placeholder="{{__('messages.new_coupon')}}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.coupon')}} {{__('messages.type')}}</label>
                                        <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">                                         
                                            <option value="default">{{__('messages.default')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4" >
                                   
                                    <div class="form-group" id="zone_wise" style="display: none">
                                        <label class="input-label" for="exampleFormControlInput1">Select {{__('messages.zone')}}</label>
                                        <select name="zone_ids[]" id="choice_zones"
                                            class="form-control js-select2-custom"
                                            multiple="multiple" data-placeholder="{{__('messages.select_zone')}}">
                                        @foreach(\App\Models\Zone::all() as $zone)
                                            <option value="{{$zone->id}}">{{$zone->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.code')}}</label>
                                        <input type="text" name="code" class="form-control"
                                            placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.limit')}} {{__('messages.for')}} {{__('messages.same')}} {{__('messages.user')}}</label>
                                        <input type="number" name="limit" id="coupon_limit" class="form-control" placeholder="EX: 10">
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.start')}} {{__('messages.date')}}</label>
                                        <input type="date" name="start_date" class="form-control" id="date_from" required>
                                    </div>
                                </div>
                               
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.expire')}} {{__('messages.date')}}</label>
                                        <input type="date" name="expire_date" class="form-control" id="date_to" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}} {{__('messages.type')}}</label>
                                        <select name="discount_type" class="form-control" id="discount_type">
                                            <option value="amount">{{__('messages.amount')}}</option>
                                            <option value="percent">{{__('messages.percent')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}}</label>
                                        <input type="number" step="0.01" min="1" max="10000" name="discount" id="discount" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.min')}} {{__('messages.purchase')}}</label>
                                        <input type="number" step="0.01" name="min_purchase" value="0" min="0" max="100000" class="form-control"
                                            placeholder="100">
                                    </div>
                                </div>  
                                
                                <div class="col-md-4 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">Upload Background Image</label>
                                        <input type="file" name="coupon_background_image" id="coupon_background_image" required>
                                    </div>
                                    <center style="display: none" id="image-viewer-section" class="pt-2">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                            src="{{asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')}}" alt="banner image"/>
                                    </center>
                                </div>  
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                        </form>
                    </div>
                </div>
                
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                    <h5>{{__('messages.coupon')}} {{__('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$coupons->total()}}</span></h5>
                        <form id="dataSearch">
                        @csrf
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{__('messages.search_here')}}" aria-label="{{__('messages.search_here')}}">
                                <button type="submit" class="btn btn-light">{{__('messages.search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom" id="table-div">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging":false,
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('messages.#')}}</th>
                                <th>{{__('messages.title')}}</th>
                                <th>{{__('messages.code')}}</th>
                                <th>{{__('messages.type')}}</th>
                                <th>{{__('messages.min')}} {{__('messages.purchase')}}</th>
                                <th>{{__('messages.discount')}}</th>
                                <th>{{__('messages.discount')}} {{__('messages.type')}}</th>
                                <th>{{__('messages.start')}} {{__('messages.date')}}</th>
                                <th>{{__('messages.expire')}} {{__('messages.date')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($coupons as $key=>$coupon)
                                <tr>
                                    <td>{{$key+$coupons->firstItem()}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$coupon['title']}}
                                    </span>
                                    </td>
                                    <td>{{$coupon['code']}}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $coupon->coupon_type)) }}</td>
                                    <td>{{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}</td>
                                    <td>{{$coupon['discount']}}</td>
                                    <td>{{$coupon['discount_type']}}</td>
                                    <td>{{$coupon['start_date']}}</td>
                                    <td>{{$coupon['expire_date']}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="couponCheckbox{{$coupon->id}}">
                                            <input type="checkbox" onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'" class="toggle-switch-input" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white" href="{{route('admin.coupon.update',[$coupon['id']])}}"title="{{__('messages.edit')}} {{__('messages.coupon')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:" onclick="form_alert('coupon-{{$coupon['id']}}','Want to delete this coupon ?')" title="{{__('messages.delete')}} {{__('messages.coupon')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                                    method="post" id="coupon-{{$coupon['id']}}">
                                                @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <table>
                            <tfoot>
                            {!! $coupons->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
    // Assuming input is the file input element

        var isequal = 0;
        function handleFile(input){
            const file = input.files[0];

            // Check if the file is an image
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = new Image();

                    img.onload = function () {
                        // Get the width and height of the image
                        const width = this.width;
                        const height = this.height;

                        // Check if the dimensions meet your criteria         

                        if (width != 343 || height != 100) {
                                // Dimensions are not allowed, handle accordingly (e.g., show an error message)
                                alert('Invalid dimensions. Width must be  343px and height must be <= 100px.');
                                // Optionally clear the file input if needed
                                input.value = '';
                                isequal = 1;
                            } else {
                            // Dimensions are within the allowed range, you can proceed with your logic
                            // ...
                            }
                        };
                            img.src = e.target.result;
                            if(isequal == 1){
                                $('#viewer').attr('src', e.target.result);
                            }
                    
                };

                reader.readAsDataURL(file);
            } else {
                // File is not an image, handle accordingly (e.g., show an error message)
                alert('Invalid file type. Please upload an image.');
                // Optionally clear the file input if needed
                input.value = '';
            }
        }

       $("#coupon_background_image").change(function () {
            handleFile(this);
            $('#image-viewer-section').show(1000);
        });

            $("#date_from").on("change", function () {
                $('#date_to').attr('min',$(this).val());
            });

            $("#date_to").on("change", function () {
                $('#date_from').attr('max',$(this).val());
            });

            $('.js-data-example-ajax').select2({});


    
    
    $(document).on('ready', function () {

        
        $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
        $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);


        
        
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                    '<img class="mb-3" src="{{asset($assetPrefixPath . '/admin/svg/illustrations/sorry.svg')}}" alt="Image Description" style="width: 7rem;">' +
                    '<p class="mb-0">No data to show</p>' +
                    '</div>'
                }
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('#zone_wise').hide();
        function coupon_type_change(coupon_type) {
           if(coupon_type=='zone_wise')
            {
               
                $('#zone_wise').show();
            }

          
            else{
                $('#zone_wise').hide();
                $('#coupon_limit').val('');
                $('#coupon_limit').removeAttr("readonly");
            }
            $('#product_wise').hide();
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
        $('#dataSearch').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.coupon.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#table-div').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
