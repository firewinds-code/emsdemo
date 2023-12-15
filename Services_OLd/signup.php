<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
if (isset($_REQUEST['ak']) && $_REQUEST['ak'] == 'ces' && $_REQUEST['ak'] != "") {
	if (strlen($_REQUEST['password']) <= 30 || is_numeric($_REQUEST['password']) || ctype_alpha($_REQUEST['password']) && trim($_REQUEST['password']) != "") {
		$password = trim($_REQUEST['password']);
	}
	//$password = $_REQUEST['password'];
	if (($_REQUEST['empid']) && trim($_REQUEST['empid']) != "" && (strlen($_REQUEST['empid']) <= 15)) {
		$empid = trim($_REQUEST['empid']);
	}
	//$empid = $_REQUEST['empid'];
	if (strlen($_REQUEST['dob']) <= 10 && is_numeric($_REQUEST['dob']) && trim($_REQUEST['dob']) != "") {
		$dob = trim($_REQUEST['dob']);
	}
	//$dob = $_REQUEST['dob'];
	if (strlen($_REQUEST['doj']) <= 10 && is_numeric($_REQUEST['doj']) && trim($_REQUEST['doj']) != "") {
		$doj = trim($_REQUEST['doj']);
	}
	//$doj = $_REQUEST['doj'];
	if (trim($_REQUEST['seq']) != "") {
		$sec_qusn = $_REQUEST['seq'];
	}
	if (trim($_REQUEST['sqa']) != "") {
		$sec_asn = $_REQUEST['sqa'];
	}


	// $myDB = new MysqliDb();
	$result['status'] = '';
	//echo "select EmployeeID from whole_details_peremp where EmployeeID = '".$empid."' and cast(DOB as date)= '".$dob."' and cast(DOJ as date)= '".$doj."' and secques is null and secans is null";
	$data = "select EmployeeID from whole_details_peremp where EmployeeID = ? and cast(DOB as date)= ? and cast(DOJ as date)= ? and secques is null and secans is null";
	$selectQ = $conn->prepare($data);
	$selectQ->bind_param("sii", $empid, $dob, $doj);
	$selectQ->execute();
	$p_data = $selectQ->get_result();
	//echo "<br>";
	// print_r($p_data);
	if ($p_data->num_rows > 0 && $p_data) {
		// $myDB =  new MysqliDb();
		$password_hash = md5($password);
		$QueryUpdate = "update employee_map set password = ?,secques = ?,secans = ?,password_updated_time=now() where EmployeeID = ? and cast(dateofjoin as date)= ?";
		// $res = $myDB->query($QueryUpdate);
		$up = $conn->prepare($QueryUpdate);
		$up->bind_param("ssssi", $password_hash, $sec_qusn, $sec_asn, $empid, $doj);
		$up->execute();
		$res = $up->get_result();
		// $MysqliError = $myDB->getLastError();
		// if ($MysqliError == "") {
		if ($up->affected_rows === 1) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
	} else {
		$result['status'] = 2;
	}
} else {
	$result['status'] = 3;
}
echo  json_encode($result);
exit;
