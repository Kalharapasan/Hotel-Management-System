<?php

namespace App\Models;

class Hotel extends BaseModel {
    public function getAll() {
        return $this->db->query("SELECT * FROM hotels ORDER BY created_at DESC");
    }

    public function getHero() {
        return $this->db->query("SELECT * FROM site_settings WHERE page_key='home_hero'")->fetch_assoc();
    }

    public function getCategories() {
        return $this->db->query("SELECT * FROM room_categories");
    }

    public function getGallery() {
        return $this->db->query("SELECT * FROM gallery LIMIT 6");
    }
}
