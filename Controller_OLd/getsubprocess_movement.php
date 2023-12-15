<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loc = '';

$loc = clean($_REQUEST['loc']);
$type = clean($_REQUEST['type']);
$client = clean($_REQUEST['client']);
$process = clean($_REQUEST['process']);

$sql = 'select distinct sub_process,cm_id from whole_details_peremp where location=? and client_name=? and process=? and cm_id not in (select cm_id from client_status_master) and id in (7,8,10)';

$sel = $conn->prepare($sql);
$sel->bind_param("iss", $loc, $client, $process);
$sel->execute();
$result = $sel->get_result();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['cm_id'] . '">' . $value['sub_process'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
