<?php

namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';

    public function findByEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        
        return $row;
    }

    public function create($fullname, $email, $password) {
        // Validate inputs
        $fullname = $this->sanitizeString($fullname);
        $email = $this->sanitizeEmail($email);
        $password = trim($password);

        if (!$fullname || !$email || !$password) {
            return false;
        }

        if (strlen($fullname) < 2 || strlen($fullname) > 100) {
            return false;
        }

        if (strlen($password) < 6) {
            return false;
        }

        // Check if email already exists
        if ($this->findByEmail($email)) {
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $this->db->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function update($id, $fullname, $email) {
        if (!is_numeric($id) || $id < 1) {
            return false;
        }

        $id = intval($id);
        $fullname = $this->sanitizeString($fullname);
        $email = $this->sanitizeEmail($email);

        if (!$fullname || !$email) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssi", $fullname, $email, $id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function verifyPassword($plain_password, $hashed_password) {
        return password_verify($plain_password, $hashed_password);
    }
}
?>
