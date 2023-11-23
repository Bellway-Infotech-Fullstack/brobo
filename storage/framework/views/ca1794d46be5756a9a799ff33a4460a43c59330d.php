<?php $__env->startSection('title','Add new category'); ?>

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
                    <h1 class="page-header-title"><?php echo e(__('messages.category')); ?></h1>
                </div>
                <?php if(isset($category)): ?>
                <a href="<?php echo e(route('admin.category.add')); ?>" class="btn btn-primary pull-right"><i class="tio-add-circle"></i> <?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?> <?php echo e(__('messages.category')); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <!-- End Page Header -->

      

        <div class="card mt-3">
            <div class="card-header pb-0">
                <h5><?php echo e(__('messages.category')); ?> <?php echo e(__('messages.list')); ?><span class="badge badge-soft-dark ml-2" id="itemCount"><?php echo e($categories->total()); ?></span></h5>
                <form id="dataSearch">
                    <?php echo csrf_field(); ?>
                    <!-- Search -->
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tio-search"></i>
                            </div>
                        </div>
                        <input type="search" name="search" class="form-control" placeholder="<?php echo e(__('messages.search_categories')); ?>" aria-label="<?php echo e(__('messages.search_categories')); ?>">
                        <button type="submit" class="btn btn-light"><?php echo e(__('messages.search')); ?></button>
                    </div>
                    <!-- End Search -->
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-align-middle" style="width:100%;"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%"><?php echo e(__('messages.#')); ?></th>
                                <th style="width: 10%"><?php echo e(__('messages.id')); ?></th>
                                <th style="width: 30%"><?php echo e(__('messages.name')); ?></th>
                                <th style="width: 10%"><?php echo e(__('messages.status')); ?></th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($key+$categories->firstItem()); ?></td>
                                <td><?php echo e($category->id); ?></td>
                                <td>
                                <span class="d-block font-size-sm text-body">
                                    <?php echo e($category['name']); ?>

                                </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($category->id); ?>">
                                    <input type="checkbox"  class="toggle-switch-input" disabled checked >
                                        <span class="toggle-switch-label" style="cursor:auto;">
                                            <span class="toggle-switch-indicator" ></span>
                                        </span>
                                    </label>
                                </td>

                               
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer page-area">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center"> 
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            <?php echo $categories->links(); ?>

                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            

            $('#dataSearch').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '<?php echo e(route('admin.category.search')); ?>',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        $('#table-div').html(data.view);
                        $('#itemCount').html(data.count);
                        $('.page-area').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

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

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/category/index.blade.php ENDPATH**/ ?>