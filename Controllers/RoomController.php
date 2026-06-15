<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class RoomController extends Controller {
    public function index() {
        $db = Database::getInstance();
        $rooms = $db->query("SELECT r.*, h.name as hotel_name, c.category_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id LEFT JOIN room_categories c ON r.category_id = c.id WHERE r.status = 'available'");
        $categories = $db->query("SELECT * FROM room_categories");
        
        $this->view('rooms', [
            'rooms' => $rooms,
            'categories' => $categories
        ]);
    }

    public function book() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        
        $db = Database::getInstance();
        $room_id = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $check_in = $_GET['check_in'] ?? null;
        $check_out = $_GET['check_out'] ?? null;
        
        // Simple booking logic
        $db->query("INSERT INTO bookings (user_id, item_id, item_type, check_in, check_out, status) VALUES ($user_id, $room_id, 'room', '$check_in', '$check_out', 'pending')");
        $db->query("UPDATE rooms SET status = 'booked' WHERE id = $room_id");
        
        $this->redirect('/profile?booked=1');
    }
}
