<?php $__env->startSection('title','Customer Edit'); ?>
<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        @media(max-width:375px){
         #Customer-image-modal .modal-content{
           width: 367px !important;
         margin-left: 0 !important;
     }
    
     }

@media(max-width:500px){
 #Customer-image-modal .modal-content{
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
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.customer.list')); ?>"><?php echo e(trans('messages.customers')); ?></a></li>
            <li class="breadcrumb-item" aria-current="page"><?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.update')); ?> </li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-black-50"><?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.update')); ?> </h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.update')); ?> <?php echo e(__('messages.form')); ?>

                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.customer.update',[$user_record['id']])); ?>" method="post">
                        
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="name"> <?php echo e(__('messages.name')); ?></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                               placeholder="Name" value="<?php echo e($user_record['name']); ?>"  required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="phmobile_numberone"><?php echo e(__('messages.mobile_number')); ?></label>
                                        <input type="text" name="mobile_number" value="<?php echo e($user_record['mobile_number']); ?>"  class="form-control" id="mobile_number"
                                               placeholder="Ex : +91017********" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="email"><?php echo e(__('messages.email')); ?></label>
                                        <input type="email" name="email"  class="form-control" id="email"
                                               placeholder="Ex : ex@gmail.com"  value="<?php echo e($user_record['email']); ?>">
                                    </div>                                
                                </div>
                            </div>
                            
                            
                            <small class="nav-subtitle border-bottom"><?php echo e(__('messages.login')); ?> <?php echo e(__('messages.info')); ?></small>
                            <br>
                            <div class="form-group">
                                <div class="row">                            
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="password"><?php echo e(__('messages.password')); ?></label>
                                        <input type="password" name="password" class="form-control" id="password" value="<?php echo e(old('password')); ?>"
                                               placeholder="<?php echo e(__('messages.password_length_placeholder',['length'=>'8+'])); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="confirm_password">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" value="<?php echo e(old('confirm_password')); ?>"
                                               placeholder="<?php echo e(__('messages.password_length_placeholder',['length'=>'8+'])); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="password">Gender</label>
                                        <select name="gender" class="form-control" id="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php echo e(($user_record['gender'] == 'Male') ? 'selected' : ''); ?>>Male</option>
                                            <option value="Female" <?php echo e(($user_record['gender'] == 'Female') ? 'selected' : ''); ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>          
                            
                            <small class="nav-subtitle border-bottom"><?php echo e(__('messages.login')); ?> <?php echo e(__('messages.info')); ?></small>
                            <br>
                            <div class="form-group">
                                <div class="row">                            
                                    <div class="col-md-4">
                                        <label class="input-label qcont" for="address">Addreess</label>
                                        <textarea name="address" class="form-control" placeholder="Address" required><?php echo e($user_record['address']); ?></textarea>
                                        
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

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/customer/edit.blade.php ENDPATH**/ ?>