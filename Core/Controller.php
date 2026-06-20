<?php
namespace App\Core;
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require_once "Views/$view.php";
    }
    protected function redirect($url) {
        // Auto-prefix root-relative URLs (e.g. '/login') with the app's base
        // path, so every existing redirect('/...') call works correctly
        // whether the app is hosted at the domain root or in a subfolder.
        if (defined('BASE_URL') && BASE_URL !== '' && strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = BASE_URL . $url;
        }
        header("Location: $url");
        exit();
    }
}
