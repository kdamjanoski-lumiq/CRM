<?php

namespace App\Controllers;

class Automotive extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("automotive");
    }

    /* load automotive dashboard */
    function index() {
        $this->check_module_availability("module_automotive");

        if ($this->login_user->user_type === "staff") {
            if (!$this->can_view_automotive()) {
                app_redirect("forbidden");
            }

            $view_data['page_type'] = "full";
            return $this->template->rander("automotive/index", $view_data);
        } else {
            // Client view
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            return $this->template->rander("automotive/client_view", $view_data);
        }
    }

    /* load settings page */
    function settings() {
        $this->check_module_availability("module_automotive");

        if (!$this->login_user->is_admin) {
            app_redirect("forbidden");
        }

        $view_data['page_type'] = "full";
        return $this->template->rander("automotive/settings/index", $view_data);
    }

    /* get dashboard statistics */
    function get_dashboard_stats() {
        $this->check_module_availability("module_automotive");

        if (!$this->can_view_automotive()) {
            app_redirect("forbidden");
        }

        // Get statistics from all modules
        $Automotive_floor_stock_model = model("App\Models\Automotive_floor_stock_model");
        $Automotive_service_appointments_model = model("App\Models\Automotive_service_appointments_model");
        $Automotive_service_jobs_model = model("App\Models\Automotive_service_jobs_model");
        $Automotive_parts_model = model("App\Models\Automotive_parts_model");
        $Automotive_trade_ins_model = model("App\Models\Automotive_trade_ins_model");
        $Automotive_deposits_model = model("App\Models\Automotive_deposits_model");

        $stats = array();

        // Floor Stock Stats
        $floor_stock_stats = $Automotive_floor_stock_model->get_summary_stats();
        $stats['floor_stock'] = array(
            'total' => $floor_stock_stats->total_count,
            'available' => $floor_stock_stats->available_count,
            'reserved' => $floor_stock_stats->reserved_count,
            'sold' => $floor_stock_stats->sold_count,
            'total_value' => $floor_stock_stats->total_available_value
        );

        // Service Stats
        $today = date('Y-m-d');
        $end_of_month = date('Y-m-t');
        $service_stats = $Automotive_service_appointments_model->get_summary_stats(array(
            'start_date' => $today,
            'end_date' => $end_of_month
        ));
        $stats['service'] = array(
            'scheduled' => $service_stats->scheduled_count,
            'in_progress' => $service_stats->in_progress_count,
            'completed' => $service_stats->completed_count
        );

        // Service Jobs Stats
        $jobs_stats = $Automotive_service_jobs_model->get_summary_stats();
        $stats['service_jobs'] = array(
            'pending' => $jobs_stats->pending_count,
            'in_progress' => $jobs_stats->in_progress_count,
            'completed' => $jobs_stats->completed_count,
            'total_revenue' => $jobs_stats->total_revenue
        );

        // Parts Stats
        $parts_stats = $Automotive_parts_model->get_summary_stats();
        $stats['parts'] = array(
            'total' => $parts_stats->total_count,
            'active' => $parts_stats->active_count,
            'low_stock' => $parts_stats->low_stock_count,
            'inventory_value' => $parts_stats->total_inventory_value
        );

        // Trade-ins Stats
        $trade_ins_stats = $Automotive_trade_ins_model->get_summary_stats();
        $stats['trade_ins'] = array(
            'pending' => $trade_ins_stats->pending_count,
            'approved' => $trade_ins_stats->approved_count,
            'total_value' => $trade_ins_stats->total_approved_value
        );

        // Deposits Stats
        $deposits_stats = $Automotive_deposits_model->get_summary_stats();
        $stats['deposits'] = array(
            'pending' => $deposits_stats->pending_count,
            'confirmed' => $deposits_stats->confirmed_count,
            'total_amount' => $deposits_stats->total_confirmed_amount
        );

        echo json_encode($stats);
    }

    /* check if user can view automotive module */
    private function can_view_automotive() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "automotive")) {
                return true;
            }
        } else {
            // Client access
            if (get_setting("module_automotive_client")) {
                return true;
            }
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