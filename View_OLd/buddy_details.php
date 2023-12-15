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
$searchBy = $msg = $batch_id = $status = '';
$btnsave = isset($_POST['btnSave']);
if ($btnsave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$cm_id = cleanUserInput($_POST['ddl_cm_id']);
		$EmployeeID = cleanUserInput($_POST['ddl_emp_id']);
		if (!empty($cm_id) && $cm_id != 'NA' && !empty($EmployeeID) && $EmployeeID != 'NA') {
			$query = 'SELECT * FROM tbl_buddy where EmployeeID = ? and DATE_ADD(cast(now() as date),INTERVAL 1 DAY) between cast(Buddy_Start as date) and cast(Buddy_End as date)';
			$select = $conn->prepare($query);
			$select->bind_param("s", $EmployeeID);
			$select->execute();
			$result_check = $select->get_result();
			if ($result_check->num_rows > 0 && $result_check) {
				echo "<script>$(function(){ toastr.info(' " . $EmployeeID . " Already exists, delete existing entry first'); }); </script>";
			} else {
				$insert = 'call sp_InsertBuddy("' . $EmployeeID . '","' . clean($_SESSION['__user_logid']) . '")';
				$myDB->rawQuery($insert);
				$my_error = $myDB->getLastError();
				if (empty($my_error)) {
					echo "<script>$(function(){ toastr.success(' " . $EmployeeID . " Add into Buddy Support data'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Request not completed'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Request Not Updated,No Employee Selected'); }); </script>";
		}
	}
}

if (!empty($_GET['action'])) {
	if (cleanUserInput($_GET['action']) == "delete" && !empty($_GET['empid'])) {
		$EmployeeID = cleanUserInput($_GET['empid']);
		if (!empty($EmployeeID) && $EmployeeID != 'NA') {
			$delete = "delete from tbl_buddy where EmployeeID = ?";
			$del = $conn->prepare($delete);
			$del->bind_param("s", $EmployeeID);
			$del->execute();
			$results = $del->get_result();
			if ($del->affected_rows === 1) {
				// $myDB->rawQuery($delete);
				// $my_error = $myDB->getLastError();
				// if (empty($my_error)) {
				echo "<script>$(function(){ toastr.success('Employee Deleted from Buddy Support'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Request not completed'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.success('Request Not Updated,No Employee Selected'); }); </script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
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

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "192",
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {
				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
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
	<span id="PageTittle_span" class="hidden">Buddy Support Manage</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Buddy Support Manage</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="input-field col s6 m6">
					<select name="ddl_cm_id" id="ddl_cm_id">
						<option value="NA">---Select---</option>
						<?php
						$sqlBy = 'select distinct cm_id,Process,sub_process,clientname from whole_details_peremp  where des_id in (9,12) order by Process';
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							foreach ($resultBy as $key => $value) {
								if (cleanUserInput($_GET['pid']) == $value['cm_id']) {
									if ($value['Process'] == $value['sub_process']) {
										echo '<option value="' . $value['cm_id'] . '"  selected>' . $value['clientname'] . ' | ' . $value['sub_process'] . '</option>';
									} else {
										echo '<option value="' . $value['cm_id'] . '"  selected>' . $value['clientname'] . ' | ' . $value['Process'] . ' | ' . $value['sub_process'] . '</option>';
									}
								} else {
									if ($value['Process'] == $value['sub_process']) {
										echo '<option value="' . $value['cm_id'] . '"  >' . $value['clientname'] . ' | ' . $value['sub_process'] . '</option>';
									} else {
										echo '<option value="' . $value['cm_id'] . '"  >' . $value['clientname'] . ' | ' . $value['Process'] . ' | ' . $value['sub_process'] . '</option>';
									}
								}
							}
						}
						?>
					</select>
					<label for="ddl_cm_id" class="active-drop-down active">Process</label>
				</div>
				<div class="input-field col s6 m6">
					<select name="ddl_emp_id" id="ddl_emp_id">
						<option value="NA">---Select---</option>
						<?php
						$pid = cleanUserInput($_GET['pid']);
						$sqlBy = 'select distinct EmployeeID,EmployeeName from whole_details_peremp  where des_id in (9,12) and cm_id =?  order by EmployeeName';
						$select = $conn->prepare($sqlBy);
						$select->bind_param("i", $pid);
						$select->execute();
						$resultBy = $select->get_result();
						// $resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							foreach ($resultBy as $key => $value) {
								echo '<option value="' . $value['EmployeeID'] . '"  >' . $value['EmployeeName'] . '  [ ' . $value['EmployeeID'] . ' ] </option>';
							}
						}
						?>
					</select>
					<label for="ddl_emp_id" class="active-drop-down active">Employee</label>
				</div>
				<div class="input-field col s12 m12 right-align">
					<input type="submit" value="Add to Buddy Support " name="btnSave" id="btnSave" class="btn waves-effect waves-green" />

				</div>

				<?php if (!empty($_GET['pid'])) { ?>
					<div id="pnlTable">
						<?php
						$getpid = cleanUserInput($_GET['pid']);
						$sqlConnect = 'select * from whole_details_peremp inner join tbl_buddy on tbl_buddy.EmployeeID = whole_details_peremp.EmployeeID where des_id in (9,12) and cast(Buddy_End as date) >= cast(now() as date) and  cm_id =?';
						$selects = $conn->prepare($sqlConnect);
						$selects->bind_param("i", $getpid);
						$selects->execute();
						$chk_task = $selects->get_result();
						//echo $sqlConnect;
						// $error = $myDB->getLastError();
						if ($chk_task) { ?>

							<div class="panel panel-default" style="margin-top: 10px;float: left;width: 100%">
								<div class="panel-body">
									<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>EmployeeID</th>
												<th>EmployeeName</th>
												<th>Designation</th>
												<th>Dept Name</th>
												<th>DOJ</th>
												<th>Client</th>
												<th>Process</th>
												<th>Sub Process</th>
												<th>Delete</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$count = 0;
											foreach ($chk_task as $key => $value) {
												$count++;
												echo '<tr>';
												echo '<td class="client_name edit_view">' . $value['EmployeeID'] . '</td>';
												echo '<td class="client_name edit_view">' . $value['EmployeeName'] . '</td>';
												echo '<td class="client_name edit_view">' . $value['designation'] . '</td>';
												echo '<td class="process edit_view">' . $value['dept_name'] . '</td>';
												echo '<td class="sub_process edit_view">' . $value['DOJ'] . '</td>';
												echo '<td class="client_name edit_view">' . $value['clientname'] . '</td>';
												echo '<td class="process edit_view">' . $value['Process'] . '</td>';
												echo '<td class="sub_process edit_view">' . $value['sub_process'] . '</td>';
												echo '<td><a href="#" data-ID="' . $value['EmployeeID'] . '" class="a__ID" onclick="javascirpt:return ApplicationDataDelete(' . "'" . $value['EmployeeID'] . "'" . ');"><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i></a></td>';
												echo '</tr>';
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						<?php
						} else {
							echo "<script>$(function(){ toastr.success('Congratulations, all employees have been aligned to concern departments.'); }); </script>";
						}
						?>
					</div>
				<?php } ?>

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
		$('#ddl_cm_id').change(function() {
			window.location = "buddy_details.php?pid=" + $('#ddl_cm_id').val();
		});
	});

	function ApplicationDataDelete(el) {
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
					alert(Resp);
				}
			}

			window.location = "buddy_details.php?pid=" + $('#ddl_cm_id').val() + "&empid=" + el + "&action=delete";
		}

		//window.location = "buddy_details.php?pid="+$('#ddl_cm_id').val()+"&empid="+el+"&action=delete";

	}
</script>