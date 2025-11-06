<?php echo form_open(get_uri("automotive_trade_ins/save"), array("id" => "trade-in-form", "class" => "general-form", "role" => "form")); ?>
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
                <label for="vehicle_type" class="col-md-3"><?php echo app_lang('vehicle_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("vehicle_type", array(
                        "caravan" => app_lang("caravan"),
                        "motorhome" => app_lang("motorhome"),
                        "trailer" => app_lang("trailer"),
                        "camper" => app_lang("camper"),
                        "other" => app_lang("other")
                    ), $model_info->vehicle_type ?? 'caravan', "class='select2' id='vehicle_type'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_make" class="col-md-3"><?php echo app_lang('vehicle_make'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vehicle_make",
                        "name" => "vehicle_make",
                        "value" => $model_info->vehicle_make ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_make'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_model" class="col-md-3"><?php echo app_lang('vehicle_model'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vehicle_model",
                        "name" => "vehicle_model",
                        "value" => $model_info->vehicle_model ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_model'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_year" class="col-md-3"><?php echo app_lang('vehicle_year'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vehicle_year",
                        "name" => "vehicle_year",
                        "value" => $model_info->vehicle_year ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_year'),
                        "type" => "number",
                        "min" => "1900",
                        "max" => date('Y') + 1,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_vin" class="col-md-3"><?php echo app_lang('vehicle_vin'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vehicle_vin",
                        "name" => "vehicle_vin",
                        "value" => $model_info->vehicle_vin ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_vin'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="mileage" class="col-md-3"><?php echo app_lang('mileage'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "mileage",
                        "name" => "mileage",
                        "value" => $model_info->mileage ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('mileage'),
                        "type" => "number",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="condition_rating" class="col-md-3"><?php echo app_lang('condition_rating'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("condition_rating", array(
                        "excellent" => app_lang("excellent"),
                        "good" => app_lang("good"),
                        "fair" => app_lang("fair"),
                        "poor" => app_lang("poor")
                    ), $model_info->condition_rating ?? 'good', "class='select2' id='condition_rating'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="trade_in_value" class="col-md-3"><?php echo app_lang('trade_in_value'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "trade_in_value",
                        "name" => "trade_in_value",
                        "value" => $model_info->trade_in_value ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('trade_in_value'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="invoice_id" class="col-md-3"><?php echo app_lang('invoice'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("invoice_id", $invoices_dropdown ?? array(), array($model_info->invoice_id ?? ''), "class='select2' id='invoice_id'");
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
                        "approved" => app_lang("approved"),
                        "completed" => app_lang("completed"),
                        "rejected" => app_lang("rejected")
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
        $("#trade-in-form").appForm({
            onSuccess: function (result) {
                $("#trade-ins-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#client_id").select2();
        $("#vehicle_type").select2();
        $("#condition_rating").select2();
        $("#status").select2();
        $("#invoice_id").select2();
        
        setDatePicker("#trade_in_date");
    });
</script>