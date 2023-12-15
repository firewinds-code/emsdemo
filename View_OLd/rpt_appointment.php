<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');
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

function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Appointment Letter Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Appointment Letter Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<script>
					$(function() {

						$('#myTable').DataTable({
							dom: 'Bfrtip',
							lengthMenu: [
								[50, -1],
								['50 rows', 'Show all']
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
							"bAutoWidth": "100%",
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


				<?php

				$myDB = new MysqliDb();
				$chk_task = $myDB->query('call rpt_appointment()');
				$my_error = $myDB->getLastError();
				if (empty($my_error)) {
					$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';
					$table .= '<th>Validate Time</th>';
					$table .= '<th>Status</th><thead><tbody>';

					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['validatetime'] . '</td>';
						$table .= '<td>' . $value['Status'] . '</td></tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
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