<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if ($_REQUEST['ID'] == 'NA') {
	echo '<option value="NA" >---Select---</option>';
} elseif ($_REQUEST['ID'] == 'Other') {
	echo '<option value="NA" >---Select---</option><option >Other</option>';
} else {
	$sql = 'SELECT * FROM ems.education_name where edu_lvl ="' . $_REQUEST['ID'] . '"';
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			echo '<option>' . $value['edu_name'] . '</option>';
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
