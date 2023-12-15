<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');

$sql = '';
$ID = clean($_REQUEST['id']);
$lvl = clean($_REQUEST['lvl']);
if ($lvl == '2') {
	$sql = 'call get_client_bydept_new("' . $ID . '")';
} else {
	$sql = 'call get_client_bydept("' . $ID . '")';
}

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
