<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
include_once(__dir__ . '/../Services/sendsms_API1.php');
$emailAddress = '';

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$alert_msg = '';
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

if (isset($_POST['btn_inactive'])) {

	foreach ($_POST['txt_check'] as $Employee) {
		$empid = $Employee;
		$remark = $_POST['txt_remark_' . $Employee];
		$empname = $_POST['txt_empname_' . $Employee];
		$myDB = new MysqliDb();
		$rstl = $myDB->rawQuery('call manage_ncns("' . $empid . '","' . $remark . '","' . $_SESSION['__user_logid'] . '")');

		$my_error = $myDB->getLastError();
		$rowCount = $myDB->count;

		if ($rowCount > 0) {

			$msg1 = "Dear " . $empname;
			$msg = $msg1 . ", You are not reporting to office since (Date : " . date('d/m/Y', strtotime("-3 days")) . ") without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by " . date('d/m/Y', strtotime("+1 days")) . " - Cogent E Services";
			$myDB = new MysqliDb();
			$rst_contact = $myDB->rawQuery('select mobile,altmobile,ofc_emailid from contact_details where EmployeeID = "' . $empid . '" limit 1');
			if (!empty($rst_contact[0]['mobile'])) {
				$TEMPLATEID = '1707161526695912794';
				$url = SMS_URL;
				$token = SMS_TOKEN;
				$credit = SMS_CREDIT;
				$sender = SMS_SENDER;
				$message = $msg;
				$number = $rst_contact[0]['mobile'];
				$sendsms = new sendsms($url, $token);
				//$message_id = $sendsms->sendmessage($credit,$sender,$message,$number);
				$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
				$response = $message_id;
				$ResultSMS = $response;

				$lbl_msg = ' SMS : ' . $response;

				//echo 'insert into ncns_sms set employeeid="'.$empid.'", smsstatus="'.$response.'", createdBy="'.$_SESSION['__user_logid'].'"';
			}
			$emailStatus = '';
			if (!empty($rst_contact[0]['ofc_emailid'])) {
				$emailAddress = $rst_contact[0]['ofc_emailid'];
				$Subject_ = 'NCNS Alert' . date('d-m-Y H:i:s');
				$mail = new PHPMailer;
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = EMAIL_HOST;
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;
				$mail->Password = EMAIL_PASS;
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom(EMAIL_FROM, 'EMS:NCNS');
				$mail->AddAddress($emailAddress);
				$mail->Subject = $Subject_;


				$Body .= $msg . "<br><br>Thanks EMS Team";
				$mail->isHTML(true);
				$mail->Body = $Body;
				if (!$mail->send()) {

					$emailStatus = 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					$emailStatus = 'Mail Send successfully.';
				}
			}
			$myDB = new MysqliDb();
			// echo 'insert into ncns_sms set employeeid="'.$empid.'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'", createdBy="'.$_SESSION['__user_logid'].'"';
			$sms_status = $myDB->rawQuery('insert into ncns_sms set employeeid="' . $empid . '", smsstatus="' . addslashes($response) . '",sms_text="' . addslashes($msg) . '",EmailAddress="' . addslashes($emailAddress) . '",emailStatus="' . addslashes($emailStatus) . '", createdBy="' . $_SESSION['__user_logid'] . '"');
			echo "<script>$(function(){ toastr.success('Request to In-active for selected Employee is saved Successfully.'); }); </script>";
		} else {

			echo "<script>$(function(){ toastr.error('Request to In-active for selected Employee is not saved . " . $my_error . "'); }); </script>";
		}
	}
}
?>
<script>
	$(function() {

		// DataTable
		var table = $('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']

			],
			buttons: [


				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,

			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		var table1 = $('#myTable1').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [


				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">NCNS Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>NCNS Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="input-field col s12 m12 right-align ">
					<button type="submit" name="btn_inactive" id="btn_inactive" value="In-Active" class="btn waves-effect waves-green hidden">In-Active</button>
				</div>
				<?php
				$myDB = new MysqliDb();

				$query1 = 'select t1.ncns, wh.EmployeeID,wh.EmployeeName,wh.DOJ,wh.designation,wh.client_name as client_name,wh.Process,wh.sub_process,pd.EmpName as PDEmployeeName from  (select EmployeeID, ROUND ( ( LENGTH(concat(';
				$d1 = intval(date('d', strtotime("-3 day", strtotime("today"))));
				$d2 = intval(date('d', strtotime("-2 day", strtotime("today"))));
				$d3 = intval(date('d', strtotime("-1 day", strtotime("today"))));
				$curr_month = date('n', time());
				$curr_year = date('Y', time());
				$pre_month = date('n', strtotime("-1 month " . date('Y-m-01', time())));
				$pre_year = date('Y', strtotime("-1 month " . date('Y-m-01', time())));
				// echo '3434343434';
				// die;
				//echo $curr_month . '-' . $curr_year . '-' . $pre_month . '-' . $pre_year;
				//die;
				if (intval(date('d', strtotime("today"))) > 3) {
					$day = 'D' . $d1 . ',D' . $d2 . ',D' . $d3;

					$query = $query1 . $day . '))- LENGTH( REPLACE (concat(' . $day . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $curr_month . ' and year=' . $curr_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1)';
					//$query = $query . '))- LENGTH( REPLACE (concat(';

				} else if (intval(date('d', strtotime("today"))) == 1) {
					$day = 'D' . $d1 . ',D' . $d2 . ',D' . $d3;

					$query = $query1 . $day . '))- LENGTH( REPLACE (concat(' . $day . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $pre_month . ' and year=' . $pre_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1)';
				} else if (intval(date('d', strtotime("today"))) == 2) {

					$day = 'D' . $d1 . ',D' . $d2;
					$day1 = 'D' . $d3;

					$query = $query1 . $day . '))- LENGTH( REPLACE (concat(' . $day . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $pre_month . ' and year=' . $pre_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1) union all ' . $query1 . $day1 . '))- LENGTH( REPLACE (concat(' . $day1 . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $curr_month . ' and year=' . $curr_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1)';
				} else if (intval(date('d', strtotime("today"))) == 3) {

					$day = 'D' . $d1;
					$day1 = 'D' . $d2 . ',D' . $d3;

					$query = $query1 . $day . '))- LENGTH( REPLACE (concat(' . $day . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $pre_month . ' and year=' . $pre_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1) union all ' . $query1 . $day1 . '))- LENGTH( REPLACE (concat(' . $day1 . '), "A", "") ) ) / LENGTH("A")) AS NCNS from calc_atnd_master where  month=' . $curr_month . ' and year=' . $curr_year . ') t1 inner join	Active_Emp_Base  wh  on t1.EmployeeID=wh.EmployeeID left join EmpID_Name pd on wh.ReportTo = pd.EmpID where wh.account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")  and ( NCNS>1)';
				}
				//echo $query;
				// die;
				//echo 'select wh.EmployeeID,wh.EmployeeName,wh.DOJ,wh.designation,wh.clientname,wh.Process,wh.sub_process,pd.EmployeeName as PDEmployeeName from whole_details_peremp  wh left outer join personal_details pd on wh.ReportTo = pd.EmployeeID where  account_head = "' . $_SESSION['__user_logid'] . '" and wh.EmployeeID not in (SELECT EmployeeID FROM ncns_cases where status = 0 ) and wh.EmployeeID not in (select EmpID from asset_employee where status="Assigned")';
				//die;
				$chk_task = $myDB->rawQuery($query);
				//	and cm_id not in (28,19,20,21,50,53)
				$my_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if ($rowCount > 0) {
					$table = '<div  class="had-container pull-left row card dataTableInline "><div class=""><table id="myTable" class="data dataTable no-footer row-border centered" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>Remark</th>';
					$table .= '<th>Total</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '</tr></thead><tbody>';

					foreach ($chk_task as $key => $value) {


						if (true) {
							$myDB = new MysqliDb();
							$result_all = $myDB->rawQuery('select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t1 where EmployeeID = "' . $value['EmployeeID'] . '" and  month=' . date('m', time()) . ' and Year =' . date('Y', time()) . ' union all select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t2 where EmployeeID = "' . $value['EmployeeID'] . '" and  month=' . date('m', strtotime("-1 month " . date('Y-m-01', time()))) . ' and Year =' . date('Y', strtotime("-1 month " . date('Y-m-01', time()))));
							//echo $result_all=('select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t1 where EmployeeID = "'.$value['EmployeeID'].'" and  month='.date('m',time()).' and Year ='.date('Y',time()).' union all select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t2 where EmployeeID = "'.$value['EmployeeID'].'" and  month='.date('m',strtotime("-1 month ".date('Y-m-01',time()))).' and Year ='.date('Y',strtotime("-1 month ".date('Y-m-01',time()))));
							$my_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							$result_cur = $result_prev = array();
							if (count($result_all) == 2) {
								$result_prev  = '';
								$result_cur = '';
								if (isset($result_all[0]) && $result_all[0] != "") {
									$result_prev  = $result_all[1];
									$result_cur = $result_all[0];
								}
							} else {
								if (isset($result_all[0]) && $result_all[0] != "") {
									if ((intval(date('Y', time())) ==  intval($result_all[0]['year'])) && (intval(date('m', time())) ==  intval($result_all[0]['month']))) {
										$result_cur = $result_all[0];
									} else {
										$result_prev = $result_all[0];
									}
								}
							}

							$count_prev = $count_abc =  0;
							$a_counter = 0;
							$inactiveThat = 0;
							$counter_check = 0;
							if (count($result_prev) > 0 && $result_prev) {
								$begin  =  new DateTime(date('Y-m-01', strtotime("-1 month " . date('Y-m-01', time()))));
								$end =  new DateTime(date('Y-m-t', strtotime("-1 month " . date('Y-m-01', time()))));

								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

									$col = "D" . intval($i->format('d'));
									$val = $result_prev[$col];
									if ($i->format('Y-m-d') < date('Y-m-d', time())) {
										$val_calc = $val;

										if (intval($i->format('d')) == 1) {
											$val_calc_prev = '-';
										} else {
											$val_calc_prev = $result_prev['D' . (intval($i->format('d')) - 1)];
										}

										if ($i->format('Y-m-d') == $i->format('Y-m-t')) {
											$val_calc_next = '-';
										} else {
											$val_calc_next = $result_prev['D' . (intval($i->format('d')) + 1)];
										}


										if ($val_calc == 'A' || $val_calc == 'LANA' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A' || $val_calc_next == 'LANA' || $val_calc_prev == 'LANA'))) {
											if ($inactiveThat > 0 || $val_calc == 'A' || $val_calc == 'LANA') {
												$count_prev++;
											}
											$inactiveThat++;
											if ($val_calc == 'A' || $val_calc == 'LANA') {
												$counter_check++;
											}
										} elseif ($val_calc == '-' || empty($val_calc) || $val_calc == 'HO') {
										} else {
											$count_prev = 0;
											$inactiveThat = 0;
											$counter_check = 0;
										}

										if ($val_calc == 'A') {
											$a_counter++;
										} else {
											$a_counter = 0;
										}
									}
								}
							}
							if (count($result_cur) > 0 && $result_cur) {
								for ($j = 1; $j <= 31; $j++) {
									if ($j < intval(date('d', time()))) {
										$val_calc = $result_cur['D' . $j];

										if ($j < 31) {
											$val_calc_next = $result_cur['D' . ($j + 1)];
										} else {
											$val_calc_next = '-';
										}

										if ($j > 1) {
											$val_calc_prev = $result_cur['D' . ($j - 1)];
										} else {
											$val_calc_prev = '-';
										}


										if ($val_calc == 'A' || $val_calc == 'LANA' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A' || $val_calc_next == 'LANA' || $val_calc_prev == 'LANA'))) {

											if ($inactiveThat > 0 || $val_calc == 'A' || $val_calc == 'LANA') {
												$count_abc++;
											}
											if ($val_calc == 'A' || $val_calc == 'LANA') {
												$counter_check++;
											}
											$inactiveThat++;
										} elseif ($val_calc == '-' || empty($val_calc) || $val_calc == 'HO') {
										} else {
											$count_abc = 0;
											$count_prev = 0;
											$inactiveThat = 0;
											$counter_check = 0;
										}

										if ($val_calc == 'A' || $val_calc == 'LANA') {
											$a_counter++;
										} else {
											$a_counter = 0;
										}
									}
								}
							}

							$final_counter  = $count_abc + $count_prev;


							if ($counter_check >= 3 || $a_counter >= 3) {
								$table .= '<tr><td><input type="checkbox" name="txt_check[]" id="txt_check_' . $value['EmployeeID'] . '" value ="' . $value['EmployeeID'] . '" onclick="javascript:return checkbox_click();"/><label for="txt_check_' . $value['EmployeeID'] . '"><span></span>' . $value['EmployeeID'] . '</label> </td>';
								$table .= '<td>' . $value['EmployeeName'] . '<input type="hidden" id="txt_empname_' . $value['EmployeeID'] . '" name="txt_empname_' . $value['EmployeeID'] . '" value="' . $value['EmployeeName'] . '"/></td>';
								$table .= '<td style="padding:0px;" ><textarea id="txt_remark_' . $value['EmployeeID'] . '" name="txt_remark_' . $value['EmployeeID'] . '" class="materialize-textarea  materialize-textarea-size " ></textarea></td>';
								$table .= '<td>' . $final_counter . '</td>';
								$table .= '<td>' . $value['DOJ'] . '</td>';
								$table .= '<td>' . $value['designation'] . '</td>';
								$table .= '<td>' . $value['clientname'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['PDEmployeeName'] . '</td>';
								$table .= '</tr>';
							}
						}
					}

					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.info('No Data Found.'" . $my_error . "); }); </script>";
				}


				?>



				<?php

				$myDB = new MysqliDb();

				//$chk_task1=$myDB->rawQuery('select * from ncns_cases left outer join whole_details_peremp on whole_details_peremp.EmployeeID = ncns_cases.EmployeeID left outer join personal_details on personal_details.EmployeeID = ReportTo where account_head = "'.$_SESSION['__user_logid'].'" and ncns_cases.status = 0');

				$chk_task1 = $myDB->rawQuery('select wh.EmployeeID,wh.EmployeeName,nc.remark,wh.DOJ,wh.designation,wh.client_name as clientname,wh.Process,wh.sub_process,pd.EmpName as PDEmployeeName from ncns_cases nc left outer join Active_Emp_Base wh on wh.EmployeeID = nc.EmployeeID left outer join EmpID_Name pd on pd.EmpID = ReportTo where account_head = "' . $_SESSION['__user_logid'] . '" and nc.status = 0');
				$my_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if ($rowCount > 0 && $chk_task1) {
					$table = '<h4 class="hd">NCNS Request sent to HR Head </h4>';
					$table .= '<div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
				<div class=""  >
					<table id="myTable1" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
				<thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>Remark</th>';

					$table .= '<th>DOJ</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '</tr></thead><tbody>';

					foreach ($chk_task1 as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['remark'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['PDEmployeeName'] . '</td>';
						$table .= '</tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					//$alert_msg="<span class='text-danger'>No Data Found  ... ".$my_error." </span>";
					echo "<script>$(function(){ toastr.info('No Data Found." . $my_error . "'); }); </script>";
				}


				?>

			</div>


			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(function() {


		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}

		$('#btn_inactive').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('input[type="checkbox"]:checked').each(function() {

				if ($('#txt_remark_' + $(this).val()).val().length < 50) {
					$('#txt_remark_' + $(this).val()).addClass('has-error');
					validate = 1;
					alert_msg += '<li>Remark should be greater than 50 character for Inactive request.</li>';
				}
			});

			if (validate == 1) {
				/*$('#alert_message').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
	      		
				return false;
				*/

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}

		});


	});

	function checkbox_click() {
		$('input[type="checkbox"]:checked').each(function() {
			/*alert($(this).val());*/
		});

		if ($('input[type="checkbox"]:checked').length > 0) {
			$('#btn_inactive').removeClass('hidden');
		} else {
			$('#btn_inactive').addClass('hidden');
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>