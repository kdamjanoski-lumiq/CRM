<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('service_appointments'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("automotive_service/appointment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_appointment'), array("class" => "btn btn-default", "title" => app_lang('add_appointment'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="appointments-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#appointments-table").appTable({
            source: '<?php echo_uri("automotive_service/appointments_list_data") ?>',
            filterDropdown: [
                {name: "status", class: "w200", options: <?php echo json_encode($status_dropdown); ?>}
            ],
            columns: [
                {title: "<?php echo app_lang("client") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("vehicle_info") ?>"},
                {title: "<?php echo app_lang("appointment_date") ?>"},
                {title: "<?php echo app_lang("service_type") ?>"},
                {title: "<?php echo app_lang("assigned_to") ?>"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>