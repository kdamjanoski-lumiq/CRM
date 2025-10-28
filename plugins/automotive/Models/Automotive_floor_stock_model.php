<?php

namespace App\Models;

class Automotive_floor_stock_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_floor_stock';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_floor_stock_table = $this->db->prefixTable('automotive_floor_stock');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_floor_stock_table.id=$id";
        }

        $stock_number = $this->_get_clean_value($options, "stock_number");
        if ($stock_number) {
            $where .= " AND $automotive_floor_stock_table.stock_number='$stock_number'";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_floor_stock_table.status='$status'";
        }

        $vehicle_type = $this->_get_clean_value($options, "vehicle_type");
        if ($vehicle_type) {
            $where .= " AND $automotive_floor_stock_table.vehicle_type='$vehicle_type'";
        }

        $condition = $this->_get_clean_value($options, "condition");
        if ($condition) {
            $where .= " AND $automotive_floor_stock_table.condition='$condition'";
        }

        $search = $this->_get_clean_value($options, "search");
        if ($search) {
            $where .= " AND ($automotive_floor_stock_table.stock_number LIKE '%$search%' 
                        OR $automotive_floor_stock_table.make LIKE '%$search%' 
                        OR $automotive_floor_stock_table.model LIKE '%$search%'
                        OR $automotive_floor_stock_table.vin LIKE '%$search%')";
        }

        $sql = "SELECT $automotive_floor_stock_table.*, 
                CONCAT($clients_table.company_name, ' ', COALESCE($clients_table.type, '')) AS sold_to_client_name,
                $invoices_table.id as invoice_number,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user
                FROM $automotive_floor_stock_table
                LEFT JOIN $clients_table ON $clients_table.id = $automotive_floor_stock_table.sold_to_client_id
                LEFT JOIN $invoices_table ON $invoices_table.id = $automotive_floor_stock_table.invoice_id
                LEFT JOIN $users_table ON $users_table.id = $automotive_floor_stock_table.created_by
                WHERE $automotive_floor_stock_table.deleted=0 $where
                ORDER BY $automotive_floor_stock_table.created_at DESC";

        return $this->db->query($sql);
    }

    function get_summary_stats($options = array()) {
        $automotive_floor_stock_table = $this->db->prefixTable('automotive_floor_stock');
        
        $where = "WHERE deleted=0";

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='available' THEN 1 ELSE 0 END) as available_count,
                SUM(CASE WHEN status='reserved' THEN 1 ELSE 0 END) as reserved_count,
                SUM(CASE WHEN status='sold' THEN 1 ELSE 0 END) as sold_count,
                SUM(CASE WHEN status='in_service' THEN 1 ELSE 0 END) as in_service_count,
                SUM(CASE WHEN status='available' THEN selling_price ELSE 0 END) as total_available_value,
                SUM(CASE WHEN status='sold' THEN selling_price ELSE 0 END) as total_sold_value,
                SUM(CASE WHEN status='available' THEN purchase_price ELSE 0 END) as total_investment
                FROM $automotive_floor_stock_table
                $where";

        return $this->db->query($sql)->getRow();
    }

    function get_stock_by_type() {
        $automotive_floor_stock_table = $this->db->prefixTable('automotive_floor_stock');
        
        $sql = "SELECT 
                vehicle_type,
                COUNT(*) as count,
                SUM(CASE WHEN status='available' THEN 1 ELSE 0 END) as available_count
                FROM $automotive_floor_stock_table
                WHERE deleted=0
                GROUP BY vehicle_type";

        return $this->db->query($sql);
    }

    function is_stock_number_exists($stock_number, $id = 0) {
        $result = $this->get_all_where(array("stock_number" => $stock_number, "deleted" => 0));
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