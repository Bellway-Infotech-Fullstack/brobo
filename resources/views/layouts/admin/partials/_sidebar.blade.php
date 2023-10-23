@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
@endphp
<style>
    .nav-sub{
        background: #019842 !important;
    }
</style>

<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php(@$restaurant_logo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value)
                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                    <img class="navbar-brand-logo" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='{{asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')}}'"
                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='{{asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')}}'"
                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Content -->
            <div class="navbar-vertical-content" style="background-color: #00080c ;">
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                           href="{{route('admin.dashboard')}}" title="{{__('messages.dashboard')}}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{__('messages.dashboard')}}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->
                    
                       <!-- Employee-->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{__('messages.employee_handle')}}">{{__('messages.employee')}} {{__('section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

          

                    @if(\App\CentralLogics\Helpers::module_permission_check('employee'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/employee*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:"
                               title="{{__('messages.Employee')}}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.employees')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/employee*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/employee/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.employee.add-new')}}"
                                       title="{{__('messages.add')}} {{__('messages.new')}} {{__('messages.Employee')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">{{__('messages.add')}} {{__('messages.new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/employee/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.employee.list')}}"
                                       title="{{__('messages.Employee')}} {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.list')}}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                @endif
                <!-- End Employee -->


                    <!-- Orders -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('order'))
                        <li class="nav-item">
                            <small
                                class="nav-subtitle">{{__('messages.booking')}} {{__('messages.section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/order*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.order')}}">
                                {{-- <i class="tio-shopping-cart nav-icon"></i> --}}
                                <img src="{{asset($assetPrefixPath . '/assets/admin/img/booking.png')}}" style="width: 20px; height: auto;filter: invert(100%);" /> &nbsp; &nbsp;
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.bookings')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/order/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['pending'])}}"
                                       title="{{__('messages.pending')}} {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::Pending()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/order/list/accepted')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['accepted'])}}"
                                       title="{{__('messages.acceptedbyDM')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{__('messages.accepted')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Models\Order::Accepted()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['processing'])}}"
                                       title="{{__('messages.preparingInRestaurants')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.processing')}}
                                                <span class="badge badge-warning badge-pill ml-1">
                                                {{\App\Models\Order::Preparing()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/services_ongoing')?'active':''}}">
                                    <a class="nav-link text-capitalize"
                                       href="{{route('admin.order.list',['services_ongoing'])}}"
                                       title="{{__('messages.serviceOngoing')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.serviceOngoing')}}
                                                <span class="badge badge-warning badge-pill ml-1">
                                                {{\App\Models\Order::ServiceOngoing()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['completed'])}}"
                                       title="{{__('messages.delivered')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{__('messages.completed')}}
                                            <span class="badge badge-success badge-pill ml-1">
                                            {{\App\Models\Order::Delivered()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['canceled'])}}"
                                       title="{{__('messages.canceled')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{__('messages.canceled')}}
                                            <span class="badge badge-soft-warning bg-light badge-pill ml-1">
                                            {{\App\Models\Order::Canceled()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['failed'])}}"
                                       title="{{__('messages.payment')}} {{__('messages.failed')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">
                                        {{__('messages.payment')}} {{__('messages.failed')}}
                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            {{\App\Models\Order::failed()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/refunded')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['refunded'])}}"
                                       title="{{__('messages.refunded')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{__('messages.refunded')}}
                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            {{\App\Models\Order::Refunded()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/order/list/scheduled')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.order.list',['scheduled'])}}"
                                       title="{{__('messages.scheduled')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        {{__('messages.scheduled')}}
                                        <span class="badge badge-info badge-pill ml-1">
                                            {{\App\Models\Order::Scheduled()->count()}}
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/order/list/all')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.order.list',['all'])}}"
                                       title="{{__('messages.all')}} {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.all')}}
                                            <span class="badge badge-info badge-pill ml-1">
                                                {{\App\Models\Order::whereIn('order_status',['pending', 'failed', 'canceled', 'services_ongoing', 'picked_up', 'service_ongoing', 'processing', 'accepted', 'delivered', 'completed', 'refunded'])->orWhere('scheduled', 1)->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order dispachment -->
                   {{--      <li class="navbar-vertical-aside-has-menu {{Request::is('admin/dispatch/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.dispatchManagement')}}">
                                <i class="tio-clock nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.dispatchManagement')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/dispatch*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/dispatch/list/searching_for_deliverymen')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.dispatch.list',['searching_for_deliverymen'])}}"
                                       title="{{__('messages.searchingDM')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.searchingDM')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::SearchingForDeliveryman()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/dispatch/list/on_going')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.dispatch.list',['on_going'])}}"
                                       title="{{__('messages.ongoingOrders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.ongoingOrders')}}
                                                <span class="badge badge-soft-dark bg-light badge-pill ml-1">
                                                {{\App\Models\Order::Ongoing()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <!-- Order dispachment End-->
                    @endif
                <!-- End Orders -->
              
                    <!-- End Restaurant -->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{__('messages.service')}} {{__('messages.section')}}">{{__('messages.service')}} {{__('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    
                    

                    <!-- Category -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('category'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/category*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.category')}}"
                            >
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.categories')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/category*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/category/add')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.add')}}"
                                       title="{{__('messages.category')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.category')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/category/add-sub-category')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.add-sub-category')}}"
                                       title="{{__('messages.sub_category')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.sub_category')}}</span>
                                    </a>
                                </li>

                                {{--<li class="nav-item {{Request::is('admin/category/add-sub-sub-category')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.add-sub-sub-category')}}"
                                        title="add new sub sub category">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Sub-Sub-Category</span>
                                    </a>
                                </li>--}}
                                <li class="nav-item {{Request::is('admin/category/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.bulk-import')}}"
                                       title="{{__('messages.bulk_import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_import')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/category/bulk-export')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.bulk-export-index')}}"
                                       title="{{__('messages.bulk_export')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_export')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Category -->

                    <!-- Attributes -->
                  {{--   @if(\App\CentralLogics\Helpers::module_permission_check('attribute'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/attribute*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.attribute.add-new')}}" title="{{__('messages.attributes')}}"
                            >
                                <i class="tio-apps nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.attributes')}}
                                </span>
                            </a>
                        </li>
                    @endif --}}
                    <!-- End Attributes -->
                    
                    

                    <!-- AddOn -->
                   {{--  @if(\App\CentralLogics\Helpers::module_permission_check('addon'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/addon*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.addons')}}"
                            >
                                <i class="tio-add-circle-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.addons')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/addon*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/addon/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.addon.add-new')}}"
                                       title="{{__('messages.addon')}} {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.list')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/addon/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.addon.bulk-import')}}"
                                       title="{{__('messages.bulk_import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_import')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/addon/bulk-export')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.addon.bulk-export-index')}}"
                                       title="{{__('messages.bukl_export')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_export')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif --}}
                <!-- End AddOn -->
                    <!-- Food -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('service'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/service*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.service')}}"
                            >
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.products')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/product*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/service/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                       title="{{__('messages.add')}} {{__('messages.new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">{{__('messages.add')}} {{__('messages.new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.list')}}"
                                       title="{{__('messages.service')}} {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.list')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.bulk-import')}}"
                                       title="{{__('messages.bulk_import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_import')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/bulk-export')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.bulk-export-index')}}"
                                       title="{{__('messages.bulk_export')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">{{__('messages.bulk_export')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                <!-- End Food -->
                <!-- DeliveryMan -->
                 {{--    @if(\App\CentralLogics\Helpers::module_permission_check('deliveryman'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{__('messages.deliveryman')}} {{__('messages.section')}}">{{__('messages.deliveryman')}} {{__('messages.section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/add')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.delivery-man.add')}}"
                               title="{{__('messages.add_delivery_man')}}"
                            >
                                <i class="tio-running nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.add_delivery_man')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.delivery-man.list')}}"
                               title="{{__('messages.deliveryman')}} {{__('messages.list')}}"
                            >
                                <i class="tio-filter-list nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.deliverymen')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/reviews/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.delivery-man.reviews.list')}}" title="{{__('messages.reviews')}}"
                            >
                                <i class="tio-star-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.reviews')}}
                                </span>
                            </a>
                        </li>
                    @endif --}}
                <!-- End DeliveryMan -->
                    <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{__('messages.employee_handle')}}">{{__('messages.marketing')}} {{__('messages.section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Banner -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('banner'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.banner.add-new')}}" title="{{__('messages.banner')}}"
                            >
                                <i class="tio-image nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.banners')}}</span>
                            </a>
                        </li>
                    @endif
                <!-- End Banner -->
                    <!-- Coupon -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('coupon'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.coupon.add-new')}}" title="{{__('messages.coupon')}}"
                            >
                                <i class="tio-gift nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.coupons')}}</span>
                            </a>
                        </li>
                    @endif
                <!-- End Coupon -->
                    <!-- Notification -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('notification'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.notification.add-new')}}"
                               title="{{__('messages.send')}} {{__('messages.notification')}}"
                            >
                                <i class="tio-notifications nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.push')}} {{__('messages.notification')}}
                                </span>
                            </a>
                        </li>
                    @endif
                <!-- End Notification -->

                    <!-- End marketing section -->

                <!-- End account -->

                    <!-- provide_dm_earning -->
                   {{--  @if(\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/provide-deliveryman-earnings*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.provide-deliveryman-earnings.index')}}"
                               title="{{__('messages.deliverymen_earning_provide')}}"
                            >
                                <i class="tio-send nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.deliverymen_earning_provide')}}</span>
                            </a>
                        </li>
                    @endif --}}
                <!-- End provide_dm_earning -->
                    <!-- Custommer -->
               
                <!-- End Custommer -->

                    <!-- Business Settings -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{__('messages.business')}} {{__('messages.settings')}}">{{__('messages.business')}} {{__('messages.settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/business-setup')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.business-setup')}}"
                               title="{{__('messages.business')}} {{__('messages.setup')}}"
                            >
                                <span class="tio-settings nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.business')}} {{__('messages.setup')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/payment-method')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.payment-method')}}"
                               title="{{__('messages.payment')}} {{__('messages.methods')}}"
                            >
                                <span class="tio-atm nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.payment')}} {{__('messages.methods')}}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/mail-config')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.mail-config')}}"
                               title="{{__('messages.mail')}} {{__('messages.config')}}"
                            >
                                <span class="tio-email nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.mail')}} {{__('messages.config')}}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/sms-module')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.sms-module')}}"
                               title="{{__('messages.sms')}} {{__('messages.module')}}">
                                <span class="tio-message nav-icon"></span>
                                <span class="text-truncate">{{__('messages.sms')}} {{__('messages.module')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/fcm-index')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.fcm-index')}}"
                               title="{{__('messages.push')}} {{__('messages.notification')}}">
                                <span class="tio-notifications nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.notification')}} {{__('messages.settings')}}</span>
                            </a>
                        </li>
                    @endif
                <!-- End Business Settings -->

                    <!-- web & adpp Settings -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{__('messages.business')}} {{__('messages.settings')}}">{{__('messages.web_and_app')}} {{__('messages.settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/app-settings*')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.app-settings')}}"
                               title="{{__('messages.app_settings')}}"
                            >
                                <span class="tio-android nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.app_settings')}}</span>
                            </a>
                        </li>
                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/landing-page-settings*')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.business-settings.landing-page-settings', 'index')}}"
                               title="{{__('messages.landing_page_settings')}}"
                            >
                                <span class="tio-website nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.landing_page_settings')}}</span>
                            </a>
                        </li>
                     

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/pages*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.pages')}} {{__('messages.setup')}}"
                            >
                                <i class="tio-pages nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.pages')}} {{__('messages.setup')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/business-settings/pages*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('admin/business-settings/pages/terms-and-conditions')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.terms-and-conditions')}}"
                                       title="{{__('messages.terms_and_condition')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.terms_and_condition')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/business-settings/pages/privacy-policy')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.privacy-policy')}}"
                                       title="{{__('messages.privacy_policy')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.privacy_policy')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/business-settings/pages/about-us')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.about-us')}}"
                                       title="{{__('messages.about_us')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.about_us')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/file-manager*')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.file-manager.index')}}"
                               title="{{__('messages.third_party_apis')}}"
                            >
                                <span class="tio-album nav-icon"></span>
                                <span
                                    class="text-truncate text-capitalize">{{__('messages.gallery')}}</span>
                            </a>
                        </li>

                     

                    @endif
                <!-- End web & adpp Settings -->

                    <!-- Report -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('report'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{__('messages.report_and_analytics')}}">{{__('messages.report_and_analytics')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/day-wise-report')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.report.day-wise-report')}}"
                               title="{{__('messages.day_wise_report')}}">
                                <span class="tio-report nav-icon"></span>
                                <span
                                    class="text-truncate">{{__('messages.day_wise_report')}}</span>
                            </a>
                        </li>

                        
                    @endif

             


                    <li class="nav-item" style="padding-top: 100px">

                    </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>
