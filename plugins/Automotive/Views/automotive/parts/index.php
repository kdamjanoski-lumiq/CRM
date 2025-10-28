<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('parts_inventory'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_parts/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_part'), array("class" => "btn btn-default", "title" => app_lang('add_part'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="parts-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#parts-table").appTable({
            source: '<?php echo_uri("automotive_parts/list_data") ?>',
            filterDropdown: [
                {name: "category", class: "w200", options: <?php echo json_encode($category_dropdown); ?>},
                {name: "low_stock", class: "w200", options: [
                    {id: "", text: "- <?php echo app_lang('stock_status'); ?> -"},
                    {id: "1", text: "<?php echo app_lang('low_stock'); ?>"}
                ]}
            ],
            columns: [
                {title: "<?php echo app_lang("part_number") ?>", "class": "w15p"},
                {title: "<?php echo app_lang("part_name") ?>", "class": "w25p"},
                {title: "<?php echo app_lang("category") ?>"},
                {title: "<?php echo app_lang("quantity_in_stock") ?>", "class": "text-center"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"},
                {title: "<?php echo app_lang("cost_price") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("selling_price") ?>", "class": "text-right"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>