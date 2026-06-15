<?php
session_start();

// Autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

require_once 'config/db.php';
use App\Core\Router;

$router = new Router();

// Public Routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/rooms', 'RoomController@index');
$router->add('GET', '/rooms/book', 'RoomController@book');
$router->add('GET', '/restaurant', 'RestaurantController@index');
$router->add('GET', '/about', 'HomeController@about');
$router->add('GET', '/contact', 'HomeController@contact');

// Auth Routes
$router->add('GET', '/login', 'AuthController@loginForm');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@registerForm');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/logout', 'AuthController@logout');

// User Profile Routes
$router->add('GET', '/profile', 'ProfileController@index');
$router->add('POST', '/profile/update', 'ProfileController@update');

// Admin Routes
$router->add('GET', '/admin', 'AdminController@dashboard');

// Admin - Hotels
$router->add('GET', '/admin/hotels', 'AdminController@hotels');
$router->add('POST', '/admin/save-hotel', 'AdminController@saveHotel');
$router->add('GET', '/admin/delete-hotel', 'AdminController@deleteHotel');

// Admin - Rooms & Categories
$router->add('GET', '/admin/rooms', 'AdminController@rooms');
$router->add('POST', '/admin/save-room', 'AdminController@saveRoom');
$router->add('GET', '/admin/delete-room', 'AdminController@deleteRoom');
$router->add('GET', '/admin/categories', 'AdminController@categories');
$router->add('POST', '/admin/save-category', 'AdminController@saveCategory');
$router->add('GET', '/admin/delete-category', 'AdminController@deleteCategory');

// Admin - Customers
$router->add('GET', '/admin/customers', 'AdminController@customers');
$router->add('POST', '/admin/save-customer', 'AdminController@saveCustomer');
$router->add('GET', '/admin/delete-customer', 'AdminController@deleteCustomer');

// Admin - Others
$router->add('GET', '/admin/employees', 'AdminController@employees');
$router->add('POST', '/admin/save-employee', 'AdminController@saveEmployee');
$router->add('GET', '/admin/delete-employee', 'AdminController@deleteEmployee');
$router->add('GET', '/admin/flights', 'AdminController@flights');
$router->add('GET', '/admin/trips', 'AdminController@trips');
$router->add('GET', '/admin/bookings', 'AdminController@bookings');
$router->add('GET', '/admin/bill', 'AdminController@generateBill');
$router->add('POST', '/admin/checkout', 'AdminController@completeCheckout');
$router->add('GET', '/admin/billing', 'AdminController@billing');
$router->add('GET', '/admin/site-settings', 'AdminController@siteSettings');

// Handle the request
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$router->handle($method, $uri);
