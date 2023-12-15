<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	$myDB = new MysqliDb();

	$Query = "select EmployeeName,EmployeeID from whole_details_peremp where clientname='" . $_REQUEST['cm_id'] . "' and Process= '" . $_REQUEST['process'] . "' order by EmployeeName ";

	//echo $Query;
	$res = $myDB->query($Query);
	if ($res) {
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
