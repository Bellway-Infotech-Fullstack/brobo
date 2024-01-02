<?php $__env->startSection('title','Settings'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><?php echo e(__('messages.smtp')); ?> <?php echo e(__('messages.mail')); ?> <?php echo e(__('messages.setup')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
        <?php ($config=\App\Models\BusinessSetting::where(['key'=>'mail_config'])->first()); ?>
            <?php ($data=$config?json_decode($config['value'],true):null); ?>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="<?php echo e(env('APP_MODE')!='demo'?route('admin.business-settings.mail-config'):'javascript:'); ?>" method="post"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.mailer')); ?> <?php echo e(__('messages.name')); ?></label><br>
                            <input type="text" placeholder="ex : Alex" class="form-control" name="name"
                                   value="<?php echo e(env('APP_MODE')!='demo'?$data['name']??'':''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.host')); ?></label><br>
                            <input type="text" class="form-control" name="host" value="<?php echo e(env('APP_MODE')!='demo'?$data['host']??'':''); ?>" required>
                        </div>
                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.driver')); ?></label><br>
                            <input type="text" class="form-control" name="driver" value="<?php echo e(env('APP_MODE')!='demo'?$data['driver']??'':''); ?>" required>
                        </div>
                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.port')); ?></label><br>
                            <input type="text" class="form-control" name="port" value="<?php echo e(env('APP_MODE')!='demo'?$data['port']??'':''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.username')); ?></label><br>
                            <input type="text" placeholder="ex : ex@yahoo.com" class="form-control" name="username"
                                   value="<?php echo e(env('APP_MODE')!='demo'?$data['username']??'':''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.email')); ?> <?php echo e(__('messages.id')); ?></label><br>
                            <input type="text" placeholder="ex : ex@yahoo.com" class="form-control" name="email"
                                   value="<?php echo e(env('APP_MODE')!='demo'?$data['email_id']??'':''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.encryption')); ?></label><br>
                            <input type="text" placeholder="ex : tls" class="form-control" name="encryption"
                                   value="<?php echo e(env('APP_MODE')!='demo'?$data['encryption']??'':''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 10px"><?php echo e(__('messages.password')); ?></label><br>
                            <input type="text" class="form-control" name="password" value="<?php echo e(env('APP_MODE')!='demo'?$data['password']??'':''); ?>" required>
                        </div>

                        <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>" onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>" class="btn btn-primary mb-2"><?php echo e(__('messages.save')); ?></button>
                    
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/business-settings/mail-index.blade.php ENDPATH**/ ?>