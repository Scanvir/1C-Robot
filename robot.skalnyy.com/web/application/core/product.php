<?php

class Product
{
    public static function getProductByCode($code, $userID = -1) {
        $db = new Database();
        $query = "INSERT into active_product (userID, productID, Upd) values (:userID, :productID, now())";
        $db->execute($query, [':userID' => $userID, 'productID' => $code]);
        
        $query = "SELECT Code, ParentCode, Name, TradeMark, Producer, Delivery FROM product where code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result= [
                'Code' => $row['Code'],
                'ParentCode' => $row['ParentCode'],
                'Name' => $row['Name'],
                'TradeMark' => $row['TradeMark'],
                'Producer' => $row['Producer'],
                'Delivery' => $row['Delivery'],
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
    public static function getProductRest($productCode, $branchCode) {
        $endpoint = 'https://api.bilaromashka.com.ua/reserve/goods/rest.php?productCode='.$productCode.'&branchCode='.$branchCode;
				
		$headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
    }
    public static function search($search) {
        $db = new Database();
        
        $search = str_replace('\'', '', $search);
        $search = str_replace('"', '', $search);
        
        $query = "SELECT Code, ParentCode, Name, TradeMark FROM product where code like :search or name like :search order by ParentCode, Name";
        $stmt = $db->query($query, [':search' => '%'.$search.'%']);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result[] = [
                'Code' => $row['Code'],
                'ParentCode' => $row['ParentCode'],
                'Name' => $row['Name'],
                'TradeMark' => $row['TradeMark'],
            ];
        }

        return $result;
    }
    public static function searchName($search) {
        $db = new Database();
        
        $search = str_replace('\'', '', $search);
        $search = str_replace('"', '', $search);
        
        $query = "SELECT Name FROM product where code like :search or name like :search order by Name LIMIT 100";
        $stmt = $db->query($query, [':search' => '%'.$search.'%']);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
            $result[] = [
                'Name' => $row['Name'],
            ];
        }

        return $result;
    }
    public static function getRests($code) {
		$endpoint = 'https://api.bilaromashka.com.ua/reserve/goods/rests.php?code=' . $code;
				
		$headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
	}
	public static function GetAllRests($branchCode) {
	    $db = new Database();
		$db->execute("DELETE FROM `Rests` WHERE branch = '".$branchCode."'");
		
		$endpoint = 'https://api.bilaromashka.com.ua/reserve/goods/allrests.php?branchCode=' . $branchCode;
				
		$headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$result = json_decode($result, true);
		    
        $query = 'INSERT INTO `Rests` (branch, product, qty, minPrice, maxPrice, Upd) VALUES ';
        $sql = array(); 
        
        foreach ($result as $row){
            $sql[] = '("'.$branchCode.'", "'.$row['product'].'",'.$row['qty'].','.$row['minPrice'].','.$row['maxPrice'].',Now())';
        }
        
        $db->execute($query.implode(',', $sql));
	}
	public static function UpdateRests(){
        self::asyncInclude('/www/wwwroot/bilaromashka.com.ua/app/updateRests.php');
	}
	private static function asyncInclude($filename, $options = '') {
        exec("php -f {$filename} {$options} >> /dev/null &");
    }
	public static function getMorionCode($code) {
        $db = new Database();
        $query = "SELECT Morion FROM codeMorion where Code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        
        if (count($stmt) > 0)
        {
            return $stmt[0]['Morion'];
        }
    
        return 0;
    }
    public static function getPhoto($code){
	    $photo = self::ReadPhoto($code);
        if ($photo)
            return $photo;
        
        $db = new Database();
        $morion = self::getMorionCode($code);

        $query = "SELECT url FROM morionPhotos WHERE morion = :morion";
        $stmt = $db->query($query, [':morion' => $morion]);
        foreach ($stmt as $key => $photo){
            $url = $photo['url'];
            $path = site_path . 'web/images/'.$code.'.jpg';
            
            if(!file_exists($path)){
                file_put_contents($path, file_get_contents($url));
            }
            
            if(file_exists($path)){
                $url = '/images/'.$code.'.jpg';
                sefl::WritePhoto($code, $url);
                return ['url' => $url];
            }
        }
        return ['url' => "/images/noPhoto.png"];
	}
	
	// Обновляем товары
	public static function UpdateProducts(){
	    $data = self::getProduct();
	    
	    $fp = fopen('/tmp/product.csv', 'w');
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

	    $db = new Database();
	    
	    $query = "DROP TABLE IF EXISTS new_product";
	    $stmt = $db->execute($query);
        
        $query = "CREATE TABLE new_product LIKE product";
        $stmt = $db->execute($query);
        
	    $query = "LOAD DATA LOCAL INFILE '/tmp/product.csv'
             INTO TABLE new_product
             FIELDS TERMINATED BY ',' 
             OPTIONALLY ENCLOSED BY '\"'
             LINES TERMINATED BY '\\n'";
        $db->execute($query);
        
        $query = "delete FROM product where Code not in (SELECT Code FROM new_product)";
        $stmt = $db->execute($query);
        
        $query = "insert into product SELECT * FROM new_product where code not in (select code from product)";
        $stmt = $db->execute($query);
        
        $query = "update product c left join new_product n on n.code = c.code set c.ParentCode = n.ParentCode, c.Name = n.Name, c.TradeMark = n.TradeMark, c.Producer = n.Producer, c.Delivery = n.Delivery where c.ParentCode <> n.ParentCode or c.Name <> n.Name or c.TradeMark <> n.TradeMark or c.Producer <> n.Producer or c.Delivery <> n.Delivery";
        $stmt = $db->execute($query);
        
        $query = "DROP TABLE IF EXISTS new_product";
        $stmt = $db->execute($query);
        
        return count($data);
    }
	// Забираем из ЦО товары
	private static function getProduct(){
		$endpoint = 'https://api.bilaromashka.com.ua/db/goods/index.php';
				
		$headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
	}
	// Обновляем каталог
	public static function UpdateCategory(){
	    $data = self::getCatalog();
	    $db = new Database();
        $query = "DROP TABLE IF EXISTS new_category";
        $stmt = $db->execute($query);
        
        $query = "CREATE TABLE new_category LIKE category";
        $stmt = $db->execute($query);
        
        $query = "INSERT INTO new_category VALUES (:Code, :ParentCode, :Name)";
        foreach ($data as $row){
            $stmt = $db->execute($query, [
                ':Code' => $row['Code'],
                ':ParentCode' => $row['ParentCode'],
                ':Name' => $row['Name'],
            ]);
        }
        
        $query = "delete FROM category where Code not in (SELECT Code FROM new_category)";
        $stmt = $db->execute($query);
        
        $query = "insert into category SELECT * FROM new_category where code not in (select code from category)";
        $stmt = $db->execute($query);
        
        $query = "update category c left join new_category n on n.code = c.code set c.ParentCode = n.ParentCode, c.Name = n.Name where c.ParentCode <> n.ParentCode and c.Name <> n.Name";
        $stmt = $db->execute($query);
        
        $query = "DROP TABLE IF EXISTS new_category";
        $stmt = $db->execute($query);
        
        return $data;
    }
	// Забираем каталог из ЦО
	private static function getCatalog(){
		$endpoint = 'https://api.bilaromashka.com.ua/db/goods/catalog.php';
				
		$headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
	}
    // Формируем YML фид
	public static function getYml(){
        $db = new Database();
        

        $query = "SELECT Code, ParentCode, Name FROM `category`";
        $stmt = $db->query($query);
        foreach ($stmt as $key => $item) {
            $cat[] = [
                'category' => [
                    '[id]' => $item['Code'], 
                    '[parentId]' => $item['ParentCode'], 
                    'name1' => $item['Name']
                    ]
                ];
        }
        
        $query = "SELECT r.product, p.ParentCode, f.url, p.Name, min(minPrice) minPrice, max(maxPrice) maxPrice FROM `Rests` r
            left join product p on p.Code = product
            left join photos f on f.Code = product
            group by r.product, p.ParentCode, f.url, p.Name
            ";
        $stmt = $db->query($query);
        foreach ($stmt as $key => $item) {
           $photo = '';
           if($item['url'] != '')
                $photo = 'https://www.bilaromashka.com.ua'.$item['url'];
                
            $prod[] = [
                'offer' => [
                    '[id]' => $item['product'], 
                    '[available]' => 'true',
                    'url' => 'https://www.bilaromashka.com.ua/search/product/?code='.$item['product'],
                    'price_min' => $item['minPrice'],
                    'price' => $item['maxPrice'],
                    'currencyId' => 'UAH',
                    'categoryId' => $item['ParentCode'],
                    'picture' => $photo,
                    'name' => $item['Name'],
                    ]
                
                ];
        }
        
	    $xml_data = Xml::newXml();
	    
	    $data = ['shop' => [
	            'name' => 'Біла ромашка', 
	            'url' => 'https://www.bilaromashka.com.ua',
	            'currencies' => ['currency' => ['[id]' => 'UAH', '[rate]' => 1]],
	            'categories' => $cat, 
	            'offers' => $prod
	            ]
        ];
	    
	    Xml::array_to_xml($data, $xml_data);
	    
	    $result = $xml_data->asXML(site_path . 'web/bilaromashka.yml');//
	    
	    return $result;
	    
	    
	}
	// Читаем в базе ссылку на фото
	public static function ReadPhoto($code){
	    $db = new Database();
	    $query = "SELECT url FROM photos WHERE code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        foreach ($stmt as $key => $photo)
            return ['url' => $photo['url']];
        return false;
	}
	// Записываем в базу ссылку на фото
    public static function WritePhoto($code, $url){
	    $db = new Database();
	    $query = "REPLACE photos VALUES (:code, :url)";
        $stmt = $db->execute($query, [':code' => $code, ':url' => $url]);
	}
}