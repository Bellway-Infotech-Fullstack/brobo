<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__env->startSection('title','Update Product'); ?>

<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(asset($assetPrefixPath . '/assets/admin/css/tags-input.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php ($opening_time=''); ?>
    <?php ($closing_time=''); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> <?php echo e(__('messages.product')); ?> <?php echo e(__('messages.update')); ?></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="product_form"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" value="<?php echo e(asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')); ?>" id="placeholder_image_path">
                    <div class="row">
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.name')); ?> <small style="color: red">* </small></label>
                                <input type="text" name="name" value="<?php echo e($product['name']); ?>" class="form-control" placeholder="New food" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.price')); ?> <small style="color: red">* </small></label>
                                <input type="number" value="<?php echo e($product['price']); ?>" min="0" max="100000" name="price"
                                       class="form-control" step="0.01"
                                       placeholder="Ex : 100" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.discount')); ?> <?php echo e(__('messages.type')); ?> <small style="color: red">* </small></label>
                                <select name="discount_type" class="form-control js-select2-custom">
                                    <option value="percent" <?php echo e($product['discount_type']=='percent'?'selected':''); ?>>
                                        <?php echo e(__('messages.percent')); ?>

                                    </option>
                                    <option value="amount" <?php echo e($product['discount_type']=='amount'?'selected':''); ?>>
                                        <?php echo e(__('messages.amount')); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.discount')); ?></label>
                                <input type="number" min="0" value="<?php echo e($product['discount']); ?>" max="100000"
                                       name="discount" class="form-control"
                                       placeholder="Ex : 100">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1"><?php echo e(__('messages.category')); ?> <small style="color: red"> * </small></label>
                                <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                        onchange="getRequest('<?php echo e(url('/')); ?>/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                        <option value="">---<?php echo e(__('messages.select')); ?>---</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  
                                        <option
                                            value="<?php echo e($category['id']); ?>" <?php echo e(($category['id'] == $product_category_parent_id) ? 'selected' : ''); ?> ><?php echo e($category['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1"><?php echo e(__('messages.sub_category')); ?> <small style="color: red"> * </small></label>
                                <select name="sub_category_id" id="sub-categories"
                                        data-id="<?php echo e(count($product_category)>=2?$product_category[1]->id:''); ?>"
                                        class="form-control js-select2-custom">

                                </select>
                            </div>
                        </div>

                    </div>

       

                  

                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1"> <?php echo e(__('messages.description')); ?></label>
                        <textarea type="text" name="description" class="form-control ckeditor"><?php echo e($product['description']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('messages.product')); ?> <?php echo e(__('messages.image')); ?></label><small style="color: red">* ( <?php echo e(__('messages.ratio')); ?> 1:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                                </div>
                                    <?php

                                    $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product['image']) : asset('storage/app/public/product/' . $product['image']);    
                                    ?>
        
                                <center style="display: block" id="image-viewer-section" class="pt-2">
                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                         src="<?php echo e($productImagePath); ?>"
                                         alt="product image"/>
                                </center>
                            </div>
    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.product')); ?> Different Angle <?php echo e(__('messages.images')); ?> </label>
                            <div>
                                <div class="row" id="coba">
                                    <?php if(count($product->images) > 0): ?>
                                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!str_contains($photo, 'video-')): ?>
                                        <?php

                                        $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $photo) : asset('storage/app/public/product/' . $photo);    
                                        ?>
                                            <div class="col-lg-2 col-md-4 col-sm-4 col-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img style="width: 100%" height="auto"
                                                               
                                                                src="<?php echo e($productImagePath); ?>"
                                                                alt="Product image">
                                                        <a href="<?php echo e(route('admin.product.remove-image',['id'=>$product['id'],'name'=>$photo])); ?>"
                                                            class="btn btn-danger btn-block">Remove</a>
    
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Total Stock <small style="color: red">* </small></label>
                                <input type="text" name="total_stock" class="form-control" placeholder="Total Stock" value= "<?php echo e($product['total_stock']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div  id="colored_image_section">
                    <?php if(count($product_color_image_data) > 0): ?>
                      
                        <?php $__currentLoopData = $product_color_image_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="colored_image_id[]" class="form-control" value="<?php echo e($photo['id']); ?>">
                        <div>
                            <div class="col-md-6">
                                 <div class="form-group">
                                     <label class="input-label" for="exampleFormControlInput1">Color Name</label>
                                     <input type="text" name="colored_name[]" class="form-control" placeholder="Color Name" value="<?php echo e($photo['color_name']); ?>">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label><?php echo e(__('messages.product')); ?> Main Image</label>
                                     <div class="custom-file">
                                         <input type="file" name="colored_image[]" data-id="<?php echo e($photo['id']); ?>"  
                                         class="custom-file-input customFileEg"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                         <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                                     </div>
                                     <?php
                           

                                     $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $photo['image']) : asset('storage/app/public/product/colored_images/' . $photo['image']);    
                                     ?>
             
                                     <center style="display: block" id="color-image-viewer-section<?php echo e($photo['id']); ?>" class="pt-2">
                                         <img style="height: 200px;border: 1px solid; border-radius: 10px;"  id="viewer<?php echo e($photo['id']); ?>"
                                              src="<?php echo e($productImagePath); ?>" alt="product image"/>
                                     </center>
                                 </div>    
                             </div>   
                         
                             <div class="row ">
                                
                             <?php $__currentLoopData = $photo['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <?php
                           

                                     $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $photo2) : asset('storage/app/public/product/colored_images/' . $photo2);    
                                     ?>
        
                           
                           
                             <div class="col-lg-2 col-md-4 col-sm-4 col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <img style="width: 100%" height="auto"
                                              
                                                src="<?php echo e($productImagePath); ?>"
                                                alt="Product image">
                                        <a href="<?php echo e(route('admin.product.remove-color-image',['id'=>$photo['id'],'name'=>$photo2])); ?>"
                                            class="btn btn-danger btn-block">Remove</a>

                                    </div>
                                </div>
                            
                        </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                        </div> 
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.product')); ?> Different Angle <?php echo e(__('messages.images')); ?></label>
                                     <div>
                                         <div class="row coba0"></div>
                                     </div>
                                 </div>
                             </div>  
                            
                                           
                        </div>
                       

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                        <?php else: ?>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Color Name</label>
                                <input type="text" name="colored_name[]" class="form-control" placeholder="Color Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__('messages.product')); ?> Main Image</label>
                                <div class="custom-file">
                                    <input type="file" name="colored_image[]" data-id="0"  class="custom-file-input customFileEg"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                                </div>
        
                                <center style="display: none" id="color-image-viewer-section0" class="pt-2">
                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer0"
                                         src="<?php echo e(asset($assetPrefixPath . '/assets/admin/img/400x400/img2.jpg')); ?>" alt="banner image"/>
                                </center>
                            </div>    
                        </div>    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.product')); ?> Different Angle <?php echo e(__('messages.images')); ?></label>
                                <div>
                                    <div class="row coba0"></div>
                                </div>
                            </div>
                        </div>  
                       <?php endif; ?>
                        
                    </div>
                    
                    
                    
                     <a href="javascript:void(0)" style="float:right;margin-top: 18px ;" title="Add More" id="add_more">Add More + </a>
                     <br>

                    <hr>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.update')); ?></button>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('script_2'); ?>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
    <script>
        function getRestaurantData(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
            $.get({
                url:'<?php echo e(url('/')); ?>/api/v1/restaurants/details/'+vendor_id,
                dataType: 'json',
                success: function(data) {
                    if(data.available_time_starts != null && data.available_time_ends != null){
                        var opening_time = data.available_time_starts;
                        var closeing_time = data.available_time_ends;
                        $('#available_time_ends').attr('min', opening_time);
                        $('#available_time_starts').attr('min', opening_time);
                        $('#available_time_ends').attr('max', closeing_time);
                        $('#available_time_starts').attr('max', closeing_time);
                        $('#available_time_starts').val(opening_time);
                        $('#available_time_ends').val(closeing_time);
                    }                    
                },
            });
        }

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
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


        $("#customFileEg1").change(function () {
            readURL(this);
            console.log("ev",ev)
            $('#image-viewer-section').show(1000)
        });

    

 // Function to read the selected image file and set the source
        function readURL2(input, id) {
                if (input.files && input.files[0]) {
                    console.log("ev",input)
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        // Set the source of the image
                        console.log("result",e.target.result)
                     
                        $('#viewer'+id).attr('src', e.target.result);
                        $('#color-image-viewer-section'+id).show();
                    };

                    // Read the selected file as a data URL
                    reader.readAsDataURL(input.files[0]);
                }
        }

        $(document).on("change",".customFileEg",function(ev){
    
            var id = $(this).attr("data-id");           
     
            readURL2(this,id);
            
        });

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category-id").val();
                let sub_category = '<?php echo e(count($product_category)>=2?$product_category[1]->id:''); ?>';
                let sub_sub_category ='<?php echo e(count($product_category)>=3?$product_category[2]->id:''); ?>';
                getRequest('<?php echo e(url('/')); ?>/admin/product/get-categories?parent_id=' + category + '&&sub_category=' + sub_category, 'sub-categories');
                getRequest('<?php echo e(url('/')); ?>/admin/product/get-categories?parent_id=' + sub_category + '&&sub_category=' + sub_sub_category, 'sub-sub-categories');
            
            }, 1000)
        
        });
    </script>

    <script>
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
            $('.js-select2-custom').prop("required",true)
        });
        
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '<?php echo e(url('/')); ?>/admin/vendor/get-restaurants',
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
        
    </script>

    <script src="<?php echo e(asset($assetPrefixPath . '/assets/admin')); ?>/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            combination_update();
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name;
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        setTimeout(function () {
            $('.call-update-sku').on('change', function () {
                combination_update();
            });
        }, 2000)

      


     
    </script>

    <!-- submit form -->
    <script>
       $(document).ready(function(){
            var length = <?php echo e(count($product_color_image_data)); ?>

            if(length >0) {
              var i = length;  
            } else {
                var i = 1; 
            }

            
            $("#add_more").on("click",function(){
               
                var htmlData = '<div class="col-md-6 colored-image-section'+i+'">'+
                                '<div class="form-group">'+
                                    '<label class="input-label" for="exampleFormControlInput1">Color Name</label>'+
                                    '<input type="text" name="colored_name[]" class="form-control" placeholder="Color Name">'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6 colored-image-section'+i+'">'+
                                    '<div class="form-group">'+
                                        '<label><?php echo e(__('messages.product')); ?> Main <?php echo e(__('messages.image')); ?></label>'+
                                        '<div class="custom-file">'+
                                        '<input type="file" name="colored_image[]"  data-id="'+i+'" class="custom-file-input  customFileEg" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">'+
                                            '<label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>'+
                                            '</div>'+        
                                            '<center style="display: none" id="color-image-viewer-section'+i+'" class="pt-2">'+
                                            '<img style="height: 200px;border: 1px solid; border-radius: 10px;"  id="viewer'+i+'" src="<?php echo e(asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')); ?>" alt="banner image"/>'+
                                            '</center>'+
                                             '</div>'+      
                                             '</div>'+  
                                             '</div>'+                                         
                                            '<div class="col-md-12 colored-image-section'+i+'">'+
                                                '<div class="form-group">'+
                                                    '<label class="input-label" for="exampleFormControlInput1"><?php echo e(__('messages.product')); ?> Different Angle <?php echo e(__('messages.images')); ?></label>'+
                                                    '<div>'+
                                                        '<div class="row coba'+i+'"></div>'+
                                                       
                                                        '</div>'+
                                                        '</div> '+

                                            '<a href="javascript:void(0)" class="remove-section" data-row-id="'+i+'" style="float:right">Remove</a>'+
                                        '</div>'+    
                                    '</div>'+
                                '</div>';
                                                
                               
                                 i++;        
                                 var new_count = i-1;
                             
                                 

                                $("#colored_image_section").append(htmlData);
                                $(".coba"+new_count).spartanMultiImagePicker({
                                    fieldName: "product_colored_images["+new_count+"][]",
                                    maxCount: 6,
                                    rowHeight: '120px',
                                    groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                                    maxFileSize: '',
                                    placeholderImage: {
                                        image: $("#placeholder_image_path").val(),
                                        width: '100%'
                                    },
                                    dropFileLabel: "Drop Here",
                                    onAddRow: function (index, file) {
                    
                                    },
                                    onRenderedPreview: function (index) {
                    
                                    },
                                    onRemoveRow: function (index) {
                    
                                    },
                                    onExtensionErr: function (index, file) {
                                        toastr.error("<?php echo e(__('messages.please_only_input_png_or_jpg_type_file')); ?>", {
                                            CloseButton: true,
                                            ProgressBar: true
                                        });
                                    },
                                    onSizeErr: function (index, file) {
                                        toastr.error("<?php echo e(__('messages.file_size_too_big')); ?>", {
                                            CloseButton: true,
                                            ProgressBar: true
                                        });
                                    }
                                });  
                                                                 

            });
            
        });

        $(document).on("click",".remove-section",function(){
            var row_id = $(this).attr("data-row-id");
            $(".colored-image-section"+row_id).remove();
            $(this).remove();
        }); 


        CKEDITOR.replace('description');
        $('#product_form').on('submit', function () {
            var formData = new FormData(this);
             // Get CKEditor data
             var editorData = CKEDITOR.instances.description.getData();

            // Append CKEditor data to FormData
            formData.append('description', editorData);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '<?php echo e(route('admin.product.update',[$product['id']])); ?>',
                data: $('#product_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('Product updated successfully!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '<?php echo e(\Request::server('HTTP_REFERER')??route('admin.product.list')); ?>';
                        }, 2000);
                    }
                }
            });
        });
    </script>
    <script src="<?php echo e(asset($assetPrefixPath.'/assets/admin/js/spartan-multi-image-picker.js')); ?>"></script>
    <script type="text/javascript">
        
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'product_images[]',
                maxCount: 6,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: $("#placeholder_image_path").val(),
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error("<?php echo e(__('messages.please_only_input_png_or_jpg_type_file')); ?>", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error("<?php echo e(__('messages.file_size_too_big')); ?>", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

            $(".coba0").spartanMultiImagePicker({
                fieldName: 'product_colored_images[0][]',
                maxCount: 6,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: $("#placeholder_image_path").val(),
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error("<?php echo e(__('messages.please_only_input_png_or_jpg_type_file')); ?>", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error("<?php echo e(__('messages.file_size_too_big')); ?>", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }); 
        });
    </script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/product/edit.blade.php ENDPATH**/ ?>