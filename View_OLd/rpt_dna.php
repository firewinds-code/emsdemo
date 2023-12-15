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
error_reporting(0);
$empID = '';
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$show = ' hidden';
$link = $btn_view = $btn_view1 = $alert_msg = '';

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$btn_add = cleanUserInput($_POST['btn_add']);
	$txtEmployeeID = cleanUserInput($_POST['txtEmployeeID']);
	$txtMonth = cleanUserInput($_POST['txtMonth']);
	$txtYear = cleanUserInput($_POST['txtYear']);
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$("#myTable .text-danger").css('color', 'red');
		$("#myTable .text-success").css('color', 'green');
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
			"iDisplayLength": 25,
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


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Report Attendance DNA</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Report Attendance DNA</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s4 m4">
					<select name="txtEmployeeID" id="txtEmployeeID">
						<?php
						$myDB = new MysqliDb();
						$result = $myDB->query('select EmployeeID,EmployeeName,des_id from whole_details_peremp order by EmployeeName');
						if (count($result) > 0) {
							foreach ($result as $key => $val) {
								echo '<option value="' . $val['EmployeeID'] . '" data_desid="' . $val['des_id'] . '">' . $val['EmployeeName'] . '( ' . $val['EmployeeID'] . ' )</option>';
							}
						}
						?>
					</select>
					<label for="txtEmployeeID" class="active-drop-down active">Employee</label>
				</div>
				<input type="hidden" name="txt_desid" id="txt_desid" value="" />
				<div class="input-field col s4 m4">
					<select name="txtMonth" id="txtMonth">
						<option value='1'>JAN</option>
						<option value='2'>FEB</option>
						<option value='3'>MAR</option>
						<option value='4'>APR</option>
						<option value='5'>MAY</option>
						<option value='6'>JUN</option>
						<option value='7'>JUL</option>
						<option value='8'>AUG</option>
						<option value='9'>SEP</option>
						<option value='10'>OCT</option>
						<option value='11'>NOV</option>
						<option value='12'>DEC</option>
					</select>
					<label for="txtMonth" class="active-drop-down active">Month</label>
				</div>
				<div class="input-field col s4 m4">
					<select name="txtYear" id="txtYear">
						<option value='2017'>2017</option>
						<option value='2018'>2018</option>
						<option value='2019'>2019</option>
						<option value='2020'>2020</option>
						<option value='2021'>2021</option>
					</select>
					<label for="txtYear" class="active-drop-down active">Year</label>
				</div>
				<div class="input-field col s12 m12 right-align">
					<button type="submit" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green" name="btn_add" id="btn_add"> Submit </button>
				</div>

				<div id="pnlTable">
					<?php
					if (isset($_POST['btn_add'])) {


						if ($btn_add  == '9' || $btn_add == '12') {
							$str = 'call atnddna1("' . $txtEmployeeID . '","' . $txtMonth . '","' . $txtYear . '")';
							$myDB = new MysqliDb();
							$chk_task = $myDB->query($str);
							$my_error = $myDB->getLastError();
							if (count($chk_task) > 0 && $chk_task) {
								$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
								$table .= '<th>EmployeeID</th>';
								$table .= '<th>DateOn</th>';
								$table .= '<th>InTime</th>';
								$table .= '<th>OutTime</th>';
								$table .= '<th>bInTime</th>';
								$table .= '<th>bOutTime</th>';
								$table .= '<th>Attendance</th>';
								$table .= '<th>Exception</th>';
								$table .= '<th>LeaveStatus</th>';

								$table .= '<th>LeaveReason</th>';
								$table .= '<th>APR</th>';
								$table .= '<th>DownTime</th>';
								$table .= '<th>DTStatus</th>';
								$table .= '<thead><tbody>';

								foreach ($chk_task as $key => $value) {
									$table .= '<td>' . $value['EmployeeID'] . '</td>';
									$table .= '<td>' . $value['EmployeeID'] . '</td>';
									$table .= '<td>' . $value['EmployeeID'] . '</td>';
									$table .= '<td>' . $value['DateOn'] . '</td>';
									$table .= '<td>' . $value['InTime'] . '</td>';
									$table .= '<td>' . $value['OutTime'] . '</td>';
									$table .= '<td>' . $value['bInTime'] . '</td>';
									$table .= '<td>' . $value['bOutTime'] . '</td>';
									$table .= '<td>' . $value['Attendance'] . '</td>';
									$table .= '<td>' . $value['Exception'] . '</td>';
									$table .= '<td>' . $value['LeaveStatus'] . '</td>';
									$table .= '<td>' . $value['ReasonofLeave'] . '</td>';
									$table .= '<td>' . $value['APR'] . '</td>';
									$table .= '<td>' . $value['DownTime'] . '</td>';
									$table .= '<td>' . $value['DTStatus'] . '</td></tr>';
								}
								$table .= '</tbody></table></div></div>';
								echo $table;
							} else {
								echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
							}
						} else {
							$str = 'call atnddna2("' . $txtEmployeeID . '","' . $txtMonth . '","' . $txtYear . '")';
							$myDB = new MysqliDb();
							$chk_task = $myDB->query($str);
							$my_error = $myDB->getLastError();
							if (count($chk_task) > 0 && $chk_task) {
								$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
											  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									        <thead><tr>';
								$table .= '<th>EmployeeID</th>';
								$table .= '<th>DateOn</th>';
								$table .= '<th>InTime</th>';
								$table .= '<th>OutTime</th>';
								$table .= '<th>bInTime</th>';
								$table .= '<th>bOutTime</th>';
								$table .= '<th>Attendance</th>';
								$table .= '<th>Exception</th>';
								$table .= '<th>LeaveStatus</th>';
								$table .= '<th>LeaveReason</th>';
								$table .= '<thead><tbody>';
								foreach ($chk_task as $key => $value) {
									$table .= '<td>' . $value['EmployeeID'] . '</td>';
									$table .= '<td>' . $value['DateOn'] . '</td>';
									$table .= '<td>' . $value['InTime'] . '</td>';
									$table .= '<td>' . $value['OutTime'] . '</td>';
									$table .= '<td>' . $value['bInTime'] . '</td>';
									$table .= '<td>' . $value['bOutTime'] . '</td>';
									$table .= '<td>' . $value['Attendance'] . '</td>';
									$table .= '<td>' . $value['Exception'] . '</td>';
									$table .= '<td>' . $value['LeaveStatus'] . '</td>';
									$table .= '<td>' . $value[0]['ReasonofLeave'] . '</td></tr>';
								}
								$table .= '</tbody></table></div></div>';
								echo $table;
							} else {
								echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
							}
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
	$(function() {
		$('#btn_add').click(function() {
			var validate = 0;
			$('#txtCardNo').closest('div').removeClass('has-error');
			$('#txtSiteNo').closest('div').removeClass('has-error');

			if ($('#txtCardNo').val() == '') {
				$('#txtCardNo').closest('div').addClass('has-error');
				validate = 1;

			}

			if ($('#txtSiteNo').val() == '') {
				$('#txtSiteNo').closest('div').addClass('has-error');
				validate = 1;

			}

			if (validate == 1) {

				return false;
			}
		});
		$('#txtEmployeeID').change(function() {
			$('#txt_desid').val($("#txtEmployeeID option:selected").attr('data_desid'));

		});
	});
</script>