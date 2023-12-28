<?php $__env->startSection('title','Payment Setup'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.gateway')); ?> <?php echo e(__('messages.setup')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row" style="padding-bottom: 20px">
            <div class="col-md-6">
               
            </div>
           
        </div>

        <div class="row digital_payment_methods" style="padding-bottom: 20px">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center"><?php echo e(__('messages.razorpay')); ?></h5>
                        <?php ($config=\App\CentralLogics\Helpers::get_business_settings('razor_pay')); ?>
                        <form
                            action="<?php echo e(env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['razor_pay']):'javascript:'); ?>"
                            method="post">
                            <?php echo csrf_field(); ?>
                                <div class="form-group mb-2">
                                    <label class="control-label"><?php echo e(__('messages.razorpay')); ?></label>
                                </div>
                                <div class="form-group mb-2 mt-2">
                                    <input type="radio" name="status" value="1" <?php echo e($config?($config['status']==1?'checked':''):''); ?>>
                                    <label style="padding-left: 10px"><?php echo e(__('messages.active')); ?></label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <input type="radio" name="status" value="0" <?php echo e($config?($config['status']==0?'checked':''):''); ?>>
                                    <label
                                        style="padding-left: 10px"><?php echo e(__('messages.inactive')); ?></label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <label style="padding-left: 10px"><?php echo e(__('messages.razorkey')); ?></label><br>
                                    <input type="text" class="form-control" name="razor_key"
                                           value="<?php echo e(env('APP_MODE')!='demo'?($config?$config['razor_key']:''):''); ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label style="padding-left: 10px"><?php echo e(__('messages.razorsecret')); ?></label><br>
                                    <input type="text" class="form-control" name="razor_secret"
                                           value="<?php echo e(env('APP_MODE')!='demo'?($config?$config['razor_secret']:''):''); ?>">
                                </div>
                                <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>"
                                        onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>"
                                        class="btn btn-primary mb-2"><?php echo e(__('messages.save')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center"><?php echo e(__('messages.paypal')); ?></h5>
                        <?php ($config=\App\CentralLogics\Helpers::get_business_settings('paypal')); ?>
                        <form
                            action="<?php echo e(env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['paypal']):'javascript:'); ?>"
                            method="post">
                            <?php echo csrf_field(); ?>
                                <div class="form-group mb-2">
                                    <label class="control-label"><?php echo e(__('messages.paypal')); ?></label>
                                </div>
                                <div class="form-group mb-2 mt-2">
                                    <input type="radio" name="status" value="1" <?php echo e($config?($config['status']==1?'checked':''):''); ?>>
                                    <label style="padding-left: 10px"><?php echo e(__('messages.active')); ?></label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <input type="radio" name="status" value="0" <?php echo e($config?($config['status']==0?'checked':''):''); ?>>
                                    <label style="padding-left: 10px"><?php echo e(__('messages.inactive')); ?></label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <label
                                        style="padding-left: 10px"><?php echo e(__('messages.paypal')); ?> <?php echo e(__('messages.client')); ?> <?php echo e(__('messages.id')); ?></label><br>
                                    <input type="text" class="form-control" name="paypal_client_id"
                                           value="<?php echo e(env('APP_MODE')!='demo'?($config?$config['paypal_client_id']:''):''); ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label style="padding-left: 10px"><?php echo e(__('messages.paypalsecret')); ?> </label><br>
                                    <input type="text" class="form-control" name="paypal_secret"
                                           value="<?php echo e(env('APP_MODE')!='demo'?$config['paypal_secret']??'':''); ?>">
                                </div>
                                <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>"
                                        onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>"
                                        class="btn btn-primary mb-2"><?php echo e(__('messages.save')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pt-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center"><?php echo e(__('messages.stripe')); ?></h5>
                        <?php ($config=\App\CentralLogics\Helpers::get_business_settings('stripe')); ?>
                        <form action="<?php echo e(env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['stripe']):'javascript:'); ?>"
                              method="post">
                            <?php echo csrf_field(); ?>
                                <div class="form-group mb-2">
                                    <label class="control-label"><?php echo e(__('messages.stripe')); ?></label>
                                </div>
                                <div class="form-group mb-2 mt-2">
                                    <input type="radio" name="status" value="1" <?php echo e($config?($config['status']==1?'checked':''):''); ?>>
                                    <label style="padding-left: 10px"><?php echo e(__('messages.active')); ?></label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <input type="radio" name="status" value="0" <?php echo e($config?($config['status']==0?'checked':''):''); ?>>
                                    <label style="padding-left: 10px"><?php echo e(__('messages.inactive')); ?> </label>
                                    <br>
                                </div>
                                <div class="form-group mb-2">
                                    <label
                                        style="padding-left: 10px"><?php echo e(__('messages.published')); ?> <?php echo e(__('messages.key')); ?></label><br>
                                    <input type="text" class="form-control" name="published_key"
                                           value="<?php echo e(env('APP_MODE')!='demo'?($config?$config['published_key']:''):''); ?>">
                                </div>

                                <div class="form-group mb-2">
                                    <label
                                        style="padding-left: 10px"><?php echo e(__('messages.api')); ?> <?php echo e(__('messages.key')); ?></label><br>
                                    <input type="text" class="form-control" name="api_key"
                                           value="<?php echo e(env('APP_MODE')!='demo'?($config?$config['api_key']:''):''); ?>">
                                </div>
                                <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>" onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>" class="btn btn-primary mb-2"><?php echo e(__('messages.save')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script>
     $('.digital_payment_methods').show();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/business-settings/payment-index.blade.php ENDPATH**/ ?>