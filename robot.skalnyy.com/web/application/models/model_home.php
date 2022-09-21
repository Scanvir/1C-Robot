<?php

class Model_Home extends Model
{

    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function getUser($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->query($query, [':id' => $id]);

        return $stmt;
    }
}