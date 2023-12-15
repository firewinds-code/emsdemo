<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$Process = clean(base64_decode($_REQUEST['process1']));
$LOc = clean(base64_decode($_REQUEST['location1']));
if (isset($Process) && ($LOc)) {
	$Query = "select sub_process,cm_id from new_client_master where process=? and location=? and cm_id not in (select cm_id from client_status_master)";
	$sql = $conn->prepare($Query);
	$sql->bind_param("si", $Process, $LOc);
	$sql->execute();
	$result = $sql->get_result();

	$toption = '<option value="NA"> -Select Sub Process- </option>';
	$toption1 = '<option value="NA"> -Select Reports_to- </option>';

	foreach ($result as $r) {
		//print_r($r);
		$toption .= "<option value='" . base64_encode($r['cm_id']) . "'>" . $r['sub_process'] . "</option>";
	}
	$data['sub_process1'] = $toption;
	$data['reports_to1'] = $toption1;
}

echo json_encode($data);
?>
<?php


?>