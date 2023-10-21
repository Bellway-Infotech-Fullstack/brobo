@extends('layouts.vendor.app')

@section('title','Notification')

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">Notification</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Notification List</span></h5>
                    </div>
                    <div class="card-body">
                        

                         <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "columnDefs": [{
                                    "targets": [],
                                    "width": "5%",
                                    "orderable": false
                                }],
                                "order": [],
                                "info": {
                                "totalQty": "#datatableWithPaginationInfoTotalQty"
                                },

                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                 "paging":false
                            }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('messages.#')}}</th>
                                <th style="width: 20%">Title</th>
                                <th style="width: 20%">Message</th>
                                <th>{{__('messages.status')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$notification->title}}</td>
                                    <td>{{$notification->message}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$notification->id}}">
                                            <input type="checkbox" class="toggle-switch-input" id="stocksCheckbox{{$notification->id}}" {{$notification->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                            href="{{route('vendor.notification.show', $notification->id)}}" title="View"><i class="tio-info"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                {!! $notifications->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- End Table -->


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
