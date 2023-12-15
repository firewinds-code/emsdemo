<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$process =  clean($_REQUEST['proc']);

if ($process == 'MIS ')
	$process = 'MIS & WFM';
else
	$process =  clean($_REQUEST['proc']);
$dept = '';
$dept = clean($_REQUEST['dept']);
if (isset($dept) && $dept != "") {
	$dept = $dept;
}
$loc = clean($_REQUEST['loc']);
if (isset($loc) && $loc != "") {
	$loc = clean($_REQUEST['loc']);
}
if (isset($_REQUEST['lvl']) && $_REQUEST['lvl'] != "") {
	$lvl = $_REQUEST['lvl'];
}
$id = clean($_REQUEST['id']);
if ($lvl == '1') {
	$sql = 'call get_subprocess_byclient_approver("' . $id . '","' . $process . '")';
} else {
	$sql = 'call get_subprocess_byclient("' . $id . '","' . $process . '","' . $dept . '","' . $loc . '")';
}

$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {
	echo '<option value="NA" >Select Subprocess</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['cm_id'] . '">' . $value['sub_process'] . '</option>';
	}
} else {
	echo '<option value="NA" >Select Subprocess</option>';
}
