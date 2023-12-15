<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'call cap_get_comment("' . $ID . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		echo '<p style="padding: 6px;"><span><b>' . $value['created_by'] . '</b> </span> <span class="blue-text">' . $value['created_at'] . '</span> : ' . $value['comment'] . '</p>';
	}
	//echo '<br />';
} else {
	echo '';
}
