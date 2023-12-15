<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__ . '/../Services/sendsms_API1.php');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';


$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$sq1 = "insert into scheduler(modulename,type)values('" . $module . "','" . $type . "');";
	$myDB->query($sq1);
}
settimestamp('Auto_NCNS', 'Start');

$dateA = date_create(date('Y-m-d'));
$dateB = date_create(date('Y-m-d'));
$dateC = date_create(date('Y-m-d'));

$date1 = date_sub($dateA, date_interval_create_from_date_string('1 days'));
$date2 = date_sub($dateB, date_interval_create_from_date_string('2 days'));
$date3 = date_sub($dateC, date_interval_create_from_date_string('3 days'));
$d1 = 'D' . $date1->format('j');
$d2 = 'D' . $date2->format('j');
$d3 = 'D' . $date3->format('j');
$m	= $date1->format('n');
$y  = $date1->format('Y');
$sqlstr = "select w.EmployeeID from ActiveEmpID w where employeeid in ('CE12102224')";

$myDB = new MysqliDb();

//$chk_task=$myDB->query('select w.EmployeeID from ActiveEmpID w left join excp_emp_ncns_msg ex on w.EmployeeID = ex.EmpID where ex. EmpID is null');
$chk_task = $myDB->query($sqlstr);
/*var_dump($chk_task);
		echo '<pre>',print_r($chk_task,1),'</pre>';
		die;*/

$my_error = $myDB->getLastError();
$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$ncns_count = 3;

		/*
						for($i=1;$i<=3;$i++)
						{
							$date = date_create(date('Y-m-d'));
						 	$date1 = date_sub($date, date_interval_create_from_date_string($i.' days'));
						 	 $strsql='select D'.$date1->format('j').' as atnd from calc_atnd_master where EmployeeID="'.$value['EmployeeID'].'" and month="'.$date1->format('n').'" and year="'.$date1->format('Y').'"';
						 	$myDB=new MysqliDb();
							$rst_atnd = $myDB->rawQuery($strsql);
							if(!empty($rst_atnd[0]['atnd']))
							{
								if($rst_atnd[0]['atnd']=='A')
								{
									$ncns_count++;
								}
							}
						
						
					 	}
						*/


		if ($ncns_count == 3) {
			$msg = '';
			$myDB = new MysqliDb();
			$rst_contact = $myDB->rawQuery('select EmployeeName, mobile, emailid from contact_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t2.EmployeeID= "' . $value['EmployeeID'] . '" limit 1');
			if (!empty($rst_contact[0]['mobile'])) {
				$msg = "Dear " . $rst_contact[0]['EmployeeName'] . ", You are not reporting to office since (Date : " . date('d/m/Y', strtotime("-3 days")) . ") without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by " . date('d/m/Y', strtotime("+1 days")) . " - Cogent E Services";
				$TEMPLATEID = '1707161526695912794';
				$url = SMS_URL;
				$token = SMS_TOKEN;
				$credit = SMS_CREDIT;
				$sender = SMS_SENDER;
				$message = $msg;
				$number = $rst_contact[0]['mobile'];
				$sendsms = new sendsms($url, $token);
				$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
				$response = $message_id;
				$ResultSMS = $response;

				$lbl_msg = ' SMS : ' . $response;
			}

			if (!empty($rst_contact[0]['emailid'])) {
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST;
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;
				$mail->Password = EMAIL_PASS;
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
				$mail->AddAddress($rst_contact[0]['emailid']);
				$emailAddress = $rst_contact[0]['emailid'];
				$mail->Subject = 'EMS profile deactivation - NCNS warning';
				$mail->isHTML(true);

				$body_ = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear ' . $rst_contact[0]['EmployeeName'] . ',</b></span><br /><br/> You are not reporting to office since (Date : ' . date('d/m/Y', strtotime("-3 days")) . ')) without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by ' . date('d/m/Y', strtotime("+1 days")) . '<div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />' . 'Regards,<br /><br/> EMS Mailer Services.<div>';


				//$body_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span> '.$msg.'.</b></span><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';

				$mail->Body = $body_;
				if (!$mail->send()) {
					settimestamp('Auto_NCNS', 'Email Not Sent ' . $emailAddress);
					$emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
				} else {
					//settimestamp('Auto_NCNS','Email Sent');
					$emailStatus =  'Mail Send successfully.';
				}
			}

			$myDB = new MysqliDb();
			//echo 'insert into login_ncns_smsmail set employeeid="'.$value['EmployeeID'].'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server" ';
			$sms_status = $myDB->rawQuery('insert into login_ncns_smsmail set employeeid="' . $value['EmployeeID'] . '", smsstatus="' . addslashes($response) . '",sms_text="' . addslashes($msg) . '",EmailAddress="' . addslashes($emailAddress) . '",emailStatus="' . addslashes($emailStatus) . '" ,createdBy= "Server",type= "NCNS",mobile="' . $rst_contact[0]['mobile'] . '" ');
		}
	}
}

settimestamp('Auto_NCNS', 'End');
