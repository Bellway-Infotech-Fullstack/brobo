<?php $__env->startSection('title','Add new notification'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-notifications"></i> <?php echo e(__('messages.notification')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="<?php echo e(route('admin.notification.store')); ?>" method="post" enctype="multipart/form-data" id="notification">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.title')); ?></label>
                                <input type="text" name="notification_title" class="form-control" placeholder="<?php echo e(__('messages.new_notification')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.zone')); ?></label>
                                <select name="zone" class="form-control js-select2-custom" >
                                    <option value="all">All Zones</option>
                                    <?php $__currentLoopData = \App\Models\Zone::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $z): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($z['id']); ?>"><?php echo e($z['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label" for="tergat"><?php echo e(__('messages.send')); ?> <?php echo e(__('messages.to')); ?></label>
                        
                                <select name="tergat" class="form-control" id="tergat" data-placeholder="<?php echo e(__('messages.select')); ?> <?php echo e(__('messages.tergat')); ?>" required>
                                    <option value="customer"><?php echo e(__('messages.customer')); ?></option>
                                    
                                    <option value="vendor"><?php echo e(__('messages.vendor')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.description')); ?></label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo e(__('messages.image')); ?></label><small style="color: red">* ( <?php echo e(__('messages.ratio')); ?> 3:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                        </div>
                        <hr>
                        <center>
                            <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="<?php echo e(asset($assetPrefixPath . '/admin/img/900x400/img1.jpg')); ?>" alt="image"/>
                        </center>
                    </div>
                    <hr>
                    <button type="submit" id="submit" class="btn btn-primary"><?php echo e(__('messages.send')); ?> <?php echo e(__('messages.notification')); ?></button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <hr>
                <div class="card">
                    <div class="card-header py-1">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                        <h3>Notification list<span
                            class="badge badge-soft-dark ml-2"><?php echo e($notifications->total()); ?></span></h3>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <form>
                                <!-- Search -->
                                <div class="input-group input-group-merge input-group-flush">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input type="search" id="column1_search" class="form-control"
                                           placeholder="<?php echo e(__('messages.search')); ?> <?php echo e(__('messages.notification')); ?>">
                                           <button type="submit" class="btn btn-light"><?php echo e(__('messages.search')); ?></button>
                                </div>
                                <!-- End Search -->
                                </form>
                            </div>
                        </div>                        
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging": false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th><?php echo e(__('messages.#')); ?></th>
                                    <th style="width: 50%"><?php echo e(__('messages.title')); ?></th>
                                    <th><?php echo e(__('messages.description')); ?></th>
                                    <th><?php echo e(__('messages.image')); ?></th>
                                    <th><?php echo e(__('messages.zone')); ?></th>
                                    <th><?php echo e(__('messages.tergat')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th style="width: 10%"><?php echo e(__('messages.action')); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+$notifications->firstItem()); ?></td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        <?php echo e(substr($notification['title'],0,25)); ?> <?php echo e(strlen($notification['title'])>25?'...':''); ?>

                                    </span>
                                    </td>
                                    <td>
                                        <?php echo e(substr($notification['description'],0,25)); ?> <?php echo e(strlen($notification['description'])>25?'...':''); ?>

                                    </td>
                                    <td>
                                        <?php if($notification['image']!=null): ?>
                                            <img style="height: 50px"
                                                 src="<?php echo e(asset('storage/app/public/notification')); ?>/<?php echo e($notification['image']); ?>">
                                        <?php else: ?>
                                            <label class="badge badge-soft-warning">No <?php echo e(__('messages.image')); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($notification->zone_id==null?__('messages.all'):($notification->zone?$notification->zone->name:__('messages.zone').' '.__('messages.deleted'))); ?>

                                    </td>
                                    <td class="text-uppercase">
                                        <?php echo e($notification->tergat); ?>

                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($notification->id); ?>">
                                            <input type="checkbox" onclick="location.href='<?php echo e(route('admin.notification.status',[$notification['id'],$notification->status?0:1])); ?>'"class="toggle-switch-input" id="stocksCheckbox<?php echo e($notification->id); ?>" <?php echo e($notification->status?'checked':''); ?>>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                            href="<?php echo e(route('admin.notification.edit',[$notification['id']])); ?>" title="<?php echo e(__('messages.edit')); ?> <?php echo e(__('messages.notification')); ?>"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:"
                                            onclick="form_alert('notification-<?php echo e($notification['id']); ?>','Want to delete this notification ?')" title="<?php echo e(__('messages.delete')); ?> <?php echo e(__('messages.notification')); ?>"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.notification.delete',[$notification['id']])); ?>" method="post" id="notification-<?php echo e($notification['id']); ?>">
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
                            <?php echo $notifications->links(); ?>

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

        $('#notification').on('submit', function (e) {
            
            e.preventDefault();
            var formData = new FormData(this);
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'you want to sent notification to '+$('#tergat').val()+'?',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '<?php echo e(__('messages.no')); ?>',
                confirmButtonText: '<?php echo e(__('messages.send')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '<?php echo e(route('admin.notification.store')); ?>',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('Notifiction sent successfully!', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    location.href = '<?php echo e(route('admin.notification.add-new')); ?>';
                                }, 2000);
                            }
                        }
                    });
                }
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/notification/index.blade.php ENDPATH**/ ?>