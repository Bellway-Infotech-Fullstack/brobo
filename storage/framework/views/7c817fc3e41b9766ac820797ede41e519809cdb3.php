<?php $__env->startSection('title','Update Coupon'); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> <?php echo e(__('messages.coupon')); ?> <?php echo e(__('messages.update')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="<?php echo e(route('admin.coupon.update',[$coupon['id']])); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.title')); ?></label>
                                <input type="text" name="title" value="<?php echo e($coupon['title']); ?>" class="form-control"
                                       placeholder="<?php echo e(__('messages.new_coupon')); ?>" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.coupon')); ?> <?php echo e(__('messages.type')); ?></label>
                                <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    
                        
                                  
                                    <option value="default" <?php echo e($coupon['coupon_type']=='default'?'selected':''); ?>><?php echo e(__('messages.default')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                           
                                <div class="form-group" id="zone_wise" style="display: none">
                                    <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.select')); ?> <?php echo e(__('messages.zone')); ?></label>
                                    <select name="zone_ids[]" id="choice_zones"
                                        class="form-control js-select2-custom"
                                        multiple="multiple" placeholder="<?php echo e(__('messages.select_zone')); ?>">
                                    <?php $__currentLoopData = \App\Models\Zone::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($zone->id); ?>" <?php echo e(($coupon->coupon_type=='zone_wise'&&json_decode($coupon->data))?(in_array($zone->id, json_decode($coupon->data))?'selected':''):''); ?>><?php echo e($zone->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.code')); ?></label>
                                <input type="text" name="code" class="form-control" value="<?php echo e($coupon['code']); ?>"
                                       placeholder="<?php echo e(\Illuminate\Support\Str::random(8)); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="limit"><?php echo e(__('messages.limit')); ?> <?php echo e(__('messages.for')); ?> <?php echo e(__('messages.same')); ?> <?php echo e(__('messages.user')); ?></label>
                                <input type="number" name="limit" id="coupon_limit" value="<?php echo e($coupon['limit']); ?>" class="form-control"
                                       placeholder="EX: 10">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for=""><?php echo e(__('messages.start')); ?> <?php echo e(__('messages.date')); ?></label>
                                <input type="date" name="start_date" class="form-control" id="date_from" placeholder="<?php echo e(__('messages.select_date')); ?>" value="<?php echo e(date('Y-m-d',strtotime($coupon['start_date']))); ?>">
                            </div>
                        </div>
                       
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="date_to"><?php echo e(__('messages.expire')); ?> <?php echo e(__('messages.date')); ?></label>
                                <input type="date" name="expire_date" class="form-control" placeholder="<?php echo e(__('messages.select_date')); ?>" id="date_to" value="<?php echo e(date('Y-m-d',strtotime($coupon['expire_date']))); ?>"
                                       data-hs-flatpickr-options='{
                                     "dateFormat": "Y-m-d"
                                   }'>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="discount_type"><?php echo e(__('messages.discount')); ?> <?php echo e(__('messages.type')); ?></label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="amount" <?php echo e($coupon['discount_type']=='amount'?'selected':''); ?>><?php echo e(__('messages.amount')); ?>

                                    </option>
                                    <option value="percent" <?php echo e($coupon['discount_type']=='percent'?'selected':''); ?>>
                                        <?php echo e(__('messages.percent')); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="discount"><?php echo e(__('messages.discount')); ?></label>
                                <input type="number" id="discount" min="1" max="10000" step="0.01" value="<?php echo e($coupon['discount']); ?>"
                                       name="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.min')); ?> <?php echo e(__('messages.purchase')); ?></label>
                                <input type="number" name="min_purchase" step="0.01" value="<?php echo e($coupon['min_purchase']); ?>"
                                       min="0" max="100000" class="form-control"
                                       placeholder="100">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Upload Background Image</label>
                                <input type="file" name="coupon_background_image" id="coupon_background_image">
                            </div>
                            <?php

                            $couponImagePath = (env('APP_ENV') == 'local') ? asset('storage/coupon_background_image/'.$coupon['background_image']) : asset('storage/app/public/coupon_background_image/' .$coupon['background_image']);    
                            ?>

                        <center style="display: block" id="image-viewer-section" class="pt-2">
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="<?php echo e($couponImagePath); ?>"
                                 alt="coupon image"/>
                        </center>
                        </div>  
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.update')); ?></button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
          $("#coupon_background_image").change(function () {
            handleFile(this);
            $('#image-viewer-section').show(1000);
        });
        $("#date_from").on("change", function () {
            $('#date_to').attr('min',$(this).val());
        });

        var isequal = 0;
        function handleFile(input){
            const file = input.files[0];

            // Check if the file is an image
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = new Image();

                    img.onload = function () {
                        // Get the width and height of the image
                        const width = this.width;
                        const height = this.height;

                        // Check if the dimensions meet your criteria         

                        if (width != 343 || height != 100) {
                                // Dimensions are not allowed, handle accordingly (e.g., show an error message)
                                alert('Invalid dimensions. Width must be  343px and height must be <= 100px.');
                                // Optionally clear the file input if needed
                                input.value = '';
                                isequal = 1;
                            } else {
                            // Dimensions are within the allowed range, you can proceed with your logic
                            // ...
                            }
                        };
                            img.src = e.target.result;
                            if(isequal == 1){
                                $('#viewer').attr('src', e.target.result);
                            }
                    
                };

                reader.readAsDataURL(file);
            } else {
                // File is not an image, handle accordingly (e.g., show an error message)
                alert('Invalid file type. Please upload an image.');
                // Optionally clear the file input if needed
                input.value = '';
            }
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#date_to").on("change", function () {
            $('#date_from').attr('max',$(this).val());
        });
        $(document).on('ready', function () {
            $('#date_from').attr('max','<?php echo e(date("Y-m-d",strtotime($coupon["expire_date"]))); ?>');
            $('#date_to').attr('min','<?php echo e(date("Y-m-d",strtotime($coupon["start_date"]))); ?>');
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
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

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
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/coupon/edit.blade.php ENDPATH**/ ?>