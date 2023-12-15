<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;
if ($_REQUEST) {
	if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
		$EmployeeID = clean($_REQUEST['EmployeeID']);
	}

	$myDB = new MysqliDb();
	$Query = "call GetCmIdByEmpId('" . $EmployeeID . "')";
	$res = $myDB->query($Query);
	if ($res) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo 'CMID NOT EXIST';
	}
} else {
	echo 'EmployeeID PLEASE !';
}
