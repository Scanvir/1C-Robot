<?php

class Multisearch
{
    public static function search($search){
        $endpoint = "https://api.multisearch.io/?id=12095&uid=000&categories=0&limit=50&query=".$search;
        $results = self::api($endpoint, str_replace(' ', '%20', $search));
        if(!empty($results)){
            $ids = $results['results']['ids'];
            $result = [];
            foreach ($ids as $id){
                $product = Product::getProductByCode($id);
                $result[] = [
                    'Code' => $id,
                    'ParentCode' => $product['ParentCode'],
                    'Name' => $product['Name'],
                    'TradeMark' => $product['TradeMark'],
                    'Producer' => $product['Producer'],
                ];
            }
            return $result;
        } else
        return [];
    }
    private static function api($endpoint, $search){
    	$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['accept: application/json']);
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