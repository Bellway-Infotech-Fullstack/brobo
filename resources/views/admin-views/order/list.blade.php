@extends('layouts.admin.app')

@section('title','Booking List')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: transparent;
        }
        .select2-container--default .select2-selection--multiple {
            border-color: #e7eaf300;
            padding: 0 .875rem;
        }
        .table-nowrap td{
            white-space:unset;
        }
        sup{
            right:unset;
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
            <div class="row align-items-center mb-3">
                <div class="col-9">
                    <h1 class="page-header-title text-capitalize">{{str_replace('_',' ',$status)}}

                    @if(!Request::is('admin/order/list/service_ongoing')) {{__('messages.orders')}} @endIf<span
                            class="badge badge-soft-dark ml-2">{{$total}}</span></h1>
                </div>

                <div class="col-3">

                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="row justify-content-between align-items-center flex-grow-1">
                    <div class="col-lg-6 mb-3 mb-lg-0">

                           <form action="javascript:" id="search-form">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{__('messages.search')}}" aria-label="{{__('messages.search')}}" required>
                                       <input type="hidden" name="status" id="status" value="{{ request('status') }}" />
                                <button type="submit" class="btn btn-light">{{__('messages.search')}}</button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <div class="d-sm-flex justify-content-sm-end align-items-sm-center">
                            <!-- Datatable Info -->
                            <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0" style="display: none;">
                                <div class="d-flex align-items-center">
                                      <span class="font-size-sm mr-3">
                                        <span id="datatableCounter">0</span>
                                        {{__('messages.selected')}}
                                      </span>
                                    {{--<a class="btn btn-sm btn-outline-danger" href="javascript:;">
                                        <i class="tio-delete-outlined"></i> Delete
                                    </a>--}}
                                </div>
                            </div>
                            <!-- End Datatable Info -->

                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                                   data-hs-unfold-options='{
                                     "target": "#usersExportDropdown",
                                     "type": "css-animation"
                                   }'>
                                    <i class="tio-download-to mr-1"></i> {{__('messages.export')}}
                                </a>

                                <div id="usersExportDropdown"
                                     class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{__('messages.options')}}</span>
                                    <a id="export-copy" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                             src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/illustrations/copy.svg"
                                             alt="Image Description">
                                        {{__('messages.copy')}}
                                    </a>
                                    <a id="export-print" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                             src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/illustrations/print.svg"
                                             alt="Image Description">
                                        {{__('messages.print')}}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-header">{{__('messages.download')}} {{__('messages.options')}}</span>
                                    <a id="export-excel" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                             src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/components/excel.svg"
                                             alt="Image Description">
                                        {{__('messages.excel')}}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                             src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                             alt="Image Description">
                                        .{{__('messages.csv')}}
                                    </a>
                                    <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                             src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/components/pdf.svg"
                                             alt="Image Description">
                                        {{__('messages.pdf')}}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                   onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').show(500)">
                                    <i class="tio-filter-list mr-1"></i> Filters <span class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                                </a>
                            </div>
                            <!-- End Unfold -->
                            <!-- Unfold -->
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                   data-hs-unfold-options='{
                                     "target": "#showHideDropdown",
                                     "type": "css-animation"
                                   }'>
                                    <i class="tio-table mr-1"></i> {{__('messages.columns')}}
                                </a>

                                <div id="showHideDropdown"
                                     class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card"
                                     style="width: 15rem;">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{__('messages.order')}} ID</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_order" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">Booking {{__('messages.date')}}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_date" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{__('messages.customer')}}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm"
                                                       for="toggleColumn_customer">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_customer" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                          

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2 text-capitalize">{{__('messages.payment')}} {{__('messages.status')}}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm"
                                                       for="toggleColumn_payment_status">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_payment_status" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">Paid Amount</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_total">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_total" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{__('messages.order')}} {{__('messages.status')}}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_order_status" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="mr-2">{{__('messages.actions')}}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm"
                                                       for="toggleColumn_actions">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                           id="toggleColumn_actions" checked>
                                                    <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                  </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       style="width: 100%"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "isResponsive": false,
                     "isShowPaging": false,
                     "paging": false
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="">{{__('messages.#')}}</th>
                        <th class="table-column-pl-0">{{__('messages.order')}} ID</th>
                        <th>Booking Date</th>
                        <th>{{__('messages.customer')}} Name</th>
                        <th>{{__('messages.customer')}} Mobile Number</th>
                        <th>Delivery Address</th>
                        <th>Pin Location</th>
                        <th class="">Product Names</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time Slot</th>
                        <th>Paid Amount</th>
                        <th>GST Number</th>
                        <th>{{__('messages.payment')}} {{__('messages.status')}}</th>                       
                        <th>{{__('messages.order')}} {{__('messages.status')}}</th>
                        <th>{{__('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$key + 1}}
                            </td>
                            <td class="table-column-pl-0">
                             <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['order_id']}}</a>

                      
                            </td>
                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                            <td>
                                @if($order->customer)
                                 <a class="text-body text-capitalize"
                                       href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['name']}}</a> 

                                  
                                @else
                                    <label class="badge badge-danger">{{__('messages.invalid')}} {{__('messages.customer')}} {{__('messages.data')}}</label>
                                @endif
                            </td>
                            <td> {{ $order->customer['mobile_number'] }}</td>
                            <td>
                                <?php
                                $addressData  =   \App\Models\UsersAddress::where('id' , $order->delivery_address_id)->first();
                                if(isset($addressData) && !empty($addressData)){
                                        $deliveryAddress = $addressData->house_name . ",";

                                        // Add floor number with suffix
                                        $floorNumber = $addressData->floor_number;
                                        if ($floorNumber % 100 >= 11 && $floorNumber % 100 <= 13) {
                                            $suffix = 'th';
                                        } else {
                                            switch ($floorNumber % 10) {
                                                case 1:
                                                    $suffix = 'st';
                                                    break;
                                                case 2:
                                                    $suffix = 'nd';
                                                    break;
                                                case 3:
                                                    $suffix = 'rd';
                                                    break;
                                                default:
                                                    $suffix = 'th';
                                                    break;
                                            }
                                        }

                                        $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". "&nbsp;floor " . "," . $addressData->landmark . "," . $addressData->area_name . "," . $addressData->zip_code;

                                    } else {
                                        $deliveryAddress = 'N/A';
                                    }

                                    echo $deliveryAddress;
                            ?>
                             </td>

                             <td>{{ $order->pin_location ?? 'N/A'  }} </td>
                             <td>
                                <?php
                                $productNames = '';
                                $cartItems = json_decode($order->cart_items, true);
                                if(isset($cartItems) && !empty($cartItems)){
                                    foreach($cartItems as $key => $value){
                                        $productNames .= "," . $value['item_name'];
                                    }
                                    $productNames = trim($productNames, ',');
                                }
                                ?>
                                
                                {{ $productNames }}
                                

                             </td>
                        
                            <td>{{date('d M Y',strtotime($order['start_date']))}}</td>
                            <?php
                                if(!empty($order['end_date'])){
                            ?>
                            <td>{{date('d M Y',strtotime($order['end_date']))}}</td>
                            <?php } else{ ?>
                                <td>N/A</td>
                            <?php } ?>
                            <td>{{ $order['time_duration'] }}</td>
                            <td>Rs. {{ ($order->paid_amount)  }} </td>
                            <td> {{ $order->gst_number ?? 'N/A'  }} </td>
                            <td>
                              
                                <span class="badge badge-soft-success">
                                  <span class="legend-indicator bg-success"></span>{{__('messages.paid')}}
                                </span>
                            
                            </td>
                            <td class="text-capitalize">
                                @if($order['status']=='ongoing')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-info"></span>Ongoing
                                    </span>
                                @elseif($order['status']=='cancelled')
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger"></span>Cancelled
                                    </span>
                      
                                @elseif($order['status']=='completed')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-success"></span>{{__('messages.delivered')}}
                                    </span>
                                @elseif($order['status']=='failed')
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger text-capitalize"></span>{{__('messages.payment')}}  {{__('messages.failed')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['status'])}}
                                    </span>
                                @endif
                            </td>
                           {{--  <td class="text-capitalize">
                                @if($order['order_type']=='take_away')
                                    <span class="badge badge-soft-dark ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-dark"></span>{{__('messages.take_away')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-success"></span>{{__('messages.delivery')}}
                                    </span>
                                @endif
                            </td> --}}
                            <td>
                                <a class="btn btn-sm btn-white"
                                           href="{{route('admin.order.details',['id'=>$order['id']])}}"><i
                                                class="tio-visible"></i> {{__('messages.view')}}</a>

                              @if($order['status']=='cancelled' && $order['refunded'] == NULL)
                              <a class="btn btn-sm btn-white mt-2 refund-money"
                              data-order-id="{{ $order['id'] }}"><i
                                   class="tio-visible"></i> Refund
                               </a>

                              @endif
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $orders->appends($_GET)->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
        <!-- Order Filter Modal -->
        <div id="datatableFilterSidebar" class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow" style="display: none">
            <div class="card card-lg sidebar-card sidebar-footer-fixed">
                <div class="card-header">
                    <h4 class="card-header-title">{{__('messages.order')}} {{__('messages.filter')}}</h4>

                    <!-- Toggle Button -->
                    <a class="js-hs-unfold-invoker_ btn btn-icon btn-xs btn-ghost-dark ml-2" href="javascript:;"
                    onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').hide(500)">
                        <i class="tio-clear tio-lg"></i>
                    </a>
                    <!-- End Toggle Button -->
                </div>
                <?php 
                $filter_count=0;
                if(isset($zone_ids) && count($zone_ids) > 0) $filter_count += 1;
                if(isset($vendor_ids) && count($vendor_ids)>0) $filter_count += 1;
                if($status=='all')
                {
                    if(isset($orderstatus) && count($orderstatus) > 0) $filter_count += 1;
                    if(isset($scheduled) && $scheduled == 1) $filter_count += 1;
                }

                if(isset($from_date) && isset($to_date)) $filter_count += 1;
                if(isset($order_type)) $filter_count += 1;
                
                ?>
                <!-- Body -->
                <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('admin.order.filter')}}" method="POST" id="order_filter_form">
                    @csrf


                    @if($status == 'all')

                    <!-- Custom Checkbox -->
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus2" name="orderStatus[]" class="custom-control-input" {{isset($orderstatus)?(in_array('ongoing', $orderstatus)?'checked':''):''}} value="ongoing">
                        <label class="custom-control-label" for="orderStatus2">Ongoing</label>
                    </div>
                 
                 
                   
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus5" name="orderStatus[]" class="custom-control-input" value="completed" {{isset($orderstatus)?(in_array('completed', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus5">Completed</label>
                    </div>
                   
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus7" name="orderStatus[]" class="custom-control-input" value="failed" {{isset($orderstatus)?(in_array('failed', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus7">{{__('messages.failed')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus8" name="orderStatus[]" class="custom-control-input" value="cancelled" {{isset($orderstatus)?(in_array('cancelled', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus8">Cancelled</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus9" name="orderStatus[]" class="custom-control-input" value="refund_requested" {{isset($orderstatus)?(in_array('refund_requested', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus9">{{__('messages.refundRequest')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus10" name="orderStatus[]" class="custom-control-input" value="refunded" {{isset($orderstatus)?(in_array('refunded', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus10">{{__('messages.refunded')}}</label>
                    </div>
                    @endif
                
                    <hr class="my-4">

                    <small class="text-cap mb-3">{{__('messages.date')}} {{__('messages.between')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group" style="margin:0;">
                                <input type="date" name="from_date" class="form-control" id="date_from" value="{{isset($from_date)?$from_date:''}}">
                            </div>
                        </div>
                        <div class="col-12 text-center">----TO----</div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="date" name="to_date" class="form-control" id="date_to" value="{{isset($to_date)?$to_date:''}}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer sidebar-footer">
                        <div class="row gx-2">
                            <div class="col">
                                <button type="reset" class="btn btn-block btn-white" id="reset">Clear all filters</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-block btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer -->
                </form>
            </div>
        </div>
        <!-- End Order Filter Modal -->
@endsection

@push('script_2')
    <!-- <script src="{{asset($assetPrefixPath . '/assets/admin')}}/js/bootstrap-select.min.js"></script> -->
    <script>
        $(document).on('ready', function () {

           
            $(".refund-money").on("click",function(){


                var order_id = $(this).attr("data-order-id");
                Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to refund amount ?',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post({
                        url: '{{route('admin.order.initiate-refund')}}',
                        data : {
                            "order_id":order_id,
                            "_token": "{{csrf_token()}}"
                        },
                        
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if(data.status == 'success'){
                                toastr.success(data.message);  
                                location.href = "{{route('admin.order.list',['refunded'])}}";
                            } else {
                                toastr.error(data.message);
                            }
                           
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })

            });
           









            @if($filter_count>0)
            $('#filter_count').html({{$filter_count}});
            @endif
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            var zone_id = [];
            $('#zone_ids').on('change', function(){
                if($(this).val())
                {
                    zone_id = $(this).val();
                }
                else
                {
                    zone_id = [];
                }
            });



            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                        },
                       
                    },
                    {
                        extend: 'csv',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                        },
                        customize: function (doc) {
                            // Adjust PDF document settings
                            doc.pageMargins = [20, 20, 20, 20]; // Adjust margins: [left, top, right, bottom]
                            doc.defaultStyle.fontSize = 8; // Adjust font size
                                        console.log("doc",doc)
                        // You can also adjust other settings here if needed
                        },
                        action: function (e, dt, node, config)
                        {
                            var order_status = $("#status").val();                            
                              window.location.href = "{!! route('admin.order.export-order-list', ['format' => 'pdf', 'order_status' => '']) !!}" + order_status;                        }
                        },
                    {
                        extend: 'print',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                        }
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="mb-3" src="{{asset($assetPrefixPath . '/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                        '<p class="mb-0">No data to show</p>' +
                        '</div>'
                }
            });

            $('#export-copy').click(function () {
                datatable.button('.buttons-copy').trigger()
            });

            $('#export-excel').click(function () {
                var order_status = $("#status").val();
                window.location.href = "{!! route('admin.order.export-order-list', ['format' => 'excel', 'order_status' => '']) !!}" + order_status;
                //datatable.button('.buttons-excel').trigger()
            });

            $('#export-csv').click(function () {
                //datatable.button('.buttons-csv').trigger()
                var order_status = $("#status").val();
                window.location.href = "{!! route('admin.order.export-order-list', ['format' => 'csv', 'order_status' => '']) !!}" + order_status;
            });

            $('#export-pdf').click(function () {
                datatable.button('.buttons-pdf').trigger()
            });

            $('#export-print').click(function () {
                datatable.button('.buttons-print').trigger()
            });

            $('#datatableSearch').on('mouseup', function (e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function () {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });

            $('#toggleColumn_order').change(function (e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_date').change(function (e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_customer').change(function (e) {
                datatable.columns(3).visible(e.target.checked)
            })
            $('#toggleColumn_restaurant').change(function (e) {
                datatable.columns(4).visible(e.target.checked)
            })

            $('#toggleColumn_payment_status').change(function (e) {
                datatable.columns(6).visible(e.target.checked)
            })

            $('#toggleColumn_total').change(function (e) {
                datatable.columns(4).visible(e.target.checked)
            })
            $('#toggleColumn_order_status').change(function (e) {
                datatable.columns(7).visible(e.target.checked)
            })

            $('#toggleColumn_actions').change(function (e) {
                datatable.columns(8).visible(e.target.checked)
            })

            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });

            $("#date_from").on("change", function () {
                $('#date_to').attr('min',$(this).val());
            });

            $("#date_to").on("change", function () {
                $('#date_from').attr('max',$(this).val());
            });
        });

        $('#reset').on('click', function(){
            // e.preventDefault();
            location.href = '{{url('/')}}/admin/booking/filter/reset';
        });


        $('#search-form').on('submit', function () {
            //var formData = new FormData(this);

            var formData = $('#search-form').serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.get({
                url: '{{route('admin.order.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
