<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
//require_once(CLS . 'MysqliDb.php');
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
			$year = $_POST['txt_Year'];
			$month = $_POST['txt_Month'];
		} else {
			$year = date('Y');
			$month = date('n');
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
					<div class="input-field col s3 m3">
						Select month
						<Select name="txt_Month" id="txt_Month">
							<?php
							$name = date('M');
							$val = date('n');
							echo "<option value=$val>$name</option> ";
							$date = date('Y-m-d', strtotime('first day of -1 month'));
							$name = date('M', strtotime($date));
							$val = date('n', strtotime($date));
							echo "<option value=$val>$name</option> ";
							$date = date('Y-m-d', strtotime('first day of -2 month'));
							$name = date('M', strtotime($date));
							$val = date('n', strtotime($date));
							echo "<option value=$val>$name</option> ";
							?>


						</Select>
					</div>
					<div class="input-field col s3 m3">
						Select Year
						<Select name="txt_Year" id="txt_Year">
							<?php
							$val1 = date('Y');
							echo "<option value=$val1>$val1</option> ";
							$date = date('Y-m-d', strtotime('first day of -1 month'));

							$val2 = date('Y', strtotime($date));
							if ($val1 != $val2) {
								echo "<option value=$val2>$val2</option> ";
							}

							$date = date('Y-m-d', strtotime('first day of -2 month'));

							$val3 = date('Y', strtotime($date));
							if ($val1 != $val3) {
								echo "<option value=$val3>$val3</option> ";
							}
							?>


						</Select>
					</div>

					<div class="input-field col s6 m6">

						Select Process

						<Select name="txt_Process" id="txt_Process">
							<?php
							$myDB = new MysqliDb();
							if ($_SESSION['__user_logid'] == 'CMK02204575' || $_SESSION['__user_logid'] == 'CEK062280649' || $_SESSION['__user_logid'] == 'MU12211128' || $_SESSION['__user_logid'] == 'CEK012035261' || $_SESSION['__user_logid'] == 'CE0321936494') {
								$rowData = $myDB->query('select client_id, client_name from client_master where client_id="82";');
							} else {
								$rowData = $myDB->query('select client_id, client_name from client_master order by client_name;');
							}

							$my_error = $myDB->getLastError();
							if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_type'] == 'COMPLIANCE' || $_SESSION['__user_type'] == 'CENTRAL MIS' || $_SESSION['__user_logid'] == 'CE12102224') {
								echo "<option value=ALL>ALL</option> ";
							}
							if (empty($my_error)) {

								foreach ($rowData as $key => $value) {
									$cm_id = $value['client_id'];
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
						<option value='Active' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Active') {
													echo "selected";
												} ?>>Active</option>
						<option value='InActive' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'InActive') {
														echo "selected";
													} ?>>InActive</option>
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
					//echo 'call sp_getAPRReport("' . $_SESSION['__user_logid'] . '","' . $month . '","' . $year . '","' . $empStatus . '","' . $process . '")';
					$chk_task = $myDB->query('call sp_getAPRReport("' . $_SESSION['__user_logid'] . '","' . $month . '","' . $year . '","' . $empStatus . '","' . $process . '")');
					$my_error = $myDB->getLastError();
					if (empty($my_error)) {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>D1</th>';
						$table .= '<th>D2</th>';
						$table .= '<th>D3</th>';
						$table .= '<th>D4</th>';
						$table .= '<th>D5</th>';
						$table .= '<th>D6</th>';
						$table .= '<th>D7</th>';
						$table .= '<th>D8</th>';
						$table .= '<th>D9</th>';
						$table .= '<th>D10</th>';
						$table .= '<th>D11</th>';
						$table .= '<th>D12</th>';
						$table .= '<th>D13</th>';
						$table .= '<th>D14</th>';
						$table .= '<th>D15</th>';
						$table .= '<th>D16</th>';
						$table .= '<th>D17</th>';
						$table .= '<th>D18</th>';
						$table .= '<th>D19</th>';
						$table .= '<th>D20</th>';
						$table .= '<th>D21</th>';
						$table .= '<th>D22</th>';
						$table .= '<th>D23</th>';
						$table .= '<th>D24</th>';
						$table .= '<th>D25</th>';
						$table .= '<th>D26</th>';
						$table .= '<th>D27</th>';
						$table .= '<th>D28</th>';
						$table .= '<th>D29</th>';
						$table .= '<th>D30</th>';
						$table .= '<th>D31</th>';
						$table .= '<th>Month</th>';
						$table .= '<th>Year</th>';
						$table .= '<th>CreatedBy</th>';
						$table .= '<th>CreatedOn</th>';
						$table .= '<th>ModifiedBy</th>';
						$table .= '<th>ModifiedOn</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['D1'] . '</td>';
							$table .= '<td>' . $value['D2'] . '</td>';
							$table .= '<td>' . $value['D3'] . '</td>';
							$table .= '<td>' . $value['D4'] . '</td>';
							$table .= '<td>' . $value['D5'] . '</td>';
							$table .= '<td>' . $value['D6'] . '</td>';
							$table .= '<td>' . $value['D7'] . '</td>';
							$table .= '<td>' . $value['D8'] . '</td>';
							$table .= '<td>' . $value['D9'] . '</td>';
							$table .= '<td>' . $value['D10'] . '</td>';
							$table .= '<td>' . $value['D11'] . '</td>';
							$table .= '<td>' . $value['D12'] . '</td>';
							$table .= '<td>' . $value['D13'] . '</td>';
							$table .= '<td>' . $value['D14'] . '</td>';
							$table .= '<td>' . $value['D15'] . '</td>';
							$table .= '<td>' . $value['D16'] . '</td>';
							$table .= '<td>' . $value['D17'] . '</td>';
							$table .= '<td>' . $value['D18'] . '</td>';
							$table .= '<td>' . $value['D19'] . '</td>';
							$table .= '<td>' . $value['D20'] . '</td>';
							$table .= '<td>' . $value['D21'] . '</td>';
							$table .= '<td>' . $value['D22'] . '</td>';
							$table .= '<td>' . $value['D23'] . '</td>';
							$table .= '<td>' . $value['D24'] . '</td>';
							$table .= '<td>' . $value['D25'] . '</td>';
							$table .= '<td>' . $value['D26'] . '</td>';
							$table .= '<td>' . $value['D27'] . '</td>';
							$table .= '<td>' . $value['D28'] . '</td>';
							$table .= '<td>' . $value['D29'] . '</td>';
							$table .= '<td>' . $value['D30'] . '</td>';
							$table .= '<td>' . $value['D31'] . '</td>';
							$table .= '<td>' . $value['Month'] . '</td>';
							$table .= '<td>' . $value['Year'] . '</td>';
							$table .= '<td>' . $value['createdby'] . '</td>';
							$table .= '<td>' . $value['createdon'] . '</td>';
							$table .= '<td>' . $value['modifiedby'] . '</td>';
							$table .= '<td>' . $value['modifiedon'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
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