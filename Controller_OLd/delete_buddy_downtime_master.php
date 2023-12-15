<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
if (isset($id) && is_numeric($id)) {

	$sql = 'DELETE from buddy_dtmatrix where ID=?';

	// $myDB = new MysqliDb();
	// $result = $myDB->query($sql);
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$res = $stmt->get_result();
	// $mysql_error = $myDB->getLastError();
	// if (empty($mysql_error)) {
	if ($stmt->affected_rows === 1) {
		echo "done";
	} else {
		echo "Row Not Deleted Try Again";
	}
}
