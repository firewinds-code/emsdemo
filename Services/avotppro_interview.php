<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
if($_REQUEST)
{
	$aadhar=$_REQUEST['aadhar'];
	 
		if($aadhar)
		{
				$curl = curl_init();

				curl_setopt_array($curl, array(				
				CURLOPT_URL => "https://kyc-api.aadhaarapi.io/api/v1/aadhaar-v2/generate-otp",				
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>"{\n\t\"id_number\": \"".$aadhar."\"\n}",
				CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				$resposne_data= json_decode($response);
				echo $response;
				/*if(isset($resposne_data) and $resposne_data->success=="true")
				{					
				echo $resposne_data->data->client_id;
				}
				else
				{
					echo $response;
				}*/
			 
	}
	else
	{
		echo "Aadhar not found";
	}	 
	
}
else
{
		echo "bad request";
}

	?>