<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$status_ah = clean($_SESSION['__status_ah']);
$status_oh = clean($_SESSION['__status_oh']);
$status_vh = clean($_SESSION['__status_vh']);
$user_logid = clean($_SESSION['__user_logid']);
$tablename = clean($_REQUEST['tablename']);
$id = clean($_REQUEST['id']);

if (isset($tablename) && $tablename != "") {
	// $id = $_REQUEST['id'];
	// $tablename = $_REQUEST['tablename'];
	$data = '';
	$data2 = '';
	$oh_ack = '';
	$vh_ack = '';
	$ah_ack = '';
	if ($status_ah != '' && $status_ah == $user_logid) {
		$data = '';
		$ah_ack = '1';
		$data2 = " ack_flg='" . $ah_ack . "',";
		$data = '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
	}
	if ($status_oh != '' && $status_oh == $user_logid) {
		$data = '';
		$oh_ack = '1';
		$data2 .= " oh_ack='" . $oh_ack . "', ";
		$data = '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
	}
	if ($status_vh != '' && $status_vh == $user_logid) {
		$data = '';
		$vh_ack = '1';
		$data2 .= " vh_ack='" . $vh_ack . "', ";
		$data = '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
	}
	echo  $data;

	$update = $myDB->query("update ctrctdetails_master  set  $data2 ack_date='" . date("y-m-d h:i:s") . "' where table_name='" . $tablename . "'");
	// echo "update ctrctdetails_master  set  $data2 ack_date='" . date("y-m-d h:i:s") . "' where table_name='" . $tablename . "' ";
}
