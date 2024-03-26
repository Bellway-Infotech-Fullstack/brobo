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
    float: right;
    top: 86px;
    position: relative;
    left: 451px;
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
                                    <select name="customer_id" id="customer_id" class="form-control js-example-theme-single">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $key => $value)
                                          <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label" for="category_id"> Select Category</label>
                                    <select name="category_id" id="category_id" class="form-control js-example-theme-single">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $value)
                                          <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="input-label" for="sub_category_id"> Select Sub Category</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-control js-example-theme-single">
                                    </select>
                                </div>

                                <br>
                              </div>
                            <div class="form-group mt-5"  id="product_section">
                                <div class="row prow">
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
                                <a href="javascript:void(0)" style="margin-top: 18px;left:1416px;
                                position: relative;" title="Add More" id="add_more">Add More + </a>
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
            var i = 1;
            $("#add_more").on("click", function() {
                var category_id = $("#category_id").val();
                var sub_category_id = $("#sub_category_id").val();
                getProducts(category_id,sub_category_id,i);

                var addhtmlData = '<div class="row mt-5 prow product-section' + i + '">' +
                    '<div class="col-md-4 product-section' + i + '">' +
                    '<label class="input-label" for="product_id" >Select Product</label>' +
                    '<select name="product_id[]" id="product_id' + i + '" class="form-control js-example-theme-single product_id"></select>' +
                    '</div>' + // End of .col-md-4.product-section div
                    '<div class="col-md-4 product-section' + i + '">' +
                    '<label class="input-label" for="quantity">Enter Quantity</label>' +
                    '<input name="quantity[]"  class="form-control" type="number" min="0">' +
                    '</div>' + // End of .col-md-4.product-section div
                    '<a href="javascript:void(0)" class="remove-section" data-row-id="'+i+'" style="float:right">Remove</a>'+
                    '</div>'; // End of .row div

                    i++;   

               
                $("#product_section").append(addhtmlData);
                $(".js-example-theme-single").select2({});
            });



            $(document).on("click",".remove-section",function(){
                var row_id = $(this).attr("data-row-id");
                $(".product-section"+row_id).remove();
                $(this).remove();
            });

            $("#category_id").on("change", function () {
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

                            $("#sub_category_id").html(cathtmlData);
                        }


                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });

            
            });

            $("#sub_category_id").on("change", function () {
                var category_id = $("#category_id").val();
                var sub_category_id = $(this).val();
                getProducts(category_id,sub_category_id,0);
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
                        var subcathtmlData = '';
                        if (data.length > 0) {
                            data = JSON.parse(data);
                            subcathtmlData += "<option value=''>Select Sub Category</option>";
                            $.each(data, function (k, val) {
                                subcathtmlData += "<option value='" + val.id + "'>" + val.name + "</option>";
                            });


                            $("#sub_category_id").html(subcathtmlData);
                        }


                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
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

   
    </script>
@endpush
