<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - Hotel Management System</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🏨 Hotel Management</div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/rooms">Rooms</a></li>
                <li><a href="/restaurant" class="active">Restaurant</a></li>
                <li><a href="/about">About</a></li>
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
        <section class="restaurant-section">
            <h1>Restaurant Menu</h1>

            <?php if(!$user_logged_in): ?>
                <div class="alert alert-info">
                    Please <a href="/login">login</a> to place an order.
                </div>
            <?php endif; ?>

            <div class="menu-grid">
                <?php if(isset($menu_items)): ?>
                    <?php while($item = $menu_items->fetch_assoc()): ?>
                        <div class="menu-card">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                            
                            <?php if($user_logged_in): ?>
                                <form method="POST" action="/restaurant/order">
                                    <input type="hidden" name="item_id" value="<?php echo intval($item['id']); ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="10">
                                    <button type="submit" class="btn btn-primary">Order</button>
                                </form>
                            <?php endif; ?>
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
