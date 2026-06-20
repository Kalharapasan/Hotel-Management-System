<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ProfileController extends Controller {
    protected $db;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
    }

    public function index() {
        try {
            $user_id = intval($_SESSION['user_id']);
            
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Database error: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $this->view('auth/profile', ['user' => $user, 'message' => null]);
            } else {
                $this->redirect('/logout');
            }
            
            $stmt->close();
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        try {
            $user_id = intval($_SESSION['user_id']);
            
            // Validate input
            if (empty($_POST['fullname']) || empty($_POST['email'])) {
                $this->index();
                return;
            }

            $fullname = $_POST['fullname'];
            $email = $_POST['email'];

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format";
                return;
            }

            // Validate fullname length
            if (strlen($fullname) < 2 || strlen($fullname) > 100) {
                echo "Full name must be between 2 and 100 characters";
                return;
            }

            // Check if email is already used by another user
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            if ($stmt) {
                $stmt->bind_param("si", $email, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $stmt->close();
                    echo "Email is already in use";
                    return;
                }
                
                $stmt->close();
            }

            // Update user profile
            $stmt = $this->db->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Database error: " . $this->db->error);
            }

            $stmt->bind_param("ssi", $fullname, $email, $user_id);
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $fullname;
                $stmt->close();
                $this->redirect('/profile');
            } else {
                throw new \Exception("Failed to update profile");
            }

        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
