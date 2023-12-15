<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');

$ID = clean($_REQUEST['ID']);

echo $sql = 'call sp_getMsgTrail("' . $ID . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		echo '<p><span><b>' . $value['EmployeeName'] . ' (' . $value['CreatedBy'] . ')</b> </span> <span class="blue-text">' . $value['CreatedOn'] . '</span> : ' . $value['Comments'] . '</p>';
	}
	echo '<br />';
} else {
	echo 'No Comment ';
}
