<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
if ($_REQUEST) {
	// $myDB=new MysqliDb();
	// $Query="select distinct cm_id from new_client_master where account_head = '".$_REQUEST['EmployeeID']."';";
	//$res = $myDB->query($Query);

	if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
		if ((substr($_REQUEST['EmployeeID'], 0, 2) == 'CE') || (substr($_REQUEST['EmployeeID'], 0, 2) == 'MU')) {
			$EmployeeID = clean($_REQUEST['EmployeeID']);
		}
	}

	$Query = "select distinct cm_id from new_client_master where account_head = ?";
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("s", $EmployeeID);
	if (!$stmt) {
		echo "failed to run";
		die;
	}
	$stmt->execute();
	$res = $stmt->get_result();

	if ($res) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo '';
	}
} else {
	echo '';
}
