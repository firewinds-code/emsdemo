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
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else if (!($user_logid == 'CE12102224' || $user_logid == 'CE03070003' || $user_logid == 'CE01145570')) {
		die("access denied ! It seems like you try for a wrong action.");
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
	header("Location: $location");
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Report Consultancy Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Report Consultancy Master </h4>

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

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();

					$chk_task = $myDB->query('call rpt_consult_master("' . $date_From . '","' . $date_To . '")');
					$my_error = $myDB->getLastError();
					if (empty($my_error) && $chk_task) {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>Location</th>';
						$table .= '<th>Candidate Name</th>';
						$table .= '<th>Mobile</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Consultancy Name</th>';
						$table .= '<th>Created Date</th>';
						$table .= '<th>Status</th>';
						$table .= '<th>Final Status</th>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Tenure</th>';
						$table .= '<th>Tenure Date</th>';
						$table .= '<th>Payout</th>';
						$table .= '<th>Ems Status</th>';
						$table .= '<th>Disposition</th>';
						$table .= '<th>DOL</th>';
						$table .= '<th>Eligible</th>';
						$table .= '<th>Status</th>';
						$table .= '<th>Billing Month</th></tr></thead><tbody>';


						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['candidate_name'] . '</td>';
							$table .= '<td>' . $value['mobile'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['ConsultancyName'] . '</td>';
							$table .= '<td>' . $value['Created Date'] . '</td>';
							$table .= '<td>' . $value['Status'] . '</td>';
							$table .= '<td>' . $value['Final Status'] . '</td>';
							$table .= '<td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['Client'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['Sub_Process'] . '</td>';
							$table .= '<td>' . $value['Designation'] . '</td>';
							$table .= '<td>' . $value['Tenure'] . '</td>';
							$table .= '<td>' . $value['Tenure Date'] . '</td>';
							$table .= '<td>' . $value['Payout'] . '</td>';
							$table .= '<td>' . $value['Ems Status'] . '</td>';
							$table .= '<td>' . $value['disposition'] . '</td>';
							$table .= '<td>' . $value['dol'] . '</td>';
							$table .= '<td>' . $value['Eligible'] . '</td>';
							$table .= '<td>' . $value['Status'] . '</td>';
							$table .= '<td>' . $value['Billing Month'] . '</td></tr>';
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
	$(document).ready(function() {

		$('#btn_view').on('click', function() {
			var date1 = $('#txt_dateFrom').val();
			var date2 = $('#txt_dateTo').val();
			var validate = 0;
			if (new Date(date2) <= new Date(date1)) {
				alert('From date can not be greater than to date');
				validate = 1;
			} else {
				// end - start returns difference in milliseconds 
				var diff = new Date(new Date(date2) - new Date(date1));

				// get days
				var days = diff / 1000 / 60 / 60 / 24;
				if (days > 15) {
					alert('Time difference can not be greater than 15 days.');
					validate = 1;
				}
			}

			if (validate == 1) {
				return false;
			}


		});

	});

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