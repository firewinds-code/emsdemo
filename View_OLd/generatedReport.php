<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require_once(LIB . 'PHPExcel.php');
// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
set_time_limit(0);
$sql = "select * from whole_details_peremp limit 2";
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$rowCount = 1;
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$col = 0;
foreach ($result as $k => $v) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $rowCount, $k);
	$col++;
}
$rowCount++;

foreach ($result as $key => $row) {

	$col = 0;
	foreach ($row as $k => $v) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $rowCount, $v);
		$col++;
	}
	$rowCount++;
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$filename = date('Y-m-d h-i-s A', time()) . '_Whole Employee Dump.xlsx';
$objWriter->save('../Reports/' . $filename);
if (file_exists('../Reports/' . $filename)) {
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$pagename = 'generatedReport';
	$select_email = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";
	$selectQ = $conn->prepare($select_email);
	$selectQ->bind_param("s", $pagename);
	$selectQ->execute();
	$select_email_array = $selectQ->get_result();

	$mail = new PHPMailer;
	$mail->isSMTP();
	// $mail->Host = EMAIL_HOST; 
	// $mail->SMTPAuth = EMAIL_AUTH;
	// $mail->Username = EMAIL_USER;   
	// $mail->Password = EMAIL_PASS;                        
	// $mail->SMTPSecure = EMAIL_SMTPSecure;
	// $mail->Port = EMAIL_PORT; 
	// $mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
	// $mysql_error=$myDB->getLastError();
	if ($select_email_array) {
		//while($email_array=mysql_fetch_array($select_email_array))
		foreach ($select_email_array as $key => $email_array) {
			$email_address = $email_array['email_address'];
			if ($email_address != "") {
				$mail->AddAddress($email_address);
			}
			$cc_email = $email_array['ccemail'];
			if ($cc_email != "") {
				$mail->addCC($cc_email);
			}
		}
	}
	$mail->Subject = 'EMS ,Employee EOD Report' . EMS_CenterName;
	$mail->AddAttachment('../Reports/' . $filename, "table.xlsx");
	$mail->isHTML(true);

	$pwd_ = '<span>Dear Sir/Ma\'am,<br/><br/><span><b>PFA.</b></span><br /><br/><div style="float:left;width:100%;"><br /><br /><br />' . 'Regards,<br /><br/> EMS Noida.<div>';

	$mail->Body = $pwd_;
	echo $pwd_;
	if (!$mail->send()) {
		$mymsg .= '.Mailer Error:' . $mail->ErrorInfo;
	} else {

		$mymsg .= '.Mail Send successfully.';
	}
}
echo 'Complete !';
