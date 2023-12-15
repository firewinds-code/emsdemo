<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$dataarray["Status"] = "0";
$key = clean($_REQUEST['key']);
$empid = clean($_REQUEST['empid']);
if ($key == "pfesic") {
	if ($_REQUEST) {
		$myDB = new MysqliDb();
		//$Query="select e.EmployeeID,OnFloor as DOD from employee_map e inner join status_table s on e.EmployeeID=s.EmployeeID where DATEDIFF(now(),OnFloor)<=30 and e.emp_status='Active' and Status=6  and cm_id ='".$_REQUEST['cmid']."' ";
		$Query = "SELECT a.EmployeeID,p.EmployeeName,c.mobile,a.doj,s.uan_no,s.esi_no from ActiveEmpID a inner join salary_details s on a.EmployeeID=s.EmployeeID inner join personal_details p on a.EmployeeID=p.EmployeeID inner join contact_details c on a.EmployeeID=c.EmployeeID where a.EmployeeID=?";

		$stmt = $conn->prepare($Query);
		$stmt->bind_param("s", $empid);
		$stmt->execute();
		$res = $stmt->get_result();
		$res = mysqli_fetch_array($res);

		// $res = $myDB->query($Query);
		if ($res) {
			$dataarray["data"] = $res;
			$dataarray["Status"] = "1";
		} else {
			$dataarray["data"] = "No Data Found";
			$dataarray["Status"] = "0";
		}
	} else {
		$dataarray["data"] = "invalid request";
		$dataarray["Status"] = "3";
	}
} else {
	$dataarray["data"] = "invalid key";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);				
	//print_r($dataarray);
