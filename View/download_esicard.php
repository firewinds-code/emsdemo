 <?php
	require_once(__dir__ . '/../Config/init.php');
	// DB main Config / class file
	require_once(CLS . 'MysqliDb.php');

	// Default timezone for page and date time
	date_default_timezone_set('Asia/Kolkata');
	require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
	require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
	require(ROOT_PATH . 'AppCode/nHead.php');
	include_once("../Services/sendsms_API1.php");
	$EmployeeID = strtoupper($_SESSION["__user_logid"]);



	$myDB = new MysqliDb();
	$sql = 'select location from personal_details where EmployeeID = "' . $EmployeeID . '"';
	$result = $myDB->rawQuery($sql);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		$loc = $result[0]['location'];
	}
	if ($loc == "1" || $loc == "2") {
		$dir_location = '';
	} else if ($loc == "3") {
		$dir_location = 'Meerut/';
	} else if ($loc == "4") {
		$dir_location = "Bareilly/";
	} else if ($loc == "5") {
		$dir_location = "Vadodara/";
	} else if ($loc == "6") {
		$dir_location = "Manglore/";
	} else if ($loc == "7") {
		$dir_location = "Bangalore/";
	} else if ($loc == "8") {
		$dir_location = "Nashik/";
	} else if ($loc == "9") {
		$dir_location = "Anantapur/";
	} else if ($loc == "10") {
		$dir_location = "Gurgaon/";
	} else if ($loc == "11") {
		$dir_location = "Hyderabad/";
	}

	$query = "Select status,email_address from esicard where EmployeeID='" . $EmployeeID . "'";
	$myDB = new MysqliDb();
	$dataArray = $myDB->rawQuery($query);
	$disable = '';

	$myDB = new MysqliDb();


	$disable = "";
	$message = $cm_id = "";
	if (count($dataArray) > 0) {
		if ($dataArray[0]['status'] == 1) {

			$message = "You have already received your ESIC card on your email " . $dataArray[0]['email_address'];
			$disable = "disabled='disabled'";
		} else
	if ($dataArray && $dataArray[0]['status'] == 0) {
			$disable = "";
			$message = "";
		}
	} else {
		$message = 'ESIC card not available';
		$disable = "disabled='disabled'";
	}
	if (isset($_POST['emailAddress']) && trim($_POST['emailAddress']) != "") {

		$myDB = new MysqliDb();
		$select_email_array = $myDB->rawQuery("select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='" . $EmployeeID . "'");
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST;
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;
		$mail->Password = EMAIL_PASS;
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT;
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		$contactNum = '';
		if ($rowCount > 0) {
			$contactNum = $select_email_array[0]['mobile'];
			$cm_id = $select_email_array[0]['cm_id'];
			$email_address = $_POST['emailAddress'];
			$mail->AddAddress($email_address);
		}
		if (file_exists('../' . $dir_location . 'esicard/' . $EmployeeID . '_esicard.pdf')) {

			$mail->AddAttachment('../' . $dir_location . 'esicard/' . $EmployeeID . '_esicard.pdf', "esicard.pdf");
			$mail->Subject = 'ESI Card';
			$mail->isHTML(true);

			$mysqlError = '';

			$body = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Hello ,<br/><br/><span><b> Please find attached the ESIC Card</b></span><br /><br/><div style="float:left;width:100%;"><br/> Thanks, Cogent<div>';

			$mail->Body = $body;
			$mymsg = "";
			if (!$mail->send()) {
				$mymsg .= '.Mailer Error:' . $mail->ErrorInfo;
				echo "<script>$(function(){ toastr.error('" . $mymsg . "') }); </script>";
			} else {
				/*id, EmployeeID, filename, createdBy, createdOn, updatedBy, updatedOn, email_address, received_date, status*/
				$myDB = new MysqliDb();
				//$myDB->rawQuery("update esicard set status=1,email_address='" . addslashes($_POST['emailAddress']) . "',received_date=now(),cm_id='" . $cm_id . "' where EmployeeID='" . $EmployeeID . "'");
				$myDB->rawQuery("update esicard set email_address='" . addslashes($_POST['emailAddress']) . "',received_date=now(),cm_id='" . $cm_id . "' where EmployeeID='" . $EmployeeID . "'");
				$emailaddress = $_POST['emailAddress'];
				echo "<script>$(function(){ toastr.success('ESIC Card sent successfully on your email address " . $emailaddress . " ') }); </script>";
			}
			/* SMS on mobile */
			$mobilenum = $contactNum;
			if (!empty($mobilenum)) {
				$templateid = '1707161526685489215';
				//$msg="Hi , your ESIC Card has been sent on your given Email ID : ".$_POST['emailAddress'];
				$msg = "Hi , your ESIC Card has been sent on your given Email ID : " . $_POST['emailAddress'] . " - Cogent E Services ";
				$url = SMS_URL;
				$token = SMS_TOKEN;
				$credit = SMS_CREDIT;
				$sender = SMS_SENDER;
				$message = $msg;
				$number = $mobilenum;
				$sendsms = new sendsms($url, $token);
				$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $templateid);
			}
		} else {
			echo "<script>$(function(){ toastr.success('ESIC Card not found') }); </script>";
		}
	}

	?>
 <!-- This div not contain a End on this Page because this activity already done in footer Page -->
 <div id="content" class="content">

 	<!-- Header Text for Page and Title -->
 	<span id="PageTittle_span" class="hidden">Get ESIC Card</span>

 	<!-- Main Div for all Page -->
 	<div class="pim-container row" id="div_main">

 		<!-- Sub Main Div for all Page -->
 		<div class="form-div">

 			<!-- Header for Form If any -->
 			<h4>Get ESIC Card on Email Id</h4>

 			<!-- Form container if any -->
 			<div class="schema-form-section row">
 				<!--<div class="input-field col s12 m12 ">-->
 				<div class="input-field col s6 m6">
 					<input type="text" name="emailAddress" id="emailAddress" <?php echo $disable; ?> />

 					<?php
						if ($myDB->count > 0) { ?>
 						<span style="color: red;" id='appid'><?php echo $message; ?></span>
 					<?php } else { ?>
 						<span style="color: red;" id='appid'><?php echo $message; ?></span>
 					<?php	} ?>
 					<label>Email Address</label>

 				</div>
 				<div class="input-field col s6 m6">
 					<input type="text" name="cemailAddress" id="cemailAddress" <?php echo $disable; ?> />
 					<label>Confirm Email Address</label>

 				</div>
 				<div class="input-field col s12 m12 right-align">
 					<button type="submit" id="btnSave" name="btnSave" class="btn waves-effect waves-green" <?php echo $disable; ?>>Send</button>

 				</div>
 				<!--</div>-->
 			</div>
 			<!--Form container End -->
 		</div>
 		<!--Main Div for all Page End -->
 	</div>
 	<!--Content Div for all Page End -->
 </div>

 <script>
 	$(document).ready(function() {

 		//event.preventDefault();
 		$('#emailAddress, #cemailAddress').on("cut copy paste", function(e) {
 			e.preventDefault();
 		});
 		$('#btnSave').click(function() {

 			var validate = 0;
 			var alert_msg = '';
 			$('#emailAddress').removeClass('has-error');
 			var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
 			if ($('#emailAddress').val() == '') {
 				$('#emailAddress').addClass('has-error');
 				validate = 1;
 				if ($('#squeryto').size() == 0) {
 					$('<span id="squeryto" class="help-block">Please enter you email address for get your ESI Card.</span>').insertAfter('#emailAddress');
 				}
 				return false;

 			} else {


 				var emaiAddress = $('#emailAddress').val();
 				email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
 				if (!email_regex.test(emaiAddress)) {
 					validate = 1;
 					if ($('#squeryto').size() == 0) {
 						$('<span id="squeryto" class="help-block">Please enter valid email address.</span>').insertAfter('#emailAddress');
 					}
 					return false;
 				}
 			}


 			if ($('#cemailAddress').val() == '') {
 				validate = 1;
 				if ($('#csqueryto').size() == 0) {
 					$('<span id="squeryto" class="help-block">Please enter you confirm email address.</span>').insertAfter('#cemailAddress');
 				}
 				return false;
 			} else {
 				if ($('#cemailAddress').val() === $('#emailAddress').val()) {
 					return true;
 				} else {
 					if ($('#csqueryto').size() == 0) {
 						$('<span id="csqueryto" class="help-block">Confirm email address not match .</span>').insertAfter('#cemailAddress');
 					}
 					return false;
 				}
 			}



 		});
 		event.preventDefault();
 	});
 </script>
 <?php



	include(ROOT_PATH . 'AppCode/footer.mpt'); ?>