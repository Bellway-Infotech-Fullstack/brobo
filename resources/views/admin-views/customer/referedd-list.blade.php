@extends('layouts.admin.app')
@section('title','Refereed User List')
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{trans('messages.dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{trans('messages.customers')}}</li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-md-flex_ align-items-center justify-content-between mb-2">
        <div class="row">
            <div class="col-md-8">
                <h3 class="h3 mb-0 text-black-50">Refereed User {{trans('messages.list')}}</h3>
            </div>

          
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header py-0">
                    <h5>{{trans('messages.customer')}} {{trans('messages.table')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$user_list->count()}}</span></h5>
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
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{trans('messages.#')}}</th>
                                <th>{{trans('messages.name')}}</th>
                                <th>{{trans('messages.email')}}</th>
                                <th>{{trans('messages.phone')}}</th>
                                <th>Refereed Amount</th>
                            </tr>
                            </thead>
                            <tbody id="set-rows">
                            @foreach($user_list as $k=>$e)
                                <tr>
                                    <th scope="row">{{$k+$user_list->firstItem()}}</th>
                                    <td class="text-capitalize">{{$e['name']}}</td>
                                    <td >{{$e['email'] ?? 'N/A'}}</td>
                                    <td>{{$e['mobile_number'] ?? 'N/A'}}</td>
                                    <td>Rs. 100</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="page-area">
                        <table>
                            <tfoot>
                            {!! $user_list->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
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
                url: '{{route('admin.customer.refereddsearch')}}',
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
    </script>
@endpush
