@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
@endphp
@foreach($products as $key=>$food)
    <tr>
        <td>{{$key+1}}</td>
        <?php
                
                                     $productImageoPath = (env('APP_ENV') == 'local') ? asset('storage/product/' . $food['image']) : asset('storage/app/public/product/' . $food['image']);        
                                    ?>
                                    <td>
                                        <a class="media align-items-center" href="{{route('admin.product.view',[$food['id']])}}">
                                            <img class="avatar avatar-lg mr-3" src="{{$productImageoPath}}" 
                                                  alt="{{$food->name}} image">
                                            <div class="media-body">
                                                <h5 class="text-hover-primary mb-0">{{Str::limit($food['name'],20,'...')}}</h5>
                                            </div>
                                        </a>
                                    </td>
        <td>
        {{Str::limit($food->category,20,'...')}}
        </td>
       
        <td>{{\App\CentralLogics\Helpers::format_currency($food['price'])}}</td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$food->id}}">
                <input type="checkbox" onclick="location.href='{{route('admin.product.status',[$food['id'],$food->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$food->id}}" {{$food->status?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>
        <td>
            <a class="btn btn-sm btn-white"
                href="{{route('admin.product.edit',[$food['id']])}}" title="{{__('messages.edit')}} {{__('messages.food')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn-white" href="javascript:"
                onclick="form_alert('food-{{$food['id']}}','Want to delete this item ?')" title="{{__('messages.delete')}} {{__('messages.product')}}"><i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('admin.product.delete',[$food['id']])}}"
                    method="post" id="food-{{$food['id']}}">
                @csrf @method('delete')
            </form>
        </td>
    </tr>
@endforeach
