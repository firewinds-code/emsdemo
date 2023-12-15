<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
include_once("../Services/sendsms_API1.php");
ini_set('display_errors', '0');
$msg = NULL;
$EmployeeID = $EmployeeName = $contactNum = '';
$getBdayList = "SELECT  distinct personal_details.EmployeeID ,DATE_FORMAT(personal_details.DOB, '%m-%d') as DOB,personal_details.FirstName ,c.mobile FROM personal_details  left join contact_details c  on personal_details.EmployeeID=c.EmployeeID left join employee_map actemp on  personal_details.EmployeeID=actemp.EmployeeID  WHERE  DATE_FORMAT(personal_details.DOB, '%m-%d') =DATE_FORMAT(curdate(),'%m-%d') and actemp.emp_status='Active'";
$myDB = new MysqliDb();
$resultsE = $myDB->query($getBdayList);
if (count($resultsE) > 0) {
	foreach ($resultsE as $val) {
		$EmployeeID = $val['EmployeeID'];
		$employeeName = $val['FirstName'];
		$employeeMobile = $val['mobile'];
		$contactNum = $employeeMobile;
		if (!empty($contactNum)) {
			$TEMPLATEID = '1707161725895450407';
			//$msg="Dear ".$employeeName.",Wishing you a day full of laughter and happiness and a year that brings you much success. Happy Birthday!! Team Cogent";
			$msg = "Dear " . $employeeName . ",Wishing you a day full of laughter and happiness and a year that brings you much success. Happy Birthday!! Team Cogent";
			$url = SMS_URL;
			$token = SMS_TOKEN;
			$credit = SMS_CREDIT;
			$sender = SMS_SENDER;
			$message = $msg;
			$number = $contactNum;
			$sendsms = new sendsms($url, $token);
			$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
		}

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$insertBDMsg = "insert into bd_msg (EmployeeID, msg_text, contact_number) values (?,?,?);";
		$insert = $conn->prepare($insertBDMsg);
		$insert->bind_param("ssi", $EmployeeID, $message, $number);
		$insert->execute();
		$resu = $insert->get_result();
		// $myDB = new MysqliDb();
		// $resu = $myDB->rawQuery($insertBDMsg);
		// $error = $myDB->getLastError();
		// if (empty($error)) {
		if ($insert->affected_rows === 1) {
			echo "<script>$(function(){ toastr.success('Successfully Inserted...') });</script>";
		}
	}
}
