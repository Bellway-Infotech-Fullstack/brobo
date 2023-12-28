<div class="card-body">
    <div class="row mb-4">
        <div class="col-sm mb-2 mb-sm-0">
            <?php ($params=session('dash_params')); ?>
            <?php if($params['zone_id']!='all'): ?>
                <?php ($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name); ?>
            <?php else: ?>
                <?php ($zone_name='All'); ?>
            <?php endif; ?>


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