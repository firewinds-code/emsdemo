<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loc = clean($_REQUEST['id']);
if (isset($loc) && $loc != '') {
	$loc = clean($_REQUEST['id']);
} else {
	$loc = '';
}
$sql = 'SELECT distinct concat(t2.client_name,"|",t1.process,"|",t1.sub_process) as Process,t1.cm_id from new_client_master t1 join client_master t2 on t1.client_name = t2.client_id left join client_status_master t3 on t1.cm_id=t3.cm_id where t1.location= ? and t3.cm_id is null order by process';
// $myDB = new MysqliDb();
// $result = $myDB->query($sql);

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loc);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->num_rows;
$mysql_error = $myDB->getLastError();
if ($result->num_rows > 0) {
	// if (count($result) > 0 && $result) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['cm_id'] . '" >' . $value['Process'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
