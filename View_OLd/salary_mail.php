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
$connn = $myDB->dbConnect();

$flag = 0;
$select_emp = "select distinct EmployeeID from salary_certificate_report where flag=?";
$selectQury = $connn->prepare($select_emp);
$selectQury->bind_param("i", $flag);
$selectQury->execute();
$result = $selectQury->get_result();

if ($result->num_rows > 0) {
	foreach ($result as $key => $value) {
		$emp = clean($value['EmployeeID']);

		$Getinfo = "select t1.EmployeeID,case when upper(t5.Gender)='FEMALE' then 'Ms.' else 'Mr.' end as Gender,t5.EmployeeName from employee_map t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join  personal_details t5 on t1.EmployeeID=t5.EmployeeID where t1.EmployeeID=?";
		$selectQ = $connn->prepare($Getinfo);
		$selectQ->bind_param("s", $emp);
		$selectQ->execute();
		$results = $selectQ->get_result();
		$resu = $results->fetch_row();
		$gender = $resu[1];
		$EmployeeName = $resu[2];
		$filename = $emp . "_salary_certificate.pdf";
		$target_dir = ROOT_PATH . "salarypdf/";
		$path = $target_dir . $filename;

		$query = "select * from salary_certificate_report where EmployeeID=? order by id desc limit 1";
		$select = $connn->prepare($query);
		$select->bind_param("s", $emp);
		$select->execute();
		$res = $select->get_result();
		$send_mail = $res->fetch_row();
		$emailid = $send_mail[4];
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
					Thanks <br/> Cogent ';
			$mail->Body = $msg2;
			$mymsg = '';
			$response = '';
			$mail->AddAttachment($path);
			if (!$mail->send()) {
				$response =  'Mailer Error:' . $mail->ErrorInfo;
				$flags = 0;
				$updateres = 'update salary_certificate_report set response=?, flag=? where EmployeeID=? ';
				$update = $connn->prepare($updateres);
				$update->bind_param("sis", $response, $flags, $emp);
				$update->execute();
				$results = $update->get_result();
			} else {
				$Flags = 1;
				$response =  'Mail Send successfully';
				$updateres = 'update salary_certificate_report set response=?, flag=? where EmployeeID=?';
				$update = $connn->prepare($updateres);
				$update->bind_param("sis", $response, $Flags, $emp);
				$update->execute();
				$results = $update->get_result();
			}
		}
	}
}
