<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

if (isset($_GET['nmonth'], $_GET['nyear']) && $_GET['nmonth'] != "" && $_GET['nyear'] != "") {
	if (is_numeric($_GET['nmonth'], $_GET['nyear'])) {
		$month = cleanUserInput($_GET['nmonth']);
		$year = cleanUserInput($_GET['nyear']);
	}
}

// echo $month . " " . $year;
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

$userID = clean($_SESSION['__user_logid']);

$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = '';
$classvarr = "'.byID'";
$date_From = $acc = "";
?>
<style>
	.collapsible-body_trst {
		display: none;
		border-bottom: 1px solid #ddd;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		padding: 2rem;
		padding-top: 0px;
	}
</style>
<script type="text/javascript">
	$(function() {
		$('#monthYear').datepicker_M({
			format: 'mmmm yyyy'
		});
		$(document).on("click blur focus change", ".pika-select", function() {
			$(".datepicker_M-day-button[data-pika-day='1']").trigger("click");
			$('select').formSelect();

		});
		$('select').formSelect();
		$(".datepicker_M-cancel").removeClass("btn-flat").addClass("btn close-btn").css("margin-right", "10px");
		$(".datepicker_M-done").removeClass("btn-flat").addClass("btn");

		$(".datepicker_M-done").click(function() {
			var month = $("select.pika-select.pika-select-month :selected").val();
			var year = $("select.pika-select.pika-select-year :selected").val();
			var month_text = $("select.pika-select.pika-select-month option:selected").text();

			$('#monthYear').val(month_text + " " + year);
			$('select').formSelect();
			var brStatus = $('#brStatus').val();
			if (brStatus == 'Acknowledged') {
				month = parseInt(month) + 1;
				location.href = 'BriefingAgent.php?nmonth=' + month + '&nyear=' + year;
			} else {
				//$('#monthYear').closest('div').addClass('has-error');
				//	$('#monthYear').html('');

				alert_msg = "Please select acknowledged data.";

				$(function() {
					toastr.error(alert_msg)
				});
				return false;

			}

		})


		$('select').formSelect();
		$(".schema-form-section input").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	});
</script>
<style>
	.ui-accordion .ui-accordion-icons {
		text-align: center;
	}

	.datepicker_M-table-wrapper,
	.datepicker_M-date-display {
		display: none;
	}

	.datepicker_M-calendar-container {
		overflow: hidden;
		padding: 0px !important;
	}

	.datepicker_M-modal {
		max-width: 350px;
	}
</style>

<script>
	$(function() {
		$("#accordion").accordion({
			collapsible: true,
			heightStyle: "content"
		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Briefing </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Briefing </h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$view_for_query = "";
				$user_status = clean($_SESSION['__user_status']);
				$user_desg = clean($_SESSION['__user_Desg']);
				if ($user_status == 6 || $user_status == 5 || $user_status == 4) {
					$view_for_query = " and (a.view_for='All' or a.view_for='onFloor' or a.view_for='CSA' or a.view_for='Support' ) ";

					if ($user_desg != 'CSA' && $user_desg != 'Senior CSA') {
						$view_for_query = " and (a.view_for='All' or a.view_for='onFloor' or a.view_for='Support' ) ";
					}
					if ($user_desg == 'CSA' || $user_desg == 'Senior CSA') {
						$view_for_query = " and (a.view_for='All' or a.view_for='onFloor' or a.view_for='CSA' ) ";
					}
				} else {
					$location = URL . 'Login';
					echo "<script>location.href='" . $location . "'</script>";
				}
				if (isset($userID) || $userID != "") {

					$movedate = "";
					// $q1 = "select EmployeeID,updated_on,new_cm_id from tbl_client_toclient_move where EmployeeID='" . $userID . "' and  flag='FM' order by id desc";
					$q1 = "select EmployeeID,updated_on,new_cm_id from tbl_client_toclient_move where EmployeeID=? and  flag='FM' order by id desc";
					$stmt = $conn->prepare($q1);
					$stmt->bind_param("s", $userID);
					$stmt->execute();
					$clientmove_query = $stmt->get_result();
					// print_r($clientmove_query);
					// $clientmove_query = $myDB->rawQuery($q1);
					$client_update_date = "";
					if ($clientmove_query->num_rows > 0) {
						foreach ($clientmove_query as $clientmove_query_val) {
							$client_update_date = $clientmove_query_val['updated_on'];
						}
					}
					// $processmove_query = $myDB->rawQuery("select EmployeeID,updated_on,new_cm_id from tbl_oh_tooh_move where EmployeeID='" . $userID . "' and  flag='FM' order by id desc");
					$processmove_queryQry = "select EmployeeID,updated_on,new_cm_id from tbl_oh_tooh_move where EmployeeID=? and  flag='FM' order by id desc";
					$stmt = $conn->prepare($processmove_queryQry);
					$stmt->bind_param("s", $userID);
					$stmt->execute();
					$processmove_query = $stmt->get_result();
					// print_r($processmove_query);
					// die;
					$process_update_date = "";
					if ($processmove_query->num_rows > 0) {
						foreach ($processmove_query as $processmove_query_val) {
							$process_update_date = $processmove_query_val['updated_on'];
						}
					}
					if ($client_update_date != "" && $process_update_date != "") {
						if ($client_update_date >= $process_update_date) {
							$movedate = $client_update_date;
						} else {
							$movedate = $process_update_date;
						}
					} else
			if ($client_update_date != "") {
						$movedate = $client_update_date;
					} else
			if ($process_update_date != "") {
						$movedate = $process_update_date;
					}
					$nmonth = "";
					$addnew_query = "";
					$nyear = "";
					$brStatus = 'Pending';
					$dateLimit = date('Y-m-01', strtotime('-1 month'));


					// echo $month . "" . $year;
					if (($month && $year) && $month != "" &&  $year != "") {
						// $nmonth = ($_GET['nmonth'] - 1);
						// $nyear = $_GET['nyear'];
						$nmonth = $month - 1;
						$nyear = $year;
						$addnew_query .= " and MONTH(a.fromdate)= '" . $month . "'  and YEAR(a.fromdate)= '" . $nyear . "' and  acc.id is not  null";
						$brStatus = 'Acknowledged';
					} else {
						$brStatus = 'Pending';
						$addnew_query .= " and  cast(a.fromdate as date)>='" . $dateLimit . "' and acc.id is null";
					}
					// $sqlConnect2 = "SELECT  a.fromdate,a.heading,a.id,a.remark1,a.remark2,a.remark3,a.quiz,a.uploaded_file,a.TotalQuestionNum,a.cm_id,a.view_for, a.emp_status,b.EmployeeID,b.EmployeeID ,b.clientname,b.Process,b.sub_process,	acc.id as ac_id,acc.EmployeeID as AGENTID FROM brf_briefing a INNER JOIN whole_details_peremp b ON a.cm_id=b.cm_id LEFT OUTER JOIN brf_acknowledge acc ON a.id=acc.BriefingId and b.EmployeeID=acc.EmployeeID where b.EmployeeID='" . $userID . "' and a.EnableStatus=1 and a.fromdate<=now() $view_for_query ";
					// /*$sqlConnect2 = "SELECT a.*,b.EmployeeID ,b.clientname,b.Process,b.sub_process,	acc.id as ac_id,acc.EmployeeID as AGENTID FROM brf_briefing a INNER JOIN whole_details_peremp b ON a.cm_id=b.cm_id LEFT OUTER JOIN brf_acknowledge acc ON a.id=acc.BriefingId and b.EmployeeID=acc.EmployeeID where b.EmployeeID='".$userID."' and a.EnableStatus=1 and a.fromdate<=now() $view_for_query ";*/
					// if ($movedate != "") {
					// 	$sqlConnect2 .= " and  a.fromdate>= '" . $movedate . "'";
					// } else {
					// 	$sqlConnect2 .= " and  cast(a.fromdate as date)>= cast(b.DOJ as date)";
					// }
					// $sqlConnect2 .= " $addnew_query  order by a.id desc ";


					if ($movedate != "") {
						$sqlConnect2 = "SELECT  a.fromdate,a.heading,a.id,a.remark1,a.remark2,a.remark3,a.quiz,a.uploaded_file,a.TotalQuestionNum,a.cm_id,a.view_for, a.emp_status,b.EmployeeID,b.EmployeeID ,b.clientname,b.Process,b.sub_process,	acc.id as ac_id,acc.EmployeeID as AGENTID FROM brf_briefing a INNER JOIN whole_details_peremp b ON a.cm_id=b.cm_id LEFT OUTER JOIN brf_acknowledge acc ON a.id=acc.BriefingId and b.EmployeeID=acc.EmployeeID where b.EmployeeID=? and a.EnableStatus=1 and a.fromdate<=now() $view_for_query and  a.fromdate>= ? $addnew_query  order by a.id desc";
						// $sqlConnect2 .= " and  a.fromdate>= '" . $movedate . "'";
						$stmt = $conn->prepare($sqlConnect2);
						$stmt->bind_param("ss", $userID, $movedate);
						$stmt->execute();
					} else {
						$sqlConnect2 = "SELECT  a.fromdate,a.heading,a.id,a.remark1,a.remark2,a.remark3,a.quiz,a.uploaded_file,a.TotalQuestionNum,a.cm_id,a.view_for, a.emp_status,b.EmployeeID,b.EmployeeID ,b.clientname,b.Process,b.sub_process,	acc.id as ac_id,acc.EmployeeID as AGENTID FROM brf_briefing a INNER JOIN whole_details_peremp b ON a.cm_id=b.cm_id LEFT OUTER JOIN brf_acknowledge acc ON a.id=acc.BriefingId and b.EmployeeID=acc.EmployeeID where b.EmployeeID=? and a.EnableStatus=1 and a.fromdate<=now() $view_for_query and  cast(a.fromdate as date)>= cast(b.DOJ as date) $addnew_query  order by a.id desc";
						// $sqlConnect2 .= " and  cast(a.fromdate as date)>= cast(b.DOJ as date)";
						$stmt = $conn->prepare($sqlConnect2);
						$stmt->bind_param("s", $userID);
						$stmt->execute();
					}
					// $sqlConnect2 .= " $addnew_query  order by a.id desc ";

					// echo $sqlConnect2;

					// $myDB = new MysqliDb();
					// $result2 = $myDB->rawQuery($sqlConnect2);
					// $error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					$result2 = $stmt->get_result();
					// print_r($result2);
				?>
					<div class='col s12 m12 no-padding'>
						&nbsp;&nbsp; Briefing Status <span style="padding-left: 15px;color:#19aec4;"> Briefing Pending = <b><span id='unread_id'>0</span></b> </span>
						<span style="padding-left: 15px;color:#ffc41e;">Briefing Acknowledged = <b><span id='read_id'>0</span></b></span>
						<span style="padding-left: 15px;color: #8cc63e;"> Quiz Pending= <b><span id='qunread_id'>0</span></b></span>
						<span style="padding-left: 15px;color: #ffc41e;"> Quiz Attempted= <b><span id='attempt_id'>0</span></b></span>
						<div class='input-field col s12 m12 '>
							<hr>
						</div>
					</div>
					<div class='input-field col s6 m6 brStatusClass'>
						<select name='brStatus' id='brStatus'>
							<option value='Pending' <?php if ($brStatus == 'Pending') {
														echo "selected";
													} ?>>Pending</option>
							<option value='Acknowledged' <?php if ($brStatus == 'Acknowledged') {
																echo "selected";
															} ?>>Acknowledged</option>
						</select>
						<label for="brStatus" class="active-drop-down active"> Briefing Status </label>
					</div>
					<div class='input-field col s6 m6 '>
						<input type="text" name="monthYear" id="monthYear" placeholder='Select Month / Year' value='<?php echo $date_From; ?>'>
					</div>
					<!--<div  class='input-field col s6 m6 ' > 
					<button type="button"  value="Search Briefing"  name="send" id="send" class="btn waves-effect waves-green  ">Search Briefing</button>
				</div>-->


					<div id="accordion1" class="col s12 m12 l12 no-padding">
						<ul class="collapsible">
							<?php
							if ($result2->num_rows > 0) {
								$id = "";
								$clientname = "";
								$process = '';
								$subprocess = '';
								$bheading = "";
								$remark1 = '';
								$remark2 = '';
								$remark3 = '';
								$enable_status = "";
								$view_for = "";
								$unread = 0;
								$qunread = 0;
								$read = 0;
								$quiz_attempt = 0;
								foreach ($result2 as $key => $val) {
									$bheading = "";
									$pretag = '<pre>';
									$pretagEnd = '</pre>';
									$briefingId = $val['id'];
									$fromdate = $val['fromdate'];
									$view_for = $val['view_for'];
									$cm_id = $val['cm_id'];
									$emp_status = $val['emp_status'];
									//$clientname=$val['clientname'];
									//$process=$val['process'];
									//$subprocess=$val['subprocess'];
									//$bheading=stripslashes($val['heading']);
									$bheading .= date('Y-m-d', strtotime($val['fromdate']));
									$bheading .= ' - ' . stripslashes($val['heading']);
									$remark1 = $pretag . stripslashes($val['remark1']) . $pretagEnd;
									if (trim($val['remark2']) != "") {
										$remark2 = $pretag . stripslashes($val['remark2']) . $pretagEnd;
									} else {
										$remark2 = stripslashes($val['remark2']);
									}
									if (trim($val['remark3']) != "") {
										$remark3 = $pretag . stripslashes($val['remark3']) . $pretagEnd;
									} else {
										$remark3 = stripslashes($val['remark3']);
									}
									$ac_id = $val['ac_id'];
									$attempt = '';
									// $select_question = "select * from brf_question where BriefingID='" . $briefingId . "'";
									$select_question = "select * from brf_question where BriefingID=?";
									$stmt = $conn->prepare($select_question);
									$stmt->bind_param("i", $briefingId);
									$stmt->execute();
									$Qresult = $stmt->get_result();
									// $Qresult = $myDB->rawQuery($select_question);
									// $error = $myDB->getLastError();
									// $QrowCount = $myDB->count;
									// $select_attempted = "select * from brf_quiz_attempted where BriefingID='" . $briefingId . "' and EmployeeID='" . $userID . "'";
									$select_attempted = "select * from brf_quiz_attempted where BriefingID=? and EmployeeID=?";
									$stmt1 = $conn->prepare($select_attempted);
									$stmt1->bind_param("is", $briefingId, $userID);
									$stmt1->execute();
									$Quizresult = $stmt1->get_result();
									// $Quizresult = $myDB->rawQuery($select_attempted);
									// $error = $myDB->getLastError();
									// $rowCount = $myDB->count;
									if ($Quizresult->num_rows > 0) {
										$attempt = 'Yes';
									} else {
										$attempt = 'No';
									}
									$EmployeeID = $val['AGENTID'];
									$file_name = stripslashes($val['uploaded_file']);
									$quiz = $val['quiz'];
									if ($Qresult->num_rows > 0) {
										$quiz = 'Yes';
										$total_question_num = $Qresult->num_rows;
									} else {
										$quiz = 'No';
										$total_question_num = 0;
									}
									//echo " quiz=".$quiz;

									$bfid = $val['id'];
									$style = 'display:Block;';

									if ($ac_id == "") {
										$color = "color:#1c94c4;";
										$title = 'Unread';
										$unread = $unread + 1;
										$style2 = "display:block;";
										$style = 'display:none;';
									} else if ($ac_id != "" && $attempt == 'No' && $quiz == 'Yes') {
										$color = "color: blue;";
										$title = 'Quiz Pending';
										$qunread = $qunread + 1;
										$read = $read + 1;
										$style2 = "display:none;";
										$style = 'display:block;';
									} else if ($ac_id != "" && $attempt == 'Yes') {
										$color = "color:#eb8f00;";
										$title = 'Read';
										$read = $read + 1;
										$quiz_attempt = $quiz_attempt + 1;
										$style2 = "display:block;";
										$style = 'display:none;';
									} else if ($ac_id != "" && ($quiz == 'No' || $quiz == '')) {
										$color = "color:#eb8f00;";
										$title = 'Read';
										$read = $read + 1;
										$style2 = "display:block;";
										$style = 'display:none;';
									}

							?>
									<li>
										<!--<span style="<?php echo $color; ?>" class="hover_color" title="<?php echo $title; ?>" id='heading_id<?php echo $val['id']; ?>' ><?php echo $bheading; ?> </span>-->
										<div class="collapsible-header" style="<?php echo $color; ?>" title="<?php echo $title; ?>" id="heading_id<?php echo $val['id']; ?>"><b><?php echo $bheading; ?></b></div>

										<div class="collapsible-body collapsible-body_trst">
											<div id='brief_id<?php echo $val['id']; ?>' style='<?php echo $style2; ?>'>
												<div class=" clearfix">
													<?php echo $remark1; ?>
												</div>
												<div class=" clearfix">
													<?php echo $remark2; ?>
												</div>

												<?php
												$fdisplay = "";

												if ($file_name != "" && file_exists(ROOT_PATH . 'briefingDoc/' . $file_name)) {
													$fdisplay = "display:none;";
												?>



													<div id='ifb'> <iframe src="<?php echo URL . 'briefingDoc/' . $file_name; ?>?url=<?php echo './briefingDoc/' . $file_name; ?>&embedded=true" style="width:99%; height:300px;" frameborder="0"></iframe>

													</div>
													<div style="padding-bottom:10px;">
														<!--<label>
				     			 <a   class="btn-floating  "  target='_blank' title='View Document' data-position="bottom" onclick="openWindow('<?php echo URL . 'briefingDoc/' . $file_name; ?>','acck<?php echo $val['id']; ?>')" >View</i></a> </label>-->

													</div>
												<?php
												}
												// if($attempt!='Yes' and $ac_id==""){ 
												?>

												<a id="acck<?php echo $val['id']; ?>" style="cursor:pointer;font-weight: bold; color: #1c94c4; " title="Click me" onclick="acknowledge_me('<?php echo $val['id']; ?>','<?php echo $userID; ?>','<?php echo $total_question_num; ?>','<?php echo $fromdate; ?>','<?php echo $emp_status; ?>','<?php echo $cm_id; ?>','<?php echo $view_for; ?>')">Acknowledge</a>
												<div class='bborder'>&nbsp;</div>
												<?php //} 
												?>

											</div>

											<div id="divacck<?php echo $val['id']; ?>" style="<?php echo $style; ?>">
												<!-- <label for="quiz">Quiz</label><br>-->

												<?php
												if ($attempt != 'Yes') {
													if (($total_question_num > 0) && ($quiz = 'Yes')) {
														$l = 1;
														foreach ($Qresult as $key => $questionArray) {

															$questionId = $questionArray['QuestionID'];
															$question = $questionArray['Question'];
															$optionA = $questionArray['Option1'];
															$optionB = $questionArray['Option2'];
															$optionC = $questionArray['Option3'];
															$optionD = $questionArray['Option4'];
												?>

															<div class="input-field col s12 m12">

																<input type='hidden' name='question_id' id='questionid<?php echo $bfid . '_' . $l; ?>' value="<?php echo $questionId; ?>">
																Q <?php echo $l; ?>: <?php echo $question; ?>

															</div>
															<div class="input-field col s6 m6">
																A. <input type='radio' name='option<?php echo $l; ?>' id='option<?php echo $bfid . '_' . $l; ?>_1' value="A" title="OptionA">
																<label for="option<?php echo $bfid . '_' . $l; ?>_1" class="detach_radio"><?php echo $optionA; ?> </label>
															</div>
															<div class="input-field col s6 m6">

																B. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_2" value="B" title="OptionB">
																<label for="option<?php echo $bfid . '_' . $l; ?>_2" class="detach_radio"><?php echo $optionB; ?> </label>

															</div>
															<div class="input-field col s6 m6">

																C. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_3" value="C" title="OptionC">
																<label for="option<?php echo $bfid . '_' . $l; ?>_3" class="detach_radio"><?php echo $optionC; ?> </label>
															</div>

															<div class="input-field col s6 m6">
																D. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_4" title="OptionA" value="D">
																<label for="option<?php echo $bfid . '_' . $l; ?>_4" class="detach_radio"><?php echo $optionD; ?> </label>
															</div>

														<?php
															$l++;
														}

														?>
														<button type="button" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-green " title="Click me" onclick="quiz_attempt('<?php echo $val['id']; ?>','<?php echo $userID; ?>','<?php echo $total_question_num; ?>','<?php echo $ac_id; ?>')">Submit</button>

														</span>
												<?php
													}
												}
												?>
											</div>

										</div>
									<?php }
								echo "<input type='hidden' name='unread' id='unread' value='" . $unread . "' >";
								echo "<input type='hidden' name='unread' id='read' value='" . $read . "' >";
								echo "<input type='hidden' name='qunread' id='qunread' value='" . $qunread . "' >";
								echo "<input type='hidden' name='attempt' id='attempt' value='" . $quiz_attempt . "' >";

								//}
								//}
									?>
									</li>
								<?php
							} else {
								//echo '<div class="red-text">Briefing not Pending..</div>';
							} ?>
						</ul>
					</div>
				<?php } else { ?>
					<script>
						$('#alert_msg').html('<ul class="text-danger">Session has exspired, Please login again</ul>')
					</script>

				<?php } ?>


				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {
		<?php if ($nmonth != "") { ?>
			$('#monthYear').datepicker('setDate', new Date(<?php echo $nyear; ?>, <?php echo $nmonth; ?>, 1));
		<?php } ?>
		$('#qunread_id').html($('#qunread').val());
		$('#unread_id').html($('#unread').val());
		$('#read_id').html($('#read').val());
		$('#attempt_id').html($('#attempt').val());

		$('#brStatus').change(function() {
			var brStatus = $('#brStatus').val();
			if (brStatus == 'Pending') {
				location.href = 'BriefingAgent.php';
			}
		});

	});

	function acknowledge_me(brifing_id, employeeid, tqnum, fromdate, empStatus, cm_id, view_for) {
		//alert("Controller/brf_setAcknowledge.php?bid="+brifing_id+"&empid="+employeeid+"&fromdate="+fromdate+"&estatus="+empStatus+"&cm_id="+cm_id);
		$('#acck' + brifing_id).hide();
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/brf_setAcknowledge.php?bid=" + brifing_id + "&empid=" + employeeid + "&fromdate=" + fromdate + "&estatus=" + empStatus + "&cm_id=" + cm_id + "&view_for=" + view_for

		}).done(function(data) {
			//alert(data);
			if (tqnum != 0) {

				$('#brief_id' + brifing_id).hide();
				$('#divacck' + brifing_id).show();
				var qunread_id = $('#qunread_id').html();
				qunread_id = parseInt(qunread_id) + 1;
				$('#qunread_id').html(qunread_id);

			}
			var unread_id = $('#unread_id').html();
			unread_id = parseInt(unread_id) - 1;
			$('#unread_id').html(unread_id);
			var read_id = $('#read_id').html();
			read_id = parseInt(read_id) + 1;
			$('#read_id').html(read_id);
			$('#heading_id' + brifing_id).css({
				'color': 'orange'
			});
		});
	}

	function quiz_attempt(brifing_id, employeeid, tqnum) {

		var validate = 0;
		var alert_msg = '';
		question_array = '';
		if (tqnum != "" && tqnum != '0') {
			flag = 0;
			question_array = [];
			question_num = [];
			for (i = 1; i <= tqnum; i++) {
				// alert(tqnum +' '+'option'+brifing_id+'_'+i+'_1');
				var check_val = "";
				var newid = 'option' + brifing_id + '_' + i + '_1';
				if ($('#' + newid).is(':checked')) {
					//alert('option1 checked');
					check_val = $("input[name=option" + i + "]:checked").val();
				} else
				if ($('#option' + brifing_id + '_' + i + '_2').is(':checked')) {
					check_val = $("input[name=option" + i + "]:checked").val();
				} else
				if ($('#option' + brifing_id + '_' + i + '_3').is(':checked')) {
					check_val = $("input[name=option" + i + "]:checked").val();
				} else
				if ($('#option' + brifing_id + '_' + i + '_4').is(':checked')) {
					check_val = $("input[name=option" + i + "]:checked").val();
				}
				if (check_val == "") {
					flag = 1;
				} else {

					question_array[i] = check_val;
				}
				var qid = 'questionid' + brifing_id + '_' + i;
				question_num[i] = $('#' + qid).val();

			}
			if (flag == 1) {
				validate = 1;
				alert_msg += '<li>Please Answer to all questions. </li>';
			}
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		  		$('#alert_message').show().attr("class","SlideInRight animated");
		  		$('#alert_message').delay(5000).fadeOut("slow");
				return false;*/
				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			} else {
				$('#quiz' + brifing_id).hide();
			}
		}

		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/brf_setQuiz.php?bid=" + brifing_id + "&empid=" + employeeid + "&ans=" + question_array + "&qnum=" + question_num

		}).done(function(data) {
			$('#divacck' + brifing_id).hide();
			var qunread_id = $('#qunread_id').html();
			//alert('quiz pending='+qunread_id);
			qunread_id = parseInt(qunread_id) - 1;
			$('#qunread_id').html(qunread_id);
			var attempt_id = $('#attempt_id').html();
			attempt_id = parseInt(attempt_id) + 1;
			$('#attempt_id').html(attempt_id);
			//alert(qunread_id);
			$('#brief_id' + brifing_id).show();
			$('#heading_id' + brifing_id).css({
				'color:': 'orange'
			});
		});
	}

	function fdownload(ackid) {
		//alert('ackid');
		$("#" + ackid).show();

	}

	function openWindow(turl, id) {
		//window.open(turl, "", "width=400,height=300");
		$("#" + id).show();



		//var fileName = $(this).attr('name');


	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>