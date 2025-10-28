<?php

namespace Automotive\Controllers;

class Automotive_service extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
    }

    /* load service appointments list view */
    function appointments() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $view_data['status_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("status") . " -"),
            array("id" => "scheduled", "text" => app_lang("scheduled")),
            array("id" => "in_progress", "text" => app_lang("in_progress")),
            array("id" => "completed", "text" => app_lang("completed")),
            array("id" => "cancelled", "text" => app_lang("cancelled"))
        ));
        return $this->template->rander("Automotive\\Views\\automotive/service/appointments", $view_data);
    }

    /* load service jobs list view */
    function jobs() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $view_data['status_dropdown'] = json_encode(array(
            array("id" => "", "text" => "- " . app_lang("status") . " -"),
            array("id" => "pending", "text" => app_lang("pending")),
            array("id" => "in_progress", "text" => app_lang("in_progress")),
            array("id" => "completed", "text" => app_lang("completed")),
            array("id" => "invoiced", "text" => app_lang("invoiced"))
        ));
        return $this->template->rander("Automotive\\Views\\automotive/service/jobs", $view_data);
    }

    /* load appointment add/edit modal */
    function appointment_modal_form() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");

        if ($id) {
            $appointment_info = $Automotive_service_appointments_model->get_one($id);
            $view_data['model_info'] = $appointment_info;
        }

        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        return $this->template->view('Automotive\\Views\\automotive/service/appointment_modal_form', $view_data);
    }

    /* save appointment */
    function save_appointment() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");

        $data = array(
            "client_id" => $this->request->getPost('client_id'),
            "vehicle_info" => $this->request->getPost('vehicle_info'),
            "appointment_date" => $this->request->getPost('appointment_date'),
            "appointment_time" => $this->request->getPost('appointment_time'),
            "service_type" => $this->request->getPost('service_type'),
            "description" => $this->request->getPost('description'),
            "assigned_to" => $this->request->getPost('assigned_to'),
            "status" => $this->request->getPost('status'),
            "notes" => $this->request->getPost('notes'),
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $Automotive_service_appointments_model->ci_save($data, $id);

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

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");

        if ($Automotive_service_appointments_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of appointments, prepared for datatable */
    function appointments_list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_appointments", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "client_id" => $this->request->getPost("client_id"),
            "assigned_to" => $this->request->getPost("assigned_to"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("automotive_service_appointments", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");
        $list_data = $Automotive_service_appointments_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_appointment_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of appointment list table */
    private function _appointment_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_appointments", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");
        $data = $Automotive_service_appointments_model->get_details($options)->getRow();
        return $this->_make_appointment_row($data, $custom_fields);
    }

    /* prepare a row of appointment list table */
    private function _make_appointment_row($data, $custom_fields) {
        $client_link = anchor(get_uri("clients/view/" . $data->client_id), $data->client_name);
        
        $status_class = "bg-secondary";
        if ($data->status == "scheduled") {
            $status_class = "bg-info";
        } else if ($data->status == "in_progress") {
            $status_class = "bg-warning";
        } else if ($data->status == "completed") {
            $status_class = "bg-success";
        } else if ($data->status == "cancelled") {
            $status_class = "bg-danger";
        }

        $status = "<span class='badge $status_class'>" . app_lang($data->status) . "</span>";

        $row_data = array(
            $client_link,
            $data->vehicle_info,
            format_to_date($data->appointment_date, false) . " " . $data->appointment_time,
            $data->service_type,
            $data->assigned_to_user ? $data->assigned_to_user : "-",
            $status
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_service/appointment_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_appointment'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_appointment'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_service/delete_appointment"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load service job add/edit modal */
    function job_modal_form() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");

        if ($id) {
            $job_info = $Automotive_service_jobs_model->get_one($id);
            $view_data['model_info'] = $job_info;
        }

        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        return $this->template->view('Automotive\\Views\\automotive/service/job_modal_form', $view_data);
    }

    /* save service job */
    function save_job() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");

        $labor_hours = unformat_currency($this->request->getPost('labor_hours'));
        $labor_rate = unformat_currency($this->request->getPost('labor_rate'));
        $parts_cost = unformat_currency($this->request->getPost('parts_cost'));
        $total_cost = ($labor_hours * $labor_rate) + $parts_cost;

        $data = array(
            "client_id" => $this->request->getPost('client_id'),
            "appointment_id" => $this->request->getPost('appointment_id'),
            "vehicle_info" => $this->request->getPost('vehicle_info'),
            "service_description" => $this->request->getPost('service_description'),
            "labor_hours" => $labor_hours,
            "labor_rate" => $labor_rate,
            "parts_cost" => $parts_cost,
            "total_cost" => $total_cost,
            "assigned_to" => $this->request->getPost('assigned_to'),
            "start_date" => $this->request->getPost('start_date'),
            "completion_date" => $this->request->getPost('completion_date'),
            "status" => $this->request->getPost('status'),
            "notes" => $this->request->getPost('notes'),
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["job_number"] = $Automotive_service_jobs_model->generate_job_number();
        }

        $save_id = $Automotive_service_jobs_model->ci_save($data, $id);

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

        if (!$this->can_manage_automotive()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");

        if ($Automotive_service_jobs_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of service jobs, prepared for datatable */
    function jobs_list_data() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_jobs", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "client_id" => $this->request->getPost("client_id"),
            "assigned_to" => $this->request->getPost("assigned_to"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("automotive_service_jobs", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");
        $list_data = $Automotive_service_jobs_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_job_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of job list table */
    private function _job_row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("automotive_service_jobs", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");
        $data = $Automotive_service_jobs_model->get_details($options)->getRow();
        return $this->_make_job_row($data, $custom_fields);
    }

    /* prepare a row of job list table */
    private function _make_job_row($data, $custom_fields) {
        $job_number = modal_anchor(get_uri("automotive_service/view_job/" . $data->id), $data->job_number, array("title" => app_lang('job_details')));
        $client_link = anchor(get_uri("clients/view/" . $data->client_id), $data->client_name);
        
        $status_class = "bg-secondary";
        if ($data->status == "pending") {
            $status_class = "bg-warning";
        } else if ($data->status == "in_progress") {
            $status_class = "bg-info";
        } else if ($data->status == "completed") {
            $status_class = "bg-success";
        } else if ($data->status == "invoiced") {
            $status_class = "bg-primary";
        }

        $status = "<span class='badge $status_class'>" . app_lang($data->status) . "</span>";

        $row_data = array(
            $job_number,
            $client_link,
            $data->vehicle_info,
            to_currency($data->total_cost),
            $data->assigned_to_user ? $data->assigned_to_user : "-",
            $status
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = modal_anchor(get_uri("automotive_service/job_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_job'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_job'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("automotive_service/delete_job"), "data-action" => "delete-confirmation"));

        return $row_data;
    }

    /* load service job details view */
    function view_job($id = 0) {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        if ($id) {
            $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");
            $job_info = $Automotive_service_jobs_model->get_details(array("id" => $id))->getRow();

            if ($job_info) {
                $view_data['job_info'] = $job_info;
                $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("automotive_service_jobs", $id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

                return $this->template->view('Automotive\\Views\\automotive/service/view_job', $view_data);
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

    /* get team members dropdown */
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $members_dropdown = array(array("id" => "", "text" => "- " . app_lang("team_member") . " -"));
        foreach ($team_members as $key => $value) {
            $members_dropdown[] = array("id" => $key, "text" => $value);
        }
        return json_encode($members_dropdown);
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