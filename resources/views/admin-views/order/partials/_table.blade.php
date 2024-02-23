@foreach($orders as $key=>$order)

<tr class="status-{{$order['order_status']}} class-all">
    <td class="">
        {{$key + 1}}
    </td>
    <td class="table-column-pl-0">
     <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['order_id']}}</a>


    </td>
    <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
    <td>
        @if($order->customer)
         <a class="text-body text-capitalize"
               href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['name']}}</a> 

          
        @else
            <label class="badge badge-danger">{{__('messages.invalid')}} {{__('messages.customer')}} {{__('messages.data')}}</label>
        @endif
    </td>

    <td> {{ $order->customer['mobile_number'] }}</td>
    <td>
        <?php
        $addressData  =   \App\Models\UsersAddress::where('id' , $order->delivery_address_id)->first();
        if(isset($addressData) && !empty($addressData)){
                $deliveryAddress = $addressData->house_name . ",";

                // Add floor number with suffix
                $floorNumber = $addressData->floor_number;
                if ($floorNumber % 100 >= 11 && $floorNumber % 100 <= 13) {
                    $suffix = 'th';
                } else {
                    switch ($floorNumber % 10) {
                        case 1:
                            $suffix = 'st';
                            break;
                        case 2:
                            $suffix = 'nd';
                            break;
                        case 3:
                            $suffix = 'rd';
                            break;
                        default:
                            $suffix = 'th';
                            break;
                    }
                }

                $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". "&nbsp;&nbsp;&nbsp;&nbsp;floor " . "," . $addressData->landmark . "," . $addressData->area_name;

            } else {
                $deliveryAddress = 'N/A';
            }

            echo $deliveryAddress;
    ?>
     </td>
      <td>{{ $order->pin_location ?? 'N/A'  }} </td>

    <td>{{date('d M Y',strtotime($order['start_date']))}}</td>
    <td>{{date('d M Y',strtotime($order['end_date']))}}</td>
    <td>{{ $order['time_duration'] }}</td>
    <td>Rs. {{ number_format($order->paid_amount)  }} </td>
    <td>
      
        <span class="badge badge-soft-success">
          <span class="legend-indicator bg-success"></span>{{__('messages.paid')}}
        </span>
    
</td>
    <td class="text-capitalize">
        @if($order['status']=='ongoing')
            <span class="badge badge-soft-info ml-2 ml-sm-3">
              <span class="legend-indicator bg-info"></span>Ongoing
            </span>
        @elseif($order['status']=='cancelled')
            <span class="badge badge-soft-danger ml-2 ml-sm-3">
              <span class="legend-indicator bg-danger"></span>Cancelled
            </span>

        @elseif($order['status']=='completed')
            <span class="badge badge-soft-success ml-2 ml-sm-3">
              <span class="legend-indicator bg-success"></span>{{__('messages.delivered')}}
            </span>
        @elseif($order['status']=='failed')
            <span class="badge badge-soft-danger ml-2 ml-sm-3">
              <span class="legend-indicator bg-danger text-capitalize"></span>{{__('messages.payment')}}  {{__('messages.failed')}}
            </span>
        @else
            <span class="badge badge-soft-danger ml-2 ml-sm-3">
              <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['status'])}}
            </span>
        @endif
    </td>
   {{--  <td class="text-capitalize">
        @if($order['order_type']=='take_away')
            <span class="badge badge-soft-dark ml-2 ml-sm-3">
                <span class="legend-indicator bg-dark"></span>{{__('messages.take_away')}}
            </span>
        @else
            <span class="badge badge-soft-success ml-2 ml-sm-3">
              <span class="legend-indicator bg-success"></span>{{__('messages.delivery')}}
            </span>
        @endif
    </td> --}}
    <td>
        <a class="btn btn-sm btn-white"
                   href="{{route('admin.order.details',['id'=>$order['id']])}}"><i
                        class="tio-visible"></i> {{__('messages.view')}}</a>

      @if($order['status']=='cancelled' && $order['refunded'] == NULL)
      <a class="btn btn-sm btn-white mt-2 refund-money"
      data-order-id="{{ $order['id'] }}"><i
           class="tio-visible"></i> Refund
       </a>

      @endif
    </td>
</tr>

@endforeach
