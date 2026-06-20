<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🏨 Hotel Management</div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/rooms" class="active">Rooms</a></li>
                <li><a href="<?php echo BASE_URL; ?>/restaurant">Restaurant</a></li>
                <li><a href="<?php echo BASE_URL; ?>/about">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>/profile">Profile</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <section class="rooms-section">
            <h1>Available Rooms</h1>

            <?php if(isset($book) && $book && isset($room)): ?>
                <div class="booking-form">
                    <h2>Book Room: <?php echo htmlspecialchars($room['room_type']); ?></h2>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/rooms/book">
                        <input type="hidden" name="room_id" value="<?php echo intval($room['id']); ?>">
                        
                        <div class="form-group">
                            <label for="check_in">Check-in Date:</label>
                            <input type="date" id="check_in" name="check_in" required>
                        </div>

                        <div class="form-group">
                            <label for="check_out">Check-out Date:</label>
                            <input type="date" id="check_out" name="check_out" required>
                        </div>

                        <div class="form-group">
                            <label>Price per Night:</label>
                            <p>$<?php echo number_format($room['price_per_night'], 2); ?></p>
                        </div>

                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        <a href="<?php echo BASE_URL; ?>/rooms" class="btn btn-secondary">Back to Rooms</a>
                    </form>
                </div>
            <?php else: ?>
                <div class="rooms-grid">
                    <?php if(isset($rooms)): ?>
                        <?php while($room = $rooms->fetch_assoc()): ?>
                            <div class="room-card">
                                <img src="<?php echo htmlspecialchars(asset($room['image_url'])); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>">
                                <h3><?php echo htmlspecialchars($room['room_type']); ?></h3>
                                <p><strong>Hotel:</strong> <?php echo htmlspecialchars($room['hotel_name']); ?></p>
                                <p><strong>Price:</strong> $<?php echo number_format($room['price_per_night'], 2); ?>/night</p>
                                <p><strong>Amenities:</strong> <?php echo htmlspecialchars($room['amenities']); ?></p>
                                
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <a href="<?php echo BASE_URL; ?>/rooms/book?id=<?php echo intval($room['id']); ?>" class="btn btn-primary">Book Now</a>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-primary">Login to Book</a>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
