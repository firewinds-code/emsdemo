<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

//$location= 'http://192.168.204.175/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
$ippr = "";
function set_server_ip()
{
	if ($_SERVER['HTTP_HOST'] == "192.168.20.233") {
		$ippr = "192.168.20.243";
	} else if ($_SERVER['HTTP_HOST'] == "10.85.91.248") {
		$ippr = "10.85.91.8";
	} else if ($_SERVER['HTTP_HOST'] == "192.168.202.252") {
		$ippr = "192.168.202.60";
	} else if ($_SERVER['HTTP_HOST'] == "192.168.254.199") {
		$ippr = "192.168.254.221";
	} else {
		$cm_id = clean($_SESSION["__cm_id"]);
		if ($cm_id == '7' || $cm_id == '9' || $cm_id == '10' || $cm_id == '11' || $cm_id == '12' || $cm_id == '13' || $cm_id == '14' || $cm_id == '15' || $cm_id == '16' || $cm_id == '66' || $cm_id == '67' || $cm_id == '68' || $cm_id == '134' || $cm_id == '144' || $cm_id == '172') {
			$ippr = "192.168.20.243";
		} else {
			$ippr = "192.168.202.60";
		}
	}
	return $ippr;
}
function get_client_ip_ref()
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

$clientip = get_client_ip_ref();
$clientip1 = filter_var($clientip, FILTER_VALIDATE_IP);

$user_logid = clean($_SESSION['__user_logid']);
if (isset($user_logid)) {
	$loc = clean($_SESSION["__location"]);
	if ($loc == '1' || $loc == '2') {
		$ippr = set_server_ip();
	} else {
		if ($loc == '3') {
			$ippr = '192.168.204.247';
		} else if ($loc == '4') {
			$ippr = '192.168.1.7';
		} else if ($loc == '5') {
			$ippr = '10.25.50.110';
		} else if ($loc == '6') {
			$ippr = '172.16.201.11';
		} else if ($loc == '7') {
			$ippr = 'cogentems.com:8082';
		}
		/*else if($_SESSION["__location"] =='8')
		{
			$ippr = '192.168.10.11';
		}*/
	}
	$user_ref = clean($_SESSION['__user_refrance']);
	$location = 'http://' . $ippr . '/pkt/login.php?logid=' . $user_logid . '&refrance=' . urlencode($user_ref);

	//$location= 'http://192.168.202.60/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
	echo "<script>location.href='" . $location . "'</script>";
	die();
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	die();
}
