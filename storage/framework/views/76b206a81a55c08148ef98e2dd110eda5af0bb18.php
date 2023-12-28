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
                
                <?php
                
                     $logoPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $restaurant_logo) : asset('storage/app/public/business/' . $restaurant_logo);        
                ?>
                <a class="navbar-brand" href="<?php echo e(route('admin.dashboard')); ?>" aria-label="Front">
                    <img class="navbar-brand-logo" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')); ?>'"
                         src="<?php echo e($logoPath); ?>"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 55px; max-width: 100%!important;"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/logo2.png')); ?>'"
                         src="<?php echo e($logoPath); ?>" alt="Logo">
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
                    
                       <!-- customer-->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="<?php echo e(__('messages.customer_handle')); ?>"><?php echo e(__('messages.customer')); ?> <?php echo e(__('section')); ?></small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

          

                    <?php if(\App\CentralLogics\Helpers::module_permission_check('customer')): ?>
                        <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/customer*')?'active':''); ?>">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:"
                               title="<?php echo e(__('messages.customer')); ?>">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.customers')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/customer*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/customer/add-new')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.customer.add-new')); ?>"
                                       title="<?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?> <?php echo e(__('messages.customer')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate"><?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/customer/list')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.customer.list')); ?>"
                                       title="<?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.list')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.list')); ?></span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                <?php endif; ?>
                <!-- End customer -->


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
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/ongoing')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['pending'])); ?>"
                                       title="Ongoing <?php echo e(__('messages.orders')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Ongoing
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::Pending()->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/ongoing')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['pending'])); ?>"
                                       title="Cancelled <?php echo e(__('messages.orders')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Cancelled
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::Pending()->count()); ?>

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/order/list/ongoing')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.order.list',['pending'])); ?>"
                                       title="Delivered <?php echo e(__('messages.orders')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            Delivered
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                <?php echo e(\App\Models\Order::Pending()->count()); ?>

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
                                                <?php echo e(\App\Models\Order::whereIn('status',['pending', 'failed', 'canceled', 'services_ongoing', 'picked_up', 'service_ongoing', 'processing', 'accepted', 'delivered', 'completed', 'refunded'])->count()); ?>

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
                               title="<?php echo e(__('messages.service')); ?> <?php echo e(__('messages.section')); ?>">Service Management</small>
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
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"><?php echo e(__('messages.products')); ?></span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: <?php echo e(Request::is('admin/product*')?'block':'none'); ?>">
                                <li class="nav-item <?php echo e(Request::is('admin/service/add-new')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.product.add-new')); ?>"
                                       title="<?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate"><?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo e(Request::is('admin/product/list')?'active':''); ?>">
                                    <a class="nav-link " href="<?php echo e(route('admin.product.list')); ?>"
                                       title="<?php echo e(__('messages.service')); ?> <?php echo e(__('messages.list')); ?>">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate"><?php echo e(__('messages.list')); ?></span>
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
                               title="<?php echo e(__('messages.customer_handle')); ?>"><?php echo e(__('messages.marketing')); ?> <?php echo e(__('messages.section')); ?></small>
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

                <!-- FAQ -->

                <li class="navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/faq*')?'active':''); ?>">
                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                       href="<?php echo e(route('admin.faq.faq-add-new')); ?>" title="FAQ"
                    >
                        <i class="tio-gift nav-icon"></i>
                        <span
                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">FAQS</span>
                    </a>
                </li>
                <!-- FAQ -->
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
                        <li class="d-none navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/business-settings/landing-page-settings*')?'active':''); ?>">
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

                        <li class="d-none navbar-vertical-aside-has-menu <?php echo e(Request::is('admin/file-manager*')?'active':''); ?>">
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