<?php
require_once(__dir__ . '/../Config/init.php');
date_default_timezone_set('Asia/Kolkata');
// require_once(CLS.'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$cmid = clean($_REQUEST['cm_id']);
if (isset($cmid)) {

	$sql = "select count(*) from employee_map inner join (select *,max(createdon) from nww_csa_ranking where Type = 'MIS' and  month(DateFor) = month(curdate()) and year(DateFor) = year(curdate()) group by EmployeeID) as t1 on t1.EmployeeID = employee_map.EmployeeID where emp_status ='Active' and cm_id = ? and df_id in (74,77)";
	$sel = $conn->prepare($sql);
	$sel->bind_param("i", $cmid);
	$sel->execute();
	$result = $sel->get_result();
	$res = $result->fetch_row();
	// $result = $myDB->query($sql);
	if ($result->num_rows > 0 && $result) {
		echo 'done|' . is_numeric($res[0]);
	} else {
		//echo 'no ' . $drt;
		echo 'no ';
	}
}
