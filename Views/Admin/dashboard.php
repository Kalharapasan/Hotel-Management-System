<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar admin-nav">
        <div class="container">
            <div class="logo">🏨 Admin Panel</div>
            <ul class="nav-links">
                <li><a href="/admin" class="active">Dashboard</a></li>
                <li><a href="/admin/hotels">Hotels</a></li>
                <li><a href="/admin/rooms">Rooms</a></li>
                <li><a href="/admin/categories">Categories</a></li>
                <li><a href="/admin/customers">Customers</a></li>
                <li><a href="/admin/employees">Employees</a></li>
                <li><a href="/admin/bookings">Bookings</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container admin-container">
        <h1>Admin Dashboard</h1>

        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Hotels</h3>
                <p class="stat-number"><?php echo $hotelCount ?? 0; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Customers</h3>
                <p class="stat-number"><?php echo $customerCount ?? 0; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p class="stat-number"><?php echo $bookingCount ?? 0; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number">$<?php echo number_format($totalRevenue ?? 0, 2); ?></p>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Room Statistics</h2>
            <div class="stats-row">
                <div class="stat-item">
                    <label>Total Rooms:</label>
                    <span><?php echo $roomStats['total'] ?? 0; ?></span>
                </div>
                <div class="stat-item">
                    <label>Available:</label>
                    <span class="available"><?php echo $roomStats['available'] ?? 0; ?></span>
                </div>
                <div class="stat-item">
                    <label>Booked:</label>
                    <span class="booked"><?php echo $roomStats['booked'] ?? 0; ?></span>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Management Options</h2>
            <div class="admin-links">
                <a href="/admin/hotels" class="btn btn-primary">Manage Hotels</a>
                <a href="/admin/rooms" class="btn btn-primary">Manage Rooms</a>
                <a href="/admin/customers" class="btn btn-primary">Manage Customers</a>
                <a href="/admin/employees" class="btn btn-primary">Manage Employees</a>
                <a href="/admin/bookings" class="btn btn-primary">View Bookings</a>
                <a href="/admin/billing" class="btn btn-primary">View Payments</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
