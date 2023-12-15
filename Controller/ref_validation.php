<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');


if(isset($_GET['todate'])  && $_GET['todate']!=""  && $_GET['fromdate']!="")
{
	$todate=$_GET['todate'];
	$fromdate=$_GET['fromdate'];
	$ID=$_GET['ID'];
	
	$Insert='CALL sp_getRefID1("'.$fromdate.'","'.$todate.'","'.$ID.'")';
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

