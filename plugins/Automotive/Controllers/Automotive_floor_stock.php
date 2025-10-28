<?php

namespace Automotive\Controllers;

class Automotive_floor_stock extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
    }

    /* load floor stock list view */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $view_data['status_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("status") . " -"),
            array("id" => "available", "text" => app_lang("available")),
            array("id" => "reserved", "text" => app_lang("reserved")),
            array("id" => "sold", "text" => app_lang("sold")),
            array("id" => "in_service", "text" => app_lang("in_service"))
        ));
        $view_data['vehicle_type_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("vehicle_type") . " -"),
            array("id" => "caravan", "text" => app_lang("caravan")),
            array("id" => "motorhome", "text" => app_lang("motorhome")),
            array("id" => "trailer", "text" => app_lang("trailer")),
            array("id" => "camper", "text" => app_lang("camper")),
            array("id" => "other", "text" => app_lang("other"))
        ));
        return $this->template->rander("Automotive\\Views\\automotive/floor_stock/index", $view_data);
    }

    /* load floor stock add/edit modal */
    function modal_form() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");

        if ($id) {
            $stock_info = $Automotive_floor_stock_model->get_one($id);
            $view_data['model_info'] = $stock_info;
        }

        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        return $this->template->view('Automotive\\Views\\automotive/floor_stock/modal_form', $view_data);
    }

    /* save floor stock */
    function save() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");

        $data = array(
            "stock_number" => $this->request->getPost('stock_number'),
            "vehicle_type" => $this->request->getPost('vehicle_type'),
            "make" => $this->request->getPost('make'),
            "model" => $this->request->getPost('model'),
            "year" => $this->request->getPost('year'),
            "vin" => $this->request->getPost('vin'),
            "color" => $this->request->getPost('color'),
            "mileage" => $this->request->getPost('mileage'),
            "purchase_price" => unformat_currency($this->request->getPost('purchase_price')),
            "selling_price" => unformat_currency($this->request->getPost('selling_price')),
            "description" => $this->request->getPost('description'),
            "features" => $this->request->getPost('features'),
            "status" => $this->request->getPost('status'),
            "location" => $this->request->getPost('location'),
            "date_acquired" => $this->request->getPost('date_acquired'),
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
        }

        // Validate stock number uniqueness
        if (!$id || ($id && $this->request->getPost('stock_number') != $Automotive_floor_stock_model->get_one($id)->stock_number)) {
            if ($Automotive_floor_stock_model->is_stock_number_exists($this->request->getPost('stock_number'))) {
                echo json_encode(array("success" => false, 'message' => app_lang('stock_number_already_exists')));
                return false;
            }
        }

        $save_id = $Automotive_floor_stock_model->ci_save($data, $id);

        if ($save_id) {
            save_custom_fields("automotive_floor_stock", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete floor stock */
    function delete() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");

        if ($Automotive_floor_stock_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of floor stock, prepared for datatable */
    function list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_floor_stock", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "vehicle_type" => $this->request->getPost("vehicle_type"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("automotive_floor_stock", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");
        $list_data = $Automotive_floor_stock_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of floor stock list table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_floor_stock", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");
        $data = $Automotive_floor_stock_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of floor stock list table */
    private function _make_row($data, $custom_fields) {
        $stock_number = modal_anchor(get_uri("automotive_floor_stock/view/" . $data->id), $data->stock_number, array("title" => app_lang('floor_stock_details')));

        $status_class = "bg-secondary";
        if ($data->status == "available") {
            $status_class = "bg-success";
        } else if ($data->status == "reserved") {
            $status_class = "bg-warning";
        } else if ($data->status == "sold") {
            $status_class = "bg-danger";
        }

        $status = "<span class='badge $status_class'>" . app_lang($data->status) . "</span>";

        $row_data = array(
            $stock_number,
            $data->vehicle_type,
            $data->make . " " . $data->model,
            $data->year,
            to_currency($data->selling_price),
            $status
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_floor_stock/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_floor_stock'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_floor_stock'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_floor_stock/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load floor stock details view */
    function view($id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        if ($id) {
            $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");
            $stock_info = $Automotive_floor_stock_model->get_details(array("id" => $id))->getRow();

            if ($stock_info) {
                $view_data['stock_info'] = $stock_info;
                $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("automotive_floor_stock", $id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

                return $this->template->view('Automotive\\Views\\automotive/floor_stock/view', $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
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

    /* check if user can view automotive */
    private function can_view_automotive() {
        if ($this->login_user->user_type == "staff") {
            return $this->access_type == "all";
        } else {
            return true; // Clients can view their own data
        }
    }

    /* check if user can manage automotive */
    private function can_manage_automotive() {
        if ($this->login_user->user_type == "staff") {
            return $this->access_type == "all";
        }
        return false;
    }
}