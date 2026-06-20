<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Hotel Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🏨 Hotel Management</div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/rooms">Rooms</a></li>
                <li><a href="<?php echo BASE_URL; ?>/restaurant">Restaurant</a></li>
                <li><a href="<?php echo BASE_URL; ?>/about">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact" class="active">Contact</a></li>
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
        <section class="contact-section">
            <h1>Contact Us</h1>

            <?php if(isset($error) && $error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($success) && $success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <div class="contact-content">
                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    <form method="POST" action="<?php echo BASE_URL; ?>/contact">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>

                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p><strong>Email:</strong> info@hotelmanagement.com</p>
                    <p><strong>Phone:</strong> +1-800-HOTEL-123</p>
                    <p><strong>Address:</strong> 123 Hotel Street, City, Country</p>
                    <p><strong>Hours:</strong> 24/7 Customer Support</p>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
