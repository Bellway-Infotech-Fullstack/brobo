                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr class="status-<?php echo e($order['order_status']); ?> class-all">
                            <td class="">
                                <?php echo e($key + 1); ?>

                            </td>
                            <td class="table-column-pl-0">
                             <a href="<?php echo e(route('admin.order.details',['id'=>$order['id']])); ?>"><?php echo e($order['order_id']); ?></a>

                      
                            </td>
                            <td><?php echo e(date('d M Y',strtotime($order['created_at']))); ?></td>
                            <td>
                                <?php if($order->customer): ?>
                                 <a class="text-body text-capitalize"
                                       href="<?php echo e(route('admin.customer.view',[$order['user_id']])); ?>"><?php echo e($order->customer['name']); ?></a> 

                                  
                                <?php else: ?>
                                    <label class="badge badge-danger"><?php echo e(__('messages.invalid')); ?> <?php echo e(__('messages.customer')); ?> <?php echo e(__('messages.data')); ?></label>
                                <?php endif; ?>
                            </td>
                        
                            <td><?php echo e(date('d M Y',strtotime($order['start_date']))); ?></td>
                            <td><?php echo e(date('d M Y',strtotime($order['end_date']))); ?></td>
                            <td><?php echo e($order['time_duration']); ?></td>
                            <td>Rs. <?php echo e(number_format($order->paid_amount)); ?> </td>
                            <td>
                              
                                <span class="badge badge-soft-success">
                                  <span class="legend-indicator bg-success"></span><?php echo e(__('messages.paid')); ?>

                                </span>
                            
                        </td>
                            <td class="text-capitalize">
                                <?php if($order['status']=='ongoing'): ?>
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-info"></span>Ongoing
                                    </span>
                                <?php elseif($order['status']=='cancelled'): ?>
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger"></span>Cancelled
                                    </span>
                      
                                <?php elseif($order['status']=='completed'): ?>
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-success"></span><?php echo e(__('messages.delivered')); ?>

                                    </span>
                                <?php elseif($order['status']=='failed'): ?>
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger text-capitalize"></span><?php echo e(__('messages.payment')); ?>  <?php echo e(__('messages.failed')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger"></span><?php echo e(str_replace('_',' ',$order['status'])); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                           
                            <td>
                                <a class="btn btn-sm btn-white"
                                           href="<?php echo e(route('admin.order.details',['id'=>$order['id']])); ?>"><i
                                                class="tio-visible"></i> <?php echo e(__('messages.view')); ?></a>

                              <?php if($order['status']=='cancelled' && $order['refunded'] == NULL): ?>
                              <a class="btn btn-sm btn-white ml-2 refund-money"
                              data-order-id="<?php echo e($order['id']); ?>"><i
                                   class="tio-visible"></i> Refund
                               </a>

                              <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/order/partials/_table.blade.php ENDPATH**/ ?>