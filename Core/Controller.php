<?php
namespace App\Core;
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require_once "Views/$view.php";
    }
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
}
