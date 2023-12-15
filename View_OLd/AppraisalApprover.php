<?php
// Server Config file..
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file..
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time..
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form..
require(ROOT_PATH . 'AppCode/nHead.php');
$DOJ = $lastDate = $p = $wo = $l = $lwp = $hwp = $h = $a = $month = '';

// Trigger Button-Save Click Event and Perform DB Action
$btnEdit = isset($_POST['btnEdit']);
if ($btnEdit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if (cleanUserInput($_POST['HRStatus']) == 'Pending' || cleanUserInput($_POST['HRStatus']) == 'Hold' || cleanUserInput($_POST['HRStatus']) == 'Postpone') {
			$Update = 'call apr_UpdateApprover("' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['PromotionRecommend']) . '","' . cleanUserInput($_POST['PerAppro']) . '","' . cleanUserInput($_POST['DataId']) . '","' . cleanUserInput($_POST['ApproverStatus']) . '","' . cleanUserInput($_POST['HoldMonth']) . '","' . cleanUserInput($_POST['PostponeMonth']) . '")';
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($Update);
			$rowCount = $myDB->count;
			$error = $myDB->getLastError();
			if (empty($error)) {
				if (cleanUserInput($_POST['ApproverStatus']) == 'Approve') {
					$Update = 'call update_aprsalary("' . cleanUserInput($_POST['EmpId']) . '","' . cleanUserInput($_POST['NewCTCM']) . '","' . cleanUserInput($_POST['takehome']) . '","' . cleanUserInput($_POST['HRA']) . '","' . cleanUserInput($_POST['convence']) . '","' . cleanUserInput($_POST['sp_allow']) . '","' . cleanUserInput($_POST['gross_sal']) . '","' . cleanUserInput($_POST['pf']) . '","' . cleanUserInput($_POST['esis']) . '","' . clean($_SESSION['__user_logid']) . '","' . cleanUserInput($_POST['Basic']) . '","' . cleanUserInput($_POST['pf_employer']) . '","' . cleanUserInput($_POST['esi_employer']) . '","' . cleanUserInput($_POST['professional_tex']) . '","' . cleanUserInput($_POST['min_wages']) . '","' . cleanUserInput($_POST['PLI']) . '","' . cleanUserInput($_POST['net_takehome']) . '")';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($Update);
					$rowCount = $myDB->count;
					$error = $myDB->getLastError();
					if (empty($error)) {
						if (cleanUserInput($_POST['PromotionRecommend']) == 'Yes') {
							$newpost = $postid = '';
							if (cleanUserInput($_POST['PromotionRecommend']) == "Yes") {
								$newpost = cleanUserInput($_POST['hiddenPost']);
								$postid = cleanUserInput($_POST['PromotionPostApr']);
							} else {
								$newpost = 'NA';
								$postid = null;
							}
							$Update = 'call apr_UpdatePromotion("' . cleanUserInput($_POST['DataId']) . '","' . cleanUserInput($_POST['AprMonth']) . '","' . cleanUserInput($_POST['PromotionRecommendApr']) . '","' . $newpost . '","' . $postid . '")';
							$myDB = new MysqliDb();
							$result = $myDB->rawQuery($Update);

							$Update = 'call apr_UpdateDesignation("' . cleanUserInput($_POST['EmpId']) . '","' . $postid . '")';
							$myDB = new MysqliDb();
							$result = $myDB->rawQuery($Update);

							$rowCount = $myDB->count;
							$error = $myDB->getLastError();
						}
					}
				} else if (cleanUserInput($_POST['ApproverStatus']) == 'Postpone') {
					$postponemonth = '+' . $_POST['PostponeMonth'] . ' months';
					$month = date_parse($_POST['AprMonth']);

					$effectiveDate = date('Y') . '-' . $month['month'] . '-01';
					$effectiveDate = date('Y-m-d', strtotime($postponemonth, strtotime($effectiveDate)));
					$updatedmonth = date("M", strtotime($effectiveDate));

					$Update = 'call apr_UpdateAppraisal("' . cleanUserInput($_POST['EmpId']) . '","' . $updatedmonth . '")';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($Update);
					$rowCount = $myDB->count;
					$error = $myDB->getLastError();
				}


				$ApproverMarks = 'call apr_QueUpdateApprover("' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['HRScore1']) . '","' . cleanUserInput($_POST['HRScore2']) . '","' . cleanUserInput($_POST['HRScore3']) . '","' . cleanUserInput($_POST['HRScore4']) . '","' . cleanUserInput($_POST['HRScore5']) . '","' . cleanUserInput($_POST['HRScore6']) . '","' . cleanUserInput($_POST['HRScore7']) . '","' . cleanUserInput($_POST['HRScore8']) . '","' . cleanUserInput($_POST['HRScore9']) . '","' . cleanUserInput($_POST['HRScore10']) . '","' . cleanUserInput($_POST['AVGScore1']) . '","' . cleanUserInput($_POST['AVGScore2']) . '","' . cleanUserInput($_POST['AVGScore3']) . '","' . cleanUserInput($_POST['AVGScore4']) . '","' . cleanUserInput($_POST['AVGScore5']) . '","' . cleanUserInput($_POST['AVGScore6']) . '","' . cleanUserInput($_POST['AVGScore7']) . '","' . cleanUserInput($_POST['AVGScore8']) . '","' . cleanUserInput($_POST['AVGScore9']) . '","' . cleanUserInput($_POST['AVGScore10']) . '","' . cleanUserInput($_POST['DataId']) . '")';
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($ApproverMarks);

				$ApproverCom = 'call apr_addcomment("' . cleanUserInput($_POST['DataId']) . '","' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['EmployeeName']) . '","' . cleanUserInput($_POST['Remarks']) . '","' . cleanUserInput($_POST['UserType']) . '")';
				$myDB = new MysqliDb();
				$result3 = $myDB->rawQuery($ApproverCom);

				/*echo $Remarks='call apr_UpdateApproverRemark("'.$_POST['DataId'].'","'.$_POST['Remarks1'].'","'.$_POST['Remarks2'].'","'.$_POST['Remarks3'].'","'.$_POST['Remarks4'].'","'.$_POST['Remarks5'].'","'.$_POST['Remarks6'].'","'.$_POST['Remarks7'].'","'.$_POST['Remarks8'].'","'.$_POST['Remarks9'].'","'.$_POST['Remarks10'].'")';
			$myDB=new MysqliDb();
			$result3 = $myDB->rawQuery($Remarks);*/

				$error = $myDB->getLastError();
				if (empty($error)) {
					echo "<script>$(function(){ toastr.success('Saved successfully.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Not saved '" . $error . "'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error(Not saved '" . $error . "'); }); </script>";
			}
		} else {
			$AHComment = 'call apr_addcomment("' . cleanUserInput($_POST['DataId']) . '","' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['EmployeeName']) . '","' . cleanUserInput($_POST['Remarks']) . '","' . cleanUserInput($_POST['UserType']) . '")';
			$myDB = new MysqliDb();
			$result3 = $myDB->rawQuery($AHComment);
			$error = $myDB->getLastError();
			if (empty($error)) {
				echo "<script>$(function(){ toastr.success('Remarks saved successfully.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Remarks not saved '" . $error . "'); }); </script>";
			}
		}
	}
}
$search = '';
$txt_search = isset($_POST['txt_search']);
if ($txt_search) {
	$search = cleanUserInput($_POST['txt_search']);
}
?>
<script>
	$(document).ready(function() {
		function eventFired_order(el) {
			$('#order_text').val($('.dt-button.active>span').text());
		}
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			"iDisplayLength": $('#order_text').val(),
			buttons: [],
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
		}).search($('#txt_search').val()).draw().on('order.dt', function() {
			eventFired_order();
		});

		$('#myTable1').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			"iDisplayLength": $('#order_text').val(),
			buttons: [],
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
		}).search($('#txt_search').val()).draw().on('order.dt', function() {
			eventFired_order();
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('input[type="search"]').change(function() {
			$('#ctl00_ContentPlaceHolder1_txt_search').val($('input[type="search"]').val());
			$('#ctl00_ContentPlaceHolder1_lblmsg2').text("Search Data  :: " + $('input[type="search"]').val());
			$('#ctl00_ContentPlaceHolder1_GridView1 input[type="checkbox"]').prop("checked", false);

		});
		$('input[type="search"]').blur(function() {
			$('#txt_search').val($('input[type="search"]').val());
		});
		$('input[type="search"]').keyup(function() {
			$('#txt_search').val($('input[type="search"]').val());
		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Appraisal Form(Approver)</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Appraisal Form(Approver)</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<style>
					.scroll {
						overflow-y: auto;
						height: 84px;
						width: 160px;
					}
				</style>

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<input type="hidden" name="EmployeeName" id="EmployeeName" value="<?php echo clean($_SESSION['__user_Name']); ?>">
				<input type="hidden" name="EmployeeId" id="EmployeeId" value="<?php echo clean($_SESSION['__user_logid']); ?>">
				<input type="hidden" name="UserType" id="UserType" value="<?php echo clean($_SESSION['__user_type']); ?>">
				<input type="hidden" name="Process" id="Process" value="<?php echo clean($_SESSION['__user_process']); ?>">
				<input type="hidden" name="SubProcess" id="SubProcess" value="<?php echo clean($_SESSION['__user_subprocess']); ?>">
				<input type="hidden" name="Designation" id="Designation" value="<?php echo clean($_SESSION['__user_Desg']); ?>">
				<input type="hidden" name="ah" id="ah" value="<?php echo clean($_SESSION['__status_ah']); ?>">
				<input type="hidden" name="qh" id="qh" value="<?php echo clean($_SESSION['__status_qh']); ?>">
				<input type="hidden" name="oh" id="oh" value="<?php echo clean($_SESSION['__status_oh']); ?>">
				<input type="hidden" name="th" id="th" value="<?php echo clean($_SESSION['__status_th']); ?>">
				<input type="hidden" name="WarningCount" id="WarningCount" value="<?php echo clean($_SESSION['__status_th']); ?>">
				<input type="hidden" name="txt_search" id="txt_search" value="<?php echo $search; ?>" />
				<input type="hidden" name="DataId" id="DataId" />
				<input type="hidden" name="RepoTo" id="RepoTo" />
				<input type="hidden" name="HRStatus" id="HRStatus" />
				<input type="hidden" name="min_wages" id="min_wages" />
				<input type="hidden" name="PLI" id="PLI" />
				<input type="hidden" name="PLIPercent" id="PLIPercent" />
				<input type="hidden" name="pli_deduct" id="pli_deduct" />
				<input type="hidden" name="Basic" id="Basic" />
				<input type="hidden" name="HRA" id="HRA" />
				<input type="hidden" name="convence" id="convence" />
				<input type="hidden" name="sp_allow" id="sp_allow" />
				<input type="hidden" name="gross_sal" id="gross_sal" />
				<input type="hidden" name="pf" id="pf" />
				<input type="hidden" name="esis" id="esis" />
				<input type="hidden" name="pf_employer" id="pf_employer" />
				<input type="hidden" name="esi_employer" id="esi_employer" />
				<input type="hidden" name="professional_tex" id="professional_tex" />
				<input type="hidden" name="net_takehome" id="net_takehome" />
				<input type="hidden" name="takehome" id="takehome" />
				<input type="hidden" name="pf_status" id="pf_status" />
				<input type="hidden" name="payrolltype" id="payrolltype" />
				<input type="hidden" name="hiddenPost" id="hiddenPost" />

				<div id="ComForm" style="display: none;">
					<div class="input-field col s12 m12 no-padding">
						<div class="input-field col s12 m12">

							<ul class="collapsible">
								<li>
									<div class="collapsible-header topic">Performance Full Year Details.</div>
									<div class="collapsible-body" id="Performance">
									</div>
								</li>

								<li>
									<div class="collapsible-header topic">Applicants Details</div>
									<div class="collapsible-body">

										<div class="input-field col s4 m4 ">
											<input type="text" id="EmpId" name="EmpId" readonly="true">
											<label for="EmpId">Employee ID</label>
										</div>
										<div class="input-field col s4 m4 ">
											<input type="text" id="EmpName" name="EmpName" readonly="true">
											<label for="EmpName">Employee Name</label>
										</div>
										<div class="input-field col s2 m2 ">
											<input type="text" id="doj" name="doj" readonly="true">
											<label for="doj">DOJ</label>
										</div>

										<div class="input-field col s2 m2">
											<input type="text" id="LastDate" name="LastDate" readonly="true">
											<label for="LastDate">Form Filled Date</label>
										</div>

										<div class="input-field col s12 m12" id="divwarning">
											<input type="text" id="WarningLetter" name="WarningLetter" readonly="true" class="hidden">
											<!-- <p style=" margin-bottom: -20px; margin-top: -51px;color:#1dadc4">Warning Letter</p>-->
											<a id="warnig_href" class="warnig_href" style="font-size: 15px;text-decoration: underline;text-align: center;  margin-top: 10px">Click Here To View CAP Details</a>
											<!--<label for="WarningLetter" class="">Warning Letter</label>-->
											<hr>
										</div>

										<div class="input-field col s6 m6">
											<textarea class="materialize-textarea" id="q1" name="q1" readonly="true"></textarea>
											<label for="q1">Q.1 What are the current responsibilities held by you ?</label>
										</div>
										<div class="input-field col s6 m6">
											<textarea class="materialize-textarea" id="q2" name="q2" readonly="true"></textarea>
											<label for="q2">Q.2 What do you consider your important achievements of the past year?</label>
										</div>
										<div class="input-field col s6 m6">
											<textarea class="materialize-textarea" id="q3" name="q3" readonly="true"></textarea>
											<label for="q3">Q.3 What are your goals for the next year?</label>
										</div>
										<div class="input-field col s6 m6">
											<textarea class="materialize-textarea" id="q4" name="q4" readonly="true"></textarea>
											<label for="q4">Q.4 What are your areas of improvement?</label>
										</div>
										<div class="input-field col s12 m12">
											<textarea class="materialize-textarea " id="q6" name="q6" readonly="true"></textarea>
											<label for="q6">Q.6 If you need any support or training (related to your job profile) from the side of organization</label>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="input-field col s12 m12">
							<fieldset>
								<legend><b> Q.5 How would you rate yourself on the following attributes? </b></legend>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="width: 25px !important;">S.NO.</th>
											<th style="width: 35px !important;">Attributes</th>
											<th style="width: 65px !important;">Applicant score out of 10</th>
											<th style="width: 65px !important;">Applicant remarks</th>

											<th style="width: 65px !important;">Evaluator score out of 10</th>
											<th style="width: 65px !important;">Evaluator remarks</th>
											<th class="hidden">Approver Remarks</th>
											<th style="width: 65px !important;">Approver score out of 10</th>
											<th style="width: 40px !important;">Avg. score</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td style="width: 40px !important;">Meeting job requirements on a timely basis</td>
											<td style="width: 10px !important;"><input type="text" name="YourScore1" id="YourScore1" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks1"></div>
												</span>
											</td>
											<td style="width: 10px !important;"><input type="text" name="EvaluatorScore1" id="EvaluatorScore1" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks1"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks1" name="Remarks1" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 10px !important;"><input type="text" name="HRScore1" id="HRScore1" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 10px !important;"><input type="text" name="AVGScore1" id="AVGScore1" readonly="true"></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Knowledge of job</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore2" id="YourScore2" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks2"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore2" id="EvaluatorScore2" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks2"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks2" name="Remarks2" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore2" id="HRScore2" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore2" id="AVGScore2" readonly="true"></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Communication skills</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore3" id="YourScore3" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks3"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore3" id="EvaluatorScore3" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks3"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks3" name="Remarks3" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore3" id="HRScore3" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore3" id="AVGScore3" readonly="true"></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Interpersonal skills</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore4" id="YourScore4" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks4"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore4" id="EvaluatorScore4" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks4"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks4" name="Remarks4" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore4" id="HRScore4" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore4" id="AVGScore4" readonly="true"></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Initiative and creativity</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore5" id="YourScore5" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks5"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore5" id="EvaluatorScore5" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks5"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks5" name="Remarks5" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore5" id="HRScore5" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore5" id="AVGScore5" readonly="true"></td>
										</tr>
										<tr>
											<td>6</td>
											<td>Decision making ability</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore6" id="YourScore6" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks6"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore6" id="EvaluatorScore6" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks6"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks6" name="Remarks6" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore6" id="HRScore6" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore6" id="AVGScore6" readonly="true"></td>
										</tr>
										<tr>
											<td>7</td>
											<td>Adaptability and flexibility</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore7" id="YourScore7" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks7"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore7" id="EvaluatorScore7" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks7"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks7" name="Remarks7" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore7" id="HRScore7" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore7" id="AVGScore7" readonly="true"></td>
										</tr>
										<tr>
											<td>8</td>
											<td>Team work</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore8" id="YourScore8" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks8"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore8" id="EvaluatorScore8" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks8"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks8" name="Remarks8" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore8" id="HRScore8" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore8" id="AVGScore8" readonly="true"></td>
										</tr>
										<tr>
											<td>9</td>
											<td>Time management</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore9" id="YourScore9" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks9"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore9" id="EvaluatorScore9" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks9"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks9" name="Remarks9" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore9" id="HRScore9" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore9" id="AVGScore9" readonly="true"></td>
										</tr>
										<tr>
											<td>10</td>
											<td>Problem solving skills</td>
											<td style="width: 30px !important;"><input type="text" name="YourScore10" id="YourScore10" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks10"></div>
												</span>
											</td>
											<td style="width: 30px !important;"><input type="text" name="EvaluatorScore10" id="EvaluatorScore10" readonly="true"></td>
											<td>
												<span>
													<div class="scroll" id="EvaluatorReamrks10"></div>
												</span>
											</td>
											<td class="hidden">
												<span>
													<textarea class="materialize-textarea" id="Remarks10" name="Remarks10" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>
											<td style="width: 35px !important;"><input type="text" name="HRScore10" id="HRScore10" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);"></td>
											<td style="width: 35px !important;"><input type="text" name="AVGScore10" id="AVGScore10" readonly="true"></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
						<div class="input-field col s6 m6">
							<!--<select id="PromotionRecommend" name="PromotionRecommend">
     							<option value="NA">Select</option>
     							<option value="No">No</option>
 	 							<option value="Yes">Yes</option>
  								</select>-->
							<input type="text" name="PromotionRecommend" id="PromotionRecommend" readonly="true">
							<label for="PromotionRecommend">Promotion recommendation(evaluators)</label>
						</div>
						<div class="input-field col s6 m6" style="display: none;" id="PromotionPostId">
							<input type="text" name="PromotionPost" id="PromotionPost" readonly="true">
							<label for="PromotionPost">Level recommendation(evaluators)</label>
						</div>
						<div class="input-field col s6 m6">
							<input type="text" name="AppraisalPer" id="AppraisalPer">
							<label for="AppraisalPer">Appraisal %(evaluators)</label>
						</div>
						<div class="input-field col s6 m6">
							<select id="ApproverStatus" name="ApproverStatus">
								<option value="NA">Select</option>
								<option value="Approve">Approve</option>
								<option value="Hold">Hold</option>
								<option value="Postpone">Postpone</option>
							</select>
							<label for="ApproverStatus" class="active-drop-down active">Approver status</label>
						</div>

						<div id="ApproverRec" style="display: none;">
							<div class="input-field col s6 m6">
								<select id="PromotionRecommendApr" name="PromotionRecommendApr">
									<option value="NA">Select</option>
									<option value="No">No</option>
									<option value="Yes">Yes</option>
								</select>
								<label for="PromotionRecommendApr" class="active-drop-down active">Promotion recommendation(approver)</label>
							</div>

							<div class="input-field col s6 m6" style="display: none;" id="PromotionPostIdApr">

								<select id="PromotionPostApr" name="PromotionPostApr"></select>
								<label for="PromotionPostApr" class="active-drop-down active">Level recommendation(approver)</label>
							</div>

						</div>

						<div class="input-field col s12 m12" id="PerAppro1">
							<input type="text" name="PerAppro" id="PerAppro" onkeypress="javascript:return calcCTC(event);">
							<label for="PerAppro">Appraisal %(approver)</label>
						</div>

						<div class="input-field col s6 m6" id="HoldMonth1">
							<!--<input type="text" id="HoldMonth" name="HoldMonth" onkeypress="javascript:return OnlyNum(event);">-->
							<select id="HoldMonth" name="HoldMonth">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<label for="HoldMonth" class="active-drop-down active">Hold(month)</label>
						</div>
						<div class="input-field col s6 m6" id="PostponeMonth1">
							<!--<input type="text" id="PostponeMonth" name="PostponeMonth" onkeypress="javascript:return OnlyNum(event);">  -->
							<select id="PostponeMonth" name="PostponeMonth">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<label for="PostponeMonth" class="active-drop-down active">Postpone(month)</label>
						</div>


						<div class="input-field col s12 m12" id="ApprCalc">
							<ul class="collapsible">
								<li>
									<div class="collapsible-header topic">Appraisal calculator</div>
									<div class="collapsible-body">


										<div class="input-field col s3 m3">
											<input type="text" name="NewCTCY" id="NewCTCY" readonly="true">
											<label for="NewCTCY">Proposed CTC yearly(INR)</label>
										</div>

										<div class="input-field col s3 m3">
											<input type="text" name="NewCTCM" id="NewCTCM" readonly="true">
											<label for="NewCTCM">Proposed CTC monthly(INR)</label>
										</div>

										<div class="input-field col s3 m3">
											<!--<select id="NewPLIPercent" name="NewPLIPercent">
     											<option value="0">0</option>
     											<option value="5">5</option>
 	 											<option value="10">10</option>
  												</select>-->
											<input type="text" name="NewPLIPercent" id="NewPLIPercent" readonly="true" value="10">
											<label for="NewPLIPercent" class="active-drop-down active">PLI(%)</label>
										</div>

										<div class="input-field col s3 m3">
											<input type="text" name="NewPLI" id="NewPLI" readonly="true">
											<label for="NewPLI">Proposed PLI monthly(INR)</label>
										</div>

										<div class="input-field col s3 m3">
											<input type="text" name="CurrentCTCY" id="CurrentCTCY" readonly="true">
											<label for="CurrentCTCY">Current CTC yearly(INR)</label>
										</div>


										<div class="input-field col s3 m3">
											<input type="text" name="CurrentCTCM" id="CurrentCTCM" readonly="true">
											<label for="CurrentCTCM">Current CTC monthly(INR)</label>
										</div>

										<div class="input-field col s3 m3">
											<input type="text" name="CurrentPLI" id="CurrentPLI" readonly="true">
											<label for="CurrentPLI">Current PLI monthly(INR)</label>
										</div>


										<div class="input-field col s3 m3">
											<input type="text" name="LastPercentage" id="LastPercentage" readonly="true">
											<label for="LastPercentage">Last year appraisal(%)</label>
										</div>

										<div class="input-field col s6 m6">
											<input type="text" name="AprMonth" id="AprMonth" readonly="true">
											<label for="AprMonth">Appraisal month</label>
										</div>

										<div class="input-field col s6 m6">
											<input type="text" name="ProMonth" id="ProMonth" readonly="true">
											<label for="ProMonth">Promotion month</label>
										</div>
									</div>
								</li>
							</ul>
						</div>

						<div class="input-field col s12 m12" id="CommentShow" style="border: 1px solid #19aec4;display: none;"></div>
						<div class="input-field col s12 m12">
							<textarea class="materialize-textarea " id="Remarks" name="Remarks"></textarea>
							<label for="Remarks">Remarks</label>
						</div>
					</div>
					<div class="input-field col s12 m12 right-align ">
						<button type="submit" value="Update" name="btnEdit" id="btnEdit" class="btn waves-effect waves-green">Update</button>
						<button type="submit" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn" onclick="location.href='inc_IncentiveCriteriaRequest.php'">Cancel</button>
					</div>
				</div>

				<div id="pnlTable">
					<?php
					$sqlConnect = "call apr_GetDataByReportHr()";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($result && $rowCount > 0) { ?>
						<div class="had-container pull-left row card dataTableInline" style="margin-top: 10px;width: 100%;padding: 15px;">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Action</th>
											<th>EmployeeName</th>
											<th>EmployeeId</th>
											<th>DOJ</th>
											<th>LastDateFill</th>
											<th>Process</th>
											<th>Sub-Process</th>
											<th>Cm_id</th>
											<th>AH</th>
											<th>ReportTo</th>
											<th>AH Status</th>
											<th>HR Status</th>
											<th class="hidden">Appraisal Month</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td id="countc' . $count . '">' . $count . '</td>';
											echo '<td class="tbl__ID"><a href="javascript:void(0)" data-ID="' . $value['id'] . '" id="' . $value['id'] . '" class="a__ID" onclick="javascript:return EditData(this);"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
											echo '<td class="EmployeeName" id="EmployeeName' . $count . '">' . $value['EmployeeName'] . '</td>';
											echo '<td class="EmployeeId" id="EmployeeId' . $count . '" Emp="' . $value['EmployeeId'] . '" >' . $value['EmployeeId'] . '</td>';
											echo '<td class="Doj"  id="Doj' . $count . '" >' . $value['Doj'] . '</td>';
											echo '<td class="LastDateFill" id="LastDateFill' . $count . '"  >' . $value['LastDateFill'] . '</td>';
											echo '<td class="Process" id="Process' . $count . '">' . $value['Process'] . '</td>';
											echo '<td class="SubProcess" id="SubProcess' . $count . '">' . $value['SubProcess'] . '</td>';
											echo '<td class="Cm_id" id="Cm_id' . $count . '">' . $value['Cm_id'] . '</td>';
											echo '<td class="AH" id="AH' . $count . '">' . $value['AH'] . '</td>';
											echo '<td class="ReportTo" id="ReportTo' . $count . '">' . $value['ReportTo'] . '</td>';
											echo '<td class="AHStatus" id="AHStatus' . $count . '">' . $value['AHStatus'] . '</td>';
											echo '<td class="HRStatus" id="HRStatus' . $count . '">' . $value['HRStatus'] . '</td>';
											echo '<td class="AprMonth hidden" id="AprMonth' . $count . '">' . $value['AppraisalMonth'] . '</td>';
										?>
											</tr>
										<?php }	?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						echo "<script>$(function(){ toastr.info('No pending appraisal form" . $error . ".') }); </script>";
					}
					?>
				</div>

				<div id="pnlTable1">
					<?php
					$sqlConnect = "call apr_GetDataByReportHr_Hold()";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($result && $rowCount > 0) { ?>
						<div style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 16px;">Hold Case</div>
						<div class="had-container pull-left row card dataTableInline" style="margin-top: 10px;width: 100%;padding: 15px;overflow-x: auto;">
							<div class="">
								<table id="myTable1" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Action</th>
											<th>EmployeeName</th>
											<th>EmployeeId</th>
											<th>DOJ</th>
											<th>LastDateFill</th>
											<th>Process</th>
											<th>Sub-Process</th>
											<th>Cm_id</th>
											<th>AH</th>
											<th>ReportTo</th>
											<th>AH Status</th>
											<th>HR Status</th>
											<th class="hidden">Appraisal Month</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td id="countc' . $count . '">' . $count . '</td>';
											echo '<td class="tbl__ID"><a href="javascript:void(0)" data-ID="' . $value['id'] . '" id="' . $value['id'] . '" class="a__ID" onclick="javascript:return EditData(this);"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
											echo '<td class="EmployeeName" id="EmployeeName' . $count . '">' . $value['EmployeeName'] . '</td>';
											echo '<td class="EmployeeId" id="EmployeeId' . $count . '" Emp="' . $value['EmployeeId'] . '" >' . $value['EmployeeId'] . '</td>';
											echo '<td class="Doj"  id="Doj' . $count . '" >' . $value['Doj'] . '</td>';
											echo '<td class="LastDateFill" id="LastDateFill' . $count . '"  >' . $value['LastDateFill'] . '</td>';
											echo '<td class="Process" id="Process' . $count . '">' . $value['Process'] . '</td>';
											echo '<td class="SubProcess" id="SubProcess' . $count . '">' . $value['SubProcess'] . '</td>';
											echo '<td class="Cm_id" id="Cm_id' . $count . '">' . $value['Cm_id'] . '</td>';
											echo '<td class="AH" id="AH' . $count . '">' . $value['AH'] . '</td>';
											echo '<td class="ReportTo" id="ReportTo' . $count . '">' . $value['ReportTo'] . '</td>';
											echo '<td class="AHStatus" id="AHStatus' . $count . '">' . $value['AHStatus'] . '</td>';
											echo '<td class="HRStatus" id="HRStatus' . $count . '">' . $value['HRStatus'] . '</td>';
											echo '<td class="AprMonth hidden" id="AprMonth' . $count . '">' . $value['AppraisalMonth'] . '</td>';
										?>
											</tr>
										<?php }	?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						echo "<script>$(function(){ toastr.info('No Hold Appraisal Form" . $error . ".') }); </script>";
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

<style>
	.modal .modal-content p {
		padding: 6px;
	}

	.lipad {
		padding: 10px;
	}
</style>

<div id="modal2" class="modal" style="height: 550px;">
	<div class="">

		<!--<form method="POST" action="<?php echo URL . 'View/CAP_FormEmp'; ?>" enctype="multipart/form-data" name="headForm">-->
		<input type="hidden" name="hrFormid" id="hrFormid">

		<div class="row center">
			<h4 style="color:#19aec4">
				<div id="divcapstatus"></div>
			</h4>
			<div class="col s12">
				<div class="input-field col s6 m6">
					<input type="text" id="capissuedate" name="capissuedate" readonly="true">
					<label for="capissuedate" class="Active">Last CAP issue date</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" id="noofcap" name="noofcap" readonly="true">
					<label for="noofcap" class="Active">No of CAP issued</label>
				</div>
			</div>
		</div>

		<div class="row center" style="padding-left: 20px !important;">
			<ul class="collapsible">

				<li class="lipad" id="lipad1">
					<div class="collapsible-header topic">CAP Details</div>
					<div class="collapsible-body">

						<div class="col s12">
							<div class="input-field col s4 m4">
								<input type="text" id="ijphr1" name="ijphr1" readonly="true">
								<label for="ijphr1" class="Active">Disqualify For IJP (In Months)</label>
							</div>
							<div class="input-field col s4 m4">
								<input type="text" id="incentivehr1" name="incentivehr1" readonly="true">
								<label for="incentivehr1" class="Active">Incentive Deduction (In Months)</label>
							</div>

							<div class="input-field col s4 m4">
								<input type="text" id="plihr1" name="plihr1" readonly="true">
								<label for="plihr1" class="Active">PLI Deduction (In Months)</label>
							</div>
						</div>

						<div class="input-field col s12 m12">
							<div class="input-field col s12 m12" id="comment_container_emp1" style="margin: 0px;max-height: 250px;overflow: auto;">
							</div>
						</div>
					</div>
				</li>

				<li class="lipad" id="lipad2">
					<div class="collapsible-header topic">CAP Details</div>
					<div class="collapsible-body">

						<div class="col s12">
							<div class="input-field col s4 m4">
								<input type="text" id="ijphr2" name="ijphr2" readonly="true">
								<label for="ijphr2" class="Active">Disqualify For IJP (In Months)</label>
							</div>

							<div class="input-field col s4 m4">
								<input type="text" id="incentivehr2" name="incentivehr2" readonly="true">
								<label for="incentivehr2" class="Active">Incentive Deduction (In Months)</label>
							</div>

							<div class="input-field col s4 m4">
								<input type="text" id="plihr2" name="plihr2" readonly="true">
								<label for="plihr2" class="Active">PLI Deduction (In Months)</label>
							</div>
						</div>

						<div class="input-field col s12 m12">
							<div class="input-field col s12 m12" id="comment_container_emp2" style="margin: 0px;max-height: 250px;overflow: auto;">
							</div>
						</div>
					</div>
				</li>


				<li class="lipad" id="lipad3">
					<div class="collapsible-header topic">CAP Details</div>
					<div class="collapsible-body">

						<div class="col s12">
							<div class="input-field col s4 m4">
								<input type="text" id="ijphr3" name="ijphr3" readonly="true">
								<label for="ijphr3" class="Active">Disqualify For IJP (In Months)</label>
							</div>
							<div class="input-field col s4 m4">
								<input type="text" id="incentivehr3" name="incentivehr3" readonly="true">
								<label for="incentivehr3" class="Active">Incentive Deduction (In Months)</label>
							</div>
							<div class="input-field col s4 m4">
								<input type="text" id="plihr3" name="plihr3" readonly="true">
								<label for="plihr3" class="Active">PLI Deduction (In Months)</label>
							</div>
						</div>

						<div class="input-field col s12 m12">
							<div class="input-field col s12 m12" id="comment_container_emp3" style="margin: 0px;max-height: 250px;overflow: auto;">
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="row" style="padding: 0 0 0 0;">
			<div class="modal-footer">
				<a href="#" class="btn modal-close waves-effect waves-green btn-flat" style="color:white;">Close</a>
			</div>
		</div>
		<!--</form>-->
	</div>

</div>

<script>
	$(document).ready(function() {
		$('.modal').modal();
	});

	$(document).ready(function() {
		$('#divwarning').hide();
		$("#WarningLetter").attr("disabled", 'true');

		$('#warnig_href').click(function() {
			$('#modal2').modal('open');
		});

		$('#btnEdit').click(function() {
			validate = 0;
			if ($('#AppraisalPer').val() == "") {
				validate = 1;
				$('#AppraisalPer').addClass('has-error');
				$('#AppraisalPer').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_AppraisalPer').length == 0) {
					$('<span id="span_AppraisalPer" class="help-block">Require *</span>').insertAfter('#AppraisalPer');
				}
			}

			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore1').val() == "") {
				validate = 1;
				$('#HRScore1').addClass('has-error');
				$('#HRScore1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore1').length == 0) {
					$('<span id="span_HRScore1" class="help-block">Require *</span>').insertAfter('#HRScore1');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore2').val() == "") {
				validate = 1;
				$('#HRScore2').addClass('has-error');
				$('#HRScore2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore2').length == 0) {
					$('<span id="span_HRScore2" class="help-block">Require *</span>').insertAfter('#HRScore2');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore3').val() == "") {
				validate = 1;
				$('#HRScore3').addClass('has-error');
				$('#HRScore3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore3').length == 0) {
					$('<span id="span_HRScore3" class="help-block">Require *</span>').insertAfter('#HRScore3');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore4').val() == "") {
				validate = 1;
				$('#HRScore4').addClass('has-error');
				$('#HRScore4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore4').length == 0) {
					$('<span id="span_HRScore4" class="help-block">Require *</span>').insertAfter('#HRScore4');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore5').val() == "") {
				validate = 1;
				$('#HRScore5').addClass('has-error');
				$('#HRScore5').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore5').length == 0) {
					$('<span id="span_HRScore5" class="help-block">Require *</span>').insertAfter('#HRScore5');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore6').val() == "") {
				validate = 1;
				$('#HRScore6').addClass('has-error');
				$('#HRScore6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore6').length == 0) {
					$('<span id="span_HRScore6" class="help-block">Require *</span>').insertAfter('#HRScore6');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore7').val() == "") {
				validate = 1;
				$('#HRScore7').addClass('has-error');
				$('#HRScore7').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore7').length == 0) {
					$('<span id="span_HRScore7" class="help-block">Require *</span>').insertAfter('#HRScore7');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore8').val() == "") {
				validate = 1;
				$('#HRScore8').addClass('has-error');
				$('#HRScore8').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore8').length == 0) {
					$('<span id="span_HRScore8" class="help-block">Require *</span>').insertAfter('#HRScore8');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore9').val() == "") {
				validate = 1;
				$('#HRScore9').addClass('has-error');
				$('#HRScore9').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore9').length == 0) {
					$('<span id="span_HRScore9" class="help-block">Require *</span>').insertAfter('#HRScore9');
				}
			}
			if ($('#ApproverStatus').val() == "Approve" && $('#HRScore10').val() == "") {
				validate = 1;
				$('#HRScore10').addClass('has-error');
				$('#HRScore10').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_HRScore10').length == 0) {
					$('<span id="span_HRScore10" class="help-block">Require *</span>').insertAfter('#HRScore10');
				}
			}
			if ($('#ApproverStatus').val() == "NA") {
				validate = 1;
				$('#ApproverStatus').addClass('has-error');
				$('#ApproverStatus').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_ApproverStatus').length == 0) {
					$('<span id="span_ApproverStatus" class="help-block">Require *</span>').insertAfter('#ApproverStatus');
				}
			}
			if ($('#PromotionRecommend').val() == "NA") {
				validate = 1;
				$('#PromotionRecommend').addClass('has-error');
				$('#PromotionRecommend').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_PromotionRecommend').length == 0) {
					$('<span id="span_PromotionRecommend" class="help-block">Require *</span>').insertAfter('#PromotionRecommend');
				}
			}
			if ($('#ApproverStatus').val() == "Approve") {
				if ($('#PerAppro').val() == "") {
					validate = 1;
					$('#PerAppro').addClass('has-error');
					$('#PerAppro').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_PerAppro').length == 0) {
						$('<span id="span_PerAppro" class="help-block">Require *</span>').insertAfter('#PerAppro');
					}
				}

				if ($('#PromotionRecommendApr').val() == "NA") {
					validate = 1;
					$('#PromotionRecommendApr').addClass('has-error');
					$('#PromotionRecommendApr').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_PromotionRecommendApr').length == 0) {
						$('<span id="span_PromotionRecommendApr" class="help-block">Require *</span>').insertAfter('#PromotionRecommendApr');
					}
				}

				if ($('#PromotionRecommendApr').val() == "Yes" && $('#PromotionPostApr').val() == "NA") {
					validate = 1;
					$('#PromotionPostApr').addClass('has-error');
					$('#PromotionPostApr').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_PromotionPostApr').length == 0) {
						$('<span id="span_PromotionPostApr" class="help-block">Require *</span>').insertAfter('#PromotionPostApr');
					}
				}

				var input = $('#PerAppro').val();
				if (input < 0 || input > 100) {
					validate = 1;
					$('#PerAppro').addClass('has-error');
					$('#PerAppro').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_PerAppro').length == 0) {
						$('<span id="span_PerAppro" class="help-block">Value should be between 0 - 100</span>').insertAfter('#PerAppro');
					}
					alert("Value should be between 0 - 100");
				}
			}
			if ($('#ApproverStatus').val() == "Hold") {
				if ($('#HoldMonth').val() == "") {
					validate = 1;
					$('#HoldMonth').addClass('has-error');
					$('#HoldMonth').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_HoldMonth').length == 0) {
						$('<span id="span_HoldMonth" class="help-block">Require *</span>').insertAfter('#HoldMonth');
					}
				}
			}

			if ($('#Remarks').val() == "") {
				validate = 1;
				$('#Remarks').addClass('has-error');
				$('#Remarks').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore10').length == 0) {
					$('<span id="span_EvaluatorScore10" class="help-block">Require *</span>').insertAfter('#Remarks');
				}
			}


			if (validate == 1) {
				return false;
			}
		});
		var t = false
		$('.Score').focus(function() {
			var $this = $(this)
			t = setInterval(
				function() {
					if (($this.val() < 1 || $this.val() > 10) && $this.val().length != 0) {
						if ($this.val() < 1) {
							$this.val(1)
						}
						if ($this.val() > 10) {
							$this.val(10)
						}
						$('p').fadeIn(1000, function() {
							$(this).fadeOut(500)
						})
					}
				}, 50)
		});
		$('.Score').blur(function() {
			if (t != false) {
				window.clearInterval(t)
				t = false;
			}
		});
	});

	$('#PromotionRecommendApr').change(function() {
		if ($('#PromotionRecommendApr').val() == 'Yes') {
			$('#PromotionPostIdApr').show();
			//getProcess();
		} else {
			$('#PromotionPostIdApr').hide();
		}
	});

	function getProcess() {
		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#PromotionPostApr').html(Resp);
				$('select').formSelect();
			}

		}
		//$('#txt_clientname').val($("#txt_client option:selected").text());
		//var location = <?php echo $_SESSION["__location"] ?>;
		//alert($('#EmpId').val());
		xmlhttp.open("GET", "../Controller/getRecommendLevel.php?EmpID=" + $('#EmpId').val(), true);
		xmlhttp.send();
		//$('#txt_cm_id').val(el1);
	}

	$('#PromotionRecommend').change(function() {
		if ($('#PromotionRecommend').val() == 'Yes') {
			$('#PromotionPostId').show();
		} else {
			$('#PromotionPostId').hide();
		}
	});

	$('#PromotionPostApr').change(function() {
		$('#hiddenPost').val($("#PromotionPostApr option:selected").text());

	});

	$('#NewPLIPercent').change(function() {
		$('#NewPLI').val(($("#NewCTC").val() * $("#NewPLIPercent").val()) / 100);
	});

	$('#PerAppro1').hide();
	$('#HoldMonth1').hide();
	$('#PostponeMonth1').hide();
	$('#ApprCalc').hide();
	$('#ApproverStatus').change(function() {
		if ($('#ApproverStatus').val() == 'Approve') {
			$('#PerAppro1').show();
			$('#ApprCalc').show();
			$('#HoldMonth1').hide();
			$('#PostponeMonth1').hide();
			$('#ApproverRec').show();
		} else if ($('#ApproverStatus').val() == 'Hold') {
			$('#HoldMonth1').show();
			$('#PerAppro1').hide();
			$('#ApprCalc').hide();
			$('#PostponeMonth1').hide();
			$('#ApproverRec').hide();
		} else if ($('#ApproverStatus').val() == 'Postpone') {
			$('#HoldMonth1').hide();
			$('#PerAppro1').hide();
			$('#ApprCalc').hide();
			$('#PostponeMonth1').show();
			$('#ApproverRec').hide();
		} else {
			$('#PerAppro1').hide();
			$('#HoldMonth1').hide();
			$('#ApprCalc').hide();
			$('#PostponeMonth1').hide();
			$('#ApproverRec').hide();
		}
	});

	function getDateString(date, format) {
		var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			getPaddedComp = function(comp) {
				return ((parseInt(comp) < 10) ? ('0' + comp) : comp)
			},
			formattedDate = format,
			o = {
				"y+": date.getFullYear(), // year
				"M+": months[date.getMonth()], //month
				"d+": getPaddedComp(date.getDate()), //day
				"h+": getPaddedComp((date.getHours() > 12) ? date.getHours() % 12 : date.getHours()), //hour
				"H+": getPaddedComp(date.getHours()), //hour
				"m+": getPaddedComp(date.getMinutes()), //minute
				"s+": getPaddedComp(date.getSeconds()), //second
				"S+": getPaddedComp(date.getMilliseconds()), //millisecond,
				"b+": (date.getHours() >= 12) ? 'PM' : 'AM'
			};

		for (var k in o) {
			if (new RegExp("(" + k + ")").test(format)) {
				formattedDate = formattedDate.replace(RegExp.$1, o[k]);
			}
		}
		return formattedDate;
	};

	function EditData(el) {
		$('#ComForm').show();

		var tr = $(el).closest('tr');
		var EmplID = tr.find('.EmployeeId').text();
		var AprMonth1 = tr.find('.AprMonth').text();
		var EmpName = 'CAP Request Status (' + EmplID + '/' + tr.find('.EmployeeName').text() + ')';
		$('#PromotionPostApr').empty();
		//alert(PromotionID);
		$.ajax({
			url: "../Controller/getRecommendLevel.php?EmpID=" + EmplID,
			success: function(result) {
				if (result != '') {
					//alert(result); 
					$('#PromotionPostApr').append(new Option("---Select---", "NA"));
					var Data = result.split('|$$|');
					jQuery.each(Data, function(i, val) {
						if (val != '') {
							arr = val.split('|$|');
							//alert(arr[0]);
							//alert(arr[1]);
							$('#PromotionPostApr').append('<option value="' + arr[0] + '">' + '' + arr[1] + '</option>');

						}

					});
				}
				$('select').formSelect();
			}
		});


		$.ajax({
			url: "../Controller/get_aprApplicantDetails.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					var ID = Data[0];
					var EmployeeName = Data[1];
					var EmployeeID = Data[2];
					var Doj = Data[3];
					var WarningLetter = Data[4];
					var WarningCount = Data[5];
					var LastDateFill = Data[6];
					var Q1 = Data[7];
					var Q2 = Data[8];
					var Q3 = Data[9];
					var Q4 = Data[10];
					var Q6 = Data[11];
					var Process = Data[12];
					var SubProcess = Data[13];
					var Cm_id = Data[14];
					var AH = Data[15];
					var ReportTo = Data[18];
					var RatingAH = Data[19];
					var RatingHR = Data[20];
					var PromotionRecomend = Data[21];
					var PromotionPost = Data[22];
					var AHStatus = Data[23];
					var HRStatus = Data[24];

					$('#DataId').val(ID);
					$('#EmpName').val(EmployeeName);
					$('#EmpId').val(EmployeeID);
					$('#doj').val(Doj);
					$('#WarningLetter').val(WarningLetter);
					$('#WarningCount').val(WarningCount);
					$('#LastDate').val(LastDateFill);
					$('#q1').val(Q1);
					$('#q2').val(Q2);
					$('#q3').val(Q3);
					$('#q4').val(Q4);
					$('#q6').val(Q6);
					$('#AppraisalPer').val(RatingAH);
					$('#PromotionPost').val(PromotionPost);
					$('#PromotionRecommend').val(PromotionRecomend);

					if (HRStatus == 'Hold' || HRStatus == 'Postpone') {
						$('#ApproverStatus').val(HRStatus);
					} else {
						$('#ApproverStatus').val('NA');
					}

					if (HRStatus == 'Hold') {
						//alert(HRStatus);
						//$('#ApproverStatus').val(HRStatus);
						$('#HoldMonth1').show();
						$('#HoldMonth').val(Data[27]);
					} else {
						$('#HoldMonth1').hide();
						$('#ApproverStatus').val('NA');
					}
					if (HRStatus == 'Postpone') {
						//$('#ApproverStatus').val(HRStatus);
						$('#PostponeMonth1').show();
						$('#PostponeMonth').val(Data[28]);
					} else {
						$('#PostponeMonth1').hide();
						//$('#ApproverStatus').val('NA');
					}
					$('#HRStatus').val(HRStatus);
					$('select').formSelect();
					if (PromotionRecomend == 'Yes') {
						$('#PromotionPostId').show();
					} else {
						$('#PromotionPostId').hide();
					}

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_aprScore.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					var EmployeeID = Data[1];

					var ApplicantScore5_1 = Data[2];
					var ApplicantScore5_2 = Data[3];
					var ApplicantScore5_3 = Data[4];
					var ApplicantScore5_4 = Data[5];
					var ApplicantScore5_5 = Data[6];
					var ApplicantScore5_6 = Data[7];
					var ApplicantScore5_7 = Data[8];
					var ApplicantScore5_8 = Data[9];
					var ApplicantScore5_9 = Data[10];
					var ApplicantScore5_10 = Data[11];
					var EvaluatorScore1 = Data[12];
					var EvaluatorScore2 = Data[13];
					var EvaluatorScore3 = Data[14];
					var EvaluatorScore4 = Data[15];
					var EvaluatorScore5 = Data[16];
					var EvaluatorScore6 = Data[17];
					var EvaluatorScore7 = Data[18];
					var EvaluatorScore8 = Data[19];
					var EvaluatorScore9 = Data[20];
					var EvaluatorScore10 = Data[21];
					var HRScore1 = Data[22];
					var HRScore2 = Data[23];
					var HRScore3 = Data[24];
					var HRScore4 = Data[25];
					var HRScore5 = Data[26];
					var HRScore6 = Data[27];
					var HRScore7 = Data[28];
					var HRScore8 = Data[29];
					var HRScore9 = Data[30];
					var HRScore10 = Data[31];

					$("#AppraisalPer").attr("disabled", 'true');

					$('#YourScore1').val(ApplicantScore5_1);
					$('#YourScore2').val(ApplicantScore5_2);
					$('#YourScore3').val(ApplicantScore5_3);
					$('#YourScore4').val(ApplicantScore5_4);
					$('#YourScore5').val(ApplicantScore5_5);
					$('#YourScore6').val(ApplicantScore5_6);
					$('#YourScore7').val(ApplicantScore5_7);
					$('#YourScore8').val(ApplicantScore5_8);
					$('#YourScore9').val(ApplicantScore5_9);
					$('#YourScore10').val(ApplicantScore5_10);

					$('#EvaluatorScore1').val(EvaluatorScore1);
					$('#EvaluatorScore2').val(EvaluatorScore2);
					$('#EvaluatorScore3').val(EvaluatorScore3);
					$('#EvaluatorScore4').val(EvaluatorScore4);
					$('#EvaluatorScore5').val(EvaluatorScore5);
					$('#EvaluatorScore6').val(EvaluatorScore6);
					$('#EvaluatorScore7').val(EvaluatorScore7);
					$('#EvaluatorScore8').val(EvaluatorScore8);
					$('#EvaluatorScore9').val(EvaluatorScore9);
					$('#EvaluatorScore10').val(EvaluatorScore10);


					$('#HRScore1').val(HRScore1);
					$('#HRScore2').val(HRScore2);
					$('#HRScore3').val(HRScore3);
					$('#HRScore4').val(HRScore4);
					$('#HRScore5').val(HRScore5);
					$('#HRScore6').val(HRScore6);
					$('#HRScore7').val(HRScore7);
					$('#HRScore8').val(HRScore8);
					$('#HRScore9').val(HRScore9);
					$('#HRScore10').val(HRScore10);

					if (HRScore1 != '') {
						$("#HRScore1").attr("disabled", 'true');
						$("#HRScore2").attr("disabled", 'true');
						$("#HRScore3").attr("disabled", 'true');
						$("#HRScore4").attr("disabled", 'true');
						$("#HRScore5").attr("disabled", 'true');
						$("#HRScore6").attr("disabled", 'true');
						$("#HRScore7").attr("disabled", 'true');
						$("#HRScore8").attr("disabled", 'true');
						$("#HRScore9").attr("disabled", 'true');
						$("#HRScore10").attr("disabled", 'true');

						$("#Remarks1").attr("disabled", 'true');
						$("#Remarks2").attr("disabled", 'true');
						$("#Remarks3").attr("disabled", 'true');
						$("#Remarks4").attr("disabled", 'true');
						$("#Remarks5").attr("disabled", 'true');
						$("#Remarks6").attr("disabled", 'true');
						$("#Remarks7").attr("disabled", 'true');
						$("#Remarks8").attr("disabled", 'true');
						$("#Remarks9").attr("disabled", 'true');
						$("#Remarks10").attr("disabled", 'true');
					}
				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_Comment.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					$('#CommentShow').html(result);
					$('#CommentShow').show();
				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_aprApplicantComment.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');

					$('#ApplicantReamrks1').empty();
					$('#ApplicantReamrks2').empty();
					$('#ApplicantReamrks3').empty();
					$('#ApplicantReamrks4').empty();
					$('#ApplicantReamrks5').empty();
					$('#ApplicantReamrks6').empty();
					$('#ApplicantReamrks7').empty();
					$('#ApplicantReamrks8').empty();
					$('#ApplicantReamrks9').empty();
					$('#ApplicantReamrks10').empty();

					$('#ApplicantReamrks1').append(Data[2]);
					$('#ApplicantReamrks2').append(Data[3]);
					$('#ApplicantReamrks3').append(Data[4]);
					$('#ApplicantReamrks4').append(Data[5]);
					$('#ApplicantReamrks5').append(Data[6]);
					$('#ApplicantReamrks6').append(Data[7]);
					$('#ApplicantReamrks7').append(Data[8]);
					$('#ApplicantReamrks8').append(Data[9]);
					$('#ApplicantReamrks9').append(Data[10]);
					$('#ApplicantReamrks10').append(Data[11]);

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_aprAHComment.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');

					$('#EvaluatorReamrks1').empty();
					$('#EvaluatorReamrks2').empty();
					$('#EvaluatorReamrks3').empty();
					$('#EvaluatorReamrks4').empty();
					$('#EvaluatorReamrks5').empty();
					$('#EvaluatorReamrks6').empty();
					$('#EvaluatorReamrks7').empty();
					$('#EvaluatorReamrks8').empty();
					$('#EvaluatorReamrks9').empty();
					$('#EvaluatorReamrks10').empty();

					$('#EvaluatorReamrks1').append(Data[2]);
					$('#EvaluatorReamrks2').append(Data[3]);
					$('#EvaluatorReamrks3').append(Data[4]);
					$('#EvaluatorReamrks4').append(Data[5]);
					$('#EvaluatorReamrks5').append(Data[6]);
					$('#EvaluatorReamrks6').append(Data[7]);
					$('#EvaluatorReamrks7').append(Data[8]);
					$('#EvaluatorReamrks8').append(Data[9]);
					$('#EvaluatorReamrks9').append(Data[10]);
					$('#EvaluatorReamrks10').append(Data[11]);

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_aprHRComment.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');

					$('#Remarks1').append(Data[2]);
					$('#Remarks2').append(Data[3]);
					$('#Remarks3').append(Data[4]);
					$('#Remarks4').append(Data[5]);
					$('#Remarks5').append(Data[6]);
					$('#Remarks6').append(Data[7]);
					$('#Remarks7').append(Data[8]);
					$('#Remarks8').append(Data[9]);
					$('#Remarks9').append(Data[10]);
					$('#Remarks10').append(Data[11]);

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_AprData.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					//alert(result);
					var ctc = Data[0];
					var pli = Data[1];
					var aprmonth = Data[2];
					ctc = parseFloat(ctc).toFixed(0);
					pli = parseFloat(pli).toFixed(0);

					var ctcy = ctc * 12;
					ctcy = parseFloat(ctcy).toFixed(0);
					$('#CurrentCTCY').val(ctcy);

					$('#CurrentCTCM').val(ctc);
					$('#CurrentPLI').val(pli);
					$('#AprMonth').val(aprmonth);

					$('#NewPLIPercent').val(Data[3]);
					$('#PLIPercent').val(Data[3]);

					$('#NewCTCM').val('0');
					$('#NewCTCY').val('0');
					$('#NewPLI').val('0');

					$('#min_wages').val(Data[4]);
					$('#Basic').val(Data[5]);
					$('#HRA').val(Data[6]);
					$('#convence').val(Data[7]);
					$('#sp_allow').val(Data[8]);
					$('#gross_sal').val(Data[9]);
					$('#pf').val(Data[10]);
					$('#esis').val(Data[11]);
					$('#pf_employer').val(Data[12]);
					$('#esi_employer').val(Data[13]);
					$('#professional_tex').val(Data[14]);
					$('#net_takehome').val(Data[15]);
					$('#pf_status').val(Data[16]);
					$('#payrolltype').val(Data[17]);
					$('#pli_deduct').val(Data[18]);

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_LastAprData.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');

					var lapr = Data[0];
					var pmonth = Data[1];
					$('#LastPercentage').val(lapr);
					//$('#ProMonth').val(pmonth);

				} else {
					$('#LastPercentage').val('NA');
					//$('#ProMonth').val('NA');
				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_LastPramotion.php?EmpID=" + EmplID,
			success: function(result) {

				if (result != '') {

					$('#ProMonth').val(result);


				} else {
					$('#ProMonth').val('NA');

				}
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/get_aprPerformance.php?EmpID=" + EmplID,
			success: function(result) {
				if (result != '') {
					$('#Performance').html(result);
				}
				$('select').formSelect();
			}
		});

		///////////////////////////////////////////////////////////////////////////////////

		var hrFormid = EmplID;
		//alert(AprMonth1);
		AprMonth1 = AprMonth1 - 1;
		var d = new Date();
		var d1 = new Date(d.getFullYear(), AprMonth1, '1');

		//alert(d1);

		d.setMonth(d1.getMonth() - 3);
		//var t= new Date();

		//alert(d);
		var month = d.getMonth() + 1;

		var year = d.getFullYear();
		var date1 = year + '-' + month + '-1';
		//alert(date1);

		d = new Date();
		d.setMonth(d1.getMonth() - 1);
		month = d.getMonth() + 1;
		var dd = new Date(d.getFullYear(), d.getMonth() + 1, 0, 23, 59, 59);
		var day = dd.getDate();
		year = d.getFullYear();
		var date2 = year + '-' + month + '-' + day;


		var capID = '';
		$.ajax({
			url: "../Controller/get_CapIDForApr.php?EmpID=" + EmplID + "&Date1=" + date1 + "&Date2=" + date2,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					capID = Data[0];
					var formattedDate = getDateString(new Date(Data[1]), "d-M-y");
					$('#capissuedate').val(formattedDate);
					/*$('#divwarning').show();
	        $('#divcapstatus').empty().append(EmpName);
	        $.ajax({url: "../Controller/getComment_CAP.php?ID="+capID, success: function(commentresult){
                                
            if(commentresult != '')
            {
            	//alert(commentresult); 
				
				$('#comment_container_emp1').empty().append(commentresult);
				$('#comment_container_emp2').empty().append(commentresult);
				
			}    
			$('select').formSelect();                         
        }});
        
        $.ajax({url: "../Controller/getCAPAH_Data.php?ID="+capID, success: function(result3){
             //alert(typeof val); 
             //alert(result3);                   
            if($.trim(result3) == "Not Exist")
            {
            			
			} 
			else
			{	
				
				var Data  = result3.split('|$|');
            	var pli = Data[0];
            	
				
				$('#ijphr').val(Data[0]);
				$('#incentivehr').val(Data[1]);
				$('#plihr').val(Data[2]);
				
                
                $('#statusHead_hr').append($("<option></option>")
                .attr("value", Data[3])
                .text(Data[3]));
                
			}   
			$('select').formSelect();                         
        }});*/


				}
				/*else
    	{
			$('#divwarning').hide();
			$('#comment_container_emp1').empty();
			$('#comment_container_emp2').empty();
			$('#divcapstatus').empty()
			$('#ijphr').val('');
			$('#incentivehr').val('');
			$('#plihr').val('');
			
		}*/
				$('select').formSelect();
			}
		});

		var lastdd = new Date();
		lastdd.setMonth(d1.getMonth() - 12);
		month = lastdd.getMonth() + 1;

		year = lastdd.getFullYear();
		date1 = year + '-' + month + '-1';
		/*alert(date1);
		alert(date2); */

		$.ajax({
			url: "../Controller/get_CapDetails.php?EmpID=" + EmplID + "&Date1=" + date1 + "&Date2=" + date2,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					$('#noofcap').val(Data[0]);

					$('#divwarning').show();
					$('#divcapstatus').empty().append(EmpName);
					if (Data[1] != '') {
						$('#lipad1').show();
						$.ajax({
							url: "../Controller/getComment_CAP.php?ID=" + Data[1],
							success: function(commentresult) {

								if (commentresult != '') {
									//alert(commentresult); 

									$('#comment_container_emp1').empty().append(commentresult);

								}
								$('select').formSelect();
							}
						});

						$.ajax({
							url: "../Controller/getCAPAH_Data.php?ID=" + Data[1],
							success: function(result3) {

								if ($.replace(/^\s+|\s+$/g, result3) == "Not Exist") {
									// if ($.trim(result3) == "Not Exist") {

								} else {

									var Data1 = result3.split('|$|');

									$('#ijphr1').val(Data1[0]);
									$('#incentivehr1').val(Data1[1]);
									$('#plihr1').val(Data1[2]);


								}
								$('select').formSelect();
							}
						});
					} else {
						$('#comment_container_emp1').empty();
						$('#ijphr1').val('');
						$('#incentivehr1').val('');
						$('#plihr1').val('');
						$('#lipad1').hide();
					}


					if (Data[2] != '') {
						$('#lipad2').show();
						$.ajax({
							url: "../Controller/getComment_CAP.php?ID=" + Data[2],
							success: function(commentresult) {

								if (commentresult != '') {
									//alert(commentresult); 

									$('#comment_container_emp2').empty().append(commentresult);

								}
								$('select').formSelect();
							}
						});

						$.ajax({
							url: "../Controller/getCAPAH_Data.php?ID=" + Data[2],
							success: function(result3) {

								if ($.replace(/^\s+|\s+$/g, result3) == "Not Exist") {
									// if ($.trim(result3) == "Not Exist") {

								} else {

									var Data1 = result3.split('|$|');

									$('#ijphr2').val(Data1[0]);
									$('#incentivehr2').val(Data1[1]);
									$('#plihr2').val(Data1[2]);


								}
								$('select').formSelect();
							}
						});
					} else {
						$('#comment_container_emp2').empty();
						$('#ijphr2').val('');
						$('#incentivehr2').val('');
						$('#plihr2').val('');
						$('#lipad2').hide();
					}

					if (Data[3] != '') {
						$('#lipad3').show();
						$.ajax({
							url: "../Controller/getComment_CAP.php?ID=" + Data[3],
							success: function(commentresult) {

								if (commentresult != '') {
									//alert(commentresult); 

									$('#comment_container_emp3').empty().append(commentresult);

								}
								$('select').formSelect();
							}
						});

						$.ajax({
							url: "../Controller/getCAPAH_Data.php?ID=" + Data[3],
							success: function(result3) {

								if ($.replace(/^\s+|\s+$/g, result3) == "Not Exist") {
									// if ($.trim(result3) == "Not Exist") {

								} else {

									var Data1 = result3.split('|$|');

									$('#ijphr3').val(Data1[0]);
									$('#incentivehr3').val(Data1[1]);
									$('#plihr3').val(Data1[2]);


								}
								$('select').formSelect();
							}
						});
					} else {
						$('#comment_container_emp3').empty();
						$('#ijphr3').val('');
						$('#incentivehr3').val('');
						$('#plihr3').val('');
						$('#lipad3').hide();
					}
				} else {
					$('#divwarning').hide();
					$('#divcapstatus').empty();

				}
				$('select').formSelect();
			}
		});

	}

	function OnlyNum(e) {
		var KeyID = (window.event) ? event.keyCode : e.which;
		if ((KeyID >= 48 && KeyID <= 57) || KeyID == 8) {
			return true;
		} else {
			return false;
		}
	}
	$('.Score').keyup(function() {
		var Your = 0;
		var Evaluator = 0;
		var Approver = 0;
		var Sum = 0;
		var Avg = 0;

		var number = this.id.split('e');
		Your = parseInt($('#YourScore' + number[1]).val());
		Evaluator = parseInt($('#EvaluatorScore' + number[1]).val());
		Approver = parseInt($('#HRScore' + number[1]).val());
		Sum = Your + Evaluator + Approver;
		Avg = Sum / 3;
		$('#AVGScore' + number[1]).val(Math.round(Avg));
	});

	function calcCTC(e) {
		//alert($("#takehome").val());
		var KeyID = (window.event) ? event.keyCode : e.which;
		var aprper = '';
		if ((KeyID >= 48 && KeyID <= 57) || KeyID == 8) {
			//alert($("#AppraisalPer").val());
			if (KeyID == 48) {
				aprper = $("#PerAppro").val() + 0;
			} else if (KeyID == 49) {
				aprper = $("#PerAppro").val() + 1;
			} else if (KeyID == 50) {
				aprper = $("#PerAppro").val() + 2;
			} else if (KeyID == 51) {
				aprper = $("#PerAppro").val() + 3;
			} else if (KeyID == 52) {
				aprper = $("#PerAppro").val() + 4;
			} else if (KeyID == 53) {
				aprper = $("#PerAppro").val() + 5;
			} else if (KeyID == 54) {
				aprper = $("#PerAppro").val() + 6;
			} else if (KeyID == 55) {
				aprper = $("#PerAppro").val() + 7;
			} else if (KeyID == 56) {
				aprper = $("#PerAppro").val() + 8;
			} else if (KeyID == 57) {
				aprper = $("#PerAppro").val() + 9;
			}

			var NewCTC = Number(($("#CurrentCTCM").val() * aprper) / 100) + Number($("#CurrentCTCM").val());

			NewCTC = parseFloat(NewCTC).toFixed(0);
			$('#NewCTCM').val(NewCTC);
			var ctcy = NewCTC * 12;
			ctcy = parseFloat(ctcy).toFixed(0);
			$('#NewCTCY').val(ctcy);

			var NewPLI = (($("#NewCTCM").val() * $("#NewPLIPercent").val()) / 100);

			NewPLI = parseFloat(NewPLI).toFixed(0);

			$('#NewPLI').val(NewPLI);


			/*$('#NewCTC').val(Number(($("#CurrentCTC").val()*aprper)/100) + Number($("#CurrentCTC").val()));
			$('#NewPLI').val(($("#NewCTC").val()*$("#NewPLIPercent").val())/100);*/

			//// Calculate All variable of salary_details page//////////////////////
			var CTC = parseFloat($('#NewCTCM').val());

			//alert(CTC);				
			var MinVages = 0;
			var Basic = 0;
			var HRA = 0;
			var ConveyenceAllownce = 0;
			var OtherAllownces = 0;
			var GrossSalary = 0;
			var EmployeePF = 0;
			var EmployeeESIC = 0;
			var EmployerPF = 0;
			var EmployerESIC = 0;
			var ProfessionalTax = 0;
			var TkHome_1 = 0;
			var PLI_percent = 0;
			var PLI_ammount = 0;
			var NetTakeHome = 0;
			// MinVages

			if (CTC < 8100) {
				$("#min_wages").val(MinVages);
			} else {
				if (CTC < 15800) {
					MinVages = 7108;
					$("#min_wages").val(7108);
				} else {
					MinVages = parseFloat(CTC);
					$("#min_wages").val(MinVages);
				}
			}


			// Basic


			if (MinVages < 15800) {
				Basic = (MinVages * 60) / 100;
				$("#Basic").val(Basic);
			} else {
				Basic = (CTC * 50) / 100;
				$("#Basic").val(Basic.toFixed(2));

			}

			// HRA

			if (MinVages < 15800) {
				HRA = (MinVages * 30) / 100;
				$("#HRA").val(HRA.toFixed(2));
			} else {
				HRA = (Basic * 40) / 100;
				$("#HRA").val(HRA.toFixed(2));
			}


			// Conveyence Allownce


			if (MinVages < 15800) {
				ConveyenceAllownce = (MinVages * 10) / 100;
				$('#convence').val(ConveyenceAllownce.toFixed(2));
			} else {
				ConveyenceAllownce = 1600;
				$('#convence').val(ConveyenceAllownce);
			}

			// Employee PF and Employer PF


			if (CTC < 15800) {
				EmployeePF = (Basic * 12) / 100;
				EmployerPF = (Basic * 13.36) / 100;
			} else if (CTC >= 15800 && $('#pf_status').val() == "Yes") {
				EmployeePF = (Basic * 12) / 100;
				EmployerPF = (Basic * 13.36) / 100;
			}
			//Other Allownces 

			if (CTC > 21000 && $('#pf_status').val() == 'Yes') {
				OtherAllownces = (CTC - (HRA + Basic + ConveyenceAllownce) - EmployerPF);
			} else if (CTC >= 15800 && $('#pf_status').val() == 'Yes') {
				OtherAllownces = CTC - (HRA + Basic + ConveyenceAllownce) - EmployerPF - (CTC * 4.23170731707317) / 100;
			} else if (CTC < 8100) {
				OtherAllownces = 0;
			} else if (CTC < 15800) {
				OtherAllownces = (CTC - 8015) / 1.0475;
			} else if (CTC <= 21000) {
				OtherAllownces = (CTC * 100 / 104.75) - (HRA + Basic + ConveyenceAllownce);
			} else {

				OtherAllownces = CTC - (HRA + Basic + ConveyenceAllownce);
			}

			$("#sp_allow").val(OtherAllownces.toFixed(2));

			if (CTC < 8100) {
				GrossSalary = CTC;
				$("#gross_sal").val(GrossSalary.toFixed(2));
			} else {
				if (CTC < 15800) {

					GrossSalary = Basic + HRA + ConveyenceAllownce + OtherAllownces;
					$("#gross_sal").val(GrossSalary.toFixed(2));
				} else {
					if (CTC >= 15800) {
						GrossSalary = Basic + HRA + ConveyenceAllownce + OtherAllownces;
						$("#gross_sal").val(GrossSalary.toFixed(2));
					} else {
						GrossSalary = (CTC * 100) / 104.75;
						$("#gross_sal").val(GrossSalary.toFixed(2));
					}
				}
			}

			// Professional Tax

			ProfessionalTax = 0;



			// Employee ESIC and Employer ESIC

			if (CTC < 8100) {

				$("#esis").val(EmployeeESIC);
				$("#esi_employer").val(EmployerESIC);
			} else {
				if (CTC > 21000) {
					$("#esis").val(EmployeeESIC);
					$("#esi_employer").val(EmployerESIC);
				} else {
					EmployeeESIC = (GrossSalary * 1.75) / 100;
					EmployerESIC = (GrossSalary * 4.75) / 100;
					$("#esis").val(EmployeeESIC.toFixed(2));
					$("#esi_employer").val(EmployerESIC.toFixed(2));
				}
			}
			// Employer PF 




			// Take Home -1 

			if ($("#payrolltype").val() == "INPE") {
				TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);


				if (CTC >= 15800) {
					//$('.pf_deduct').removeClass('hidden');  
					if ($('#pf_status').val() == 'Yes') {
						TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
					} else {
						TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
						EmployeePF = 0;
						$('#pf_status').val('No');
					}
				} else {
					//$('.pf_deduct').addClass('hidden');
					$('#pf_status').val('No');
				}

			} else if ($("#payrolltype").val() == "OUTPE") {
				//$('.pf_deduct').removeClass('hidden');  
				if ($('#pf_status').val() == 'Yes') {
					TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
				} else {
					TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
					EmployeePF = 0;
				}
			} else {
				//$('.pf_deduct').addClass('hidden');  
				$('#pf_status').val('No');
				TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
				EmployeePF = 0;

			}

			// PF insert 

			$("#pf_employer").val(EmployerPF.toFixed(2));
			$("#pf").val(EmployeePF.toFixed(2));

			$("#takehome").val(TkHome_1.toFixed(2));

			// PLI Calculation 

			if (!isNaN(parseFloat($("#PLIPercent").val())) && parseFloat($("#PLIPercent").val()) <= 100 && parseFloat($("#PLIPercent").val()) >= 0 && $("#pli_deduct").val() == "Yes") {
				PLI_percent = parseFloat($("#PLIPercent").val());
			} else {
				if (parseFloat($("#PLIPercent").val()) > 100) {
					$("#PLIPercent").val("100");
					PLI_percent = 100;
				}
			}

			PLI_ammount = (CTC * PLI_percent) / 100;
			//alert(PLI_ammount);
			$("#PLI").val(PLI_ammount.toFixed(2));


			// Net Take Home
			NetTakeHome = TkHome_1 - PLI_ammount;
			$("#net_takehome").val(NetTakeHome.toFixed(0));
			/*$("#ntake_hoveDiv").text("  "+ NetTakeHome.toFixed(0));
			$("#ctc_hoveDiv").text("  "+ CTC.toFixed(0));
			$("#take_hoveDiv").text("  "+ TkHome_1.toFixed(0));*/

			return true;

		} else {
			return false;
		}
		$('select').formSelect();
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>