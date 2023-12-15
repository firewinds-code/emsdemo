<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');


$ippr = "";
$user_logid = clean($_SESSION['__user_logid']);
$user_refrance = clean($_SESSION['__user_refrance']);
if (isset($user_logid)) {


	$location = 'http://45.79.123.250/asset-management/?user_id=' . $user_logid . '&password=' . $user_refrance;

	//$location= 'http://192.168.202.60/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
	echo "<script>location.href='" . $location . "'</script>";
	die();
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	die();
}
