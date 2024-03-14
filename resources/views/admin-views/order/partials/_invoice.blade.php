<style>
    #printableArea *:not(input, a){
        color: black;
    }
</style>
<div class="content container-fluid">
        <div class="row" id="printableArea" style="font-family: emoji;">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                           value="Proceed, If thermal printer is ready."/>
                    <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{__('messages.back')}}</a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5">
             
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>Booking ID : {{$order['order_id']}}</h5>
                    </div>



                    <div class="col-6">
                        <h5>
                            Booking Date :  {{date('d M Y ',strtotime($order['created_at']))}}
                        </h5>
                    </div>
                    

                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Booking From {{date('d M Y',strtotime($order['start_date']))}} To  {{date('d M Y',strtotime($order['end_date']))}}</h5>
                    </div>
                 </div>

                 <div class="row mt-3">
                    <div class="col-12">
                        <h5>Time Slot :  {{ $order['time_duration'] }}</h5>
                    </div>
                 </div>
                <div class="row mt-3">
                    <div class="col-6">
                        @php
                           $addressData  =   \App\Models\UsersAddress::where(['id' => $order['delivery_address_id']])->first();
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

                            $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". " floor " . "," . $addressData->landmark . "," . $addressData->area_name . "," . $addressData->zip_code;
                            } else {
                                $deliveryAddress = '';
                            }
    
                        @endphp
                            <h5>Delivery Address : {!! $deliveryAddress !!}</h5>

                            @if($order['damage_amount'] > 0)

                            <h5>Damage Amount : Rs.  {{ $order['damage_amount'] }}</h5>
                            @endif

                            

                    </div>
                    
                  
                    

                </div>
                <h5 class="text-uppercase"></h5>
                <span>---------------------------------------------------------------------------------</span>
                <table class="table table-bordered mt-3" style="width: 98%; color:#000000">
                    <thead>
                    <tr>
                        <th class="">S. No.</th>
                        <th class="">Item Name</th>
                        <th style="width: 10%">Quantity</th>
                        <th class="">{{__('messages.price')}}</th>
                        <th class="">Sub Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if ($order->order_type == 'parcel')
                        <tr>
                            <td>1</td>
                            <td>{{__('messages.delivery_charge')}}</td>
                            <td>{{\App\CentralLogics\Helpers::format_currency($order->delivery_charge)}}</td>
                        </tr>
                    @else
                        @php($sub_total=0)
                        @php($total_item_price=0)
                        @php($total_tax=0)
                        @php($total_dis_on_pro=0)
                        @php($add_ons_cost=0)
                        @php($count=0)
                         @php($test=0)
                          @php($total_gst=0)
                    
                        @foreach(json_decode($order->cart_items,true) as $detail)
                        <?php
                        
                         $gstData = \App\Models\BusinessSetting::where('key','gst_percent')->first();
                                            $gst = $gstData->value;
                                            $amount = $detail['item_price'] * $detail['quantity'];
                                           $total_item_price = $total_item_price + $detail['item_price'];
                                           // Calculate the GST amount
                                            if($detail['category_id'] == '1'){
                                                
                                                $gst_amount = ($gst / 100) * $detail['item_price'];
                                                 // Add the GST amount to the original total item price
                                                 $total_item_price += $gst_amount;
                                                 //echo "total_item_price".$total_item_price;
                                             
                                            } else {
                                                 $total_item_price =  $detail['item_price'];
                                            }
                                            
                                                 // Calculate the GST amount
                                           $gst_amount = 0;
                                              $start_timestamp = strtotime($order['start_date']);
                                            $end_timestamp = strtotime($order['end_date']);
                                            
                                            // Calculate the difference in seconds
                                            $difference_in_seconds = $end_timestamp - $start_timestamp;
                                            
                                            // Convert seconds to days
                                             $difference_in_days = floor($difference_in_seconds / (60 * 60 * 24));
                                            if($detail['category_id'] == '1'){
                                                
                                                 $gst_amount = ($gst / 100) * $detail['item_price'];
                                                  $total_item_price += $gst_amount;

                                            } else {
                                                $total_item_price =  $detail['item_price'];
                                            }
                                            
                                            $total_gst = $total_gst +  $gst_amount*$difference_in_days;
                                            
                                            
                  $itemColorImageId = (isset($detail['item_color_image_id']) && !empty($detail['item_color_image_id'])) ? $detail['item_color_image_id'] :  0 ;
                  $itemColorImageData = \App\Models\ProductColoredImage::where('id',$itemColorImageId)->first();
                  $itemData = \App\Models\Product::where('id',$detail['item_id'])->first();

                  $colorName = ($itemColorImageId == 0) ? $itemData->color_name : $itemColorImageData->color_name ;

                                            
                                            ?>
                                            
                        @php($count++)
                                <tr>
                                    <td class="text-break">
                                        {{$count}} <br>  </td>
 
                                    <td class="text-break">
                                        {{$detail['item_name']}} ({{ $colorName }})<br>  </td>
                                        <td class="">
                                            {{$detail['quantity']}}
                                        </td>
                                            <td style="width: 28%">
                                        Rs . {{$detail['item_price'] / $detail['quantity'] }}
                                    </td>
                                    <td style="width: 28%">
                                        <?php
                                                  if($detail['category_id'] == '1'){
                                                      $test =  $test + $detail['item_price']*$difference_in_days;
                                                      ?>
                                                  
                                                    <h5> Rs . {{ $detail['item_price']*$difference_in_days }} (for {{$difference_in_days }} days)</h5>
                                                    
                                                    <?php 
                                                    
                                                  } else {
                                                      $test =  $test + $detail['item_price'];
                                                    ?>
                                                        <h5> Rs  . {{ $detail['item_price'] }}</h5>
                                                    <?php }
                                                    ?>
                                    </td>
                                
                                    
                                   
                                </tr>
                                @php($sub_total=$sub_total+$amount)
                            
                           
                        @endforeach                        
                    @endif

                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            
                          
                            @if ($order->order_type !='parcel')
                          
                            <?php

                                $coupon_data = \App\Models\Coupon::where('id',$order->coupon_id)->first();
                                $coupon_discount_amount = (isset($coupon_data)) ?  $coupon_data['discount'] : 0;
                                ?>
                            
                            <dt class="col-6">{{__('messages.subtotal')}}:</dt>
                            <dd class="col-6">Rs.  {{$test}}</dd>
                               <dt class="col-sm-6">Refrrral {{ __('messages.discount') }}:</dt>
                                        <dd class="col-sm-6">-  Rs.  {{  $order['referral_discount'] ?? 0 }}</dd>
                                        <dt class="col-sm-6">{{ __('messages.coupon') }} {{ __('messages.discount') }}:</dt>
                                        <dd class="col-sm-6">-  Rs. {{  $order['coupon_discount'] ?? 0 }}</dd>    
                            
                             <dt class="col-sm-6">{{ __('messages.gst') }}  :</dt>
                            <dd class="col-sm-6">+ Rs. {{  $order['gst_amount'] ?? 0 }} </dd>
                                
                                
                                
                            <dt class="col-6"></dt>
                            <dd class="col-6">
                          
                       
                            <dt class="col-6">{{__('messages.delivery_charge')}}:</dt>
                            <dd class="col-6">
                                @php($del_c=$order['delivery_charge'])
                              + Rs. {{$del_c}}
                            </dd>      
                            
                                
                            <dt class="col-sm-6">Pending Amount:</dt>
                            <dd class="col-sm-6"> Rs.  {{ $order['pending_amount'] }} </dd>
                          
                            @endif


                            <dt class="col-6" style="font-size: 20px">{{__('messages.total')}}:</dt>
                            <dd class="col-6" style="font-size: 20px">
                               <?php  $grandTotal = $order['paid_amount'];?>
                               Rs.  {{ $grandTotal }}
                            
                            </dd>
                        </dl>
                    </div>
                </div>
                <span>---------------------------------------------------------------------------------</span>
                <h5 class="text-center pt-3">
                    """{{__('THANK YOU')}}"""
                </h5>
                <span>---------------------------------------------------------------------------------</span>
            </div>
        </div>
    </div>
