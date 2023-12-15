<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result['msg'] = "";
$Query = null;
// $myDB = new MysqliDb();

$Query = 'SELECT t1.cm_id,concat(t2.client_name,"|",t1.process,"|",t1.sub_process," (",t3.location,")") as Process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join location_master t3 on t1.location=t3.id where t1.cm_id not in (select cm_id from client_status_master) order by Process;';
// $res = $myDB->query($Query);
$stmt = $conn->prepare($Query);
$stmt->execute();
$res = $stmt->get_result();
$res1 = $res->fetch_all(MYSQLI_ASSOC);
// print_r($res1);
// die;
if ($res1) {

	$result['data'] = $res1;
	$result['msg'] = 'data Found';
	$result['status'] = 1;

	echo	json_encode($result);
} else {

	$result['msg'] = 'data not Found';
	$result['status'] = 0;

	echo	json_encode($result);
}
