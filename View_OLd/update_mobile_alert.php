<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$remark = $empname = $empid = '';
$relation = '';
ini_set("display_errors", "1");
$mobile = $altmobile = $em_contact = $txt_relation = $emailid = '';
//print_r($_SESSION);
if (isset($_POST['btnSave'])) {
	// echo "<pre>";
	// print_r($_POST);
	$clean_u_logid = clean($_SESSION['__user_logid']);
	$createBy = $clean_u_logid;
	$empid = cleanUserInput($_POST['empid']);
	$mobile = cleanUserInput($_POST['mobile']);
	$altmobile = cleanUserInput($_POST['altmobile']);
	$em_contact = cleanUserInput($_POST['em_contact']);
	$txt_relation = cleanUserInput($_POST['txt_relation']);
	$emailid = cleanUserInput($_POST['emailid']);

	if (is_numeric($mobile) && is_numeric($altmobile) && is_numeric($em_contact)) {

		if ($empid != "" && $mobile != "" && $txt_relation != ""  && $em_contact != "" && $emailid != "") {
			$myDB = new MysqliDb();
			$insert = "call Update_mobile_number('" . $empid . "','" . $mobile . "','" . $altmobile . "','" . $em_contact . "','" . $txt_relation . "','" . $emailid . "')";
			$resulti = $myDB->query($insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Mobile Number Updated Successfully.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Mobile Number Not Update.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Please enter Mobile Number.'); }); </script>";
		}
	}
}


?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Contact Detail Confirmation</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Contact Detail Confirmation</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div id="leftmenu" class="container drawer drawer--left">

				</div>
				<?php
				$clean_u_logid = clean($_SESSION['__user_logid']);
				$empid = $clean_u_logid;
				$myDB = new MysqliDb();
				$conn = $myDB->dbConnect();
				$select_query = "SELECT mobile,altmobile,em_contact,emailid,em_relation FROM ems.contact_details where EmployeeID= ? ";
				$select = $conn->prepare($select_query);
				$select->bind_param("s", $empid);
				$select->execute();
				$result = $select->get_result();

				// $result = $myDB->rawQuery($select_query);
				// $mysql_error = $myDB->getLastError();
				if ($result->num_rows > 0) {
					foreach ($result as $key => $value) {
						$clean_u_logid = clean($_SESSION['__user_logid']);
						$empid = $clean_u_logid;
						$empname = clean($_SESSION['__user_Name']);
						$mobile = $value['mobile'];
						$altmobile = $value['altmobile'];
						$em_contact = $value['em_contact'];
						$relation = $value['em_relation'];
						$emailid = $value['emailid'];
					}
				}
				?>
				<div class="input-field col s12 m12 statuscheck">
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" readonly="true" id="empnameEdit" name="empname" value="<?php echo $empname; ?>" />
							<label for="empnameEdit">Name</label>
						</div>

						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" readonly="true" id="empidEdit" name="empid" value="<?php echo $empid; ?>" />
							<label for="empidEdit">Employee ID</label>
						</div>
					</div>
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" id="mobile" class="check-numeric" name='mobile' value="<?php echo $mobile; ?>" />
							<label for="mobile">Mobile Number</label>
						</div>

						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" id="altmobile" class="check-numeric" name="altmobile" value="<?php echo $altmobile; ?>" />
							<label for="altmobile">Alternate Mobile Number</label>
						</div>
					</div>
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" id="em_contact" class="check-numeric" name="em_contact" value="<?php echo $em_contact; ?>">
							<label for="em_contact">Emergency Contact Number</label>
						</div>

						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" id="txt_relation" maxlength="10" name="txt_relation" value="<?php echo $relation; ?>">
							<label for="txt_relation">Relation</label>
						</div>
					</div>
					<div class="input-field col s12 m12">
						<div class="input-field col s6 m6 clsIDHome">
							<input type="text" id="emailid" maxlength="80" name="emailid" value="<?php echo $emailid; ?>">
							<label for="txt_relation">Email ID</label>
						</div>
					</div>

					<div class="input-field col s12 m12 right-align">
						<input type="submit" value="Confirm" name="btnSave" id="btnSave1" class="btn waves-effect waves-green" />
					</div>

					<script>
						$('.check-numeric').blur(function() {
							if (!$.isNumeric(this.value))
								this.value = '';
						});
						$(function() {

							var minLength = 10;
							var maxLength = 10;
							$('#mobile,#altmobile,#em_contact').on('keydown keyup change', function() {
								var char = $(this).val();
								var charLength = $(this).val().length;
								if (charLength < minLength) {
									$('#warning-message').text('Length is short, minimum ' + minLength + ' required.');
								} else if (charLength > maxLength) {
									$('#warning-message').text('Length is not valid, maximum ' + maxLength + ' allowed.');
									$(this).val(char.substring(0, maxLength));
								} else {
									$('#warning-message').text('');
								}
							});

							$('#mobile,#altmobile,#em_contact').keydown(function(event) {
								if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

									// Allow: Ctrl+A
									(event.keyCode == 65 && event.ctrlKey === true) ||

									// Allow: Ctrl+V
									(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

									// Allow: Ctrl+c
									(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

									// Allow: Ctrl+x
									(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

									// Allow: home, end, left, right
									(event.keyCode >= 35 && event.keyCode <= 39)) {
									// let it happen, don't do anything
									return;
								} else {
									// Ensure that it is a number and stop the keypress
									if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
										event.preventDefault();
									}
								}
							});

							function validateEmail(email) {
								var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
								return re.test(email);
							}
							$('#btnSave1').click(function() {
								//alert('hide');
								var mobile = $('#mobile').val().trim();
								validate = 0;
								if (mobile == "") {
									$('#mobile').addClass('has-error');
									validate = 1;
									if ($('#smobile').size() == 0) {
										$('<span id="smobile" class="help-block">Mobile number should not be empty.</span>').insertAfter('#mobile');
									}
								} else
								if (mobile.length < 10) {
									$('#mobile').addClass('has-error');
									validate = 1;
									if ($('#smobile1').size() == 0) {
										$('<span id="smobile1" class="help-block">Mobile number should not be greater than 10 digit.</span>').insertAfter('#mobile');
									}

								}

								var em_contact = $('#em_contact').val().trim();
								if (em_contact == "") {
									$('#em_contact').addClass('has-error');
									validate = 1;
									if ($('#sem_contact').size() == 0) {
										$('<span id="sem_contact" class="help-block">Emergency mobile number should not be empty.</span>').insertAfter('#em_contact');
									}

								} else
								if (em_contact.length < 10) {

									$('#em_contact').addClass('has-error');
									validate = 1;
									if ($('#sem_contact').size() == 0) {
										$('<span id="sem_contact" class="help-block">Emergency mobile number should not be greater than 10 digit.</span>').insertAfter('#em_contact');
									}

								}

								if (em_contact == mobile) {
									$('#em_contact').addClass('has-error');
									validate = 1;
									if ($('#sem_contact').size() == 0) {
										$('<span id="sem_contact" class="help-block">Emergency mobile number should not be same as Mobile No.</span>').insertAfter('#em_contact');
									}
								}
								if ($('#altmobile').val() != "") {
									var altmobile = $('#altmobile').val();
									if (altmobile == em_contact || altmobile === mobile) {
										$('#altmobile').addClass('has-error');
										validate = 1;
										if ($('#saltmobile').size() == 0) {
											$('<span id="saltmobile" class="help-block">Alternate mobile number should not be same as mobile number or emergency mobile number.</span>').insertAfter('#altmobile');
										}
									}
								}
								var txt_relation = $('#txt_relation').val().trim();
								if (txt_relation == "") {
									$('#txt_relation').addClass('has-error');
									validate = 1;
									if ($('#stxt_relation').size() == 0) {
										$('<span id="stxt_relation" class="help-block">Relation with Emergency Contact you given should not be empty.</span>').insertAfter('#txt_relation');
									}
								}

								var emailid = $('#emailid').val().trim();
								if (emailid == "") {
									$('#emailid').addClass('has-error');
									validate = 1;
									if ($('#semailid').size() == 0) {
										$('<span id="semailid" class="help-block">Email ID should not be empty.</span>').insertAfter('#emailid');
									}

								} else {

									if (!validateEmail(emailid)) {
										$('#emailid').addClass('has-error');
										validate = 1;
										if ($('#semailid1').size() == 0) {
											$('<span id="semailid1" class="help-block">Email ID is not valid, please try again.</span>').insertAfter('#emailid');
										}
									}
								}
								if (validate == 1) {
									return false;
								}


							});

							<?php if (isset($_POST['btnSave'])) { ?>
								window.location = <?php echo  "'" . URL . 'View/' . "'"; ?>
							<?php } ?>
						});
					</script>
				</div>
				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>
	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>