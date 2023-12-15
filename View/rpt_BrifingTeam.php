<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

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
			"iDisplayLength": 25,
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
	<span id="PageTittle_span" class="hidden">Team Briefing Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Team Briefing Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div id="pnlTable">



					<?php
					$myDB = new MysqliDb();
					$dt_Test = $myDB->query("select distinct EmployeeID from status_table where (status_table.ReportTo = '" . $_SESSION['__user_logid'] . "' or status_table.Qa_ops = '" . $_SESSION['__user_logid'] . "')");
					if (count($dt_Test) > 0 && $dt_Test) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border centered" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<tr>';
						$table .= '<th rowspan = "2" style="text-align:center">EmployeeID</th>';
						$table .= '<th rowspan = "2" style="text-align:center">Employee Name</th>';
						$table .= '<th rowspan = "2" style="text-align:center">Team Lead (Report To)</th>';
						$table .= '<th colspan="3">Last Week</th>';
						$table .= '<th colspan="3">Current Week</th>';
						$table .= '<th colspan="3">MTD</th>';
						$table .= '</tr>';

						$table .= '<th>Total Question</th>';
						$table .= '<th>Correct Answer</th>';
						$table .= '<th>Briefing Score</th>';


						$table .= '<th>Total Question</th>';
						$table .= '<th>Correct Answer</th>';
						$table .= '<th>Briefing Score</th>';

						$table .= '<th>Total Question</th>';
						$table .= '<th>Correct Answer</th>';
						$table .= '<th>Briefing Score</th>';

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
							$db_in = new MysqliDb();
							$data_emp = $db_in->query("SELECT pd1.EmployeeName,status_table.ReportTo,pd2.EmployeeName as ReportToName FROM status_table inner join personal_details pd1 on pd1.EmployeeID  = status_table.EmployeeID inner join personal_details pd2 on pd2.EmployeeID  = status_table.ReportTo where status_table.EmployeeID = '" . $value['EmployeeID'] . "'");

							if (!empty($data_emp[0]['EmployeeName'])) {
								$table .= '<td>' . $data_emp[0]['EmployeeName'] . '</td>';
							} else {
								$table .= '<td></td>';
							}

							if (!empty($data_emp[0]['ReportToName'])) {
								$table .= '<td>' . $data_emp[0]['ReportToName'] . '</td>';
							} else {
								$table .= '<td></td>';
							}

							$myDB = new MysqliDb();
							$query_i = $myDB->query('select sum(brf_quiz_attempted.Answer = brf_question.Answer) as sum,count(*) as count from brf_question  inner join brf_quiz_attempted on brf_quiz_attempted.BriefingID = brf_question.BriefingID and  brf_quiz_attempted.QuestionId = brf_question.QuestionId where brf_quiz_attempted.EmployeeID = "' . $value['EmployeeID'] . '" and brf_quiz_attempted.AttemptedDate  between "' . $datelast_mon . '" and "' . $datelast . '" group by brf_quiz_attempted.BriefingID');
							$prev_qsn = 0;
							$prev_correct_asn = 0;
							$prev_score = 0;
							$prev_avg_score = 0;
							if (count($query_i) > 0 && $query_i) {
								foreach ($query_i as $qsn => $data_i) {

									$prev_qsn = $prev_qsn + $data_i['count'];
									$prev_correct_asn = $prev_correct_asn + $data_i['sum'];
								}
								$prev_avg_score = round(($prev_correct_asn * 100) / $prev_qsn, 2);
							}
							$table .= '<td>' . $prev_qsn . '</td>';
							$table .= '<td>' . $prev_correct_asn . '</td>';
							$table .= '<td>' . $prev_avg_score . ' %</td>';

							$myDB = new MysqliDb();

							$query_i = $myDB->query('select sum(brf_quiz_attempted.Answer = brf_question.Answer) as sum,count(*) as count from brf_question  inner join brf_quiz_attempted on brf_quiz_attempted.BriefingID = brf_question.BriefingID and  brf_quiz_attempted.QuestionId = brf_question.QuestionId where brf_quiz_attempted.EmployeeID = "' . $value['EmployeeID'] . '" and brf_quiz_attempted.AttemptedDate  between "' . $monday . '" and "' . $datecurrent . '" group by brf_quiz_attempted.BriefingID');
							$_qsn = 0;
							$_correct_asn = 0;
							$_score = 0;
							$_avg_score = 0;
							if (count($query_i) > 0 && $query_i) {
								foreach ($query_i as $qsn => $data_i) {

									$_qsn = $_qsn + $data_i['count'];
									$_correct_asn = $_correct_asn + $data_i['sum'];
								}
								$_avg_score = round(($_correct_asn * 100) / $_qsn, 2);
							}
							$table .= '<td>' . $_qsn . '</td>';
							$table .= '<td>' . $_correct_asn . '</td>';
							$table .= '<td>' . $_avg_score . ' %</td>';



							$myDB = new MysqliDb();
							$query_i = $myDB->query('select sum(brf_quiz_attempted.Answer = brf_question.Answer) as sum,count(*) as count from brf_question  inner join brf_quiz_attempted on brf_quiz_attempted.BriefingID = brf_question.BriefingID and  brf_quiz_attempted.QuestionId = brf_question.QuestionId where brf_quiz_attempted.EmployeeID = "' . $value['EmployeeID'] . '" and month(brf_quiz_attempted.AttemptedDate) =  month("' . $date_on . '") group by brf_quiz_attempted.BriefingID');

							$mtd_qsn = 0;
							$mtd_correct_asn = 0;
							$_score = 0;
							$mtd_avg_score = 0;
							if (count($query_i) > 0 && $query_i) {
								foreach ($query_i as $qsn => $data_i) {

									$mtd_qsn = $mtd_qsn + $data_i['count'];
									$mtd_correct_asn = $mtd_correct_asn + $data_i['sum'];
								}
								$mtd_avg_score = round(($mtd_correct_asn * 100) / $mtd_qsn, 2);
							}

							$table .= '<td>' . $mtd_qsn . '</td>';
							$table .= '<td>' . $mtd_correct_asn . '</td>';
							$table .= '<td>' . $mtd_avg_score . ' %</td>';


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