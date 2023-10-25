<div class="card-body">
    <div class="row mb-4">
        <div class="col-sm mb-2 mb-sm-0">
            <?php ($params=session('dash_params')); ?>
            <?php if($params['zone_id']!='all'): ?>
                <?php ($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name); ?>
            <?php else: ?>
                <?php ($zone_name='All'); ?>
            <?php endif; ?>
            <div class="d-flex align-items-center">
                <span class="h3 mb-0">
                    <span class="legend-indicator bg-primary" style="background-color: #511281!important;"></span>
                    <?php echo e(__('messages.total_sell')); ?> : <?php echo e(\App\CentralLogics\Helpers::format_currency(array_sum($total_sell))); ?>

                      <label style="font-size: 10px" class="badge badge-soft-info">( Zone : <?php echo e($zone_name); ?> )</label>
                </span>
            </div>
            <div class="d-flex align-items-center mt-2 mb-2">
                <span class="h5 mb-0">
                    <span class="legend-indicator bg-primary" style="background-color: #4CA1A3!important;"></span>
                    <?php echo e(__('messages.admin_commission')); ?> : <?php echo e(\App\CentralLogics\Helpers::format_currency(array_sum($commission))); ?>

                </span>
            </div>
        </div>

        <div class="col-sm-auto align-self-sm-end">
            <!-- Legend Indicators -->
            <div class="row font-size-sm">
                <div class="col-auto">
                    <h5 class="card-header-title"><i class="tio-chart-bar-4" style="font-size: 50px"></i></h5>
                </div>
            </div>
            <!-- End Legend Indicators -->
        </div>
    </div>
    <!-- End Row -->

    <!-- Bar Chart -->
    <div class="chartjs-custom">
        <canvas id="updatingData" style="height: 20rem;"
                data-hs-chartjs-options='{
                            "type": "bar",
                            "data": {
                              "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                              "datasets": [{
                                "data": [<?php echo e($total_sell[1]); ?>,<?php echo e($total_sell[2]); ?>,<?php echo e($total_sell[3]); ?>,<?php echo e($total_sell[4]); ?>,<?php echo e($total_sell[5]); ?>,<?php echo e($total_sell[6]); ?>,<?php echo e($total_sell[7]); ?>,<?php echo e($total_sell[8]); ?>,<?php echo e($total_sell[9]); ?>,<?php echo e($total_sell[10]); ?>,<?php echo e($total_sell[11]); ?>,<?php echo e($total_sell[12]); ?>],
                                "backgroundColor": "#511281",
                                "hoverBackgroundColor": "#511281",
                                "borderColor": "#511281"
                              },
                              {
                                "data": [<?php echo e($commission[1]); ?>,<?php echo e($commission[2]); ?>,<?php echo e($commission[3]); ?>,<?php echo e($commission[4]); ?>,<?php echo e($commission[5]); ?>,<?php echo e($commission[6]); ?>,<?php echo e($commission[7]); ?>,<?php echo e($commission[8]); ?>,<?php echo e($commission[9]); ?>,<?php echo e($commission[10]); ?>,<?php echo e($commission[11]); ?>,<?php echo e($commission[12]); ?>],
                                "backgroundColor": "#4CA1A3",
                                "borderColor": "#4CA1A3"
                              }]
                            },
                            "options": {
                              "scales": {
                                "yAxes": [{
                                  "gridLines": {
                                    "color": "#e7eaf3",
                                    "drawBorder": false,
                                    "zeroLineColor": "#e7eaf3"
                                  },
                                  "ticks": {
                                    "beginAtZero": true,
                                    "stepSize": <?php echo e(array_sum($total_sell)/5); ?>,
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 10,
                                    "postfix": " <?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>"
                                  }
                                }],
                                "xAxes": [{
                                  "gridLines": {
                                    "display": false,
                                    "drawBorder": false
                                  },
                                  "ticks": {
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 5
                                  },
                                  "categoryPercentage": 0.5,
                                  "maxBarThickness": "10"
                                }]
                              },
                              "cornerRadius": 2,
                              "tooltips": {
                                "prefix": " ",
                                "hasIndicator": true,
                                "mode": "index",
                                "intersect": false
                              },
                              "hover": {
                                "mode": "nearest",
                                "intersect": true
                              }
                            }
                          }'></canvas>
    </div>
    <!-- End Bar Chart -->
</div>

<script>
    // INITIALIZATION OF CHARTJS
    // =======================================================
    Chart.plugins.unregister(ChartDataLabels);

    $('.js-chart').each(function () {
        $.HSCore.components.HSChartJS.init($(this));
    });

    var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
</script>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/partials/_monthly-earning-graph.blade.php ENDPATH**/ ?>