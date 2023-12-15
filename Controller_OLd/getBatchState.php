<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$BatchID = intval($_REQUEST['id']);
$loc = intval($_REQUEST['loc']);
$type = clean($_REQUEST['type']);
if ($type == 'th') {
	$sql = 'select nc.cm_id,dt.training_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.process = nc.process and bt.subprocess = nc.sub_process inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID =? and nc.location=?';
	$sel = $conn->prepare($sql);
	$sel->bind_param("ii", $BatchID, $loc);
	$sel->execute();
	$result = $sel->get_result();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();

	if ($result->num_rows > 0 && $result) {
		foreach ($result as $key => $value) {
			echo $value['training_days'];
		}
	} else {
		echo 0;
	}
} elseif ($type == 'ojt') {
	//$sql='select nc.cm_id,dt.ojt_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.process = nc.process and bt.subprocess = nc.sub_process inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID ="'.intval($_REQUEST['id']).'" and nc.location="'.intval($_REQUEST['loc']).'"';

	$sql = 'select nc.cm_id,dt.ojt_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.cm_id=nc.cm_id inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID =? and nc.location=?';
	$sel = $conn->prepare($sql);
	$sel->bind_param("ii", $BatchID, $loc);
	$sel->execute();
	$result = $sel->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();

	if ($result->num_rows > 0 && $result) {
		foreach ($result as $key => $value) {
			echo $value['ojt_days'];
		}
	} else {
		echo 0;
	}
}
