<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 0);
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

if ($_REQUEST['term'] && trim($_REQUEST['term']) && $_REQUEST['term'] != "") {
	$term = clean($_REQUEST['term']);
}
// $result = $myDB->query('select distinct EmployeeID,EmployeeName from personal_details where EmployeeID like "%' . $term . '%" or EmployeeName like "%' . $term . '%"');
// echo 'select distinct EmployeeID,EmployeeName from personal_details where EmployeeID like "%' . $term . '%" or EmployeeName like "%' . $term . '%"';
// die;
$like = '"%' . $term . '%"';
$resultQry = 'select distinct EmployeeID,EmployeeName from personal_details where EmployeeID like ? or EmployeeName like "%' . $term . '%"';
$stmt = $conn->prepare($resultQry);
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
// print_r($result);
// die;
foreach ($result  as $key => $value) {
	$data[] = $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')';
}
echo json_encode($data);
