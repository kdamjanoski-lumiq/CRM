<?php

namespace Automotive\Models;

class Automotive_parts_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'automotive_parts';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $automotive_parts_table.id=$id";
        }

        $part_number = $this->_get_clean_value($options, "part_number");
        if ($part_number) {
            $where .= " AND $automotive_parts_table.part_number='$part_number'";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $automotive_parts_table.status='$status'";
        }

        $category = $this->_get_clean_value($options, "category");
        if ($category) {
            $where .= " AND $automotive_parts_table.category='$category'";
        }

        $low_stock = $this->_get_clean_value($options, "low_stock");
        if ($low_stock) {
            $where .= " AND $automotive_parts_table.quantity_in_stock <= $automotive_parts_table.minimum_stock_level";
        }

        $search = $this->_get_clean_value($options, "search");
        if ($search) {
            $where .= " AND ($automotive_parts_table.part_number LIKE '%$search%' 
                        OR $automotive_parts_table.part_name LIKE '%$search%' 
                        OR $automotive_parts_table.manufacturer LIKE '%$search%')";
        }

        $sql = "SELECT $automotive_parts_table.*, 
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS created_by_user,
                ($automotive_parts_table.selling_price - $automotive_parts_table.unit_cost) as profit_margin,
                ($automotive_parts_table.quantity_in_stock * $automotive_parts_table.unit_cost) as total_value
                FROM $automotive_parts_table
                LEFT JOIN $users_table ON $users_table.id = $automotive_parts_table.created_by
                WHERE $automotive_parts_table.deleted=0 $where
                ORDER BY $automotive_parts_table.part_name";

        return $this->db->query($sql);
    }

    function get_summary_stats($options = array()) {
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        
        $where = "WHERE deleted=0";

        $sql = "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN status='active' THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status='discontinued' THEN 1 ELSE 0 END) as discontinued_count,
                SUM(CASE WHEN status='out_of_stock' THEN 1 ELSE 0 END) as out_of_stock_count,
                SUM(CASE WHEN quantity_in_stock <= minimum_stock_level AND status='active' THEN 1 ELSE 0 END) as low_stock_count,
                SUM(quantity_in_stock * unit_cost) as total_inventory_value,
                SUM(quantity_in_stock) as total_quantity
                FROM $automotive_parts_table
                $where";

        return $this->db->query($sql)->getRow();
    }

    function get_categories() {
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        
        $sql = "SELECT DISTINCT category 
                FROM $automotive_parts_table 
                WHERE deleted=0 AND category IS NOT NULL AND category != ''
                ORDER BY category";

        return $this->db->query($sql);
    }

    function is_part_number_exists($part_number, $id = 0) {
        $result = $this->get_all_where(array("part_number" => $part_number, "deleted" => 0));
        if ($result->getRow()) {
            if ($id && $result->getRow()->id == $id) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function update_stock($part_id, $quantity_change) {
        $automotive_parts_table = $this->db->prefixTable('automotive_parts');
        
        $sql = "UPDATE $automotive_parts_table 
                SET quantity_in_stock = quantity_in_stock + ($quantity_change)
                WHERE id = $part_id";
        
        return $this->db->query($sql);
    }
}