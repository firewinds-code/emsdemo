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
//$GetData = "select distinct t1.EmployeeId,t1.EmployeeName,t1.WEF,t1.Designation,t3.emailid,t4.location,t4.gender from tbl_promotion t1 left outer join tbl_increment t2 on t1.EmployeeId = t2.EmployeeId join contact_details t3 on t1.EmployeeId = t3.EmployeeID join personal_details t4 on t1.EmployeeID = t4.EmployeeID where t1.Flag=0 and case when t2.EmployeeId is null then 1=1 else   t2.Flag!=0 end limit 210";

$GetData = "select t1.EmployeeID, t1.EmployeeName, t1.WEF,t1.Designation,t3.emailid,t4.location,t4.Gender,t2.df_id from tbl_promotion t1 join employee_map t2 on t1.EmployeeId=t2.EmployeeID join contact_details t3 on t1.EmployeeID=t3.EmployeeID join personal_details t4 on t1.EmployeeID=t4.EmployeeID where t2.emp_status='Active' and df_id=77 and t1.flag=0";
//Flag=0";

$myDB = new MysqliDb();
function dateFormatu($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M Y", strtotime($data));
}

function dateFormatu1($data)
{
	return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M", strtotime($data));
}

$dateformat = dateFormatu($date = date('Y-m-d'));
$resultsE = $myDB->query($GetData);
if (count($resultsE) > 0) {
	foreach ($resultsE as $val) {
		$EmployeeID = $val['EmployeeID'];

		if ($val['df_id'] == '77') {
			$EmployeeName = $val['EmployeeName'];
			$WEF = $val['WEF'];
			$WEF = dateFormatu($WEF);
			$locationid = $loc = $val['location'];
			$designation = $val['Designation'];
			$Gender = strtolower($val['Gender']);
			if ($Gender == 'female') {
				$Gender = 'Ms';
			} else if ($Gender == 'male') {
				$Gender = 'Mr';
			} else {
				$Gender = '';
			}
			$emailid = $val['emailid'];
			$emailid = strtolower($emailid);
			$filename = $EmployeeID . "_Promotion.pdf";

			$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";

			$myDB = new MysqliDb();
			$location_array = array();
			$Locationresult = $myDB->query($Locationquery);
			//var_dump($Locationresult) ;exit;
			if ($loc == "1" || $loc == "2") {
				$target_dir = ROOT_PATH . "promotion_pdf/";
			}
			if ($loc == "3") {

				$target_dir = ROOT_PATH . "Meerut/promotion_pdf/";
			} else if ($loc == "4") {

				$target_dir = ROOT_PATH . "Bareilly/promotion_pdf/";
			} else if ($loc == "5") {
				$target_dir = ROOT_PATH . "Vadodara/promotion_pdf/";
			} else if ($loc == "6") {
				$target_dir = ROOT_PATH . "Manglore/promotion_pdf/";
			} else if ($loc == "7") {
				$target_dir = ROOT_PATH . "Bangalore/promotion_pdf/";
			} else if ($loc == "8") {
				$target_dir = ROOT_PATH . "Nashik/promotion_pdf/";
			} else if ($loc == "9") {
				$target_dir = ROOT_PATH . "Anantapur/promotion_pdf/";
			} else if ($loc == "10") {
				$target_dir = ROOT_PATH . "Gurgaon/promotion_pdf/";
			} else if ($loc == "11") {
				$target_dir = ROOT_PATH . "Hyderabad/promotion_pdf/";
			}
			if (!is_dir($target_dir)) {
				@mkdir($target_dir, 0777, true);
			}
			//$WEF1 = substr($WEF, -4);
			///// pdf creation
			//$pdf1="<p>Date:".$dateformat."</p>";
			$pdf2 = "<h3>PROMOTION LETTER</h3></br>";
			$pdf = "<table>
		<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br><br>
		<tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr>
		<tr><td><b>Employee Code : </b>" . $EmployeeID . "</td></tr></br></br></table>
	
		<p>Consequent to the performance review during the ";
			$pdf = $pdf . substr($WEF, -4);
			$pdf = $pdf . ", we are pleased to inform you that w.e.f. ";
			$pdf = $pdf . dateFormatu1(date('j-F', strtotime($val['WEF'])));
			$pdf = $pdf . " you are promoted to the position of ";
			$pdf = $pdf . $designation;
			$pdf = $pdf . ".</p>
			<p> All other terms and condition of appointment remain the same.</p>
			<p>You are requested to maintain full confidentiality of this letter. </p>
			<p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute your best to the company goals and objectives.</p>
			
			<p>Congratulations and best wishes.</p>
			<p>Yours truly,</p>
			<p><b>For Cogent E Services Ltd.</b></p></br><br>";
			//echo $pdf;
			$pdf4 = "<p>(S.K Garg)</p><br>
				<p><b>Authorized Signatory</b></p>";
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
			$filename = $EmployeeID . "_Promotion.pdf";
			$path = $target_dir . $filename;
			$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

			$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$tcpdf->SetTitle('Cogent|Promotion Checklist');

			$tcpdf->SetMargins(10, 10, 10, 10);

			$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$tcpdf->setPrintHeader(false);
			$tcpdf->setPrintFooter(false);
			$tcpdf->setListIndentWidth(3);

			$tcpdf->SetAutoPageBreak(TRUE, 11);

			$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$tcpdf->AddPage();

			$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 180, 30, 15, 'JPEG'); //sig 

			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); // Cogent Logo

			/*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
				if ('AE' == substr($EmployeeID, 0, 2)) {
					$tcpdf->Image('../Style/images/client_logo.jpg', 140, 5, 60, 30, 'JPG'); //logo right Aurum
				} else {
					$tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
				}
			} elseif ($locationid == "3") {
				if ('OC' == substr($EmployeeID, 0, 2)) {
					$tcpdf->Image('../Style/images/client_logo.png', 140, 5, 60, 30, 'PNG'); //logo right Orium
				} else {
					$tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
				}
			}*/

			$tcpdf->SetFont('times', '', 10.5);
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdf1, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdf2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 200, $pdf4, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

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
				$mail->setFrom(EMAIL_FROM,  'Cogent | Promotion Letter');
				$mail->AddAddress($emailid);

				$mail->Subject = "Candidate Promotion Letter";
				$mail->isHTML(true);
				$msg2 = 'Dear ' . $EmployeeName . ',<br/><br/>
			    Please find here with the attached your Promotion letter.';
				$mail->Body = $msg2;
				$mymsg = '';
				$response = '';
				$mail->AddAttachment($path);
				if (!$mail->send()) {
					$response =  'Mailer Error:' . $mail->ErrorInfo;
					echo  'Mailer Error:' . $mail->ErrorInfo;
				} else {
					$response =  'Mail Send successfully';

					$updateFlag = "update tbl_promotion set flag=1 where employeeid='" . $EmployeeID . "';";
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
				$response =  " Your emailid is not exist.";
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
	<span id="PageTittle_span" class="hidden">PROMOTION LETTER</span>
	<div class="pim-container" id="div_main">
		<div class="form-div">
			<p style="text-align: right">
				<?php
				if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8" || $locationid == "9") {
					if ('AE' == substr($EmployeeID, 0, 2)) {
				?>
						<img src="../Style/images/client_logo.JPG" style="width: 200px;height: 70px;" />
					<?php
					} else { ?>
						<img src="../Style/images/newLogo cogent.jpg" style="width: 200px;height: 50px;" />
						<?php }
				} else {

					if ($locationid == "3") {
						if ('OC' == substr($EmployeeID, 0, 2)) {
						?>
							<img src="../Style/images/client_logo.png" style="width: 200px;height: 70px;" />
						<?php 	}
					} else {
						?>
						<img src="../Style/images/newLogo cogent.jpg" style="width: 200px;height: 50px;" />
				<?php
					}
				}

				?>
			</p><br />
			<h4 style="text-align: center"><u>PROMOTION LETTER</u></h4>
			<div class="schema-form-section ">
				<p><b>Date : </b> <?php echo $dateformat; ?></p><br><br>
				<P><b><?php echo $Gender; ?> : </b> <?php echo $EmployeeName; ?></P>
				<P><b>Employee Code : </b> <?php echo $EmployeeID; ?></P></br>

				<p>Consequent to the performance review during the <?php echo substr($WEF, -4); ?>, we are pleased to inform you that w.e.f. <?php echo $WEF; ?> you are promoted to the position of <?php echo $designation; ?>.</p>
				<p> All other terms and condition of appointment remain the same.</p>
				<p>You are requested to maintain full confidentiality of this letter. </p>
				<p>We look forward to a long and fruitful mutual association and are confident that you will continue to contribute your best to the company goals and objectives.</p>
				<p>Salary review date will be as per your appraisal cycle only.</p>
				<p>Congratulations and best wishes.</p>
				<p>Yours truly,</p>
				<p><b>For Cogent E Services Ltd.</b></p></br><br>
				<p><img src="../Style/img/sk_sign.jpg" style="width: 120px;height: 70px;" /></p>
				<p>(S.K Garg)</p>
				<p><b>Authorized Signatory</b></p>
				<br /><br />
				<p style="text-align: center"><u>
						<?php
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
						?>

					</u></p><br />
			</div>
		</div>
	</div>
</div>