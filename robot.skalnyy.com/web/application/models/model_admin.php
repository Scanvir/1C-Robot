<?php

class Model_Admin extends Model
{

    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function getProductCode() {
        $query = "SELECT Code, Morion FROM codeMorion order by Morion";
        $stmt = $this->db->query($query);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result[] = [
                'Code' => $row['Code'],
                'Morion' => (int)$row['Morion'],
            ];
        }
    
        return $result;
    }
    public function updateBranchReserve($code, $reserve) {
        if ($reserve == 'true') 
            $reserve = 1;
        else
            $reserve = 0;

        $query = "UPDATE branch set Reserve = :reserve WHERE Code = :code";
        $stmt = $this->db->execute($query, [':reserve' => $reserve, ':code' => $code]);
    }
    public function photoCount(){
        $query = "SELECT count(1) product FROM product";
        $stmt = $this->db->query($query);
        
        $product = $stmt[0]['product'];
        
        $query = "SELECT count(1) morion FROM photos where left (url, 8) = '/images/'";
        $stmt = $this->db->query($query);
        
        $morion = $stmt[0]['morion'];
        
        $query = "SELECT count(distinct product) without FROM Rests
            left join photos ps on ps.code = product
            where ps.code is null";
        $stmt = $this->db->query($query);
        
        $without = $stmt[0]['without'];
        
        return [
            'product' => $product,
            'morion' => $morion,
            'without' => $without,
        ];
    }
    public function getProductWithoutPhotoToXLS(){
        $query = "SELECT distinct r.product, pt.Name, pt.Producer FROM Rests r
            left join photos ps on ps.code = product
            left join product pt on pt.code = product
            where ps.code is null";
        $stmt = $this->db->query($query);
        foreach($stmt as $row){
            $result[] = [
                'Code' => $row['product'],
                'Name' => $row['Name'],
                'Producer' => $row['Producer'],
            ];
        }
        return $result;
    }
    public function top20product(){
        $query = "SELECT productID, p.name, count(1) cnt FROM `active_product` a
            left join product p on p.Code = a.productID
            group by productID  
            ORDER BY `count(1)`  DESC
            limit 20";
        $result = [];
        $stmt = $this->db->query($query);
        foreach($stmt as $row){
            $result[] = [
                'Code' => $row['product'],
                'Name' => $row['Name'],
                'Cnt' => $row['cnt'],
            ];
        }
        return $result;
    }
}