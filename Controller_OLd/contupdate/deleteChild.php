<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$ID = clean($_REQUEST['ID']);
$sql = "call remove_child('" . $ID . "')";
$myDB = new MysqliDb();
$result = $myDB->rawQuery($sql);
$row_count = $myDB->count;
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {
	echo "Done|Row Deleted Affected Row are :" . $row_count;
} else {
	echo "Row Not Deleted Try Again";
}
