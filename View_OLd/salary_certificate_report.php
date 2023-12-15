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
$myDB = new MysqliDb();
$connn = $myDB->dbConnect();

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$date_From = cleanUserInput($_POST['txt_dateFrom']);
	$date_To = cleanUserInput($_POST['txt_dateTo']);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Salary Certificate Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Salary Certificate Report</h4>

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
							format: 'Y-m-d',
							maxDate: '0',
							scrollInput: false
						});
						$('#myTable').DataTable({
							dom: 'Bfrtip',
							lengthMenu: [
								[25, 50, -1],
								['25 rows', '50 rows', 'Show all']
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
							"sScrollY": "100%",
							"sScrollX": "100%",
							"bScrollCollapse": false,
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
						<span>Date From</span>
						<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<span>Date To</span>
						<input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>
					</div>
				</div>
				<?php
				if (isset($_POST['btn_view'])) {


					//$sqlConnect = "select EmployeeID,month, reason, per_email_id from salary_certificate_report where created_at between '" . $date_From . "' and '" . $date_To . "'";
					$sqlConnect = "select distinct(cr.EmployeeID),c.EmpName,c.client,c.process,c.sub_process, cr.reason,cr.month,cr.created_at,cr.per_email_id from salary_certificate_report cr left Join salary_certificate c on c.Empid=cr.EmployeeID where created_at between ? and ? order by created_at";
					$stmt = $connn->prepare($sqlConnect);
					$stmt->bind_param("ss", $date_From, $date_To);
					$stmt->execute();

					$result = $stmt->get_result();
					$count = $result->num_rows;

					if ($result->num_rows > 0) {

						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Email_ID</th>';
						$table .= '<th>Month</th>';
						$table .= '<th>Reason</th>';
						$table .= '<th>Date</th>';
						$table .= '<th>Time</th><thead><tbody>';

						foreach ($result as $key => $value) {
							$date = date_create($value['created_at']);
							$Date = date_format($date, "d-M-y");
							$Time = date_format($date, "H:i:s");
							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmpName'] . '</td>';
							$table .= '<td>' . $value['client'] . '</td>';
							$table .= '<td>' . $value['process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['per_email_id'] . '</td>';
							$table .= '<td>' . $value['month'] . '</td>';
							$table .= '<td>' . $value['reason'] . '</td>';
							$table .= '<td>' . $Date . '</td>';
							$table .= '<td>' . $Time . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
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


	$(document).ready(function() {

		$('#btn_view').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#txt_dateFrom').val() == '') {
				$('#txt_dateFrom').addClass('has-error');
				if ($('#spantxt_dateFrom').length == 0) {
					$('<span id="spantxt_dateFrom" class="help-block"></span>').insertAfter('#txt_dateFrom');
				}
				$('#spantxt_dateFrom').html('Required');
				validate = 1;
			}
			if ($('#txt_dateTo').val() == '') {
				$('#txt_dateTo').addClass('has-error');
				if ($('#spantxt_dateTo').length == 0) {
					$('<span id="spantxt_dateTo" class="help-block"></span>').insertAfter('#txt_dateTo');
				}
				$('#spantxt_dateTo').html('Required');
				validate = 1;
			}


			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});

	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>