# Web-Based Installer - No Database Access Required!

## 🎉 Easy Installation

You don't need database access! Just follow these simple steps:

### Step 1: Access the Installer
Open your browser and go to:
```
http://your-crm-url/automotive_installer
```

### Step 2: Click Install
Click the **"Install Automotive Module"** button on the page.

### Step 3: Done!
The installer will automatically:
- ✅ Create all 7 database tables
- ✅ Set up proper indexes and relationships
- ✅ Configure everything for you

### Step 4: Start Using
After installation completes, click **"Open Automotive Dashboard"** or navigate to:
```
http://your-crm-url/automotive
```

## 🔧 Features of the Installer

### What It Does
- **Automatic Database Setup** - Creates all tables automatically
- **Safety Checks** - Won't reinstall if already installed
- **Error Handling** - Shows clear error messages if something goes wrong
- **One-Click Install** - No manual SQL scripts needed
- **Uninstall Option** - Can remove the module if needed

### Installation Process
1. Checks if module is already installed
2. Creates 7 database tables:
   - automotive_trade_ins
   - automotive_deposits
   - automotive_floor_stock
   - automotive_service_appointments
   - automotive_service_jobs
   - automotive_parts
   - automotive_parts_sales
3. Sets up all indexes and constraints
4. Confirms successful installation

## 🚀 Quick Start After Installation

Once installed, you can access:

- **Dashboard:** `/automotive`
- **Trade-ins:** `/automotive_trade_ins`
- **Deposits:** `/automotive_deposits`
- **Floor Stock:** `/automotive_floor_stock`
- **Service Appointments:** `/automotive_service/appointments`
- **Service Jobs:** `/automotive_service/jobs`
- **Parts Inventory:** `/automotive_parts`
- **Parts Sales:** `/automotive_parts/sales`

## ⚠️ Important Notes

### Before Installation
- Make sure you're logged in as an administrator
- Ensure your database user has CREATE TABLE permissions
- Backup your database (recommended)

### After Installation
- Clear your browser cache
- Configure permissions in Settings → Roles
- Add custom fields if needed in Settings → Custom Fields

## 🆘 Troubleshooting

### Installer Page Not Loading?
- Clear cache: `rm -rf writable/cache/*`
- Check if the controller file exists: `app/Controllers/Automotive_installer.php`
- Check if the view file exists: `app/Views/automotive_installer/index.php`

### Installation Failed?
- Check database user permissions
- Check error logs in `writable/logs/`
- Ensure database connection is working
- Try accessing: `/automotive_installer` again

### Already Installed Message?
- If you see "Already Installed" but tables don't exist:
  1. Use the uninstall button
  2. Then install again

### Permission Denied After Installation?
- Go to Settings → Team Members → Roles
- Edit your role and enable automotive permissions

## 🔄 Uninstalling

If you need to uninstall:

1. Go to: `http://your-crm-url/automotive_installer`
2. Click **"Uninstall Module"**
3. Confirm the action (WARNING: This deletes all data!)

## 📚 Full Documentation

For complete feature documentation, see:
- **AUTOMOTIVE_MODULE_README.md** - Complete features
- **QUICK_START.md** - Quick start guide
- **INSTALLATION_STEPS.md** - Detailed manual installation (if needed)

## ✅ Success Checklist

After installation:
- [ ] Installer shows "Installed" status
- [ ] Can access `/automotive` dashboard
- [ ] Can create a test record
- [ ] No errors in browser console
- [ ] No errors in PHP logs

---

**That's it!** No database access needed. Just click install and you're ready to go! 🚀