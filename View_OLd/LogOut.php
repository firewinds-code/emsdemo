<?php
require_once(__dir__ . '/../Config/init.php');
require(ROOT_PATH . 'Controller/log_create.php');
require_once(CLS . 'MysqliDb.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$userlogid = clean($_SESSION['__user_logid']);
if (isset($_SESSION['__user_logid'])) {
	$Action = new PHPLog_Action($_SESSION['__user_logid'], "Logout", $_SESSION["__user_Name"] . " Log Out From EMS");
}

$sqlQry = "update emp_auth set flag=0 where EmployeeID= ?";
$stmt = $conn->prepare($sqlQry);
$stmt->bind_param('s', $userlogid);
$stmt->execute();
$res_sqlQry = $stmt->get_result();
// $row = $result->fetch_assoc();
// echo "<pre>";
// print_r($res_sqlQry);
// die;
if ($stmt->affected_rows === 1) {
	session_unset();
	session_destroy();
	setcookie("PHPSESSID", $_COOKIE["PHPSESSID"], time() - 3600, "/"); // delete session cookie
	$logout = URL . "LogIn";
	ini_set("session.use_cookies", 0);
	header("Location: $logout");
	exit();
	// echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
} else {
	echo "<script>$(function(){ toastr.error('Some Error Occured'); }); </script>";
}
