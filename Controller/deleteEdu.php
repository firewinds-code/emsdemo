<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='delete from education_details where edu_id='.$_REQUEST['ID'];
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	if(isset($_REQUEST['file']) && $_REQUEST['file']!=""){
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/ems/Education/".$_REQUEST['file'])){
			if(unlink($_SERVER['DOCUMENT_ROOT']."/ems/Education/".$_REQUEST['file']))
			{
				echo 'done|<b>file Deleted Successfully</b>';
			}
			else
			{
				echo '<b>file not deleted</b>';
			}
		}
	}
	else
	{
		echo "Row Not Deleted Try Again :".$mysql_error;
	}
?>

