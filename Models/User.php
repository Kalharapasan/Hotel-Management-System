<?php

namespace App\Models;

class User extends BaseModel {
    public function getById($id) {
        return $this->db->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
    }

    public function update($id, $data) {
        $fullname = $this->db->real_escape_string($data['fullname']);
        $email = $this->db->real_escape_string($data['email']);
        return $this->db->query("UPDATE users SET fullname = '$fullname', email = '$email' WHERE id = $id");
    }
}
