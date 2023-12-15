<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$loc = clean($_REQUEST['locid']);
$conid = clean($_REQUEST['conid']);
if (isset($loc) && $loc != '' && isset($conid) && $conid != '') {
} else {
	$loc = '';
	$conid = '';
}
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$sql = "select t1.cm_id,concat(t3.client_name,'|',t2.process,'|',t2.sub_process) as Process from manage_consultancy t1 join new_client_master t2 on t1.cm_id=t2.cm_id join client_master t3 on t2.client_name=t3.client_id where locid=? and consultancy_id=? and Active=1 and (cast(now() as date) between t1.start_date and t1.end_date);";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("ii", $loc, $conid);
$selectQ->execute();
$result = $selectQ->get_result();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['cm_id'] . '" >' . $value['Process'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
