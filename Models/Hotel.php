<?php

namespace App\Models;

class Hotel extends BaseModel {
    protected $table = 'hotels';

    public function getAvailable() {
        $result = $this->db->query("SELECT * FROM hotels ORDER BY id DESC");
        return $result ? $result : null;
    }

    public function create($name, $location, $description, $amenities, $price, $image_url = '', $booking_url = '') {
        // Validate inputs
        $name = $this->sanitizeString($name);
        $location = $this->sanitizeString($location);
        $description = $this->sanitizeString($description);
        $amenities = $this->sanitizeString($amenities);
        $price = $this->sanitizeFloat($price);
        $image_url = $this->sanitizeString($image_url);
        $booking_url = $this->sanitizeString($booking_url);

        if (!$name || !$location || !$description || $price <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO hotels (name, location, description, amenities, price_per_night, image_url, booking_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssssdss", $name, $location, $description, $amenities, $price, $image_url, $booking_url);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function update($id, $name, $location, $description, $amenities, $price, $image_url = '', $booking_url = '') {
        if (!is_numeric($id) || $id < 1) {
            return false;
        }

        $id = intval($id);
        $name = $this->sanitizeString($name);
        $location = $this->sanitizeString($location);
        $description = $this->sanitizeString($description);
        $amenities = $this->sanitizeString($amenities);
        $price = $this->sanitizeFloat($price);
        $image_url = $this->sanitizeString($image_url);
        $booking_url = $this->sanitizeString($booking_url);

        if (!$name || !$location || !$description || $price <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE hotels SET name = ?, location = ?, description = ?, amenities = ?, price_per_night = ?, image_url = ?, booking_url = ? WHERE id = ?");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssssdss", $name, $location, $description, $amenities, $price, $image_url, $booking_url);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}
?>
