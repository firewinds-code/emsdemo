<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// used for email
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	$clean_u_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_u_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$tcid = "";
$cm_id = "";
$client = "";
$old_process = $new_process = $move_date = $tcid_array = '';
$classvarr = "'.byID'";
$searchBy = '';
$process = '';
$date = date("Y-m-d h:i:s");
$Body = '';
$tableContent = '';
$action = '';
$status = '';
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
$msg = '';
$max_key = "";
$min_key = "";
$updatedBy = $clean_u_logid;
$oh = "";
?>
<input type='hidden' id='account_head' name='account_head' value='<?php echo $updatedBy; ?>'>
<?php
if (isset($_POST['update_status'])) {
	$date = date('d');
	 if ($date >= 25 && $date <= 30) {
	// if ($date >= 1 && $date <= 20) {

		$counttol = 0;
		if (isset($_POST['status']) &&  is_array($_POST['status'])) {
			$status = $_POST['status'];
		}
		//$oh=$_POST['oh'];	
		$cm_id = $_POST['cm_id'];
		$flag = "toRT";
		if (isset($_POST['NRT_Comment']) && is_array($_POST['NRT_Comment'])) {
			$max_key = max(array_keys($_POST['NRT_Comment']));
			$min_key = min(array_keys($_POST['NRT_Comment']));
		}
		$mysql_error = '';
		if (isset($_POST['tcid']) and $max_key != '') {
			$date = date("Y-m-d h:i:s");
			$checked_arr = $_POST['tcid'];
			$count_check = count($status);

			$moveid = "";
			for ($i = $max_key; $i >= $min_key; $i--) {
				if (isset($checked_arr[$i]) && $checked_arr[$i] != "" && $_POST['NRT_Comment'][$i] != "") {
					$empID = $checked_arr[$i];
					$NRT_Comment = addslashes($_POST['NRT_Comment'][$i]);
					$moveid =	$_POST['moveid'][$i];
					if ($status[$i] == 'NRT_Reject') {
						$flag = 'NRTR';
					} else
					if ($status[$i] == 'NRT_Approve') {
						$flag = 'NRTA';
					}

					// $save = "UPDATE tbl_tl2_tl_movement set status='" . $status[$i] . "',flag='" . $flag . "',NRT_updated_on='" . $date . "',NRT_Comment='" . $NRT_Comment . "',NRT_UpdatedBy='" . $updatedBy . "' , Updated_by='" . $updatedBy . "',updated_on='" . $date . "' where  EmployeeID='" . $checked_arr[$i] . "' and id='" . $moveid . "' ";
					$save = "UPDATE tbl_tl2_tl_movement set status='" . $status[$i] . "',flag=?,NRT_updated_on=?,NRT_Comment=?,NRT_UpdatedBy=?, Updated_by=?,updated_on=? where  EmployeeID='" . $checked_arr[$i] . "' and id=? ";
					$stmt = $conn->prepare($save);
					$stmt->bind_param("sssssi",  $flag, $date, $NRT_Comment, $updatedBy, $updatedBy, $date, $moveid);
					$stmt->execute();
					$resultBy = $stmt->get_result();
					// print_r($resultBy);
					// die;
					// $myDB = new MysqliDb();
					// $resultBy = $myDB->rawQuery($save);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					if ($resultBy->num_rows > 0) $counttol++;
				}
			}

			if (($counttol > 0) && $resultBy->num_rows > 0) {
				echo "<script>$(function(){ toastr.success('Data Updated Successfully...'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data Not Updated :-'); }); </script>";
			}
		}
	} else {
		// $msg='<p class="text-success">You can update between 25th to 30th of the month</p>';
		echo "<script>$(function(){ toastr.error('You can update between 25th to 30th of the month'); }); </script>";
	}
}
/**
ENd  coding 
 */
$date = date("Y-m-d h:i:s");
if (isset($_POST['transfer_client'])) {
	//print_r($_POST);
	//echo "<br><br><br>";
	$counttol = 0;
	$sub_process_info =  cleanUserInput($_POST['sub_process_info']);
	$move_date =  cleanUserInput($_POST['move_date']);
	$tcid_array =  cleanUserInput($_POST['tcid']);
	$cm_id =  cleanUserInput($_POST['cm_id']);
	$oh = cleanUserInput($_POST['oh']);
	//$flag="toOH";	
	$date = date('d');
	$created_on = date("Y-m-d h:i:s");
	$date = date('d');
	// if ($date >= 25 && $date <= 30) {
	if ($date >= 1 && $date <= 20) {
		// a.status='OHApprove' and a.flag='toOH' 	
		if (isset($_POST['tcid'])) {
			$checked_arr = $_POST['tcid'];
			$count_check = count($checked_arr);
			if ($cm_id != "") {
				$max_key = max(array_keys($_POST['EmployeeName']));
				$min_key = min(array_keys($_POST['EmployeeName']));

				for ($p = $max_key; $p >= $min_key; $p--) {
					if (isset($_POST['tcid'][$p]) && $_POST['tcid'][$p] != "") {
						$employee_name =  cleanUserInput($_POST['EmployeeName'][$p]);
						$empID =  cleanUserInput($_POST['tcid'][$p]);
						// $select = $myDB->rawQuery("select id from tbl_tl2_tl_movement where EmployeeID='" . $empID . "' and `move_date`='" . $move_date . "' and `cm_id`='" . $cm_id . "' and `flag`='toOH' and  `status`='Pending' and `transfer_by`='" . $updatedBy . "' ");
						$selectQry = "select id from tbl_tl2_tl_movement where EmployeeID=? and `move_date`=? and `cm_id`=? and `flag`='toOH' and  `status`='Pending' and `transfer_by`=? ";
						$st = $conn->prepare($selectQry);
						$st->bind_param("ssis", $empID, $move_date, $cm_id, $updatedBy);
						$st->execute();
						$select = $st->get_result();
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if ($select->num_rows < 1) {
							// $save = "INSERT into tbl_tl2_tl_movement set  EmployeeID='" . $empID . "', `move_date`='" . $move_date . "' ,`old_ReportsTo`='" . $updatedBy . "',`flag`='toOH',`cm_id`='" . $cm_id . "' , `status`='Pending', `transfer_by`='" . $updatedBy . "',created_on='" . $created_on . "' ";
							$save = "INSERT into tbl_tl2_tl_movement set  EmployeeID=?, `move_date`=? ,`old_ReportsTo`=?',`flag`='toOH',`cm_id`=? , `status`='Pending', `transfer_by`=?,created_on=? ";
							//echo "<br><br><br>";
							$tableContent .= "<tr><td>" . $empID . "</td><td>" . $employee_name . "</td><td>" . $sub_process_info . "</td><td>" . $move_date . "</td></tr>";

							$stIns = $conn->prepare($save);
							$stIns->bind_param("sssiss", $empID, $move_date, $updatedBy, $cm_id, $updatedBy, $created_on);
							$stIns->execute();
							$resultBy = $stIns->get_result();
							// $myDB = new MysqliDb();
							// $resultBy = $myDB->rawQuery($save);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;

							if ($resultBy->num_rows > 0) $counttol++;

							// $mysql_error = $myDB->getLastError();
						}
					}
				}
				if ($counttol > 0 && $resultBy) {
					// $sender_data = "SELEsCT ofc_emailid,emailid  FROM contact_details  where EmployeeID  IN ('" . $oh . "' , '" . $updatedBy . "' )";
					$sender_data = "SELECT ofc_emailid,emailid  FROM contact_details  where EmployeeID  IN (? ,? )";
					$stsend = $conn->prepare($sender_data);
					$stsend->bind_param("ss", $oh, $updatedBy);
					$stsend->execute();
					$resultBy = $stsend->get_result();
					// print_r($resultBy);
					// die;
					$sender_AH = "";
					$sender_OH = "";
					$EmailTo = "";
					$email_array = array();
					// $myDB = new MysqliDb();
					// $resultBy = $myDB->rawQuery($sender_data);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;

					if ($resultBy->num_rows > 0) {
						foreach ($resultBy as $key => $data_array_sender) {
							if ($data_array_sender['ofc_emailid'] != "") {
								$email_array[] = $data_array_sender['ofc_emailid'];
							}
						}
					}

					$unique_Array = array_unique($email_array);

					$pagename = 'TL-2-TL-movement';
					// $select_email_array = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'";
					$select_email_array = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";
					$stemail = $conn->prepare($select_email_array);
					$stemail->bind_param("s", $pagename);
					$stemail->execute();
					$resultBy = $stemail->get_result();
					// $myDB = new MysqliDb();
					// $resultBy = $myDB->rawQuery($select_email_array);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					/**
					Coding for Send Email
					 */
					// $Subject_ = 'Employee Movement(ReportsTo) :  ' . date('d-m-Y H:i:s');
					// $mail = new PHPMailer;
					// $mail->isSMTP(); // Set mailer to use SMTP
					// $mail->Host = 'mail.cogenteservices.in';  // Specify main and backup SMTP servers
					// $mail->SMTPAuth = true; // Enable SMTP authentication
					// $mail->Username = 'ems@cogenteservices.in'; // SMTP username
					// $mail->Password = '987654321'; // SMTP password*/
					// $mail->SMTPSecure = 'TLS'; // Enable TLS encryption, `ssl` also accepted
					// /*$mail->Port = 587;*/
					// $mail->Port = 25;
					// $mail->setFrom('ems@cogenteservices.in', 'EMS:Employee Movement');

					//$mail->AddAddress('rinku.kumari@cogenteservices.in');
					for ($e = 0; $e < count($unique_Array); $e++) {
						$mail->AddAddress($unique_Array[$e]);
					}


					if ($resultBy->num_rows > 0) {
						foreach ($resultBy as $key => $email_array) {
							$email_address = $email_array['email_address'];
							if ($email_address != "") {
								$mail->AddAddress($email_address);
							}
							$cc_email = $email_array['ccemail'];
							if ($cc_email != "") {
								$mail->addCC($cc_email);
							}
						}
					}

					$mail->Subject = $Subject_;
					$Body .= "Hi Team,<br>Following TL to TL movement has been initiated. Please act accordingly<br><br>
			        <table border='1'>";
					$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Sub-Process Info</b></td><td><b>Move Date</b></td></tr>";
					$Body .= $tableContent;
					$Body .= "</table><br><br>Thanks EMS Team";
					$mail->isHTML(true);
					$mail->Body = $Body;
					// if (!$mail->send()) {
					// 	$lblMMAILmsg = 'Mail could not be sent.';
					// 	$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
					// } else {
					// 	$lblMMAILmsg = 'and Mail Sent Successfully.';
					// }

					echo "<script>$(function(){ toastr.success('Sub-Process Transfter Request Proceeded Successfully..'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error '); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>Current Sub Process not Selected ...'); }); </script>";
			}
		}
	} else {
		echo "<script>$(function(){ toastr.error('You can update between 25th to 30th of the month'); }); </script>";
	}
}
?>
<script>
	$(document).ready(function() {
		var dateToday = new Date();
		$('#move_date').datepicker({
			dateFormat: 'yy-mm-dd',
			minDate: dateToday

		});
		$('.statuscheck').addClass('hidden');
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
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

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
			}
		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement Sub-Process to Sub-Process </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement Sub-Process to Sub-Process </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s6 m6 ">
					<?php
					$action = '';
					if (isset($_GET['action'])) {
						$action = $_GET['action'];
					}
					if ($action == 'acc') {
						$process = 'To Sub-Process';
						// $sqlBy = "SELECT distinct b.process,b.cm_id,b.client_name,concat( b.clientname,' | ',b.process,' | ',b.sub_process) AS Client FROM whole_details_peremp b INNER JOIN tbl_tl2_tl_movement a ON  a.cm_id=b.cm_id where new_ReportsTo='" . $updatedBy . "' and a.status='OHApprove' and a.flag='toNRT'  ";
						$sqlBy = "SELECT distinct b.process,b.cm_id,b.client_name,concat( b.clientname,' | ',b.process,' | ',b.sub_process) AS Client FROM whole_details_peremp b INNER JOIN tbl_tl2_tl_movement a ON  a.cm_id=b.cm_id where new_ReportsTo=? and a.status='OHApprove' and a.flag='toNRT'";
						$stm = $conn->prepare($sqlBy);
						$stm->bind_param("s", $updatedBy);
						$stm->execute();
					} else
					if ($action == 'tr') {
						$process = 'From Sub-Process';
						// $sqlBy = "SELECT b.cm_id,b.oh,b.clientname, concat( b.clientname,' | ',b.process,' | ',b.sub_process) as Client FROM whole_details_peremp b where b.ReportTo='" . $updatedBy . "' group by b.cm_id";
						$sqlBy = "SELECT b.cm_id,b.oh,b.clientname, concat( b.clientname,' | ',b.process,' | ',b.sub_process) as Client FROM whole_details_peremp b where b.ReportTo=? group by b.cm_id";
						$stm = $conn->prepare($sqlBy);
						$stm->bind_param("s", $updatedBy);
						$stm->execute();
					}
					//echo $sqlBy;
					$resultBy = $stm->get_result();
					$rowRes = $resultBy->fetch_row();
					// print_r($resultBy);
					// die;
					// $myDB = new MysqliDb();
					// $resultBy = $myDB->rawQuery($sqlBy);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					?>
					<select id="queryfrom" name="getaction" disabled="disabled">
						<option value="NA">----Select Action----</option>
						<option value="tr" <?php if ($action == 'tr') echo 'selected'; ?>>Transfer</option>
						<option value="acc" <?php if ($action == 'acc') echo 'selected'; ?>>Accept</option>
					</select>
					<label for="txt_Client_ach" class="active-drop-down active">Action</label>
				</div>
				<div class="input-field col s6 m6 8">
					<?php
					$id = "";
					$acc_cm_id = "";
					if ($action == 'acc') {
						$id = "acc_cm_id";
						if (isset($_GET['acc_cm_id'])) {
							$acc_cm_id = cleanUserInput($_GET['acc_cm_id']);
						}
					} else {
						$acc_cm_id = "";
						$id = 'queryfrom1';
						if ($resultBy->num_rows > 0) {
							$oh = clean($rowRes[1]); //['oh'];
							//	$client=$resultBy[0]['Client'];
							if (isset($_GET['cm_id'])) {
								$acc_cm_id = cleanUserInput($_GET['cm_id']);
							}
						}
					}
					?>
					<select id="<?php echo $id; ?>" name="cm_id">
						<option value="">Select</option>
						<?php

						if ($resultBy->num_rows > 0) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								$select = '';


								echo '<option  value="' . $value['cm_id'] . '"';
								if ($value['cm_id'] == $acc_cm_id) {
									echo "selected";
								}

								echo ' >' . $value['Client'] . '</option>';
							}
						}
						?>
					</select>
					<label for="txt_Client_ach" class="active-drop-down active"><?php echo $process; ?></label>
				</div>
				<div>
					<?php
					if ($action == 'tr') {
						$movedate = date("Y-m", strtotime("+ 10 days"));
						$movedate .= '-01';
					?>
						<div class="input-field col s6 m6 8">
							<input type="text" name="move_date" id="move_date1" value="<?php echo $movedate; ?>" readonly />
							<label for="move_date"> Move Date</label>
						</div>
						<div class="input-field col s12 m12 right-align">
							<input type='hidden' name='oh' id='oh' value="<?php echo $oh; ?>">
							<input type='text' name='sub_process_info' id='sub_process_info' value="">
							<button type="submit" name="transfer_client" id="transfer_client" class="btn waves-effect waves-green ">Transfer</button>
						</div>
					<?php } else 
	      	if ($action == 'acc') {
					?>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" name="update_status" id="update_status" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Update</button>

						</div>
					<?php	} ?>

				</div>
				<div id="pnlTable">
					<?php
					$sqlConnect = "";
					if ($action == 'tr') {
						$date1 = date('d', strtotime("-1 days"));
						$date2 = date('d', strtotime("-2 days"));
						$date3 = date('d', strtotime("-3 days"));
						if (substr($date1, 0, 1) == 0) {
							$date1 = str_replace('0', '', $date1);
						}
						if (substr($date2, 0, 1) == 0) {
							$date2 = str_replace('0', '', $date2);
						}
						if (substr($date3, 0, 1) == 0) {
							$date3 = str_replace('0', '', $date3);
						}
						$year = array();
						$month = array();
						$month[] = date('m', strtotime("-3 days"));
						$year[] = date('Y', strtotime("-3 days"));
						$month[] = date('m', strtotime("-2 days"));
						$year[] = date('Y', strtotime("-2 days"));
						$month[] = date('m', strtotime("-1 days"));
						$year[] = date('Y', strtotime("-1 days"));
						$months = implode(',', array_unique($month));
						$years = implode(',', array_unique($year));
						if (isset($_GET['cm_id']) && trim($_GET['cm_id']) != "") {
							$cm_id = $_GET['cm_id'];

							$ncns_query = "select calc_atnd_master.EmployeeID from calc_atnd_master left outer join employee_map on calc_atnd_master.EmployeeID=employee_map.EmployeeID  where  D" . $date1 . "='A' and  D" . $date2 . "='A' and D" . $date3 . "='A' and month in (" . $months . ") and Year IN(" . $years . ") and employee_map.cm_id='" . $cm_id . "' ";

							// $sqlConnect = "select id,cm_id,EmployeeID,EmployeeName,client_name,clientname,Process,sub_process,designation,emp_level,emp_status from whole_details_peremp where  ReportTo='" . $updatedBy . "'  and whole_details_peremp.cm_id = '" . $cm_id . "'  and EmployeeID NOT IN(select EmployeeID from tbl_tl2_tl_movement where old_ReportsTo='" . $updatedBy . "' and flag not in ('NRTR' , 'OHR','FM')) and  EmployeeID NOT IN(select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where rg_status > 0 and rg_status < 9 and accept = 1 and employee_map.emp_status = 'Active' and employee_map.cm_id = '" . $cm_id . "' and final_acceptance is null) ";

							// $sqlConnect .= "  and EmployeeID not in ($ncns_query)";

							$sqlConnect = "select id,cm_id,EmployeeID,EmployeeName,client_name,clientname,Process,sub_process,designation,emp_level,emp_status from whole_details_peremp where  ReportTo=?  and whole_details_peremp.cm_id = ?  and EmployeeID NOT IN(select EmployeeID from tbl_tl2_tl_movement where old_ReportsTo=? and flag not in ('NRTR' , 'OHR','FM')) and  EmployeeID NOT IN(select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where rg_status > 0 and rg_status < 9 and accept = 1 and employee_map.emp_status = 'Active' and employee_map.cm_id = ? and final_acceptance is null) ";

							$sqlConnect .= "  and EmployeeID not in (?)";

							$stmUp = $conn->prepare($sqlConnect);
							$stmUp->bind_param("sisis", $updatedBy, $cm_id, $updatedBy, $cm_id, $ncns_query);
							$stmUp->execute();
						}
					} else
					if ($action == 'acc') {
						if (isset($_GET['acc_cm_id']) and $_GET['acc_cm_id'] != "") {
							$acc_cm_id = $_GET['acc_cm_id'];

							// $sqlConnect = "select b.OH_comment,b.id as moveid ,a.id,a.EmployeeID,a.EmployeeName,a.client_name,a.clientname,a.cm_id,a.Process,a.sub_process ,a.designation,a.emp_level,a.emp_status ,b.status,b.NRT_Comment from  tbl_tl2_tl_movement b  INNER JOIN whole_details_peremp a ON  b.EmployeeID=a.EmployeeID where  b.new_ReportsTo='" . $updatedBy . "' and b.cm_id='" . $acc_cm_id . "' and b.flag='toNRT' and b.status='OHApprove'  ";
							$sqlConnect = "select b.OH_comment,b.id as moveid ,a.id,a.EmployeeID,a.EmployeeName,a.client_name,a.clientname,a.cm_id,a.Process,a.sub_process ,a.designation,a.emp_level,a.emp_status ,b.status,b.NRT_Comment from  tbl_tl2_tl_movement b  INNER JOIN whole_details_peremp a ON  b.EmployeeID=a.EmployeeID where  b.new_ReportsTo=? and b.cm_id=? and b.flag='toNRT' and b.status='OHApprove'  ";
							$stmUp = $conn->prepare($sqlConnect);
							$stmUp->bind_param("si", $updatedBy, $acc_cm_id);
							$stmUp->execute();
						}
					}
					if ($sqlConnect != "") {

						$resultBy = $stmUp->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($sqlConnect);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if ($resultBy->num_rows > 0) { ?>
							<div class="had-container pull-left row card dataTableInline ">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th width="3%">Serial No.</th>
												<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll">Employee</label></th>
												<th class="hidden">EmployeeID</th>
												<th>EmployeeName</th>
												<th>Designation</th>
												<th>Level</th>
												<?php if ($action == 'acc') { ?>
													<th>OH Comment</th>
													<th>Status</th>
													<th>Comment</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody id="emplist">
											<?php
											$count = $resultBy->num_rows;
											$i = 0;
											$j = 0;
											foreach ($resultBy as $key => $data_array) {
												$i++;
												echo '<tr>';
												echo "<td width='3%' >" . $i . "</td>";
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $j . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $data_array['EmployeeID'] . '</label></td>';
												echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
												echo '<td class="FullName">' . $data_array['EmployeeName'] . '</td>';
												echo '<td class="client_name">' . $data_array['designation'] . '</td>';
												echo '<td class="process">' . $data_array['emp_level'] . '</td>';	?>
												<input type='hidden' name='EmployeeName[<?php echo  $j; ?>]' class='pempname' value="<?php echo $data_array['EmployeeName']; ?>">
												<?php
												if ($action == 'acc') {
													$NRT_comment = "";
													if ($data_array['NRT_Comment'] != "") {
														$NRT_comment = stripcslashes($data_array['NRT_Comment']);
													}
												?>
													<td class="comment" style="padding: 0px;max-height: 30px;min-height: 30px;"><?php echo  $data_array['OH_comment']; ?></td>
													<td class="active_status" style="padding: 0px;max-height: 30px;min-height: 30px;">
														<select name="status[<?php echo  $j; ?>]" id="status<?php echo  $i; ?>" class="form-control">
															<option value="NRT_Approve" <?php if ($data_array['status'] == 'NRT_Approve') { ?> selected <?php } ?>>Approve</option>
															<option value="NRT_Reject" <?php if ($data_array['status'] == 'NRT_Reject') { ?> selected <?php } ?>>Reject</option>
														</select>
													</td>
													<td class="comment" style="padding: 0px;max-height: 30px;min-height: 30px;">
														<textarea name='NRT_Comment[<?php echo  $j; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea nrtcomment "><?php echo  $NRT_comment; ?></textarea>
													</td>
													<input type="hidden" name='moveid[<?php echo  $j; ?>]' id='moveid<?php echo  $j; ?>' class='moveid' value="<?php echo $data_array['moveid']; ?>">
											<?php
												}
												echo '</tr>';
												$j++;
											}


											?>
											<script>
												$("input:checkbox").click(function() {
													if ($('input:checkbox:checked').length > 0) {
														checklistdata();

													} else {
														$('#client_to').val('No');
														$('.statuscheck').addClass('hidden');
														$('#docTable').html('');
														$('#docstable').addClass('hidden');
													}
												});
											</script>
										</tbody>
									</table>
								</div>
							</div>
					<?php
						} else {
							echo "<script>$(function(){ toastr.info('Please select your client.'); }); </script>";
						}
					}
					?>

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
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});


		$('#btnCan').click(function() {
			$("input:checkbox").prop('checked', false);
			$('#client_to').val('NA');
			$('.statuscheck').addClass('hidden');
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
		});

		$("#cbAll").change(function() {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function() {
			if ($('input.cb_child:checkbox:checked').length > 0) {
				checklistdata();
				if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

					$("#cbAll").prop("checked", true);
				} else {
					$("#cbAll").prop("checked", false);
				}
			} else {
				$("#cbAll").prop("checked", false);
				$('#client_to').val('NA');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#client_to').change(function() {
			var tolientid = $('#client_to').val();
			var new_cm_id = $(this).children(":selected").attr("id");
			$('#new_cm_id').val(new_cm_id);
			var old_process_info = $("#queryfrom1  option:selected").text();
			$('#old_process_info').val(old_process_info);
			if (tolientid == 'NA') {
				$('#transfer_client').addClass('hidden');
			} else {
				$('#transfer_client').removeClass('hidden');
			}
		});
		$('#transfer_client').on('click', function() {
			alert_msg = "";
			validate = 0;
			if ($('input.cb_child:checkbox:checked').length < 1) {
				validate = 1;
				alert_msg = 'Please check atleast one employee';

			}
			var client_process = $("#queryfrom1  option:selected").text();
			$('#sub_process_info').val(client_process);
			var currentTime = new Date()
			var day = currentTime.getDate()
			// if (!(day >= 25 && day <= 30)) {
			if (!(day >= 1 && day <= 20)) {

				validate = 1;
				alert_msg = '<li>Movement is valid between 25th to 30th  of the month </li>';
			}
			if (validate == 1) {
				//$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				//$('#alert_message').show().attr("class","SlideInRight animated");
				//$('#alert_message').delay(5000).fadeOut("slow");

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			} else {
				var rr = confirm('Are you want to proceed?');
				if (rr == false) {
					return false;
				}
			}




		})
		$('#update_status').on('click', function() {
			alert_msg = "";
			validate = 0;
			var currentTime = new Date()
			var day = currentTime.getDate()
			 if (!(day >= 25 && day <= 30)) {
			// if (!(day >= 1 && day <= 20)) {
				validate = 1;
				alert_msg = '<li>You can approve between 25th to 30th of the month  </li>';
			} else {


				var inputElements = document.getElementsByClassName('cb_child');
				var nrtcomment = document.getElementsByClassName('nrtcomment');
				var pempname = document.getElementsByClassName('pempname');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						checkedValue = nrtcomment[i].value.trim();

						var pempname = pempname[i].value.trim();
						if (checkedValue == "") {
							validate = 1;
							alert_msg += '<li> Write the comment for ' + pempname + '  </li>';
							break;
						} else {
							var statusval = document.getElementById('status' + i).value;
							if (statusval == 'Pending') {
								validate = 1;
								alert_msg += '<li> Please change the status for ' + pempname + '  </li>';
								break;
							}
						}

					}
				}
			}
			if (validate == 1) {
				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
			confirm('Are you want to proceed?');


		})

		$('#queryfrom1').change(function() {
			var cm_id = $('#queryfrom1').val().trim();
			location.href = 'TL-2-TL-movement.php?action=tr&cm_id=' + cm_id;
		})
		$('#acc_cm_id').change(function() {
			var acc_cm_id = $('#acc_cm_id').val();
			//if(acc_cm_id!=""){
			location.href = 'TL-2-TL-movement.php?action=acc&acc_cm_id=' + acc_cm_id;
			//}
		});


	});

	function checklistdata() {
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>