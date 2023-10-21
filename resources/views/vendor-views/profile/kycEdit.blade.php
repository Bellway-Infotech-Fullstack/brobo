@extends('layouts.vendor.app')

@section('title','KYC Details')

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page">{{__('messages.owner')}}</li>
                <li class="breadcrumb-item">{{__('messages.kyc_details')}} {{__('messages.details')}}</li>
            </ol>
        </nav>

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">{{__('messages.kyc_details')}} {{__('messages.details')}}</h1>
        </div>
        <!-- Content Row -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 ">{{__('messages.edit')}}</h1>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="text-danger">{{$error}}</div>
                            @endforeach
                        @endIf
                        <form action="{{route('vendor.profile.kyc_update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                          

                          {{--  <div class="form-group">
                                <label class="input-label" for="address">{{__('messages.vendor')}} {{__('messages.kyc_details')}}</label>
                                <input type="text" name="kyc" class="form-control" placeholder="{{__('messages.vendor')}} {{__('messages.kyc_details')}}" required value="{{$data->kyc}}" />
                            </div> --}}

                            <div class="form-group">
                                <label class="input-label" for="address">Aadhaar Card Number</label>
                                <input type="text" name="aadhaar_card_number" class="form-control" placeholder="Aadhaar Card Number" required value="{{$data->aadhaar_card_number}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1').substring(0, 12);" />
                            </div>

                            <div class="form-group">
                                <label class="input-label" for="address">Pan Card Number</label>
                                <input type="text" name="pan_card_number" class="form-control" placeholder="Pan Card Number" required value="{{$data->pan_card_number}}" oninput="this.value = this.value.replace(/[^0-9a-zA-Z]/g, '').replace(/(\..*)\./g, '$1').substring(0, 10);" />
                            </div>


                            <div class="form-group">
                                    <label>Upload Aadhaar card Front Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="aadhaar_front_image" id="aadhaar_front_image" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="aadhaar_front_image">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                    </div>

                            <div class="avatar avatar-xxl avatar-border-lg">
                                    <img class="avatar-img" id="AadhaarFrontImageCoverImageViewer" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$data->aadhaar_front_image}}" alt="Aadhaar card Front Image">
                            </div>
                            </div>

                            <div class="form-group">
                                    <label>Upload Aadhaar card Back Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="aadhaar_back_image" id="aadhaar_back_image" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="aadhaar_back_image">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                    </div>

                                    <div class="avatar avatar-xxl avatar-border-lg">
                                    <img class="avatar-img" id="AadhaarBackImageCoverImageViewer" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$data->aadhaar_back_image}}" alt="Aadhaar Card Back Image">
                            </div>
                            </div>

                            <div class="form-group">
                                    <label>Upload Pan card Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="pan_card_image" id="pan_card_image" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="pan_card_image">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                    </div>

                                    <div class="avatar avatar-xxl avatar-border-lg">
                                    <img class="avatar-img" id="panCardCoverImageViewer" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$data->pan_card_image}}" alt="Pan Card Image">
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="btn_update">{{__('messages.update')}}</button>
                            <a class="btn btn-danger" href="{{route('vendor.profile.view')}}">{{__('messages.cancel')}}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end/js/croppie.js')}}"></script>
@endpush

@push('script_2')
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#aadhaar_front_image").change(function () {
           readURL(this, 'AadhaarFrontImageCoverImageViewer');
        });

        $("#aadhaar_back_image").change(function () {
           readURL(this, 'AadhaarBackImageCoverImageViewer');
        });

        $("#pan_card_image").change(function () {
            readURL(this, 'panCardCoverImageViewer');
        });
    </script>
@endPush

