<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
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
	$insQ = $conn->prepare($sq1);
	$insQ->bind_param("ss", $module, $type);
	$insQ->execute();
	$result = $insQ->get_result();
	// $myDB->query($sq1);
}
settimestamp('Auto_welcome_newjoinee', 'Start');
$myDB = new MysqliDb();
//$chk_task=$myDB->query('select EmployeeID,location from whole_details_peremp where cast(DOJ as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (5,7,8,10,13,14,15,16,22);');

//$chk_task=$myDB->query('select EmployeeID,location from whole_details_peremp where cast(DOJ as date)>="2020-11-16" and des_id in (5,7,8,10,13,14,15,16,22);');

$chk_task = $myDB->query('select EmployeeID,location from whole_details_peremp where EmployeeID="CE0321936546" and des_id in (5,7,8,10,13,14,15,16,22);');

//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();

$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_welcome();');

		$Joinee_EmpID = $value['EmployeeID'];
		$Joinee_loc = $value['location'];


		$chktask1 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location=? and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="";';
		$selectQ = $conn->prepare($chktask1);
		$selectQ->bind_param("i", $Joinee_loc);
		$selectQ->execute();
		$chk_task1 = $selectQ->get_result();
		//$chk_task1=$myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID in ("CE10091236","CE12102224");');

		//$chk_task1=$myDB->query('select "CE12102224" as EmployeeID,"md.masood@cogenteservices.com" as emailid;');

		// $my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$sql = 'insert into welcome_mail_temp set rec_empid=?,rec_email_id=?,empid=?';
			$insQ = $conn->prepare($sql);
			$insQ->bind_param("sss", $value['EmployeeID'], $value['ofc_emailid'], $Joinee_EmpID);
			$insQ->execute();
			$rst = $insQ->get_result();
		}

		// $myDB = new MysqliDb();
		$chktask2 = 'select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!=? and t1.location !="" and t1.des_id in (1,5,7,8,10,13,14,15,16,22) and ofc_emailid is not null and ofc_emailid !="";';
		$selectQ = $conn->prepare($chktask2);
		$selectQ->bind_param("i", $Joinee_loc);
		$selectQ->execute();
		$chk_task2 = $selectQ->get_result();

		// $my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$sql = 'insert into welcome_mail_temp set rec_empid=?,rec_email_id=?,empid=?';
			$insQr = $conn->prepare($sql);
			$insQr->bind_param("sss", $value['EmployeeID'], $value['ofc_emailid'], $Joinee_EmpID);
			$insQr->execute();
			$rst = $insQr->get_result();
		}

		sendnotification($Joinee_EmpID);
	}
}


$myDB = new MysqliDb();
//$chk_task=$myDB->query('select EmployeeID,location,client_name from whole_details_peremp where cast(DOJ as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (1,2,3,4,6,11);');



//$tablename='whole_details_peremp';
/*$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{
				foreach($chk_task as $key=>$value)
				{
					$myDB=new MysqliDb();
					//$rst = $myDB->rawQuery('call manage_auto_mail_welcome();');
					
					$Joinee_EmpID = $value['EmployeeID'];					
					$Joinee_loc = $value['location'];
					$Joinee_client = $value['client_name'];
					
					$myDB=new MysqliDb();
					$chk_task1=$myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="'.$Joinee_loc.'" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="";');
		
			
					$my_error= $myDB->getLastError();
					
					foreach($chk_task1 as $key=>$value)
					{
						//$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="'.$value['EmployeeID'].'",rec_email_id="'.$value['ofc_emailid'].'",empid="'.$Joinee_EmpID.'"');
						
					}
					
					
					$myDB=new MysqliDb();
					$chk_task2=$myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="'.$Joinee_loc.'" and t1.client_name ="'.$Joinee_client.'" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="";');
		
			
					$my_error= $myDB->getLastError();
					
					foreach($chk_task2 as $key=>$value)
					{
						//$rst = $myDB->rawQuery('insert into welcome_mail_temp set rec_empid="'.$value['EmployeeID'].'",rec_email_id="'.$value['ofc_emailid'].'",empid="'.$Joinee_EmpID.'"');
						
					}
					//sendnotification($Joinee_EmpID);
				}
			}*/

settimestamp('Auto_welcome_newjoinee', 'End');

function sendnotification($Joinee_EmpID)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$Qury = 'select t1.EmployeeName,t1.FirstName, t1.designation,DOJ,Process,t1.Gender,t1.img,ReportTo,t3.EmployeeName as "ReportToName",mobile,emailid,t4.location,t1.location as "locid" from whole_details_peremp t1 join contact_details t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t3.EmployeeID=t1.ReportTo inner join location_master t4 on t1.location=t4.id where t1.EmployeeID= ?';
	$selectQ = $conn->prepare($Qury);
	$selectQ->bind_param("s", $Joinee_EmpID);
	$selectQ->execute();
	$result = $selectQ->get_result();
	$rst_contact = $result->fetch_row();
	//$Joinee_EmpID = $value['EmployeeID'];			 		
	if (!empty(clean($rst_contact[0]))) {

		$dir_loc = "";
		if (clean($rst_contact[12]) == "3") {
			$dir_loc = "Meerut/";
		} else if (clean($rst_contact[12]) == "4") {
			$dir_loc = "Bareilly/";
		} else if (clean($rst_contact[12]) == "5") {
			$dir_loc = "Vadodara/";
		} else if (clean($rst_contact[12]) == "6") {
			$dir_loc = "Manglore/";
		} else if (clean($rst_contact[12]) == "7") {
			$dir_loc = "Bangalore/";
		}

		if (trim(clean($rst_contact[6])) != '') {
			//$imgsrc = '../Images/'. clean($rst_contact[0])['img'];
			$imgsrc = '../' . $dir_loc . 'Images/' . clean($rst_contact[6]);
			//echo $imgsrc = 'https://demo.cogentlab.com/erpm/'.$dir_loc.'Images/'. clean($rst_contact[0])['img'].'<br/>';
			if (file_exists($imgsrc)) {
				//$imgsrc = 'https://demo.cogentlab.com/erpm/Images/'. clean($rst_contact[0])['img'];
				$imgsrc = 'https://demo.cogentlab.com/erpm/' . $dir_loc . 'Images/' . clean($rst_contact[6]);
			} else {
				$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
			}
		} else {
			$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
		}

		/*$imgsrc = '../Images/'. clean($rst_contact[0])['img'];
					if(file_exists($imgsrc))
					{
						$imgsrc = 'https://demo.cogentlab.com/erpm/Images/'. clean($rst_contact[0])['img'];
					}
					else
					{
						$imgsrc = 'https://demo.cogentlab.com/erpm/Images/profile_logo.jpg';
					}*/


		if (clean($rst_contact[5]) == 'Female') {
			$gender = 'She';
			$gender1 = 'her';
		} else {
			$gender = 'He';
			$gender1 = 'his';
		}
		$empname = clean($rst_contact[0]);

		$SQL = 'select edu_level,edu_name,board,specialization from education_details where EmployeeID= ? order by edu_level desc limit 1';
		$SelectQy = $conn->prepare($SQL);
		$SelectQy->bind_param("s", $Joinee_EmpID);
		$SelectQy->execute();
		$results = $SelectQy->get_result();
		$rst_edu = $results->fetch_row();
		if (!empty(clean($rst_edu[0]))) {

			if (clean($rst_edu[0]) == "Basic") {
				$higher_edu = '12th';
			} else {
				$higher_edu = clean($rst_edu[0]);
				if ($higher_edu == 'Graduation') {
					$higher_edu = 'Graduate';
				} else if ($higher_edu == 'Post Graduation') {
					$higher_edu = 'Post Graduate';
				}
			}
			$university = clean($rst_edu[2]);
			$degree = clean($rst_edu[3]);
		}

		// $myDB = new MysqliDb();
		$SEL = 'select * from experince_details where employer is not null and employer !="" and EmployeeID= ?';
		$SelectQury = $conn->prepare($SEL);
		$SelectQury->bind_param("s", $Joinee_EmpID);
		$SelectQury->execute();
		$chk_exp = $SelectQury->get_result();
		$years = $months = $days = $diff = 0;
		$expdiv = '';
		$employer = $latest_exp = '';
		//$tablename='whole_details_peremp';
		$my_error = $myDB->getLastError();
		$table = '';
		if ($chk_exp->num_rows > 0 && $chk_exp) {
			foreach ($chk_exp as $key => $value) {
				//echo $value['from']. ','.$value['to'].'-';
				$date1 = $value['from'];
				$date2 = $value['to'];

				$diff = $diff + abs(strtotime($date2) - strtotime($date1));
				if ($employer != '') {
					$dateTimestamp1 = strtotime($latest_exp);
					$dateTimestamp2 = strtotime($value['to']);
					if ($dateTimestamp1 > $dateTimestamp2) {
					} else {
						$employer = $value['employer'];
						$latest_exp = $value['to'];
					}
				} else {
					$employer = $value['employer'];
					$latest_exp = $value['to'];
				}
			}
		} else {
			$exp = 'NA';
		}

		$years = $years + floor($diff / (365 * 60 * 60 * 24));
		$months = $months + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
		$days = $days + floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

		//echo 'Days-'. $days.',Month-'.$months.',Year-'.$years; die;
		if ($years == 0 && $months == 0) {
			$exp = 'NA';
			$expdiv = '';
		} else {
			if ($years != 0) {
				if ($months != 0) {
					$exp = $years . ' Years' . ' and ' . $months . ' months';
				} else {
					$exp = $years . ' Years';
				}

				$expdiv = '<tr><td style="padding-top: 25px;"><b>' . clean($rst_contact[0]) . '</b> carries a total work experience of <b>' . $exp . '</b> . ' . $gender . ' previously worked with <b>' . $employer . '</b>.</td></tr>';
			} else if ($months != 0) {
				if ($days != 0) {
					$exp = $months . ' Months' . ' and ' . $days . ' days';
				} else {
					$exp = $months . ' Months';
				}

				$expdiv = '<tr><td style="padding-top: 25px;"><b>' . clean($rst_contact[0]) . '</b> carries a total work experience of <b>' . $exp . '</b> . ' . $gender . ' previously worked with <b>' . $employer . '</b>.</td></tr>';
			}
		}
		//echo $expdiv;die;
		$expdiv = '<tr><td style="padding-top: 25px;"><b>' . clean($rst_contact[1]) . '</b> carries a total work experience of <b>16 Years</b> . ' . $gender . ' previously worked with <b>ZINKA LOGISTICS SOLUTIONS PRIVATE LIMITED</b>.</td></tr>';

		$doj = clean($rst_contact[3]);
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

		$desig = "Senior Manager &#45; Operation";

		$body_ = '<html>
					<head>
					<script src= 
"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"> 
    </script> 
      
    <script src= 
"https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"> 
    </script> 
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
	
	<div id="html-content-holder">
	<table width="100%">
			<tr>
			<td style="padding-left:7%;">
						<div style="border: dashed  #ccc 2px;border-right: dashed  #ccc 2px;height:120px;width:100%;position:relative;top:3px;">
						<table>
									<tr>
										<td style="padding-left:6%;"><img src="https://demo.cogentlab.com/erpm/Style/images/cogent-logobkp1.png" style="float:left;margin-top: 20px;"/>
										</td>
											<td style="padding-left:30px;padding-top:20px;">
											<span style="font-family: Verdana; font-size: 30px;">Introducing</span>											
											<br/><br/>
											<span style="font-family: Verdana; font-size: 36px; font-weight: bold;">' . clean($rst_contact[0]) . '</span>
											</td>											
												
								</tr>
							</table>
							</div>
				</td>
					
				<td style="padding-top: 10px;align="left"><img src="https://demo.cogentlab.com/erpm/Style/images/Picture3.png" class="imgcss" width="250" height="145"/></td>
						
			</tr>
			
			<tr style="background-color: #fff;">
				<td width="60%" style="padding-left: 50px;">
					<table style="width: 100%; text-align: left;font-family: Verdana; font-size: 20px; padding-top: 25px;">
						<tr>
							<td>
								We are please to introduce <b>' . clean($rst_contact[0]) . '</b> who has joined us as <b>' . $desig . '</b> on <b>';

		$body_ .= $doj;
		$body_ .= '
								</b>.
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								' . $gender . ' is a <b>Graduate</b> with <b>Bachelor of Business Administration (Business Administration)</b>.
							</td>
						</tr>
						';

		$body_ .= $expdiv;

		$body_ .= '<tr>
							<td style="padding-top: 25px;">
								<b>' . clean($rst_contact[1]) . '</b> will work in <b>Zomato</b> and report to <b>Imtiyaz Ahmad Khan</b> at <b>' . clean($rst_contact[11]) . '</b>. ' . $gender . ' can be reached at <b>' . clean($rst_contact[9]) . '</b>

							</td>
						</tr>	
						<tr>
							<td style="padding-top: 25px;">
								We are confident that <b>' . clean($rst_contact[1]) . '</b> will fulfill ' . $gender1 . ' role to the best of ' . $gender1 . ' ability to maintain our standard of care and delivery

							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								Welcome on board <b>' . clean($rst_contact[0]) . '</b>!
							</td>
						</tr>
					</table>
				</td>
				<td align="left" width="30%" style="position: relative;	top: -65px;"><img src=' . $imgsrc . ' class="imgcss" width="250"/></td>
				
			</tr>
			
		</table>
		
	</body>
	</div>
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
		echo $body_;
		die;
		$sub = 'Welcome on board - ' . clean($rst_contact[0]) . ' (Senior Manager - Operation - Noida)';
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

					/*$mail->addBcc($value['rec_email_id']);
									echo $value['id'].'-'.$value['rec_empid'].'-'.$value['rec_email_id'].'<br/>';*/
					$mailcount++;
				}

				$mail->AddAddress('ems@cogenteservices.in');
				//$mail->AddAddress('sachin.siwach@cogenteservices.in');
				//$mail->addBcc('md.masood@cogenteservices.com');

				$mail->Subject = $sub;

				/*if(!$mail->send())
							 	{
							 		
							 		echo $emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
							  	} 
								else
								{
									
								   echo  $emailStatus =  'Mail Send successfully.';
								}*/
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
			//$mail->addBcc('md.masood@cogenteservices.com');

			$mail->Subject = $sub;

			/*if(!$mail->send())
				 	{
				 		
				 		echo $emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
				  	} 
					else
					{
						
					   echo  $emailStatus =  'Mail Send successfully.';
					}*/
		}
	}
}

?>