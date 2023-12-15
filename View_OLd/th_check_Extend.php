<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');


$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

//Mail function
// require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
// require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

if (isset($_SESSION)) {
	$clean_user_login = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_login)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

// Global variable used in Page Cycle
$last_to = $last_from = $last_to = $dept = $emp_nam = $status = $msg = $searchBy = '';
$classvarr = "'.byID'";
$clean_user_login = clean($_SESSION['__user_logid']);

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$status = cleanUserInput($_POST['txt_thcheck_Trainer']);
		$createBy = $clean_user_login;
		$_POST['cb'] =  implode(" ", $_POST['cb']);
		$_POST['cb'] = clean(($_POST['cb']));
		$_POST['cb'] = explode(" ", $_POST['cb']);
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			$batch_id_tr = 0;

			if ($count_check > 0) {
				$clean_text_trcheck_batch = cleanUserInput($_POST['text_trcheck_Batch']);
				if ($clean_text_trcheck_batch != 'New' && !empty($clean_text_trcheck_batch) && $clean_text_trcheck_batch != 'NA') {
					$batch_id_tr = $clean_text_trcheck_batch;

					$roster_WO = '';
					if (!empty(($_POST['txt_wo_date']))) {
						$roster_WO  = clean(implode('|', ($_POST['txt_wo_date'])));
					}
					$roster_HO = '';
					if (!empty($_POST['txt_ho_date'])) {
						$roster_HO  = clean(implode('|', $_POST['txt_ho_date']));
					}
					$clean_txt_shiftIN = cleanUserInput($_POST['txt_ShiftIn']);
					$clean_shiftOut = cleanUserInput($_POST['txt_ShiftOut']);
					$roster_log = "InTime :" . $clean_txt_shiftIN . ",OutTime :" . $clean_shiftOut . ',WO:' . $roster_WO . ',HO:' . $roster_HO;
					foreach ($_POST['cb'] as $val) {
						$empID = clean($val);
						$myDB = new MysqliDb();
						$clean_date_crt = cleanUserInput($_POST['txt_Date_crt_1']);
						$clean_day_crt1 = cleanUserInput($_POST['txt_Day_crt_1']);
						$clean_txt_remark = cleanUserInput($_POST['txt_Remark_' . $empID]);
						$date_1_crt = (!empty($clean_date_crt) ? '"' . $clean_date_crt . '"' : "NULL");
						$save = 'call manage_status_th_extended("' . $empID . '","' . $status . '","' . $createBy . '","' . $clean_txt_remark . '",' . $date_1_crt . ',"' . intval($clean_day_crt1) . '","' . $roster_log . '")';


						$resulti = $myDB->query($save);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							$wolist = array();
							$holist = array();
							$clean_txt_wo_date = cleanUserInput($_POST['txt_wo_date']);
							if (!empty($clean_txt_wo_date)) {
								$wolist  = cleanUserInput($_POST['txt_wo_date']);
							}
							$clean_txt_ho_date = cleanUserInput($_POST['txt_ho_date']);
							if (!empty($clean_txt_ho_date)) {
								$holist  = cleanUserInput($_POST['txt_ho_date']);
							}

							$begin = new DateTime(cleanUserInput($_POST['start_date_cir']));
							$end   = new DateTime(cleanUserInput($_POST['txt_Date_crt_1']));

							for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {


								if (in_array($i->format("Y-m-d"), $holist)) {
									$intime_roster = 'HO';
									$outtime_roster = 'HO';
								} else if (in_array($i->format("Y-m-d"), $wolist)) {
									$intime_roster = 'WO';
									$outtime_roster = 'WO';
								} else {
									$intime_roster = cleanUserInput($_POST['txt_ShiftIn']);
									$outtime_roster = cleanUserInput($_POST['txt_ShiftOut']);
								}
								$str_insert_ros = 'call  sp_insert_roster_backdate("' . $empID . '","' . $i->format("Y-m-d") . '","' . $intime_roster . '","' . $outtime_roster . '","1","WFOB")';

								$myDB = new MysqliDb();
								$myDB->query($str_insert_ros);
								$mnError = $myDB->getLastError();
							}
							echo "<script>$(function(){ toastr.success('Congratulations, selected employee Extanted successfully.'); }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Record not updated " . $mysql_error . "'); }); </script>";;
						}
					}
					$myDB = new MysqliDb();
					$pagename = 'th_check_Extend';
					// $select_email_array = $myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'");


					$selectemailarrayQuery = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";

					$stmt = $conn->prepare($selectemailarrayQuery);
					$stmt->bind_param("s", $pagename);
					if (!$stmt) {
						echo "failed to run";
						die;
					}
					$stmt->execute();
					$result = $stmt->get_result();
					//$count = $result->num_rows;


					// $mail = new PHPMailer;
					// $mail->isSMTP();
					// $mail->Host = EMAIL_HOST;
					// $mail->SMTPAuth = EMAIL_AUTH;
					// $mail->Username = EMAIL_USER;
					// $mail->Password = EMAIL_PASS;
					// $mail->SMTPSecure = EMAIL_SMTPSecure;
					// $mail->Port = EMAIL_PORT;
					// $mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);

					//if (count($select_email_array) > 0) {
					if ($result->num_rows > 0) {
						//foreach ($select_email_array as $key => $email_array) {
						foreach ($result as $key => $email_array) {
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
					$myDB = new MysqliDb();
					$dt_mail = $myDB->query("select distinct ofc_emailid,contact_details.EmployeeID from contact_details inner join ( select distinct account_head EmployeeID from whole_details_peremp where BatchID = '" . $clean_text_trcheck_batch . "' union all  select distinct th from whole_details_peremp where BatchID = '" . $clean_text_trcheck_batch . "' union all  select distinct oh from whole_details_peremp where BatchID = '" . $clean_text_trcheck_batch . "') td  on td.EmployeeID = contact_details.EmployeeID where  contact_details.EmployeeID  != 'CE03070003' and contact_details.EmployeeID  != 'CE07147134' and ofc_emailid != ''");

					if (count($dt_mail) > 0 && $dt_mail) {
						foreach ($dt_mail as $key => $ofcEmail) {
							if (!empty($ofcEmail['ofc_emailid']) && filter_var($ofcEmail['ofc_emailid'], FILTER_VALIDATE_EMAIL)) {
								$mail->AddAddress($ofcEmail['ofc_emailid']);
							}
						}
					}
					$count = 0;
					// $myDB = new MysqliDb();
					// $dt_batch = $myDB->query("select BacthName,process,subprocess from batch_master where BacthID = '" . $clean_text_trcheck_batch . "'");

					$BacthID = $clean_text_trcheck_batch;
					$DtBatchQuery = "select BacthName,process,subprocess from batch_master where BacthID = ?";

					$stmt = $conn->prepare($DtBatchQuery);
					$stmt->bind_param("i", $BacthID);
					if (!$stmt) {
						echo "failed to run";
						die;
					}
					$stmt->execute();
					$dt_batch = $stmt->get_result();
					$row = $dt_batch->fetch_row();



					$table = '<table border="1" colspacing=0><htead><tr><th>Sr No.</th><th>EmployeeID</th><th>Trainer</th><th>Batch</th><th>Process</th><th>Sub Process</th><th>End Training</th></tr></thead><tbody>';
					if (isset($_POST['cb'])) {
						foreach ($_POST['cb'] as $val) {
							$count++;
							$table .= '<tr>';
							$table .= '<td>' . $count . '</td>';
							$table .= '<td>' . clean($val) . '</td>';
							$table .= '<td>' . clean($_POST['txt_thcheck_Trainer']) . '</td>';
							// $table .= '<td>' . $dt_batch[0]['BacthName'] . '</td>';
							// $table .= '<td>' . $dt_batch[0]['process'] . '</td>';
							// $table .= '<td>' . $dt_batch[0]['subprocess'] . '</td>';
							$table .= '<td>' . $row[0] . '</td>';
							$table .= '<td>' . $row[1] . '</td>';
							$table .= '<td>' . $row[2] . '</td>';
							$table .= '<td>' . cleanUserInput($_POST['txt_Date_crt_1']) . '</td>';

							$table .= '</tr>';
						}
					}
					$table .= '</tbody></table>';
					$mail->Subject = 'EMS ' . EMS_CenterName . ', Alert for batch extension [' . date('d M,Y', time()) . ']';
					$mail->isHTML(true);
					$mysqlError = $myDB->getLastError();
					$pwd_ = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear ALL,<br/><br/><span><b>Please find the List of extended batch from EMS Today.</b></span><br /><br/><div style="float:left;width:100%;">' . $table . '</div><div style="float:left;width:100%;"><br /><br /><br />' . 'Regards,<br /><br/> EMS Noida.<div>';

					$mail->Body = $pwd_;
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated, No Bacth assigned.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
	}
}

?>
<script>
	$(document).ready(function() {
		$('.statuscheck').addClass('hidden');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Training Head Extend</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Align Trainer</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="col s12 m12">
					<div class="input-field col s12 m12">
						<select id="text_trcheck_Batch" name="text_trcheck_Batch">
							<option value="NA">----Select----</option>
							<?php
							$clean_text_trcheck_batch = cleanUserInput($_POST['text_trcheck_Batch']);
							// $sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID left outer join batch_master on batch_master.BacthID=status_training.BatchID  where batch_master.createdby="' . $clean_user_login . '" and status_training.createdby ="' . $clean_user_login . '" and status_table.Status=3 and status_training.status= "NO"';

							$empID  = $clean_user_login;
							$sqlByQuery = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID left outer join batch_master on batch_master.BacthID=status_training.BatchID  where batch_master.createdby=? and status_training.createdby =? and status_table.Status=3 and status_training.status= "NO"';

							$stmt = $conn->prepare($sqlByQuery);
							$stmt->bind_param("ss", $empID, $empID);
							if (!$stmt) {
								echo "failed to run";
								die;
							}
							$stmt->execute();
							$resultBy = $stmt->get_result();
							$count = $resultBy->num_rows;
							//echo $sqlBy;
							$batch_id = 0;

							// $myDB = new MysqliDb();
							// $resultBy = $myDB->query($sqlBy);
							// $error = $myDB->getLastError();
							//if (count($resultBy) > 0 && $resultBy) {
							if ($count > 0 && $resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									if (isset($clean_text_trcheck_batch)) {
										$batch_id = $clean_text_trcheck_batch;
									}

									if ($batch_id == $value['BacthID']) {
										echo '<option value="' . $value['BacthID'] . '" selected >' . $value['BacthName'] . '</option>';
									} else {
										echo '<option value="' . $value['BacthID'] . '" >' . $value['BacthName'] . '</option>';
									}
								}
							}

							?>
						</select>
						<label for="text_trcheck_Batch" class="active-drop-down active">Select Batch Number</label>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button class="btn waves-effect waves-green hidden" id="btnChange" name="btnChange">Search</button>
					</div>

					<div class="input-field col s6 m6 statuscheck">

						<select id="txt_thcheck_Trainer" name="txt_thcheck_Trainer">
							<option value="NA">----Select----</option>
							<?php

							$sqlBy = 'select personal_details.EmployeeID,personal_details.EmployeeName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join  designation_master on designation_master.ID=df_master.des_id inner join personal_details on personal_details.EmployeeID=employee_map.EmployeeID inner join  status_table on status_table.EmployeeID=employee_map.EmployeeID where (Designation not in ("Senior CSA","CSA") and function_id in (10,8,7) and  employee_map.emp_status="Active" and personal_details.EmployeeID is not null ) and status_table.Status = 6  order by EmployeeName';


							$myDB = new MysqliDb();
							$resultBy = $myDB->query($sqlBy);
							$error = $myDB->getLastError();
							if (count($resultBy) > 0 && $resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '</option>';
								}
							}

							?>
						</select>
						<label for="txt_thcheck_Trainer" class="active-drop-down active">Trainer</label>
					</div>

					<div class="input-field col s6 m6 statuscheck">
						<input type="text" name="start_date_cir" id="start_date_cir" />
						<label for="start_date_cir">Roster Affected From</label>
					</div>

					<div class="input-field col s6 m6 =" id="div_date_1">
						<input type="text" id="txt_Date_crt_1" name="txt_Date_crt_1" />
						<label for="txt_Date_crt_1">New Certification Date</label>
					</div>

					<div class="input-field col s6 m6" id="div_duration_1">
						<input type="text" id="txt_Day_crt_1" name="txt_Day_crt_1" />
						<label for="txt_Day_crt_1">Ext. Training Days</label>
					</div>

					<div class="input-field col s6 m6 statuscheck" id="shif_div1">
						<select class="form-control clsInput" id="txt_ShiftIn" name="txt_ShiftIn">
							<option Selected="True" Value="NA">---Select---</option>
							<option>0:00</option>
							<option>0:30</option>
							<option>1:00</option>
							<option>1:30</option>
							<option>2:00</option>
							<option>2:30</option>
							<option>3:00</option>
							<option>3:30</option>
							<option>4:00</option>
							<option>4:30</option>
							<option>5:00</option>
							<option>5:30</option>
							<option>6:00</option>
							<option>6:30</option>
							<option>7:00</option>
							<option>7:30</option>
							<option>8:00</option>
							<option>8:30</option>
							<option>9:00</option>
							<option>9:30</option>
							<option>10:00</option>
							<option>10:30</option>
							<option>11:00</option>
							<option>11:30</option>
							<option>12:00</option>
							<option>12:30</option>
							<option>13:00</option>
							<option>13:30</option>
							<option>14:00</option>
							<option>14:30</option>
							<option>15:00</option>
							<option>15:30</option>
							<option>16:00</option>
							<option>16:30</option>
							<option>17:00</option>
							<option>17:30</option>
							<option>18:00</option>
							<option>18:30</option>
							<option>19:00</option>
							<option>19:30</option>
							<option>20:00</option>
							<option>20:30</option>
							<option>21:00</option>
							<option>21:30</option>
							<option>22:00</option>
							<option>22:30</option>
							<option>23:00</option>
							<option>23:30</option>
						</select>
						<label for="txt_ShiftIn" class="active-drop-down active">Shift IN</label>
					</div>

					<div class="input-field col s6 m6 statuscheck" id="shif_div2">
						<select class="form-control clsInput" id="txt_ShiftOut" name="txt_ShiftOut" readonly="true">
							<option Selected="True" Value="NA">---Select---</option>
							<option>0:00</option>
							<option>0:30</option>
							<option>1:00</option>
							<option>1:30</option>
							<option>2:00</option>
							<option>2:30</option>
							<option>3:00</option>
							<option>3:30</option>
							<option>4:00</option>
							<option>4:30</option>
							<option>5:00</option>
							<option>5:30</option>
							<option>6:00</option>
							<option>6:30</option>
							<option>7:00</option>
							<option>7:30</option>
							<option>8:00</option>
							<option>8:30</option>
							<option>9:00</option>
							<option>9:30</option>
							<option>10:00</option>
							<option>10:30</option>
							<option>11:00</option>
							<option>11:30</option>
							<option>12:00</option>
							<option>12:30</option>
							<option>13:00</option>
							<option>13:30</option>
							<option>14:00</option>
							<option>14:30</option>
							<option>15:00</option>
							<option>15:30</option>
							<option>16:00</option>
							<option>16:30</option>
							<option>17:00</option>
							<option>17:30</option>
							<option>18:00</option>
							<option>18:30</option>
							<option>19:00</option>
							<option>19:30</option>
							<option>20:00</option>
							<option>20:30</option>
							<option>21:00</option>
							<option>21:30</option>
							<option>22:00</option>
							<option>22:30</option>
							<option>23:00</option>
							<option>23:30</option>
							<!--<option>WO</option>-->
						</select>
						<label for="txt_ShiftOut" class="active-drop-down active">Shift Out</label>
					</div>



					<div class="input-field col s12 m12 statuscheck" id="wo_chunks">

					</div>
					<div class="input-field col s12 m12 statuscheck" id="ho_chunks">

					</div>
					<div class="input-field col s6 m6 statuscheck">
						<span title="Information of selected Employee count" class="">Selected Employee Count:&nbsp;&nbsp;&nbsp;<b><span id="checkInfo_lbl">0</span></b></span>
					</div>

					<div class="input-field col s6 m6 right-align statuscheck">
						<input type="submit" value="Submit" name="btnSave" id="btnSave" class="btn waves-effect waves-green" />
						<input type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect modal-action modal-close waves-red close-btn" />

					</div>


					<div id="pnlTable">
						<?php
						$clean_text_trcheck_batch = cleanUserInput($_POST['text_trcheck_Batch']);
						if (!empty($clean_text_trcheck_batch)) {
							$sqlConnect = 'call get_thcheck_extendedlist("' . $clean_user_login . '","' . $clean_text_trcheck_batch . '")';
							//echo $sqlConnect;
							$myDB = new MysqliDb();
							$result = $myDB->query($sqlConnect);
							$error = $myDB->getLastError();
							if (count($result) > 0 && $result) { ?>
								<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
									<div class="">
										<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label>
													</th>
													<th class="hidden">Employee ID</th>
													<th>Employee Name</th>
													<th>Client</th>
													<th>Process</th>
													<th>Sub Process</th>
													<th>DOJ</th>
													<th>Remark</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$count = 0;
												foreach ($result as $key => $value) {
													$count++;
													echo '<tr>';
													echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmployeeID'] . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
													echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
													echo '<td class="FullName">' . $value['EmployeeName'] . '</td>';
													echo '<td class="client_name">' . $value['client_name'] . '</td>';
													echo '<td class="process">' . $value['process'] . '</td>';
													echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
													echo '<td class="doj">' . $value['dateofjoin'] . '</td>';
													echo '<td class="Remark" style="padding:0px;">
							<textarea name="txt_Remark_' . $value['EmployeeID'] . '" id="txt_Remark_' . $value['EmployeeID'] . '" class="materialize-textarea" ></textarea></td>';
													echo '</tr>';
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
						<?php
							} else {
								echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments " . $error . "'); }); </script>";
							}
						}

						?>
					</div>
				</div>


				<script>
					$(document).ready(function() {
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}

						});
						$('#btnSave').click(function() {
							var validate = 0;
							var alert_msg = '';

							if ($('input.cb_child:checkbox:checked').length <= 0) {
								validate = 1;
								toastr.info('Check Atleast On Employee');
							}
							if ($('#txt_Date_crt_1').val() == '') {
								validate = 1;

								$('#txt_Date_crt_1').addClass("has-error");
								toastr.info('Select Final date for Certification');
							}
							if ($('#txt_ShiftIn').val() == 'NA') {
								validate = 1;
								$('#txt_ShiftIn').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
								toastr.info('Select Shift for training');
							}
							if ($('#start_date_cir').val() == '') {
								validate = 1;
								$('#start_date_cir').addClass("has-error");
								toastr.info('Roster affect date should not be Empty');
							}

							if ($('#txt_Date_crt_1').val() < $('#start_date_cir').val()) {
								validate = 1;
								$('#txt_Date_crt_1').addClass("has-error");
								toastr.info('Certification extension date could not lesser than roster affect date');
							}
							if ($('#txt_thcheck_Trainer').val() == '' || $('#txt_thcheck_Trainer').val() == 'NA') {
								$('#txt_thcheck_Trainer').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
								validate = 1;
								toastr.info('Trainer Should not be empty');
							}

							$('input.cb_child:checkbox:checked').each(function() {
								if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 50) {
									validate = 1;
									toastr.info('Remark Can\'t be Empty or not less than 50 For any Checked Employee.');
								}
							});

							if (validate == 1) {

								return false;
							}

						});


						$('#btnCan').click(function() {
							$("input:checkbox").prop('checked', false);
							$('#txt_thcheck_Trainer').val('NA');
							$('.statuscheck').addClass('hidden');
							$('#docTable').html('');
							$('#docstable').addClass('hidden');
							$('#div_date_1').addClass('hidden');
							$('#div_duration_1').addClass('hidden');

							$('#txt_Date_crt_1').addClass('hidden');

							$('#div_date_2').addClass('hidden');
							$('#txt_Date_crt_2').addClass('hidden');

							$('#div_date_3').addClass('hidden');
							$('#txt_Date_crt_3').addClass('hidden');

							$('#div_date_4').addClass('hidden');
							$('#txt_Date_crt_4').addClass('hidden');

							$('#div_date_5').addClass('hidden');
							$('#txt_Date_crt_5').addClass('hidden');
							$('#txt_Date_crt_1,#txt_Date_crt_2,#txt_Date_crt_3,#txt_Date_crt_4,#txt_Date_crt_5').val('');
							$('#txt_Day_crt_1,#txt_Day_crt_2,#txt_Day_crt_3,#txt_Day_crt_4,#txt_Day_crt_5').val('');
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						});
						$("input:checkbox").click(function() {
							if ($('input:checkbox:checked').length > 0) {
								checklistdata();
								$('#div_date_1').removeClass('hidden');
								$('#div_duration_1').removeClass('hidden');
								$('#txt_Date_crt_1').removeClass('hidden');
							} else {
								$('#txt_thcheck_Trainer').val('No');
								$('.statuscheck').addClass('hidden');
								$('#docTable').html('');
								$('#docstable').addClass('hidden');

								$('#div_date_1').addClass('hidden');
								$('#div_duration_1').addClass('hidden');
								$('#txt_Date_crt_1').addClass('hidden');

								$('#div_date_2').addClass('hidden');
								$('#txt_Date_crt_2').addClass('hidden');

								$('#div_date_3').addClass('hidden');
								$('#txt_Date_crt_3').addClass('hidden');

								$('#div_date_4').addClass('hidden');
								$('#txt_Date_crt_4').addClass('hidden');

								$('#div_date_5').addClass('hidden');
								$('#txt_Date_crt_5').addClass('hidden');
							}
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						});
						$('#div_error').removeClass('hidden');
						$("#cbAll").change(function() {
							$("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
							$('select').formSelect();
						});
						$("input:checkbox").change(function() {

							$('#checkInfo_lbl').text($('input.cb_child:checkbox:checked').length)

							if ($('input.cb_child:checkbox:checked').length > 0) {
								checklistdata();
								$('#div_date_1').removeClass('hidden');
								$('#div_duration_1').removeClass('hidden');
								$('#txt_Date_crt_1').removeClass('hidden');
								if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

									$("#cbAll").prop("checked", true);
								} else {
									$("#cbAll").prop("checked", false);
								}
							} else {
								$("#cbAll").prop("checked", false);
								$('#txt_thcheck_Trainer').val('NA');
								$('.statuscheck').addClass('hidden');
								$('#docTable').html('');
								$('#docstable').addClass('hidden');

								$('#div_date_1').addClass('hidden');
								$('#div_duration_1').addClass('hidden');
								$('#txt_Date_crt_1').addClass('hidden');

								$('#div_date_2').addClass('hidden');
								$('#txt_Date_crt_2').addClass('hidden');

								$('#div_date_3').addClass('hidden');
								$('#txt_Date_crt_3').addClass('hidden');

								$('#div_date_4').addClass('hidden');
								$('#txt_Date_crt_4').addClass('hidden');

								$('#div_date_5').addClass('hidden');
								$('#txt_Date_crt_5').addClass('hidden');
							}
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						});
						$('input[type = "text"]').change(function() {
							if ($(this).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});


						$('#txt_ShiftIn').change(function() {

							var time = $('#txt_ShiftIn').val();
							var startTime = new Date();
							var parts = time.match(/(\d+):(\d+)/);
							if (parts) {
								var hours = parseInt(parts[1]),
									minutes = parseInt(parts[2])

								startTime.setHours(hours, minutes, 0, 0);
							}

							startTime.setHours(startTime.getHours() + 9, startTime.getMinutes(), 0, 0);

							var minute = '00';
							if (startTime.getMinutes() < 10) {
								minute = '0' + startTime.getMinutes();
							} else {
								minute = startTime.getMinutes();
							}
							//alert(startTime.getHours() + ':' + minute);
							$('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						});
						$('#div_date_1').addClass('hidden');
						$('#div_duration_1').addClass('hidden');
						$('#txt_Date_crt_1').addClass('hidden');

						$('#div_date_2').addClass('hidden');
						$('#txt_Date_crt_2').addClass('hidden');

						$('#div_date_3').addClass('hidden');
						$('#txt_Date_crt_3').addClass('hidden');

						$('#div_date_4').addClass('hidden');
						$('#txt_Date_crt_4').addClass('hidden');

						$('#div_date_5').addClass('hidden');
						$('#txt_Date_crt_5').addClass('hidden');
						$('#txt_Date_crt_1,#txt_Date_crt_2,#txt_Date_crt_3,#txt_Date_crt_4,#txt_Date_crt_5').val('');
						$('#txt_Day_crt_1,#txt_Day_crt_2,#txt_Day_crt_3,#txt_Day_crt_4,#txt_Day_crt_5').val('');
						$('#txt_thcheck_crNo').change(function() {

							$('#txt_Date_crt_1,#txt_Date_crt_2,#txt_Date_crt_3,#txt_Date_crt_4,#txt_Date_crt_5').val('');
							$('#txt_Day_crt_1,#txt_Day_crt_2,#txt_Day_crt_3,#txt_Day_crt_4,#txt_Day_crt_5').val('');
							$('#div_date_1').addClass('hidden');
							$('#div_duration_1').addClass('hidden');
							$('#txt_Date_crt_1').addClass('hidden');

							$('#div_date_2').addClass('hidden');
							$('#txt_Date_crt_2').addClass('hidden');

							$('#div_date_3').addClass('hidden');
							$('#txt_Date_crt_3').addClass('hidden');

							$('#div_date_4').addClass('hidden');
							$('#txt_Date_crt_4').addClass('hidden');

							$('#div_date_5').addClass('hidden');
							$('#txt_Date_crt_5').addClass('hidden');

							if ($(this).val() == '1' || $(this).val() == '2' || $(this).val() == '3' || $(this).val() == '4' || $(this).val() == '5') {
								$('#div_date_1').removeClass('hidden');
								$('#txt_Date_crt_1').removeClass('hidden');
								$('#div_duration_1').removeClass('hidden');
							}
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						});
						$('#text_trcheck_Batch').change(function() {
							$('#btnChange').click();
							$('select').formSelect();
						});
						$('#start_date_cir').datepicker({
							minDate: '-5d',
							maxDate: '+5d',
							dateFormat: 'yy-mm-dd'
						});
					});

					$('#txt_Date_crt_1').datepicker({
						dateFormat: 'yy-mm-dd',
						minDate: 0,
						onSelect: function(dateText, inst) {
							$('#txt_Date_crt_2').datepicker("option", "minDate", dateText);
							var d1 = new Date(dateText.split('-').join(','));
							// get date from other text field
							var today = new Date();
							var d2 = new Date(today.getFullYear(), today.getMonth(), today.getDate());

							// d2 -d1 gives result in milliseconds
							// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
							// and populate it to some text field #textfield
							createWO(new Date(d2), new Date(d1));

							if (d2.getTime() == d1.getTime()) {

								$('#txt_Day_crt_1').val(parseInt(Math.abs((today - d1) / 86400000) + 1));
							} else {


								$('#txt_Day_crt_1').val(parseInt(Math.abs((today - d1) / 86400000) + 2));
							}
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}

							});
							$('select').formSelect();
						}
					});
					$('#txt_Date_crt_2').datepicker({
						dateFormat: 'yy-mm-dd',
						onSelect: function(dateText, inst) {
							$('#txt_Date_crt_3').datepicker("option", "minDate", dateText);
							var d1 = new Date(dateText);
							// get date from other text field
							var d2 = new Date();
							// d2 -d1 gives result in milliseconds
							// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
							// and populate it to some text field #textfield
							if (d2.getDay() == d1.getDay()) {
								$('#txt_Day_crt_2').val(parseInt(Math.abs((d2 - d1) / 86400000)));
							} else {
								$('#txt_Day_crt_2').val(parseInt(Math.abs((d2 - d1) / 86400000)) + 1);
							}
							$('select').formSelect();
						}
					});
					$('#txt_Date_crt_3').datepicker({
						dateFormat: 'yy-mm-dd',
						onSelect: function(dateText, inst) {
							$('#txt_Date_crt_4').datepicker("option", "minDate", dateText);
							var d1 = new Date(dateText);
							// get date from other text field
							var d2 = new Date();
							// d2 -d1 gives result in milliseconds
							// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
							// and populate it to some text field #textfield
							if (d2.getDay() == d1.getDay()) {
								$('#txt_Day_crt_3').val(parseInt(Math.abs((d2 - d1) / 86400000)));
							} else {
								$('#txt_Day_crt_3').val(parseInt(Math.abs((d2 - d1) / 86400000)) + 1);
							}
							$('select').formSelect();
						}

					});
					$('#txt_Date_crt_4').datepicker({
						dateFormat: 'yy-mm-dd',
						onSelect: function(dateText, inst) {
							$('#txt_Date_crt_5').datepicker("option", "minDate", dateText);
							var d1 = new Date(dateText);
							// get date from other text field
							var d2 = new Date();
							// d2 -d1 gives result in milliseconds
							// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
							// and populate it to some text field #textfield
							if (d2.getDay() == d1.getDay()) {
								$('#txt_Day_crt_4').val(parseInt(Math.abs((d2 - d1) / 86400000)));
							} else {
								$('#txt_Day_crt_4').val(parseInt(Math.abs((d2 - d1) / 86400000)) + 1);
							}
							$('select').formSelect();
						}

					});


					$('#txt_Date_crt_5').datepicker({
						dateFormat: 'yy-mm-dd',
						onSelect: function(dateText, inst) {

							var d1 = new Date(dateText);
							// get date from other text field
							var d2 = new Date();
							// d2 -d1 gives result in milliseconds
							// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
							// and populate it to some text field #textfield

							if (d2.getDay() == d1.getDay()) {
								$('#txt_Day_crt_5').val(parseInt(Math.abs((d2 - d1) / 86400000)));
							} else {

								$('#txt_Day_crt_5').val(parseInt(Math.abs((d2 - d1) / 86400000)) + 1);
							}
							$('select').formSelect();
						}
					});

					function checklistdata() {
						//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
						$('.statuscheck').removeClass('hidden');


					}

					function date_to_mysql(date) {
						var day = date.getDate();
						var month = date.getMonth() + 1;
						var year = date.getFullYear();
						if (day < 10) {
							day = "0" + day;
						}
						if (month < 10) {
							month = "0" + month;
						}
						return year + "-" + month + "-" + day;
					}

					function createWO(date1, date2) {

						var dateObj1 = date1;
						var dateObj2 = date2;

						var count = 0;
						var elements1 = '<div class="col s1">WO List :</div>';
						var elements2 = '<div class="col s1">HO List :</div>';



						while (dateObj1.getTime() <= dateObj2.getTime()) {

							count++;
							var date_on = date_to_mysql(dateObj1);

							var date_apart = date_on.replace('-', '_');

							elements1 += "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_wo_date[]' id='txt_wo_date" + date_apart + "' value=" + date_on + " /><label for='txt_wo_date" + date_apart + "' >" + dateObj1.getDate() + "</label></div>";
							elements2 += "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_ho_date[]' id='txt_ho_date" + date_apart + "' value=" + date_on + " /><label for='txt_ho_date" + date_apart + "'>" + dateObj1.getDate() + "</label></div>";

							dateObj1.setDate(dateObj1.getDate() + 1);
						}

						$('#wo_chunks').html(elements1);
						$('#ho_chunks').html(elements2);
						$('select').formSelect();
						//return count;
					}
					$(document).on("click blur focus change", ".has-error", function() {
						$(".has-error").each(function() {
							if ($(this).hasClass("has-error")) {
								$(this).removeClass("has-error");
								$(this).next("span.help-block").remove();
								if ($(this).is('select')) {
									$(this).parent('.select-wrapper').find("span.help-block").remove();
								}
								if ($(this).hasClass('select-dropdown')) {
									$(this).parent('.select-wrapper').find("span.help-block").remove();
								}
							}
						});
					});
				</script>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>