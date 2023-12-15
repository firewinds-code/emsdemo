<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$user_logid = clean($_SESSION['__user_logid']);

if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$classvarr = "'.byID'";
$msg = $para1 = $para2 = $para3 = $para4 = $para5 = $sdate = $searchBy = $remark = $empname = $empid = $edate = $empID = $val = '';
if (isset($_GET['sdate'])) {
	$sdate = cleanUserInput($_GET['sdate']);
	$edate = cleanUserInput($_GET['enddate']);
}
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = $user_logid;
		$empname = trim(cleanUserInput($_POST['empname']));
		$empid = trim(cleanUserInput($_POST['empid']));
		$remark = trim(cleanUserInput($_POST['remark2']));
		$disposition = trim(cleanUserInput($_POST['txt_Disposition']));

		if ($disposition == 'Better career prospect') {
			$para1 = trim(cleanUserInput($_POST['text_company']));
			$para2 = trim(cleanUserInput($_POST['text_location']));
			$para3 = trim(cleanUserInput($_POST['text_level']));
			$para4 = trim(cleanUserInput($_POST['bc_date']));
			$para5 = trim(cleanUserInput($_POST['text_ctc']));
			if ($para1 == "" || $para2 == "" || $para3 == "" || $para4 == "" || $para5 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		} elseif ($disposition == 'Family Reasons') {
			$para1 = $fdid_apply_leave = trim(cleanUserInput($_POST['resion_apply_leave']));
			$para2 = trim(cleanUserInput($_POST['whome_did_you_apply']));
			$para3 = trim(cleanUserInput($_POST['issue_resolve_rejoin']));
			$para4 = trim(cleanUserInput($_POST['family_date']));
			if ($para1 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		} elseif ($disposition == 'Further Studies') {
			$para1 = trim(cleanUserInput($_POST['learning']));
			$para2 = trim(cleanUserInput($_POST['joinback']));
			$para3 = trim(cleanUserInput($_POST['join_date']));
			if ($para1 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		} elseif ($disposition == 'Getting married') {
			$para1 = trim(cleanUserInput($_POST['willing_work']));
			$para2 = trim(cleanUserInput($_POST['mpref_location']));
			$para3 = trim(cleanUserInput($_POST['when_date']));
			if ($para1 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		} elseif ($disposition == 'Long Distance') {
			$para1 = trim(cleanUserInput($_POST['pref_location']));
			$para2 = trim(cleanUserInput($_POST['pref_shift']));
			$para3 = trim(cleanUserInput($_POST['distance_join']));
			$para4 = trim(cleanUserInput($_POST['joinback_date']));

			if ($para1 == "" || $para2 == "" || $para3 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		} elseif ($disposition == 'Decertification') {
			$para1 = trim(cleanUserInput($_POST['oprocess_offered']));
			$para2 = trim(cleanUserInput($_POST['decertify_joinback']));
			$para3 = trim(cleanUserInput($_POST['dcerti_join']));

			if ($para1 == "") {
				echo "<script>$(function(){ toastr.info('Please fill all the fields') }); </script>";
			}
		}
		if ($disposition == "") {
			$disposition = 'NA';
		}
		$contact_status = cleanUserInput($_POST['txt_ContactStatus']);
		if ($empid != "" && ($remark != "" && $remark != "Remark")) {
			$empID = $val;
			$myDB = new MysqliDb();
			//echo "<br>";echo "<br>";
			$insert = "call Add_inactive_remark('" . $empid . "','" . $remark . "','" . $createBy . "','" . $disposition . "','" . $contact_status . "','" . $para1 . "','" . $para2 . "','" . $para3 . "','" . $para4 . "','" . $para5 . "')";
			$resulti = $myDB->query($insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Remark Added Successfully') }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Remark Added Successfully " . $mysql_error . "') }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Please enter Remark') }); </script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('#when_date, #join_date, #joinback_date, #family_date, #bc_date, #dcerti_join,#startdate,#enddate').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('.statuscheck').addClass('hidden');
		$('#txt_ED_joindate_to').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#txt_ED_joindate_from').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Inactive Employee</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Inactive Employee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="statuscheck">
					<div class="input-field col s4 m4 clsIDHome">

						<input type="text" readonly="true" id="empnameEdit" name="empname" value="<?php echo 'Name'; ?>" />
						<label class='empnameEdit'>Name</label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="empidEdit" name="empid" value="<?php echo 'Employee ID'; ?>" />
						<label for="empidEdit">Employee ID</label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="ProcessEdit" name="Process" value="<?php echo 'Process'; ?>" />
						<label for="ProcessEdit">Process</label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="SubProcessEdit" name="SubProcess" placeholder="<?php echo 'Sub Process'; ?>" />
						<label for="txt_Comment">Sub Process </label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="leaving_dateEdit" name="leaving_date" value="<?php echo 'leaving_dateEdit'; ?>" />
						<label for="leaving_dateEdit">Releiving Date</label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="leaving_resionEdit" name="leaving_resionEdit" placeholder="<?php echo 'leaving_resionEdit'; ?>" />
						<label for="leaving_resionEdit">Resion of Releiving</label>
					</div>
					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="mobile" name="mobile" placeholder="<?php echo 'mobile'; ?>" />
						<label for="mobile">Mobile No</label>
					</div>

					<div class="input-field col s4 m4 clsIDHome">
						<input type="text" readonly="true" id="altmobile" name="altmobile" placeholder="<?php echo 'altmobile'; ?>" />
						<label for="altmobile">Alt. Mobile No</label>
					</div>

					<div class="input-field col s4 m4 clsIDHome">
						<select id="txt_ContactStatus" name="txt_ContactStatus">
							<option value="NA">---Select---</option>
							<option value="Not Contacted">Not Contacted</option>
							<option value="Not Contactable">Not Contactable</option>
							<option value="Contacted">Contacted</option>
						</select>
						<label for="txt_ContactStatus" class="active-drop-down active">Contact Status</label>
					</div>

					<div class="input-field col s4 m4 clsIDHome">
						<select id="txt_Disposition" name="txt_Disposition">
							<option value="NA">---Select---</option>
						</select>
						<label for="txt_Disposition" class="active-drop-down active">Disposition</label>
					</div>

					<div id="better_carieer_id">
						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="text_company" name="text_company" maxlength="100" />
							<label for="txt_Comment">Company Name</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="text_location" name="text_location" maxlength="100" />
							<label for="txt_Comment">Location</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="text_level" name="text_level" maxlength="100" />
							<label for="text_level">Level</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="text_ctc" name="text_ctc" maxlength="100" />
							<label for="text_ctc">CTC</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="bc_date" name="bc_date" />
							<label for="bc_date">By when date</label>
						</div>
					</div>

					<div id='family_resion_id' class="col s12 m12 clsIDHome no-padding">
						<div class="col s4 m4 no-padding">
							<div class="input-field col s7 m7">
								<p>Did you apply for leaves?</p>
							</div>
							<div class="input-field col s3 m3">

								<input type="radio" id="reason_yes" name="resion_apply_leave" value="Yes" />
								<label for="reason_yes">Yes</label>

							</div>
							<div class="input-field col s2 m2">
								<input type="radio" id="reason_no" name="resion_apply_leave" value="No" />
								<label for="reason_no">No</label>
							</div>
						</div>
						<div id="whome_apply_id" class="col s12 m12 no-padding">
							<div class="input-field col s4 m4 clsIDHome" id="whome_apply">
								<select id="did_you_apply" name="whome_did_you_apply">
									<option value="OH">OH</option>
									<option value="AH">AH</option>
								</select>
								<label for="whome_did_you_apply" class="active-drop-down active">To whome did you apply leave?:</label>
							</div>

							<div class="col s4 m4 clsIDHome" id='resean_will_join'>
								<div class="input-field col s6 m6">
									<p>Will you re join?</p>
								</div>
								<div class="input-field col s3 m3">

									<input type="radio" id="resolve_yes" name="issue_resolve_rejoin" value="Yes" />
									<label for="resolve_yes">Yes</label>
								</div>
								<div class="input-field col s2 m2">

									<input type="radio" id="resolve_no" name="issue_resolve_rejoin" value="No" />
									<label for="resolve_no">No</label>
								</div>
							</div>


							<div class="input-field col s4 m4 clsIDHome" id="familyr_jd">
								<input type="text" id="family_date" name="family_date" />
								<label for="family_date">By when date</label>
							</div>
						</div>
					</div>
					<div id="further_study" class="input-field col s12 m12 no-padding">
						<div class="col s2 m2 clsIDHome">
							<p>Learning?</p>
						</div>
						<div class="col s2 m2 clsIDHome">
							<input type="radio" id="distance" name="learning" value="distance" />
							<label for="distance">Distance</label>
						</div>
						<div class="col s2 m2 clsIDHome">
							<input type="radio" id="regular" name="learning" value="regular" />
							<label for="regular">Regular</label>
						</div>

						<div class="col s6 m6 clsIDHome" id='willing_join_id'>
							<div class="col s4 m4 clsIDHome">
								<p>Willing to join back?</p>
							</div>
							<div class="col s3 m3 clsIDHome">
								<input type="radio" id="join_yes" name="joinback" value="Yes" />
								<label for="join_yes">Yes</label>
							</div>
							<div class="col s2 m2 clsIDHome">
								<input type="radio" id="join_no" name="joinback" value="No" />
								<label for="join_no">No</label>
							</div>

						</div>
						<div class="input-field col s6 m6 clsIDHome" id='fs_jdid'>
							<input type="text" id="join_date" name="join_date" />
							<label for="join_date">Join date</label>
						</div>

					</div>

					<div id='get_married' class="input-field col s12 m12 no-padding clsIDHome">
						<div class="col s4 m4">

							<div class="col s6 m6">
								<p>Willing to work?</p>
							</div>
							<div class="col s3 m3">
								<input type="radio" id="willing_yes" name="willing_work" value="Yes" />
								<label for="willing_yes">Yes</label>
							</div>
							<div class="col s2 m2">
								<input type="radio" id="willing_no" name="willing_work" value="No" />
								<label for="willing_no">No</label>
							</div>
						</div>


						<div class="input-field col s4 m4 clsIDHome" id='pref_location_id'>
							<input type="text" id="mpref_location" name="mpref_location" />
							<label for="mpref_location">Location Prefrance</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome" id='pref_date_id'>
							<input type="text" id="when_date" name="when_date" />
							<label for="when_date">By When</label>
						</div>

					</div>

					<div id='long_distance' class="input-field col s12 m12 no-padding">

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="pref_location" name="pref_location" />
							<label for="pref_location">Location</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<input type="text" id="pref_shift" name="pref_shift" />
							<label for="pref_shift">Shift</label>
						</div>

						<div class="input-field col s4 m4 clsIDHome">
							<div class="col s6 m6">
								<p>Willing to join back?</p>
							</div>
							<div class="col s3 m3">
								<input type="radio" id="distance_join_yes" name="distance_join" value="Yes" />
								<label for="distance_join_yes">Yes</label>
							</div>
							<div class="col s2 m2">
								<input type="radio" id="distance_join_no" name="distance_join" value="No" />
								<label for="distance_join_no">No</label>
							</div>

						</div>

						<div class="input-field col s6 m6 clsIDHome" id='joinbackdate_id'>
							<input type="text" id="joinback_date" name="joinback_date" />
							<label for="joinback_date">Join back date</label>
						</div>

					</div>

					<div id='decertification' class="input-field col s12 m12 no-padding">
						<div class="col s4 m4 clsIDHome">
							<div class="col s6 m6">
								<p>Did other process offered to You?</p>
							</div>
							<div class="col s3 m3">
								<input type="radio" id="oprocess_yes" name="oprocess_offered" value="Yes" />
								<label for="oprocess_yes">Yes</label>
							</div>
							<div class="col s2 m2">
								<input type="radio" id="oprocess_no" name="oprocess_offered" value="No" />
								<label for="oprocess_no">No</label>
							</div>

						</div>

						<div class="col s4 m4" id='willing_back_join'>
							<div class="col s6 m6">
								<p>Willing to join back?</p>
							</div>
							<div class="col s3 m3">
								<input type="radio" id="willing_join_yes" name="decertify_joinback" value="Yes" />
								<label for="willing_join_yes">Yes</label>
							</div>
							<div class="col s2 m2">
								<input type="radio" id="willing_join_no" name="decertify_joinback" value="No" />
								<label for="willing_join_no">No</label>
							</div>

						</div>

						<div class="input-field col s4 m4" id='decerti_id'>
							<input type="text" id="dcerti_join" name="dcerti_join" />
							<label for="dcerti_join">Join back date</label>
						</div>

					</div>

					<div class="input-field col s12 m12">
						<textarea id="textremark" class="materialize-textarea" name="remark2" maxlength="1000"></textarea>
						<label for="textremark">Remark</label>
					</div>

					<div class="input-field col s12 m12 right-align clsIDHome">
						<input type="submit" value="Update" name="btnSave" id="btnSave1" class="btn waves-effect waves-green" />
						<input type="button" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect modal-action modal-close waves-red close-btn" />
					</div>
				</div>

				<div id="search">
					<div class="input-field col s5 m5">
						<input type="text" id="startdate" name="startdate" value="<?php echo $sdate; ?>">
						<label for="startdate">Start Date</label>
					</div>
					<div class="input-field col s5 m5">
						<input type="text" id="enddate" name="enddate" value="<?php echo $edate; ?>">
						<label for="enddate">End Date</label>
					</div>
					<div class="input-field col s2 m2">
						<input type="button" value="Search" name="searchemp" id="searchemp" class="btn waves-effect waves-green" />
						<?php $flg = clean($_GET['flg']); ?>
						<input type="hidden" name="flag" id="flg" value="<?php echo $flg ?>" ?>
					</div>
				</div>



				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					if ($sdate != "" && $edate != "") {
						$user_logid = clean($_SESSION['__user_logid']);
						$flg = cleanUserInput($_GET['flg']);
						$sqlConnect = "call  get_inactive_emp_list('" . $user_logid . "','" . $flg . "','" . $sdate . "','" . $edate . "')";
						$myDB = new MysqliDb();
						$result = $myDB->query($sqlConnect);
						$error = $myDB->getLastError();
						if (empty($error)) { ?>
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>SN.</th>
										<th>EmployeeID</th>
										<th>EmployeeName</th>
										<th>JoiningDate</th>
										<th>RelevingDate</th>
										<th>Designation</th>
										<th>EmpStage</th>
										<th>ClientName</th>
										<th>Process</th>
										<th>SubProcess</th>
										<th>AccountHead</th>
										<th>ReportTo</th>
										<th>Reason of Leaving</th>
										<th>Action </th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 0;
									///  print_r($result);
									foreach ($result as $key => $value) {
										$doj_date = "";
										$dol_date = "";
										if ($value['dol'] != '0000-00-00 00:00:00') {
											$dol_date = date("Y-m-d", strtotime($value['dol']));
										}
										if ($value['DOJ'] != '0000-00-00 00:00:00') {
											$doj_date = date("Y-m-d", strtotime($value['DOJ']));
										}
										$count++;
										echo '<tr>';
										echo '<td id="countc' . $count . '">' . $count . '</td>';
										echo '<td class="Process" id="empid' . $count . '">' . $value['EmployeeID'] . '</td>';
										echo '<td class="SubProcess"  id="empname' . $count . '" >' . $value['EmployeeName'] . '</td>';
										echo '<td class="doj" id="doj' . $count . '"  >' . $doj_date . '</td>';
										echo '<td class="dol_date" id="dol_date' . $count . '"  >' . $dol_date . '</td>';
										echo '<td class="designation" id="designation' . $count . '"  >' . $value['designation'] . '</td>';
										echo '<td class="designation" id="designation' . $count . '"  >' . $value['EmpStage'] . '</td>';
										echo '<td class="clientname" id="clientname' . $count . '">' . $value['clientname'] . '</td>';
										echo '<td class="Process" id="Process' . $count . '">' . $value['Process'] . '</td>';
										echo '<td class="sub_process" id="sub_process' . $count . '">' . $value['sub_process'] . '</td>';
										echo '<td class="AccountHead" id="AccountHead' . $count . '">' . $value['AccountHead'] . '</td>';
										echo '<td class="ReportsTo" id="ReportsTo' . $count . '">' . $value['ReportsTo'] . '</td>';
										echo '<td class="rsnofleaving" id="rsnofleaving' . $count . '">' . $value['rsnofleaving'] . '</td>';
									?>
										<input type='hidden' name='remark' id="remark<?php echo $count; ?>" value="<?php echo  $value['remark']; ?>">
										<input type='hidden' name='leaving_resion' id="leaving_resion<?php echo $count; ?>" value="<?php echo  $value['rsnofleaving']; ?>">
										<input type='hidden' name='disposition3' id="disposition<?php echo $count; ?>" value="<?php echo  $value['disposition']; ?>">
										<input type='hidden' name='contact_status3' id="contact_status<?php echo $count; ?>" value="<?php echo  $value['contact_status']; ?>">
										<input type='hidden' name='mobile' id="mobilenum<?php echo $count; ?>" value="<?php echo  $value['mobile']; ?>">
										<input type='hidden' name='altmobile' id="altmobilenum<?php echo $count; ?>" value="<?php echo  $value['altmobile']; ?>">
										<input type='hidden' name='leaving_date' id="leaving_date<?php echo $count; ?>" value="<?php echo $dol_date ?>">
										<td class="manage_item tbl__ID"><a onclick="return getEditData('<?php echo $count; ?>');"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" id="'.$value['cm_id'].'" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>


									<?php }
									?>
								</tbody>
							</table>
					<?php
						} else {
							echo "<script>$(function(){ toastr.error('Data Not Found') }); </script>";
						}
					}
					?>
				</div>
				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	$(document).ready(function() {
		$('#searchemp').on('click', function() {
			validate = 0;
			alert_msg = "";
			var startdate = $('#startdate').val();
			var enddate = $('#enddate').val();
			var flg = $('#flg').val();

			if (startdate != "" && enddate != "") {
				location.href = 'inactive-emp-list.php?flg=' + flg + '&sdate=' + startdate + '&enddate=' + enddate;
			} else {

				validate = 1;
				alert_msg = '<li> Please select From date and To date</li>';

			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");

				return false;
			}
		});
		$('#family_resion_id').hide();
		$('#familyr_jd').hide();
		$('#whome_apply').hide();
		$('#better_carieer_id').hide();
		$('#further_study').hide();
		$('#willing_join_id').hide();
		$('#fs_jdid').hide();

		$('#get_married').hide();
		$('#long_distance').hide();
		$('#decertification').hide();


		$("#txt_Disposition").on('change', function() {
			$('#text_company').val('');
			$('#text_location').val('');
			$('#text_level').val('');
			$('#bc_date').val('');
			$('#willing_join_id').hide();
			$('#fs_jdid').hide();
			$('#better_carieer_id').hide();
			$('#family_resion_id').hide();
			$('#whome_apply').hide();
			$('#further_study').hide();
			$('#fs_jdid').hide();
			$('#get_married').hide();
			$('#pref_location_id').hide();
			$('#joinbackdate_id').hide();
			$('#pref_date_id').hide();
			$('#long_distance').hide();
			$('#decertification').hide();
			$('#decerti_id').hide();
			$('#willing_back_join').hide();
			$("#distance_join_no").prop("checked", false);
			$("#distance_join_yes").prop("checked", false);
			$("#reason_yes").prop("checked", false);
			$("#resolve_yes").prop("checked", false);
			$("#oprocess_no").prop("checked", false);
			$("#willing_join_yes").prop("checked", false);
			var dispo = $("#txt_Disposition").val();
			if (dispo == 'Better career prospect') {
				$('#better_carieer_id').show();
				$('#family_resion_id').hide();
			} else
			if (dispo == 'Family Reasons') {
				$('#family_resion_id').show();
				$('#whome_apply').hide();
				$('#whome_apply_id').hide();
				$('#resean_will_join').hide();

				$('#reason_yes').click(function() {
					if ($("#reason_yes").is(":checked")) {
						$('#whome_apply').show();
						$('#whome_apply_id').show();
						$('#resean_will_join').show();

						$('#resolve_yes').click(function() {
							if ($("#resolve_yes").is(":checked")) {
								$('#familyr_jd').show();
							}
						});
						$('#resolve_no').click(function() {
							if ($("#resolve_no").is(":checked")) {
								$('#familyr_jd').hide();

							}
						});
					}


				});
				$('#reason_no').click(function() {
					if ($("#reason_no").is(":checked")) {
						$('#whome_apply').hide();
						$('#whome_apply_id').hide();
						$('#familyr_jd').hide();
						$("#resolve_yes").prop('checked', false);


					}
				});

				$('#whome_apply').show();
				$('#better_carieer_id').hide();

			} else
			if (dispo == 'Further Studies') {
				$('#family_resion_id').hide();
				$('#whome_apply').hide();
				$('#better_carieer_id').hide();
				$('#further_study').show();

				$('#distance').click(function() {
					$('#join_yes').prop('checked', false);
					if ($("#distance").is(":checked")) {
						$('#willing_join_id').show();
						$('#join_yes').click(function() {
							if ($("#join_yes").is(":checked")) {
								$('#fs_jdid').show();
							}
						});
						$('#join_no').click(function() {
							if ($("#join_no").is(":checked")) {
								$('#fs_jdid').hide();
							}
						});

					}
				});
				$('#regular').click(function() {
					if ($("#regular").is(":checked")) {
						$('#willing_join_id').hide();
						$('#fs_jdid').hide();
					}
				});
			} else
			if (dispo == 'Getting married') {
				$('#get_married').show();
				$('#willing_yes').click(function() {
					if ($("#willing_yes").is(":checked")) {
						$('#pref_location_id').show();
						$('#pref_date_id').show();

					}
				});
				$('#willing_no').click(function() {
					if ($("#willing_no").is(":checked")) {
						$('#pref_location_id').hide();
						$('#pref_date_id').hide();

					}
				});
			} else
			if (dispo == 'Long Distance') {
				$('#long_distance').show();
				$('#distance_join_yes').click(function() {
					if ($("#distance_join_yes").is(":checked")) {

						$('#joinbackdate_id').show();
					}
				});
				$('#distance_join_no').click(function() {
					if ($("#distance_join_no").is(":checked")) {
						$('#joinbackdate_id').hide();
					}
				});


			} else
			if (dispo == 'Decertification') {
				$('#decertification').show();

				$('#oprocess_no').click(function() {
					if ($("#oprocess_no").is(":checked")) {

						$('#willing_back_join').show();
						$('#willing_join_yes').click(function() {
							if ($("#willing_join_yes").is(":checked")) {
								$('#decerti_id').show();
							}
						});
						$('#willing_join_no').click(function() {
							if ($("#willing_join_no").is(":checked")) {
								$('#decerti_id').hide();
							}
						});
					}

				});
				$('#oprocess_yes').click(function() {
					if ($("#oprocess_yes").is(":checked")) {
						$('#decerti_id').hide();
						$('#willing_back_join').hide();
						$("#willing_join_yes").prop("checked", false);
					}
				});
			}

		})



		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}

		$('#div_error').removeClass('hidden');


		$('#btnCancel').click(function() {
			$('.statuscheck').addClass('hidden');
			$('#search').show();
		});


		$('#btnSave1').click(function() {
			validate = 0;
			alert_msg = "";


			if ($('#txt_ContactStatus').val() == "NA") {
				validate = 1;
				$('#txt_ContactStatus').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				if ($('#stxt_ContactStatus').size() == 0) {
					$('<span id="stxt_ContactStatus" class="help-block">Please select Contact Status.</span>').insertAfter('#txt_ContactStatus');
				}
			}

			var txt_Disposition = $("#txt_Disposition  option:selected").val();
			//alert(txt_Disposition);
			var contact_status = $('#txt_ContactStatus').val();
			if (contact_status == 'Not Contacted') {
				var optiontest = $("#txt_Disposition  option:selected").val();
				// alert(optiontest);
				if (optiontest == "NA") {
					validate = 1;
					$('#txt_Disposition  option:selected').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
					if ($('#stxt_Disposition  option:selected').size() == 0) {
						$('<span id="stxt_Disposition  option:selected" class="help-block">Please select Disposition.</span>').insertAfter('#txt_Disposition  option:selected');
					}

				}
			}
			if (txt_Disposition == 'Better career prospect') {
				var text_company = $('#text_company').val().trim();

				if (text_company == "") {
					validate = 1;
					$('#text_company').addClass('has-error');
					if ($('#stext_company').size() == 0) {
						$('<span id="stext_company" class="help-block">Company name should not be empty.</span>').insertAfter('#text_company');
					}
				}

				var text_location = $('#text_location').val().trim();

				if (text_location == "") {
					validate = 1;
					$('#text_location').addClass('has-error');
					if ($('#stext_location').size() == 0) {
						$('<span id="stext_location" class="help-block">Company Location should not be empty.</span>').insertAfter('#text_location');
					}
				}

				var text_level = $('#text_level').val().trim();

				if (text_level == "") {
					validate = 1;
					$('#text_level').addClass('has-error');
					if ($('#sstext_level').size() == 0) {
						$('<span id="sstext_level" class="help-block">Company level should not be empty.</span>').insertAfter('#text_level');
					}
				}

				var bc_date = $('#bc_date').val().trim();

				if (bc_date == "") {
					validate = 1;
					$('#bc_date').addClass('has-error');
					if ($('#sbc_date').size() == 0) {
						$('<span id="sbc_date" class="help-block">When date should not be empty.</span>').insertAfter('#bc_date');
					}
				}

			} else if (txt_Disposition == 'Family Reasons') {
				if ($("#reason_yes").is(":checked")) {

					if ($("#resolve_yes").is(":checked")) {
						if ($('#family_date').val() == "") {
							validate = 1;
							$('#family_date').addClass('has-error');
							if ($('#sfamily_date').size() == 0) {
								$('<span id="sfamily_date" class="help-block">By when date should not be empty.</span>').insertAfter('#family_date');
							}
						}
					} else if (!$("#resolve_no").is(":checked")) {
						validate = 1;
						alert_msg += '<li>Will you re join are not selceted </li>';
						$(function() {
							toastr.error(alert_msg)
						});
					}
				} else if (!$("#reason_no").is(":checked")) {
					validate = 1;
					alert_msg += '<li>Did you apply for leaves are not selceted </li>';
					$(function() {
						toastr.error(alert_msg)
					});
				}


			} else if (txt_Disposition == 'Further Studies') {
				if ($("#distance").is(":checked")) {
					if ($("#join_yes").is(":checked")) {
						if ($('#join_date').val() == "") {
							$('#join_date').addClass('has-error');
							if ($('#sjoin_date').size() == 0) {
								$('<span id="sjoin_date" class="help-block">Join date should not be empty.</span>').insertAfter('#join_date');
							}
							validate = 1;
						}
					} else {
						validate = 1;
						alert_msg += '<li>Willing to join back is not selected</li>';
						$(function() {
							toastr.error(alert_msg)
						});
					}
				} else if (!$("#regular").is(":checked")) {
					validate = 1;
					alert_msg += '<li>Learing process is not selected</li>';
					$(function() {
						toastr.error(alert_msg)
					});
				}
			} else if (txt_Disposition == 'Getting married') {

				if ($("#willing_yes").is(":checked")) {
					if ($('#mpref_location').val() == "") {
						validate = 1;
						$('#mpref_location').addClass('has-error');
						if ($('#smpref_location').size() == 0) {
							$('<span id="smpref_location" class="help-block">Preferred location should not be empty.</span>').insertAfter('#mpref_location');
						}
					}
					if ($('#when_date').val() == "") {
						validate = 1;
						$('#when_date').addClass('has-error');
						if ($('#swhen_date').size() == 0) {
							$('<span id="swhen_date" class="help-block">By When date should not be empty.</span>').insertAfter('#when_date');
						}
					}

				} else if (!$("#willing_no").is(":checked")) {
					validate = 1;
					alert_msg += '<li>Willing to work is not selected</li>';
					$(function() {
						toastr.error(alert_msg)
					});

				}
			} else if (txt_Disposition == 'Long Distance') {

				if ($('#pref_location').val() == "") {
					validate = 1;
					$('#pref_location').addClass('has-error');
					if ($('#spref_location').size() == 0) {
						$('<span id="spref_location" class="help-block">Preferred location should not be empty.</span>').insertAfter('#pref_location');
					}
				}
				if ($('#pref_shift').val() == "") {
					validate = 1;
					$('#pref_shift').addClass('has-error');
					if ($('#spref_shift').size() == 0) {
						$('<span id="spref_shift" class="help-block">Preferred shift should not be empty.</span>').insertAfter('#pref_shift');
					}
				}
				if ($("#distance_join_yes").is(":checked")) {
					if ($('#joinback_date').val() == "") {
						validate = 1;
						$('#joinback_date').addClass('has-error');
						if ($('#sjoinback_date').size() == 0) {
							$('<span id="sjoinback_date" class="help-block">Willing to join back date should not be empty.</span>').insertAfter('#joinback_date');
						}
					}
				} else if (!$("#distance_join_no").is(":checked")) {
					validate = 1;
					$('#distance_join_no').addClass('has-error');
					if ($('#sdistance_join_no').size() == 0) {
						$('<span id="sdistance_join_no" class="help-block">Willing to join back not selected.</span>').insertAfter('#distance_join_no');
					}
				}
			} else if (txt_Disposition == 'Decertification') {

				if ($("#oprocess_no").is(":checked")) {
					if ($("#willing_join_yes").is(":checked")) {
						//alert('willing join yes');
						if ($('#dcerti_join').val() == "") {
							validate = 1;
							alert_msg += '<li>Join back date should not be empty</li>';
							$(function() {
								toastr.error(alert_msg)
							});
						}
					} else if (!$("#willing_join_no").is(":checked")) {
						//alert('willing join no');
						validate = 1;
						alert_msg += '<li>Willing to join back should not be empty</li>';
						$(function() {
							toastr.error(alert_msg)
						});
					}
				} else if (!$("#oprocess_yes").is(":checked")) {
					validate = 1;
					alert_msg += '<li>Did other process offered to You is not selected </li>';
					$(function() {
						toastr.error(alert_msg)
					});

				}
			}
			var remark2 = $('#textremark').val().trim();
			if (remark2 == "") {
				validate = 1;
				$('#textremark').addClass('has-error');
				if ($('#stextremark').size() == 0) {
					$('<span id="stextremark" class="help-block">Remark should not be empty.</span>').insertAfter('#textremark');
				}
			}
			if (validate == 1) {

				return false;
			}
			return confirm('Are you want to proceed?');
		});



		$('#txt_ContactStatus').on('change', function() {
			var contact_status = $('#txt_ContactStatus').val();
			if (contact_status == 'Not Contactable' || contact_status == 'NA') {
				$('#txt_Disposition').html("<option value='NA'>---Select---</option>");
				$('#txt_Disposition').prop('disabled', true);
				$('select').formSelect();
			} else if (contact_status == 'Not Contacted') {
				$('#txt_Disposition').prop('disabled', false);
				$('#txt_Disposition').empty();
				var Not_Contacted = "<option value='NA'>---Select---</option><option value='Switched Off'>Switched Off</option><option value='Ringing No Answer'>Ringing No Answer</option><option value='Number Busy'>Number Busy</option><option value='Call Disconnected'>Call Disconnected</option> '<option value='Not a right party'>Not a right party</option><option value=Call Back' >Call Back</option><option value='No. Not in use'>No. Not in use</option><option value='Call Silent' >Call Silent</option> <option value='Out Of Coverage Area' >Out Of Coverage Area</option>";
				$('#txt_Disposition').html(Not_Contacted);
				$('select').formSelect();
			} else if (contact_status == 'Contacted') {
				$('#txt_Disposition').prop('disabled', false);
				$('#txt_Disposition').empty();
				var contactable_text = "<option value='NA'>---Select---</option><option value='Better career prospect'>Better career prospect</option><option value='Health Reasons'>Health Reasons</option><option value='Relocation'>Relocation </option> '<option value='Further Studies'>Further Studies</option><option value='Salary Issues' >Salary Issues</option><option value='Family Reasons'>Family Reasons</option><option value='Getting married' >Getting married</option> <option value='Pregnancy / Maternity Leave' >Pregnancy / Maternity Leave</option><option value='Relation with Supervisor / Behavior Issues'>Relation with Supervisor / Behavior Issues</option> <option value='Long Distance'>Long Distance</option><option value='Shift Concern'>Shift Concern</option><option value='Does not want to work in call centre'>Does not want to work in call centre</option><option value='Decertification'>Decertification</option><option value='Examination'>Examination</option><option value='Other'>Other</option>";
				$('#txt_Disposition').html(contactable_text);
				$('select').formSelect();
			}
			$('select').formSelect();

		})
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');
		$('select').formSelect();

	}

	function getEditData(id) {

		$('#search').hide();
		var Process = $('#Process' + id).html();
		var SubProcess = $('#sub_process' + id).html();
		var empid = $('#empid' + id).html();
		var empname = $('#empname' + id).html();
		var leaving_resion = $('#leaving_resion' + id).val();
		var leaving_date = $('#leaving_date' + id).val();
		var mobileno = $('#mobilenum' + id).val();
		var altmobileno = $('#altmobilenum' + id).val();
		var remark = "";
		disposition = "";
		contact_status = "";
		remark = $('#remark' + id).val().trim();
		if (remark != "") {
			var contact_status = $('#contact_status' + id).val();
			var disposition = $('#disposition' + id).val();
		}

		$('#ProcessEdit').val(Process);
		$('#SubProcessEdit').val(SubProcess);
		$('#empidEdit').val(empid);
		$('#empnameEdit').val(empname);
		$('#leaving_resionEdit').val(leaving_resion);
		$('#leaving_dateEdit').val(leaving_date);
		$('#mobile').val(mobileno);
		$('#altmobile').val(altmobileno);
		$('.statuscheck').removeClass('hidden');
		$('select').formSelect();
		//if(remark!="")	{


		if (remark != "") {
			var contact_status = $('#contact_status' + id).val();
			if (contact_status == 'Not Contactable') {
				$('#txt_Disposition').prop('disabled', true);
				$('select').formSelect();
			} else if (contact_status == 'Not Contacted') {
				$('#txt_Disposition').prop('disabled', false);
				var Not_Contacted = "<option value='NA'>---Select---</option><option value='Switched Off'>Switched Off</option><option value='Ringing No Answer'>Ringing No Answer</option><option value='Number Busy'>Number Busy</option><option value='Call Disconnected'>Call Disconnected</option> '<option value='Not a right party'>Not a right party</option><option value=Call Back' >Call Back</option><option value='No. Not in use'>No. Not in use</option><option value='Call Silent' >Call Silent</option> <option value='Out Of Coverage Area' >Out Of Coverage Area</option>";
				$('#txt_Disposition').html(Not_Contacted);
				$('select').formSelect();
			} else if (contact_status == 'Contacted') {
				$('#txt_Disposition').prop('disabled', false);
				$('#txt_Disposition').empty();

				var contactable_text = "<option value='NA'>---Select---</option><option value='Better career prospect'>Better career prospect</option><option value='Health Reasons'>Health Reasons</option><option value='Relocation'>Relocation </option> '<option value='Further Studies'>Further Studies</option><option value='Salary Issues' >Salary Issues</option><option value='Family Reasons'>Family Reasons</option><option value='Getting married' >Getting married</option> <option value='Pregnancy / Maternity Leave' >Pregnancy / Maternity Leave</option><option value='Relation with Supervisor / Behavior Issues'>Relation with Supervisor / Behavior Issues</option><option value='Long Distance'>Long Distance</option><option value='Shift Concern'>Shift Concern</option><option value='Does not want to work in call centre'>Does not want to work in call centre</option><option value='Decertification'>Decertification</option><option value='Examination'>Examination</option>	<option value='Other'>Other</option>";
				$('#txt_Disposition').html(contactable_text);
				$('select').formSelect();
			}
			$('#textremark').val(remark);
			$('#txt_Disposition').val(disposition);
			$('#txt_ContactStatus').val(contact_status);
			$('select').formSelect();
		} else {
			$('#textremark').val('');
			$('#txt_Disposition').val('NA');
			$('#txt_ContactStatus').val('NA');
			$('#txt_Disposition').prop('disabled', true);
			$('select').formSelect();
		}

		$('#hiddenid').val(id);
		$('#editdataid').show();
	}
	$('select').formSelect();
</script>