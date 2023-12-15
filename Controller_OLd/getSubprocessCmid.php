<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
$process =  clean($_REQUEST['proc']);
if ($process  == 'MIS ')
	$process = 'MIS & WFM';
else
	$process =  clean($_REQUEST['proc']);

$loc = clean($_REQUEST['loc']);
if (isset($loc) && $loc != "") {
	$loc = clean($_REQUEST['loc']);
}

$id = clean($_REQUEST['id']);
$sql = 'call get_subprocess_byclient("' . $id . '","' . $process . '","' . $loc . '")';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $mysql_error) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option id="' . $value['cm_id'] . '">' . $value['sub_process'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
