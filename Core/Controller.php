<?php

namespace App\Core;

class Controller
{
    public function view($view)
    {
        require "Views/$view.php";
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}