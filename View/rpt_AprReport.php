<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
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

		if ($isPostBack && isset($_POST)) {

			$date_From = $_POST['txt_dateFrom'];
		} else {

			$date_From = date('Y-m-d', strtotime("-1 days"));
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">APR Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>APR Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<script>
					$(function() {

						$('#txt_dateFrom').datetimepicker({
							timepicker: false,
							format: 'Y-m-d'


						});
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
							}, 'pageLength'],
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





				<div class="input-field col s12 m12" id="rpt_container">

					<div class="input-field col s6 m6">

						<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>



					<div class="input-field col s6 m6">



						<Select name="txt_Process" id="txt_Process">
							<?php
							$myDB = new MysqliDb();
							$rowData = $myDB->query('select client_id, client_name from client_master order by client_name;');
							$my_error = $myDB->getLastError();
							if (empty($my_error)) {

								foreach ($rowData as $key => $value) {
									$cm_id = $value['client_name'];
									$process = $value['client_name'];
									echo "<option value=$cm_id>$process</option> ";
								}
							}
							?>
						</Select>
					</div>



				</div>

				<div class="input-field col s6 m6 left-align">

					<Select name="emp_status" style="min-width: 200px;" id="status">
						<option value='Summary' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Summary') {
													echo "selected";
												} ?>>Summary Report</option>
						<option value='Detailed' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Detailed') {
														echo "selected";
													} ?>>Detailed Report</option>
					</Select>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
						<i class="fa fa-search"></i> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>


				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$empStatus = $_POST['emp_status'];
					$process = $_POST['txt_Process'];
					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					//echo 'call sp_getRawAPRReport("'.$date_From.'","'.$process.'","'.$empStatus.'")';
					$chk_task = $myDB->query('call sp_getRawAPRReport("' . $date_From . '","' . $process . '","' . $empStatus . '")');
					$my_error = $myDB->getLastError();
					if (empty($my_error)) {
						if ($empStatus == "Summary") {
							$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
							$table .= '<th>CosmoID</th>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>Date</th>';
							$table .= '<th>LoggedIn</th>';
							$table .= '<th>LoggedOut</th>';
							$table .= '<th>On Call</th>';
							$table .= '<th>Break</th>';
							$table .= '<th>APR</th>';
							$table .= '<th>Process</th><thead><tbody>';

							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['agentid'] . '</td>';
								$table .= '<td>' . $value['employeeid'] . '</td>';
								$table .= '<td>' . $value['date'] . '</td>';
								$table .= '<td>' . $value['logged_in'] . '</td>';
								$table .= '<td>' . $value['logged_out'] . '</td>';
								$table .= '<td>' . $value['on_call'] . '</td>';
								$table .= '<td>' . $value['break'] . '</td>';
								$table .= '<td>' . $value['apr'] . '</td>';
								$table .= '<td>' . $value['process'] . '</td></tr>';
							}
							$table .= '</tbody></table></div></div>';
							echo $table;
						} else if ($empStatus == "Detailed") {
							$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
							$table .= '<th>Process</th>';
							$table .= '<th>LoggedIn</th>';
							$table .= '<th>LoggedOut</th>';
							$table .= '<th>Duration</th>';
							$table .= '<th>Agent ID</th>';
							$table .= '<th>Agent First Name</th>';
							$table .= '<th>Agent Last Name</th>';
							$table .= '<th>Available</th>';
							$table .= '<th>talk</th>';
							$table .= '<th>WrapUp</th>';
							$table .= '<th>Release</th>';
							$table .= '<th>Hold</th>';
							$table .= '<th>Other</th>';
							$table .= '<th>calls</th>';
							$table .= '<th>ASA</th>';
							$table .= '<th>MaxSpeedAns</th><thead><tbody>';

							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['Process_Name'] . '</td>';
								$table .= '<td>' . $value['LoggedIn'] . '</td>';
								$table .= '<td>' . $value['Loggedout'] . '</td>';
								$table .= '<td>' . $value['Duration'] . '</td>';
								$table .= '<td>' . $value['Agent_ID'] . '</td>';
								$table .= '<td>' . $value['AgentFirstName'] . '</td>';
								$table .= '<td>' . $value['AgentLastName'] . '</td>';
								$table .= '<td>' . $value['Available'] . '</td>';
								$table .= '<td>' . $value['talk'] . '</td>';
								$table .= '<td>' . $value['WrapUp'] . '</td>';
								$table .= '<td>' . $value['Release'] . '</td>';
								$table .= '<td>' . $value['hold'] . '</td>';
								$table .= '<td>' . $value['other'] . '</td>';
								$table .= '<td>' . $value['calls'] . '</td>';
								$table .= '<td>' . $value['ASA'] . '</td>';
								$table .= '<td>' . $value['MaxSpeedAns'] . '</td></tr>';
							}
							$table .= '</tbody></table></div></div>';
							echo $table;
						}
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
					}
				}

				?>
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>