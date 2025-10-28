# Automotive Module - Installation & Testing Steps

## Prerequisites
- RISE CRM installed and running
- MySQL/MariaDB database access
- Administrator access to RISE CRM
- PHP 7.4+ with required extensions

## Installation Steps

### Step 1: Backup Your Database
**CRITICAL: Always backup before making changes**

```bash
# Create backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Or use phpMyAdmin Export feature
```

### Step 2: Install Database Tables

**Option A: Using SQL File (Recommended)**
```bash
# From terminal
mysql -u username -p database_name < install_automotive_module.sql

# Enter your MySQL password when prompted
```

**Option B: Using phpMyAdmin**
1. Login to phpMyAdmin
2. Select your RISE CRM database
3. Click "Import" tab
4. Choose file: `install_automotive_module.sql`
5. Click "Go" button
6. Verify all 7 tables are created

**Option C: Using CodeIgniter Migration**
```bash
# From CRM root directory
php spark migrate

# This will run: app/Database/Migrations/2024-01-27-000001_AddAutomotiveTables.php
```

### Step 3: Verify Database Tables
Check that all tables are created:

```sql
SHOW TABLES LIKE 'automotive_%';
```

You should see:
- automotive_trade_ins
- automotive_deposits
- automotive_floor_stock
- automotive_service_appointments
- automotive_service_jobs
- automotive_parts
- automotive_parts_sales

### Step 4: Clear Application Cache
```bash
# From CRM root directory
rm -rf writable/cache/*
rm -rf writable/session/*

# Or via browser
# Navigate to: Settings → System Settings → Clear Cache
```

### Step 5: Verify File Structure
Ensure all files are in place:

```bash
# Check controllers
ls -la app/Controllers/Automotive*.php

# Check models
ls -la app/Models/Automotive*.php

# Check views
ls -la app/Views/automotive/

# Check language file
cat app/Language/english/custom_lang.php | grep automotive
```

### Step 6: Set File Permissions
```bash
# Ensure proper permissions
chmod 755 app/Controllers/Automotive*.php
chmod 755 app/Models/Automotive*.php
chmod -R 755 app/Views/automotive/
```

### Step 7: Access the Module
1. Login to RISE CRM as Administrator
2. Navigate to: `http://your-crm-url/automotive`
3. You should see the Automotive Dashboard

## Testing Checklist

### Basic Functionality Tests

#### 1. Dashboard Access
- [ ] Navigate to `/automotive`
- [ ] Dashboard loads without errors
- [ ] All widgets display (even with 0 values)
- [ ] Quick action buttons are visible

#### 2. Trade-Ins Module
- [ ] Navigate to Automotive → Trade-ins
- [ ] Click "Add Trade-in" button
- [ ] Modal form opens
- [ ] Fill in required fields:
  - Client: Select a client
  - Vehicle Make: "Toyota"
  - Vehicle Model: "Camper"
  - Vehicle Year: "2020"
  - Trade-in Value: "15000"
- [ ] Click Save
- [ ] Record appears in the list
- [ ] Edit the record
- [ ] Delete the record

#### 3. Deposits Module
- [ ] Navigate to Automotive → Deposits
- [ ] Click "Add Deposit"
- [ ] Fill in required fields:
  - Client: Select a client
  - Invoice: Select an invoice
  - Deposit Amount: "5000"
  - Payment Date: Today's date
- [ ] Click Save
- [ ] Record appears in the list
- [ ] Verify deposit status

#### 4. Floor Stock Module
- [ ] Navigate to Automotive → Floor Stock
- [ ] Click "Add Floor Stock"
- [ ] Fill in required fields:
  - Stock Number: "STK001"
  - Vehicle Type: "Caravan"
  - Make: "Jayco"
  - Model: "Eagle"
  - Year: "2023"
  - Purchase Price: "45000"
  - Selling Price: "55000"
- [ ] Click Save
- [ ] Record appears in the list
- [ ] Filter by status
- [ ] Filter by vehicle type

#### 5. Service Appointments
- [ ] Navigate to Automotive → Service → Appointments
- [ ] Click "Add Appointment"
- [ ] Fill in required fields:
  - Client: Select a client
  - Vehicle Info: "2020 Toyota Camper"
  - Appointment Date: Future date
  - Appointment Time: "10:00"
  - Service Type: "Annual Service"
- [ ] Click Save
- [ ] Record appears in the list

#### 6. Service Jobs
- [ ] Navigate to Automotive → Service → Jobs
- [ ] Click "Add Job"
- [ ] Fill in required fields:
  - Client: Select a client
  - Vehicle Info: "2020 Toyota Camper"
  - Service Description: "Annual service and inspection"
  - Labor Hours: "3"
  - Labor Rate: "100"
  - Parts Cost: "250"
- [ ] Click Save
- [ ] Verify job number is auto-generated
- [ ] Verify total cost is calculated

#### 7. Parts Inventory
- [ ] Navigate to Automotive → Parts → Inventory
- [ ] Click "Add Part"
- [ ] Fill in required fields:
  - Part Number: "PART001"
  - Part Name: "Oil Filter"
  - Cost Price: "15"
  - Selling Price: "25"
  - Quantity in Stock: "50"
  - Reorder Level: "10"
- [ ] Click Save
- [ ] Record appears in the list

#### 8. Parts Sales
- [ ] Navigate to Automotive → Parts → Sales
- [ ] Click "Add Sale"
- [ ] Fill in required fields:
  - Part: Select the part created above
  - Quantity: "2"
  - Unit Price: "25"
  - Sale Date: Today's date
- [ ] Click Save
- [ ] Verify stock quantity decreased
- [ ] Verify sale number is auto-generated

### Advanced Testing

#### Data Relationships
- [ ] Create a trade-in linked to an invoice
- [ ] Create a deposit linked to an invoice
- [ ] Create a service job and generate an invoice
- [ ] Verify all relationships work correctly

#### Filtering & Search
- [ ] Test status filters on all modules
- [ ] Test date range filters
- [ ] Test search functionality
- [ ] Test sorting on all columns

#### Permissions Testing
- [ ] Create a new role with limited automotive permissions
- [ ] Assign user to that role
- [ ] Login as that user
- [ ] Verify permission restrictions work

#### Custom Fields
- [ ] Go to Settings → Custom Fields
- [ ] Add custom field to "Automotive Trade-ins"
- [ ] Add custom field to "Automotive Floor Stock"
- [ ] Verify custom fields appear in forms
- [ ] Save data with custom fields
- [ ] Verify custom field data is saved

#### Export & Print
- [ ] Test Excel export on all modules
- [ ] Test print functionality
- [ ] Verify data exports correctly

## Common Issues & Solutions

### Issue 1: Tables Not Created
**Solution:**
```sql
-- Check if tables exist
SHOW TABLES LIKE 'automotive_%';

-- If missing, run SQL file again
SOURCE install_automotive_module.sql;
```

### Issue 2: Permission Denied
**Solution:**
1. Check user role has automotive permissions
2. Clear cache
3. Logout and login again

### Issue 3: Module Not Showing in Menu
**Solution:**
1. Module menu integration requires Settings configuration
2. Access directly via URL: `/automotive`
3. Add menu items manually in Settings → Menu

### Issue 4: 404 Not Found
**Solution:**
1. Clear cache: `rm -rf writable/cache/*`
2. Check .htaccess file exists
3. Verify mod_rewrite is enabled

### Issue 5: Database Connection Error
**Solution:**
1. Check database credentials in `.env` file
2. Verify database user has proper privileges
3. Test database connection

### Issue 6: Dropdown Not Loading
**Solution:**
1. Check if clients/invoices exist in database
2. Verify foreign key relationships
3. Check browser console for JavaScript errors

## Performance Testing

### Load Testing
- [ ] Create 100+ floor stock records
- [ ] Test list loading speed
- [ ] Test filtering performance
- [ ] Test search performance

### Concurrent Users
- [ ] Test with multiple users simultaneously
- [ ] Verify no data conflicts
- [ ] Check for race conditions

## Security Testing

### Input Validation
- [ ] Test SQL injection on all forms
- [ ] Test XSS attacks
- [ ] Test CSRF protection
- [ ] Test file upload restrictions

### Access Control
- [ ] Test unauthorized access attempts
- [ ] Verify permission checks work
- [ ] Test session management

## Final Verification

### Checklist
- [ ] All 7 database tables created
- [ ] All controllers accessible
- [ ] All views render correctly
- [ ] All CRUD operations work
- [ ] Dashboard statistics display
- [ ] Filters and search work
- [ ] Custom fields functional
- [ ] Permissions enforced
- [ ] No PHP errors in logs
- [ ] No JavaScript console errors
- [ ] Mobile responsive design works
- [ ] Export/Print functions work

## Post-Installation

### Configuration
1. **Enable Module** (if using module system)
   - Settings → Modules → Enable "Automotive"

2. **Set Permissions**
   - Settings → Team Members → Roles
   - Configure automotive permissions per role

3. **Add Menu Items** (optional)
   - Settings → Menu
   - Add automotive menu items

4. **Configure Custom Fields**
   - Settings → Custom Fields
   - Add fields as needed

### Training
1. Create sample data for training
2. Document your specific workflows
3. Train staff on module usage
4. Create internal documentation

## Rollback Procedure

If you need to uninstall:

```sql
-- Backup data first
CREATE TABLE automotive_trade_ins_backup AS SELECT * FROM automotive_trade_ins;
CREATE TABLE automotive_deposits_backup AS SELECT * FROM automotive_deposits;
-- ... repeat for all tables

-- Drop tables
DROP TABLE IF EXISTS automotive_parts_sales;
DROP TABLE IF EXISTS automotive_parts;
DROP TABLE IF EXISTS automotive_service_jobs;
DROP TABLE IF EXISTS automotive_service_appointments;
DROP TABLE IF EXISTS automotive_floor_stock;
DROP TABLE IF EXISTS automotive_deposits;
DROP TABLE IF EXISTS automotive_trade_ins;

-- Remove files
rm -rf app/Controllers/Automotive*.php
rm -rf app/Models/Automotive*.php
rm -rf app/Views/automotive/
```

## Support

For issues or questions:
1. Check error logs: `writable/logs/`
2. Enable debug mode in `.env`: `CI_ENVIRONMENT = development`
3. Review this documentation
4. Check RISE CRM documentation

## Success Criteria

Installation is successful when:
✅ All database tables created
✅ Dashboard accessible at `/automotive`
✅ All CRUD operations work
✅ No errors in logs
✅ Permissions enforced correctly
✅ Data relationships work
✅ Custom fields functional

---

**Installation Date:** _____________
**Installed By:** _____________
**Version:** 1.0.0
**Status:** _____________