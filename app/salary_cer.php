<?php

require_once(__dir__ . '/../Config/init.php');
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
require('../TCPDF/tcpdf.php');
ini_set('display_errors', '0');
//print_r($_SESSION);
// $page=''; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Salary Certificate</title>

	<script src="https://demo.cogentlab.com/erpm/Script/jquery.js"></script>
	<script src="https://demo.cogentlab.com/erpm/Script/toastr-master/build/toastr.min.js"></script>

	<link rel="stylesheet" href="https://demo.cogentlab.com/erpm/Script/toastr-master/build/toastr.css" />

	<link rel="icon" type="image/png" href="https://demo.cogentlab.com/erpm/Style/img/favicon.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<!-- Theme style -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> -->





</head>
<!-- /.card -->
<!-- <style>
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: 0.25 rem;
    }
</style> -->
<style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
<body>
	<div class="container">
		<!-- <div class="row"> -->
		<div class="card card-info" >
			<div class="card-header">
				<h3 class="card-title" style="text-align:center;float:none">Salary Certificate</h3>
			</div>
			<form method="post" id="forms">
				<div class="d-flex-column align-items-center">
					<div class="card-body col-md-12">
						<label for="months">Month</label>
						<select class="form-control" name="months" id="months" required>
							<option value="">Select Month</option>
							<option value="one">One Month</option>
							<option value="three">Three Month</option>
							<!--<option value="six">Six Month</option>-->
						</select>
					</div>
					<div class="card-body col-md-12">
						<label for="msg">Reason For Salary Certificate</label>
						<input type="text" class="form-control" name="msg" id="msg" required />
					</div>
					<div class="card-body p-0" style="text-align:center;">
						<!-- <input type="submit" value="Submit" name="btn_view" id="btn_view" />  -->
						<button type="submit" class="btn btn-success" name="btn_view" id="btn_view">Submit</button>
					</div><br>

				</div>
			</form>
		</div>
	</div>
	<!-- </div> -->
</body>


<?php
if (isset($_POST['btn_view'])) {
	// print_r($_POST);
	// die;
	$myDB = new MysqliDb();
	$employee_id = $_GET['id'];
	$Getinfo = "select t1.EmployeeID,case when upper(t5.Gender)='FEMALE' then 'Ms.' else 'Mr.' end as Gender,case when upper(t5.Gender)='FEMALE' then 'Her' else 'His' end as Gender1,t4.function from employee_map t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id join function_master t4 on t3.function_id=t4.id join personal_details t5 on t1.EmployeeID=t5.EmployeeID where t1.EmployeeID='" . $employee_id . "'";

	$results = $myDB->query($Getinfo);

	$gender = $results[0]['Gender'];
	$gender1 = $results[0]['Gender1'];
	$function = $results[0]['function'];

	if (($_POST['months'] == 'one')) {
		$month1 = date('Y-m-01', strtotime('-1 month'));
		//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'  and  cast(concat(year,'-',month,'-1') as date) between '" . $month1 . "' and '" . $month1 . "' ";
		$GetData = "select * from salary_certificate where Empid='" . $employee_id . "' order by concat(year,month) desc limit 1";
		$myDB = new MysqliDb();
	}

	if (($_POST['months'] == 'three')) {
		$month1 = date('Y-m-01', strtotime('-1 month'));
		$month3 = date('Y-m-01', strtotime('-3 month'));
		//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'  and  cast(concat(year,'-',month,'-1') as date) between '" . $month3 . "' and '" . $month1 . "'";
		$GetData = "select * from salary_certificate where Empid='" . $employee_id . "' order by concat(year,month) desc limit 3";
		$myDB = new MysqliDb();
	}

	if (($_POST['months'] == 'six')) {
		$month1 = date('Y-m-01', strtotime('-1 month'));
		$month6 = date('Y-m-01', strtotime('-6 month'));
		//$GetData = "select * from salary_certificate where Empid='" . $employee_id . "'and  cast(concat(year,'-',month,'-1') as date) between '" . $month6 . "' and '" . $month1 . "' ";
		$GetData = "select * from salary_certificate where Empid='" . $employee_id . "' order by concat(year,month) desc limit 6";
		$myDB = new MysqliDb();
	}

	function dateFormatu($data)
	{
		return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date("M Y", strtotime($data));
	}
	$dateformat = dateFormatu($date = date('Y-m-d'));
	$resultsE = $myDB->query($GetData);

	foreach ($resultsE as $val) {
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
	$logo = '<img src="../Style/images/cogent.png" style="width:220px;height: 100px;"/>';
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

	if (count($resultsE) > 0) {
		for ($sal = 0; $sal < count($resultsE); $sal++) {
			$month = date('M', mktime(0, 0, 0, $resultsE[$sal]['month'], 10)) . ' - ' . substr($resultsE[$sal]['year'], 2, 2);
			$page1 .= '<tr align="center">
		 
		<td>' . $resultsE[$sal]['year'] . '</td>
		<td>' . $month . '</td>
		<td>' . (int)$resultsE[$sal]['ctc'] . '</td>
		<td>' . (int)$resultsE[$sal]['take_home'] . '</td>
		<td>' . (int)$resultsE[$sal]['net_paysalary'] . '</td>
	</tr>';
		}
	}

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

	$myDB = new MysqliDb();
	$select_email_array = $myDB->rawQuery("select emailid from contact_details a inner Join salary_certificate b on a.EmployeeID=b.Empid where  Empid='" . $employee_id . "' limit 1");
	// echo "select emailid from contact_details a inner Join salary_certificate b on a.EmployeeID=b.Empid where  Empid='" . $employee_id . "' limit 1";
	// die;
	$emailid = $select_email_array[0]['emailid'];
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;
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
	// 		//echo "<script>$(function(){ toastr.error('Something wrong. Please try after some time.') });</script>";
	// 		echo "<script type='text/javascript'>toastr.error('Something went wrong.Please try after some time.')</script>";

	// 	} else {
	// 		$response =  'Mail Send successfully on your email address- ' . $emailid . ' ';
	// 		// echo "'.$response.'";
	// 		//echo "<script>$(function(){ toastr.success('" . $response . "') });</script>";
	// 		echo "<script type='text/javascript'>toastr.success('$response' )</script>";

	// 	}
	// } else {
	// 	$response =  "Your Email ID does not exist.";
	// 	echo "<script type='text/javascript'>toastr.success('$response')</script>";

	// }

	if ($emailid != "") {
		//insert data
		$dt = new datetime();
		$dt = $dt->format('Y-m-d H:i:s');
		$mnth = $_POST['months'];
		$reason = $_POST['msg'];
		$myDB = new MysqliDb();
		$Insertdata = "insert into salary_certificate_report (EmployeeID,month, reason,per_email_id,created_at,flag)values('" . $employee_id . "','" . $mnth . "','" . $reason . "','" . $emailid . "','" . $dt . "',0);";
		$myDB = new MysqliDb();
		$resu = $myDB->rawQuery($Insertdata);
		$error = $myDB->getLastError();
		echo "<script type='text/javascript'>toastr.success('Your request has been submitted. Email will be received within 1-2 hours on your Email Address ($emailid)')</script>";
	} else {
		$response =  "Your Email ID does not exist.";
		echo "<script>$(function(){ toastr.error('" . $response . "') });</script>";

		$dt = new datetime();
		$dt = $dt->format('Y-m-d H:i:s');
		$mnth = $_POST['months'];
		$reason = $_POST['msg'];
		$myDB = new MysqliDb();
		$Insertdata = "insert into salary_certificate_report (EmployeeID,month, reason,response,created_at,flag)values('" . $employee_id . "','" . $mnth . "','" . $reason . "','" . $response . "','" . $dt . "',1);";
		$myDB = new MysqliDb();
		$resu = $myDB->rawQuery($Insertdata);
		$error = $myDB->getLastError();
	}
}

?>

<script>
	$(document).ready(function() {
		$("#msg").validate();
	});
</script>