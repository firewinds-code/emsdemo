<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
//  $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

if (isset($_REQUEST['emp_id']) && (trim($_REQUEST['emp_id'])) && (strlen($_REQUEST['emp_id']) <= 15)) {
	if ((substr($_REQUEST['emp_id'], 0, 2) == 'CE') || (substr($_REQUEST['emp_id'], 0, 2) == 'MU')) {
		$emp_id = clean($_REQUEST['emp_id']);
	}
}
if (isset($_REQUEST['txt_dob']) && (trim($_REQUEST['txt_dob'])) && (strlen($_REQUEST['txt_dob']) <= 10)) {
	if (is_numeric($_REQUEST['txt_dob']) || is_string($_REQUEST['txt_dob'])) {
		$txt_dob = clean($_REQUEST['txt_dob']);
	}
}

// $Queryattendance = 'select secques,secans,DOB,e.EmployeeID from employee_map e inner join View_EmpinfoActive a on e.EmployeeID=a.EmployeeID WHERE e.EmployeeID="'.$emp_id.'" and DOB="'.$txt_dob .'" LIMIT 1 ';
$Queryattendance = 'select secques,secans,DOB,e.EmployeeID from employee_map e inner join View_EmpinfoActive a on e.EmployeeID=a.EmployeeID WHERE e.EmployeeID=? and DOB=? LIMIT 1 ';
//  $questionData = $myDB->query($Queryattendance);
$stmt = $conn->prepare($Queryattendance);
$stmt->bind_param("ss", $emp_id, $txt_dob);
$stmt->execute();
$questionData = $stmt->get_result();
$questionDataRow = $questionData->fetch_row();
//  print_r($questionData);
//  die;
$result = array();
if ($questionData->num_rows > 0) {
	//print_r( $result); 
	$result['secquestion'] = $questionDataRow[0]; //['secques'];
	$result['secanswer'] = $questionDataRow[1]; //['secans'];
	$result['emp_id'] = $questionDataRow[3]; //['EmployeeID'];
	$result['status'] = 1;

	//print_r($resultSends); 

} else {
	$result['secquestion'] = "";
	$result['secanswer'] = "";
	$result['emp_id'] = "";
	$result['status'] = 0;
}

echo  json_encode($result);
//echo   $resultSends ; 
exit;
