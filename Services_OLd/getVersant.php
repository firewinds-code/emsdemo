<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;
if (isset($_REQUEST['empid']) && trim($_REQUEST['empid']) != "" && (strlen($_REQUEST['empid']) <= 15)) {
	$EmployeeID = trim($_REQUEST['empid']);
	// $myDB=new MysqliDb();
	$Query = "select a.ID, a.cm_id, a.cert_name, a.filename from certification_require_by_cmid a inner join employee_map b on a.cm_id=b.cm_id where b.EmployeeID=?";
	$selectQ = $conn->prepare($Query);
	$selectQ->bind_param("s", $EmployeeID);
	$selectQ->execute();
	$res = $selectQ->get_result();
	// $res =$myDB->query($Query);
	if ($res->num_rows > 0) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo NULL;
	}
}
