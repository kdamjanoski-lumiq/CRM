# Automotive Dealership Management Plugin for RISE CRM

## 🚗 Complete Automotive Dealership Solution

A comprehensive plugin for managing automotive dealerships specializing in caravans, motorhomes, trailers, and campers.

## ✨ Features

### 1. Trade-In Management
- Record vehicle trade-ins with complete details
- Link trade-ins to invoices for automatic value deduction
- Approval workflow (pending → approved → completed → rejected)
- Support for multiple vehicle types
- Condition rating system
- Image upload support

### 2. Deposit Tracking
- Record and track deposits against invoices
- Multiple payment methods support
- Transaction reference tracking
- Status management (pending/confirmed/refunded)
- Payment date tracking

### 3. Floor Stock Inventory
- Complete showroom inventory management
- Stock status tracking (available/reserved/sold/in service)
- Purchase and selling price management
- Stock number validation
- Vehicle details (make, model, year, VIN, color, mileage)
- Location tracking
- Date acquired and sold tracking

### 4. Service Appointments
- Schedule service appointments
- Assign to technicians
- Track appointment status
- Service type categorization
- Notes and descriptions

### 5. Service Jobs
- Work order management
- Job number auto-generation
- Labor hours and rate tracking
- Parts cost tracking
- Total cost calculation
- Status workflow (pending/in_progress/completed/invoiced)
- Link to invoices

### 6. Parts Inventory
- Complete parts catalog
- Stock quantity management
- Low stock alerts (reorder level)
- Supplier and manufacturer tracking
- Cost and selling price tracking
- Category management

### 7. Parts Sales
- Track parts sales
- Automatic stock updates
- Sale number auto-generation
- Link to service jobs and clients
- Invoice integration

### 8. Dashboard
- Real-time statistics
- Floor stock summary
- Service appointments count
- Active jobs count
- Trade-ins tracking
- Deposits tracking
- Low stock alerts
- Total inventory value
- Quick action buttons

## 📦 Installation

### Method 1: Via RISE CRM Plugin Manager (Recommended)

1. **Download the Plugin**
   - Download `Automotive.zip` from releases

2. **Install via Plugin Manager**
   - Login to RISE CRM as Administrator
   - Go to **Settings → Plugins**
   - Click **"Install Plugin"**
   - Upload `Automotive.zip`
   - Click **"Install"**

3. **Activate the Plugin**
   - After installation, click **"Activate"**
   - The plugin will automatically create all database tables

4. **Start Using**
   - Navigate to **Automotive** in the left menu
   - You'll see the dashboard with all modules!

### Method 2: Manual Installation

1. **Extract Plugin**
   ```bash
   unzip Automotive.zip -d /path/to/rise-crm/plugins/
   ```

2. **Set Permissions**
   ```bash
   chmod -R 755 plugins/Automotive
   ```

3. **Install via Plugin Manager**
   - Go to **Settings → Plugins**
   - Find "Automotive Dealership Management"
   - Click **"Install"** then **"Activate"**

## 🎯 Usage

### Accessing Modules

After installation, you'll find the Automotive menu in the left sidebar with these options:

- **Dashboard** - Overview and statistics
- **Trade-ins** - Manage vehicle trade-ins
- **Deposits** - Track invoice deposits
- **Floor Stock** - Showroom inventory
- **Service Appointments** - Schedule appointments
- **Service Jobs** - Work order management
- **Parts Inventory** - Parts catalog
- **Parts Sales** - Track parts sales

### Quick Start

1. **Add Floor Stock**
   - Go to Floor Stock
   - Click "Add Floor Stock"
   - Fill in vehicle details
   - Set selling price
   - Save

2. **Record a Trade-In**
   - Go to Trade-ins
   - Click "Add Trade-in"
   - Select client
   - Enter vehicle details
   - Set trade-in value
   - Link to invoice (optional)
   - Save

3. **Schedule Service**
   - Go to Service Appointments
   - Click "Add Appointment"
   - Select client
   - Set date and time
   - Assign technician
   - Save

4. **Create Service Job**
   - Go to Service Jobs
   - Click "Add Job"
   - Enter service details
   - Add labor and parts costs
   - Track progress
   - Generate invoice when complete

## 🔧 Configuration

### Permissions

Configure user permissions in **Settings → Team Members → Roles**:

- View Automotive
- Manage Trade-ins
- Manage Deposits
- Manage Floor Stock
- Manage Service
- Manage Parts

### Custom Fields

Add custom fields in **Settings → Custom Fields**:

- Select module (e.g., "Automotive Floor Stock")
- Add fields as needed
- Fields will appear in forms automatically

## 📊 Database Tables

The plugin creates 7 tables:

1. `automotive_trade_ins` - Trade-in records
2. `automotive_deposits` - Deposit tracking
3. `automotive_floor_stock` - Inventory
4. `automotive_service_appointments` - Appointments
5. `automotive_service_jobs` - Service jobs
6. `automotive_parts` - Parts catalog
7. `automotive_parts_sales` - Parts sales

## 🔄 Updates

To update the plugin:

1. Backup your database
2. Go to **Settings → Plugins**
3. Find "Automotive Dealership Management"
4. Click **"Update"** if available
5. Follow update instructions

## 🗑️ Uninstallation

To uninstall the plugin:

1. **Backup Your Data First!**
2. Go to **Settings → Plugins**
3. Find "Automotive Dealership Management"
4. Click **"Deactivate"**
5. Click **"Uninstall"**
6. Confirm the action

**Warning:** Uninstalling will delete all automotive data permanently!

## 🔐 Security

- Permission-based access control
- SQL injection prevention
- XSS protection
- CSRF protection
- Input validation
- Secure session management

## 🆘 Troubleshooting

### Plugin Not Showing in Menu
- Clear cache: Delete `writable/cache/*`
- Check if plugin is activated
- Verify user has permissions

### Installation Failed
- Check database user permissions
- Check error logs in `writable/logs/`
- Ensure RISE CRM version is 2.8 or higher

### Permission Denied
- Go to Settings → Roles
- Enable automotive permissions for your role
- Logout and login again

## 📚 Support

For issues or questions:
- Check documentation
- Review error logs
- Contact plugin author

## 📝 Changelog

### Version 1.0.0 (2024-01-27)
- Initial release
- Trade-in management
- Deposit tracking
- Floor stock inventory
- Service appointments and jobs
- Parts inventory and sales
- Dashboard with statistics
- Full CRUD operations
- Custom fields support
- Permission system integration

## 👨‍💻 Developer

**Lumiq Development**
- GitHub: https://github.com/kdamjanoski-lumiq

## 📄 License

This plugin follows the same license as RISE CRM.

## 🙏 Credits

Built for RISE CRM - The Ultimate Project Manager & CRM
- Website: https://codecanyon.net/item/rise-ultimate-project-manager/15455641

---

**Version:** 1.0.0  
**Requires:** RISE CRM 2.8 or higher  
**Last Updated:** January 27, 2024