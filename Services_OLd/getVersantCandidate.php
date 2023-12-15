<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;
$cm_id = '';
if ((strlen($_REQUEST['cmid']) <= 5) && is_numeric($_REQUEST['cmid'])) {
	$cm_id = trim($_REQUEST['cmid']);
}

if (isset($_REQUEST['cmid']) && $cm_id != "" && isset($_REQUEST['desig']) && trim($_REQUEST['desig']) != "" && ctype_alpha($_REQUEST['desig'])) {
	$cmid = trim($_REQUEST['cmid']);
	$desig = trim($_REQUEST['desig']);
	if ($desig != 'CSA' && $desig != 'FIELD EXECUTIVE') {
		$Query = "select 'P Map Test' as cert_name,'PMapTest' as filename;";
		$selectQ = $conn->prepare($Query);
	} else {
		$Query = "select a.ID, a.cm_id, a.cert_name, a.filename from certification_require_by_cmid a where a.cm_id=?";
		$selectQ = $conn->prepare($Query);
		$selectQ->bind_param("i", $cmid);
	}
	$selectQ->execute();
	$res = $selectQ->get_result();
	// $myDB = new MysqliDb();
	// $res = $myDB->query($Query);
	if ($res->num_rows > 0) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo NULL;
	}
}
