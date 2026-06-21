<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AdminController extends Controller {
    protected $db;

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
    }

    private function uploadFile($file, $folder) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = 'uploads/' . $folder . '/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return '/' . $destination;
            }
        }
        return null;
    }

    public function dashboard() {
        $data = [
            'hotelCount' => $this->db->query("SELECT COUNT(*) as count FROM hotels")->fetch_assoc()['count'],
            'customerCount' => $this->db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
            'bookingCount' => $this->db->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'],
            'totalRevenue' => $this->db->query("SELECT SUM(amount) as total FROM payments WHERE status='completed'")->fetch_assoc()['total'] ?? 0,
            'roomStats' => [
                'total' => $this->db->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'],
                'available' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status='available'")->fetch_assoc()['count'],
                'booked' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status='booked'")->fetch_assoc()['count']
            ],
            'recentBookings' => $this->db->query("SELECT b.*, u.fullname FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.booking_date DESC LIMIT 5"),
            'recentOrders' => $this->db->query("SELECT r.*, u.fullname FROM restaurant_orders r JOIN users u ON r.user_id = u.id ORDER BY r.order_date DESC LIMIT 5")
        ];
        $this->view('admin/dashboard', $data);
    }

    // --- Hotels ---
    public function hotels() {
        $data['hotels'] = $this->db->query("SELECT * FROM hotels ORDER BY id DESC");
        $data['edit_hotel'] = null;
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $data['edit_hotel'] = $this->db->query("SELECT * FROM hotels WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/hotels', $data);
    }

    public function saveHotel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->db->real_escape_string($_POST['name']);
            $location = $this->db->real_escape_string($_POST['location']);
            $description = $this->db->real_escape_string($_POST['description']);
            $amenities = $this->db->real_escape_string($_POST['amenities']);
            $price = $_POST['price'];
            $booking_url = $this->db->real_escape_string($_POST['booking_url']);

            $image_url = $_POST['existing_image'];
            $new_image = $this->uploadFile($_FILES['image'], 'hotels');
            if ($new_image) $image_url = $new_image;

            if (isset($_POST['hotel_id']) && !empty($_POST['hotel_id'])) {
                $id = $_POST['hotel_id'];
                $this->db->query("UPDATE hotels SET name='$name', location='$location', description='$description', amenities='$amenities', price_per_night='$price', image_url='$image_url', booking_url='$booking_url' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO hotels (name, location, description, amenities, price_per_night, image_url, booking_url) VALUES ('$name', '$location', '$description', '$amenities', '$price', '$image_url', '$booking_url')");
            }
            $this->redirect('/admin/hotels');
        }
    }

    public function deleteHotel() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("DELETE FROM hotels WHERE id=$id");
            $this->redirect('/admin/hotels');
        }
    }

    // --- Rooms ---
    public function rooms() {
        $data['rooms'] = $this->db->query("SELECT r.*, h.name as hotel_name, c.category_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id LEFT JOIN room_categories c ON r.category_id = c.id ORDER BY r.id DESC");
        $data['hotels'] = $this->db->query("SELECT id, name FROM hotels");
        $data['categories'] = $this->db->query("SELECT id, category_name FROM room_categories");
        $data['edit_room'] = null;
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $data['edit_room'] = $this->db->query("SELECT * FROM rooms WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/rooms', $data);
    }

    public function saveRoom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotel_id = $_POST['hotel_id'];
            $category_id = $_POST['category_id'] ?: 'NULL';
            $type = $this->db->real_escape_string($_POST['room_type']);
            $price = $_POST['price'];
            $status = $_POST['status'];
            $amenities = $this->db->real_escape_string($_POST['amenities']);

            $image_url = $_POST['existing_image'];
            $new_image = $this->uploadFile($_FILES['image'], 'rooms');
            if ($new_image) $image_url = $new_image;

            if (isset($_POST['room_id']) && !empty($_POST['room_id'])) {
                $id = $_POST['room_id'];
                $this->db->query("UPDATE rooms SET hotel_id='$hotel_id', category_id=$category_id, room_type='$type', price_per_night='$price', status='$status', amenities='$amenities', image_url='$image_url' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO rooms (hotel_id, category_id, room_type, price_per_night, status, amenities, image_url) VALUES ('$hotel_id', $category_id, '$type', '$price', '$status', '$amenities', '$image_url')");
            }
            $this->redirect('/admin/rooms');
        }
    }

    public function deleteRoom() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("DELETE FROM rooms WHERE id=$id");
            $this->redirect('/admin/rooms');
        }
    }

    // --- Categories ---
    public function categories() {
        $data['categories'] = $this->db->query("SELECT * FROM room_categories ORDER BY id DESC");
        $this->view('admin/categories', $data);
    }

    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->db->real_escape_string($_POST['category_name']);
            $desc = $this->db->real_escape_string($_POST['description']);
            if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
                $id = $_POST['category_id'];
                $this->db->query("UPDATE room_categories SET category_name='$name', description='$desc' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO room_categories (category_name, description) VALUES ('$name', '$desc')");
            }
            $this->redirect('/admin/categories');
        }
    }

    public function deleteCategory() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("DELETE FROM room_categories WHERE id=$id");
            $this->redirect('/admin/categories');
        }
    }

    // --- Customers ---
    public function customers() {
        $data['customers'] = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $data['edit_customer'] = null;
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $data['edit_customer'] = $this->db->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/customers', $data);
    }

    public function saveCustomer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['user_id'];
            $fullname = $this->db->real_escape_string($_POST['fullname']);
            $email = $this->db->real_escape_string($_POST['email']);
            $this->db->query("UPDATE users SET fullname='$fullname', email='$email' WHERE id=$id");
            $this->redirect('/admin/customers');
        }
    }

    public function deleteCustomer() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("DELETE FROM users WHERE id=$id");
            $this->redirect('/admin/customers');
        }
    }

    // --- Employees ---
    public function employees() {
        $data['employees'] = $this->db->query("SELECT * FROM employees ORDER BY id DESC");
        $data['edit_emp'] = null;
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $data['edit_emp'] = $this->db->query("SELECT * FROM employees WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/employees', $data);
    }

    public function saveEmployee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $this->db->real_escape_string($_POST['fullname']);
            $role = $this->db->real_escape_string($_POST['role']);
            $email = $this->db->real_escape_string($_POST['email']);
            $phone = $this->db->real_escape_string($_POST['phone']);
            $salary = $_POST['salary'];
            $status = $_POST['status'];

            $image_url = $_POST['existing_image'];
            $new_image = $this->uploadFile($_FILES['image'], 'employees');
            if ($new_image) $image_url = $new_image;

            if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
                $id = $_POST['employee_id'];
                $this->db->query("UPDATE employees SET fullname='$fullname', role='$role', email='$email', phone='$phone', salary='$salary', status='$status' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO employees (fullname, role, email, phone, salary, status) VALUES ('$fullname', '$role', '$email', '$phone', '$salary', '$status')");
            }
            $this->redirect('/admin/employees');
        }
    }

    public function deleteEmployee() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->db->query("DELETE FROM employees WHERE id=$id");
            $this->redirect('/admin/employees');
        }
    }

    // --- Bookings & Billing ---
    public function bookings() {
        $data['bookings'] = $this->db->query("SELECT b.*, u.fullname as customer_name, u.email as customer_email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.booking_date DESC");
        $this->view('admin/bookings', $data);
    }

    public function generateBill() {
        if (isset($_GET['booking_id'])) {
            $booking_id = $_GET['booking_id'];
            $booking = $this->db->query("SELECT b.*, u.fullname, u.email FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.id = $booking_id")->fetch_assoc();
            
            $price = 0;
            if ($booking['item_type'] == 'room') {
                $room = $this->db->query("SELECT price_per_night FROM rooms WHERE id = " . $booking['item_id'])->fetch_assoc();
                $price = $room['price_per_night'];
            }
            
            $orders = $this->db->query("SELECT SUM(total_amount) as total FROM restaurant_orders WHERE user_id = " . $booking['user_id'] . " AND status != 'delivered'");
            $restaurant_total = $orders->fetch_assoc()['total'] ?? 0;
            
            $data = [
                'booking' => $booking,
                'room_price' => $price,
                'restaurant_total' => $restaurant_total,
                'total' => $price + $restaurant_total
            ];
            
            $this->view('admin/bill', $data);
        }
    }

    public function completeCheckout() {
        if (isset($_POST['booking_id'])) {
            $booking_id = $_POST['booking_id'];
            $user_id = $_POST['user_id'];
            $amount = $_POST['total_amount'];
            $this->db->query("UPDATE bookings SET status = 'confirmed' WHERE id = $booking_id");
            $this->db->query("INSERT INTO payments (booking_id, amount, status, payment_method) VALUES ($booking_id, $amount, 'completed', 'Cash')");
            $this->db->query("UPDATE restaurant_orders SET status = 'delivered' WHERE user_id = $user_id");
            $this->redirect('/admin/billing');
        }
    }

    public function billing() {
        $data['payments'] = $this->db->query("SELECT p.*, b.item_type, u.fullname FROM payments p JOIN bookings b ON p.booking_id = b.id JOIN users u ON b.user_id = u.id ORDER BY p.created_at DESC");
        $this->view('admin/billing', $data);
    }

    // --- Flights ---
    public function flights() {
        $data['flights'] = $this->db->query("SELECT * FROM flights ORDER BY created_at DESC");
        $data['edit_flight'] = null;
        if (isset($_GET['edit'])) {
            $id = (int) $_GET['edit'];
            $data['edit_flight'] = $this->db->query("SELECT * FROM flights WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/flights', $data);
    }

    public function saveFlight() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $airline = $this->db->real_escape_string($_POST['airline']);
            $departure = $this->db->real_escape_string($_POST['departure']);
            $arrival = $this->db->real_escape_string($_POST['arrival']);
            $price = $_POST['price'];
            $departure_time = $this->db->real_escape_string($_POST['departure_time']);

            if (isset($_POST['flight_id']) && !empty($_POST['flight_id'])) {
                $id = (int) $_POST['flight_id'];
                $this->db->query("UPDATE flights SET airline='$airline', departure='$departure', arrival='$arrival', price='$price', departure_time='$departure_time' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO flights (airline, departure, arrival, price, departure_time) VALUES ('$airline', '$departure', '$arrival', '$price', '$departure_time')");
            }
            $this->redirect('/admin/flights');
        }
    }

    public function deleteFlight() {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $this->db->query("DELETE FROM flights WHERE id=$id");
            $this->redirect('/admin/flights');
        }
    }

    // --- Trips ---
    public function trips() {
        $data['trips'] = $this->db->query("SELECT * FROM trips ORDER BY created_at DESC");
        $data['edit_trip'] = null;
        if (isset($_GET['edit'])) {
            $id = (int) $_GET['edit'];
            $data['edit_trip'] = $this->db->query("SELECT * FROM trips WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/trips', $data);
    }

    public function saveTrip() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $this->db->real_escape_string($_POST['title']);
            $description = $this->db->real_escape_string($_POST['description']);
            $duration = $this->db->real_escape_string($_POST['duration']);
            $price = $_POST['price'];

            $image_url = $_POST['image_url'];
            $new_image = $this->uploadFile($_FILES['image'] ?? null, 'trips');
            if ($new_image) $image_url = $new_image;
            $image_url = $this->db->real_escape_string($image_url);

            if (isset($_POST['trip_id']) && !empty($_POST['trip_id'])) {
                $id = (int) $_POST['trip_id'];
                $this->db->query("UPDATE trips SET title='$title', description='$description', duration='$duration', price='$price', image_url='$image_url' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO trips (title, description, duration, price, image_url) VALUES ('$title', '$description', '$duration', '$price', '$image_url')");
            }
            $this->redirect('/admin/trips');
        }
    }

    public function deleteTrip() {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $this->db->query("DELETE FROM trips WHERE id=$id");
            $this->redirect('/admin/trips');
        }
    }

    // --- Site Settings ---
    public function siteSettings() {
        $data['settings'] = [];
        $res = $this->db->query("SELECT * FROM site_settings");
        while ($row = $res->fetch_assoc()) {
            $data['settings'][$row['page_key']] = $row;
        }
        $this->view('admin/site_settings', $data);
    }

    public function saveSiteSetting() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page_key = $this->db->real_escape_string($_POST['page_key']);
            $title = $this->db->real_escape_string($_POST['title']);
            $content = $this->db->real_escape_string($_POST['content']);

            $image_url = $_POST['image_url'];
            $new_image = $this->uploadFile($_FILES['image'] ?? null, 'site_settings');
            if ($new_image) $image_url = $new_image;
            $image_url = $this->db->real_escape_string($image_url);

            $existing = $this->db->query("SELECT id FROM site_settings WHERE page_key='$page_key'");
            if ($existing->num_rows > 0) {
                $this->db->query("UPDATE site_settings SET title='$title', content='$content', image_url='$image_url' WHERE page_key='$page_key'");
            } else {
                $this->db->query("INSERT INTO site_settings (page_key, title, content, image_url) VALUES ('$page_key', '$title', '$content', '$image_url')");
            }
            $this->redirect('/admin/site-settings');
        }
    }

    // --- Restaurant Management ---
    public function restaurantManage() {
        $data['menu'] = $this->db->query("SELECT * FROM menu_items ORDER BY category");
        $data['orders'] = $this->db->query("SELECT o.*, u.fullname FROM restaurant_orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC");
        $data['edit_menu'] = null;
        if (isset($_GET['edit_menu'])) {
            $id = (int) $_GET['edit_menu'];
            $data['edit_menu'] = $this->db->query("SELECT * FROM menu_items WHERE id = $id")->fetch_assoc();
        }
        $this->view('admin/restaurant_manage', $data);
    }

    public function saveMenuItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->db->real_escape_string($_POST['name']);
            $cat = $this->db->real_escape_string($_POST['category']);
            $price = $_POST['price'];
            $desc = $this->db->real_escape_string($_POST['description']);

            $url = $_POST['image_url'];
            $new_image = $this->uploadFile($_FILES['image'] ?? null, 'menu');
            if ($new_image) $url = $new_image;
            $url = $this->db->real_escape_string($url);

            if (isset($_POST['menu_id']) && !empty($_POST['menu_id'])) {
                $id = (int) $_POST['menu_id'];
                $this->db->query("UPDATE menu_items SET name='$name', category='$cat', price='$price', description='$desc', image_url='$url' WHERE id=$id");
            } else {
                $this->db->query("INSERT INTO menu_items (name, category, price, description, image_url) VALUES ('$name', '$cat', '$price', '$desc', '$url')");
            }
            $this->redirect('/admin/restaurant');
        }
    }

    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $oid = (int) $_POST['order_id'];
            $status = $this->db->real_escape_string($_POST['status']);
            $this->db->query("UPDATE restaurant_orders SET status='$status' WHERE id=$oid");
            $this->redirect('/admin/restaurant');
        }
    }
}
