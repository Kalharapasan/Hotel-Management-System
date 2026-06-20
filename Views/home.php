<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System - Home</title>
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
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li><a href="/admin">Dashboard</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <section class="hero">
            <h1>Welcome to Our Hotel Management System</h1>
            <p>Book your perfect stay with us</p>
            <a href="/rooms" class="btn btn-primary">Browse Rooms</a>
        </section>

        <section class="featured-hotels">
            <h2>Featured Hotels</h2>
            <div class="grid">
                <?php if(isset($hotels)): ?>
                    <?php while($hotel = $hotels->fetch_assoc()): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                            <h3><?php echo htmlspecialchars($hotel['name']); ?></h3>
                            <p><?php echo htmlspecialchars($hotel['location']); ?></p>
                            <p class="price">$<?php echo number_format($hotel['price_per_night'], 2); ?>/night</p>
                            <a href="/rooms" class="btn btn-secondary">View Rooms</a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="featured-rooms">
            <h2>Featured Rooms</h2>
            <div class="grid">
                <?php if(isset($rooms)): ?>
                    <?php while($room = $rooms->fetch_assoc()): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>">
                            <h3><?php echo htmlspecialchars($room['room_type']); ?></h3>
                            <p><?php echo htmlspecialchars($room['hotel_name']); ?></p>
                            <p class="price">$<?php echo number_format($room['price_per_night'], 2); ?>/night</p>
                            <a href="/rooms/book?id=<?php echo intval($room['id']); ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
