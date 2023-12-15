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
$old_client = $new_client = $move_date = $tcid_array = '';
$classvarr = "'.byID'";
$searchBy = '';
$client_name = '';
$cm_id = '';
$date = date("Y-m-d h:i:s");
if (isset($_GET['cm_id']) && $_GET['cm_id'] != '') {
	$client_name = cleanUserInput($_GET['cm_id']);
}
$action = '';
if (isset($_GET['action'])) {
	$action =  cleanUserInput($_GET['action']);
}
$msg = '';
$updatedBy = clean($_SESSION['__user_logid']);
$Loggin_location = clean($_SESSION["__location"]);
?>
<input type='hidden' id='account_head' name='account_head' value='<?php echo $updatedBy; ?>'>
<?php
if (isset($_POST['update_status'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$status =  cleanUserInput($_POST['status']);
		$new_client = $old_client =  cleanUserInput($_POST['old_client']);
		//$new_client =$_POST['new_client'];	

		$flag = "toHR";
		$moveid = "";
		$update_true = 0;
		if (isset($_POST['tcid'])) {
			$date = date("Y-m-d h:i:s");
			$checked_arr = cleanUserInput($_POST['tcid']);
			// $count_check = count($status);
			if ($old_client != "") {
				$max_key = max(array_keys($_POST['ah_comment']));
				$min_key = min(array_keys($_POST['ah_comment']));
				for ($i = $max_key; $i >= $min_key; $i--) {

					if (isset($checked_arr[$i]) && $checked_arr[$i] != "") {
						$empID = $checked_arr[$i];
						$ah_comment = trim(addslashes($_POST['ah_comment'][$i]));
						$moveid =	$_POST['moveid'][$i];
						if ($status[$i] == 'AHReject') {
							$flag = 'NCR';
						} else
					if ($status[$i] == 'AHApprove') {
							$flag = 'NCA';
						}
						if ($ah_comment != "") {

							$save = "UPDATE tbl_client_toclient_move set status='" . $status[$i] . "',flag=?,AH_updated_on=?,ah_comment=?,AH_updated_by=?,Updated_by=?,updated_on=? where new_cm_id=? and EmployeeID='" . $checked_arr[$i] . "' and flag='toNC' and id=?";
							$upQ = $conn->prepare($save);
							$upQ->bind_param("issssssi", $flag, $date, $ah_comment, $updatedBy, $updatedBy, $date, $old_client, $moveid);
							$upQ->execute();
							$resultBy = $upQ->get_result();
							// $resultBy = $myDB->rawQuery($save);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;
							$update_true = 1;
						}
					}
				}
				if (!empty($resultBy) && $update_true == 1) {
					echo "<script>$(function(){ toastr.success('Data Updated Successfully...'); }); </script>";
				} else {
					//$msg='<p class="text-danger">Data Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
					echo "<script>$(function(){ toastr.error('Data Not Updated :-'); }); </script>";
				}
			}
		}
	}
}
/**
ENd  coding 
 */

if (isset($_POST['transfer_client'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		//print_r($_POST);
		$old_client = cleanUserInput($_POST['old_client']);
		$new_client = cleanUserInput($_POST['new_client']);
		$move_date = cleanUserInput($_POST['move_date']);
		$move_date = cleanUserInput($_POST['move_date']);
		$new_process_info = cleanUserInput($_POST['new_process_info']);
		$old_process_info = cleanUserInput($_POST['old_process_info']);
		$flag = "toHR";
		$tableContent = "";
		$Body = "";
		if ($old_client != $new_client) {
			if (isset($_POST['tcid'])) {
				$checked_arr = cleanUserInput($_POST['tcid']);
				if ($old_client != "") {
					$max_key = max(array_keys($_POST['EmployeeName']));
					$min_key = min(array_keys($_POST['EmployeeName']));
					$notAplicable = "";
					$notSalaryslab = $notVersant = $notBGV = '';
					$Applicable = '';
					$empID = '';
					$rowCountTransfer = "";
					for ($p = $max_key; $p >= $min_key; $p--) {
						if (isset($checked_arr[$p]) && $checked_arr[$p] != "") {
							$empID = $checked_arr[$p];
							$empname = cleanUserInput($_POST['EmployeeName']);
							$employee_name = $empname[$p];
							$chekDf = "select EmployeeID from employee_map map inner join df_master on df_master.df_id = map.df_id where EmployeeID = ? and (map.df_id in (select df_id  from employee_map where cm_id = ?   or df_master.function_id in (7,8,10) ))";

							$resulti = 0;
							$selectQury = $conn->prepare($chekDf);
							$selectQury->bind_param("ss", $empID, $new_client);
							$selectQury->execute();
							$resultBy = $selectQury->get_result();
							// $myDB = new MysqliDb();
							// $resultBy = $myDB->rawQuery($chekDf);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;
							if (!empty($resultBy) && $resultBy->num_rows > 0) {
								$mysquery = "select ctc from salary_details s inner join whole_details_peremp w on w.EmployeeID=s.EmployeeID where s.EmployeeID=? and (CAST(SUBSTRING_INDEX(ctc, '-', -1) AS UNSIGNED)  <= (select CAST(SUBSTRING_INDEX(max_lim, '-', -1) AS UNSIGNED) as maxslab from tbl_salary_slab_by_cps  where cm_id=?) or w.des_id not  in (9,12) )";
								$selQ = $conn->prepare($mysquery);
								$selQ->bind_param("si", $empID, $new_client);
								$selQ->execute();
								$resultBy = $selQ->get_result();
								// $myDB = new MysqliDb();
								// $resultBy = $myDB->rawQuery($mysquery);
								// $mysql_error = $myDB->getLastError();
								// $rowCount = $myDB->count;

								if (!empty($resultBy) && $resultBy->num_rows > 0) {
									$mysquery = "select cert_name,cm_id from certification_require_by_cmid where cm_id=?";
									$versantflag = 0;
									$selQ = $conn->prepare($mysquery);
									$selQ->bind_param("i", $new_client);
									$selQ->execute();
									$versant = $selQ->get_result();
									// $myDB = new MysqliDb();
									// $versant = $myDB->rawQuery($mysquery);
									// $mysql_error = $myDB->getLastError();
									// $versantrowCount = $myDB->count;

									if (!empty($versant) && $versant->num_rows > 0) {
										$mysquery = "select test_name from test_score where EmpID=?";
										$selQ = $conn->prepare($mysquery);
										$selQ->bind_param("s", $empID);
										$selQ->execute();
										$testname = $selQ->get_result();
										// $myDB = new MysqliDb();
										// $testname = $myDB->rawQuery($mysquery);
										// $mysql_error = $myDB->getLastError();
										// $testrowCount = $myDB->count;

										if (!empty($testname) && $testname->num_rows > 0) {
											if ($versant->num_rows == $testname->num_rows) {
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

									if ($versantflag == 1) {
										$notVersant .= $empID . ',';
									} else {
										$bgvflag = 0;

										//$myDB = new MysqliDb();
										//$testname = $myDB->rawQuery($mysquery);
										$sql = "SELECT * FROM bgv_matrix where cm_id = ? and desig = (select case when df_id=74 then 'CSA' else 'Support' end as desig from employee_map where EmployeeID=?) and (Addr='Yes' or Edu='Yes' or Emp='Yes' or Crim='Yes');";
										$selQ = $conn->prepare($sql);
										$selQ->bind_param("is", $new_client, $empID);
										$selQ->execute();
										$query = $selQ->get_result();
										if ($query->num_rows > 0) {
											$sql = "select doc_file from doc_details where employeeid=? and doc_stype='BG verification';";
											$selQ = $conn->prepare($sql);
											$selQ->bind_param("s",  $empID);
											$selQ->execute();
											$query = $selQ->get_result();
											if ($query->num_rows <= 0) {
												$bgvflag = 1;
											}
										}
										if ($bgvflag == 1) {
											$notBGV .= $empID . ',';
										} else {
											$_POST['tcid'][$p];
											if ($_POST['tcid'][$p] != "") {
												$save = 'call add_TransferFromAccountHead("' . $empID . '","' . $old_client . '","' . $new_client . '","' . $move_date . '","' . $date . '","' . $flag . '","' . $updatedBy . '","' . $Loggin_location . '")';
												$tableContent .= "<tr><td>" . $empID . "</td><td>" . $employee_name . "</td><td>" . $old_process_info . "</td><td>" . $new_process_info . "</td><td>" . $move_date . "</td></tr>";
												$Applicable .= $empID . ',';
												$myDB = new MysqliDb();
												$resultBy = $myDB->rawQuery($save);
												$mysql_error = $myDB->getLastError();
												$rowCountTransfer = $myDB->count;
											}
										}
									}
								} else {
									$notSalaryslab .= $empID . ',';
								}
							} else {
								$notAplicable .= $empID . ',';
							}
						}
					}
					if ($notAplicable != "") {
						$notAplicable = substr($notAplicable, 0, -1);
					}
					if ($notSalaryslab != "") {
						$notSalaryslab = substr($notSalaryslab, 0, -1);
					}
					if ($Applicable != "") {
						$Applicable = substr($Applicable, 0, -1);
					}
					if ($rowCountTransfer > 0) {
						$email_array = array();

						$loca = clean($_SESSION["__location"]);
						$hr_query = "SELECT a.account_head, b.ofc_emailid as AHHR_email_id FROM new_client_master  a INNER JOIN contact_details b ON b.EmployeeID=a.account_head and a.process='Human Resource' and a.location=?";
						$selectQury = $conn->prepare($hr_query);
						$selectQury->bind_param("i", $loca);
						$selectQury->execute();
						$resultBy = $selectQury->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($hr_query);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if (!empty($resultBy) && $resultBy->num_rows > 0) {
							foreach ($resultBy as $key => $hrdata_array) {
								$email_array[] = $hrdata_array['AHHR_email_id'];
							}
						}
						$sender_data = "SELECT a.account_head, b.EmployeeID ,b.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.account_head and a.cm_id=?";
						$selQu = $conn->prepare($sender_data);
						$selQu->bind_param("i", $old_client);
						$selQu->execute();
						$resultBy = $selQu->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($sender_data);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if (!empty($resultBy) && $resultBy->num_rows > 0) {
							foreach ($resultBy as $key => $data_array_sender) {
								$email_array[] = $data_array_sender['AH_email_id'];
							}
						}

						$receive_data = "SELECT a.account_head, b.EmployeeID ,b.ofc_emailid as AH_email_id  FROM ems.contact_details b INNER JOIN ems.new_client_master a ON b.EmployeeID=a.account_head and a.cm_id=?";
						$selQu = $conn->prepare($receive_data);
						$selQu->bind_param("i", $new_client);
						$selQu->execute();
						$resultBy = $selQu->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($receive_data);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if (!empty($resultBy) && $resultBy->num_rows > 0) {
							foreach ($resultBy as $key => $data_array_receiver) {
								$email_array[] = $data_array_receiver['AH_email_id'];
							}
						}
						$loc = clean($_SESSION["__location"]);
						$pagename = 'client_to_clent_move';
						$select_email_query = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location=?";
						$unique_Array = array_unique($email_array);
						$Subject_ = 'Employee Movement Noida: ' . $new_process_info . ' ' . date('d-m-Y H:i:s');
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
							$mail->AddAddress($unique_Array[$e]);
						}
						$selQu = $conn->prepare($select_email_query);
						$selQu->bind_param("si", $pagename, $loc);
						$selQu->execute();
						$resultBy = $selQu->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($select_email_query);
						// $mysql_error = $myDB->getLastError();

						if (!empty($resultBy)) {
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
						$Body .= "Hi Team,<br>Following Client to Client movement has been initiated. Please act accordingly<br><br>
			        <table border='1'>";
						$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Current Process</b></td><td><b>New Process</b></td><td><b>Move Date</b></td></tr>";
						$Body .= $tableContent;
						$Body .= "</table><br><br>Thanks EMS Team";
						$mail->isHTML(true);
						$mail->Body = $Body;
						if (!$mail->send()) {
							$lblMMAILmsg = 'Mail could not be sent.';
							$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
							// echo $lblMMAILmsg;
						} else {
							$lblMMAILmsg = 'and Mail Send successfully.';
						}
						if ($notAplicable != "") {
							echo "<script>$(function(){ toastr.error('[" . $notAplicable . "] Employee(s) Movement can not proceed for due to cross funtion found'); }); </script>";
						}
						if ($notSalaryslab != "") {
							echo "<script>$(function(){ toastr.error('[" . $notSalaryslab . "] Employee(s) Movement can not proceed due to lower salary slab'); }); </script>";
						}
						if ($notVersant != "") {
							echo "<script>$(function(){ toastr.error('[" . $notVersant . "] Employee(s) Movement can not proceed due to Versant not updated'); }); </script>";
						}
						if ($notBGV != "") {
							echo "<script>$(function(){ toastr.error('[" . $notBGV . "] Employee(s) Movement can not proceed due to BGV not updated'); }); </script>";
						}
						if ($Applicable != "") {
							echo "<script>$(function(){ toastr.success('[" . $Applicable . "] Employee(s) Movement initiated Successfully.." . $lblMMAILmsg . "'); }); </script>";
						}
					} else {
						if ($notAplicable != "") {
							echo "<script>$(function(){ toastr.error('[" . $notAplicable . "] Employee(s) Movement can not proceed for due to cross funtion found'); }); </script>";
						}

						if ($notSalaryslab != "") {
							echo "<script>$(function(){ toastr.error('[" . $notSalaryslab . "] Employee(s) Transfer cannot be initiated due to salary capping'); }); </script>";
						}
						if ($notVersant != "") {
							echo "<script>$(function(){ toastr.error('[" . $notVersant . "] Employee(s) Movement can not proceed due to Versant not updated'); }); </script>";
						}
						if ($notBGV != "") {
							echo "<script>$(function(){ toastr.error('[" . $notBGV . "] Employee(s) Movement can not proceed due to BGV not updated'); }); </script>";
						}
					}
				} else {
					echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>Current Client not Selected ...'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Employee Not Transfter ::Error :- <code>Current Client and new Client are same ...'); }); </script>";
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
	<span id="PageTittle_span" class="hidden">Employee Movement Client to Client </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement Client to Client </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s6 m6 ">
					<select id="queryfrom" name="getaction" disabled>
						<option value="tr" <?php if ($action == 'tr') echo 'selected'; ?>>Transfer</option>
						<option value="acc" <?php if ($action == 'acc') echo 'selected'; ?>>Accept</option>
					</select>
					<label for="queryfrom" class="active-drop-down active">Action</label>
				</div>
				<div class="input-field col s6 m6 ">
					<?php
					$sqlBy = '';
					$action_move = '';
					if ($action == 'acc') {
						$action_move = 'To Client';
						$sqlBy = "SELECT  cm_id, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id  INNER JOIN tbl_client_toclient_move  ON new_client_master.cm_id=tbl_client_toclient_move.new_cm_id  where tbl_client_toclient_move.status='Approve' and new_client_master.account_head=? and new_client_master.location=? group by new_client_master.cm_id";
						$selectQury = $conn->prepare($sqlBy);
						$selectQury->bind_param("si", $updatedBy, $Loggin_location);
						$selectQury->execute();
					} else
					if ($action == 'tr') {
						$action_move = 'From Client';
						$sqlBy = "SELECT  cm_id, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id where new_client_master.account_head=?  and new_client_master.location=?";
						$selectQury = $conn->prepare($sqlBy);
						$selectQury->bind_param("si", $updatedBy, $Loggin_location);
						$selectQury->execute();
					}

					if (isset($action) and $sqlBy != "") {
						$resultBy = $selectQury->get_result();
						// $myDB = new MysqliDb();
						// $resultBy = $myDB->rawQuery($sqlBy);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
					}
					?>
					<select id="queryfrom1" name="old_client" required>
						<option value="NA">----Select----</option>
						<?php

						if (!empty($resultBy) && $resultBy->num_rows > 0) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								$select = '';
								if ($client_name != '' && $value['cm_id'] == $client_name) {
									$select = "selected";
								}
								echo '<option value="' . $value['cm_id'] . '"  ' . $select . ' >' . $value['Client'] . '</option>';
							}
						}

						?>
					</select>
					<label for="txt_Client_ach" class="active-drop-down active"><?php echo $action_move; ?></label>
				</div>
				<div class='statuscheck row'>
					<?php
					if ($action == 'tr') {
					?>
						<div class="input-field col s6 m6 8">
							<select id="client_to" name="new_client" required>
								<option value="NA">----Select----</option>
								<?php
								if (isset($_GET['cm_id']) && trim($_GET['cm_id']) != "") {
									$cm_id = cleanUserInput($_GET['cm_id']);
								}
								//   $sqlBy2 ="SELECT distinct cm_id, concat(clientname,' | ',process,' | ',sub_process) as Client FROM whole_details_peremp  where  cm_id!='".$cm_id."'  and location='".$Loggin_location."'";

								$sqlBy2 = "select ncm.cm_id,concat(cm.client_name,' | ',ncm.tprocess,' | ',ncm.sub_process) as Client from client_master cm inner join (select Distinct process as tprocess,sub_process, client_name,cm_id from new_client_master  where cm_id Not IN(select  cm_id  from client_status_master) and location=? and cm_id!=?) ncm on cm.client_id=ncm.client_name order by cm.client_name";
								$selectQ = $conn->prepare($sqlBy2);
								$selectQ->bind_param("ii", $Loggin_location, $cm_id);
								$selectQ->execute();
								$resultBy2 = $selectQ->get_result();
								// $myDB = new MysqliDb();
								// $resultBy2 = $myDB->rawQuery($sqlBy2);
								// $mysql_error = $myDB->getLastError();
								// $rowCount = $myDB->count;
								if (!empty($resultBy2) && $resultBy2->num_rows > 0) {
									$selec = '';
									foreach ($resultBy2 as $key => $value) {

										echo '<option value="' . $value['cm_id'] . '"   >' . $value['Client'] . '</option>';
									}
								}

								?>
							</select>

							<label for="txt_Client_ach" class="active-drop-down active">To Client :</label>
						</div>

						<div class="input-field col s6 m6 ">
							<input type="text" name="move_date" id="move_date" value="<?php echo date('Y-m-d'); ?>" readonly />
							<label for="move_date"> Move Date</label>
						</div>
						<div class="input-field col s12 m12 right-align">
							<input type='hidden' name='old_process_info' id='old_process_info' value="">
							<input type='hidden' name='new_process_info' id='new_process_info' value="">
							<button type="submit" name="transfer_client" id="transfer_client" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green hidden">Transfer</button>
						</div>
					<?php } else if ($action == 'acc') { ?>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" name="update_status" id="update_status" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Update</button>
						</div>
					<?php } ?>
				</div>


				<div id="pnlTable">
					<div class="had-container pull-left row card">
						<div class="">
							<?php
							if (isset($_GET['cm_id']) && $_GET['cm_id'] != "") {

								if ($action == 'tr') {
									$client_name = cleanUserInput($_GET['cm_id']);

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

									$ncns_query = "select calc_atnd_master.EmployeeID from calc_atnd_master left outer join employee_map on calc_atnd_master.EmployeeID=employee_map.EmployeeID  where  D" . $date1 . "='A' and  D" . $date2 . "='A' and D" . $date3 . "='A' and month in (" . $months . ") and Year IN(" . $years . ") and employee_map.cm_id='" . $client_name . "' ";

									$sqlConnect = "select id,EmployeeID,EmployeeName,client_name,clientname,Process,sub_process,designation,emp_status,emp_level from whole_details_peremp where 	cm_id=?  and status='6' and location=? and EmployeeID NOT IN(select EmployeeID from tbl_client_toclient_move where old_cm_id=?   and (flag<>'toONC' and flag<>'NCR' and flag<>'FM')) and  EmployeeID NOT IN(select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where rg_status > 0 and rg_status < 9 and accept = 1 and employee_map.emp_status = 'Active' and employee_map.cm_id = ? and final_acceptance is null) ";

									$sqlConnect .= "   and EmployeeID not in ($ncns_query)";

									$selectQury = $conn->prepare($sqlConnect);
									$selectQury->bind_param("iiii", $client_name, $Loggin_location, $client_name, $client_name);
									$selectQury->execute();
								} else if ($action == 'acc') {
									$client_name = cleanUserInput($_GET['cm_id']);
									$sqlConnect = "select distinct a.id,a.EmployeeID,a.EmployeeName,a.client_name,a.clientname,a.Process,a.sub_process,a.designation,a.emp_level,a.emp_status,b.hr_comment ,b.ah_comment,b.status,b.id as moveid from whole_details_peremp a, tbl_client_toclient_move b where b.EmployeeID=a.EmployeeID and	b.new_cm_id=? and b.flag='toNC' and b.status='Approve' and b.move_location=?";
									$selectQury = $conn->prepare($sqlConnect);
									$selectQury->bind_param("ii", $client_name, $Loggin_location);
									$selectQury->execute();
								}
								$result = $selectQury->get_result();
								//echo  $sqlConnect;
								// $myDB = new MysqliDb();
								// $result = $myDB->rawQuery($sqlConnect);
								// $mysql_error = $myDB->getLastError();
								// $rowCount = $myDB->count;
								if (!empty($result) && $result->num_rows > 0) { ?>

									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>SNo.</th>
												<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll"></label></th>
												<th class="hidden">EmployeeID</th>
												<th>EmployeeName</th>
												<!-- <th>Client</th>
						            				<th>Process</th>-->
												<th>Designation</th>
												<th>Level</th>
												<th>Current Client</th>
												<?php if ($action == 'acc') { ?>
													<th>HR Comment</th>
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
												echo "<td >" . $i . "</td>";
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $j . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" >' . $data_array['EmployeeID'] . '</label></td>';
												echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
												echo '<td class="FullName">' . $data_array['EmployeeName'] . '</td>';
											?>
												<input class='empclass' type='hidden' name='EmployeeName[<?php echo $j; ?>]' value="<?php echo $data_array['EmployeeName']; ?>">
												<?php
												echo '<td class="client_name">' . $data_array['designation'] . '</td>';
												echo '<td class="process">' . $data_array['emp_level'] . '</td>';
												echo '<td class="sub_process">' . $data_array['clientname'] . ' | ' . $data_array['Process'] . ' | ' . $data_array['sub_process'] . '</td>';
												if ($action == 'acc') { ?>
													<td class="comment"><?php echo  $data_array['hr_comment']; ?></td>
													<td class="active_status">
														<select name="status[<?php echo $j; ?>]" id="status<?php echo  $j; ?>">
															<option value="AHApprove" <?php if ($data_array['status'] == 'AHApprove') {
																							echo "selected";
																						} ?>>Approve</option>
															<option value="AHReject" <?php if ($data_array['status'] == 'AHReject') {
																							echo "selected";
																						} ?>>Reject</option>
														</select>
													</td>
													<td class="comment"><textarea name='ah_comment[<?php echo $j; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea materialize-textarea-size ahcomment "><?php echo  stripcslashes($data_array['ah_comment']); ?></textarea></td>
													<input type="hidden" name='moveid[<?php echo  $j; ?>]' id='moveid<?php echo  $j; ?>' class='moveid' value="<?php echo $data_array['moveid']; ?>">
											<?php
												}
												echo '</tr>';
												$j++;
											}



											?>
											<script>
												$("input:checkbox").click(function() {
													if ($('input.cb_child:checkbox:checked').length > 0) {

														var tcount = $('input.cb_child:checkbox:checked').length;

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
									//echo '<div id="div_error" class="slideInDown animated hidden">Data not found </div>';
									echo "<script>$(function(){ toastr.error('Data not found for movement'); }); </script>";
								}
							} else {
								//echo '<div id="div_error" class="slideInDown animated hidden">Please select your client:: <code >'.$error.'</code> </div>';
								echo "<script>$(function(){ toastr.info('Please select your client first.'); }); </script>";
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
		}


		$('#update_status').click(function() {
			//alert('tttttt');

			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast One Employee ....  </li>';
			} else {

				var checkedValue = null;
				var inputElements = document.getElementsByClassName('cb_child');
				var ahComment = document.getElementsByClassName('ahcomment');
				var empclass = document.getElementsByClassName('empclass');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						checkedValue = ahComment[i].value.trim();
						empname = empclass[i].value.trim();
						if (checkedValue == "") {
							validate = 1;
							alert_msg += '<li> Write the comment for ' + empname + '</li>';
							break;
						}
					}
				}

			}
			if (validate == 1) {
				//$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				//$('#alert_message').show().attr("class","SlideInRight animated");
				//$('#alert_message').delay(5000).fadeOut("slow");

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
		});

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
			//$('#queryfrom').val('NA');
			location.href = 'ctc?cm_id=' + tval + '&action=' + tval2;
		})
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>