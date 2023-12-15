<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
ini_set('display_errors', '0');
require('../TCPDF/tcpdf.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
$dateformat = date('y-m-d');
$EmployeeID = $EmployeeName = $locationid = $dol = $rsnofleaving = $Gender = $doj = $emailid = $designation = $loc = '';


$GetData = "select t1.EmployeeID,email_id,t2.location,Gender,EmployeeName,dol from releiving_experience_ack t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join exit_emp t3 on t1.EmployeeID=t3.EmployeeID where (Mail_response like 'Mailer Error:SMTP Error: The following recipients failed%' or Mail_response like 'Mailer Error:%') and cast(Created_date as date)>='2022-01-01' order by Created_date limit 20";
$title = '';
$myDB = new MysqliDb();
$resultsE = $myDB->query($GetData);
function dateFormatu($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M Y", strtotime($data));
}
"count=" . count($resultsE);
if (count($resultsE) > 0) {
	foreach ($resultsE as $val) {
		$EmployeeID = $val['EmployeeID'];
		$EmployeeName = $val['EmployeeName'];
		$dol = $val['dol'];
		$dol = dateFormatu($dol);
		$rsnofleaving = $val['rsnofleaving'];
		$locationid = $loc = $val['location'];
		$doj = $val['DOJ'];
		$doj = dateFormatu($doj);
		$designation = $val['designation'];
		$MarriageStatus = $val['MarriageStatus'];
		$Gender = $val['Gender'];
		$emailid = $val['email_id'];



		if ($loc == "1" || $loc == "2") {
			$target_dir = ROOT_PATH . "releiving__experience_pdf/";
		}
		if ($loc == "3") {

			$target_dir = ROOT_PATH . "Meerut/releiving__experience_pdf/";
		} else if ($loc == "4") {

			$target_dir = ROOT_PATH . "Bareilly/releiving__experience_pdf/";
		} else if ($loc == "5") {
			$target_dir = ROOT_PATH . "Vadodara/releiving__experience_pdf/";
		} else if ($loc == "6") {
			$target_dir = ROOT_PATH . "Manglore/releiving__experience_pdf/";
		} else if ($loc == "7") {
			$target_dir = ROOT_PATH . "Bangalore/releiving__experience_pdf/";
		} else if ($loc == "8") {
			$target_dir = ROOT_PATH . "Nashik/releiving__experience_pdf/";
		} else if ($loc == "9") {
			$target_dir = ROOT_PATH . "Anantapur/releiving__experience_pdf/";
		} else if ($loc == "10") {
			$target_dir = ROOT_PATH . "Gurgaon/releiving__experience_pdf/";
		} else if ($loc == "11") {
			$target_dir = ROOT_PATH . "Hyderabad/releiving__experience_pdf/";
		}

		///// pdf creation	

		if ($Gender == 'Female') {
			if ($MarriageStatus = 'Single') {
				$title = 'Ms.';
			} else {
				$title = 'Mrs.';
			}
		} else {
			$title = 'Mr.';
		}

		$filename = $EmployeeID . "_RelievingExperience.pdf";
		if ($target_dir != '') {
			$path = $target_dir . $filename;
		}

		if ($path != '') {
			if (file_exists($path)) {
				if ($emailid != "") {

					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = EMAIL_HOST;
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;
					$mail->Password = EMAIL_PASS;
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT;
					$mail->setFrom(EMAIL_FROM,  'Cogent | Relieving & Experience Letter');
					//$mail->AddAddress('kavya.singh@cogenteservices.com');
					$mail->AddAddress($emailid);
					$mail->addBCC('vijayram.yadav@cogenteservices.com');
					$mail->addBCC('sanoj.pandey@cogenteservices.com');
					//$mail->addBCC('md.masood@cogenteservices.com');
					$mail->Subject = "Candidate Relieving & Experience Letter";
					$mail->isHTML(true);
					$msg2 = $title . " " . $EmployeeName . ',<br/><br/>
				     Please find  attached your Relieving & Experience letter which was proceed on ' . $dol . '.</br><br/>
				     Thanks <br/> Cogent ';
					$mail->Body = $msg2;
					$mymsg = '';
					$response = '';
					$mail->AddAttachment($path);
					if (!$mail->send()) {
						$response =  'Mailer Error:' . $mail->ErrorInfo;
						echo  'Mailer Error:' . $mail->ErrorInfo;
					} else {
						$response =  'Mail Send successfully';
					}
				}
			}
		}


		//insert data
		$dt = new datetime();
		$dt = $dt->format('Y-m-d H:i:s');
		$myDB = new MysqliDb();
		echo $Insertrelieving = "update releiving_experience_ack set Mail_response = '" . $response . "' where employeeid='" . $EmployeeID . "';";
		$myDB = new MysqliDb();
		$resu = $myDB->rawQuery($Insertrelieving);
		$error = $myDB->getLastError();
		if (empty($error)) {
			echo "<script>$(function(){ toastr.success('Successfully Inserted...') });</script>";
		}
	}
}

?>

<?php
//include(ROOT_PATH.'AppCode/footer.mpt'); 
?>