<?php

namespace App\Models;

class User extends BaseModel
{
    public function getById($id)
    {
        $id = (int) $id;

        return $this->db
            ->query("SELECT * FROM users WHERE id=$id")
            ->fetch_assoc();
    }

    public function update($id, $data)
    {
        $id = (int) $id;

        $fullname = $data['fullname'];
        $email = $data['email'];

        return $this->db->query("
            UPDATE users 
            SET fullname='$fullname', email='$email' 
            WHERE id=$id
        ");
    }
}