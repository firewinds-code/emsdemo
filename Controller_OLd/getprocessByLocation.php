<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$loc = '';
$loc = clean($_REQUEST['loc']);
if (isset($_REQUEST['loc']) && $loc != "") {
	$cmid = clean($_REQUEST['cmid']);
	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	$sql = 'select nc.*,cm.*,t1.location from new_client_master nc inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location where t1.id=? and nc.cm_id not in (select cm_id from client_status_master) order by cm.client_name';
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("i", $loc);
	$selectQ->execute();
	$result = $selectQ->get_result();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();
	if ($result->num_rows > 0 && $result) {
		echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($cmid != '' && $cmid == $value['cm_id']) {
				echo '<option value="' . $value['cm_id'] . '"  selected>' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</option>';
			} else {
				echo '<option value="' . $value['cm_id'] . '"  >' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</option>';
			}
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
