<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data = json_decode($_POST, true);
$getPara = $sql = $result = '';
$status = array();
$cmid = '';

if (isset($Data['appkey']) and $Data['appkey'] == 'cmiddetail') {

	if (isset($Data['cmid'])) {
		$cmid = cleanUserInput($Data['cmid']);
	}
	if ($cmid != '') {
		// $sql = "select b.client_name,`process`,sub_process,account_head,reporttoname(account_head) ahname,th,reporttoname(th) thname, oh,reporttoname(oh) ohname,qh,reporttoname(qh) qhname from new_client_master a inner join client_master b on a.client_name=b.client_id where a.cm_id Not IN(select  cm_id  from client_status_master) and a.cm_id='" . $cmid . "'";
		$sql = "select b.client_name,`process`,sub_process,account_head,reporttoname(account_head) ahname,th,reporttoname(th) thname, oh,reporttoname(oh) ohname,qh,reporttoname(qh) qhname from new_client_master a inner join client_master b on a.client_name=b.client_id where a.cm_id Not IN(select  cm_id  from client_status_master) and a.cm_id=?";

		// $myDB = new MysqliDb();
		// $result = $myDB->query($sql);
		$stmte = $conn->prepare($sql);
		$stmte->bind_param("s", $cmid);
		$stmte->execute();
		$res = $stmte->get_result();
		$result = $res->fetch_all(MYSQLI_ASSOC);

		$status['data'] = $result;
		$status['msg'] = 'getdata';
	} else {
		$status['data'] = '';
		$status['msg'] = 'cm_id not found';
	}
} else {
	$status['data'] = '';
	$status['msg'] = 'set proper app key';
}
print_r(json_encode($status));
