<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = 0;
// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit;
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit;
}
$oldremark = $reqid = $mymsg = '';
$DSAad = clean(($_REQUEST['ID*DSAad']));
$DSAadArr = explode("_", $DSAad);
// echo "<pre>";
// print_r($DSAadArr);
$urlId = base64_decode($DSAadArr[1]);
$urlIdPre = $DSAadArr[0];
$urlIdPost = $DSAadArr[2];
// echo $urlIdPre;
$finalUrlId = $urlIdPre . '_' . $urlId . '_' . $urlIdPost;
// echo $finalUrlId;

// $IDDSAad = isset($_REQUEST['ID*DSAad']);
$hid = isset($_POST['hidID']);
if ($DSAad || $hid) {
	if ($DSAad) {
		// $DSAad = clean($_REQUEST['ID*DSAad']);
		// $exop = explode('_', $DSAad);
		// echo 'ffffff'
		// echo "<pre>";
		// print_r($exop);
		// $reqid = $exop[1];
		$reqid = $urlId;
	} else {
		$reqid = cleanUserInput($_POST['hidID']);
	}
}

$feedsave = isset($_POST['feedSave']);
if ($feedsave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$rating = cleanUserInput($_POST['rating']);
		$feedback = trim(addslashes($_POST['feedback']));
		if ($rating != "" && $feedback != "") {
			$update = "update issue_tracker set  rating=? ,feedback=?,feedback_date=now()  where id=? ";
			$upQ = $conn->prepare($update);
			$upQ->bind_param("ssi", $rating, $feedback, $reqid);
			$upQ->execute();
			$result = $upQ->get_result();
			// $result = $myDB->rawQuery($update);
			echo "<script>$(function(){ toastr.success('Feedback added successfully') }); </script>";
		}
	}
}
$btnsave = isset($_POST['btnSave']);
if ($btnsave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$rmk = cleanUserInput($_POST['remark']);
		$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . $rmk;
		$oldremark = cleanUserInput($_POST['oldremark']);
		$createdby = clean($_SESSION['__user_logid']);
		$myDB = new MysqliDb();
		$query = 'call open_issueticket("' . $oldremark . ' | ' . $remark . '","' . $reqid . '");';
		$result = $myDB->query($query);
		//echo $query;
		$mysql_error = $myDB->getLastError();
		if (count($result) > 0 && $result) {
			$user_name = clean($_SESSION['__user_Name']);
			$mymsg = "<div class='alert alert-success'><span><b>Issuee Request Submited </b></span>, We will try our best to resolve it as soon as possible. Thank You <b class='text-danger'>Mr. " . $user_name . "</b></div>";
		} else {
			$mymsg = '<div class="alert alert-danger"><b>Query Not Submited<b></div>' . $mysql_error;
		}
	}
}
$btnclose = isset($_POST['btnClose']);
if ($btnclose) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$myDB = new MysqliDb();
		$userid = clean($_SESSION['__user_logid']);
		$query = "call getGender('" . $userid . "')";
		//echo($query);
		$gender_f = $myDB->query($query);
		$gender_m = $gender_f[0]['Gender'];
		if (strtoupper($gender_m) == 'FEMALE') {
			$gender_last = 'Mrs';
		} else {
			$gender_last = 'Mr';
		}
		$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . 'Thank You Sir Issue is Resolved.';
		$oldremark = cleanUserInput($_POST['oldremark']);
		$createdby = clean($_SESSION['__user_logid']);
		$myDB = new MysqliDb();
		$query = 'call close_issueticket("' . $oldremark . ' | ' . $remark . '","' . $reqid . '");';
		$result = $myDB->query($query);
		//echo $query;
		$mysql_error = $myDB->getLastError();
		$user_name = clean($_SESSION['__user_Name']);
		if (count($result) > 0 && $result) {
			$mymsg = "<div class='alert alert-success'><span><b>Congratulations Issue Resolved Successfully </b></span>. Thank You <b class='text-danger'>" . $gender_last . ". " . $user_name . "</b></div>";
			$mymsg = "<div class='alert alert-success'><span><b>Congratulations Issue Resolved Successfully </b></span>. Thank You <b class='text-danger'>" . $gender_last . ". " . $user_name . "</b></div>";
		} else {
			$mymsg = '<div class="alert alert-danger"><b>Query Not Submited<b></div>' . $mysql_error;
		}
	}
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Happy to Help</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Happy to Help</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				$issue = $req_remark = $reqName = $hd_remark = '';
				if (!empty($reqid)) {
					$myDB = new MysqliDb();
					$result = $myDB->query('call get_issuetracker_byID("' . $reqid . '")');
					if ($result) {
						foreach ($result as $key => $value) {
							echo '<div class="col s12 m12">';
							echo '
					<div class="input-field col s3 m3">Request By</div>
					<div class="input-field col s9 m9"><b>' . $value['EmployeeName'] . '</b></div>';

							echo
							'<div class="input-field col s3 m3">Issue</div>
					<div class="input-field col s9 m9"><b>' . $value['queary'] . '</b></div>';

							$issue = $value['queary'];
							$reqby = $value['requestby'];
							$reqName = $value['EmployeeName'];
							//echo '<div><label>Request TO :</label><b>'.$value['pd1']['EmployeeName'].'</b></div>';						
							echo '
					<div class="input-field col s3 m3">Belongs To</div>
					<div class="input-field col s9 m9"><b>' . $value['bt'] . '</b></div>';

							$oldremark = $value['requester_remark'];
							$datetime1 = new DateTime(date('Y-m-d H:i:s'));
							$datetime2 = new DateTime($value['request_date']);
							$interval = $datetime2->diff($datetime1);
							if ($value['status'] == 'Pending') {
								if ($value['tat'] > ($interval->days * 24) + $interval->h) {
									$class = "red-text";
									$text = '<code>Tat Miss</code>';
								} else {
									$class = 'green-text';
									$text = '<code>In Tat</code>';
								}
							} else {
								$class = 'red-text';
								$text = '';
							}
							/*echo '<div ><label>Tat :</label><b class="'.$class.'">'.$value['tat'].' Hour  </b>'.$text.'</div>';*/
							echo '
					<div class="input-field col s3 m3">Request Time</div>
					<div class="input-field col s9 m9"><b>' . $value['request_date'] . '</b></div>';

							echo '
					<div class="input-field col s3 m3">Mobile No</div>
					<div class="input-field col s9 m9"><b>' . $value['mobileNo'] . '</b></div>';

							echo '
					<div class="input-field col s3 m3">Communicated With</div>
					<div class="input-field col s9 m9"><b>' . $value['committed_with'] . '</b></div>';

							echo '<div class="input-field col s3 m3">Concern Of</div>
					<div class="input-field col s9 m9"><b>' . $value['concern_off'] . '</b></div>';

							if ($value['status'] == 'Resolve' || $value['status'] == 'close') {
								echo '
						<div class="input-field col s3 m3">Request Status</div>
						<div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';

								echo '<div class="input-field col s3 m3">Requester Remark</div>
						<div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';

								$req_remark = $value['requester_remark'];
								echo '
						<div class="input-field col s3 m3">Handler Remark</div>
						<div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' </b></div>';

								$hd_remark = $value['handler_remark'];
								echo '</div>';
								echo '<div class="input-field col s3 m3">Remark</div>
						<textarea name="remark" id="remark" class="form-control" placeholder="Remark Body" title="Type Your remark Here"></textarea>
						</div>
						<div align="right">';
								if ($value['status'] == 'Resolve') {
									echo '<button type="submit"  id="btnSave" class="button button-3d-caution button-rounded" name="btnSave" >  Re Open  <i class="fa fa-send"></i>';
								}
								echo '</button>&nbsp;<button type="submit"  id="btnClose" class="button button-3d-highlight button-rounded" name="btnClose" >Issue Resolved <i class="fa fa-close"></i></button><p></p>
						</div>';
							} else {
								echo '<div class="input-field col s3 m3">Requester Remark</div>
						<div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';

								$req_remark = $value['requester_remark'];
								echo '<div class="input-field col s3 m3">Handler Remark</div>
						<div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' </b></div>';
								$hd_remark = $value['handler_remark'];
								if ($value['status'] == 'Close') {
									echo '<div class="input-field col s3 m3">Request Status</div>
							      <div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';
									if ($value['rating'] == "" && $value['feedback'] == "") {


				?>
										<div class="input-field col s3 m3"><b>How would you rate resolution on your query?</b></div>
										<div class="input-field col s9 m9">
											<form action="" method="post"><span class="input-field col s3 m3">Ratinng <select name="rating" id="rating">
														<option value="">Select</option><?php for ($i = 1; $i <= 10; $i++) { ?>
															<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
														<?php } ?>

													</select></span><span class="input-field col s5 m5">Feedback <textarea name="feedback" maxlength="250" id="feedback"></textarea></span><span class="input-field col s1 m1" style="margin-top:32px;"><button type="submit" id="feedSave" class="btn waves-effect waves-green" name="feedSave">
														Submit <i class="fa fa-send"></i></button></span></form>
										</div>
									<?php
									} else { ?>
										<div class="input-field col s3 m3">Rating</div>
										<div class="input-field col s9 m9"><b><?php echo $value['rating']; ?></b></div>
										<div class="input-field col s3 m3">Feedback</div>
										<div class="input-field col s9 m9"><b><?php echo $value['feedback']; ?></b></div>
				<?php	}
								} else
						if ($value['status'] == 'Refer') {
									echo '<div class="input-field col s3 m3">Request Status</div>
							<div class="input-field col s9 m9"><b> InProgress </b></div>';
								} else {
									echo '<div class="input-field col s3 m3">Request Status</div>
							<div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';
								}

								echo '</div>';
							}
						}
					}
				}
				$btnsave = isset($_POST['btnSave']);
				if ($btnsave) {
					$myDB = new MysqliDb();
					$dataContact = $myDB->query("call get_contact('" . $reqby . "')");
					$mailID = $dataContact[0]['emailid'];

					if (true) {
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$pagename = 'open_issue';
						$loca = clean($_SESSION["__location"]);
						$select_emailarray = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location =?";
						$sel = $conn->prepare($select_emailarray);
						$sel->bind_param("si", $pagename, $loca);
						$sel->execute();
						$select_email_array = $sel->get_result();

						$emailid = $mailID;
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST;
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;
						$mail->Password = EMAIL_PASS;
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT;
						$mail->setFrom(EMAIL_FROM, 'EMS:Cogent Grievance System');
						//$mail->AddAddress('md.masood@cogenteservices.com');
						if ($select_email_array->num_rows > 0 && $select_email_array) {
							foreach ($select_email_array as $Key => $val) {
								$email_address = $val['email_address'];

								if ($email_address != "") {
									$mail->AddAddress($email_address);
								}
								$cc_email = $val['ccemail'];

								if ($cc_email != "") {
									$mail->addCC($cc_email);
								}
							}
						}
						$refID_id = $reqid;
						$loc = clean($_SESSION["__location"]);
						if ($loc == "1") {
							$EMS_CenterName = "Noida";
						} else if ($loc == "2") {
							$EMS_CenterName = "Mumbai";
						} else if ($loc == "3") {
							$EMS_CenterName = "Meerut";
						} else if ($loc == "4") {
							$EMS_CenterName = "Bareilly";
						} else if ($loc == "5") {
							$EMS_CenterName = "Vadodara";
						} else if ($loc == "6") {
							$EMS_CenterName = "Mangalore";
						} else if ($loc == "7") {
							$EMS_CenterName = "Bangalore";
						} else if ($loc == "8") {
$EMS_CenterName = "Nashik";
						} else if ($loc == "9") {
							$EMS_CenterName = "Anantapur";

						}

						$mail->Subject = 'Happy to help ' . $EMS_CenterName . ', Reference #' . $refID_id . ' : Re-Open';
						$mail->isHTML(true);
						$myDB = new MysqliDb();
						$info_emp = $myDB->query('call get_info_for_Issue_tracker("' . $reqby . '")');

						$mysqlError = $myDB->getLastError();
						if (empty($mysqlError)) {
							$pwd_ = '<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: ' . $issue . '</b>.<br /><br /><b>Concern:</b> ' . $req_remark . '.<br/><br/><br/>Concern Feedback : ' . $hd_remark . '<br/> Thank You</b>.<br/>Regard,<br/>' . strtoupper($reqName) . '(<b>&nbsp;' . $reqby . '&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>' . strtoupper($info_emp[0]['Designation']) . '<br/><b>Process &nbsp;:&nbsp;</b>' . $info_emp[0]['Process'] . '&nbsp;(&nbsp;' . $info_emp[0]['sub_process'] . '&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>' . $info_emp[0]['AccountHead'] . '<br /><b>Report To &nbsp;:&nbsp;</b>' . $info_emp[0]['ReportTo'] . '<br />';
							$mail->Body = $pwd_;
						}


						if (!$mail->send()) {
							$mymsg .= '<div class="alert alert-success">Mailer Error:' . $mail->ErrorInfo . '</div>';
						} else {

							$mymsg .= '<div class="alert alert-success">Mail Send successfully.' . '</div>';
						}
					}
				}
				?>
				<input type="hidden" name="hidID" id="hidID" value="<?php echo $reqid; ?>" />
				<input type="hidden" name="oldremark" id="oldremark" value="<?php echo $oldremark; ?>" />
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	function checkRepeat(str) {
		var repeats = /(.)\1{3,}/;

		return repeats.test(str)
	}
	$(function() {

		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';
			$('#remark').closest('div').removeClass('has-error');
			if (checkRepeat($('#remark').val())) {
				//$('#remark').closest('div').addClass('has-error');
				$('#remark').addClass('has-error');
				validate = 1;
				$('<span id="spanremark" class="help-block">Remark should not contain Repeat character</span>').insertAfter('#remark');
				return false;
			}
			if ($('#remark').val().length < 250) {
				$('#remark').addClass('has-error');
				validate = 1;
				$('<span id="spanremark" class="help-block">Remark should be greater than 250 character</span>').insertAfter('#remark');
				return false;
			}

		});
		$('#querysub').change(function() {
			var tval = $(this).val();
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getHandler.php?id=" + tval + "&loc=" + <?php echo '"' . clean($_SESSION['__location']) . '"'; ?>
			}).done(function(data) { // data what is sent back by the php page

				$('#handler').html(data);
				$('#handler').val('NA');
			});
		});
		$('#queryto').change(function() {
			var tval = $(this).val();

			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getIssue.php?id=" + tval + "&loc=" + <?php echo '"' . clean($_SESSION['__location']) . '"'; ?>
			}).done(function(data) { // data what is sent back by the php page

				$('#querysub').html(data);
				$('#querysub').val('NA');
			});
		});
		$('#alertdiv').delay(5000).fadeOut("slow");
		$('#feedSave').click(function() {
			validate = 0;
			if ($('#rating').val() == '') {
				$('#rating').addClass('has-error');
				validate = 1;
				$('<span id="spanrating" class="help-block">Select your rating</span>').insertAfter('#rating');
				return false;
			}
			if ($('#feedback').val() == '') {
				$('#feedback').addClass('has-error');
				validate = 1;
				$('<span id="spanfeedback" class="help-block">Please write your feedback</span>').insertAfter('#feedback');
				return false;
			}


		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>