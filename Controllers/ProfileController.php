<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;

class ProfileController extends Controller {
    protected $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->userModel = new User();
    }

    public function index() {
        $db = Database::getInstance();
        $user_id = $_SESSION['user_id'];
        $user = $db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
        
        $bookings = $db->query("SELECT b.*, r.room_type, h.name as hotel_name FROM bookings b JOIN rooms r ON b.item_id = r.id JOIN hotels h ON r.hotel_id = h.id WHERE b.user_id = $user_id AND b.item_type = 'room' ORDER BY b.booking_date DESC");
        
        $orders = $db->query("SELECT * FROM restaurant_orders WHERE user_id = $user_id ORDER BY order_date DESC");

        $this->view('auth/profile', [
            'user' => $user,
            'bookings' => $bookings,
            'orders' => $orders
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email']
            ];
            if ($this->userModel->update($_SESSION['user_id'], $data)) {
                $_SESSION['user_name'] = $data['fullname'];
                $this->redirect('/profile?success=1');
            }
        }
    }
}
