<?php

session_start();

require_once 'config/db.php';
require_once 'app/Core/Router.php';

use App\Core\Router;

$router = new Router();