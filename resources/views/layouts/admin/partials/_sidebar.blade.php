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
                
                <?php
                
                     $logoPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $restaurant_logo) : asset('storage/app/public/business/' . $restaurant_logo);        
                ?>
                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                    <img class="navbar-brand-logo" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='{{asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')}}'"
                         src="{{$logoPath}}"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='{{asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')}}'"
                         src="{{$logoPath}}" alt="Logo">
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
                    
                       <!-- customer-->

                   

          
         
                    @if(\App\CentralLogics\Helpers::module_permission_check('customer'))

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{__('messages.customer_handle')}}">{{__('messages.customer')}} {{__('section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:"
                               title="{{__('messages.customer')}}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.customers')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/customer*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/customer/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.customer.add-new')}}"
                                       title="{{__('messages.add')}} {{__('messages.new')}} {{__('messages.customer')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">{{__('messages.add')}} {{__('messages.new')}}</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item {{Request::is('admin/customer/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.customer.list')}}"
                                       title="{{__('messages.customer')}} {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.list')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/customer/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.customer.refereed-list')}}"
                                       title="{{__('messages.customer')}} {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Referred Customer {{__('messages.list')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                @endif
                <!-- End customer -->
                <li class="nav-item">
                    <small class="nav-subtitle"
                           title="product section">POS System</small>
                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                </li>
                
                @if(\App\CentralLogics\Helpers::module_permission_check('pos'))
                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/service*')?'active':''}}">
                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                       href="javascript:" title="POS"
                    >
                        <i class="tio-shopping nav-icon"></i>
                        <span
                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.pos')}}</span>
                    </a>
                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                        style="display: {{Request::is('admin/pos*')?'block':'none'}}">
                        <li class="nav-item {{Request::is('admin/pos/create')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.pos.create')}}"
                               title="{{__('messages.pos')}}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{__('messages.pos')}}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Request::is('admin/pos/bookings')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.pos.booking-list')}}"
                               title="POS {{__('messages.bookings')}}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{__('messages.bookings')}}</span>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

                    <!-- Orders -->
                    @if(\App\CentralLogics\Helpers::module_permission_check('booking'))
                        <li class="nav-item">
                            <small
                                class="nav-subtitle">{{__('messages.booking')}} {{__('messages.section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/booking*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{__('messages.order')}}">
                                {{-- <i class="tio-shopping-cart nav-icon"></i> --}}
                                <img src="{{asset($assetPrefixPath . '/assets/admin/img/booking.png')}}" style="width: 20px; height: auto;filter: invert(100%);" /> &nbsp; &nbsp;
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{__('messages.bookings')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/booking*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/booking/list/ongoing') ? 'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['ongoing'])}}"
                                       title="Ongoing {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Ongoing
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::ServiceOngoing()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/booking/list/cancelled')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['cancelled'])}}"
                                       title="Cancelled {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Cancelled
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::Cancelled()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/booking/list/completed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['completed'])}}"
                                       title="Completed {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Completed
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::Completed()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/booking/list/refunded')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['refunded'])}}"
                                       title="Refunded">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Refunded
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::Refunded()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/booking/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.list',['failed'])}}"
                                       title="Payment Failed">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Payment Failed
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Models\Order::failed()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                               
                                <li class="nav-item {{Request::is('admin/booking/list/all')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.order.list',['all'])}}"
                                       title="{{__('messages.all')}} {{__('messages.orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{__('messages.all')}}
                                            <span class="badge badge-info badge-pill ml-1">
                                                {{\App\Models\Order::All()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                       
                   @endif
                  
                <!-- End Orders -->
              
                    <!-- End Restaurant -->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="product section">Product Management</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    
                     @if(\App\CentralLogics\Helpers::module_permission_check('product'))
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/service*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="Product"
                            >
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{__('messages.products')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/product*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/product/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                       title="{{__('messages.add')}} {{__('messages.new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">{{__('messages.add')}} {{__('messages.new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.list')}}"
                                       title="Product {{__('messages.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.list')}}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif

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
                               
                            </ul>
                        </li>
                    @endif
                    <!-- End Category -->

                  
                    
                    @if (\App\CentralLogics\Helpers::module_permission_check('zone'))
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/zone*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.zone.home') }}" title="{{ __('messages.zone') }}">
                            <i class="tio-city nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            Delivery Zone</span>
                        </a>
                    </li>
                @endif
                   
            
              
                    <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{__('messages.customer_handle')}}">{{__('messages.marketing')}} {{__('messages.section')}}</small>
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

                <!-- FAQ -->

                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/faq*')?'active':''}}">
                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                       href="{{route('admin.faq.faq-add-new')}}" title="FAQ"
                    >
                        <i class="tio-gift nav-icon"></i>
                        <span
                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">FAQS</span>
                    </a>
                </li>
                <!-- FAQ -->
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

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/business-setup')?'active':'s'}}">
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
                        <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/config*') ? 'active' : '' }}">
                        <a class="nav-link " href="{{ route('admin.business-settings.config-setup') }}"
                            title="{{ __('messages.third_party_apis') }}">
                            <span class="tio-key nav-icon"></span>
                            <span class="text-truncate">{{ __('messages.third_party_apis') }}</span>
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
                                style="display: {{Request::is('admin/web-app-settings/pages*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('admin/web-app-settings//pages/terms-and-conditions')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.terms-and-conditions')}}"
                                       title="{{__('messages.terms_and_condition')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{__('messages.terms_and_condition')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/web-app-settings/pages/privacy-policy')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.privacy-policy')}}"
                                       title="Privacy Policy">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Privacy Policy</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/web-app-settings/pages/privacy-policy')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.refunds-and-returns-policy')}}"
                                       title="Returns and Refunds Policy">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Returns and Refunds Policy</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/web-app-settings/pages/privacy-policy')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.shipping-policy')}}"
                                       title="Shipping Policy">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Shipping Policy</span>
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


                         <!-- Employee-->

                         @if (\App\CentralLogics\Helpers::modules_permission_check(['custom_role', 'employee']))
                         <li class="nav-item">
                             <small class="nav-subtitle"
                                 title="{{ __('messages.employee_handle') }}">{{ __('messages.employee') }}
                                 {{ __('section') }}</small>
                             <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                         </li>
                     @endIf
 
                     @if (\App\CentralLogics\Helpers::module_permission_check('custom_role'))
                         <li
                             class="navbar-vertical-aside-has-menu {{ Request::is('admin/custom-role*') ? 'active' : '' }}">
                             <a class="js-navbar-vertical-aside-menu-link nav-link"
                                 href="{{ route('admin.custom-role.create') }}"
                                 title="Employee {{ __('messages.Role') }}">
                                 <i class="tio-incognito nav-icon"></i>
                                 <span
                                     class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Employee
                                     {{ __('messages.Role') }}</span>
                             </a>
                         </li>
                     @endif
 
                     @if (\App\CentralLogics\Helpers::module_permission_check('employee'))
                         <li
                             class="navbar-vertical-aside-has-menu {{ Request::is('admin/employee*') ? 'active' : '' }}">
                             <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                 title="Employee">
                                 <i class="tio-user nav-icon"></i>
                                 <span
                                     class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Employee</span>
                             </a>
                             <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                 style="display: {{ Request::is('admin/employee*') ? 'block' : 'none' }}">
                                 <li class="nav-item {{ Request::is('admin/employee/add-new') ? 'active' : '' }}">
                                     <a class="nav-link " href="{{ route('admin.employee.add-new') }}"
                                         title="{{ __('messages.add') }} {{ __('messages.new') }} {{ __('messages.Employee') }}">
                                         <span class="tio-circle nav-indicator-icon"></span>
                                         <span class="text-truncate">{{ __('messages.add') }}
                                             {{ __('messages.new') }}</span>
                                     </a>
                                 </li>
                                 <li class="nav-item {{ Request::is('admin/employee/list') ? 'active' : '' }}">
                                     <a class="nav-link " href="{{ route('admin.employee.list') }}"
                                         title="Employee {{ __('messages.list') }}">
                                         <span class="tio-circle nav-indicator-icon"></span>
                                         <span class="text-truncate">{{ __('messages.list') }}</span>
                                     </a>
                                 </li>
 
                             </ul>
                         </li>
                     @endif
                     <!-- End Employee -->    


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
