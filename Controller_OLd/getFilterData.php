<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');

$action = cleanUserInput($_GET['action']);
if (isset($action) and $action == 'bulkmsg') {
	$message = cleanUserInput($_GET['message']);
	$logid = cleanUserInput($_GET['logid']);
	$logname = cleanUserInput($_GET['logname']);
	$loc = cleanUserInput($_GET['location']);
	$client = cleanUserInput($_GET['client']);
	$process = cleanUserInput($_GET['process']);
	$subprocess = cleanUserInput($_GET['subprocess']);
	$desg = cleanUserInput($_GET['designation']);
	$where = array();
	$where[] = 'e.emp_status = "Active"';
	if (isset($loc) and $loc != "all") {
		$where[] = 'p.location = "' . $loc . '"';
	}
	if (isset($client) and $client != "all") {
		$where[] = 'c.client_id = "' . $client . '"';
	}
	if (isset($process) and $process != "all") {

		$where[] = 'process = "' . str_replace('_', ' ', $process) . '"';
	}
	if (isset($subprocess) and $subprocess != "all") {
		$where[] = 'sub_process = "' . str_replace('_', ' ', $subprocess) . '"';
	}
	if (isset($desg) and $desg != "all") {
		$where[] = 'des_id = "' . str_replace('_', ' ', $desg) . '"';
	}
	// use the array with the "implode" function to join its parts
	$sql = "select 
e.EmployeeID,  e.cm_id,  EmployeeName, client_id, c.client_name,   process, p.location,  sub_process,  des_id
from employee_map e
left  join personal_details p on p.EmployeeID = e.EmployeeID
left  join new_client_master nc on nc.cm_id = e.cm_id
left  join client_master c on c.client_id = nc.client_name
left  join df_master d on d.df_id = e.df_id
where " . implode(" and ", $where) . "";


	// $myDB = new MysqliDb();
	$result = $myDB->query($sql);
	if (count($result) > 0 && $result) {

		//$data_array=mysql_fetch_array($result);

		foreach ($result as $key => $value) {

			$sql = "call Add_Chat_message('" . $message . "','" . $value['EmployeeID'] . "','" . $logid . "','" . $logname . "')";
			$resultBy = $myDB->query($sql);
		}
		$tableValue = "<p>Message Send Successfully</p>";
	} else {
		$tableValue = '<p>EmployeeID  Not Exists Message Not Send</p>';
	}
	echo $tableValue;
}

$loc_id = cleanUserInput($_GET['location_id']);
if (isset($loc_id) and $loc_id !== '') {
	$sqlclient = "select client_id,client_name from client_master where client_id in (select distinct client_name from new_client_master where location=?) order by client_name";
	$selectQ = $conn->prepare($sqlclient);
	$selectQ->bind_param("i", $loc_id);
	$selectQ->execute();
	$result = $selectQ->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlclient);
	$tableValue = "";
	if ($result->num_rows > 0 && $result) {


		$tableValue .= '<option Selected="True" Value="all">-All Client-</option>';
		foreach ($result as $key => $value) {
			$tableValue .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
		}
	} else {
		$tableValue .= '<option Selected="True" Value="all">-All Client-</option>';
	}
	echo $tableValue;
}

$clientid = cleanUserInput($_GET['clientid']);
if (isset($clientid) and $clientid !== '') {

	$loc_id =  cleanUserInput($_GET['location']);

	$sqlprocess = "select distinct process from new_client_master where location=? and client_name=? order by process";
	$selectQ = $conn->prepare($sqlprocess);
	$selectQ->bind_param("ii", $loc_id, $clientid);
	$selectQ->execute();
	$result = $selectQ->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlprocess);
	$tableValue = "";
	if ($result->num_rows > 0 && $result) {

		//$data_array=mysql_fetch_array($result);
		$tableValue .= '<option Selected="True" Value="all">-All Process-</option>';
		foreach ($result as $key => $value) {
			$tableValue .= "<option value=" . str_replace(' ', '_', $value['process']) . ">" . $value['process'] . "</option>";
		}
	} else {
		$tableValue .= '<option Selected="True" Value="all">-All Process-</option>';
	}
	echo $tableValue;
}

$process = cleanUserInput($_GET['processid']);
if (isset($process) and $process !== '') {

	$loc = cleanUserInput($_GET['loc']);
	$client_id = cleanUserInput($_GET['client_id']);

	$sqlsubprocess = "select distinct sub_process from new_client_master where location=? and client_name=? and process=? order by sub_process";
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlsubprocess);
	$selectQ = $conn->prepare($sqlsubprocess);
	$selectQ->bind_param("iis", $loc, $client_id, $process);
	$selectQ->execute();
	$result = $selectQ->get_result();
	$tableValue = "";
	if ($result->num_rows > 0 && $result) {
		$tableValue .= '<option Selected="True" Value="all">-All SubProcess-</option>';
		foreach ($result as $key => $value) {
			$tableValue .= "<option value=" . str_replace(' ', '_', $value['sub_process']) . ">" . $value['sub_process'] . "</option>";
			//$tableValue.="<option value=".$value['sub_process'].">".$value['sub_process']."</option>";
		}
	} else {
		$tableValue .= '<option Selected="True" Value="all">-All SubProcessel-</option>';
	}
	echo $tableValue;
}
/*
select 
e.EmployeeID,  e.cm_id,  EmployeeName, client_id, c.client_name,   process, p.location,  sub_process,  des_id
from employee_map e
left  join personal_details p on p.EmployeeID = e.EmployeeID
left  join new_client_master nc on nc.cm_id = e.cm_id
left  join client_master c on c.client_id = nc.client_name
left  join df_master d on d.df_id = e.df_id
where  e.emp_status='Active' 
#and c.client_name='Information Technology' and process='Software Development' and sub_process='Software Development'
#and des_id=5 and p.location=1
limit 100;
	*/
