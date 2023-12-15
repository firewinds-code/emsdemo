<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	$myDB = new MysqliDb();
	//$_REQUEST['cm_id'] = Clientname
	if ($_REQUEST['type'] == "1") {
		$Query = "select EmployeeName,EmployeeID from whole_details_peremp where client_name =(select client_name from new_client_master where cm_id='" . $_REQUEST['cmid'] . "') and Process= (select Process from new_client_master where cm_id='" . $_REQUEST['cmid'] . "') order by EmployeeName";
		//$Query = "select EmpName as EmployeeName,a.EmployeeID,c.client_name,Process,sub_process from  ActiveEmpID a  left join EmpID_Name n on a.EmployeeID=n.EmpID left join new_client_master nc on a.cm_id= nc.cm_id left join  client_master c on nc.client_name=c.client_id where c.client_name = '" . $_REQUEST['cm_id'] . "' and Process= '" . $_REQUEST['process'] . "' order by EmployeeName ";
	} else {
		$Query = "select EmployeeName,EmployeeID from whole_details_peremp where client_name =(select client_name from new_client_master where cm_id='" . $_REQUEST['cmid'] . "') and Process= (select Process from new_client_master where cm_id='" . $_REQUEST['cmid'] . "') order by EmployeeName";
		//$Query = "select EmpName as EmployeeName,a.EmployeeID,c.client_name,Process,sub_process from  ActiveEmpID a  left join EmpID_Name n on a.EmployeeID=n.EmpID left join new_client_master nc on a.cm_id= nc.cm_id left join  client_master c on nc.client_name=c.client_id where c.client_name = '" . $_REQUEST['cm_id'] . "' and Process= '" . $_REQUEST['process'] . "' order by EmployeeName ";
	}
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
