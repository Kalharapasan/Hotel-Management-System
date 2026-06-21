<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class RestaurantController extends Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        try {
            $data = [];
            
            // Get all menu items
            $result = $this->db->query("SELECT * FROM menu_items ORDER BY category ASC, name ASC");
            $data['menu_items'] = $result ? $result : [];
            
            // Check if user is logged in
            $data['user_logged_in'] = isset($_SESSION['user_id']);
            $data['user_id'] = $_SESSION['user_id'] ?? null;
            
            $this->view('restaurant', $data);
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function orderItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/restaurant');
            return;
        }

        try {
            if (!isset($_SESSION['user_id'])) {
                $this->redirect('/login');
                return;
            }

            // Validate inputs
            if (empty($_POST['item_id']) || empty($_POST['quantity'])) {
                echo "Invalid item or quantity";
                return;
            }

            $item_id = intval($_POST['item_id']);
            $quantity = intval($_POST['quantity']);
            $user_id = intval($_SESSION['user_id']);

            if ($quantity < 1 || $quantity > 100) {
                echo "Quantity must be between 1 and 100";
                return;
            }

            // Get menu item
            $stmt = $this->db->prepare("SELECT id, price FROM menu_items WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Database error");
            }

            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows === 0) {
                $stmt->close();
                echo "Item not found";
                return;
            }

            $item = $result->fetch_assoc();
            $total_amount = $item['price'] * $quantity;
            $stmt->close();

            // Create restaurant order
            $order_stmt = $this->db->prepare("INSERT INTO restaurant_orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
            if (!$order_stmt) {
                throw new \Exception("Database error");
            }

            $order_stmt->bind_param("id", $user_id, $total_amount);
            if ($order_stmt->execute()) {
                $order_stmt->close();
                echo "Order placed successfully!";
            } else {
                throw new \Exception("Failed to place order");
            }

        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
