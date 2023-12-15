<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='call remove_account('.$_REQUEST['ID'].')';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error)){
		echo "Row Deleted Affected Row are :".$myDB->count; 
	}
	else
	{
		echo "Row Not Deleted Try Again :".$mysql_error;
	}
?>

