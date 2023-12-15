<?php
require_once(__dir__.'/../Config/init.php');
require(ROOT_PATH.'Controller/log_create.php');
if(isset($_SESSION['__user_logid'])){
	$Action=new PHPLog_Action($_SESSION['__user_logid'],"Logout", $_SESSION["__user_Name"]." Log Out From EMS");	
}
session_destroy();   
$logout = URL."LogIn";        
header("Location: $logout");
exit();
?>