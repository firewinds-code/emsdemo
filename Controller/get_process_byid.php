<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$clientId = $_REQUEST['clientId'];
$prcess = $_REQUEST['prcess'];
$locId = $_REQUEST['locId'];


$Query = "select distinct process from new_client_master where client_name=? and  location=? and cm_id not in (select cm_id from client_status_master)";
$sql = $conn->prepare($Query);
$sql->bind_param("ss", $clientId, $locId);
$sql->execute();
$result = $sql->get_result();
$count = $result->num_rows;

$toption = '';
foreach ($result as $r) {
	//print_r($r);
	if ($prcess == '') {

		$toption .= "<option value='" . $r['process'] . "'>" . $r['process'] . "</option>";
	} else {
		$slected =  $r['process'] == $prcess ? 'Selected' : '';
		$toption .= "<option $slected value='" . $r['process'] . "'>" . $r['process'] . "</option>";
	}
}
// $data['toption'] = $toption;
echo $toption;
