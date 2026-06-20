<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class FlightController extends Controller {
    public function index() {
        $db = Database::getInstance();
        $flights = $db->query("SELECT * FROM flights ORDER BY price ASC");

        $this->view('flights', [
            'flights' => $flights
        ]);
    }
}
