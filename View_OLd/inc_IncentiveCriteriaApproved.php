 <?php
	require_once(__dir__ . '/../Config/init.php');
	// require_once(CLS . 'MysqliDb.php');
	date_default_timezone_set('Asia/Kolkata');
	require(ROOT_PATH . 'AppCode/nHead.php');
	ini_set('display_errors', '1');

	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$user_logid = clean($_SESSION['__user_logid']);
	if (isset($_SESSION)) {
		if (!isset($user_logid)) {
			$location = URL . 'Login';

			echo "<script>location.href='" . $location . "'</script>";
		}
		if ($user_logid != 'CE10091236' && 	$user_logid != 'CE03070003') {
			$location = URL . 'Login';

			echo "<script>location.href='" . $location . "'</script>";
		}
	} else {
		$location = URL . 'Login';
		//header("Location: $location");
		echo "<script>location.href='" . $location . "'</script>";
	}

	$Id = "";
	if (isset($_GET['action'], $_GET['id'])) {
		$Id = base64_decode($_GET['id']);
		if ($Id != "") {
			if ($_GET['action'] == 'act') {
				$status = 0;
			} else
		if ($_GET['action'] == 'dact') {
				$status = 1;
			}
			// $myDB = new MysqliDb();
			// $myDB->rawQuery("Update inc_incentive_criteria set incentiveStatus='" . $status . "' where id=$Id");

			$upQry = "Update inc_incentive_criteria set incentiveStatus=? where id=?";
			$stmt = $conn->prepare($upQry);
			$stmt->bind_param("ii", $status, $Id);
			if (!$stmt) {
				echo "failed to run";
				die;
			}
			$updt = $stmt->execute();
		}
	}
	$delid = isset($_GET['delid']);
	if ($delid) {
		$delid = base64_decode($_GET['delid']);
		if ($delid != "") {
			// $myDB = new MysqliDb();
			// $myDB->rawQuery("delete from inc_incentive_criteria where id=$delid");
			$DelQuery = "delete from inc_incentive_criteria where id=?";
			$stmt = $conn->prepare($DelQuery);
			$stmt->bind_param("i", $delid);
			$delt = $stmt->execute();

			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
		}
	}

	$sachinSirId = 'CE03070003';
	$Incentive_Type = $StartDate = $Rate = $criteria1 = $criteria2 = $cm_id = $process = $Request_Status = $BaseCriteria = $EndDate = '';
	$classvarr = "'.byID'";
	$searchBy = '';
	$Id = "";
	$user_log_ig = isset($_SESSION['__user_logid']);
	if ($user_log_ig) {
		$btnsave = isset($_POST['btnSave']);
		if ($btnsave) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$createdBy = clean($_SESSION['__user_logid']);
				$Incentive_Type = cleanUserInput($_POST['Incentive_Type']);
				$StartDate = trim(cleanUserInput($_POST['StartDate']));
				$EndDate = trim(cleanUserInput($_POST['EndDate']));
				$Rate = trim(cleanUserInput($_POST['Rate']));
				$criteria1 = cleanUserInput($_POST['criteria1']);
				$criteria2 = cleanUserInput($_POST['criteria2']);
				$Rate2 = trim(cleanUserInput($_POST['Rate2']));
				$criteria12 = cleanUserInput($_POST['criteria12']);
				$criteria22 = cleanUserInput($_POST['criteria22']);
				$Rate3 = trim(cleanUserInput($_POST['Rate3']));
				$criteria13 = cleanUserInput($_POST['criteria13']);
				$criteria23 = cleanUserInput($_POST['criteria23']);
				$ApplicableFor = cleanUserInput($_POST['ApplicableFor']);
				$cm_id = cleanUserInput($_POST['cm_id']);
				$process = cleanUserInput($_POST['userProcess2']);
				$Request_Status = 'Approved';
				$BaseCriteria = cleanUserInput($_POST['BaseCriteria2']);
				$resulti = 0;
				$month = date('m', strtotime($StartDate));
				if ($createdBy != "" && $Incentive_Type != "" && $StartDate != "" && $EndDate != "" && $Rate != "" && $BaseCriteria != "" && $criteria1 != "" && $criteria2 != "" && $cm_id != "" && $process != "" && $Request_Status != "" && $ApplicableFor != "") {
					//$empID=$val;
					// $myDB = new MysqliDb();
					// $select_query = $myDB->rawQuery("select id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type='" . $Incentive_Type . "' and CreatedBy='" . $createdBy . "' and incentiveStatus=1 and Request_Status!='Decline' and cm_id='" . $cm_id . "' and ApplicableFor='" . $ApplicableFor . "' and ((month(EndDate)>= month('" . $StartDate . "')) and (year(EndDate)=year('" . $StartDate . "'))  || (month(EndDate)< month('" . $StartDate . "') and year(EndDate)>year('" . $StartDate . "')))order by id desc ");

					$Query = "select id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type=? and CreatedBy=? and incentiveStatus=1 and Request_Status!='Decline' and cm_id=? and ApplicableFor=? and ((month(EndDate)>= month(?)) and (year(EndDate)=year(?))  || (month(EndDate)< month(?) and year(EndDate)>year(?)))order by id desc ";
					$stmt = $conn->prepare($Query);
					$stmt->bind_param("ssssssss", $Incentive_Type, $createdBy, $cm_id, $ApplicableFor, $StartDate, $StartDate, $StartDate, $StartDate);
					if (!$stmt) {
						echo "failed to run";
						die;
					}
					$stmt->execute();
					$Getresult = $stmt->get_result();
					$count = $Getresult->num_rows;


					$my_error = $myDB->getLastError();
					//$rowCount = $myDB->count;
					//if ($rowCount < 1) {
					if ($count < 1) {
						$insert = "call inc_AddRequest('" . $createdBy . "','" . $Incentive_Type . "','" . $StartDate . "','" . $EndDate . "','" . $Rate . "','" . $BaseCriteria . "','" . $criteria1 . "','" . $criteria2 . "','" . $cm_id . "','" . $process . "','" . $Request_Status . "','" . $Rate2 . "','" . $criteria12 . "','" . $criteria22 . "','" . $Rate3 . "','" . $criteria13 . "','" . $criteria23 . "','" . $ApplicableFor . "')";
						$resulti = $myDB->rawQuery($insert);
						$resulti = 1;
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
						if ($resulti && $rowCount > 0) {
							echo "<script>$(function(){ toastr.success('Incentive Added Successfully.') }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Incentive Not Added <code>" . $mysql_error . "</code>') }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Incentive Scheme is already going on for this month .') }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Please enter data correctly.') }); </script>";
				}
			}
		}
		// $btnedit = $_POST['btnEdit'];
		if (isset($_POST['btnEdit'])) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				//print_r($_POST);

				$createdBy = clean($_SESSION['__user_logid']);
				$Incentive_Type = cleanUserInput($_POST['Incentive_Type']);
				$StartDate = cleanUserInput($_POST['StartDate']);
				$EndDate = cleanUserInput($_POST['EndDate']);
				$Rate = cleanUserInput($_POST['Rate']);
				$criteria1 = cleanUserInput($_POST['criteria1']);
				$criteria2 = cleanUserInput($_POST['criteria2']);
				$Rate2 = cleanUserInput($_POST['Rate2']);
				$criteria12 = cleanUserInput($_POST['criteria12']);
				$criteria22 = cleanUserInput($_POST['criteria22']);
				$Rate3 = cleanUserInput($_POST['Rate3']);
				$criteria13 = cleanUserInput($_POST['criteria13']);
				$criteria23 = cleanUserInput($_POST['criteria23']);
				$ApplicableFor = cleanUserInput($_POST['ApplicableFor']);
				$cm_id = cleanUserInput($_POST['cm_id']);
				$Request_Status = 'Approved';
				$process = cleanUserInput($_POST['userProcess2']);
				$BaseCriteria = cleanUserInput($_POST['BaseCriteria2']);
				$Id = cleanUserInput($_POST['editId']);
				//$incentiveStatus="1";
				$incentiveStatus = cleanUserInput($_POST['incentiveStatus']);

				if ($Id != ""  && $createdBy != "" && $Incentive_Type != "" && $StartDate != "" && $EndDate != "" && $Rate != "" && $BaseCriteria != "" && $criteria1 != "" && $criteria2 != "" && $cm_id != "" && $process != "" && $Request_Status != "") {

					// $myDB = new MysqliDb();
					// $select_query = $myDB->rawQuery("select id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type='" . $Incentive_Type . "' and CreatedBy='" . $createdBy . "' and incentiveStatus=1 and Request_Status!='Decline' and cm_id='" . $cm_id . "' and ApplicableFor='" . $ApplicableFor . "' and ((month(EndDate)>= month('" . $StartDate . "')) and (year(EndDate)=year('" . $StartDate . "'))  || (month(EndDate)< month('" . $StartDate . "') and year(EndDate)>year('" . $StartDate . "'))) and id!=$Id ");

					$Query = "SELECT id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type=? and CreatedBy=? and incentiveStatus=1 and Request_Status!='Decline' and cm_id=? and ApplicableFor=? 
	and ((month(EndDate)>= month(?)) and (year(EndDate)=year(?))  || (month(EndDate)< month(?) 
			and year(EndDate)>year(?))) and id!=? ";
					$stmt = $conn->prepare($Query);
					$stmt->bind_param("ssssssssi", $Incentive_Type, $createdBy, $cm_id, $ApplicableFor, $StartDate, $StartDate, $StartDate, $StartDate, $Id);
					if (!$stmt) {
						echo "failed to run";
						die;
					}
					$stmt->execute();
					$Getresult = $stmt->get_result();
					$count = $Getresult->num_rows;


					$mysql_error = $myDB->getLastError();

					//$rowCount = $myDB->count;
					//if ($rowCount < 1) {
					if ($count < 1) {
						$insert = "call inc_UpdateRequest('" . $Id . "','" . $incentiveStatus . "','" . $createdBy . "','" . $Incentive_Type . "','" . $StartDate . "','" . $EndDate . "','" . $Rate . "','" . $BaseCriteria . "','" . $criteria1 . "','" . $criteria2 . "','" . $cm_id . "','" . $process . "','" . $Request_Status . "','','" . $sachinSirId . "','" . $Rate2 . "','" . $criteria12 . "','" . $criteria22 . "','" . $Rate3 . "','" . $criteria13 . "','" . $criteria23 . "','" . $ApplicableFor . "')";
						$resulti = $myDB->rawQuery($insert);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
						if ($rowCount > 0) {
							//$msg='<p class="text-success">Incentive Updated Successfully...</p>';
							echo "<script>$(function(){ toastr.success('Incentive Updated Successfully.') }); </script>";
						} else {
							//$msg='<p class="text-danger">Incentive Not Updated <code>'.$mysql_error.'</code></p>';
							echo "<script>$(function(){ toastr.error('Incentive Not Updated <code>." . $mysql_error . "</code>') }); </script>";
						}
					} else {
						//$msg='<p class="text-success">Incentive Scheme is already going on for this month ...</p>';
						echo "<script>$(function(){ toastr.success('Incentive Scheme is already going on for this month .') }); </script>";
					}
				} else {
					//$msg='<p  class="text-danger">Please enter data correctly...</p>';
					echo "<script>$(function(){ toastr.error('Please enter data correctly..') }); </script>";
				}
			}
		}
	}

	?>
 <script>
 	$(document).ready(function() {
 		$('#StartDate, #EndDate').datetimepicker({
 			timepicker: false,
 			format: 'Y-m-d',
 			minDate: new Date(),
 			scrollInput: false,
 		});
 		//$('.statuscheck').addClass('hidden');
 		$('#myTable').DataTable({
 			dom: 'Bfrtip',
 			"iDisplayLength": 25,
 			scrollX: '100%',
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
 		$('.byDate').addClass('hidden');
 		$('.byDept').addClass('hidden');
 		var classvarr = <?php echo $classvarr; ?>;
 		$(classvarr).removeClass('hidden');
 		$('#searchBy').change(function() {
 			$('.byID').addClass('hidden');
 			$('.byDate').addClass('hidden');
 			$('.byDept').addClass('hidden');
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
 	<span id="PageTittle_span" class="hidden">Manage Incentive </span>

 	<!-- Main Div for all Page -->
 	<div class="pim-container ">

 		<!-- Sub Main Div for all Page -->
 		<div class="form-div">

 			<!-- Header for Form If any -->
 			<h4>Manage Incentive </h4>

 			<!-- Form container if any -->
 			<div class="schema-form-section row">
 				<?php
					$_SESSION["token"] = csrfToken();
					?>
 				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

 				<div class="statuscheck">
 					<div class="input-field col s12 m12 ">
 						<div class="input-field col s4 m4 ">
 							<select id="Incentive_Type" name="Incentive_Type">
 								<option value="">---Select---</option>
 								<option value="Split">Split</option>
 								<option value="Attendance">Attendance</option>
 								<option value="Night/Late Evening">Night/Late Evening</option>
 								<option value="Morning">Morning</option>
 								<option value="Woman">Woman</option>
 							</select>
 							<label for="Incentive_Type" class="active-drop-down active">Incentive Type</label>
 						</div>

 						<div class="input-field col s4 m4">
 							<input type="text" id="StartDate" name="StartDate" />
 							<label for="StartDate">Start Date</label>
 						</div>

 						<div class="input-field col s4 m4 ">
 							<input type="text" id="EndDate" name="EndDate">
 							<label for="EndDate">End Date</label>
 						</div>
 					</div>
 					<div class="input-field col s12 m12 ">
 						<div class="input-field col s4 m4 ">
 							<select disabled='disabled' id="BaseCriteria" name="BaseCriteria">
 								<option value="">---Select---</option>
 								<option value="Login Window">Login Window</option>
 								<option value="Present Days">Present Days</option>
 							</select>
 							<label for="BaseCriteria" class="active-drop-down active">Base Criteria</label>
 						</div>
 						<input type='hidden' name='BaseCriteria2' id='BaseCriteria2'>

 						<?php $processQuery = "call inc_Process()";
							$myDB = new MysqliDb();
							$resultBy = $myDB->rawQuery($processQuery);
							?>
 						<div class="input-field col s4 m4 ">
 							<select class=" " id="cm_id" name="cm_id" style="min-width: 200px;max-width: 150;">
 								<option value=" ">---Select Process---</option>
 								<?php
									if ($resultBy) {

										$selected = '';
										foreach ($resultBy as $key => $value) {
											if ($cm_id == $value['cm_id']) {
												$selected = 'selected';
											} else {
												$selected = '';
											}
											echo '<option id="' . $value['process'] . '" value="' . $value['cm_id'] . '" ' . $selected . '>' . $value['ProcessInfo'] . '</option>';
										}
									}
									?>

 							</select>
 							<label for="cm_id" class="active-drop-down active">Process</label>


 						</div>
 						<input type='hidden' id='userProcess' name='userProcess2'>
 						<input type='hidden' id='editId' name='editId'>
 						<div id='datastatus' class="input-field col s4 m4 ">
 							<select id="ApplicableFor" name="ApplicableFor">
 								<option value="CSA">CSA</option>
 								<option value="Support">Support</option>
 							</select>
 							<label for="ApplicableFor" class="active-drop-down active">Applicable for</label>
 						</div>
 					</div>
 					<div class="input-field col s12 m12 ">
 						<div class="input-field col s4 m4 ">
 							<input type="text" id="Rate" name="Rate" onkeypress="return isNumber(event)" />
 							<label for="Rate" class="active">Incentive Amount</label>
 						</div>
 						<div class="input-field col s4 m4 ">
 							<select id="criteria1" name="criteria1">
 								<option value="">---Select---</option>
 							</select>
 							<label for="criteria1" class="active-drop-down active" id='c1'>Criteria 1 </label>
 						</div>
 						<div class="input-field col s4 m4 ">
 							<select id="criteria2" name="criteria2">
 								<option value="">---Select---</option>
 							</select>
 							<label for="criteria2" class="active-drop-down active" id='c2'>Criteria 2 </label>
 						</div>
 					</div>
 					<div id="newField" style="display:none;">
 						<div class="input-field col s12 m12 ">
 							<div class="input-field col s4 m4 ">
 								<input type="text" id="Rate2" name="Rate2" onkeypress="return isNumber(event)" />
 								<label for="Rate2">Incentive Amount2 </label>
 							</div>
 							<div class="input-field col s4 m4 ">
 								<select id="criteria12" name="criteria12">
 									<option value="">---Select---</option>
 								</select>
 								<label for="Rate2" class="active-drop-down active" id='c12'>Present </label>
 							</div>
 							<div class="input-field col s4 m4 ">
 								<select id="criteria22" name="criteria22">
 									<option value="">---Select---</option>
 								</select>
 								<label for="criteria22" class="active-drop-down active" id='c22'>Absent </label>
 							</div>
 						</div>
 						<div class="input-field col s12 m12 ">
 							<div class="input-field col s4 m4 ">
 								<input type="text" id="Rate3" name="Rate3" onkeypress="return isNumber(event)" />
 								<label for="Rate3">Incentive Amount3 </label>
 							</div>
 							<div class="input-field col s4 m4 ">
 								<select id="criteria13" name="criteria13">
 									<option value="">---Select---</option>
 								</select>
 								<label for="criteria13" class="active-drop-down active" id='c13'>Present </label>
 							</div>
 							<div class="input-field col s4 m4 ">
 								<select id="criteria23" name="criteria23">
 									<option value="">---Select---</option>
 								</select>
 								<label for="criteria23" class="active-drop-down active" id='c23'> Absent </label>
 							</div>
 						</div>
 					</div>
 					<div class="input-field col s4 m4 ">
 						<select id="incentiveStatus" name="incentiveStatus">
 							<option value="0" <?php if ($Id != "" and $incentiveStatus == 0) {
													echo "selected='selected'";
												} ?>>Inactive</option>
 							<option value="1" <?php if ($Id != "" and $incentiveStatus == 0) {
													echo "selected='selected'";
												} ?>>Active</option>
 						</select>
 						<label for="incentiveStatus" class="active-drop-down active">Status </label>
 					</div>
 					<div class="input-field col s12 m12 right-align ">
 						<button type="submit" value="Save" name="btnSave" id="btnSave1" class="btn waves-effect waves-green  ">Add</button>`
 						<button type="submit" value="Update" name="btnEdit" id="btnEdit" class="btn waves-effect waves-green " style="display:none;">Update</button>
 						<button type="submit" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn ">Cancel</button>
 					</div>
 				</div>
 				<div id="pnlTable">
 					<?php
						$sqlConnect = "call inc_GetIncentiveCriteria('')";
						$myDB = new MysqliDb();
						$result = $myDB->rawQuery($sqlConnect);
						//echo $sqlConnect;
						$error = $myDB->getLastError();
						if ($result) { ?>

 						<div class="had-container pull-left row card dataTableInline" id="tbl_div">
 							<div class="">
 								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
 									<thead>
 										<tr>
 											<th>SN.</th>
 											<th>IncentiveType</th>
 											<th>StartDate</th>
 											<th>EndDate</th>
 											<th>BaseCriteria</th>
 											<th>Rate</th>
 											<th>Criteria 1</th>
 											<th>Criteria 2</th>
 											<th>Applicable For</th>
 											<th>Process</th>
 											<th>Sub-Process</th>
 											<!-- <th>RequestStatus</th>-->
 											<th>RequestedOn</th>
 											<th>Status</th>
 											<th>Action </th>
 										</tr>
 									</thead>
 									<tbody>
 										<?php
											$count = 0;
											///  print_r($result);
											foreach ($result as $key => $value) {
												$count++;
												$level = "";
												if ($value['Incentive_Type'] == 'Attendance' || $value['Incentive_Type'] == 'Woman') {
													$level = ' Days';
												}
												echo '<tr>';
												echo '<td id="countc' . $count . '">' . $count . '</td>';
												echo '<td class="Incentive_Type" id="Incentive_Type' . $count . '">' . $value['Incentive_Type'] . '</td>';
												echo '<td class="StartDate"  id="StartDate' . $count . '" >' . $value['StartDate'] . '</td>';
												echo '<td class="EndDate" id="EndDate' . $count . '"  >' . $value['EndDate'] . '</td>';
												echo '<td class="BaseCriteria" id="BaseCriteria' . $count . '"  >' . $value['BaseCriteria'] . '</td>';
												echo '<td class="Rate" id="Rate_edit' . $count . '">' . $value['Rate'] . '</td>';
												echo '<td class="criteria1" id="criteria1_edit' . $count . '">' . $value['criteria1'] . '</td>';
												echo '<td class="criteria2" id="criteria2_edit' . $count . '">' . $value['criteria2'] . '</td>';
												echo '<td class="ApplicableFor" id="ApplicableFor' . $count . '">' . $value['ApplicableFor'] . '</td>';
												echo '<td class="Process" id="Process' . $count . '">' . $value['Process'] . '</td>';
												echo '<td class="Process" id="Process' . $count . '">' . $value['sub_process'] . '</td>';
												//echo '<td class="Request_Status" id="Request_Status'.$count.'">'.$value['Request_Status'].'</td>';	
												echo '<td class="CreatedOn" id="CreatedOn' . $count . '">' . substr($value['CreatedOn'], 0, 10) . '</td>';
												echo '<td class="incentiveStatus" id="incentiveStatus' . $count . '">';

												if ($value['incentiveStatus'] == '1') {
													if ($value['StartDate'] <= date('Y-m-d') && ($value['EndDate'] >= date('Y-m-d') || $value['EndDate'] < ('Y-m-d'))) {
														echo "Active";
													} else { ?>
 													<a onclick="return confirm('Are u want to Inactive?');" href='inc_IncentiveCriteriaApproved.php?action=act&id=<?php echo base64_encode($value['id']); ?>'>Active</a>
 												<?php
													}
												} else
								if ($value['incentiveStatus'] == '0') {


													if ($value['StartDate'] <= date('Y-m-d') && ($value['EndDate'] >= date('Y-m-d') || $value['EndDate'] < ('Y-m-d'))) {
														echo "Inactive";
													} else {
													?>
 													<a onclick="return confirm('Are u want to Active?');" href='inc_IncentiveCriteriaApproved.php?action=dact&id=<?php echo base64_encode($value['id']); ?>'>Inactive</a>
 											<?php
													}
												}
												echo '</td>';
												?>
 											<input type='hidden' id='cm_id<?php echo $count; ?>' value='<?php echo $value['cm_id']; ?>'>
 											<input type='hidden' id='id<?php echo $count; ?>' value='<?php echo $value['id']; ?>'>
 											<input type='hidden' id='incStatus<?php echo $count; ?>' value='<?php echo $value['incentiveStatus']; ?>'>
 											<input type='hidden' id='criteria12<?php echo $count; ?>' value='<?php echo $value['criteria12']; ?>'>
 											<input type='hidden' id='criteria22<?php echo $count; ?>' value='<?php echo $value['criteria22']; ?>'>
 											<input type='hidden' id='criteria13<?php echo $count; ?>' value='<?php echo $value['criteria13']; ?>'>
 											<input type='hidden' id='criteria23<?php echo $count; ?>' value='<?php echo $value['criteria23']; ?>'>
 											<input type='hidden' id='Rate2<?php echo $count; ?>' value='<?php echo $value['Rate2']; ?>'>
 											<input type='hidden' id='Rate3<?php echo $count; ?>' value='<?php echo $value['Rate3']; ?>'>
 											<td class="tbl__ID">


 												<?php if ($value['StartDate'] <= date('Y-m-d') && ($value['EndDate'] >= date('Y-m-d') || $value['EndDate'] < ('Y-m-d')) && $value['incentiveStatus'] == '1') { ?>
 													<a>
 														<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Can't">ohrm_edit</i>
 													</a>
 													<a>
 														<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Can't">ohrm_delete</i>
 													</a>
 												<?php } else { ?>
 													<a onclick="return getEditData('<?php echo $count; ?>');">
 														<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i>
 													</a>
 													<a onclick=" return  deleteData('<?php echo base64_encode($value['id']); ?>');">
 														<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i>
 													</a>
 												<?php } ?>
 											</td>
 											</tr>
 										<?php }
											?>
 									</tbody>
 								</table>
 							</div>
 						</div>
 					<?php
						} else {
							//echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found <code >" . $error . "</code>'); }); </script>";
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
 	function getEditData(editID) {
 		$(".schema-form-section input,.schema-form-section text").each(function(index, element) {
 			if ($(element).val().length > 0) {
 				$(this).siblings('label, i').addClass('active');
 			} else {
 				$(this).siblings('label, i').removeClass('active');
 			}
 		});
 		//$('.statuscheck').removeClass('hidden');	
 		$('#datastatus').show();
 		$('#btnEdit').show();
 		$('#btnSave1').hide();
 		$('#newField').hide();
 		var Incentive_Type = incType = $('#Incentive_Type' + editID).html();
 		if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
 			$("#BaseCriteria").val('Login Window');
 			$("#BaseCriteria2").val('Login Window');
 			$('select').formSelect();

 		} else {
 			$("#BaseCriteria").val('Present Days');
 			$("#BaseCriteria2").val('Present Days');
 			$('select').formSelect();
 		}
 		if (incType == 'Split') {
 			criteria1 = "<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>";
 			$("#criteria1").html(criteria1);
 			criteria2 = "<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";
 			$("#criteria2").html(criteria2);
 			$('select').formSelect();

 		} else
 		if (incType == 'Attendance') {
 			$('#newField').show();
 			criteria1 = " <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
 			$("#c1").html('Present ');
 			$("#criteria1").html(criteria1);
 			$("#criteria12").html(criteria1);
 			$("#criteria13").html(criteria1);
 			criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
 			//alert(criteria2);	
 			$("#c2").html('Absent ');
 			$("#criteria2").html(criteria2);
 			$("#criteria22").html(criteria2);
 			$("#criteria23").html(criteria2);

 			$('select').formSelect();
 		} else
 		if (incType == 'Night/Late Evening') {
 			criteria1 = "<option value=''>---Select---</option>";
 			var criteria1 = "";
 			for (i = 1; i <= 12; i++) {
 				criteria1 += "<option value='" + i + " PM'>" + i + " PM</option>";
 			}
 			criteria1 += "<option value='12 AM'>12 AM</option>";
 			$("#criteria1").html(criteria1);

 			criteria2 = "<option value=''>---Select---</option><option value='9 PM'>9 PM</option>  <option value='10 PM'>10 PM</option>  <option value='11 PM'>11 PM</option>  <option value='12 PM'>12 PM</option>";

 			for (j = 1; j <= 9; j++) {
 				criteria2 += "<option value='" + j + " AM'>" + j + " AM</option>";

 			}
 			$("#criteria2").html(criteria2);

 			$('select').formSelect();
 		} else
 		if (incType == 'Morning') {
 			criteria1 = "<option value=''>---Select---</option>";
 			var criteria1 = "";
 			for (i = 4; i <= 7; i++) {
 				criteria1 += "<option value='" + i + " AM'>" + i + " AM</option>";
 			}
 			$("#criteria1").html(criteria1);
 			var criteria2 = "";
 			criteria2 = "<option value=''>---Select---</option>	<option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";
 			$("#criteria2").html(criteria2);
 			$('select').formSelect();
 		} else
 		if (incType == 'Woman') {

 			$('#newField').show();
 			criteria1 = "<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
 			$("#c1").html('Present ');
 			$("#criteria1").html(criteria1);
 			$("#criteria12").html(criteria1);
 			$("#criteria13").html(criteria1);
 			criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
 			$("#c2").html('Absent ');
 			$("#criteria2").html(criteria2);
 			$("#criteria22").html(criteria2);
 			$("#criteria23").html(criteria2);
 			$('select').formSelect();
 		}

 		var StartDate = $('#StartDate' + editID).html();
 		var EndDate = $('#EndDate' + editID).html();
 		//	var BaseCriteria= $('#BaseCriteria'+editID).html();
 		var Rate = $('#Rate_edit' + editID).html();
 		var Rate2 = $('#Rate2' + editID).val();
 		var Rate3 = $('#Rate3' + editID).val();
 		var criteria1 = $('#criteria1_edit' + editID).html();
 		if (criteria1.indexOf(";") >= 0) {
 			ct = criteria1.split(';');
 			criteria1 = '>' + ct[1];
 		}

 		var criteria2 = $('#criteria2_edit' + editID).html();
 		var criteria12 = $('#criteria12' + editID).val();
 		var criteria22 = $('#criteria22' + editID).val();
 		var criteria13 = $('#criteria13' + editID).val();
 		var criteria23 = $('#criteria23' + editID).val();
 		var cm_id = $('#cm_id' + editID).val();
 		var process = $('#Process' + editID).html();
 		//alert(process);
 		var selectProcess = "<option  value='" + cm_id + "'>" + process + "</option>";
 		var Request_Status = $('#Request_Status' + editID).html();
 		var ApplicableFor = $('#ApplicableFor' + editID).html();
 		var incSstatus = $('#incStatus' + editID).val();
 		var editId = $('#id' + editID).val();
 		$('#Incentive_Type').val(Incentive_Type);
 		$('#StartDate').val(StartDate);
 		$('#EndDate').val(EndDate);
 		//$('#BaseCriteria2').val(BaseCriteria);
 		//$('#BaseCriteria').val(BaseCriteria);
 		$('#Rate').val(Rate);
 		$('#Rate2').val(Rate2);
 		$('#Rate3').val(Rate3);
 		$('#criteria1').val(criteria1);
 		$('#criteria12').val(criteria12);
 		$('#criteria13').val(criteria13);
 		$('#criteria2').val(criteria2);
 		$('#criteria22').val(criteria22);
 		$('#criteria23').val(criteria23);
 		//$('#cm_id').html(selectProcess);
 		$('#cm_id').val(cm_id);
 		$('select').formSelect();
 		$('#userProcess').val(process);
 		$('#ApplicableFor').val(ApplicableFor);
 		$('#Request_Status').val(Request_Status);
 		$('#incentiveStatus').val(incSstatus);
 		$('#editId').val(editId);
 		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
 			if ($(element).val().length > 0) {
 				$(this).siblings('label, i').addClass('active');
 			} else {
 				$(this).siblings('label, i').removeClass('active');
 			}
 		});
 		$('select').formSelect();
 	}
 	$(document).ready(function() {
 		$('#btnCancel').click(function() {
 			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

 				if ($(element).val().length > 0) {
 					$(this).siblings('label, i').addClass('active');
 				} else {
 					$(this).siblings('label, i').removeClass('active');
 				}

 			});
 			//$('.statuscheck').addClass('hidden');	
 		});


 		$("#Incentive_Type").on('change', function() {
 			var incType = $("#Incentive_Type").val();
 			if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
 				$("#BaseCriteria").val('Login Window');
 				$("#BaseCriteria2").val('Login Window');
 				$("#c1").html('Shift IN ');
 				$("#c2").html('Shift OUT ');
 				$('#newField').hide();
 				$("#criteria12").val('');
 				$("#criteria13").val('');
 				$("#criteria23").val('');
 				$("#criteria22").val('');
 				$("#Rate2").val('');
 				$("#Rate3").val('');

 			} else {
 				$("#BaseCriteria").val('Present Days');
 				$("#BaseCriteria2").val('Present Days');
 				$("#c1").html('Present ');
 				$("#c2").html('Absent ');
 				$('#newField').show();
 			}
 			var criteria1 = "";
 			var criteria2 = "";
 			if (incType == 'Split') {
 				criteria1 = "<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>";
 				$("#criteria1").html(criteria1);
 				criteria2 = "<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";
 				$("#criteria2").html(criteria2);
 				$('select').formSelect();
 			} else
 			if (incType == 'Attendance') {
 				$('#newField').show();
 				criteria1 = " <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
 				$("#c1").html('Present ');
 				$("#criteria1").html(criteria1);
 				$("#criteria12").html(criteria1);
 				$("#criteria13").html(criteria1);
 				criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
 				//alert(criteria2);	
 				$("#c2").html('Absent ');
 				$("#criteria2").html(criteria2);
 				$("#criteria22").html(criteria2);
 				$("#criteria23").html(criteria2);
 				$('select').formSelect();
 			} else
 			if (incType == 'Night/Late Evening') {
 				criteria1 = "<option value=''>---Select---</option>";
 				criteria1 += "<option value='12 PM'>12 PM</option>";
 				for (i = 1; i < 12; i++) {
 					criteria1 += "<option value='" + i + " PM'>" + i + " PM</option>";
 				}
 				criteria1 += "<option value='12 AM'>12 AM</option>";
 				$("#criteria1").html(criteria1);

 				criteria2 = "<option value=''>---Select---</option>	<option value='9 PM'>9 PM</option><option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option><option value='12 PM'>12 PM</option>";

 				for (j = 1; j <= 9; j++) {
 					criteria2 += "<option value='" + j + " AM'>" + j + " AM</option>";

 				}
 				$("#criteria2").html(criteria2);
 				$('select').formSelect();

 			} else
 			if (incType == 'Morning') {
 				criteria1 = "<option value=''>---Select---</option>";
 				for (i = 4; i <= 7; i++) {
 					criteria1 += "<option value='" + i + " AM'>" + i + " AM</option>";
 				}
 				$("#criteria1").html(criteria1);
 				criteria2 = "<option value=''>---Select---</option>	<option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";
 				$("#criteria2").html(criteria2);
 				$('select').formSelect();
 			} else
 			if (incType == 'Woman') {
 				$('#newField').show();
 				criteria1 = "<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
 				$("#c1").html('Present ');
 				$("#criteria1").html(criteria1);
 				$("#criteria12").html(criteria1);
 				$("#criteria13").html(criteria1);
 				criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
 				$("#c2").html('Absent ');
 				$("#criteria2").html(criteria2);
 				$("#criteria22").html(criteria2);
 				$("#criteria23").html(criteria2);
 				$('select').formSelect();
 			}
 			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
 				if ($(element).val().length > 0) {
 					$(this).siblings('label, i').addClass('active');
 				} else {
 					$(this).siblings('label, i').removeClass('active');
 				}
 			});
 			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
 				if ($(element).val().length > 0) {
 					$(this).siblings('label, i').addClass('active');
 				} else {
 					$(this).siblings('label, i').removeClass('active');
 				}
 			});
 		});
 		$('#cm_id').change(function() {
 			var Process = $('#cm_id option:selected').attr('id');
 			$('#userProcess').val(Process);
 			$('select').formSelect();
 			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

 				if ($(element).val().length > 0) {
 					$(this).siblings('label, i').addClass('active');
 				} else {
 					$(this).siblings('label, i').removeClass('active');
 				}

 			});
 			$('select').formSelect();
 		});
 		$('#criteria1').change(function() {
 			$('#criteria2').val('');
 			var incType = $("#Incentive_Type").val();
 			if (incType == 'Attendance') {
 				$('#criteria2').val('');
 				$('select').formSelect();
 			} else
 			if (incType == 'Night/Late Evening') {
 				var criteria1 = $('#criteria1').val();
 				//alert(criteria1);
 				if (criteria1 == '1 PM') {
 					$('#criteria2').val('10 PM');
 				} else
 				if (criteria1 == '2 PM') {
 					$('#criteria2').val('11 PM');
 				} else
 				if (criteria1 == '3 PM') {
 					$('#criteria2').val('12 PM');
 				} else
 				if (criteria1 == '4 PM') {
 					$('#criteria2').val('1 AM');
 				} else
 				if (criteria1 == '5 PM') {
 					$('#criteria2').val('2 AM');
 				} else
 				if (criteria1 == '6 PM') {
 					$('#criteria2').val('3 AM');
 				} else
 				if (criteria1 == '7 PM') {
 					$('#criteria2').val('4 AM');
 				} else
 				if (criteria1 == '8 PM') {
 					$('#criteria2').val('5 AM');
 				} else
 				if (criteria1 == '9 PM') {
 					$('#criteria2').val('6 AM');
 				} else
 				if (criteria1 == '10 PM') {
 					$('#criteria2').val('7 AM');
 				} else
 				if (criteria1 == '11 PM') {
 					$('#criteria2').val('8 AM');
 				} else
 				if (criteria1 == '12 PM') {
 					$('#criteria2').val('9 PM');
 				} else
 				if (criteria1 == '12 AM') {
 					$('#criteria2').val('9 AM');
 				}
 				$('select').formSelect();
 			} else
 			if (incType == 'Morning') {
 				var criteria1 = $('#criteria1').val();
 				if (criteria1 == '4 AM') {
 					$('#criteria2').val('1 PM');
 				} else
 				if (criteria1 == '5 AM') {
 					$('#criteria2').val('2 PM');
 				} else
 				if (criteria1 == '6 AM') {
 					$('#criteria2').val('3 PM');
 				} else
 				if (criteria1 == '7 AM') {
 					$('#criteria2').val('4 PM');
 				}
 				$('select').formSelect();
 			}
 			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

 				if ($(element).val().length > 0) {
 					$(this).siblings('label, i').addClass('active');
 				} else {
 					$(this).siblings('label, i').removeClass('active');
 				}

 			});

 		});
 		$('#btnSave1, #btnEdit').click(function() {
 			validate = 0;
 			alert_msg = "";
 			incType = $('#Incentive_Type').val();
 			if ($('#Incentive_Type').val() == "") {
 				validate = 1;
 				//alert_msg+='<li> Please select Incentive Type</li>';
 				$('#Incentive_Type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 				if ($('#span_Incentive_Type').size() == 0) {
 					$('<span id="span_Incentive_Type" class="help-block">Please select Incentive Type</span>').insertAfter('#Incentive_Type');
 				}

 			}
 			if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
 				var level1 = 'Shift IN';
 				var level2 = 'Shift OUT';
 			} else {
 				var level1 = 'Present day';
 				var level2 = 'Absent day';
 			}
 			var StartDate = $('#StartDate').val().trim();
 			if (StartDate == '') {
 				validate = 1;
 				$('#StartDate').addClass('has-error');
 				if ($('#stxt_StartDate').size() == 0) {
 					$('<span id="stxt_StartDate" class="help-block">Please select Start Date</span>').insertAfter('#StartDate');
 				}

 			}
 			var EndDate = $('#EndDate').val().trim();
 			if (EndDate == '') {
 				validate = 1;
 				$('#EndDate').addClass('has-error');
 				if ($('#stxt_EndDate').size() == 0) {
 					$('<span id="stxt_EndDate" class="help-block">Please select End Date</span>').insertAfter('#EndDate');
 				}

 			}
 			var cm_id = $('#cm_id').val().trim();
 			if (cm_id == "") {
 				validate = 1;
 				$('#cm_id').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 				if ($('#span_cm_id').size() == 0) {
 					$('<span id="span_cm_id" class="help-block">Please select process</span>').insertAfter('#cm_id');
 				}
 			}
 			//var Process=	$('#cm_id option:selected').attr('id');
 			//$('#userProcess').val(Process);
 			var Rate = $('#Rate').val().trim();
 			if (Rate == "") {
 				validate = 1;
 				$('#Rate').addClass('has-error');
 				if ($('#stxt_Rate').size() == 0) {
 					$('<span id="stxt_Rate" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate');
 				}
 			}
 			var criteria1 = $('#criteria1').val().trim();
 			if (criteria1 == "") {
 				validate = 1;
 				$('#criteria1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 				if ($('#span_criteria1').size() == 0) {
 					$('<span id="span_criteria1" class="help-block"> Please select ' + level1 + '</span>').insertAfter('#criteria1');
 				}
 			}
 			var criteria2 = $('#criteria2').val().trim();
 			if (criteria2 == "") {
 				validate = 1;
 				$('#criteria2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 				if ($('#span_criteria2').size() == 0) {
 					$('<span id="span_criteria2" class="help-block">Please select ' + level2 + '</span>').insertAfter('#criteria2');
 				}
 			}
 			var Rate2 = $('#Rate2').val().trim();
 			var criteria12 = $('#criteria12').val().trim();
 			var criteria22 = $('#criteria22').val().trim();
 			if (!((Rate2 == "" && criteria12 == "" && criteria22 == "") || (Rate2 != "" && criteria12 != "" && criteria22 != ""))) {
 				validate = 1;
 				//alert_msg+='<li>Incentive Amount2, Present day and Absent day should not be empty</li>';
 				if (Rate2 == "") {
 					$('#Rate2').addClass('has-error');
 					if ($('#stxt_Rate2').size() == 0) {
 						$('<span id="stxt_Rate2" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate2');
 					}
 				}
 				if (criteria12 == "") {
 					$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 					if ($('#span_criteria12').size() == 0) {
 						$('<span id="span_criteria12" class="help-block"> Please select present day </span>').insertAfter('#criteria12');
 					}
 				}
 				if (criteria22 == "") {
 					$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 					if ($('#span_criteria22').size() == 0) {
 						$('<span id="span_criteria22" class="help-block"> Please select absent day </span>').insertAfter('#criteria22');
 					}
 				}

 			} else {
 				$('#span_criteria22').html('');
 				$('#span_criteria12').html('');
 				$('#stxt_Rate2').html('');
 				$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
 				$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
 				$('#Rate2').removeClass('has-error');
 			}
 			var Rate3 = $('#Rate3').val().trim();
 			var criteria13 = $('#criteria13').val().trim();
 			var criteria23 = $('#criteria23').val().trim();
 			if (!((Rate3 == "" && criteria13 == "" && criteria23 == "") || (Rate3 != "" && criteria13 != "" && criteria23 != ""))) {
 				validate = 1;
 				if (Rate3 == "") {
 					$('#Rate3').addClass('has-error');
 					if ($('#stxt_Rate3').size() == 0) {
 						$('<span id="stxt_Rate3" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate3');
 					}
 				}
 				if (criteria13 == "") {
 					$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 					if ($('#span_criteria13').size() == 0) {
 						$('<span id="span_criteria13" class="help-block"> Please select present day </span>').insertAfter('#criteria13');
 					}
 				}
 				if (criteria23 == "") {
 					$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
 					if ($('#span_criteria23').size() == 0) {
 						$('<span id="span_criteria23" class="help-block"> Please select absent day </span>').insertAfter('#criteria23');
 					}
 				}

 			} else {
 				$('#span_criteria23').html('');
 				$('#span_criteria13').html('');
 				$('#stxt_Rate3').html('');
 				$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
 				$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
 				$('#Rate3').removeClass('has-error');
 			}


 			if (validate == 1) {
 				if (alert_msg != "") {
 					$(function() {
 						toastr.error(alert_msg)
 					});
 				}
 				return false;
 			}
 			return confirm('Are you want to proceed?');
 		});



 	});

 	function checklistdata() {
 		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
 		//$('.statuscheck').removeClass('hidden');

 	}

 	function isNumber(evt) {
 		evt = (evt) ? evt : window.event;
 		var charCode = (evt.which) ? evt.which : evt.keyCode;
 		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
 			return false;
 		}
 		return true;
 	}

 	function deleteData(editID) {
 		if (editID != "") {
 			var result = confirm('Are you want to proceed?');
 			if (result) {
 				location.href = 'inc_IncentiveCriteriaApproved.php?delid=' + editID + ')';
 			}
 		}
 	}
 </script>
 <?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>