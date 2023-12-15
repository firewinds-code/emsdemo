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
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
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

?>
<script>
	$(document).ready(function() {
		$('#txt_ED_joindate_to').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#txt_ED_joindate_from').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollY: 192,
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
		$('.byDate').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Briefing Acknowledgement</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Briefing Acknowledgement <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="BriefingMaster.php" data-position="bottom" data-tooltip="Back"><i class="material-icons">arrow_back</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$bf_id = "";
				if (isset($_GET['id']) and cleanUserInput($_GET['id']) != "") {
					$bf_id = cleanUserInput($_GET['id']);
				}
				$user_type = clean($_SESSION["__user_type"]);
				if ($user_type != 'ADMINISTRATOR' and $user_type != 'CENTRAL MIS') {
					$select_briefing = "select a.heading,a.id,a.cm_id, a.CreatedOn,b.sub_process from brf_briefing  a Inner Join  new_client_master b ON a.cm_id=b.cm_id where a.CreatedBy=? order by a.id desc";
					$stmt = $conn->prepare($select_briefing);
					$stmt->bind_param("s", $user_logid);
					$stmt->execute();
				} else {
					$select_briefing = "select a.heading,a.id,a.cm_id, a.CreatedOn,b.sub_process from brf_briefing  a Inner Join  new_client_master b ON a.cm_id=b.cm_id  order by a.id desc";
					$stmt = $conn->prepare($select_briefing);
					$stmt->execute();
				}
				$result_briefing = $stmt->get_result();
				// $myDB = new MysqliDb();
				// $result_briefing = $myDB->query($select_briefing);
				?>
				<div class="input-field col s12 m12 ">
					<select name='briefing_id' id='briefing_id'>
						<option value="">Select Briefing</option>
						<?php
						if ($result_briefing) {
							foreach ($result_briefing as $key => $value) { ?>
								<option value="<?php echo $value['id']; ?>" <?php if ($value['id'] == $bf_id) {
																				echo "selected";
																			} ?>>
									<?php echo trim($value['heading']);
									if ($value['sub_process'] != "") {
										echo  " (" . $value['sub_process'] . ") :- " . $value['CreatedOn'];
									} else {
										echo  " :-  " . $value['CreatedOn'];
									} ?></option>
						<?php }
						} ?>
					</select>
					<!-- <label for="briefing_id" class="active">Briefing</label>	-->
				</div>

				<?php
				if (clean($_SESSION["__login_type"]) == "Briefing") {
					$sqlConnect = "select a.*,b.EmployeeName,c.quiz,c.TotalQuestionNum from brf_acknowledge a   INNER JOIN  brf_briefing c ON a.BriefingId=c.id INNER JOIN personal_details b on a.EmployeeID=b.EmployeeID where a.BriefingId=? order by a.id desc";
					$stmt1 = $conn->prepare($sqlConnect);
					$stmt1->bind_param("i", $bf_id);
					$stmt1->execute();
				} else {
					$sqlConnect = "select a.*,b.EmployeeName,c.quiz,c.TotalQuestionNum from brf_acknowledge a   INNER JOIN  brf_briefing c ON a.BriefingId=c.id INNER JOIN personal_details b on a.EmployeeID=b.EmployeeID where a.BriefingId=? order by a.id desc";
					$stmt1 = $conn->prepare($sqlConnect);
					$stmt1->bind_param("i", $bf_id);
					$stmt1->execute();
				}
				$result = $stmt1->get_result();
				//echo $sqlConnect;
				//echo $sqlConnect;
				// $myDB = new MysqliDb();
				// $result = $myDB->query($sqlConnect);
				//print_r($result);
				// $error = $myDB->getLastError();
				if ($result) { ?>
					<div class="had-container pull-left row card dataTableInline" id="tbl_div">
						<div class="">
							<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Employee ID </th>
										<th>Employee Name</th>
										<th style="vertical-align:top;text-align:center;">Acknowledge Date & Time </th>
										<th style="vertical-align:top;text-align:center;">Quiz Status </th>
										<th style="vertical-align:top;text-align:center;">Quiz Attempted </th>
										<th style="vertical-align:top;text-align:center;">Total Question </th>
										<th style="vertical-align:top;text-align:center;">Correct Total </th>
										<th style="vertical-align:top;text-align:center;">Incorrect Total</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									foreach ($result as $key => $value) {
										$attempt_quiz = "0";
										$check = "";
										$correct = 0;
										$InCorrect = 0;
										//$tq=$value['TotalQuestionNum'];
										$select_question = "select * from brf_question where BriefingID=?";
										$stmt1 = $conn->prepare($select_question);
										$stmt1->bind_param("i", $bf_id);
										$stmt1->execute();
										$Qresult = $stmt1->get_result();
										// $myDB = new MysqliDb();
										// $Qresult = $myDB->rawQuery($select_question);
										// $error = $myDB->getLastError();
										// $QrowCount = $myDB->count;
										if ($Qresult->num_rows > 0) {
											$tq = $Qresult->num_rows;
											$Quiz = 'Yes';

											// $myDB = new MysqliDb();
											//echo "select a.*,b.Answer as ActualAnswer from brf_quiz_attempted a INNER JOIN brf_question b ON a.QuestionId=b.QuestionId where a.BriefingId='".$bf_id."' and a.EmployeeId='".$value['EmployeeID']."'";     
											$emId = $value['EmployeeID'];
											$select_attemptedQry = "select a.*,b.Answer as ActualAnswer from brf_quiz_attempted a INNER JOIN brf_question b ON a.QuestionId=b.QuestionId where a.BriefingId=? and a.EmployeeId=?";
											$stmt = $conn->prepare($select_attemptedQry);
											$stmt->bind_param("is", $bf_id, $emId);
											$stmt->execute();
											$select_attempted = $stmt->get_result();
											// $error = $myDB->getLastError();
											// $rowCount = $myDB->count;
											if ($select_attempted->num_rows > 0) {
												$attempt_quiz = 'Yes';
												if ($tq > 0) {
													foreach ($select_attempted as $select_attempted_val) {
														$user_ans = strtoupper($select_attempted_val['Answer']);
														$ans = strtoupper($select_attempted_val['ActualAnswer']);
														if ($user_ans == $ans) {
															$correct++;
														} else {
															$InCorrect++;
														}
													}
												}
											} else {
												$attempt_quiz = 'No';
											}
										} else {
											$tq = 0;
											$Quiz = 'No';
										}
										echo '<tr style="vertical-align:top;">';
										echo '<td class="client_name" style="vertical-align:top;">' . $value['EmployeeID'] . '</td>';
										echo '<td class="process" style="vertical-align:top;" >' . $value['EmployeeName'] . '</td>';
										echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $value['AcknowledgeDate'] . '</td>';
										echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $Quiz . '</td>';
										echo '<td class="subprocess" style="vertical-align:top;text-align:center;" >';
										echo $attempt_quiz;
										echo '</td>';
										echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $tq . '</td>';
										echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $correct . '</td>';
										echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $InCorrect . '</td>';
										echo '</tr>';
										$i++;
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<?php
				} else {
					//echo '<div id="div_error" class="slideInDown animated hidden">DATA NOT Found :: <code >'.$error.'</code> </div>';
					echo "<script>$(function(){ toastr.info('Briefing Acknowledge Detail Not Found .'); }); </script>";
				}
				?>

				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}
		$('#div_error').removeClass('hidden');
		$('#briefing_id').on('change', function() {
			var bfid = $('#briefing_id').val();
			if (bfid != "") {
				location.href = 'briefingAcknowledgeReport.php?id=' + bfid;
			}
		});
	});
	var newwindow;

	function createPop(accid, bfid) {
		newwindow = window.open('briefingQuiz.php?bfid=' + bfid + '&accid=' + accid, 'Quiz Report', 'width=600,height=600,toolbar=0,menubar=0,location=0');
		if (window.focus) {
			newwindow.focus()
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>