@extends('layouts.admin.app')

@section('title',\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value??'Dashboard')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .grid-card {
            border: 2px solid #00000012;
            border-radius: 10px;
            padding: 10px;
        }

        .label_1 {
            position: absolute;
            font-size: 10px;
            background: #865439;
            color: #ffffff;
            width: 60px;
            padding: 2px;
            font-weight: bold;
            border-radius: 6px;
        }
    </style>
@endpush



@section('content')
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{__('messages.welcome')}},.</h1>
                    <p class="page-header-text">{{__('messages.welcome_message')}}</p>
                </div>

                <div class="col-sm-auto" style="width: 306px;">
                    <label class="badge badge-soft-success float-right">
                        {{__('messages.software_version')}} : {{env('SOFTWARE_VERSION')}}
                    </label>
                    <select name="zone_id" class="form-control js-select2-custom"
                            onchange="fetch_data_zone_wise(this.value)">
                        <option value="all">All Zones</option>
                        @foreach(\App\Models\Zone::orderBy('name')->get() as $zone)
                            <option
                                value="{{$zone['id']}}" {{$params['zone_id'] == $zone['id']?'selected':''}}>
                                {{$zone['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- Stats -->
      {{--   <div class="card mb-3">
            <div class="card-body">
                <div class="row gx-2 gx-lg-3 mb-2">
                    <div class="col-9">
                        <h4><i class="tio-chart-bar-4"></i>{{__('order_texts.dashboard_order_statistics')}}</h4>
                    </div>
                    <div class="col-3">
                        <select class="custom-select" name="statistics_type" onchange="order_stats_update(this.value)">
                            <option
                                value="overall" {{$params['statistics_type'] == 'overall'?'selected':''}}>
                                Overall Statistics
                            </option>
                            <option
                                value="today" {{$params['statistics_type'] == 'today'?'selected':''}}>
                                Today's Statistics
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row gx-2 gx-lg-3" id="order_stats">
                    @include('admin-views.partials._dashboard-order-stats',['data'=>$data])
                </div>
            </div>
        </div> --}}

        <!-- End Stats -->

        <div class="row gx-2 gx-lg-3">
            <div class="col-lg-12 mb-3 mb-lg-12">
                <!-- Card -->
                <div class="card h-100" id="monthly-earning-graph">
                    <!-- Body -->
                @include('admin-views.partials._monthly-earning-graph',['total_sell'=>$total_sell,'commission'=>$commission])
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->

        <div class="row gx-2 gx-lg-3">
            <div class="col-lg-6 mb-3">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header">
                        <h5 class="card-header-title">
                            Users Overview
                        </h5>
                        <select class="custom-select" style="width: 30%" name="user_overview"
                                onchange="user_overview_stats_update(this.value)">
                            <option
                                value="this_month" {{$params['user_overview'] == 'this_month'?'selected':''}}>
                                This Month
                            </option>
                            <option
                                value="overall" {{$params['user_overview'] == 'overall'?'selected':''}}>
                                Overall
                            </option>
                        </select>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body" id="user-overview-board">
                        @if($params['zone_id']!='all')
                            @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
                        @else
                            @php($zone_name='All')
                        @endif
                        <label class="badge badge-soft-info">( Zone : {{$zone_name}} )</label>
                        <div class="chartjs-custom mx-auto">
                            <canvas id="user-overview" class="mt-2"></canvas>
                        </div>
                        <!-- End Chart -->
                    </div>
                    <!-- End Body -->
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header">
                        <h5 class="card-header-title">
                            Business Overview
                        </h5>
                        <select class="custom-select" style="width: 30%" name="business_overview"
                                onchange="business_overview_stats_update(this.value)">
                            <option
                                value="this_month" {{$params['business_overview'] == 'this_month'?'selected':''}}>
                                This Month
                            </option>
                            <option
                                value="overall" {{$params['business_overview'] == 'overall'?'selected':''}}>
                                Overall
                            </option>
                        </select>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body" id="business-overview-board">
                        @if($params['zone_id']!='all')
                            @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
                        @else
                            @php($zone_name='All')
                        @endif
                        <label class="badge badge-soft-info">( Zone : {{$zone_name}} )</label>
                        <!-- Chart -->
                        <div class="chartjs-custom mx-auto">
                            <canvas style="max-height: 85%;" id="business-overview" class="mt-2"></canvas>
                        </div>
                        <!-- End Chart -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-6 mb-3">
                <!-- Card -->
                <div class="card h-100" id="top-selling-foods-view">
                    @include('admin-views.partials._top-selling-foods',['top_sell'=>$data['top_sell']])
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-6 mb-3">
                <!-- Card -->
                <div class="card h-100" id="popular-restaurants-view">
                    @include('admin-views.partials._popular-restaurants',['popular'=>$data['popular']])
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-6 mt-3">
                <!-- Card -->
                <div class="card h-100" id="top-rated-foods-view">
                    @include('admin-views.partials._top-rated-foods',['top_rated_services'=>$data['top_rated_services']])
                </div>
                <!-- End Card -->
            </div>

           {{--  <div class="col-lg-6 mt-3">
                <!-- Card -->
                <div class="card h-100" id="top-deliveryman-view">
                    @include('admin-views.partials._top-deliveryman',['top_deliveryman'=>$data['top_deliveryman']])
                </div>
                <!-- End Card -->
            </div> --}}

            <div class="col-lg-6 mt-3">
                <!-- Card -->
                <div class="card h-100" id="top-customer-view">
                    @include('admin-views.partials._top-customer',['top_customer'=>$data['top_customer']])
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-6 mt-3">
                <!-- Card -->
                <div class="card h-100" id="top-restaurants-view">
                    @include('admin-views.partials._top-restaurants',['top_restaurants'=>$data['top_restaurants']])
                </div>
                <!-- End Card -->
            </div>
        </div>
      
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{__('messages.welcome')}}, .</h1>
                    <p class="page-header-text">{{__('messages.employee_welcome_message')}}</p>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
  
    </div>
@endsection

@push('script')
    <script src="{{asset('assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{asset('assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush


@push('script_2')
   
@endpush
