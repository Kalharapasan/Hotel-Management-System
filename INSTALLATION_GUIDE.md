# 🚀 INSTALLATION & DEPLOYMENT GUIDE

## Step 1: Extract the Fixed Project
```bash
unzip Hotel-Management-System-FIXED.zip
cd Hotel-Management-System
```

## Step 2: Verify Requirements
- PHP 7.4+ ✅
- MySQL 5.7+ ✅
- Web Server (Apache/Nginx) ✅

## Step 3: Configure Database (if needed)
Edit `Core/Database.php` if your MySQL credentials are different:
```php
$host = 'localhost';      // Your MySQL host
$user = 'root';          // Your MySQL username
$pass = '';              // Your MySQL password
$dbname = 'hotel_management_db';
```

## Step 4: Deploy to Web Server
```bash
# Copy to web root
cp -r Hotel-Management-System /var/www/html/

# Or if using Apache document root
cp -r Hotel-Management-System /var/www/html/hotel

# Set permissions
chmod -R 755 /var/www/html/Hotel-Management-System/
chmod -R 755 /var/www/html/Hotel-Management-System/uploads/
```

## Step 5: Access the Application
Open in browser:
```
http://localhost/Hotel-Management-System
```

## Step 6: Login with Default Credentials
```
Admin Login:
- Username: admin
- Password: admin123
- Email: admin@hotel.com
```

## Database Initialization
The system will automatically:
1. Create the database `hotel_management_db`
2. Create all required tables
3. Insert sample data
4. Create a default admin account

No manual SQL execution needed!

## Troubleshooting

### Issue: "Connection failed"
**Solution:** Check MySQL is running and credentials are correct

### Issue: "Permission denied" on uploads
**Solution:** 
```bash
chmod -R 777 uploads/
```

### Issue: Blank page
**Solution:** Check PHP error logs
```bash
tail -f /var/log/apache2/error.log
```

### Issue: CSS/JS not loading
**Solution:** Check permissions and paths
```bash
ls -la css/
ls -la js/
```

## Security Setup (Recommended)

### 1. Change Default Admin Password
```
1. Login as admin
2. Go to Profile
3. Change password immediately
```

### 2. Create Strong Passwords
```
Minimum 6 characters
Mix of uppercase, lowercase, numbers
Example: Admin@2024!
```

### 3. Set File Permissions
```bash
chmod 644 config/db.php
chmod 755 uploads/
chmod 777 uploads/hotels/
chmod 777 uploads/rooms/
chmod 777 uploads/customers/
chmod 777 uploads/employees/
```

### 4. Enable HTTPS
Configure your web server for SSL/TLS certificates

### 5. Backup Regularly
```bash
# Database backup
mysqldump -u root hotel_management_db > backup.sql

# File backup
tar -czf hotel-backup-$(date +%Y%m%d).tar.gz Hotel-Management-System/
```

## Performance Tips

### 1. Enable Caching
- Use browser caching
- Implement page caching
- Use Redis for sessions

### 2. Optimize Database
```sql
ALTER TABLE users ADD INDEX (email);
ALTER TABLE bookings ADD INDEX (user_id);
ALTER TABLE rooms ADD INDEX (hotel_id);
ALTER TABLE payments ADD INDEX (booking_id);
```

### 3. Compress Files
```bash
gzip css/style.css
gzip js/main.js
```

### 4. Use CDN for Assets
Move images to CDN for faster loading

## Monitoring & Logs

### Check Error Logs
```bash
tail -f /var/log/apache2/error.log
tail -f /var/log/mysql/error.log
```

### Check Access Logs
```bash
tail -f /var/log/apache2/access.log
```

### Database Logs
```bash
mysql -u root -p -e "SHOW ENGINE INNODB STATUS"
```

## Maintenance

### Weekly
- Check error logs
- Verify backups
- Monitor disk space

### Monthly
- Update PHP packages
- Review security logs
- Optimize database

### Quarterly
- Security audit
- Performance review
- Backup restoration test

## Updating the System

### Step 1: Backup Everything
```bash
mysqldump -u root hotel_management_db > backup.sql
tar -czf hotel-backup-$(date +%Y%m%d).tar.gz Hotel-Management-System/
```

### Step 2: Download Latest Version
```bash
wget https://example.com/Hotel-Management-System-v2.zip
```

### Step 3: Extract and Compare
```bash
unzip Hotel-Management-System-v2.zip
diff -r Hotel-Management-System Hotel-Management-System-v2/
```

### Step 4: Merge Updates
- Keep your custom changes
- Update core files
- Test thoroughly

### Step 5: Rollback if Needed
```bash
# Restore from backup
mysql -u root hotel_management_db < backup.sql
```

## Production Checklist

- [ ] All default passwords changed
- [ ] HTTPS enabled
- [ ] Database backups configured
- [ ] Error logging enabled
- [ ] Regular backup schedule set
- [ ] Performance optimized
- [ ] Security headers configured
- [ ] Rate limiting enabled
- [ ] Monitoring alerts set
- [ ] Load balancing configured (if needed)
- [ ] CDN configured (if needed)
- [ ] DNS properly configured
- [ ] SSL certificate valid
- [ ] Firewall rules configured
- [ ] Database replication set (if needed)

## Support & Documentation

For detailed technical information, see:
- `FIXES_APPLIED.md` - All changes made
- `ERROR_REPORT.md` - Errors that were fixed
- `QUICK_REFERENCE.md` - Quick reference guide
- `SUMMARY.md` - Executive summary

---

**Last Updated:** June 20, 2026
**Version:** 1.0 (Fixed)
**Status:** Ready for Production
