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

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$dateformat = date('y-m-d');
$EmployeeID = $EmployeeName = $locationid = $dol = $rsnofleaving = $Gender = $doj = $emailid = $designation = $loc = '';


$GetData = "select c.emailid, e.EmployeeID ,case when des.ID in (9,12,33,34,35,36) then des.Designation else concat(des.Designation,' - ', fun.function) end as designation,ex.disposition, p.EmployeeName ,p.location,e.dateofjoin DOJ,p.Gender,p.MarriageStatus, DATE_FORMAT(ex.dol,'%Y-%m-%d') as dol ,ex.rsnofleaving ,ex.createdon,st.ctc from exit_emp ex left join  `employee_map` e on ex.EmployeeID=e.EmployeeID left JOIN `personal_details` p ON ((p.`EmployeeID` = e.`EmployeeID`)) LEFT JOIN `df_master` d ON ((d.`df_id` =e.`df_id`)) LEFT JOIN `designation_master` des ON ((des.`ID` = d.`des_id`)) left join function_master fun on d.function_id = fun.id left join contact_details c on c.`EmployeeID` = e.`EmployeeID` left join salary_details st on st.EmployeeID=e.EmployeeID where  cast(DATE_SUB(ex.createdon, INTERVAL -3 DAY) as date)=cast(now() as date) and ex.disposition in ('RES', 'IR') and e.dateofjoin < cast(date_sub(now(), interval 90 day) as date) and e.EmployeeID='RS102224910'";

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
		$EmployeeName = ucwords($val['EmployeeName']);
		$dol = $val['dol'];
		$dol = dateFormatu($dol);
		$rsnofleaving = $val['rsnofleaving'];
		$locationid = $loc = $val['location'];
		$doj = $val['DOJ'];
		$doj = dateFormatu($doj);
		$designation = $val['designation'];
		$MarriageStatus = $val['MarriageStatus'];
		$Gender = $val['Gender'];
		$emailid = $val['emailid'];


		if ($Gender == 'Female') {
			if ($MarriageStatus = 'Single') {
				$title = 'Ms.';
			} else {
				$title = 'Mrs.';
			}
		} else {
			$title = 'Mr.';
		}

		if (substr($EmployeeID, 0, 2) == 'AE' || substr($EmployeeID, 0, 2) == 'RS' || substr($EmployeeID, 0, 3) == 'OCM' || substr($EmployeeID, 0, 3) == 'RSM') {
			$companyname = 'Red Stone Consulting';
		} else {
			$companyname = 'Cogent E Services Ltd.';
		}


		$ctc = $val['ctc'];
		$annual = number_format($ctc * 12, 0);
		$annual_word = number_format($ctc * 12, 0, '.', '');
		if ($loc != '') {


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
			if (!is_dir($target_dir)) {
				@mkdir($target_dir, 0777, true);
			}
		}
		///// pdf creation	
		$pdf = "<h4>RELIEVING & EXPERIENCE LETTER</h4>";
		$pdf1 = "<table>
	             <tr><td>Date: " . dateFormatu($dateformat) . "</td></tr><br><br>
	             <tr><td><b>" . $title . "</b>  " . $EmployeeName . "</td></tr>
				 <tr><td><b>Employee Code: </b>" . $EmployeeID . "</td></tr>
	             </table><br>
				 <p>Further to your resignation from the company, we wish to inform you that you are relieved from your services effective close of working hours on " . $dol . ". While we regret your decision to leave the company, we wish you a successful career ahead.</p>

				 <p>We hereby confirm the following details of your employment with " . $companyname . ".<br><br>
				 <b> Designation: " . $designation . " </b><br><br>
				 <b> Date of Joining:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . $doj . " <br>
				 <b> Last working day:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . $dol . " <br>
				 <b> Annual CTC:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INR " . $annual . " (" . AmountInWords(($annual_word)) . " Only) </p>
 
				 <p>There are no financial dues pending towards you from the company.</p>
				 <p>We wish you all the best in your future endeavors.</p> <br /><br />";

		$pdftruly = "<p>Yours truly,<br>
			<b>For " . $companyname . "</b></p></br>";
		$pdf2 = "<p>(S.K Garg)</p>
				<p><b>(Authorized Signatory)</b></p>";

		if ($companyname == 'Red Stone Consulting') {
			$location = 'Red Stone Consulting, 53, Madhav Kunj, Pratap Nagar, Agra - 282010, India <br> Website: https://redstonec.in/';
		} else {
			$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId =?";

			$location_array = array();
			// $Locationresult = $myDB->query($Locationquery);
			$selectQury = $conn->prepare($Locationquery);
			$selectQury->bind_param("i", $locationid);
			$selectQury->execute();
			$Locationresult = $selectQury->get_result();
			if ($Locationresult->num_rows > 0) {
				foreach ($Locationresult as $val) {

					if ($locationid == "1" || $locationid == "2") {

						if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
							$location = '';
							$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
						}
					} else {
						if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
							$location = '';
							$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
						} else {
							$location = '';
							$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
						}
					}
				}
			}
		}


		//$pdf3 = '<p style="text-align: center"><u>' . $location . '</u></p>';
		$pdf3 = "
	        <style>
	        p{
	            color:#958d8d;
	            text-align:center;
	            font-size:13px;
	            margin:15px 15px;
	        }
	        </style>
	            <br><br><hr><p>$location </p>";


		$filename = $EmployeeID . "_RelievingExperience.pdf";
		if ($target_dir != '') {
			$path = $target_dir . $filename;
		}



		$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$tcpdf->SetTitle('Cogent|Relieving & Experience Letter');

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

		$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 180, 30, 15, 'JPEG'); //SIG

		if ($companyname == "Cogent E Services Ltd.") {
			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
		} else {
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		}

		/*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
			if ('AE' == substr($EmployeeID, 0, 2)) {
				$tcpdf->Image('../Style/images/client_logo.JPG', 140, 5, 60, 30, 'JPG'); //logo right Aurum
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

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 170, $pdftruly, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 200, $pdf2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdf3, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');

		if ($path != '') {
			$tcpdf->Output($path, 'F');
		}

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
			$mail->setFrom(EMAIL_FROM,  'Cogent | Relieving & Experience Letter');
			//$mail->AddAddress('kavya.singh@cogenteservices.com');
			$mail->AddAddress($emailid);
			$mail->addBCC('vijayram.yadav@cogenteservices.com');
			$mail->addBCC('sanoj.pandey@cogenteservices.com');
			$mail->Subject = "Candidate Relieving & Experience Letter";
			$mail->isHTML(true);
			$msg2 = $title . " " . $EmployeeName . ',<br/><br/>
		     Please find  attached your Relieving & Experience letter which was proceed on ' . $dol . '.</br><br/>
		     Thanks <br/> Cogent ';
			$mail->Body = $msg2;
			$mymsg = '';
			$response = '';
			$mail->AddAttachment($path);
			// if (!$mail->send()) {
			// 	$response =  'Mailer Error:' . $mail->ErrorInfo;
			// 	echo  'Mailer Error:' . $mail->ErrorInfo;
			// } else {
			// 	$response =  'Mail Send successfully';
			// }
		} else {
			$response =  " $emailid Your emailid is not exist.";
		}

		///////////////end mail

		//insert data
		$dt = new datetime();
		$dt = $dt->format('Y-m-d H:i:s');
		// $myDB = new MysqliDb();
		$Insertrelieving = "insert into releiving_experience_ack (EmployeeID, Mail_response,file_name, email_id ,Created_date,location_id)values(?,?,?,?,?,?);";
		// $myDB = new MysqliDb();
		// $resu = $myDB->rawQuery($Insertrelieving);
		// $error = $myDB->getLastError();
		$insQ = $conn->prepare($Insertrelieving);
		$insQ->bind_param("sssssi", $EmployeeID, $response, $filename, $emailid, $dt, $locationid);
		$insQ->execute();
		$res = $insQ->get_result();
		// if (empty($error)) {
		// 	echo "<script>$(function(){ toastr.success('Successfully Inserted...') });</script>";
		// }
	}
}

function AmountInWords(float $amount)
{
	$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
	// Check if there is any number after decimal
	$amt_hundred = null;
	$count_length = strlen($num);
	$x = 0;
	$string = array();
	$change_words = array(
		0 => '', 1 => 'One', 2 => 'Two',
		3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
		7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
		10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
		13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
		16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
		19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
		40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
		70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
	);
	$here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	while ($x < $count_length) {
		$get_divider = ($x == 2) ? 10 : 100;
		$amount = floor($num % $get_divider);
		$num = floor($num / $get_divider);
		$x += $get_divider == 10 ? 1 : 2;
		if ($amount) {
			$add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
			$amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
			$string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' 
       ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' 
       ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
		} else $string[] = null;
	}
	$implode_to_Rupees = implode('', array_reverse($string));
	$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
	return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
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
	<span id="PageTittle_span" class="hidden">RELIEVING & EXPERIENCE LETTER </span>
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
					<!--<img src="../Style/images/newLogo cogent.jpg" style="text-align: right;width: 200px;height: 50px;"/>-->
				</p></br>
				<h4 style="text-align: center"><u>RELIEVING & EXPERIENCE LETTER</u></h4>

				<p><b>Date:</b><?php echo $dateformat; ?></p><br>
				<P><b><?php echo $title . " </b>" . $EmployeeName . ","; ?></P>
				<P><b>Employee Code:</b> <?php echo $EmployeeID; ?></P><br>

				<p>Further to your resignation from the company, we wish to inform you that you are relieved from your services effective close of working hours on <last date of working>. While we regret your decision to leave the company. We wish you a successful career ahead.</p>

				<p>We hereby confirm the following details of your employment with Cogent E Services Ltd.:</p>
				<p><b> Designation: </b><?php echo $designation; ?></p>
				<p><b> Date of Joining: </b><?php echo $doj; ?></p>
				<p><b> Date of Leaving: </b><?php echo $dol; ?> </p>
				<p><b> Annual CTC: </b><?php echo $ctc; ?></p>

				<p>There are no financial dues pending.</p>
				<p>We wish you all the best for your future endeavors.</p><br><br>



				<p>Yours truly,</p>
				<p><b>For Cogent E-Services Ltd.</b></p><br /><br />
				<p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;" /></p>
				<p><b>(Authorized Signatory)</b></p>

				<p style="text-align: center"><u><?php
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
<?php
//include(ROOT_PATH.'AppCode/footer.mpt'); 
?>