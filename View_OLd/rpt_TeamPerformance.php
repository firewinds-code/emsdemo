<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	$clean_user_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_logid)) {
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
?>

<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/



		// DataTable
		var table = $('#myTable').DataTable({
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
			"sScrollY": "192",
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});;
		$('#txt_Subproc, #txt_process').keyup(function() {
			table.draw();
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>


<style>
	.dataTables_scrollHead {
		height: 80px;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Team Performance Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Team Performance Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div id="pnlTable">
					<?php

					$select_emp = "select * from (select performance_data.* from performance_data inner join status_table on status_table.EmployeeID = performance_data.EmployeeID inner join employee_map on employee_map.EmployeeID = performance_data.EmployeeID  and emp_status = 'Active' where (status_table.ReportTo = ? or status_table.Qa_ops = ?) and performance_data.Type ='Detail'   order by DateFor desc,EmployeeID ) performance_data group by EmployeeID";

					$selectQury = $conn->prepare($select_emp);
					$clean_user_logid = clean($_SESSION['__user_logid']);
					$selectQury->bind_param("ss", $clean_user_logid, $clean_user_logid);
					$selectQury->execute();
					$dt_Test = $selectQury->get_result();
					if ($dt_Test->num_rows > 0 && $dt_Test) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead>';
						$table .= '<tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>Team Lead (Report To)</th>';
						for ($j = 1; $j <= 15; $j++) {
							$table .= '<th>Lable</th>';
							$table .= '<th>Value</th>';
						}
						$table .= '</tr>';
						$table .= '</thead><tbody>';
						foreach ($dt_Test as $key => $value) {
							$date_on = date('Y-m-d', time());
							$monday = '';
							if (strtolower(date('l', strtotime($date_on))) == 'monday') {
								$monday =  date('Y-m-d', strtotime($date_on));
							} else {
								$monday =  date('Y-m-d', strtotime($date_on . ' last monday'));
							}
							$datecurrent = date('Y-m-d', strtotime($monday . ' +7 days'));

							$datelast_mon = date('Y-m-d', strtotime($monday . ' -7 days'));
							$datelast = date('Y-m-d', strtotime($monday . ' -1 days'));

							$table .= '<tr>';
							$table .= '<td>' . $value['EmployeeID'] . '</td>';
							$clean_EmployeeID = clean($value['EmployeeID']);
							$query = "SELECT pd1.EmployeeName,status_table.ReportTo,pd2.EmployeeName as ReportToName FROM status_table inner join personal_details pd1 on pd1.EmployeeID  = status_table.EmployeeID inner join personal_details pd2 on pd2.EmployeeID  = status_table.ReportTo where status_table.EmployeeID = ?";
							$selectQuy = $conn->prepare($query);
							$selectQuy->bind_param("s", $clean_EmployeeID);
							$selectQuy->execute();
							$data = $selectQuy->get_result();
							$data_emp = $data->fetch_row();
							if (!empty(clean($data_emp[0]))) {
								$table .= '<td>' . clean($data_emp[0]) . '</td>';
							} else {
								$table .= '<td></td>';
							}
							if (!empty(clean($data_emp[2]))) {
								$table .= '<td>' . clean($data_emp[2]) . '</td>';
							} else {
								$table .= '<td></td>';
							}

							for ($j = 1; $j <= 15; $j++) {
								$table .= '<td>' . $value['L' . $j] . '</td>';
								$table .= '<td>' . $value['V' . $j] . '</td>';
							}
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.info('No Data Found.'); }); </script>";
					}
					?>

				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>