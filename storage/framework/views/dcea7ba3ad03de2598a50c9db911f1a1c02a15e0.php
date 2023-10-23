<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-users-switch"></i> <?php echo e(trans('messages.top_customers')); ?>

    </h5>
    <?php ($params=session('dash_params')); ?>
    <?php if($params['zone_id']!='all'): ?>
        <?php ($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name); ?>
    <?php else: ?>
        <?php ($zone_name='All'); ?>
    <?php endif; ?>
    <label class="badge badge-soft-info">( Zone : <?php echo e($zone_name); ?> )</label>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        <?php $__currentLoopData = $top_customer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-4 mt-2"
                 onclick="location.href='<?php echo e(route('admin.customer.view',[$item['user_id']])); ?>'"
                 style="padding-left: 6px;padding-right: 6px;cursor: pointer">
                <div class="grid-card" style="min-height: 170px">
                    <label class="label_1">Bookings : <?php echo e($item['count']); ?></label>
                    <center class="mt-6">
                        <img style="border-radius: 50%;width: 60px;height: 60px"
                             onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img1.jpg')); ?>'"
                             src="<?php echo e(asset('storage/app/public/profile/'.$item->customer->image??'')); ?>">
                    </center>
                    <div class="text-center mt-2">
                                            <span class=""
                                                  style="font-size: 10px"><?php echo e($item->customer['f_name']??'Not exist'); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<!-- End Body -->
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/partials/_top-customer.blade.php ENDPATH**/ ?>