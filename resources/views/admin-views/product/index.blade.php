@extends('layouts.admin.app')
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
@endphp
@section('title','Add new product')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset($assetPrefixPath . '/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{__('messages.add')}} {{__('messages.new')}} {{__('messages.product')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form 
                    action="javascript:"
                     {{-- action="{{route('admin.product.store')}}" --}}
                     method="post" 
                     id="food_form"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')}}" id="placeholder_image_path">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.product')}} {{__('messages.name')}} <small style="color: red">* </small></label>
                                <input type="text" name="name" class="form-control" placeholder="{{__('messages.product')}} {{__('messages.name')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.price')}} <small style="color: red">* </small></label>
                                <input type="number" min="0" max="9999999999999999999999" step="0.01"  name="price" class="form-control"
                                       placeholder="Ex : 100" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}} {{__('messages.type')}} <small style="color: red">* </small></label>
                                <select name="discount_type" class="form-control js-select2-custom">
                                    <option value="percent">{{__('messages.percent')}}</option>
                                    <option value="amount">{{__('messages.amount')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}}</label>
                                <input type="number" min="0" max="9999999999999999999999" value="0" name="discount" class="form-control"
                                       placeholder="Ex : 100" >
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{__('messages.category')}} <small style="color: red"> * </small></label>
                                <select name="category_id"  class="form-control js-select2-custom"
                                        onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                    <option value="">---{{__('messages.select')}}---</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{__('messages.sub_category')}} <small style="color: red"> * </small></label>
                                <select name="sub_category_id" id="sub-categories"
                                        class="form-control js-select2-custom">

                                </select>
                            </div>
                        </div>
                        
  
                    </div>

            

                   

                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.description')}}</label>
                        <textarea type="text" name="description" class="form-control ckeditor"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Color Name <small style="color: red"> * </small></label>
                                <input type="text" name="color_name" class="form-control" value="" required>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-dark">{{__('messages.product')}} Main Image</label><small style="color: red">* ( {{__('messages.ratio')}} 1:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                </div>
        
                                <center style="display: none" id="image-viewer-section" class="pt-2">
                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                        src="{{asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')}}" alt="banner image"/>
                                </center>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.product')}} Different Angle {{__('messages.images')}}</label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>

                       
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Total Stock <small style="color: red">* </small></label>
                                <input type="text" name="total_stock" class="form-control" placeholder="Total Stock" required>
                            </div>
                        </div>
                    </div>

                    <div  id="colored_image_section">                       
                       <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Color Name</label>
                                <input type="text" name="colored_name[]" class="form-control" placeholder="Color Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('messages.product')}} Main Image</label>
                                <div class="custom-file">
                                    <input type="file" name="colored_image[]" data-id="0"  class="custom-file-input customFileEg"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                                </div>
        
                                <center style="display: none" id="color-image-viewer-section0" class="pt-2">
                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer0"
                                         src="{{asset($assetPrefixPath . '/assets/admin/img/400x400/img2.jpg')}}" alt="banner image"/>
                                </center>
                            </div>    
                        </div>    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.product')}} Different Angle {{__('messages.images')}}</label>
                                <div>
                                    <div class="row coba0"></div>
                                </div>
                            </div>
                        </div>                    
                    </div>
                    <a href="javascript:void(0)" style="float:right;margin-top: 18px;" title="Add More" id="add_more">Add More + </a>
                    <br>
                    <hr>
                    <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('script_2')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
    <script>
        $(document).ready(function(){
            var i = 1;
            $("#add_more").on("click",function(){
               
                var htmlData = '<div class="col-md-6 colored-image-section'+i+'">'+
                                '<div class="form-group">'+
                                    '<label class="input-label" for="exampleFormControlInput1">Color Name</label>'+
                                    '<input type="text" name="colored_name[]" class="form-control" placeholder="Color Name">'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6 colored-image-section'+i+'">'+
                                    '<div class="form-group">'+
                                        '<label>{{__('messages.product')}} Main {{__('messages.image')}}</label>'+
                                        '<div class="custom-file">'+
                                        '<input type="file" name="colored_image[]" data-id="'+i+'" class="custom-file-input customFileEg" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">'+
                                            '<label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>'+
                                            '</div>'+        
                                            '<center style="display: none" id="color-image-viewer-section'+i+'" class="pt-2">'+
                                            '<img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer'+i+'" src="{{asset($assetPrefixPath.'/assets/admin/img/400x400/img2.jpg')}}" alt="banner image"/>'+
                                            '</center>'+
                                             '</div>'+
                                             '</div>'+
                                          
                                            '<div class="col-md-12 colored-image-section'+i+'">'+
                                                '<div class="form-group">'+
                                                    '<label class="input-label" for="exampleFormControlInput1">{{__('messages.product')}} {{__('messages.images')}}</label>'+
                                                   
                                                        '<div class="row coba'+i+'"></div>'+
                                                       
                                                        '</div>'+
                                                        '</div> '+
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
                                        toastr.error("{{__('messages.please_only_input_png_or_jpg_type_file')}}", {
                                            CloseButton: true,
                                            ProgressBar: true
                                        });
                                    },
                                    onSizeErr: function (index, file) {
                                        toastr.error("{{__('messages.file_size_too_big')}}", {
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

        function getRestaurantData(route, vendor_id , id) {
            $.get({
                url: route+vendor_id,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
            $.get({
                url:'{{url('/')}}/api/v1/restaurants/details/'+restaurant_id,
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
            console.log("this",this)
            readURL(this);
            $('#image-viewer-section').show(1000);
        });

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
    </script>

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
               
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            $('.js-select2-custom').prop("required",true)
        });
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/vendor/get-restaurants',
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


    <script src="{{asset($assetPrefixPath . '/assets/admin')}}/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name;
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="{{__('messages.choice_title')}}" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="{{__('messages.enter_choice_values')}}" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

       
    </script>

    <script>
        CKEDITOR.replace('description');
        $('#food_form').on('submit', function (e) {
           
            e.preventDefault();
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
                url: '{{route('admin.product.store')}}',
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
                        toastr.success('product uploaded successfully!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{\Request::server('HTTP_REFERER')??route('admin.product.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
      <script src="{{asset($assetPrefixPath.'/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
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
                      toastr.error("{{__('messages.please_only_input_png_or_jpg_type_file')}}", {
                          CloseButton: true,
                          ProgressBar: true
                      });
                  },
                  onSizeErr: function (index, file) {
                      toastr.error("{{__('messages.file_size_too_big')}}", {
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
                    toastr.error("{{__('messages.please_only_input_png_or_jpg_type_file')}}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error("{{__('messages.file_size_too_big')}}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }); 

              
                });
      </script>
@endpush


