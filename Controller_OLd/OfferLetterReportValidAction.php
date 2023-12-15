<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$empName = clean($_REQUEST['txtEmployeeName']);
$name = explode('(', $empName);
$EmpID = $EmployeeID = clean($_REQUEST['EmpID']);
$Comment = clean($_REQUEST['Comment']);
$user_logid = clean($_SESSION['__user_logid']);

if (isset($user_logid)) {
	$show = '';

	$validateBy = $user_logid;
	// $Update = 'update doc_al_status set validate=1,validateby="' . $validateBy . '",validatetime = now(),comment="' . $Comment . '" where EmployeeID="' . $EmpID . '" ';
	$Update = 'update doc_al_status set validate=1,validateby=?,validatetime = now(),comment=? where EmployeeID=? ';
	$stmt = $conn->prepare($Update);
	$stmt->bind_param("sss", $validateB, $Comment, $EmpID);
	$stmt->execute();
	$res = $stmt->get_result($Update);
	$body = "";
	// $myDB->rawQuery($Update);
	// $mysql_error = $myDB->getLastError();
	if ($myDB->count > 0) {
		// $selectCount = $myDB->rawQuery("Select EmployeeID from appointmentlonline Where EmployeeID='" . $EmployeeID . "' ");
		$selectCountQry = "Select EmployeeID from appointmentlonline Where EmployeeID=? ";
		$stmt = $conn->prepare($selectCountQry);
		$stmt->bind_param("s", $EmployeeID);
		$stmt->execute();
		$selectCount = $stmt->get_result();
		if ($selectCount->num_rows < 1) {
			// $select_email_array = $myDB->rawQuery("select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='" . $EmployeeID . "'");
			$select_email_arrayQry = "select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID=?";
			$stmt = $conn->prepare($select_email_arrayQry);
			$stmt->bind_param("s", $EmployeeID);
			$stmt->execute();
			$select_email_array = $stmt->get_result();
			$select_email_arrayRow = $select_email_array->fetch_row();

			// $insert_QueryQry = $myDB->rawQuery("Insert Into appointmentlonline set EmployeeID='" . $EmployeeID . "',EmpName='" . $name[0] . "',cm_id='" . $select_email_array[0]['cm_id'] . "',date='" . date('Y-m-d') . "',status='0'");
			$insert_QueryQry = "Insert Into appointmentlonline set EmployeeID=?,EmpName='" . $name[0] . "',cm_id=?,date='" . date('Y-m-d') . "',status='0'";
			$stmt = $conn->prepare($insert_QueryQry,);
			$stmt->bind_param("si", $EmployeeID, $select_email_array[2]);
			$stmt->execute();
			$insert_Query = $stmt->get_result();
			include('../View/appointmentLetter_download1.php');
		}
	}
	echo 1;
}
