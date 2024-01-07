
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="{{asset('public/css/custom.css')}}" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!------ Include the above in your HEAD tag ----------> 
<style type="text/css"> 
   body{
   width:100%!important;
   font-size:12px;
   font-family: "DejaVu Sans,Helvetica Neue",Helvetica,Arial,sans-serif!important;
   }
   *{
   font-family: "DejaVu Sans,Helvetica Neue",Helvetica,Arial,sans-serif!important;
   }
   .container{
   width: 700px;
   }
   .outer_border{
   border:1px solid #999999!important;
   padding:4%!important;

   
   margin-bottom:2%!important;
   }
   .top_box{
   width:47%; padding:0%
   }
   .table_pad{
   padding:0% 2%;
   } 
   .border{
   border:1px solid #CCCCCC!important;
   }
   .small_text{
   font-size:10px!important;
   }
   .bg_color1{
   background:#3a5082;
   color: #fff;
   }
   .text_color1{outer_border
   color:#3a5082;
   }
   td{
   padding:4px;
   } 
   .pull-right{
    float:right
   }
</style>
<?php ?>
<div class="container">
   <div class="outer_border">
      <div class="row">
         <div  class=" pull-left top_box p-4">


            
            <img src="{{ $logoPath }}" height="100">		   

           <br>
           <p>

            <?php
             $phoneData = \App\Models\BusinessSetting::where(['key' => 'phone'])->first();
             $emailData = \App\Models\BusinessSetting::where(['key' => 'email_address'])->first();


            ?>
                Phone : {{ ($phoneData) ? $phoneData->value : 'N/A' }} <br>
                Email : {{ ($emailData) ? $emailData->value : 'N/A' }} <br>
          
           	
         </p>
         
    
         </div>
         <div style="" class="pull-right top_box p-4" style="width:47%; padding:0%;margin-top:-150px;">
            <h2 style="color:#687cbf;font-weight: bold;font-size:30px; text-align:right; padding-right: 30px;" style="color:#687cbf;font-weight: bold;font-size:30px; text-align:right; padding-right: 30px;" id="invoice">INVOICE</h2>
            <table width="100%" height="70" border="0" class="table_pad" style="margin-top:20px;">
               <tr>
                  <td> Date</td>
                  <td>
                  {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}


                  </td>
               </tr>
               <tr>
                  <td width="50%">Invoice </td>
                  <td width="50%">#{{ $order['order_id'] }}</td> 
               </tr>
               <tr>
                  <td>Customer ID</td>
                  <td>{{ $order->customer['id'] }} </td>
               </tr>
            </table>
         </div>
      </div>
      <div class="row" style="right:15px;position:relative">
         <div class="">
            <table width="100%" border="0">
               <tr>
                  <td colspan="2">
                     <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">BILL TO </div>
                     <table width="100%" border="0">
                         <?php
                           $address = json_decode($order->delivery_address, true);     
                         ?>
                        <tr>
                        
                           <td width="18%">Name</td>
                           <td width="82%"> {{ $order->customer['name'] }}</td>
                        </tr>
                        <tr>
                           <td>Phone</td>
                           <td>{{$order->customer['mobile_number']}}</td>
                        </tr>
                        <tr>
                           <td>Email</td>
                           <td>{{$order->customer['email'] ?? 'N/A'}}</td>
                        </tr>
                      
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="2"> </td>
               </tr>
            </table>
         </div>
      </div>
      <dd style="clear:both;"></dd>
      <div class="row" style="right:15px;position:relative">
      <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">Order Details</div>
         <table height="82"  style="width:100%;margin-top:10px;" border="1" cellpadding="0" cellspacing="0">
            <tr class="bg_color1">
               <td width="18%">S. No.</td>
               <td width="58%" height="12" style="padding-left: 10px;">Item Name</td>
               <td width="13%">Quantity </td>
               <td width="58%" height="12" style="padding-left: 10px;">Price</td>
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
               @endphp

               <?php
            if(count($details) > 0){
               $total_item_price = 0;
               foreach ($details as $key => $detail){
               
            
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
                   $count++;
		          
                  

                  $total_item_price = $total_item_price + $detail['item_price'];
                  
     

                   ?>
                   <tr>
                   <td>{{$count }}</td>
                  <td> {{$detail['item_name'] ?? ''}}  </td> 
                  <td>  {{ $detail['quantity'] ?? ''}}   </td>
                  <td> Rs. {{$detail['item_price'] / $detail['quantity'] ?? ''}}   </td>
                  <td> Rs. {{$detail['item_price'] }}  </td>                  
                  
               </tr>
               <?php
                   
               }}

               ?>
           
         </table>
         <table align="right"  border="1" cellpadding="0" cellspacing="0" style="top:10px;position:relative">
         <tr>
           
           
               

               <tr>
                  <td> <strong>  Sub Total : </strong>  </td>
                  <td align="right">  Rs.  {{ $total_item_price }}</td>
                  </tr>  

               <tr>
               <td> <strong> Delivery Fee : </strong>  </td>
               <td align="right">  Rs. {{ $deliveryCharge }}</td>
               </tr>  
              
          
               <tr>
               <td> <strong>Coupon Discount :</strong> </td>
               <td align="right">
                  {{ $coupon_discount_amount }}
               </td>
               </tr> 
               <td> <strong>Grand Total :</strong> </td>
               <td align="right">
               
               @php   
               $grandTotal = ($total_item_price + $deliveryCharge)  - $coupon_discount_amount 
               @endphp
              Rs.  {{ $grandTotal }}
               </td>
            </tr>   
            </table>
      </div>
   </div>
</div>