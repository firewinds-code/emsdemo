<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
//ini_set('error_reporting', E_ALL);

// Trigger Button-Save Click Event and Perform DB Action

$date = date('Y-m-d');
//$date = '2022-05-05';

$sqlConnect = 'select EmployeeID, location,client_name, process, sub_process, reports_to from transfer_emp where transfer_date=?';
$selectQ = $conn->prepare($sqlConnect);
$selectQ->bind_param("s", $date);
$selectQ->execute();
$result = $selectQ->get_result();
// print_r($result);
// $myDB = new MysqliDb();
// $result = $myDB->query($sqlConnect);

// echo ($sqlConnect);
// die;
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0) {
	foreach ($result as $key => $value) {
		$updatelocation = 'update personal_details,employee_map,status_table set personal_details.location=?, employee_map.cm_id=? ,status_table.ReportTo=? where personal_details.EmployeeID=? and employee_map.EmployeeID=? and status_table.EmployeeID=?;';
		// $myDB = new MysqliDb();
		// $resultBy = $myDB->query($updatelocation);
		$update = $conn->prepare($updatelocation);
		$update->bind_param("iissss", $value['location'], $value['sub_process'], $value['reports_to'], $value['EmployeeID'], $value['EmployeeID'], $value['EmployeeID']);
		$update->execute();
		$resultBy = $update->get_result();
	}
	// 	$updatelocation1 = "delete from transfer_emp where transfer_date = DATE_SUB(CURDATE(),INTERVAL 1 DAY) "; 
	// 	$myDB = new MysqliDb();
	//    $resultBy1 = $myDB->query($updatelocation1);	
}
