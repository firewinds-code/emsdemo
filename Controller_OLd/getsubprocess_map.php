<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
$process =  clean($_REQUEST['proc']);
if ($process == 'MIS ')
	$process = 'MIS & WFM';
else
	$process =  clean($_REQUEST['proc']);
$dept = $loc = $sql = '';
$dept = clean($_REQUEST['dept']);
if (isset($dep) && $dep != "") {
	$dept = clean($_REQUEST['dept']);
}
$loc = clean($_REQUEST['loc']);
if (isset($loc) && $loc != "") {
	$loc = clean($_REQUEST['loc']);
}
$lvl = clean($_REQUEST['lvl']);
if ($lvl == '2') {
	$id = clean($_REQUEST['id']);
	$sql = 'call get_subprocess_byclient_new("' . $id . '","' . $process . '","' . $dept . '","' . $loc . '")';
} else {
	$id = clean($_REQUEST['id']);
	$sql = 'call get_subprocess_byclient("' . $id . '","' . $process . '","' . $dept . '","' . $loc . '")';
}
//echo $sql;
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
