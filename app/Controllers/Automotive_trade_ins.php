<?php

namespace App\Controllers;

class Automotive_trade_ins extends Security_Controller {

    protected $Automotive_trade_ins_model;

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
        $this->Automotive_trade_ins_model = model("App\Models\Automotive_trade_ins_model");
    }

    /* load trade-ins list view */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_trade_ins()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_trade_ins'] = $this->can_edit_trade_ins();
        $view_data['status_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("status") . " -"),
            array("id" => "pending", "text" => app_lang("pending")),
            array("id" => "approved", "text" => app_lang("approved")),
            array("id" => "completed", "text" => app_lang("completed")),
            array("id" => "rejected", "text" => app_lang("rejected"))
        ));
        return $this->template->rander("automotive/trade_ins/index", $view_data);
    }

    /* load trade-in add/edit modal */
    function modal_form() {
        $this->check_module_availability("module_automotive");

        $trade_in_id = $this->request->getPost('id');
        $invoice_id = $this->request->getPost('invoice_id');
        $client_id = $this->request->getPost('client_id');

        if (!$this->can_edit_trade_ins($trade_in_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric",
            "client_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Automotive_trade_ins_model->get_one($trade_in_id);
        $view_data['invoice_id'] = $invoice_id ? $invoice_id : $view_data['model_info']->invoice_id;
        $view_data['client_id'] = $client_id ? $client_id : $view_data['model_info']->client_id;

        // Get clients dropdown
        $view_data["clients_dropdown"] = $this->_get_clients_dropdown();

        // Get invoices dropdown for the client
        if ($view_data['client_id']) {
            $view_data["invoices_dropdown"] = $this->_get_invoices_dropdown($view_data['client_id']);
        }

        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("automotive_trade_ins", $trade_in_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('automotive/trade_ins/modal_form', $view_data);
    }

    /* save trade-in */
    function save() {
        $this->check_module_availability("module_automotive");

        $trade_in_id = $this->request->getPost('id');

        if (!$this->can_edit_trade_ins($trade_in_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "required|numeric",
            "vehicle_make" => "required",
            "vehicle_model" => "required",
            "vehicle_year" => "required|numeric",
            "trade_in_value" => "required|numeric"
        ));

        $data = array(
            "client_id" => $this->request->getPost('client_id'),
            "invoice_id" => $this->request->getPost('invoice_id') ? $this->request->getPost('invoice_id') : 0,
            "vehicle_make" => $this->request->getPost('vehicle_make'),
            "vehicle_model" => $this->request->getPost('vehicle_model'),
            "vehicle_year" => $this->request->getPost('vehicle_year'),
            "vehicle_vin" => $this->request->getPost('vehicle_vin'),
            "vehicle_registration" => $this->request->getPost('vehicle_registration'),
            "mileage" => $this->request->getPost('mileage') ? $this->request->getPost('mileage') : 0,
            "condition" => $this->request->getPost('condition'),
            "trade_in_value" => unformat_currency($this->request->getPost('trade_in_value')),
            "notes" => $this->request->getPost('notes'),
            "status" => $this->request->getPost('status')
        );

        if (!$trade_in_id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Automotive_trade_ins_model->ci_save($data, $trade_in_id);
        if ($save_id) {
            save_custom_fields("automotive_trade_ins", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo trade-in */
    function delete() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if (!$this->can_edit_trade_ins($id)) {
            app_redirect("forbidden");
        }

        if ($this->Automotive_trade_ins_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of trade-ins, prepared for datatable */
    function list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_trade_ins()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_trade_ins", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "client_id" => $this->request->getPost("client_id"),
            "invoice_id" => $this->request->getPost("invoice_id"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Automotive_trade_ins_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of trade-in list table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_trade_ins", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Automotive_trade_ins_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of trade-in list table */
    private function _make_row($data, $custom_fields) {
        $vehicle = $data->vehicle_year . " " . $data->vehicle_make . " " . $data->vehicle_model;
        
        $status_class = "bg-secondary";
        if ($data->status == "approved") {
            $status_class = "bg-success";
        } else if ($data->status == "pending") {
            $status_class = "bg-warning";
        } else if ($data->status == "rejected") {
            $status_class = "bg-danger";
        }

        $row_data = array(
            $data->id,
            anchor(get_uri("clients/view/" . $data->client_id), $data->client_name),
            $vehicle,
            $data->vehicle_registration ? $data->vehicle_registration : "-",
            to_decimal_format($data->trade_in_value),
            "<span class='badge $status_class'>" . app_lang($data->status) . "</span>",
            format_to_date($data->created_at, false)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_trade_ins/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_trade_in'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_trade_in'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_trade_ins/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* get clients dropdown */
    private function _get_clients_dropdown() {
        $clients = $this->Clients_model->get_dropdown_list(array("company_name"), "id", array("deleted" => 0));
        $clients_dropdown = array(array("id" => "", "text" => "- " . app_lang("client") . " -"));
        foreach ($clients as $key => $value) {
            $clients_dropdown[] = array("id" => $key, "text" => $value);
        }
        return json_encode($clients_dropdown);
    }

    /* get invoices dropdown for a client */
    private function _get_invoices_dropdown($client_id) {
        $invoices = $this->Invoices_model->get_details(array("client_id" => $client_id))->getResult();
        $invoices_dropdown = array(array("id" => "", "text" => "- " . app_lang("invoice") . " -"));
        foreach ($invoices as $invoice) {
            $invoices_dropdown[] = array("id" => $invoice->id, "text" => get_invoice_id($invoice->id));
        }
        return json_encode($invoices_dropdown);
    }

    /* check if user can view trade-ins */
    private function can_view_trade_ins() {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check if user can edit trade-ins */
    private function can_edit_trade_ins($trade_in_id = 0) {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check module availability */
    protected function check_module_availability($module_name) {
        if (!get_setting($module_name)) {
            app_redirect("forbidden");
        }
    }
}