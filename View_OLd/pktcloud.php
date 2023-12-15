<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

//$location= 'http://192.168.204.175/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
$ippr = "";
$user_logid = isset($_SESSION['__user_logid']);
if ($user_logid) {
	$userid = clean($_SESSION['__user_logid']);
	$user_ref = clean($_SESSION['__user_refrance']);
	$location = 'https://qms.cogentlab.com/pkt/login.php?logid=' . $userid . '&refrance=' . urlencode($user_ref);

	//$location= 'http://192.168.202.60/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
	echo "<script>location.href='" . $location . "'</script>";
	die();
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	die();
}
