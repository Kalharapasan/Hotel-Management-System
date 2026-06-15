<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Hotel;

class HomeController extends Controller {
    public function index() {
        $model = new Hotel();
        $db = Database::getInstance();
        
        $search = $_GET['search'] ?? '';
        $location = $_GET['location'] ?? '';
        
        $query = "SELECT * FROM hotels WHERE 1=1";
        if (!empty($search)) $query .= " AND name LIKE '%$search%'";
        if (!empty($location)) $query .= " AND location LIKE '%$location%'";
        $query .= " ORDER BY created_at DESC";
        
        $hotels = $db->query($query);
        
        $data = [
            'hero' => $model->getHero(),
            'hotels' => $hotels,
            'categories' => $model->getCategories(),
            'gallery' => $model->getGallery(),
            'employees' => $this->db->query("SELECT * FROM employees WHERE status='active' LIMIT 4")
        ];
        $this->view('home', $data);
    }

    public function about() {
        $this->view('about');
    }

    public function contact() {
        $this->view('contact');
    }
}
