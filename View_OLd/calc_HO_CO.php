<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$weekOFF_date = '';
$location = '';
$myDB = new MysqliDb();
#$date_hoList = $myDB->query("select DateOn, Reason, Associates, Support from ho_list_admin where datediff(curdate() , DateOn) = 5 limit 1;");
$date_hoList = $myDB->query("select DateOn, Reason, Associates,location, Support from ho_list_admin where datediff(curdate(), DateOn) <= 12  and datediff(curdate(), DateOn) >= 1 ;");
if (count($date_hoList) > 0 && $date_hoList) {
	foreach ($date_hoList as $value) {
		$weekOFF_date = $value['DateOn'];
		$location = $value['location'];
		if (strtotime($weekOFF_date) && $weekOFF_date < date('Y-m-d')) {
			$myDB = new MysqliDb();
			$rs_emp = $myDB->query("call get_HO_CO_calc('" . $weekOFF_date . "','" . $location . "')");
			//$date = '2016-09-01';//date('Y-m-d',time());
			$monday_counter = array();
			$rosterType = array();
			if (count($rs_emp) > 0) {
				foreach ($rs_emp as $rs_key => $rs_val) {
					$EmpID = $rs_val['EmployeeID'];
					$myDB = new MysqliDb();
					$COdate = date('Y-m-d', strtotime($weekOFF_date . " +1 days"));
					$myDB->query('call insert_CO("' . $EmpID . '","' . $COdate . '",4)');
				}
			}


			echo 'complete';
		} else {
			echo "Invalid Date";
		}
	}
} else {
	echo "No Data";
}
