<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
header("Content-Type: application/json; charset=UTF-8");
$Data = array();
$flag = array();
$_POST = file_get_contents('php://input');
$Data = json_decode($_POST, true);
ini_set('display_errors', '1');
//var_dump($Data);

if (isset($Data['EmpID']) && (trim($Data['EmpID'])) && (strlen($Data['EmpID']) <= 15)) {
	if ((substr($Data['EmpID'], 0, 2) == 'CE') || (substr($Data['EmpID'], 0, 2) == 'MU')) {
		$EmpID = clean($Data['EmpID']);
	}
}
if (isset($Data['date']) && (trim($Data['date'])) && (strlen($Data['date']) <= 10)) {
	if (is_numeric($Data['date']) || ctype_alpha($Data['date'])) {
		$date = clean($Data['date']);
	}
}
if (isset($Data['key']) && (trim($Data['key']))) {
	if (is_string($Data['key'])) {
		$key = clean($Data['key']);
	}
}

$EmployeeID = $key = '';
$date = '';
if ($EmpID && $date != "") {
	// $EmployeeID = $Data['EmpID'];
	// $date = $Data['date'];
	// $key = $Data['key'];
}        
if ($key == 'roster') {

	if ($EmpID != "" && $date != "") {
		// $query = "SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roster_temp  where DateOn='" . $date . "' AND EmployeeID = '" . $EmpID . "' ";
		$query = "SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roster_temp  where DateOn=? AND EmployeeID =?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $date,$EmpID);
		$stmt->execute();
		$flag = $stmt->get_result();
		// print_r($flag);
		// die;
		// $flag = $myDB->query($query);
		if ($flag->num_rows < 1) {
			$flag['status'] = 0;
			$flag['msg'] = 'Data not found ';
		}
	}
} else {
	$flag['status'] = 0;
	$flag['msg'] = 'Please add correct roster key';
}
if (count($flag) > 0) {
	$dataarray = json_encode($flag);
	print_r($dataarray);
}
