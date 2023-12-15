<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__ . '/../Services/sendsms_API.php');

error_reporting(E_ALL);
//ini_set('display_errors', 0);
ini_set('display_errors', 1);
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
settimestamp('Auto_Exit_Mail', 'Start');
$myDB = new MysqliDb();
$chk_task = $myDB->query('select EmployeeID,location from whole_dump_emp_data where cast(dol as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (5,7,8,10,13,14,15,16,22) and disposition in ("IR","RES")');

//$chk_task=$myDB->query('select EmployeeID,location from whole_details_peremp where EmployeeID="CE12102224";');
//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();


$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_exit();');
		$Joinee_EmpID = $value['EmployeeID'];
		$Joinee_loc = $value['location'];

		$myDB = new MysqliDb();
		$chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');

		//$chk_task1=$myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID in ("CE10091236","CE101619856","CE011929747");');

		//$chk_task1=$myDB->query('select "CE12102224" as EmployeeID,"md.masood@cogenteservices.com" as emailid;');

		$my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}

		$myDB = new MysqliDb();
		$chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.des_id in (1,5,7,8,10,13,14,15,16,22,23) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');


		$my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}

		sendnotification($Joinee_EmpID);
	}
}


$myDB = new MysqliDb();
$chk_task = $myDB->query('select EmployeeID,location,client_name from whole_dump_emp_data where cast(dol as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (1,2,3,4,6,11) and disposition in ("IR","RES");');

//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();


$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_exit();');

		$Joinee_EmpID = $value['EmployeeID'];
		$Joinee_loc = $value['location'];
		$Joinee_client = $value['client_name'];

		$myDB = new MysqliDb();
		$chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');

		//$tablename='whole_details_peremp';
		$my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}


		$myDB = new MysqliDb();
		$chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.client_name ="' . $Joinee_client . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');

		//$tablename='whole_details_peremp';
		$my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}

		sendnotification($Joinee_EmpID);
	}
}

settimestamp('Auto_Exit_Mail', 'End');


function sendnotification($Joinee_EmpID)
{
	$myDB = new MysqliDb();
	$rst_contact = $myDB->rawQuery('select t2.EmployeeName, mobile, emailid,t4.gender,t4.img,DATE_FORMAT(t3.dol,"%d %b %Y")as `dol`,t2.account_head,t2.ReportTo,t2.designation,t5.location,t2.location as "locid" from contact_details t1 join whole_dump_emp_data t2 on t1.EmployeeID=t2.EmployeeID inner join (select EmployeeID,dol from exit_emp where EmployeeID="' . $Joinee_EmpID . '" order by dol desc limit 1) t3 on t1.EmployeeID=t3.EmployeeID inner join personal_details t4 on t1.EmployeeID=t4.EmployeeID inner join location_master t5 on t2.location=t5.id where t2.EmployeeID= "' . $Joinee_EmpID . '" limit 1');

	if (!empty($rst_contact[0]['EmployeeName'])) {
		$dir_loc = "";
		if ($rst_contact[0]['locid'] == "3") {
			$dir_loc = "Meerut/";
		} else if ($rst_contact[0]['locid'] == "4") {
			$dir_loc = "Bareilly/";
		} else if ($rst_contact[0]['locid'] == "5") {
			$dir_loc = "Vadodara/";
		} else if ($rst_contact[0]['locid'] == "6") {
			$dir_loc = "Manglore/";
		} else if ($rst_contact[0]['locid'] == "7") {
			$dir_loc = "Bangalore/";
		} else if ($rst_contact[0]['locid'] == "8") {
			$dir_loc = "Nashik/";
		} else if ($rst_contact[0]['locid'] == "9") {
			$dir_loc = "Anantapur/";
		} else if ($rst_contact[0]['locid'] == "10") {
			$dir_loc = "Gurgaon/";
		} else if ($rst_contact[0]['locid'] == "11") {
			$dir_loc = "Hyderabad/";
		}

		if (trim($rst_contact[0]['img']) != '') {
			//$imgsrc = '../Images/'. $rst_contact[0]['img'];
			$imgsrc = '../' . $dir_loc . 'Images/' . $rst_contact[0]['img'];
			//echo $imgsrc = 'https://ems.cogentlab.com/erpm/'.$dir_loc.'Images/'. $rst_contact[0]['img'].'<br/>';
			if (file_exists($imgsrc)) {
				//$imgsrc = 'https://ems.cogentlab.com/erpm/Images/'. $rst_contact[0]['img'];
				$imgsrc = 'https://ems.cogentlab.com/erpm/' . $dir_loc . 'Images/' . $rst_contact[0]['img'];
			} else {
				$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
			}
		} else {
			$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
		}

		//echo $imgsrc;		 			
		$reportto = '';
		$reporttoname = 'NA';
		if ($rst_contact[0]['account_head'] == $Joinee_EmpID) {
			$reportto = $rst_contact[0]['ReportTo'];
		} else {
			$reportto = $rst_contact[0]['account_head'];
		}

		$myDB = new MysqliDb();
		$rst_report = $myDB->rawQuery('select EmployeeName from personal_details where EmployeeID= "' . $reportto . '" ');
		if (!empty($rst_report[0]['EmployeeName'])) {
			$reporttoname = $rst_report[0]['EmployeeName'];
		}
		$gender = 'He';
		$gender1 = 'his';


		if ($rst_contact[0]['gender'] == 'Male') {
			$gender = 'He';
			$gender1 = 'his';
		} else {
			$gender = 'She';
			$gender1 = 'her';
		}

		$dol = $rst_contact[0]['dol'];
		$time = strtotime($dol);
		$day = date("j", $time);
		$month = date("M", $time);
		$year = date("Y", $time);
		if ($day == "1") {
			$dol = $day . "<sup>st</sup> " . $month . ' ' . $year;
		} else if ($day == "2") {
			$dol = $day . "<sup>nd</sup> " . $month . ' ' . $year;
		} else if ($day == "3") {
			$dol = $day . "<sup>rd</sup> " . $month . ' ' . $year;
		} else {
			$dol = $day . "<sup>th</sup> " . $month . ' ' . $year;
		}

		$body_ = '<html>
							<head>
	<style>
		.imgcss
		{
			display: block;
    width: 310px !important;
    max-width: 100% !important;
		}
	</style>
</head>
	<body>
		<table width="100%">
			<tr>
				<td style="width: 50%; padding-left:0 px; vertical-align: top; padding-top: 10px;"><img src="https://ems.cogentlab.com/erpm/Style/images/exit_header_img3.png"/></td>
				
				<td rowspan="2" style="width: 50%; "><img src="https://ems.cogentlab.com/erpm/Style/images/exit_header_img2.png"/></td>
				
			</tr>
			<tr>
				<td style="text-align: left; padding-left: 256px;font-family: Verdana; font-size: 35px; font-weight: bold;" >' . $rst_contact[0]['EmployeeName'] . '</td>
				
			</tr>
			
			<tr>
				<td width="70%" style="padding-left: 50px; padding-right: 50px;">
					<table style="width: 100%; text-align: left;font-family: Verdana; font-size: 17px;">
						<tr>
							<td>
								Dear Employees,
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								This is to notify that <b>' . $rst_contact[0]['EmployeeName'] . ' (' . $rst_contact[0]['designation'] . ')</b> is moving out of the company, effective <b>' . $dol . '</b>. ' . $gender . ' has decided to leave because of better opportunity. 
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								As of <b>' . $dol . '</b>, please direct all department related questions to <b>' . $reporttoname . '</b> until we are able to secure a replacement.
							</td>
						</tr>
						
						<tr>
							<td style="padding-top: 25px;">
								We wish <b>' . $rst_contact[0]['EmployeeName'] . '</b> good luck for ' . $gender1 . ' future.


							</td>
						</tr>	
						
					</table>
				</td>
				<td align="left" width="30%" style="padding-top: 25px;"><img src=' . $imgsrc . ' class="imgcss" width="250"/></td>
				
			</tr>
			
		</table>
	</body>
</html>';
		$sub = 'Employee Exit Announcement - ' . $rst_contact[0]['EmployeeName'] . ' (' . $rst_contact[0]['designation'] . ' - ' . $rst_contact[0]['location'] . ')';
		//echo $sub. '<br/>';
		//echo $body_; die; echo '<br/>';
		$myDB = new MysqliDb();
		$count = $mailcount = 0;
		$rst_contact = $myDB->rawQuery('select count(*) as count from exit_mail_temp where empid="' . $Joinee_EmpID . '"');
		echo $rst_contact[0]['count'];
		if (!empty($rst_contact[0]['count'])) {
			$count = $rst_contact[0]['count'];
		}


		if ($count > 300) {
			while ($mailcount < $count) {
				$myDB = new MysqliDb();
				echo 'select id,rec_empid, rec_email_id from exit_mail_temp where empid="' . $Joinee_EmpID . '" limit ' . $mailcount . ',300' . '<br/>';
				$chk_task1 = $myDB->query('select id,rec_empid, rec_email_id from exit_mail_temp where empid="' . $Joinee_EmpID . '" limit ' . $mailcount . ',300;');

				//$chk_task1=$myDB->query('select "CE12102224" as EmployeeID,"md.masood@cogenteservices.com" as emailid;');

				$my_error = $myDB->getLastError();

				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST;
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;
				$mail->Password = EMAIL_PASS;
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
				$mail->Body = $body_;
				$mail->isHTML(true);
				foreach ($chk_task1 as $key => $value) {

					$mail->addBcc($value['rec_email_id']);
					echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
					$mailcount++;
				}

				$mail->AddAddress('ems@cogenteservices.in');
				$mail->addBcc('sachin.siwach@cogenteservices.com');
				//$mail->addBcc('nitin.sahni@cogenteservices.com');

				$mail->Subject = $sub;

				if (!$mail->send()) {

					echo $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
				} else {

					echo  $emailStatus =  'Mail Send successfully.';
				}
			}
		} else {
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST;
			$mail->SMTPAuth = EMAIL_AUTH;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASS;
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT;
			$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
			$mail->Body = $body_;
			$mail->isHTML(true);
			$myDB = new MysqliDb();
			$chk_task1 = $myDB->query('select id,rec_empid, rec_email_id from exit_mail_temp where empid="' . $Joinee_EmpID . '";');


			$my_error = $myDB->getLastError();

			foreach ($chk_task1 as $key => $value) {

				$mail->addBcc($value['rec_email_id']);
				echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
			}
			$mail->AddAddress('ems@cogenteservices.in');
			$mail->addBcc('sachin.siwach@cogenteservices.com');
			//$mail->addBcc('nitin.sahni@cogenteservices.com');

			$mail->Subject = $sub;

			if (!$mail->send()) {

				echo $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
			} else {

				echo  $emailStatus =  'Mail Send successfully.';
			}
		}
	}

	/*$myDB=new MysqliDb();
							echo 'insert into exit_mail set employeeid="'.$Sender_EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"';
							$sms_status = $myDB->rawQuery('insert into exit_mail set employeeid="'.$Sender_EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"');  */
}
