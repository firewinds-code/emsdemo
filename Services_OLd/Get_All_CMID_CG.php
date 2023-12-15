<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
/*require_once('../init.php');
	$default59 = array('host' => '192.168.202.252','user' => 'root','pass' => 'india@123','db' => 'ems');                        
	$myDB->__destruct($default59);
	$myDB->__construct($default59);*/

if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
	if ((substr($_REQUEST['EmployeeID'], 0, 2) == 'CE') || (substr($_REQUEST['EmployeeID'], 0, 2) == 'MU')) {
		$empid = clean($_REQUEST['EmployeeID']);
	}
}



if ($_REQUEST) {
	$myDB = new MysqliDb();
	/*$Query="select cm_id,client_name,process,sub_process from new_client_master where concat( client_name,process)  in 
(select concat( client_name,process) from whole_details_peremp where EmployeeID ='".$_REQUEST['EmployeeID']."' or  qh ='".$_REQUEST['EmployeeID']."')";*/

	// $Query="select distinct w.cm_id,w.client_name,w.process,w.sub_process from new_client_master c 
	// join whole_details_peremp w  on concat( c.client_name,c.process)= concat( w.client_name,w.process)
	//  where w.EmployeeID ='".$empid."' or  w.qh ='".$empid."'";
	// 				$res =$myDB->query($Query);
	///

	$query = "select distinct w.cm_id,w.client_name,w.process,w.sub_process from new_client_master c 
				join whole_details_peremp w  on concat( c.client_name,c.process)= concat( w.client_name,w.process)
				 where w.EmployeeID =? or  w.qh =?";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("ss", $empid, $empid);
	$stmt->execute();
	$res = $stmt->get_result();
	$count = $res->num_rows;

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
