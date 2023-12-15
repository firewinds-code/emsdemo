<?php

require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$lvl = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['lvl']) && $_REQUEST['lvl'] != '') {
	$lvl = $_REQUEST['lvl'];
}
$id = clean(intval($_REQUEST['id']));
$Loc = clean(intval($_REQUEST['loc']));

if ($_REQUEST['type'] == 'th') {

	if ($lvl == 'Yes') {
		$sql = 'select nc.cm_id,dt.training_days from batch_master bt inner join new_client_master nc on bt.cm_id  = nc.cm_id and bt.cm_id=nc.cm_id inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID =?';
		$sel1 = $conn->prepare($sql);
		$sel1->bind_param("i", $id);
		$sel1->execute();
	} else {
		$sql = 'select nc.cm_id,dt.training_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.process = nc.process and bt.subprocess = nc.sub_process inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID = ? and nc.location = ?';
		$sel1 = $conn->prepare($sql);
		$sel1->bind_param("ii", $id, $Loc);
		$sel1->execute();
	}
	$result = $sel1->get_result();
	if ($result->num_rows > 0 && $result) {
		foreach ($result as $key => $value) {
			echo $value['training_days'];
		}
	} else {
		echo 0;
	}
} elseif ($_REQUEST['type'] == 'ojt') {
	//$sql='select nc.cm_id,dt.ojt_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.process = nc.process and bt.subprocess = nc.sub_process inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID ="'.intval($_REQUEST['id']).'" and nc.location="'.intval($_REQUEST['loc']).'"';
	if ($lvl == 'Yes') {
		$sql = 'select nc.cm_id,dt.ojt_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.cm_id=nc.cm_id inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID = ? ';
		$sel1 = $conn->prepare($sql);
		$sel1->bind_param("i", $id);
		$sel1->execute();
	} else {
		$sql = 'select nc.cm_id,dt.ojt_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.cm_id=nc.cm_id inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID = ? and nc.location = ?';
		$sel1 = $conn->prepare($sql);
		$sel1->bind_param("ii", $id, $Loc);
		$sel1->execute();
	}
	$result = $sel1->get_result();
	if ($result->num_rows > 0 && $result) {
		foreach ($result as $key => $value) {
			echo $value['ojt_days'];
		}
	} else {
		echo 0;
	}
}
