<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'update resign_details set accept = 1 ,accept_time = now(),rg_status=1 where EmployeeID = ? and rg_status = 0;';
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
$up = $conn->prepare($sql);
$up->bind_param("s", $ID);
$up->execute();
$results = $up->get_result();
// print_r($results);
// die;
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();

// if (empty($mysql_error)) {
if ($up->affected_rows === 1) {
	echo 'done';
} else {
	echo 'notdone';
}
