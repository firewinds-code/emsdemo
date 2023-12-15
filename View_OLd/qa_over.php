<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$last_to = $last_from = $last_to = $dept = $emp_nam = $status = '';
$classvarr = "'.byID'";
$searchBy = '';
$msg = '';
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = clean($_SESSION['__user_logid']);
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {

				if ($_POST['batch_id'] > 0 || !empty($_POST['batch_id'])) {
					foreach ($_POST['cb'] as $val) {
						$empID = cleanUserInput($val);
						// $check_rtr_q = $myDB->query('select EmployeeID from status_table where EmployeeID ="' . $empID . '" and reOJT is not null limit 1');
						$check_rtr_qQry = 'select EmployeeID from status_table where EmployeeID =? and reOJT is not null limit 1';
						$stmt = $conn->prepare($check_rtr_qQry);
						$stmt->bind_param("s", $empID);
						$stmt->execute();
						$check_rtr_q = $stmt->get_result();
						// print_r($check_rtr_q);
						// die;
						if ($check_rtr_q->num_rows > 0 && $check_rtr_q) {
							echo "<script>$(function(){ toastr.error('$empID not referred to Re-OJT, Employee already referred in Re-OJT once'); }); </script>";
						} else {
							$myDB = new MysqliDb();
							$txt_remark = cleanUserInput($_POST['txt_Remark_' . $empID]);
							$save = 'call manage_qh_over("' . $empID . '","' . $txt_remark . '","' . $createBy . '","QH OVER")';
							$resulti = $myDB->query($save);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								echo "<script>$(function(){ toastr.success('Employee $empID updated successfully.'); }); </script>";
							} else {
								echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
							}
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
					$txt_remark = cleanUserInput($_POST['txt_Remark_' . $empID]);
					$save = 'call manage_refer_hr("' . $empID . '","' . $createBy . '","' . $txt_remark . '","QH OVER REFER")';
					$resulti = $myDB->query($save);
					$mysql_error = $myDB->getLastError();
					if (empty($resulti)) {
						echo "<script>$(function(){ toastr.success('Employee $empID reffered to HR Successfully'); }); </script>";
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
					// $check_rtr_q = $myDB->query('select EmployeeID from status_training where retrain_flag = 1 and EmployeeID = "' . $empID . '"');
					$check_rtr_qQry = 'select EmployeeID from status_training where retrain_flag = 1 and EmployeeID = ?';
					$stmt = $conn->prepare($check_rtr_qQry);
					$stmt->bind_param("s", $empID);
					$stmt->execute();
					$check_rtr_q = $stmt->get_result();
					if ($check_rtr_q->num_rows > 0 && $check_rtr_q) {
						echo "<script>$(function(){ toastr.error('$empID not referred to Re-Training, Employee already referred in Re-Training once'); }); </script>";
					} else {
						$myDB = new MysqliDb();
						$txt_remark = cleanUserInput($_POST['txt_Remark_' . $empID]);
						$save = 'call manage_retrain("' . $empID . '","' . $createBy . '","' . 	$txt_remark . '","QH RETRAIN")';
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
							// $sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID  left outer join batch_master on batch_master.BacthID=status_table.BatchID left outer join status_quality on status_quality.EmployeeID=status_table.EmployeeID where  status_table.EmployeeID !="' . $_SESSION['__user_logid'] . '" and status_table.Status=5 and ReportTo = "' . $_SESSION['__user_logid'] . '" and ojt_status  = 2;';
							$user = clean($_SESSION['__user_logid']);
							$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID  left outer join batch_master on batch_master.BacthID=status_table.BatchID left outer join status_quality on status_quality.EmployeeID=status_table.EmployeeID where  status_table.EmployeeID != ? and status_table.Status=5 and ReportTo = ? and ojt_status  = 2;';
							$stmt = $conn->prepare($sqlBy);
							$stmt->bind_param("ss", $user, $user);
							$stmt->execute();
							$resultBy = $stmt->get_result();
							$batch_id = 0;
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
						$user = clean($_SESSION['__user_logid']);
						$sqlConnect = 'call get_qhchecklist_over("' . $user  . '",' . $batch_id . ')';
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
											<th>OJT Status</th>
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
											if (!empty($value['reOJT'])) {
												echo '<td class="OJTStatus">In RE-OJT</td>';
											} else {
												echo '<td class="OJTStatus">In OJT</td>';
											}
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
							echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments. " . $error . "'); }); </script>";
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
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}


		});
		$('select').formSelect();
		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				toastr.info('Check atleast on Employee');
			}
			if ($('#txt_thcheck_Quality').val() == '' || $('#txt_thcheck_Quality').val() == 'NA') {
				validate = 1;
				$('#txt_thcheck_Quality').addClass("has-error");
				toastr.info('select Final date for Certification');
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
			} else {
				$('#txt_thcheck_Quality').val('No');
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
		});
		<?php

		if (isset($_POST['text_trcheck_Batch'])) {
			echo "$('#text_trcheck_Batch').val('" . $batch_id . "');";
		}
		if ($batch_id == 0) {
			echo "$('.btnslogs').addClass('hidden');";
		}
		?>
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>