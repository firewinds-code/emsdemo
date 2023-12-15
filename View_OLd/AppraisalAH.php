<?php
// Server Config file..
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file..
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time..
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form..
require(ROOT_PATH . 'AppCode/nHead.php');
$DOJ = $lastDate = $p = $wo = $l = $lwp = $hwp = $h = $a = $month = '';

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btnEdit'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$newpost = $postid = '';
		if (cleanUserInput($_POST['AHStatus']) == 'Pending') {
			if ($_POST['PromotionRecommend'] == "Yes") {
				$newpost = cleanUserInput($_POST['hiddenPost']);
				$postid = cleanUserInput($_POST['PromotionPost']);
			} else {
				$newpost = 'NA';
				$postid = null;
			}
			/*echo $newpost;
		die;*/

			$EmployeeId = cleanUserInput($_POST['EmployeeId']);
			$PromotionRecommend = cleanUserInput($_POST['PromotionRecommend']);
			$AppraisalPer = cleanUserInput($_POST['AppraisalPer']);
			$DataId = cleanUserInput($_POST['DataId']);
			$UpdateAH = 'call apr_UpdateAH("' . $EmployeeId . '","' . $PromotionRecommend . '","' . $newpost . '","' . $postid . '","' . $AppraisalPer . '","' . $DataId . '")';
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($UpdateAH);
			$Error = $myDB->getLastError();
			if (empty($Error)) {
				$EvaluatorScore1 = cleanUserInput($_POST['EvaluatorScore1']);
				$EvaluatorScore2 = cleanUserInput($_POST['EvaluatorScore2']);
				$EvaluatorScore3 = cleanUserInput($_POST['EvaluatorScore3']);
				$EvaluatorScore4 = cleanUserInput($_POST['EvaluatorScore4']);
				$EvaluatorScore5 = cleanUserInput($_POST['EvaluatorScore5']);
				$EvaluatorScore6 = cleanUserInput($_POST['EvaluatorScore6']);
				$EvaluatorScore7 = cleanUserInput($_POST['EvaluatorScore7']);
				$EvaluatorScore8 = cleanUserInput($_POST['EvaluatorScore8']);
				$EvaluatorScore9 = cleanUserInput($_POST['EvaluatorScore9']);
				$EvaluatorScore10 = cleanUserInput($_POST['EvaluatorScore10']);
				$AHMarking = 'call apr_QueUpdateAH("' . $EmployeeId . '","' . $EvaluatorScore1 . '","' . $EvaluatorScore2 . '","' . $EvaluatorScore3 . '","' . $EvaluatorScore4 . '","' . $EvaluatorScore5 . '","' . $EvaluatorScore6 . '","' . $EvaluatorScore7 . '","' . $EvaluatorScore8 . '","' . $EvaluatorScore9 . '","' . $EvaluatorScore10 . '","' . $DataId . '")';
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($AHMarking);
				/*if($_POST['PromotionRecommend']=='Yes')
			{
				$remaks = $_POST['Remarks'].','.$newpost;
			}
			else
			{
				$remaks = $_POST['Remarks'];
			}*/
				$remaks = cleanUserInput($_POST['Remarks']);
				$EmployeeName = cleanUserInput($_POST['EmployeeName']);
				$userType = cleanUserInput($_POST['UserType']);
				$AHComment = 'call apr_addcomment("' . $DataId . '","' . $EmployeeId . '","' . $EmployeeName . '","' . $remaks . '","' . $userType . '")';
				$myDB = new MysqliDb();
				$result3 = $myDB->rawQuery($AHComment);

				$Remarks1 = cleanUserInput($_POST['Remarks1']);
				$Remarks2 = cleanUserInput($_POST['Remarks2']);
				$Remarks3 = cleanUserInput($_POST['Remarks3']);
				$Remarks4 = cleanUserInput($_POST['Remarks4']);
				$Remarks5 = cleanUserInput($_POST['Remarks5']);
				$Remarks6 = cleanUserInput($_POST['Remarks6']);
				$Remarks7 = cleanUserInput($_POST['Remarks7']);
				$Remarks8 = cleanUserInput($_POST['Remarks8']);
				$Remarks9 = cleanUserInput($_POST['Remarks9']);
				$Remarks10 = cleanUserInput($_POST['Remarks10']);
				$Remarks = 'call apr_UpdateEvaluaterRemark("' . $DataId . '","' . $Remarks1 . '","' . $Remarks2 . '","' . $Remarks3 . '","' . $Remarks4 . '","' . $Remarks5 . '","' . $Remarks6 . '","' . $Remarks7 . '","' . $Remarks8 . '","' . $Remarks9 . '","' . $Remarks10 . '")';
				$myDB = new MysqliDb();
				$result3 = $myDB->rawQuery($Remarks);

				$error = $myDB->getLastError();
				if (empty($error)) {
					echo "<script>$(function(){ toastr.success('Saved Successfully.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Not Saved '" . $error . "'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error(Not Saved '" . $Error . "'); }); </script>";
			}
		} else {
			$EmployeeName = cleanUserInput($_POST['EmployeeName']);
			$remaks = cleanUserInput($_POST['Remarks']);
			$userType = cleanUserInput($_POST['UserType']);
			$AHComment = 'call apr_addcomment("' . $DataId . '","' . $EmployeeId . '","' . $EmployeeName . '","' . $remaks . '","' . $userType . '")';
			$myDB = new MysqliDb();
			$result3 = $myDB->rawQuery($AHComment);
			$error = $myDB->getLastError();
			if (empty($error)) {
				echo "<script>$(function(){ toastr.success('Saved Remarks Successfully.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Remarks Not Saved.'" . $error . "'); }); </script>";
			}
		}
	}
}
$search = 'Pending';
if (isset($_POST['txt_search'])) {
	$search = cleanUserInput($_POST['txt_search']);
}
//print_r($_SESSION);
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
	<span id="PageTittle_span" class="hidden">Appraisal Form(AH)</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Appraisal Form(AH)</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<style>
					#CommentShow {
						max-height: 101px;
						overflow: auto;
					}

					.scroll {
						overflow-y: auto;
						height: 84px;
						width: 220px;
					}
				</style>
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<input type="hidden" name="EmployeeName" id="EmployeeName" value="<?php echo $_SESSION['__user_Name']; ?>">
				<input type="hidden" name="EmployeeId" id="EmployeeId" value="<?php echo $_SESSION['__user_logid']; ?>">
				<input type="hidden" name="UserType" id="UserType" value="<?php echo $_SESSION['__user_type']; ?>">
				<input type="hidden" name="Process" id="Process" value="<?php echo $_SESSION['__user_process']; ?>">
				<input type="hidden" name="SubProcess" id="SubProcess" value="<?php echo $_SESSION['__user_subprocess']; ?>">
				<input type="hidden" name="hiddenPost" id="hiddenPost" />

				<input type="hidden" name="Designation" id="Designation" value="<?php echo $_SESSION['__user_Desg']; ?>">
				<input type="hidden" name="ah" id="ah" value="<?php echo $_SESSION['__status_ah']; ?>">
				<input type="hidden" name="qh" id="qh" value="<?php echo $_SESSION['__status_qh']; ?>">
				<input type="hidden" name="oh" id="oh" value="<?php echo $_SESSION['__status_oh']; ?>">
				<input type="hidden" name="th" id="th" value="<?php echo $_SESSION['__status_th']; ?>">
				<input type="hidden" name="WarningCount" id="WarningCount" value="<?php echo $_SESSION['__status_th']; ?>">
				<input type="hidden" name="txt_search" id="txt_search" value="<?php echo $search; ?>" />
				<input type="hidden" name="DataId" id="DataId" />
				<input type="hidden" name="RepoTo" id="RepoTo" />
				<input type="hidden" name="HRStatus" id="HRStatus" />
				<input type="hidden" name="AHStatus" id="AHStatus" />
				<input type="hidden" name="takehome" id="takehome" />
				<input type="hidden" name="aprEmpID" id="aprEmpID" />


				<div id="ComForm" style="display: none;">
					<div class="input-field col s12 m12 no-padding">
						<div class="input-field col s12 m12">
							<ul class="collapsible">
								<li>
									<div class="collapsible-header topic">Performance Full Year Details</div>
									<div class="collapsible-body" id="Performance">
									</div>
								</li>

								<li>
									<div class="collapsible-header topic">Applicants Details</div>
									<div class="collapsible-body">

										<div class="input-field col s4 m4 ">
											<input type="text" id="EmpId" name="EmpId" readonly="true">
											<label for="EmpId">Employee Id</label>
										</div>

										<div class="input-field col s4 m4 ">
											<input type="text" id="EmpName" name="EmpName" readonly="true">
											<label for="EmpName">Employee name</label>
										</div>
										<div class="input-field col s2 m2 ">
											<input type="text" id="doj" name="doj" readonly="true">
											<label for="doj">DOJ</label>
										</div>

										<div class="input-field col s2 m2">
											<input type="text" id="LastDate" name="LastDate" readonly="true">
											<label for="LastDate">Form filled date</label>
										</div>

										<div class="input-field col s12 m12" id="divwarning">
											<input type="text" id="WarningLetter" name="WarningLetter" readonly="true" class="hidden">
											<!--<p style=" margin-bottom: -20px; margin-top: -51px;color:#1dadc4">Warning Letter</p>-->
											<a id="warnig_href" class="warnig_href" style="font-size: 15px;text-decoration: underline;text-align: center;  margin-top: 10px">Click here to view CAP details</a>
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
								<table class="table table-bordered" style="overflow: auto;">
									<thead>
										<tr>
											<th style="width: 25px !important;">S.NO.</th>
											<th style="width: 35px !important;">Attributes</th>
											<th style="width: 65px !important;">Applicant score out of 10</th>
											<th style="width: 65px !important;">Applicant remarks</th>
											<th style="width: 65px !important;">Evaluators score out of 10</th>
											<th>Evaluator Remarks</th>

											<th style="width: 65px !important;">Approver score out of 10</th>
											<th style="width: 40px !important;">Avg. score</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td style="width: 100px !important;">Meeting job requirements on a timely basis</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore1" id="YourScore1" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks1"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore1" id="EvaluatorScore1" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks1" name="Remarks1" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore1" id="HRScore1" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore1" id="AVGScore1" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Knowledge of job</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore2" id="YourScore2" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks2"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore2" id="EvaluatorScore2" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks2" name="Remarks2" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore2" id="HRScore2" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore2" id="AVGScore2" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Communication skills</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore3" id="YourScore3" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks3"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore3" id="EvaluatorScore3" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks3" name="Remarks3" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore3" id="HRScore3" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore3" id="AVGScore3" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Interpersonal skills</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore4" id="YourScore4" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks4"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore4" id="EvaluatorScore4" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks4" name="Remarks4" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore4" id="HRScore4" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore4" id="AVGScore4" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Initiative and creativity</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore5" id="YourScore5" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks5"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore5" id="EvaluatorScore5" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks5" name="Remarks5" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore5" id="HRScore5" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore5" id="AVGScore5" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>6</td>
											<td>Decision making ability</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore6" id="YourScore6" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks6"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore6" id="EvaluatorScore6" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks6" name="Remarks6" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore6" id="HRScore6" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore6" id="AVGScore6" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>7</td>
											<td>Adaptability and flexibility</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore7" id="YourScore7" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks7"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore7" id="EvaluatorScore7" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks7" name="Remarks7" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore7" id="HRScore7" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore7" id="AVGScore7" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>8</td>
											<td>Team work</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore8" id="YourScore8" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks8"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore8" id="EvaluatorScore8" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks8" name="Remarks8" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore8" id="HRScore8" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore8" id="AVGScore8" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>9</td>
											<td>Time management</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore9" id="YourScore9" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks9"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore9" id="EvaluatorScore9" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks9" name="Remarks9" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore9" id="HRScore9" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore9" id="AVGScore9" readonly="true"></textarea></td>
										</tr>
										<tr>
											<td>10</td>
											<td>Problem solving skills</td>
											<td style="width: 50px !important;">
												<input type="text" name="YourScore10" id="YourScore10" readonly="true">

											</td>
											<td>
												<span>
													<div class="scroll" id="ApplicantReamrks10"></div>
												</span>
											</td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="EvaluatorScore10" id="EvaluatorScore10" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea></td>
											<td>
												<span>
													<textarea class="materialize-textarea" id="Remarks10" name="Remarks10" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
											</td>

											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="HRScore10" id="HRScore10" readonly="true"></textarea></td>
											<td style="width: 35px !important;"><textarea class="materialize-textarea Score" name="AVGScore10" id="AVGScore10" readonly="true"></textarea></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>

						<div class="input-field col s6 m6">
							<select id="PromotionRecommend" name="PromotionRecommend">
								<option value="">Select</option>
								<option value="No">No</option>
								<option value="Yes">Yes</option>
							</select>
							<label for="PromotionRecommend" class="active-drop-down active">Promotion recommendation</label>
						</div>

						<div class="input-field col s6 m6" style="display: none;" id="PromotionPostId">

							<select id="PromotionPost" name="PromotionPost"></select>
							<label for="PromotionPost" class="active-drop-down active">Level recommendation</label>
						</div>

						<div class="input-field col s6 m6" style="display: none;" id="PromotionPostId1">
							<input type="text" name="PromotionPost1" id="PromotionPost1" readonly="true">
							<label for="PromotionPost1">Level recommendation</label>
						</div>

						<div class="input-field col s12 m12">
							<input type="text" name="AppraisalPer" id="AppraisalPer" onkeypress="javascript:return calcCTC(event);" maxlength="3">
							<label for="AppraisalPer">Appraisal percentage</label>
						</div>


						<div class="input-field col s12 m12" id="divAprCalc">
							<ul class="collapsible">
								<li>
									<div class="collapsible-header topic">Appraisal Calculator</div>
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
											<label for="NewPLIPercent" class="active-drop-down active">PLI percent(%)</label>
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
											<label for="AprMonth">Appraisal Month</label>
										</div>

										<div class="input-field col s6 m6">
											<input type="text" name="ProMonth" id="ProMonth" readonly="true">
											<label for="ProMonth">Promotion Month</label>
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

					<?php if (date('d') >= 6 && date('d') <= 20) { ?>
						<div class="input-field col s12 m12 right-align " id="hideButtons">
							<button type="submit" value="Update" name="btnEdit" id="btnEdit" class="btn waves-effect waves-green">Update</button>
							<button type="submit" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn" onclick="location.href='AppraisalAH.php'">Cancel</button>
						</div>

					<?php } ?>

				</div>


				<div id="pnlTable">
					<?php
					$userlogid = clean($_SESSION['__user_logid']);
					$sqlConnect = "call apr_GetDataByReportTo('" . $userlogid . "')";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($result && $rowCount > 0) { ?>
						<div class="had-container pull-left row card dataTableInline" id="tbl_div">
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
											<!--<th>Process</th>
												<th>Sub-Process</th>
												<th>Cm_id</th>
												<th>AH</th>
												<th>ReportTo</th>-->
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
											echo '<td class="EmployeeId" id="EmployeeId' . $count . '"  >' . $value['EmployeeId'] . '</td>';
											echo '<td class="Doj"  id="Doj' . $count . '" >' . $value['Doj'] . '</td>';
											echo '<td class="LastDateFill" id="LastDateFill' . $count . '"  >' . $value['LastDateFill'] . '</td>';
											/*echo '<td class="Process" id="Process'.$count.'">'.$value['Process'].'</td>';	
											echo '<td class="SubProcess" id="SubProcess'.$count.'">'.$value['SubProcess'].'</td>';
											echo '<td class="Cm_id" id="Cm_id'.$count.'">'.$value['Cm_id'].'</td>';	
				 							echo '<td class="AH" id="AH'.$count.'">'.$value['AH'].'</td>';	
											echo '<td class="ReportTo" id="ReportTo'.$count.'">'.$value['ReportTo'].'</td>';*/
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
						echo "<script>$(function(){ toastr.info('No pending appraisal form " . $error . ".') }); </script>";
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
	/*$('.req2').click(function() {
    		
        $('#modal2').modal('open');
		
    });*/

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
			var input = $('#AppraisalPer').val();
			if (input < 0 || input > 100) {
				validate = 1;
				$('#AppraisalPer').addClass('has-error');
				$('#AppraisalPer').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_AppraisalPer').length == 0) {
					$('<span id="span_AppraisalPer" class="help-block">Value should be between 0 - 100</span>').insertAfter('#AppraisalPer');
				}
				alert("Value should be between 0 - 100");
			}

			if ($('#EvaluatorScore1').val() == "") {
				validate = 1;
				$('#EvaluatorScore1').addClass('has-error');
				$('#EvaluatorScore1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore1').length == 0) {
					$('<span id="span_EvaluatorScore1" class="help-block">Require *</span>').insertAfter('#EvaluatorScore1');
				}
			}
			if ($('#EvaluatorScore2').val() == "") {
				validate = 1;
				$('#EvaluatorScore2').addClass('has-error');
				$('#EvaluatorScore2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore2').length == 0) {
					$('<span id="span_EvaluatorScore2" class="help-block">Require *</span>').insertAfter('#EvaluatorScore2');
				}
			}
			if ($('#EvaluatorScore3').val() == "") {
				validate = 1;
				$('#EvaluatorScore3').addClass('has-error');
				$('#EvaluatorScore3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore3').length == 0) {
					$('<span id="span_EvaluatorScore3" class="help-block">Require *</span>').insertAfter('#EvaluatorScore3');
				}
			}
			if ($('#EvaluatorScore4').val() == "") {
				validate = 1;
				$('#EvaluatorScore4').addClass('has-error');
				$('#EvaluatorScore4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore4').length == 0) {
					$('<span id="span_EvaluatorScore4" class="help-block">Require *</span>').insertAfter('#EvaluatorScore4');
				}
			}
			if ($('#EvaluatorScore5').val() == "") {
				validate = 1;
				$('#EvaluatorScore5').addClass('has-error');
				$('#EvaluatorScore5').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore5').length == 0) {
					$('<span id="span_EvaluatorScore5" class="help-block">Require *</span>').insertAfter('#EvaluatorScore5');
				}
			}
			if ($('#EvaluatorScore6').val() == "") {
				validate = 1;
				$('#EvaluatorScore6').addClass('has-error');
				$('#EvaluatorScore6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore6').length == 0) {
					$('<span id="span_EvaluatorScore6" class="help-block">Require *</span>').insertAfter('#EvaluatorScore6');
				}
			}
			if ($('#EvaluatorScore7').val() == "") {
				validate = 1;
				$('#EvaluatorScore7').addClass('has-error');
				$('#EvaluatorScore7').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore7').length == 0) {
					$('<span id="span_EvaluatorScore7" class="help-block">Require *</span>').insertAfter('#EvaluatorScore7');
				}
			}
			if ($('#EvaluatorScore8').val() == "") {
				validate = 1;
				$('#EvaluatorScore8').addClass('has-error');
				$('#EvaluatorScore8').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore8').length == 0) {
					$('<span id="span_EvaluatorScore8" class="help-block">Require *</span>').insertAfter('#EvaluatorScore8');
				}
			}
			if ($('#EvaluatorScore9').val() == "") {
				validate = 1;
				$('#EvaluatorScore9').addClass('has-error');
				$('#EvaluatorScore9').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore9').length == 0) {
					$('<span id="span_EvaluatorScore9" class="help-block">Require *</span>').insertAfter('#EvaluatorScore9');
				}
			}
			if ($('#EvaluatorScore10').val() == "") {
				validate = 1;
				$('#EvaluatorScore10').addClass('has-error');
				$('#EvaluatorScore10').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_EvaluatorScore10').length == 0) {
					$('<span id="span_EvaluatorScore10" class="help-block">Require *</span>').insertAfter('#EvaluatorScore10');
				}
			}

			if ($('#Remarks1').val().length < 15) {
				validate = 1;
				$('#Remarks1').addClass('has-error');
				$('#Remarks1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks1').length == 0) {
					$('<span id="span_Remarks1" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks1');
				}
			}
			if ($('#Remarks2').val().length < 15) {
				validate = 1;
				$('#Remarks2').addClass('has-error');
				$('#Remarks2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks2').length == 0) {
					$('<span id="span_Remarks2" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks2');
				}
			}
			if ($('#Remarks3').val().length < 15) {
				validate = 1;
				$('#Remarks3').addClass('has-error');
				$('#Remarks3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks3').length == 0) {
					$('<span id="span_Remarks3" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks3');
				}
			}
			if ($('#Remarks4').val().length < 15) {
				validate = 1;
				$('#Remarks4').addClass('has-error');
				$('#Remarks4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks4').length == 0) {
					$('<span id="span_Remarks4" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks4');
				}
			}
			if ($('#Remarks5').val().length < 15) {
				validate = 1;
				$('#Remarks5').addClass('has-error');
				$('#Remarks5').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks5').length == 0) {
					$('<span id="span_Remarks5" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks5');
				}
			}
			if ($('#Remarks6').val().length < 15) {
				validate = 1;
				$('#Remarks6').addClass('has-error');
				$('#Remarks6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks6').length == 0) {
					$('<span id="span_Remarks6" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks6');
				}
			}
			if ($('#Remarks7').val().length < 15) {
				validate = 1;
				$('#Remarks7').addClass('has-error');
				$('#Remarks7').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks7').length == 0) {
					$('<span id="span_Remarks7" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks7');
				}
			}
			if ($('#Remarks8').val().length < 15) {
				validate = 1;
				$('#Remarks8').addClass('has-error');
				$('#Remarks8').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks8').length == 0) {
					$('<span id="span_Remarks8" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks8');
				}
			}
			if ($('#Remarks9').val().length < 15) {
				validate = 1;
				$('#Remarks9').addClass('has-error');
				$('#Remarks9').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks9').length == 0) {
					$('<span id="span_Remarks9" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks9');
				}
			}
			if ($('#Remarks10').val().length < 15) {
				validate = 1;
				$('#Remarks10').addClass('has-error');
				$('#Remarks10').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks10').length == 0) {
					$('<span id="span_Remarks10" class="help-block">Remark should be greater than 15 character</span>').insertAfter('#Remarks10');
				}
			}

			if ($('#PromotionRecommend').val() == "") {
				validate = 1;
				$('#PromotionRecommend').addClass('has-error');
				$('#PromotionRecommend').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_PromotionRecommend').length == 0) {
					$('<span id="span_PromotionRecommend" class="help-block">Require *</span>').insertAfter('#PromotionRecommend');
				}
			}

			if ($('#PromotionRecommend').val() == "Yes" && $('#PromotionPost').val() == "NA") {
				validate = 1;
				$('#PromotionPost').addClass('has-error');
				$('#PromotionPost').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_PromotionPost').length == 0) {
					$('<span id="span_PromotionPost" class="help-block">Require *</span>').insertAfter('#PromotionPost');
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

	$('#PromotionRecommend').change(function() {
		if ($('#PromotionRecommend').val() == 'Yes') {
			$('#PromotionPostId').show();
			//getProcess();
		} else {
			$('#PromotionPostId').hide();
		}



	});

	$('#PromotionPost').change(function() {
		$('#hiddenPost').val($("#PromotionPost option:selected").text());

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
				$('#PromotionPost').html(Resp);
				$('select').formSelect();
			}

		}

		xmlhttp.open("GET", "../Controller/getRecommendLevel.php?EmpID=" + $('#EmpId').val(), true);
		xmlhttp.send();

	}

	$('#NewPLIPercent').change(function() {
		$('#NewPLI').val(($("#NewCTC").val() * $("#NewPLIPercent").val()) / 100);
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
		var d = new Date();

		var month = d.getMonth() + 1;
		var day = d.getDate();
		var PromotionPost = '';
		var PromotionID = '';
		$('#ComForm').show();
		var tr = $(el).closest('tr');
		var EmplID = tr.find('.EmployeeId').text();
		var AprMonth1 = tr.find('.AprMonth').text();
		var EmpName = 'CAP Request Status (' + EmplID + '/' + tr.find('.EmployeeName').text() + ')';
		//alert(EmpName);
		$('#aprEmpID').val(EmplID);
		$('#PromotionPost').empty();
		//alert(PromotionID);
		$.ajax({
			url: "../Controller/getRecommendLevel.php?EmpID=" + EmplID,
			success: function(result) {
				if (result != '') {
					//alert(result); 
					$('#PromotionPost').append(new Option("---Select---", "NA"));
					var Data = result.split('|$$|');
					jQuery.each(Data, function(i, val) {
						if (val != '') {
							arr = val.split('|$|');
							//alert(arr[0]);
							//alert(arr[1]);
							$('#PromotionPost').append('<option value="' + arr[0] + '">' + '' + arr[1] + '</option>');

						}

					});
				}
				$('select').formSelect();
			}
		});

		//$('#PromotionPost').val(PromotionID);


		var aprmonth = '';
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
					var ReportTo = Data[16];
					var RatingAH = Data[19];
					var RatingHR = Data[20];
					var PromotionRecomend = Data[21];
					PromotionPost = Data[22];
					PromotionID = Data[32];
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

					$('#PromotionPost1').val(PromotionPost);
					$('#PromotionRecommend').val(PromotionRecomend);
					$('#HRStatus').val(HRStatus);
					$('#RepoTo').val(ReportTo);
					$('select').formSelect();
					if (PromotionRecomend == 'Yes') {
						$('#PromotionPost').val(PromotionID);
						$('#PromotionPostId').show();

					} else {
						$('#PromotionPost').val('NA');
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

					var AVG1 = Data[32];
					var AVG2 = Data[33];
					var AVG3 = Data[34];
					var AVG4 = Data[35];
					var AVG5 = Data[36];
					var AVG6 = Data[37];
					var AVG7 = Data[38];
					var AVG8 = Data[39];
					var AVG9 = Data[40];
					var AVG10 = Data[41];

					if (EvaluatorScore1 != '') {
						if (day > 20) {
							$("#EvaluatorScore1").attr("disabled", 'true');
							$("#EvaluatorScore2").attr("disabled", 'true');
							$("#EvaluatorScore3").attr("disabled", 'true');
							$("#EvaluatorScore4").attr("disabled", 'true');
							$("#EvaluatorScore5").attr("disabled", 'true');
							$("#EvaluatorScore6").attr("disabled", 'true');
							$("#EvaluatorScore7").attr("disabled", 'true');
							$("#EvaluatorScore8").attr("disabled", 'true');
							$("#EvaluatorScore9").attr("disabled", 'true');
							$("#EvaluatorScore10").attr("disabled", 'true');
							$("#PromotionRecommend").attr("disabled", 'true');
							$("#AppraisalPer").attr("disabled", 'true');
							$("#divAprCalc").hide();
							$("#EvaluatorScore10").attr("disabled", 'true');
							$("#PromotionPost").attr("disabled", 'true');
							$("#PromotionPostId").hide();
							$("#PromotionPostId1").show();
						}

					}
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

					$('#AVGScore1').val(AVG1);
					$('#AVGScore2').val(AVG2);
					$('#AVGScore3').val(AVG3);
					$('#AVGScore4').val(AVG4);
					$('#AVGScore5').val(AVG5);
					$('#AVGScore6').val(AVG6);
					$('#AVGScore7').val(AVG7);
					$('#AVGScore8').val(AVG8);
					$('#AVGScore9').val(AVG9);
					$('#AVGScore10').val(AVG10);
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
			url: "../Controller/get_AprData.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');
					//alert(result);
					var ctc = Data[0];
					var pli = Data[1];
					aprmonth = Data[2];
					ctc = parseFloat(ctc).toFixed(0);
					pli = parseFloat(pli).toFixed(0);

					var ctcy = ctc * 12;
					ctcy = parseFloat(ctcy).toFixed(0);
					$('#CurrentCTCY').val(ctcy);

					$('#CurrentCTCM').val(ctc);
					$('#CurrentPLI').val(pli);
					$('#AprMonth').val(aprmonth);
					//alert($("#AprMonth").val());
					$('#NewPLIPercent').val(Data[3]);

					$('#NewCTCM').val('0');
					$('#NewCTCY').val('0');
					$('#NewPLI').val('0');

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

		var loginId = "<?php echo $_SESSION['__user_logid'] ?>";

		var RTO = tr.find('.ReportTo').text();
		var AH = tr.find('.AH').text();
		var AHStatus = tr.find('.AHStatus').text();
		$('#AHStatus').val(AHStatus);

		if (AHStatus != 'Pending') {
			$.ajax({
				url: "../Controller/get_aprAHComment.php?ID=" + el.id,
				success: function(result) {
					if (result != '') {
						var Data = result.split('|$|');
						$('#Remarks1').val(Data[2]);
						$('#Remarks2').val(Data[3]);
						$('#Remarks3').val(Data[4]);
						$('#Remarks4').val(Data[5]);
						$('#Remarks5').val(Data[6]);
						$('#Remarks6').val(Data[7]);
						$('#Remarks7').val(Data[8]);
						$('#Remarks8').val(Data[9]);
						$('#Remarks9').val(Data[10]);
						$('#Remarks10').val(Data[11]);

						if (day > 20) {
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


		}


		/*if(loginId == RTO && RTO != AH)
    {
		$('#hideButtons').hide();
	}
	else
	{
		$('#hideButtons').show();
	}*/

		if (loginId == RTO && RTO != AH && day > 20) {
			$("#EvaluatorScore1").attr("disabled", 'true');
			$("#EvaluatorScore2").attr("disabled", 'true');
			$("#EvaluatorScore3").attr("disabled", 'true');
			$("#EvaluatorScore4").attr("disabled", 'true');
			$("#EvaluatorScore5").attr("disabled", 'true');
			$("#EvaluatorScore6").attr("disabled", 'true');
			$("#EvaluatorScore7").attr("disabled", 'true');
			$("#EvaluatorScore8").attr("disabled", 'true');
			$("#EvaluatorScore9").attr("disabled", 'true');
			$("#EvaluatorScore10").attr("disabled", 'true');
			$("#PromotionRecommend").attr("disabled", 'true');
			$("#AppraisalPer").attr("disabled", 'true');
			$("#divAprCalc").hide();
			$("#EvaluatorScore10").attr("disabled", 'true');
			$("#PromotionPost").attr("disabled", 'true');
		} else {
			$("#EvaluatorScore1").removeAttr("disabled");
			$("#EvaluatorScore2").removeAttr("disabled");
			$("#EvaluatorScore3").removeAttr("disabled");
			$("#EvaluatorScore4").removeAttr("disabled");
			$("#EvaluatorScore5").removeAttr("disabled");
			$("#EvaluatorScore6").removeAttr("disabled");
			$("#EvaluatorScore7").removeAttr("disabled");
			$("#EvaluatorScore8").removeAttr("disabled");
			$("#EvaluatorScore9").removeAttr("disabled");
			$("#EvaluatorScore10").removeAttr("disabled");
			$("#PromotionRecommend").removeAttr("disabled");
			$("#AppraisalPer").removeAttr("disabled");
			$("#EvaluatorScore10").removeAttr("disabled");
			$("#PromotionPost").removeAttr("disabled");
		}




		$.ajax({
			url: "../Controller/get_aprPerformance.php?EmpID=" + EmplID,
			success: function(result) {
				if (result != '') {
					$('#Performance').html(result);
				}
				$('select').formSelect();
			}
		});


		/////////////////////////////////////////////////////////////////////////////////////////
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


	function calcCTC(e) {
		//alert($("#takehome").val());
		var KeyID = (window.event) ? event.keyCode : e.which;
		var aprper = '';
		if ((KeyID >= 48 && KeyID <= 57) || KeyID == 8) {
			//alert($("#AppraisalPer").val());
			if (KeyID == 48) {
				aprper = $("#AppraisalPer").val() + 0;
			} else if (KeyID == 49) {
				aprper = $("#AppraisalPer").val() + 1;
			} else if (KeyID == 50) {
				aprper = $("#AppraisalPer").val() + 2;
			} else if (KeyID == 51) {
				aprper = $("#AppraisalPer").val() + 3;
			} else if (KeyID == 52) {
				aprper = $("#AppraisalPer").val() + 4;
			} else if (KeyID == 53) {
				aprper = $("#AppraisalPer").val() + 5;
			} else if (KeyID == 54) {
				aprper = $("#AppraisalPer").val() + 6;
			} else if (KeyID == 55) {
				aprper = $("#AppraisalPer").val() + 7;
			} else if (KeyID == 56) {
				aprper = $("#AppraisalPer").val() + 8;
			} else if (KeyID == 57) {
				aprper = $("#AppraisalPer").val() + 9;
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
			//alert($("#plipercent").val());
			return true;

		} else {
			return false;
		}
		$('select').formSelect();
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>