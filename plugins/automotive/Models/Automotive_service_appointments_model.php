<?php

namespace App\Models;

class Automotive_service_appointments_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_service_appointments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_service_appointments_table = $this->db->prefixTable('automotive_service_appointments');
        $clients_table = $this->db->prefixTable('clients');
        $users_table = $this->db->prefixTable('users');
        $assigned_users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_service_appointments_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $automotive_service_appointments_table.client_id=$client_id";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_service_appointments_table.status='$status'";
        }

        $assigned_to = $this->_get_clean_value($options, "assigned_to");
        if ($assigned_to) {
            $where .= " AND $automotive_service_appointments_table.assigned_to=$assigned_to";
        }

        $service_type = $this->_get_clean_value($options, "service_type");
        if ($service_type) {
            $where .= " AND $automotive_service_appointments_table.service_type='$service_type'";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_service_appointments_table.appointment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $appointment_date = $this->_get_clean_value($options, "appointment_date");
        if ($appointment_date) {
            $where .= " AND $automotive_service_appointments_table.appointment_date='$appointment_date'";
        }

        $sql = "SELECT $automotive_service_appointments_table.*, 
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS client_name,
                CONCAT($assigned_users_table.first_name, ' ', $assigned_users_table.last_name) AS assigned_to_user,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user
                FROM $automotive_service_appointments_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_service_appointments_table.client_id
                LEFT JOIN $assigned_users_table ON $assigned_users_table.id = $automotive_service_appointments_table.assigned_to
                LEFT JOIN $users_table ON $users_table.id = $automotive_service_appointments_table.created_by
                WHERE $automotive_service_appointments_table.deleted=0 $where
                ORDER BY $automotive_service_appointments_table.appointment_date DESC, $automotive_service_appointments_table.appointment_time DESC";

        return $this->db->query($sql);
    }

    function get_calendar_events($start_date, $end_date, $options = array()) {
        $automotive_service_appointments_table = $this->db->prefixTable('automotive_service_appointments');
        $clients_table = $this->db->prefixTable('clients');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $assigned_to = $this->_get_clean_value($options, "assigned_to");
        if ($assigned_to) {
            $where .= " AND $automotive_service_appointments_table.assigned_to=$assigned_to";
        }

        $sql = "SELECT $automotive_service_appointments_table.id,
                $automotive_service_appointments_table.appointment_date,
                $automotive_service_appointments_table.appointment_time,
                $automotive_service_appointments_table.service_type,
                $automotive_service_appointments_table.status,
                CONCAT($clients_table.company_name, ' - ', $automotive_service_appointments_table.vehicle_registration) AS title,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS assigned_to_user
                FROM $automotive_service_appointments_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_service_appointments_table.client_id
                LEFT JOIN $users_table ON $users_table.id = $automotive_service_appointments_table.assigned_to
                WHERE $automotive_service_appointments_table.deleted=0 
                AND $automotive_service_appointments_table.appointment_date BETWEEN '$start_date' AND '$end_date'
                $where
                ORDER BY $automotive_service_appointments_table.appointment_date, $automotive_service_appointments_table.appointment_time";

        return $this->db->query($sql);
    }

    function get_summary_stats($options = array()) {
        $automotive_service_appointments_table = $this->db->prefixTable('automotive_service_appointments');
        
        $where = "WHERE deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND (appointment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='scheduled' THEN 1 ELSE 0 END) as scheduled_count,
                SUM(CASE WHEN status='in_progress' THEN 1 ELSE 0 END) as in_progress_count,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END) as cancelled_count,
                SUM(estimated_cost) as total_estimated_cost,
                SUM(actual_cost) as total_actual_cost
                FROM $automotive_service_appointments_table
                $where";

        return $this->db->query($sql)->getRow();
    }
}