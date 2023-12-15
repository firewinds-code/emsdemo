<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$myDB = new MysqliDb();
$data_exp = $myDB->rawQuery('call get_exceed_issues_data()');
echo $myDB->getLastError();

$alert_msg = '';
if (count($data_exp) > 0) {
	foreach ($data_exp as $exp_key => $exp_val) {


		/*$myDB=new MysqliDb();
		$date1=$exp_val['updateby_handler'];
		$day1='D'.Date('j',strtotime($exp_val['updateby_handler']));
	 	$day2='D'.Date('j',strtotime($date1. ' - 1 day'));
	 $getAtndQuery=" select $day1,$day2 as `val` ,EmployeeID ,  CONCAT('01-', `Month`, '-', `Year`) AS `DateOn` from calc_atnd_master where EmployeeID='CE121622565'  and ( $day1 in ('P','H','P(Short Login)(1)','P(Attendance Change)','H(Attendance Change)','HWP','P(Short Leave)(1)','H(Biometric issue)','P(Biometric issue)','P(Short Login)(2)','P(Short Login)(3)','P(Short Login)(4)','P(Short Login)(5)','HWP(Biometric issue)','HWP(Attendance Change)' ) ||  $day2 in ('P','H','P(Short Login)(1)','P(Attendance Change)','H(Attendance Change)','HWP','P(Short Leave)(1)','H(Biometric issue)','P(Biometric issue)','P(Short Login)(2)','P(Short Login)(3)','P(Short Login)(4)','P(Short Login)(5)','HWP(Biometric issue)','HWP(Attendance Change)' ) ) limit 10 ";
		$PDay_dt= $myDB->rawQuery($getAtndQuery);
		if(isset($PDay_dt[0]['EmployeeID']) && $PDay_dt[0]['EmployeeID']!="")
		{
			$myDB=new MysqliDb();
			$update = "update issue_tracker set  status ='close' where id = ".$exp_val['id'];		
			$result = $myDB->rawQuery($update);
				
		}*/

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$expval = $exp_val['id'];
		$update = "update issue_tracker set  status ='close' where id = ? ";
		$up = $conn->prepare($sql);
		$up->bind_param("i", $expval);
		$result = $up->get_result();
		// $result = $myDB->rawQuery($update);
	}
}
#call get_exceed_exp_data('CE07147134');

#echo $alert_msg;
echo '<br /> Run for ' . count($data_exp) . ' Employee';
