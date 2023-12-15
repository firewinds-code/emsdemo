<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$loc = $lvl = $sql = '';
$id = clean($_REQUEST['id']);
$loc = clean($_REQUEST['loc']);
$lvl = clean($_REQUEST['lvl']);
if (isset($id) && $id != '') {

	if (isset($loc) && $loc != "") {
		$loc = clean($_REQUEST['loc']);
	}
	if (isset($lvl) && $lvl != "") {
		$lvl = clean($_REQUEST['lvl']);
	}

	if ($lvl == '2') {
		$sql = 'call get_process_byclient_new("' . $id . '","' . $loc . '")';
	} else if ($lvl == '3') {
		$sql = 'call get_process_byclient_approver("' . $id . '")';
	} else {
		$sql = 'call get_process_byclient("' . $id . '","' . $loc . '")';
	}
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			echo '<option>' . $value['process'] . '</option>';
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
