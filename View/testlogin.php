<?php

			
			 	$Priority="ndnd";
			 	$Smstype="normal";
				
			 
					$url = "http://bhashsms.com/api/sendmsg.php";   
					$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>"7835857351",'text'=>'test', 'priority'=>$Priority, 'stype'=>$Smstype);
				 	$postvars = '';
				 	foreach($fields as $key=>$value)
				 	{
			 			echo $postvars .= $key . "=" . $value . "&"; 
			 			echo "<br>";
			 		} 
				 	$ch = curl_init();	
			 		curl_setopt($ch,CURLOPT_URL,$url);
			 		curl_setopt($ch,CURLOPT_POST, 0);
				 	curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
				 	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				 	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
				 	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
				echo	$response = curl_exec($ch);
				 	curl_close ($ch);
					
					
				
		

?>
