<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
	$sql='delete from doc_details where doc_id='.$_REQUEST['ID'];
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	//echo $_SERVER['DOCUMENT_ROOT']."/ems2apr/Docs/".$_REQUEST['type'].$_REQUEST['file'];
	
	if(isset($_REQUEST['file']) && $_REQUEST['file']!='' && $_REQUEST['type']!=''){

		if(file_exists($_SERVER['DOCUMENT_ROOT']."/ems/Docs/".$_REQUEST['type'].$_REQUEST['file'])){
			if(unlink($_SERVER['DOCUMENT_ROOT']."/ems/Docs/".$_REQUEST['type'].$_REQUEST['file']))
			{
				echo 'done|<b>file Deleted Successfully</b>';
			}
			else
			{
				echo '<b>notdone|file not deleted</b>';
			}
		}
	}
	else
	{
		echo "notdone|Row Not Deleted Try Again :".$mysql_error;
	}
?>

