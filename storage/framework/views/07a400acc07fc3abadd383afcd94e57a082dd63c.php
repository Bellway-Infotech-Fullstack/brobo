<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-align-to-top"></i> <?php echo e(trans('messages.top_selling_Services')); ?>

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
        <?php $__currentLoopData = $top_sell; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 col-sm-6 mt-2"
                 onclick="location.href='<?php echo e(route('admin.service.view',[$item['service_id']])); ?>'"
                 style="cursor: pointer;padding-right: 6px;padding-left: 6px">
                <div class="grid-card">
                    <label class="label_1">Booked : <?php echo e($item['count']); ?></label>
                    <img style="width: 100%;height: 120px"
                         src="<?php echo e(asset('storage/app/public/product')); ?>/<?php echo e($item->service['image']); ?>"
                         onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img2.jpg')); ?>'"
                         alt="<?php echo e($item->service->name); ?> image">
                    <div class="text-center mt-2">
                        <span class="" style="font-size: 10px"><?php echo e($item->service['name']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<!-- End Body -->
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/partials/_top-selling-foods.blade.php ENDPATH**/ ?>