<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	//$myDB = new MysqliDb();
	// 	$Query = "select e.EmployeeID,OnFloor as DOD from employee_map e inner join status_table s on e.EmployeeID=s.EmployeeID where DATEDIFF(now(),OnFloor)<=30 and e.emp_status='Active' and Status=6
	//   and cm_id ='" . $_REQUEST['cmid'] . "' ";

	if ($_REQUEST['cmid'] && (strlen($_REQUEST['cmid']) <= 5)) {
		if (is_numeric($_REQUEST['cmid'])) {
			$cmid = clean($_REQUEST['cmid']);
		}
	}

	$Query = "select e.EmployeeID,OnFloor as DOD from employee_map e inner join status_table s on e.EmployeeID=s.EmployeeID where DATEDIFF(now(),OnFloor)<=30 and e.emp_status='Active' and Status=6
  and cm_id =? ";

	$stmt = $conn->prepare($Query);
	$stmt->bind_param("i", $cmid);
	if (!$stmt) {
		echo "failed to run";
		die;
	}
	$stmt->execute();
	$res = $stmt->get_result();
	$res = $res->fetch_all(MYSQLI_ASSOC);


	$dataarray["Status"] = "0";
	//$res = $myDB->query($Query);
	if ($res) {
		/*	foreach($res as $key=>$value)
					{
						$result[] = $value;
		   			}*/
		//$result = json_encode($result);				
		$dataarray["data"] = $res;
		$dataarray["Status"] = "1";
	} else {
		$dataarray["data"] = "CMID NOT EXIST";
		$dataarray["Status"] = "0";
	}
} else {
	$dataarray["data"] = "invalid request";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);				
	//print_r($dataarray);
