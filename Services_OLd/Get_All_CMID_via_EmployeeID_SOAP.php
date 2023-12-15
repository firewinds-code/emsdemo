<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID =$empid= $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
/*require_once('../init.php');
	$default59 = array('host' => '192.168.202.252','user' => 'root','pass' => 'india@123','db' => 'ems');                        
	$myDB->__destruct($default59);
	$myDB->__construct($default59);*/


/* if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
	if ((substr($_REQUEST['EmployeeID'], 0, 2) == 'CE') || (substr($_REQUEST['EmployeeID'], 0, 2) == 'MU')) {
		$empid = clean($_REQUEST['EmployeeID']);
	}
} */

$empid = clean($_REQUEST['EmployeeID']);
$isin = 0;
$emplist = ["CE061930045", "CE061930050", "CE071728286", "CE071728536", "CE071930074", "CE121622091", "CE101930165", "CE121829689", "CE121829697"];
foreach ($emplist as $string) {

	if (strpos($empid, $string) !== false) {
		$isin = 1;
		break;
	}
}


if ($_REQUEST) {
	$myDB = new MysqliDb();
	if ($isin == 1) {
		//$Query="select distinct w.cm_id,w.client_name,w.process,w.sub_process from new_client_master c join whole_details_peremp w  on c.cm_id=w.cm_id where w.cm_id not in (47,36,34,5,6,59,37,35,186,29)";

		$Query = "select distinct w.cm_id,w.client_name,w.process,w.sub_process from whole_details_peremp w   where w.cm_id not in (47,36,34,5,6,59,37,35,186,29)";
		$stmt = $conn->prepare($Query);
	} else {
		// echo "hjkhjkk";
		//$Query="select distinct cm_id,client_name,process,sub_process,c.location,w.location from new_client_master c inner join (select concat(client_name,process) cp,location from whole_details_peremp where EmployeeID ='".$_REQUEST['EmployeeID']."' or  qh ='".$_REQUEST['EmployeeID']."')w on concat(c.client_name,c.process)  =w.cp and c.location=w.location;";
		if ($_REQUEST['emp_type'] == "Demo") {
			$sql_client = cleanUserInput($_REQUEST['client']);
			$Query = "select distinct cm_id,client_name,process,w.location from new_client_master c
			left join  (
			select concat( client_name,process) cp,location from new_client_master nc
			inner join ActiveEmpID a on nc.cm_id=a.cm_id  where client_name =? or client_name =?
			)w on concat(c.client_name,c.process)  =w.cp and c.location=w.location where w.location is not null;";
			$query = "select * from table_name WHERE t.ijpID=?";

			$stmt = $conn->prepare($Query);
			$stmt->bind_param("ss", $sql_client, $sql_client);
		} else {
			$Query = "select distinct cm_id,client_name,process,w.location from new_client_master c
			left join  (
			select concat( client_name,process) cp,location from new_client_master nc
			inner join ActiveEmpID a on nc.cm_id=a.cm_id  where EmployeeID =? or qh =?
			)w on concat(c.client_name,c.process)  =w.cp and c.location=w.location where w.location is not null;";
			$stmt = $conn->prepare($Query);
			$stmt->bind_param("ss", $empid, $empid);
		}



		//$Query="select cm_id,client_name,process,sub_process from new_client_master where concat( client_name,process)  in (select concat( client_name,process) from whole_details_peremp where EmployeeID ='".$_REQUEST['EmployeeID']."' or  qh ='".$_REQUEST['EmployeeID']."')";
	}
//echo $Query.' dfgsdfg '.$empid; die;
	$stmt->execute();
	$res = $stmt->get_result();
	// $count = $res->num_rows;   
//print_r($res);die;
	// $res =$myDB->query($Query);
	if ($res->num_rows > 0) {
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
