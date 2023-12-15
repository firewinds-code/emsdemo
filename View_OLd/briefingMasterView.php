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
$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = '';
$classvarr = "'.byID'";

$userID = clean($_SESSION['__user_logid']);

?>
<script type="text/javascript">
	$(function() {
		$('#monthYear').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'MM yy',
			onClose: function(dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
				var brStatus = $('#brStatus').val();
				if (brStatus == 'Acknowledged') {
					month = parseInt(month) + 1;
					location.href = 'BriefingAgent.php?nmonth=' + month + '&nyear=' + year;
				} else {
					//$('#monthYear').closest('div').addClass('has-error');
					//	$('#monthYear').html('');

					alert_msg = "Please select acknowledged data.";
					$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
					$('#alert_message').show().attr("class", "SlideInRight animated");
					$('#alert_message').delay(5000).fadeOut("slow");
					return false;

				}
			}
		});
	});
</script>
<style>
	.ui-datepicker-calendar {
		display: none;
	}

	.collapsible-body_trst {
		display: none;
		border-bottom: 1px solid #ddd;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		padding: 2rem;
		padding-top: 0px;
	}
</style>
<link rel="stylesheet" href="<?php echo STYLE . 'jquery-ui.css'; ?>" />
<script src="<?php echo SCRIPT . 'jquery-ui.js"'; ?>"></script>

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
			<h4>Briefing <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="BriefingMaster.php" data-position="bottom" data-tooltip="Back"><i class="material-icons">arrow_back</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$view_for_query = "";
				$bid = "";
				if (isset($_GET['delid']) && ($_GET['delid']) != "") {
					if (is_numeric($_GET['delid'])) {
						$delid = ($_GET['delid']);
					}
				}
				$userid = clean($_SESSION['__user_logid']);
				if (isset($userid) || $userid != "") {

					if (isset($_GET['id']) && $_GET['id'] != "") {
						if (is_numeric($_GET['id'])) {
							$bid = $_GET['id'];
						}
					}
					// echo $bid;

					$movedate = "";
					// $sqlConnect2 = "SELECT a.* FROM brf_briefing a  where a.CreatedBy='" . $_SESSION['__user_logid'] . "' and a.id='" . $bid . "'";
					$sqlConnect2 = "SELECT a.* FROM brf_briefing a  where a.CreatedBy=? and a.id=?";
					$stmt = $conn->prepare($sqlConnect2);
					$stmt->bind_param("si", $userID, $bid);
					$stmt->execute();
					$result2 = $stmt->get_result();
					// print_r($result2);
					// die;
					// $myDB = new MysqliDb();
					// $result2 = $myDB->rawQuery($sqlConnect2);
				?>
					<style>
						h3:hover {
							color: #DBDBDB !important;
						}
					</style>
					<div id="accordion1" class="col s12 m12 l12 no-padding">
						<ul class="collapsible">
							<?php
							if ($result2) {
								$id = "";
								$clientname = "";
								$process = '';
								$subprocess = '';
								$bheading = "";
								$remark1 = '';
								$remark2 = '';
								$remark3 = '';
								$enable_status = "";

								$unread = 0;
								$qunread = 0;
								$read = 0;
								$quiz_attempt = 0;
								$pretag = '<pre>';
								$pretagEnd = '</pre>';
								foreach ($result2 as $key => $val) {
									$bheading = "";
									$briefingId = $val['id'];
									$fromdate = $val['fromdate'];
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
									//Fti$ac_id=$val['ac_id'];
									$attempt = '';

									$quiz = "";
									//$EmployeeID=$val['AGENTID'];
									$file_name = stripslashes($val['uploaded_file']);
									$quiz = $val['quiz'];
									$bfid = $val['id'];
							?>
									<li>
										<div class="collapsible-header" id="heading_id<?php echo $val['id']; ?>"><b><?php echo $bheading; ?></b></div>

										<div class="collapsible-body collapsible-body_trst">
											<span id='brief_id<?php echo $val['id']; ?>'>
												<div class="clearfix">
													<?php echo $remark1; ?>
												</div>
												<div class="clearfix">
													<?php echo $remark2; ?>
												</div>

												<?php if ($file_name != "" && file_exists(ROOT_PATH . 'briefingDoc/' . $file_name)) { ?>


													<div class="input-field col s12 m12 ">
														<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" target='_blank' href="<?php echo URL . 'briefingDoc/' . $file_name; ?>" data-position="bottom" data-tooltip="Download File"><i class="material-icons">File Download</i></a>

													</div>

												<?php
												}
												if ($quiz == 'Yes') { ?>
													<span>
														<?php
														// $select_question = "select * from brf_question where BriefingID='" . $briefingId . "'";
														$select_question = "select * from brf_question where BriefingID=?";
														$stmt = $conn->prepare($select_question);
														$stmt->bind_param("i", $briefingId);
														$stmt->execute();
														$Qresult = $stmt->get_result();
														// $Qresult = $myDB->rawQuery($select_question);
														// $my_error = $myDB->getLastError();
														// $rowCount = $myDB->count;
														if ($Qresult->num_rows > 0) {
															$l = 1;
															foreach ($Qresult as $key => $questionArray) {
																$questionId = $questionArray['QuestionID'];
																$question = $questionArray['Question'];
																$optionA = $questionArray['Option1'];
																$optionB = $questionArray['Option2'];
																$optionC = $questionArray['Option3'];
																$optionD = $questionArray['Option4'];
																$Answer = $questionArray['Answer'];
														?>

																<!--
		   					    <div class="input-field col s12 m12">
	     
							     <input type='hidden' name='question_id' id='questionid<?php echo $bfid . '_' . $l; ?>' value="<?php echo $questionId; ?>">
								  Q <?php echo $l; ?>: <?php echo $question; ?>
								  
							</div> 
						  
						    	<div class="input-field col s6 m6">
							    	A. <input type='radio' name='option<?php echo $l; ?>' id='option<?php echo $bfid . '_' . $l; ?>_1' value="A" title="OptionA"  >       
								    <label for="option<?php echo $bfid . '_' . $l; ?>_1" class="detach_radio"><?php echo $optionA; ?> </label>
								    
								    
								  
								    
							    </div>
							    <div class="input-field col s6 m6">
						      
							     B. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_2"  value="B" title="OptionB" >
							     <label for="option<?php echo $bfid . '_' . $l; ?>_2" class="detach_radio"><?php echo $optionB; ?> </label>
							        
						    </div>
						        <div class="input-field col s6 m6">
						
						     C. <input type='radio'  name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_3"  value="C" title="OptionC" >      
						     <label for="option<?php echo $bfid . '_' . $l; ?>_3" class="detach_radio"><?php echo $optionC; ?> </label>
						    </div>
						    
   						        <div class="input-field col s6 m6">
						     D. <input type='radio'  name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_4"  title="OptionA" value="D">  
						      <label for="option<?php echo $bfid . '_' . $l; ?>_4" class="detach_radio"><?php echo $optionD; ?> </label>
						    </div>-->
																<div class="input-field col s12 m12">

																	<input type='hidden' name='question_id' id='questionid<?php echo $bfid . '_' . $l; ?>' value="<?php echo $questionId; ?>">
																	Q <?php echo $l; ?>: <?php echo $question; ?>

																</div>
																<div class="input-field col s6 m6">
																	A. <input type='radio' name='option<?php echo $l; ?>' id='option<?php echo $bfid . '_' . $l; ?>_1' value="A" title="OptionA">
																	<label for="option<?php echo $bfid . '_' . $l; ?>_1" class="detach_radio active"><?php echo $optionA; ?> </label>
																</div>
																<div class="input-field col s6 m6">

																	B. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_2" value="B" title="OptionB">
																	<label for="option<?php echo $bfid . '_' . $l; ?>_2" class="detach_radio active"><?php echo $optionB; ?> </label>

																</div>
																<div class="input-field col s6 m6">

																	C. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_3" value="C" title="OptionC">
																	<label for="option<?php echo $bfid . '_' . $l; ?>_3" class="detach_radio active"><?php echo $optionC; ?> </label>
																</div>

																<div class="input-field col s6 m6">
																	D. <input type='radio' name='option<?php echo $l; ?>' id="option<?php echo $bfid . '_' . $l; ?>_4" title="OptionA" value="D">
																	<label for="option<?php echo $bfid . '_' . $l; ?>_4" class="detach_radio active"><?php echo $optionD; ?> </label>
																</div>


																<div class="input-field col s6 m6">
																	Answer : ( <?php echo $Answer; ?> )
																</div>

															<?php
																$l++;
															}

															?>

													</span>
											<?php
														}
													}
											?>
										</div>
										</p>
					</div>
				<?php }
							} else { ?>
				No Briefing Available
			<?php }

			?>
			</div>
		<?php } else { ?>
			<script>
				$('#alert_msg').html('<ul class="text-danger">Session has exspired, Please login again</ul>')
			</script>

		<?php } ?>
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {


		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}


	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>