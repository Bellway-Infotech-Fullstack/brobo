<?php $__env->startSection('title','Notification Settings'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><?php echo e(__('messages.notification')); ?> <?php echo e(__('messages.setting')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(env('APP_MODE')!='demo'?route('admin.business-settings.update-fcm'):'javascript:'); ?>" method="post"
                              enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php ($key=\App\Models\BusinessSetting::where('key','push_notification_key')->first()); ?>
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1"><?php echo e(__('messages.server')); ?> <?php echo e(__('messages.key')); ?></label>
                                <textarea name="push_notification_key" class="form-control"
                                          required><?php echo e(env('APP_MODE')!='demo'?$key->value??'':''); ?></textarea>
                            </div>
                            <?php ($project_id=\App\Models\BusinessSetting::where('key','fcm_project_id')->first()); ?>
                            <div class="row" style="<?php echo e($project_id?($project_id->value?'display: none;':''):''); ?>">
                                
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">FCM Project ID</label>
                                        <input type="text" value="<?php echo e($project_id->value??''); ?>"
                                               name="fcm_project_id" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>" onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>" class="btn btn-primary"><?php echo e(__('messages.submit')); ?></button>
                        </form>
                    </div>
                </div>
            </div>

            <hr>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">

                <div class="card">
                    <div class="card-header">
                        <h2><?php echo e(__('messages.push')); ?> <?php echo e(__('messages.messages')); ?></h2>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.business-settings.update-fcm-messages')); ?>" method="post"
                              enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <?php ($opm=\App\Models\BusinessSetting::where('key','order_pending_message')->first()); ?>
                                <?php ($data=$opm?json_decode($opm->value,true):null); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="pending_status">
                                            <input type="checkbox" name="pending_status" class="toggle-switch-input"
                                                   value="1" id="pending_status" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                            <span
                                                class="d-block"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.pending')); ?> <?php echo e(__('messages.message')); ?></span>
                                          </span>
                                        </label>
                                        <textarea name="pending_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>

                                <?php ($ocm=\App\Models\BusinessSetting::where('key','order_confirmation_msg')->first()); ?>
                                <?php ($data=$ocm?json_decode($ocm->value,true):''); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="confirm_status">
                                            <input type="checkbox" name="confirm_status" class="toggle-switch-input"
                                                   value="1" id="confirm_status" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block"> <?php echo e(__('messages.order')); ?> <?php echo e(__('messages.confirmation')); ?> <?php echo e(__('messages.message')); ?></span>
                                              </span>
                                        </label>

                                        <textarea name="confirm_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>

                                <?php ($oprm=\App\Models\BusinessSetting::where('key','order_processing_message')->first()); ?>
                                <?php ($data=$oprm?json_decode($oprm->value,true):null); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="processing_status">
                                            <input type="checkbox" name="processing_status"
                                                   class="toggle-switch-input"
                                                   value="1" id="processing_status" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.processing')); ?> <?php echo e(__('messages.message')); ?></span>
                                              </span>
                                        </label>

                                        <textarea name="processing_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>

                              

                                <?php ($ofdm=\App\Models\BusinessSetting::where('key','out_for_delivery_message')->first()); ?>
                                <?php ($data=$ofdm?json_decode($ofdm->value,true):''); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="out_for_delivery">
                                            <input type="checkbox" name="out_for_delivery_status"
                                                   class="toggle-switch-input"
                                                   value="1" id="out_for_delivery" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.out_for_delivery')); ?> <?php echo e(__('messages.message')); ?></span>
                                              </span>
                                        </label>
                                        <textarea name="out_for_delivery_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>

                                <?php ($odm=\App\Models\BusinessSetting::where('key','order_delivered_message')->first()); ?>
                                <?php ($data=$odm?json_decode($odm->value,true):''); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="delivered_status">
                                            <input type="checkbox" name="delivered_status"
                                                   class="toggle-switch-input"
                                                   value="1" id="delivered_status" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.delivered')); ?> <?php echo e(__('messages.message')); ?></span>
                                              </span>
                                        </label>

                                        <textarea name="delivered_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>

                                <?php ($dba=\App\Models\BusinessSetting::where('key','delivery_boy_assign_message')->first()); ?>
                                <?php ($data=$dba?json_decode($dba->value,true):''); ?>
                               
                                
                                
                               

                                <?php ($dbc=\App\Models\BusinessSetting::where('key','order_cancled_message')->first()); ?>
                                <?php ($data=$dbc?json_decode($dbc->value,true):''); ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">

                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="order_cancled_message">
                                            <input type="checkbox" name="order_cancled_message_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="order_cancled_message" <?php echo e($data?($data['status']==1?'checked':''):''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.canceled')); ?> <?php echo e(__('messages.message')); ?></span>
                                              </span>
                                        </label>

                                        <textarea name="order_cancled_message"
                                                  class="form-control"><?php echo e($data['message']??''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary"><?php echo e(__('messages.submit')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/business-settings/fcm-index.blade.php ENDPATH**/ ?>