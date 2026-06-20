<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AuthController extends Controller {
    
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    private function validatePassword($password) {
        return !empty($password) && strlen($password) >= 6;
    }
    
    public function loginForm() {
        $this->view('auth/login', ['error' => null]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }
        
        $db = Database::getInstance();
        $error = null;
        
        // Validate inputs
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $this->view('auth/login', ['error' => 'Email and password are required']);
            return;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if (!$this->validateEmail($email)) {
            $this->view('auth/login', ['error' => 'Invalid email format']);
            return;
        }

        // Check Admins with prepared statement
        $stmt = $db->prepare("SELECT id, username, password FROM admins WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $adminResult = $stmt->get_result();
            
            if ($adminResult && $adminResult->num_rows > 0) {
                $admin = $adminResult->fetch_assoc();
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['username'];
                    $this->redirect('/admin');
                    return;
                }
            }
            $stmt->close();
        }

        // Check Users with prepared statement
        $stmt = $db->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $userResult = $stmt->get_result();
            
            if ($userResult && $userResult->num_rows > 0) {
                $user = $userResult->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['fullname'];
                    $this->redirect('/');
                    return;
                }
            }
            $stmt->close();
        }
        
        $this->view('auth/login', ['error' => 'Invalid email or password']);
    }

    public function registerForm() {
        $this->view('auth/register', ['error' => null]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('auth/register', ['error' => null]);
            return;
        }
        
        // Validate inputs
        if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password'])) {
            $this->view('auth/register', ['error' => 'All fields are required']);
            return;
        }
        
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Validate email format
        if (!$this->validateEmail($email)) {
            $this->view('auth/register', ['error' => 'Invalid email format']);
            return;
        }
        
        // Validate password strength
        if (!$this->validatePassword($password)) {
            $this->view('auth/register', ['error' => 'Password must be at least 6 characters long']);
            return;
        }
        
        // Validate fullname
        if (strlen($fullname) < 2 || strlen($fullname) > 100) {
            $this->view('auth/register', ['error' => 'Full name must be between 2 and 100 characters']);
            return;
        }
        
        $db = Database::getInstance();
        
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $check = $stmt->get_result();
            
            if ($check && $check->num_rows > 0) {
                $stmt->close();
                $this->view('auth/register', ['error' => 'Email already exists']);
                return;
            }
            $stmt->close();
        }
        
        // Insert new user with prepared statement
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
            if ($stmt->execute()) {
                $stmt->close();
                $this->redirect('/login');
                return;
            } else {
                $stmt->close();
                $this->view('auth/register', ['error' => 'Registration failed. Please try again.']);
                return;
            }
        }
        
        $this->view('auth/register', ['error' => 'An error occurred during registration']);
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
