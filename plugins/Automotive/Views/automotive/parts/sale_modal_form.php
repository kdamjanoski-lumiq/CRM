<?php echo form_open(get_uri("automotive_parts/save_sale"), array("id" => "sale-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="part_id" class="col-md-3"><?php echo app_lang('part'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("part_id", $parts_dropdown, array($model_info->part_id ?? ''), "class='select2 validate-hidden' id='part_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="client_id" class="col-md-3"><?php echo app_lang('client'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("client_id", $clients_dropdown, array($model_info->client_id ?? ''), "class='select2' id='client_id'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="quantity" class="col-md-3"><?php echo app_lang('quantity'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "quantity",
                        "name" => "quantity",
                        "value" => $model_info->quantity ?? '1',
                        "class" => "form-control",
                        "placeholder" => app_lang('quantity'),
                        "type" => "number",
                        "min" => "1",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="unit_price" class="col-md-3"><?php echo app_lang('unit_price'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "unit_price",
                        "name" => "unit_price",
                        "value" => $model_info->unit_price ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('unit_price'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="sale_date" class="col-md-3"><?php echo app_lang('sale_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "sale_date",
                        "name" => "sale_date",
                        "value" => $model_info->sale_date ?? date('Y-m-d'),
                        "class" => "form-control",
                        "placeholder" => app_lang('sale_date'),
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
        $("#sale-form").appForm({
            onSuccess: function (result) {
                $("#sales-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#part_id").select2();
        $("#client_id").select2();
        
        setDatePicker("#sale_date");
    });
</script>