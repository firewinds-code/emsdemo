<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$sess = clean($_SESSION['__user_type']) == 'ADMINISTRATOR' || clean($_SESSION['__user_logid']) == 'CE10091236' || clean($_SESSION['__user_logid']) == 'CE011929747';
if ($sess) {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}
/*
ALTER TABLE `new_client_master` 
ADD COLUMN `VH` VARCHAR(45) NULL DEFAULT NULL AFTER `days_of_rotation`;

*/
// Global variable used in Page Cycle
$alert_msg = $_ITID = $_HRID = $_ReportsTo = $_Stipend = '';
$_StipendDays = 0;
if (isset($_POST['StipendDays']) and trim($_POST['StipendDays']) != "") {
	$_StipendDays = trim(cleanUserInput($_POST['StipendDays']));
}
if (isset($_POST['rotation_date']) and trim($_POST['rotation_date']) != "") {
	$_rotation_date = trim(cleanUserInput($_POST['rotation_date']));
} else {
	$_rotation_date = 0;
}
if (isset($_POST['from_floordate']) and trim($_POST['from_floordate']) != "") {
	$_from_floordate = trim(cleanUserInput($_POST['from_floordate']));
} else {
	$_from_floordate = 0;
}
if (isset($_POST['from_joiningdate']) and trim($_POST['from_joiningdate']) != "") {
	$_from_joiningdate = trim(cleanUserInput($_POST['from_joiningdate']));
} else {
	$_from_joiningdate = 0;
}
// Trigger Button-Save Click Event and Perform DB Action
$btn_client = isset($_POST['btn_Client_Save']);
if ($btn_client) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Client_Name = cleanUserInput($_POST['txt_Client_Name']);
		$txt_Client_ach = cleanUserInput($_POST['txt_Client_ach']);
		$txt_Client_dept = cleanUserInput($_POST['txt_Client_dept']);
		$txt_Client_proc = cleanUserInput($_POST['txt_Client_proc']);
		$txt_Client_oh = cleanUserInput($_POST['txt_Client_oh']);
		$txt_location = cleanUserInput($_POST['txt_location']);
		$txt_Client_qh = cleanUserInput($_POST['txt_Client_qh']);
		$txt_Client_th = cleanUserInput($_POST['txt_Client_th']);
		$txt_Client_subproc = cleanUserInput($_POST['txt_Client_subproc']);
		$txt_ERSPOC = cleanUserInput($_POST['txt_ERSPOC']);
		$txt_vertical_head = cleanUserInput($_POST['txt_vertical_head']);
		$hid_Excp_ID = cleanUserInput($_POST['hid_Excp_ID']);
		$btn_client = cleanUserInput($_POST['btn_client']);


		$_Name = (isset($txt_Client_Name) ? $txt_Client_Name : null);
		$_ach = (isset($txt_Client_ach) ? $txt_Client_ach : null);
		$_dept = (isset($txt_Client_dept) ? $txt_Client_dept : null);
		$_proc = (isset($txt_Client_proc) ? $txt_Client_proc : null);
		$_oh = (isset($txt_Client_oh) ? $txt_Client_oh : null);
		$_location = (isset($txt_location) ? $txt_location : null);
		$_qh = (isset($txt_Client_qh) ? $txt_Client_qh : null);
		$_th = (isset($txt_Client_th) ? $txt_Client_th : null);
		$_subproc = (isset($txt_Client_subproc) ? $txt_Client_subproc : null);
		$_er_scop = (isset($txt_ERSPOC) ? $txt_ERSPOC : null);
		$txt_vertical_head = (isset($txt_vertical_head) ? $txt_vertical_head : null);
		$txt_ExcpID = (isset($hid_Excp_ID) ? $hid_Excp_ID : null);

		$EmpID = substr($txt_ExcpID, strpos($txt_ExcpID, "(") + 1, (strpos($txt_ExcpID, ")")) - (strpos($txt_ExcpID, "(") + 1));
		$EmpName = substr($txt_ExcpID, 0, strpos($txt_ExcpID, "("));

		if (isset($_POST['HRID']) and trim($_POST['HRID']) != "") {
			$_HRID = trim(cleanUserInput($_POST['HRID']));
		}
		if (isset($_POST['ITID']) and trim($_POST['ITID']) != "") {
			$_ITID = trim(cleanUserInput($_POST['ITID']));
		}
		if (isset($_POST['ReportsTo']) and trim($_POST['ReportsTo']) != "") {
			$_ReportsTo = trim(cleanUserInput($_POST['ReportsTo']));
		}

		if (isset($_POST['Stipen2']) and trim($_POST['Stipen2']) != "") {
			$_Stipend = trim(cleanUserInput($_POST['Stipen2']));
		}
		if ($_HRID != "" && $_ITID != "" && $_ReportsTo != "") {
			$createBy = clean($_SESSION['__user_logid']);
			echo $Insert = 'CALL add_client_new("' . trim($_Name) . '","' . $_ach . '","' . $_dept . '","' . trim($_proc) . '","' . $_oh . '","' . $_qh . '","' . $_th . '","' . trim($_subproc) . '","' . $createBy . '","' . $_HRID . '","' . $_ITID . '","' . $_ReportsTo . '","' . $_Stipend . '","' . $_StipendDays . '","' . trim($_er_scop) . '","' . trim($_from_joiningdate) . '","' . trim($_from_floordate) . '","' . trim($_rotation_date) . '","' . $txt_vertical_head . '","' . $_location . '","' . $EmpID . '")';
			die;

			$myDB = new MysqliDb();
			$myDB->rawQuery($Insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				if ($myDB->count > 0) {
					echo "<script>$(function(){ toastr.success('Client Added Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Client Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Client not Added.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Client Details Should not be empty.'); }); </script>";
		}
	}
}

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_Client_Edit'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Client_Name = cleanUserInput($_POST['txt_Client_Name']);
		$txt_Client_ach = cleanUserInput($_POST['txt_Client_ach']);
		$txt_Client_dept = cleanUserInput($_POST['txt_Client_dept']);
		$txt_Client_proc = cleanUserInput($_POST['txt_Client_proc']);
		$txt_Client_oh = cleanUserInput($_POST['txt_Client_oh']);
		$txt_location = cleanUserInput($_POST['txt_location']);
		$txt_Client_qh = cleanUserInput($_POST['txt_Client_qh']);
		$txt_Client_th = cleanUserInput($_POST['txt_Client_th']);
		$txt_Client_subproc = cleanUserInput($_POST['txt_Client_subproc']);
		$txt_ERSPOC = cleanUserInput($_POST['txt_ERSPOC']);
		$txt_vertical_head = cleanUserInput($_POST['txt_vertical_head']);
		$hid_Excp_ID = cleanUserInput($_POST['hid_Excp_ID']);

		$DataID = cleanUserInput($_POST['hid_Client_ID']);


		$_Name = (isset($txt_Client_Name) ? $txt_Client_Name : null);
		$_ach = (isset($txt_Client_ach) ? $txt_Client_ach : null);
		$_dept = (isset($txt_Client_dept) ? $txt_Client_dept : null);
		$_proc = (isset($txt_Client_proc) ? $txt_Client_proc : null);
		$_oh = (isset($txt_Client_oh) ? $txt_Client_oh : null);
		$_location = (isset($txt_location) ? $txt_location : null);
		$_qh = (isset($txt_Client_qh) ? $txt_Client_qh : null);
		$_th = (isset($txt_Client_th) ? $txt_Client_th : null);
		$_subproc = (isset($txt_Client_subproc) ? $txt_Client_subproc : null);
		$_er_scop = (isset($txt_ERSPOC) ? $txt_ERSPOC : null);
		$txt_vertical_head = (isset($txt_vertical_head) ? $txt_vertical_head : null);
		$txt_ExcpID = (isset($hid_Excp_ID) ? $hid_Excp_ID : null);

		$EmpID = substr($txt_ExcpID, strpos($txt_ExcpID, "(") + 1, (strpos($txt_ExcpID, ")")) - (strpos($txt_ExcpID, "(") + 1));
		$EmpName = substr($txt_ExcpID, 0, strpos($txt_ExcpID, "("));

		//die;
		if (isset($_POST['HRID']) and trim($_POST['HRID']) != "") {
			$_HRID = trim(cleanUserInput($_POST['HRID']));
		}
		if (isset($_POST['ITID']) and trim($_POST['ITID']) != "") {
			$_ITID = trim(cleanUserInput($_POST['ITID']));
		}
		if (isset($_POST['ReportsTo']) and trim($_POST['ReportsTo']) != "") {
			$_ReportsTo = trim(cleanUserInput($_POST['ReportsTo']));
		}
		if (isset($_POST['Stipen2']) and trim($_POST['Stipen2']) != "") {
			$_Stipend = trim(cleanUserInput($_POST['Stipen2']));
		}

		if (isset($_POST['StipendDays']) and trim($_POST['StipendDays']) != "") {
			$_StipendDays = trim(cleanUserInput($_POST['StipendDays']));
		}
		if (isset($_POST['h_dtid'])) {
			$_h_dtid = cleanUserInput($_POST['h_dtid']);
		} else $_h_dtid = "";
		$ModifiedBy = clean($_SESSION['__user_logid']);
		if ($_HRID != "" && $_ITID != "" && $_ReportsTo != "") {
			echo	$Update = 'call save_client_new("' . trim($_Name) . '","' . $_ach . '","' . $_dept . '","' . trim($_proc) . '","' . $_oh . '","' . $_qh . '","' . $_th . '","' . trim($_subproc) . '","' . $ModifiedBy . '","' . $DataID . '","' . $_HRID . '","' . $_ITID . '","' . $_ReportsTo . '","' . $_h_dtid . '","' . $_Stipend . '","' . $_StipendDays . '","' . trim($_er_scop) . '","' . trim($_from_joiningdate) . '","' . trim($_from_floordate) . '","' . trim($_rotation_date) . '","' . $txt_vertical_head . '","' . $_location . '","' . $EmpID . '","' . $EmpName . '")';
			die;
			$myDB = new MysqliDb();
			if (!empty($DataID) || $DataID != '') {
				$myDB->rawQuery($Update);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Client Updated Successfully'); }); </script>";
					$_Comp = $_Hod = $_Name = '';
					$_Hod = "NA";
				} else {
					echo "<script>$(function(){ toastr.error('Client not updated); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: <code>(If Not Resolved then contact to technical person)</code>'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Client Details Should not be empty); }); </script>";
		}
	}
}
?>

<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}
				/*,'copy'*/
				, 'pageLength'
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Client Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Client Master Details <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Client"><i class="material-icons">add</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal modal_big">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Client Master Details</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="col s12 m12">

								<div class="input-field col s4 m4">
									<select id="txt_location" name="txt_location" required onchange="javascript:return getProcess(this);">
										<option value="NA">----Select----</option>
										<?php
										$sqlBy = 'select id,location from location_master';
										$stmt = $conn->prepare($sqlBy);
										$stmt->execute();
										$resultBy = $stmt->get_result();
										// $myDB = new MysqliDb();
										// $resultBy = $myDB->rawQuery($sqlBy);
										// $mysql_error = $myDB->getLastError();
										if ($resultBy->num_rows > 0) {
											foreach ($resultBy as $key => $value) {
												echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
											}
										}
										?>
									</select>
									<label for="txt_location" class="active-drop-down active">Location</label>
								</div>

								<div class="input-field col s4 m4">
									<input type="text" id="txt_Client_Name" name="txt_Client_Name" required />
									<label for="txt_Client_Name">Client Name</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_ach" name="txt_Client_ach" required>
										<!--<option value="NA">----Select----</option>	
				    <?php
					$sqlBy = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and (Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD") order by personal_details.EmployeeName';
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();
					// $stmt = $conn->prepare($sqlBy);
					// $stmt->execute();
					// $resultBy = $stmt->get_result();
					if (empty($mysql_error)) {
						foreach ($resultBy as $key => $value) {
							echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
						}
					}
					?>-->
									</select>
									<label for="txt_Client_ach" class="active-drop-down active">Account Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_vertical_head" name="txt_vertical_head" required>
										<!--<option value="NA">----Select----</option>	
				    <?php
					if (empty($mysql_error)) {
						foreach ($resultBy as $key => $value) {
							echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
						}
					}
					?>-->
									</select>
									<label for="txt_vertical_head" class="active-drop-down active">Vertical Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_dept" name="txt_Client_dept" required>
										<option value="NA">----Select----</option>
										<?php
										//$sqlBy = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
										$sqlBy = "select dept_id,dept_name from dept_master";
										// $myDB = new MysqliDb();
										// $resultBy = $myDB->rawQuery($sqlBy);
										// $mysql_error = $myDB->getLastError();

										$stmt = $conn->prepare($sqlBy);
										$stmt->execute();
										$resultBy = $stmt->get_result();
										if ($resultBy->num_rows > 0) {
											foreach ($resultBy as $key => $value) {
												echo '<option value="' . $value['dept_id'] . '"  >' . $value['dept_name'] . '</option>';
											}
										}
										?>
									</select>
									<label for="txt_Client_dept" class="active-drop-down active">Department</label>
								</div>

							</div>
							<div class="col s12 m12">

								<div class="input-field col s4 m4">
									<input type="text" id="txt_Client_proc" name="txt_Client_proc" required />
									<label for="txt_Client_proc">Process</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_oh" name="txt_Client_oh" required>
										<!--<option value="NA">----Select----</option>	
			      	<?php
						$sqlBy = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and (Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD") order by personal_details.EmployeeName ';
						$myDB = new MysqliDb();
						$resultBy = $myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							foreach ($resultBy as $key => $value) {
								echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
							}
						}
						?>-->
									</select>
									<label for="txt_Client_oh" class="active-drop-down active">Operation Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_qh" name="txt_Client_qh" required>
										<!--<option value="NA">----Select----</option>	
				<?php
				$sqlBy = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and (Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD") order by personal_details.EmployeeName ';
				$myDB = new MysqliDb();
				$resultBy = $myDB->rawQuery($sqlBy);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					foreach ($resultBy as $key => $value) {
						echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
					}
				}
				?>-->
									</select>
									<label for="txt_Client_qh" class="active-drop-down active">Quality Head</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s4 m4">
									<select id="txt_Client_th" name="txt_Client_th" required>
										<!--<option value="NA">----Select----</option>	
				<?php
				$sqlBy = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and (Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")  order by personal_details.EmployeeName';
				$myDB = new MysqliDb();
				$resultBy = $myDB->rawQuery($sqlBy);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					foreach ($resultBy as $key => $value) {
						echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
					}
				}
				?>-->
									</select>
									<label for="txt_Client_th" class="active-drop-down active">Training Head</label>
								</div>
								<div class="input-field col s4 m4">
									<?php
									/*$sqlBy ="select EmployeeID,EmployeeName from whole_details_peremp where sub_process= 'Human Resource'"; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($sqlBy);*/
									?>
									<select id="txt_ERSPOC" name="txt_ERSPOC" required>
										<!--<option value="">----Select----</option>	
						<?php
						if ($resultBy) {
							foreach ($resultBy as $key => $value) {
								echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
							}
						}
						?>-->
									</select>
									<label for="txt_ERSPOC" class="active-drop-down active">ER SPOC</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="txt_Client_subproc" name="txt_Client_subproc" required />
									<label for="txt_Client_subproc">Sub Process</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s4 m4">
									<input type="text" id="txt_ITID" name="ITID" maxlength="12" required />
									<label for="txt_ITID">IT</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="txt_HRID" name="HRID" maxlength="12" required />
									<label for="txt_HRID">HR</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="txt_ReportsTo" name="ReportsTo" required maxlength="12" />
									<label for="txt_ReportsTo">Reports To</label>
								</div>
							</div>
							<div class="col s12 m12">

								<div class="input-field col s4 m4">
									<select id="txt_exception_approver" name="txt_exception_approver" required>
										<!--<option value="NA">----Select----</option>	
					    <?php
						if (empty($mysql_error)) {
							foreach ($resultBy as $key => $value) {
								echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
							}
						}
						?>-->
									</select>
									<label for="txt_exception_approver" class="active-drop-down active">Exception Approver</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="txt_Stipen" maxlength="10" required name="Stipen2" onkeypress="javascript:return isNumber(event)" />
									<label for="txt_Stipen">Stipend</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="txt_StipendDays" required maxlength="2" name="StipendDays" onkeypress="javascript:return isNumber(event)" />
									<label for="txt_StipendDays">Stipend Days</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s4 m4">
									<input type="text" id="from_joiningdate" maxlength="3" name="from_joiningdate" onkeypress="javascript:return isNumber(event)" />
									<label for="from_joiningdate">Induction</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="from_floordate" maxlength="3" name="from_floordate" onkeypress="javascript:return isNumber(event)" />
									<label for="from_floordate">ER Induction</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" id="rotation_date" maxlength="3" name="rotation_date" onkeypress="javascript:return isNumber(event)" />
									<label for="rotation_date">ER Induction Period</label>
								</div>
							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="h_dtid" name="h_dtid" />
								<input type="hidden" class="form-control hidden" id="hid_Client_ID" name="hid_Client_ID" />
								<input type="hidden" class="form-control hidden" id="hid_Excp_ID" name="hid_Excp_ID" />
								<button type="submit" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Add</button>
								<button type="submit" name="btn_Client_Edit" id="btn_Client_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_Client_Can" id="btn_Client_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$sqlConnect = 'call select_client()';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
					?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th class="hidden">AH ID </th>
									<th class="hidden">VH ID </th>
									<th>A/C Head</th>
									<th class="hidden">Dept ID</th>
									<th>Dept Name</th>
									<th>Process</th>
									<th class="hidden">OH</th>
									<th>OH Name</th>
									<th class="hidden">QH</th>
									<th>QH Name</th>
									<th class="hidden">TH</th>
									<th>TH Name</th>
									<th class="hidden">ER scop</th>
									<th>Sub Process</th>
									<th>Location</th>
									<th>Manage Client</th>
									<th>Create New</th>
									<th class="hidden">HRID</th>
									<th class="hidden">ITID</th>
									<th class="hidden">ReportsTo</th>
									<th class="hidden">Exception</th>
									<th class="hidden">DT ID</th>
									<th class="hidden">Stipen</th>
									<th class="hidden">Stipen days</th>
									<th class="hidden">FromJoiningd</th>
									<th class="hidden">FromFloord</th>
									<th class="hidden">Rotation Days</th>
									<th class="hidden">locid</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="cm_id">' . $value['cm_id'] . '</td>';
									echo '<td class="client_name">' . $value['cli'] . '</td>';
									echo '<td class="account_head hidden">' . $value['account_head'] . '</td>';
									echo '<td class="VH hidden">' . $value['VH'] . '</td>';
									echo '<td class="EmployeeName">' . $value['ach'] . '</td>';
									echo '<td class="dept_id hidden">' . $value['dept_id'] . '</td>';
									echo '<td class="dept_name">' . $value['dept_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="oh hidden">' . $value['oh'] . '</td>';
									echo '<td class="ohn">' . $value['ohn'] . '</td>';
									echo '<td class="qh hidden">' . $value['qh'] . '</td>';
									echo '<td class="qhn">' . $value['qhn'] . '</td>';
									echo '<td class="th hidden">' . $value['th'] . '</td>';
									echo '<td class="thn">' . $value['thn'] . '</td>';
									echo '<td class="er_scop hidden ">' . $value['er_scop'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="location">' . $value['loc_name'] . '</td>';
									echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';

									//<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item hidden hid" id="'.$value['cm_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>';

									echo '<td  class="manage_item" ><img alt="Process" title="Add Process" class="imgBtn imgbtnProcess"   onclick="javascript:return AddProc(this);" src="../Style/images/porc_png.png" id="' . $value['cm_id'] . '" /> <img alt="Sub Process"  title="Add Sub Process"  class="imgBtn imgbtnSubprocee" src="../Style/images/sproc_png.png"   onclick="javascript:return AddSubProc(this);" id="' . $value['cm_id'] . '" />  </td>';
									echo '<td class="HRID hidden">' . $value['HRID'] . '</td>';
									echo '<td class="ITID hidden">' . $value['ITID'] . '</td>';
									echo '<td class="ReportsTo hidden">' . $value['ReportsTo'] . '</td>';
									echo '<td class="Exception hidden">' . $value['excep_spoc'] . '</td>';
									echo '<td class="dtid hidden">' . $value['ID'] . '</td>';
									echo '<td class="Stipend hidden">' . $value['Stipend'] . '</td>';
									echo '<td class="StipendDays hidden">' . $value['StipendDays'] . '</td>';
									echo '<td class="dtfromjoin hidden">' . $value['days_from_joining'] . '</td>';
									echo '<td class="dtfromfloor hidden">' . $value['days_from_floor'] . '</td>';
									echo '<td class="dtrotation hidden">' . $value['days_of_rotation'] . '</td>';
									echo '<td class="locid hidden">' . $value['location'] . '</td>';
								?>

								<?php
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php } ?>
				</div>
				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {

		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Client_Can').trigger("click");
			}
		});

		// This code for remove error span from all element contain .has-error class on listed events
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

		// This code for cancel button trigger click and also for model close
		$('#btn_Client_Can').on('click', function() {
			$('#txt_Client_Name').val('');
			$('#hid_Client_ID').val('');
			$('#txt_Client_ach').val('NA');
			$('#txt_exception_approver').val('NA');
			$('#txt_Client_dept').val('NA');
			$('#txt_Client_proc').val('');
			$('#txt_Client_oh').val('NA');
			$('#txt_Client_qh').val('NA');
			$('#txt_Client_th').val('NA');

			$('#txt_location').val('NA');

			$('#txt_Client_th').val('');
			$('#txt_Client_subproc').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			//$('#btn_Client_Can').addClass('hidden');
			$('#txt_ERSPOC').val('NA');
			$('#txt_ITID').val('');
			$('#txt_HRID').val('');
			$('#txt_ReportsTo').val('');
			$('#txt_Stipen').val('');
			$('#txt_StipendDays').val('');


			// This code for remove error span from input text on model close and cancel
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
			// This code active label on value assign when any event trigger and value assign by javascript code.

			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();

		});

		// This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.

		$('#btn_Client_Edit,#btn_Client_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			if ($('#txt_location').val() == 'NA') {
				$('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_location').length == 0) {
					$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
				}
				validate = 1;
			}
			if ($('#txt_Client_Name').val() == '') {
				$('#txt_Client_Name').addClass("has-error");
				if ($('#spantxt_Client_Name').length == 0) {
					$('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
				}
				validate = 1;
			}
			if ($('#txt_Client_ach').val() == 'NA') {
				$('#txt_Client_ach').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_ach').length == 0) {
					$('<span id="spantxt_Client_ach" class="help-block">Required *</span>').insertAfter('#txt_Client_ach');
				}
				validate = 1;
			}
			if ($('#txt_vertical_head').val() == 'NA') {
				$('#txt_vertical_head').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_vertical_head').length == 0) {
					$('<span id="spantxt_vertical_head" class="help-block">Required *</span>').insertAfter('#txt_vertical_head');
				}
				validate = 1;
			}
			if ($('#txt_Client_dept').val() == 'NA') {
				$('#txt_Client_dept').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_dept').length == 0) {
					$('<span id="spantxt_Client_dept" class="help-block">Required *</span>').insertAfter('#txt_Client_dept');
				}
				validate = 1;
			}
			if ($('#txt_Client_proc').val() == '') {
				$('#txt_Client_proc').addClass("has-error");
				if ($('#spantxt_Client_proc').length == 0) {
					$('<span id="spantxt_Client_proc" class="help-block">Required *</span>').insertAfter('#txt_Client_proc');
				}
				validate = 1;
			}
			if ($('#txt_Client_oh').val() == 'NA') {
				$('#txt_Client_oh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_oh').length == 0) {
					$('<span id="spantxt_Client_oh" class="help-block">Required *</span>').insertAfter('#txt_Client_oh');
				}
				validate = 1;
			}
			if ($('#txt_Client_qh').val() == 'NA') {
				$('#txt_Client_qh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_qh').length == 0) {
					$('<span id="spantxt_Client_qh" class="help-block">Required *</span>').insertAfter('#txt_Client_qh');
				}
				validate = 1;
			}
			if ($('#txt_Client_th').val() == 'NA') {
				$('#txt_Client_th').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_th').length == 0) {
					$('<span id="spantxt_Client_th" class="help-block">Required *</span>').insertAfter('#txt_Client_th');
				}
				validate = 1;
			}
			if ($('#txt_ERSPOC').val() == 'NA') {
				$('#txt_ERSPOC').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_ERSPOC').length == 0) {
					$('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#txt_ERSPOC');
				}
				validate = 1;
			}
			if ($('#txt_Client_subproc').val() == '') {
				$('#txt_Client_subproc').addClass("has-error");
				if ($('#spantxt_Client_subproc').length == 0) {
					$('<span id="spantxt_Client_subproc" class="help-block">Required *</span>').insertAfter('#txt_Client_subproc');
				}
				validate = 1;
			}
			if ($('#txt_ITID').val() == '') {
				$('#txt_ITID').addClass("has-error");
				if ($('#spantxt_ITID').length == 0) {
					$('<span id="spantxt_ITID" class="help-block">Required *</span>').insertAfter('#txt_ITID');
				}
				validate = 1;
			}
			if ($('#txt_HRID').val() == '') {
				$('#txt_HRID').addClass("has-error");
				if ($('#spantxt_HRID').length == 0) {
					$('<span id="spantxt_HRID" class="help-block">Required *</span>').insertAfter('#txt_HRID');
				}
				validate = 1;
			}
			if ($('#txt_ReportsTo').val() == '') {
				$('#txt_ReportsTo').addClass("has-error");
				if ($('#spantxt_ReportsTo').length == 0) {
					$('<span id="spantxt_ReportsTo" class="help-block">Required *</span>').insertAfter('#txt_ReportsTo');
				}
				validate = 1;
			}
			if ($('#txt_exception_approver').val() == 'NA') {
				$('#txt_exception_approver').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_exception_approver').length == 0) {
					$('<span id="spantxt_exception_approver" class="help-block">Required *</span>').insertAfter('#txt_exception_approver');
				}
				validate = 1;
			}
			if ($('#txt_Stipen').val() == '') {
				$('#txt_Stipen').addClass("has-error");
				if ($('#spantxt_Stipen').length == 0) {
					$('<span id="spantxt_Stipen" class="help-block">Required *</span>').insertAfter('#txt_Stipen');
				}
				validate = 1;
			}
			if ($('#txt_StipendDays').val() == '') {
				$('#txt_StipendDays').addClass("has-error");
				if ($('#spantxt_StipendDays').length == 0) {
					$('<span id="spantxt_StipendDays" class="help-block">Required *</span>').insertAfter('#txt_StipendDays');
				}
				validate = 1;
			}
			//alert($("#txt_exception_approver option:selected").text());	
			$('#hid_Excp_ID').val($("#txt_exception_approver option:selected").text());
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				alert_msg = 'Please fill all required field';

				$(function() {
					toastr.error(alert_msg);
				});
				//alert('1');
				return false;
			}
			//return false;
		});


	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var VH = tr.find('.VH').text();
		var dtid = tr.find('.dtid').text();
		var er_scop = tr.find('.er_scop').text();
		var sub_process = tr.find('.sub_process').text();
		var HRID = tr.find('.HRID').text();
		var ITID = tr.find('.ITID').text();
		var ReportsTo = tr.find('.ReportsTo').text();
		var Exception = tr.find('.Exception').text();

		var Stipend = tr.find('.Stipend').text();
		var StipendDays = tr.find('.StipendDays').text();
		var dtrotation = tr.find('.dtrotation').text();
		var dtfromfloor = tr.find('.dtfromfloor').text();
		var dtfromjoin = tr.find('.dtfromjoin').text();
		var location = tr.find('.location').text();
		var locid = tr.find('.locid').text();
		$('#txt_location').val(locid);
		//alert(location);alert(locid);
		//$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');	

		/*$("#txt_location option").filter(function() {
				return this.text == location; 
			}).attr('selected', true);*/

		getProcess(locid, account_head, VH, oh, qh, th, er_scop, Exception);

		//alert(account_head);
		$('#from_joiningdate').val(dtfromjoin);
		$('#from_floordate').val(dtfromfloor);
		$('#rotation_date').val(dtrotation);
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_exception_approver').val(Exception);
		//alert(Exception);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_Client_proc').val(process);
		$('#txt_Client_oh').val(oh);


		//$('#txt_location').val(location);	
		$('#txt_Client_qh').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_ERSPOC').val(er_scop);
		$('#txt_vertical_head').val(VH);
		$('#txt_HRID').val(HRID);
		$('#txt_ITID').val(ITID);
		$('#h_dtid').val(dtid);
		$('#txt_ReportsTo').val(ReportsTo);
		$('#txt_Stipen').val(Stipend);
		$('#txt_StipendDays').val(StipendDays);
		$('#txt_Client_subproc').val(sub_process);
		$('#btn_Client_Save').addClass('hidden');
		$('#btn_Client_Edit').removeClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');


		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger edit on Sub Proc data table also trigger model open on a Model ID

	function AddSubProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var location = tr.find('.location').text();

		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_Client_proc').val(process);
		$('#txt_location').val(location);
		$('#txt_Client_oh').val(oh);
		$('#txt_Client_qh').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger edit on Proc data table also trigger model open on a Model ID

	function AddProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var location = tr.find('.location').text();
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_location').val(location);
		$('#txt_Client_proc').val('');
		$('#txt_Client_oh').val('NA');
		$('#txt_Client_qh').val('NA');
		$('#txt_Client_th').val('NA');
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger del*t*

	// function ApplicationDataDelete(el, dtid) {
	// 	////alert(el);
	// 	var currentUrl = window.location.href;
	// 	var Cnfm = confirm("Do You Want To Delete This ");
	// 	if (Cnfm) {
	// 		var xmlhttp;
	// 		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	// 			xmlhttp = new XMLHttpRequest();
	// 		} else { // code for IE6, IE5
	// 			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	// 		}
	// 		xmlhttp.onreadystatechange = function() {
	// 			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	// 				var Resp = xmlhttp.responseText;
	// 				//alert(Resp);
	// 				window.location.href = currentUrl;
	// 			}
	// 		}
	// 		xmlhttp.open("GET", "../Controller/DeleteClient.php?ID=" + el.id + "&dttid" + dtid, true);
	// 		xmlhttp.send();
	// 	}
	// }

	function getProcess(el, ach, VH, oh, qh, th, er_scop, Exception) {

		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_Client_ach').html(Resp);
				//$('#txt_vertical_head').html(Resp);
				/*$('#txt_Client_oh').html(Resp);
				$('#txt_Client_qh').html(Resp);
				$('#txt_Client_th').html(Resp);*/
				$('select').formSelect();
			}

		}

		//var location = <?php echo $_SESSION["__location"] ?>;
		//alert(el);
		//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
		xmlhttp.open("GET", "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + ach, true);
		xmlhttp.send();

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=excep&val=" + Exception,
			success: function(result) {
				//alert(result);
				$("#txt_exception_approver").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=vh&val=" + VH,
			success: function(result) {
				//alert(result);
				$("#txt_vertical_head").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + oh,
			success: function(result) {
				//alert(result);
				$("#txt_Client_oh").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + qh,
			success: function(result) {
				//alert(result);
				$("#txt_Client_qh").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + th,
			success: function(result) {
				//alert(result);
				$("#txt_Client_th").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});


		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=hr&val=" + er_scop,
			success: function(result) {
				//alert(result);
				$("#txt_ERSPOC").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});



	}



	function isNumber(evt) {
		var iKeyCode = (evt.which) ? evt.which : evt.keyCode
		if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
			return false;
		return true;
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>