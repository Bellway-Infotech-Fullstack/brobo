<?php $__env->startSection('title','Update category'); ?>

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
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i><?php echo e($category->position?__('messages.sub').' ':''); ?><?php echo e(__('messages.category')); ?> <?php echo e(__('messages.update')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="<?php echo e(route('admin.category.update',[$category['id']])); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.name')); ?></label>
                                <input type="text" name="name" value="<?php echo e($category['name']); ?>" class="form-control" placeholder="">
                            </div>
                            <input name="position" value="0" style="display: none">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?php echo e(__('messages.image')); ?></label><small style="color: red">* ( <?php echo e(__('messages.ratio')); ?> 1:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-12">
                            <center>
                                <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                     src="<?php echo e(asset('storage/app/public/category')); ?>/<?php echo e($category['image']); ?>" alt=""/>
                            </center>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.update')); ?></button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/category/edit.blade.php ENDPATH**/ ?>