<?php echo form_open(get_uri("automotive_service/save_job"), array("id" => "job-form", "class" => "general-form", "role" => "form")); ?>
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
                <label for="service_description" class="col-md-3"><?php echo app_lang('service_description'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "service_description",
                        "name" => "service_description",
                        "value" => $model_info->service_description ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('service_description'),
                        "rows" => 3,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="labor_hours" class="col-md-3"><?php echo app_lang('labor_hours'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "labor_hours",
                        "name" => "labor_hours",
                        "value" => $model_info->labor_hours ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('labor_hours'),
                        "type" => "number",
                        "step" => "0.01",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="labor_rate" class="col-md-3"><?php echo app_lang('labor_rate'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "labor_rate",
                        "name" => "labor_rate",
                        "value" => $model_info->labor_rate ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('labor_rate'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="parts_cost" class="col-md-3"><?php echo app_lang('parts_cost'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "parts_cost",
                        "name" => "parts_cost",
                        "value" => $model_info->parts_cost ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('parts_cost'),
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
                <label for="start_date" class="col-md-3"><?php echo app_lang('start_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "start_date",
                        "name" => "start_date",
                        "value" => $model_info->start_date ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('start_date'),
                        "autocomplete" => "off",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="completion_date" class="col-md-3"><?php echo app_lang('completion_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "completion_date",
                        "name" => "completion_date",
                        "value" => $model_info->completion_date ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('completion_date'),
                        "autocomplete" => "off",
                    ));
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
                        "pending" => app_lang("pending"),
                        "in_progress" => app_lang("in_progress"),
                        "completed" => app_lang("completed"),
                        "invoiced" => app_lang("invoiced")
                    ), $model_info->status ?? 'pending', "class='select2' id='status'");
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
        $("#job-form").appForm({
            onSuccess: function (result) {
                $("#jobs-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#client_id").select2();
        $("#assigned_to").select2();
        $("#status").select2();
        
        setDatePicker("#start_date");
        setDatePicker("#completion_date");
    });
</script>