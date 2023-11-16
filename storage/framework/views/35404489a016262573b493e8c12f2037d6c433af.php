<?php $__env->startSection('title','Service Bulk Import'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(trans('messages.dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="<?php echo e(route('admin.service.list')); ?>"><?php echo e(trans('messages.services')); ?></a>
                </li>
                <li class="breadcrumb-item"><?php echo e(trans('messages.bulk_import')); ?> </li>
            </ol>
        </nav>
        <h1 class="text-capitalize"><?php echo e(__('messages.services')); ?> <?php echo e(__('messages.bulk_import')); ?></h1>
        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                <div class="jumbotron pt-1" style="background: white">
                    <h3>Instructions : </h3>
                    <p> 1. Download the format file and fill it with proper data.</p>

                    <p>2. You can download the example file to understand how the data must be filled.</p>

                    <p>3. Once you have downloaded and filled the format file, upload it in the form below and
                        submit.</p>

                    <p> 4. After uploading services you need to edit them and set image and variations.</p>

                    <p> 5. You can get service id from their list, please input the right ids.</p>

                    <p> 6. You can upload your service images in service folder from gallery, and copy image`s path.</p>
                    
                </div>
            </div>

            <div class="col-md-12">
                <form class="product-form" action="<?php echo e(route('admin.service.bulk-import')); ?>" method="POST"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h4>Import Food File</h4>
                            <a href="<?php echo e(asset($assetPrefixPath . '/services_bulk_format.xlsx')); ?>" download=""
                               class="btn btn-secondary">Download Format</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="file" name="products_file">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/product/bulk-import.blade.php ENDPATH**/ ?>