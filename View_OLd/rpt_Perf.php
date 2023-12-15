<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	$user_ah = clean($_SESSION["__status_ah"]);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else if ($user_ah == 'No') {
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
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$year = cleanUserInput($_POST['txt_Year']);
				$month = cleanUserInput($_POST['txt_Month']);
			}
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
	<span id="PageTittle_span" class="hidden">Performance Acknowledgement Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Performance Acknowledgement Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

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

					<div class="input-field col s6 m6 left-align">
						Select Status
						<Select name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
						<i class="fa fa-search"></i> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>

				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					// if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
					$empStatus = cleanUserInput($_POST['emp_status']);
					$process = cleanUserInput($_POST['txt_Process']);
					// }
					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					/*echo 'call getPer_Report("'.$_SESSION['__user_logid'].'","'.$month.'","'.$year.'","'.$empStatus.'")';
			          die;*/
					$userlogID = clean($_SESSION['__user_logid']);
					// echo ('call getPer_Report("' . $userlogID . '","' . $month . '","' . $year . '","' . $empStatus . '")');
					// die;
					$chk_task = $myDB->query('call getPer_Report("' . $userlogID . '","' . $month . '","' . $year . '","' . $empStatus . '")');
					$my_error = $myDB->getLastError();
					if (empty($my_error) && $chk_task) {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Date</th>';
						$table .= '<th>Acknowledge Date</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>L1</th>';
						$table .= '<th>V1</th>';
						$table .= '<th>L2</th>';
						$table .= '<th>V2</th>';
						$table .= '<th>L3</th>';
						$table .= '<th>V3</th>';
						$table .= '<th>DL4</th>';
						$table .= '<th>V4</th>';
						$table .= '<th>L5</th>';
						$table .= '<th>V5</th>';
						$table .= '<th>L6</th>';
						$table .= '<th>V6</th>';
						$table .= '<th>L7</th>';
						$table .= '<th>V7</th>';
						$table .= '<th>L8</th>';
						$table .= '<th>L8</th>';
						$table .= '<th>L9</th>';
						$table .= '<th>V9</th>';
						$table .= '<th>L10</th>';
						$table .= '<th>V10</th>';
						$table .= '<th>L11</th>';
						$table .= '<th>V11</th>';
						$table .= '<th>L12</th>';
						$table .= '<th>V12</th>';
						$table .= '<th>L13</th>';
						$table .= '<th>V13</th>';
						$table .= '<th>L14</th>';
						$table .= '<th>V14</th>';
						$table .= '<th>L15</th>';
						$table .= '<th>V15</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['DateFor'] . '</td>';
							$table .= '<td>' . $value['Acknowledge_date'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['L1'] . '</td>';
							$table .= '<td>' . $value['V1'] . '</td>';
							$table .= '<td>' . $value['L2'] . '</td>';
							$table .= '<td>' . $value['V2'] . '</td>';
							$table .= '<td>' . $value['L3'] . '</td>';
							$table .= '<td>' . $value['V3'] . '</td>';
							$table .= '<td>' . $value['L4'] . '</td>';
							$table .= '<td>' . $value['V4'] . '</td>';
							$table .= '<td>' . $value['L5'] . '</td>';
							$table .= '<td>' . $value['V5'] . '</td>';
							$table .= '<td>' . $value['L6'] . '</td>';
							$table .= '<td>' . $value['V6'] . '</td>';
							$table .= '<td>' . $value['L7'] . '</td>';
							$table .= '<td>' . $value['V7'] . '</td>';
							$table .= '<td>' . $value['L8'] . '</td>';
							$table .= '<td>' . $value['V8'] . '</td>';
							$table .= '<td>' . $value['L9'] . '</td>';
							$table .= '<td>' . $value['V9'] . '</td>';
							$table .= '<td>' . $value['L10'] . '</td>';
							$table .= '<td>' . $value['V10'] . '</td>';
							$table .= '<td>' . $value['L11'] . '</td>';
							$table .= '<td>' . $value['V11'] . '</td>';
							$table .= '<td>' . $value['L12'] . '</td>';
							$table .= '<td>' . $value['V12'] . '</td>';
							$table .= '<td>' . $value['L13'] . '</td>';
							$table .= '<td>' . $value['V13'] . '</td>';
							$table .= '<td>' . $value['L14'] . '</td>';
							$table .= '<td>' . $value['V14'] . '</td>';
							$table .= '<td>' . $value['L15'] . '</td>';
							$table .= '<td>' . $value['V15'] . '</td></tr>';
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