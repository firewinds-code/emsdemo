<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'call GetRequestDetailsByID("' . $ID . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		foreach ($value as $k => $v) {
			echo $v . '|$|';
			/*foreach($v as $ke => $val)
				{
					echo $val.'|$|';
				}*/
		}
	}
} else {
	echo 'No Comment ';
}
