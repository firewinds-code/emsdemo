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
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

$userType = clean($_SESSION['__user_type']);
$EmployeeID = clean($_SESSION['__user_logid']);
if ($userType == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224') {
	// proceed further
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$date_From = cleanUserInput($_POST['txt_dateFrom']);
	$date_To = cleanUserInput($_POST['txt_dateTo']);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Transfer Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Transfer Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<script>
					$(function() {
						$('#txt_dateFrom,#txt_dateTo').datetimepicker({
							timepicker: false,
							format: 'Y-m-d',
							maxDate: '0',
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
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<?php
				$sql_btn_view = isset($_POST['btn_view']);
				if ($sql_btn_view) {

					// $date_From = cleanUserInput($_POST['txt_dateFrom']);
					// $date_To = cleanUserInput($_POST['txt_dateTo']);
					$sqlConnect = "select t.EmployeeID, l.location,c.client_name, t.process, n.sub_process, t.reports_to from transfer_emp as t join location_master as l on l.id=t.location join client_master as c on c.client_id=t.client_name join new_client_master as n on n.cm_id=t.sub_process  where transfer_date between ? and ?";
					$sql = $conn->prepare($sqlConnect);
					$sql->bind_param("ss", $date_From, $date_To);
					$sql->execute();
					$result = $sql->get_result();
					$count = $result->num_rows;
					// echo ($sqlConnect);
					// die;
					if ($result->num_rows > 0) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                	<div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Location</th>';
						$table .= '<th>Client Name</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Report To</th><thead><tbody>';


						foreach ($result as $key => $value) {
							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['client_name'] . '</td>';
							$table .= '<td>' . $value['process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['reports_to'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
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