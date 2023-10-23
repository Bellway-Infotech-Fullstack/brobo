<?php $__env->startSection('title','Product Preview'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>
<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-6">
                    <h1 class="page-header-title"><?php echo e($product['name']); ?></h1>
                </div>
                <div class="col-6">
                    <a href="<?php echo e(route('admin.product.edit',[$product['id']])); ?>" class="btn btn-primary float-right">
                        <i class="tio-edit"></i> <?php echo e(__('messages.edit')); ?>

                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">
                

                  
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-sm-4 col-12 pt-2">
                        <h4 class="border-bottom"><?php echo e($product['name']); ?></h4>
                        <span><?php echo e(__('messages.price')); ?> :
                            <span><?php echo e(\App\CentralLogics\Helpers::format_currency($product['price'])); ?></span>
                        </span><br>
                        <span><?php echo e(__('messages.tax')); ?> :
                            <span><?php echo e(\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::tax_calculate($product,$product['price']))); ?></span>
                        </span><br>
                        <span><?php echo e(__('messages.discount')); ?> :
                            <span><?php echo e(\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product,$product['price']))); ?></span>
                        </span><br>
                        <span>
                            Total Stock  <?php echo e($product['total_stock']); ?>

                        </span><br>
                      
                      
                    </div>

                    <div class="col-sm-8 col-12 pt-2 border-left">
                        <h4><?php echo e(__('messages.short')); ?> <?php echo e(__('messages.description')); ?> : </h4>
                        <?php echo $product['description']; ?>

                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

  
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script>
    function status_form_alert(id, message, e) {
        e.preventDefault();
        Swal.fire({
            title: '<?php echo e(__('messages.are_you_sure')); ?>',   
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/product/view.blade.php ENDPATH**/ ?>