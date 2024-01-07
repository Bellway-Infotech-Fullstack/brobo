<?php $__env->startSection('title','Customer Details'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="<?php echo e(route('admin.customer.list')); ?>">
                                    <?php echo e(__('messages.customers')); ?>

                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.details')); ?></li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title"><?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.id')); ?> #<?php echo e($customer['id']); ?></h1>
                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range">
                        </i> <?php echo e(__('messages.joined_at')); ?> : <?php echo e(date('d M Y '.config('timeformat'),strtotime($customer['created_at']))); ?>

                        </span>
                    </div>
                    
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                       href="<?php echo e(route('admin.customer.view',[$customer['id']-1])); ?>"
                       data-toggle="tooltip" data-placement="top" title="Previous customer">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                       href="<?php echo e(route('admin.customer.view',[$customer['id']+1])); ?>" data-toggle="tooltip"
                       data-placement="top" title="Next customer">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-title"></h5>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th><?php echo e(__('messages.#')); ?></th>
                                <th style="width: 50%" class="text-center"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.id')); ?></th>
                                <th style="width: 50%"><?php echo e(__('messages.total')); ?></th>
                                <th style="width: 10%"><?php echo e(__('messages.action')); ?></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>
                                    <input type="text" id="column1_search" class="form-control form-control-sm"
                                           placeholder="Search ID">
                                </th>
                                <th></th>
                                <th>
                                    
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+1); ?></td>
                                    <td class="table-column-pl-0 text-center">
                                        <a href="<?php echo e(route('admin.order.details',['id'=>$order['id']])); ?>"><?php echo e($order['order_id']); ?></a>
                                    </td>
                                    <td>Rs. <?php echo e($order['paid_amount']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                                    href="<?php echo e(route('admin.order.details',['id'=>$order['id']])); ?>" title="<?php echo e(__('messages.view')); ?>"><i
                                                            class="tio-visible"></i></a>
                                        <a class="btn btn-sm btn-white" target="_blank"
                                                    href="<?php echo e(route('admin.order.generate-invoice',[$order['id']])); ?>" title="<?php echo e(__('messages.invoice')); ?>"><i
                                                            class="tio-download"></i> </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <!-- Footer -->
                        <div class="card-footer">
                            <!-- Pagination -->
                        <?php echo $orders->links(); ?>

                        <!-- End Pagination -->
                        </div>
                        <!-- End Footer -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title"><?php echo e(__('messages.customer')); ?></h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <?php if($customer): ?>
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                              
                                <div class="media-body">
                                <span
                                    class="text-body text-hover-primary"><?php echo e($customer['name']); ?></span>
                                </div>
                                <div class="media-body text-right">
                                    
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary"><?php echo e(count($orders)); ?> <?php echo e(__('messages.orders')); ?></span>
                                </div>
                                <div class="media-body text-right">
                                    
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5><?php echo e(__('messages.contact')); ?> <?php echo e(__('messages.info')); ?></h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    <?php echo e($customer['email']); ?>

                                </li>
                                <li>
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    <?php echo e($customer['mobile_number']); ?>

                                </li>
                                <li>
                                    <i class="fa fa-location mr-2"></i>
                                    <?php echo e($customer['address']); ?>

                                </li>
                            </ul>

                           
                            

                        </div>
                <?php endif; ?>
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/customer/customer-view.blade.php ENDPATH**/ ?>