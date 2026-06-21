<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class TripController extends Controller {
    public function index() {
        $db = Database::getInstance();
        $trips = $db->query("SELECT * FROM trips ORDER BY created_at DESC");

        $this->view('trips', [
            'trips' => $trips
        ]);
    }
}
