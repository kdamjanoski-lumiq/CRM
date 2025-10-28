<?php

namespace App\Models;

class Automotive_deposits_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_deposits';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_deposits_table = $this->db->prefixTable('automotive_deposits');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $payment_methods_table = $this->db->prefixTable('payment_methods');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_deposits_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $automotive_deposits_table.client_id=$client_id";
        }

        $invoice_id = $this->_get_clean_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $automotive_deposits_table.invoice_id=$invoice_id";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_deposits_table.status='$status'";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_deposits_table.payment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $automotive_deposits_table.*, 
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS client_name,
                $invoices_table.id as invoice_number,
                $payment_methods_table.title as payment_method,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user
                FROM $automotive_deposits_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_deposits_table.client_id
                LEFT JOIN $invoices_table ON $invoices_table.id = $automotive_deposits_table.invoice_id
                LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $automotive_deposits_table.payment_method_id
                LEFT JOIN $users_table ON $users_table.id = $automotive_deposits_table.created_by
                WHERE $automotive_deposits_table.deleted=0 $where
                ORDER BY $automotive_deposits_table.payment_date DESC";

        return $this->db->query($sql);
    }

    function get_total_deposits_by_invoice($invoice_id) {
        $automotive_deposits_table = $this->db->prefixTable('automotive_deposits');
        
        $sql = "SELECT SUM(amount) as total_deposits
                FROM $automotive_deposits_table
                WHERE invoice_id = $invoice_id 
                AND status = 'confirmed'
                AND deleted = 0";
        
        $result = $this->db->query($sql)->getRow();
        return $result ? $result->total_deposits : 0;
    }

    function get_summary_stats($options = array()) {
        $automotive_deposits_table = $this->db->prefixTable('automotive_deposits');
        
        $where = "WHERE deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND (payment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) as confirmed_count,
                SUM(CASE WHEN status='refunded' THEN 1 ELSE 0 END) as refunded_count,
                SUM(CASE WHEN status='confirmed' THEN amount ELSE 0 END) as total_confirmed_amount,
                SUM(CASE WHEN status='pending' THEN amount ELSE 0 END) as total_pending_amount
                FROM $automotive_deposits_table
                $where";

        return $this->db->query($sql)->getRow();
    }
}