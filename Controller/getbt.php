<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='call getHandler("'.$_REQUEST['id'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if( count($result) > 0 && $result)
	{
		echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value){
				echo '<option value="'.$value['EmployeeID'].'" >'.$value['EmployeeName'].'</option>';
			}
		
	}
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
?>

