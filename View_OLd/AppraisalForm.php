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
#echo $ee = $_SERVER["PHP_SELF"];
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean($_SESSION['__user_logid']);
$Appraisal = clean($_SESSION["__Appraisal"]);
$__user_Name = clean($_SESSION['__user_Name']);
$__user_process = clean($_SESSION['__user_process']);
$__user_subprocess = clean($_SESSION['__user_subprocess']);
$__user_Desg = clean($_SESSION['__user_Desg']);


// if ($Appraisal == "No") {
// 	$location = URL . 'View';
// 	//header("Location: $location");
// 	echo "<script>location.href='" . $location . "'</script>";
// 	exit();
// }
/*if($_SESSION['__user_type']!='ADMINISTRATOR' || $_SESSION['__user_logid'] != 'CE10091236' || $_SESSION['__user_logid'] != 'CE03070003' || $_SESSION['__user_logid'] != 'CE12102224')
{
	$location= URL.'Error'; 
	//header("Location: $location");
	echo "<script>location.href='".$location."'</script>";
	exit();
}*/

// Trigger Button-Save Click Event and Perform DB Action
$btnSave = isset($_POST['btnSave']);
if ($btnSave) {
	//echo $Waringcount;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$q6 = '';
		if (cleanUserInput($_POST['needTraining']) != 'Yes') {
			$q6 = 'NA';
		} else {
			$q6 = cleanUserInput($_POST['q6']);
		}
		$addform = 'call apr_addappraisal("' . cleanUserInput($_POST['EmployeeName']) . '","' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['doj']) . '","' . cleanUserInput($_POST['LastDate']) . '","' . cleanUserInput($_POST['Process']) . '","' . cleanUserInput($_POST['SubProcess']) . '","' . cleanUserInput($_POST['CMID']) . '","' . cleanUserInput($_POST['ah']) . '","' . cleanUserInput($_POST['ReportTo']) . '","' . cleanUserInput($_POST['q1']) . '","' . cleanUserInput($_POST['q2']) . '","' . cleanUserInput($_POST['q3']) . '","' . cleanUserInput($_POST['q4']) . '","' . cleanUserInput($_POST['needTraining']) . '","' . $q6 . '","' . cleanUserInput($_POST['relocate']) . '")';
		$myDB = new MysqliDb();
		$result = $myDB->rawQuery($addform);
		$Error = $myDB->getLastError();
		$Id = $result[0]['id'];
		if (empty($Error)) {
			$question = 'call apr_addquestion("' . $Id . '","' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['YourScore1']) . '","' . cleanUserInput($_POST['YourScore2']) . '","' . cleanUserInput($_POST['YourScore3']) . '","' . cleanUserInput($_POST['YourScore4']) . '","' . cleanUserInput($_POST['YourScore5']) . '","' . cleanUserInput($_POST['YourScore6']) . '","' . cleanUserInput($_POST['YourScore7']) . '","' . cleanUserInput($_POST['YourScore8']) . '","' . cleanUserInput($_POST['YourScore9']) . '","' . cleanUserInput($_POST['YourScore10']) . '")';
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($question);

			/*$comment='call apr_addcomment("'.$Id.'","'.$_POST['EmployeeId'].'","'.$_POST['EmployeeName'].'","'.$_POST['Remarks'].'","'.$_POST['UserType'].'")';
		$myDB=new MysqliDb();
		$result3 = $myDB->rawQuery($comment);*/

			$Remarks = 'call apr_addRemark("' . $Id . '","' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['Remarks1']) . '","' . cleanUserInput($_POST['Remarks2']) . '","' . cleanUserInput($_POST['Remarks3']) . '","' . cleanUserInput($_POST['Remarks4']) . '","' . cleanUserInput($_POST['Remarks5']) . '","' . cleanUserInput($_POST['Remarks6']) . '","' . cleanUserInput($_POST['Remarks7']) . '","' . cleanUserInput($_POST['Remarks8']) . '","' . cleanUserInput($_POST['Remarks9']) . '","' . cleanUserInput($_POST['Remarks10']) . '")';
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

		// Trigger this Event when account head and userlogin same person..
		if (cleanUserInput($_POST['ah']) == cleanUserInput($_POST['EmployeeId'])) {
			$UpdateAH = 'call apr_UpdateAH("' . cleanUserInput($_POST['EmployeeId']) . '","","","","","' . $Id . '")';
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($UpdateAH);
			$Error = $myDB->getLastError();
			if (empty($Error)) {
				$AHMarking = 'call apr_QueUpdateAH("' . cleanUserInput($_POST['EmployeeId']) . '","' . cleanUserInput($_POST['YourScore1']) . '","' . cleanUserInput($_POST['YourScore2']) . '","' . cleanUserInput($_POST['YourScore3']) . '","' . cleanUserInput($_POST['YourScore4']) . '","' . cleanUserInput($_POST['YourScore5']) . '","' . cleanUserInput($_POST['YourScore6']) . '","' . cleanUserInput($_POST['YourScore7']) . '","' . cleanUserInput($_POST['YourScore8']) . '","' . cleanUserInput($_POST['YourScore9']) . '","' . cleanUserInput($_POST['YourScore10']) . '","' . $Id . '")';
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($AHMarking);
			} else {
				echo "<script>$(function(){ toastr.error(Not Saved '" . $Error . "'); }); </script>";
			}
		}
	}
}

$sql = "select EmployeeID, FirstName, LastName, EmployeeName, DOB, MotherName, Gender, BloodGroup, MarriageStatus, Spouse,MarriageDate, ChildStatus, FatherName, emp_level, emp_status, password, cm_id, df_id, DOJ, Process, sub_process,account_head, oh, qh, th, client_name, clientname, id, `function`, des_id, designation, dept_id, dept_name, status,ReportTo, Qa_ops, BatchID, Quality, DOD, Trainer, TL,month(str_to_date(concat(AppraisalMonth, '1, 2018'),'%M %d,%Y')) as AppraisalMonth,AppraisalMonth as apr from whole_dump_emp_data where EmployeeID=? ";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("s", $EmployeeID);
$selectQ->execute();
$select_query = $selectQ->get_result();
if ($select_query->num_rows > 0) {
	foreach ($select_query as $key => $value) {
		$DOJ = $value['DOJ'];
		$cm_id = $value['cm_id'];
		$ReportTo = $value['ReportTo'];
		$account_head = $value['account_head'];
		$oh = $value['oh'];
		$qh = $value['qh'];
		$th = $value['th'];
		$tmpdate = $value['apr'];
		//$DOJ1= date_format($DOJ,"d-M-Y");
		/*if($tmpdate < 10)
     	{
			$tmpdate = '0'.$tmpdate;
		}*/
		//$dd = "Y-".$tmpdate."-05";
		$dd = "05-" . $tmpdate . '-' . date('Y');
		//$lastDate = date($dd);
		$lastDate = $dd;
	}
}

$effectiveDate = strtotime("+2 months", strtotime($DOJ)); // returns timestamp
$lastDateCon = date('m-d', $effectiveDate);
#$lastDate=date("Y-".$lastDateCon);
#$lastDate=date('Y-m-5');

$DateFromDOJ = date('d', strtotime($DOJ));
if ($DateFromDOJ >= 15) {
	$UpdateMonth = strtotime("+1 months", strtotime($DOJ));
	$UpdateMonth = date('Y-m-01', $UpdateMonth);
} else {
	$UpdateMonth = $DOJ;
}
$ConvertMD = date('m-d', strtotime($UpdateMonth));
$ConcatCurrentY = date("Y-" . $ConvertMD);
$search = 'Pending';
$txt_search = isset($_POST['txt_search']);
if ($txt_search) {
	$search = cleanUserInput($_POST['txt_search']);
}
$title_text = 'Performance (' . date('M', strtotime("-1 years", strtotime(date('Y-m-d')))) . ' ' . date('Y', strtotime("-1 years", strtotime(date('Y-m-d')))) . ' - ' . date('M', strtotime("-1 months", strtotime(date('Y-m-d')))) . ' ' . date('Y', strtotime("-1 months", strtotime(date('Y-m-d')))) . ' ) Full Details';
?>

<script>
	$(document).ready(function() {
		$('#btnEdit').hide();
		$('#RemarksAdd').hide();
		$('#StartDate, #EndDate').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: new Date(),
			scrollInput: false,
		});
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
		});
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Appraisal Form</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Appraisal Form</h4>

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
				<input type="hidden" name="EmployeeName" id="EmployeeName" value="<?php echo $__user_Name; ?>">
				<input type="hidden" name="EmployeeId" id="EmployeeId" value="<?php echo $EmployeeID; ?>">
				<input type="hidden" name="UserType" id="UserType" value="<?php echo $__user_type; ?>">
				<input type="hidden" name="Process" id="Process" value="<?php echo $__user_process; ?>">
				<input type="hidden" name="SubProcess" id="SubProcess" value="<?php echo $__user_subprocess; ?>">
				<input type="hidden" name="ReportTo" id="ReportTo" value="<?php echo $ReportTo; ?>">
				<input type="hidden" name="CMID" id="CMID" value="<?php echo $cm_id; ?>">
				<input type="hidden" name="Designation" id="Designation" value="<?php echo $__user_Desg; ?>">
				<input type="hidden" name="ah" id="ah" value="<?php echo $account_head; ?>">
				<input type="hidden" name="qh" id="qh" value="<?php echo $qh; ?>">
				<input type="hidden" name="oh" id="oh" value="<?php echo $oh; ?>">
				<input type="hidden" name="th" id="th" value="<?php echo $th; ?>">
				<input type="hidden" name="DataId" id="DataId" />

				<div class="input-field col s12 m12 no-padding">
					<div class="input-field col s6 m6 ">
						<input type="text" id="doj" name="doj" value="<?php echo date_format(date_create($DOJ), "d-M-Y"); ?>" readonly="true">
						<label for="doj">DOJ</label>
					</div>

					<div class="input-field col s6 m6">
						<input type="text" id="LastDate" name="LastDate" value="<?php echo $lastDate; ?>" readonly="true">
						<label for="LastDate">Last date to fill the form</label>
					</div>
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6">
							<textarea class="materialize-textarea" id="q1" name="q1"></textarea>
							<label for="q1">Q.1 What are the current responsibilities held by you ?</label>
						</div>
						<div class="input-field col s6 m6">
							<textarea class="materialize-textarea" id="q2" name="q2"></textarea>
							<label for="q2">Q.2 What do you consider your important achievements of the past year?</label>
						</div>
					</div>
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6">
							<textarea class="materialize-textarea" id="q3" name="q3"></textarea>
							<label for="q3">Q.3 What are your goals for the next year?</label>
						</div>

						<div class="input-field col s6 m6">
							<textarea class="materialize-textarea" id="q4" name="q4"></textarea>
							<label for="q4">Q.4 What are your areas of improvement?</label>
						</div>
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
										<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks</th>
										<th style="width: 35px !important;">Evaluators score out of 10</th>
										<th style="width: 65px !important;">Evaluators remarks</th>
										<th style="width: 65px !important;">Approver score out of 10</th>
										<th style="width: 40px !important;">Avg. score</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td style="width: 100px !important;">Meeting job requirements on a timely basis</td>
										<td style="width: 50px !important;">
											<textarea class="materialize-textarea Score" name="YourScore1" id="YourScore1" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks1" name="Remarks1" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>
										</td>

										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore1" id="EvaluatorScore1" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore1" id="EvaluatorScore1" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks1"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore1" id="HRScore1" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore1" id="HRScore1" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore1" id="AVGScore1" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore1" id="AVGScore1" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Knowledge of job</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore2" id="YourScore2" placeholder="Score" class="Score"  onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore2" id="YourScore2" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks2" name="Remarks2" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore2" id="EvaluatorScore2" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore2" id="EvaluatorScore2" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks2"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore2" id="HRScore2" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore2" id="HRScore2" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore2" id="AVGScore2" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore2" id="AVGScore2" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Communication skills</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore3" id="YourScore3" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore3" id="YourScore3" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks3" name="Remarks3" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore3" id="EvaluatorScore3" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore3" id="EvaluatorScore3" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks3"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore3" id="HRScore3" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore3" id="HRScore3" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore3" id="AVGScore3" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore3" id="AVGScore3" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>4</td>
										<td>Interpersonal skills</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore4" id="YourScore4" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore4" id="YourScore4" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks4" name="Remarks4" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore4" id="EvaluatorScore4" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore4" id="EvaluatorScore4" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks4"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore4" id="HRScore4" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore4" id="HRScore4" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore4" id="AVGScore4" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore4" id="AVGScore4" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>5</td>
										<td>Initiative and creativity</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore5" id="YourScore5" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore5" id="YourScore5" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks5" name="Remarks5" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore5" id="EvaluatorScore5" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore5" id="EvaluatorScore5" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks5"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore5" id="HRScore5" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore5" id="HRScore5" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore5" id="AVGScore5" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore5" id="AVGScore5" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>6</td>
										<td>Decision making ability</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore6" id="YourScore6" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore6" id="YourScore6" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks6" name="Remarks6" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore6" id="EvaluatorScore6" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore6" id="EvaluatorScore6" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks6"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore6" id="HRScore6" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore6" id="HRScore6" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore6" id="AVGScore6" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore6" id="AVGScore6" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>7</td>
										<td>Adaptability and flexibility</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore7" id="YourScore7" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore7" id="YourScore7" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks7" name="Remarks7" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore7" id="EvaluatorScore7" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore7" id="EvaluatorScore7" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks7"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore7" id="HRScore7" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore7" id="HRScore7" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore7" id="AVGScore7" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore7" id="AVGScore7" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>8</td>
										<td>Team work</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore8" id="YourScore8" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore8" id="YourScore8" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks8" name="Remarks8" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore" id="EvaluatorScore8" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore8" id="EvaluatorScore8" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks8"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore8" id="HRScore8" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore8" id="HRScore8" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore8" id="AVGScore8" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore8" id="AVGScore8" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>9</td>
										<td>Time management</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore9" id="YourScore9" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore9" id="YourScore9" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks9" name="Remarks9" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore9" id="EvaluatorScore9" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore9" id="EvaluatorScore9" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks9"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore9" id="HRScore9" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore9" id="HRScore9" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore9" id="AVGScore9" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore9" id="AVGScore9" readonly="true"></textarea>
										</td>
									</tr>
									<tr>
										<td>10</td>
										<td>Problem solving skills</td>
										<td style="width: 50px !important;">
											<!--<input type="text" name="YourScore10" id="YourScore10" placeholder="Score" class="Score" onkeypress="javascript:return OnlyNum(event);">--><textarea class="materialize-textarea Score" name="YourScore10" id="YourScore10" placeholder="Score" onkeypress="javascript:return OnlyNum(event);"></textarea>
										</td>
										<td>
											<span>
												<textarea class="materialize-textarea" id="Remarks10" name="Remarks10" placeholder="Remarks" style="line-height: 1.2;" rows="4" cols="20"></textarea></span>

										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="EvaluatorScore10" id="EvaluatorScore10" readonly="true">--><textarea class="materialize-textarea Score" name="EvaluatorScore10" id="EvaluatorScore10" readonly="true"></textarea>
										</td>
										<td>
											<span>
												<div class="scroll" id="EvaluatorReamrks10"></div>
											</span>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="HRScore10" id="HRScore10" readonly="true">--><textarea class="materialize-textarea Score" name="HRScore10" id="HRScore10" readonly="true"></textarea>
										</td>
										<td style="width: 35px !important;">
											<!--<input type="text" name="AVGScore10" id="AVGScore10" readonly="true">--><textarea class="materialize-textarea Score" name="AVGScore10" id="AVGScore10" readonly="true"></textarea>
										</td>
									</tr>
								</tbody>
							</table>
						</fieldset>
					</div>

					<div class="input-field col s6 m6">
						<select name="needTraining" id="needTraining">
							<option value="NA">---Select---</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
						<label for="needTraining" class="active-drop-down active">Need training</label>
					</div>

					<div class="input-field col s6 m6">
						<select name="relocate" id="relocate">
							<option value="NA">---Select---</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
						<label for="needTraining" class="active-drop-down active">Willing to relocate</label>
					</div>

					<div class="input-field col s12 m12" id="divTraining">
						<textarea class="materialize-textarea " id="q6" name="q6"></textarea>
						<label for="q6">Q.6 If you need any support or training (related to your job profile) from the side of organization on above point</label>
						<!--<label for="q6">Q.6 Description of Training.</label>	-->
					</div>

					<div class="input-field col s12 m12" id="RemarksAdd">
						<textarea class="materialize-textarea " id="Remarks" name="Remarks"></textarea>
						<label for="Remarks">Additional remarks</label>
					</div>

					<div class="input-field col s12 m12" id="CommentShow" style="border: 1px solid #19aec4;display: none;"></div>



				</div>

				<div class="input-field col s12 m12 right-align " id="ButtonDiv">
					<?php
					/*$CurMonth=date("m");
$UpdateMonth = strtotime("-1 months", strtotime($UpdateMonth));
$UpdateMonth=date('m',$UpdateMonth);
if($CurMonth==$UpdateMonth)
{
   echo '<button type="submit" value="Save" name="btnSave" id="btnSave" class="btn waves-effect waves-green">Save</button>';
}*/

					$date = date_parse($_SESSION["__AppraisalMonth"]);
					$effectiveDate = date('Y') . '-' . $date['month'] . '-01';

					//$UpdateMonth = strtotime("-1 months", strtotime($effectiveDate));
					$UpdateMonth = strtotime($effectiveDate);
					$UpdateMonth = date('m', $UpdateMonth);
					$__Appraisal = clean($_SESSION["__Appraisal"]);
					if ($__Appraisal == 'Yes' && (date('d') >= 1 && date('d') <= 15)) {
						echo '<button type="submit" value="Save" name="btnSave" id="btnSave" class="btn waves-effect waves-green">Save</button>';
					}
					?>
					<button type="submit" value="Update" name="btnEdit" id="btnEdit" class="btn waves-effect waves-green">Dispute</button>
					<button type="submit" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn" style="display: none;">Cancel</button>
				</div>

				<!--
<div id="pnlTable">
<?php
$sqlConnect = "call apr_GetDataToApplicant('" . clean($_SESSION['__user_logid']) . "')";
$myDB = new MysqliDb();
$result = $myDB->rawQuery($sqlConnect);
$error = $myDB->getLastError();
$rowCount = $myDB->count;
if ($result && $rowCount > 0) { ?>
    <div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
	  <div class=""  >
		<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
			    <thead>
			        <tr>
						<th>SN.</th> 
						<th>Action</th> 
						<th>EmployeeName</th>
						<th>EmployeeId</th> 
						<th>DOJ</th> 
						<th>LastDateFill</th>
						
						<th>AH Status</th>
						<th>HR Status</th>
						<th>HR Rating</th>
						<th>Created On</th>
						<th class="hidden">Diff</th>
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

						echo '<td class="AHStatus" id="AHStatus' . $count . '">' . $value['AHStatus'] . '</td>';
						echo '<td class="HRStatus" id="HRStatus' . $count . '">' . $value['HRStatus'] . '</td>';
						echo '<td class="RatingHR" id="RatingHR' . $count . '">' . $value['RatingHR'] . '</td>';
						echo '<td class="CreatedOn" id="CreatedOn' . $count . '">' . $value['CreatedOn'] . '</td>';
						echo '<td class="Diff hidden" id="Diff' . $count . '">' . $value['Diff'] . '</td>';
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
-->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {

		//alert($('#EmployeeId').val());
		$('#divTraining').hide();

		getData();

		$('#needTraining').change(function() {
			if ($('#needTraining').val() == 'Yes') {
				$('#divTraining').show();
			} else {
				$('#divTraining').hide();
			}

		});

		$('#PromotionRecommend').change(function() {
			if ($('#PromotionRecommend').val() == 'Yes') {
				$('#PromotionPostId').show();
			} else {
				$('#PromotionPostId').hide();
			}
		});

		$('#btnEdit').click(function() {
			validate = 0;
			if ($('#Remarks').val() == "") {
				validate = 1;
				$('#Remarks').addClass('has-error');
				$('#Remarks').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_q1').length == 0) {
					$('<span id="span_q1" class="help-block">Require *</span>').insertAfter('#Remarks');
				}
			}

			if (validate == 1) {
				return false;
			}
		});


		$('#btnSave').click(function() {
			validate = 0;
			if ($('#q1').val() == "") {
				validate = 1;
				$('#q1').addClass('has-error');
				$('#q1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_q1').length == 0) {
					$('<span id="span_q1" class="help-block">Require *</span>').insertAfter('#q1');
				}
			}
			if ($('#q2').val() == "") {
				validate = 1;
				$('#q2').addClass('has-error');
				$('#q2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Q2').length == 0) {
					$('<span id="span_Q2" class="help-block"> Require *</span>').insertAfter('#q2');
				}
			}
			if ($('#q3').val() == "") {
				validate = 1;
				$('#q3').addClass('has-error');
				$('#q3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Q3').length == 0) {
					$('<span id="span_Q3" class="help-block">Require *</span>').insertAfter('#q3');
				}
			}
			if ($('#q4').val() == "") {
				validate = 1;
				$('#q4').addClass('has-error');
				$('#q4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Q4').length == 0) {
					$('<span id="span_Q4" class="help-block">Require *</span>').insertAfter('#q4');
				}
			}

			if ($('#YourScore1').val() == "") {
				validate = 1;
				$('#YourScore1').addClass('has-error');
				$('#YourScore1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore1').length == 0) {
					$('<span id="span_YourScore1" class="help-block">Require *</span>').insertAfter('#YourScore1');
				}
			}
			if ($('#YourScore2').val() == "") {
				validate = 1;
				$('#YourScore2').addClass('has-error');
				$('#YourScore2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore2').length == 0) {
					$('<span id="span_YourScore2" class="help-block">Require *</span>').insertAfter('#YourScore2');
				}
			}
			if ($('#YourScore3').val() == "") {
				validate = 1;
				$('#YourScore3').addClass('has-error');
				$('#YourScore3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore3').length == 0) {
					$('<span id="span_YourScore3" class="help-block">Require *</span>').insertAfter('#YourScore3');
				}
			}
			if ($('#YourScore4').val() == "") {
				validate = 1;
				$('#YourScore4').addClass('has-error');
				$('#YourScore4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore4').length == 0) {
					$('<span id="span_YourScore4" class="help-block">Require *</span>').insertAfter('#YourScore4');
				}
			}
			if ($('#YourScore5').val() == "") {
				validate = 1;
				$('#YourScore5').addClass('has-error');
				$('#YourScore5').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore5').length == 0) {
					$('<span id="span_YourScore5" class="help-block">Require *</span>').insertAfter('#YourScore5');
				}
			}
			if ($('#YourScore6').val() == "") {
				validate = 1;
				$('#YourScore6').addClass('has-error');
				$('#YourScore6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore6').length == 0) {
					$('<span id="span_YourScore6" class="help-block">Require *</span>').insertAfter('#YourScore6');
				}
			}
			if ($('#YourScore7').val() == "") {
				validate = 1;
				$('#YourScore7').addClass('has-error');
				$('#YourScore7').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore7').length == 0) {
					$('<span id="span_YourScore7" class="help-block">Require *</span>').insertAfter('#YourScore7');
				}
			}
			if ($('#YourScore8').val() == "") {
				validate = 1;
				$('#YourScore8').addClass('has-error');
				$('#YourScore8').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore8').length == 0) {
					$('<span id="span_YourScore8" class="help-block">Require *</span>').insertAfter('#YourScore8');
				}
			}
			if ($('#YourScore9').val() == "") {
				validate = 1;
				$('#YourScore9').addClass('has-error');
				$('#YourScore9').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore9').length == 0) {
					$('<span id="span_YourScore9" class="help-block">Require *</span>').insertAfter('#YourScore9');
				}
			}
			if ($('#YourScore10').val() == "") {
				validate = 1;
				$('#YourScore10').addClass('has-error');
				$('#YourScore10').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_YourScore10').length == 0) {
					$('<span id="span_YourScore10" class="help-block">Require *</span>').insertAfter('#YourScore10');
				}
			}


			if ($('#Remarks1').val().length < 50) {
				validate = 1;
				$('#Remarks1').addClass('has-error');
				$('#Remarks1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks1').length == 0) {
					$('<span id="span_Remarks1" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks1');
				}
			}
			if ($('#Remarks2').val().length < 50) {
				validate = 1;
				$('#Remarks2').addClass('has-error');
				$('#Remarks2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks2').length == 0) {
					$('<span id="span_Remarks2" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks2');
				}
			}
			if ($('#Remarks3').val().length < 50) {
				validate = 1;
				$('#Remarks3').addClass('has-error');
				$('#Remarks3').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks3').length == 0) {
					$('<span id="span_Remarks3" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks3');
				}
			}
			if ($('#Remarks4').val().length < 50) {
				validate = 1;
				$('#Remarks4').addClass('has-error');
				$('#Remarks4').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks4').length == 0) {
					$('<span id="span_Remarks4" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks4');
				}
			}
			if ($('#Remarks5').val().length < 50) {
				validate = 1;
				$('#Remarks5').addClass('has-error');
				$('#Remarks5').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks5').length == 0) {
					$('<span id="span_Remarks5" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks5');
				}
			}
			if ($('#Remarks6').val().length < 50) {
				validate = 1;
				$('#Remarks6').addClass('has-error');
				$('#Remarks6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks6').length == 0) {
					$('<span id="span_Remarks6" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks6');
				}
			}
			if ($('#Remarks7').val().length < 50) {
				validate = 1;
				$('#Remarks7').addClass('has-error');
				$('#Remarks7').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks7').length == 0) {
					$('<span id="span_Remarks7" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks7');
				}
			}
			if ($('#Remarks8').val().length < 50) {
				validate = 1;
				$('#Remarks8').addClass('has-error');
				$('#Remarks8').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks8').length == 0) {
					$('<span id="span_Remarks8" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks8');
				}
			}
			if ($('#Remarks9').val().length < 50) {
				validate = 1;
				$('#Remarks9').addClass('has-error');
				$('#Remarks9').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks9').length == 0) {
					$('<span id="span_Remarks9" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks9');
				}
			}
			if ($('#Remarks10').val().length < 50) {
				validate = 1;
				$('#Remarks10').addClass('has-error');
				$('#Remarks10').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Remarks10').length == 0) {
					$('<span id="span_Remarks10" class="help-block">Remark should be greater than 50 character</span>').insertAfter('#Remarks10');
				}
			}

			if ($('#needTraining').val() == "NA") {
				validate = 1;
				$('#needTraining').addClass('has-error');
				$('#needTraining').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_needTraining').length == 0) {
					$('<span id="span_needTraining" class="help-block">Require *</span>').insertAfter('#needTraining');
				}
			}

			if ($('#q6').val() == "" && $('#needTraining').val() == "Yes") {

				validate = 1;
				$('#q6').addClass('has-error');
				$('#q6').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_q6').length == 0) {
					$('<span id="span_q6" class="help-block">Require *</span>').insertAfter('#q6');
				}
			}



			if ($('#relocate').val() == "NA") {
				validate = 1;
				$('#relocate').addClass('has-error');
				$('#relocate').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_relocate').length == 0) {
					$('<span id="span_relocate" class="help-block">Require *</span>').insertAfter('#relocate');
				}
			}

			/*if($('#Remarks').val()==""){
			validate=1;
			$('#Remarks').addClass('has-error');
	     	$('#Remarks').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
			if($('#span_Remarks').length == 0)
			{
			   $('<span id="span_Remarks" class="help-block">Require *</span>').insertAfter('#Remarks');
			}
		}*/

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

	function getData() {
		var d = new Date();

		var month = d.getMonth() + 1;
		var day = d.getDate();

		$.ajax({
			url: "../Controller/get_aprApplicant.php?EmpID=" + $('#EmployeeId').val(),
			success: function(result) {
				if (result != '') {
					var elID = result;
					var AHStatus = '';
					var HRStatus = '';
					$('#ComForm').show();
					if (day > 5) {
						$('#btnSave').hide();
					}


					//alert('Hii');
					$('#btnCan').show();
					$.ajax({
						url: "../Controller/get_aprApplicantDetails.php?ID=" + elID,
						success: function(result) {
							if (result != '') {
								var Data = result.split('|$|');

								var ID = Data[0];
								var EmployeeName = Data[1];
								var EmployeeID = Data[2];
								var Doj = Data[3];
								//var WarningLetter =Data[4];
								//var WarningCount =Data[5];
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
								var PromotionPost = Data[22];
								AHStatus = Data[23];
								HRStatus = Data[24];
								var NTraining = Data[30];
								var Reloc = Data[31];

								$('#DataId').val(ID);
								$('#EmpName').val(EmployeeName);
								$('#EmpId').val(EmployeeID);
								$('#doj').val(Doj);
								//$('#WarningLetter').val(WarningLetter);
								//$('#WarningCount').val(WarningCount);
								$('#LastDate').val(LastDateFill);
								$('#q1').val(Q1);
								$('#q2').val(Q2);
								$('#q3').val(Q3);
								$('#q4').val(Q4);
								$('#needTraining').val(NTraining);
								if (NTraining == 'Yes') {
									$('#divTraining').show();
									$('#q6').val(Q6);
								} else {
									$('#divTraining').hide();
								}
								$('#relocate').val(Reloc);

								$('#AppraisalPer').val(RatingAH);
								$('#PromotionPost').val(PromotionPost);
								$('#PromotionRecommend').val(PromotionRecomend);
								$('#HRStatus').val(HRStatus);
								$('#RepoTo').val(ReportTo);
								$('select').formSelect();

								if (AHStatus == 'Approve' && HRStatus == 'Pending') {
									if (Data[29] == 0) {
										//$("#btnEdit").show();
										//$("#RemarksAdd").show();
									}

								}
								/*if(day>5)
				        {
							if(Data[29] ==0)
				        	{
					        	//alert(Data[29]);
								$("#btnEdit").show();
								$("#RemarksAdd").show();
							}
							else
							{
								$('#btnEdit').hide();
								$('#RemarksAdd').hide();
							}	
						}*/

								//$('#btnEdit').show();
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
						url: "../Controller/get_aprScore.php?ID=" + elID,
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

								if (day > 5) {
									if (EvaluatorScore1 != '') {
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
										$("#EvaluatorScore10").attr("disabled", 'true');
										$("#PromotionPost").attr("disabled", 'true');
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
						url: "../Controller/get_aprApplicantComment.php?ID=" + elID,
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

							}
							$('select').formSelect();
						}
					});

					$.ajax({
						url: "../Controller/get_aprAHComment.php?ID=" + elID,
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

					//var tr = $(el).closest('tr');
					//var EmplID = tr.find('.EmployeeId').text();
					$.ajax({
						url: "../Controller/get_aprPerformance.php?EmpID=" + $('#EmployeeId').val(),
						success: function(result) {
							if (result != '') {
								$('#Performance').html(result);
							}
							$('select').formSelect();
						}
					});

					if (day > 5) {
						$("#YourScore1").attr("disabled", 'true');
						$("#YourScore2").attr("disabled", 'true');
						$("#YourScore3").attr("disabled", 'true');
						$("#YourScore4").attr("disabled", 'true');
						$("#YourScore5").attr("disabled", 'true');
						$("#YourScore6").attr("disabled", 'true');
						$("#YourScore7").attr("disabled", 'true');
						$("#YourScore8").attr("disabled", 'true');
						$("#YourScore9").attr("disabled", 'true');
						$("#YourScore10").attr("disabled", 'true');

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

						$("#EvaluatorReamrks1").attr("disabled", 'true');
						$("#EvaluatorReamrks2").attr("disabled", 'true');
						$("#EvaluatorReamrks3").attr("disabled", 'true');
						$("#EvaluatorReamrks4").attr("disabled", 'true');
						$("#EvaluatorReamrks5").attr("disabled", 'true');
						$("#EvaluatorReamrks6").attr("disabled", 'true');
						$("#EvaluatorReamrks7").attr("disabled", 'true');
						$("#EvaluatorReamrks8").attr("disabled", 'true');
						$("#EvaluatorReamrks9").attr("disabled", 'true');
						$("#EvaluatorReamrks10").attr("disabled", 'true');

						$("#q1").attr("disabled", 'true');
						$("#q2").attr("disabled", 'true');
						$("#q3").attr("disabled", 'true');
						$("#q4").attr("disabled", 'true');
						$("#q6").attr("disabled", 'true');
						$("#needTraining").attr("disabled", 'true');
						$("#relocate").attr("disabled", 'true');
						$("#PromotionRecommend").attr("disabled", 'true');
						$("#AppraisalPer").attr("disabled", 'true');
						$("#EvaluatorScore10").attr("disabled", 'true');
						$("#PromotionPost").attr("disabled", 'true');
					}


					//var tr = $(el).closest('tr');
					//var AHStatus = tr.find('.AHStatus').text();
					//var HRStatus = tr.find('.HRStatus').text();

					if (AHStatus != 'Pending') {
						$.ajax({
							url: "../Controller/get_Comment.php?ID=" + elID,
							success: function(result) {
								if (result != '') {
									$('#CommentShow').html(result);
									$('#CommentShow').show();
								}
								$('select').formSelect();
							}
						});
					}


					if (AHStatus == 'Approve' && HRStatus == 'Pending') {
						$('#ButtonDiv').show();
					} else {
						//alert('there');
						//$('#ButtonDiv').hide();
						//$('#RemarksAdd').hide();
					}

					/*if(AHStatus=='Approve' && HRStatus=='Pending')
					 {
					 	$('#ButtonDiv').show();
					 }
					 else
					 {
					 	//alert('qqqq');
					 	$('#ButtonDiv').hide();
					 	$('#RemarksAdd').hide();
					 }*/




				}



			}
		});
	}

	function EditData(el) {
		$('#ComForm').show();
		$('#btnSave').hide();

		//alert('Hii');
		$('#btnCan').show();
		$.ajax({
			url: "../Controller/get_aprApplicantDetails.php?ID=" + el.id,
			success: function(result) {
				if (result != '') {
					var Data = result.split('|$|');

					var ID = Data[0];
					var EmployeeName = Data[1];
					var EmployeeID = Data[2];
					var Doj = Data[3];
					// var WarningLetter =Data[4];
					//var WarningCount =Data[5];
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
					var PromotionPost = Data[22];
					var AHStatus = Data[23];
					var HRStatus = Data[24];
					var NTraining = Data[30];
					var Reloc = Data[31];

					$('#DataId').val(ID);
					$('#EmpName').val(EmployeeName);
					$('#EmpId').val(EmployeeID);
					$('#doj').val(Doj);
					//$('#WarningLetter').val(WarningLetter);
					//$('#WarningCount').val(WarningCount);
					$('#LastDate').val(LastDateFill);
					$('#q1').val(Q1);
					$('#q2').val(Q2);
					$('#q3').val(Q3);
					$('#q4').val(Q4);
					$('#needTraining').val(NTraining);
					if (NTraining == 'Yes') {
						$('#divTraining').show();
						$('#q6').val(Q6);
					} else {
						$('#divTraining').hide();
					}
					$('#relocate').val(Reloc);

					$('#AppraisalPer').val(RatingAH);
					$('#PromotionPost').val(PromotionPost);
					$('#PromotionRecommend').val(PromotionRecomend);
					$('#HRStatus').val(HRStatus);
					$('#RepoTo').val(ReportTo);
					$('select').formSelect();
					if (Data[29] == 0) {
						//alert(Data[29]);
						/*$("#btnEdit").show();
						$("#RemarksAdd").show();*/
					} else {
						$('#btnEdit').hide();
						$('#RemarksAdd').hide();
					}
					//$('#btnEdit').show();
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
						$("#EvaluatorScore10").attr("disabled", 'true');
						$("#PromotionPost").attr("disabled", 'true');
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
			url: "../Controller/get_aprApplicantComment.php?ID=" + el.id,
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

				}
				$('select').formSelect();
			}
		});

		var tr = $(el).closest('tr');
		var EmplID = tr.find('.EmployeeId').text();
		$.ajax({
			url: "../Controller/get_aprPerformance.php?EmpID=" + EmplID,
			success: function(result) {
				if (result != '') {
					$('#Performance').html(result);
				}
				$('select').formSelect();
			}
		});

		$("#YourScore1").attr("disabled", 'true');
		$("#YourScore2").attr("disabled", 'true');
		$("#YourScore3").attr("disabled", 'true');
		$("#YourScore4").attr("disabled", 'true');
		$("#YourScore5").attr("disabled", 'true');
		$("#YourScore6").attr("disabled", 'true');
		$("#YourScore7").attr("disabled", 'true');
		$("#YourScore8").attr("disabled", 'true');
		$("#YourScore9").attr("disabled", 'true');
		$("#YourScore10").attr("disabled", 'true');

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

		$("#q1").attr("disabled", 'true');
		$("#q2").attr("disabled", 'true');
		$("#q3").attr("disabled", 'true');
		$("#q4").attr("disabled", 'true');
		$("#q6").attr("disabled", 'true');
		$("#needTraining").attr("disabled", 'true');
		$("#relocate").attr("disabled", 'true');
		$("#PromotionRecommend").attr("disabled", 'true');
		$("#AppraisalPer").attr("disabled", 'true');
		$("#EvaluatorScore10").attr("disabled", 'true');
		$("#PromotionPost").attr("disabled", 'true');

		var tr = $(el).closest('tr');
		var AHStatus = tr.find('.AHStatus').text();
		var HRStatus = tr.find('.HRStatus').text();

		if (AHStatus != 'Pending') {
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
		}


		if (AHStatus == 'Approve' && HRStatus == 'Pending') {
			$('#ButtonDiv').show();
		} else {
			//alert('qqqq');
			$('#ButtonDiv').hide();
			$('#RemarksAdd').hide();
		}
	}

	function OnlyNum(e) {
		var KeyID = (window.event) ? event.keyCode : e.which;
		if ((KeyID >= 48 && KeyID <= 57) || KeyID == 8) {
			return true;
		} else {
			return false;
		}
	}

	/*var tr = $('#Diff1').closest('tr');
	var Diffrence = tr.find('.Diff').text();
	var ClassPre = $("td").hasClass("Diff");
	if(ClassPre==true)
	{
		if(Diffrence >= 11)
		{
			$('#ButtonDiv').show();
		}
		else
		{
			$('#ButtonDiv').hide();
		}
	}*/
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>