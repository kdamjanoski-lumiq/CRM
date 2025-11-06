<?php echo form_open(get_uri("automotive_parts/save"), array("id" => "part-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="part_number" class="col-md-3"><?php echo app_lang('part_number'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "part_number",
                        "name" => "part_number",
                        "value" => $model_info->part_number ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('part_number'),
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
                <label for="part_name" class="col-md-3"><?php echo app_lang('part_name'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "part_name",
                        "name" => "part_name",
                        "value" => $model_info->part_name ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('part_name'),
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
                <label for="category" class="col-md-3"><?php echo app_lang('category'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "category",
                        "name" => "category",
                        "value" => $model_info->category ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('category'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="manufacturer" class="col-md-3"><?php echo app_lang('manufacturer'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "manufacturer",
                        "name" => "manufacturer",
                        "value" => $model_info->manufacturer ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('manufacturer'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="supplier" class="col-md-3"><?php echo app_lang('supplier'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "supplier",
                        "name" => "supplier",
                        "value" => $model_info->supplier ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('supplier'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="cost_price" class="col-md-3"><?php echo app_lang('cost_price'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "cost_price",
                        "name" => "cost_price",
                        "value" => $model_info->cost_price ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('cost_price'),
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
                        "value" => $model_info->selling_price ?? '0',
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
                <label for="quantity_in_stock" class="col-md-3"><?php echo app_lang('quantity_in_stock'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "quantity_in_stock",
                        "name" => "quantity_in_stock",
                        "value" => $model_info->quantity_in_stock ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('quantity_in_stock'),
                        "type" => "number",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="reorder_level" class="col-md-3"><?php echo app_lang('reorder_level'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "reorder_level",
                        "name" => "reorder_level",
                        "value" => $model_info->reorder_level ?? '0',
                        "class" => "form-control",
                        "placeholder" => app_lang('reorder_level'),
                        "type" => "number",
                    ));
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
        $("#part-form").appForm({
            onSuccess: function (result) {
                $("#parts-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>