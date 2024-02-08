
<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
?>
<?php $__env->startSection('title', __('Order Details')); ?>

<?php $__env->startPush('css_or_js'); ?>
    <style>
        .item-box {
            height: 250px;
            width: 150px;
            padding: 3px;
        }

        .header-item {
            width: 10rem;
        }

    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
    $campaign_order = isset($order->details[0]->campaign) ? true : false;
    $parcel_order = $order->order_type == 'parcel' ? true : false;
 
    $cartItems = json_decode($order->cart_items,true);  
    $product_price = 0;
    $total_item_price = 0;
    ?>
  
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="<?php echo e(route('admin.order.list', ['status' => 'all'])); ?>">
                                    <?php echo e(__('messages.orders')); ?>

                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('messages.order')); ?>

                                <?php echo e(__('messages.details')); ?></li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title"><?php echo e(__('messages.order')); ?> #<?php echo e($order['order_id']); ?></h1>

                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span><?php echo e(__('messages.paid')); ?>

                            </span>
                        

                        <?php if($order['status'] == 'ongoing'): ?>
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                                <span class="legend-indicator bg-info text"></span>Ongoing
                            </span>
                        <?php elseif($order['status'] == 'cancelled'): ?>
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                                <span class="legend-indicator bg-danger"></span>Cancelled
                            </span>
               
                        
                        <?php elseif($order['status'] == 'completed'): ?>
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                                <span class="legend-indicator bg-success"></span>Completed
                            </span>
               
                        <?php else: ?>
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                                <span
                                    class="legend-indicator bg-danger"></span><?php echo e(str_replace('_', ' ', $order['status'])); ?>

                            </span>
                        <?php endif; ?>
                       
                     
                        <span class="ml-2 ml-sm-3">
                            <i class="tio-date-range"></i>
                           Booking Date - <?php echo e(date('d M Y ' , strtotime($order['created_at']))); ?>

                        </span>
                        
                     
                    </div>
                    
                    <div class="row">
                        
                            <span class="ml-2 ml-sm-3">
                           Start Date - <?php echo e(date('d M Y ' , strtotime($order['start_date']))); ?>

                        </span>
                        
                         <span class="ml-2 ml-sm-3">
                           End Date - <?php echo e(date('d M Y ' , strtotime($order['end_date']))); ?>

                        </span>
                        
                        <span class="ml-2 ml-sm-3">
                          Time Slot - <?php echo e($order['time_duration']); ?>

                        </span>
                        
                    </div>

                    <div class="mt-2">
                        <a class="text-body mr-3" href=<?php echo e(route('admin.order.generate-invoice', [$order['id']])); ?>>
                            <i class="tio-print mr-1"></i> <?php echo e(__('messages.print')); ?> <?php echo e(__('messages.invoice')); ?>

                        </a>
                     <a class="text-body mr-3" href=<?php echo e(route('admin.order.download-invoice', [$order['id']])); ?>>
                            <i class="tio-download mr-1"></i> Download <?php echo e(__('messages.invoice')); ?>

                        </a> 
                    

                        <?php
                            $refund_amount = $order->order_amount;
                            if ($order->order_status == 'delivered') {
                                $refund_amount = $order->order_amount - $order->delivery_charge;
                            }
                        ?>
                        
                        <?php if($order->order_status != 'refunded'): ?>
                            <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <?php echo e(__('messages.status')); ?>

                                    </button>
                                    <?php ($order_delivery_verification = (bool) \App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value); ?>
                                    <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item <?php echo e($order['status'] == 'ongoing' ? 'active' : ''); ?>"
                                            onclick="route_alert('<?php echo e(route('admin.order.status', ['id' => $order['id'], 'status' => 'ongoing'])); ?>','Change status to ongoing ?')"
                                            href="javascript:">Ongoing</a>
                                    
                                 
                                   
                                   
                                 
                                        <a class="dropdown-item <?php echo e($order['status'] == 'cancelled' ? 'active' : ''); ?>"
                                            onclick="route_alert('<?php echo e(route('admin.order.status', ['id' => $order['id'], 'status' => 'cancelled'])); ?>','Change status to cancelled ?')"
                                            href="javascript:">Cancelled</a>
                                            
                                        <a class="dropdown-item <?php echo e($order['status'] == 'completed' ? 'active' : ''); ?>"
                                            onclick="route_alert('<?php echo e(route('admin.order.status', ['id' => $order['id'], 'status' => 'completed'])); ?>','Change status to completed ?')"
                                            href="javascript:">Completed</a>
                                            
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- End Unfold -->
                    </div>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                        href="<?php echo e(route('admin.order.details', [$order['id'] - 1])); ?>" data-toggle="tooltip"
                        data-placement="top" title="Previous order">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                        href="<?php echo e(route('admin.order.details', [$order['id'] + 1])); ?>" data-toggle="tooltip"
                        data-placement="top" title="Next order">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom  d-flex justify-content-between">
                                <h4 class="card-header-title">
                                   
                                  
                                    <?php echo e(__('messages.order')); ?> <?php echo e(__('messages.details')); ?>

                                    <span
                                        class="badge badge-soft-dark rounded-circle ml-1">  <?php echo e((count($cartItems))); ?> </span>
                                </h4>
                           
                            </div>
                        </div>
                     
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                    

                               <?php
                                  $delivery_charge = $order->delivery_charge;
                                 
                                    
                                ?>
   

                                    <!-- Media -->
                                    <div class="media">

                                      
                                    
                                      
                                        <div class="media-body">
                                            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $amount = $value['item_price'] * $value['quantity'];
                                            $total_item_price = $total_item_price + $value['item_price'];
                                            $itemColorImageId = (isset($value['item_color_image_id']) && !empty($value['item_color_image_id'])) ? $value['item_color_image_id'] :  0 ;
                                            $itemColorImageData = \App\Models\ProductColoredImage::where('id',$itemColorImageId)->first();
                                            $itemColorImage = (isset($itemColorImageData) && !empty($itemColorImageData)) ? $itemColorImageData->image : '';
                                            $itemColorImage = (env('APP_ENV') == 'local') ? asset('storage/product/colored_images/' . $itemColorImage) : asset('storage/app/public/product/colored_images/' . $itemColorImage); 
                                            $itemImage = (isset($itemColorImageId) && !empty($itemColorImageId)) ? $itemColorImage : $value['item_image'];




                                             ?>
                                     
                                                <img class="img-fluid" src="<?php echo e($itemImage); ?>"  alt="Product Image" height="100" width="100">
                                            <div class="row">
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <strong>
                                                        <?php echo e(Str::limit($value['item_name'], 20, '...')); ?></strong><br>
                                                </div>

                                                <div class="col col-md-2 align-self-center">
                                                    <h6>    Rs . <?php echo e($value['item_price'] / $value['quantity']); ?>

                                                    </h6>
                                                </div>
                                                <div class="col col-md-1 align-self-center">
                                                    <h5><?php echo e($value['quantity']); ?></h5>
                                                </div>

                                                <div class="col col-md-3 align-self-center text-right">
                                                  
                                                    <h5> Rs . <?php echo e($value['item_price']); ?></h5>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                      
                                    </div>
                                
                                  
                                    <!-- End Media -->
                                    <hr>
                               

                            <?php

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
                            


                         

                            ?>
                     

                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                       

                                        <dt class="col-sm-6"><?php echo e(__('messages.subtotal')); ?>:</dt>
                                        <dd class="col-sm-6">
                                            
                                      <?php
                                            $start_timestamp = strtotime($order['start_date']);
                                            $end_timestamp = strtotime($order['end_date']);
                                            
                                            // Calculate the difference in seconds
                                            $difference_in_seconds = $end_timestamp - $start_timestamp;
                                            
                                            // Convert seconds to days
                                             $difference_in_days = floor($difference_in_seconds / (60 * 60 * 24));

                                            
                                  ?>
                                            Rs.  <?php echo e($total_item_price*$difference_in_days); ?>

                                        </dd>
                                      
                                        <dt class="col-sm-6"><?php echo e(__('messages.coupon')); ?>

                                            <?php echo e(__('messages.discount')); ?>:</dt>
                                        <dd class="col-sm-6">
                                          <?php
                                          if(isset($coupon_data)){

                                            ?>
                                            - <?php echo e($coupon_data->discount_type == 'amount' ? ' Rs.' : '(%)'); ?>    <?php echo e($coupon_discount_amount); ?>


                                            <?php } else{ ?>

                                            0

                                            <?php } ?>
                                        </dd>
                                     
                                      
                                        <dt class="col-sm-6"><?php echo e(__('messages.delivery')); ?>

                                            <?php echo e(__('messages.fee')); ?>:</dt>
                                        <dd class="col-sm-6">
                                            +  Rs. <?php echo e($delivery_charge); ?>

                                            <hr>
                                        </dd>
                                        <dt class="col-sm-6">Pending Amount:</dt>
                                        <dd class="col-sm-6">
                                              Rs.    <?php echo e($order->pending_amount); ?>

                                        </dd>
                                        


                                    <dt class="col-sm-6"><?php echo e(__('messages.total')); ?>:</dt>
                                    <dd class="col-sm-6">
                                        Rs.  <?php echo e($total_item_price*$difference_in_days + $delivery_charge  - $coupon_discount_amount); ?>

                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                            <?php if($editing): ?>
                                <div class="offset-sm-8 col-sm-4 d-flex justify-content-between">
                                    <button class="btn btn-sm btn-danger" type="button"
                                        onclick="cancle_editing_order()"><?php echo e(__('messages.cancel')); ?></button>
                                    <button class="btn btn-sm btn-primary" type="button"
                                        onclick="update_order()"><?php echo e(__('messages.submit')); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4">
                <?php if($parcel_order || ($order['order_type'] != 'take_away' && $order->store && !$order->store->self_delivery_system)): ?>
                    <!-- Card -->
                    <div class="card mb-2">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title"><?php echo e(__('messages.deliveryman')); ?></h4>
                            <?php if($order->delivery_man && !isset($order->delivered)): ?>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    <?php echo e(__('messages.change')); ?>

                                </button>
                            <?php endif; ?>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->

                        <div class="card-body">
                            <?php if($order->delivery_man): ?>
                                <a class="media align-items-center  deco-none"
                                    href="<?php echo e(route('admin.delivery-man.preview', [$order->delivery_man['id']])); ?>">
                                    <div class="avatar avatar-circle mr-3">

                                        <img class="avatar-img" style="width: 75px"
                                     
                                            src="<?php echo e(asset('storage/app/public/delivery-man/' . $order->delivery_man->image)); ?>"
                                            alt="Image Description">
                                    </div>
                                    <div class="media-body">
                                        <span
                                            class="text-body text-hover-primary"><?php echo e($order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name']); ?></span><br>
                                        <span class="badge badge-ligh"><?php echo e($order->delivery_man->orders_count); ?>

                                            <?php echo e(__('messages.orders_delivered')); ?></span>
                                    </div>
                                </a>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5><?php echo e(__('messages.contact')); ?> <?php echo e(__('messages.info')); ?></h5>
                                </div>

                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>
                                        <i class="tio-online mr-2"></i>
                                        <?php echo e($order->delivery_man['email']); ?>

                                    </li>
                                    <li>
                                        <a class="deco-none" href="tel:<?php echo e($order->delivery_man['phone']); ?>">
                                            <i class="tio-android-phone-vs mr-2"></i>
                                            <?php echo e($order->delivery_man['phone']); ?></a>
                                    </li>
                                </ul>

                                <hr>
                                <?php ($address = $order->dm_last_location); ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5><?php echo e(__('messages.last')); ?> <?php echo e(__('messages.location')); ?></h5>
                                </div>
                                <?php if(isset($address)): ?>
                                    <span class="d-block">
                                        <a target="_blank"
                                            href="http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo e($address['latitude']); ?>+<?php echo e($address['longitude']); ?>">
                                            <i class="tio-map"></i> <?php echo e($address['location']); ?><br>
                                        </a>
                                    </span>
                                <?php else: ?>
                                    <span class="d-block text-lowercase qcont">
                                        <?php echo e(__('messages.location') . ' ' . __('messages.not_found')); ?>

                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="w-100 text-center">
                                    <div class="hs-unfold">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#myModal" data-lat='21.03' data-lng='105.85'>
                                            <?php echo e(__('messages.assign_delivery_mam_manually')); ?>

                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                <?php endif; ?>
                <!-- Customer Card -->
                <div class="card mb-2">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title"><?php echo e(__('messages.customer')); ?></h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <?php if($order->customer): ?>
                        <div class="card-body">

                            <a class="media align-items-center deco-none"
                                href="<?php echo e(route('admin.customer.view', [$order->customer['id']])); ?>">
                               
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"><?php echo e($order->customer['name']); ?></span><br>

                                    <?php

                                    $customer_orders_count = \App\Models\Order::where('user_id',$order->user_id)->count();
                                    ?>



                                    <span class="badge badge-ligh"><?php echo e($customer_orders_count); ?>

                                        <?php echo e(__('messages.orders')); ?></span>
                                </div>

                            </a>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5><?php echo e(__('messages.contact')); ?> <?php echo e(__('messages.info')); ?></h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    <?php echo e($order->customer['email'] ?? 'N/A'); ?>

                                </li>
                                <li>
                                    <a class="deco-none" href="tel:<?php echo e($order->customer['mobile_number']); ?>">
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        <?php echo e($order->customer['mobile_number']); ?>

                                    </a>
                                </li>
                            </ul>

                            <?php
                                $deliveryAddressData =  \App\Models\UsersAddress::where('customer_id',$order->user_id)->first();
                                $houseName = $deliveryAddressData->house_name . ",";
                                // Add floor number with suffix
                                $floorNumber = $deliveryAddressData->floor_number;
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

                                $deliveryAddress = $floorNumber."<sup>".trim($suffix)."</sup>"."&nbsp;&nbsp;&nbsp; floor " . "," . $deliveryAddressData->landmark . "," . $deliveryAddressData->area_name;




                                
                            ?>

                            <?php if($deliveryAddressData): ?>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5><?php echo e(__( 'messages.delivery')); ?> <?php echo e(__('messages.address')); ?></h5>
                                   
                                </div>
                                    <span class="d-block"> <?php echo $deliveryAddress; ?> </span>
                            <?php endif; ?>
                          
                        </div>
                    <?php endif; ?>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
                <?php if($order->store): ?>
                    <!-- Restaurant Card -->
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title"><?php echo e(__('messages.store')); ?></h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <a class="media align-items-center deco-none"
                                href="<?php echo e(route('admin.vendor.view', [$order->store['id']])); ?>">
                                <div class="avatar avatar-circle mr-3">
                                    <img class="avatar-img" style="width: 75px"
                                     
                                        src="<?php echo e(asset('storage/app/public/store/' . $order->store->logo)); ?>"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary text-break"><?php echo e($order->store->name); ?></span><br>
                                    <span class="badge badge-ligh"><?php echo e($order->store->orders_count); ?>

                                        <?php echo e(__('messages.orders_served')); ?></span>
                                </div>
                            </a>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5><?php echo e(__('messages.contact')); ?> <?php echo e(__('messages.info')); ?></h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    <?php echo e($order->store['email']); ?>

                                </li>
                                <li>
                                    <a class="deco-none" href="tel:<?php echo e($order->store['phone']); ?>">
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        <?php echo e($order->store['phone']); ?>

                                    </a>
                                </li>
                            </ul>
                            <hr>
                            <span class="d-block">
                                <a target="_blank"
                                    href="http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo e($order->store['latitude']); ?>+<?php echo e($order->store['longitude']); ?>">
                                    <i class="tio-map"></i> <?php echo e($order->store['address']); ?><br>
                                </a>
                            </span>
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                <?php endif; ?>
            </div>
        </div>
        <!-- End Row -->
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
               

                <form action="<?php echo e(route('admin.order.add-payment-ref-code', [$order['id']])); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="form-group">
                            <input type="text" name="transaction_reference" class="form-control"
                                placeholder="EX : Code123" required>
                        </div>
                        <!-- End Input Group -->
                        <button class="btn btn-primary"><?php echo e(__('messages.submit')); ?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal -->
    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0" style="margin-bottom: -1px;">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                            viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z" />
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                            aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                    d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Header -->

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                        <i class="tio-location-search"></i>
                    </span>
                </div>

                <?php if(isset($address)): ?>
                    <form action="<?php echo e(route('admin.order.update-shipping', [$order['id']])); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.type')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                        value="<?php echo e($address['address_type']); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.contact')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                        value="<?php echo e($address['contact_person_number']); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.name')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                        value="<?php echo e($address['contact_person_name']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('House')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="house"
                                        value="<?php echo e(isset($address['house'])?$address['house']:''); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('Floor')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="floor"
                                        value="<?php echo e(isset($address['floor'])? $address['floor'] : ''); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('Road')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="road"
                                        value="<?php echo e(isset($address['road'])?$address['road']:''); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.address')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address"
                                        value="<?php echo e($address['address']); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.latitude')); ?>

                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude"
                                        value="<?php echo e($address['latitude']); ?>">
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.longitude')); ?>

                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude"
                                        value="<?php echo e($address['longitude']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                data-dismiss="modal"><?php echo e(__('messages.close')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e(__('messages.save')); ?>

                                <?php echo e(__('messages.changes')); ?></button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Dm assign Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><?php echo e(__('messages.assign')); ?>

                        <?php echo e(__('messages.deliveryman')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 my-2">
                            <ul class="list-group overflow-auto" style="max-height:400px;">
                               
                            </ul>
                        </div>
                        <div class="col-md-7 modal_body_map">
                            <div class="location-map" id="dmassign-map">
                                <div style="width: 600px; height: 400px;" id="map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel"><?php echo e(__('messages.location')); ?>

                        <?php echo e(__('messages.data')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div style="width: 100%; height: 400px;" id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('<?php echo url()->full(); ?>');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function set_category_filter(id) {
            var nurl = new URL('<?php echo url()->full(); ?>');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        function addon_quantity_input_toggle(e) {
            var cb = $(e.target);
            if (cb.is(":checked")) {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'visible'
                });
            } else {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'hidden'
                });
            }
        }

        function quick_view_cart_item(key) {
            $.get({
                url: '<?php echo e(route('admin.order.quick-view-cart-item')); ?>',
                dataType: 'json',
                data: {
                    key: key,
                    order_id: '<?php echo e($order->id); ?>',
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function quickView(product_id) {
            $.get({
                url: '<?php echo e(route('admin.order.quick-view')); ?>',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    order_id: '<?php echo e($order->id); ?>',
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...")
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, the minimum value was reached'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, stock limit exceeded.'
                    });
                    $(this).val($(this).data('oldValue'));
                }
            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }


     
  

        function edit_order() {
            Swal.fire({
                title: '<?php echo e(__('messages.are_you_sure')); ?>',
                text: '<?php echo e(__('messages.you_want_to_edit_this_order')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(__('messages.no')); ?>',
                confirmButtonText: '<?php echo e(__('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = '<?php echo e(route('admin.order.edit', $order->id)); ?>';
                }
            })
        }

        function cancle_editing_order() {
            Swal.fire({
                title: '<?php echo e(__('messages.are_you_sure')); ?>',
                text: '<?php echo e(__('messages.you_want_to_cancel_editing')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(__('messages.no')); ?>',
                confirmButtonText: '<?php echo e(__('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = '<?php echo e(route('admin.order.edit', $order->id)); ?>?cancle=true';
                }
            })
        }

        function update_order() {
            Swal.fire({
                title: '<?php echo e(__('messages.are_you_sure')); ?>',
                text: '<?php echo e(__('messages.you_want_to_submit_all_changes_for_this_order')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(__('messages.no')); ?>',
                confirmButtonText: '<?php echo e(__('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = '<?php echo e(route('admin.order.update', $order->id)); ?>';
                }
            })
        }
    

     
        $(document).ready(function() {

            // Re-init map before show modal
            $('#myModal').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                $("#dmassign-map").css("width", "100%");
                $("#map_canvas").css("width", "100%");
            });


         
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/order/order-view.blade.php ENDPATH**/ ?>