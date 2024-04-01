
<style>
    body {
        font-family: Arial, sans-serif;
    }
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        font-size:8px;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    sup{
            right:unset;
        }
</style>
</head>
<body>
<h1>Order List</h1>
<table>
    <thead>
        <tr>
            <th class="">{{__('messages.#')}}</th>
            <th class="table-column-pl-0">{{__('messages.order')}} ID</th>
            <th>Booking Date</th>
            <th>{{__('messages.customer')}} Name</th>
            <th>{{__('messages.customer')}} Mobile Number</th>
            <th>Delivery Address</th>
            <th>Pin Location</th>
            <th class="">Product Names</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Time Slot</th>
            <th>Paid Amount</th>
             <th>GST Number</th>
            <th>{{__('messages.payment')}} {{__('messages.status')}}</th>                       
            <th>{{__('messages.order')}} {{__('messages.status')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $key => $order)
                            
        <tr class="status-{{$order['order_status']}} class-all">
            <td class="">
                {{$key + 1}}
            </td>
            <td class="table-column-pl-0">
             {{$order['order_id']}}

      
            </td>
            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
            <td>
                @if($order->customer)
                {{$order->customer['name']}}

                  
                @else
                    <label class="badge badge-danger">{{__('messages.invalid')}} {{__('messages.customer')}} {{__('messages.data')}}</label>
                @endif
            </td>
            <td> {{ $order->customer['mobile_number'] ?? 'N/A' }}</td>
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

                        $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". "&nbsp;floor " . "," . $addressData->landmark . "," . $addressData->area_name . "," . $addressData->zip_code;

                    } else {
                        $deliveryAddress = 'N/A';
                    }

                    echo $deliveryAddress;
            ?>
             </td>

             <td>{{ $order->pin_location ?? 'N/A'  }} </td>
             <td>
                <?php
                $productNames = '';
                $cartItems = json_decode($order->cart_items, true);
                if(isset($cartItems) && !empty($cartItems)){
                    foreach($cartItems as $key => $value){
                        $productNames .= "," . $value['item_name'];
                    }
                    $productNames = trim($productNames, ',');
                }
                ?>
                
                {{ $productNames }}
                

             </td>
        
            <td>{{date('d M Y',strtotime($order['start_date']))}}</td>
            <?php
                    if(!empty($order['end_date'])){
                ?>
                <td>{{date('d M Y',strtotime($order['end_date']))}}</td>
                <?php } else{ ?>
                    <td>N/A</td>
                <?php } ?>
            <td>{{ $order['time_duration'] }}</td>
            <td>Rs. {{ ($order->paid_amount)  }} </td>
            <td> {{ $order->gst_number ?? 'N/A'  }} </td>
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
        </tr>
        @endforeach
    </tbody>
</table>
