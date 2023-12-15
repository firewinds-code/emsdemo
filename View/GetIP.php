<?php
		  
		    echo "IP";
		      echo "</br>1 "; echo  $ipaddress = getenv('HTTP_CLIENT_IP');
		      echo "</br>2 "; echo $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		      echo "</br>3 "; echo $ipaddress = getenv('HTTP_X_FORWARDED');
		      echo "</br>4 "; echo $ipaddress = getenv('HTTP_FORWARDED_FOR');
		      echo "</br>5 ";echo $ipaddress = getenv('HTTP_FORWARDED');
		      echo "</br>6 "; echo $ipaddress = getenv('REMOTE_ADDR');
		
?>