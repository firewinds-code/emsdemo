<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '0');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$myDB = new MysqliDb();
$select_emp = "select distinct EmployeeID from salary_certificate_report where flag=0";
$result = $myDB->query($select_emp);
$mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;

if (empty($mysql_error)) {
	foreach ($result as $key => $value) {
		$emp = $value['EmployeeID'];

		$Getinfo = "select t1.EmployeeID,case when upper(t5.Gender)='FEMALE' then 'Ms.' else 'Mr.' end as Gender,t5.EmployeeName from employee_map t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join  personal_details t5 on t1.EmployeeID=t5.EmployeeID where t1.EmployeeID='" . $emp . "'";
		$myDB = new MysqliDb();
		$results = $myDB->query($Getinfo);

		$gender = $results[0]['Gender'];
		$EmployeeName = $results[0]['EmployeeName'];

		$filename = $emp . "_salary_certificate.pdf";
		$target_dir = ROOT_PATH . "salarypdf/";
		$path = $target_dir . $filename;

		$send_mail = $myDB->rawQuery(" select * from salary_certificate_report where EmployeeID='" . $emp . "' order by id desc limit 1");
		$emailid = $send_mail[0]['per_email_id'];
		if ($emailid != "") {
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST;
			$mail->SMTPAuth = EMAIL_AUTH;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASS;
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT;
			$mail->setFrom(EMAIL_FROM,  'Cogent | Salary Certificate');
			$mail->AddAddress($emailid);
			$mail->Subject = "Salary Certificate";
			$mail->isHTML(true);
			$msg2 = "Dear " .  $gender . " " . $EmployeeName . ',<br/><br/>
					Please find  attached your Salary Certificate.</br><br/>
					Yours truly <br/> HR Team <br/> Cogent E Services Limited.';
			$mail->Body = $msg2;
			$mymsg = '';
			$response = '';
			$mail->AddAttachment($path);
			if (!$mail->send()) {
				$response =  'Mailer Error:' . $mail->ErrorInfo;
				$updateres = 'update salary_certificate_report set response="' . $response . '", flag=0 where EmployeeID="' . $emp . '" ';
				$myDB = new MysqliDb();
				$resultBy = $myDB->query($updateres);
			} else {
				$response =  'Mail Send successfully';
				$updateres = 'update salary_certificate_report set response="' . $response . '", flag=1 where EmployeeID="' . $emp . '" ';
				$myDB = new MysqliDb();
				$resultBy = $myDB->query($updateres);
			}
		}
	}
}
