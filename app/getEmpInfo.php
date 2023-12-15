<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
//$connection = $mysqli->mysqli();
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data = json_decode($_POST, true);
date_default_timezone_set('Asia/Kolkata');
$result = array();
$response = array();


if (isset($Data) && count($Data) > 0) {
	if (isset($Data['appkey']) && $Data['appkey'] == 'getInfo') {


		$emp_id = ($Data['LoginId']);

		if (trim($emp_id) != '') {
			$Query = "Call app_getSingleEmpInfo_new('" . $emp_id . "')";
			//  $Query="Call app_getSingleEmpInfo('".$emp_id."')";


			$res = $myDB->query($Query);
			if ($res) {
				$response['msg'] = "Success";
				$response['status'] = 1;
				$response['employ_details'] = $res[0];
			} else {
				$response['msg'] = "Invalid Id or Employee not found in our List";
				$response['status'] = 0;
			}
		} else {
			$response['msg'] = "Parameter should not be null";
			$response['status'] = 0;
		}
	} else {
		$response['msg'] = "Bad Request";
		$response['status'] = 0;
	}
} else {
	$response['msg'] = "Invalid Request";
	$response['status'] = 0;
}

echo json_encode($response);
