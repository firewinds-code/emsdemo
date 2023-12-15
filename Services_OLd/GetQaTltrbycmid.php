<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// $myDB = new MysqliDb();     
// $conn = $myDB->dbConnect();

$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;

if ($_REQUEST['cmid'] && (strlen($_REQUEST['cmid']) <= 5)) {
	if (is_numeric($_REQUEST['cmid'])) {
		$cmid = clean($_REQUEST['cmid']);
	}
}

if ($cmid != "") {
	// $Query = "select t.EmployeeID ,Qa,Trainer,TL from ActiveEmpID a inner join (select EmployeeID,   Qa_ops as Qa ,Trainer,ReportTo as TL  from whole_details_peremp where cm_id='" . $cmid . "')t on a.EmployeeID=t.EmployeeID";
	$Query = "select t.EmployeeID ,Qa,Trainer,TL from ActiveEmpID a inner join (select EmployeeID,   Qa_ops as Qa ,Trainer,ReportTo as TL  from whole_details_peremp where cm_id=?)t on a.EmployeeID=t.EmployeeID";
	$dataarray["Status"] = "0";
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("i", $cmid);
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
		$dataarray["data"] = "CMID NOT EXIST";
		$dataarray["Status"] = "0";
	}
} else {
	$dataarray["data"] = "invalid request";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);
	//print_r($dataarray);
