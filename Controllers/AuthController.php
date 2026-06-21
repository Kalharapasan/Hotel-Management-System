<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AuthController extends Controller {
    public function loginForm() {
        $this->view('auth/login');
    }

    public function login() {
        $db = Database::getInstance();
        $email = $db->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Check Admins
        $adminResult = $db->query("SELECT * FROM admins WHERE email = '$email'");
        if ($adminResult->num_rows > 0) {
            $admin = $adminResult->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['username'];
                $this->redirect('/admin');
            }
        }

        // Check Users
        $userResult = $db->query("SELECT * FROM users WHERE email = '$email'");
        if ($userResult->num_rows > 0) {
            $user = $userResult->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $this->redirect('/');
            }
        }
        $this->view('auth/login', ['error' => 'Invalid credentials']);
    }

    public function registerForm() {
        $this->view('auth/register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            $fullname = $db->real_escape_string($_POST['fullname']);
            $email = $db->real_escape_string($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $check = $db->query("SELECT id FROM users WHERE email = '$email'");
            if ($check->num_rows > 0) {
                $this->view('auth/register', ['error' => 'Email already exists']);
                return;
            }

            $db->query("INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')");
            $this->redirect('/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
