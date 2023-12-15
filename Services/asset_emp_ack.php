<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$empID = $rsnofleaving = '';
$flag = 2;
//print_r(($_GET['EmployeID']));
//print_r(($_GET['Status']));
//print_r(($_GET['Asset_details']));
//die;
if (isset($_GET['Asset_details']) && $_GET['Asset_details'] != '' && isset($_GET['EmployeID']) && $_GET['EmployeID'] != '' && isset($_GET['Status']) && $_GET['Status'] != '') {
	$getData = json_decode($_GET['Asset_details']);
	print_r($getData);
	//die;
	$myDB = new MysqliDb();
	$sql = "call asset_employee_manage('" . $_GET['EmployeID'] . "','" . $_GET['Status'] . "')";
	//die;
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		if (count($getData) > 0) {
			foreach ($getData as $data) {
				// echo $data->emp_id;
				// die;
				if (isset($data->emp_id) && isset($data->brand) && isset($data->product_name) && isset($data->brand_name) && isset($data->model_no) && isset($data->serial_no)) {
					$sql = "insert into asset_employee_details (EmpID,Asset,Asset_type,Brand,ModelNo,SerialNo) values('" . $data->emp_id . "','" . $data->brand . "','" . $data->product_name . "','" . $data->brand_name . "','" . $data->model_no . "','" . $data->serial_no . "')";
					$result = $myDB->query($sql);
				}
			}
			echo 'Asset Assigned';
		}
	}
} else {
	echo 'No Data';
}
