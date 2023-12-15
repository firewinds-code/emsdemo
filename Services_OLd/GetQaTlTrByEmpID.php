<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();    
$result = array();
$Query = null;

if (isset($_REQUEST['empid']) && (trim($_REQUEST['empid'])) && (strlen($_REQUEST['empid']) <= 15)) {
	if ((substr($_REQUEST['empid'], 0, 2) == 'CE') || (substr($_REQUEST['empid'], 0, 2) == 'MU')) {
		$empid = clean($_REQUEST['empid']);
	}
}

if ($_REQUEST) {
	// $Query = "select s.EmployeeID,s.ReportTo as TL,s.Qa_ops as QA,t.Trainer,nc.OH,nc.QH,nc.account_head as AH,nc.VH from status_table s inner join ActiveEmpID a on s.EmployeeID=a.EmployeeID left join new_client_master nc on nc.cm_id=a.cm_id left join status_training t on s.EmployeeID=t.EmployeeID where a.EmployeeID='" . $empid . "' ";
	$Query = "select s.EmployeeID,s.ReportTo as TL,s.Qa_ops as QA,t.Trainer,nc.OH,nc.QH,nc.account_head as AH,nc.VH from status_table s inner join ActiveEmpID a on s.EmployeeID=a.EmployeeID left join new_client_master nc on nc.cm_id=a.cm_id left join status_training t on s.EmployeeID=t.EmployeeID where a.EmployeeID=?";
	$dataarray["Status"] = "0";
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("s", $empid);
	$stmt->execute();
	$res = $stmt->get_result();
	// print_r($res);
	// die;
	// $res = $myDB->query($Query);
	if ($res) {
		/*foreach($res as $key=>$value)
					{
						$result[] = $value;
		   			}*/
		//$result = json_encode($result);
		$dataarray["data"] = $res;
		$dataarray["Status"] = "1";
	} else {
		$dataarray["data"] = "Data NOT EXIST";
		$dataarray["Status"] = "0";
	}
} else {
	$dataarray["data"] = "invalid request";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);
	//print_r($dataarray);
