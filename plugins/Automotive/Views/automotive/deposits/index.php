<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('deposits'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_deposits/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_deposit'), array("class" => "btn btn-default", "title" => app_lang('add_deposit'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="deposits-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#deposits-table").appTable({
            source: '<?php echo_uri("automotive_deposits/list_data") ?>',
            filterDropdown: [
                {name: "status", class: "w200", options: <?php echo json_encode($status_dropdown); ?>}
            ],
            columns: [
                {title: "<?php echo app_lang("client") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("invoice") ?>"},
                {title: "<?php echo app_lang("deposit_amount") ?>", "class": "text-right"},
                {title: "<?php echo app_lang("payment_method") ?>"},
                {title: "<?php echo app_lang("payment_date") ?>"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>