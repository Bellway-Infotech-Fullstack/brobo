<?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$productData = \App\Models\Product::where('id' , $banner['product_id'])->first();
$appEnv = env('APP_ENV');
$assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
$storagePath = ($appEnv == 'local') ? asset('/storage/banner') : asset('/storage/app/public/banner');
?>
    <tr>
        <td><?php echo e($key+1); ?></td>
        <td><?php echo e($productData->name); ?></td>
        <td>
            <span class="media align-items-center">
                <img class="avatar avatar-lg mr-3" src="<?php echo e($storagePath); ?>/<?php echo e($banner->image); ?>" 
                      alt="<?php echo e($banner->name); ?> image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0"><?php echo e($banner['title']); ?></h5>
                </div>
            </span>
        <span class="d-block font-size-sm text-body"> </span>
        </td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="statusCheckbox<?php echo e($banner->id); ?>">
                <input type="checkbox" onclick="location.href='<?php echo e(route('admin.banner.status',[$banner['id'],$banner->status?0:1])); ?>'" class="toggle-switch-input" id="statusCheckbox<?php echo e($banner->id); ?>" <?php echo e($banner->status?'checked':''); ?>>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>
        <td>
            <a class="btn btn-sm btn-white" href="<?php echo e(route('admin.banner.edit',[$banner['id']])); ?>"title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.banner')); ?>"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn-white" href="javascript:" onclick="form_alert('banner-<?php echo e($banner['id']); ?>','Want to delete this banner ?')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.banner')); ?>"><i class="tio-delete-outlined"></i>
            </a>
            <form action="<?php echo e(route('admin.banner.delete',[$banner['id']])); ?>"
                        method="post" id="banner-<?php echo e($banner['id']); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
            </form>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/banner/partials/_table.blade.php ENDPATH**/ ?>