<style>

</style>
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
  $productImagePath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $product['image']) : asset('storage/app/public/product/' . $product['image']);    
  $productData = \App\Models\Product::find($product['id']);
  if ($productData->discount_type == 'amount') {
        $productData->discounted_price = number_format($productData->price - $productData->discount, 2);
    } else {
        if($productData->discount > 0){
        
            $discounted_price = (($productData->discount / 100) * $productData->price);
            $productData->discounted_price = number_format(($productData->price- $discounted_price),2);
        } else {
            $productData->discounted_price = 0;
        }

    }
    // Remove commas from discounted_price
    $discounted_price = str_replace(',', '', $productData->discounted_price);
@endphp
<div class="product-card card" onclick="quickView('{{$product->id}}')"  style="cursor: pointer;">
    <div class="card-header inline_product clickable p-0" style="height:134px;width:100%;overflow:hidden;">
        <div class="d-flex align-items-center justify-content-center d-block">
            <img src="{{$productImagePath}}" style="width: 100%; border-radius: 5%;">
        </div>
    </div>

    <div class="card-body inline_product text-center p-1 clickable"
         style="height:3.5rem; max-height: 3.5rem">
        <div style="position: relative;" class="product-title1 text-dark font-weight-bold">
            {{ Str::limit($product['name'], 15, '...') }}
        </div>
        <div class="justify-content-between text-center">
            <div class="product-price text-center">
                {{--@if($product->discount > 0)
                    <strike style="font-size: 12px!important;color: grey!important;">
                        {{\App\CentralLogics\Helpers::format_currency($product['price'])}}
                    </strike><br>
                @endif--}}
                <span class="text-accent text-dark font-weight-bold">
                    {{ $discounted_price }}
                </span>
            </div>
        </div>
    </div>
</div>
