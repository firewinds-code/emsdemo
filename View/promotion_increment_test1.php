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
//$dateformat=date('js m, Y',strtotime($_date1=date('Y-m-d')));
$EmployeeID = $EmployeeName = $locationid = $designation = $WEF = '';
$GetData = "select pr.EmployeeId ,pr.EmployeeName ,pr.Designation, des.id,contact_details.emailid,p.location, p.Gender,i.WEF, i.NewCtc from tbl_promotion as pr  left join contact_details on  pr.EmployeeId=contact_details.EmployeeId left join personal_details p on pr.EmployeeId=p.EmployeeId left join tbl_increment i on pr.EmployeeID=i.EmployeeId join designation_master des on pr.Designation=des.Designation  where pr.EmployeeId='CE01145570' and pr.flag=0 and i.flag=0 limit 100; ";
//Flag=0";
$myDB = new MysqliDb();
function dateFormatu($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" F Y", strtotime($data));
}

function dateFormatu1($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" F Y", strtotime($data));
}

$dateformat = dateFormatu($date = date('Y-m-d'));
echo "<br>";
$resultsE = $myDB->query($GetData);
if (count($resultsE) > 0) {

	foreach ($resultsE as $val) {
		$EmployeeID = $val['EmployeeId'];
		if (substr($EmployeeID, 0, 3) != 'CCE' && substr($EmployeeID, 0, 3) != 'OCM' && substr($EmployeeID, 0, 2) != 'AE' && substr($EmployeeID, 0, 2) != 'RS' && substr($EmployeeID, 0, 3) != 'RSM') {


			$var_desg_id = intval($val['id']);
			if (in_array($var_desg_id, array(2, 3, 4, 6, 11, 18, 19, 20, 21, 25, 26, 27, 28))) {

				echo 'there';
				$EmployeeID = $val['EmployeeId'];
				$EmployeeName = ucwords($val['EmployeeName']);
				$WEF = $val['WEF'];
				$newCtc = number_format($val['NewCtc']);
				$WEF = dateFormatu($WEF);
				$locationid = $loc = $val['location'];
				$designation = $val['Designation'];
				$Gender = $val['Gender'];
				if ($val['Gender'] == 'Male') {
					$Gender = 'Mr';
				} else {
					$Gender = 'Ms';
				}
				$emailid = $val['emailid'];
				$emailid = strtolower($emailid);
				$filename = $EmployeeID . "_Promotion_increment.pdf";

				$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";

				$myDB = new MysqliDb();
				$location_array = array();
				$Locationresult = $myDB->query($Locationquery);
				//var_dump($Locationresult) ;exit;
				if ($loc == "1" || $loc == "2") {
					$target_dir = ROOT_PATH . "Promotion_increment_pdf/";
				}
				if ($loc == "3") {

					$target_dir = ROOT_PATH . "Meerut/Promotion_increment_pdf/";
				} else if ($loc == "4") {

					$target_dir = ROOT_PATH . "Bareilly/Promotion_increment_pdf/";
				} else if ($loc == "5") {
					$target_dir = ROOT_PATH . "Vadodara/Promotion_increment_pdf/";
				} else if ($loc == "6") {
					$target_dir = ROOT_PATH . "Manglore/Promotion_increment_pdf/";
				} else if ($loc == "7") {
					$target_dir = ROOT_PATH . "Bangalore/Promotion_increment_pdf/";
				} else if ($loc == "8") {
					$target_dir = ROOT_PATH . "Nashik/Promotion_increment_pdf/";
				} else if ($loc == "9") {
					$target_dir = ROOT_PATH . "Anantapur/Promotion_increment_pdf/";
				}
				if (!is_dir($target_dir)) {
					@mkdir($target_dir, 0777, true);
				}

				///// pdf creation
				//$pdf1="<p>Date:".$dateformat."</p>";
				$pdfhead = "<h3>PROMOTION LETTER WITH INCREMENT</h3></br>";
				$pdf = "<table>
			<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br><br>
			<tr><td><b>" . $Gender . ". </b>" . $EmployeeName . "</td></tr>
			<tr><td><b>Employee Code : </b>" . $EmployeeID . "</td></tr></br></br></table><br/>
			
			<p>Congratulations ! <br/> <br/> Based on your performance the company has decided to accord you a promotion with an increment in salary. Henceforth, your designation and remuneration w.e.f. ";
				$pdf .= $WEF;
				$pdf .= " will be as under:</p>";
				$pdf .= "<p><b>Designation: </b>";
				$pdf .= $designation;
				$pdf .= "<br><b>Annual CTC: INR </b>";
				$pdf .= $newCtc;
				$pdf .= ".</p><p>Please note that your Cost to Company (CTC) will include a 10% Performance Linked Incentive(PLI) which shall be paid out basis successful achievement of the set performance standards. The PLI shall be paid half yearly <br>Please note that the PLI will be payable only if you are still on the company rolls on the date of disbursement. <br>PLI will not be payable, if you are not on company rolls, have resigned, serving notice period on the date of disbursement.</p>

	        <p>All other terms and conditions of appointment remain the same. </p>

	        <p>You are requested to maintain full confidentiality of this letter. </p>

	        <p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute to the best of your abilities. </p>

	        <p>Best Wishes,</p>
	        <p>Yours truly,</p>
			<p><b>For Cogent E Services Ltd.</b></p></br><br>";
				//echo $pdf;
				$pdf1 = "<p><b>Authorized Signatory</b></p>";

				if (count($Locationresult) > 0) {
					foreach ($Locationresult as $val) {
						if ($locationid == "1" || $locationid == "2") {

							if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
								//echo $location;exit;

							} else {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							}
						} else {
							if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							} else {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							}
						}
					}
				}

				$pdflocation = "
	        <style>
	        p{
	            color:#958d8d;
	            text-align:center;
	            font-size:13px;
	            margin:15px 15px;
	        }
	        </style>
	            <br><br><hr><p>$location </p>";
				//$pdflocation = '<p style="text-align: center"><u>' . $location . '</u></p>';
				$filename = $EmployeeID . "_Promotion_increment.pdf";
				$path = $target_dir . $filename;
				$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

				$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				$tcpdf->SetTitle('Cogent|Promotion_increment Checklist');

				$tcpdf->SetMargins(10, 10, 10, 10);

				$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$tcpdf->setPrintHeader(false);
				$tcpdf->setPrintFooter(false);
				$tcpdf->setListIndentWidth(3);

				$tcpdf->SetAutoPageBreak(TRUE, 11);

				$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				$tcpdf->AddPage();

				$tcpdf->Image('../Style/images/Cogent.png', 150, 10, 40, 20, 'PNG'); //logo right cogent logo

				$tcpdf->SetFont('times', '', 10.5);
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdfhead, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
				$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 210, 30, 15, 'JPEG'); //sig 
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 230, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdflocation, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
				$tcpdf->Output($path, 'F');
				/////end pdf

				///////mail functionality
				if ($emailid != "") {
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = EMAIL_HOST;
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;
					$mail->Password = EMAIL_PASS;
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT;
					$mail->setFrom(EMAIL_FROM,  'Cogent | Promotion_increment Letter');
					$mail->AddAddress($emailid);

					$mail->Subject = "Candidate Promotion_increment Letter";
					$mail->isHTML(true);
					$msg2 = 'Dear ' . $EmployeeName . ',<br/><br/>
			    Please find here with the attached your Promotion_increment letter.';
					$mail->Body = $msg2;
					$mymsg = '';
					$response = '';
					$mail->AddAttachment($path);
					// if (!$mail->send()) {
					// 	$response =  'Mailer Error:' . $mail->ErrorInfo;
					// 	echo  'Mailer Error:' . $mail->ErrorInfo;
					// } else {
					// 	$response =  'Mail Send successfully';

					// 	$updateFlag = "update tbl_promotion set Flag=1 where employeeid='" . $EmployeeID . "';";
					// 	$myDB = new MysqliDb();
					// 	$resu = $myDB->rawQuery($updateFlag);
					// 	$myDB = new MysqliDb();
					// 	$updateFlag = "update tbl_increment set Flag=1 where employeeid='" . $EmployeeID . "';";
					// 	$myDB = new MysqliDb();
					// 	$resu = $myDB->rawQuery($updateFlag);
					// 	$error = $myDB->getLastError();
					// 	if (empty($error)) {
					// 		echo "<script>$(function(){ toastr.success('Successfully Updated...') });</script>";
					// 	} else {
					// 		echo "<script>$(function(){ toastr.error('Your request is already submitted') });</script>";
					// 	}
					// }
				} else {
					$response =  " $emailid Your emailid is not exist.";
				}
				echo $response;
				///////////////end mail
			} else if (in_array($var_desg_id, array(1, 5, 7, 8, 10, 13, 15, 16, 22, 23))) {


				$EmployeeID = $val['EmployeeId'];
				$EmployeeName = $val['EmployeeName'];
				$WEF = $val['WEF'];
				$newCtc = number_format($val['NewCtc']);
				$WEF = dateFormatu($WEF);
				$locationid = $loc = $val['location'];
				$designation = $val['Designation'];
				$Gender = $val['Gender'];
				if ($val['Gender'] == 'Male') {
					$Gender = 'Mr';
				} else {
					$Gender = 'Ms';
				}
				$emailid = $val['emailid'];
				$emailid = strtolower($emailid);
				$filename = $EmployeeID . "_Promotion_increment.pdf";

				$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";

				$myDB = new MysqliDb();
				$location_array = array();
				$Locationresult = $myDB->query($Locationquery);
				//var_dump($Locationresult) ;exit;
				if ($loc == "1" || $loc == "2") {
					$target_dir = ROOT_PATH . "Promotion_increment_pdf/";
				}
				if ($loc == "3") {

					$target_dir = ROOT_PATH . "Meerut/Promotion_increment_pdf/";
				} else if ($loc == "4") {

					$target_dir = ROOT_PATH . "Bareilly/Promotion_increment_pdf/";
				} else if ($loc == "5") {
					$target_dir = ROOT_PATH . "Vadodara/Promotion_increment_pdf/";
				} else if ($loc == "6") {
					$target_dir = ROOT_PATH . "Manglore/Promotion_increment_pdf/";
				} else if ($loc == "7") {
					$target_dir = ROOT_PATH . "Bangalore/Promotion_increment_pdf/";
				} else if ($loc == "8") {
					$target_dir = ROOT_PATH . "Nashik/Promotion_increment_pdf/";
				} else if ($loc == "9") {
					$target_dir = ROOT_PATH . "Anantapur/Promotion_increment_pdf/";
				}

				if (!is_dir($target_dir)) {
					@mkdir($target_dir, 0777, true);
				}
				echo 'there';
				///// pdf creation
				//$pdf1="<p>Date:".$dateformat."</p>";
				$pdfhead = "<h3>PROMOTION LETTER WITH INCREMENT</h3></br>";
				$pdf = "<table>
		<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br><br>
		<tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr>
		<tr><td><b>Employee Code : </b>" . $EmployeeID . "</td></tr></br></br></table><br/>
		
		<p>Based on your performance during the assessment process that you recently underwent, you have been found worthy of promotion. Henceforth, your designation and remuneration w.e.f. ";
				$pdf .= $WEF;
				$pdf .= " will be as under:</p>";
				$pdf .= "<p><b>Designation: </b>";
				$pdf .= $designation;
				$pdf .= ".<br><b>Annual CTC: </b>";
				$pdf .= $newCtc;
				$pdf .= ".</p><p>Please note that your Cost to Company (CTC) will continue to have 10% Performance Linked Incentive(PLI) which shall be paid out basis successful achievement of the set performance standards. The PLI shall be yearly</p>

        <p>Please note that the PLI will be payable only if you are still on the company rolls on the date of disbursement <br>PLI will not be payable, if you are not on Company rolls, have resigned, serving notice period on the date of disbursement.</p>

        <p>All other terms and conditions of appointment remain the same. </p>

        <p>You are requested to maintain full confidentiality of this letter. </p>

        <p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute your best to the company goals and objectives. </p>

        <p>Congratulations and best wishes,</p>
        <p>Yours truly,</p>
		<p><b>For Cogent E Services Ltd.</b></p></br><br>";
				//echo $pdf;
				$pdf1 = "<p><b>Authorized Signatory</b></p>";

				if (count($Locationresult) > 0) {
					foreach ($Locationresult as $val) {
						if ($locationid == "1" || $locationid == "2") {

							if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
								//echo $location;exit;

							} else {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							}
						} else {
							if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							} else {
								$location = '';
								$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
							}
						}
					}
				}

				$pdflocation = "
	        <style>
	        p{
	            color:#958d8d;
	            text-align:center;
	            font-size:13px;
	            margin:15px 15px;
	        }
	        </style>
	            <br><br><hr><p>$location </p>";
				//$pdflocation = '<p style="text-align: center">' . $location . '</p>';
				$filename = $EmployeeID . "_Promotion_increment.pdf";
				$path = $target_dir . $filename;
				$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

				$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				$tcpdf->SetTitle('Cogent|Promotion_increment Checklist');

				$tcpdf->SetMargins(10, 10, 10, 10);

				$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$tcpdf->setPrintHeader(false);
				$tcpdf->setPrintFooter(false);
				$tcpdf->setListIndentWidth(3);

				$tcpdf->SetAutoPageBreak(TRUE, 11);

				$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				$tcpdf->AddPage();

				$tcpdf->Image('../Style/images/Cogent.png', 150, 10, 40, 20, 'PNG'); //logo right cogent logo

				$tcpdf->SetFont('times', '', 10.5);
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdfhead, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
				$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 210, 30, 15, 'JPEG'); //sig 
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 230, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
				$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdflocation, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
				$tcpdf->Output($path, 'F');
				/////end pdf

				///////mail functionality
				if ($emailid != "") {
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = EMAIL_HOST;
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;
					$mail->Password = EMAIL_PASS;
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT;
					$mail->setFrom(EMAIL_FROM,  'Cogent | Promotion_increment Letter');
					$mail->AddAddress($emailid);

					$mail->Subject = "Candidate Promotion_increment Letter";
					$mail->isHTML(true);
					$msg2 = 'Dear ' . $Gender . '. ' . $EmployeeName . ',<br/><br/>
			    Please find attached your letter of promotion. <br/><br/> Yours truly <br/>Hr Team <br/>Cogent E Services Limited.';
					$mail->Body = $msg2;
					$mymsg = '';
					$response = '';
					$mail->AddAttachment($path);
					// if (!$mail->send()) {
					// 	$response =  'Mailer Error:' . $mail->ErrorInfo;
					// 	echo  'Mailer Error:' . $mail->ErrorInfo;
					// } else {
					// 	$response =  'Mail Send successfully';

					// 	$updateFlag = "update tbl_promotion set Flag=1 where employeeid='" . $EmployeeID . "';";
					// 	$myDB = new MysqliDb();
					// 	$resu = $myDB->rawQuery($updateFlag);
					// 	$myDB = new MysqliDb();
					// 	$updateFlag = "update tbl_increment set Flag=1 where employeeid='" . $EmployeeID . "';";
					// 	$myDB = new MysqliDb();
					// 	$resu = $myDB->rawQuery($updateFlag);
					// 	$error = $myDB->getLastError();
					// 	if (empty($error)) {
					// 		echo "<script>$(function(){ toastr.success('Successfully Updated...') });</script>";
					// 	} else {
					// 		echo "<script>$(function(){ toastr.error('Your request is already submitted') });</script>";
					// 	}
					// }
				} else {
					$response =  " $emailid Your emailid is not exist.";
				}
				echo $response;
				///////////////end mail
			}
		}
	}
}


?>

<style>
	.short,
	.weak {
		color: red;
	}

	.good {
		color: #e66b1a;
	}

	.strong {
		color: green;
	}
</style>