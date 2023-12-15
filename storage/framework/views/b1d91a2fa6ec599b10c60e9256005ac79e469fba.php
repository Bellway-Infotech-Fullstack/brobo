<?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <th scope="row"><?php echo e($k+1); ?></th>
    <td class="text-capitalize"><?php echo e($e['name']); ?></td>
    <td >
        <?php echo e($e['email']); ?>

    </td>
    <td><?php echo e($e['mobile_number']); ?></td>
    <td>
        <a class="btn btn-sm btn-white"
            href="<?php echo e(route('admin.customer.edit',[$e['id']])); ?>" title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.customer')); ?>"><i class="tio-edit"></i>
        </a>
        <a class="btn btn-sm btn-danger" href="javascript:"
            onclick="form_alert('employee-<?php echo e($e['id']); ?>','<?php echo e(__('messages.Want_to_delete_this_role')); ?>')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.customer')); ?>"><i class="tio-delete-outlined"></i>
        </a>
        <form action="<?php echo e(route('admin.customer.delete',[$e['id']])); ?>"
                method="post" id="employee-<?php echo e($e['id']); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
        </form>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/customer/partials/_table.blade.php ENDPATH**/ ?>