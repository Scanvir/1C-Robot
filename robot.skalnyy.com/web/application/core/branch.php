<?php

class Branch
{
    private $db;

    public static function GetBranch(){
		$db = new Database();
		
		$query = "SELECT Code, Name, Position, WorkTime, Reserve FROM branch order by name";
        $stmt = $db->query($query);
        
        foreach ($stmt as $row)
        {   
			$result[] = [
                'Code' => $row['Code'],
                'Name' => $row['Name'],
				'Position' => $row['Position'],
				'WorkTime' => $row['WorkTime'],
				'Reserve' => $row['Reserve'],
            ];
        }
        return $result;
	}
    public static function GetWorkBranch(){
		$db = new Database();
		$branches = [];
		
		$codeList = "'_'";
		$work = self::WorkBranch();
		foreach ($work as $key => $row){
		    if ($row['Work'] == 1)
	            $codeList .= ",'".$row['Code']."'";
		}
		
		$query = "SELECT distinct position FROM branch WHERE code in (".$codeList.") order by position";
		$stmt = $db->query($query);
		
		foreach ($stmt as $row)
        {
			$branches[] = [
                'position' => $row['position'],
                'branch' => [],
            ];
        }

		$query = "SELECT code, name, position, geo, reserve FROM branch WHERE code in (".$codeList.") order by name";
        $stmt = $db->query($query);
        
        foreach ($stmt as $row)
        {   
            $key = array_search($row['position'], array_column($branches, 'position'));
            
			$branches[$key]['branch'][] = [
                'code' => $row['code'],
                'name' => $row['name'],
				'geo' => $row['geo'],
				'reserve' => $row['reserve'],
            ];
        }
        return $branches;
	}
	public static function GetBranchByCode($code){
		$db = new Database();
		
		$query = "SELECT Code, Name, Position, Geo, WorkTime, Tel, Reserve FROM branch where Code = :code";
        $stmt = $db->query($query, [':code' => $code]);
        
        $result = [];
        
        foreach ($stmt as $row)
        {
			$result = [
                'Code' => $row['Code'],
                'Name' => $row['Name'],
                'Position' => $row['Position'],
                'Geo' => $row['Geo'],
                'WorkTime' => $row['WorkTime'],
                'Tel' => $row['Tel'],
                'Reserve' => $row['Reserve'],
            ];
        }
    
        return $result;
	}
	
	public static function UpdateBranch(){
        $data = self::AllBranch();
        $db = new Database();
        foreach ($data as $row){
            $query = "SELECT Code from branch where Code = :Code";
            $stmt = $db->query($query, [':Code' => $row['Code']]);
            if (count($stmt) == 1){
                $query = "UPDATE branch set Name = :Name, Position = :Position, Geo = :Geo, WorkTime = :WorkTime, Tel = :Tel, Upd = now()
                    WHERE Code = :Code";
                $stmt = $db->execute($query, [
                    ':Code' => $row['Code'],
                    ':Name' => $row['Name'], 
                    ':Position' => $row['Position'],
                    ':Geo' => $row['Geo'],
                    ':WorkTime' => $row['WorkTime'],
                    ':Tel' => $row['Tel'],
                   
                ]);
            } else {
                $query = "INSERT into branch values(:Code, :Name, :Position, :Geo, :WorkTime, :Tel, 0, now())";
                $stmt = $db->execute($query, [
                    ':Code' => $row['Code'],
                    ':Name' => $row['Name'], 
                    ':Position' => $row['Position'],
                    ':Geo' => $row['Geo'],
                    ':WorkTime' => $row['WorkTime'],
                    ':Tel' => $row['Tel'],
                ]);
            }
            
        }
    }
	static function WorkBranch(){
		$endpoint = 'https://api.bilaromashka.com.ua/db/branch/work.php';
				
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
	static function AllBranch(){
		$endpoint = 'https://api.bilaromashka.com.ua/db/branch/index.php';
				
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
}