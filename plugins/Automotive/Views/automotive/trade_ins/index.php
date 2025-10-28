<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('trade_ins'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_trade_ins/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_trade_in'), array("class" => "btn btn-default", "title" => app_lang('add_trade_in'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="trade-ins-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#trade-ins-table").appTable({
            source: '<?php echo_uri("automotive_trade_ins/list_data") ?>',
            filterDropdown: [
                {name: "status", class: "w200", options: <?php echo json_encode($status_dropdown); ?>}
            ],
            columns: [
                {title: "<?php echo app_lang("client") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("vehicle_make") ?>"},
                {title: "<?php echo app_lang("vehicle_model") ?>"},
                {title: "<?php echo app_lang("vehicle_year") ?>"},
                {title: "<?php echo app_lang("trade_in_value") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"},
                {title: "<?php echo app_lang("created_date") ?>", "class": "w15p"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>