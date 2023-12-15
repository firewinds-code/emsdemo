<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$last_to = $last_from = $last_to = $dept = $emp_nam = $status = '';
$classvarr = "'.byID'";
$searchBy = $c_sstaus = '';
$msg = '';
if (isset($_POST['btnSave'])) {


	$createBy = $_SESSION['__user_logid'];
	if (isset($_POST['cb'])) {
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		if ($count_check > 0) {
			$myDB = new MysqliDb();


			foreach ($_POST['cb'] as $val) {
				$empID = $val;
				$myDB = new MysqliDb();
				$save = 'call manage_status_th_after("' . $empID . '","' . $_POST['txt_Remark_' . $empID] . '","' . $createBy . '","TH AFTER")';
				$resulti = $myDB->query($save);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Employee $empID Refer to QH successfully.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated for $empID. $mysql_error.'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
	}
}
if (isset($_POST['btn_refer'])) {

	$createBy = $_SESSION['__user_logid'];
	if (isset($_POST['cb'])) {
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		if ($count_check > 0) {

			foreach ($_POST['cb'] as $val) {
				$empID = $val;
				$myDB = new MysqliDb();
				$save = 'call manage_refer_hr("' . $empID . '","' . $createBy . '","' . $_POST['txt_Remark_' . $empID] . '","TH AFTER RETRAIN")';
				$resulti = $myDB->query($save);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Employee  $empID reffered to HR successfully.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated for $empID.$mysql_error'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Record not updated, No Employee Selected'); }); </script>";
	}
}
if (isset($_POST['btn_retrain'])) {

	$status = trim($_POST['txt_thcheck_Trainer']);
	$createBy = $_SESSION['__user_logid'];

	if ($status != '' && $status != 'NA') {
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			$batch_id_tr = 0;

			if ($count_check > 0) {

				if ($_POST['text_trcheck_Batch'] != 'New' && !empty($_POST['text_trcheck_Batch']) && $_POST['text_trcheck_Batch'] != 'NA') {
					$batch_id_tr = $_POST['text_trcheck_Batch'];

					$myDB = new MysqliDb();
					$batch_id_new = $myDB->query('call retrain_batch_id("' . $batch_id_tr . '")');
					if (isset($batch_id_new[0]['bid'])) {
						$batch_id_new = $batch_id_new[0]['bid'];
					} else {
						$batch_id_new = '';
					}

					$roster_WO = '';
					if (!empty($_POST['txt_wo_date'])) {
						$roster_WO  = implode('|', $_POST['txt_wo_date']);
					}
					$roster_HO = '';
					if (!empty($_POST['txt_ho_date'])) {
						$roster_HO  = implode('|', $_POST['txt_ho_date']);
					}

					$roster_log = "InTime :" . $_POST['txt_ShiftIn'] . ",OutTime :" . $_POST['txt_ShiftOut'] . ',WO:' . $roster_WO . ',HO:' . $roster_HO;
					foreach ($_POST['cb'] as $val) {

						$empID = $val;
						$myDB = new MysqliDb();
						$check_rtr_q = $myDB->query('select EmployeeID from status_training where retrain_flag = 1 and EmployeeID = "' . $empID . '"');
						if (count($check_rtr_q) > 0 && $check_rtr_q) {
							echo "<script>$(function(){ toastr.error('$empID not referred to Re-Training, Employee already referred in Re-Training once'); }); </script>";
						} else {

							$date_1_crt = (!empty($_POST['txt_Date_crt_1']) ? '"' . $_POST['txt_Date_crt_1'] . '"' : "NULL");
							$date_2_crt = (!empty($_POST['txt_Date_crt_2']) ? '"' . $_POST['txt_Date_crt_2'] . '"' : "NULL");
							$date_3_crt = (!empty($_POST['txt_Date_crt_3']) ? '"' . $_POST['txt_Date_crt_3'] . '"' : "NULL");
							$date_4_crt = (!empty($_POST['txt_Date_crt_4']) ? '"' . $_POST['txt_Date_crt_4'] . '"' : "NULL");
							$date_5_crt = (!empty($_POST['txt_Date_crt_5']) ? '"' . $_POST['txt_Date_crt_5'] . '"' : "NULL");
							$txt_Day_crt_1 = $txt_Day_crt_2 = $txt_Day_crt_3 = $txt_Day_crt_4 = $txt_Day_crt_5 = '';
							if (isset($_POST['txt_Day_crt_1'])) {
								$txt_Day_crt_1 = $_POST['txt_Day_crt_1'];
							}
							if (isset($_POST['txt_Day_crt_2'])) {
								$txt_Day_crt_2 = $_POST['txt_Day_crt_2'];
							}
							if (isset($_POST['txt_Day_crt_3'])) {
								$txt_Day_crt_3 = $_POST['txt_Day_crt_3'];
							}
							if (isset($_POST['txt_Day_crt_4'])) {
								$txt_Day_crt_4 = $_POST['txt_Day_crt_4'];
							}
							if (isset($_POST['txt_Day_crt_5'])) {
								$txt_Day_crt_5 = $_POST['txt_Day_crt_5'];
							}
							$save = 'call manage_status_th("' . $empID . '","' . $status . '","' . $createBy . '",' . $batch_id_new . ',"' . addslashes($_POST['txt_Remark_' . $empID]) . '","' . $_POST['txt_thcheck_crNo'] . '",' . $date_1_crt . ',' . $date_2_crt . ',' . $date_3_crt . ',' . $date_4_crt . ',' . $date_5_crt . ',"' . intval($txt_Day_crt_1) . '","' . intval($txt_Day_crt_2) . '","' . intval($txt_Day_crt_3) . '","' . intval($txt_Day_crt_4) . '","' . intval($txt_Day_crt_5) . '","' . $roster_log . '")';

							$myDB = new MysqliDb();
							$resulti = $myDB->query($save);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								$myDB = new MysqliDb();
								$save = 'call manage_retrain("' . $empID . '","' . $createBy . '","' . addslashes($_POST['txt_Remark_' . $empID]) . '","TH AFTER RETRAIN")';
								$resulti = $myDB->query($save);

								$wolist = array();
								$holist = array();
								if (!empty($_POST['txt_wo_date'])) {
									$wolist  = $_POST['txt_wo_date'];
								}
								if (!empty($_POST['txt_ho_date'])) {
									$holist  = $_POST['txt_ho_date'];
								}

								$begin = new DateTime($_POST['start_date_cir']);
								$end   = new DateTime($_POST['txt_Date_crt_1']);

								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {


									if (in_array($i->format("Y-m-d"), $holist)) {
										$intime_roster = 'HO';
										$outtime_roster = 'HO';
									} else if (in_array($i->format("Y-m-d"), $wolist)) {
										$intime_roster = 'WO';
										$outtime_roster = 'WO';
									} else {
										$intime_roster = $_POST['txt_ShiftIn'];
										$outtime_roster = $_POST['txt_ShiftOut'];
									}
									$str_insert_ros = 'call  sp_insert_roster_backdate("' . $empID . '","' . $i->format("Y-m-d") . '","' . $intime_roster . '","' . $outtime_roster . '","1","WFOB")';
									$myDB = new MysqliDb();
									$myDB->query($str_insert_ros);
								}
								echo "<script>$(function(){ toastr.success('Congratulations, selected employee aligned to Batch No. $batch_id_new successfully.'); }); </script>";
							} else {
								echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
							}
						}
					}
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated. No Bacth assigned.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated.No Employee Selected.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated.No Employee Selected.'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Record not updated.No Employee Selected.'); }); </script>";
	}
}
if (isset($_POST['cS_ctatus'])) {
	$c_sstaus = $_POST['cS_ctatus'];
} else {
	$c_sstaus = 'YES';
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
	<span id="PageTittle_span" class="hidden">Manage Employee Training Head After Training</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Trainee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="form-inline">
					<div class="col s12 m12 no-padding">
						<div class="input-field col s5 s5">

							<select id="text_trcheck_Batch" name="text_trcheck_Batch">
								<option value="NA">----Select----</option>
								<?php
								if ($_SESSION['training_approver'] != 'No') {
									$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map  left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID left outer join new_client_master on employee_map.cm_id=new_client_master.cm_id  left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID  left outer join batch_master on batch_master.BacthID=status_training.BatchID  where  status_table.Status=3 and status_training.Status ="YES" and new_client_master.client_name in (select client_id from training_master where Approver_id="' . $_SESSION['__user_logid'] . '") and employee_map.emp_status="Active" and datediff(status_training.date_cer_1,status_table.OutTraining) <= 0';
								} else {
									$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map  left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID left outer join new_client_master on employee_map.cm_id=new_client_master.cm_id  left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID  left outer join batch_master on batch_master.BacthID=status_training.BatchID  where new_client_master.th="' . $_SESSION['__user_logid'] . '" and status_training.EmployeeID !="' . $_SESSION['__user_logid'] . '" and status_table.Status=3 and  ReportTo =  "' . $_SESSION['__user_logid'] . '"  and status_training.Status ="YES" and new_client_master.client_name not in (select client_id from training_master) and employee_map.emp_status="Active" and datediff(status_training.date_cer_1,status_table.OutTraining) <= 0';
								}

								$batch_id = 0;
								$myDB = new MysqliDb();
								$resultBy = $myDB->query($sqlBy);
								$error = $myDB->getLastError();
								if (count($resultBy) > 0 && $resultBy) {
									$selec = '';
									foreach ($resultBy as $key => $value) {
										if (isset($_POST['text_trcheck_Batch'])) {
											$batch_id = $_POST['text_trcheck_Batch'];
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
							<label for="text_trcheck_Batch" class="active-drop-down active">Batch Name</label>
						</div>
						<div class="input-field col s5 s5">

							<select name="cS_ctatus" id="cS_ctatus">
								<option <?php echo ($c_sstaus == 'YES') ? 'selected' : ''; ?>>YES</option>
								<option <?php echo ($c_sstaus == 'NO') ? 'selected' : ''; ?>>NO</option>
							</select>
							<label for="cS_ctatus" class="active-drop-down active">Certification Status</label>

						</div>
						<div class="input-field col s2 s2">
							<input type="submit" value="Search" name="btnChange" id="btnChange" class="btn waves-effect waves-green" />
						</div>
					</div>

					<div class="input-field col s12 s12 no-padding statuscheck " id="accordian">

						<div class="input-field col s6 s6 statuscheck">

							<select id="txt_thcheck_Trainer" name="txt_thcheck_Trainer">

							</select>
							<label for="txt_thcheck_Trainer" class="active-drop-down active">Trainer</label>
						</div>
						<div class="input-field col s6 s6 statuscheck">

							<select id="txt_thcheck_crNo" name="txt_thcheck_crNo">
								<option value="1">ONE</option>
								<!--<option value="2">TWO</option>	
		            	<option value="3">THREE</option>	
		            	<option value="4">FOUR</option>	
		            	<option value="5">FIVE</option>	-->

							</select>
							<label for="txt_thcheck_crNo" class="active-drop-down active">No of Certification</label>
						</div>
						<div class="input-field col s6 s6 statuscheck">
							<input name="start_date_cir" readonly="true" value="<?php echo date('Y-m-d', time()); ?>" />
							<label>Start Certification Date</label>
						</div>

						<div class="input-field col s6 s6" id="div_date_1">

							<input type="text" id="txt_Date_crt_1" name="txt_Date_crt_1" readonly="" />
							<label for="txt_Date_crt_1">Final Certification Date</label>
						</div>
						<div class="input-field col s6 s6" id="div_duration_1">
							<input type="text" id="txt_Day_crt_1" name="txt_Day_crt_1" readonly="" />
							<label for="txt_Day_crt_1"> Duration :</label>

						</div>
						<div class="input-field col s6 s6 statuscheck" id="shif_div1">

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
							<input type="submit" value="Retrain " class="btn waves-effect waves-light red darken-1" name="btn_retrain" id="btn_retrain" title="All the checked Employee Refer To You to retrain in Employee CheckList" />
						</div>
						<div class="input-field col s6 m6 statuscheck right-align">
							<input type="submit" value="Send to QH" name="btnSave" id="btnSave" class="btn waves-effect waves-green" />
							<input type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect waves-red close-btn" />
						</div>
					</div>


					<div id="pnlTable" class="col s12 m12 card">
						<?php
						if ($batch_id != 'NA' && !empty($batch_id)) {
							$sqlConnect = 'call get_thchecklist_after("' . $_SESSION['__user_logid'] . '","' . $c_sstaus . '","' . $batch_id . '")';
							$myDB = new MysqliDb();
							$result = $myDB->query($sqlConnect);
							//echo $sqlConnect;
							$error = $myDB->getLastError();
							if (count($result) > 0 && $result) { ?>

								<div class="flow-x-scroll">
									<table id="myTable" class="data dataTable no-footer" cellspacing="0">
										<thead>
											<tr>
												<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
												<th class="hidden">Employee ID</th>
												<th>Employee Name</th>
												<th>Certification Status</th>
												<th class="">Training Staus</th>
												<th class="">Retrain Staus</th>
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
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmpID'] . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmpID'] . '</label></td>';
												echo '<td class="EmpID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmpID'] . '">' . $value['EmpID'] . '</a></td>';
												echo '<td class="FullName">' . $value['EmpName'] . '</td>';
												echo '<td class="c_status">' . $value['c_status'] . '</td>';
												if ($value['de_status'] > 0) {
													echo '<td class="de_status">Training Incomplete</td>';
												} else {
													echo '<td class="de_status text-danger">Training Complete</td>';
												}
												if ($value['retrain_flag'] == '1') {
													echo '<td class="cirtification_level  ">Re- Training</td>';
												} else {
													echo '<td class="cirtification_level  ">Training</td>';
												}
												echo '<td class="client_name">' . $value['client_name'] . '</td>';
												echo '<td class="process">' . $value['process'] . '</td>';
												echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
												echo '<td class="Remark no-padding input-field" ><textarea type="text" style="min-width:200px;" class="materialize-textarea" name="txt_Remark_' . $value['EmpID'] . '" id="txt_Remark_' . $value['EmpID'] . '" class="txt_remark" placeholder="Enter Remarks for ' . $value['EmpName'] . '" ></textarea></td>';
												echo '</tr>';
											}
											?>
										</tbody>
									</table>

								</div>
						<?php
							} else {
								echo "<script>$(function(){ toastr.info('Congratulations, all employees have been aligned to concern departments. " . $error . " '); }); </script>";
							}
						} else {
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
		$('input[type = "text"]').change(function() {
			if ($(this).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		$('#btnSave,#btn_refer,#btn_retrain').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				toastr.info('Check Atleast On Employee.');
			}

			$('input.cb_child:checkbox:checked').each(function() {
				if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 10 || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length > 100) {
					validate = 1;
				//toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
					toastr.info('Remark can\'t be empty, it should be between 10 to 100 characters');
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
			} else {
				$('#txt_thcheck_Trainer').val('No');
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
				$('#txt_thcheck_Trainer').val('NA');
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
		$('select').formSelect();
		$('#btn_refer').click(function() {

			if (confirm("You really want to Refer these Employee to HR...")) {
				return true;
			} else {
				return false;
			}
			return false;
		});
		$('#btn_retrain').click(function() {
			var alert_msg = '';
			var validate = 0;
			if (confirm("You really want to Refer these Employee For ReTraining because if any Employee is already Retrain then that auto refer to HR...")) {

				if ($('#txt_Date_crt_1').val() == '' || $('#txt_Date_crt_1').val() == 'NA') {
					$('#txt_Date_crt_1').addClass("has-error");
					validate = 1;
					toastr.info('select Final date for Certification.');
				}
				if ($('#txt_ShiftIn').val() == '' || $('#txt_ShiftIn').val() == 'NA') {
					$('#txt_Date_crt_1').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					validate = 1;
					toastr.info('Select Shift for training.');
				}
				if (validate == 1) {

					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
			return false;
		});
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$("#txt_thcheck_Trainer").empty();
		$.ajax({
			url: "../Controller/get_listByBatchID.php?batchID=" + $('#text_trcheck_Batch').val(),
			success: function(result) {
				//alert(result);
				$("#txt_thcheck_Trainer").html(result);
				$('select').formSelect();
			}
		});

		//alert($('#text_trcheck_Batch').val());
		$('.statuscheck').removeClass('hidden');
		if ($('.c_status').text().substr(0, 3) == 'YES') {
			$('#btn_refer').addClass('hidden');
		}
	}
	$('select').formSelect();


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
	$('select').formSelect();

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
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
		//return count;
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>