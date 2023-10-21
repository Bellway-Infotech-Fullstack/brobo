@extends('layouts.admin.app')

@section('title','Update Vendor info')

@push('css_or_js')
<style>
    #map{
        height: 100%;
    }
    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        #map{
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{__('messages.update')}} {{__('messages.restaurant')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.vendor.update',[$vendor['id']])}}" method="post" class="js-validate"
                      enctype="multipart/form-data">
                    @csrf

                    
                    <small class="nav-subtitle text-secondary border-bottom">{{__('messages.restaurant')}} {{__('messages.info')}}</small>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-12">
                             <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="f_name">{{__('messages.first')}} {{__('messages.name')}}</label>
                                    <input type="text" name="f_name" class="form-control" placeholder="{{__('messages.first')}} {{__('messages.name')}}"
                                         value="{{$vendor->f_name}}"  required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="l_name">{{__('messages.last')}} {{__('messages.name')}}</label>
                                    <input type="text" name="l_name" class="form-control" placeholder="{{__('messages.last')}} {{__('messages.name')}}"
                                    value="{{$vendor->l_name}}"  required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="phone">{{__('messages.phone')}}</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Ex : 017********"
                                    value="{{$vendor->phone}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="address">{{__('messages.vendor')}} {{__('messages.address')}}</label>
                                <textarea type="text" name="address" class="form-control" placeholder="{{__('messages.vendor')}} {{__('messages.address')}}" required >{{$vendor->address}}</textarea>
                            </div>

                             {{-- <div class="form-group">
                                <label class="input-label" for="address">{{__('messages.vendor')}} {{__('messages.kyc_details')}}</label>
                                <input type="text" name="kyc" class="form-control" placeholder="{{__('messages.vendor')}} {{__('messages.kyc_details')}}" required value="{{$vendor->kyc}}" />
                            </div> --}}

                            <div class="form-group">
                                <label class="input-label" for="address">Aadhaar Card Number</label>
                                <input type="text" name="aadhaar_card_number" class="form-control" placeholder="Aadhaar Card Number" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1').substring(0, 12);" required value="{{$vendor->aadhaar_card_number}}" />
                            </div>

                            <div class="form-group">
                                <label class="input-label" for="address">Pan Card Number</label>
                                <input type="text" name="pan_card_number" class="form-control" placeholder="Pan Card Number" oninput="this.value = this.value.replace(/[^0-9a-zA-Z]/g, '').replace(/(\..*)\./g, '$1').substring(0, 10);" required value="{{$vendor->pan_card_number}}" />
                            </div>


                            <div class="form-group">
                                    <label>Upload Aadhaar card Front Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="aadhaar_front_image" id="aadhaar_front_image" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="aadhaar_front_image">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                    </div>

                            <div class="avatar avatar-xxl avatar-border-lg">
                                    <img class="avatar-img" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$vendor->aadhaar_front_image}}" alt="Image Description" id="AadhaarFrontImageCoverImageViewer" />
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
                                    <img class="avatar-img" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$vendor->aadhaar_back_image}}" alt="Image Description" id="AadhaarBackImageCoverImageViewer">
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
                                    <img id="panCardCoverImageViewer" class="avatar-img" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/restaurant/identity/')}}/{{$vendor->pan_card_image}}" alt="Image Description">
                            </div>
                            </div>


                            <small class="nav-subtitle text-secondary border-bottom">{{__('messages.bank_info')}}</small>
                            <br>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name">{{__('messages.bank_name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" 
                                               class="form-control" id="name"
                                               value="{{$vendor->bank_name}}"
                                               required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="name">{{__('messages.branch')}} {{__('messages.name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="branch"  class="form-control"
                                               id="name"
                                               value="{{$vendor->branch}}"
                                               required>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="account_no">{{__('messages.holder_name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="holder_name" 
                                               class="form-control" id="account_no"
                                               value="{{$vendor->holder_name}}"
                                               required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="account_no">{{__('messages.account_no')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="account_no" 
                                               class="form-control" id="account_no"
                                               value="{{$vendor->account_no}}"
                                               required>
                                    </div>

                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ifsc_code">IFSC Code<span class="text-danger">*</span></label>
                                        <input type="text" name="ifsc_code" 
                                               class="form-control" id="ifsc_code"
                                               value="{{$vendor->ifsc_code}}"
                                               required>
                                    </div>
                                </div>
                            </div>


                           {{--  <div class="form-group">
                                <label class="input-label" for="tax">{{__('messages.vat/tax')}} (%)</label>
                                <input type="number" name="tax" class="form-control" placeholder="{{__('messages.vat/tax')}}" min="0" step=".01" required value="{{$vendor->tax}}">
                            </div> --}}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="input-label" for="minimum_delivery_time">{{__('messages.minimum_delivery_time')}}</label>
                                    <input type="text" name="minimum_delivery_time" class="form-control" placeholder="30" pattern="^[0-9]{2}$" required value="{{explode('-',$vendor->delivery_time)[0]}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="input-label" for="maximum_delivery_time">{{__('messages.maximum_delivery_time')}}</label>
                                    <input type="text" name="maximum_delivery_time" class="form-control" placeholder="40" pattern="[0-9]{2}" required value="{{explode('-',$vendor->delivery_time)[1]}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{__('messages.restaurant')}} {{__('messages.logo')}}<small style="color: red"> ( {{__('messages.ratio')}} 1:1 )</small></label>
                                <div class="custom-file">
                                    <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12" style="margin-top: auto;margin-bottom: auto;">
                            <div class="form-group" style="margin-bottom:0%;">                       
                                <center>
                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                        src="{{asset('storage/app/public/restaurant').'/'.$vendor->image}}" alt="Image"/>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="choice_zones">{{__('messages.zone')}}<span
                                        class="input-label-secondary" title="{{__('messages.select_zone_for_map')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.select_zone_for_map')}}"></span></label>
                                <select name="zone_id" id="choice_zones" onchange="get_zone_data(this.value)" data-placeholder="{{__('messages.select')}} {{__('messages.zone')}}"
                                        class="form-control js-select2-custom">
                                    @foreach(\App\Models\Zone::all() as $zone)
                                        @if(isset(auth('admin')->user()->zone_id))
                                            @if(auth('admin')->user()->zone_id == $zone->id)
                                                <option value="{{$zone->id}}" {{$vendor->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                            @endif
                                        @else
                                            <option value="{{$zone->id}}" {{$vendor->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.latitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.restaurant_lat_lng_warning')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.restaurant_lat_lng_warning')}}"></span></label>
                                <input type="text"
                                       name="latitude" class="form-control" id="latitude"
                                       placeholder="Ex : -94.22213" value="{{$vendor->latitude}}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.longitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.restaurant_lat_lng_warning')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.restaurant_lat_lng_warning')}}"></span></label>
                                <input type="text"
                                       name="longitude" class="form-control" id="longitude"
                                       placeholder="Ex : 103.344322" value="{{$vendor->longitude}}" readonly>
                            </div>
                        </div>

                        <div class="col-md-8 col-12">
                            <div id="map"></div>
                        </div>

                        {{--<div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="">
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius."></i>
                                    {{__('messages.coverage')}} ( {{__('messages.km')}} )
                                </label>
                                <input type="number" value=""
                                       name="coverage" class="form-control" placeholder="Ex : 3">
                            </div>
                        </div>--}}
                    </div>
                    <div class="form-group">
                        <label for="name">{{__('messages.upload')}} {{__('messages.cover')}} {{__('messages.photo')}} <span class="text-danger">({{__('messages.ratio')}} 2:1)</span></label>
                        <div class="custom-file">
                            <input type="file" name="cover_photo" id="coverImageUpload" class="custom-file-input"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileUpload">{{__('messages.choose')}} {{__('messages.file')}}</label>
                        </div>
                    </div> 
                    <center>
                        <img style="max-width: 100%;border: 1px solid; border-radius: 10px; max-height:200px;" id="coverImageViewer"
                        onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                        src="{{asset('storage/app/public/restaurant/cover/'.$vendor->cover_photo)}}" alt="Product thumbnail"/>
                    </center>  
                    
                    <br>
                    <small class="nav-subtitle text-secondary border-bottom">{{__('messages.vendor')}} {{__('messages.info')}}</small>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.first')}} {{__('messages.name')}}</label>
                                <input type="text" name="f_name" class="form-control" placeholder="{{__('messages.first')}} {{__('messages.name')}}" value="{{$vendor->f_name}}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.last')}} {{__('messages.name')}}</label>
                                <input type="text" name="l_name" class="form-control" placeholder="{{__('messages.last')}} {{__('messages.name')}}"
                                value="{{$vendor->l_name}}"  required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.phone')}}</label>
                                <input type="text" name="phone" class="form-control" placeholder="Ex : 017********"
                                value="{{$vendor->phone}}"   required>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                    <small class="nav-subtitle text-secondary border-bottom">{{__('messages.login')}} {{__('messages.info')}}</small>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.email')}}</label>
                                <input type="email" name="email" class="form-control" placeholder="Ex : ex@example.com" value="{{$vendor->email}}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrPassword">Password</label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control" name="password" id="signupSrPassword" placeholder="{{__('messages.password_length_placeholder',['length'=>'6+'])}}" aria-label="6+ characters required"
                                    data-msg="Your password is invalid. Please try again."
                                    data-hs-toggle-password-options='{
                                    "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                    "defaultClass": "tio-hidden-outlined",
                                    "showClass": "tio-visible-outlined",
                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                    }'>
                                    <div class="js-toggle-password-target-1 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrConfirmPassword">Confirm password</label>

                                <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control" name="confirmPassword" id="signupSrConfirmPassword" placeholder="{{__('messages.password_length_placeholder', ['length'=>'6+'])}}" aria-label="6+ characters required"                                      data-msg="Password does not match the confirm password."
                                        data-hs-toggle-password-options='{
                                        "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                        }'>
                                <div class="js-toggle-password-target-2 input-group-append">
                                    <a class="input-group-text" href="javascript:;">
                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

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

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function () {
            readURL(this, 'coverImageViewer');
        });

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
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initMap&v=3.45.8"></script>
    <script> 
        let myLatlng = { lat: {{$vendor->latitude}}, lng: {{$vendor->longitude}} };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: myLatlng,
        });
        var zonePolygon = null;
        let infoWindow = new google.maps.InfoWindow({
                content: "Click the map to get Lat/Lng!",
                position: myLatlng,
            });
        var bounds = new google.maps.LatLngBounds();
        function initMap() {
            // Create the initial InfoWindow.
            new google.maps.Marker({
                position: { lat: {{$vendor->latitude}}, lng: {{$vendor->longitude}} },
                map,
                title: "{{$vendor->name}}",
            });
            infoWindow.open(map);            
        }
        initMap();
        function get_zone_data(id)
        {
            $.get({
                url: '{{url('/')}}/admin/zone/get-coordinates/'+id,
                dataType: 'json',
                success: function (data) {
                    if(zonePolygon)
                    {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                        position: mapsMouseEvent.latLng,
                        content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });    
                },
            });
        }
        $(document).on('ready', function (){
            var id = $('#choice_zones').val();
            $.get({
                url: '{{url('/')}}/admin/zone/get-coordinates/'+id,
                dataType: 'json',
                success: function (data) {
                    if(zonePolygon)
                    {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function(path) {
                        path.forEach(function(latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                        position: mapsMouseEvent.latLng,
                        content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });    
                },
            });
        });
    </script>
<script>
      $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
          new HSTogglePassword(this).init()
        });


        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function() {
          $.HSCore.components.HSValidation.init($(this), {
            rules: {
              confirmPassword: {
                equalTo: '#signupSrPassword'
              }
            }
          });
        });

        get_zone_data({{$vendor->zone_id}});
      });
    </script>
@endpush
