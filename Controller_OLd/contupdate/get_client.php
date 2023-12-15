<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$loCation = clean($_REQUEST['location1']);

if (isset($loCation)) {

	$sqlclient = "select client_id,client_name from client_master where client_id in (select distinct client_name from whole_details_peremp where location=?) order by client_name";
	$sql = $conn->prepare($sqlclient);
	$sql->bind_param("i", $loCation);
	$sql->execute();
	$result = $sql->get_result();
	$count = $result->num_rows;

	$toption = '<option value="NA"> -Select Client Name- </option>';
	$toption1 = '<option value="NA"> -Select Process- </option>';
	$toption2 = '<option value="NA"> -Select Sub_process- </option>';
	$toption3 = '<option value="NA"> -Select reports_to- </option>';


	foreach ($result as $key => $value) {
		$toption .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
	}

	$data['client_name1'] = $toption;
	$data['process1'] = $toption1;
	$data['sub_process1'] = $toption2;
	$data['reports_to1'] = $toption3;
}
echo json_encode($data);
