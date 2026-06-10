<?php

namespace App\Models;

use App\Core\Database;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // optional helper: run query
    protected function query($sql)
    {
        return $this->db->query($sql);
    }

    // optional helper: fetch single row
    protected function fetchOne($sql)
    {
        return $this->db->query($sql)->fetch_assoc();
    }
}