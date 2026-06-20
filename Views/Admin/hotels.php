<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar admin-nav">
        <div class="container">
            <div class="logo">🏨 Admin Panel</div>
            <ul class="nav-links">
                <li><a href="/admin">Dashboard</a></li>
                <li><a href="/admin/hotels" class="active">Hotels</a></li>
                <li><a href="/admin/rooms">Rooms</a></li>
                <li><a href="/admin/customers">Customers</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container admin-container">
        <h1>Manage Hotels</h1>

        <button class="btn btn-primary" onclick="toggleForm()">Add New Hotel</button>

        <div id="hotelForm" style="display: none;" class="admin-form">
            <h2>Add/Edit Hotel</h2>
            <form method="POST" action="/admin/save-hotel" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Hotel Name:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Location:</label>
                    <input type="text" name="location" required>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label>Amenities:</label>
                    <textarea name="amenities" required></textarea>
                </div>

                <div class="form-group">
                    <label>Price per Night:</label>
                    <input type="number" name="price" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Image:</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Save Hotel</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm()">Cancel</button>
            </form>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($hotels)): ?>
                    <?php while($hotel = $hotels->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo intval($hotel['id']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['name']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['location']); ?></td>
                            <td>$<?php echo number_format($hotel['price_per_night'], 2); ?></td>
                            <td>
                                <a href="/admin/hotels?edit=<?php echo intval($hotel['id']); ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="/admin/delete-hotel?id=<?php echo intval($hotel['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this hotel?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2026 Hotel Management System. All rights reserved.</p>
    </footer>

    <script>
        function toggleForm() {
            const form = document.getElementById('hotelForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
