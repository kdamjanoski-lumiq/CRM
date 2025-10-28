# Automotive Dealership Plugin for RISE CRM

A comprehensive automotive dealership management plugin for RISE CRM, specifically designed for managing caravans, motorhomes, trailers, and campers.

## Features

### 🚗 Trade-In Management
- Record and track vehicle trade-ins
- Link to invoices for automatic value deduction
- Approval workflow (pending → approved → completed → rejected)
- Vehicle condition assessment
- Image upload support
- VIN and registration tracking

### 💰 Deposits Tracking
- Record deposits against invoices
- Multiple payment methods support
- Transaction reference tracking
- Status management (pending, confirmed, refunded)
- Automatic invoice balance calculation

### 📦 Floor Stock Inventory
- Comprehensive showroom inventory management
- Support for caravans, motorhomes, trailers, campers
- Stock status tracking (available, reserved, sold, in service)
- Purchase and selling price management
- Image galleries
- Stock number generation
- Sales integration with invoices

### 🔧 Service Department
- **Appointments**: Calendar-based booking system
- **Service Jobs**: Complete work order management
- Technician assignment
- Labor and parts cost tracking
- Job status workflow
- Invoice generation from completed jobs

### 🔩 Parts Inventory & Sales
- Complete parts catalog
- Stock level management with low stock alerts
- Supplier and manufacturer tracking
- Parts sales with profit calculation
- Integration with service jobs
- Category management

## Installation

### Method 1: Via RISE CRM Plugin Manager (Recommended)

1. Download the plugin ZIP file
2. Log in to RISE CRM as Administrator
3. Go to **Settings** > **Plugins**
4. Click **Upload Plugin**
5. Select the downloaded ZIP file
6. Click **Install**
7. The plugin will automatically:
   - Create all database tables
   - Configure default settings
   - Add menu items
   - Set up permissions

### Method 2: Manual Installation

1. Extract the plugin files
2. Upload the `automotive` folder to `plugins/` directory in your RISE CRM installation
3. Go to **Settings** > **Plugins**
4. Find "Automotive Dealership" and click **Install**

## Configuration

After installation:

1. Go to **Settings** > **Plugins** > **Automotive Dealership** > **Settings**
2. Configure:
   - Enable/disable module
   - Client portal access
   - Stock number prefix
   - Service job number prefix

## Permissions

Configure user permissions in **Settings** > **Roles**:

- **Automotive Module** - Main module access
- **Trade-Ins Management** - Manage trade-ins
- **Deposits Management** - Manage deposits
- **Floor Stock Management** - Manage inventory
- **Service Department** - Manage service operations
- **Parts Management** - Manage parts inventory

Admins have full access by default.

## Usage

### Trade-Ins
1. Navigate to **Automotive** > **Trade-Ins**
2. Click **Add Trade-In**
3. Fill in vehicle details
4. Link to invoice (optional)
5. Set status and value
6. Save

### Deposits
1. Go to **Automotive** > **Deposits**
2. Click **Add Deposit**
3. Select client and invoice
4. Enter amount and payment details
5. Save

### Floor Stock
1. Access **Automotive** > **Floor Stock**
2. Click **Add Vehicle**
3. Enter vehicle details
4. Set pricing
5. Upload images
6. Save

### Service Appointments
1. Navigate to **Automotive** > **Service** > **Appointments**
2. Click **Schedule Appointment**
3. Select client and date/time
4. Enter vehicle and service details
5. Assign technician
6. Save

### Service Jobs
1. Go to **Automotive** > **Service** > **Jobs**
2. Click **Create Job**
3. Link to appointment (optional)
4. Enter job details
5. Track labor and parts
6. Update status as work progresses
7. Generate invoice when complete

### Parts Management
1. Access **Automotive** > **Parts**
2. Click **Add Part**
3. Enter part details
4. Set stock levels and pricing
5. Save

## Custom Fields

Add custom fields to any component:
1. Go to **Settings** > **Custom Fields**
2. Select context (e.g., automotive_trade_ins)
3. Create fields as needed

## Integration

### Invoice Integration
- Trade-ins automatically deduct from invoice totals
- Deposits track against invoices
- Floor stock sales create invoices
- Service jobs generate invoices
- Parts sales link to invoices

### Client Integration
- All data links to client records
- Client dashboard shows automotive data
- Service history per client

## Support

For support and documentation:
- Check the included documentation files
- Review RISE CRM forums
- Contact plugin support

## Requirements

- RISE CRM v3.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher

## Version

**Version**: 1.0.0  
**Author**: NinjaTech AI  
**License**: Compatible with RISE CRM license

## Changelog

### Version 1.0.0
- Initial release
- Trade-in management
- Deposits tracking
- Floor stock inventory
- Service department
- Parts inventory & sales
- Dashboard and reporting
- Custom fields support
- Permission system