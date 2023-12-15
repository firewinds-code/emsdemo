<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
$myDB = new MysqliDb();

$conn = $myDB->dbConnect();
// Main contain Header file which contains html , head , body , one default form 
$client_name1 = clean(base64_decode($_REQUEST['client_name1']));
$location1 = clean(base64_decode($_REQUEST['location1']));
if (isset($client_name1) && ($location1)) {
	$Query = "select distinct process from new_client_master where client_name=? and location=?";
	$sel = $conn->prepare($Query);
	$sel->bind_param("ii", $client_name1, $location1);
	$sel->execute();
	$result = $sel->get_result();

	// $myDB = new MysqliDb();
	// $result = $myDB->query($Query);

	$toption = '<option value="NA"> -Select Process- </option>';
	$toption1 = '<option value="NA"> -Select Sub_process- </option>';
	$toption2 = '<option value="NA"> -Select reports_to- </option>';
	foreach ($result as $r) {
		//print_r($r);
		$toption .= "<option value='" . base64_encode($r['process']) . "'>" . $r['process'] . "</option>";
	}
	$data['process1'] = $toption;
	$data['sub_process1'] = $toption1;
	$data['reports_to1'] = $toption2;
}
echo json_encode($data);
