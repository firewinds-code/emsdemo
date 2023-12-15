<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

if (isset($_REQUEST['process1']) && ($_REQUEST['location1'])) {
	$Query = "select sub_process,cm_id from new_client_master where process='" . $_REQUEST['process1'] . "' and location='" . $_REQUEST['location1'] . "' and cm_id not in (select cm_id from client_status_master)";
	$myDB = new MysqliDb();
	$result = $myDB->query($Query);
	$toption = '<option value="NA"> -Select Sub Process- </option>';
	$toption1 = '<option value="NA"> -Select Reports_to- </option>';

	foreach ($result as $r) {
		//print_r($r);
		$toption .= "<option value='" . $r['cm_id'] . "'>" . $r['sub_process'] . "</option>";
	}
	$data['sub_process1'] = $toption;
	$data['reports_to1'] = $toption1;
}

echo json_encode($data);
?>
<?php


?>