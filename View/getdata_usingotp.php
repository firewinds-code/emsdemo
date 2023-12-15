<?php

				$client_id="aadhaar_v2_YwjgzRniprylayxoIKgd";
				$otp="300042";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://kyc-api.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",						
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>"{\n\t\"client_id\": \"".$client_id."\",\n\t\"otp\": \"".$otp."\"\n}",
		CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"
		),
		));
		
		echo "responce=".$response2 = curl_exec($curl);
	//	$resposne_data2= json_decode($response2);
	/*echo "<br>";
		print_r($resposne_data2);*/
?>