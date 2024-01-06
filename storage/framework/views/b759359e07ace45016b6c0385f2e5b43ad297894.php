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
                <?php if($order->store): ?>
                <div class="text-center pt-4 mb-3">
                  
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        <?php echo e(__('messages.phone')); ?> : <?php echo e($order->store->phone); ?>

                    </h5>
               
		        </h5>
           
		     
                </div>                    
                

                <span>---------------------------------------------------------------------------------</span>
                <?php endif; ?>
                <div class="row mt-3">
                    <div class="col-6">
                        <h5><?php echo e(__('order_id')); ?> : <?php echo e($order['id']); ?></h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            <?php echo e(date('d/M/Y '.config('timeformat'),strtotime($order['created_at']))); ?>

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
                        <th class=""><?php echo e(__('messages.price')); ?></th>
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
                        <?php $__currentLoopData = json_decode($order->cart_items,true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           
                                <tr>
                                    <td class="">
                                        <?php echo e($detail['quantity']); ?>

                                    </td>
                                    <td class="text-break">
                                        <?php echo e($detail['item_name']); ?> <br>
                                      
                                        
                                        

                                    </td>
                                    <td style="width: 28%">
                                        <?php ($amount=($detail['item_price'])); ?>
                                        <?php echo e(\App\CentralLogics\Helpers::format_currency($amount)); ?>

                                    </td>
                                   
                                    
                                   
                                </tr>
                                <?php ($sub_total=$amount); ?>
                            
                           
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                        
                    <?php endif; ?>

                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            <?php if($order->order_type !='parcel'): ?>
                            <dt class="col-6"><?php echo e(__('item_price')); ?>:</dt>
                            <dd class="col-6"><?php echo e(\App\CentralLogics\Helpers::format_currency($sub_total)); ?></dd>

                            <?php

                                $coupon_data = \App\Models\Coupon::where('id',$order->coupon_id)->first();


                                                        

                                                    

                                $coupon_discount_amount = (isset($coupon_data)) ?  $coupon_data['discount'] : 0;
                                ?>
                            
                            <dt class="col-6"><?php echo e(__('messages.subtotal')); ?>:</dt>
                            <dd class="col-6">
                                <?php echo e(\App\CentralLogics\Helpers::format_currency($sub_total)); ?></dd>
                            <dt class="col-6"></dt>
                            <dd class="col-6">
                            <dt class="col-6">Coupon Discount:</dt>
                            <dd class="col-6">
                                - <?php echo e(($coupon_discount_amount)); ?></dd>
                       
                            <dt class="col-6"><?php echo e(__('messages.delivery_charge')); ?>:</dt>
                            <dd class="col-6">
                                <?php ($del_c=$order['delivery_charge']); ?>
                                <?php echo e(\App\CentralLogics\Helpers::format_currency($del_c)); ?>

                                <hr>
                            </dd>                                
                            <?php endif; ?>


                            <dt class="col-6" style="font-size: 20px"><?php echo e(__('messages.total')); ?>:</dt>
                            <dd class="col-6" style="font-size: 20px">
                               <?php  
                                $grandTotal = ($sub_total + $del_c)  - $coupon_discount_amount;

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