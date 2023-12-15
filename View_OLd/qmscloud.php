<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');


$ippr = "";
$user_logid = isset($_SESSION['__user_logid']);
if ($user_logid) {

	$userid = clean($_SESSION['__user_logid']);
	$user_ref = clean($_SESSION['__user_refrance']);

	//$location = 'https://demo.cogentlab.com/qms/admin/login.php?logid=' . $userid . '&refrance=' . urlencode($user_ref); 
	$user_ref = base64_encode($userid . '~~@@' . $user_ref);

	//print_r($paramreqArr = explode("~~@@",base64_decode(urlencode($user_ref))));die;
	$location = 'https://demo.cogentlab.com/qms/admin/login.php?refrance=' . $user_ref;
	echo "<script>location.href='" . $location . "'</script>";
	die();
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	die();
}
