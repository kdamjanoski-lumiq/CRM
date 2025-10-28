# Automotive Plugin - Installation Guide

## 🚀 Super Easy Installation

### Step 1: Get the Plugin
Download `Automotive.zip` from the releases or create it from the plugin folder.

### Step 2: Install
1. Login to RISE CRM as **Administrator**
2. Go to **Settings → Plugins**
3. Click **"Install Plugin"** button
4. Choose `Automotive.zip` file
5. Click **"Install"**

### Step 3: Activate
1. After installation completes
2. Click **"Activate"** button
3. Plugin will automatically create all database tables

### Step 4: Use It!
1. Look for **"Automotive"** in the left menu
2. Click to open the dashboard
3. Start managing your dealership!

## ✅ That's It!

No database access needed. No manual SQL scripts. Just upload and activate!

## 📍 What You Get

After activation, you'll have access to:

- **Dashboard** - `/automotive`
- **Trade-ins** - `/automotive_trade_ins`
- **Deposits** - `/automotive_deposits`
- **Floor Stock** - `/automotive_floor_stock`
- **Service Appointments** - `/automotive_service/appointments`
- **Service Jobs** - `/automotive_service/jobs`
- **Parts Inventory** - `/automotive_parts`
- **Parts Sales** - `/automotive_parts/sales`

## 🔧 Post-Installation

### Configure Permissions
1. Go to **Settings → Team Members → Roles**
2. Edit each role
3. Enable automotive permissions as needed

### Add Custom Fields (Optional)
1. Go to **Settings → Custom Fields**
2. Select automotive module
3. Add fields as needed

## 🆘 Troubleshooting

### Plugin Not Showing?
```bash
# Clear cache
rm -rf writable/cache/*
```

### Installation Failed?
- Check database user has CREATE TABLE permission
- Check error logs in `writable/logs/`
- Ensure RISE CRM is version 2.8 or higher

### Menu Not Showing?
- Make sure plugin is **Activated** (not just installed)
- Clear browser cache
- Logout and login again

## 📞 Need Help?

Check the main README.md file for detailed documentation.

---

**Ready to go!** Just upload, activate, and start using! 🎉