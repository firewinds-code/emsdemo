<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

if (isset($_REQUEST['location1'])) {
	$location1 = clean($_REQUEST['location1']);
	$sqlclient = "select distinct t2.client_id,t2.client_name from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where location=? and cm_id not in (select cm_id from client_status_master);";
	$stmt = $conn->prepare($sqlclient);
	$stmt->bind_param("i", $location1);
	$stmt->execute();
	$result = $stmt->get_result();
	//$sqlclient="select client_id,client_name from client_master where client_id in (select distinct client_name from whole_details_peremp where location='".$_REQUEST['location1']."') order by client_name";	
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlclient);


	$toption = '<option value="NA"> -Select Client Name- </option>';
	$toption1 = '<option value="NA"> -Select Process- </option>';
	$toption2 = '<option value="NA"> -Select Sub_process- </option>';
	$toption3 = '<option value="NA"> -Select reports_to- </option>';


	foreach ($result as $value) {
		$toption .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
	}

	$data['client_name1'] = $toption;
	$data['process1'] = $toption1;
	$data['sub_process1'] = $toption2;
	$data['reports_to1'] = $toption3;
}
echo json_encode($data);
