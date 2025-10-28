<?php echo form_open(get_uri("automotive_floor_stock/save"), array("id" => "floor-stock-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="stock_number" class="col-md-3"><?php echo app_lang('stock_number'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "stock_number",
                        "name" => "stock_number",
                        "value" => $model_info->stock_number ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('stock_number'),
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
                <label for="make" class="col-md-3"><?php echo app_lang('vehicle_make'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "make",
                        "name" => "make",
                        "value" => $model_info->make ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_make'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="model" class="col-md-3"><?php echo app_lang('vehicle_model'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "model",
                        "name" => "model",
                        "value" => $model_info->model ?? '',
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
                <label for="year" class="col-md-3"><?php echo app_lang('vehicle_year'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "year",
                        "name" => "year",
                        "value" => $model_info->year ?? '',
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
                <label for="vin" class="col-md-3"><?php echo app_lang('vehicle_vin'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "vin",
                        "name" => "vin",
                        "value" => $model_info->vin ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('vehicle_vin'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="color" class="col-md-3"><?php echo app_lang('color'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "color",
                        "name" => "color",
                        "value" => $model_info->color ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('color'),
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
                <label for="purchase_price" class="col-md-3"><?php echo app_lang('purchase_price'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "purchase_price",
                        "name" => "purchase_price",
                        "value" => $model_info->purchase_price ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('purchase_price'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="selling_price" class="col-md-3"><?php echo app_lang('selling_price'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "selling_price",
                        "name" => "selling_price",
                        "value" => $model_info->selling_price ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('selling_price'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
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
                        "available" => app_lang("available"),
                        "reserved" => app_lang("reserved"),
                        "sold" => app_lang("sold"),
                        "in_service" => app_lang("in_service")
                    ), $model_info->status ?? 'available', "class='select2' id='status'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="location" class="col-md-3"><?php echo app_lang('location'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "location",
                        "name" => "location",
                        "value" => $model_info->location ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('location'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="date_acquired" class="col-md-3"><?php echo app_lang('date_acquired'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "date_acquired",
                        "name" => "date_acquired",
                        "value" => $model_info->date_acquired ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('date_acquired'),
                        "autocomplete" => "off",
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
                <label for="features" class="col-md-3"><?php echo app_lang('features'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "features",
                        "name" => "features",
                        "value" => $model_info->features ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('features'),
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
        $("#floor-stock-form").appForm({
            onSuccess: function (result) {
                $("#floor-stock-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#vehicle_type").select2();
        $("#status").select2();
        
        setDatePicker("#date_acquired");
    });
</script>