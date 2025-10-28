<?php

/**
 * Automotive Dealership Plugin - Installation Script
 * 
 * This script is executed when the plugin is installed.
 * It creates all necessary database tables and initial configuration.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Automotive_installer {

    private $ci;

    function __construct() {
        $this->ci = get_instance();
        $this->ci->load->dbforge();
    }

    function install() {
        // Create all automotive tables
        $this->create_trade_ins_table();
        $this->create_deposits_table();
        $this->create_floor_stock_table();
        $this->create_service_appointments_table();
        $this->create_service_jobs_table();
        $this->create_parts_table();
        $this->create_parts_sales_table();

        // Insert default settings
        $this->insert_default_settings();

        return true;
    }

    private function create_trade_ins_table() {
        if (!$this->ci->db->tableExists('automotive_trade_ins')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0
                ),
                'client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'vehicle_make' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ),
                'vehicle_model' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ),
                'vehicle_year' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'vehicle_vin' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'vehicle_registration' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'mileage' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'condition' => array(
                    'type' => 'ENUM',
                    'constraint' => array('excellent', 'good', 'fair', 'poor'),
                    'default' => 'good'
                ),
                'trade_in_value' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('pending', 'approved', 'completed', 'rejected'),
                    'default' => 'pending'
                ),
                'images' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('invoice_id');
            $this->ci->dbforge->addKey('client_id');
            $this->ci->dbforge->addKey('status');
            $this->ci->dbforge->createTable('automotive_trade_ins');
        }
    }

    private function create_deposits_table() {
        if (!$this->ci->db->tableExists('automotive_deposits')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'amount' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2'
                ),
                'payment_date' => array(
                    'type' => 'DATE'
                ),
                'payment_method_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'transaction_reference' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('pending', 'confirmed', 'refunded'),
                    'default' => 'confirmed'
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('invoice_id');
            $this->ci->dbforge->addKey('client_id');
            $this->ci->dbforge->addKey('payment_date');
            $this->ci->dbforge->createTable('automotive_deposits');
        }
    }

    private function create_floor_stock_table() {
        if (!$this->ci->db->tableExists('automotive_floor_stock')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'stock_number' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ),
                'vehicle_type' => array(
                    'type' => 'ENUM',
                    'constraint' => array('caravan', 'motorhome', 'trailer', 'camper', 'other'),
                    'default' => 'caravan'
                ),
                'make' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ),
                'model' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ),
                'year' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'vin' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'registration' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'color' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'mileage' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'purchase_price' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'selling_price' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2'
                ),
                'condition' => array(
                    'type' => 'ENUM',
                    'constraint' => array('new', 'used', 'certified'),
                    'default' => 'used'
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('available', 'reserved', 'sold', 'in_service'),
                    'default' => 'available'
                ),
                'location' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'features' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'images' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'sold_date' => array(
                    'type' => 'DATE',
                    'null' => TRUE
                ),
                'sold_to_client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('stock_number', FALSE, TRUE); // Unique key
            $this->ci->dbforge->addKey('status');
            $this->ci->dbforge->addKey('vehicle_type');
            $this->ci->dbforge->createTable('automotive_floor_stock');
        }
    }

    private function create_service_appointments_table() {
        if (!$this->ci->db->tableExists('automotive_service_appointments')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'vehicle_registration' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'vehicle_make' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'vehicle_model' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'appointment_date' => array(
                    'type' => 'DATE'
                ),
                'appointment_time' => array(
                    'type' => 'TIME'
                ),
                'service_type' => array(
                    'type' => 'ENUM',
                    'constraint' => array('maintenance', 'repair', 'inspection', 'warranty', 'other'),
                    'default' => 'maintenance'
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'assigned_to' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('scheduled', 'in_progress', 'completed', 'cancelled'),
                    'default' => 'scheduled'
                ),
                'estimated_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'actual_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('client_id');
            $this->ci->dbforge->addKey('appointment_date');
            $this->ci->dbforge->addKey('assigned_to');
            $this->ci->dbforge->addKey('status');
            $this->ci->dbforge->createTable('automotive_service_appointments');
        }
    }

    private function create_service_jobs_table() {
        if (!$this->ci->db->tableExists('automotive_service_jobs')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'appointment_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'job_number' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ),
                'vehicle_registration' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'vehicle_make' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'vehicle_model' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'vehicle_vin' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE
                ),
                'mileage' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'work_performed' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'assigned_to' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'start_date' => array(
                    'type' => 'DATE',
                    'null' => TRUE
                ),
                'completion_date' => array(
                    'type' => 'DATE',
                    'null' => TRUE
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('pending', 'in_progress', 'completed', 'invoiced'),
                    'default' => 'pending'
                ),
                'labor_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'parts_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'total_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('job_number', FALSE, TRUE); // Unique key
            $this->ci->dbforge->addKey('client_id');
            $this->ci->dbforge->addKey('appointment_id');
            $this->ci->dbforge->addKey('assigned_to');
            $this->ci->dbforge->addKey('status');
            $this->ci->dbforge->createTable('automotive_service_jobs');
        }
    }

    private function create_parts_table() {
        if (!$this->ci->db->tableExists('automotive_parts')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'part_number' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ),
                'part_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 200
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'category' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'manufacturer' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'supplier' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'quantity_in_stock' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0
                ),
                'minimum_stock_level' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0
                ),
                'unit_cost' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'selling_price' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'location' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ),
                'compatible_vehicles' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'images' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array('active', 'discontinued', 'out_of_stock'),
                    'default' => 'active'
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('part_number', FALSE, TRUE); // Unique key
            $this->ci->dbforge->addKey('status');
            $this->ci->dbforge->addKey('category');
            $this->ci->dbforge->createTable('automotive_parts');
        }
    }

    private function create_parts_sales_table() {
        if (!$this->ci->db->tableExists('automotive_parts_sales')) {
            $this->ci->dbforge->addField(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'part_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'service_job_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'client_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'quantity' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'unit_price' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2'
                ),
                'total_price' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2'
                ),
                'sale_date' => array(
                    'type' => 'DATE'
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at' => array(
                    'type' => 'DATETIME'
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                )
            ));
            $this->ci->dbforge->addKey('id', TRUE);
            $this->ci->dbforge->addKey('part_id');
            $this->ci->dbforge->addKey('service_job_id');
            $this->ci->dbforge->addKey('invoice_id');
            $this->ci->dbforge->addKey('client_id');
            $this->ci->dbforge->addKey('sale_date');
            $this->ci->dbforge->createTable('automotive_parts_sales');
        }
    }

    private function insert_default_settings() {
        // Insert default plugin settings
        $settings = array(
            array(
                'setting_name' => 'module_automotive',
                'setting_value' => '1',
                'type' => 'app'
            ),
            array(
                'setting_name' => 'module_automotive_client',
                'setting_value' => '0',
                'type' => 'app'
            ),
            array(
                'setting_name' => 'automotive_stock_number_prefix',
                'setting_value' => 'STK',
                'type' => 'app'
            ),
            array(
                'setting_name' => 'automotive_job_number_prefix',
                'setting_value' => 'SJ',
                'type' => 'app'
            )
        );

        foreach ($settings as $setting) {
            // Check if setting already exists
            $existing = $this->ci->db->get_where('settings', array('setting_name' => $setting['setting_name']))->row();
            if (!$existing) {
                $this->ci->db->insert('settings', $setting);
            }
        }
    }

    function uninstall() {
        // Drop all automotive tables
        $tables = array(
            'automotive_trade_ins',
            'automotive_deposits',
            'automotive_floor_stock',
            'automotive_service_appointments',
            'automotive_service_jobs',
            'automotive_parts',
            'automotive_parts_sales'
        );

        foreach ($tables as $table) {
            $this->ci->dbforge->dropTable($table, TRUE);
        }

        // Remove settings
        $this->ci->db->where_in('setting_name', array(
            'module_automotive',
            'module_automotive_client',
            'automotive_stock_number_prefix',
            'automotive_job_number_prefix'
        ));
        $this->ci->db->delete('settings');

        return true;
    }
}