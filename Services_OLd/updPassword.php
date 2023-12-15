<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');

// $myDB =  new MysqliDb();
if (($_REQUEST['emp_id']) && trim($_REQUEST['emp_id']) != "" && (strlen($_REQUEST['emp_id']) <= 15)) {
	$emp_id = trim($_REQUEST['emp_id']);
}
if (strlen($_REQUEST['newpassword']) <= 30 || is_numeric($_REQUEST['newpassword']) || ctype_alpha($_REQUEST['newpassword']) && trim($_REQUEST['newpassword']) != "") {
	$newpassword = trim($_REQUEST['newpassword']);
}
$pass = md5($newpassword);
$QueryUpdate = 'UPDATE employee_map SET password =? where EmployeeID=?';

//$QueryUpdate = $myDB->query($QueryUpdate);
// $res = $myDB->query($QueryUpdate);
$update = $conn->prepare($QueryUpdate);
$update->bind_param("ss", $pass, $emp_id);
$update->execute();
$result = $update->get_result();
if ($update->affected_rows === 1) {
	// if (!$myDB->getLastError()) {
	$result['status'] = 1;
} else {
	$result['status'] = 0;
}

echo  json_encode($result);
//echo   $resultSends ; 
exit;
