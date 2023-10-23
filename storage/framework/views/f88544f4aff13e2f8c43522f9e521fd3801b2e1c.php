<?php $__env->startSection('title','Employee Add'); ?>
<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
           @media(max-width:375px){
            #employee-image-modal .modal-content{
              width: 367px !important;
            margin-left: 0 !important;
        }
       
        }

   @media(max-width:500px){
    #employee-image-modal .modal-content{
              width: 400px !important;
            margin-left: 0 !important;
        }
      
      
   }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="content container-fluid"> 
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
            <li class="breadcrumb-item" aria-current="page"><?php echo e(__('messages.add')); ?> <?php echo e(__('messages.Employee')); ?></li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-black-50"><?php echo e(__('messages.Employee')); ?></h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php echo e(__('messages.employee_form')); ?>

                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.employee.add-new')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="input-label qcont" for="fname"><?php echo e(__('messages.first')); ?> <?php echo e(__('messages.name')); ?></label>
                                    <input type="text" name="f_name" class="form-control" id="fname"
                                           placeholder="<?php echo e(__('messages.first_name')); ?>" value="<?php echo e(old('f_name')); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label qcont" for="lname"><?php echo e(__('messages.last')); ?> <?php echo e(__('messages.name')); ?></label>
                                    <input type="text" name="l_name" class="form-control" id="lname" value="<?php echo e(old('l_name')); ?>"
                                           placeholder="<?php echo e(__('messages.last_name')); ?>" value="<?php echo e(old('name')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="input-label" for="title"><?php echo e(__('messages.zone')); ?></label>
                                    <select name="zone_id" class="form-control js-select2-custom">
                                        <?php if(!isset(auth('admin')->user()->zone_id)): ?>
                                        <option value="" <?php echo e(!isset($e->zone_id)?'selected':''); ?>><?php echo e(__('messages.all')); ?></option>
                                        <?php endif; ?>
                                        <?php ($zones=\App\Models\Zone::all()); ?>
                                        <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($zone['id']); ?>"><?php echo e($zone['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label qcont" for="role_id"><?php echo e(__('messages.Role')); ?></label>
                                    <select class="form-control js-select2-custom" name="role_id"
                                            style="width: 100%" required>
                                        <option value="" selected disabled><?php echo e(__('messages.select')); ?> <?php echo e(__('messages.Role')); ?></option>
                                        <?php $__currentLoopData = $rls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <small class="nav-subtitle border-bottom"><?php echo e(__('messages.login')); ?> <?php echo e(__('messages.info')); ?></small>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="input-label qcont" for="phone"><?php echo e(__('messages.phone')); ?></label>
                                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" id="phone"
                                           placeholder="Ex : +88017********" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label qcont" for="email"><?php echo e(__('messages.email')); ?></label>
                                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" id="email"
                                           placeholder="Ex : ex@gmail.com" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label qcont" for="password"><?php echo e(__('messages.password')); ?></label>
                                    <input type="text" name="password" class="form-control" id="password" value="<?php echo e(old('password')); ?>"
                                           placeholder="<?php echo e(__('messages.password_length_placeholder',['length'=>'6+'])); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="input-label qcont" for="customFileUpload"><?php echo e(__('messages.employee_image')); ?></label>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" value="<?php echo e(old('image')); ?>" required>
                                            <label class="custom-file-label" for="customFileUpload"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                                        </div>
                                    </div> 
                                    <center>
                                        <img style="width: auto;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                            src="<?php echo e(asset('public\assets\admin\img\400x400\img2.jpg')); ?>" alt="Employee thumbnail"/>
                                    </center>   
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><?php echo e(__('messages.submit')); ?></button>
                    </form>
                </div>
            </div>
        </div>
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

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/employee/add-new.blade.php ENDPATH**/ ?>