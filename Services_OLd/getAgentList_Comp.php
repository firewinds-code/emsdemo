<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;

if ($_REQUEST['cmid'] && (strlen($_REQUEST['cmid']) <= 5)) {
	if (is_numeric($_REQUEST['cmid'])) {
		$cmid = clean($_REQUEST['cmid']);
	}
}


if ($_REQUEST) {
	$myDB = new MysqliDb();
	$sql_type = cleanUserInput($_REQUEST['type']);
	if ($sql_type == "1") {
		// $Query = "select EmployeeName,EmployeeID from whole_details_peremp where clientname =(select clientname from new_client_master where cm_id='" . $cmid . "') and Process= (select Process from new_client_master where cm_id='" . $cmid . "') order by EmployeeName";
		///

		$query = "select EmployeeName,EmployeeID from whole_details_peremp where clientname =(select clientname from new_client_master where cm_id=?) and Process= (select Process from new_client_master where cm_id=?) order by EmployeeName";

		$stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $cmid, $cmid);
	} else {
		// $Query = "select EmployeeName,EmployeeID from whole_details_peremp where clientname='" . $_REQUEST['cm_id'] . "' and Process= '" . $_REQUEST['process'] . "' order by EmployeeName ";
		///

		$sql_cm_id = cleanUserInput($_REQUEST['cm_id']);
		$sql_process = cleanUserInput($_REQUEST['process']);

		$query = "select EmployeeName,EmployeeID from whole_details_peremp where clientname=? and Process=? order by EmployeeName ";

		$stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $_REQUEST['cm_id'], $_REQUEST['process']);
	}
	//echo $Query;
	// $res = $myDB->query($Query);
	// /

	$stmt->execute();
	$res = $stmt->get_result();

	if ($res->num_rows > 0) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo 'EmployeeID NOT EXIST';
	}
} else {
	echo 'ID PLEASE !';
}
