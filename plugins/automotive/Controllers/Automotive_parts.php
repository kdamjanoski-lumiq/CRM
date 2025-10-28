<?php

namespace App\Controllers;

class Automotive_parts extends Security_Controller {

    protected $Automotive_parts_model;
    protected $Automotive_parts_sales_model;

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
        $this->Automotive_parts_model = model("App\Models\Automotive_parts_model");
        $this->Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");
    }

    /* load parts list view */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_parts()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_parts'] = $this->can_edit_parts();
        
        // Get categories for filter
        $categories = $this->Automotive_parts_model->get_categories()->getResult();
        $view_data['categories_dropdown'] = array(array("id" => "", "text" => "- " . app_lang("category") . " -"));
        foreach ($categories as $category) {
            $view_data['categories_dropdown'][] = array("id" => $category->category, "text" => $category->category);
        }
        
        return $this->template->rander("automotive/parts/index", $view_data);
    }

    /* load part add/edit modal */
    function modal_form() {
        $this->check_module_availability("module_automotive");

        $part_id = $this->request->getPost('id');

        if (!$this->can_edit_parts($part_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Automotive_parts_model->get_one($part_id);

        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("automotive_parts", $part_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('automotive/parts/modal_form', $view_data);
    }

    /* save part */
    function save() {
        $this->check_module_availability("module_automotive");

        $part_id = $this->request->getPost('id');

        if (!$this->can_edit_parts($part_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "part_number" => "required",
            "part_name" => "required",
            "selling_price" => "required|numeric"
        ));

        $part_number = $this->request->getPost('part_number');
        
        // Check if part number already exists
        if ($this->Automotive_parts_model->is_part_number_exists($part_number, $part_id)) {
            echo json_encode(array("success" => false, 'message' => app_lang('duplicate_part_number')));
            exit();
        }

        $data = array(
            "part_number" => $part_number,
            "part_name" => $this->request->getPost('part_name'),
            "description" => $this->request->getPost('description'),
            "category" => $this->request->getPost('category'),
            "manufacturer" => $this->request->getPost('manufacturer'),
            "supplier" => $this->request->getPost('supplier'),
            "quantity_in_stock" => $this->request->getPost('quantity_in_stock') ? $this->request->getPost('quantity_in_stock') : 0,
            "minimum_stock_level" => $this->request->getPost('minimum_stock_level') ? $this->request->getPost('minimum_stock_level') : 0,
            "unit_cost" => unformat_currency($this->request->getPost('unit_cost')),
            "selling_price" => unformat_currency($this->request->getPost('selling_price')),
            "location" => $this->request->getPost('location'),
            "compatible_vehicles" => $this->request->getPost('compatible_vehicles'),
            "status" => $this->request->getPost('status')
        );

        if (!$part_id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Automotive_parts_model->ci_save($data, $part_id);
        if ($save_id) {
            save_custom_fields("automotive_parts", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete part */
    function delete() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if (!$this->can_edit_parts($id)) {
            app_redirect("forbidden");
        }

        if ($this->Automotive_parts_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of parts */
    function list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_parts()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "category" => $this->request->getPost("category"),
            "low_stock" => $this->request->getPost("low_stock"),
            "search" => $this->request->getPost("search"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Automotive_parts_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of parts list table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Automotive_parts_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of parts list table */
    private function _make_row($data, $custom_fields) {
        $status_class = "bg-secondary";
        if ($data->status == "active") {
            $status_class = "bg-success";
        } else if ($data->status == "discontinued") {
            $status_class = "bg-warning";
        } else if ($data->status == "out_of_stock") {
            $status_class = "bg-danger";
        }

        // Check if low stock
        $stock_display = $data->quantity_in_stock;
        if ($data->quantity_in_stock <= $data->minimum_stock_level && $data->status == "active") {
            $stock_display = "<span class='text-danger'>" . $data->quantity_in_stock . " <i data-feather='alert-triangle' class='icon-16'></i></span>";
        }

        $profit_margin = $data->selling_price - $data->unit_cost;
        $profit_percentage = $data->unit_cost > 0 ? (($profit_margin / $data->unit_cost) * 100) : 0;

        $row_data = array(
            $data->id,
            $data->part_number,
            $data->part_name,
            $data->category ? $data->category : "-",
            $stock_display,
            to_currency($data->unit_cost),
            to_currency($data->selling_price),
            number_format($profit_percentage, 1) . "%",
            "<span class='badge $status_class'>" . app_lang($data->status) . "</span>"
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_parts/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_part'), "data-post-id" => $data->id))
                . modal_anchor(get_uri("automotive_parts/sell_modal_form"), "<i data-feather='shopping-cart' class='icon-16'></i>", array("class" => "sell", "title" => app_lang('sell_part'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_part'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_parts/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load sell part modal */
    function sell_modal_form() {
        $this->check_module_availability("module_automotive");

        $part_id = $this->request->getPost('part_id');

        if (!$this->can_edit_parts()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "part_id" => "required|numeric"
        ));

        $view_data['part_info'] = $this->Automotive_parts_model->get_one($part_id);
        
        if (!$view_data['part_info']) {
            show_404();
        }

        // Get clients dropdown
        $view_data["clients_dropdown"] = $this->_get_clients_dropdown();

        return $this->template->view('automotive/parts/sell_modal_form', $view_data);
    }

    /* save parts sale */
    function save_sale() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_edit_parts()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "part_id" => "required|numeric",
            "quantity" => "required|numeric",
            "unit_price" => "required|numeric"
        ));

        $part_id = $this->request->getPost('part_id');
        $quantity = $this->request->getPost('quantity');
        $unit_price = unformat_currency($this->request->getPost('unit_price'));
        $total_price = $quantity * $unit_price;

        // Check if enough stock
        $part = $this->Automotive_parts_model->get_one($part_id);
        if ($part->quantity_in_stock < $quantity) {
            echo json_encode(array("success" => false, 'message' => app_lang('insufficient_stock')));
            exit();
        }

        $data = array(
            "part_id" => $part_id,
            "service_job_id" => $this->request->getPost('service_job_id') ? $this->request->getPost('service_job_id') : NULL,
            "invoice_id" => $this->request->getPost('invoice_id') ? $this->request->getPost('invoice_id') : NULL,
            "client_id" => $this->request->getPost('client_id') ? $this->request->getPost('client_id') : NULL,
            "quantity" => $quantity,
            "unit_price" => $unit_price,
            "total_price" => $total_price,
            "sale_date" => get_current_utc_time(),
            "notes" => $this->request->getPost('notes'),
            "created_by" => $this->login_user->id,
            "created_at" => get_current_utc_time()
        );

        $save_id = $this->Automotive_parts_sales_model->ci_save($data);
        if ($save_id) {
            // Update stock
            $this->Automotive_parts_model->update_stock($part_id, -$quantity);
            
            // Update part status if out of stock
            $updated_part = $this->Automotive_parts_model->get_one($part_id);
            if ($updated_part->quantity_in_stock <= 0) {
                $this->Automotive_parts_model->ci_save(array("status" => "out_of_stock"), $part_id);
            }

            echo json_encode(array("success" => true, 'id' => $save_id, 'message' => app_lang('part_sold_successfully')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* view part details */
    function view($part_id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_parts()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['part_info'] = $this->Automotive_parts_model->get_details(array("id" => $part_id))->getRow();
        
        if (!$view_data['part_info']) {
            show_404();
        }

        $view_data['can_edit_parts'] = $this->can_edit_parts($part_id);
        
        // Get sales history
        $view_data['sales_history'] = $this->Automotive_parts_sales_model->get_details(array("part_id" => $part_id))->getResult();
        
        return $this->template->rander("automotive/parts/view", $view_data);
    }

    /* get low stock parts */
    function low_stock_alert() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_parts()) {
            app_redirect("forbidden");
        }

        $options = array("low_stock" => true, "status" => "active");
        $low_stock_parts = $this->Automotive_parts_model->get_details($options)->getResult();

        echo json_encode(array("success" => true, "data" => $low_stock_parts));
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

    /* check if user can view parts */
    private function can_view_parts() {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check if user can edit parts */
    private function can_edit_parts($part_id = 0) {
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