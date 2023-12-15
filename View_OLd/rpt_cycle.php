<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$__user_type = clean($_SESSION['_s_user_type']);
$__user_logid = clean($_SESSION['__user_logid']);


$my_error = '';
$chk_task = array();
$myDB = new MysqliDb();
$ALLStatus = '';
$emp_status = 'Active';
$txt_empmap_client = '';
if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
	$btnget = isset($_POST['btn_getdata']);
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt = cleanUserInput($_POST['txt_empmap_client']);
	}
	if (($btnget) && $txt != "") {
		if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
			$txt_empmap_client = cleanUserInput($_POST['txt_empmap_client']);
			$emp_status = cleanUserInput($_POST['emp_status']);
		}

		if ($emp_status == 'Active' && $txt_empmap_client == 'All') {
			$query = 'call EmployeeCycle_reportActiveAll()';
			$chk_task = $myDB->query($query);
		} elseif ($emp_status == 'Active' && $txt_empmap_client != 'All') {
			$query = 'call EmployeeCycle_reportActiveByClient("' . $txt_empmap_client . '")';
			$chk_task = $myDB->query($query);
		} elseif ($emp_status == 'InActive') {
			$query = 'call EmployeeCycle_reportInactive("' . $txt_empmap_client . '")';
			$chk_task = $myDB->query($query);
		}
	}
} else {
	/* for All Active Employee for OPS */
	$chk_task = $myDB->query('call EmployeeCycle_reportActiveOPS("' . $__user_logid . '")');
}
?>

<script>
	$(function() {
		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex) {
				var proc = $('#txt_process').val().toLowerCase();
				var sproc = $('#txt_Subproc').val().toLowerCase();
				var process = data[4]; // use data for the age column
				var subprocess = data[5]; // use data for the age column
				if (process.toLowerCase().indexOf(proc) >= 0 && subprocess.toLowerCase().indexOf(sproc) >= 0) {
					return true;
				} else {
					return false;
				}
			}
		);
		// DataTable
		var table = $('#myTable').DataTable({
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
			"iDisplayLength": 25,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});;
		$('#txt_Subproc, #txt_process').keyup(function() {
			table.draw();
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php

				if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
				?>
					<div class="form-inline col-sm-12" id="rpt_container">
						<div class="input-field col s6 m6">
							<select id="txt_empmap_client" name="txt_empmap_client" required>
								<option value="NA">----Select----</option>
								<?php
								$sqlBy = "select client_id,client_name FROM client_master";
								$myDB = new MysqliDb();
								$resultBy = $myDB->query($sqlBy);
								if ($resultBy) {
									$selec = '';
									foreach ($resultBy as $key => $value) {
										if ($value['client_id'] == $txt_empmap_client) {
											$selec = ' selected ';
										} else {
											$selec = '';
										}
										echo '<option value="' . $value['client_id'] . '"  ' . $selec . '>' . $value['client_name'] . '</option>';
									}
								}
								?>
								<option value="All" <?php if ('All' == $txt_empmap_client) {
														echo "selected";
													} ?>>All </option>
							</select>
							<label for="txt_empmap_client" class="active-drop-down active">Client *</label>
						</div>
						<div class="input-field col s6 m6">
							<select id="emp_status" name="emp_status" required>
								<option value="NA">----Select----</option>
								<option value="Active" <?php if ($emp_status == 'Active') {
															echo "Selected";
														} ?>>Active </option>
								<option value="InActive" <?php if ($emp_status == 'InActive') {
																echo "Selected";
															} ?>>InActive </option>
							</select>
							<label for="emp_status" class="active-drop-down active">Status*</label>
						</div>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" title="Get Data" name="btn_getdata" id="btn_getdata" class="btn waves-effect waves-green">Get Data</button>
							<?php if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {  ?>
								<button type="button" name="btn_Excel" id="btn_Excel" class="btn waves-effect modal-action modal-close waves-red close-btn" onclick="javascript:return downloadexcel(this);">Export</button>
							<?php }  ?>
						</div>
					</div>
				<?php } ?>
				<?php

				if (count($chk_task) > 0 && $chk_task) {
					if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
						$table .= '<th>Employee ID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>Employee Stage</th>';
						$table .= '<th>Batch Name</th>';
						$table .= '<th>Batch No.</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Employee Status</th>';

						$table .= '<th>DOJ</th>';
						$table .= '<th>EmpID Creation Date</th>';
						$table .= '<th>DOB</th>';
						$table .= '<th>Primary Language</th>';
						$table .= '<th>Secondary Language</th>';

						$table .= '<th>Trainer</th>';
						$table .= '<th>Training Head</th>';
						$table .= '<th>Quality Analyst (OJT)</th>';
						$table .= '<th>Quality Analyst (OPS)</th>';
						$table .= '<th>Quality Head</th>';
						$table .= '<th>Operation Head</th>';
						$table .= '<th>Account Head</th>';
						$table .= '<th>Vertical Head</th>';
						$table .= '<th>Supervisor</th>';

						$table .= '<th>Created On</th>';
						$table .= '<th>Training Start Training</th>';
						$table .= '<th>Training End Date</th>';
						$table .= '<th>Training Out Date</th>';
						$table .= '<th>Re Training</th>';
						$table .= '<th>OUT FROM TH</th>';
						$table .= '<th>In OJT QA</th>';

						$table .= '<th>Out Date</th>';
						$table .= '<th>Out OJT</th>';
						$table .= '<th>Re OJT</th>';
						$table .= '<th>RHR Date</th>';

						$table .= '<th>Training Days Overrun</th>';
						$table .= '<th>OJT Days Overrun</th>';

						$table .= '<th>On Floor</th>';
						$table .= '<th>Mapped Date</th>';

						$table .= '<th>Inactive Date</th>';
						$table .= '<th>Inactive Reason</th>';

						$table .= '</tr></thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Employee Level'] . '</td>';

							$table .= '<td>' . $value['BacthName'] . '</td>';
							$table .= '<td>' . $value['batch_no'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['emp_status'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['EmpIDcreationdate'] . '</td>';
							$table .= '<td>' . date('Y-m-d', strtotime($value['DOB'])) . '</td>';
							$table .= '<td>' . $value['primary_language'] . '</td>';
							$table .= '<td>' . $value['secondary_language'] . '</td>';
							$table .= '<td>' . $value['Trainer'] . '</td>';
							$table .= '<td>' . $value['TH'] . '</td>';
							$table .= '<td>' . $value['QA_OJT'] . '</td>';
							$table .= '<td>' . $value['QA_OPS'] . '</td>';
							$table .= '<td>' . $value['QH'] . '</td>';
							$table .= '<td>' . $value['oh'] . '</td>';
							$table .= '<td>' . $value['AH'] . '</td>';
							$table .= '<td>' . $value['VH'] . '</td>';
							$table .= '<td>' . $value['RT'] . '</td>';

							$table .= '<td>' . $value['CreatedOn'] . '</td>';
							$table .= '<td>' . $value['InTraining'] . '</td>';
							$table .= '<td>' . $value['CertDate'] . '</td>';
							$table .= '<td>' . $value['OutTraining'] . '</td>';
							$table .= '<td>' . $value['reTrain'] . '</td>';

							$table .= '<td>' . $value['InOJT'] . '</td>';
							$table .= '<td>' . $value['InQAOJT'] . '</td>';
							$table .= '<td>' . $value['ojt_Date'] . '</td>';
							$table .= '<td>' . $value['OutOJTQA'] . '</td>';
							$table .= '<td>' . $value['reOJT'] . '</td>';
							$table .= '<td>' . $value['rhr_date'] . '</td>';


							if (!empty($value['CertDate']) && !empty($value['OutTraining'])) {
								$date_1  = new DateTime($value['CertDate']);
								$date_2  = new DateTime($value['OutTraining']);
								$diff = date_diff($date_1, $date_2);
								if ($date_1 <= $date_2) {
									$table .= '<td>' . $diff->format("%R%a") . '</td>';
								} else {
									$table .= '&nbsp;<td></td>';
								}
							} else {
								$table .= '<td>&nbsp;</td>';
							}
							if (!empty($value['ojt_Date']) && !empty($value['OutOJTQA'])) {
								$date_1  = new DateTime($value['ojt_Date']);
								$date_2  = new DateTime($value['OutOJTQA']);
								$diff = date_diff($date_1, $date_2);
								if ($date_1 <= $date_2) {
									$table .= '<td>' . $diff->format("%R%a") . '&nbsp;</td>';
								} else {
									$table .= '<td>&nbsp;</td>';
								}
							} else {
								$table .= '<td>&nbsp;</td>';
							}
							$table .= '<td>' . $value['onFloor'] . '</td>';
							$table .= '<td>' . $value['mapped_date'] . '</td>';
							$table .= '<td>' . $value['dol'] . '</td>';
							$table .= '<td>' . $value['disposition'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div></div>';
					} else {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Employee Stage</th>';
						$table .= '<th>Batch Name</th>';
						$table .= '<th>Batch No</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Employee Status</th>';
						$table .= '<th>Primary Language</th>';
						$table .= '<th>Secondary Language</th>';
						$table .= '<th>Trainer</th>';
						$table .= '<th>Training Head</th>';
						$table .= '<th>Quality Analyst (OJT)</th>';
						$table .= '<th>Quality Head</th>';
						$table .= '<th>Quality Analyst (OPS)</th>';
						$table .= '<th>Date of Join</th>';
						$table .= '<th>EmpID Creation Date</th>';
						$table .= '<th>Training Start Training</th>';
						$table .= '<th>Training End Date</th>';
						$table .= '<th>Training Out Date</th>';
						$table .= '<th>Certification Attempts</th>';
						$table .= '<th>OJT Start Date</th>';
						$table .= '<th>OJT End Date</th>';
						$table .= '<th>OJT Out Date</th>';
						$table .= '<th>RHR Date</th>';
						$table .= '<th>Training Days Overrun</th>';
						$table .= '<th>OJT Days Overrun</th>';
						$table .= '<th>On Floor</th>';
						$table .= '<th>Inactive Date</th>';
						$table .= '<th>Inactive Reason</th>';
						$table .= '</tr></thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Employee Level'] . '</td>';
							$table .= '<td>' . $value['BacthName'] . '</td>';
							$table .= '<td>' . $value['batch_no'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['emp_status'] . '</td>';
							$table .= '<td>' . $value['primary_language'] . '</td>';
							$table .= '<td>' . $value['secondary_language'] . '</td>';
							$table .= '<td>' . $value['Trainer'] . '</td>';
							$table .= '<td>' . $value['TH'] . '</td>';
							$table .= '<td>' . $value['QA_OJT'] . '</td>';
							$table .= '<td>' . $value['QH'] . '</td>';
							$table .= '<td>' . $value['QA_OPS'] . '</td>';
							$table .= '<td>' . date('Y-m-d', strtotime($value['DOJ'])) . '</td>';
							$table .= '<td>' . $value['EmpIDcreationdate'] . '</td>';
							$table .= '<td>' . $value['InTraining'] . '</td>';
							$table .= '<td>' . $value['CertDate'] . '</td>';
							$table .= '<td>' . $value['OutTraining'] . '</td>';
							if (!empty($value['reTrain']) && !empty($value['InTraining'])) {
								$table .= '<td>2</td>';
							} elseif (!empty($value['InTraining'])) {
								$table .= '<td>1</td>';
							} else {
								$table .= '<td>0</td>';
							}
							$table .= '<td>' . $value['InQAOJT'] . '</td>';
							$table .= '<td>' . $value['ojt_Date'] . '</td>';
							$table .= '<td>' . $value['OutOJTQA'] . '</td>';
							$table .= '<td>' . $value['rhr_date'] . '</td>';

							if (!empty($value['CertDate']) && !empty($value['OutTraining'])) {
								$date_1  = new DateTime($value['CertDate']);
								$date_2  = new DateTime($value['OutTraining']);

								$diff = date_diff($date_1, $date_2);
								if ($date_1 <= $date_2) {
									$table .= '<td>' . $diff->format("%R%a") . '</td>';
								} else {
									$table .= '<td></td>';
								}
							} else {
								$table .= '<td></td>';
							}
							if (!empty($value['ojt_Date']) && !empty($value['OutOJTQA'])) {
								$date_1  = new DateTime($value['ojt_Date']);
								$date_2  = new DateTime($value['OutOJTQA']);
								$diff = date_diff($date_1, $date_2);
								if ($date_1 <= $date_2) {
									$table .= '<td>' . $diff->format("%R%a") . '</td>';
								} else {
									$table .= '<td></td>';
								}
							} else {
								$table .= '<td></td>';
							}
							$table .= '<td>' . $value['onFloor'] . '</td>';
							$table .= '<td>' . $value['dol'] . '</td>';
							$table .= '<td>' . $value['disposition'] . '</td>';
							$table .= '</tr>';
						}

						$table .= '</tbody></table></div></div>';
					}

					echo $table;
				} else {
					echo "<script>$(function(){ toastr.info('No record found. $my_error'); }); </script>";
				}


				?>
				<div class="col-sm-12">
					<?php
					if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
					?>
						<input type="text" style="display: none;" id="txt_process" placeholder="Process" value="" />
						<input type="text" style="display: none;" id="txt_Subproc" placeholder="Sub Process" />
					<?php
					} else {
					?>
						<input type="text" style="display: none;" id="txt_process" placeholder="Process" value="" />
						<input type="text" style="display: none;" id="txt_Subproc" placeholder="Process" />
					<?php
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
	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});

	function downloadexcel(el) {

		$item = $(el);

		var status = $('#emp_status').val();
		var client = $('#txt_empmap_client').val();
		var sp = "";
		//$query =  'call sp_get_atnd_Report("' . $userid . '","' . $month . '","' . $year . '","' . $dept . '","' . $type . '","' . $loc . '")';
		if (status == 'Active' && client == 'All') {
			// sp = "call EmployeeCycle_reportActiveAll()";
			sp = "call EmployeeCycle_reportActiveAll_EXCEL()";
		} else if (status == 'Active' && client != 'All') {
			// sp = "call EmployeeCycle_reportActiveByClient('" + client + "')";
			sp = "call EmployeeCycle_reportActiveByClient_EXCEL('" + client + "')";
		} else if (status == 'InActive') {
			// sp = "call EmployeeCycle_reportInactive('" + client + "')";
			sp = "call EmployeeCycle_reportInactive_EXCEL('" + client + "')";
		}

		var url = "textExport.php?sp=" + sp;
		//alert(url);
		window.location.href = url;


	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>