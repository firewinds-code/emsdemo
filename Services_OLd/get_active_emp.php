<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result['msg'] = "";
$Query = null;
$myDB = new MysqliDb();
$Query = "SELECT t1.EmployeeID,EmployeeName from personal_details t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID where t2.emp_status='Active' and df_id not in('74','77') ;";
// $res = $myDB->query($Query);
// if (empty($myDB->getLastError())) {
$stmt = $conn->prepare($Query);
$stmt->execute();
$res = $stmt->get_result();
$res1 = $res->fetch_all(MYSQLI_ASSOC);

if ($res1) {

	$result['data'] = $res1;
	$result['msg'] = 'data Found';
	$result['status'] = 1;

	echo	json_encode($result);
} else {

	$result['msg'] = 'data not Found';
	$result['status'] = 0;

	echo	json_encode($result);
}
