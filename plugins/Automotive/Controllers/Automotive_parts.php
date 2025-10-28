<?php

namespace Automotive\Controllers;

class Automotive_parts extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
    }

    /* load parts inventory list view */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $view_data['category_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("category") . " -")
        ));
        return $this->template->rander("Automotive\\Views\\automotive/parts/index", $view_data);
    }

    /* load parts sales list view */
    function sales() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        return $this->template->rander("Automotive\\Views\\automotive/parts/sales");
    }

    /* load part add/edit modal */
    function modal_form() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");

        if ($id) {
            $part_info = $Automotive_parts_model->get_one($id);
            $view_data['model_info'] = $part_info;
        }

        return $this->template->view('Automotive\\Views\\automotive/parts/modal_form', $view_data);
    }

    /* save part */
    function save() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");

        $data = array(
            "part_number" => $this->request->getPost('part_number'),
            "part_name" => $this->request->getPost('part_name'),
            "description" => $this->request->getPost('description'),
            "category" => $this->request->getPost('category'),
            "manufacturer" => $this->request->getPost('manufacturer'),
            "supplier" => $this->request->getPost('supplier'),
            "cost_price" => unformat_currency($this->request->getPost('cost_price')),
            "selling_price" => unformat_currency($this->request->getPost('selling_price')),
            "quantity_in_stock" => $this->request->getPost('quantity_in_stock'),
            "reorder_level" => $this->request->getPost('reorder_level'),
            "location" => $this->request->getPost('location'),
            "notes" => $this->request->getPost('notes'),
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
        }

        // Validate part number uniqueness
        if (!$id || ($id && $this->request->getPost('part_number') != $Automotive_parts_model->get_one($id)->part_number)) {
            if ($Automotive_parts_model->is_part_number_exists($this->request->getPost('part_number'))) {
                echo json_encode(array("success" => false, 'message' => app_lang('part_number_already_exists')));
                return false;
            }
        }

        $save_id = $Automotive_parts_model->ci_save($data, $id);

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

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");

        if ($Automotive_parts_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of parts, prepared for datatable */
    function list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "category" => $this->request->getPost("category"),
            "low_stock" => $this->request->getPost("low_stock"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("automotive_parts", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $Automotive_parts_model = model("App\Models\Automotive_parts_model");
        $list_data = $Automotive_parts_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of parts list table */
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $Automotive_parts_model = model("App\Models\Automotive_parts_model");
        $data = $Automotive_parts_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of parts list table */
    private function _make_row($data, $custom_fields) {
        $part_number = modal_anchor(get_uri("automotive_parts/view/" . $data->id), $data->part_number, array("title" => app_lang('part_details')));

        $stock_status = "";
        if ($data->quantity_in_stock <= $data->reorder_level) {
            $stock_status = "<span class='badge bg-danger'>" . app_lang('low_stock') . "</span>";
        } else {
            $stock_status = "<span class='badge bg-success'>" . app_lang('in_stock') . "</span>";
        }

        $row_data = array(
            $part_number,
            $data->part_name,
            $data->category ? $data->category : "-",
            $data->quantity_in_stock,
            $stock_status,
            to_currency($data->cost_price),
            to_currency($data->selling_price)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_parts/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_part'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_part'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_parts/delete"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load part details view */
    function view($id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        if ($id) {
            $Automotive_parts_model = model("App\Models\Automotive_parts_model");
            $part_info = $Automotive_parts_model->get_details(array("id" => $id))->getRow();

            if ($part_info) {
                $view_data['part_info'] = $part_info;
                $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("automotive_parts", $id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

                return $this->template->view('Automotive\\Views\\automotive/parts/view', $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    /* load parts sale add/edit modal */
    function sale_modal_form() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");

        if ($id) {
            $sale_info = $Automotive_parts_sales_model->get_one($id);
            $view_data['model_info'] = $sale_info;
        }

        $view_data['parts_dropdown'] = $this->_get_parts_dropdown();
        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        return $this->template->view('Automotive\\Views\\automotive/parts/sale_modal_form', $view_data);
    }

    /* save parts sale */
    function save_sale() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");

        $part_id = $this->request->getPost('part_id');
        $quantity = $this->request->getPost('quantity');
        $unit_price = unformat_currency($this->request->getPost('unit_price'));
        $total_price = $quantity * $unit_price;

        // Check stock availability
        $part = $Automotive_parts_model->get_one($part_id);
        if (!$id && $part->quantity_in_stock < $quantity) {
            echo json_encode(array("success" => false, 'message' => app_lang('insufficient_stock')));
            return false;
        }

        $data = array(
            "part_id" => $part_id,
            "client_id" => $this->request->getPost('client_id'),
            "service_job_id" => $this->request->getPost('service_job_id'),
            "quantity" => $quantity,
            "unit_price" => $unit_price,
            "total_price" => $total_price,
            "sale_date" => $this->request->getPost('sale_date'),
            "notes" => $this->request->getPost('notes'),
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["sale_number"] = $Automotive_parts_sales_model->generate_sale_number();
        }

        $save_id = $Automotive_parts_sales_model->ci_save($data, $id);

        if ($save_id) {
            // Update stock quantity
            if (!$id) {
                $new_quantity = $part->quantity_in_stock - $quantity;
                $Automotive_parts_model->ci_save(array("quantity_in_stock" => $new_quantity), $part_id);
            }

            save_custom_fields("automotive_parts_sales", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_sale_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete parts sale */
    function delete_sale() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");

        if ($Automotive_parts_sales_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of parts sales, prepared for datatable */
    function sales_list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts_sales", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "part_id" => $this->request->getPost("part_id"),
            "client_id" => $this->request->getPost("client_id"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("automotive_parts_sales", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");
        $list_data = $Automotive_parts_sales_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_sale_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of sales list table */
    private function _sale_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_parts_sales", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");
        $data = $Automotive_parts_sales_model->get_details($options)->getRow();
        return $this->_make_sale_row($data, $custom_fields);
    }

    /* prepare a row of sales list table */
    private function _make_sale_row($data, $custom_fields) {
        $sale_number = modal_anchor(get_uri("automotive_parts/view_sale/" . $data->id), $data->sale_number, array("title" => app_lang('sale_details')));
        $client_link = $data->client_id ? anchor(get_uri("clients/view/" . $data->client_id), $data->client_name) : "-";

        $row_data = array(
            $sale_number,
            $data->part_name,
            $client_link,
            $data->quantity,
            to_currency($data->unit_price),
            to_currency($data->total_price),
            format_to_date($data->sale_date, false)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_parts/sale_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_sale'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_sale'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_parts/delete_sale"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load parts sale details view */
    function view_sale($id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        if ($id) {
            $Automotive_parts_sales_model = model("App\Models\Automotive_parts_sales_model");
            $sale_info = $Automotive_parts_sales_model->get_details(array("id" => $id))->getRow();

            if ($sale_info) {
                $view_data['sale_info'] = $sale_info;
                $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("automotive_parts_sales", $id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

                return $this->template->view('Automotive\\Views\\automotive/parts/view_sale', $view_data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    /* get parts dropdown */
    private function _get_parts_dropdown() {
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");
        $parts = $Automotive_parts_model->get_all_where(array("deleted" => 0))->getResult();
        
        $parts_dropdown = array(array("id" => "", "text" => "- " . app_lang("part") . " -"));
        foreach ($parts as $part) {
            $parts_dropdown[] = array("id" => $part->id, "text" => $part->part_number . " - " . $part->part_name);
        }
        return json_encode($parts_dropdown);
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