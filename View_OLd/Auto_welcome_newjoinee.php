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

// error_reporting(E_ALL);
ini_set('display_errors', 1);

require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$sq1 = "insert into scheduler(modulename,type)values(?,?);";
	// $myDB->query($sq1);
	$ins = $conn->prepare($sq1);
	$ins->bind_param("ss", $module, $type);
	$ins->execute();
	$res = $ins->get_result();
}
settimestamp('Auto_welcome_newjoinee', 'Start');

// $myDB = new MysqliDb();

$chk_task = $myDB->query('select empid,name,t3.ofc_emailid as email,t3.mobile as contact_no,doj,designation,location,immediate_manager,assignment,gender,linkdinLink,des_id,loc_id,img from mail_template t1 join employee_map t2 on t1.empid=t2.EmployeeID join contact_details t3 on t1.empid=t3.EmployeeID where emp_status="Active" and t1.flag=1 and des_id in (5,7,8,10,13,14,15,16,22)');

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
		$chktask1 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location=? and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";';
		$selectQury = $conn->prepare($chktask1);
		$selectQury->bind_param("i", $Joinee_loc);
		$selectQury->execute();
		$chk_task1 = $selectQury->get_result();
		// $my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}

		// $myDB = new MysqliDb();
		$chktask2 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!=? and t1.location !="" and t1.des_id in (1,5,7,8,10,13,14,15,16,22,23,29) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";';
		$selectQury = $conn->prepare($chktask2);
		$selectQury->bind_param("i", $Joinee_loc);
		$selectQury->execute();
		$chk_task2 = $selectQury->get_result();

		// $my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$sql = 'insert into welcome_mail_temp set rec_empid=?,rec_email_id=?,empid=?';
			$insQ = $conn->prepare($sql);
			$insQ->bind_param("sss", $value['EmployeeID'], $value['ofc_emailid'], $Joinee_EmpID);
			$insQ->execute();
			$rst = $insQ->get_result();
		}
		$query = 'insert into welcome_mail_temp set rec_email_id="Abhinav@cogenteservices.com",empid=?';
		$insQu = $conn->prepare($query);
		$insQu->bind_param("s", $Joinee_EmpID);
		$insQu->execute();
		$rst = $insQu->get_result();

		$query = 'insert into welcome_mail_temp set rec_email_id="Gaurav@cogenteservices.com",empid=?';
		$insQu = $conn->prepare($query);
		$insQu->bind_param("s", $Joinee_EmpID);
		$insQu->execute();
		$rst = $insQu->get_result();

		$query = 'insert into welcome_mail_temp set rec_email_id="Pranjal@cogenteservices.com",empid=?';
		$insQu = $conn->prepare($query);
		$insQu->bind_param("s", $Joinee_EmpID);
		$insQu->execute();
		$rst = $insQu->get_result();

		$query = 'insert into welcome_mail_temp set rec_email_id="Jaspreet@cogenteservices.com",empid=?';
		$insQu = $conn->prepare($query);
		$insQu->bind_param("s", $Joinee_EmpID);
		$insQu->execute();
		$rst = $insQu->get_result();
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
		$chktask1 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location=? and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";';
		$selectQury = $conn->prepare($chktask1);
		$selectQury->bind_param("i", $Joinee_loc);
		$selectQury->execute();
		$chk_task1 = $selectQury->get_result();

		// $my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$sql = 'insert into welcome_mail_temp set rec_empid=?,rec_email_id=?,empid=?';
			$insQ = $conn->prepare($sql);
			$insQ->bind_param("sss", $value['EmployeeID'], $value['ofc_emailid'], $Joinee_EmpID);
			$insQ->execute();
			$rst = $insQ->get_result();
		}


		// $myDB = new MysqliDb();
		$chktask2 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!=? and t1.client_name =? and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";';
		$selectQury = $conn->prepare($chktask2);
		$selectQury->bind_param("is", $Joinee_loc, $Joinee_client);
		$selectQury->execute();
		$chk_task2 = $selectQury->get_result();

		// $my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$sql = 'insert into welcome_mail_temp set rec_empid=?,rec_email_id=?,empid=?';
			$insQ = $conn->prepare($sql);
			$insQ->bind_param("sss", $value['EmployeeID'], $value['ofc_emailid'], $Joinee_EmpID);
			$insQ->execute();
			$rst = $insQ->get_result();
		}

		sendnotification($Joinee_EmpID, $Joinee_EmpName, $Joinee_EMail, $Joinee_Mobile, $Joinee_doj, $Joinee_designation, $Joinee_location, $Joinee_immediate_manager, $Joinee_assignment, $Joinee_gender, $Joinee_linkdin, $Joinee_loc, $Joinee_img);
	}
}

settimestamp('Auto_welcome_newjoinee', 'End');

function sendnotification($Joinee_EmpID, $Joinee_EmpName, $Joinee_EMail, $Joinee_Mobile, $Joinee_doj, $Joinee_designation, $Joinee_location, $Joinee_immediate_manager, $Joinee_assignment, $Joinee_gender, $Joinee_linkdin, $Joinee_loc, $Joinee_img)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$rstcontact = 'select t1.EmployeeName,t1.FirstName, t1.designation,DOJ,Process,t1.Gender,t1.img,ReportTo,t3.EmployeeName as "ReportToName",mobile,emailid,t4.location,t1.location as "locid" from whole_details_peremp t1 join contact_details t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t3.EmployeeID=t1.ReportTo inner join location_master t4 on t1.location=t4.id where t1.EmployeeID=?';
	$selQ = $conn->prepare($rstcontact);
	$selQ->bind_param("s", $Joinee_EmpID);
	$selQ->execute();
	$rst_contact = $selQ->get_result();
	$rst_contacts = $rst_contact->fetch_row();
	//$Joinee_EmpID = $value['EmployeeID'];			 		
	if (!empty($rst_contacts[0])) {
		$proimgsrc = 'https://demo.cogentlab.com/erpm/Style/images/mail_pic.png';
		//$proimgsrc = 'http://localhost:8080/emsmigration/branches/Style/images/mail_pic.png';

		$mailimgsrc = 'https://demo.cogentlab.com/erpm/Style/images/cogent-logobkp_mail.png';
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
		}

		if (trim($Joinee_img) != '') {
			//$imgsrc = '../Images/'. $rst_contact[0]['img'];
			$imgsrc = '../' . $dir_loc . 'Images/' . $Joinee_img;
			//echo $imgsrc = 'https://demo.cogentlab.com/erpm/'.$dir_loc.'Images/'. $rst_contact[0]['img'].'<br/>';
			if (file_exists($imgsrc)) {
				$imgsrc = 'https://demo.cogentlab.com/erpm/' . $dir_loc . 'Images/' . $Joinee_img;
				//$imgsrc = 'http://localhost:8080/emsmigration/branches/'.$dir_loc.'Images/'. $Joinee_img;
			} else {
				$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
			}
		} else {
			$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
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


		$body_ = '<html>
					<head>
					<script src= "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
      
    				<script src="https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"></script> 
					<style>
							.imgcss
							{
								display: block;
								width: 200px !important;
								 border: solid  #ccc 2px;
								border-radius: 50%;
								 -moz-border-radius: 50%;
							    -webkit-border-radius: 50%;
								max-width: 100% !important;
							}
							.proimgcss
							{
								display: block;
								width: 200px !important;
								height: 200px !important;
																
							}
							.mailimgcss
							{
								display: block;width: 105px !important;	height: 50px !important;margin-top: 109px;margin-left: 62px;								
							}
							.circle
							{
								width: 200px;
							    height: 200px;
							    
							    -moz-border-radius: 100px;
							    -webkit-border-radius: 100px;
							    border-radius: 100px;
							    border: solid  #fff 2px;
							    border-right: solid  #fff 2px;
							    position:relative;
							    top:20px;
							}
						</style>
						<!--[if gte mso 9]>
							<style>
							.imgcss
							{
								display: block;
								width: 200px !important;
								 border: solid  #ccc 2px;
								border-radius: 50%;
								 -moz-border-radius: 50%;
							    -webkit-border-radius: 50%;
								max-width: 100% !important;
							}
							.circle
							{
								width: 200px;
							    height: 200px;
							    
							    -moz-border-radius: 100px;
							    -webkit-border-radius: 100px;
							    border-radius: 100px;
							    border: solid  #fff 2px;
							    border-right: solid  #fff 2px;
							    position:relative;
							    top:20px;
							}
							.circle img{
								border-radius: 100px;
							}
							</style>
						<![endif]-->	
					</head>
	<body width=“100%” style=“margin: 0; padding: 0 !important; mso-line-height-rule: exactly;”>
	
	<div id="html-content-holder">
	<table width="100%">
			<tr>
				<td width="60%" style="padding-left:7%;padding-top: 20px;">
				<div class="circle" style="border-radius:50%"  >
					<img src=' . $imgsrc . ' class="imgcss" width="200" height="200" style="border-radius:50%"  />
				</div>
				</td>
				<td width="40%"><img src=' . $proimgsrc . ' width="200" height="200" /></td>
			</tr>
			<tr>
				<td width="60%" style="padding-left:7%;padding-top: 20px;">
					<table width="100%">
						<tr>
							<td colspan="2" style="font-size: 25px;font-weight: bold;font-family: calibri;">' . $Joinee_EmpName . '</td>
							
						</tr>
						<tr style="font-family: calibri;color: #808080;">
							<td>' . $Joinee_EMail . '</td><td>+91-' . $Joinee_Mobile . '</td>
							
						</tr>
						<tr>
							<td style="font-size: 25px;font-family: calibri;height: 50px">' . $doj . '</td>';
		if ($Joinee_linkdin != '') {
			$body_ = $body_ . '<td style="font-size: 25px;font-family: calibri;height: 50px">
								<a href="' . $Joinee_linkdin . '">
									<img src="https://demo.cogentlab.com/erpm/Images/linkdin.jpg" height="40" width="50">
								</a>
							</td>';
		}
		$body_ = $body_ . '
							
						</tr>
						<tr>
							<td colspan="2" style="font-size: 25px;font-family: calibri;height: 50px">We are happy to welcome you to the Cogent family.</td>
							
						</tr>
						<tr>
							<td style="font-family: calibri;color: #808080;">Designation:</td><td style="font-family: calibri; font-weight: bold;">' . $Joinee_designation . '</td>
							
						</tr>
						<tr>
							<td style="font-family: calibri;color: #808080;">Location:</td><td style="font-family: calibri; font-weight: bold;">' . $Joinee_location . '</td>
							
						</tr>
						<tr>
							<td style="font-family: calibri;color: #808080;">Immediate Manager:</td><td style="font-family: calibri; font-weight: bold;">' . $Joinee_immediate_manager . '</td>
							
						</tr>
						<tr>
							<td style="font-family: calibri;color: #808080;">Assignment:</td><td style="font-family: calibri; font-weight: bold;">' . $Joinee_assignment . '</td>
							
						</tr>
						<tr>
							<td colspan="2" style="font-size: 22px;font-family: calibri; font-weight: bold;height: 50px">Wishing ' . $gender1 . ' the very best in the new assignment.</td>
							
						</tr>
					</table>
					</td>
					<td width="40%"> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=' . $mailimgsrc . ' /></td>
				
				
				
			</tr>
			
		</table>
		</div>
	</body>
	
</html>';
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
		$update = 'update mail_template set flag=2, ModifiedBy ="Server",Modifiedon=now() where empid = ? ';
		$upQu = $conn->prepare($update);
		$upQu->bind_param("s", $Joinee_EmpID);
		$upQu->execute();
		$res = $upQu->get_result();
		// echo $body_;
		// die;
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
				foreach ($chk_task1 as $key => $value) {

					$mail->addBcc($value['rec_email_id']);
					echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
					$mailcount++;
				}

				$mail->AddAddress('ems@cogenteservices.in');

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
			$chk_task1 = $myDB->query('select id,rec_empid, rec_email_id from welcome_mail_temp;');


			$my_error = $myDB->getLastError();

			foreach ($chk_task1 as $key => $value) {

				$mail->addBcc($value['rec_email_id']);
				echo $value['id'] . '-' . $value['rec_empid'] . '-' . $value['rec_email_id'] . '<br/>';
			}
			$mail->AddAddress('ems@cogenteservices.in');
			// $mail->addBcc('md.masood@cogenteservices.com');
			// $mail->addBcc('bachansingh.rawat@cogenteservices.com');
			$mail->Subject = $sub;

			if (!$mail->send()) {

				echo $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
			} else {

				echo  $emailStatus =  'Mail Send successfully.';
			}
		}
	}
}

?>