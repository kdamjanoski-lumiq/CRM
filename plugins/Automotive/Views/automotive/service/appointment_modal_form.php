<?php echo form_open(get_uri("automotive_service/save_appointment"), array("id" => "appointment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="client_id" class="col-md-3"><?php echo app_lang('client'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("client_id", $clients_dropdown, array($model_info->client_id ?? ''), "class='select2 validate-hidden' id='client_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_info" class="col-md-3"><?php echo app_lang('vehicle_info'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vehicle_info",
                        "name" => "vehicle_info",
                        "value" => $model_info->vehicle_info ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_info'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="appointment_date" class="col-md-3"><?php echo app_lang('appointment_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "appointment_date",
                        "name" => "appointment_date",
                        "value" => $model_info->appointment_date ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('appointment_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="appointment_time" class="col-md-3"><?php echo app_lang('appointment_time'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "appointment_time",
                        "name" => "appointment_time",
                        "value" => $model_info->appointment_time ?? '',
                        "class" => "form-control",
                        "placeholder" => "HH:MM",
                        "type" => "time",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="service_type" class="col-md-3"><?php echo app_lang('service_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "service_type",
                        "name" => "service_type",
                        "value" => $model_info->service_type ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('service_type'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="description" class="col-md-3"><?php echo app_lang('description'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "value" => $model_info->description ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "rows" => 3
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="assigned_to" class="col-md-3"><?php echo app_lang('assigned_to'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("assigned_to", $team_members_dropdown, array($model_info->assigned_to ?? ''), "class='select2' id='assigned_to'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="status" class="col-md-3"><?php echo app_lang('status'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("status", array(
                        "scheduled" => app_lang("scheduled"),
                        "in_progress" => app_lang("in_progress"),
                        "completed" => app_lang("completed"),
                        "cancelled" => app_lang("cancelled")
                    ), $model_info->status ?? 'scheduled', "class='select2' id='status'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="notes" class="col-md-3"><?php echo app_lang('notes'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "notes",
                        "name" => "notes",
                        "value" => $model_info->notes ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('notes'),
                        "rows" => 3
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields ?? array(), "label_column" => "col-md-3", "field_column" => "col-md-9")); ?> 

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#appointment-form").appForm({
            onSuccess: function (result) {
                $("#appointments-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#client_id").select2();
        $("#assigned_to").select2();
        $("#status").select2();
        
        setDatePicker("#appointment_date");
    });
</script>