<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');
require('../TCPDF/tcpdf.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
function dateFormatu($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M Y", strtotime($data));
}

function dateFormatu1($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M", strtotime($data));
}

$dateformat = dateFormatu($date = date('Y-m-d'));
//$dateformat=date('jS m, Y',strtotime($_date1=date('Y-m-d')));
$EmployeeID = $EmployeeName = $ctc = $locationid = $PLIMode = $WEF = $NewCTC = $PLIPercent = $loc = $emailid = '';
$GetData = "select distinct t2.EmployeeId, t1.EmployeeId,t1.EmployeeName,t1.WEF,t1.PLIMode,t1.NewCTC,t1.PLIPercent,t3.emailid,t4.location,t4.gender from tbl_increment t1 left outer join tbl_promotion t2 on t1.EmployeeId = t2.EmployeeId join contact_details t3 on t1.EmployeeId = t3.EmployeeID join personal_details t4 on t1.EmployeeID = t4.EmployeeID where t1.Flag=0 and case when t2.EmployeeId is null then 1=1 else   t2.Flag!=0 end limit 100";
//Flag=0";
$myDB = new MysqliDb();
$resultsE = $myDB->query($GetData);
if (count($resultsE) > 0) {
	foreach ($resultsE as $val) {
		$EmployeeID = $val['EmployeeId'];

		if (substr($EmployeeID, 0, 3) != 'CCE' && substr($EmployeeID, 0, 3) != 'OCM' && substr($EmployeeID, 0, 2) != 'AE' && substr($EmployeeID, 0, 2) != 'RS' && substr($EmployeeID, 0, 3) != 'RSM') {
			$EmployeeName = $val['EmployeeName'];
			//$WEF = dateFormatu($val['WEF']);
			$WEF = $val['WEF'];
			$PLIMode = $val['PLIMode'];
			$NewCTC = number_format($val['NewCTC']);
			$PLIPercent = $val['PLIPercent'];
			$loc = $locationid = $val['location'];
			$emailid = $val['emailid'];
			$emailid = strtolower($emailid);
			$Gender = strtolower($val['gender']);
			if ($Gender == 'female') {
				$Gender = 'Ms';
			} else if ($Gender == 'male') {
				$Gender = 'Mr';
			} else {
				$Gender = '';
			}
			//$ctc = $val['ctc'];
			$filename = $EmployeeID . "_increment.pdf";
			$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";
			$myDB = new MysqliDb();
			$location_array = array();
			$Locationresult = $myDB->query($Locationquery);
			if ($loc == "1" || $loc == "2") {
				$target_dir = ROOT_PATH . "increment_pdf/";
			}
			if ($loc == "3") {

				$target_dir = ROOT_PATH . "Meerut/increment_pdf/";
			} else if ($loc == "4") {

				$target_dir = ROOT_PATH . "Bareilly/increment_pdf/";
			} else if ($loc == "5") {
				$target_dir = ROOT_PATH . "Vadodara/increment_pdf/";
			} else if ($loc == "6") {
				$target_dir = ROOT_PATH . "Manglore/increment_pdf/";
			} else if ($loc == "7") {
				$target_dir = ROOT_PATH . "Bangalore/increment_pdf/";
			} else if ($loc == "8") {
				$target_dir = ROOT_PATH . "Nashik/increment_pdf/";
			} else if ($loc == "9") {
				$target_dir = ROOT_PATH . "Anantapur/increment_pdf/";
			} else if ($loc == "10") {
				$target_dir = ROOT_PATH . "Gurgaon/increment_pdf/";
			} else if ($loc == "11") {
				$target_dir = ROOT_PATH . "Hyderabad/increment_pdf/";
			}

			if (!is_dir($target_dir)) {
				@mkdir($target_dir, 0777, true);
			}
			///// pdf creation
			$pdf2 = "<h4>INCREMENT LETTER</h4></br>";
			$pdf = "<table>
		<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br><br>
		<tr><td><b>" . $Gender . " : </b> " . $EmployeeName . "</td></tr>
		<tr><td><b>Employee Code : </b> " . $EmployeeID . "</td></tr></table><br/>
	
		<p>Based on your performance during the ";
			$pdf = $pdf . date('Y', strtotime($WEF));
			$pdf = $pdf . ", you have been found worthy of increment. Henceforth, your revised remuneration w.e.f. ";
			$pdf = $pdf . dateFormatu1(date('j-F', strtotime($WEF)));
			$pdf = $pdf . " will be as under:</p><p><b>Annual CTC:</b>";
			$pdf = $pdf . $NewCTC;
			$pdf = $pdf . "</p><p>The above remuneration will include Performance Linked Incentive. All other terms and conditions of appointment remain the same. </p>
		<p>You are requested to maintain full confidentiality of this letter. </p>
		<p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute your best to the company goals and objectives. </p>
		<p>Congratulations and best wishes,</p>
		<p>Yours truly,</p>
				<p><b>For Cogent E Services Ltd.</b></p></br>
				";
			$pdf3 = "<p>(S.K Garg)</p>
				<p><b>Authorized Signatory</b></p>";
			if (count($Locationresult) > 0) {
				foreach ($Locationresult as $val) {

					if ($locationid == "1" || $locationid == "2") {

						if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
							$location = '';
							$location = $val['companyname'] . ' ' . $val['address'] . '<br/> Website : www.cogenteservices.com';
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
			$pdf1 = "
	        <style>
	        p{
	            color:#958d8d;
	            text-align:center;
	            font-size:13px;
	            margin:15px 15px;
	        }
	        </style>
	            <br><br><hr><p>$location </p>";
			$filename = $EmployeeID . "_Increment.pdf";
			$path = $target_dir . $filename;
			$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$tcpdf->SetTitle('Cogent|Increment Checklist');

			$tcpdf->SetMargins(10, 10, 10, 10);
			$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$tcpdf->setPrintHeader(false);
			$tcpdf->setPrintFooter(false);
			$tcpdf->setListIndentWidth(3);

			$tcpdf->SetAutoPageBreak(TRUE, 11);

			$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$tcpdf->AddPage();

			$tcpdf->SetFont('times', '', 10.5);

			$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 170, 30, 15, 'JPEG'); //SIG

			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); // Cogent Logo

			/*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
			if ('AE' == substr($EmployeeID, 0, 2)) {
				$tcpdf->Image('../Style/images/client_logo.jpg', 140, 5, 60, 30, 'JPG'); //logo right Aurum
			} else {
				$tcpdf->Image('../Style/images/cogent-logo.png', 160, 5, 45, 18, 'PNG'); //logo right cogent logo
			}
		} elseif ($locationid == "3") {
			if ('OC' == substr($EmployeeID, 0, 2)) {
				$tcpdf->Image('../Style/images/client_logo.png', 140, 5, 60, 30, 'PNG'); //logo right Orium
			} else {
				$tcpdf->Image('../Style/images/cogent-logo.png', 160, 5, 45, 18, 'PNG'); //logo right cogent logo
			}
		}*/

			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 5, $y = 40, $pdf2, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = 'C');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 50, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 190, $pdf3, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

			$tcpdf->Output($path, 'F');
			////end pdf

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
				$mail->setFrom(EMAIL_FROM,  'Cogent | Increment Letter');
				$mail->AddAddress($emailid);

				$mail->Subject = "Candidate Increment Letter";
				$mail->isHTML(true);
				$msg2 = 'Dear ' . $EmployeeName . ',<br/><br/>
			    Please find  attached your Increment letter.';
				$mail->Body = $msg2;
				$mymsg = '';
				$response = '';
				$mail->AddAttachment($path);
				if (!$mail->send()) {
					$response =  'Mailer Error:' . $mail->ErrorInfo;
					echo  'Mailer Error:' . $mail->ErrorInfo;
				} else {

					$response =  'Mail Send successfully';

					$updateFlag = "update tbl_increment set flag=1 where employeeid='" . $EmployeeID . "';";
					$myDB = new MysqliDb();
					$resu = $myDB->rawQuery($updateFlag);
					$error = $myDB->getLastError();
					if (empty($error)) {
						echo "<script>$(function(){ toastr.success('Successfully Updated...') });</script>";
					} else {
						echo "<script>$(function(){ toastr.error('Your request is already submitted') });</script>";
					}
				}
			} else {
				$response =  "Your emailid is not exist.";
			}
			///////////////end mail
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
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">INCREMENT LETTER </span>
	<div class="pim-container" id="div_main">
		<div class="form-div">
			<div class="schema-form-section ">
				<p style="text-align: right;">
					<?php
					if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8" || $locationid == "9") {
						if ('AE' == substr($EmployeeID, 0, 2)) {
					?>
							<img src="../Style/images/client_logo.JPG" style="width: 200px;height: 70px;" />
						<?php
						} else { ?>
							<img src="../Style/images/cogent-logo.png" style="width: 200px;height: 50px;" />
							<?php }
					} else {

						if ($locationid == "3") {
							if ('OC' == substr($EmployeeID, 0, 2)) {
							?>
								<img src="../Style/images/client_logo.png" style="width: 200px;height: 70px;" />
							<?php 	}
						} else {
							?>
							<img src="../Style/images/cogent-logo.png" style="width: 200px;height: 50px;" />
					<?php
						}
					}

					?>
				</p></br>
				<h4 style="text-align: center">INCREMENT LETTER </h4>
				<p><b>Date : </b><?php echo $dateformat; ?></p><br><br>
				<p><b><?php echo $Gender; ?> : </b><?php echo $EmployeeName; ?></p>
				<P><b>Employee Code : </b><?php echo $EmployeeID; ?></P><br />

				<p>Based on your performance during the <?php echo date('Y', strtotime($WEF)); ?>, you have been found worthy of increment. Henceforth, your revised remuneration w.e.f. <?php echo dateFormatu($WEF); ?> will be as under:</p>
				<p><b>Annual CTC:</b><?php echo $NewCTC; ?> .</p>
				<p>The above remuneration will include Performance Linked Incentive. All other terms and conditions of appointment remain the same. </p>
				<p>You are requested to maintain full confidentiality of this letter. </p>
				<p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute your best to the company goals and objectives. </p>
				<p>Congratulations and best wishes,</p>
				<p>Yours truly,</p>
				<p><b>For Cogent E Services Ltd.</b></p></br>
				<p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;" /></p>
				<p>(S.K Garg)</p>
				<p><b>Authorized Signatory</b></p>
				<br /><br />
				<p style="text-align: center"><u> <?php
													if (count($Locationresult) > 0) {
														foreach ($Locationresult as $val) {
															if ($locationid == "1" || $locationid == "2") {
																if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
																	echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
																}
															} else {
																if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
																	echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
																} else {
																	echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
																}
															}
														}
													}
													?></u></p><br />
			</div>
		</div>
	</div>
</div>