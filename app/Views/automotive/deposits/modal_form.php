<?php echo form_open(get_uri("automotive_deposits/save"), array("id" => "deposit-form", "class" => "general-form", "role" => "form")); ?>
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
                <label for="invoice_id" class="col-md-3"><?php echo app_lang('invoice'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("invoice_id", $invoices_dropdown ?? array(), array($model_info->invoice_id ?? ''), "class='select2 validate-hidden' id='invoice_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="deposit_amount" class="col-md-3"><?php echo app_lang('deposit_amount'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "deposit_amount",
                        "name" => "deposit_amount",
                        "value" => $model_info->deposit_amount ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('deposit_amount'),
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
                <label for="payment_method" class="col-md-3"><?php echo app_lang('payment_method'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("payment_method", $payment_methods_dropdown ?? array(), array($model_info->payment_method ?? ''), "class='select2' id='payment_method'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="payment_date" class="col-md-3"><?php echo app_lang('payment_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "payment_date",
                        "name" => "payment_date",
                        "value" => $model_info->payment_date ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('payment_date'),
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
                <label for="transaction_reference" class="col-md-3"><?php echo app_lang('transaction_reference'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "transaction_reference",
                        "name" => "transaction_reference",
                        "value" => $model_info->transaction_reference ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('transaction_reference'),
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
                        "confirmed" => app_lang("confirmed"),
                        "refunded" => app_lang("refunded")
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
        $("#deposit-form").appForm({
            onSuccess: function (result) {
                $("#deposits-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $("#client_id").select2();
        $("#invoice_id").select2();
        $("#payment_method").select2();
        $("#status").select2();
        
        setDatePicker("#payment_date");
    });
</script>