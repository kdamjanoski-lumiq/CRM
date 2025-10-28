<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('parts_sales'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_parts/sale_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_sale'), array("class" => "btn btn-default", "title" => app_lang('add_sale'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="sales-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#sales-table").appTable({
            source: '<?php echo_uri("automotive_parts/sales_list_data") ?>',
            columns: [
                {title: "<?php echo app_lang("sale_number") ?>", "class": "w15p"},
                {title: "<?php echo app_lang("part_name") ?>", "class": "w25p"},
                {title: "<?php echo app_lang("client") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("quantity") ?>", "class": "text-center"},
                {title: "<?php echo app_lang("unit_price") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("total_price") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("sale_date") ?>"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>