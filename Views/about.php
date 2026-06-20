<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Hotel Management System</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🏨 Hotel Management</div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/rooms">Rooms</a></li>
                <li><a href="/restaurant">Restaurant</a></li>
                <li><a href="/about" class="active">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <section class="about-section">
            <h1>About Our Hotel Management System</h1>
            
            <div class="about-content">
                <p>Welcome to our state-of-the-art Hotel Management System, designed to provide the best booking experience for our guests.</p>
                
                <h2>Our Features</h2>
                <ul>
                    <li>🛏️ Easy room booking system</li>
                    <li>🍽️ Restaurant menu and ordering</li>
                    <li>👤 User profile management</li>
                    <li>💳 Secure payment processing</li>
                    <li>📊 Advanced admin dashboard</li>
                    <li>🔒 Enterprise-level security</li>
                </ul>

                <h2>Our Mission</h2>
                <p>To provide the best hospitality experience by leveraging modern technology and excellent customer service.</p>

                <h2>Contact Us</h2>
                <p>Email: info@hotelmanagement.com</p>
                <p>Phone: +1-800-HOTEL-123</p>
                <p>Address: 123 Hotel Street, City, Country</p>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
