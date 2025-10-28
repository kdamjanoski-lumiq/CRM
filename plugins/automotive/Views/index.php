<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-md-12">
            <div class="page-title clearfix">
                <h1><?php echo app_lang('automotive_dashboard'); ?></h1>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Floor Stock Summary -->
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-primary">
                        <i data-feather="package" class="icon"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="floor-stock-count">0</h1>
                        <span class="bg-transparent-white"><?php echo app_lang("floor_stock"); ?></span>
                    </div>
                    <a href="<?php echo get_uri('automotive_floor_stock'); ?>" class="widget-link">
                        <i data-feather="arrow-right-circle" class="icon-16"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Service Appointments -->
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-success">
                        <i data-feather="calendar" class="icon"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="appointments-count">0</h1>
                        <span class="bg-transparent-white"><?php echo app_lang("appointments"); ?></span>
                    </div>
                    <a href="<?php echo get_uri('automotive_service/appointments'); ?>" class="widget-link">
                        <i data-feather="arrow-right-circle" class="icon-16"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Service Jobs -->
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-warning">
                        <i data-feather="tool" class="icon"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="jobs-count">0</h1>
                        <span class="bg-transparent-white"><?php echo app_lang("service_jobs"); ?></span>
                    </div>
                    <a href="<?php echo get_uri('automotive_service/jobs'); ?>" class="widget-link">
                        <i data-feather="arrow-right-circle" class="icon-16"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Parts Inventory -->
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-info">
                        <i data-feather="box" class="icon"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="parts-count">0</h1>
                        <span class="bg-transparent-white"><?php echo app_lang("parts"); ?></span>
                    </div>
                    <a href="<?php echo get_uri('automotive_parts'); ?>" class="widget-link">
                        <i data-feather="arrow-right-circle" class="icon-16"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Trade-ins Summary -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("trade_ins"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("pending"); ?>:</span>
                        <strong id="trade-ins-pending">0</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("approved"); ?>:</span>
                        <strong id="trade-ins-approved">0</strong>
                    </div>
                    <div>
                        <span class="text-muted"><?php echo app_lang("total_value"); ?>:</span>
                        <strong id="trade-ins-value">$0.00</strong>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo get_uri('automotive_trade_ins'); ?>" class="btn btn-sm btn-default">
                        <?php echo app_lang("view_all"); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Deposits Summary -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("deposits"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("pending"); ?>:</span>
                        <strong id="deposits-pending">0</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("confirmed"); ?>:</span>
                        <strong id="deposits-confirmed">0</strong>
                    </div>
                    <div>
                        <span class="text-muted"><?php echo app_lang("total_amount"); ?>:</span>
                        <strong id="deposits-amount">$0.00</strong>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo get_uri('automotive_deposits'); ?>" class="btn btn-sm btn-default">
                        <?php echo app_lang("view_all"); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Parts Low Stock Alert -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("low_stock_alerts"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("low_stock"); ?>:</span>
                        <strong id="parts-low-stock" class="text-danger">0</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted"><?php echo app_lang("total_parts"); ?>:</span>
                        <strong id="parts-total">0</strong>
                    </div>
                    <div>
                        <span class="text-muted"><?php echo app_lang("inventory_value"); ?>:</span>
                        <strong id="parts-value">$0.00</strong>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo get_uri('automotive_parts'); ?>" class="btn btn-sm btn-default">
                        <?php echo app_lang("view_all"); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Load dashboard statistics
        $.ajax({
            url: "<?php echo get_uri('automotive/get_dashboard_stats'); ?>",
            type: 'POST',
            dataType: 'json',
            success: function(result) {
                // Floor Stock
                $("#floor-stock-count").text(result.floor_stock.total);
                
                // Service
                $("#appointments-count").text(result.service.scheduled);
                $("#jobs-count").text(result.service_jobs.in_progress);
                
                // Parts
                $("#parts-count").text(result.parts.total);
                $("#parts-low-stock").text(result.parts.low_stock);
                $("#parts-total").text(result.parts.total);
                $("#parts-value").text(toCurrency(result.parts.inventory_value));
                
                // Trade-ins
                $("#trade-ins-pending").text(result.trade_ins.pending);
                $("#trade-ins-approved").text(result.trade_ins.approved);
                $("#trade-ins-value").text(toCurrency(result.trade_ins.total_value));
                
                // Deposits
                $("#deposits-pending").text(result.deposits.pending);
                $("#deposits-confirmed").text(result.deposits.confirmed);
                $("#deposits-amount").text(toCurrency(result.deposits.total_amount));
            }
        });
    });
</script>