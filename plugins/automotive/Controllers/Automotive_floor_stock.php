<?php

namespace App\Controllers;

class Automotive_floor_stock extends Security_Controller {

    protected $Automotive_floor_stock_model;

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
        $this->Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");
    }

    /* load floor stock list view */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_floor_stock()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_floor_stock'] = $this->can_edit_floor_stock();
        
        // Get vehicle types for filter
        $view_data['vehicle_types'] = array(
            array("id" => "", "text" => "- " . app_lang("vehicle_type") . " -"),
            array("id" => "caravan", "text" => app_lang("caravan")),
            array("id" => "motorhome", "text" => app_lang("motorhome")),
            array("id" => "trailer", "text" => app_lang("trailer")),
            array("id" => "camper", "text" => app_lang("camper")),
            array("id" => "other", "text" => app_lang("other"))
        );
        
        // Get status for filter
        $view_data['status_dropdown'] = array(
            array("id" => "", "text" => "- " . app_lang("status") . " -"),
            array("id" => "available", "text" => app_lang("available")),
            array("id" => "reserved", "text" => app_lang("reserved")),
            array("id" => "sold", "text" => app_lang("sold")),
            array("id" => "in_service", "text" => app_lang("in_service"))
        );

        return $this->template->rander("automotive/floor_stock/index", $view_data);
    }

    /* load floor stock add/edit modal */
    function modal_form() {
        $this->check_module_availability("module_automotive");

        $stock_id = $this->request->getPost('id');

        if (!$this->can_edit_floor_stock($stock_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Automotive_floor_stock_model->get_one($stock_id);
        
        // Generate next stock number if new
        if (!$stock_id) {
            $prefix = get_setting("automotive_stock_number_prefix") ?: "STK";
            $last_stock = $this->Automotive_floor_stock_model->get_all(array("deleted" => 0))->getLastRow();
            if ($last_stock && $last_stock->stock_number) {
                $number = (int) filter_var($last_stock->stock_number, FILTER_SANITIZE_NUMBER_INT);
                $next_number = $number + 1;
            } else {
                $next_number = 1;
            }
            $view_data['suggested_stock_number'] = $prefix . "-" . str_pad($next_number, 5, "0", STR_PAD_LEFT);
        }

        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("automotive_floor_stock", $stock_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('automotive/floor_stock/modal_form', $view_data);
    }

    /* save floor stock */
    function save() {
        $this->check_module_availability("module_automotive");

        $stock_id = $this->request->getPost('id');

        if (!$this->can_edit_floor_stock($stock_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "stock_number" => "required",
            "make" => "required",
            "model" => "required",
            "year" => "required|numeric",
            "selling_price" => "required|numeric"
        ));

        $stock_number = $this->request->getPost('stock_number');
        
        // Check if stock number already exists
        if ($this->Automotive_floor_stock_model->is_stock_number_exists($stock_number, $stock_id)) {
            echo json_encode(array("success" => false, 'message' => app_lang('duplicate_stock_number')));
            exit();
        }

        $data = array(
            "stock_number" => $stock_number,
            "vehicle_type" => $this->request->getPost('vehicle_type'),
            "make" => $this->request->getPost('make'),
            "model" => $this->request->getPost('model'),
            "year" => $this->request->getPost('year'),
            "vin" => $this->request->getPost('vin'),
            "registration" => $this->request->getPost('registration'),
            "color" => $this->request->getPost('color'),
            "mileage" => $this->request->getPost('mileage') ? $this->request->getPost('mileage') : 0,
            "purchase_price" => unformat_currency($this->request->getPost('purchase_price')),
            "selling_price" => unformat_currency($this->request->getPost('selling_price')),
            "condition" => $this->request->getPost('condition'),
            "status" => $this->request->getPost('status'),
            "location" => $this->request->getPost('location'),
            "description" => $this->request->getPost('description'),
            "features" => $this->request->getPost('features')
        );

        if (!$stock_id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Automotive_floor_stock_model->ci_save($data, $stock_id);
        if ($save_id) {
            save_custom_fields("automotive_floor_stock", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo floor stock */
    function delete() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if (!$this->can_edit_floor_stock($id)) {
            app_redirect("forbidden");
        }

        if ($this->Automotive_floor_stock_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of floor stock, prepared for datatable */
    function list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_floor_stock()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_floor_stock", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "vehicle_type" => $this->request->getPost("vehicle_type"),
            "search" => $this->request->getPost("search"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Automotive_floor_stock_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of floor stock list table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_floor_stock", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Automotive_floor_stock_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of floor stock list table */
    private function _make_row($data, $custom_fields) {
        $vehicle = $data->year . " " . $data->make . " " . $data->model;
        
        $status_class = "bg-secondary";
        if ($data->status == "available") {
            $status_class = "bg-success";
        } else if ($data->status == "reserved") {
            $status_class = "bg-warning";
        } else if ($data->status == "sold") {
            $status_class = "bg-info";
        } else if ($data->status == "in_service") {
            $status_class = "bg-danger";
        }

        $row_data = array(
            $data->id,
            $data->stock_number,
            app_lang($data->vehicle_type),
            $vehicle,
            to_currency($data->selling_price),
            "<span class='badge $status_class'>" . app_lang($data->status) . "</span>",
            $data->location ? $data->location : "-",
            format_to_date($data->created_at, false)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_floor_stock/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_vehicle'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_vehicle'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_floor_stock/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* view floor stock details */
    function view($stock_id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_floor_stock()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['stock_info'] = $this->Automotive_floor_stock_model->get_details(array("id" => $stock_id))->getRow();
        
        if (!$view_data['stock_info']) {
            show_404();
        }

        $view_data['can_edit_floor_stock'] = $this->can_edit_floor_stock($stock_id);
        
        return $this->template->rander("automotive/floor_stock/view", $view_data);
    }

    /* mark vehicle as sold */
    function mark_as_sold() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric",
            "client_id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        $client_id = $this->request->getPost('client_id');

        if (!$this->can_edit_floor_stock($id)) {
            app_redirect("forbidden");
        }

        $data = array(
            "status" => "sold",
            "sold_date" => get_current_utc_time(),
            "sold_to_client_id" => $client_id
        );

        if ($this->Automotive_floor_stock_model->ci_save($data, $id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('vehicle_marked_as_sold')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* check if user can view floor stock */
    private function can_view_floor_stock() {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check if user can edit floor stock */
    private function can_edit_floor_stock($stock_id = 0) {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check module availability */
    private function check_module_availability($module_name) {
        if (!get_setting($module_name)) {
            app_redirect("forbidden");
        }
    }
}