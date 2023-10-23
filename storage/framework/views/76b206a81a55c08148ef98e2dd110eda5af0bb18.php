<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
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
                <?php (@$restaurant_logo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value); ?>
                <a class="navbar-brand" href="<?php echo e(route('admin.dashboard')); ?>" aria-label="Front">
                    <img class="navbar-brand-logo" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')); ?>'"
                         src="<?php echo e(asset('storage/app/public/business/'.$restaurant_logo)); ?>"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')); ?>'"
                         src="<?php echo e(asset('storage/app/public/business/'.$restaurant_logo)); ?>" alt="Logo">
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
                    <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin')?'show':''); ?>">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                           href="<?php echo e(route('admin.dashboard')); ?>" title="<?php echo e(__('messages.dashboard')); ?>">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                <?php echo e(__('messages.dashboard')); ?>

                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->
                    
                       <!-- Employee-->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="<?php echo e(__('messages.employee_handle')); ?>"><?php echo e(__('messages.employee')); ?> <?php echo e(__('section')); ?></small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

          

                    <?php if(\App\CentralLogics\Helpers::module_permission_check('employee')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/employee*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:"
                               title="<?php echo e(__('messages.Employee')); ?>">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.employees')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/employee*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/employee/add-new')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.employee.add-new')); ?>"
                                       title="<?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?> <?php echo e(__('messages.Employee')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate"><?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/employee/list')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.employee.list')); ?>"
                                       title="<?php echo e(__('messages.Employee')); ?> <?php echo e(__('messages.list')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.list')); ?></span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                <?php endif; ?>
                <!-- End Employee -->


                    <!-- Orders -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('order')): ?>
                        <li class="nav-item">
                            <small
                                class="nav-subtitle"><?php echo e(__('messages.booking')); ?> <?php echo e(__('messages.section')); ?></small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/order*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="<?php echo e(__('messages.order')); ?>">
                                
                                <img src="<?php echo e(asset($assetPrefixPath . '/assets/admin/img/booking.png')); ?>" style="width: 20px; height: auto;filter: invert(100%);" /> &nbsp; &nbsp;
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    <?php echo e(__('messages.bookings')); ?>

                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/order*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/pending')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['pending'])); ?>"
                                       title="<?php echo e(__('messages.pending')); ?> <?php echo e(__('messages.orders')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            <?php echo e(__('messages.pending')); ?>

                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::Pending()->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item <?php echo e(Request::is('admin/order/list/accepted')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['accepted'])); ?>"
                                       title="<?php echo e(__('messages.acceptedbyDM')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        <?php echo e(__('messages.accepted')); ?>

                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::Accepted()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/processing')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['processing'])); ?>"
                                       title="<?php echo e(__('messages.preparingInRestaurants')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            <?php echo e(__('messages.processing')); ?>

                                                <span class="badge badge-warning badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::Preparing()->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/services_ongoing')?'active':''); ?>">
                                    <a class="nav-link text-capitalize"
                                       href="<?php echo e(route('admin.order.list',['services_ongoing'])); ?>"
                                       title="<?php echo e(__('messages.serviceOngoing')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            <?php echo e(__('messages.serviceOngoing')); ?>

                                                <span class="badge badge-warning badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::ServiceOngoing()->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/delivered')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['completed'])); ?>"
                                       title="<?php echo e(__('messages.delivered')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        <?php echo e(__('messages.completed')); ?>

                                            <span class="badge badge-success badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::Delivered()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/canceled')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['canceled'])); ?>"
                                       title="<?php echo e(__('messages.canceled')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        <?php echo e(__('messages.canceled')); ?>

                                            <span class="badge badge-soft-warning bg-light badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::Canceled()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/failed')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['failed'])); ?>"
                                       title="<?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.failed')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize">
                                        <?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.failed')); ?>

                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::failed()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/refunded')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['refunded'])); ?>"
                                       title="<?php echo e(__('messages.refunded')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        <?php echo e(__('messages.refunded')); ?>

                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::Refunded()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>

                                <li class="nav-item <?php echo e(Request::is('admin/order/list/scheduled')?'active':''); ?>">
                                    <a class="nav-link" href="<?php echo e(route('admin.order.list',['scheduled'])); ?>"
                                       title="<?php echo e(__('messages.scheduled')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                        <?php echo e(__('messages.scheduled')); ?>

                                        <span class="badge badge-info badge-pill ml-1">
                                            <?php echo e(\App\Models\Order::Scheduled()->count()); ?>

                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/all')?'active':''); ?>">
                                    <a class="nav-link" href="<?php echo e(route('admin.order.list',['all'])); ?>"
                                       title="<?php echo e(__('messages.all')); ?> <?php echo e(__('messages.orders')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            <?php echo e(__('messages.all')); ?>

                                            <span class="badge badge-info badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::whereIn('order_status',['pending', 'failed', 'canceled', 'services_ongoing', 'picked_up', 'service_ongoing', 'processing', 'accepted', 'delivered', 'completed', 'refunded'])->orWhere('scheduled', 1)->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order dispachment -->
                   
                        <!-- Order dispachment End-->
                    <?php endif; ?>
                <!-- End Orders -->
              
                    <!-- End Restaurant -->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="<?php echo e(__('messages.service')); ?> <?php echo e(__('messages.section')); ?>"><?php echo e(__('messages.service')); ?> <?php echo e(__('messages.management')); ?></small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    
                    

                    <!-- Category -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('category')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/category*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="<?php echo e(__('messages.category')); ?>"
                            >
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.categories')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/category*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/category/add')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.category.add')); ?>"
                                       title="<?php echo e(__('messages.category')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.category')); ?></span>
                                    </a>
                                </li>

                                <li class="nav-item <?php echo e(Request::is('admin/category/add-sub-category')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.category.add-sub-category')); ?>"
                                       title="<?php echo e(__('messages.sub_category')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.sub_category')); ?></span>
                                    </a>
                                </li>

                                
                                <li class="nav-item <?php echo e(Request::is('admin/category/bulk-import')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.category.bulk-import')); ?>"
                                       title="<?php echo e(__('messages.bulk_import')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize"><?php echo e(__('messages.bulk_import')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/category/bulk-export')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.category.bulk-export-index')); ?>"
                                       title="<?php echo e(__('messages.bulk_export')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize"><?php echo e(__('messages.bulk_export')); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!-- End Category -->

                    <!-- Attributes -->
                  
                    <!-- End Attributes -->
                    
                    

                    <!-- AddOn -->
                   
                <!-- End AddOn -->
                    <!-- Food -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('service')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/service*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="<?php echo e(__('messages.service')); ?>"
                            >
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.services')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/service*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/service/add-new')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.service.add-new')); ?>"
                                       title="<?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate"><?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/service/list')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.service.list')); ?>"
                                       title="<?php echo e(__('messages.service')); ?> <?php echo e(__('messages.list')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.list')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/service/bulk-import')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.service.bulk-import')); ?>"
                                       title="<?php echo e(__('messages.bulk_import')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize"><?php echo e(__('messages.bulk_import')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/service/bulk-export')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.service.bulk-export-index')); ?>"
                                       title="<?php echo e(__('messages.bulk_export')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate text-capitalize"><?php echo e(__('messages.bulk_export')); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <!-- End Food -->
                <!-- DeliveryMan -->
                 
                <!-- End DeliveryMan -->
                    <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="<?php echo e(__('messages.employee_handle')); ?>"><?php echo e(__('messages.marketing')); ?> <?php echo e(__('messages.section')); ?></small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Banner -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('banner')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/banner*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="<?php echo e(route('admin.banner.add-new')); ?>" title="<?php echo e(__('messages.banner')); ?>"
                            >
                                <i class="tio-image nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.banners')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <!-- End Banner -->
                    <!-- Coupon -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('coupon')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/coupon*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="<?php echo e(route('admin.coupon.add-new')); ?>" title="<?php echo e(__('messages.coupon')); ?>"
                            >
                                <i class="tio-gift nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.coupons')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <!-- End Coupon -->
                    <!-- Notification -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('notification')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/notification*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="<?php echo e(route('admin.notification.add-new')); ?>"
                               title="<?php echo e(__('messages.send')); ?> <?php echo e(__('messages.notification')); ?>"
                            >
                                <i class="tio-notifications nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    <?php echo e(__('messages.push')); ?> <?php echo e(__('messages.notification')); ?>

                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                <!-- End Notification -->

                    <!-- End marketing section -->

                <!-- End account -->

                    <!-- provide_dm_earning -->
                   
                <!-- End provide_dm_earning -->
                    <!-- Custommer -->
               
                <!-- End Custommer -->

                    <!-- Business Settings -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('settings')): ?>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="<?php echo e(__('messages.business')); ?> <?php echo e(__('messages.settings')); ?>"><?php echo e(__('messages.business')); ?> <?php echo e(__('messages.settings')); ?></small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/business-setup')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.business-setup')); ?>"
                               title="<?php echo e(__('messages.business')); ?> <?php echo e(__('messages.setup')); ?>"
                            >
                                <span class="tio-settings nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.business')); ?> <?php echo e(__('messages.setup')); ?></span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/payment-method')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.payment-method')); ?>"
                               title="<?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.methods')); ?>"
                            >
                                <span class="tio-atm nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.methods')); ?></span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/mail-config')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.mail-config')); ?>"
                               title="<?php echo e(__('messages.mail')); ?> <?php echo e(__('messages.config')); ?>"
                            >
                                <span class="tio-email nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.mail')); ?> <?php echo e(__('messages.config')); ?></span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/sms-module')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.sms-module')); ?>"
                               title="<?php echo e(__('messages.sms')); ?> <?php echo e(__('messages.module')); ?>">
                                <span class="tio-message nav-icon"></span>
                                <span class="text-truncate"><?php echo e(__('messages.sms')); ?> <?php echo e(__('messages.module')); ?></span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/fcm-index')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.fcm-index')); ?>"
                               title="<?php echo e(__('messages.push')); ?> <?php echo e(__('messages.notification')); ?>">
                                <span class="tio-notifications nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.notification')); ?> <?php echo e(__('messages.settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <!-- End Business Settings -->

                    <!-- web & adpp Settings -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('settings')): ?>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="<?php echo e(__('messages.business')); ?> <?php echo e(__('messages.settings')); ?>"><?php echo e(__('messages.web_and_app')); ?> <?php echo e(__('messages.settings')); ?></small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/app-settings*')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.app-settings')); ?>"
                               title="<?php echo e(__('messages.app_settings')); ?>"
                            >
                                <span class="tio-android nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.app_settings')); ?></span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/landing-page-settings*')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.business-settings.landing-page-settings', 'index')); ?>"
                               title="<?php echo e(__('messages.landing_page_settings')); ?>"
                            >
                                <span class="tio-website nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.landing_page_settings')); ?></span>
                            </a>
                        </li>
                     

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/pages*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="<?php echo e(__('messages.pages')); ?> <?php echo e(__('messages.setup')); ?>"
                            >
                                <i class="tio-pages nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.pages')); ?> <?php echo e(__('messages.setup')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/business-settings/pages*')?'block':'none'); ?>">

                                <li class="nav-item <?php echo e(Request::is('admin/business-settings/pages/terms-and-conditions')?'active':''); ?>">
                                    <a class="nav-link "
                                       href="<?php echo e(route('admin.business-settings.terms-and-conditions')); ?>"
                                       title="<?php echo e(__('messages.terms_and_condition')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.terms_and_condition')); ?></span>
                                    </a>
                                </li>

                                <li class="nav-item <?php echo e(Request::is('admin/business-settings/pages/privacy-policy')?'active':''); ?>">
                                    <a class="nav-link "
                                       href="<?php echo e(route('admin.business-settings.privacy-policy')); ?>"
                                       title="<?php echo e(__('messages.privacy_policy')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.privacy_policy')); ?></span>
                                    </a>
                                </li>

                                <li class="nav-item <?php echo e(Request::is('admin/business-settings/pages/about-us')?'active':''); ?>">
                                    <a class="nav-link "
                                       href="<?php echo e(route('admin.business-settings.about-us')); ?>"
                                       title="<?php echo e(__('messages.about_us')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.about_us')); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/file-manager*')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.file-manager.index')); ?>"
                               title="<?php echo e(__('messages.third_party_apis')); ?>"
                            >
                                <span class="tio-album nav-icon"></span>
                                <span
                                    class="text-truncate text-capitalize"><?php echo e(__('messages.gallery')); ?></span>
                            </a>
                        </li>

                     

                    <?php endif; ?>
                <!-- End web & adpp Settings -->

                    <!-- Report -->
                    <?php if(\App\CentralLogics\Helpers::module_permission_check('report')): ?>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="<?php echo e(__('messages.report_and_analytics')); ?>"><?php echo e(__('messages.report_and_analytics')); ?></small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/report/day-wise-report')?'active':''); ?>">
                            <a class="nav-link " href="<?php echo e(route('admin.report.day-wise-report')); ?>"
                               title="<?php echo e(__('messages.day_wise_report')); ?>">
                                <span class="tio-report nav-icon"></span>
                                <span
                                    class="text-truncate"><?php echo e(__('messages.day_wise_report')); ?></span>
                            </a>
                        </li>

                        
                    <?php endif; ?>

             


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
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/layouts/admin/partials/_sidebar.blade.php ENDPATH**/ ?>