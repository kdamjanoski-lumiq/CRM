<?php

namespace App\Controllers;

class Automotive_service extends Security_Controller {

    protected $Automotive_service_appointments_model;
    protected $Automotive_service_jobs_model;

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
        $this->Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");
        $this->Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");
    }

    /* load service dashboard */
    function index() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_service()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_service'] = $this->can_edit_service();
        return $this->template->rander("automotive/service/index", $view_data);
    }

    /* load appointments list view */
    function appointments() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_service()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_service'] = $this->can_edit_service();
        
        // Get team members for assignment
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        
        return $this->template->rander("automotive/service/appointments", $view_data);
    }

    /* load appointment add/edit modal */
    function appointment_modal_form() {
        $this->check_module_availability("module_automotive");

        $appointment_id = $this->request->getPost('id');
        $client_id = $this->request->getPost('client_id');

        if (!$this->can_edit_service($appointment_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Automotive_service_appointments_model->get_one($appointment_id);
        $view_data['client_id'] = $client_id ? $client_id : $view_data['model_info']->client_id;

        // Get clients dropdown
        $view_data["clients_dropdown"] = $this->_get_clients_dropdown();
        
        // Get team members dropdown
        $view_data["team_members_dropdown"] = $this->_get_team_members_dropdown();

        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("automotive_service_appointments", $appointment_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('automotive/service/appointment_modal_form', $view_data);
    }

    /* save appointment */
    function save_appointment() {
        $this->check_module_availability("module_automotive");

        $appointment_id = $this->request->getPost('id');

        if (!$this->can_edit_service($appointment_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "required|numeric",
            "appointment_date" => "required",
            "appointment_time" => "required"
        ));

        $data = array(
            "client_id" => $this->request->getPost('client_id'),
            "vehicle_registration" => $this->request->getPost('vehicle_registration'),
            "vehicle_make" => $this->request->getPost('vehicle_make'),
            "vehicle_model" => $this->request->getPost('vehicle_model'),
            "appointment_date" => $this->request->getPost('appointment_date'),
            "appointment_time" => $this->request->getPost('appointment_time'),
            "service_type" => $this->request->getPost('service_type'),
            "description" => $this->request->getPost('description'),
            "assigned_to" => $this->request->getPost('assigned_to') ? $this->request->getPost('assigned_to') : NULL,
            "status" => $this->request->getPost('status'),
            "estimated_cost" => unformat_currency($this->request->getPost('estimated_cost')),
            "notes" => $this->request->getPost('notes')
        );

        if (!$appointment_id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Automotive_service_appointments_model->ci_save($data, $appointment_id);
        if ($save_id) {
            save_custom_fields("automotive_service_appointments", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_appointment_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete appointment */
    function delete_appointment() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if (!$this->can_edit_service($id)) {
            app_redirect("forbidden");
        }

        if ($this->Automotive_service_appointments_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of appointments */
    function appointments_list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_service()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_appointments", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "client_id" => $this->request->getPost("client_id"),
            "assigned_to" => $this->request->getPost("assigned_to"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Automotive_service_appointments_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_appointment_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    private function _appointment_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_appointments", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Automotive_service_appointments_model->get_details($options)->getRow();
        return $this->_make_appointment_row($data, $custom_fields);
    }

    private function _make_appointment_row($data, $custom_fields) {
        $status_class = "bg-secondary";
        if ($data->status == "scheduled") {
            $status_class = "bg-primary";
        } else if ($data->status == "in_progress") {
            $status_class = "bg-warning";
        } else if ($data->status == "completed") {
            $status_class = "bg-success";
        } else if ($data->status == "cancelled") {
            $status_class = "bg-danger";
        }

        $row_data = array(
            $data->id,
            anchor(get_uri("clients/view/" . $data->client_id), $data->client_name),
            $data->vehicle_registration ? $data->vehicle_registration : "-",
            format_to_date($data->appointment_date, false) . " " . format_to_time($data->appointment_time),
            app_lang($data->service_type),
            $data->assigned_to_user ? $data->assigned_to_user : "-",
            "<span class='badge $status_class'>" . app_lang($data->status) . "</span>"
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_service/appointment_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_service/delete_appointment"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load service jobs list view */
    function jobs() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_service()) {
            app_redirect("forbidden");
        }

        $view_data['can_edit_service'] = $this->can_edit_service();
        return $this->template->rander("automotive/service/jobs", $view_data);
    }

    /* load job add/edit modal */
    function job_modal_form() {
        $this->check_module_availability("module_automotive");

        $job_id = $this->request->getPost('id');
        $appointment_id = $this->request->getPost('appointment_id');
        $client_id = $this->request->getPost('client_id');

        if (!$this->can_edit_service($job_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "appointment_id" => "numeric",
            "client_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Automotive_service_jobs_model->get_one($job_id);
        $view_data['appointment_id'] = $appointment_id ? $appointment_id : $view_data['model_info']->appointment_id;
        $view_data['client_id'] = $client_id ? $client_id : $view_data['model_info']->client_id;

        // Generate next job number if new
        if (!$job_id) {
            $view_data['suggested_job_number'] = $this->Automotive_service_jobs_model->get_next_job_number();
        }

        // Get clients dropdown
        $view_data["clients_dropdown"] = $this->_get_clients_dropdown();
        
        // Get team members dropdown
        $view_data["team_members_dropdown"] = $this->_get_team_members_dropdown();

        // Get custom fields
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("automotive_service_jobs", $job_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('automotive/service/job_modal_form', $view_data);
    }

    /* save service job */
    function save_job() {
        $this->check_module_availability("module_automotive");

        $job_id = $this->request->getPost('id');

        if (!$this->can_edit_service($job_id)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "required|numeric",
            "job_number" => "required"
        ));

        $job_number = $this->request->getPost('job_number');
        
        // Check if job number already exists
        if ($this->Automotive_service_jobs_model->is_job_number_exists($job_number, $job_id)) {
            echo json_encode(array("success" => false, 'message' => app_lang('duplicate_job_number')));
            exit();
        }

        $labor_cost = unformat_currency($this->request->getPost('labor_cost'));
        $parts_cost = unformat_currency($this->request->getPost('parts_cost'));
        $total_cost = $labor_cost + $parts_cost;

        $data = array(
            "appointment_id" => $this->request->getPost('appointment_id') ? $this->request->getPost('appointment_id') : NULL,
            "client_id" => $this->request->getPost('client_id'),
            "job_number" => $job_number,
            "vehicle_registration" => $this->request->getPost('vehicle_registration'),
            "vehicle_make" => $this->request->getPost('vehicle_make'),
            "vehicle_model" => $this->request->getPost('vehicle_model'),
            "vehicle_vin" => $this->request->getPost('vehicle_vin'),
            "mileage" => $this->request->getPost('mileage') ? $this->request->getPost('mileage') : 0,
            "description" => $this->request->getPost('description'),
            "work_performed" => $this->request->getPost('work_performed'),
            "assigned_to" => $this->request->getPost('assigned_to') ? $this->request->getPost('assigned_to') : NULL,
            "start_date" => $this->request->getPost('start_date'),
            "completion_date" => $this->request->getPost('completion_date'),
            "status" => $this->request->getPost('status'),
            "labor_cost" => $labor_cost,
            "parts_cost" => $parts_cost,
            "total_cost" => $total_cost,
            "notes" => $this->request->getPost('notes')
        );

        if (!$job_id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Automotive_service_jobs_model->ci_save($data, $job_id);
        if ($save_id) {
            save_custom_fields("automotive_service_jobs", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_job_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete service job */
    function delete_job() {
        $this->check_module_availability("module_automotive");

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if (!$this->can_edit_service($id)) {
            app_redirect("forbidden");
        }

        if ($this->Automotive_service_jobs_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of service jobs */
    function jobs_list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_service()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_jobs", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "client_id" => $this->request->getPost("client_id"),
            "assigned_to" => $this->request->getPost("assigned_to"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Automotive_service_jobs_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_job_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    private function _job_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_jobs", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Automotive_service_jobs_model->get_details($options)->getRow();
        return $this->_make_job_row($data, $custom_fields);
    }

    private function _make_job_row($data, $custom_fields) {
        $status_class = "bg-secondary";
        if ($data->status == "pending") {
            $status_class = "bg-warning";
        } else if ($data->status == "in_progress") {
            $status_class = "bg-primary";
        } else if ($data->status == "completed") {
            $status_class = "bg-success";
        } else if ($data->status == "invoiced") {
            $status_class = "bg-info";
        }

        $row_data = array(
            $data->id,
            $data->job_number,
            anchor(get_uri("clients/view/" . $data->client_id), $data->client_name),
            $data->vehicle_registration ? $data->vehicle_registration : "-",
            $data->assigned_to_user ? $data->assigned_to_user : "-",
            to_currency($data->total_cost),
            "<span class='badge $status_class'>" . app_lang($data->status) . "</span>",
            format_to_date($data->created_at, false)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_service/job_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_service/delete_job"), "data-action" => "delete-confirmation"));

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

    /* get team members dropdown */
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $team_members_dropdown = array(array("id" => "", "text" => "- " . app_lang("team_member") . " -"));
        foreach ($team_members as $key => $value) {
            $team_members_dropdown[] = array("id" => $key, "text" => $value);
        }
        return json_encode($team_members_dropdown);
    }

    /* check if user can view service */
    private function can_view_service() {
        if ($this->login_user->user_type == "staff") {
            return $this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive");
        }
        return false;
    }

    /* check if user can edit service */
    private function can_edit_service($id = 0) {
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