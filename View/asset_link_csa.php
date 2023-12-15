<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');


$ippr="";

if(isset($_SESSION['__user_logid']))
{
	
	
$location= 'https://portal.cogentlab.com/asset-management/csa/?user_id='.$_SESSION['__user_logid'].'&password='.$_SESSION['__user_refrance']; 

//$location= 'http://192.168.202.60/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
echo "<script>location.href='".$location."'</script>";
die();
}
else
{
	$location= URL.'Login'; 
echo "<script>location.href='".$location."'</script>";
	die();
}
