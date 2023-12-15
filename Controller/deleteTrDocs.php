<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='delete from trainee_doc where ID='.$_REQUEST['ID'];
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error)){
		echo "done|Document Deleted Affected Row are :".count($result);
		if(unlink(ROOT_PATH.'TraineeDocs/'.$_REQUEST['file']))
		{
			echo '<b>file Deleted Successfully</b>';
		}
		else
		{
			echo '<b>file not deleted</b>';
		}
	}
	else
	{
		echo "Row Not Deleted Try Again :".$mysql_error;
	}
?>

