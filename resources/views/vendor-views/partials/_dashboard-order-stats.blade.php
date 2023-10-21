<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{route('vendor.order.list',['completed'])}}" style="background: #2C2E43">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">{{__('messages.delivered')}}</h6>
            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                    <span class="card-title h2" style="color: white!important;">
                        {{$data['completed']}}
                    </span>
                </div>
                <div class="col-6 mt-2">
                    <i class="tio-shopping-cart ml-6" style="font-size: 30px;color: white"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{route('vendor.order.list',['processing'])}}" style="background: #334257">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">{{__('messages.cooking')}}</h6>

            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                        <span class="card-title h2" style="color: white!important;">
                            {{$data['processing']}}
                        </span>
                </div>

                <div class="col-6 mt-2">
                    <i class="tio-checkmark-circle ml-6" style="font-size: 30px;color: white"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{route('vendor.order.list',['accepted'])}}" style="background: #362222">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">{{__('messages.accepted')}}</h6>

            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                        <span class="card-title h2" style="color: white!important;">
                          {{$data['accepted']}}
                        </span>
                </div>

                <div class="col-6 mt-2">
                    <i class="tio-time ml-6" style="font-size: 30px;color: white"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{route('vendor.order.list',['services_ongoing'])}}" style="background: #053742">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">{{__('messages.Service_on_the_way')}}</h6>

            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                    <span class="card-title h2" style="color: white!important;">
                       {{$data['services_ongoing']}}
                    </span>
                </div>

                <div class="col-6 mt-2">
                    <i class="tio-bike ml-6" style="font-size: 30px;color: white"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-12">
    <div class="card card-body" style="background: #FEF7DC!important;">
        <div class="row gx-lg-4">
            <div class="col-sm-6 col-lg-3">
                <div class="media" style="cursor: pointer"
                     onclick="location.href='{{route('vendor.order.list',['completed'])}}'">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{__('messages.delivered')}}</h6>
                        <span class="card-title h3">
                         {{$data['completed']}}
                        </span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                      <i class="tio-checkmark-circle-outlined"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-sm">
                <div class="media" style="cursor: pointer"
                     onclick="location.href='{{route('vendor.order.list',['refunded'])}}'">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{__('messages.refunded')}}</h6>
                        <span
                            class="card-title h3">{{$data['refunded']}}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                      <i class="tio-remove-from-trash"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-lg">
                <div class="media" style="cursor: pointer"
                     onclick="location.href='{{route('vendor.order.list',['scheduled'])}}'">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{__('messages.scheduled')}}</h6>
                        <span
                            class="card-title h3">{{$data['scheduled']}}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                      <i class="tio-history"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-sm">
                <div class="media" style="cursor: pointer"
                     onclick="location.href='{{route('vendor.order.list',['all'])}}'">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{__('messages.all')}}</h6>
                        <span
                            class="card-title h3">{{$data['all']}}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                      <i class="tio-message-failed"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
