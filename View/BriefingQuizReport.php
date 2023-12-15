<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
if (isset($_SESSION)) {

	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//print_r($_SESSION);
$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = '';
$classvarr = "'.byID'";
$searchBy = '';
$bf_id = $error = "";
?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({

			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Briefing Quiz Report </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Briefing Quiz Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				if (isset($_GET['id']) and $_GET['id'] != "") {
					$bf_id = $_GET['id'];
				}
				$select_briefing = "select a.heading,a.id,a.cm_id,a.CreatedOn,b.sub_process from brf_briefing a INNER JOIN new_client_master b ON a.cm_id=b.cm_id  where";
				if ($_SESSION["__user_type"] != 'ADMINISTRATOR' and $_SESSION["__user_type"] != 'CENTRAL MIS') {
					$select_briefing .= "  a.CreatedBy='" . $_SESSION['__user_logid'] . "' and ";
				}
				$select_briefing .= " quiz='Yes' order by a.id desc";
				$myDB = new MysqliDb();
				//echo $select_briefing;
				$result_briefing = $myDB->rawQuery($select_briefing);
				?>
				<div class='input-field col s12 m12'>

					<select name='briefing_id' id='briefing_id' class='form-control' style="width: 200px;">
						<option value="">Select Briefing</option>
						<?php foreach ($result_briefing as $key => $value) { ?>
							<option value="<?php echo $value['id']; ?>" <?php if ($value['id'] == $bf_id) {
																			echo "selected";
																		} ?>><?php echo $value['heading'];
																																if ($value['sub_process'] != "") {
																																	echo  " (" . $value['sub_process'] . ") :- " . $value['CreatedOn'];
																																} else {
																																	echo  " :-  " . $value['CreatedOn'];
																																} ?></option>
						<?php  } ?>
					</select>
				</div>

				<?php
				$bfid = "";
				$clientname = "";
				$bheading = "";
				$remark1 = '';
				$remark2 = '';
				$remark3 = '';
				$cm_id = "";
				$enable_status = "";

				?>

				<div class="had-container pull-left row card dataTableInline" id="tbl_div">
					<div class="">
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>EmployeeId</th>
									<th>EmployeeName</th>
									<th>Attempted Date & Time</th>
									<th>Question</th>
									<th>Answer</th>
									<th>Result</th>

								</tr>
							</thead>
							<tbody>
								<?php

								if (isset($_GET['id']) and $_GET['id'] != "") {
									$bfid = $_GET['id'];


									$select_attempted = "select a.*,b.Answer as ActualAnswer,b.Question,b.Option1,b.Option2,b.Option3,b.Option4,c.EmployeeName from brf_quiz_attempted a 
INNER JOIN brf_question b ON a.QuestionId=b.QuestionId INNER JOIN personal_details c ON c.EmployeeID=a.EmployeeID where a.BriefingId='" . $bfid . "' order By b.BriefingID,b.QuestionID";
									//and a.EmployeeId='".$empid."'";

									//echo $sqlConnect;
									$myDB = new MysqliDb();
									$result2 = $myDB->query($select_attempted);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									$i = 1;


									if ($rowCount > 0) {


										foreach ($result2 as $key => $val) {

											$check = "";
											$optionAns = "";
											$user_ans = strtoupper($val['Answer']);
											$ans = strtoupper($val['ActualAnswer']);
											if ($user_ans == $ans) {
												$check = 'Correct';
											} else {
												$check = 'In Correct';
											}
											if ($user_ans == 'A') {
												$optionAns = $val['Option1'];
											} elseif ($user_ans == 'B') {
												$optionAns = $val['Option2'];
											} elseif ($user_ans == 'C') {
												$optionAns = $val['Option3'];
											} elseif ($user_ans == 'D') {
												$optionAns = $val['Option4'];
											}

											echo "<tr>";
											echo "<td>" . $val['EmployeeID'] . "</td>";
											echo "<td>" . $val['EmployeeName'] . "</td>";
											echo "<td>" . $val['AttemptedDate'] . "</td>";
											echo "<td>" . $question = $val['Question'] . "</td>";
											echo "<td>" . $user_ans . " (" . $optionAns . ")</td>";
											echo "<td>" . $check . "</td>";
											echo "</tr>";


											$i++;
										}
									}
								} else {
									echo "<script>$(function(){ toastr.info('Quiz Not Found.<code >" . $error . "</code>'); }); </script>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

			</div>

			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$('#briefing_id').on('change', function() {
		var bfid = $('#briefing_id').val();
		if (bfid != "") {
			location.href = 'BriefingQuizReport.php?id=' + bfid;
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>