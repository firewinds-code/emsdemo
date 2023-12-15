<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$myDB = new MysqliDb();
$data_exp = $myDB->query('
select t2.EmployeeID, t2.EmployeeName, location_master.location,t1.dol, t1.rsnofleaving,t1.disposition, t2.designation, t2.Process, t2.sub_process, t2.ReportTo, t2.account_head from exit_emp t1 inner join whole_dump_emp_data t2 on t2.EmployeeID = t1.EmployeeID    inner join location_master on location_master.id=t2.location where   cast(t1.createdon as date) = curdate() and emp_status = "Inactive" ');
$table = '';
if (count($data_exp) > 0 && $data_exp) {
	$count = 0;
	$table = '<table border="1" colspacing=0><htead><tr><th>Sr No.</th><th>EmployeeID</th><th>EmployeeName</th><th>Location</th><th>Date Of Leaving</th><th>Reason Of Leaving</th><th>Disposition</th><th>Designation</th><th>Process</th><th>Sub Process</th><th>Report To</th><th>Account Head</th></tr></thead><tbody>';
	foreach ($data_exp as $key => $val) {
		$count++;
		$table .= '<tr>';
		$table .= '<td>' . $count . '</td>';
		$table .= '<td>' . $val['EmployeeID'] . '</td>';
		$table .= '<td>' . $val['EmployeeName'] . '</td>';
		$table .= '<td>' . $val['location'] . '</td>';
		$table .= '<td>' . $val['dol'] . '</td>';
		$table .= '<td>' . $val['rsnofleaving'] . '</td>';
		$table .= '<td>' . $val['disposition'] . '</td>';
		$table .= '<td>' . $val['designation'] . '</td>';
		$table .= '<td>' . $val['Process'] . '</td>';
		$table .= '<td>' . $val['sub_process'] . '</td>';
		$table .= '<td>' . $val['ReportTo'] . '</td>';
		$table .= '<td>' . $val['account_head'] . '</td>';


		$table .= '</tr>';
	}


	$table .= '</tbody></table>';
	$myDB = new MysqliDb();
	$pagename = 'auto_Inactive_mail';
	$sql = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("s", $pagename);
	$selectQ->execute();
	$select_email_array = $selectQ->get_result();

	// $mysql_error = $myDB->getLastError();
	// $rowCount = $myDB->count;
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = EMAIL_HOST;
	$mail->SMTPAuth = EMAIL_AUTH;
	$mail->Username = EMAIL_USER;
	$mail->Password = EMAIL_PASS;
	$mail->SMTPSecure = EMAIL_SMTPSecure;
	$mail->Port = EMAIL_PORT;
	$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
	if ($select_email_array->num_rows > 0) {
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
	$mail->Subject = 'Inactive Employee EOD Report [' . date('d M,Y', time()) . ']';
	$mail->isHTML(true);
	$pwd_ = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear ALL,<br/><br/><span><b>Please find below the List Inactive Employee from EMS Today.</b></span><br /><br/><div style="float:left;width:100%;">' . $table . '</div><div style="float:left;width:100%;"><br /><br /><br />' . 'Regards,<br /><br/> EMS Noida.<div>';

	$mail->Body = $pwd_;
	//echo $pwd_;
	$mymsg = "";
	if (!$mail->send()) {
		$mymsg .= '.Mailer Error:' . $mail->ErrorInfo;
	} else {
		$mymsg .= '.Mail Send successfully.';
	}
}
