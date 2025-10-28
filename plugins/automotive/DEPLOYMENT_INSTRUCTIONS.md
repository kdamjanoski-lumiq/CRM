# Automotive Plugin - Deployment Instructions

## 📦 Creating the Plugin Package

### Step 1: Prepare the Plugin Structure

Ensure your plugin folder structure is correct:

```
automotive/
├── plugin.json
├── install.php
├── README.md
├── PLUGIN_INSTALLATION.md
├── DEPLOYMENT_INSTRUCTIONS.md
├── Controllers/
│   ├── Automotive.php
│   ├── Automotive_trade_ins.php
│   └── Automotive_deposits.php
├── Models/
│   ├── Automotive_trade_ins_model.php
│   ├── Automotive_deposits_model.php
│   ├── Automotive_floor_stock_model.php
│   ├── Automotive_service_appointments_model.php
│   ├── Automotive_service_jobs_model.php
│   ├── Automotive_parts_model.php
│   └── Automotive_parts_sales_model.php
├── Views/
│   └── (view files to be created)
├── Language/
│   └── english/
│       └── automotive_lang.php
└── Config/
    └── Routes.php
```

### Step 2: Complete Remaining Components

Before packaging, ensure these are completed:

**Controllers (3 remaining):**
- [ ] `Automotive_floor_stock.php`
- [ ] `Automotive_service.php`
- [ ] `Automotive_parts.php`

**Views (all components):**
- [ ] Dashboard views
- [ ] Trade-ins views
- [ ] Deposits views
- [ ] Floor stock views
- [ ] Service views
- [ ] Parts views

### Step 3: Create the ZIP Package

#### Option A: Using Command Line (Linux/Mac)

```bash
cd /path/to/plugins/
zip -r automotive.zip automotive/ -x "*.git*" "*.DS_Store" "*__MACOSX*"
```

#### Option B: Using GUI

1. Navigate to the plugins folder
2. Right-click on the `automotive` folder
3. Select "Compress" or "Create Archive"
4. Name it `automotive.zip`

#### Option C: Using PHP Script

Create a file `create_package.php`:

```php
<?php
$source = 'automotive';
$destination = 'automotive.zip';

$zip = new ZipArchive();
if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    echo "Package created: $destination\n";
} else {
    echo "Failed to create package\n";
}
```

Run: `php create_package.php`

## 🚀 Deployment Methods

### Method 1: Direct Upload to RISE CRM

**For Single Installation:**

1. Log in to RISE CRM as Administrator
2. Go to **Settings** > **Plugins**
3. Click **Upload Plugin**
4. Select `automotive.zip`
5. Click **Upload**
6. Click **Install**

### Method 2: FTP/SFTP Deployment

**For Manual Installation:**

```bash
# Upload via SFTP
sftp user@your-server.com
cd /path/to/rise-crm/plugins/
put -r automotive/
exit

# Set permissions
ssh user@your-server.com
cd /path/to/rise-crm/plugins/
chmod -R 755 automotive
chown -R www-data:www-data automotive
```

Then install via RISE CRM admin panel.

### Method 3: Git Deployment

**For Version Control:**

```bash
# On your server
cd /path/to/rise-crm/plugins/
git clone https://github.com/your-repo/automotive.git
chmod -R 755 automotive
chown -R www-data:www-data automotive
```

Then install via RISE CRM admin panel.

## 🔄 Deployment Workflow

### Development Environment

1. **Setup**
   ```bash
   # Clone RISE CRM
   git clone https://github.com/your-rise-crm.git
   cd rise-crm
   
   # Create plugin directory
   mkdir -p plugins/automotive
   cd plugins/automotive
   
   # Copy plugin files
   # ... copy all files ...
   ```

2. **Testing**
   - Install plugin in development
   - Test all features
   - Check for errors
   - Verify database operations

3. **Version Control**
   ```bash
   git init
   git add .
   git commit -m "Initial plugin version"
   git tag v1.0.0
   ```

### Staging Environment

1. **Deploy to Staging**
   ```bash
   # Upload plugin
   scp automotive.zip user@staging-server:/tmp/
   
   # SSH to staging
   ssh user@staging-server
   cd /path/to/rise-crm/plugins/
   unzip /tmp/automotive.zip
   chmod -R 755 automotive
   ```

2. **Install and Test**
   - Install via admin panel
   - Run full test suite
   - Verify all functionality
   - Check performance

3. **User Acceptance Testing**
   - Have users test features
   - Collect feedback
   - Fix any issues
   - Prepare for production

### Production Environment

1. **Pre-Deployment Checklist**
   - [ ] All features tested
   - [ ] No critical bugs
   - [ ] Documentation complete
   - [ ] Backup plan ready
   - [ ] Rollback plan ready

2. **Backup**
   ```bash
   # Backup database
   mysqldump -u user -p database_name > backup_$(date +%Y%m%d).sql
   
   # Backup files
   tar -czf rise_crm_backup_$(date +%Y%m%d).tar.gz /path/to/rise-crm/
   ```

3. **Deploy**
   ```bash
   # Upload plugin
   scp automotive.zip user@production-server:/tmp/
   
   # SSH to production
   ssh user@production-server
   cd /path/to/rise-crm/plugins/
   unzip /tmp/automotive.zip
   chmod -R 755 automotive
   chown -R www-data:www-data automotive
   ```

4. **Install**
   - Log in as Administrator
   - Go to Settings > Plugins
   - Install "Automotive Dealership"
   - Verify installation

5. **Post-Deployment**
   - Test critical features
   - Monitor error logs
   - Check performance
   - Notify users

## 📋 Pre-Deployment Checklist

### Code Quality
- [ ] All PHP files have proper syntax
- [ ] No debug code or var_dumps
- [ ] Error handling implemented
- [ ] Security checks in place
- [ ] Code follows RISE CRM standards

### Functionality
- [ ] All CRUD operations work
- [ ] Permissions system working
- [ ] Custom fields functional
- [ ] Invoice integration working
- [ ] Client integration working

### Database
- [ ] All tables created correctly
- [ ] Indexes properly set
- [ ] Foreign keys defined
- [ ] Default data inserted
- [ ] Migration tested

### Documentation
- [ ] README.md complete
- [ ] Installation guide ready
- [ ] User guide available
- [ ] API documentation (if applicable)
- [ ] Changelog updated

### Testing
- [ ] Unit tests pass
- [ ] Integration tests pass
- [ ] User acceptance testing done
- [ ] Performance testing done
- [ ] Security testing done

## 🔍 Post-Deployment Verification

### Immediate Checks (First 5 minutes)

```bash
# Check PHP errors
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log

# Check RISE CRM logs
tail -f /path/to/rise-crm/writable/logs/*.log
```

### Functionality Checks (First 30 minutes)

1. **Menu and Navigation**
   - [ ] Automotive menu appears
   - [ ] All submenu items work
   - [ ] No 404 errors

2. **Basic Operations**
   - [ ] Create a trade-in
   - [ ] Create a deposit
   - [ ] Add floor stock
   - [ ] Schedule appointment
   - [ ] Create service job
   - [ ] Add part

3. **Permissions**
   - [ ] Admin access works
   - [ ] Staff access works
   - [ ] Client access works (if enabled)
   - [ ] Restricted users blocked

4. **Integration**
   - [ ] Invoice integration works
   - [ ] Client integration works
   - [ ] Custom fields work
   - [ ] File uploads work

### Performance Checks (First 24 hours)

1. **Monitor**
   - Page load times
   - Database query performance
   - Server resource usage
   - Error rates

2. **Optimize if needed**
   - Add database indexes
   - Enable caching
   - Optimize queries
   - Compress assets

## 🐛 Troubleshooting Deployment Issues

### Issue: Plugin Won't Install

**Symptoms**: Installation fails or hangs

**Solutions**:
```bash
# Check PHP memory limit
php -i | grep memory_limit

# Check database permissions
mysql -u user -p -e "SHOW GRANTS;"

# Check file permissions
ls -la plugins/automotive/

# Check PHP error log
tail -100 /var/log/php_errors.log
```

### Issue: Database Tables Not Created

**Symptoms**: Tables missing after installation

**Solutions**:
```bash
# Check if tables exist
mysql -u user -p database_name -e "SHOW TABLES LIKE 'automotive%';"

# Manually run install script
cd plugins/automotive/
php -r "require 'install.php'; $installer = new Automotive_installer(); $installer->install();"

# Check for SQL errors
tail -100 /var/log/mysql/error.log
```

### Issue: Permission Denied Errors

**Symptoms**: 403 or permission errors

**Solutions**:
```bash
# Fix file permissions
chmod -R 755 plugins/automotive/
chown -R www-data:www-data plugins/automotive/

# Check SELinux (if applicable)
setenforce 0  # Temporarily disable
# or
chcon -R -t httpd_sys_content_t plugins/automotive/
```

## 🔄 Update Deployment

### Deploying Updates

1. **Prepare Update**
   - Increment version in plugin.json
   - Update changelog
   - Test thoroughly

2. **Create Update Package**
   ```bash
   # Update version
   sed -i 's/"version": "1.0.0"/"version": "1.1.0"/' plugin.json
   
   # Create package
   zip -r automotive_v1.1.0.zip automotive/
   ```

3. **Deploy Update**
   - Backup current installation
   - Upload new version
   - Uninstall old version (data preserved)
   - Install new version
   - Verify functionality

## 📊 Monitoring

### What to Monitor

1. **Error Logs**
   ```bash
   # Watch for errors
   tail -f /var/log/apache2/error.log | grep automotive
   ```

2. **Database Performance**
   ```sql
   -- Slow queries
   SELECT * FROM mysql.slow_log WHERE sql_text LIKE '%automotive%';
   
   -- Table sizes
   SELECT 
       table_name,
       ROUND(((data_length + index_length) / 1024 / 1024), 2) AS "Size (MB)"
   FROM information_schema.TABLES
   WHERE table_schema = 'your_database'
   AND table_name LIKE 'automotive%';
   ```

3. **User Activity**
   - Monitor usage patterns
   - Track feature adoption
   - Collect user feedback

## 🎯 Success Metrics

Track these metrics post-deployment:

- [ ] Installation success rate
- [ ] User adoption rate
- [ ] Error rate < 1%
- [ ] Page load time < 2 seconds
- [ ] User satisfaction > 80%
- [ ] Support tickets < 5 per week

## 📞 Support Plan

### Level 1: Self-Service
- Documentation
- FAQ
- Video tutorials

### Level 2: Community Support
- Forums
- User groups
- Knowledge base

### Level 3: Direct Support
- Email support
- Ticket system
- Phone support (if applicable)

## ✅ Final Checklist

Before marking deployment complete:

- [ ] Plugin installed successfully
- [ ] All features working
- [ ] No critical errors
- [ ] Performance acceptable
- [ ] Users notified
- [ ] Documentation accessible
- [ ] Support channels ready
- [ ] Monitoring in place
- [ ] Backup verified
- [ ] Rollback plan ready

---

**Deployment Version**: 1.0.0  
**Last Updated**: 2024  
**Status**: Ready for Production