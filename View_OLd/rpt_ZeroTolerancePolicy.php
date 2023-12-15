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
$query = 'select id,location from location_master';
$location_array = array();
$result = $myDB->query($query);
foreach ($result as $lval) {
	$location_array[$lval['id']] = $lval['location'];
}

$client_id = clean($_SESSION['__user_client_ID']);
$user_logid = clean($_SESSION['__user_logid']);
$status_ah = clean($_SESSION['__status_ah']);
if (!isset($user_logid) || $client_id != '40' || $status_ah == '' || ($user_logid != $status_ah)) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Zero Tolerance Policy Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Zero Tolerance Policy Report </h4>

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

				<?php
				//if(isset($_POST['btn_view']))
				//{
				//	$sqlConnect="select a.EmployeeID, b.EmployeeName,b.location, a.ack_date from zerotolerancepolicy_ack a Inner Join personal_details b on a.EmployeeID=b.EmployeeID";
				$statusah = clean($_SESSION['__status_ah']);
				$sqlConnect = "select a.EmployeeID, b.EmployeeName,b.location ,a.ack_date from zerotolerancepolicy_ack a Inner Join whole_details_peremp b on a.EmployeeID=b.EmployeeID and b.account_head=? and b.client_name='40';";

				$selectQ = $conn->prepare($sqlConnect);
				$selectQ->bind_param("s", $statusah);
				$selectQ->execute();
				$result = $selectQ->get_result();
				// $myDB = new MysqliDb();
				// $result = $myDB->query($sqlConnect);
				if ($result->num_rows > 0) {
				?>
					<table id="myTable" class="data dataTable no-footer row-border" style="width:100%;">

						<thead>
							<tr>
								<th>EmployeeID </th>
								<th>Employee Name</th>
								<th>Acknowledge Date</th>
								<th>Location</th>

							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($result as $key => $value) {
								$lcation_name = '';
								if ($value['location'] != "") {
									$lcation_name = $location_array[$value['location']];
								}
								echo '<tr>';
								echo '<td class="test_name">' . $value['EmployeeID'] . '</td>';
								echo '<td class="test_name">' . $value['EmployeeName'] . '</td>';
								echo '<td class="test_name">' . $value['ack_date'] . '</td>';
								echo '<td class="test_name">' . $lcation_name . '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
			</div>
		</div>
	<?php
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

	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>