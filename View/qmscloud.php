<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

//$location= 'http://192.168.204.175/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 

$ippr="";

if(isset($_SESSION['__user_logid']))
{
	
	
$location= 'https://qms.cogentlab.com/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.urlencode($_SESSION['__user_refrance']); 

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
?>