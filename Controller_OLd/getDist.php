<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ctr = clean($_REQUEST['ctr']);
$sql = 'call getDist("' . $ctr . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option>' . $value['district'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
