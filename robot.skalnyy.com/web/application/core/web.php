<?php

class Web {
    
    public static function webGet($endpoint){
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
    public static function webPost($endpoint, $body){

        $headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		return $result;
        curl_close($ch);
        $data = json_decode($result, true);
        
		return $data;
    }
}