<?php $__env->startSection('title','Add new coupon'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> <?php echo e(__('messages.add')); ?> <?php echo e(__('messages.new')); ?> <?php echo e(__('messages.coupon')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.coupon.store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.title')); ?></label>
                                        <input type="text" name="title" class="form-control" placeholder="<?php echo e(__('messages.new_coupon')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.coupon')); ?> <?php echo e(__('messages.type')); ?></label>
                                        <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                            <option value="service_wise"><?php echo e(__('messages.service')); ?> <?php echo e(__('messages.wise')); ?></option>
                                            <option value="zone_wise"><?php echo e(__('messages.zone')); ?> <?php echo e(__('messages.wise')); ?></option>
                                            
                                            <option value="first_order"><?php echo e(__('messages.first')); ?> <?php echo e(__('messages.order')); ?></option>
                                            <option value="default"><?php echo e(__('messages.default')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4" >
                                    <div class="form-group" id="restaurant_wise">
                                        <label class="input-label" for="exampleFormControlSelect1"><?php echo e(__('messages.service')); ?><span
                                                class="input-label-secondary"></span></label>
                                        <select name="service_ids[]" class="js-data-example-ajax form-control" data-placeholder="<?php echo e(__('messages.select_Service')); ?>" title="<?php echo e(__('messages.select_Service')); ?>">                                            
                                        </select>
                                    </div>
                                    <div class="form-group" id="zone_wise">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.select')); ?> <?php echo e(__('messages.zone')); ?></label>
                                        <select name="zone_ids[]" id="choice_zones"
                                            class="form-control js-select2-custom"
                                            multiple="multiple" data-placeholder="<?php echo e(__('messages.select_zone')); ?>">
                                        <?php $__currentLoopData = \App\Models\Zone::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($zone->id); ?>"><?php echo e($zone->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.code')); ?></label>
                                        <input type="text" name="code" class="form-control"
                                            placeholder="<?php echo e(\Illuminate\Support\Str::random(8)); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.limit')); ?> <?php echo e(__('messages.for')); ?> <?php echo e(__('messages.same')); ?> <?php echo e(__('messages.user')); ?></label>
                                        <input type="number" name="limit" id="coupon_limit" class="form-control" placeholder="EX: 10">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.start')); ?> <?php echo e(__('messages.date')); ?></label>
                                        <input type="date" name="start_date" class="form-control" id="date_from" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.expire')); ?> <?php echo e(__('messages.date')); ?></label>
                                        <input type="date" name="expire_date" class="form-control" id="date_to" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.discount')); ?> <?php echo e(__('messages.type')); ?></label>
                                        <select name="discount_type" class="form-control" id="discount_type">
                                            <option value="amount"><?php echo e(__('messages.amount')); ?></option>
                                            <option value="percent"><?php echo e(__('messages.percent')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.discount')); ?></label>
                                        <input type="number" step="0.01" min="1" max="10000" name="discount" id="discount" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="max_discount"><?php echo e(__('messages.max')); ?> <?php echo e(__('messages.discount')); ?></label>
                                        <input type="number" step="0.01" min="0" value="0" max="1000000" name="max_discount" id="max_discount" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.min')); ?> <?php echo e(__('messages.purchase')); ?></label>
                                        <input type="number" step="0.01" name="min_purchase" value="0" min="0" max="100000" class="form-control"
                                            placeholder="100">
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
                    <h5><?php echo e(__('messages.coupon')); ?> <?php echo e(__('messages.list')); ?><span class="badge badge-soft-dark ml-2" id="itemCount"><?php echo e($coupons->total()); ?></span></h5>
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
                                <th><?php echo e(__('messages.title')); ?></th>
                                <th><?php echo e(__('messages.code')); ?></th>
                                <th><?php echo e(__('messages.type')); ?></th>
                                <th><?php echo e(__('messages.total_uses')); ?></th>
                                <th><?php echo e(__('messages.min')); ?> <?php echo e(__('messages.purchase')); ?></th>
                                <th><?php echo e(__('messages.max')); ?> <?php echo e(__('messages.discount')); ?></th>
                                <th><?php echo e(__('messages.discount')); ?></th>
                                <th><?php echo e(__('messages.discount')); ?> <?php echo e(__('messages.type')); ?></th>
                                <th><?php echo e(__('messages.start')); ?> <?php echo e(__('messages.date')); ?></th>
                                <th><?php echo e(__('messages.expire')); ?> <?php echo e(__('messages.date')); ?></th>
                                <th><?php echo e(__('messages.status')); ?></th>
                                <th><?php echo e(__('messages.action')); ?></th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+$coupons->firstItem()); ?></td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        <?php echo e($coupon['title']); ?>

                                    </span>
                                    </td>
                                    <td><?php echo e($coupon['code']); ?></td>
                                    <td><?php echo e(__('messages.'.$coupon->coupon_type)); ?></td>
                                    <td><?php echo e($coupon->total_uses); ?></td>
                                    <td><?php echo e(\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])); ?></td>
                                    <td><?php echo e(\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])); ?></td>
                                    <td><?php echo e($coupon['discount']); ?></td>
                                    <td><?php echo e($coupon['discount_type']); ?></td>
                                    <td><?php echo e($coupon['start_date']); ?></td>
                                    <td><?php echo e($coupon['expire_date']); ?></td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="couponCheckbox<?php echo e($coupon->id); ?>">
                                            <input type="checkbox" onclick="location.href='<?php echo e(route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])); ?>'" class="toggle-switch-input" id="couponCheckbox<?php echo e($coupon->id); ?>" <?php echo e($coupon->status?'checked':''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white" href="<?php echo e(route('admin.coupon.update',[$coupon['id']])); ?>"title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.coupon')); ?>"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:" onclick="form_alert('coupon-<?php echo e($coupon['id']); ?>','Want to delete this coupon ?')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.coupon')); ?>"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.coupon.delete',[$coupon['id']])); ?>"
                                                    method="post" id="coupon-<?php echo e($coupon['id']); ?>">
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
                            <?php echo $coupons->links(); ?>

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
<script>
    $("#date_from").on("change", function () {
        $('#date_to').attr('min',$(this).val());
    });

    $("#date_to").on("change", function () {
        $('#date_from').attr('max',$(this).val());
    });
    
    $(document).on('ready', function () {
        $('#discount_type').on('change', function() {
         if($('#discount_type').val() == 'amount')
            {
                $('#max_discount').attr("readonly","true");
                $('#max_discount').val(0);
            }
            else
            {
                $('#max_discount').removeAttr("readonly");
            }
        });
        
        $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
        $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '<?php echo e(url('/')); ?>/admin/vendor/get-services',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
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
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('#zone_wise').hide();
        function coupon_type_change(coupon_type) {
           if(coupon_type=='zone_wise')
            {
                $('#restaurant_wise').hide();
                $('#zone_wise').show();
            }
            else if(coupon_type=='restaurant_wise')
            {
                $('#restaurant_wise').show();
                $('#zone_wise').hide();
            }
            else if(coupon_type=='first_order')
            {
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val(1);
                $('#coupon_limit').attr("readonly","true");
            }
            else{
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val('');
                $('#coupon_limit').removeAttr("readonly");
            }

            if(coupon_type=='free_delivery')
            {
                $('#discount_type').attr("disabled","true");
                $('#discount_type').val("").trigger( "change" );
                $('#max_discount').val(0);
                $('#max_discount').attr("readonly","true");
                $('#discount').val(0);
                $('#discount').attr("readonly","true");
            }
            else{
                $('#max_discount').removeAttr("readonly");
                $('#discount_type').removeAttr("disabled");
                $('#discount').removeAttr("readonly");
            }
        }
        $('#dataSearch').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '<?php echo e(route('admin.coupon.search')); ?>',
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

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/coupon/index.blade.php ENDPATH**/ ?>