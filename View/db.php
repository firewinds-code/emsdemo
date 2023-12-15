<?php
 //$conn = new mysqli('ems.cogentlab.com', 'root', 'asg@rd#123','ems');
 $conn = new mysqli('172.105.60.114', 'intuser', 'Int@123456','interview_ems');
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}
		else
		{
			echo 'Database Connected';
		}
?>
