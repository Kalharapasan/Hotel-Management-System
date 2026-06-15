<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class RestaurantController extends Controller {
    public function index() {
        $db = Database::getInstance();
        
        $message = "";
        if (isset($_GET['order'])) {
            if (!isset($_SESSION['user_id'])) {
                $this->redirect('/login');
            }
            
            $item_id = $_GET['order'];
            $user_id = $_SESSION['user_id'];
            $item = $db->query("SELECT price FROM menu_items WHERE id = $item_id")->fetch_assoc();
            
            if ($item) {
                $price = $item['price'];
                $db->query("INSERT INTO restaurant_orders (user_id, total_amount, status) VALUES ($user_id, $price, 'pending')");
                $message = "Order placed successfully! We're preparing your meal.";
            }
        }

        $menu = $db->query("SELECT * FROM menu_items ORDER BY category");

        $this->view('restaurant', [
            'menu' => $menu,
            'message' => $message
        ]);
    }
}
