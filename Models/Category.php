<?php

namespace App\Models;

class Category extends BaseModel {
    protected $table = 'room_categories';

    public function findByName($name) {
        $name = $this->sanitizeString($name);
        
        if (!$name) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM room_categories WHERE category_name = ?");
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        
        return $row;
    }

    public function create($name, $description = '') {
        // Validate inputs
        $name = $this->sanitizeString($name);
        $description = $this->sanitizeString($description);

        if (!$name || strlen($name) < 2) {
            return false;
        }

        // Check if category already exists
        if ($this->findByName($name)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO room_categories (category_name, description) VALUES (?, ?)");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ss", $name, $description);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function update($id, $name, $description = '') {
        if (!is_numeric($id) || $id < 1) {
            return false;
        }

        $id = intval($id);
        $name = $this->sanitizeString($name);
        $description = $this->sanitizeString($description);

        if (!$name || strlen($name) < 2) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE room_categories SET category_name = ?, description = ? WHERE id = ?");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssi", $name, $description, $id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}
?>
