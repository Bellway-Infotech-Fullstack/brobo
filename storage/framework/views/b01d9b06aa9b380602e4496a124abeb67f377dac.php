<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-star"></i> <?php echo e(trans('messages.top_rated_Services')); ?>

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
        <div class="col-12">
            <table class="table">
                <tbody>
                <?php $__currentLoopData = $top_rated_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($service=\App\Models\Service::find($item['service_id'])); ?>
                    <tr onclick="location.href='<?php echo e(route('admin.service.view',['id' => $item['service_id']])); ?>'"
                        style="cursor: pointer">
                        <td scope="row">
                            <img height="35" style="border-radius: 5px"
                                 src="<?php echo e(asset('storage/app/public/product')); ?>/<?php echo e($service['image']); ?>"
                                 onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img2.jpg')); ?>'"
                                 alt="<?php echo e($service->name); ?> image">
                            <span class="ml-2">
                                                    <?php echo e($service->name??'Not exist!'); ?>

                                                </span>
                        </td>
                        <td>
                                                <span style="font-size: 18px">
                                                    <?php echo e(round($item['ratings_average'],2)); ?> <i style="color: gold" class="tio-star"></i>
                                                </span>
                        </td>
                        <td>
                                                  <span style="font-size: 18px">
                                                    <?php echo e($item['total']); ?> <i class="tio-users-switch"></i>
                                                  </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End Body -->
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/partials/_top-rated-foods.blade.php ENDPATH**/ ?>