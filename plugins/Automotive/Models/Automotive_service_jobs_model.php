<?php

namespace Automotive\Models;

class Automotive_service_jobs_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_service_jobs';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_service_jobs_table = $this->db->prefixTable('automotive_service_jobs');
        $automotive_service_appointments_table = $this->db->prefixTable('automotive_service_appointments');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $users_table = $this->db->prefixTable('users');
        $assigned_users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_service_jobs_table.id=$id";
        }

        $job_number = $this->_get_clean_value($options, "job_number");
        if ($job_number) {
            $where .= " AND $automotive_service_jobs_table.job_number='$job_number'";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $automotive_service_jobs_table.client_id=$client_id";
        }

        $appointment_id = $this->_get_clean_value($options, "appointment_id");
        if ($appointment_id) {
            $where .= " AND $automotive_service_jobs_table.appointment_id=$appointment_id";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_service_jobs_table.status='$status'";
        }

        $assigned_to = $this->_get_clean_value($options, "assigned_to");
        if ($assigned_to) {
            $where .= " AND $automotive_service_jobs_table.assigned_to=$assigned_to";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_service_jobs_table.start_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $automotive_service_jobs_table.*, 
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS client_name,
                $invoices_table.id as invoice_number,
                CONCAT($assigned_users_table.first_name, ' ', $assigned_users_table.last_name) AS assigned_to_user,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user,
                $automotive_service_appointments_table.appointment_date
                FROM $automotive_service_jobs_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_service_jobs_table.client_id
                LEFT JOIN $invoices_table ON $invoices_table.id = $automotive_service_jobs_table.invoice_id
                LEFT JOIN $assigned_users_table ON $assigned_users_table.id = $automotive_service_jobs_table.assigned_to
                LEFT JOIN $users_table ON $users_table.id = $automotive_service_jobs_table.created_by
                LEFT JOIN $automotive_service_appointments_table ON $automotive_service_appointments_table.id = $automotive_service_jobs_table.appointment_id
                WHERE $automotive_service_jobs_table.deleted=0 $where
                ORDER BY $automotive_service_jobs_table.created_at DESC";

        return $this->db->query($sql);
    }

    function get_summary_stats($options = array()) {
        $automotive_service_jobs_table = $this->db->prefixTable('automotive_service_jobs');
        
        $where = "WHERE deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND (start_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status='in_progress' THEN 1 ELSE 0 END) as in_progress_count,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status='invoiced' THEN 1 ELSE 0 END) as invoiced_count,
                SUM(labor_cost) as total_labor_cost,
                SUM(parts_cost) as total_parts_cost,
                SUM(total_cost) as total_revenue
                FROM $automotive_service_jobs_table
                $where";

        return $this->db->query($sql)->getRow();
    }

    function get_next_job_number() {
        $automotive_service_jobs_table = $this->db->prefixTable('automotive_service_jobs');
        
        $sql = "SELECT job_number FROM $automotive_service_jobs_table 
                WHERE deleted=0 
                ORDER BY id DESC LIMIT 1";
        
        $result = $this->db->query($sql)->getRow();
        
        if ($result && $result->job_number) {
            // Extract number from job_number (e.g., "SJ-00001" -> 1)
            $number = (int) filter_var($result->job_number, FILTER_SANITIZE_NUMBER_INT);
            $next_number = $number + 1;
            return "SJ-" . str_pad($next_number, 5, "0", STR_PAD_LEFT);
        } else {
            return "SJ-00001";
        }
    }

    function is_job_number_exists($job_number, $id = 0) {
        $result = $this->get_all_where(array("job_number" => $job_number, "deleted" => 0));
        if ($result->getRow()) {
            if ($id && $result->getRow()->id == $id) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}