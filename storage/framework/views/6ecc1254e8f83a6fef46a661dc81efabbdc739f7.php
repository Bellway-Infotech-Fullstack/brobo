<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($key+1); ?></td>
    <td><?php echo e($category->id); ?></td>
    <td>
        <span class="d-block font-size-sm text-body">
            <?php echo e($category->parent->name ?? 'N/A'); ?>

        </span>
    </td>
    <td>
    <span class="d-block font-size-sm text-body">
        <?php echo e($category['name']); ?>

    </span>
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($category->id); ?>">
        <input type="checkbox" onclick="location.href='<?php echo e(route('admin.category.status',[$category['id'],$category->status?0:1])); ?>'"class="toggle-switch-input" id="stocksCheckbox<?php echo e($category->id); ?>" <?php echo e($category->status?'checked':''); ?>>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td>
  
    <td>
        <a class="btn btn-sm btn-white"
            href="<?php echo e(route('admin.category.edit',[$category['id']])); ?>" title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.category')); ?>"><i class="tio-edit"></i>
        </a>
        <a class="btn btn-sm btn-white" href="javascript:"
        onclick="form_alert('category-<?php echo e($category['id']); ?>','Want to delete this category')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.category')); ?>"><i class="tio-delete-outlined"></i>
        </a>
        <form action="<?php echo e(route('admin.category.delete',[$category['id']])); ?>" method="post" id="category-<?php echo e($category['id']); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
        </form>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/category/partials/_subcategory_table.blade.php ENDPATH**/ ?>