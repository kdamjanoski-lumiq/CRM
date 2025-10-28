-- Automotive Module Installation Script
-- This script creates all necessary tables for the automotive dealership module

-- Table: automotive_trade_ins
CREATE TABLE IF NOT EXISTS `automotive_trade_ins` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(11) UNSIGNED NOT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `vehicle_make` varchar(100) NOT NULL,
  `vehicle_model` varchar(100) NOT NULL,
  `vehicle_year` int(4) NOT NULL,
  `vehicle_vin` varchar(17) DEFAULT NULL,
  `vehicle_type` enum('caravan','motorhome','trailer','camper','other') DEFAULT 'caravan',
  `mileage` int(11) DEFAULT NULL,
  `condition_rating` enum('excellent','good','fair','poor') DEFAULT 'good',
  `trade_in_value` decimal(15,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` enum('pending','approved','completed','rejected') DEFAULT 'pending',
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_deposits
CREATE TABLE IF NOT EXISTS `automotive_deposits` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL,
  `deposit_amount` decimal(15,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','refunded') DEFAULT 'pending',
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `client_id` (`client_id`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_floor_stock
CREATE TABLE IF NOT EXISTS `automotive_floor_stock` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_number` varchar(50) NOT NULL UNIQUE,
  `vehicle_type` enum('caravan','motorhome','trailer','camper','other') DEFAULT 'caravan',
  `make` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `vin` varchar(17) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT 0.00,
  `selling_price` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` enum('available','reserved','sold','in_service') DEFAULT 'available',
  `location` varchar(100) DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `date_sold` date DEFAULT NULL,
  `sold_to_client_id` int(11) UNSIGNED DEFAULT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `stock_number` (`stock_number`),
  KEY `status` (`status`),
  KEY `vehicle_type` (`vehicle_type`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_service_appointments
CREATE TABLE IF NOT EXISTS `automotive_service_appointments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(11) UNSIGNED NOT NULL,
  `vehicle_info` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) UNSIGNED DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `appointment_date` (`appointment_date`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_service_jobs
CREATE TABLE IF NOT EXISTS `automotive_service_jobs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_number` varchar(50) NOT NULL UNIQUE,
  `appointment_id` int(11) UNSIGNED DEFAULT NULL,
  `client_id` int(11) UNSIGNED NOT NULL,
  `vehicle_info` varchar(255) NOT NULL,
  `service_description` text NOT NULL,
  `labor_hours` decimal(10,2) DEFAULT 0.00,
  `labor_rate` decimal(10,2) DEFAULT 0.00,
  `parts_cost` decimal(15,2) DEFAULT 0.00,
  `total_cost` decimal(15,2) DEFAULT 0.00,
  `assigned_to` int(11) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','invoiced') DEFAULT 'pending',
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `job_number` (`job_number`),
  KEY `client_id` (`client_id`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_parts
CREATE TABLE IF NOT EXISTS `automotive_parts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `part_number` varchar(100) NOT NULL UNIQUE,
  `part_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT 0.00,
  `selling_price` decimal(15,2) DEFAULT 0.00,
  `quantity_in_stock` int(11) DEFAULT 0,
  `reorder_level` int(11) DEFAULT 0,
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `part_number` (`part_number`),
  KEY `category` (`category`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: automotive_parts_sales
CREATE TABLE IF NOT EXISTS `automotive_parts_sales` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_number` varchar(50) NOT NULL UNIQUE,
  `part_id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `service_job_id` int(11) UNSIGNED DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(15,2) DEFAULT 0.00,
  `total_price` decimal(15,2) DEFAULT 0.00,
  `sale_date` date NOT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sale_number` (`sale_number`),
  KEY `part_id` (`part_id`),
  KEY `client_id` (`client_id`),
  KEY `service_job_id` (`service_job_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;