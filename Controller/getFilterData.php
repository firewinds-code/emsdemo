<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');

if (isset($_GET['action']) and $_GET['action'] == 'bulkmsg') {
	$message = $_GET['message'];
	$logid = $_GET['logid'];
	$logname = $_GET['logname'];

	$where = array();
	$where[] = 'e.emp_status = "Active"';
	if (isset($_GET['location']) and $_GET['location'] != "all") {
		$where[] = 'p.location = "' . $_GET['location'] . '"';
	}
	if (isset($_GET['client']) and $_GET['client'] != "all") {
		$where[] = 'c.client_id = "' . $_GET['client'] . '"';
	}
	if (isset($_GET['process']) and $_GET['process'] != "all") {

		$where[] = 'process = "' . str_replace('_', ' ', $_GET['process']) . '"';
	}
	if (isset($_GET['subprocess']) and $_GET['subprocess'] != "all") {
		$where[] = 'sub_process = "' . str_replace('_', ' ', $_GET['subprocess']) . '"';
	}
	if (isset($_GET['designation']) and $_GET['designation'] != "all") {
		$where[] = 'des_id = "' . str_replace('_', ' ', $_GET['designation']) . '"';
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


	$myDB = new MysqliDb();
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

if (isset($_GET['location_id']) and $_GET['location_id'] !== '') {
	$loc_id = $_GET['location_id'];

	$sqlclient = "select client_id,client_name from client_master where client_id in (select distinct client_name from new_client_master where location='" . $loc_id . "' and cm_id not in (select cm_id from client_status_master))  order by client_name";
	$myDB = new MysqliDb();
	$result = $myDB->query($sqlclient);
	$tableValue = "";
	if (count($result) > 0 && $result) {


		$tableValue .= '<option Selected="True" Value="all">-All Client-</option>';
		foreach ($result as $key => $value) {
			$tableValue .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
		}
	} else {
		$tableValue .= '<option Selected="True" Value="all">-All Client-</option>';
	}
	echo $tableValue;
}


if (isset($_GET['clientid']) and $_GET['clientid'] !== '') {
	$clientid = $_GET['clientid'];
	$loc_id = $_GET['location'];

	$sqlprocess = "select distinct process from new_client_master where location='" . $loc_id . "' and client_name='" . $clientid . "' order by process";
	$myDB = new MysqliDb();
	$result = $myDB->query($sqlprocess);
	$tableValue = "";
	if (count($result) > 0 && $result) {

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

if (isset($_GET['processid']) and $_GET['processid'] !== '') {
	$process = $_GET['processid'];
	$loc = $_GET['loc'];
	$client_id = $_GET['client_id'];

	$sqlsubprocess = "select distinct sub_process from new_client_master where location='" . $loc . "' and client_name='" . $client_id . "' and process='" . $process . "' order by sub_process";
	$myDB = new MysqliDb();
	$result = $myDB->query($sqlsubprocess);
	$tableValue = "";
	if (count($result) > 0 && $result) {


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
