<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
/*require_once('../init.php');
	$default59 = array('host' => '192.168.202.252','user' => 'root','pass' => 'india@123','db' => 'ems');                        
	$myDB->__destruct($default59);
	$myDB->__construct($default59);*/


// Uncomment if Need to provide all operation related sheet
$isin = 0;
// $emplist = [""];
// foreach ($emplist as $string) {

// 	if (strpos($_REQUEST['EmployeeID'], $string) !== false) {
// 		$isin = 1;
// 		break;
// 	}
// }


if ($_REQUEST) {
	$myDB = new MysqliDb();
	if ($isin == 1) {

		$Query = "select distinct w.cm_id,w.client_name,w.process,w.sub_process from whole_details_peremp w   where w.cm_id not in (47,36,34,5,6,59,37,35,186,29)";
	} else {
		$people = array("OFF8438", "7073695246", "APOLLO1", "DEEPAK", "DEEPAKM", "MS@TATAAIG.COM", "ANUJBHATT", "9654124157", "9650055338", "9555428884", "ANJANI");
		if ($_REQUEST['emp_type'] == "Demo" || in_array($_REQUEST['EmployeeID'], $people)) {
			$Query = "select distinct cm_id,client_name,process,w.location from new_client_master c left join  (select concat( client_name,process) cp,location from new_client_master nc inner join ActiveEmpID a on nc.cm_id=a.cm_id  where client_name ='" . $_REQUEST['client'] . "' or client_name ='" . $_REQUEST['client'] . "' )w on concat(c.client_name,c.process)  =w.cp and c.location=w.location where w.location is not null;";
		} else {
			//if data required by Employee Location
			//$Query = "select distinct cm_id,client_name,process,w.location from new_client_master c left join  (select concat( client_name,process) cp,location from new_client_master nc inner join ActiveEmpID a on nc.cm_id=a.cm_id  where EmployeeID ='" . $_REQUEST['EmployeeID'] . "' or qh ='" . $_REQUEST['EmployeeID'] . "')w on concat(c.client_name,c.process)  =w.cp and c.location=w.location where w.location is not null;";

			//if data not required by Employee Location
			$Query = "select distinct cm_id,client_name,process,w.location from new_client_master c left join (select concat(client_name,process) cp,location from new_client_master nc inner join ActiveEmpID a on nc.cm_id=a.cm_id  where EmployeeID ='" . $_REQUEST['EmployeeID'] . "' or qh ='" . $_REQUEST['EmployeeID'] . "') w on concat(c.client_name,c.process)  =w.cp  where w.location is not null";
		}
	}
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
