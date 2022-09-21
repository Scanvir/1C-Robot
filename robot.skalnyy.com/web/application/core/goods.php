<?php

class Goods
{
    public static function getCategory() {
        $db = new Database();
        $query = "SELECT Code, Name, Position FROM branch where code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        
        foreach ($stmt as $row)
        {
            $result= [
                'Code' => $row['Code'],
                'Name' => $row['Name'],
                'Position' => $row['Position'],
            ];
        }
    
        return $result;
    }
    public static function getMainCatalog() {
        $db = new Database();
        $query = "SELECT Code, ParentCode, Name FROM category order by ParentCode, Name";
        $stmt = $db->query($query);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            if ($row['ParentCode'] == 0)
            {
                $result[] = ['Code' => $row['Code'], 'Name' => $row['Name'], 'Sub' => []];
            } else {
                $key = array_search($row['ParentCode'], array_column($result, 'Code'));
                $result[$key]['Sub'][] = ['Code' => $row['Code'], 'Name' => $row['Name']];
            }
        }

        return $result;
    }
    public static function getProduct($code) {
        $db = new Database();
        $query = "SELECT Code FROM product where ParentCode = :code order by Name";
        $stmt = $db->query($query, [':code' => $code]);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result[] = [
                'Code' => $row['Code'],
            ];
        }

        return $result;
    }
    public static function searchProducts($search) {
        $db = new Database();
        $query = "SELECT Code, ParentCode, Name FROM product where name like :search order by ParentCode, Name";
        $stmt = $db->query($query, [':search' => "%" . $search . "%"]);
        
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