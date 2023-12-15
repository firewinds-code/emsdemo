<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

$EmployeeID = clean($_SESSION['__user_logid']);
$ID = cleanUserInput($_REQUEST['ID']);

if ($ID > 0) {
	$sql = 'insert into acknowledge_details (EmployeeID,action_id) value (?,?);';
	$ins = $conn->prepare($sql);
	$ins->bind_param("si", $EmployeeID, $ID);
	$ins->execute();
	$result = $ins->get_result();

	// $result = $myDB->query($sql);
	// $mysql_error=$myDB->getLastError();
	if ($ins->affected_rows === 1) {
		echo "<script>$(function(){ toastr.success('Announcement acknowledged successful') }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('No action performed.') }); </script>";
	}
} else {
	echo "<script>$(function(){ toastr.error('Request id is blank') }); </script>";
}
