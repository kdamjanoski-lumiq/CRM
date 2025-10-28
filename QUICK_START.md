# Automotive Module - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Step 1: Install Database (Choose One Method)

**Method A: Direct SQL (Fastest)**
```bash
cd /path/to/CRM
mysql -u your_username -p your_database < install_automotive_module.sql
```

**Method B: phpMyAdmin**
1. Open phpMyAdmin
2. Select your RISE CRM database
3. Click "Import" → Choose `install_automotive_module.sql` → Click "Go"

**Method C: CodeIgniter Migration**
```bash
cd /path/to/CRM
php spark migrate
```

### Step 2: Clear Cache
```bash
rm -rf writable/cache/*
```

### Step 3: Access the Module
Open your browser and navigate to:
```
http://your-crm-url/automotive
```

You should see the Automotive Dashboard! 🎉

## 📋 Quick Test

### Test 1: Add a Vehicle to Floor Stock
1. Go to: `http://your-crm-url/automotive_floor_stock`
2. Click "Add Floor Stock"
3. Fill in:
   - Stock Number: `TEST001`
   - Vehicle Type: `Caravan`
   - Make: `Jayco`
   - Model: `Eagle`
   - Year: `2023`
   - Purchase Price: `45000`
   - Selling Price: `55000`
4. Click "Save"
5. ✅ Record should appear in the list

### Test 2: Add a Trade-In
1. Go to: `http://your-crm-url/automotive_trade_ins`
2. Click "Add Trade-in"
3. Fill in:
   - Client: Select any client
   - Vehicle Make: `Toyota`
   - Vehicle Model: `Camper`
   - Vehicle Year: `2020`
   - Trade-in Value: `15000`
4. Click "Save"
5. ✅ Record should appear in the list

### Test 3: View Dashboard Statistics
1. Go to: `http://your-crm-url/automotive`
2. ✅ You should see statistics update with your test data

## 🎯 What You Get

### 7 Complete Modules
1. **Dashboard** - Real-time statistics and quick actions
2. **Trade-ins** - Vehicle trade-in management
3. **Deposits** - Invoice deposit tracking
4. **Floor Stock** - Showroom inventory management
5. **Service Appointments** - Service scheduling
6. **Service Jobs** - Work order management
7. **Parts** - Inventory and sales tracking

### Key Features
✅ Full CRUD operations on all modules
✅ DataTables with search, filter, sort
✅ Export to Excel
✅ Print functionality
✅ Custom fields support
✅ Permission-based access control
✅ Mobile responsive design
✅ Integration with clients and invoices

## 📍 Direct URLs

Access modules directly:
- Dashboard: `/automotive`
- Trade-ins: `/automotive_trade_ins`
- Deposits: `/automotive_deposits`
- Floor Stock: `/automotive_floor_stock`
- Service Appointments: `/automotive_service/appointments`
- Service Jobs: `/automotive_service/jobs`
- Parts Inventory: `/automotive_parts`
- Parts Sales: `/automotive_parts/sales`

## ⚙️ Configuration (Optional)

### Add to Menu
1. Go to Settings → Menu
2. Add menu items for automotive modules
3. Set permissions per role

### Configure Permissions
1. Go to Settings → Team Members → Roles
2. Edit each role
3. Enable automotive permissions as needed

### Add Custom Fields
1. Go to Settings → Custom Fields
2. Select module (e.g., "Automotive Floor Stock")
3. Add custom fields as needed

## 🆘 Troubleshooting

### Module Not Loading?
```bash
# Clear cache
rm -rf writable/cache/*

# Check if tables exist
mysql -u username -p database_name -e "SHOW TABLES LIKE 'automotive_%';"
```

### Permission Denied?
- Login as Administrator
- Check user role has automotive permissions
- Clear cache and logout/login

### 404 Error?
- Check .htaccess file exists
- Verify mod_rewrite is enabled
- Clear cache

## 📚 Full Documentation

For detailed information, see:
- **AUTOMOTIVE_MODULE_README.md** - Complete feature documentation
- **INSTALLATION_STEPS.md** - Detailed installation guide
- **AUTOMOTIVE_MODULE_SUMMARY.md** - Project overview

## ✅ Success Checklist

After installation, verify:
- [ ] Dashboard loads at `/automotive`
- [ ] Can add a floor stock item
- [ ] Can add a trade-in
- [ ] Can add a deposit
- [ ] Can add a service appointment
- [ ] Can add a part
- [ ] Statistics display correctly
- [ ] No errors in browser console
- [ ] No errors in PHP logs

## 🎓 Next Steps

1. **Import Sample Data** - Create test records in each module
2. **Configure Permissions** - Set up role-based access
3. **Add Custom Fields** - Customize for your needs
4. **Train Users** - Show team how to use the module
5. **Go Live** - Start using in production!

## 💡 Pro Tips

- Use stock numbers that match your existing system
- Link trade-ins to invoices for automatic deduction
- Set reorder levels on parts for low stock alerts
- Assign service jobs to technicians for tracking
- Use custom fields for business-specific data

---

**Need Help?** Check the full documentation in:
- AUTOMOTIVE_MODULE_README.md
- INSTALLATION_STEPS.md

**Ready to Go?** Start with Step 1 above! 🚀