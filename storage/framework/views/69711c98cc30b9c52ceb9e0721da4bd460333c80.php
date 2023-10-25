<?php $__env->startSection('title',__('messages.app_settings')); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><?php echo e(__('messages.app_settings')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <?php ($app_minimum_version_android=\App\Models\BusinessSetting::where(['key'=>'app_minimum_version_android'])->first()); ?>
        <?php ($app_minimum_version_android=$app_minimum_version_android?$app_minimum_version_android->value:null); ?>

        <?php ($app_url_android=\App\Models\BusinessSetting::where(['key'=>'app_url_android'])->first()); ?>
        <?php ($app_url_android=$app_url_android?$app_url_android->value:null); ?>

        <?php ($app_minimum_version_ios=\App\Models\BusinessSetting::where(['key'=>'app_minimum_version_ios'])->first()); ?>
        <?php ($app_minimum_version_ios=$app_minimum_version_ios?$app_minimum_version_ios->value:null); ?>

        <?php ($app_url_ios=\App\Models\BusinessSetting::where(['key'=>'app_url_ios'])->first()); ?>
        <?php ($app_url_ios=$app_url_ios?$app_url_ios->value:null); ?>

        <?php ($popular_food=\App\Models\BusinessSetting::where(['key'=>'popular_food'])->first()); ?>
        <?php ($popular_food=$popular_food?$popular_food->value:null); ?>

        <?php ($popular_restaurant=\App\Models\BusinessSetting::where(['key'=>'popular_restaurant'])->first()); ?>
        <?php ($popular_restaurant=$popular_restaurant?$popular_restaurant->value:null); ?>

        <?php ($new_restaurant=\App\Models\BusinessSetting::where(['key'=>'new_restaurant'])->first()); ?>
        <?php ($new_restaurant=$new_restaurant?$new_restaurant->value:null); ?>

        <?php ($most_reviewed_foods=\App\Models\BusinessSetting::where(['key'=>'most_reviewed_foods'])->first()); ?>
        <?php ($most_reviewed_foods=$most_reviewed_foods?$most_reviewed_foods->value:null); ?>
    
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12 d-none">
                <div class="form-group">
                    <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control" for="popular_food">
                        <span class="pr-2"><?php echo e(__('messages.popular_Services')); ?>:</span> 
                        <input type="checkbox" class="toggle-switch-input" onclick="location.href='<?php echo e(route('admin.business-settings.toggle-settings',['popular_food',$popular_food?0:1, 'popular_food'])); ?>'" id="popular_food" <?php echo e($popular_food?'checked':''); ?>>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12 d-none">
                <div class="form-group">
                    <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control" for="popular_restaurant">
                        <span class="pr-2"><?php echo e(__('messages.popular_vendors')); ?>:</span> 
                        <input type="checkbox" name="popular_restaurant" class="toggle-switch-input" onclick="location.href='<?php echo e(route('admin.business-settings.toggle-settings',['popular_restaurant',$popular_restaurant?0:1, 'popular_restaurant'])); ?>'" id="popular_restaurant" <?php echo e($popular_restaurant?'checked':''); ?>>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12 d-none">
                <div class="form-group">
                    <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control" for="new_restaurant">
                        <span class="pr-2 text-capitalize"><?php echo e(__('messages.new_vendors')); ?>:</span> 
                        <input type="checkbox" class="toggle-switch-input" onclick="location.href='<?php echo e(route('admin.business-settings.toggle-settings',['new_restaurant',$new_restaurant?0:1, 'new_restaurant'])); ?>'" id="new_restaurant" <?php echo e($new_restaurant?'checked':''); ?>>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12 d-none">
                <div class="form-group">
                    <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control" for="most_reviewed_foods">
                        <span class="pr-2 text-capitalize"><?php echo e(__('messages.most_reviewed_services')); ?>:</span> 
                        <input type="checkbox" class="toggle-switch-input" onclick="location.href='<?php echo e(route('admin.business-settings.toggle-settings',['most_reviewed_foods',$most_reviewed_foods?0:1, 'most_reviewed_foods'])); ?>'" id="most_reviewed_foods" <?php echo e($most_reviewed_foods?'checked':''); ?>>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gx-2 gx-lg-3">
                    <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                        <form action="<?php echo e(route('admin.business-settings.app-settings')); ?>" method="post"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label  class="input-label d-inline text-capitalize"><?php echo e(__('messages.app_minimum_version')); ?> (<?php echo e(__('messages.android')); ?>)</label>
                                        <input type="number" placeholder="<?php echo e(__('messages.app_minimum_version')); ?>" class="form-control" name="app_minimum_version_android"
                                            value="<?php echo e(env('APP_MODE')!='demo'?$app_minimum_version_android??'':''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label d-inline text-capitalize"><?php echo e(__('messages.app_url')); ?> (<?php echo e(__('messages.android')); ?>)</label>
                                        <input type="text" placeholder="<?php echo e(__('messages.app_url')); ?>" class="form-control" name="app_url_android"
                                            value="<?php echo e(env('APP_MODE')!='demo'?$app_url_android??'':''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label  class="input-label d-inline text-capitalize"><?php echo e(__('messages.app_minimum_version')); ?> (<?php echo e(__('messages.ios')); ?>)</label>
                                        <input type="number" placeholder="<?php echo e(__('messages.app_minimum_version')); ?>" class="form-control" name="app_minimum_version_ios"
                                            value="<?php echo e(env('APP_MODE')!='demo'?$app_minimum_version_ios??'':''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label d-inline text-capitalize"><?php echo e(__('messages.app_url')); ?> (<?php echo e(__('messages.ios')); ?>)</label>
                                        <input type="text" placeholder="<?php echo e(__('messages.app_url')); ?>" class="form-control" name="app_url_ios"
                                            value="<?php echo e(env('APP_MODE')!='demo'?$app_url_ios??'':''); ?>">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>" onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>" class="btn btn-primary mb-2"><?php echo e(trans('messages.submit')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/business-settings/app-settings.blade.php ENDPATH**/ ?>