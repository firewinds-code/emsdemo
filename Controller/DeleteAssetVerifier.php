<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

	//$sql='call remove_client_new('.$_REQUEST['ID'].')';
	$sql="DELETE FROM `ems`.`asset_verifier` WHERE (`id` = '".$_REQUEST['ID']."')";
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error)){
		echo "Row Deleted Affected Row are :".$row_count;
		 
	}
	else
	{
		echo "Row Not Deleted Try Again :";
	}
?>

