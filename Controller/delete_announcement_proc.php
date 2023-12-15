<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if($_REQUEST['ID'] > 0 )
{
$sql='delete from announcement_inproc where id = '.$_REQUEST['ID'].'';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.success('Row Deleted Affected Row are ".$myDB->count."') }); </script>";
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Row Not Deleted Try Again ".$mysql_error."') }); </script>";
	}
}
else
	{
		echo "<script>$(function(){ toastr.error('Request id is blank') }); </script>";
	}
?>

