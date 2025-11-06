# Automotive Plugin - Complete Installation Guide

## 🎉 You Now Have a Proper RISE CRM Plugin!

The automotive module has been converted into a **proper RISE CRM plugin** that follows all official guidelines and best practices.

## 📦 What's Included

### Plugin Structure
```
plugins/Automotive/
├── index.php                    # Plugin metadata and hooks
├── README.md                    # Complete documentation
├── INSTALLATION.md              # Quick installation guide
├── Controllers/                 # 6 controllers
│   ├── Automotive.php
│   ├── Automotive_trade_ins.php
│   ├── Automotive_deposits.php
│   ├── Automotive_floor_stock.php
│   ├── Automotive_service.php
│   └── Automotive_parts.php
├── Models/                      # 7 models
│   ├── Automotive_trade_ins_model.php
│   ├── Automotive_deposits_model.php
│   ├── Automotive_floor_stock_model.php
│   ├── Automotive_service_appointments_model.php
│   ├── Automotive_service_jobs_model.php
│   ├── Automotive_parts_model.php
│   └── Automotive_parts_sales_model.php
├── Views/                       # 17 view files
│   └── automotive/
│       ├── index.php
│       ├── trade_ins/
│       ├── deposits/
│       ├── floor_stock/
│       ├── service/
│       └── parts/
├── Language/                    # Language support
│   └── english/
│       └── automotive_lang.php
├── Config/                      # Routes configuration
│   └── Routes.php
└── Libraries/                   # For future extensions
```

### Plugin ZIP File
- **Location:** `CRM/plugins/Automotive.zip`
- **Size:** ~100KB
- **Ready to install!**

## 🚀 Installation Methods

### Method 1: Via Plugin Manager (Easiest!)

1. **Login to RISE CRM**
   - Login as Administrator

2. **Go to Plugin Manager**
   - Navigate to: **Settings → Plugins**

3. **Install Plugin**
   - Click **"Install Plugin"** button
   - Upload `Automotive.zip`
   - Click **"Install"**

4. **Activate Plugin**
   - After installation, click **"Activate"**
   - Database tables will be created automatically

5. **Done!**
   - Look for "Automotive" in the left menu
   - Start using the module!

### Method 2: Manual Installation

1. **Extract Plugin**
   ```bash
   cd /path/to/rise-crm/plugins/
   unzip Automotive.zip
   ```

2. **Set Permissions**
   ```bash
   chmod -R 755 Automotive/
   ```

3. **Activate via Plugin Manager**
   - Go to **Settings → Plugins**
   - Find "Automotive Dealership Management"
   - Click **"Install"** then **"Activate"**

## ✨ Features

### Automatic Installation
- ✅ Creates all 7 database tables automatically
- ✅ Sets up proper indexes and relationships
- ✅ No manual SQL scripts needed
- ✅ Safe installation with error handling

### Menu Integration
- ✅ Automatically adds "Automotive" to left menu
- ✅ Includes all 8 sub-menu items
- ✅ Proper icon (truck) and positioning

### Plugin Hooks
- ✅ Installation hook - Creates database tables
- ✅ Uninstallation hook - Cleans up tables
- ✅ Activation hook - Ready for future features
- ✅ Deactivation hook - Ready for future features
- ✅ Menu filter hook - Adds menu items

### Security
- ✅ Directory access protection
- ✅ Proper namespacing
- ✅ Permission-based access control
- ✅ SQL injection prevention
- ✅ XSS protection

## 📋 Post-Installation

### 1. Configure Permissions
```
Settings → Team Members → Roles → Edit Role
Enable automotive permissions:
- View Automotive
- Manage Trade-ins
- Manage Deposits
- Manage Floor Stock
- Manage Service
- Manage Parts
```

### 2. Add Custom Fields (Optional)
```
Settings → Custom Fields
Select module (e.g., "Automotive Floor Stock")
Add custom fields as needed
```

### 3. Test the Plugin
- Navigate to `/automotive`
- Try creating a test record in each module
- Verify all features work correctly

## 🎯 Accessing the Plugin

After installation, access via:

- **Dashboard:** `/automotive`
- **Trade-ins:** `/automotive_trade_ins`
- **Deposits:** `/automotive_deposits`
- **Floor Stock:** `/automotive_floor_stock`
- **Service Appointments:** `/automotive_service/appointments`
- **Service Jobs:** `/automotive_service/jobs`
- **Parts Inventory:** `/automotive_parts`
- **Parts Sales:** `/automotive_parts/sales`

## 🔄 Updates

To update the plugin in the future:

1. Backup your database
2. Go to **Settings → Plugins**
3. Find "Automotive Dealership Management"
4. Click **"Update"** (when available)
5. Follow update instructions

## 🗑️ Uninstallation

To uninstall:

1. **BACKUP YOUR DATA FIRST!**
2. Go to **Settings → Plugins**
3. Find "Automotive Dealership Management"
4. Click **"Deactivate"**
5. Click **"Uninstall"**
6. Confirm (this will delete all data!)

## 🆘 Troubleshooting

### Plugin Not Showing in List
```bash
# Clear cache
rm -rf writable/cache/*

# Check plugin exists
ls -la plugins/Automotive/
```

### Installation Failed
- Check database user has CREATE TABLE permission
- Check error logs: `writable/logs/`
- Ensure RISE CRM version is 2.8+

### Menu Not Appearing
- Verify plugin is **Activated** (not just installed)
- Clear browser cache
- Logout and login again
- Check user has permissions

### Database Tables Not Created
- Check installation logs
- Verify database connection
- Try deactivate and reactivate

## 📊 Database Tables Created

The plugin creates these tables:

1. `automotive_trade_ins` - Trade-in records
2. `automotive_deposits` - Deposit tracking
3. `automotive_floor_stock` - Inventory management
4. `automotive_service_appointments` - Service scheduling
5. `automotive_service_jobs` - Work orders
6. `automotive_parts` - Parts catalog
7. `automotive_parts_sales` - Parts sales tracking

All tables include:
- Proper indexes for performance
- Foreign key relationships
- Soft delete support
- Created/updated timestamps

## 🔐 Security Features

- Permission-based access control
- SQL injection prevention (using query builder)
- XSS protection (input sanitization)
- CSRF protection (CodeIgniter built-in)
- Secure file handling
- Directory access protection

## 📚 Documentation

Complete documentation available in:

- **README.md** - Full feature documentation
- **INSTALLATION.md** - Quick installation guide
- **This file** - Complete installation guide

## ✅ Success Checklist

After installation, verify:

- [ ] Plugin shows as "Active" in Settings → Plugins
- [ ] "Automotive" menu appears in left sidebar
- [ ] Can access `/automotive` dashboard
- [ ] Can create a test record
- [ ] All 7 database tables exist
- [ ] No errors in browser console
- [ ] No errors in PHP logs

## 🎓 Next Steps

1. **Configure Permissions** - Set up role-based access
2. **Add Custom Fields** - Customize for your needs
3. **Import Data** - If migrating from another system
4. **Train Users** - Show team how to use the module
5. **Go Live** - Start managing your dealership!

## 💡 Pro Tips

- Use stock numbers that match your existing system
- Link trade-ins to invoices for automatic deduction
- Set reorder levels on parts for low stock alerts
- Assign service jobs to technicians for tracking
- Use custom fields for business-specific data

## 📞 Support

For issues or questions:
- Check the README.md file
- Review error logs
- Check RISE CRM documentation
- Contact plugin author

## 🎉 You're Ready!

The plugin is now a **proper RISE CRM plugin** that:
- ✅ Follows all official guidelines
- ✅ Uses proper namespacing
- ✅ Includes all required hooks
- ✅ Has automatic installation
- ✅ Integrates seamlessly with RISE CRM
- ✅ Is ready for distribution

**Just upload `Automotive.zip` and activate!** 🚀

---

**Plugin Version:** 1.0.0  
**RISE CRM Version Required:** 2.8+  
**Last Updated:** January 27, 2024  
**Author:** Lumiq Development