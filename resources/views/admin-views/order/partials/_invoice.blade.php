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
                @if ($order->store)
                <div class="text-center pt-4 mb-3">
                  
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        {{__('messages.phone')}} : {{$order->store->phone}}
                    </h5>
               
		        </h5>
           
		     
                </div>                    
                

                <span>---------------------------------------------------------------------------------</span>
                @endif
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>{{__('order_id')}} : {{$order['id']}}</h5>
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
                        <th style="width: 10%">Quantity</th>
                        <th class="">Name</th>
                        <th class="">{{__('messages.price')}}</th>
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
                        @foreach(json_decode($order->cart_items,true) as $detail)
                           
                                <tr>
                                    <td class="">
                                        {{$detail['quantity']}}
                                    </td>
                                    <td class="text-break">
                                        {{$detail['item_name']}} <br>
                                      
                                        
                                        

                                    </td>
                                    <td style="width: 28%">
                                        @php($amount=($detail['item_price']))
                                        {{\App\CentralLogics\Helpers::format_currency($amount)}}
                                    </td>
                                   
                                    
                                   
                                </tr>
                                @php($sub_total=$amount)
                            
                           
                        @endforeach                        
                    @endif

                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            @if ($order->order_type !='parcel')
                            <dt class="col-6">{{__('item_price')}}:</dt>
                            <dd class="col-6">{{\App\CentralLogics\Helpers::format_currency($sub_total)}}</dd>

                            <?php

                                $coupon_data = \App\Models\Coupon::where('id',$order->coupon_id)->first();


                                                        

                                                    

                                $coupon_discount_amount = (isset($coupon_data)) ?  $coupon_data['discount'] : 0;
                                ?>
                            
                            <dt class="col-6">{{__('messages.subtotal')}}:</dt>
                            <dd class="col-6">
                                {{\App\CentralLogics\Helpers::format_currency($sub_total)}}</dd>
                            <dt class="col-6"></dt>
                            <dd class="col-6">
                            <dt class="col-6">Coupon Discount:</dt>
                            <dd class="col-6">
                                - {{($coupon_discount_amount)}}</dd>
                       
                            <dt class="col-6">{{__('messages.delivery_charge')}}:</dt>
                            <dd class="col-6">
                                @php($del_c=$order['delivery_charge'])
                                {{\App\CentralLogics\Helpers::format_currency($del_c)}}
                                <hr>
                            </dd>                                
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
