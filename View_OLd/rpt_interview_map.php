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
$user_logid = clean($_SESSION['__user_logid']);
$user_type = clean($_SESSION['__user_type']);
if (isset($_SESSION) && $user_type == 'ADMINISTRATOR' ||  $user_logid == 'CE01145570' ||  $user_logid == 'CE081930104') {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
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
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Interview Map Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Interview Map Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<script>
					$(function() {
						$('#txt_dateFrom,#txt_dateTo').datetimepicker({
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
					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>

					<div class="input-field col s4 m4 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>

					</div>
				</div>
				<?php
				//if(isset($_POST['btn_view']))
				//{
				$myDB = new MysqliDb();
				$conn = $myDB->dbConnect();
				//echo 'select a.EmployeeID, a.dateofjoin,a.emp_status,b.primary_language,b.INTID,b.EmployeeName from employee_map a inner join  personal_details b on a.EmployeeID=b.EmployeeID where dateofjoin between "'.$date_From.'" and "'.$date_To.'"';
				$sql = 'select a.EmployeeID, a.dateofjoin,a.emp_status,b.primary_language,b.INTID,b.EmployeeName from employee_map a inner join  personal_details b on a.EmployeeID=b.EmployeeID where dateofjoin between ? and ?';
				$selectQ = $conn->prepare($sql);
				$selectQ->bind_param("ss", $date_From, $date_To);
				$selectQ->execute();
				$chk_task = $selectQ->get_result();
				if ($chk_task->num_rows > 0) {

					$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;">';
					$table .= '<div class="panel-body">';
					$table .= '<table id="myTable" class="data dataTable no-footer row-border"   style="width:100%;"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>InterviewID</th>';
					$table .= '<th>EmpName</th>';
					$table .= '<th>Date of Join</th>';
					$table .= '<th>Primary Language</th>';
					$table .= '<th>Employee Status</th>';

					$table .= '</thead></tr>';
					$table .= '<tbody>';
					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['INTID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['dateofjoin'] . '</td>';
						$table .= '<td>' . $value['primary_language'] . '</td>';
						$table .= '<td>' . $value['emp_status'] . '</td>';
						$table .= '</tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
				}
				//	}

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

	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>