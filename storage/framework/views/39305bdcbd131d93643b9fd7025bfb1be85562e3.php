<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__env->startSection('title','Add new faq'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> <?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?> FAQ</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.faq.faq-store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">Question <small style="color: red"> * </small></label>
                                        <input type="text" name="question" class="form-control" placeholder="Question" required>
                                    </div>
                                </div>                             
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">Answer <small style="color: red"> * </small></label>
                                        <textarea name="answer" class="form-control ckeditor" required></textarea>
                                    </div>
                                </div>                             
                            </div>                      

                           
                            <button type="submit" class="btn btn-primary"><?php echo e(__('messages.submit')); ?></button>
                        </form>
                    </div>
                </div>
                
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                    <h5>FAQ List<span class="badge badge-soft-dark ml-2" id="itemCount"><?php echo e($faqs->total()); ?></span></h5>
                        <form id="dataSearch">
                        <?php echo csrf_field(); ?>
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="<?php echo e(__('messages.search_here')); ?>" aria-label="<?php echo e(__('messages.search_here')); ?>">
                                <button type="submit" class="btn btn-light"><?php echo e(__('messages.search')); ?></button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom" id="table-div">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging":false,
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th><?php echo e(__('messages.#')); ?></th>
                                <th>Question</th>
                                <th><?php echo e(__('messages.status')); ?></th>
                                <th><?php echo e(__('messages.action')); ?></th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+$faqs->firstItem()); ?></td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        <?php echo e($faq['question']); ?>

                                    </span>
                                    </td>
                                   
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="faqCheckbox<?php echo e($faq->id); ?>">
                                            <input type="checkbox" onclick="location.href='<?php echo e(route('admin.faq.faq-status',[$faq['id'],$faq->status?0:1])); ?>'" class="toggle-switch-input" id="faqCheckbox<?php echo e($faq->id); ?>" <?php echo e($faq->status?'checked':''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white" href="<?php echo e(route('admin.faq.faq-update',[$faq['id']])); ?>"title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.faq')); ?>"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:" onclick="form_alert('faq-<?php echo e($faq['id']); ?>','Want to delete this faq ?')" title="<?php echo e(__('messages.faq-delete')); ?> <?php echo e(__('messages.faq')); ?>"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.faq.faq-delete',[$faq['id']])); ?>"
                                                    method="post" id="faq-<?php echo e($faq['id']); ?>">
                                                <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <hr>
                        <table>
                            <tfoot>
                            <?php echo $faqs->links(); ?>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                    '<img class="mb-3" src="<?php echo e(asset($assetPrefixPath . '/admin/svg/illustrations/sorry.svg')); ?>" alt="Image Description" style="width: 7rem;">' +
                    '<p class="mb-0">No data to show</p>' +
                    '</div>'
                }
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
     
        });

       
        $('#dataSearch').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '<?php echo e(route('admin.faq.search')); ?>',
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
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/faq/index.blade.php ENDPATH**/ ?>