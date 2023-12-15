<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

	$sql='update resign_details set accept = 1 ,accept_time = now(),rg_status=1 where EmployeeID = "'.$_REQUEST['ID'].'" and rg_status = 0;';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error))
	{
		echo 'done';
		
	}
	else
	{
		echo 'notdone';
		
	}
?>

