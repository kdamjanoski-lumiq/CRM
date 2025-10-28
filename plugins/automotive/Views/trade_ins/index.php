<div id="page-content" class="page-wrapper clearfix grid-button">
    <div class="card clearfix">
        <ul id="trade-ins-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("trade_ins"); ?></h4>
            </li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_edit_trade_ins) { ?>
                        <?php echo modal_anchor(get_uri("automotive_trade_ins/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_trade_in'), array("class" => "btn btn-default", "title" => app_lang('add_trade_in'))); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="table-responsive">
            <table id="trade-ins-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#trade-ins-table").appTable({
            source: '<?php echo_uri("automotive_trade_ins/list_data") ?>',
            filterDropdown: [
                {name: "status", class: "w200", options: [
                    {id: "", text: "- <?php echo app_lang('status'); ?> -"},
                    {id: "pending", text: "<?php echo app_lang('pending'); ?>"},
                    {id: "approved", text: "<?php echo app_lang('approved'); ?>"},
                    {id: "completed", text: "<?php echo app_lang('completed'); ?>"},
                    {id: "rejected", text: "<?php echo app_lang('rejected'); ?>"}
                ]}
            ],
            columns: [
                {title: "<?php echo app_lang('id') ?>", "class": "w10p"},
                {title: "<?php echo app_lang('client') ?>"},
                {title: "<?php echo app_lang('vehicle') ?>"},
                {title: "<?php echo app_lang('registration') ?>"},
                {title: "<?php echo app_lang('value') ?>", "class": "text-right"},
                {title: "<?php echo app_lang('status') ?>", "class": "w15p"},
                {title: "<?php echo app_lang('created_date') ?>", "class": "w15p"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>