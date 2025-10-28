# Automotive Dealership Module for RISE CRM

## Overview
This module adds comprehensive automotive dealership management functionality to RISE CRM, specifically designed for managing caravans, motorhomes, trailers, and campers.

## Features

### 1. Trade-In Management
- Record vehicle trade-ins with full details (make, model, year, VIN, condition)
- Link trade-ins to invoices for automatic value deduction
- Approval workflow (pending → approved → completed → rejected)
- Image upload support
- Custom fields support

### 2. Deposit Tracking
- Record deposits against invoices
- Multiple payment methods support
- Transaction reference tracking
- Status management (pending, confirmed, refunded)
- Automatic balance calculation

### 3. Floor Stock Inventory
- Manage showroom inventory for multiple vehicle types
- Stock status tracking (available, reserved, sold, in service)
- Purchase and selling price management
- Stock number generation and validation
- Image galleries and sales integration

### 4. Service Department
- **Appointments**: Schedule service appointments with calendar integration
- **Service Jobs**: Manage service jobs with job number generation
- Technician assignment
- Labor and parts cost tracking
- Invoice generation from completed jobs

### 5. Parts Inventory & Sales
- Complete parts catalog with stock management
- Low stock alerts
- Supplier and manufacturer tracking
- Parts sales with automatic stock updates
- Profit margin calculation
- Integration with service jobs

## Installation

### Step 1: Database Setup
Run the SQL installation script to create all necessary tables:

```bash
mysql -u your_username -p your_database_name < install_automotive_module.sql
```

Or import via phpMyAdmin:
1. Open phpMyAdmin
2. Select your RISE CRM database
3. Go to "Import" tab
4. Choose `install_automotive_module.sql`
5. Click "Go"

### Step 2: Run Database Migration (Alternative)
If using CodeIgniter migrations:

```bash
php spark migrate
```

### Step 3: Clear Cache
Clear the application cache:

```bash
# From CRM root directory
rm -rf writable/cache/*
```

### Step 4: Set Permissions
Ensure proper file permissions:

```bash
chmod -R 755 app/Controllers/Automotive*.php
chmod -R 755 app/Models/Automotive*.php
chmod -R 755 app/Views/automotive/
```

### Step 5: Enable Module
1. Login as Administrator
2. Go to Settings → Modules
3. Enable "Automotive Module"

### Step 6: Configure Permissions
1. Go to Settings → Team Members → Roles
2. Edit roles and enable automotive permissions:
   - View Automotive
   - Manage Trade-ins
   - Manage Deposits
   - Manage Floor Stock
   - Manage Service
   - Manage Parts

## Module Structure

```
CRM/
├── app/
│   ├── Controllers/
│   │   ├── Automotive.php                    # Main dashboard
│   │   ├── Automotive_trade_ins.php          # Trade-ins management
│   │   ├── Automotive_deposits.php           # Deposits management
│   │   ├── Automotive_floor_stock.php        # Floor stock management
│   │   ├── Automotive_service.php            # Service appointments & jobs
│   │   └── Automotive_parts.php              # Parts inventory & sales
│   │
│   ├── Models/
│   │   ├── Automotive_trade_ins_model.php
│   │   ├── Automotive_deposits_model.php
│   │   ├── Automotive_floor_stock_model.php
│   │   ├── Automotive_service_appointments_model.php
│   │   ├── Automotive_service_jobs_model.php
│   │   ├── Automotive_parts_model.php
│   │   └── Automotive_parts_sales_model.php
│   │
│   ├── Views/
│   │   └── automotive/
│   │       ├── index.php                     # Dashboard
│   │       ├── trade_ins/
│   │       │   ├── index.php
│   │       │   └── modal_form.php
│   │       ├── deposits/
│   │       │   ├── index.php
│   │       │   └── modal_form.php
│   │       ├── floor_stock/
│   │       │   ├── index.php
│   │       │   └── modal_form.php
│   │       ├── service/
│   │       │   ├── appointments.php
│   │       │   ├── appointment_modal_form.php
│   │       │   ├── jobs.php
│   │       │   └── job_modal_form.php
│   │       └── parts/
│   │           ├── index.php
│   │           ├── modal_form.php
│   │           ├── sales.php
│   │           └── sale_modal_form.php
│   │
│   ├── Language/
│   │   └── english/
│   │       └── custom_lang.php               # Automotive language strings
│   │
│   └── Database/
│       └── Migrations/
│           └── 2024-01-27-000001_AddAutomotiveTables.php
│
└── install_automotive_module.sql             # Direct SQL installation

```

## Usage Guide

### Dashboard
Access the automotive dashboard at: `/automotive`

The dashboard displays:
- Total floor stock count
- Available vehicles
- Scheduled service appointments
- Active service jobs
- Pending trade-ins
- Pending deposits
- Low stock parts alerts
- Total inventory value

### Trade-Ins
1. Navigate to Automotive → Trade-ins
2. Click "Add Trade-in"
3. Fill in vehicle details and trade-in value
4. Link to an invoice (optional)
5. Set status (pending/approved/completed/rejected)

### Deposits
1. Navigate to Automotive → Deposits
2. Click "Add Deposit"
3. Select client and invoice
4. Enter deposit amount and payment details
5. Track deposit status

### Floor Stock
1. Navigate to Automotive → Floor Stock
2. Click "Add Floor Stock"
3. Enter vehicle details and pricing
4. Set stock status (available/reserved/sold/in service)
5. Track inventory and sales

### Service Appointments
1. Navigate to Automotive → Service → Appointments
2. Click "Add Appointment"
3. Select client and enter vehicle info
4. Set appointment date/time and service type
5. Assign to technician

### Service Jobs
1. Navigate to Automotive → Service → Jobs
2. Click "Add Job"
3. Enter service description
4. Add labor hours, rate, and parts cost
5. Track job status and generate invoice

### Parts Inventory
1. Navigate to Automotive → Parts → Inventory
2. Click "Add Part"
3. Enter part details and pricing
4. Set stock quantity and reorder level
5. Track low stock alerts

### Parts Sales
1. Navigate to Automotive → Parts → Sales
2. Click "Add Sale"
3. Select part and quantity
4. Enter client (optional)
5. Stock automatically updates

## Database Schema

### Tables Created
- `automotive_trade_ins` - Vehicle trade-in records
- `automotive_deposits` - Invoice deposit tracking
- `automotive_floor_stock` - Showroom inventory
- `automotive_service_appointments` - Service scheduling
- `automotive_service_jobs` - Work order management
- `automotive_parts` - Parts catalog
- `automotive_parts_sales` - Parts sales transactions

### Key Relationships
- Trade-ins → Clients, Invoices
- Deposits → Clients, Invoices
- Floor Stock → Clients (when sold), Invoices
- Service Appointments → Clients, Users (assigned)
- Service Jobs → Clients, Appointments, Invoices
- Parts Sales → Parts, Clients, Service Jobs, Invoices

## Custom Fields Support
All modules support custom fields. Add custom fields via:
Settings → Custom Fields → Select "Automotive [Module Name]"

## Permissions
The module includes granular permissions:
- `automotive` - View automotive module
- `automotive_trade_ins` - Manage trade-ins
- `automotive_deposits` - Manage deposits
- `automotive_floor_stock` - Manage floor stock
- `automotive_service` - Manage service
- `automotive_parts` - Manage parts

## API Integration
All controllers follow RISE CRM's REST API patterns. Access via:
- `/automotive/get_dashboard_stats` - Dashboard statistics
- `/automotive_trade_ins/list_data` - Trade-ins list
- `/automotive_deposits/list_data` - Deposits list
- `/automotive_floor_stock/list_data` - Floor stock list
- `/automotive_service/appointments_list_data` - Appointments list
- `/automotive_service/jobs_list_data` - Service jobs list
- `/automotive_parts/list_data` - Parts list
- `/automotive_parts/sales_list_data` - Parts sales list

## Troubleshooting

### Module Not Showing
1. Clear cache: `rm -rf writable/cache/*`
2. Check database tables are created
3. Verify permissions are set correctly

### Permission Denied
1. Check user role has automotive permissions
2. Verify module is enabled in Settings → Modules

### Database Errors
1. Ensure all tables are created successfully
2. Check database user has proper privileges
3. Verify table names match exactly

## Support & Customization
For customization or support:
1. Review controller code in `app/Controllers/Automotive*.php`
2. Check model methods in `app/Models/Automotive*_model.php`
3. Modify views in `app/Views/automotive/`
4. Add custom language strings in `app/Language/english/custom_lang.php`

## Version History
- v1.0.0 (2024-01-27) - Initial release
  - Trade-in management
  - Deposit tracking
  - Floor stock inventory
  - Service appointments and jobs
  - Parts inventory and sales
  - Dashboard with statistics
  - Full CRUD operations
  - Custom fields support
  - Permission system integration

## License
This module follows the same license as RISE CRM.