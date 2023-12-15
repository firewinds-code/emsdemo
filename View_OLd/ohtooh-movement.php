<?php
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
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	$userid = clean($_SESSION['__user_logid']);
	if (!isset($userid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$old_process = $new_process = $move_date = $tcid_array = '';
$Loggin_location = clean($_SESSION["__location"]);
$classvarr = "'.byID'";
$searchBy = '';
$process = '';
$Body = "";
$client_id = "";
$tableContent = "";
$date = date("Y-m-d h:i:s");
if (isset($_GET['process']) && $_GET['process'] != '') {
	$process = cleanUserInput($_GET['process']);
}
if (isset($_GET['client_id']) && trim($_GET['client_id']) != "") {
	$client_id = cleanUserInput($_GET['client_id']);
}
$cm_id = "";
if (isset($_GET['cm_id']) && trim($_GET['cm_id']) != "") {
	$cm_id = cleanUserInput($_GET['cm_id']);
}
$action = '';
if (isset($_GET['action'])) {
	$action = cleanUserInput($_GET['action']);
}
$msg = '';
$max_key = "";
$min_key = "";
$oh_comment = "";
$moveid = "";
/**
coding for update status 
 */

$updatedBy = clean($_SESSION['__user_logid']);
?>
<input type='hidden' id='account_head' name='account_head' value='<?php echo $updatedBy; ?>'>
<?php
if (isset($_POST['update_status'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$counttol = 0;
		$status = $_POST['status'];
		//print_r(cleanUserInput($_POST));
		$old_process = cleanUserInput($_POST['old_process']);
		//$new_process =cleanUserInput($_POST['new_process'];)	
		$tcid_array = cleanUserInput($_POST['tcid']);
		$flag = "";
		$max_key = max(array_keys($_POST['oh_comment']));
		$min_key = min(array_keys($_POST['oh_comment']));
		$mysql_error = '';
		if (isset($_POST['tcid'])) {
			$date = date("Y-m-d h:i:s");
			$checked_arr = cleanUserInput($_POST['tcid']);
			//print_r($checked_arr);
			//die;
			$count_check = count($status);
			if ($old_process != "") {
				for ($i = $max_key; $i >= $min_key; $i--) {


					if (isset($checked_arr[$i]) && $checked_arr[$i] != "" && $_POST['oh_comment'][$i] != "") {
						if ($status[$i] == 'OHReject') {
							$flag = 'NPR';
						} else
					if ($status[$i] == 'OHApprove') {
							$flag = 'NPA';
						}
						$moveid =	cleanUserInput($_POST['moveid'][$i]);
						$empID = cleanUserInput($checked_arr[$i]);
						$oh_comment = cleanUserInput($_POST['oh_comment'][$i]);
						if ($flag != "") {
							$save = "UPDATE tbl_oh_tooh_move set status='" . $status[$i] . "',flag=?,OH_updated_on=?,oh_comment=?,OH_updated_by=?, Updated_by=?,updated_on=? where  EmployeeID=? and id='" . $checked_arr[$i] . "' and move_location=?";
							// $resulti = $myDB->rawQuery($save);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;3
							$stmt = $conn->prepare($save);
							$stmt->bind_param("issssss",  $flag, $date, $oh_comment, $updatedBy, $updatedBy, $date,  $moveid);
							$stmt->execute();
							$resultBy = $stmt->get_result();
							if ($resultBy->num_rows > 0) $counttol++;
						}
					}
				}
				if (($counttol > 0)) {
					echo "<script>$(function(){ toastr.success('Data Updated Successfully...'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Data Not Updated :'); }); </script>";
				}
			}
		}
	}
}
/**
ENd  coding 
 */
$date = date("Y-m-d h:i:s");
if (isset($_POST['transfer_client'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		//print_r($_POST);
		//echo "<br><br><br>";
		$counttol = 0;
		$old_process = cleanUserInput($_POST['old_process']);
		$new_process_info = cleanUserInput($_POST['new_process_info']);
		$old_process_info = cleanUserInput($_POST['old_process_info']);
		$new_process = cleanUserInput($_POST['new_process']);
		$move_date = cleanUserInput($_POST['move_date']);
		$tcid_array = cleanUserInput($_POST['tcid']);
		$cm_id = cleanUserInput($_POST['cm_id']);
		$new_cm_id = cleanUserInput($_POST['new_cm_id']);

		$flag = "toAH";
		if ($cm_id != $new_cm_id) {
			$tcid = isset($_POST['tcid']);
			if ($tcid) {
				$checked_arr = $_POST['tcid'];
				$count_check = count($checked_arr);
				if ($old_process != "") {
					$max_key = max(array_keys($_POST['EmployeeName']));
					$min_key = min(array_keys($_POST['EmployeeName']));
					$notSalaryslab = $notVersant = $notBGV = '';
					$Applicable = '';
					for ($p = $max_key; $p >= $min_key; $p--) {
						if (isset($_POST['tcid'][$p]) && $_POST['tcid'][$p] != "") {
							$employee_name = cleanUserInput($_POST['EmployeeName'][$p]);
							$empID = cleanUserInput($_POST['tcid'][$p]);

							// $mysquery = "select ctc from salary_details s inner join whole_details_peremp w on w.EmployeeID=s.EmployeeID where s.EmployeeID='" . $empID . "' and ( CAST(SUBSTRING_INDEX(ctc, '-', -1) AS UNSIGNED)  <= (select CAST(SUBSTRING_INDEX(max_lim, '-', -1) AS UNSIGNED) as maxslab from tbl_salary_slab_by_cps  where cm_id='" . $new_cm_id . "') or w.des_id not  in (9,12) )";
							$mysquery = "SELECT ctc from salary_details s inner join whole_details_peremp w on w.EmployeeID=s.EmployeeID where s.EmployeeID=? and ( CAST(SUBSTRING_INDEX(ctc, '-', -1) AS UNSIGNED)  <= (select CAST(SUBSTRING_INDEX(max_lim, '-', -1) AS UNSIGNED) as maxslab from tbl_salary_slab_by_cps  where cm_id=?) or w.des_id not  in (9,12) )";
							$stmt1 = $conn->prepare($mysquery);
							$stmt1->bind_param("si", $empID, $new_cm_id);
							$stmt1->execute();
							$resultBy = $stmt1->get_result();
							// $salary_array = $myDB->query($mysquery);
							$salary_array = $stmt1->get_result();
							if ($salary_array->num_rows > 0) {
								// $mysquery = "select cert_name,cm_id from certification_require_by_cmid where cm_id='" . $new_cm_id . "'";
								$mysquery = "select cert_name,cm_id from certification_require_by_cmid where cm_id=?";
								$versantflag = 0;
								$sts = $conn->prepare($mysquery);
								$sts->bind_param("i", $new_cm_id);
								$sts->execute();
								$versant = $sts->get_result();
								// $myDB = new MysqliDb();
								// $versant = $myDB->rawQuery($mysquery);
								// $mysql_error = $myDB->getLastError();
								// $versantrowCount = $myDB->count;
								if (($versant) && $versant->num_rows > 0) {
									// $mysquery = "select test_name from test_score where EmpID='" . $empID . "'";
									$mysquery = "select test_name from test_score where EmpID=?";
									// $testname = $myDB->rawQuery($mysquery);
									// $mysql_error = $myDB->getLastError();
									// $testrowCount = $myDB->count;
									$sts = $conn->prepare($mysquery);
									$sts->bind_param("s", $empID);
									$sts->execute();
									$testname = $sts->get_result();
									if (($testname) && $testname->num_rows > 0) {
										if ($versantrowCount == $testname->num_rows) {
											$flag = '';
											foreach ($versant as $key => $value1) {
												if ($flag == 1) {
													$versantflag = 1;
													break;
												}
												$flag = 1;
												$cert_name = $value1['cert_name'];
												//echo 'certification_require_by_cmid - ' . $cert_name . '<br/>';
												foreach ($testname as $key => $value) {
													if ($cert_name == $value['test_name'])
														$flag = 0;
													else {
														if (strpos($cert_name, "ant -") == '4' && strpos($value['test_name'], "ant -") == '4') {
															//echo 'there1';
															if ((int)substr($value['test_name'], strlen($value['test_name']) - 1, 1) > (int)substr($cert_name, strlen($cert_name) - 1, 1)) {
																//echo 'there';
																$flag = 0;
															}
														}
													}
													//echo 'test_name - ' . $value['test_name'] . '<br/>';
												}
											}
											if ($flag == 1) {
												$versantflag = 1;
											}
										} else {
											$versantflag = 1;
										}
									} else {
										$versantflag = 1;
									}
								}
								//echo $versantflag;
								if ($versantflag == 1) {
									$notVersant .= $empID . ',';
								} else {
									$bgvflag = 0;
									// $query = $myDB->rawQuery("SELECT * FROM bgv_matrix where cm_id = '" . $new_cm_id . "' and desig = (select case when df_id=74 then 'CSA' else 'Support' end as desig from employee_map where EmployeeID='" . $empID . "') and (Addr='Yes' or Edu='Yes' or Emp='Yes' or Crim='Yes');");
									$queryQry = "SELECT * FROM bgv_matrix where cm_id = ? and desig = (select case when df_id=74 then 'CSA' else 'Support' end as desig from employee_map where EmployeeID=?) and (Addr='Yes' or Edu='Yes' or Emp='Yes' or Crim='Yes');";
									$stbgv = $conn->prepare($queryQry);
									$stbgv->bind_param("is", $new_cm_id, $empID);
									$stbgv->execute();
									$query = $stbgv->get_result();
									if ($query->num_rows > 0) {
										// $query = $myDB->rawQuery("select doc_file from doc_details where employeeid='" . $empID . "' and doc_stype='BG verification';");
										$queryQry = "select doc_file from doc_details where employeeid=? and doc_stype='BG verification'";
										$stdoc = $conn->prepare($queryQry);
										$stdoc->bind_param("s", $empID);
										$stdoc->execute();
										$query = $stdoc->get_result();
										if ($query->num_rows <= 0) {
											$bgvflag = 1;
										}
									}
									if ($bgvflag == 1) {
										$notBGV .= $empID . ',';
									} else {
										// die;
										// $select = $myDB->rawQuery("select id from tbl_oh_tooh_move where EmployeeID='" . $empID . "' and `move_date`='" . $move_date . "' and `cm_id`='" . $cm_id . "'  and `new_cm_id`='" . $new_cm_id . "' and `flag`='" . $flag . "' and  `status`='Pending' and move_location='" . $Loggin_location . "'");
										$selectQry = "select id from tbl_oh_tooh_move where EmployeeID=? and `move_date`=? and `cm_id`=?  and `new_cm_id`=? and `flag`=? and  `status`='Pending' and move_location=?";
										$stsel = $conn->prepare($selectQry);
										$stsel->bind_param("ssiiii", $empID, $move_date, $cm_id, $new_cm_id, $flag, $Loggin_location);
										$stsel->execute();
										$select = $stsel->get_result();
										// $mysql_error = $myDB->getLastError();
										// $rowCount = $myDB->count;
										if ($select->num_rows < 1) {
											// $save = "INSERT into tbl_oh_tooh_move set  EmployeeID='" . $empID . "', `move_date`='" . $move_date . "' ,`old_process`='" . $old_process . "',`new_process`='" . $new_process . "',`new_cm_id`='" . $new_cm_id . "',`flag`='" . $flag . "',`cm_id`='" . $cm_id . "' , `status`='Pending', `transfer_by`='" . $updatedBy . "',created_on='" . $date . "',move_location='" . $Loggin_location . "'";
											$save = "INSERT into tbl_oh_tooh_move set  EmployeeID=?, `move_date`=?,`old_process`=?,`new_process`=?,`new_cm_id`=?,`flag`=?,`cm_id`=?, `status`='Pending', `transfer_by`=?,created_on=?,move_location=?";
											$Applicable .= $empID . ',';
											$tableContent .= "<tr><td>" . $empID . "</td><td>" . $employee_name . "</td><td>" . $old_process_info . "</td><td>" . $new_process_info . "</td><td>" . $move_date . "</td></tr>"; //echo "<br>";
											// $resulti = $myDB->rawQuery($save);
											// $mysql_error = $myDB->getLastError();
											// $rowCount = $myDB->count;
											$stins = $conn->prepare($save);
											$stins->bind_param("ssssiiissi", $empID, $move_date, $old_process, $new_process, $new_cm_id, $flag, $cm_id, $updatedBy, $date, $Loggin_location);
											$stins->execute();
											$resulti = $stins->get_result();
											if ($resulti->num_rows > 0) $counttol++;
										}
									}
								}
							} else {
								$notSalaryslab .= $empID . ',';
							}
						}
					}
					if ($notSalaryslab != "") {
						$notSalaryslab = substr($notSalaryslab, 0, -1);
					}
					if ($notVersant != "") {
						$notVersant = substr($notVersant, 0, -1);
					}
					if ($notBGV != "") {
						$notBGV = substr($notBGV, 0, -1);
					}
					if (($counttol > 0) && empty($mysql_error)) {
						// $sender_data = $myDB->rawQuery("SELECT a.oh,a.account_head, c.EmployeeID as AH_EmployeeID ,b.EmployeeID as OH_EmployeeID,b.ofc_emailid  as OH_email_id,c.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.oh INNER JOIN ems.contact_details c on c.EmployeeID=a.account_head and a.cm_id='" . $cm_id . "'");
						$sender_dataQry = "SELECT a.oh,a.account_head, c.EmployeeID as AH_EmployeeID ,b.EmployeeID as OH_EmployeeID,b.ofc_emailid  as OH_email_id,c.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.oh INNER JOIN ems.contact_details c on c.EmployeeID=a.account_head and a.cm_id=?";
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						$stm = $conn->prepare($sender_dataQry);
						$stm->bind_param("i",  $cm_id);
						$stm->execute();
						$sender_data = $stm->get_result();
						$sender_AH = "";
						$sender_OH = "";
						$EmailTo = "";
						$email_array = array();
						if ($sender_data && $sender_data->num_rows > 0) {
							foreach ($sender_data as $key => $data_array_sender) {
								if ($data_array_sender['AH_email_id'] != "") {
									$email_array[] = $data_array_sender['AH_email_id'];
								}
								if ($data_array_sender['OH_email_id'] != "") {
									$email_array[] = $data_array_sender['OH_email_id'];
								}
							}
						}
						// $receiver_data = $myDB->rawQuery("SELECT a.oh,a.account_head, c.EmployeeID as AH_EmployeeID ,b.EmployeeID as OH_EmployeeID,b.ofc_emailid as OH_email_id,c.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.oh INNER JOIN ems.contact_details c on c.EmployeeID=a.account_head and a.cm_id='" . $new_cm_id . "' and a.location='" . $Loggin_location . "'");
						$receiver_dataQry = "SELECT a.oh,a.account_head, c.EmployeeID as AH_EmployeeID ,b.EmployeeID as OH_EmployeeID,b.ofc_emailid as OH_email_id,c.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.oh INNER JOIN ems.contact_details c on c.EmployeeID=a.account_head and a.cm_id=? and a.location=?";
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						$stmsel = $conn->prepare($receiver_dataQry);
						$stmsel->bind_param("ii",  $new_cm_id, $Loggin_location);
						$stmsel->execute();
						$receiver_data = $stmsel->get_result();
						if ($receiver_data && $receiver_data->num_rows > 0) {
							foreach ($receiver_data as $key => $data_array_receiver) {
								if ($data_array_receiver['AH_email_id'] != "") {
									$email_array[] = $data_array_receiver['AH_email_id'];
								}
								if ($data_array_receiver['OH_email_id'] != "") {
									$email_array[] = $data_array_receiver['OH_email_id'];
								}
							}
						}
						$unique_Array = array_unique($email_array);

						/**
					Coding for Send Email
						 */
						$pagename = 'ohtooh-movement';
						// $select_email_array = $myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'");
						$select_email_arrayQry = "SELECT a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";
						$stmsel = $conn->prepare($select_email_arrayQry);
						$stmsel->bind_param("s",  $pagename);
						$stmsel->execute();
						$select_email_array = $stmsel->get_result();

						$Subject_ = 'Employee Movement ' . EMS_CenterName . ' : ' . $new_process_info . ' ' . date('d-m-Y H:i:s');
						$mail = new PHPMailer;
						$mail->isSMTP(); // Set mailer to use SMTP
						$mail->Host = EMAIL_HOST;
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;
						$mail->Password = EMAIL_PASS;
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT;
						$mail->setFrom(EMAIL_FROM, 'EMS:Employee Movement');
						for ($e = 0; $e < count($unique_Array); $e++) {
							if (isset($unique_Array[$e])) {
								$mail->AddAddress($unique_Array[$e]);
							}
						}
						//$mail->addCC('sachin.siwach@cogenteservices.com');
						if ($select_email_array->num_rows > 0) {
							foreach ($select_email_array as $key => $email_array) {
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

						$Body .= "Hi Team,<br>Following Process to Process movement has been initiated. Please act accordingly<br><br>
			        <table border='1'>";
						$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Current Process</b></td><td><b>New Process</b></td><td><b>Move Date</b></td></tr>";
						$Body .= $tableContent;
						$Body .= "</table><br><br>Thanks EMS Team";
						$mail->isHTML(true);
						$mail->Body = $Body;
						if (!$mail->send()) {
							$lblMMAILmsg = 'Mail could not be sent.';
							$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
						} else {
							$lblMMAILmsg = 'and Mail Send successfully.';
						}


						if ($notSalaryslab != "") {
							echo "<script>$(function(){ toastr.error('Employee(s) Transfer cannot be initiated due to salary capping'); }); </script>";
						}
						if ($notVersant != "") {
							echo "<script>$(function(){ toastr.error(' Employee(s) Movement can not proceed due to Versant not updated'); }); </script>";
						}
						if ($notBGV != "") {
							echo "<script>$(function(){ toastr.error(' Employee(s) Movement can not proceed due to BGV not updated'); }); </script>";
						}
						if ($Applicable != "") {
							echo "<script>$(function(){ toastr.success(' Employee(s) Movement initiated Successfully..'); }); </script>";
						}
					} else {
						if ($notSalaryslab != "") {
							// $msg.='<p class="text-success">['.$notSalaryslab.'] Employee(s) Movement can not proceed due to lower salary slab</p>';
							echo "<script>$(function(){ toastr.error(' Employee(s) Movement can not proceed due to lower salary slab'); }); </script>";
						} else if ($notVersant != "") {
							echo "<script>$(function(){ toastr.error(' Employee(s) Movement can not proceed due to Versant not updated'); }); </script>";
						} else if ($notBGV != "") {
							echo "<script>$(function(){ toastr.error(' Employee(s) Movement can not proceed due to BGV not updated'); }); </script>";
						} else if ($Applicable != "") {
							echo "<script>$(function(){ toastr.success(' Employee(s) Movement initiated Successfully..'); }); </script>";
						} else {
							//$msg='<p class="text-danger">Employee Not Transfter ::Error :- <code>'".$mysql_error."'</code></p>';
							echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>''</code>'); }); </script>";
						}
					}
				} else {
					echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>Current Client not Selected ...'); }); </script>";
				}
			}
		} else {

			echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>Current Sub-Process and new Sub-Process are same ...'); }); </script>";
		}
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
	<span id="PageTittle_span" class="hidden">Employee Movement Process to Process </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement Process to Process </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				if ($action == 'acc') {
					$process = 'To Process';
					// $sqlBy = "SELECT distinct b.process,b.cm_id,b.client_name,concat(c.client_name,'|',b.process,'|',b.sub_process) AS Client FROM tbl_oh_tooh_move a,new_client_master b,client_master c where a.new_cm_id=b.cm_id  and c.client_id=b.client_name and b.oh='" . $updatedBy . "' and a.status='AHApprove' and a.flag='toOH' and a.move_location='" . $Loggin_location . "'  ";
					$sqlBy = "SELECT distinct b.process,b.cm_id,b.client_name,concat(c.client_name,'|',b.process,'|',b.sub_process) AS Client FROM tbl_oh_tooh_move a,new_client_master b,client_master c where a.new_cm_id=b.cm_id  and c.client_id=b.client_name and b.oh=? and a.status='AHApprove' and a.flag='toOH' and a.move_location=?  ";
					$stQr = $conn->prepare($sqlBy);
					$stQr->bind_param("si", $updatedBy, $Loggin_location);
					// $stQr->execute();
				} else
						if ($action == 'tr') {
					$process = 'From Process';
					// $sqlBy = "SELECT b.cm_id,b.process,b.client_name, concat( client_master.client_name,' | ',b.process,' | ',b.sub_process) as Client FROM new_client_master b inner join client_master  on b.client_name = client_master.client_id where b.oh='" . $updatedBy . "' and b.location='" . $Loggin_location . "' and  client_master.client_id NOT IN(1,5,13,15,39)";
					$sqlBy = "SELECT b.cm_id,b.process,b.client_name, concat( client_master.client_name,' | ',b.process,' | ',b.sub_process) as Client FROM new_client_master b inner join client_master  on b.client_name = client_master.client_id where b.oh=? and b.location=? and  client_master.client_id NOT IN(1,5,13,15,39)";
					$stQr = $conn->prepare($sqlBy);
					$stQr->bind_param("si", $updatedBy, $Loggin_location);
					// $stQr->execute();
				}
				// $myDB = new MysqliDb();
				// $resultBy = $myDB->rawQuery($sqlBy);
				// $mysql_error = $myDB->getLastError();
				// $rowCount = $myDB->count;
				$stQr->execute();
				$resultBy = $stQr->get_result();
				?>
				<div class="input-field col s6 m6 ">
					<select id="queryfrom" name="getaction" disabled="disabled">
						<option value="NA">----Select Action----</option>
						<option value="tr" <?php if ($action == 'tr') echo 'selected'; ?>>Transfer</option>
						<option value="acc" <?php if ($action == 'acc') echo 'selected'; ?>>Accept</option>
					</select>
					<label for="queryfrom" class="active-drop-down active">Action</label>
				</div>
				<div class="input-field col s6 m6 ">
					<select id="queryfrom1" name="old_process">
						<option value="NA">----Select----</option>
						<?php

						if ($resultBy->num_rows > 0) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								$select = '';
								if ($process != '' && $value['cm_id'] == $cm_id) {
									$select = "selected";
								}
								echo '<option id="' . $value['client_name'] . '_' . $value['cm_id'] . '" value="' . $value['process'] . '"  ' . $select . ' >' . $value['Client'] . '</option>';
							}
						}

						?>
					</select>

					<label for="queryfrom1" class="active-drop-down active"><?php echo $process; ?> </label>

				</div>


				<div class="statuscheck">
					<?php
					if ($action == 'tr') {
						if (isset($_GET['client_id']) && trim($_GET['client_id']) != "") {
							$client_id = cleanUserInput($_GET['client_id']);
						}
						if (isset($_GET['cm_id']) && trim($_GET['cm_id']) != "") {
							$cm_id = cleanUserInput($_GET['cm_id']);
						}

						if ($client_id != "") {

							//echo "New process=";
							// $sqlBy2 = "SELECT  cm_id,process,new_client_master.client_name, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id where  new_client_master.client_name='" . $client_id . "'  and new_client_master.location='" . $Loggin_location . "' and   new_client_master.cm_id not In($cm_id)";
							$sqlBy2 = "SELECT  cm_id,process,new_client_master.client_name, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id where  new_client_master.client_name=?  and new_client_master.location=? and   new_client_master.cm_id not In(?)";
							$stselect = $conn->prepare($sqlBy2);
							$stselect->bind_param("ssi",  $client_id, $Loggin_location, $cm_id);
							$stselect->execute();
							$resultBy2 = $stselect->get_result();
					?>

							<div class="input-field col s6 m6 ">
								<select id="client_to" name="new_process">
									<option value="NA">----Select----</option>
									<?php
									// $resultBy2 = $myDB->rawQuery($sqlBy2);
									// $mysql_error = $myDB->getLastError();
									// $rowCount = $myDB->count;
									if ($resultBy2 && $resultBy2->num_rows > 0) {
										$selec = '';
										foreach ($resultBy2 as $key => $value) {

											echo '<option value="' . $value['process'] . '"  id="' . $value['cm_id'] . '" >' . $value['Client'] . '</option>';
										}
									}

									?>
								</select>
								<label for="client_to" class="active-drop-down active">To Process</label>
							</div>
							<div class="input-field col s6 m6 ">
								<input type='hidden' name='new_cm_id' id='new_cm_id' value="">
								<input type='hidden' name='old_process_info' id='old_process_info' value="">
								<input type='hidden' name='new_process_info' id='new_process_info' value="">
								<input type="text" name="move_date" id="move_date" value="<?php echo date('Y-m-d'); ?>" readonly />
								<label for="move_date" class=" active">Move Date</label>
							</div>
							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="transfer_client" id="transfer_client" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green hidden">Transfer</button>

							</div>
						<?php }
					} else 
			      	if ($action == 'acc') {
						?>
						<div class="input-field col s12 m12 right-align ">
							<button type="submit" name="update_status" id="update_status" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Update</button>
						</div>
					<?php	} ?>
					<!-- <input  type="button" value="Cancel" name="btnCan" id="btnCan" class="button button-3d-highlight button-rounded"/>-->
				</div>

				<div id="pnlTable">
					<?php
					$processs = isset($_GET['process']);
					if ($processs && $processs != "") {

						if ($action == 'tr') {
							$process = cleanUserInput($_GET['process']);
							$cm_id = cleanUserInput($_GET['cm_id']);


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

							$ncns_query = "SELECT calc_atnd_master.EmployeeID from calc_atnd_master left outer join employee_map on calc_atnd_master.EmployeeID=employee_map.EmployeeID  where  D" . $date1 . "='A' and  D" . $date2 . "='A' and D" . $date3 . "='A' and month in (" . $months . ") and Year IN(" . $years . ") and employee_map.cm_id='" . $cm_id . "' ";


							$sqlConnect = "SELECT id,cm_id,EmployeeID,EmployeeName,client_name,clientname,Process,sub_process,designation,emp_level,emp_status from whole_details_peremp where 	process=? and cm_id=? and status='6' and location=? and EmployeeID NOT IN(select EmployeeID from tbl_oh_tooh_move where old_process=? and cm_id=? and(flag<>'AHR' and flag<>'NPR' and flag<>'FM')) and  EmployeeID NOT IN(select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where rg_status > 0 and rg_status < 9 and accept = 1 and employee_map.emp_status = 'Active' and employee_map.cm_id = ? and final_acceptance is null) ";

							$sqlConnect .= "  and EmployeeID not in (?)";

							$stmts = $conn->prepare($sqlConnect);
							$stmts->bind_param("siisiis",  $process, $cm_id, $Loggin_location, $process, $cm_id, $cm_id, $ncns_query);
							$stmts->execute();
						} else
					if ($action == 'acc') {
							$process = cleanUserInput($_GET['process']);
							if (isset($_GET['cm_id']) && trim($_GET['cm_id']) != "") {
								$cm_id = cleanUserInput($_GET['cm_id']);
							}

							$sqlConnect = "SELECT b.AH_comment, b.oh_comment,b.id as moveid,a.id,a.EmployeeID,a.EmployeeName,b.status,a.client_name,a.clientname,a.cm_id,a.Process,a.sub_process ,a.designation,a.emp_level,a.emp_status from whole_details_peremp a, tbl_oh_tooh_move b where b.EmployeeID=a.EmployeeID and	b.new_cm_id=? and b.flag='toOH' and b.status='AHApprove' and a.location=? ";
							$stmts = $conn->prepare($sqlConnect);
							$stmts->bind_param("ii", $cm_id, $Loggin_location);
							$stmts->execute();
						}
						// $result = $myDB->rawQuery($sqlConnect);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						$result = $stmts->get_result();
						if ($result->num_rows > 0) { ?>
							<div class="had-container pull-left row card dataTableInline">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Serial No.</th>
												<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll">Employee</label></th>
												<th class="hidden">EmployeeID</th>
												<th>EmployeeName</th>
												<th>Designation</th>
												<th>Level</th>

												<th>Current Process</th>
												<?php if ($action == 'acc') { ?>
													<th>AH Comment</th>
													<th>Status</th>
													<th>Comment</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody id="emplist">
											<?php
											$i = 0;
											$j = 0;
											foreach ($result as $key => $data_array) {
												$i++;

												echo '<tr>';
												echo "<td  >" . $i . "</td>";
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $j . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" >' . $data_array['EmployeeID'] . '</label></td>';
												echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"   class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
												echo '<td class="FullName">' . $data_array['EmployeeName'] . '</td>';
												echo '<td class="client_name">' . $data_array['designation'] . '</td>';
												echo '<td class="process">' . $data_array['emp_level'] . '</td>';	?>

												<input type='hidden' name='EmployeeName[<?php echo  $j; ?>]' class='pempname' value="<?php echo $data_array['EmployeeName']; ?>">

												<?php
												echo '<td class="sub_process">' . $data_array['clientname'] . ' | ' . $data_array['Process'] . ' | ' . $data_array['sub_process'] . '</td>'; 	?>
												<input type="hidden" name='cm_id' value="<?php echo $data_array['cm_id']; ?>">
												<?php
												if ($action == 'acc') {

													if ($data_array['oh_comment'] != "") {
														$oh_comment = $data_array['oh_comment'];
													}
												?>

													<td class="comment"><?php echo  $data_array['AH_comment']; ?></td>
													<td class="active_status">
														<select name="status[<?php echo  $j; ?>]" id="status<?php echo  $i; ?>">
															<option value="OHApprove" <?php if ($data_array['status'] == 'OHApprove') { ?> selected <?php } ?>>Approve</option>
															<option value="OHReject" <?php if ($data_array['status'] == 'OHReject') { ?> selected <?php } ?>>Reject</option>
														</select>
													</td>
													<td class="comment"><textarea name='oh_comment[<?php echo  $j; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea materialize-textarea-size ohcomment "><?php echo  $oh_comment; ?></textarea></td>
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
						}
					} else {
						//echo '<div id="div_error" class="slideInDown animated hidden">Please select your client:: <code >'.$error.'</code> </div>';
						echo "<script>$(function(){ toastr.info('Please select your Proces first.'); }); </script>";
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
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		$('#update_status').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast One Employee ....  </li>';
			} else {

				var checkedValue = null;
				var inputElements = document.getElementsByClassName('cb_child');
				var pempname = document.getElementsByClassName('pempname');

				var ohComment = document.getElementsByClassName('ohcomment');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						checkedValue = ohComment[i].value.trim();
						var empname = pempname[i].value.trim();
						if (checkedValue == "") {
							validate = 1;
							alert_msg += '<li> Write the comment for ' + empname + '  </li>';
							break;
						}
					}
				}

			}
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
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
		$('#btnCan').click(function() {
			$("input:checkbox").prop('checked', false);
			$('#client_to').val('NA');
			$('.statuscheck').addClass('hidden');
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
		});

		$('#div_error').removeClass('hidden');
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
			//alert( tolientid);
			var new_cm_id = $(this).children(":selected").attr("id");
			$('#new_cm_id').val(new_cm_id);
			var optiontest = $("#client_to  option:selected").text();
			$('#new_process_info').val(optiontest);
			var old_process_info = $("#queryfrom1  option:selected").text();
			$('#old_process_info').val(old_process_info);
			if (tolientid == 'NA') {
				$('#transfer_client').addClass('hidden');
			} else {
				$('#transfer_client').removeClass('hidden');
			}
		});
		$('#queryfrom1').change(function() {
			var tval2 = $('#queryfrom').val().trim();
			var tval = $('#queryfrom1').val().trim();
			var cclient_id = $(this).children(":selected").attr("id");
			//alert(cclient_id);
			$ccarray = cclient_id.split('_');
			//$('#queryfrom').val('NA');
			location.href = 'ohtooh-movement.php?process=' + tval + '&action=' + tval2 + '&client_id=' + $ccarray[0] + '&cm_id=' + $ccarray[1];
		})
		$('#queryfrom').change(function() {
			var tval2 = $(this).val().trim();
			var account_head2 = $('#account_head').val().trim();
			if (tval2 != "NA") {
				location.href = 'ohtooh-movement.php?action=' + tval2;
				/*jQuery.ajax({
						  url: <?php echo '"' . URL . '"'; ?>+'Controller/getClientList.php?account_head='+account_head2+'&action='+tval2
						}).done(function(data) { // data what is sent back by the php page
							$('#queryfrom1').html(data);
							$('#queryfrom1').val('NA');
					});*/
			}

		});
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>