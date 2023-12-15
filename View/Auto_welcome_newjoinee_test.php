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
settimestamp('Auto_welcome_newjoinee', 'Start');

$myDB = new MysqliDb();

$chk_task = $myDB->query('select empid,name,t3.ofc_emailid as email,t3.mobile as contact_no,doj,designation,location,immediate_manager,assignment,gender,linkdinLink,des_id,loc_id,img from mail_template t1 join employee_map t2 on t1.empid=t2.EmployeeID join contact_details t3 on t1.empid=t3.EmployeeID where emp_status="Active" and t1.empid="CEB032315582" and des_id in (5,7,8,10,13,14,15,16,22)');

//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();

$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_welcome();');

		$Joinee_EmpID = $value['empid'];
		$Joinee_loc = $value['loc_id'];
		$Joinee_EmpName = $value['name'];
		$Joinee_EMail = $value['email'];
		$Joinee_Mobile = $value['contact_no'];
		$Joinee_doj = $value['doj'];
		$Joinee_designation = $value['designation'];
		$Joinee_location = $value['location'];
		$Joinee_immediate_manager = $value['immediate_manager'];
		$Joinee_assignment = $value['assignment'];
		$Joinee_gender = $value['gender'];
		$Joinee_linkdin = $value['linkdinLink'];
		$Joinee_img = $value['img'];

		// $myDB = new MysqliDb();
		// $chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');

		// $my_error = $myDB->getLastError();

		// foreach ($chk_task1 as $key => $value) {
		// 	$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		// }

		// $myDB = new MysqliDb();
		// $chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.location !="" and t1.des_id in (1,5,7,8,10,13,14,15,16,22,23,29) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');


		// $my_error = $myDB->getLastError();

		// foreach ($chk_task2 as $key => $value) {
		// 	$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		// }
		// $rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_email_id="Abhinav@cogenteservices.com",empid="' . $Joinee_EmpID . '"');
		// $rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_email_id="Gaurav@cogenteservices.com",empid="' . $Joinee_EmpID . '"');
		// $rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_email_id="Pranjal@cogenteservices.com",empid="' . $Joinee_EmpID . '"');
		// $rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_email_id="Jaspreet@cogenteservices.com",empid="' . $Joinee_EmpID . '"');

		//sendnotification($Joinee_EmpID);
		sendnotification($Joinee_EmpID, $Joinee_EmpName, $Joinee_EMail, $Joinee_Mobile, $Joinee_doj, $Joinee_designation, $Joinee_location, $Joinee_immediate_manager, $Joinee_assignment, $Joinee_gender, $Joinee_linkdin, $Joinee_loc, $Joinee_img);
	}
}


$myDB = new MysqliDb();
$chk_task = $myDB->query('select empid,name,t3.ofc_emailid as email,t3.mobile as contact_no,doj,designation,location,immediate_manager,assignment,gender,linkdinLink,des_id,loc_id,client_name,img from mail_template t1 join employee_map t2 on t1.empid=t2.EmployeeID join contact_details t3 on t1.empid=t3.EmployeeID where t2.emp_status="Active" and t1.flag=1 and des_id in (1,2,3,4,6,11);');



$tablename = 'whole_details_peremp';
$my_error = $myDB->getLastError();
$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_welcome();');

		$Joinee_EmpID = $value['empid'];
		$Joinee_loc = $value['loc_id'];
		$Joinee_client = $value['client_name'];
		$Joinee_EmpName = $value['name'];
		$Joinee_EMail = $value['email'];
		$Joinee_Mobile = $value['contact_no'];
		$Joinee_doj = $value['doj'];
		$Joinee_designation = $value['designation'];
		$Joinee_location = $value['location'];
		$Joinee_immediate_manager = $value['immediate_manager'];
		$Joinee_assignment = $value['assignment'];
		$Joinee_gender = $value['gender'];
		$Joinee_linkdin = $value['linkdinLink'];
		$Joinee_img = $value['img'];

		// $myDB = new MysqliDb();
		// $chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');


		// $my_error = $myDB->getLastError();

		// foreach ($chk_task1 as $key => $value) {
		// 	$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		// }


		// $myDB = new MysqliDb();
		// $chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.client_name ="' . $Joinee_client . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%" and t1.EmployeeID !="CE07147134";');


		// $my_error = $myDB->getLastError();

		// foreach ($chk_task2 as $key => $value) {
		// 	$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		// }

		sendnotification($Joinee_EmpID, $Joinee_EmpName, $Joinee_EMail, $Joinee_Mobile, $Joinee_doj, $Joinee_designation, $Joinee_location, $Joinee_immediate_manager, $Joinee_assignment, $Joinee_gender, $Joinee_linkdin, $Joinee_loc, $Joinee_img);
	}
}

settimestamp('Auto_welcome_newjoinee', 'End');

function sendnotification($Joinee_EmpID, $Joinee_EmpName, $Joinee_EMail, $Joinee_Mobile, $Joinee_doj, $Joinee_designation, $Joinee_location, $Joinee_immediate_manager, $Joinee_assignment, $Joinee_gender, $Joinee_linkdin, $Joinee_loc, $Joinee_img)
{
	$myDB = new MysqliDb();
	$rst_contact = $myDB->rawQuery('select t1.EmployeeName,t1.FirstName, t1.designation,DOJ,Process,t1.Gender,t1.img,ReportTo,t3.EmployeeName as "ReportToName",mobile,emailid,t4.location,t1.location as "locid" from whole_details_peremp t1 join contact_details t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t3.EmployeeID=t1.ReportTo inner join location_master t4 on t1.location=t4.id where t1.EmployeeID= "' . $Joinee_EmpID . '"');
	//$Joinee_EmpID = $value['EmployeeID'];			 		
	if (!empty($rst_contact[0]['EmployeeName'])) {
		$proimgsrc = 'https://ems.cogentlab.com/erpm/Style/images/mail_pic.png';
		//$proimgsrc = 'http://localhost:8080/emsmigration/branches/Style/images/mail_pic.png';

		$mailimgsrc = 'https://ems.cogentlab.com/erpm/Style/images/cogent-logobkp_mail.png';
		//$mailimgsrc = 'http://localhost:8080/emsmigration/branches/Style/images/cogent-logobkp_mail.png';

		$dir_loc = "";
		if ($Joinee_loc == "3") {
			$dir_loc = "Meerut/";
		} else if ($Joinee_loc == "4") {
			$dir_loc = "Bareilly/";
		} else if ($Joinee_loc == "5") {
			$dir_loc = "Vadodara/";
		} else if ($Joinee_loc == "6") {
			$dir_loc = "Manglore/";
		} else if ($Joinee_loc == "7") {
			$dir_loc = "Bangalore/";
		} else if ($Joinee_loc == "8") {
			$dir_loc = "Nashik/";
		} else if ($Joinee_loc == "9") {
			$dir_loc = "Anantapur/";
		}

		if (trim($Joinee_img) != '') {
			//$imgsrc = '../Images/'. $rst_contact[0]['img'];
			$imgsrc = '../' . $dir_loc . 'Images/' . $Joinee_img;
			//echo $imgsrc = 'https://ems.cogentlab.com/erpm/'.$dir_loc.'Images/'. $rst_contact[0]['img'].'<br/>';
			if (file_exists($imgsrc)) {
				$imgsrc = 'https://ems.cogentlab.com/erpm/' . $dir_loc . 'Images/' . $Joinee_img;
				//$imgsrc = 'http://localhost:8080/emsmigration/branches/'.$dir_loc.'Images/'. $Joinee_img;
			} else {
				$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
			}
		} else {
			$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
		}


		$Joinee_gender = strtoupper($Joinee_gender);
		if ($Joinee_gender == 'FEMALE') {
			$gender = 'She';
			$gender1 = 'her';
		} else {
			$gender = 'He';
			$gender1 = 'him';
		}





		//$doj = $rst_contact[0]['DOJ'];
		$doj = $Joinee_doj;
		$time = strtotime($doj);
		$day = date("j", $time);
		$month = date("M", $time);
		$year = date("Y", $time);
		if ($day == "1") {
			$doj = $day . "<sup>st</sup> " . $month . ' ' . $year;
		} else if ($day == "2") {
			$doj = $day . "<sup>nd</sup> " . $month . ' ' . $year;
		} else if ($day == "3") {
			$doj = $day . "<sup>rd</sup> " . $month . ' ' . $year;
		} else {
			$doj = $day . "<sup>th</sup> " . $month . ' ' . $year;
		}


		$body_ = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
		style="font-family:Arial,Helvetica;font-size:13px;max-width:600px;border:solid 1px #efefef;background-color:#fbfaff;background-color: white; padding-bottom: 17px;">
		<tbody style="background-color: white">
	
			<tr>
				<td align="left" valign="top" style="padding:8px 15px 0px 15px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td align="left" valign="bottom" width="100">
									<img src=' . $imgsrc . ' alt="profile_logo"
										class="CToWUd" data-bit="iit" width="100" height="100">
								</td>
								
								<td valign="top"
									style="color:#fff;font-size:15px;padding-top:15px;font-family:Arial,Helvetica,sans-serif;text-align:left;font-weight:300">
								</td>
								<td align="right" style="float: right;" valign="bottom" >
                                <img src="https://dashboard.cogentlab.com/logo.png" width="100px" height="60px"
								 alt="cogentlogo" class="CToWUd" data-bit="iit" style="margin-top: 10px;">
                            </td>
							</tr>
						</tbody>
					</table>
	
				</td>
			</tr>
	
	
			<tr>
				<td align="left" valign="top" style="padding:0;background-color:#fbfaff;padding:15px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
						style="background-color:#ffffff;border:solid 1px #e6e3f1">
						<tbody>
							<tr>
								<td align="center" valign="top"
									style="padding:15px 0px 0px 0px;font-family:Arial,Helvetica">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
										<tbody>
											<tr>
												<td align="center" valign="top">
	
													<span
														style="margin:0px;padding:0px 30px 10px 30px;font-size:16px;line-height:18px;color:#232434;display:block">
						   <strong style="display:block;margin:0 auto;text-align:center">' . $Joinee_EmpName . '<strong>
														<strong
															style="display:block;margin:0 auto;text-align:center;font-size:18px;color:#333333;padding-top:5px">Congratulations!!</strong>
													</span>
													<p align="center"
														style="font-size:14px;line-height:18px;color:#555555;margin:0;">
			                             <div style="font-size: 15px;font-family: calibri;">' . $Joinee_EMail . '</div>
													<div style="font-size: 15px;font-family: calibri;">+91-' . $Joinee_Mobile . '';
		if ($Joinee_linkdin != '') {
			$body_ = $body_ . '<a href="' . $Joinee_linkdin . '">
														<img src="https://ems.cogentlab.com/erpm/Images/linkdin.jpg" alt="LinkdIn" height="30" width="40" style="
														margin-top: -5px;
														position: absolute;
														width: 38px;
														height: 27px;">
													  </a>';
		}
		$body_ = $body_ . '</div>
													<div style="font-size: 15px;font-family: calibri;">' . $doj . '</div>
	
													</p>
												</td>
											</tr>
	
	
	
											<tr>
												<td align="center" valign="top" style="padding:0 10px">
													<table width="100%" border="0" cellspacing="0" cellpadding="0"
														align="center" style="background-color:#f5f7ff;border-radius:4px">
														<tbody>
	
														</tbody>
													</table>
												</td>
											</tr>
	
	
	
	
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
	
				</td>
			</tr>
			<tr>
				<td align="center" valign="top" style="padding:0px 15px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0"
						style="background-color:#ffffff;border:solid 1px #e0e3fc;border-radius:4px">
						<tbody>
							<tr>
								<td align="left" valign="top" style="padding:15px;border-radius:4px">
	
									<span
										style="font-size:17px;color:#515e6c;padding-bottom:5px;padding-right:5px;font-weight:bold;">
										We are happy to welcome you to the Cogent team.</span>
	
									<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left"
										style="font-size:12px;text-align:left;color:#6e7884">
										<tbody>
											<tr>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;font-weight:bold">Designation:</td>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;color:#515e6c;font-size:13px;font-weight:bold">' . $Joinee_designation . '</td>
											</tr>
											<tr>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;font-weight:bold">Location:</td>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;color:#515e6c;font-size:13px;font-weight:bold">' . $Joinee_location . '</td>
											</tr>
											<tr>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;font-weight:bold">Immediate
													Manager:</td>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;color:#515e6c;font-size:13px;font-weight:bold">' . $Joinee_immediate_manager . '</td>
											</tr>
											<tr>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;font-weight:bold">Assignment:</td>
												<td align="left" valign="top"
													style="padding:7px 0;text-align:left;color:#515e6c;font-size:13px;font-weight:bold">' . $Joinee_assignment . '</td>
											</tr>
										</tbody>
	
									</table>
									<br><span
										style="font-size:17px;color:#515e6c;padding-bottom:5px;padding-right:5px;font-weight:bold;"> <br>
										<b style="color: #048b04;">Congratulations</b>! We look forward to you joining the team</span>
	
								</td>
							</tr>
	
	
	
						</tbody>
					</table>
				</td>
			</tr>
	
	
		</tbody>
	</table><br><br>';
?>
		<script>
			$(document).ready(function() {

				// Global variable 
				var element = $("#html-content-holder");

				// Global variable
				var getCanvas;
				html2canvas(element, {
					letterRendering: 1,
					allowTaint: true,
					onrendered: function(canvas) {
						$("#previewImage").append(canvas);
						getCanvas = canvas;
					}
				});
			});
		</script>
<?php
		//$myDB->rawQuery('update mail_template set flag=2, ModifiedBy ="Server",Modifiedon=now() where empid = "' . $Joinee_EmpID . '" ');
		echo $body_;
		//die;
		$sub = 'Welcome on board - ' . $Joinee_EmpName . ' (' . $Joinee_designation . ' - ' . $Joinee_location . ')';
		$myDB = new MysqliDb();
		$count = $mailcount = 0;
		$rst_contact = $myDB->rawQuery('select count(*) as count from welcome_mail_temp');
		echo $rst_contact[0]['count'];
		if (!empty($rst_contact[0]['count'])) {
			$count = $rst_contact[0]['count'];
		}

		if ($count > 300) {
			while ($mailcount < $count) {
				$myDB = new MysqliDb();
				echo 'select id,rec_empid, rec_email_id from welcome_mail_temp limit ' . $mailcount . ',300' . '<br/>';
				$chk_task1 = $myDB->query('select id,rec_empid, rec_email_id from welcome_mail_temp limit ' . $mailcount . ',300;');



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
				// foreach ($chk_task1 as $key => $value) {

				// 	$mail->addBcc($value['rec_email_id']);
				// 	echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
				// 	$mailcount++;
				// }

				$mail->AddAddress('ems@cogenteservices.in');

				$mail->Subject = $sub;
				echo '111';
				// if (!$mail->send()) {

				// 	echo $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
				// } else {

				// 	echo  $emailStatus =  'Mail Send successfully.';
				// }
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
			$chk_task1 = $myDB->query('select id,rec_empid, rec_email_id from welcome_mail_temp;');


			$my_error = $myDB->getLastError();

			// foreach ($chk_task1 as $key => $value) {

			// 	$mail->addBcc($value['rec_email_id']);
			// 	echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
			// }
			$mail->AddAddress('ems@cogenteservices.in');
			$mail->addBcc('md.masood@cogenteservices.com');
			$mail->addBcc('bachansingh.rawat@cogenteservices.com');
			$mail->addBcc('sachin.siwach@cogenteservices.com');
			$mail->Subject = $sub;
			echo '222';
			if (!$mail->send()) {

				echo $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
			} else {

				echo  $emailStatus =  'Mail Send successfully.';
			}
		}
	}
}

?>