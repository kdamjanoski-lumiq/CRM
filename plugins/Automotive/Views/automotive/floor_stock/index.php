<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('floor_stock'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_floor_stock/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_floor_stock'), array("class" => "btn btn-default", "title" => app_lang('add_floor_stock'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="floor-stock-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#floor-stock-table").appTable({
            source: '<?php echo_uri("automotive_floor_stock/list_data") ?>',
            filterDropdown: [
                {name: "status", class: "w200", options: <?php echo json_encode($status_dropdown); ?>},
                {name: "vehicle_type", class: "w200", options: <?php echo json_encode($vehicle_type_dropdown); ?>}
            ],
            columns: [
                {title: "<?php echo app_lang("stock_number") ?>", "class": "w15p"},
                {title: "<?php echo app_lang("vehicle_type") ?>"},
                {title: "<?php echo app_lang("vehicle") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("year") ?>"},
                {title: "<?php echo app_lang("selling_price") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>