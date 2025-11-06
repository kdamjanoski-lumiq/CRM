<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('automotive_dashboard'); ?></h1>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Floor Stock Summary -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-primary">
                                <i data-feather="truck" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="total-floor-stock">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('floor_stock'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_floor_stock'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Available Stock -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-success">
                                <i data-feather="check-circle" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="available-stock">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('available'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Appointments -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-info">
                                <i data-feather="calendar" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="scheduled-appointments">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('scheduled'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_service/appointments'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Service Jobs -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-warning">
                                <i data-feather="tool" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="active-jobs">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('in_progress'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_service/jobs'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Trade-ins -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-coral">
                                <i data-feather="repeat" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="pending-trade-ins">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('trade_ins'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_trade_ins'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Deposits -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-dark">
                                <i data-feather="dollar-sign" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="pending-deposits">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('deposits'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_deposits'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Parts -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-danger">
                                <i data-feather="alert-triangle" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="low-stock-parts">0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('low_stock'); ?></span>
                            </div>
                            <a href="<?php echo get_uri('automotive_parts'); ?>" class="widget-link">
                                <i data-feather="arrow-right-circle" class="icon"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Inventory Value -->
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-icon-widget">
                        <div class="card-body">
                            <div class="widget-icon bg-purple">
                                <i data-feather="trending-up" class="icon"></i>
                            </div>
                            <div class="widget-details">
                                <h1 id="total-inventory-value">$0</h1>
                                <span class="bg-transparent-white"><?php echo app_lang('total_value'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo app_lang('quick_actions'); ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo modal_anchor(get_uri("automotive_floor_stock/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_floor_stock'), array("class" => "btn btn-primary", "title" => app_lang('add_floor_stock'))); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo modal_anchor(get_uri("automotive_service/appointment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_appointment'), array("class" => "btn btn-info", "title" => app_lang('add_appointment'))); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo modal_anchor(get_uri("automotive_trade_ins/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_trade_in'), array("class" => "btn btn-coral", "title" => app_lang('add_trade_in'))); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo modal_anchor(get_uri("automotive_parts/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_part'), array("class" => "btn btn-success", "title" => app_lang('add_part'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Load dashboard statistics
        $.ajax({
            url: "<?php echo get_uri('automotive/get_dashboard_stats'); ?>",
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                if (result.floor_stock) {
                    $("#total-floor-stock").html(result.floor_stock.total);
                    $("#available-stock").html(result.floor_stock.available);
                    $("#total-inventory-value").html(toCurrency(result.floor_stock.total_value));
                }
                if (result.service) {
                    $("#scheduled-appointments").html(result.service.scheduled);
                    $("#active-jobs").html(result.service.in_progress);
                }
                if (result.trade_ins) {
                    $("#pending-trade-ins").html(result.trade_ins.pending);
                }
                if (result.deposits) {
                    $("#pending-deposits").html(result.deposits.pending);
                }
                if (result.parts) {
                    $("#low-stock-parts").html(result.parts.low_stock);
                }
            }
        });
    });
</script>