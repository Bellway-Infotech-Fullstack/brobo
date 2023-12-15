<?php $__env->startSection('title',__('messages.day_wise_report')); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> <?php echo e(__('messages.day_wise_report')); ?> <span class="h6 badge badge-soft-success ml-2" id="itemCount">( <?php echo e(session('from_date')); ?> - <?php echo e(session('to_date')); ?> )</span></h1>
                </div>
                <div class="col-sm-auto" style="width: 306px;">
                    <select name="zone_id" class="form-control js-select2-custom"
                            onchange="set_zone_filter('<?php echo e(url()->full()); ?>',this.value)">
                        <option value="all">All Zones</option>
                        <?php $__currentLoopData = \App\Models\Zone::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $z): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($z['id']); ?>" <?php echo e(isset($zone) && $zone->id == $z['id']?'selected':''); ?>>
                                <?php echo e($z['name']); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card px-3">
            <div class="row" style="border-radius: 10px">
                <div class="col-lg-12 pt-3">
                    <form action="<?php echo e(route('admin.report.set-date')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label"><?php echo e(__('messages.show')); ?> <?php echo e(__('messages.data')); ?> by <?php echo e(__('messages.date')); ?>

                                        <?php echo e(__('messages.range')); ?></label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <input type="date" name="from" id="from_date" <?php echo e(session()->has('from_date')?'value='.session('from_date'):''); ?>

                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <input type="date" name="to" id="to_date" <?php echo e(session()->has('to_date')?'value='.session('to_date'):''); ?>

                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-block"><?php echo e(__('messages.show')); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                    $from = session('from_date');
                    $to = session('to_date');
                    $total=\App\Models\Order::when(isset($zone), function($query)use($zone){
                        return $query->whereHas('restaurant', function($q)use($zone){
                            return $q->where('zone_id', $zone->id);
                        });
                    })->whereBetween('created_at', [$from, $to])->count();
                    if($total==0){
                    $total=.01;
                    }
                ?>
                <!--Admin earned-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <?php
                    $order_transactions=\App\Models\OrderTransaction::when(isset($zone), function($query)use($zone){
                            return $query->whereIn('vendor_id', $zone->restaurants->pluck('vendor_id'));
                        })->whereBetween('created_at', [$from, $to])->get();
                    $admin_earned=$order_transactions->sum('admin_commission');
                    $restaurant_earned=$order_transactions->sum('restaurant_amount');
                    $deliveryman_earned=$order_transactions->sum('delivery_charge');
                    $total_sell=$order_transactions->sum('order_amount');
                ?>
                <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-user nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1"><?php echo e(__('messages.admin')); ?> <?php echo e(__('messages.earned')); ?></h4>
                                            <span class="font-size-sm text-success">
                                                <?php echo e(\App\CentralLogics\Helpers::format_currency($admin_earned)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--Admin earned End-->
                <!--restaurant earned-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-shop nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1 text-capitalize"><?php echo e(__('messages.vendor')); ?> <?php echo e(__('messages.earned')); ?></h4>
                                            <span class="font-size-sm text-warning">
                                                <?php echo e(\App\CentralLogics\Helpers::format_currency($restaurant_earned)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--restaurant earned end-->
                <!--Deliveryman earned-->
              
                <!--Deliveryman earned end-->
                <!--Total sell-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-money nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1"><?php echo e(__('messages.total_sell')); ?></h4>
                                            <span class="font-size-sm text-dark">
                                            <?php echo e(\App\CentralLogics\Helpers::format_currency($total_sell)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--total sell end-->

                <!--In progress-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <?php
                    $returned=\App\Models\Order::when(isset($zone), function($query)use($zone){
                        return $query->whereHas('restaurant', function($q)use($zone){
                            return $q->where('zone_id', $zone->id);
                        });
                    })->whereIn('order_status',['pending','accepted', 'confirmed', 'processing','handover','picked_up'])->whereBetween('created_at', [$from, $to])->count()
                ?>
                    <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-running nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1 text-capitalize"><?php echo e(__('messages.in')); ?> <?php echo e(__('messages.progress')); ?></h4>
                                            <span class="font-size-sm text-warning">
                                            <i class="tio-trending-up"></i> <?php echo e($returned); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>

                                <div class="col-auto">
                                    <!-- Circle -->
                                    <div class="js-circle"
                                        data-hs-circles-options='{
                            "value": <?php echo e(round(($returned/$total)*100)); ?>,
                            "maxValue": 100,
                            "duration": 2000,
                            "isViewportInit": true,
                            "colors": ["#e7eaf3", "#ec9a3c"],
                            "radius": 25,
                            "width": 3,
                            "fgStrokeLinecap": "round",
                            "textFontSize": 14,
                            "additionalText": "%",
                            "textClass": "circle-custom-text",
                            "textColor": "#ec9a3c"
                            }'></div>
                                    <!-- End Circle -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--In progress End-->
                <!--In Delivered -->
                <div class="col-sm-6 col-lg-3 mb-3">
                <?php
                    $delivered=\App\Models\Order::when(isset($zone), function($query)use($zone){
                        return $query->whereHas('restaurant', function($q)use($zone){
                            return $q->where('zone_id', $zone->id);
                        });
                    })->where(['order_status'=>'delivered'])->whereBetween('created_at', [$from, $to])->count()
                ?>
                    <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-shopping-cart nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1"><?php echo e(__('messages.delivered')); ?></h4>
                                            <span class="font-size-sm text-success">
                                            <i class="tio-trending-up"></i> <?php echo e($delivered); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>

                                <div class="col-auto">
                                    <!-- Circle -->
                                    <div class="js-circle"
                                        data-hs-circles-options='{
                                        "value": <?php echo e(round(($delivered/$total)*100)); ?>,
                                        "maxValue": 100,
                                        "duration": 2000,
                                        "isViewportInit": true,
                                        "colors": ["#e7eaf3", "green"],
                                        "radius": 25,
                                        "width": 3,
                                        "fgStrokeLinecap": "round",
                                        "textFontSize": 14,
                                        "additionalText": "%",
                                        "textClass": "circle-custom-text",
                                        "textColor": "green"
                                        }'></div>
                                    <!-- End Circle -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--Delivered End-->
                <!--Failed-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <?php
                    $failed=\App\Models\Order::when(isset($zone), function($query)use($zone){
                        return $query->whereHas('restaurant', function($q)use($zone){
                            return $q->where('zone_id', $zone->id);
                        });
                    })->where(['order_status'=>'failed'])->whereBetween('created_at', [$from, $to])->count()
                ?>
                <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-message-failed nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1"><?php echo e(__('messages.failed')); ?></h4>
                                            <span class="font-size-sm text-danger">
                                            <i class="tio-trending-up"></i> <?php echo e($failed); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>

                                <div class="col-auto">
                                    <!-- Circle -->
                                    <div class="js-circle"
                                        data-hs-circles-options='{
                            "value": <?php echo e(round(($failed/$total)*100)); ?>,
                            "maxValue": 100,
                            "duration": 2000,
                            "isViewportInit": true,
                            "colors": ["#e7eaf3", "darkred"],
                            "radius": 25,
                            "width": 3,
                            "fgStrokeLinecap": "round",
                            "textFontSize": 14,
                            "additionalText": "%",
                            "textClass": "circle-custom-text",
                            "textColor": "darkred"
                            }'></div>
                                    <!-- End Circle -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--Failed End-->
                <!--Canceled-->
                <div class="col-sm-6 col-lg-3 mb-3">
                <?php
                    $canceled=\App\Models\Order::when(isset($zone), function($query)use($zone){
                        return $query->whereHas('restaurant', function($q)use($zone){
                            return $q->where('zone_id', $zone->id);
                        });
                    })->where(['order_status'=>'canceled'])->whereBetween('created_at', [$from, $to])->count()
                ?>
                <!-- Card -->
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <!-- Media -->
                                    <div class="media">
                                        <i class="tio-flight-cancelled nav-icon"></i>

                                        <div class="media-body">
                                            <h4 class="mb-1"><?php echo e(__('messages.canceled')); ?></h4>
                                            <span class="font-size-sm text-muted">
                                            <i class="tio-trending-up"></i> <?php echo e($canceled); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <!-- End Media -->
                                </div>

                                <div class="col-auto">
                                    <!-- Circle -->
                                    <div class="js-circle"
                                        data-hs-circles-options='{
                            "value": <?php echo e(round(($canceled/$total)*100)); ?>,
                            "maxValue": 100,
                            "duration": 2000,
                            "isViewportInit": true,
                            "colors": ["#e7eaf3", "gray"],
                            "radius": 25,
                            "width": 3,
                            "fgStrokeLinecap": "round",
                            "textFontSize": 14,
                            "additionalText": "%",
                            "textClass": "circle-custom-text",
                            "textColor": "gray"
                            }'></div>
                                    <!-- End Circle -->
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!--canceled End-->
                
            </div>
        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header">
                <h1><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.transactions')); ?>

                </h1>
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-thead-bordered table-align-middle card-table"
                        style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th  style="width: 5%"><?php echo e(__('messages.sl#')); ?></th>
                                <th  style="width: 10%"><?php echo e(__('messages.order')); ?> <?php echo e(__('messages.id')); ?></th>
                                <th  style="width: 17%"><?php echo e(__('messages.total_order_amount')); ?></th>
                                <th  style="width: 10%"><?php echo e(__('messages.vendor_commission')); ?></th>
                                <th  style="width: 10%"><?php echo e(__('messages.admin_commission')); ?></th>
                                
                                
                                <th  style="width: 10%"><?php echo e(__('messages.amount')); ?> <?php echo e(__('messages.received_by')); ?></th>
                                <th  style="width: 22%"><?php echo e(__('messages.created_at')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php ($order_transactions = \App\Models\OrderTransaction::when(isset($zone), function($query)use($zone){
                            return $query->whereIn('vendor_id', $zone->restaurants->pluck('vendor_id'));
                        })->whereBetween('created_at', [$from, $to])->latest()->paginate(25)); ?>
                        <?php $__currentLoopData = $order_transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$ot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr scope="row">
                                <td ><?php echo e($k+$order_transactions->firstItem()); ?></td>
                                <td><a href="<?php echo e(route('admin.order.details',$ot->order_id)); ?>"><?php echo e($ot->order_id); ?></a></td>
                                <td><?php echo e($ot->order_amount); ?></td>
                                <td><?php echo e($ot->restaurant_amount - $ot->tax); ?></td>
                                <td><?php echo e($ot->admin_commission); ?></td>
                                
                                
                                <td class="text-capitalize"><?php echo e($ot->received_by); ?></td>
                                <td><?php echo e($ot->created_at->format('Y/m/d '.config('timeformat'))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            <div class="card-footer">
                <?php echo $order_transactions->links(); ?>

            </div>    
        </div>
        <!-- End Card -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('script_2'); ?>

    <script src="<?php echo e(asset($assetPrefixPath . '/assets/admin')); ?>/vendor/chart.js/dist/Chart.min.js"></script>
    <script
        src="<?php echo e(asset($assetPrefixPath . '/assets/admin')); ?>/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
    <script src="<?php echo e(asset($assetPrefixPath . '/assets/admin')); ?>/js/hs.chartjs-matrix.js"></script>

    <script>
        $(document).on('ready', function () {

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function (e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function (dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function () {
                                return '';
                            },
                            label: function (item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() + ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function () {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/report/day-wise-report.blade.php ENDPATH**/ ?>