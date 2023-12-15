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
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224') {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

// Global variable used in Page Cycle
$bgv1 = $bgv2 = $bgv3 = $bgv4 = $bgv5 = $bgv6 = $bgv7 = $bgv8 = "No";
$alert_msg  = $_Stipend = '';
$_StipendDays = 0;
if (isset($_POST['StipendDays']) and trim($_POST['StipendDays']) != "") {
	$_StipendDays = trim($_POST['StipendDays']);
}
if (isset($_POST['rotation_date']) and trim($_POST['rotation_date']) != "") {
	$_rotation_date = trim($_POST['rotation_date']);
} else {
	$_rotation_date = 0;
}
if (isset($_POST['from_floordate']) and trim($_POST['from_floordate']) != "") {
	$_from_floordate = trim($_POST['from_floordate']);
} else {
	$_from_floordate = 0;
}
if (isset($_POST['from_joiningdate']) and trim($_POST['from_joiningdate']) != "") {
	$_from_joiningdate = trim($_POST['from_joiningdate']);
} else {
	$_from_joiningdate = 0;
}
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_Client_Save'])) {
	$_Name = (isset($_POST['txt_Client_Name']) ? $_POST['txt_Client_Name'] : null);
	$_ach = (isset($_POST['txt_Client_ach']) ? $_POST['txt_Client_ach'] : null);
	$_ph = (isset($_POST['process_head']) ? $_POST['process_head'] : null);
	$_dept = (isset($_POST['txt_Client_dept']) ? $_POST['txt_Client_dept'] : null);
	$_proc = (isset($_POST['txt_Client_proc']) ? $_POST['txt_Client_proc'] : null);
	$_oh = (isset($_POST['txt_Client_oh']) ? $_POST['txt_Client_oh'] : null);
	$_location = (isset($_POST['txt_location']) ? $_POST['txt_location'] : null);
	$_qh = (isset($_POST['txt_Client_qh']) ? $_POST['txt_Client_qh'] : null);
	$_th = (isset($_POST['txt_Client_th']) ? $_POST['txt_Client_th'] : null);
	$_subproc = (isset($_POST['txt_Client_subproc']) ? $_POST['txt_Client_subproc'] : null);
	$txt_vertical_head = (isset($_POST['txt_vertical_head']) ? $_POST['txt_vertical_head'] : null);
	$sitespoc = (isset($_POST['txt_Site_Spoc']) ? $_POST['txt_Site_Spoc'] : null);

	if (isset($_POST['Stipen2']) and trim($_POST['Stipen2']) != "") {
		$_Stipend = trim($_POST['Stipen2']);
	}
	if (isset($_POST['bgv1']) and trim($_POST['bgv1']) != "") {
		$bgv1 = cleanUserInput(trim($_POST['bgv1']));
	} else {
		$bgv1 = "No";
	}
	if (($_POST['bgv2']) and trim($_POST['bgv2']) != "") {
		$bgv2 = cleanUserInput(trim($_POST['bgv2']));
	} else {
		$bgv2 = "No";
	}
	if (($_POST['bgv3']) and trim($_POST['bgv3']) != "") {
		$bgv3 = cleanUserInput(trim($_POST['bgv3']));
	} else {
		$bgv3 = "No";
	}
	if (($_POST['bgv4']) and trim($_POST['bgv4']) != "") {
		$bgv4 = cleanUserInput(trim($_POST['bgv4']));
	} else {
		$bgv4 = "No";
	}
	if (isset($_POST['bgv5']) and trim($_POST['bgv5']) != "") {
		$bgv5 = cleanUserInput(trim($_POST['bgv5']));
	} else {
		$bgv5 = "No";
	}
	if (($_POST['bgv6']) and trim($_POST['bgv6']) != "") {
		$bgv6 = cleanUserInput(trim($_POST['bgv6']));
	} else {
		$bgv6 = "No";
	}
	if (($_POST['bgv7']) and trim($_POST['bgv7']) != "") {
		$bgv7 = cleanUserInput(trim($_POST['bgv7']));
	} else {
		$bgv7 = "No";
	}
	if (($_POST['bgv8']) and trim($_POST['bgv8']) != "") {
		$bgv8 = cleanUserInput(trim($_POST['bgv8']));
	} else {
		$bgv8 = "No";
	}

	$createBy = $_SESSION['__user_logid'];
	$Insert = 'CALL add_client_new("' . trim($_Name) . '","' . $_ach . '","' . $_dept . '","' . trim($_proc) . '","' . $_oh . '","' . $_qh . '","' . $_th . '","' . trim($_subproc) . '","' . $createBy . '","' . $sitespoc . '","' . $_Stipend . '","' . $_StipendDays . '","' . trim($_from_joiningdate) . '","' . trim($_from_floordate) . '","' . trim($_rotation_date) . '","' . $txt_vertical_head . '","' . $_location . '","' . $_ph . '")';
	$myDB = new MysqliDb();
	$resCount = $myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();

	$select_clientMaster = "select cm_id from new_client_master  order by  cm_id desc limit 1;";
	$myDB = new MysqliDb();
	$Res_cmid = $myDB->query($select_clientMaster);

	$cmid = $Res_cmid[0]['cm_id'];
	if (empty($mysql_error)) {
		$insert = 'insert into bgv_matrix (cm_id,desig, Addr,Edu,Emp,Crim)values(?,"Support",?,?,?,?)';
		$insQ = $conn->prepare($insert);
		$insQ->bind_param('issss', $cmid, $bgv1, $bgv2, $bgv3, $bgv4);
		$insQ->execute();
		$resu = $insQ->get_result();


		$insert = 'insert into bgv_matrix (cm_id,desig, Addr,Edu,Emp,Crim)values(?,"CSA",?,?,?,?)';
		$insQ = $conn->prepare($insert);
		$insQ->bind_param('issss', $cmid, $bgv5, $bgv6, $bgv7, $bgv8);
		$insQ->execute();
		$resu = $insQ->get_result();
	}
	if ($insQ->affected_rows === 1) {
		echo "<script>$(function(){ toastr.success('Client Added Successfully'); }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Client not Added.'); }); </script>";
	}
}

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_Client_Edit'])) {
	$DataID = $_POST['hid_Client_ID'];
	$_Name = (isset($_POST['txt_Client_Name']) ? $_POST['txt_Client_Name'] : null);
	$_ach = (isset($_POST['txt_Client_ach']) ? $_POST['txt_Client_ach'] : null);
	$_ph = (isset($_POST['process_head']) ? $_POST['process_head'] : null);
	$_dept = (isset($_POST['txt_Client_dept']) ? $_POST['txt_Client_dept'] : null);
	$_proc = (isset($_POST['txt_Client_proc']) ? $_POST['txt_Client_proc'] : null);
	$_oh = (isset($_POST['txt_Client_oh']) ? $_POST['txt_Client_oh'] : null);
	$_location = (isset($_POST['txt_location']) ? $_POST['txt_location'] : null);
	$_qh = (isset($_POST['txt_Client_qh']) ? $_POST['txt_Client_qh'] : null);
	$_th = (isset($_POST['txt_Client_th']) ? $_POST['txt_Client_th'] : null);
	$_subproc = (isset($_POST['txt_Client_subproc']) ? $_POST['txt_Client_subproc'] : null);

	$txt_vertical_head = (isset($_POST['txt_vertical_head']) ? $_POST['txt_vertical_head'] : null);
	$sitespoc = (isset($_POST['txt_Site_Spoc']) ? $_POST['txt_Site_Spoc'] : null);
	if (isset($_POST['Stipen2']) and trim($_POST['Stipen2']) != "") {
		$_Stipend = trim($_POST['Stipen2']);
	}

	if (isset($_POST['StipendDays']) and trim($_POST['StipendDays']) != "") {
		$_StipendDays = trim($_POST['StipendDays']);
	}
	if (isset($_POST['h_dtid'])) {
		$_h_dtid = $_POST['h_dtid'];
	} else $_h_dtid = "";
	$ModifiedBy = $_SESSION['__user_logid'];

	$Update = 'call save_client_new("' . trim($_Name) . '","' . $_ach . '","' . $_dept . '","' . trim($_proc) . '","' . $_oh . '","' . $_qh . '","' . $_th . '","' . trim($_subproc) . '","' . $ModifiedBy . '","' . $DataID . '","' . $sitespoc . '","' . $_h_dtid . '","' . $_Stipend . '","' . $_StipendDays . '","' . trim($_from_joiningdate) . '","' . trim($_from_floordate) . '","' . trim($_rotation_date) . '","' . $txt_vertical_head . '","' . $_location . '","' . $_ph . '")';

	$myDB = new MysqliDb();
	if (!empty($DataID) || $DataID != '') {
		$myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();

		if (isset($_POST['bgv1']) and trim($_POST['bgv1']) != "") {
			$bgv1 = cleanUserInput(trim($_POST['bgv1']));
		}
		if (($_POST['bgv2']) and trim($_POST['bgv2']) != "") {
			$bgv2 = cleanUserInput(trim($_POST['bgv2']));
		}
		if (($_POST['bgv3']) and trim($_POST['bgv3']) != "") {
			$bgv3 = cleanUserInput(trim($_POST['bgv3']));
		}
		if (($_POST['bgv4']) and trim($_POST['bgv4']) != "") {
			$bgv4 = cleanUserInput(trim($_POST['bgv4']));
		}
		if (isset($_POST['bgv5']) and trim($_POST['bgv5']) != "") {
			$bgv5 = cleanUserInput(trim($_POST['bgv5']));
		}
		if (($_POST['bgv6']) and trim($_POST['bgv6']) != "") {
			$bgv6 = cleanUserInput(trim($_POST['bgv6']));
		}
		if (($_POST['bgv7']) and trim($_POST['bgv7']) != "") {
			$bgv7 = cleanUserInput(trim($_POST['bgv7']));
		}
		if (($_POST['bgv8']) and trim($_POST['bgv8']) != "") {
			$bgv8 = cleanUserInput(trim($_POST['bgv8']));
		}
		$sel = 'select * from bgv_matrix where cm_id=? and desig="Support"';
		$selectQ = $conn->prepare($sel);
		$selectQ->bind_param('i', $DataID);
		$selectQ->execute();
		$res = $selectQ->get_result();
		if ($res->num_rows > 0) {
			$Update = 'update  bgv_matrix set Addr=?,Edu=?,Emp=?,Crim=? where cm_id=? and desig="Support"';
			$upQ = $conn->prepare($Update);
			$upQ->bind_param('ssssi', $bgv1, $bgv2, $bgv3, $bgv4, $DataID);
			$upQ->execute();
			$resu = $upQ->get_result();
		} else {
			$insert = 'insert into bgv_matrix (cm_id,desig, Addr,Edu,Emp,Crim)values(?,"Support",?,?,?,?)';
			$upQ = $conn->prepare($insert);
			$upQ->bind_param('issss', $DataID, $bgv1, $bgv2, $bgv3, $bgv4);
			$upQ->execute();
			$resu = $upQ->get_result();
		}

		$sel = 'select * from bgv_matrix where cm_id=? and desig="CSA"';
		$selectQ = $conn->prepare($sel);
		$selectQ->bind_param('i', $DataID);
		$selectQ->execute();
		$res = $selectQ->get_result();

		if ($res->num_rows > 0) {
			$Update = 'update  bgv_matrix set Addr=?,Edu=?,Emp=?,Crim=? where cm_id=? and desig="CSA"';
			$upQ = $conn->prepare($Update);
			$upQ->bind_param('ssssi', $bgv5, $bgv6, $bgv7, $bgv8, $DataID);
			$upQ->execute();
			$resu = $upQ->get_result();
		} else {
			$insert = 'insert into bgv_matrix (cm_id,desig, Addr,Edu,Emp,Crim)values(?,"CSA",?,?,?,?)';
			$upQ = $conn->prepare($insert);
			$upQ->bind_param('issss', $DataID, $bgv5, $bgv6, $bgv7, $bgv8);
			$upQ->execute();
			$resu = $upQ->get_result();
		}

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
										$sqlBy = 'select id,location from location_master;';
										$myDB = new MysqliDb();
										$resultBy = $myDB->rawQuery($sqlBy);
										$mysql_error = $myDB->getLastError();
										if (empty($mysql_error)) {
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
									</select>
									<label for="txt_Client_ach" class="active-drop-down active">Account Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_vertical_head" name="txt_vertical_head" required>
									</select>
									<label for="txt_vertical_head" class="active-drop-down active">Vertical Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_dept" name="txt_Client_dept" required>
										<option value="NA">----Select----</option>
										<?php
										$sqlBy = "select dept_id,dept_name from dept_master";
										$myDB = new MysqliDb();
										$resultBy = $myDB->rawQuery($sqlBy);
										$mysql_error = $myDB->getLastError();
										if (empty($mysql_error)) {
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
									</select>
									<label for="txt_Client_oh" class="active-drop-down active">Operation Head</label>
								</div>
								<div class="input-field col s4 m4">
									<select id="txt_Client_qh" name="txt_Client_qh" required>
									</select>
									<label for="txt_Client_qh" class="active-drop-down active">Quality Head</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s4 m4">
									<select id="txt_Client_th" name="txt_Client_th" required>
									</select>
									<label for="txt_Client_th" class="active-drop-down active">Training Head</label>
								</div>

								<div class="input-field col s4 m4">
									<select id="process_head" name="process_head" required>
									</select>
									<label for="process_head" class="active-drop-down active">Process Head</label>
								</div>

								<div class="input-field col s4 m4">
									<input type="text" id="txt_Client_subproc" name="txt_Client_subproc" required />
									<label for="txt_Client_subproc">Sub Process</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s4 m4">
									<select id="txt_Site_Spoc" name="txt_Site_Spoc" required>
									</select>
									<label for="txt_Site_Spoc" class="active-drop-down active">Site Spoc</label>
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

							<div class="col s12 m12">
								<h4>BGV</h4>
								<p><b>For Support</b></p>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv1" id="bgv1" value="Yes">
									<label id="lbl" for="bgv1">Address</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv2" id="bgv2" value="Yes">
									<label id="lbl" for="bgv2">Education</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv3" id="bgv3" value="Yes">
									<label id="lbl" for="bgv3">Employement</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv4" id="bgv4" value="Yes">
									<label id="lbl" for="bgv4">Criminal</label>
								</div>
								<br><br><br>
								<p><b>For CSA</b></p>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv5" id="bgv5" value="Yes">
									<label id="lbl" for="bgv5">Address</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv6" id="bgv6" value="Yes">
									<label id="lbl" for="bgv6">Education</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv7" id="bgv7" value="Yes">
									<label id="lbl" for="bgv7">Employement</label>
								</div>
								<div class="input-field col s3 m3">
									<input type="checkbox" name="bgv8" id="bgv8" value="Yes">
									<label id="lbl" for="bgv8">Criminal</label>
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
									<th class="hidden">P/H</th>
									<th>Process Head</th>
									<th class="hidden">Dept ID</th>
									<th>Dept Name</th>
									<th>Process</th>
									<th class="hidden">OH</th>
									<th>OH Name</th>
									<th class="hidden">QH</th>
									<th>QH Name</th>
									<th class="hidden">TH</th>
									<th>TH Name</th>
									<th>Sub Process</th>
									<th>Location</th>
									<th>Manage Client</th>
									<th>Create New</th>
									<th class="hidden">Site Spoc</th>
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
									echo '<td class="process_head hidden">' . $value['process_head'] . '</td>';
									echo '<td class="p_head">' . $value['phn'] . '</td>';
									echo '<td class="dept_id hidden">' . $value['dept_id'] . '</td>';
									echo '<td class="dept_name">' . $value['dept_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="oh hidden">' . $value['oh'] . '</td>';
									echo '<td class="ohn">' . $value['ohn'] . '</td>';
									echo '<td class="qh hidden">' . $value['qh'] . '</td>';
									echo '<td class="qhn">' . $value['qhn'] . '</td>';
									echo '<td class="th hidden">' . $value['th'] . '</td>';
									echo '<td class="thn">' . $value['thn'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="location">' . $value['loc_name'] . '</td>';
									echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
									echo '<td  class="manage_item" ><img alt="Process" title="Add Process" class="imgBtn imgbtnProcess"   onclick="javascript:return AddProc(this);" src="../Style/images/porc_png.png" id="' . $value['cm_id'] . '" /> <img alt="Sub Process"  title="Add Sub Process"  class="imgBtn imgbtnSubprocee" src="../Style/images/sproc_png.png"   onclick="javascript:return AddSubProc(this);" id="' . $value['cm_id'] . '" />  </td>';
									echo '<td class="SiteSpoc hidden">' . $value['SiteSpoc'] . '</td>';
									echo '<td class="dtid hidden">' . $value['ID'] . '</td>';
									echo '<td class="Stipend hidden">' . $value['Stipend'] . '</td>';
									echo '<td class="StipendDays hidden">' . $value['StipendDays'] . '</td>';
									echo '<td class="dtfromjoin hidden">' . $value['days_from_joining'] . '</td>';
									echo '<td class="dtfromfloor hidden">' . $value['days_from_floor'] . '</td>';
									echo '<td class="dtrotation hidden">' . $value['days_of_rotation'] . '</td>';
									echo '<td class="locid hidden">' . $value['location'] . '</td>';
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
			$('#txt_Site_Spoc').val('NA');
			$('#txt_Client_ach').val('NA');
			$('#txt_Client_dept').val('NA');
			$('#txt_Client_proc').val('');
			$('#txt_Client_oh').val('NA');
			$('#txt_Client_qh').val('NA');
			$('#txt_Client_th').val('NA');
			$('#process_head').val('NA');
			$('#txt_location').val('NA');

			$('#txt_Client_th').val('');
			$('#txt_Client_subproc').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			$('#txt_Stipen').val('');
			$('#txt_StipendDays').val('');
			$('#bgv1').prop('checked', false);
			$('#bgv2').prop('checked', false);
			$('#bgv3').prop('checked', false);
			$('#bgv4').prop('checked', false);
			$('#bgv5').prop('checked', false);
			$('#bgv6').prop('checked', false);
			$('#bgv7').prop('checked', false);
			$('#bgv8').prop('checked', false);

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
				if ($('#spantxt_location').size() == 0) {
					$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
				}
				validate = 1;
			}
			if ($('#txt_Client_Name').val() == '') {
				$('#txt_Client_Name').addClass("has-error");
				if ($('#spantxt_Client_Name').size() == 0) {
					$('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
				}
				validate = 1;
			}
			if ($('#txt_Client_ach').val() == 'NA') {
				$('#txt_Client_ach').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_ach').size() == 0) {
					$('<span id="spantxt_Client_ach" class="help-block">Required *</span>').insertAfter('#txt_Client_ach');
				}
				validate = 1;
			}
			if ($('#process_head').val() == 'NA') {
				$('#process_head').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanprocess_head').size() == 0) {
					$('<span id="spanprocess_head" class="help-block">Required *</span>').insertAfter('#process_head');
				}
				validate = 1;
			}
			if ($('#txt_vertical_head').val() == 'NA') {
				$('#txt_vertical_head').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_vertical_head').size() == 0) {
					$('<span id="spantxt_vertical_head" class="help-block">Required *</span>').insertAfter('#txt_vertical_head');
				}
				validate = 1;
			}
			if ($('#txt_Client_dept').val() == 'NA') {
				$('#txt_Client_dept').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_dept').size() == 0) {
					$('<span id="spantxt_Client_dept" class="help-block">Required *</span>').insertAfter('#txt_Client_dept');
				}
				validate = 1;
			}
			if ($('#txt_Client_proc').val() == '') {
				$('#txt_Client_proc').addClass("has-error");
				if ($('#spantxt_Client_proc').size() == 0) {
					$('<span id="spantxt_Client_proc" class="help-block">Required *</span>').insertAfter('#txt_Client_proc');
				}
				validate = 1;
			}
			if ($('#txt_Client_oh').val() == 'NA') {
				$('#txt_Client_oh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_oh').size() == 0) {
					$('<span id="spantxt_Client_oh" class="help-block">Required *</span>').insertAfter('#txt_Client_oh');
				}
				validate = 1;
			}
			if ($('#txt_Client_qh').val() == 'NA') {
				$('#txt_Client_qh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_qh').size() == 0) {
					$('<span id="spantxt_Client_qh" class="help-block">Required *</span>').insertAfter('#txt_Client_qh');
				}
				validate = 1;
			}
			if ($('#txt_Client_th').val() == 'NA') {
				$('#txt_Client_th').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_th').size() == 0) {
					$('<span id="spantxt_Client_th" class="help-block">Required *</span>').insertAfter('#txt_Client_th');
				}
				validate = 1;
			}
			if ($('#txt_Site_Spoc').val() == 'NA') {
				$('#txt_Site_Spoc').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Site_Spoc').size() == 0) {
					$('<span id="spantxt_Site_Spoc" class="help-block">Required *</span>').insertAfter('#txt_Site_Spoc');
				}
				validate = 1;
			}

			if ($('#txt_Client_subproc').val() == '') {
				$('#txt_Client_subproc').addClass("has-error");
				if ($('#spantxt_Client_subproc').size() == 0) {
					$('<span id="spantxt_Client_subproc" class="help-block">Required *</span>').insertAfter('#txt_Client_subproc');
				}
				validate = 1;
			}

			if ($('#txt_Stipen').val() == '') {
				$('#txt_Stipen').addClass("has-error");
				if ($('#spantxt_Stipen').size() == 0) {
					$('<span id="spantxt_Stipen" class="help-block">Required *</span>').insertAfter('#txt_Stipen');
				}
				validate = 1;
			}
			if ($('#txt_StipendDays').val() == '') {
				$('#txt_StipendDays').addClass("has-error");
				if ($('#spantxt_StipendDays').size() == 0) {
					$('<span id="spantxt_StipendDays" class="help-block">Required *</span>').insertAfter('#txt_StipendDays');
				}
				validate = 1;
			}
			if (validate == 1) {
				alert_msg = 'Please fill all required field';

				$(function() {
					toastr.error(alert_msg);
				});
				return false;
			}
		});


	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var SiteSpoc = tr.find('.SiteSpoc').text();
		var account_head = tr.find('.account_head').text();
		var process_head = tr.find('.process_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var VH = tr.find('.VH').text();
		var dtid = tr.find('.dtid').text();
		var sub_process = tr.find('.sub_process').text();
		var Stipend = tr.find('.Stipend').text();
		var StipendDays = tr.find('.StipendDays').text();
		var dtrotation = tr.find('.dtrotation').text();
		var dtfromfloor = tr.find('.dtfromfloor').text();
		var dtfromjoin = tr.find('.dtfromjoin').text();
		var location = $.trim(tr.find('.location').text());
		var locid = $.trim(tr.find('.locid').text());


		$('#txt_location').val(locid);


		getProcess(locid, account_head, VH, oh, qh, th, SiteSpoc, process_head);

		getbgv1(client_id);
		getbgv2(client_id);
		$('#from_joiningdate').val(dtfromjoin);
		$('#from_floordate').val(dtfromfloor);
		$('#rotation_date').val(dtrotation);
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Site_Spoc').val(SiteSpoc);
		$('#txt_Client_ach').val(account_head);
		$('#process_head').val(process_head);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_Client_proc').val(process);
		$('#txt_Client_oh').val(oh);
		$('#txt_Client_qh').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_vertical_head').val(VH);
		$('#h_dtid').val(dtid);
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
		var SiteSpoc = tr.find('.SiteSpoc').text();
		var account_head = tr.find('.account_head').text();
		var process_head = tr.find('.process_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var location = tr.find('.location').text();

		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Site_Spoc').val(SiteSpoc);
		$('#txt_Client_ach').val(account_head);
		$('#process_head').val(process_head);
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
		var SiteSpoc = tr.find('.SiteSpoc').text();
		var account_head = tr.find('.account_head').text();
		var process_head = tr.find('.process_head').text();
		var dept_id = tr.find('.dept_id').text();
		var location = tr.find('.location').text();
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Site_Spoc').val(SiteSpoc);
		$('#txt_Client_ach').val(account_head);
		$('#process_head').val(process_head);
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

	function ApplicationDataDelete(el, dtid) {
		////alert(el);
		var currentUrl = window.location.href;
		var Cnfm = confirm("Do You Want To Delete This ");
		if (Cnfm) {
			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var Resp = xmlhttp.responseText;
					//alert(Resp);
					window.location.href = currentUrl;
				}
			}
			xmlhttp.open("GET", "../Controller/DeleteClient.php?ID=" + el.id + "&dttid" + dtid, true);
			xmlhttp.send();
		}
	}


	function getbgv1(client_id) {
		$.ajax({
			url: "../Controller/getbgv.php?cm_id=" + client_id + "&type=support",
			success: function(result) {
				$("#bgv1").html(result);
				$("#bgv2").html(result);
				$("#bgv3").html(result);
				$("#bgv4").html(result);
			}
		});
	}

	function getbgv2(client_id) {
		$.ajax({
			url: "../Controller/getbgv.php?cm_id=" + client_id + "&type=CSA",
			success: function(result) {
				// alert(result)
				$("#bgv5").html(result);
				$("#bgv6").html(result);
				$("#bgv7").html(result);
				$("#bgv8").html(result);
			}
		});
	}

	function getProcess(el, ach, VH, oh, qh, th, SiteSpoc, process_head) {
		$.ajax({
			url: "../Controller/getalignmentByLocation_new.php?loc=" + $('#txt_location').val() + "&VH=" + VH + "&SiteSpoc=" + SiteSpoc + "&oh=" + oh + "&qh=" + qh + "&th=" + th + "&ach=" + ach + "&ph=" + process_head,
			success: function(result) {
				//alert(result);
				var array = result.split("||NA||");
				$("#txt_vertical_head").html(array[0]);
				$('select').formSelect();
				$("#txt_Site_Spoc").html(array[1]);
				$('select').formSelect();
				$("#txt_Client_oh").html(array[2]);
				$('select').formSelect();
				$("#txt_Client_qh").html(array[3]);
				$('select').formSelect();
				$("#txt_Client_th").html(array[4]);
				$('select').formSelect();
				$("#txt_Client_ach").html(array[5]);
				$('select').formSelect();
				$("#process_head").html(array[6]);
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