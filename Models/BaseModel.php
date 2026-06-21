<?php

namespace App\Models;

use App\Core\Database;

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById($id) {
        if (!is_numeric($id) || $id < 1) {
            return null;
        }

        $id = intval($id);
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        
        return $row;
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $result ? $result : null;
    }

    public function delete($id) {
        if (!is_numeric($id) || $id < 1) {
            return false;
        }

        $id = intval($id);
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    protected function sanitizeString($string) {
        return trim(htmlspecialchars($string, ENT_QUOTES, 'UTF-8'));
    }

    protected function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    }

    protected function sanitizeInt($value) {
        return intval($value);
    }

    protected function sanitizeFloat($value) {
        return floatval($value);
    }
}
?>
