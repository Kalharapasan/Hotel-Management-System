<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'hotel_management_db';

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// Create Tables
$tables = [
    "admins" => "CREATE TABLE IF NOT EXISTS admins (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) DEFAULT NULL,
        social_id VARCHAR(255) DEFAULT NULL,
        social_type ENUM('google', 'facebook', 'none') DEFAULT 'none',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "hotels" => "CREATE TABLE IF NOT EXISTS hotels (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        location VARCHAR(255) NOT NULL,
        description TEXT,
        amenities TEXT,
        price_per_night DECIMAL(10,2) NOT NULL,
        image_url VARCHAR(255),
        booking_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "flights" => "CREATE TABLE IF NOT EXISTS flights (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        airline VARCHAR(100) NOT NULL,
        departure VARCHAR(100) NOT NULL,
        arrival VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        departure_time DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "trips" => "CREATE TABLE IF NOT EXISTS trips (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        duration VARCHAR(50),
        price DECIMAL(10,2) NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "bookings" => "CREATE TABLE IF NOT EXISTS bookings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11),
        item_id INT(11),
        item_type ENUM('hotel', 'flight', 'trip', 'room'),
        check_in DATE,
        check_out DATE,
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    "room_categories" => "CREATE TABLE IF NOT EXISTS room_categories (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "rooms" => "CREATE TABLE IF NOT EXISTS rooms (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        hotel_id INT(11),
        category_id INT(11),
        room_type VARCHAR(100) NOT NULL,
        price_per_night DECIMAL(10,2) NOT NULL,
        status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
        amenities TEXT,
        image_url VARCHAR(255),
        FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES room_categories(id) ON DELETE SET NULL
    )",
    "employees" => "CREATE TABLE IF NOT EXISTS employees (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        role VARCHAR(50) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        salary DECIMAL(10,2),
        joined_at DATE,
        status ENUM('active', 'inactive') DEFAULT 'active'
    )",
    "menu_items" => "CREATE TABLE IF NOT EXISTS menu_items (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        image_url VARCHAR(255)
    )",
    "restaurant_orders" => "CREATE TABLE IF NOT EXISTS restaurant_orders (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11),
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'preparing', 'delivered', 'cancelled') DEFAULT 'pending',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    "gallery" => "CREATE TABLE IF NOT EXISTS gallery (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        image_url VARCHAR(255) NOT NULL,
        title VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "site_settings" => "CREATE TABLE IF NOT EXISTS site_settings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        page_key VARCHAR(50) UNIQUE,
        title VARCHAR(255),
        content TEXT,
        image_url VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "payments" => "CREATE TABLE IF NOT EXISTS payments (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        booking_id INT(11),
        amount DECIMAL(10,2) NOT NULL,
        payment_method VARCHAR(50),
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        transaction_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $name => $sql) {
    if (!$conn->query($sql)) {
        echo "Error creating table $name: " . $conn->error . "<br>";
    }
}

// Seed Dummy Data if empty
$checkAdmins = $conn->query("SELECT id FROM admins LIMIT 1");
if ($checkAdmins->num_rows == 0) {
    $hashedPass = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password, email) VALUES ('admin', '$hashedPass', 'admin@hotel.com')");
    
    // Hotels
    $conn->query("INSERT INTO hotels (name, location, amenities, price_per_night, image_url, booking_url) VALUES 
        ('Grand Royal Hotel', 'Paris, France', 'Free WiFi, Pool, Spa, Gym', 250.00, 'https://images.unsplash.com/photo-1566073771259-6a8506099945', '#'),
        ('Ocean View Resort', 'Bali, Indonesia', 'Beach Access, Pool, Breakfast', 150.00, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4', '#'),
        ('Mountain Retreat', 'Swiss Alps', 'Skiing, Fireplace, Spa', 300.00, 'https://images.unsplash.com/photo-1502784444187-359ac186c5bb', '#')");

    // Flights
    $conn->query("INSERT INTO flights (airline, departure, arrival, price, departure_time) VALUES 
        ('SkyHigh Air', 'New York', 'London', 450.00, '2024-06-01 10:00:00'),
        ('Global Jet', 'Dubai', 'Tokyo', 700.00, '2024-06-02 14:30:00')");

    // Trips
    $conn->query("INSERT INTO trips (title, description, duration, price, image_url) VALUES 
        ('European Discovery', 'Visit London, Paris, and Rome in 10 days.', '10 Days', 1200.00, 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b'),
        ('Safari Adventure', 'Experience the wild in Kenya.', '7 Days', 2500.00, 'https://images.unsplash.com/photo-1516426122078-c23e76319801')");

    $conn->query("INSERT INTO users (fullname, email, password) VALUES ('John Doe', 'john@example.com', '".password_hash('password', PASSWORD_DEFAULT)."')");
    
    // Room Categories
    $conn->query("INSERT INTO room_categories (category_name, description) VALUES 
        ('Single Room', 'Perfect for solo travelers.'),
        ('Family Room', 'Spacious rooms for the whole family.'),
        ('Couple Room', 'Romantic setting for two.')");

    // Rooms (Update with category_id)
    $conn->query("INSERT INTO rooms (hotel_id, category_id, room_type, price_per_night, amenities, image_url) VALUES 
        (1, 1, 'Standard Single', 120.00, 'Single Bed, WiFi', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd'),
        (1, 2, 'Luxury Family Suite', 350.00, 'King Bed + 2 Twin, Balcony', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a')");

    // Employees
    $conn->query("INSERT INTO employees (fullname, role, email, phone, salary, joined_at) VALUES 
        ('Jane Smith', 'Manager', 'jane@luxestay.com', '123456789', 5000.00, '2023-01-15'),
        ('Mike Johnson', 'Chef', 'mike@luxestay.com', '987654321', 4000.00, '2023-03-10')");

    // Menu Items
    $conn->query("INSERT INTO menu_items (name, category, price, description, image_url) VALUES 
        ('Grilled Salmon', 'Main Course', 25.00, 'Fresh Atlantic salmon with lemon butter.', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288'),
        ('Caesar Salad', 'Starters', 12.00, 'Classic Caesar with homemade croutons.', 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9')");

    // Gallery
    $conn->query("INSERT INTO gallery (image_url, title) VALUES 
        ('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb', 'Hotel Lobby'),
        ('https://images.unsplash.com/photo-1584132967334-10e028bd69f7', 'Infinity Pool'),
        ('https://images.unsplash.com/photo-1571896349842-33c89424de2d', 'Beach Side View')");
}

function getDb() {
    global $conn;
    return $conn;
}
?>
