<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loc = '';
$loc = clean($_REQUEST['loc']);
$type = clean($_REQUEST['type']);
$mode = clean($_REQUEST['mode']);
if (isset($loc) && $loc != "" && isset($type) && $type != "") {

	$client = clean($_REQUEST['client']);
	$process = clean($_REQUEST['process']);
	$subprocess = clean($_REQUEST['subprocess']);

	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	//$sql='select nc.*,cm.*,t1.location from new_client_master nc inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location where t1.id="'.$loc.'" order by cm.client_name';

	if ($type == "client") {
		$sql = 'select client_id, client_name from client_master where client_id in (select distinct client_name from new_client_master where location=?) order by client_name';

		$sel = $conn->prepare($sql);
		$sel->bind_param("i", $loc);
		$sel->execute();
		$result = $sel->get_result();

		// $result = $myDB->query($sql);
		// $mysql_error = $myDB->getLastError();
		if ($result->num_rows > 0 && $result) {
			echo '<option value="NA" >---Select---</option>';
			foreach ($result as $key => $value) {
				echo '<option value="' . $value['client_id'] . '">' . $value['client_name'] . '</option>';
			}
		} else {
			echo '<option value="NA" >---Select---</option>';
		}
	} else if ($type == "Process") {
		$sql = 'select distinct process from new_client_master where location=? and client_name=?';
		$sel = $conn->prepare($sql);
		$sel->bind_param("ii", $loc, $client);
		$sel->execute();
		$result = $sel->get_result();
		// $myDB = new MysqliDb();
		// $result = $myDB->query($sql);
		// $mysql_error = $myDB->getLastError();
		if ($result->num_rows > 0 && $result) {
			echo '<option value="NA" >---Select---</option>';
			foreach ($result as $key => $value) {
				echo '<option value="' . $value['process'] . '">' . $value['process'] . '</option>';
			}
		} else {
			echo '<option value="NA" >---Select---</option>';
		}
	} else if ($type == "SubProcess") {
		$sql = 'select sub_process,cm_id from new_client_master where location=? and client_name=? and process=? and cm_id not in (select cm_id from client_status_master)';
		$sel = $conn->prepare($sql);
		$sel->bind_param("iis", $loc, $client, $process);
		$sel->execute();
		$result = $sel->get_result();
		// $myDB = new MysqliDb();
		// $result = $myDB->query($sql);
		// $mysql_error = $myDB->getLastError();
		if ($result->num_rows > 0 && $result) {
			echo '<option value="NA" >---Select---</option>';
			foreach ($result as $key => $value) {
				echo '<option value="' . $value['cm_id'] . '">' . $value['sub_process'] . '</option>';
			}
		} else {
			echo '<option value="NA" >---Select---</option>';
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
} else if (isset($mode) && $mode != "") {

	$type = clean($_REQUEST['type']);
	$client = clean($_REQUEST['client']);
	$process = clean($_REQUEST['process']);
	$mode = clean($_REQUEST['mode']);

	if ($mode == "Online") {
		if ($type == "client") {
			$sql = 'select client_id, client_name from client_master where client_id in (select distinct client_name from new_client_master) order by client_name';

			$myDB = new MysqliDb();
			$result = $myDB->query($sql);
			$mysql_error = $myDB->getLastError();
			if (count($result) > 0 && $result) {
				echo '<option value="NA" >---Select---</option>';
				foreach ($result as $key => $value) {
					echo '<option value="' . $value['client_id'] . '">' . $value['client_name'] . '</option>';
				}
			} else {
				echo '<option value="NA" >---Select---</option>';
			}
		} else if ($type == "Process") {
			$sql = 'select distinct process from new_client_master where client_name=?';
			$sel = $conn->prepare($sql);
			$sel->bind_param("i",  $client);
			$sel->execute();
			$result = $sel->get_result();
			// $myDB = new MysqliDb();
			// $result = $myDB->query($sql);
			// $mysql_error = $myDB->getLastError();
			if ($result->num_rows > 0 && $result) {
				echo '<option value="NA" >---Select---</option>';
				foreach ($result as $key => $value) {
					echo '<option value="' . $value['process'] . '">' . $value['process'] . '</option>';
				}
			} else {
				echo '<option value="NA" >---Select---</option>';
			}
		} else {
			echo '<option value="NA" >---Select---</option>';
		}
	}
}
