<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$ID = clean($_REQUEST['ID']);
$sql = 'call ack_perform("' . $ID . '")';
$myDB = new MysqliDb();
// $sel = $conn->prepare($sql);
// $sel->bind_param("i", $ID);
// $sel->execute();
// $results = $sel->get_result();
$result = $myDB->rawQuery($sql);
$row_count = $myDB->count;
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {
	// if ($sel->affected_rows === 1) {
	echo "Done";
} else {
	echo "No";
}
