<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$food): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($key+1); ?></td>
        <?php
                
                                     $productImageoPath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $food['image']) : asset('storage/app/public/product/' . $food['image']);        
                                    ?>
                                    <td>
                                        <a class="media align-items-center" href="<?php echo e(route('admin.product.view',[$food['id']])); ?>">
                                            <img class="avatar avatar-lg mr-3" src="<?php echo e($productImageoPath); ?>" 
                                                  alt="<?php echo e($food->name); ?> image">
                                            <div class="media-body">
                                                <h5 class="text-hover-primary mb-0"><?php echo e(Str::limit($food['name'],20,'...')); ?></h5>
                                            </div>
                                        </a>
                                    </td>
        <td>
        <?php echo e(Str::limit($food->category,20,'...')); ?>

        </td>
       
        <td><?php echo e(\App\CentralLogics\Helpers::format_currency($food['price'])); ?></td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($food->id); ?>">
                <input type="checkbox" onclick="location.href='<?php echo e(route('admin.product.status',[$food['id'],$food->status?0:1])); ?>'"class="toggle-switch-input" id="stocksCheckbox<?php echo e($food->id); ?>" <?php echo e($food->status?'checked':''); ?>>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>
        <td>
            <a class="btn btn-sm btn-white"
                href="<?php echo e(route('admin.product.edit',[$food['id']])); ?>" title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.food')); ?>"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn-white" href="javascript:"
                onclick="form_alert('food-<?php echo e($food['id']); ?>','Want to delete this item ?')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.product')); ?>"><i class="tio-delete-outlined"></i>
            </a>
            <form action="<?php echo e(route('admin.product.delete',[$food['id']])); ?>"
                    method="post" id="food-<?php echo e($food['id']); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
            </form>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/product/partials/_table.blade.php ENDPATH**/ ?>