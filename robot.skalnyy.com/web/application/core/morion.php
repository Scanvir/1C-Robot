<?php

class Morion {

    private static function web($endpoint, $headers, $get = true, $body = "") {
    	$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($get)
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		else {
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
    }
    
    public static function UpdateMorion() {
        $data = self::web('https://api.bilaromashka.com.ua/db/goods/morion.php', ['0' => 'accept: application/json', '1' => "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw=="]);
        
        $fp = fopen('/tmp/morion.csv', 'w');
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        
        $db = new Database();
        
        $query = "DROP TABLE IF EXISTS new_codeMorion";
        $stmt = $db->execute($query);
        
        $query = "CREATE TABLE new_codeMorion LIKE codeMorion";
        $stmt = $db->execute($query);
        
        $query = "LOAD DATA LOCAL INFILE '/tmp/morion.csv'
             INTO TABLE new_codeMorion
             FIELDS TERMINATED BY ',' 
             OPTIONALLY ENCLOSED BY '\"'
             LINES TERMINATED BY '\\n'";
        $db->execute($query);
        
        $query = "truncate table codeMorion";
        $stmt = $db->execute($query);
        
        $query = "insert into codeMorion SELECT * FROM new_codeMorion";
        $stmt = $db->execute($query);
        
        $query = "DROP TABLE IF EXISTS new_codeMorion";
        $stmt = $db->execute($query);
        
		return count($data);
	}
	
    public static function GetInstruction($code) {
        $instr = self::ReadInstruction($code);
        if ($instr)
            return $instr;
        
        $morionCode = Product::getMorionCode($code);
        
        if ($morionCode == 0)
            return '';

        $data = self::web('https://spho.pharmbase.com.ua/spauth/verify', ['0' => 'accept: application/json', '1' => 'AccessKey: 3e18420667ea8ca5465c97df62b0a80c']);
	    $token = $data['token'];

	    $body = "{\"id_morion\": " . $morionCode . "}";
	    $data = self::web('https://spho.pharmbase.com.ua/morion/get-drug-gfc', ['0' => 'Content-Type:application/json', '1' => 'Token:' . $token, '3' => 'Accept-Charset:utf-8'], false, $body);
	    
		self::SaveInstruction($code, $data['info_html_ukr']);
		return $data;
	}
	public static function ReadInstruction($code) {
	    $db = new Database();
	    $query = "SELECT instruction FROM instructions WHERE code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        foreach ($stmt as $key => $instr)
            return ['info_html_ukr' => $instr['instruction']];
        return false;
	}
    public static function SaveInstruction($code, $instruction) {
	    $db = new Database();
	    $query = "REPLACE instructions VALUES (:code, :instruction, Now())";
        $stmt = $db->execute($query, [':code' => $code, ':instruction' => $instruction]);
	}
	public static function UpdateURLPhoto(){
	    $db = new Database();
	    $data = self::web('https://spho.pharmbase.com.ua/vault/v1/files/?customer_id=12163ebcc08a3a1a78f34c7b87f93933', ['0' => 'accept: application/json']);
	    $a = 0;
	    foreach ($data as $key => $photo){
	        $morion = $photo['id'];
	        $url = $photo['images'][0]['url'];
	        $query = "REPLACE morionPhotos VALUES (:morion, :url)";
            $stmt = $db->execute($query, [':morion' => $morion, ':url' => $url]);
            $a ++;
	    }
	    return $a;
	}
}