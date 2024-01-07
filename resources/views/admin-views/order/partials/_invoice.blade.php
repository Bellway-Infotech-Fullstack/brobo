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
                        <h5>Booking ID  {{$order['order_id']}}</h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            {{date('d/M/Y '.config('timeformat'),strtotime($order['created_at']))}}
                        </h5>
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
                        @php($total_tax=0)
                        @php($total_dis_on_pro=0)
                        @php($add_ons_cost=0)
                        @php($count=0)
                        @foreach(json_decode($order->cart_items,true) as $detail)
                        @php($count++)
                                <tr>
                                    <td class="text-break">
                                        {{$count}} <br>  </td>

                                    <td class="text-break">
                                        {{$detail['item_name']}} <br>  </td>
                                        <td class="">
                                            {{$detail['quantity']}}
                                        </td>
                                    <td style="width: 28%">
                                        @php($amount=($detail['item_price']))
                                        Rs . {{$detail['item_price']/ $detail['quantity']}}
                                    </td>
                                    <td style="width: 28%">
                                        Rs . {{$detail['item_price']}}
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
                            <dd class="col-6">
                                Rs.  {{$sub_total}}</dd>
                            <dt class="col-6"></dt>
                            <dd class="col-6">
                          
                       
                            <dt class="col-6">{{__('messages.delivery_charge')}}:</dt>
                            <dd class="col-6">
                                @php($del_c=$order['delivery_charge'])
                               Rs. {{$del_c}}
                                <hr>
                            </dd>      
                            <dt class="col-6">Coupon Discount:</dt>
                            <dd class="col-6">
                                - {{($coupon_discount_amount)}}</dd>                          
                            @endif


                            <dt class="col-6" style="font-size: 20px">{{__('messages.total')}}:</dt>
                            <dd class="col-6" style="font-size: 20px">
                               <?php  
                                $grandTotal = ($sub_total + $del_c)  - $coupon_discount_amount;

                                ?>
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
