<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');


if(isset($_GET['number'])  && $_GET['number']!="")
{
	$number=$_GET['number'];
		
	$Insert='call sp_chkmobile("'.$number.'")';
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		
		if(count($result) > 0 && $result)
		{
			echo '0';
		}
		else{
			echo '1';
		}
}	
?>

