


        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet">
        <style type="text/css">
            body {
                width: 100% !important;
                font-size: 12px;
                font-family: "DejaVu Sans,Helvetica Neue", Helvetica, Arial, sans-serif!important;
            }

            * {
                font-family: "DejaVu Sans,Helvetica Neue", Helvetica, Arial, sans-serif!important;
            }

            .container {
                width: 700px;
                
            }

            .outer_border {
                border: 1px solid #999999!important;
                padding: 4%!important;
                margin-bottom: 2%!important;
                height:925px;
            }

            .top_box {
                width: 47%;
                padding: 0%;
            }

            .table_pad {
                padding: 0% 2%;
            }

            .border {
                border: 1px solid #CCCCCC!important;
            }

            .small_text {
                font-size: 10px!important;
            }

            .bg_color1 {
                background: #3a5082;
                color: #fff;
            }

            .text_color1 {
                color: #3a5082;
            }

            td {
                padding: 4px;
            }

            .pull-right {
                float: right;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="outer_border">
                <div class="row">
                    <div class="pull-left top_box p-4">
                        <img src="{{ $logoPath }}" height="100">
                        <br>
                        <p>
                            <?php
                            $phoneData = \App\Models\BusinessSetting::where(['key' => 'phone'])->first();
                            $emailData = \App\Models\BusinessSetting::where(['key' => 'email_address'])->first();
                            ?>
                            Phone: {{ ($phoneData) ? $phoneData->value : 'N/A' }} <br>
                            Email: {{ ($emailData) ? $emailData->value : 'N/A' }} <br>
                        </p>
                    </div>
                    <div class="pull-right top_box p-4" style="width:47%; padding:0%;top:-115px;position:absolute">
                        <h2 style="color:#687cbf;font-weight: bold;font-size:30px; text-align:right; padding-right: 30px;margin-top:100px;" id="invoice">INVOICE</h2>
                        <table width="100%" height="70" border="0" class="table_pad" style="margin-top:20px;">
                            <tr>
                                <td> Start Date</td>
                                <td>{{ date('d M Y',strtotime($order['start_date'])) }}</td>
                            </tr>
                            <tr>
                                <td> End Date</td>
                                <?php
                                            if(!empty($order['end_date'])){
                                        ?>
                                        <td>{{date('d M Y',strtotime($order['end_date']))}}</td>
                                        <?php } else{ ?>
                                            <td>N/A</td>
                                        <?php } ?>
                            </tr>
                            <tr>
                                <td> Time Slot</td>
                                <td>{{ $order['time_duration'] }}</td>
                            </tr>
                            <tr>
                                <td>Booking Date</td>
                                <td>{{ date('d M Y ',strtotime($order['created_at'])) }}</td>
                            </tr>
                            <tr>
                                <td width="50%">Invoice </td>
                                <td width="50%">#{{ $order['order_id'] }}</td>
                            </tr>

                            @if($order['damage_amount'] > 0)
                                <tr>
                                    <td width="50%">Damage Amount</td>
                                    <td width="50%">Rs. {{ $order['damage_amount'] }}</td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td width="50%">GST Number </td>
                                <td width="50%">{{ $order['gst_number'] ?? 'N/A'  }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top:130px;right:15px;position:relative">
            
                            <table width="100%" border="0">
                            <tr>
                                <td colspan="2">
                                    <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">Bill To - </div>
                                    <table width="100%" border="0">
                                        <tr>
                                            <td>Name : {{ $order->customer['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone : {{ $order->customer['mobile_number'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email : {{$order->customer['email'] ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"> </td>
                            </tr>
                        </table>
                
                        <table width="100%" border="0">
                            <tr>
                                <td colspan="2">
                                    <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">Delivery Address -</div>
                                    <table width="100%" border="0">
                                        <tr>
                                            @php
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

                                                    $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". " floor " . "," . $addressData->landmark . "," . $addressData->area_name . "," . $addressData->zip_code;
                                                } else {
                                                    $deliveryAddress = '';
                                                }
                                            @endphp
                                            <td width="82%">  {!! $deliveryAddress !!}</td>

                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"> </td>
                            </tr>
                        </table>
                    
                </div>
                <div class="row" style="right:15px;position:relative">
                    <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">Booking Details - </div>
                    <table height="82"  style="width:100%;margin-top:10px;" border="1" cellpadding="0" cellspacing="0">
                        <tr class="bg_color1">
                            <td width="18%">S. No.</td>
                            <td width="58%" height="12" style="padding-left: 10px;">Item Name</td>
                            <td width="13%">Quantity </td>
                            <td width="58%" height="12" style="padding-left: 10px;">Price</td>
                            <td width="13%">Days </td>
                            <td width="13%">Subtotal</td>
                        </tr>
                        @php
                            $count = 0;
                            $total_addon_price = 0;
                            $product_price = 0;
                            $details = json_decode($order->cart_items,true);
                            $deliveryCharge = $order['delivery_charge'];
                            $storeDiscountAmount = 0;
                            $total_tax_amount = $order['total_tax_amount'];
                            $coupon_discount_amount = $order['coupon_discount_amount'];
                            $totalTaxAmount  = 0;
                            $productSubTotal = 0;
                            $test = 0;
                            $total_gst = 0;
                        @endphp

                        <?php
                        if(count($details) > 0){
                            $total_item_price = 0;
                            foreach ($details as $key => $detail){
                                
                                
                                    $gstData = \App\Models\BusinessSetting::where('key','gst_percent')->first();
                                                        $gst = $gstData->value;
                                                        $amount = $detail['item_price'] * $detail['quantity'];
                                                    $total_item_price = $total_item_price + $detail['item_price'];
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
                                                        
                                                        
                                $coupon_data = \App\Models\Coupon::where('id',$order->coupon_id)->first();
                                $coupon_discount_amount = (isset($coupon_data)) ?  $coupon_data['discount'] : 0;

                                if(isset($coupon_data)){

                                    if ($coupon_data->discount_type == 'amount') {
                                        $discountedPrice = number_format($product_price - $coupon_data->discount, 2);
                                    } else {
                                        $discountedPrice = number_format(($coupon_data['discount'] / 100) * $product_price, 2);
                                        $discountedPrice = number_format(($product_price - $discountedPrice),2);
                                    }
                                }

                                $amount = $detail['item_price'] * $detail['quantity'];
                                $product_price = $amount;
                                    
                                $total_gst = $total_gst +  $gst_amount*$difference_in_days;
                                $count++;
                            //  $total_item_price = $total_item_price + $detail['item_price'];

                            $itemColorImageId = (isset($detail['item_color_image_id']) && !empty($detail['item_color_image_id'])) ? $detail['item_color_image_id'] :  0 ;
                            $itemColorImageData = \App\Models\ProductColoredImage::where('id',$itemColorImageId)->first();
                            $itemData = \App\Models\Product::where('id',$detail['item_id'])->first();

                            $colorName = ($itemColorImageId == 0) ? ($itemData->color_name ?? 'N/A') : ($itemColorImageData->color_name ?? 'N/A') ;
                                ?>
                                <tr>
                                    <td>{{$count }}</td>
                                    <td> {{$detail['item_name'] ?? ''}} ({{ $colorName }})   </td>
                                    <td>  {{ $detail['quantity'] ?? ''}}   </td>
                                    <td> Rs. {{$detail['item_price']/$detail['quantity'] }}  </td>
                                    <td> {{ ($difference_in_days > 0) ? $difference_in_days  : 'N/A'  }} </td>
                                    <td> 
                                    
                                    <?php
                                                            if($detail['category_id'] == '1'){
                                                                $test =  $test + $detail['item_price']*$difference_in_days;
                                                                ?>
                                                            
                                                                Rs . {{ $detail['item_price']*$difference_in_days }} 
                                                                
                                                                <?php 
                                                                
                                                            } else {
                                                                $test =  $test + $detail['item_price'];
                                                                ?>
                                                                    Rs  . {{ $detail['item_price'] }}
                                                                <?php }
                                                                ?>
                                    </td>
                                
                                </tr>
                                <?php }} ?>
                    </table>
                    <table align="right" height="92" border="1" cellpadding="0" cellspacing="0" style="width:50%;margin-top:30px;left:10px;position:relative;">
                        <tr>
                            <?php
                            $start_timestamp = strtotime($order['start_date']);
                            $end_timestamp = strtotime($order['end_date']);
                            $difference_in_seconds = $end_timestamp - $start_timestamp;
                            $difference_in_days = floor($difference_in_seconds / (60 * 60 * 24));
                            ?>
                            <td><strong>Sub Total:</strong></td>
                            <td align="right">Rs. {{ $test }}</td>
                        </tr>
                        <tr>
                            <td><strong>Referral {{ __('messages.discount') }}:</strong></td>
                            <td align="right">- Rs. {{ $order['referral_discount'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Coupon Discount:</strong></td>
                            <td align="right">- Rs. {{ $order['coupon_discount'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.gst') }}:</strong></td>
                            <td align="right">+ Rs. {{ $order['gst_amount'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Delivery Fee:</strong></td>
                            <td align="right">+ Rs. {{ $deliveryCharge }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pending Amount:</strong></td>
                            <td align="right">Rs. {{ $order['pending_amount'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Grand Total:</strong></td>
                            <td align="right">
                                @php
                                    $grandTotal = $order['paid_amount'];
                                @endphp
                                Rs. {{ $grandTotal }}
                            </td>
                        </tr>
                    </table>
                    
                </div>

            
            </div>
            <div class="row" style="float:right">System Generated</div>
        </div>
    </body>

