<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AdminController extends Controller {
    protected $db;
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    private $maxFileSize = 5 * 1024 * 1024; // 5MB

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
    }

    private function uploadFile($file, $folder) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        // Validate file size
        if ($file['size'] > $this->maxFileSize) {
            return null;
        }
        
        // Validate file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions)) {
            return null;
        }
        
        // Create unique filename
        $filename = uniqid() . '.' . $ext;
        $destination = 'uploads/' . $folder . '/' . $filename;
        
        // Ensure directory exists
        if (!is_dir('uploads/' . $folder)) {
            mkdir('uploads/' . $folder, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/' . $destination;
        }
        return null;
    }

    private function sanitizeInt($value) {
        return intval($value);
    }

    private function getSafeInt($value) {
        $int = intval($value);
        if ($int < 1) {
            throw new \Exception("Invalid ID");
        }
        return $int;
    }

    public function dashboard() {
        try {
            $data = [
                'hotelCount' => 0,
                'customerCount' => 0,
                'bookingCount' => 0,
                'totalRevenue' => 0,
                'roomStats' => ['total' => 0, 'available' => 0, 'booked' => 0],
                'recentBookings' => null,
                'recentOrders' => null
            ];
            
            // Get hotel count
            $result = $this->db->query("SELECT COUNT(*) as count FROM hotels");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['hotelCount'] = $row['count'] ?? 0;
            }
            
            // Get customer count
            $result = $this->db->query("SELECT COUNT(*) as count FROM users");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['customerCount'] = $row['count'] ?? 0;
            }
            
            // Get booking count
            $result = $this->db->query("SELECT COUNT(*) as count FROM bookings");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['bookingCount'] = $row['count'] ?? 0;
            }
            
            // Get total revenue
            $result = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status='completed'");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['totalRevenue'] = $row['total'] ?? 0;
            }
            
            // Get room stats
            $result = $this->db->query("SELECT COUNT(*) as count FROM rooms");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['roomStats']['total'] = $row['count'] ?? 0;
            }
            
            $result = $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status='available'");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['roomStats']['available'] = $row['count'] ?? 0;
            }
            
            $result = $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status='booked'");
            if ($result) {
                $row = $result->fetch_assoc();
                $data['roomStats']['booked'] = $row['count'] ?? 0;
            }
            
            // Get recent bookings
            $data['recentBookings'] = $this->db->query("SELECT b.*, u.fullname FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.booking_date DESC LIMIT 5");
            
            // Get recent orders
            $data['recentOrders'] = $this->db->query("SELECT r.*, u.fullname FROM restaurant_orders r JOIN users u ON r.user_id = u.id ORDER BY r.order_date DESC LIMIT 5");
            
            $this->view('admin/dashboard', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Hotels ---
    public function hotels() {
        try {
            $data = [
                'hotels' => $this->db->query("SELECT * FROM hotels ORDER BY id DESC"),
                'edit_hotel' => null
            ];
            
            if (isset($_GET['edit'])) {
                $id = $this->getSafeInt($_GET['edit']);
                $stmt = $this->db->prepare("SELECT * FROM hotels WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        $data['edit_hotel'] = $result->fetch_assoc();
                    }
                    $stmt->close();
                }
            }
            $this->view('admin/hotels', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function saveHotel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/hotels');
            return;
        }
        
        try {
            // Validate required fields
            $required = ['name', 'location', 'description', 'amenities', 'price'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("Missing required field: $field");
                }
            }
            
            $name = $_POST['name'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $amenities = $_POST['amenities'];
            $price = floatval($_POST['price']);
            $booking_url = $_POST['booking_url'] ?? '';
            
            $image_url = $_POST['existing_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $new_image = $this->uploadFile($_FILES['image'], 'hotels');
                if ($new_image) {
                    $image_url = $new_image;
                }
            }

            if (isset($_POST['hotel_id']) && !empty($_POST['hotel_id'])) {
                $id = $this->getSafeInt($_POST['hotel_id']);
                $stmt = $this->db->prepare("UPDATE hotels SET name=?, location=?, description=?, amenities=?, price_per_night=?, image_url=?, booking_url=? WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("ssssdssi", $name, $location, $description, $amenities, $price, $image_url, $booking_url, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO hotels (name, location, description, amenities, price_per_night, image_url, booking_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssssdss", $name, $location, $description, $amenities, $price, $image_url, $booking_url);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/hotels');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function deleteHotel() {
        try {
            if (isset($_GET['id'])) {
                $id = $this->getSafeInt($_GET['id']);
                $stmt = $this->db->prepare("DELETE FROM hotels WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/hotels');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Rooms ---
    public function rooms() {
        try {
            $data = [
                'rooms' => $this->db->query("SELECT r.*, h.name as hotel_name, c.category_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id LEFT JOIN room_categories c ON r.category_id = c.id ORDER BY r.id DESC"),
                'hotels' => $this->db->query("SELECT id, name FROM hotels"),
                'categories' => $this->db->query("SELECT id, category_name FROM room_categories"),
                'edit_room' => null
            ];
            
            if (isset($_GET['edit'])) {
                $id = $this->getSafeInt($_GET['edit']);
                $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        $data['edit_room'] = $result->fetch_assoc();
                    }
                    $stmt->close();
                }
            }
            $this->view('admin/rooms', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function saveRoom() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/rooms');
            return;
        }
        
        try {
            $hotel_id = $this->getSafeInt($_POST['hotel_id'] ?? 0);
            $category_id = !empty($_POST['category_id']) ? $this->getSafeInt($_POST['category_id']) : null;
            $type = $_POST['room_type'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $status = $_POST['status'] ?? 'available';
            $amenities = $_POST['amenities'] ?? '';

            $image_url = $_POST['existing_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $new_image = $this->uploadFile($_FILES['image'], 'rooms');
                if ($new_image) {
                    $image_url = $new_image;
                }
            }

            if (isset($_POST['room_id']) && !empty($_POST['room_id'])) {
                $id = $this->getSafeInt($_POST['room_id']);
                $stmt = $this->db->prepare("UPDATE rooms SET hotel_id=?, category_id=?, room_type=?, price_per_night=?, status=?, amenities=?, image_url=? WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("iisdssi", $hotel_id, $category_id, $type, $price, $status, $amenities, $image_url, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO rooms (hotel_id, category_id, room_type, price_per_night, status, amenities, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("iisdsss", $hotel_id, $category_id, $type, $price, $status, $amenities, $image_url);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/rooms');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function deleteRoom() {
        try {
            if (isset($_GET['id'])) {
                $id = $this->getSafeInt($_GET['id']);
                $stmt = $this->db->prepare("DELETE FROM rooms WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/rooms');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Categories ---
    public function categories() {
        try {
            $data['categories'] = $this->db->query("SELECT * FROM room_categories ORDER BY id DESC");
            $this->view('admin/categories', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
            return;
        }
        
        try {
            $name = $_POST['category_name'] ?? '';
            $desc = $_POST['description'] ?? '';
            
            if (empty($name)) {
                throw new \Exception("Category name is required");
            }
            
            if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
                $id = $this->getSafeInt($_POST['category_id']);
                $stmt = $this->db->prepare("UPDATE room_categories SET category_name=?, description=? WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("ssi", $name, $desc, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO room_categories (category_name, description) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ss", $name, $desc);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/categories');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function deleteCategory() {
        try {
            if (isset($_GET['id'])) {
                $id = $this->getSafeInt($_GET['id']);
                $stmt = $this->db->prepare("DELETE FROM room_categories WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/categories');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Customers ---
    public function customers() {
        try {
            $data = [
                'customers' => $this->db->query("SELECT * FROM users ORDER BY created_at DESC"),
                'edit_customer' => null
            ];
            
            if (isset($_GET['edit'])) {
                $id = $this->getSafeInt($_GET['edit']);
                $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        $data['edit_customer'] = $result->fetch_assoc();
                    }
                    $stmt->close();
                }
            }
            $this->view('admin/customers', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function saveCustomer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/customers');
            return;
        }
        
        try {
            $id = $this->getSafeInt($_POST['user_id'] ?? 0);
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (empty($fullname) || empty($email)) {
                throw new \Exception("Name and email are required");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Invalid email format");
            }
            
            $stmt = $this->db->prepare("UPDATE users SET fullname=?, email=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param("ssi", $fullname, $email, $id);
                $stmt->execute();
                $stmt->close();
            }
            $this->redirect('/admin/customers');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function deleteCustomer() {
        try {
            if (isset($_GET['id'])) {
                $id = $this->getSafeInt($_GET['id']);
                $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/customers');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Employees ---
    public function employees() {
        try {
            $data = [
                'employees' => $this->db->query("SELECT * FROM employees ORDER BY id DESC"),
                'edit_emp' => null
            ];
            
            if (isset($_GET['edit'])) {
                $id = $this->getSafeInt($_GET['edit']);
                $stmt = $this->db->prepare("SELECT * FROM employees WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        $data['edit_emp'] = $result->fetch_assoc();
                    }
                    $stmt->close();
                }
            }
            $this->view('admin/employees', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function saveEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/employees');
            return;
        }
        
        try {
            $fullname = $_POST['fullname'] ?? '';
            $role = $_POST['role'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $salary = floatval($_POST['salary'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            $image_url = $_POST['existing_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $new_image = $this->uploadFile($_FILES['image'], 'employees');
                if ($new_image) {
                    $image_url = $new_image;
                }
            }

            if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
                $id = $this->getSafeInt($_POST['employee_id']);
                $stmt = $this->db->prepare("UPDATE employees SET fullname=?, role=?, email=?, phone=?, salary=?, status=? WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("ssssdssi", $fullname, $role, $email, $phone, $salary, $status, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO employees (fullname, role, email, phone, salary, status) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssssds", $fullname, $role, $email, $phone, $salary, $status);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/employees');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function deleteEmployee() {
        try {
            if (isset($_GET['id'])) {
                $id = $this->getSafeInt($_GET['id']);
                $stmt = $this->db->prepare("DELETE FROM employees WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->redirect('/admin/employees');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // --- Bookings & Billing ---
    public function bookings() {
        try {
            $data['bookings'] = $this->db->query("SELECT b.*, u.fullname as customer_name, u.email as customer_email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.booking_date DESC");
            $this->view('admin/bookings', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function generateBill() {
        try {
            if (isset($_GET['booking_id'])) {
                $booking_id = $this->getSafeInt($_GET['booking_id']);
                $stmt = $this->db->prepare("SELECT b.*, u.fullname, u.email FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.id = ?");
                
                if ($stmt) {
                    $stmt->bind_param("i", $booking_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $booking = $result->fetch_assoc();
                    $stmt->close();
                    
                    if (!$booking) {
                        throw new \Exception("Booking not found");
                    }
                    
                    $price = 0;
                    if ($booking['item_type'] == 'room') {
                        $stmt = $this->db->prepare("SELECT price_per_night FROM rooms WHERE id = ?");
                        if ($stmt) {
                            $item_id = $booking['item_id'];
                            $stmt->bind_param("i", $item_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $room = $result->fetch_assoc();
                            $price = $room['price_per_night'] ?? 0;
                            $stmt->close();
                        }
                    }
                    
                    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) as total FROM restaurant_orders WHERE user_id = ? AND status != 'delivered'");
                    if ($stmt) {
                        $user_id = $booking['user_id'];
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $orders = $result->fetch_assoc();
                        $restaurant_total = $orders['total'] ?? 0;
                        $stmt->close();
                    }
                    
                    $data = [
                        'booking' => $booking,
                        'room_price' => $price,
                        'restaurant_total' => $restaurant_total,
                        'total' => $price + $restaurant_total
                    ];
                    
                    $this->view('admin/bill', $data);
                }
            }
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function completeCheckout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/billing');
            return;
        }
        
        try {
            $booking_id = $this->getSafeInt($_POST['booking_id'] ?? 0);
            $user_id = $this->getSafeInt($_POST['user_id'] ?? 0);
            $amount = floatval($_POST['total_amount'] ?? 0);
            
            // Update booking status
            $stmt = $this->db->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $booking_id);
                $stmt->execute();
                $stmt->close();
            }
            
            // Record payment
            $stmt = $this->db->prepare("INSERT INTO payments (booking_id, amount, status, payment_method) VALUES (?, ?, 'completed', 'Cash')");
            if ($stmt) {
                $stmt->bind_param("id", $booking_id, $amount);
                $stmt->execute();
                $stmt->close();
            }
            
            // Update restaurant orders
            $stmt = $this->db->prepare("UPDATE restaurant_orders SET status = 'delivered' WHERE user_id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
            }
            
            $this->redirect('/admin/billing');
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function billing() {
        try {
            $data['payments'] = $this->db->query("SELECT p.*, b.item_type, u.fullname FROM payments p JOIN bookings b ON p.booking_id = b.id JOIN users u ON b.user_id = u.id ORDER BY p.created_at DESC");
            $this->view('admin/billing', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    // Placeholder methods for missing features
    public function flights() {
        $this->view('admin/flights', []);
    }

    public function trips() {
        $this->view('admin/trips', []);
    }

    public function siteSettings() {
        $this->view('admin/site_settings', []);
    }
}
