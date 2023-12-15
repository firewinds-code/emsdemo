<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;

$myDB = new MysqliDb();
//$Query="select * from activeempid;";

//$Query="select 'CE12102224' as EmployeeID,'Mohd Masood' as EmployeeName,'9990015749' as mobile from activeempid  t1 join  contact_details t2 on t1.EmployeeID = t2.EmployeeID join personal_details t3 on t1.EmployeeID = t3.EmployeeID where length(mobile)=10 limit 1;";
$Query = "call sendsms_record()";
$res = $myDB->query($Query);
if ($res) {
	foreach ($res as $key => $value) {
		$result[] = $value;
	}
	$result = json_encode($result);
	echo $result;
} else {
	echo 'EmployeeID NOT EXIST';
}
