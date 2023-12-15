<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

if($_REQUEST['ID'] > 0 )
{
$sql='insert into acknowledge_details (EmployeeID,action_id) value ("'.$_SESSION['__user_logid'].'","'.$_REQUEST['ID'].'");';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.success('Announcement acknowledged successful') }); </script>";
	}
	else
	{
		echo "<script>$(function(){ toastr.error('No action performed:".$mysql_error."') }); </script>";
	}
}
else
	{
		echo "<script>$(function(){ toastr.error('Request id is blank') }); </script>";
	}
?>

