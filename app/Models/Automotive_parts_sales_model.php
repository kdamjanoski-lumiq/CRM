<?php

namespace App\Models;

class Automotive_parts_sales_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_parts_sales';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_parts_sales_table = $this->db->prefixTable('automotive_parts_sales');
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        $automotive_service_jobs_table = $this->db->prefixTable('automotive_service_jobs');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_parts_sales_table.id=$id";
        }

        $part_id = $this->_get_clean_value($options, "part_id");
        if ($part_id) {
            $where .= " AND $automotive_parts_sales_table.part_id=$part_id";
        }

        $service_job_id = $this->_get_clean_value($options, "service_job_id");
        if ($service_job_id) {
            $where .= " AND $automotive_parts_sales_table.service_job_id=$service_job_id";
        }

        $invoice_id = $this->_get_clean_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $automotive_parts_sales_table.invoice_id=$invoice_id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $automotive_parts_sales_table.client_id=$client_id";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_parts_sales_table.sale_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $automotive_parts_sales_table.*, 
                $automotive_parts_table.part_number,
                $automotive_parts_table.part_name,
                $automotive_parts_table.unit_cost,
                $automotive_service_jobs_table.job_number,
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS client_name,
                $invoices_table.id as invoice_number,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user,
                ($automotive_parts_sales_table.total_price - ($automotive_parts_table.unit_cost * $automotive_parts_sales_table.quantity)) as profit
                FROM $automotive_parts_sales_table
                LEFT JOIN $automotive_parts_table ON $automotive_parts_table.id = $automotive_parts_sales_table.part_id
                LEFT JOIN $automotive_service_jobs_table ON $automotive_service_jobs_table.id = $automotive_parts_sales_table.service_job_id
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_parts_sales_table.client_id
                LEFT JOIN $invoices_table ON $invoices_table.id = $automotive_parts_sales_table.invoice_id
                LEFT JOIN $users_table ON $users_table.id = $automotive_parts_sales_table.created_by
                WHERE $automotive_parts_sales_table.deleted=0 $where
                ORDER BY $automotive_parts_sales_table.sale_date DESC";

        return $this->db->query($sql);
    }

    function get_summary_stats($options = array()) {
        $automotive_parts_sales_table = $this->db->prefixTable('automotive_parts_sales');
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        
        $where = "WHERE $automotive_parts_sales_table.deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_parts_sales_table.sale_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM($automotive_parts_sales_table.quantity) as total_quantity_sold,
                SUM($automotive_parts_sales_table.total_price) as total_revenue,
                SUM($automotive_parts_table.unit_cost * $automotive_parts_sales_table.quantity) as total_cost,
                SUM($automotive_parts_sales_table.total_price - ($automotive_parts_table.unit_cost * $automotive_parts_sales_table.quantity)) as total_profit
                FROM $automotive_parts_sales_table
                LEFT JOIN $automotive_parts_table ON $automotive_parts_table.id = $automotive_parts_sales_table.part_id
                $where";

        return $this->db->query($sql)->getRow();
    }

    function get_top_selling_parts($limit = 10, $options = array()) {
        $automotive_parts_sales_table = $this->db->prefixTable('automotive_parts_sales');
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        
        $where = "WHERE $automotive_parts_sales_table.deleted=0";
        
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($automotive_parts_sales_table.sale_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT 
                $automotive_parts_table.part_number,
                $automotive_parts_table.part_name,
                SUM($automotive_parts_sales_table.quantity) as total_quantity_sold,
                SUM($automotive_parts_sales_table.total_price) as total_revenue
                FROM $automotive_parts_sales_table
                LEFT JOIN $automotive_parts_table ON $automotive_parts_table.id = $automotive_parts_sales_table.part_id
                $where
                GROUP BY $automotive_parts_sales_table.part_id
                ORDER BY total_quantity_sold DESC
                LIMIT $limit";

        return $this->db->query($sql);
    }
}