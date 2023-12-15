<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$last_to = $last_from = $last_to = $dept = $emp_nam = $status = $searchBy = $msg = '';
$classvarr = "'.byID'";

//echo $sqlBy = 'select EmpID_Name.EmpID,EmpID_Name.EmpName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join designation_master on designation_master.ID=df_master.des_id inner join EmpID_Name on EmpID_Name.EmpID=employee_map.EmployeeID join new_client_master t1 on t1.cm_id=employee_map.cm_id where Designation not in ("Senior CSA","CSA") and function_id=' . $_SESSION['__user_Function'] . ' and t1.oh="' . $_SESSION['__user_logid'] . '" and employee_map.emp_status="Active" and EmpID_Name.loc=' . $_SESSION['__location'] . ' and EmpID_Name.EmpID is not null';

if (isset($_POST['btnSave'])) {
	$status = $_POST['txt_thcheck_Quality'];
	$createBy = $_SESSION['__user_logid'];
	if ($status != '' && $status != 'NA') {
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {
				foreach ($_POST['cb'] as $val) {
					$empID = $val;
					$myDB = new MysqliDb();
					$save = 'call manage_status_oh("' . $empID . '","' . $status . '")';
					$resulti = $myDB->query($save);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
						echo "<script>$(function(){ toastr.success('Record updated successfully.'); }); </script>";
					} else {
						echo "<script>$(function(){ toastr.error('Record not updated. " . $mysql_error . " '); }); </script>";
					}
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
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Operation Head</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Employee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<script>
					$(document).ready(function() {
						$('.statuscheck').addClass('hidden');
						$('#alert_msg_close').click(function() {
							$('#alert_message').hide();
						});
						if ($('#alert_msg').text() == '') {
							$('#alert_message').hide();
						}
						$('#txt_ED_joindate_to').datetimepicker({
							format: 'Y-m-d',
							timepicker: false
						});
						$('#txt_ED_joindate_from').datetimepicker({
							format: 'Y-m-d',
							timepicker: false
						});

						$('.buttons-copy').attr('id', 'buttons_copy');
						$('.buttons-csv').attr('id', 'buttons_csv');
						$('.buttons-excel').attr('id', 'buttons_excel');
						$('.buttons-pdf').attr('id', 'buttons_pdf');
						$('.buttons-print').attr('id', 'buttons_print');
						$('.buttons-page-length').attr('id', 'buttons_page_length');
						$('.byID').addClass('hidden');
						$('.byDate').addClass('hidden');
						$('.byDept').addClass('hidden');
						var classvarr = <?php echo $classvarr; ?>;
						$(classvarr).removeClass('hidden');
						$('#searchBy').change(function() {
							$('.byID').addClass('hidden');
							$('.byDate').addClass('hidden');
							$('.byDept').addClass('hidden');
							$('#txt_ED_joindate_to').val('');
							$('#txt_ED_joindate_from').val('');
							$('#txt_ED_Dept').val('NA');
							$('#ddl_ED_Emp_Name').val('');
							if ($(this).val() == 'By ID') {
								$('.byID').removeClass('hidden');
							} else if ($(this).val() == 'By Date') {
								$('.byDate').removeClass('hidden');
							} else if ($(this).val() == 'By Dept') {
								$('.byDept').removeClass('hidden');
							}


						});
					});
				</script>

				<div class="input-field col s12">

					<select class="form-control" style="max-width: 250px;" id="txt_thcheck_Quality" name="txt_thcheck_Quality">
						<option value="NA">----Select----</option>
						<?php
						//$sqlBy ='SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM ems.employee_map left outer join personal_details on personal_details.EmployeeID=employee_map.EmployeeID where dept_id=6 and client_id="'.$_SESSION["__user_client_ID"].'" and designation not in ("Senior Custumer Care Executive","Custumer Care Executive") '; 
						//$sqlBy ='select personal_details.EmployeeID,personal_details.EmployeeName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join designation_master on designation_master.ID=df_master.des_id inner join personal_details on personal_details.EmployeeID=employee_map.EmployeeID where Designation not in ("Senior CSA","CSA") and function_id='.$_SESSION['__user_Function'].' and employee_map.emp_status="Active" and personal_details.EmployeeID is not null'; 

						$sqlBy = 'select EmpID_Name.EmpID,EmpID_Name.EmpName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join designation_master on designation_master.ID=df_master.des_id inner join EmpID_Name on EmpID_Name.EmpID=employee_map.EmployeeID join new_client_master t1 on t1.cm_id=employee_map.cm_id where employee_map.df_id not in (74,77,146, 147,148,149) and function_id=' . $_SESSION['__user_Function'] . ' and t1.oh="' . $_SESSION['__user_logid'] . '" and employee_map.emp_status="Active" and EmpID_Name.loc=' . $_SESSION['__location'] . ' and EmpID_Name.EmpID is not null';

						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							$selec = '';
							foreach ($resultBy as $key => $value) {

								echo '<option value="' . $value['EmpID'] . '"  >' . $value['EmpName'] . '  ( ' . $value['EmpID'] . ' ) </option>';
							}
						}

						?>
					</select>

					<label for="txt_thcheck_Quality" class="active-drop-down active">Reporting</label>
				</div>
				<div class="input-field col s12 m12 right-align">
					<input type="submit" value="Submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green" />
					<input type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect modal-action modal-close waves-red close-btn" />
				</div>

				<div id="pnlTable">
					<?php
					$sqlConnect = 'call get_ohchecklist("' . $_SESSION['__user_logid'] . '")';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					$error = $myDB->getLastError();
					if (count($result) > 0 && $result) { ?>
						<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
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
											<th>Supervisor</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="EmpId">
								<input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmployeeID'] . '">
								<label for="cb' . $count . '">' . $value['EmployeeID'] . '</label></td>';
											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
											echo '<td class="FullName">' . $value['EmployeeName'] . '</td>';
											echo '<td class="client_name">' . $value['client_name'] . '</td>';
											echo '<td class="process">' . $value['process'] . '</td>';
											echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
											echo '<td class="Supervisor">' . $value['Supervisor'] . '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						echo "<script>$(function(){ toastr.error('Record not updated, May be you not have any Trainee assigned ." . $error . " '); }); </script>";
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
		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
			}
			if ($('#txt_thcheck_Quality').val() == '' || $('#txt_thcheck_Quality').val() == 'NA') {
				$('#txt_thcheck_Quality').addClass('has-error');
				validate = 1;

			}
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
		});


	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>