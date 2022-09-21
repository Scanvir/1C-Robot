<?php

class Model_Search extends Model
{

    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function getProduct($code) {
        $query = "SELECT Code FROM product where ParentCode = :code order by Name";
        $stmt = $this->db->query($query, [':code' => $code]);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result[] = [
                'Code' => $row['Code'],
            ];
        }

        return $result;
    }
}