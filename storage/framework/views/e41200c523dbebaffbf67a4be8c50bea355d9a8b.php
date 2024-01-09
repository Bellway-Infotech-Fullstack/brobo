<?php $__currentLoopData = $userList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <th scope="row"><?php echo e($k+1); ?></th>
    <td class="text-capitalize"><?php echo e($e['name']); ?></td>
    <td >
        <?php echo e($e['email'] ?? 'N/A'); ?>

    </td>
    <td><?php echo e($e['mobile_number']); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/customer/partials/_rtable.blade.php ENDPATH**/ ?>