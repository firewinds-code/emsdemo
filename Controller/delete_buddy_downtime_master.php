<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

if(isset($_REQUEST['ID']) && is_numeric($_REQUEST['ID']))
{
	$sql='delete from buddy_dtmatrix where ID='.$_REQUEST['ID'];
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "done";
	}
	else
	{
		echo "Row Not Deleted Try Again $mysql_error";
	}
}
?>

