<?php

namespace App\Models;

class Category extends BaseModel {
    public function getAll() {
        return $this->db->query("SELECT * FROM room_categories ORDER BY id DESC");
    }

    public function create($name, $description) {
        $name = $this->db->real_escape_string($name);
        $description = $this->db->real_escape_string($description);
        return $this->db->query("INSERT INTO room_categories (category_name, description) VALUES ('$name', '$description')");
    }

    public function delete($id) {
        return $this->db->query("DELETE FROM room_categories WHERE id = $id");
    }
}
