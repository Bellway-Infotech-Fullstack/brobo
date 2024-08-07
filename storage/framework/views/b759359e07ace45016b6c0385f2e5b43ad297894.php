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
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-danger non-printable"><?php echo e(__('messages.back')); ?></a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5">
             
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>Booking ID : <?php echo e($order['order_id']); ?></h5>
                    </div>



                    <div class="col-6">
                        <h5>
                            Booking Date :  <?php echo e(date('d M Y ',strtotime($order['created_at']))); ?>

                        </h5>
                    </div>
                    

                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Booking From <?php echo e(date('d M Y',strtotime($order['start_date']))); ?> To  <?php echo e(date('d M Y',strtotime($order['end_date']))); ?></h5>
                    </div>
                 </div>

                 <div class="row mt-3">
                    <div class="col-12">
                        <h5>Time Slot :  <?php echo e($order['time_duration']); ?></h5>
                    </div>
                 </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <?php
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

                            $deliveryAddress .= $floorNumber . "<sup>". $suffix .  "</sup>". " floor " . "," . $addressData->landmark . "," . $addressData->area_name;
                            } else {
                                $deliveryAddress = '';
                            }
    
                        ?>
                            <h5>Delivery Address : <?php echo $deliveryAddress; ?></h5>

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
                        <th class=""><?php echo e(__('messages.price')); ?></th>
                        <th class="">Sub Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if($order->order_type == 'parcel'): ?>
                        <tr>
                            <td>1</td>
                            <td><?php echo e(__('messages.delivery_charge')); ?></td>
                            <td><?php echo e(\App\CentralLogics\Helpers::format_currency($order->delivery_charge)); ?></td>
                        </tr>
                    <?php else: ?>
                        <?php ($sub_total=0); ?>
                        <?php ($total_tax=0); ?>
                        <?php ($total_dis_on_pro=0); ?>
                        <?php ($add_ons_cost=0); ?>
                        <?php ($count=0); ?>
                        <?php $__currentLoopData = json_decode($order->cart_items,true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($count++); ?>
                                <tr>
                                    <td class="text-break">
                                        <?php echo e($count); ?> <br>  </td>

                                    <td class="text-break">
                                        <?php echo e($detail['item_name']); ?> <br>  </td>
                                        <td class="">
                                            <?php echo e($detail['quantity']); ?>

                                        </td>
                                    <td style="width: 28%">
                                        <?php ($amount=($detail['item_price'])); ?>
                                        Rs . <?php echo e($detail['item_price']/ $detail['quantity']); ?>

                                    </td>
                                    <td style="width: 28%">
                                        Rs . <?php echo e($detail['item_price']); ?>

                                    </td>
                                    
                                   
                                </tr>
                                <?php ($sub_total=$sub_total+$amount); ?>
                            
                           
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                        
                    <?php endif; ?>

                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            <?php if($order->order_type !='parcel'): ?>
                          
                            <?php

                                $coupon_data = \App\Models\Coupon::where('id',$order->coupon_id)->first();
                                $coupon_discount_amount = (isset($coupon_data)) ?  $coupon_data['discount'] : 0;
                                ?>
                            
                            <dt class="col-6"><?php echo e(__('messages.subtotal')); ?>:</dt>
                            <dd class="col-6">
                                       
                                      <?php
                                            $start_timestamp = strtotime($order['start_date']);
                                            $end_timestamp = strtotime($order['end_date']);
                                            
                                            // Calculate the difference in seconds
                                            $difference_in_seconds = $end_timestamp - $start_timestamp;
                                            
                                            // Convert seconds to days
                                             $difference_in_days = floor($difference_in_seconds / (60 * 60 * 24));

                                            
                                  ?>
                                Rs.  <?php echo e($sub_total*$difference_in_days); ?></dd>
                            <dt class="col-6"></dt>
                            <dd class="col-6">
                          
                       
                            <dt class="col-6"><?php echo e(__('messages.delivery_charge')); ?>:</dt>
                            <dd class="col-6">
                                <?php ($del_c=$order['delivery_charge']); ?>
                               Rs. <?php echo e($del_c); ?>

                                <hr>
                            </dd>      
                            <dt class="col-6">Coupon Discount:</dt>
                            <dd class="col-6">
                                - <?php echo e(($coupon_discount_amount)); ?></dd>                          
                            <?php endif; ?>


                            <dt class="col-6" style="font-size: 20px"><?php echo e(__('messages.total')); ?>:</dt>
                            <dd class="col-6" style="font-size: 20px">
                               <?php  
                               
                               
                                $grandTotal = ($sub_total*$difference_in_days + $del_c)  - $coupon_discount_amount;

                                ?>
                               Rs.  <?php echo e($grandTotal); ?>

                            
                            </dd>
                        </dl>
                    </div>
                </div>
                <span>---------------------------------------------------------------------------------</span>
                <h5 class="text-center pt-3">
                    """<?php echo e(__('THANK YOU')); ?>"""
                </h5>
                <span>---------------------------------------------------------------------------------</span>
            </div>
        </div>
    </div>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/order/partials/_invoice.blade.php ENDPATH**/ ?>