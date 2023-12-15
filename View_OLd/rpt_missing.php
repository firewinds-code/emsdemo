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

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logID = clean($_SESSION['__user_logid']);
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	if (!isset($user_logID)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
$DateTo = '';
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	if (!empty($_POST['txt_dateFor'])) {
		$DateTo = date('Y-m-d', strtotime('-1 days ' . $_POST['txt_dateFor']));
	} else {
		$DateTo = date('Y-m-d', strtotime("yesterday"));
	}
}
?>

<script>
	$(function() {
		$('#txt_dateFor').datetimepicker({
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
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 25,
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
	<span id="PageTittle_span" class="hidden">Missing APR Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Missing APR Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s4 m4">
					<input type="text" name="txt_dateFor" id="txt_dateFor" value="<?php echo date('Y-m-d', strtotime('+1 days ' . $DateTo)); ?>" />
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<?php
				if (true) {
					if (empty($DateTo)) {
						$DateTo = date('Y-m-d', strtotime("yesterday"));
					}
					$month = intval(date('m', strtotime($DateTo)));
					$year = intval(date('Y', strtotime($DateTo)));
					$sqlBy = "select t1.EmployeeID, t1.D" . intval(date('d', strtotime($DateTo))) . " as Attendance,t2.InTime,t2.OutTime,t3.InTime as RInTime,t3.OutTime as ROutTime,t4.D" . intval(date('d', strtotime($DateTo))) . " apr from calc_atnd_master t1 left outer join bioinout t2 on t1.EmployeeID=t2.EmpID left outer join roster_temp t3 on t1.EmployeeID=t3.EmployeeID left outer join hours_hlp t4 on t1.EmployeeID=t4.EmployeeID where t1.Month=? and t1.year=? and (t1.D" . intval(date('d', strtotime($DateTo))) . "='A') and t2.DateOn=? and (t2.InTime is not null and t2.OutTime is not null) and t3.DateOn=? and t3.InTime !='WO' and t2.OutTime-t2.InTime!=0 and t4.Month=? and t4.year=?";

					$selectQury = $conn->prepare($sqlBy);
					$selectQury->bind_param("iissii", $month, $year, $DateTo, $DateTo, $month, $year);
					$selectQury->execute();
					$chk_task = $selectQury->get_result();
					// print_r($chk_task);
					// $chk_task = $myDB->rawQuery($sqlBy);
					// $mysql_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Date</th>';
						$table .= '<th>Attendance</th>';

						$table .= '<th>InTime</th>';
						$table .= '<th>OutTime</th>';

						$table .= '<th>Roster InTime</th>';
						$table .= '<th>Roster OutTime</th>';
						$table .= '<th>APR</th>';

						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$table .= '<tr>';
							$table .= '<td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $DateTo . '</td>';
							$table .= '<td>' . $value['Attendance'] . '</td>';
							$table .= '<td>' . $value['InTime'] . '</td>';
							$table .= '<td>' . $value['OutTime'] . '</td>';
							$table .= '<td>' . $value['RInTime'] . '</td>';
							$table .= '<td>' . $value['ROutTime'] . '</td>';
							$table .= '<td>' . $value['apr'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
					}
				}
				?>
				<!--Reprot / Data Table End -->
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