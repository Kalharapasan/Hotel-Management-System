<?php
namespace App\Core;
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require_once "Views/$view.php";
    }
    protected function redirect($url) {
        if (strpos($url, '/') === 0) {
            $url = BASE_URL . $url;
        }
        header("Location: $url");
        exit();
    }
}
