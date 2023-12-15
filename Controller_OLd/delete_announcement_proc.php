<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$id = clean($_REQUEST['ID']);
if ($_REQUEST['ID'] > 0) {
	$sql = 'DELETE from announcement_inproc where id = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $id);
	$delt = $stmt->execute();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();
	if ($delt) {
		echo "<script>$(function(){ toastr.success('Row Deleted Affected Row are " . $stmt->affected_rows . "') }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Row Not Deleted Try Again.') }); </script>";
	}
} else {
	echo "<script>$(function(){ toastr.error('Request id is blank') }); </script>";
}
