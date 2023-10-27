<div id="headerMain" class="d-none">
    <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
                <?php (@$restaurant_logo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value); ?>
                <a class="navbar-brand" href="<?php echo e(route('admin.dashboard')); ?>" aria-label="">
                    <img class="navbar-brand-logo" style="max-height: 48px; border-radius: 8px"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/img1.jpg')); ?>'"
                         src="<?php echo e(asset('storage/app/public/business/'.$restaurant_logo)); ?>" alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 48px; border-radius: 8px"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/assets/admin/img/160x160/img1.jpg')); ?>'"
                         src="<?php echo e(asset('storage/app/public/business/'.$restaurant_logo)); ?>" alt="Logo">
                </a>
                <!-- End Logo -->
            </div>

            <div class="navbar-nav-wrap-content-left">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">

                    <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="<?php echo e(route('admin.message.list')); ?>">
                                <i class="tio-notifications"></i>
                                <?php ($message=\App\Models\Conversation::where('checked',0)->count()); ?>
                                <?php if($message!=0): ?>
                                    <span class="btn-status btn-sm-status btn-status-danger"></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="<?php echo e(route('admin.order.list',['status'=>'pending'])); ?>">
                                
                                
                                <img src="<?php echo e(asset($assetPrefixPath . 'assets/admin/img/booking.png')); ?>" style="width: 20px; height: auto;" />
                                
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>


                    <li class="nav-item">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img"
                                         onerror="this.src='<?php echo e(asset($assetPrefixPath . 'assets/admin/img/160x160/img1.jpg')); ?>'"
                                         src="<?php echo e(asset('storage/app/public/admin')); ?>/<?php echo e(auth()->user()->id); ?>"
                                         alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                                 style="width: 16rem;">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img"
                                                 onerror="this.src='<?php echo e(asset($assetPrefixPath . 'assets/admin/img/160x160/img1.jpg')); ?>'"
                                                 src="<?php echo e(asset('storage/app/public/admin')); ?>/<?php echo e(auth()->user()->image); ?>"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5"><?php echo e(auth()->user()->name); ?></span>
                                            <span class="card-text"><?php echo e(auth()->user()->email); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="<?php echo e(route('admin.settings')); ?>">
                                    <span class="text-truncate pr-2" title="Settings"><?php echo e(__('messages.settings')); ?></span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: 'Do you want to logout?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#FC6A57',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='<?php echo e(route('admin.auth.logout')); ?>';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2" title="Sign out"><?php echo e(__('messages.sign_out')); ?></span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/layouts/admin/partials/_header.blade.php ENDPATH**/ ?>