<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__ . '/../Services/sendsms_API.php');

// error_reporting(E_ALL);
ini_set('display_errors', 0);

// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';


$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';

$myDB = new MysqliDb();
$chk_task = $myDB->query('select "CMK07194077" as EmployeeID');

//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();
$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$rstcontact = 'select t2.EmployeeName, mobile, emailid,t4.gender,t4.img,DATE_FORMAT(t3.dol,"%d %b %Y")as `dol`,t2.account_head,t2.ReportTo,t2.location from contact_details t1 join whole_dump_emp_data t2 on t1.EmployeeID=t2.EmployeeID inner join exit_emp t3 on t1.EmployeeID=t3.EmployeeID inner join personal_details t4 on t1.EmployeeID=t4.EmployeeID where t2.EmployeeID= ? limit 1';
		$selectQ = $conn->prepare($rstcontact);
		$selectQ->bind_param("s", $value['EmployeeID']);
		$selectQ->execute();
		$result = $selectQ->get_result();
		$rst_contact = $result->fetch_row();
		if (!empty(clean($rst_contact[0]))) {
			$dir_loc = "";
			if (clean($rst_contact[8]) == "3") {
				$dir_loc = "Meerut/";
			} else if (clean($rst_contact[8]) == "4") {
				$dir_loc = "Bareilly/";
			} else if (clean($rst_contact[8]) == "5") {
				$dir_loc = "Vadodara/";
			} else if (clean($rst_contact[8]) == "6") {
				$dir_loc = "Manglore/";
			} else if (clean($rst_contact[8]) == "7") {
				$dir_loc = "Bangalore/";
			}
			if (trim(clean($rst_contact[4])) != '') {
				//$imgsrc = '../Images/'. clean($rst_contact[0])['img'];
				$imgsrc = '../' . $dir_loc . 'Images/' . clean($rst_contact[4]);
				//echo $imgsrc = 'https://demo.cogentlab.com/erpm/'.$dir_loc.'Images/'. clean($rst_contact[0])['img'].'<br/>';
				if (file_exists($imgsrc)) {
					//$imgsrc = 'https://demo.cogentlab.com/erpm/Images/'. clean($rst_contact[0])['img'];
					$imgsrc = 'https://demo.cogentlab.com/erpm/' . $dir_loc . 'Images/' . clean($rst_contact[4]);
				} else {
					$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
				}
			} else {
				$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
			}

			//echo $imgsrc;
			$reportto = '';
			$reporttoname = 'NA';
			if (clean($rst_contact[6]) == $value['EmployeeID']) {
				$reportto = clean($rst_contact[7]);
			} else {
				$reportto = clean($rst_contact[6]);
			}

			$myDB = new MysqliDb();
			$conn = $myDB->dbConnect();
			$rstreport = 'select EmployeeName from personal_details where EmployeeID= ? ';
			$selQr = $conn->prepare($rstreport);
			$selQr->bind_param("s", $reportto);
			$selQr->execute();
			$results = $selQr->get_result();
			$rst_report = $results->fetch_row();
			if (!empty(clean($rst_report[0]))) {
				$reporttoname = clean($rst_report[0]);
			}
			$gender = 'He';
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST;
			$mail->SMTPAuth = EMAIL_AUTH;
			$mail->Username = EMAIL_USER;
			$mail->Password = EMAIL_PASS;
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT;
			$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
			//$mail->AddAddress(clean($rst_contact[0])['emailid']);
			$mail->AddAddress('md.masood@cogenteservices.com');
			$emailAddress = clean($rst_contact[2]);
			$mail->Subject = 'Employee Exit Announcement';
			$mail->isHTML(true);
			if (clean($rst_contact[3]) == 'Male') {
				$gender = 'He';
			} else {
				$gender = 'She';
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
				<td style="width: 50%; padding-left:0 px; vertical-align: top; padding-top: 10px;"><img src="https://demo.cogentlab.com/erpm/Style/images/exit_header_img1.png"/></td>
				
				<td rowspan="2" style="width: 50%; "><img src="https://demo.cogentlab.com/erpm/Style/images/exit_header_img2.png"/></td>
				
			</tr>
			<tr>
				<td style="text-align: left; padding-left: 256px;font-family: Verdana; font-size: 35px; font-weight: bold;" >' . clean($rst_contact[0]) . '</td>
				
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
								This is to notify that <b>' . clean($rst_contact[0]) . '</b> is moving out of the company, effective <b>' . clean($rst_contact[5]) . '</b>. ' . $gender . ' has decided to leave because of better opportunity. 
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								As of <b>' . clean($rst_contact[5]) . '</b>, please direct all department related questions to <b>' . $reporttoname . '</b> until we are able to secure a replacement.
							</td>
						</tr>
						
						<tr>
							<td style="padding-top: 25px;">
								We wish <b>' . clean($rst_contact[0]) . '</b> good luck for his/ her future.


							</td>
						</tr>	
						
					</table>
				</td>
				<td align="left" width="30%" style="padding-top: 25px;"><img src=' . $imgsrc . ' class="imgcss" width="250"/></td>
				
			</tr>
			
		</table>
	</body>
</html>';
			echo $body_;
			$mail->Body = $body_;
			/*if(!$mail->send())
						 	{
						 		
						 		echo $emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
						  	} 
							else
							{
								
							   echo  $emailStatus =  'Mail Send successfully.';
							}*/
		}

		/*$myDB=new MysqliDb();
							echo 'insert into exit_mail set employeeid="'.$EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"';
							$sms_status = $myDB->rawQuery('insert into exit_mail set employeeid="'.$EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"');*/
	}
}
