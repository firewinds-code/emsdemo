<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Global variable used in Page Cycle
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action

?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}
				/*,'copy'*/
				,
				'pageLength'

			]
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
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Dynamic Report</span>

	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Dynamic Report</h4>
			<div class="schema-form-section row">

				<div id="pnlTable">
					<?php
					//$sqlConnect = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
					$sqlConnect = "SELECT EmpID,EmployeeName,report_name,l1.location,c.client_name,process,sub_process ,t1.Createdon from report_map t1 left join report_master t2 on t1.reportID=t2.id left join new_client_master nc on t1.processID=nc.cm_id left join client_master c on nc.client_name=c.client_id left join personal_details p on t1.EmpID=p.EmployeeID left join location_master l1 on l1.id=nc.location;";
					// $myDB = new MysqliDb();
					// $result = $myDB->rawQuery($sqlConnect);
					$stmt = $conn->prepare($sqlConnect);
					// $stmt->bind_param("s", $loc);
					$stmt->execute();
					$result = $stmt->get_result();
					$count = $result->num_rows;
					$mysql_error = $myDB->getLastError();
					if ($result->num_rows > 0) {


						// if (empty($mysql_error)) { 
					?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Employee ID</th>
									<th>Employee Name</th>
									<th>Report Name</th>
									<th>Location</th>
									<th>Client</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Created On</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="EmpID">' . $value['EmpID'] . '</td>';
									echo '<td class="EmployeeName">' . $value['EmployeeName'] . '</td>';
									echo '<td class="report_name">' . $value['report_name'] . '</td>';
									echo '<td class="location">' . $value['location'] . '</td>';
									echo '<td class="client_name">' . $value['client_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="Createdon">' . $value['Createdon'] . '</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php }  ?>
				</div>
			</div>
		</div>
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		//Model Assigned and initiation code on document load


	});


	// This code for trigger edit on all data table also trigger model open on a Model ID
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>