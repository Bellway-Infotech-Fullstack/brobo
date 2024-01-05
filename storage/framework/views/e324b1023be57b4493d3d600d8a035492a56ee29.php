<div class="col-12">
    <?php ($params=session('dash_params')); ?>
    <?php if($params['zone_id']!='all'): ?>
        <?php ($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name); ?>
    <?php else: ?>
        <?php ($zone_name='All'); ?>
    <?php endif; ?>
    <label class="badge badge-soft-info">( Zone : <?php echo e($zone_name); ?> )</label>
</div>

<div class="col-sm-4 col-lg-4 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="<?php echo e(route('admin.order.list',['all'])); ?>"
       style="background: #54436B">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">Total Orders</h6>
            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                            <span class="card-title h2" style="color: white!important;">
                                        <?php echo e($total_orders); ?> 
                                </span>
                </div>
                <div class="col-6 mt-2">
                    <i class="tio-man" style="font-size: 30px;color: white"></i>
                
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-4 col-lg-4 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="<?php echo e(route('admin.customer.list')); ?>"
       style="background: #402218">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">Total Users</h6>

            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                        <span class="card-title h2" style="color: white!important;">
                            <?php echo e($total_users); ?>

                        </span>
                </div>

                <div class="col-6 mt-2">
                    <i class="tio-man" style="font-size: 30px;color: white"></i>
               
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-4 col-lg-4 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="<?php echo e(route('admin.order.list',['all'])); ?>"
       style="background: #334257">
        <div class="card-body">
            <h6 class="card-subtitle"
                style="color: white!important;">Total Sales</h6>

            <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                    <span class="card-title h2" style="color: white!important;">
                        Rs. <?php echo e(number_format($total_sales)); ?>

                    </span>
                </div>

                <div class="col-6 mt-2">
                    <i class="tio-bike" style="font-size: 30px;color: white"></i>
                  
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
                     onclick="location.href='<?php echo e(route('admin.order.list',['completed'])); ?>'">
                    <div class="media-body">
                        <h6 class="card-subtitle"><?php echo e(__('messages.delivered')); ?></h6>
                        <span class="card-title h3">
                                             <?php echo e($data['completed']); ?>

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
                     onclick="location.href='<?php echo e(route('admin.order.list',['cancelled'])); ?>'">
                    <div class="media-body">
                        <h6 class="card-subtitle">Cancelled</h6>
                        <span
                            class="card-title h3"><?php echo e($data['cancelled']); ?></span>
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
                     onclick="location.href='<?php echo e(route('admin.order.list',['failed'])); ?>'">
                    <div class="media-body">
                        <h6 class="card-subtitle"><?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.failed')); ?></h6>
                        <span
                            class="card-title h3"><?php echo e($data['refund_requested']); ?></span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                                          <i class="tio-hand-wave"></i>
                                        </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-sm">
                <div class="media" style="cursor: pointer"
                     onclick="location.href='<?php echo e(route('admin.order.list',['refunded'])); ?>'">
                    <div class="media-body">
                        <h6 class="card-subtitle"><?php echo e(__('messages.refunded')); ?></h6>
                        <span
                            class="card-title h3"><?php echo e($data['refunded']); ?></span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary icon-circle ml-3">
                                          <i class="tio-history"></i>
                                        </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/partials/_dashboard-order-stats.blade.php ENDPATH**/ ?>