<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'call cap_get_issue_comment("' . $ID . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		echo $value['comment'];
	}
	//echo '<br />';
} else {
	echo '';
}
