@extends('layouts.admin.app')
@section('title','Customer List')
@push('css_or_js')
<style>
#datatable_filter,#datatable_info{
    display:none
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
                <h1 class="page-header-title text-capitalize">{{trans('messages.customers')}}
                    <span
                            class="badge badge-soft-dark ml-2">  {{$user_list->total()}}</span>

                  

            </div>

            <div class="col-3">

            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <!-- Page Heading -->
    <div class="d-md-flex_ align-items-center justify-content-between mb-2">
        <div class="row">
            <div class="col-md-8">
            </div>

            <div class="col-md-4">
                <a href="{{route('admin.customer.add-new')}}" class="btn btn-primary  float-right">
                    <i class="tio-add-circle"></i>
                    <span class="text">{{trans('messages.add')}} {{trans('messages.new')}}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">                 
                    <form action="javascript:" id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-flush">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{__('messages.search')}}" aria-label="Search">
                            <button type="submit" class="btn btn-light">{{__('messages.search')}}</button>
                        </div>
                        <!-- End Search -->
                        
                    </form>
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
                           
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
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
                     "entries": "#datatableEntries",
                     "isResponsive": false,
                     "isShowPaging": false,
                     "paging": false
                   }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{trans('messages.#')}}</th>
                                <th>{{trans('messages.name')}}</th>
                                <th>{{trans('messages.email')}}</th>
                                <th>{{trans('messages.phone')}}</th>
                                <th style="width: 50px">{{trans('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody id="set-rows">
                            @foreach($user_list as $k=>$e)
                                <tr>
                                    <th scope="row">{{$k+$user_list->firstItem()}}</th>
                                    <td class="text-capitalize">{{$e['name']}}</td>
                                    <td >{{$e['email'] ?? 'N/A'}}</td>
                                    <td>{{$e['mobile_number'] ?? 'N/A'}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                            href="{{route('admin.customer.edit',[$e['id']])}}" title="{{__('messages.edit')}} {{__('messages.customer')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="javascript:"
                                            onclick="form_alert('customer-{{$e['id']}}','{{__('messages.Want_to_delete_this_role')}}')" title="{{__('messages.delete')}} {{__('messages.customer')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.customer.delete',[$e['id']])}}"
                                                method="post" id="customer-{{$e['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                  <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $user_list->appends($_GET)->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        action: function (e, dt, node, config)
                        {
                            window.location.href = "{{ route('admin.customer.export',['format'=>'excel']) }}";
                        }
                    },
                       

                    
                    
                    
                    {
                        extend: 'csv',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        action: function (e, dt, node, config)
                        {
                            window.location.href = "{{ route('admin.customer.export',['format'=>'pdf']) }}";
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'd-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
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
               // datatable.button('.buttons-excel').trigger()
                window.location.href = "{{ route('admin.customer.export',['format'=>'csv']) }}";
            });





            $('#export-csv').click(function () {
                window.location.href = "{{ route('admin.customer.export',['format'=>'csv']) }}";
            });

            $('#export-pdf').click(function () {
                window.location.href = "{{ route('admin.customer.export',['format'=>'pdf']) }}";
            });

            $('#export-print').click(function () {
                datatable.button('.buttons-print').trigger()
            });
            
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.customer.search')}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        $('#set-rows').html(data.view);
                        $('#itemCount').html(data.count);
                        $('.page-area').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
            });
        });

        function exportData(format) {
            // Send a request to the server to get all data
            var url = "{{ route('admin.customer.export') }}?format=" + format;
            $.get(url, function(data) {
                // Create a temporary hidden anchor element
                var downloadAnchor = document.createElement('a');
                if (format === 'pdf') {
                    downloadAnchor.href = 'data:application/pdf;base64,' + data;
                    downloadAnchor.download = 'customer_list.pdf';
                } else if (format === 'csv') {
                    downloadAnchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(data);
                    downloadAnchor.download = 'customer_list.csv';
                }
                downloadAnchor.style.display = 'none';
                document.body.appendChild(downloadAnchor);
                
                // Trigger the click event on the anchor element
                downloadAnchor.click();

                // Clean up
                document.body.removeChild(downloadAnchor);
            });
        }
        
    </script>
@endpush
