<?php

namespace Automotive\Models;

class Automotive_trade_ins_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_trade_ins';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_trade_ins_table = $this->db->prefixTable('automotive_trade_ins');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_trade_ins_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $automotive_trade_ins_table.client_id=$client_id";
        }

        $invoice_id = $this->_get_clean_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $automotive_trade_ins_table.invoice_id=$invoice_id";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_trade_ins_table.status='$status'";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_trade_ins_table.created_at BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $automotive_trade_ins_table.*, 
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS client_name,
                $invoices_table.id as invoice_number,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user
                FROM $automotive_trade_ins_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_trade_ins_table.client_id
                LEFT JOIN $invoices_table ON $invoices_table.id = $automotive_trade_ins_table.invoice_id
                LEFT JOIN $users_table ON $users_table.id = $automotive_trade_ins_table.created_by
                WHERE $automotive_trade_ins_table.deleted=0 $where";

        return $this->db->query($sql);
    }

    function get_trade_in_value_by_invoice($invoice_id) {
        $automotive_trade_ins_table = $this->db->prefixTable('automotive_trade_ins');
        
        $sql = "SELECT SUM(trade_in_value) as total_trade_in_value
                FROM $automotive_trade_ins_table
                WHERE invoice_id = $invoice_id 
                AND status = 'approved'
                AND deleted = 0";
        
        $result = $this->db->query($sql)->getRow();
        return $result ? $result->total_trade_in_value : 0;
    }

    function get_summary_stats($options = array()) {
        $automotive_trade_ins_table = $this->db->prefixTable('automotive_trade_ins');
        
        $where = "WHERE deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND (created_at BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) as rejected_count,
                SUM(CASE WHEN status='approved' THEN trade_in_value ELSE 0 END) as total_approved_value
                FROM $automotive_trade_ins_table
                $where";

        return $this->db->query($sql)->getRow();
    }
}