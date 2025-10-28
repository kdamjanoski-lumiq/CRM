# Automotive Dealership Plugin - Installation & Setup Guide

## 📦 Plugin Package Structure

```
automotive/
├── plugin.json                 # Plugin configuration
├── install.php                 # Installation script
├── README.md                   # User documentation
├── PLUGIN_INSTALLATION.md      # This file
├── Controllers/                # Plugin controllers
│   ├── Automotive.php
│   ├── Automotive_trade_ins.php
│   └── Automotive_deposits.php
├── Models/                     # Plugin models
│   ├── Automotive_trade_ins_model.php
│   ├── Automotive_deposits_model.php
│   ├── Automotive_floor_stock_model.php
│   ├── Automotive_service_appointments_model.php
│   ├── Automotive_service_jobs_model.php
│   ├── Automotive_parts_model.php
│   └── Automotive_parts_sales_model.php
├── Views/                      # Plugin views (to be created)
├── Language/                   # Language files
│   └── english/
│       └── automotive_lang.php
└── Config/                     # Plugin configuration
    └── Routes.php
```

## 🚀 Installation Methods

### Method 1: Via RISE CRM Plugin Manager (Recommended)

1. **Prepare the Plugin**
   - Ensure all files are in a folder named `automotive`
   - Create a ZIP file of the `automotive` folder
   - Name it `automotive.zip`

2. **Upload to RISE CRM**
   - Log in to RISE CRM as Administrator
   - Navigate to **Settings** > **Plugins**
   - Click **Upload Plugin** button
   - Select `automotive.zip`
   - Click **Upload**

3. **Install the Plugin**
   - After upload, find "Automotive Dealership" in the plugin list
   - Click **Install** button
   - Wait for installation to complete
   - The plugin will automatically:
     * Create 7 database tables
     * Insert default settings
     * Configure permissions
     * Add menu items

4. **Verify Installation**
   - Check that "Automotive" appears in the left menu
   - Go to **Settings** > **Plugins** and verify status is "Active"
   - Check **Settings** > **Roles** for automotive permissions

### Method 2: Manual Installation

1. **Upload Files**
   ```bash
   # Via FTP/SFTP
   Upload the 'automotive' folder to:
   /path/to/rise-crm/plugins/
   ```

2. **Set Permissions**
   ```bash
   chmod -R 755 plugins/automotive
   chown -R www-data:www-data plugins/automotive
   ```

3. **Install via Admin Panel**
   - Log in to RISE CRM as Administrator
   - Go to **Settings** > **Plugins**
   - Find "Automotive Dealership"
   - Click **Install**

## ⚙️ Configuration

### 1. Basic Settings

After installation, configure the plugin:

1. Go to **Settings** > **Plugins**
2. Find "Automotive Dealership"
3. Click **Settings**
4. Configure:
   - **Enable Automotive Module**: Yes/No
   - **Enable Client Portal Access**: Yes/No (allow clients to view their data)
   - **Stock Number Prefix**: Default "STK" (e.g., STK-00001)
   - **Service Job Number Prefix**: Default "SJ" (e.g., SJ-00001)

### 2. Permissions Setup

Configure user access:

1. Go to **Settings** > **Roles**
2. Select a role (e.g., "Manager", "Staff")
3. Find "Automotive" section
4. Set permissions:
   - **Automotive Module**: All / Specific / None
   - **Trade-Ins Management**: All / Specific / None
   - **Deposits Management**: All / Specific / None
   - **Floor Stock Management**: All / Specific / None
   - **Service Department**: All / Specific / None
   - **Parts Management**: All / Specific / None

**Note**: Administrators automatically have full access to all features.

### 3. Custom Fields (Optional)

Add custom fields to any component:

1. Go to **Settings** > **Custom Fields**
2. Click **Add Field**
3. Select context:
   - `automotive_trade_ins`
   - `automotive_deposits`
   - `automotive_floor_stock`
   - `automotive_service_appointments`
   - `automotive_service_jobs`
   - `automotive_parts`
4. Configure field type and options
5. Save

## 🔍 Verification Checklist

After installation, verify:

- [ ] Plugin appears in **Settings** > **Plugins** as "Active"
- [ ] "Automotive" menu appears in left navigation
- [ ] All submenu items are visible (Dashboard, Trade-Ins, Deposits, etc.)
- [ ] Database tables created (check via phpMyAdmin):
  - `automotive_trade_ins`
  - `automotive_deposits`
  - `automotive_floor_stock`
  - `automotive_service_appointments`
  - `automotive_service_jobs`
  - `automotive_parts`
  - `automotive_parts_sales`
- [ ] Settings are accessible
- [ ] Permissions are configurable in Roles
- [ ] No PHP errors in error log

## 🔧 Troubleshooting

### Plugin Not Appearing

**Problem**: Plugin doesn't show in plugin list

**Solutions**:
1. Check folder name is exactly `automotive`
2. Verify `plugin.json` exists and is valid JSON
3. Check file permissions (755 for folders, 644 for files)
4. Clear RISE CRM cache
5. Check PHP error logs

### Installation Fails

**Problem**: Installation process fails

**Solutions**:
1. Check database user has CREATE TABLE permissions
2. Verify PHP version is 7.4 or higher
3. Check MySQL version is 5.7 or higher
4. Review PHP error logs for specific errors
5. Ensure no table name conflicts

### Menu Not Appearing

**Problem**: Automotive menu doesn't show

**Solutions**:
1. Verify plugin is installed (not just uploaded)
2. Check user has automotive permissions
3. Clear browser cache
4. Check `plugin.json` menu configuration
5. Verify module is enabled in settings

### Database Tables Not Created

**Problem**: Tables missing after installation

**Solutions**:
1. Check database user permissions
2. Manually run `install.php` script
3. Check for SQL errors in logs
4. Verify table prefix matches RISE CRM configuration
5. Check for existing tables with same names

### Permission Issues

**Problem**: Users can't access features

**Solutions**:
1. Verify plugin is enabled in settings
2. Check user role has automotive permissions
3. Ensure user is not restricted
4. Verify permission configuration in Roles
5. Check if module is enabled for clients (if applicable)

## 📊 Database Schema

The plugin creates 7 tables:

1. **automotive_trade_ins** - Trade-in vehicles
2. **automotive_deposits** - Invoice deposits
3. **automotive_floor_stock** - Showroom inventory
4. **automotive_service_appointments** - Service bookings
5. **automotive_service_jobs** - Work orders
6. **automotive_parts** - Parts catalog
7. **automotive_parts_sales** - Parts transactions

All tables include:
- Primary key `id`
- Soft delete flag `deleted`
- Created by/at tracking
- Proper indexes for performance

## 🔄 Updating the Plugin

To update to a newer version:

1. **Backup First**
   - Backup database (especially automotive_* tables)
   - Backup plugin files

2. **Update Process**
   - Go to **Settings** > **Plugins**
   - Find "Automotive Dealership"
   - Click **Uninstall** (data is preserved)
   - Upload new version
   - Click **Install**

3. **Verify Update**
   - Check version number
   - Test functionality
   - Verify data integrity

## 🗑️ Uninstalling the Plugin

**Warning**: Uninstalling will delete all automotive data!

1. **Backup Data** (Important!)
   ```sql
   -- Backup all automotive tables
   mysqldump -u username -p database_name 
   automotive_trade_ins 
   automotive_deposits 
   automotive_floor_stock 
   automotive_service_appointments 
   automotive_service_jobs 
   automotive_parts 
   automotive_parts_sales > automotive_backup.sql
   ```

2. **Uninstall**
   - Go to **Settings** > **Plugins**
   - Find "Automotive Dealership"
   - Click **Uninstall**
   - Confirm action

3. **What Gets Removed**
   - All 7 database tables
   - Plugin settings
   - Menu items
   - Permissions configuration
   - Plugin files (if deleted)

## 📞 Support

### Getting Help

1. **Documentation**
   - Review README.md
   - Check RISE CRM documentation
   - Review plugin source code

2. **Common Issues**
   - Check troubleshooting section above
   - Review PHP error logs
   - Check database error logs
   - Verify RISE CRM version compatibility

3. **Reporting Issues**
   When reporting issues, include:
   - RISE CRM version
   - PHP version
   - MySQL version
   - Plugin version
   - Error messages
   - Steps to reproduce

## 🔐 Security Notes

- Plugin follows RISE CRM security standards
- All controllers extend Security_Controller
- Permission checks on every action
- SQL injection protection via query builder
- XSS protection on all inputs
- CSRF protection enabled

## 📈 Performance

- Database tables are properly indexed
- Efficient SQL queries with joins
- Pagination support for large datasets
- Optimized for production use

## ✅ Post-Installation Checklist

- [ ] Plugin installed successfully
- [ ] All tables created
- [ ] Settings configured
- [ ] Permissions set up
- [ ] Menu items visible
- [ ] Test basic functionality:
  - [ ] Create a trade-in
  - [ ] Create a deposit
  - [ ] Add floor stock
  - [ ] Schedule appointment
  - [ ] Create service job
  - [ ] Add part
- [ ] Custom fields working (if configured)
- [ ] User permissions working
- [ ] No errors in logs

## 🎉 Next Steps

After successful installation:

1. **Configure Settings** - Set up prefixes and options
2. **Set Permissions** - Configure role-based access
3. **Add Custom Fields** - If needed for your business
4. **Train Users** - Show team how to use the module
5. **Start Using** - Begin managing your automotive operations

---

**Plugin Version**: 1.0.0  
**Minimum RISE CRM Version**: 3.0  
**Last Updated**: 2024