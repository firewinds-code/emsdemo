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
$searchBy = '';
$msg = '';
if (isset($_POST['btnSave'])) {

	$status = trim($_POST['txt_thcheck_Trainer']);
	$createBy = $_SESSION['__user_logid'];

	if ($status != "" && $status != "NA") {
		$myDB = new MysqliDb();
		$query_check = $myDB->query('SELECT  distinct status_training.Trainer,status_training.BatchID FROM  status_training inner join  status_table on status_table.EmployeeID = status_training.EmployeeID inner join  employee_map on employee_map.EmployeeID = status_training.EmployeeID  where status_table.Status="3"  and status_training.`Status`="NO" and employee_map.emp_status ="Active" and status_training.Trainer = "' . $status . '"');
		$vaidate = 0;

		if (count($query_check) > 0 && $query_check) {
			$vaidate = 1;
			foreach ($query_check as $key_dt => $data_check) {
				$data_value = $data_check['BatchID'];
				if ($_POST['text_trcheck_Batch'] == $data_check['BatchID']) {
					$vaidate = 0;
				}
			}
		}
		if (isset($_POST['cb']) && $vaidate == 0) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			$batch_id_tr = 0;

			if ($count_check > 0) {

				if ($_POST['text_trcheck_Batch'] != 'New' && !empty($_POST['text_trcheck_Batch']) && $_POST['text_trcheck_Batch'] != 'NA') {
					$batch_id_tr = $_POST['text_trcheck_Batch'];

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
						$date_1_crt = (!empty($_POST['txt_Date_crt_1']) ? '"' . $_POST['txt_Date_crt_1'] . '"' : "NULL");
						$date_2_crt = (!empty($_POST['txt_Date_crt_2']) ? '"' . $_POST['txt_Date_crt_2'] . '"' : "NULL");
						$date_3_crt = (!empty($_POST['txt_Date_crt_3']) ? '"' . $_POST['txt_Date_crt_3'] . '"' : "NULL");
						$date_4_crt = (!empty($_POST['txt_Date_crt_4']) ? '"' . $_POST['txt_Date_crt_4'] . '"' : "NULL");
						$date_5_crt = (!empty($_POST['txt_Date_crt_5']) ? '"' . $_POST['txt_Date_crt_5'] . '"' : "NULL");
						$save = 'call manage_status_th("' . $empID . '","' . $status . '","' . $createBy . '",' . $batch_id_tr . ',"' . $_POST['txt_Remark_' . $empID] . '","' . $_POST['txt_thcheck_crNo'] . '",' . $date_1_crt . ',' . $date_2_crt . ',' . $date_3_crt . ',' . $date_4_crt . ',' . $date_5_crt . ',"' . intval($_POST['txt_Day_crt_1']) . '","' . intval($_POST['txt_Day_crt_2']) . '","' . intval($_POST['txt_Day_crt_3']) . '","' . intval($_POST['txt_Day_crt_4']) . '","' . intval($_POST['txt_Day_crt_5']) . '","' . $roster_log . '")';


						$resulti = $myDB->query($save);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
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
								$mnError = $myDB->getLastError();
							}
							echo "<script>$(function(){ toastr.success('Congratulations, selected employee aligned to Batch successfully.'); }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
						}
					}
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated, No Bacth Assigned.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
			}
		} else {
			if ($vaidate == 1) {
				echo "<script>$(function(){ toastr.error('Record not updated, Trainer already assigned a batch.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
			}
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
				$save = 'call manage_refer_hr("' . $empID . '","' . $createBy . '","' . $_POST['txt_Remark_' . $empID] . '","th")';
				$resulti = $myDB->query($save);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Employee $empID reffered to HR successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected'); }); </script>";
	}
}
?>
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
	<span id="PageTittle_span" class="hidden">Manage Employee Training Head</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Align Trainer <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="batch_master.php" data-position="bottom" data-tooltip="Create New Batch" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="">
					<input type="hidden" id="txt_batch_sd" name="txt_batch_sd" value="0" />
					<div class="input-field col s6 s6 statuscheck">


						<select id="text_trcheck_Batch" name="text_trcheck_Batch" onchange="javascript:return getBatch_state();">
							<option value="NA">----Select----</option>

							<?php

							$sqlBy = 'SELECT distinct batch_master.BacthID, batch_master.BacthName,batch_master.createdon FROM batch_master where batch_master.createdby="' . $_SESSION["__user_logid"] . '" and datediff(curdate(),batch_master.createdon) < 5 having datediff(curdate(),batch_master.createdon) < (3 + (
								select count(DateOn) from   batch_master left join roster_temp on roster_temp.EmployeeID=batch_master.createdby  where EmployeeID = batch_master.createdby and roster_temp.InTime like "%WO%" and DateOn between cast(batch_master.createdon as date) and curdate())
								) order by batch_master.BacthName;';

							//$sqlBy = 'SELECT distinct batch_master.BacthID, batch_master.BacthName,createdon FROM batch_master where batch_master.createdby="'.$_SESSION["__user_logid"].'" and datediff(curdate(),createdon) < 5 having datediff(curdate(),createdon) < (3 + (select count(DateOn) from roster_temp where EmployeeID = batch_master.createdby and roster_temp.InTime like "%WO%" and DateOn between cast(batch_master.createdon as date) and curdate())) order by batch_master.BacthName';
							$batch_id = 0;
							$myDB = new MysqliDb();
							$resultBy = $myDB->query($sqlBy);
							$error  = $myDB->getLastError();
							if (count($resultBy) > 0 && $resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									$batch_id = $_POST['text_trcheck_Batch'];
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
						<input name="start_date_cir" id="start_date_cir" readonly="true" value="<?php echo date('Y-m-d', time()); ?>" />
						<label for="start_date_cir">Training Start Date</label>

					</div>

					<div class="input-field col s6 s6" id="div_date_1">
						<input type="text" id="txt_Date_crt_1" name="txt_Date_crt_1" readonly="" />
						<label for="txt_Date_crt_1">Certification Date:</label>

					</div>
					<div class="input-field col s6 s6" id="div_duration_1">
						<input type="text" id="txt_Day_crt_1" name="txt_Day_crt_1" readonly="" />
						<label for="txt_Day_crt_1"> Training Days</label>
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
						<label for="txt_ShiftIn" class="active-drop-down active"> Shift IN</label>
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
						<label for="txt_ShiftOut" class="active-drop-down active"> Shift Out</label>
					</div>

					<div class="input-field col s6 s6" id="div_date_2">

						<div class="input-field col s8 s8">
							<input type="text" id="txt_Date_crt_2" class="form-control" name="txt_Date_crt_2" placeholder="click here" readonly="" />
							<label for="txt_Date_crt_2">Date Certification 2</label>
						</div>
						<div class="input-field col s4 s4">
							<input type="text" id="txt_Day_crt_2" class="form-control" name="txt_Day_crt_2" readonly="" />
							<label for="txt_Day_crt_2">Day Certification 2</label>
						</div>
					</div>
					<div class="input-field col s6 s6" id="div_date_3">

						<div class="input-field col s8 s8">
							<input type="text" id="txt_Date_crt_3" class="form-control" name="txt_Date_crt_3" placeholder="click here" readonly="" />
							<label for="txt_Date_crt_3">Date Certification 3</label>
						</div>
						<div class="input-field col s4 s4">
							<input type="text" id="txt_Day_crt_3" class="form-control" name="txt_Day_crt_3" readonly="" />
							<label for="txt_Day_crt_3">Day Certification 3</label>
						</div>
					</div>
					<div class="input-field col s6 s6" id="div_date_4">
						<div class="input-field col s8 s8">
							<input type="text" id="txt_Date_crt_4" class="form-control" name="txt_Date_crt_4" placeholder="click here" readonly="" />
							<label for="txt_Date_crt_4">Date Certification 4</label>
						</div>
						<div class="input-field col s4 s4">
							<input type="text" id="txt_Day_crt_4" class="form-control" name="txt_Day_crt_4" readonly="" />
							<label for="txt_Day_crt_4">Day Certification 4</label>
						</div>

					</div>
					<div class="input-field col s6 s6" id="div_date_5">
						<div class="input-field col s8 s8">
							<input type="text" id="txt_Date_crt_5" class="form-control" name="txt_Date_crt_5" placeholder="click here" readonly="" />
							<label for="txt_Date_crt_5">Date Certification 5</label>
						</div>
						<div class="input-field col s4 s4">
							<input type="text" id="txt_Day_crt_5" class="form-control" name="txt_Day_crt_5" readonly="" />
							<label for="txt_Day_crt_5">Day Certification 5</label>
						</div>

					</div>

					<div class="input-field col s12 m12 statuscheck" id="wo_chunks">

					</div>
					<div class="input-field col s12 m12 statuscheck" id="ho_chunks">

					</div>
					<div class="input-field col s12 m12">
						<span title="Information of selected Employee count" class="">Selected Employee Count:&nbsp;&nbsp;&nbsp;<b><span id="checkInfo_lbl">0</span></b></span>
					</div>
					<div class="input-field col s5 m5 statuscheck left-align">
						<input type="submit" value="Refer To HR" name="btn_refer" id="btn_refer" class="btn waves-effect waves-green" />

					</div>
					<div class="input-field col s7 m7 statuscheck right-align">

						<input type="submit" value="Submit" name="btnSave" id="btnSave" class="btn waves-effect waves-green" />
						<input type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect waves-green close-btn" />

					</div>


					<div id="pnlTable" class="col s12 m12 card">
						<?php
						$sqlConnect = 'call get_thchecklist("' . $_SESSION['__user_logid'] . '")';
						$myDB = new MysqliDb();
						$result = $myDB->query($sqlConnect);
						//echo $sqlConnect;
						$error = $myDB->getLastError();
						if (count($result) > 0 && $result) { ?>

							<div class="flow-x-scroll">

								<table id="myTable" class="data dataTable no-footer" cellspacing="0">
									<thead>
										<tr>
											<th>
												<input type="checkbox" id="cbAll" name="cbAll" value="ALL">
												<label for="cbAll">Employee ID</label>
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
											echo '<td class="EmployeeID"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmployeeID'] . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
											echo '<td class="FullName">' . $value['employeename'] . '</td>';
											echo '<td class="client_name">' . $value['client_name'] . '</td>';
											echo '<td class="process">' . $value['process'] . '</td>';
											echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
											echo '<td class="doj">' . $value['dateofjoin'] . '</td>';
											echo '<td class="Remark no-padding input-field"><textarea name="txt_Remark_' . $value['EmployeeID'] . '" id="txt_Remark_' . $value['EmployeeID'] . '" style="min-width:300px;" class="materialize-textarea" placeholder="Remark for ' . $value['employeename'] . '"></textarea></td>';
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
		$('.statuscheck').addClass('hidden');
		$('select').formSelect();
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
			$('input[type = "text"]').change(function() {
				if ($(this).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
		});

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
				toastr.info('Select Final date for Certification');
			}
			if ($('#txt_ShiftIn').val() == 'NA') {
				validate = 1;
				toastr.info('Select Shift for training');
			}

			var spanID = "span" + $("#txt_thcheck_Trainer").attr('id');
			$("#txt_thcheck_Trainer").removeClass('has-error');
			if ($("#txt_thcheck_Trainer").is('select')) {
				$('#txt_thcheck_Trainer').parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
			}
			if (($('#txt_thcheck_Trainer').val() == '' || $('#txt_thcheck_Trainer').val() == 'NA' || $('#txt_thcheck_Trainer').val() == undefined) && !$('#txt_thcheck_Trainer').hasClass('.select-dropdown')) {
				validate = 1;
				$('#txt_thcheck_Trainer').addClass('has-error');
				if ($('#txt_thcheck_Trainer').is('select')) {
					$('#txt_thcheck_Trainer').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				}
				if ($('#' + spanID).size() == 0) {
					$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $('#txt_thcheck_Trainer').attr('id'));
				}
				var attr_error = $('#txt_thcheck_Trainer').attr('data-error-msg');
				if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
					$('#' + spanID).html('Required *');
				} else {
					$('#' + spanID).html($('#txt_thcheck_Trainer').attr("data-error-msg"));
				}
			}

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
			$('select').formSelect();
		});
		$('#div_error').removeClass('hidden');
		$("#cbAll").change(function() {
			$("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
			$('select').formSelect();
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
		});
		$("input:checkbox").change(function() {

			$('#checkInfo_lbl').text($('input.cb_child:checkbox:checked').length);
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
			$('select').formSelect();
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
		});

		$('#btn_refer').click(function() {
			var alert_msg = '';
			if (confirm("You really want to Refer these Employee to HR...")) {
				var validate = 0;
				$('input.cb_child:checkbox:checked').each(function() {
					if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 50) {
						validate = 1;
						toastr.info('Remark Can\'t be Empty or not less than 50 For any Checked Employee.');
					}

				});
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
		$('select').formSelect();
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
			/*else if($(this).val() == '2')
			{
				$('#div_date_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');
				
				$('#div_date_2').removeClass('hidden');
				$('#txt_Date_crt_2').removeClass('hidden');
			}
			else if($(this).val() == '3')
			{
				$('#div_date_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');
				
				$('#div_date_2').removeClass('hidden');
				$('#txt_Date_crt_2').removeClass('hidden');
				
				$('#div_date_3').removeClass('hidden');
				$('#txt_Date_crt_3').removeClass('hidden');
			}
			else if($(this).val() == '4')
			{
				$('#div_date_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');
				
				$('#div_date_2').removeClass('hidden');
				$('#txt_Date_crt_2').removeClass('hidden');
				
				$('#div_date_3').removeClass('hidden');
				$('#txt_Date_crt_3').removeClass('hidden');
				
				$('#div_date_4').removeClass('hidden');
				$('#txt_Date_crt_4').removeClass('hidden');
			}
			else if($(this).val() == '5')
			{
				$('#div_date_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');
				
				$('#div_date_2').removeClass('hidden');
				$('#txt_Date_crt_2').removeClass('hidden');
				
				$('#div_date_3').removeClass('hidden');
				$('#txt_Date_crt_3').removeClass('hidden');
				
				$('#div_date_4').removeClass('hidden');
				$('#txt_Date_crt_4').removeClass('hidden');
				
				$('#div_date_5').removeClass('hidden');
			    $('#txt_Date_crt_5').removeClass('hidden');
			}*/
			$('select').formSelect();
		});


	});

	/*$('#txt_Date_crt_1').datepicker({
			dateFormat:'yy-mm-dd',
			minDate: 0,
			onSelect: function (dateText, inst) {
			 			$('#txt_Date_crt_2').datepicker("option", "minDate", dateText);
				           var d1=new Date(dateText.split('-').join(','));
					       // get date from other text field
					       var today = new Date();
					       var d2=new Date(today.getFullYear(),today.getMonth(),today.getDate());
					       
					       // d2 -d1 gives result in milliseconds
					       // calculate number of days by Math.abs((d2-d1)/86400000, as 24*3600*1000 = 86400000
					       // and populate it to some text field #textfield
					       createWO(new Date(d2),new Date(d1));
					       
					       if(d2.getTime() == d1.getTime())
					       {
					       	
						     $('#txt_Day_crt_1').val(parseInt(Math.abs((today-d1)/86400000) + 1));
						   }
						   else
						   {
						   	
						   	 
						   	 $('#txt_Day_crt_1').val(parseInt(Math.abs((today-d1)/86400000) + 2));
						   }
					       
				        }
		});*/
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
		$('select').formSelect();
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	}

	function getBatch_state() {
		var batchid = $("#text_trcheck_Batch").val();
		var approver = <?php echo "'" . $_SESSION["training_approver"] . "'"; ?>;
		var location = <?php echo $_SESSION["__location"] ?>;
		$.ajax("../Controller/getBatchState.php?type=th&id=" + batchid + "&loc=" + location + "&lvl=" + approver).done(function(result) {

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

					}
				});

			} else {
				$("#txt_batch_sd").val(0);
				$('#txt_Date_crt_1').val("");
				$('#txt_Date_crt_1').datepicker("destroy");
				$("#ho_chunks").html("");
				$("#wo_chunks").html("");
			}

			$("#txt_thcheck_Trainer").empty();
			$.ajax({
				url: "../Controller/get_listByBatchID.php?batchID=" + batchid,
				success: function(result) {
					//alert(result);
					$("#txt_thcheck_Trainer").html(result);
					$('select').formSelect();
				}
			});

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

			elements1 = elements1 + "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_wo_date[]' id='txt_wo_date" + date_apart + "' value=" + date_on + " /><label for='txt_wo_date" + date_apart + "' >" + dateObj1.getDate() + "</label></div>";
			elements2 = elements2 + "<div class='input-field col s1 m1 input_chunk'><input type='checkbox' name='txt_ho_date[]' id='txt_ho_date" + date_apart + "' value=" + date_on + " /><label for='txt_ho_date" + date_apart + "'>" + dateObj1.getDate() + "</label></div>";

			dateObj1.setDate(dateObj1.getDate() + 1);
		}

		$('#wo_chunks').html(elements1);
		$('#ho_chunks').html(elements2);
		$('select').formSelect();
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
				$("#" + txtId).trigger('click');
			}
			dateObj1.setDate(dateObj1.getDate() + 1);
		}
		$('select').formSelect();
	};
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>