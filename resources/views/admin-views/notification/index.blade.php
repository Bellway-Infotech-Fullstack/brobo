@extends('layouts.admin.app')

@section('title','Add new notification')

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
                    <h1 class="page-header-title"><i class="tio-notifications"></i> {{__('messages.notification')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data" id="notification">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input type="text" name="notification_title" class="form-control" placeholder="{{__('messages.new_notification')}}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label" for="tergat">{{__('messages.send')}} {{__('messages.to')}}</label>
                                <select name="target_user_id" id="tergat"  class="form-control js-select2-custom">
                                    <option value="all">All Users</option>
                                    @foreach (\App\Models\User::where('role_id', 2)->orderBy('name')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.description')}}</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <hr>
                    <button type="submit" id="submit" class="btn btn-primary">{{__('messages.send')}} {{__('messages.notification')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <hr>
                <div class="card">
                    <div class="card-header py-1">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                        <h3>Notification list<span
                            class="badge badge-soft-dark ml-2">{{$notifications->total()}}</span></h3>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <form>
                                <!-- Search -->
                                <div class="input-group input-group-merge input-group-flush">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input type="search" id="column1_search" class="form-control"
                                           placeholder="{{__('messages.search')}} {{__('messages.notification')}}">
                                           <button type="submit" class="btn btn-light">{{__('messages.search')}}</button>
                                </div>
                                <!-- End Search -->
                                </form>
                            </div>
                        </div>                        
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging": false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th>{{__('messages.#')}}</th>
                                    <th style="width: 50%">{{__('messages.title')}}</th>
                                    <th>{{__('messages.description')}}</th>
                                    <th style="width: 10%">{{__('messages.action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$key+$notifications->firstItem()}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{substr($notification['title'],0,25)}} {{strlen($notification['title'])>25?'...':''}}
                                    </span>
                                    </td>
                                    <td>
                                        {{substr($notification['description'],0,25)}} {{strlen($notification['description'])>25?'...':''}}
                                    </td>
                                  
                                   
                                    
                                 
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                            href="{{route('admin.notification.edit',[$notification['id']])}}" title="{{__('messages.edit')}} {{__('messages.notification')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:"
                                            onclick="form_alert('notification-{{$notification['id']}}','Want to delete this notification ?')" title="{{__('messages.delete')}} {{__('messages.notification')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.notification.delete',[$notification['id']])}}" method="post" id="notification-{{$notification['id']}}">
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
                            {!! $notifications->links() !!}
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
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
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

        $('#notification').on('submit', function (e) {
            
            e.preventDefault();
            var formData = new FormData(this);
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'you want to sent notification to '+$('#tergat').val()+'?',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{__('messages.no')}}',
                confirmButtonText: '{{__('messages.send')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.notification.store')}}',
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
                                toastr.success('Notifiction sent successfully!', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    location.href = '{{route('admin.notification.add-new')}}';
                                }, 2000);
                            }
                        }
                    });
                }
            })
        })
    </script>
@endpush
