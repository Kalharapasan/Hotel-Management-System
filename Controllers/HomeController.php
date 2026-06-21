<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        try {
            $data = [];
            
            // Get featured hotels
            $result = $this->db->query("SELECT * FROM hotels LIMIT 6");
            $data['hotels'] = $result ? $result : [];
            
            // Get featured rooms
            $result = $this->db->query("SELECT r.*, h.name as hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id WHERE r.status = 'available' LIMIT 6");
            $data['rooms'] = $result ? $result : [];
            
            // Get gallery images
            $result = $this->db->query("SELECT * FROM gallery LIMIT 6");
            $data['gallery'] = $result ? $result : [];
            
            $this->view('home', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function about() {
        try {
            $this->view('about', []);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function contact() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate inputs
                if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
                    $this->view('contact', ['error' => 'All fields are required']);
                    return;
                }

                $name = $_POST['name'];
                $email = $_POST['email'];
                $message = $_POST['message'];

                // Validate email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->view('contact', ['error' => 'Invalid email format']);
                    return;
                }

                // Validate message length
                if (strlen($message) < 10 || strlen($message) > 5000) {
                    $this->view('contact', ['error' => 'Message must be between 10 and 5000 characters']);
                    return;
                }

                // You can process the contact form here
                // For now, just display success message
                $this->view('contact', ['success' => 'Thank you for your message. We will contact you soon.']);
            } else {
                $this->view('contact', []);
            }
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
