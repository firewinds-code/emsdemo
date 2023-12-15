<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$sql='call getHandlerbyIssue("'.$_REQUEST['id'].'", "'.$_REQUEST['loc'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error)){
		//echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value)
		{
			echo '<option value="'.$value['EmployeeID'].'" >'.$value['EmployeeName'].'</option>';
		}
	}
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
?>

