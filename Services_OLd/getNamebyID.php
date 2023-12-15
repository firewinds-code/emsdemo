<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();     
$empid = $Query = null;

// if (isset($_REQUEST['empid']) && (trim($_REQUEST['empid'])) && (strlen($_REQUEST['empid']) <= 15)) {
// 	if ((substr($_REQUEST['empid'], 0, 2) == 'CE') || (substr($_REQUEST['empid'], 0, 2) == 'MU')) {
// 		$empid = clean($_REQUEST['empid']);
// 	}
// }
$empid = clean($_REQUEST['empid']);

if ($empid != "") {
	// $EMPID = $_REQUEST["empid"];
	// $Query = "select EmployeeName,EmployeeID from personal_details where EmployeeID='" . $EMPID . "'";
	$Query = "select EmployeeName,EmployeeID from personal_details where EmployeeID=?";
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("s", $empid);
	$stmt->execute();
	$dataarray["Status"] = "0";
	$res = $stmt->get_result();
	$res1 = $res->fetch_all(MYSQLI_ASSOC);
	// print_r($res1);
	// die;
	// $res = $myDB->query($Query);
	if ($res) {
		$dataarray["data"] = $res1;
		$dataarray["Status"] = "1";
	} else {
		$dataarray["data"] = "Employee NOT EXIST";
		$dataarray["Status"] = "0";
	}
} else {
	$dataarray["data"] = "invalid request";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);
