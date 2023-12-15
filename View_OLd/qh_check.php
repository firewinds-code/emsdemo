<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$status = $c_sstaus = $status = '';
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$status = trim(cleanUserInput($_POST['txt_thcheck_Quality']));
		$createBy = clean($_SESSION['__user_logid']);

		if ($status != "" && $status != "NA") {
			if (isset($_POST['cb'])) {
				$checked_arr = $_POST['cb'];
				$count_check = count($checked_arr);
				if ($count_check > 0) {

					if ($_POST['batch_id'] > 0 || !empty($_POST['batch_id'])) {
						foreach ($_POST['cb'] as $val) {
							$empID = cleanUserInput($val);
							$roster_WO = '';
							if (!empty($_POST['txt_wo_date'])) {
								$txtWoDate = $_POST['txt_wo_date'];
								$txtWoDateAr = '';
								foreach ($txtWoDate as $row) {
									$txtWoDateAr .= clean($row) . '|';
								}
								$txtWoDateAr = rtrim($txtWoDateAr, '|');
								$roster_WO  = $txtWoDateAr;
								// die;
								// $roster_WO  = implode('|', $_POST['txt_wo_date']);
							}
							$roster_HO = '';
							if (!empty($_POST['txt_ho_date'])) {
								$txtHoDate = $_POST['txt_ho_date'];
								$txtHoDateAr = '';
								foreach ($txtHoDate as $rows) {
									$txtHoDateAr .= clean($rows) . '|';
								}
								$txtHoDateAr = rtrim($txtHoDateAr, '|');
								$roster_HO  = $txtHoDateAr;
								// $roster_HO  = implode('|', $_POST['txt_ho_date']);
							}

							$roster_log = "InTime :" . cleanUserInput($_POST['txt_ShiftIn']) . ",OutTime :" . cleanUserInput($_POST['txt_ShiftOut']) . ',WO:' . $roster_WO . ',HO:' . $roster_HO;
							$myDB = new MysqliDb();
							echo $save = 'call manage_status_qh("' . $empID . '","' . $status . '","' . $createBy . '",' . ($_POST['batch_id'] . ',"' . ($_POST['txt_Remark_' . $empID]) . '","QH","' . ($_POST['txt_Date_crt_1']) . '","' . ($_POST['txt_Day_crt_1'])) . '","' . $roster_log . '")';
							$resulti = $myDB->query($save);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {

								$wolist = array();
								$holist = array();
								if (!empty($_POST['txt_wo_date'])) {
									$wolist  = cleanUserInput($_POST['txt_wo_date']);
								}
								if (!empty($_POST['txt_ho_date'])) {
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
								}
								echo "<script>$(function(){ toastr.success('QA [$status] aligned to $empID successfully.'); }); </script>";
							} else {
								echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
							}
						}
					} else {
						echo "<script>$(function(){ toastr.error('Record not updated, No Bacth assigned.'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
	}
}
if (isset($_POST['btn_refer'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = clean($_SESSION['__user_logid']);
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {

				foreach ($_POST['cb'] as $val) {
					$empID = cleanUserInput($val);
					$myDB = new MysqliDb();
					$save = 'call manage_refer_hr("' . $empID . '","' . $createBy . '","' . cleanUserInput($_POST['txt_Remark_' . $empID]) . '","QH REFER")';
					$resulti = $myDB->query($save);
					$mysql_error = $myDB->getLastError();
					if (empty($resulti)) {
						echo "<script>$(function(){ toastr.success('Employee $empID reffered to HR successfully.'); }); </script>";
					} else {
						echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
					}
				}
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
	}
}
if (isset($_POST['btn_retrain'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = clean($_SESSION['__user_logid']);
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {

				foreach ($_POST['cb'] as $val) {
					$empID = cleanUserInput($val);
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					$checkrtr_q = 'select EmployeeID from status_training where retrain_flag = 1 and EmployeeID = ?';
					$selQu = $conn->prepare($checkrtr_q);
					$selQu->bind_param("s", $empID);
					$selQu->execute();
					$check_rtr_q = $selQu->get_result();
					if ($check_rtr_q->num_rows > 0 && $check_rtr_q) {
						echo "<script>$(function(){ toastr.error('$empID not referred to Re-Training, Employee already referred in Re-Training once'); }); </script>";
					} else {
						$myDB = new MysqliDb();
						$save = 'call manage_retrain("' . $empID . '","' . $createBy . '","' . cleanUserInput($_POST['txt_Remark_' . $empID]) . '","QH RETRAIN")';
						$resulti = $myDB->query($save);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							echo "<script>$(function(){ toastr.success('Employee $empID reffered to you successfully.'); }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
						}
					}
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

<style>
	textarea.materialize-textarea {
		overflow-y: hidden;
		padding: 0px 8px;
		resize: none;
		min-height: 2rem;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Quality Head</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Align Quality Auditor</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="form-inline">
					<div class="input-field col s6 s6">
						<select id="text_trcheck_Batch" name="text_trcheck_Batch">
							<option value="NA">----Select----</option>
							<?php
							$empid = clean($_SESSION['__user_logid']);
							$loc = clean($_SESSION['__location']);
							$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM personal_details left outer join status_table on status_table.EmployeeID=personal_details.EmployeeID  left outer join batch_master on batch_master.BacthID=status_table.BatchID where  status_table.EmployeeID !=? and status_table.Status=4 and personal_details.location=? and BacthName is not null order by BacthName;';
							$batch_id = 0;
							$selectQ = $conn->prepare($sqlBy);
							$selectQ->bind_param("si", $empid, $loc);
							$selectQ->execute();
							$resultBy = $selectQ->get_result();
							// $myDB = new MysqliDb();
							// $resultBy = $myDB->query($sqlBy);
							// $error  = $myDB->getLastError();
							if ($resultBy->num_rows > 0 && $resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									$batch_id = $value['BacthID'];
									echo '<option value="' . $value['BacthID'] . '" selected >' . $value['BacthName'] . '  ' . $value['BacthID'] . ' </option>';
								}
							}

							?>
						</select>
						<label for="text_trcheck_Batch" class="active-drop-down active">Select Batch Number</label>
					</div>
					<div class="input-field col s6 s6 btnslogs">
						<input type="submit" value="Search" name="btnSave1" id="btnSave1" class="btn waves-effect waves-green" />

					</div>
					<div class="input-field col s12 s12 statuscheck">
						<hr />
					</div>
					<div class="input-field col s12 s12 no-padding statuscheck" id="accordian">

						<div class="input-field col s6 s6 statuscheck">
							<select id="txt_thcheck_Quality" name="txt_thcheck_Quality">
								<option value="NA">----Select----</option>
								<?php

								$sqlBy = 'select personal_details.EmployeeID,personal_details.EmployeeName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join  designation_master on designation_master.ID=df_master.des_id inner join personal_details on personal_details.EmployeeID=employee_map.EmployeeID inner join  status_table on status_table.EmployeeID=employee_map.EmployeeID where (Designation not in ("Senior CSA","CSA") and function_id in (10,8) and  employee_map.emp_status="Active" and personal_details.EmployeeID is not null ) and status_table.Status = 6  order by EmployeeName';

								$myDB = new MysqliDb();
								$resultBy = $myDB->query($sqlBy);
								if (count($resultBy) > 0 && $resultBy) {
									$selec = '';
									foreach ($resultBy as $key => $value) {

										echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '  ( ' . $value['EmployeeID'] . ' ) </option>';
									}
								}

								?>
							</select>
							<label for="txt_thcheck_Quality" class="active-drop-down active">Quality (OJT)</label>
						</div>
						<div class="input-field col s6 s6 statuscheck">
							<input name="start_date_cir" readonly="true" value="<?php echo date('Y-m-d', time()); ?>" />
							<label for="start_date_cir">OJT Start Date</label>
						</div>

						<div class="input-field col s6 s6" id="div_date_1">
							<input type="text" id="txt_Date_crt_1" name="txt_Date_crt_1" readonly />
							<label for="txt_Date_crt_1">OJT End Date</label>
						</div>
						<div class="input-field col s6 s6" id="div_duration_1">
							<input type="text" id="txt_Day_crt_1" name="txt_Day_crt_1" readonly="" />
							<label for="txt_Day_crt_1">OJT Days</label>

						</div>
						<div class="input-field col s6 s6 statuscheck" id="shif_div1">

							<select id="txt_ShiftIn" name="txt_ShiftIn">
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
							<label for="txt_ShiftIn" class="active-drop-down active">Shift IN</label>
						</div>
						<div class="input-field col s6 s6 statuscheck" id="shif_div2">
							<select id="txt_ShiftOut" name="txt_ShiftOut" readonly="true">
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

					</div>

					<div class="input-field col s12 m12 no-padding statuscheck">
						<div class="input-field col s6 m6 statuscheck">

							<input type="submit" value="Refer To HR" name="btn_refer" id="btn_refer" title="All the checked Employee Refer To HR" class="btn waves-effect waves-light green lighten-1" />
							<input type="submit" value="Retrain " name="btn_retrain" id="btn_retrain" title="All the checked Employee Refer To You to retrain in Employee CheckList" class="btn waves-effect waves-light red darken-1" />

						</div>
						<div class="input-field col s6 m6 statuscheck right-align">

							<input type="submit" value="Send To QA" name="btnSave" id="btnSave" class="btn waves-effect waves-green" />
							<input type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect waves-red close-btn" />
						</div>
					</div>

					<?php
					if (isset($_POST['btnSave1'])) {
						$batch_id = cleanUserInput($_POST['text_trcheck_Batch']);
					}
					?>
					<input type="hidden" id="batch_id" name="batch_id" value="<?php echo $batch_id; ?>" />
					<div id="pnlTable" class="col s12 m12 card">
						<?php
						$EmpID = clean($_SESSION['__user_logid']);
						$sqlConnect = 'call get_qhchecklist("' . $EmpID . '",' . $batch_id . ')';
						$myDB = new MysqliDb();
						$result = $myDB->query($sqlConnect);
						//echo $sqlConnect;
						$error = $myDB->getLastError();
						if (count($result) > 0 && $result) { ?>

							<div class="flow-x-scroll">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
											<th class="hidden">Employee ID</th>
											<th>Employee Name</th>
											<th>Client</th>
											<th>Process</th>
											<th>Sub Process</th>
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
											echo '<td class="Remark no-padding input-field" ><textarea type="text" style="min-width:200px;" class="materialize-textarea" name="txt_Remark_' . $value['EmployeeID'] . '" id="txt_Remark_' . $value['EmployeeID'] . '" class="txt_remark" placeholder="Enter Remarks for ' . $value['EmployeeName'] . '" ></textarea></td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						<?php
						} else {
							echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments. " . $error . " '); }); </script>";
						}


						?>

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
		$('select').formSelect();
		$('input[type = "text"]').change(function() {
			if ($(this).val().length > 0) {
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
				toastr.info('Check atleast on Employee');
			}
			if ($('#txt_thcheck_Quality').val() == '' || $('#txt_thcheck_Quality').val() == 'NA') {
				$('#txt_thcheck_Quality').closest('div').addClass('has-error');
				$('#txt_ShiftIn').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				validate = 1;
				toastr.info('Quality Should not be empty');
			}
			if ($('#txt_Date_crt_1').val() == '') {
				validate = 1;
				$('#txt_ShiftIn').addClass("has-error");
				toastr.info('select Final date for Certification');
			}
			if ($('#txt_ShiftIn').val() == 'NA') {
				validate = 1;
				$('#txt_ShiftIn').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				toastr.info('Select Shift for training');

			}
			$('input.cb_child:checkbox:checked').each(function() {
				if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 100) {
					validate = 1;
					toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
				}



			});
			if (validate == 1) {

				return false;
			}
		});
		$('#btn_refer,#btn_retrain').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				toastr.info('Check atleast on Employee');
			}
			$('input.cb_child:checkbox:checked').each(function() {
				if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 100) {
					validate = 1;
					toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
				}



			});


			if (validate == 1) {
				return false;
			}
		});
		$('#btnCan').click(function() {
			$("input:checkbox").prop('checked', false);
			$('#txt_thcheck_Quality').val('NA');
			$('.statuscheck').addClass('hidden');
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
		});
		$("input:checkbox").click(function() {
			if ($('input:checkbox:checked').length > 0) {
				checklistdata();
				getBatch_state();
			} else {
				$('#txt_thcheck_Quality').val('No');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
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
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}


			});
			$('select').formSelect();
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
				$('#txt_thcheck_Quality').val('NA');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
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
			$('.btnslogs').removeClass('hidden');
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();
		});
		<?php

		if (isset($_POST['text_trcheck_Batch'])) {
			echo "$('#text_trcheck_Batch').val('" . $batch_id . "');";
		}
		if ($batch_id == 0) {
			echo "$('.btnslogs').addClass('hidden');";
		}
		?>
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

	function getBatch_state() {
		var batchid = $("#text_trcheck_Batch").val();
		var location = <?php echo $_SESSION["__location"] ?>;
		$.ajax("../Controller/getBatchState.php?type=ojt&id=" + batchid + "&loc=" + location).done(function(result) {

			if (!(result == 0 || isNaN(result))) {

				$("#txt_batch_sd").val(result - 1);

				var dateText = $("input[name='start_date_cir']").val();

				var dateText_temp = new Date(dateText.split('-').join(','));

				dateText_temp.setDate(dateText_temp.getDate() + parseInt(result - 1));
				dateText = date_to_mysql(dateText_temp);
				$('#txt_Date_crt_1').val(dateText);

				var d1 = new Date(dateText.split('-').join(','));


				// get date from other text field
				var today = new Date();
				var d2 = new Date(today.getFullYear(), today.getMonth(), today.getDate());



				// d2 -d1 gives result in milliseconds
				// calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
				// and populate it to some text field #textfield
				createWO(new Date(d2), new Date(d1));
				selectSunday(new Date(d2), new Date(d1));
				if (d2.getTime() == d1.getTime()) {

					$('#txt_Day_crt_1').val(parseInt(Math.abs((today - d1) / 86400000) + 1));
				} else {


					$('#txt_Day_crt_1').val(parseInt(Math.abs((today - d1) / 86400000) + 2));
				}
				$('#txt_Date_crt_1').datepicker("destroy");
				$('#txt_Date_crt_1').datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0,
					maxDate: "+" + result - 1 + "d",
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
						selectSunday(new Date(d2), new Date(d1));
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

					}
				});

			} else {
				$("#txt_batch_sd").val(0);
				$('#txt_Date_crt_1').val("");
				$('#txt_Date_crt_1').datepicker("destroy");
				$("#ho_chunks").html("");
				$("#wo_chunks").html("");
				toastr.info('OJT days is 0 for this Process.');
			}
			$('select').formSelect();
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
		});


	}
	$('#wo_chunks').on('click', 'input[name ^= "txt_wo_date"]', function() {

		var alter = 0;
		if ($(this).prop("checked") == true) {
			var datachk = $(this).val();
			var dt_elm = $(this);
			$("#ho_chunks").children("input[type='checkbox']:checked").each(function() {


				if ($(this).val() == datachk) {
					dt_elm.prop("checked", false);
					alter = 1;

				}
			});
		}


		if (alter == 0) {



			if ($(this).prop("checked") == true) {
				var dateText = $("#txt_Date_crt_1").val();

				var dateText_temp = new Date(dateText.split('-').join(','));

				dateText_temp.setDate(dateText_temp.getDate() + parseInt(1));
				dateText = date_to_mysql(dateText_temp);
				$('#txt_Date_crt_1').val(dateText);

				var date_apart = dateText.replace('-', '_');
				elements1 = "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_wo_date[]' id='txt_wo_date" + date_apart + "' value=" + dateText + " /><label for='txt_wo_date" + date_apart + "' >" + dateText_temp.getDate() + "</label></div>";
				elements2 = "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_ho_date[]' id='txt_ho_date" + date_apart + "' value=" + dateText + " /><label for='txt_ho_date" + date_apart + "'>" + dateText_temp.getDate() + "</label></div>";


				$('#wo_chunks').append(elements1);
				$('#ho_chunks').append(elements2);
			} else {
				var dateText = $("#txt_Date_crt_1").val();

				var dateText_temp = new Date(dateText.split('-').join(','));

				dateText_temp.setDate(dateText_temp.getDate() - parseInt(1));
				dateText = date_to_mysql(dateText_temp);
				$('#txt_Date_crt_1').val(dateText);

				var date_apart = dateText.replace('-', '_');
				/*$('#wo_chunks').children("input").last().remove();
				$('#wo_chunks').children("label").last().remove();
				
				$('#ho_chunks').children("input").last().remove();
				$('#ho_chunks').children("label").last().remove();*/
				$('#wo_chunks').children(".input_chunk").last().remove();

				$('#ho_chunks').children(".input_chunk").last().remove();

			}
		}
		$('select').formSelect();
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	});
	$('#ho_chunks').on('click', 'input[name ^= "txt_ho_date"]', function() {


		var alter = 0;
		if ($(this).prop("checked") == true) {
			var datachk = $(this).val();
			var dt_elm = $(this);
			$("#wo_chunks").children("input[type='checkbox']:checked").each(function() {


				if ($(this).val() == datachk) {
					dt_elm.prop("checked", false);
					alter = 1;

				}
			});
		}


		if (alter == 0) {



			if ($(this).prop("checked") == true) {
				var dateText = $("#txt_Date_crt_1").val();

				var dateText_temp = new Date(dateText.split('-').join(','));

				dateText_temp.setDate(dateText_temp.getDate() + parseInt(1));
				dateText = date_to_mysql(dateText_temp);
				$('#txt_Date_crt_1').val(dateText);

				var date_apart = dateText.replace('-', '_');
				elements1 = "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_wo_date[]' id='txt_wo_date" + date_apart + "' value=" + dateText + " /><label for='txt_wo_date" + date_apart + "' >" + dateText_temp.getDate() + "</label></div>";
				elements2 = "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_ho_date[]' id='txt_ho_date" + date_apart + "' value=" + dateText + " /><label for='txt_ho_date" + date_apart + "'>" + dateText_temp.getDate() + "</label></div>";


			} else {
				var dateText = $("#txt_Date_crt_1").val();

				var dateText_temp = new Date(dateText.split('-').join(','));

				dateText_temp.setDate(dateText_temp.getDate() - parseInt(1));
				dateText = date_to_mysql(dateText_temp);
				$('#txt_Date_crt_1').val(dateText);

				var date_apart = dateText.replace('-', '_');
				/*$('#wo_chunks').children("input").last().remove();
				$('#wo_chunks').children("label").last().remove();
				
				$('#ho_chunks').children("input").last().remove();
				$('#ho_chunks').children("label").last().remove();*/
				$('#wo_chunks').children(".input_chunk").last().remove();
				$('#ho_chunks').children(".input_chunk").last().remove();

			}
		}
		$('select').formSelect();
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	});

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

			elements1 = elements1 + "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_wo_date[]' id='txt_wo_date" + date_apart + "' value=" + date_on + " /><label for='txt_wo_date" + date_apart + "' >" + dateObj1.getDate() + "</label></div>";
			elements2 = elements2 + "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_ho_date[]' id='txt_ho_date" + date_apart + "' value=" + date_on + " /><label for='txt_ho_date" + date_apart + "'>" + dateObj1.getDate() + "</label></div>";


			dateObj1.setDate(dateObj1.getDate() + 1);
		}

		$('#wo_chunks').html(elements1);
		$('#ho_chunks').html(elements2);

		//return count;
	}

	function selectSunday(date1, date2) {
		var dateObj1 = date1;
		var dateObj2 = date2;

		while (dateObj1.getTime() <= dateObj2.getTime()) {
			if (dateObj1.getDay() == 0) {
				var dateText = date_to_mysql(dateObj1);
				var date_apart = dateText.replace('-', '_');

				var txtId = 'txt_wo_date' + date_apart;
				$("#" + txtId).trigger("click");
			}
			dateObj1.setDate(dateObj1.getDate() + 1);
		}
	};
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>