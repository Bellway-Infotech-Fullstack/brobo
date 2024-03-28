<style>
    .btn-check {
        position: absolute;
        clip: rect(0,0,0,0);
        pointer-events: none;
    }

    .choice-input{
        width: 7rem;
    }
    .addon-input{
        height: 7rem;
        width: 7rem;
    }
    .addon-quantity-input{        
        height: 2rem;
        width: 7rem;
        z-index: 9;
        bottom: 1rem;
        visibility: hidden;
    }
    .check-label{
        background-color: #F3F3F3;
        color: #000000;
        border-width:2px;
        border-color: #BABFC4;
        font-weight: bold;
    }
    .btn-check:checked + .check-label {
        background-color: #EF7822;
        color: #FFFFFF;
        border:none;
    }
    .image-list {
        list-style-type: none;
        padding: 0;
        margin-top: 10px;
        margin-left: 15px;
    }

    .image-list li {
        margin-right: 10px; 
        display: inline-block;
    }

    .image-list img {
        height: 100px;
        width: 100px;
        border-radius: 5%;
        cursor: pointer;
    }
    .current-image{
        border: 1px solid black;
    }
</style>
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
  $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product['image']) : asset('storage/app/public/product/' . $product['image']);  
  $productData = \App\Models\Product::find($product['id']);

  $itemDetail = \App\Models\Product::where('id', $product['id'])
                ->with('coloredImages')
                ->select('*')
                ->get();  

           

$items = $itemDetail->map(function ($item) {
        // Modify the item's image property
        $main_item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
        if ($main_item_image === null) {
            $main_item_image = '';
        }
    
    
    // Update colored images paths

    $mainProductColorName = $item->color_name;
    $mainProductImages = $item->images; 


        //echo "main_item_image".$main_item_image;

    // Create the main item color data
    $main_item_colored_data = [
        'id' => 0,
        "product_id" => $item->id,
        'color_name' => $mainProductColorName,
        'image' => $main_item_image,
        'images' => $mainProductImages,
        'created_at' => $item->created_at, 
        'updated_at' => $item->updated_at, 
    ];

    // Insert the main item color data at the beginning of the coloredImages array
    $item->coloredImages->prepend((object)$main_item_colored_data);

    $item->coloredImages->map(function ($coloredImage) use ($mainProductImages,$main_item_image)  {


            //array_push($item->coloredImages, $main_item_colored_data);
        
        // Add image path to colored_image
        $all_item_colored_images = array();
        
        if($coloredImage->id == 0){
            array_push($all_item_colored_images, $main_item_image);
            foreach ($mainProductImages as $key => $val) {
            /*  $main_item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $main_item_image) : asset('storage/app/public/product/' . $main_item_image);*/
            
            $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
            array_push($all_item_colored_images, $item_image);
        }
            
            $coloredImage->images = $all_item_colored_images;
        } else {
            $coloredImage->image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $coloredImage->image) : asset('storage/app/public/product/colored_images/' . $coloredImage->image);

        
        if (isset($coloredImage->images) && !empty($coloredImage->images)) {

            
            // array_push($all_item_colored_images, $main_item_image);
            array_push($all_item_colored_images, $coloredImage->image);

            foreach ($coloredImage->images as $key => $val) {
                $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $val) : asset('storage/app/public/product/colored_images/' . $val);
                array_push($all_item_colored_images, $item_image);
            }
            // array_push($all_item_colored_images, $main_item_image);
            
            $coloredImage->images = $all_item_colored_images;
        }
        }

        

        return $coloredImage;
    });

        // Modify the item's image property
        $item->image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $item->image) : asset('storage/app/public/product/' . $item->image);
        if ($item->image === null) {
            $item->image = '';
        }

    $all_item_images = array();
    if (isset($item->images) && !empty($item->images)) {
        array_push($all_item_images, $item->image);
        foreach ($item->images as $key => $val) {
            $item_image = (env('APP_ENV') == 'local') ? asset('storage/product/' . $val) : asset('storage/app/public/product/' . $val);
            array_push($all_item_images, $item_image);
        }
        $item->images = $all_item_images;
    }

    if ($item->images === null) {
        $item->images = [];
    }

    // Check and set description to blank if null
    if ($item->description === null) {
        $item->description = '';
    }

        // Calculate discount price
    
    if ($item->discount_type == 'amount') {
        $item->discounted_price = number_format($item->price - $item->discount, 2);
    } else {
        if($item->discount > 0){
            $discounted_price = (($item->discount / 100) * $item->price);
            $item->discounted_price = number_format(($item->price- $discounted_price),2);
        } else {
                $item->discounted_price = 0;
        }
    }
    // Remove commas from discounted_price
    $item->discounted_price = str_replace(',', '', $item->discounted_price);

    // get sub catefory name

    $item->sub_category_id = $item->category_id;

    $category_data = \App\Models\Category::find($item->category_id);
    if($category_data){
            $item->category_id = $category_data->parent_id;

    $item->sub_category_name = $category_data->name ?? '';
    } else {
        $item->category_id =  '';
        $item->sub_category_name = '';
    }

    

    return $item;
});

$mainProductDifferentAngleImages = $items[0]->images;



  if ($productData->discount_type == 'amount') {
        $productData->discounted_price = number_format($productData->price - $productData->discount, 2);
    } else {
        if($productData->discount > 0){
        
            $discounted_price = (($productData->discount / 100) * $productData->price);
            $productData->discounted_price = number_format(($productData->price- $discounted_price),2);
        } else {
            $productData->discounted_price = $productData->price;
        }

    }
    // Remove commas from discounted_price
    $discounted_price = str_replace(',', '', $productData->discounted_price);
@endphp
<div class="modal-header p-0">
    <h4 class="modal-title product-title">
    </h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex flex-row">
        <!-- Product gallery-->
        <div class="d-flex align-items-center justify-content-center active" style="height:9.5rem;">
            <img class="img-responsive" style="height:100%;width:auto;overflow:hidden;border-radius: 5%;"
                src="{{ $itemImage }}" 
           
                    alt="Product image" width="" id="main_image">
            <div class="cz-image-zoom-pane"></div>
        </div>
        <!-- Product details-->
        <div class="details pl-2">
            @if ($item_type=='food')
            <a href="#" class="h3 mb-2 product-title">{{$product->name}}</a>
            @else
            <div class="h3 mb-2 product-title">{{ $product->name }}</div>
            @endif
            <div class="mb-3 text-dark">
                <span class="h3 font-weight-normal text-accent mr-1">
                    {{ $discounted_price }}  
                </span>
                @if($productData->discount > 0)
                    <strike style="font-size: 12px!important;">
                        {{ $product->price }}
                       
                    </strike>
                @endif
            </div>

            @if($product->discount > 0)
                <div class="mb-3 text-dark">
                    <strong>Discount : </strong>
                    <?php
                       $discount_type =  $productData->discount_type;
                       if($discount_type == 'percent'){
                    ?>
                    <strong id="set-discount-amount">{{ $product->discount  }} % </strong>

                    <?php } else { ?>
                    <strong id="set-discount-amount">Rs. {{ $product->discount  }} </strong>
                    <?php }?>
                </div>
            @endif
            <!-- Product panels-->
            {{--<div style="margin-left: -1%" class="sharethis-inline-share-buttons"></div>--}}
        </div>
    </div>
    <div class="row">
        <ul class="image-list" id="main_image_section">
            <?php
          
             if(isset($mainProductDifferentAngleImages) && !empty($mainProductDifferentAngleImages)){
                foreach($mainProductDifferentAngleImages as $key => $value){
                    $className = ($key === 0) ? "current-image" : "";
            ?>
            <li>            
                <img class="img-responsive {{$className}}" src="{{$value}}"  alt="Product image" onclick="displayImage('{{$value}}',this)">
            </li>
    
            <?php }} ?>
        </ul>
    
       
    </div>
    <strong class="h3">Variants : </strong>
    <div class="row">
     
        <ul class="image-list">
    
            <?php
               $coloredImages =  $items[0]->coloredImages;
               
              
                 if(isset($coloredImages) && !empty($coloredImages)){
                    foreach($coloredImages as $key => $value){
                        $className = ($key === 0) ? "current-image" : "";
                ?>
                <li>            
                    <img class="img-responsive {{$className}}" src="{{$value->image}}"  alt="Product image" onclick="getProductColorImageDetail('{{$value->image}}','{{$value->id}}','{{$value->product_id}}',this)">
                    <p style="margin-left:35px;"> {{ $value->color_name }} </p>
                </li>
             
            <?php }} ?>
        </ul>
    </div>
    <div class="row pt-2">
        <div class="col-12">
            <h2>{{__('messages.description')}}</h2>
            <span class="d-block text-dark">
                {!! $product->description ?? 'N/A' !!}
            </span>
            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="cart_item_key" value="{{ $item_key }}">
                <input type="hidden" name="item_type" value="{{ $item_type }}">
                <input type="hidden" name="order_details_id" value="{{ $cart_item['id'] }}">
                <input type="hidden" name="order_id" value="{{ $order_id }}">                
                <input type="hidden" name="item_price" value="{{ $discounted_price }}">
                <input type="hidden" name="item_color_image_id" id="item_color_image_id" value="{{ $item_color_image_id }}">

                <!-- Quantity + Add to cart -->
                <div class="d-flex justify-content-between">
                    <div class="product-description-label mt-2 text-dark h3">{{__('Quantity')}}:</div>
                    <div class="product-quantity d-flex align-items-center">
                        <div class="input-group input-group--style-2 pr-3"
                                style="width: 160px;">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark" type="button"
                                        data-type="minus" data-field="quantity"
                                        {{$cart_item['quantity'] <= 1? 'disabled="disabled"':''}}  style="padding: 10px">
                                        <i class="tio-remove  font-weight-bold"></i>
                                </button>
                            </span>
                            <input type="text" name="quantity"
                                    class="form-control input-number text-center cart-qty-field"
                                    placeholder="1" value="{{$cart_item['quantity']}}" min="1" max="100">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark" type="button" data-type="plus"
                                        data-field="quantity" style="padding: 10px">
                                        <i class="tio-add  font-weight-bold"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
              
                
                <div class="d-flex justify-content-left flex-wrap">
              
                </div>
                <div class="row no-gutters d-none mt-2 text-dark" id="chosen_price_div" >
                    <div class="col-2">
                        <div class="product-description-label">{{__('Total Price')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            <strong id="chosen_price"></strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <button class="btn btn-sm btn-danger"
                            onclick="removeFromCart({{$item_key}})"
                            type="button"
                            style="width:37%;">
                            <i class="tio-delete"></i>
                        {{trans('messages.delete')}}
                    </button>
                    <button class="btn btn-sm btn-primary"
                            onclick="update_order_item()"
                            type="button"
                            style="width:37%;">
                            <i class="tio-edit"></i>
                        {{trans('messages.update')}}
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    cartQuantityInitialize();
    var img_src = "<?=$itemImage?>";
    var color_image_id = "<?=$item_color_image_id?>";
    var product_id = "<?=$item_color_image_id?>";
    getProductColorImageDetail(img_src,color_image_id,product_id,this);
  //  getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
       // getVariantPrice();
    });

    function displayImage(img_src,ele){
        $(".image-list").find("img").removeClass("current-image");
        $(ele).addClass("current-image");
        $("#main_image").attr("src",img_src);
    }

     function getProductColorImageDetail(img_src,color_image_id,product_id,ele){
        var tokenValue = $('input[name="_token"]').val();
        $(".image-list").find("img").removeClass("current-image");
        $("#item_color_image_id").val(color_image_id);
          
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': tokenValue
                }
            });
            $.post({
                url: '{{ route('admin.order.get-product-color-image-detail') }}',
                data: {"color_image_id":color_image_id,"product_id":product_id},
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    var images = data.data.images;
                    var htmlData = '';
                    if (images.length > 0) {
                        $.each(images, function(k, val) {
                            var className = (k === 0) ? "current-image" : "";
                            htmlData += '<li><img class="img-responsive ' + className + '" src="' + val + '" alt="Product image" onclick="displayImage(\'' + val + '\', this)"></li>';


                        });
                    }
                    $("#main_image_section").html(htmlData);
                    $(ele).addClass("current-image");  
                    $("#main_image").attr("src",img_src);
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
    }
</script>

