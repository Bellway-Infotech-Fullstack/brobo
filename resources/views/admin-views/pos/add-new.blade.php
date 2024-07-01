@extends('layouts.admin.app')
@section('title','Add POS')
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
.prow{
    border: 1px solid #c7b3b3;
    padding: 12px;
    border-radius: 5px;
}
.remove-section{
    margin-top: 30px; /* Adjust margin-top as needed for proper alignment */

}

        </style>
@endpush

@section('content')
<div class="content container-fluid"> 


    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    POS Form
                </div>
                <div class="card-body">
                    <form action="{{route('admin.pos.addproductsincart')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="input-label" for="customer_id"> Select Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control js-example-theme-single" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $key => $value)
                                          <option value="{{$value->id}}" {{ old('customer_id') == $value->id ? 'selected' : '' }}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label" for="category_id"> Select Category</label>
                                    <select name="category_id[]" id="category_id0" class="form-control js-example-theme-single" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $value)
                                          <option value="{{$value->id}}" {{ old('category_id') == $value->id ? 'selected' : '' }}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label" for="sub_category_id"> Select Sub Category</label>
                                    <select name="sub_category_id[]" id="sub_category_id0" class="form-control js-example-theme-single">
                                    </select>
                                </div>

                                <br>
                              </div>
                            <div class="form-group mt-5"  id="add_more_section">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="input-label" for="product_id"> Select Product</label>
                                        <select name="product_id[]" id="product_id0" class="form-control js-example-theme-single product_id">
                                            
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-label" for="quantity">Enter Quantity</label>
                                        <input name="quantity[]"  class="form-control" type="number" min="0">
                                    </div>
                                </div>
                                <div style="float:right">
                                <a href="javascript:void(0)" style="margin-top: 18px" title="Add More" id="add_more">Add More + </a>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                       

                        <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>

        $(document).ready(function(){
            var category_id = $("#category_id").val();
            var sub_category_id = $("#sub_category_id").val();
            getProducts(category_id,sub_category_id,0);
            var i = 1;
            
            // Assuming you have a JavaScript variable 'categories' containing your category data
            var categories = {!! json_encode($categories) !!}; // Assuming $categories is passed from PHP

              // Function to populate categories in select dropdown
    function populateCategories() {
        var options = '<option value="">Select Category</option>';
        $.each(categories, function(index, category) {
            options += '<option value="' + category.id + '">' + category.name + '</option>';
        });
        return options;
    }

    // Function to populate subcategories
    function populateSubcategories(subcategories) {
        var options = '<option value="">Select Sub Category</option>';
        $.each(subcategories, function(index, subcategory) {
                options += '<option value="' + subcategory.id + '">' + subcategory.name + '</option>';
            
        });
        return options;
    }

    // Function to populate products based on category and subcategory
    function populateProducts(products) {
        var options = '<option value="">Select Product</option>';
        $.each(products, function(index, product) {
                options += '<option value="' + product.id + '">' + product.name + '</option>';
            
        });
        return options;
    }

    // Add more section functionality
    $("#add_more").on("click", function() {
        var addhtmlData =
            '<div class="row mt-5 prow add-more-section' + i + '">' +
            '<div class="col-md-3 add-more-section' + i + '">' +
            '<label class="input-label" for="category_id' + i + '"> Select Category</label>' +
            '<select name="category_id[]" id="category_id' + i + '"  class="form-control js-example-theme-single category-select" required>' +
            populateCategories() + // Populate categories dynamically
            '</select>' +
            '</div>' +
            '<div class="col-md-3 add-more-section' + i + '">' +
            '<label class="input-label" for="sub_category_id' + i + '"> Select Sub Category</label>' +
            '<select name="sub_category_id[]" id="sub_category_id' + i + '"  class="form-control js-example-theme-single subcategory-select">' +
            '<option value="">Select Subcategory</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-md-3 add-more-section' + i + '">' +
            '<label class="input-label" for="product_id' + i + '">Select Product</label>' +
            '<select name="product_id[]" id="product_id' + i + '" class="form-control js-example-theme-single product-select"></select>' +
            '</div>' +
            '<div class="col-md-3 add-more-section' + i + '">' +
            '<label class="input-label" for="quantity">Enter Quantity</label>' +
            '<input name="quantity[]" class="form-control" type="number" min="0">' +
            '</div>' +
            
            '</div>'+
            '<div style="float:right">'+
            '<a href="javascript:void(0)" class="remove-section" data-row-id="' + i + '">Remove</a>' +
            '</div>';

        i++;

        $("#add_more_section").append(addhtmlData);
        $(".js-example-theme-single").select2();
    });

    // Event delegation for dynamic elements
    $(document).on("change", "[id^=category_id]", function() {
        var categoryId = $(this).val();
        var subcategorySelect = $(this).closest('.row').find('.subcategory-select');
     //   getProducts(categoryId,sub_category_id,0);
        
        var all_subcategories = fetchSubcategories(categoryId);
        console.log("subcategorySelect",subcategorySelect)
        subcategorySelect.html(populateSubcategories(all_subcategories));

       console.log("sub ddd",all_subcategories)
      // Populate subcategories select dropdown
     // subcategorySelect.html('<option value="">Loading...</option>'); // Placeholder while loading
     
        var productSelect = $(this).closest('.row').find('.product-select');

        var allProducts = fetchProducts(categoryId,'');
        console.log("allProducts",allProducts)
        productSelect.html(populateProducts(allProducts));


//        productSelect.html('<option value="">Select Product</option>'); // Reset products
    });

    $(document).on("change", "[id^=sub_category_id]", function() {
        var categoryId = $(this).closest('.row').find('.category-select').val();
        var subcategoryId = $(this).val();
        var productSelect = $(this).closest('.row').find('.product-select');
        var allProducts = fetchProducts(categoryId,subcategoryId);
        productSelect.html(populateProducts(allProducts));

    //    productSelect.html(populateProducts(categoryId, subcategoryId));
    });

            $(document).on("click",".remove-section",function(){
                var row_id = $(this).attr("data-row-id");
                $(".add-more-section"+row_id).remove();
                $(this).remove();
            });

            $("#category_id0").on("change", function () {
                var category_id = $(this).val();
                getProducts(category_id,'',0);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.pos.getsubcategories') }}',
                    data: { "category_id": category_id },
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        var cathtmlData = '';
                        if (data.length > 0) {
                            data = JSON.parse(data);
                            cathtmlData += "<option value=''>Select Sub Category</option>";
                            $.each(data, function (k, val) {
                                cathtmlData += "<option value='" + val.id + "'>" + val.name + "</option>";
                            });

                            $("#sub_category_id0").html(cathtmlData);
                        }


                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });

            
            });

            $("#sub_category_id0").on("change", function () {
                var category_id = $("#category_id0").val();
                var sub_category_id = $(this).val();
                getProducts(category_id,sub_category_id,0);
               
            });


            $(".js-example-theme-single").select2({});

        });

        

        function getProducts(category_id,sub_category_id,id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.getproducts') }}',
                data: { "category_id": category_id,"sub_category_id": sub_category_id },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    var phtmlData = '';
                    console.log("pr data", data);
                    if (data.length > 0) {
                        data = JSON.parse(data);
                        phtmlData += "<option value=''>Select Product</option>";
                        $.each(data, function (k, val) {
                            phtmlData += "<option value='" + val.id + "'>" + val.name + "</option>";
                        });

                       

                        $("#product_id"+id).html(phtmlData);
                    }


                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function fetchSubcategories(categoryId){
            var subcategories = [];

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.pos.getsubcategories') }}',
                    data: { "category_id": categoryId },
                    async: false, // Synchronous for demonstration purposes; use async: true in real scenarios

                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        var cathtmlData = '';
                        if (data.length > 0) {
                            data = JSON.parse(data);
                            subcategories = data;
                            console.log("dd",subcategories)
                        /*    cathtmlData += "<option value=''>Select Sub Category</option>";
                            $.each(data, function (k, val) {
                                cathtmlData += "<option value='" + val.id + "'>" + val.name + "</option>";
                            });

                            $("#sub_category_id0").html(cathtmlData);*/
                        }


                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
                return subcategories; // Return fetched subcategories (mocked here)

            
            
        }

        function fetchProducts(category_id,sub_category_id){
            var products = [];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.getproducts') }}',
                data: { "category_id": category_id,"sub_category_id": sub_category_id },
                async: false, // Synchronous for demonstration purposes; use async: true in real scenarios

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    var phtmlData = '';
                    console.log("pr data", data);
                    if (data.length > 0) {
                        data = JSON.parse(data);
                        products = data;
                       /* phtmlData += "<option value=''>Select Product</option>";
                        $.each(data, function (k, val) {
                            phtmlData += "<option value='" + val.id + "'>" + val.name + "</option>";
                        });

                       

                        $("#product_id"+id).html(phtmlData);*/
                    }


                },
                complete: function () {
                    $('#loading').hide();
                },
            });
            return products;
        }

   
    </script>
@endpush
