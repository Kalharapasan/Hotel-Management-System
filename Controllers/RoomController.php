<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class RoomController extends Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        try {
            $data['rooms'] = $this->db->query("SELECT r.*, h.name as hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id WHERE r.status = 'available' ORDER BY r.id DESC");
            $data['hotels'] = $this->db->query("SELECT DISTINCT h.id, h.name FROM hotels h JOIN rooms r ON h.id = r.hotel_id WHERE r.status = 'available'");
            $this->view('rooms', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function book() {
        try {
            if (!isset($_SESSION['user_id'])) {
                $this->redirect('/login');
                return;
            }

            $room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            if ($room_id <= 0) {
                echo "Invalid room ID";
                return;
            }

            $stmt = $this->db->prepare("SELECT r.*, h.name as hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id WHERE r.id = ? AND r.status = 'available'");
            if (!$stmt) {
                throw new \Exception("Database error");
            }

            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $room = $result->fetch_assoc();
                $this->view('rooms', ['room' => $room, 'book' => true]);
            } else {
                echo "Room not found or not available";
            }

            $stmt->close();
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function bookRoom() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/rooms');
            return;
        }

        try {
            if (!isset($_SESSION['user_id'])) {
                $this->redirect('/login');
                return;
            }

            // Validate inputs
            if (empty($_POST['room_id']) || empty($_POST['check_in']) || empty($_POST['check_out'])) {
                echo "All fields are required";
                return;
            }

            $room_id = intval($_POST['room_id']);
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];
            $user_id = intval($_SESSION['user_id']);

            // Validate dates
            if (!strtotime($check_in) || !strtotime($check_out)) {
                echo "Invalid date format";
                return;
            }

            if (strtotime($check_in) >= strtotime($check_out)) {
                echo "Check-out date must be after check-in date";
                return;
            }

            // Verify room exists and is available
            $stmt = $this->db->prepare("SELECT id, status FROM rooms WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Database error");
            }

            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows === 0) {
                $stmt->close();
                echo "Room not found";
                return;
            }

            $room = $result->fetch_assoc();
            if ($room['status'] !== 'available') {
                $stmt->close();
                echo "Room is not available";
                return;
            }

            $stmt->close();

            // Create booking
            $stmt = $this->db->prepare("INSERT INTO bookings (user_id, item_id, item_type, check_in, check_out, status) VALUES (?, ?, 'room', ?, ?, 'pending')");
            if (!$stmt) {
                throw new \Exception("Database error");
            }

            $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);
            if ($stmt->execute()) {
                $stmt->close();
                
                // Update room status
                $update_stmt = $this->db->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
                if ($update_stmt) {
                    $update_stmt->bind_param("i", $room_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                echo "Booking successful! Check your profile for details.";
                // Optionally redirect
                // $this->redirect('/profile');
            } else {
                throw new \Exception("Failed to create booking");
            }

        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
