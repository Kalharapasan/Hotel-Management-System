# Hotel Management System - Complete & Fixed

A professional, secure hotel management system built with PHP, featuring user authentication, room booking, restaurant management, and an admin dashboard.

## ✨ Features

- 🔐 **Secure Authentication** - User login/registration with password hashing
- 🏨 **Hotel Management** - CRUD operations for hotels
- 🛏️ **Room Booking** - Browse and book available rooms
- 👥 **Customer Management** - Manage user profiles and bookings
- 🍽️ **Restaurant System** - Menu management and food ordering
- 👨‍💼 **Employee Management** - Staff management interface
- 📊 **Admin Dashboard** - Complete admin panel with statistics
- 💳 **Payment Tracking** - Billing and payment management
- 🔒 **Enterprise Security** - Prepared statements, input validation, file upload security

## 🚀 Quick Start

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx with mod_rewrite

### Installation

1. **Extract the project**
```bash
unzip Hotel-Management-System-COMPLETE.zip
cd Hotel-Management-System
```

2. **Copy to web server**
```bash
cp -r . /var/www/html/hotel
chmod -R 755 /var/www/html/hotel/
chmod -R 777 /var/www/html/hotel/uploads/
```

3. **Access in browser**
```
http://localhost/hotel
```

4. **Login**
- Username: `admin`
- Password: `admin123`

The database and all tables are created automatically on first access.

## 📁 Project Structure

```
Hotel-Management-System/
├── index.php                    # Entry point
├── .htaccess                    # Apache rewrite rules
├── config/
│   └── db.php                   # Database configuration
├── Core/
│   ├── Database.php             # Database connection
│   ├── Router.php               # URL routing
│   └── Controller.php           # Base controller
├── Controllers/                 # Application controllers
│   ├── AuthController.php
│   ├── AdminController.php
│   ├── ProfileController.php
│   ├── RoomController.php
│   ├── HomeController.php
│   └── RestaurantController.php
├── Models/                      # Database models
│   ├── BaseModel.php
│   ├── User.php
│   ├── Hotel.php
│   └── Category.php
├── Views/                       # HTML templates
│   ├── auth/
│   ├── admin/
│   ├── home.php
│   ├── rooms.php
│   └── ...
├── css/                         # Stylesheets
│   └── style.css
├── js/                          # JavaScript
│   └── main.js
└── uploads/                     # File uploads
    ├── hotels/
    ├── rooms/
    ├── customers/
    └── employees/
```

## 🔐 Security Features

### SQL Injection Prevention
- All database queries use prepared statements
- Parameterized queries with type binding

### Input Validation
- Email format validation
- Password strength requirements (min 6 chars)
- Integer and float validation
- String length validation

### File Upload Security
- Allowed extensions: jpg, jpeg, png, gif only
- Maximum file size: 5MB
- Safe filename generation using uniqid()

### Authentication
- Session-based authentication
- Bcrypt password hashing
- Protected admin routes
- Logout functionality

### Output Security
- HTML escaping on all output
- XSS prevention

### Error Handling
- Try-catch blocks throughout
- Database error checking
- User-friendly error messages

## 🎯 Default Credentials

```
Admin:
  Username: admin
  Password: admin123
  Email: admin@hotel.com

Test User:
  Email: john@example.com
  Password: password
```

⚠️ **Change these immediately after first login!**

## 📊 Database Tables

- `admins` - Admin accounts
- `users` - Customer accounts
- `hotels` - Hotel listings
- `rooms` - Room inventory
- `room_categories` - Room types
- `bookings` - Room bookings
- `flights` - Flight information
- `trips` - Trip packages
- `employees` - Staff management
- `menu_items` - Restaurant menu
- `restaurant_orders` - Food orders
- `payments` - Payment records
- `gallery` - Image gallery
- `site_settings` - Site configuration

## 🛠️ Configuration

Edit `config/db.php` to change database credentials:

```php
$host = 'localhost';    // MySQL host
$user = 'root';         // MySQL username
$pass = '';             // MySQL password
$dbname = 'hotel_management_db';
```

## 🚢 Deployment Checklist

- [ ] Extract project files
- [ ] Review SECURITY.md
- [ ] Change default admin password
- [ ] Configure database credentials if needed
- [ ] Set folder permissions (755 for dirs, 644 for files)
- [ ] Test login/register functionality
- [ ] Test file uploads
- [ ] Enable HTTPS
- [ ] Set up automated backups
- [ ] Enable error logging
- [ ] Test on staging server
- [ ] Deploy to production

## 📋 API Endpoints

### Public Routes
- `GET /` - Home page
- `GET /rooms` - Room listing
- `GET /restaurant` - Restaurant menu
- `GET /about` - About page
- `GET /contact` - Contact page

### Authentication
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /logout` - Logout

### User Routes (Authenticated)
- `GET /profile` - User profile
- `POST /profile/update` - Update profile
- `GET /rooms/book` - Book room form
- `POST /rooms/book` - Process booking
- `POST /restaurant/order` - Place order

### Admin Routes (Admin Only)
- `GET /admin` - Admin dashboard
- `GET /admin/hotels` - Manage hotels
- `POST /admin/save-hotel` - Save hotel
- `GET /admin/rooms` - Manage rooms
- `GET /admin/customers` - Manage customers
- `GET /admin/employees` - Manage employees
- `GET /admin/bookings` - View bookings
- `GET /admin/billing` - Billing management

## 🎓 Code Examples

### Using Prepared Statements
```php
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

### Input Validation
```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new \Exception("Invalid email");
}

if (strlen($password) < 6) {
    throw new \Exception("Password too short");
}
```

### File Upload Security
```php
$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    throw new \Exception("File type not allowed");
}

if ($file['size'] > 5242880) { // 5MB
    throw new \Exception("File too large");
}
```

## 🐛 Troubleshooting

### Error: "Connection failed"
- Check MySQL is running
- Verify credentials in config/db.php
- Check user permissions on database

### Error: "Permission denied" on uploads
```bash
chmod -R 777 uploads/
```

### Error: "Class not found"
- Verify class namespace matches file structure
- Check PHP autoloader in index.php

### Error: "404 Not Found"
- Verify route is registered in index.php
- Check .htaccess file exists
- Enable mod_rewrite on Apache

## 📞 Support

For documentation and detailed guides, see:
- `COMPLETE_CODE_REFERENCE.md` - Full code documentation
- `INSTALLATION_GUIDE.md` - Setup instructions
- `SUMMARY.md` - Technical summary

## 📄 License
[License](./LICENSE.md): Proprietary – Permission Required

## ✅ All Fixed Issues

1. ✅ SQL Injection vulnerabilities
2. ✅ Missing input validation
3. ✅ File upload security
4. ✅ Missing error handling
5. ✅ Missing authentication checks
6. ✅ Database connection errors
7. ✅ Unescaped output (XSS)
8. ✅ Router validation
9. ✅ Type safety issues
10. ✅ Undefined variables

## 🎉 Ready for Production

This system is:
- ✅ Fully functional
- ✅ Secure
- ✅ Well-documented
- ✅ Easy to deploy
- ✅ Production-ready

---

**Date:** June 20, 2026  
**Version:** 1.0 (Complete & Fixed)  
**Status:** ✅ PRODUCTION READY
