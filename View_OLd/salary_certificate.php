<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '0');

require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

// include_once("../Services/sendsms_API1.php");
require('../TCPDF/tcpdf.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$btn_view = isset($_POST['btn_view']);
if ($btn_view) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$employee_id = clean($_SESSION['__user_logid']);

		$Getinfo = "select distinct t1.EmployeeID,case when upper(t5.Gender)='FEMALE' then 'Ms.' else 'Mr.' end as Gender,case when upper(t5.Gender)='FEMALE' then 'Her' else 'His' end as Gender1,t4.function from employee_map t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id join function_master t4 on t3.function_id=t4.id join personal_details t5 on t1.EmployeeID=t5.EmployeeID where t1.EmployeeID=?";
		//$results = $myDB->query($Getinfo);
		$selectQury = $conn->prepare($Getinfo);
		$selectQury->bind_param("s", $employee_id);
		$selectQury->execute();
		$results = $selectQury->get_result();
		$gender .= '';
		$gender1 .= '';
		$function .= '';
		if ($results->num_rows > 0) {
			foreach ($results as $key => $value) {
				$gender .= $value['Gender'];
				$gender1 .= $value['Gender1'];
				$function .= $value['function'];
			}
		}

		// $gender = $results[0]['Gender'];
		// $gender1 = $results[0]['Gender1'];
		// $function = $results[0]['function'];

		if ((cleanUserInput($_POST['months']) == 'one')) {
			$month1 = date('Y-m-01', strtotime('-1 month'));
			//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'  and  cast(concat(year,'-',month,'-1') as date) between '" . $month1 . "' and '" . $month1 . "' ";
			$GetData = "select * from salary_certificate where Empid=? order by concat(year,month) desc limit 1";
			$sel = $conn->prepare($GetData);
			$sel->bind_param("s", $employee_id);
			$sel->execute();
			// $resultsE = $sel->get_result();
		}

		if ((cleanUserInput($_POST['months']) == 'three')) {
			$month1 = date('Y-m-01', strtotime('-1 month'));
			$month3 = date('Y-m-01', strtotime('-3 month'));
			//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'  and  cast(concat(year,'-',month,'-1') as date) between '" . $month3 . "' and '" . $month1 . "'";
			$GetData = "select * from salary_certificate where Empid=? order by concat(year,month) desc limit 3";
			$sel = $conn->prepare($GetData);
			$sel->bind_param("s", $employee_id);
			$sel->execute();
			// $resultsE = $sel->get_result();
		}

		if ((cleanUserInput($_POST['months']) == 'six')) {
			$month1 = date('Y-m-01', strtotime('-1 month'));
			$month6 = date('Y-m-01', strtotime('-6 month'));
			//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'and  cast(concat(year,'-',month,'-1') as date) between '" . $month6 . "' and '" . $month1 . "' ";
			$GetData = "select * from salary_certificate where Empid=? order by concat(year,month) desc limit 6";
			$sel = $conn->prepare($GetData);
			$sel->bind_param("s", $employee_id);
			$sel->execute();
			// $resultsE = $sel->get_result();
		}

		function dateFormatu($data)
		{
			return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date("M Y", strtotime($data));
		}
		$dateformat = dateFormatu($date = date('Y-m-d'));
		$resultsE = $sel->get_result();

		// $resultsE = $myDB->query($GetData);
		// print_r($resultsE);
		// die;



		// echo "<pre>";
		// print_r($val);
		// die;
		//$year = $val['year']; 
		//$month = $val['month'];
		//$ctc = $val['ctc'];
		//$take_home = $val['take_home'];
		//$netpay = $val['net_paysalary'];
		foreach ($resultsE as $val) {
			// echo $val['EmpName'];
			// die;
			$EmployeeName = $val['EmpName'];
			$designation = $val['designation'];
		}
		if (substr($employee_id, 0, 2) == 'AE' || substr($employee_id, 0, 2) == 'RS' || substr($employee_id, 0, 3) == 'OCM' || substr($employee_id, 0, 3) == 'RSM') {
			$companyname = 'Red Stone Consulting';
			$company = 'Redstone';
			$companyadd = 'Red Stone Consulting, 53, Madhav Kunj, Pratap Nagar, Agra - 282010, India <br> Website: https://redstonec.in,Email: contact@redstonec.in';
		} else {
			$companyname = 'Cogent E-Services Ltd.';
			$company = 'Cogent';
			$companyadd = 'Cogent E Services Limited C-100, Sector 63 Noida - 201301,India<br> Website : www.cogenteservices.com';
		}

		$target_dir = ROOT_PATH . "salarypdf/";
		if (!is_dir($target_dir)) {
			@mkdir($target_dir, 0777, true);
		}

		$filename = $employee_id . "_salary_certificate.pdf";
		$logo = '<img src="../Style/images/Cogent.png" style="width:220px;height: 100px;"/>';
		$dates = "<table>
        <tr><td><b>Date : </b>" . $dateformat . "</td></tr><br></table>";
		$pdfheading = "<h4><u>TO WHOM SO EVER IT MAY CONCERN</u></h4></br>";

		$page1 .= '<p>This is to certify that <b>' . $gender . ' ' . $EmployeeName . '</b> is working with ' . $companyname . ' As <b>' . $designation . ' ' . $function . '</b>. ' . $gender1 . ' monthly salary in the last ' . $_POST['months'] . ' months has been :-
    <br><br></p>
	<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#cccccc" align="center">	
	<th width="100">Year</th>
	<th width="100">Month</th>
	<th width="100">CTC</th>
	<th width="100">Take Home</th>
	<th width="160">Payable Salary</th>
	</tr>';
		if ($resultsE->num_rows > 0) {
			// echo count($resultsE);
			// echo $resultsE->num_rows;
			// die;
			foreach ($resultsE as $ValueS) {
				//for ($sal = 0; $sal < $resultsE->num_rows; $sal++) {

				$month = date('M', mktime(0, 0, 0, $ValueS['month'], 10)) . ' - ' . substr($ValueS['year'], 2, 2);
				// echo $ValueS[$sal]['year'];
				// die;
				$page1 .= '<tr align="center">
			<td>' . $ValueS['year'] . '</td>
			<td>' . $month . '</td>
			<td>' . (int)$ValueS['ctc'] . '</td>
			<td>' . (int)$ValueS['take_home'] . '</td>
			<td>' . (int)$ValueS['net_paysalary'] . '</td>
		</tr>';
				//	}
			}
		}
		// if (count($resultsE) > 0) {
		// 	for ($sal = 0; $sal < count($resultsE); $sal++) {
		// 		$month = date('M', mktime(0, 0, 0, $resultsE[$sal]['month'], 10)) . ' - ' . substr($resultsE[$sal]['year'], 2, 2);
		// 		$page1 .= '<tr align="center">

		// 	<td>' . $resultsE[$sal]['year'] . '</td>
		// 	<td>' . $month . '</td>
		// 	<td>' . (int)$resultsE[$sal]['ctc'] . '</td>
		// 	<td>' . (int)$resultsE[$sal]['take_home'] . '</td>
		// 	<td>' . (int)$resultsE[$sal]['net_paysalary'] . '</td>
		// </tr>';
		// 	}
		// }

		$page1 .= "</table><br><br>
<p>This certificate is being issued to him for the purpose of verification only. $company has no liability towards payments against the loan or whatsoever.</p>";

		$page2 = "
    <p>For $companyname </p></br>";
		$sign = '<img src="../Style/img/sk_sign.jpg" style="width:100px;height: 50px;"/>';

		$page3 = "<p>( S. K Garg )<br>
		Authorized Signatory</p>
		<br><br><br><br>";

		$pdfhr = '<hr size="2" width="100%" align="center" style="border-color:red;">';

		$pdfaddress = '<p style="color:#958d8d;text-align:center;font-size:12px;margin:15px 15px;">' . $companyadd . '
      </p>';

		//echo $page1;
		$path = $target_dir . $filename;

		$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
		$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$tcpdf->SetTitle('Cogent|salary_certificate Checklist');
		$tcpdf->SetMargins(10, 10, 10, 10);
		$tcpdf->setCellPaddings(7, 7, 7, 7);
		$tcpdf->setCellHeightRatio(1.4);
		$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$tcpdf->setPrintHeader(false);
		$tcpdf->setPrintFooter(false);
		$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$tcpdf->AddPage();


		$tcpdf->SetFont('times', '', 10);

		if (substr($employee_id, 0, 2) == 'AE' || substr($employee_id, 0, 2) == 'RS' || substr($employee_id, 0, 3) == 'OCM' || substr($employee_id, 0, 3) == 'RSM') {
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		} else {
			//$tcpdf->Image('../Style/images/cogent-logo.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
			$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 150, $y = 0, $logo, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		}
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 150, $y = 30, $dates, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 40, $pdfheading, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 20, $y = 60, $page1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '', $autopadding = true, $spacing = +0.254);
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 20, $y = 150, $page2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '', $autopadding = true);
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 20, $y = 155, $sign, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 20, $y = 170, $page3, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 254, $pdfhr, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 260, $pdfaddress, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
		$tcpdf->Output($path, 'F');



		$selectEmailArray = "select emailid from contact_details a inner Join salary_certificate b on a.EmployeeID=b.Empid where  Empid=? limit 1";
		$SelecT = $conn->prepare($selectEmailArray);
		$SelecT->bind_param("s", $employee_id);
		$SelecT->execute();

		$resuLT = $SelecT->get_result();
		$row = $resuLT->fetch_row();
		$emailid = clean($row[0]);
		//$value = $row ? $row[0] : "test";
		//echo '<script>alert("' . $value . '")</script>';
		// echo $select =$myDB->query("select emailid from contact_details a inner Join salary_certificate b on a.EmployeeID=b.Empid where  Empid='" . $employee_id . "' limit 1");
		// die;
		//echo $email = $resuLT[0]['emailid'];
		//print_r($resuLT);
		//die;

		// foreach ($resuLT as $VAL) {
		// 	$emailid = $VAL['emailid'];
		// }
		// $mysql_error = $myDB->getLastError();
		// $rowCount = $myDB->count;

		// if ($emailid != "") {
		// 	$mail = new PHPMailer;
		// 	$mail->isSMTP();
		// 	$mail->Host = EMAIL_HOST;
		// 	$mail->SMTPAuth = EMAIL_AUTH;
		// 	$mail->Username = EMAIL_USER;
		// 	$mail->Password = EMAIL_PASS;
		// 	$mail->SMTPSecure = EMAIL_SMTPSecure;
		// 	$mail->Port = EMAIL_PORT;
		// 	$mail->setFrom(EMAIL_FROM,  'Cogent | Salary Certificate');
		// 	$mail->AddAddress($emailid);
		// 	$mail->Subject = "Salary Certificate";
		// 	$mail->isHTML(true);
		// 	$msg2 = "Dear " .  $gender . " " . $EmployeeName . ',<br/><br/>
		// 				Please find  attached your Salary Certificate.</br><br/>
		// 				Thanks <br/> Cogent ';
		// 	$mail->Body = $msg2;
		// 	$mymsg = '';
		// 	$response = '';
		// 	$mail->AddAttachment($path);
		// 	if (!$mail->send()) {
		// 		$response =  'Mailer Error:' . $mail->ErrorInfo;
		// 		// echo  'Mailer Error:'. $mail->ErrorInfo;
		// 		echo "<script>$(function(){ toastr.error('Something went wrong. Please try after some time.') });</script>";
		// 	} else {
		// 		$response =  'Mail Send successfully';
		// 		echo "<script>$(function(){ toastr.success('Mail Send successfully on your email address- ' . $emailid . ') });</script>";
		// 	}
		// } else {
		// 	$response =  "Your Email ID does not exist.";
		// 	echo "<script>$(function(){ toastr.error('" . $response . "') });</script>";
		// }
		if ($emailid != "") {
			//insert data
			$dt = new datetime();
			$dt = $dt->format('Y-m-d H:i:s');
			$mnth = cleanUserInput($_POST['months']);
			$reason = cleanUserInput($_POST['msg']);
			$flag = 0;
			$Insertdata = "insert into salary_certificate_report (EmployeeID,month, reason,per_email_id,created_at,flag)values(?,?,?,?,?,?);";
			$stmt = $conn->prepare($Insertdata);
			$stmt->bind_param("sssssi", $employee_id, $mnth, $reason, $emailid, $dt, $flag);
			$insert = $stmt->execute();
			if ($stmt->affected_rows === 1) {
				echo "<script>$(function(){ toastr.success('Your request has been submitted. Email will be received within 1-2 hours on your Email Address ($emailid)') });</script>";
			}
		} else {
			$response =  "Your Email ID does not exist.";
			echo "<script>$(function(){ toastr.error('" . $response . "') });</script>";
			$dt = new datetime();
			$dt = $dt->format('Y-m-d H:i:s');
			$mnth = $_POST['months'];
			$reason = $_POST['msg'];
			$flag = 1;
			$Insertdata = "insert into salary_certificate_report (EmployeeID,month, reason,response,created_at,flag)values(?,?,?,?,?,?);";
			$stmt = $conn->prepare($Insertdata);
			$stmt->bind_param("sssssi", $employee_id, $mnth, $reason, $response, $dt, $flag);
			$insert = $stmt->execute();
		}
	}
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Salary Certificate</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Salary Certificate</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class=" bymonth">
						<div class="input-field col s6 m6 8">
							<div class="form-group">
								<select class="form-control" name="months" id="months">
									<option value="NA">Select Month</option>
									<option value="one">One Month</option>
									<option value="three">Three Month</option>
									<!--<option value="six">Six Month</option>-->
								</select>
								<label for="months" class="active-drop-down active">Month</label>
							</div>
						</div>
					</div>
					<div class="input-field col s6 m6 8">
						<label for="msg">Reason For Salary Certificate</label>
						<input type="text" class="form-control" name="msg" id="msg" />
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Submit</button>
					</div>
				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {

		$('#btn_view').on('click', function() {
			var validate = 0;
			var alert_msg = '';

			if ($('#months').val() == 'NA') {
				$('#months').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanmonths').length == 0) {
					$('<span id="spanmonths" class="help-block">Required *</span>').insertAfter('#months');
				}
				validate = 1;
			}
			if ($('#msg').val() == '') {
				$('#msg').addClass('has-error');
				if ($('#spanmsg').length == 0) {
					$('<span id="spanmsg" class="help-block"></span>').insertAfter('#msg');
				}
				$('#spanmsg').html('Required');
				validate = 1;

			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});

	});

	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>