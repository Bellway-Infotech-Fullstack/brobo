@extends('layouts.admin.app')
@section('title','Customer Edit')
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @media(max-width:375px){
         #Customer-image-modal .modal-content{
           width: 367px !important;
         margin-left: 0 !important;
     }
    
     }

@media(max-width:500px){
 #Customer-image-modal .modal-content{
           width: 400px !important;
         margin-left: 0 !important;
     }
   
   
}
 </style>
@endpush

@section('content')
<div class="content container-fluid"> 
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('messages.dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin.customer.list')}}">{{trans('messages.customers')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{__('messages.customer')}} {{__('messages.update')}} </li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-black-50">{{__('messages.customer')}} {{__('messages.update')}} </h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{__('messages.customer')}} {{__('messages.update')}} {{__('messages.form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.customer.update',[$user_record['id']])}}" method="post">
                        
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="name"> {{__('messages.name')}}</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                               placeholder="Name" value="{{$user_record['name']}}"  required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="phmobile_numberone">Mobile Number</label>
                                        <input type="text" name="mobile_number" value="{{$user_record['mobile_number']}}"  class="form-control" id="mobile_number"
                                               placeholder="Ex : +91017********" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="email">{{__('messages.email')}}</label>
                                        <input type="email" name="email"  class="form-control" id="email"
                                               placeholder="Ex : ex@gmail.com"  value="{{$user_record['email']}}" required readonly>
                                    </div>                                
                                </div>
                            </div>
                            
                            
                            <small class="nav-subtitle border-bottom">{{__('messages.login')}} {{__('messages.info')}}</small>
                            <br>
                            <div class="form-group">
                                <div class="row">                            
                                 
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="password">Gender</label>
                                        <select name="gender" class="form-control" id="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ ($user_record['gender'] == 'Male') ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ ($user_record['gender'] == 'Female') ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="password">Home City</label>
                                        <input type="text" name="home_city" class="form-control" id="home_city" value="{{$user_record['home_city']}}"
                                               placeholder="Home City" required>
                                    </div>
                                  
                                </div>
                            </div>          
                            
                           
    
                            <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                        </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
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

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
@endpush
