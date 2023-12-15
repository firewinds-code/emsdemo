<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;
$myDB = new MysqliDb();
$Query = "call getState('India')";
$res = $myDB->query($Query);
if ($res) {
	foreach ($res as $key => $value) {
		$result[] = $value;
	}
	$result = json_encode($result);
	echo $result;
} else {
	echo NULL;
}
