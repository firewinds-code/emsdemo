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
$dept = '';
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
			$date_To = $_POST['txt_dateTo'];
			$date_From = $_POST['txt_dateFrom'];
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
	<span id="PageTittle_span" class="hidden">Appraisal Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Appraisal Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

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


					<div class="input-field col s4 m4">
						<Select name="txt_dept" id="txt_dept" required>
							<?php
							if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_type'] == 'CENTRAL MIS' || $_SESSION['__user_type'] == 'HR' || ($_SESSION['__user_type'] == 'HR' &&  $_SESSION['__status_ah'] == $_SESSION['__user_logid'])) {

								$myDB = new MysqliDb();
								$rowData = $myDB->query('select distinct Process,cm_id from new_client_master;');
								if (count($rowData) > 0) {
									if ($dept == 'ALL') {
										echo '<option selected>ALL</option>';
									} else {
										echo '<option selected>ALL</option>';
									}

									foreach ($rowData as $key => $value) {
										if ($dept == $value['Process']) {
											echo '<option selected>' . $value['Process'] . '</option>';
										} else {
											echo '<option value=' . $value['cm_id'] . '>' . $value['Process'] . '</option>';
										}
									}
								}
							} else {
								$myDB = new MysqliDb();
								$rowData = $myDB->query('select distinct Process,cm_id from new_client_master where account_head="' . $_SESSION['__user_logid'] . '" or th="' . $_SESSION['__user_logid'] . '" or qh="' . $_SESSION['__user_logid'] . '" or oh="' . $_SESSION['__user_logid'] . '"');

								if (count($rowData) > 0) {
									if ($dept == 'ALL Process') {
										echo '<option selected>ALL Process</option>';
									} else {
										echo '<option>ALL Process</option>';
									}
									foreach ($rowData as $key => $value) {
										if ($dept == $value['Process']) {
											echo '<option selected>' . $value['Process'] . '</option>';
										} else {
											echo '<option value=' . $value['cm_id'] . '>' . $value['Process'] . '</option>';
										}
									}
								} else {
									echo '<option >' . $_SESSION['__user_process'] . '</option>';
								}
							}
							?>
						</Select>
					</div>


					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					echo 'call apr_getappraisalReport("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '")';
					$myDB = new MysqliDb();
					$chk_task = $myDB->query('call apr_getappraisalReport("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '")');
					//echo $sql='call apr_getappraisalReport("'.$_SESSION['__user_logid'].'","'.$date_From.'","'.$date_To.'")';
					$my_error = $myDB->getLastError();
					if (empty($my_error)) {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>Employee Id</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Warning Letter</th>';
						$table .= '<th>Q.3 What are the current responsibilities held by you?</th>';
						$table .= '<th>Q.2 What do you consider your important achievements of the past year?</th>';
						$table .= '<th>Q.3 What are your goals for the next year?</th>';
						$table .= '<th>Q.4 What are your areas of improvement?</th>';
						$table .= '<th>Q.6 IF YOU NEED ANY SUPPORT OR TRANING ( RELATED TO JOB PROFILE) FROM THE SIDE OF ORIGNATION</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>CMID</th>';
						$table .= '<th>Account Head</th>';
						$table .= '<th>Report To</th>';
						$table .= '<th>Rating PMS</th>';
						$table .= '<th>Rating Managr</th>';
						$table .= '<th>Rating AH</th>';
						$table .= '<th>Promotion Recomend</th>';
						$table .= '<th>Promotion Post</th>';
						$table .= '<th>AH Status</th>';
						$table .= '<th>Rating HR</th>';
						$table .= '<th>HR Status</th>';
						$table .= '<th>Hold(Month)</th>';
						$table .= '<th>Meeting job requirements on a timely basis(Applicant)</th>';
						$table .= '<th>Knowledge of job(Applicant)</th>';
						$table .= '<th>Communication skills(Applicant)</th>';
						$table .= '<th>Interpersonal skills(Applicant)</th>';
						$table .= '<th>Initiative and creativity(Applicant)</th>';
						$table .= '<th>Decision making ability(Applicant)</th>';
						$table .= '<th>Adaptability and Flexibility(Applicant)</th>';
						$table .= '<th>Team work(Applicant)</th>';
						$table .= '<th>Time management(Applicant)</th>';
						$table .= '<th>Problem solving skills(Applicant)</th>';
						$table .= '<th>Meeting job requirements on a timely basis(Evaluators)</th>';
						$table .= '<th>Knowledge of job(Evaluators)</th>';
						$table .= '<th>Communication skills(Evaluators)</th>';
						$table .= '<th>Interpersonal skills(Evaluators)</th>';
						$table .= '<th>Initiative and creativity(Evaluators)</th>';
						$table .= '<th>Decision making ability(Evaluators)</th>';
						$table .= '<th>Adaptability and Flexibility(Evaluators)</th>';
						$table .= '<th>Team work(Evaluators)</th>';
						$table .= '<th>Time management(Evaluators)</th>';
						$table .= '<th>Problem solving skills(Evaluators)</th>';
						$table .= '<th>Meeting job requirements on a timely basis(Approver)</th>';
						$table .= '<th>Knowledge of job(Approver)</th>';
						$table .= '<th>Communication skills(Approver)</th>';
						$table .= '<th>Interpersonal skills(Approver)</th>';
						$table .= '<th>Initiative and creativity(Approver)</th>';
						$table .= '<th>Decision making ability(Approver)</th>';
						$table .= '<th>Adaptability and Flexibility(Approver)</th>';
						$table .= '<th>Team work(Approver)</th>';
						$table .= '<th>Time management(Approver)</th>';
						$table .= '<th>Problem solving skills(Approver)</th>';
						$table .= '<th>Meeting job requirements on a timely basis(Average)</th>';
						$table .= '<th>Knowledge of job(Average)</th>';
						$table .= '<th>Communication skills(Average)</th>';
						$table .= '<th>Interpersonal skills(Average)</th>';
						$table .= '<th>Initiative and creativity(Average)</th>';
						$table .= '<th>Decision making ability(Average)</th>';
						$table .= '<th>Adaptability and Flexibility(Average)</th>';
						$table .= '<th>Team work(Average)</th>';
						$table .= '<th>Time management(Average)</th>';
						$table .= '<th>Problem solving skills(Average)</th>';
						$table .= '<th>Comments</th></tr><thead><tbody>';
						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeId'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Doj'] . '</td>';
							$table .= '<td>' . $value['WarningLetter'] . '</td>';
							$table .= '<td>' . $value['Q1'] . '</td>';
							$table .= '<td>' . $value['Q2'] . '</td>';
							$table .= '<td>' . $value['Q3'] . '</td>';
							$table .= '<td>' . $value['Q4'] . '</td>';
							$table .= '<td>' . $value['Q6'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['SubProcess'] . '</td>';
							$table .= '<td>' . $value['Cm_id'] . '</td>';
							$table .= '<td>' . $value['AH'] . '</td>';
							$table .= '<td>' . $value['ReportTo'] . '</td>';
							$table .= '<td>' . $value['RatingPerPMS'] . '</td>';
							$table .= '<td>' . $value['RatingManagr'] . '</td>';
							$table .= '<td>' . $value['RatingAH'] . '</td>';
							$table .= '<td>' . $value['PromotionRecomend'] . '</td>';
							$table .= '<td>' . $value['PromotionPost'] . '</td>';
							$table .= '<td>' . $value['AHStatus'] . '</td>';
							$table .= '<td>' . $value['RatingHR'] . '</td>';
							$table .= '<td>' . $value['HRStatus'] . '</td>';
							$table .= '<td>' . $value['HoldForMonth'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_1'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_2'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_3'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_4'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_5'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_6'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_7'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_8'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_9'] . '</td>';
							$table .= '<td>' . $value['ApplicantScore5_10'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_1'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_2'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_3'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_4'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_5'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_6'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_7'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_8'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_9'] . '</td>';
							$table .= '<td>' . $value['EvaluatorsScore5_10'] . '</td>';
							$table .= '<td>' . $value['HRScore5_1'] . '</td>';
							$table .= '<td>' . $value['HRScore5_2'] . '</td>';
							$table .= '<td>' . $value['HRScore5_3'] . '</td>';
							$table .= '<td>' . $value['HRScore5_4'] . '</td>';
							$table .= '<td>' . $value['HRScore5_5'] . '</td>';
							$table .= '<td>' . $value['HRScore5_6'] . '</td>';
							$table .= '<td>' . $value['HRScore5_7'] . '</td>';
							$table .= '<td>' . $value['HRScore5_8'] . '</td>';
							$table .= '<td>' . $value['HRScore5_9'] . '</td>';
							$table .= '<td>' . $value['HRScore5_10'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_1'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_2'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_3'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_4'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_5'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_6'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_7'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_8'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_9'] . '</td>';
							$table .= '<td>' . $value['AVGScore5_10'] . '</td>';
							$table .= '<td>' . $value['Comments'] . '</td></tr>';
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