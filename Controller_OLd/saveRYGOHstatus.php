<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$saves = clean($_REQUEST['saves']);
$empid = clean($_REQUEST['empid']);
$rygsrid = addslashes($_REQUEST['rygsr']);
$rygstatus = clean($_REQUEST['rygstatus']);
$substatus = clean($_REQUEST['substatus']);
$oh = clean($_REQUEST['oh']);
if (isset($saves) && $saves == 'saves' &&  $rygstatus != "" && $substatus != "" &&  $rygsrid != "" &&  $empid != "" &&  $oh != "") {


	$q = "select EmployeeID from ryg_oh where EmployeeID=?  and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
	$selectQ = $conn->prepare($q);
	$selectQ->bind_param("s", $empid);
	$selectQ->execute();
	$result_q = $selectQ->get_result();
	// $result_q = $myDB->query($q);
	if ($result_q->num_rows > 0) {
		$sqlUpd = "update ryg_oh set  ryg_status=? ,ryg_substatus=?, ryg_remark=? where EmployeeID=? and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
		// $result = $myDB->query($sqlUpd);
		// $mysql_error = $myDB->getLastError();
		// if (empty($mysql_error)) {
		$update = $conn->prepare($sqlUpd);
		$update->bind_param("ssss", $rygstatus, $substatus, $rygsrid, $empid);
		$update->execute();
		$result = $update->get_result();
		if ($update->affected_rows === 1) {
			echo "updated";
		} else {
			echo "error: ";
		}
	} else {
		$sql = "INSERT ryg_oh  set  EmployeeID=?, oh_id=?, ryg_status=?,ryg_substatus=?,ryg_remark=?,created_on=now()";
		// $result = $myDB->query($sql);
		// $mysql_error = $myDB->getLastError();
		// if (empty($mysql_error)) {
		$insert = $conn->prepare($sql);
		$insert->bind_param("sssss", $empid, $oh, $rygstatus, $substatus, $rygsrid);
		$insert->execute();
		$result = $insert->get_result();
		if ($insert->affected_rows === 1) {
			echo "Inserted ";
		} else {
			echo "error: ";
		}
	}
}
