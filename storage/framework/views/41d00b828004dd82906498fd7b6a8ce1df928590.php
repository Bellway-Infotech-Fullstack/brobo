<?php $__env->startSection('title','Order Details'); ?>
<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';

  ?>
<?php $__env->startPush('css_or_js'); ?>
<style>
    .item-box{
        height:250px;
        width:150px;
        padding:3px;
    }

    .header-item{
        width:10rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $campaign_order=isset($order->details[0]->campaign)?true:false;?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="<?php echo e(route('admin.order.list',['status'=>'all'])); ?>">
                                    Bookings 
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.details')); ?></li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title"><?php echo e(__('messages.order')); ?> #<?php echo e($order['id']); ?></h1>

                        <?php if($order['payment_status']=='paid'): ?>
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span><?php echo e(__('messages.paid')); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge badge-soft-danger ml-sm-3">
                                <span class="legend-indicator bg-danger"></span><?php echo e(__('messages.unpaid')); ?>

                            </span>
                        <?php endif; ?>

                        <?php if($order['order_status']=='pending'): ?>
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info text"></span><?php echo e(__('messages.pending')); ?>

                            </span>
                        <?php elseif($order['order_status']=='confirmed'): ?>
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info"></span><?php echo e(__('messages.confirmed')); ?>

                            </span>
                        <?php elseif($order['order_status']=='processing'): ?>
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span><?php echo e(__('messages.processing')); ?>

                            </span>
                        <?php elseif($order['order_status']=='picked_up'): ?>
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span><?php echo e(__('messages.out_for_delivery')); ?>

                            </span>
                        <?php elseif($order['order_status']=='delivered'): ?>
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span><?php echo e(__('messages.delivered')); ?>

                            </span>
                        <?php elseif($order['order_status']=='failed'): ?>
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                                <span class="legend-indicator text-capitalize bg-danger"></span><?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.failed')); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span><?php echo e(str_replace('_',' ',$order['order_status'])); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($campaign_order): ?>
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span><?php echo e(__('messages.campaign_order')); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($order->edited): ?>
                            <span class="badge badge-soft-dark ml-sm-3">
                                <span class="legend-indicator bg-dark"></span><?php echo e(__('messages.edited')); ?>

                            </span>
                        <?php endif; ?>
                        <span class="ml-2 ml-sm-3">
                                <i class="tio-date-range"></i> <?php echo e(date('d M Y '.config('timeformat'),strtotime($order['created_at']))); ?>

                        </span>
                    </div>

                    <div class="mt-2">
                        <a class="text-body mr-3"
                           href=<?php echo e(route('admin.order.generate-invoice',[$order['id']])); ?>>
                            <i class="tio-print mr-1"></i> <?php echo e(__('messages.print')); ?> <?php echo e(__('messages.invoice')); ?>

                        </a>

                        <!-- Unfold -->
                    <div class="hs-unfold ml-1">
                            <h5>
                                <i class="tio-shop"></i>
                                <?php echo e(__('messages.vendor')); ?> : <label
                                    class="badge badge-secondary"><?php echo e($order->vendor?$order->vendor->names():'Vendor deleted!'); ?></label>
                            </h5>
                        </div>
                        <?php
                            $refund_amount = $order->order_amount;
                            if($order->order_status == 'delivered')
                            {
                                $refund_amount = $order->order_amount - $order->delivery_charge;
                            }
                        ?>
                        <div class="hs-unfold ml-1">
                            <h5>
                                
                                <?php if($order->payment_method != 'cash_on_delivery' && $order->payment_status == 'paid' && $order->order_status != 'refunded'): ?>
                                <button class="btn btn-xs btn-danger"  onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'refunded'])); ?>','<?php echo e(__('messages.you_want_to_refund_this_order', ['amount'=> $refund_amount.' '.\App\CentralLogics\Helpers::currency_code()])); ?>', '<?php echo e(__('messages.are_you_sure_want_to_refund')); ?>')" ><i class="tio-money"></i> <?php echo e(__('messages.refund_this_order')); ?></button>
                                <?php endif; ?>
                            </h5>
                        </div>


                        <section class="float-right row">

                            <?php if($order['order_status']=='processing'): ?>
                                <div>
                                    <input type="text" id='otp' class="form-control form-control-sm" placeholder="Customer OTP" />
                                </div>
                            <?php endif; ?>
                        <div class="hs-unfold float-right">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    <?php echo e(__('messages.status')); ?>

                                </button>
                                <?php ($order_delivery_verification = (boolean)\App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value); ?>
                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item <?php echo e($order['order_status']=='pending'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'pending'])); ?>','Change status to pending ?')"
                                       href="javascript:"><?php echo e(__('messages.pending')); ?></a>
                                    <a class="dropdown-item <?php echo e($order['order_status']=='accepted'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'accepted'])); ?>','Change status to accepted ?')"
                                       href="javascript:"><?php echo e(__('messages.accepted')); ?></a>
                                    <a class="dropdown-item <?php echo e($order['order_status']=='processing'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'processing'])); ?>','Change status to processing ?')"
                                       href="javascript:"><?php echo e(__('messages.processing')); ?></a>
                                   
                                    <a class="dropdown-item <?php echo e($order['order_status']=='services_ongoing'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'services_ongoing'])); ?>&otp=' + document.getElementById('otp').value,'Change status to Service ongoing?')"
                                       href="javascript:"><?php echo e(__('messages.serviceOngoing')); ?></a>
                                    <a class="dropdown-item <?php echo e($order['order_status']=='delivered'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'delivered'])); ?>','Change status to completed (payment status will be paid if not)?')"
                                       href="javascript:"><?php echo e(__('messages.delivered')); ?></a>
                                    <a class="dropdown-item <?php echo e($order['order_status']=='canceled'?'active':''); ?>"
                                       onclick="route_alert('<?php echo e(route('admin.order.status',['id'=>$order['id'],'order_status'=>'canceled'])); ?>','Change status to canceled ?')"
                                       href="javascript:"><?php echo e(__('messages.canceled')); ?></a>
                                </div>
                            </div>
                        </div>
                        </section>

                        <!-- End Unfold -->
                    </div>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                       href="<?php echo e(route('admin.order.details',[$order['id']-1])); ?>"
                       data-toggle="tooltip" data-placement="top" title="Previous order">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                       href="<?php echo e(route('admin.order.details',[$order['id']+1])); ?>" data-toggle="tooltip"
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
                                        class="badge badge-soft-dark rounded-circle ml-1"><?php echo e($order->count()); ?></span>
                                </h4>
                                <?php if(!$editing && in_array($order->order_status, ['pending','confirmed','processing','accepted'])): ?>
                                <button class="btn btn-sm btn-primary" type="button" onclick="edit_order()">
                                    <i class="tio-edit"></i> <?php echo e(__('messages.edit')); ?>

                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 pt-2">
                                <h6 style="color: #8a8a8a;">
                                    <?php echo e(__('messages.order')); ?> <?php echo e(__('messages.note')); ?> : <?php echo e($order['order_note']); ?>

                                </h6>
                            </div>
                            <div class="col-6 pt-2">
                                <div class="text-right">
                                    <h6 class="text-capitalize" style="color: #8a8a8a;">
                                        <?php echo e(__('messages.payment')); ?> <?php echo e(__('messages.method')); ?> : <?php echo e(str_replace('_',' ',$order['payment_method'])); ?>

                                    </h6>
                                   
                                    <?php if($order->schedule_at && $order->scheduled): ?>
                                    <h6 class="text-capitalize" style="color: #8a8a8a;"><?php echo e(__('messages.scheduled_at')); ?>

                                        : <label style="font-size: 10px"
                                                 class="badge badge-soft-primary"><?php echo e(date('d M Y '.config('timeformat'),strtotime($order['schedule_at']))); ?></label>
                                    </h6>
                                    <?php endif; ?>
                                    <?php if($order->coupon): ?>
                                    <h6 class="text-capitalize" style="color: #8a8a8a;"><?php echo e(__('messages.coupon')); ?>

                                        : <label style="font-size: 10px"
                                                 class="badge badge-soft-primary"><?php echo e($order->coupon_code); ?> (<?php echo e(__('messages.'.$order->coupon->coupon_type)); ?>)</label>
                                    </h6>
                                    <?php endif; ?>

                                    <?php if($order->due_amount): ?>
                                    <h6 class="text-capitalize" style="color: #8a8a8a;">Due Amount
                                        : <label style="font-size: 10px"
                                                 class="badge badge-soft-primary"><?php echo e($order->payment_type); ?> - <?php echo e(\App\CentralLogics\Helpers::format_currency($order->due_amount)); ?></label>
                                    </h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- food cart -->
                        <?php if($editing && !$campaign_order): ?>
                        <div class="row border-top pt-1">
                            <div class="col-12 d-flex flex-wrap justify-content-between ">
                                <form id="search-form" class="header-item">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch" type="search" value="<?php echo e($keyword?$keyword:''); ?>" name="search" class="form-control" placeholder="Search here" aria-label="Search here">
                                    </div>
                                    <!-- End Search -->
                                </form>
                                <div class="input-group header-item">
                                    <select name="category" id="category" class="form-control js-select2-custom mx-1" title="<?php echo e(__('messages.select')); ?> <?php echo e(__('messages.category')); ?>" onchange="set_category_filter(this.value)">
                                        <option value=""><?php echo e(__('messages.all')); ?> <?php echo e(__("messages.categories")); ?></option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>" <?php echo e($category==$item->id?'selected':''); ?>><?php echo e($item->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-12" id="items">
                                <div class="d-flex flex-wrap mt-2 mb-3" style="justify-content: space-around;">
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="item-box">
                                            <?php echo $__env->make('admin-views.order.partials._single_product',['product'=>$product, 'restaurant_data'=>$order->vendor], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <?php echo $products->withQueryString()->links(); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                    <?php
                        $coupon = null;
                        $total_addon_price = 0;
                        $product_price = 0;
                        $restaurant_discount_amount = 0;
                        $del_c=$order['delivery_charge'];
                        if($editing)
                        {
                            $del_c=$order['delivery_charge'];
                        }
                        if($order->coupon_code)
                        {
                            $coupon = \App\Models\Coupon::where(['code' => $order['coupon_code']])->first();
                            if($editing && $coupon->coupon_type == 'free_delivery')
                            {
                                $del_c = 0;
                                $coupon = null;
                            }
                        }
                        //$details = $order->details;

                        if($editing)
                        {
                            $details = session('order_cart');
                        }
                        else
                        {
                          /*  foreach($details as $key=>$item)
                            {
                                $details[$key]->status = true;
                            }*/
                        }
                    ?>
        

                    <?php 
                        $coupon_discount_amount = $order['coupon_discount_amount'];

                        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;
                    
                        $total_tax_amount= $order['total_tax_amount'];

                        if($editing)
                        {
                            $restaurant_discount = \App\CentralLogics\Helpers::get_restaurant_discount($order->vendor);
                            if(isset($restaurant_discount))
                            {
                                if($product_price + $total_addon_price < $restaurant_discount['min_purchase'])
                                {
                                    $restaurant_discount_amount = 0;
                                }
                    
                                if($restaurant_discount_amount > $restaurant_discount['max_discount'])
                                {
                                    $restaurant_discount_amount = $restaurant_discount['max_discount'];
                                }
                            }
                            $coupon_discount_amount = $coupon ? \App\CentralLogics\CouponLogic::get_discount($coupon, $product_price + $total_addon_price - $restaurant_discount_amount) : $order['coupon_discount_amount']; 
                            $tax = $order->vendor->tax;

                            $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;
                    
                            $total_tax_amount = ($tax > 0)?(($total_price * $tax)/100):0;  
                            
                            $total_tax_amount = round($total_tax_amount, 2);

                            $restaurant_discount_amount = round($restaurant_discount_amount, 2);

                            if($order->vendor->free_delivery)
                            {
                                $del_c = 0;
                            }

                            $free_delivery_over = \App\Models\BusinessSetting::where('key', 'free_delivery_over')->first()->value;
                            if(isset($free_delivery_over))
                            {
                                if($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount)
                                {
                                    $del_c = 0;
                                }
                            }
                        }
                        else
                        {
                            $restaurant_discount_amount = $order['restaurant_discount_amount'];
                        }


                    ?>

                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-sm-6"><?php echo e(__('messages.service')); ?> <?php echo e(__('messages.price')); ?>:</dt>
                                    <dd class="col-sm-6"><?php echo e(\App\CentralLogics\Helpers::format_currency($product_price)); ?></dd>
                                    

                                    <dt class="col-sm-6"><?php echo e(__('messages.subtotal')); ?>:</dt>
                                    <dd class="col-sm-6">
                                        <?php echo e(\App\CentralLogics\Helpers::format_currency($product_price+$total_addon_price)); ?></dd>
                                    <dt class="col-sm-6"><?php echo e(__('messages.discount')); ?>:</dt>
                                    <dd class="col-sm-6">
                                        - <?php echo e(\App\CentralLogics\Helpers::format_currency($restaurant_discount_amount)); ?></dd>
                                    <dt class="col-sm-6"><?php echo e(__('messages.coupon')); ?> <?php echo e(__('messages.discount')); ?>:</dt>
                                    <dd class="col-sm-6">
                                        - <?php echo e(\App\CentralLogics\Helpers::format_currency($coupon_discount_amount)); ?></dd>
                                    <dt class="col-sm-6"><?php echo e(__('messages.tax')); ?>:</dt>
                                    <dd class="col-sm-6">
                                        + <?php echo e(\App\CentralLogics\Helpers::format_currency($total_tax_amount)); ?></dd>
                                    

                                    <dt class="col-sm-6"><?php echo e(__('messages.total')); ?>:</dt>
                                    <dd class="col-sm-6"><?php echo e(\App\CentralLogics\Helpers::format_currency($product_price+$del_c+$total_tax_amount+$total_addon_price-$coupon_discount_amount - $restaurant_discount_amount)); ?></dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                            <?php if($editing): ?>
                            <div class="offset-sm-8 col-sm-4 d-flex justify-content-between">
                                <button class="btn btn-sm btn-danger" type="button" onclick="cancle_editing_order()"><?php echo e(__('messages.cancel')); ?></button>
                                <button class="btn btn-sm btn-primary" type="button" onclick="update_order()"><?php echo e(__('messages.submit')); ?></button>
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
                <?php if($order['order_type']!='take_away'): ?>
                <!-- Card -->
                <div class="card mb-2">
                    <!-- Header -->
                   
                    <!-- End Header -->

                    <!-- Body -->
                    
                    <div class="card-body">
                    <?php if($order->delivery_man): ?>    
                        <a class="media align-items-center  deco-none" href="<?php echo e(route('admin.delivery-man.preview',[$order->delivery_man['id']])); ?>">                            
                            <div class="avatar avatar-circle mr-3">
    
                                    <img class="avatar-img" style="width: 75px"
                                    onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img1.jpg')); ?>'"
                                    src="<?php echo e(asset('storage/app/public/delivery-man/'.$order->delivery_man->image)); ?>"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="text-body text-hover-primary"><?php echo e($order->delivery_man['f_name'].' '.$order->delivery_man['l_name']); ?></span><br>
                                <span class="badge badge-ligh"><?php echo e($order->delivery_man->orders->count()); ?> <?php echo e(__('messages.orders_delivered')); ?></span>
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
                        <?php ($address=$order->dm_last_location); ?>
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
                            <?php echo e(__('messages.location').' '.__('messages.not_found')); ?>

                        </span>
                        <?php endif; ?>
           
                    <?php else: ?>
                        <div class="w-100 text-center">
                            <div class="hs-unfold">
                                <button type="button" class="btn font-weight-bold" data-toggle="modal" data-target="#myModal" data-lat='21.03' data-lng='105.85'>
                                    Customer Service Info
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
     
                            <a class="media align-items-center deco-none" href="<?php echo e(route('admin.customer.view',[$order->customer['id']])); ?>">    
                                <div class="avatar avatar-circle mr-3">
                                    
                                    <img class="avatar-img" style="width: 75px"
                                    onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img1.jpg')); ?>'"
                                    src="<?php echo e(asset('storage/app/public/profile/'.$order->customer->image)); ?>"
                                    alt="Image Description">

                                </div>
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"><?php echo e($order->customer['name']); ?> </span><br>
                                    <span class="badge badge-ligh">
                                        
                                        
                                        /*  
                                   <!--  $order->customer->orders->count()}}  -->
                                        
                                        
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
                                    <?php echo e($order->customer['email']); ?>

                                </li>
                                <li>
                                    <a class="deco-none" href="tel:<?php echo e($order->customer['phone']); ?>">
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        <?php echo e($order->customer['phone']); ?>

                                    </a>
                                </li>
                                <li>
                                    
                                </li>
                            </ul>

                           
                        </div>
                    <?php endif; ?>
                <!-- End Body -->
                </div>
                <!-- End Card -->
                <!-- Restaurant Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title"><?php echo e(__('messages.vendor')); ?></h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <?php if($order->vendor): ?>
                    <div class="card-body">
                        <a class="media align-items-center deco-none" href="<?php echo e(route('admin.vendor.view',[$order->vendor['id']])); ?>">
                            <div class="avatar avatar-circle mr-3">
                                <img
                                    class="avatar-img" style="width: 75px"
                                    onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img1.jpg')); ?>'"
                                    src="<?php echo e(asset('storage/app/public/restaurant/'.$order->vendor->logo)); ?>"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="text-body text-hover-primary"><?php echo e($order->vendor->names()); ?></span><br>
                                <span class="badge badge-ligh"><?php echo e($order->vendor->orders->count()); ?> <?php echo e(__('messages.orders_served')); ?></span>
                            </div>
                        </a>
                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5><?php echo e(__('messages.contact')); ?> <?php echo e(__('messages.info')); ?></h5>
                        </div>

                        <ul class="list-unstyled list-unstyled-py-2">
                            <li>
                                <i class="tio-online mr-2"></i>
                                <?php echo e($order->vendor['email']); ?>

                            </li>
                            <li>
                                <a class="deco-none" href="tel:<?php echo e($order->vendor['phone']); ?>">
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    <?php echo e($order->vendor['phone']); ?>

                                </a>
                            </li>
                        </ul>
                        <hr>
                        <span class="d-block">
                            <a target="_blank"
                                href="http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo e($order->vendor['latitude']); ?>+<?php echo e($order->vendor['longitude']); ?>">
                                <i class="tio-map"></i> <?php echo e($order->vendor['address']); ?><br>
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel"><?php echo e(__('messages.reference')); ?> <?php echo e(__('messages.code')); ?> <?php echo e(__('messages.add')); ?></h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                            aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('admin.order.add-payment-ref-code',[$order['id']])); ?>" method="post">
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
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                                aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                      d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z"/>
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
                    <form action="<?php echo e(route('admin.order.update-shipping',[$order['id']])); ?>"
                          method="post">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.type')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                           value="<?php echo e($address['address_type'] ?? NULL); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.contact')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                           value="<?php echo e($address['contact_person_number'] ?? NULL); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.name')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                           value="<?php echo e($address['contact_person_name'] ?? NULL); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.address')); ?>

                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address"
                                           value="<?php echo e($address['address']); ?>"
                                           >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.latitude')); ?>

                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude"
                                           value="<?php echo e($address['latitude']); ?>"
                                           >
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    <?php echo e(__('messages.longitude')); ?>

                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude"
                                           value="<?php echo e($address['longitude']); ?>" >
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo e(__('messages.close')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e(__('messages.save')); ?> <?php echo e(__('messages.changes')); ?></button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Dm assign Modal -->
   
    <!-- End Modal -->

    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel"><?php echo e(__('messages.location')); ?> <?php echo e(__('messages.data')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var keyword= $('#datatableSearch').val();
            var nurl = new URL('<?php echo url()->full(); ?>');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function set_category_filter(id) {
            var nurl = new URL('<?php echo url()->full(); ?>');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        function addon_quantity_input_toggle(e)
        {
            var cb = $(e.target);
            if(cb.is(":checked"))
            {
                cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
            }
            else
            {
                cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
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
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
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
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log("success...")
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function (e) {
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

            $('.input-number').focusin(function () {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function () {

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
            $(".input-number").keydown(function (e) {
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

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(route('admin.food.variant-price')); ?>',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function (data) {
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    }
                });
            }
        }

        function update_order_item(form_id = 'add-to-cart-form') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '<?php echo e(route('admin.order.add-to-cart')); ?>',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "<?php echo e(__('messages.product_already_added_in_cart')); ?>"
                        });
                        return false;
                    } 
                    else if (data.data == 0) {
                        toastr.success('<?php echo e(__('messages.product_has_been_added_in_cart')); ?>', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        location.reload();
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('<?php echo e(__('messages.order_updated_successfully')); ?>', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        }

        function removeFromCart(key) {
            Swal.fire({
                title: '<?php echo e(__('messages.are_you_sure')); ?>',
                text: '<?php echo e(__('messages.you_want_to_remove_this_order_item')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(__('messages.no')); ?>',
                confirmButtonText: '<?php echo e(__('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post('<?php echo e(route('admin.order.remove-from-cart')); ?>', {_token: '<?php echo e(csrf_token()); ?>', key: key, order_id: '<?php echo e($order->id); ?>'}, function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('<?php echo e(__('messages.item_has_been_removed_from_cart')); ?>', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            location.reload();
                        }

                    });
                }
            })

        }

        function edit_order()
        {
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

        function cancle_editing_order()
        {
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

        function update_order()
        {
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
    </script>


    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '<?php echo e(url('/')); ?>/admin/order/add-delivery-man/<?php echo e($order['id']); ?>/' + id,
                success: function (data) {
                    location.reload();
                    console.log(data)
                    toastr.success('Successfully added', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                error: function (response) {
                    console.log(response);
                    toastr.error(response.responseJSON.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('Only available when order is out for delivery!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
    <script>
        var deliveryMan = [];
        var map = null;
        var myLatlng = 6;
        var dmbounds = new google.maps.LatLngBounds (null);
        var locationbounds = new google.maps.LatLngBounds (null);
        var dmMarkers = [];
        dmbounds.extend(myLatlng);
        locationbounds.extend(myLatlng);
        var myOptions = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,

            panControl: true,
            mapTypeControl: false,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: false,
            streetViewControl: false,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            }
        };
     
        $(document).ready(function() {

            // Re-init map before show modal
            $('#myModal').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                $("#dmassign-map").css("width", "100%");
                $("#map_canvas").css("width", "100%");
            });

            // Trigger map resize event after modal shown
            $('#myModal').on('shown.bs.modal', function() {
               // initializeGMap();
                google.maps.event.trigger(map, "resize");
                map.setCenter(myLatlng);
            });

            
            function initializegLocationMap() {
                map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);

                var infowindow = new google.maps.InfoWindow();
            
                <?php if($order->customer && isset($address)): ?>
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo e($address['latitude']); ?>, <?php echo e($address['longitude']); ?>),
                    map: map,
                    title: "<?php echo e($order->customer->f_name); ?> <?php echo e($order->customer->l_name); ?>",
                    icon: "<?php echo e(asset($assetPrefixPath . '/admin/img/customer_location.png')); ?>"
                });

                google.maps.event.addListener(marker, 'click', (function(marker) {
                    return function() {
                        infowindow.setContent("<div style='float:left'><img style='max-height:40px;wide:auto;' src='<?php echo e(asset('storage/app/public/profile/'.$order->customer->image)); ?>'></div><div style='float:right; padding: 10px;'><b><?php echo e($order->customer->f_name); ?> <?php echo e($order->customer->l_name); ?></b><br/><?php echo e($address['address']); ?></div>");
                        infowindow.open(map, marker);
                    }
                })(marker));
                locationbounds.extend(marker.getPosition());
                <?php endif; ?>
                <?php if($order->delivery_man && $order->dm_last_location): ?>
                var dmmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo e($order->dm_last_location['latitude']); ?>, <?php echo e($order->dm_last_location['longitude']); ?>),
                    map: map,
                    title: "<?php echo e($order->delivery_man->f_name); ?>  <?php echo e($order->delivery_man->l_name); ?>",
                    icon: "<?php echo e(asset($assetPrefixPath . '/admin/img/delivery_boy_map.png')); ?>"
                });

                google.maps.event.addListener(dmmarker, 'click', (function(dmmarker) {
                    return function() {
                        infowindow.setContent("<div style='float:left'><img style='max-height:40px;wide:auto;' src='<?php echo e(asset('storage/app/public/delivery-man/'.$order->delivery_man->image)); ?>'></div><div style='float:right; padding: 10px;'><b><?php echo e($order->delivery_man->f_name); ?>  <?php echo e($order->delivery_man->l_name); ?></b><br/> <?php echo e($order->dm_last_location['location']); ?></div>");
                        infowindow.open(map, dmmarker);
                    }
                })(dmmarker));
                locationbounds.extend(dmmarker.getPosition());
                <?php endif; ?>

                <?php if($order->vendor): ?>
                var Retaurantmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo e($order->vendor->latitude); ?>, <?php echo e($order->vendor->longitude); ?>),
                    map: map,
                    title: "<?php echo e($order->vendor->names()); ?>",
                    icon: "<?php echo e(asset($assetPrefixPath . '/admin/img/restaurant_map.png')); ?>"
                });

                google.maps.event.addListener(Retaurantmarker, 'click', (function(Retaurantmarker) {
                    return function() {
                        infowindow.setContent("<div style='float:left'><img style='max-height:40px;wide:auto;' src='<?php echo e(asset('storage/app/public/restaurant/'.$order->vendor->logo)); ?>'></div><div style='float:right; padding: 10px;'><b><?php echo e($order->vendor->name); ?></b><br/> <?php echo e($order->vendor->address); ?></div>");
                        infowindow.open(map, Retaurantmarker);
                    }
                })(Retaurantmarker));
                locationbounds.extend(Retaurantmarker.getPosition());
                <?php endif; ?>
                
                google.maps.event.addListenerOnce(map, 'idle', function() {
                    map.fitBounds(locationbounds);
                });
            }

            // Re-init map before show modal
            $('#locationModal').on('shown.bs.modal', function(event) {
                initializegLocationMap();
            });


            $('.dm_list').on('click', function() {
                var id = $(this).data('id');
                map.panTo(dmMarkers[id].getPosition());
                map.setZoom(13);
                dmMarkers[id].setAnimation(google.maps.Animation.BOUNCE);
                window.setTimeout(() => {
                    dmMarkers[id].setAnimation(null);
                }, 3);
            });
        })
        

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/order/order-view.blade.php ENDPATH**/ ?>