<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');

$createBy = clean($_SESSION['__user_logid']);
$imsrc = URL . 'Style/images/agent-icon.png';
$EmployeeID = $btnShow = '';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (isset($_POST['btn_contact_Save1'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_contatc_mob = cleanUserInput($_POST['txt_contatc_mob']);
		$mob = (isset($txt_contatc_mob) ? $txt_contatc_mob : null);
		$emp = cleanUserInput($_POST['EmployeeID']);
		$EmployeeID = (isset($emp) ? $emp : null);
		if (strstr($mob, "X")) {
			if (isset($_POST['mobno'])) {
				$mob = cleanUserInput($_POST['mobno']);
			}
		}
		$txt_contatc_altmob = cleanUserInput($_POST['txt_contatc_altmob']);
		$altmob = (isset($txt_contatc_altmob) ? $txt_contatc_altmob : null);
		if (strstr($altmob, "X")) {
			$altmobno = cleanUserInput($_POST['altmobno']);
			$altmob = (isset($altmobno) ? $altmobno : null);
		}
		$txt_contatc_email = cleanUserInput($_POST['txt_contatc_email']);
		$email = (isset($txt_contatc_email) ? $txt_contatc_email : null);
		$txt_contatc_em_contact = cleanUserInput($_POST['txt_contatc_em_contact']);
		$em_contact = (isset($txt_contatc_em_contact) ? $txt_contatc_em_contact : null);
		$txt_contatc_em_relation = cleanUserInput($_POST['txt_contatc_em_relation']);
		$em_relation = (isset($txt_contatc_em_relation) ? $txt_contatc_em_relation : null);
		$txt_contatc_ofcemail = cleanUserInput($_POST['txt_contatc_ofcemail']);
		$ofcemail = (isset($txt_contatc_ofcemail) ? $txt_contatc_ofcemail : null);
		$myDB = new MysqliDb();
		$createBy = clean($_SESSION['__user_logid']);
		$sqlInsertDoc = 'call manage_contact("' . $EmployeeID . '","' . $email . '","' . $altmob . '","' . $mob . '","' . $em_contact . '","' . $createBy . '","' . $em_relation . '","' . $ofcemail . '")';
		$result = $myDB->query($sqlInsertDoc);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Contact info is Saved Successfully') }); </script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd ') });</script>";
		}
	}
} else {
	$em_relation = $mob = $altmob = $email = $em_contact = $ofcemail = '';
}
$empid = clean($_REQUEST['empid']);
if (isset($empid)) {
	$EmployeeID = $empid;
	$getDetails = 'call get_contact("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($myDB->count > 0) {

		//$mob= $result_all[0]['mobile'];
		$mob = "XXXXXX" . substr($result_all[0]['mobile'], -4);
		//$altmob=$result_all[0]['altmobile'];
		$altmob = "XXXXXX" . substr($result_all[0]['altmobile'], -4);
		$email = $result_all[0]['emailid'];
		$em_contact = $result_all[0]['em_contact'];
		$em_relation =  $result_all[0]['em_relation'];
		$ofcemail =  $result_all[0]['ofc_emailid'];
		$btnShow = ' hidden';
		echo "<input type='hidden' name='mobno' id='hidmo' value='" . $result_all[0]['mobile'] . "'>";
		echo "<input type='hidden' name='altmobno' id='hidaltmo' value='" . $result_all[0]['altmobile'] . "'>";
	}
	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
	} else {
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') }); window.location='" . URL . "'</script>";
	}
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = cleanUserInput($_POST['EmployeeID']);
		$getDetails = 'call get_contact("' . $EmployeeID . '")';
		$myDB = new MysqliDb();
		$result_all = $myDB->query($getDetails);
		if ($result_all) {
			echo "<input type='hidden' name='mobno' id='hidmo' value='" . $result_all[0]['mobile'] . "'>";
			echo "<input type='hidden' name='altmobno' id='hidaltmo' value='" . $result_all[0]['altmobile'] . "'>";
		}
	}
}
?>
<script>
	$createBy = clean($_SESSION['__user_logid']);
	$__user_type = clean($_SESSION['__user_type']);
	$(document).ready(function() {
		var usrtype = <?php echo "'" . $__user_type . "'"; ?>;
		var usrid = <?php echo "'" . $createBy . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR' || usrid == 'CE12102224') {} else if (usrtype === 'AUDIT') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();
			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS') {

			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		} else {
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"' . URL . '/undefined"'; ?>;
		}

		function validateEmail(sEmail) {
			var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
			if (filter.test(sEmail)) {
				return true;
			} else {
				return false;
			}
		}
		$('#txt_contatc_email').keyup(function() {
			if (validateEmail($(this).val())) {
				$('#txt_contatc_email').removeClass('has-error').addClass('has-success');
				$('#mailcheck').html('Valid Mail').css('color', 'green');
			} else {
				$('#txt_contatc_email').addClass('has-error');
				$('#mailcheck').html('Invalid Mail').css('color', 'red');
			}
		});
		$('#txt_contatc_ofcemail').keyup(function() {
			if ($(this).val() != "") {
				if (validateEmail($(this).val())) {
					var str = $(this).val();
					var words = str.split('@');
					if (words[1].toLowerCase() == 'cogenteservices.com' || words[1].toLowerCase() == 'cogenteservices.in') {
						$('#txt_contatc_ofcemail').removeClass('has-error').addClass('has-success');
						$('#mailcheck1').html('Valid Mail').css('color', 'green');
					} else {
						$('#txt_contatc_ofcemail').addClass('has-error');
						$('#mailcheck1').html('Invalid Mail').css('color', 'red');
					}
				} else {
					$('#txt_contatc_ofcemail').addClass('has-error');
					$('#mailcheck1').html('Invalid Mail').css('color', 'red');
				}
			}
		});

		// $('.EmployeeDetail').on('click', function() {

		// 	var tval = $(this).text();

		// 	$.ajax({
		// 		url: <?php echo '"' . URL . '"'; ?> + "Controller/GetEmployee.php?empid=" + tval
		// 	}).done(function(data) { // data what is sent back by the php page
		// 		$('#myDiv').html(data).removeClass('hidden');
		// 		$('.imgBtn_close').on('click', function() {
		// 			var el = $(this).parent('div').parent('div');
		// 			el.addClass('hidden');
		// 		});
		// 		// display data
		// 	});

		// });
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Contact Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>


		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Contact Details</h4>
			<?php
			if ($EmployeeID == '' && empty($EmployeeID)) {
				echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
				exit();
			}
			?>
			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">


				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />

				<?php $mob = "XXXXXX" . substr($mob, -4);
				$altmob = "XXXXXX" . substr($altmob, -4);
				?>

				<div class="input-field col s6 m6">
					<input type="text" maxlength="10" value="<?php echo ($mob); ?>" id="txt_contatc_mob" name="txt_contatc_mob" required />
					<label for="txt_contatc_mob">Mobile No *</label>
					<span></span>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" maxlength="10" value="<?php echo ($altmob); ?>" id="txt_contatc_altmob" name="txt_contatc_altmob" />
					<label for="txt_contatc_altmob">Alternate Mob. No </label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" maxlength="10" value="<?php echo ($em_contact); ?>" id="txt_contatc_em_contact" name="txt_contatc_em_contact" required />
					<label for="txt_contatc_em_contact">Emergency Mob No *</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" maxlength="10" value="<?php echo ($em_relation); ?>" id="txt_contatc_em_relation" name="txt_contatc_em_relation" required />
					<label for="txt_contatc_em_relation">Relation *</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" maxlength="255" value="<?php echo ($email); ?>" id="txt_contatc_email" name="txt_contatc_email" required />
					<label for="txt_contatc_email">Email ID *</label>
					<span id="mailcheck" style="color: rgb(255, 0, 0);margin: 3px;padding: 3px;line-height: 30px;text-shadow: 0px 0px 1px #8A8787;"></span>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" maxlength="255" value="<?php echo ($ofcemail); ?>" id="txt_contatc_ofcemail" name="txt_contatc_ofcemail" />
					<span id="mailcheck1" style="color: rgb(255, 0, 0);margin: 3px;padding: 3px;line-height: 30px;text-shadow: 0px 0px 1px #8A8787;"></span>
					<label for="txt_contatc_ofcemail">Office Email ID</label>
				</div>
				<div class="input-field col s12 m12 right-align">
					<button type="submit" title="Update Details" name="btn_contact_Save1" id="btn_contact_Save1" class="btn waves-effect waves-green">Save Data</button>
				</div>
				<script>
					$(document).ready(function() {


						var minLength = 10;
						var maxLength = 10;
						$('#txt_contatc_mob').on('keydown keyup change', function() {
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


						$('#txt_contatc_mob,#txt_contatc_altmob,#txt_contatc_em_contact').keydown(function(event) {
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


						$('#btn_contact_Save1').on('click', function() {
							var validate = 0;
							var alert_msg = '';
							$('#documentid').val('');
							// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
							$("input,select,textarea").each(function() {

								var spanID = "span" + $(this).attr('id');
								$(this).removeClass('has-error');
								if ($(this).is('select')) {
									$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
								}
								var attr_req = $(this).attr('required');
								if ((($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) && $(this).val()) {
									validate = 1;
									$(this).addClass('has-error');
									if ($(this).is('select')) {
										$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
									}
									if ($('#' + spanID).size() == 0) {
										$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
									}
									var attr_error = $(this).attr('data-error-msg');
									if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
										$('#' + spanID).html('Required *');
									} else {
										$('#' + spanID).html($(this).attr("data-error-msg"));
									}
								}
							})
							var str = $('#txt_contatc_ofcemail').val();
							if (str != '') {
								var words = str.split('@');
								if (words[1].toLowerCase() == 'cogenteservices.com' || words[1].toLowerCase() == 'cogenteservices.in') {
									$('#txt_contatc_ofcemail').removeClass('has-error').addClass('has-success');

								} else {
									$('#txt_contatc_ofcemail').addClass('has-error');
									alert_msg = 'Invalid office Email ID';
									validate = 1;
								}
							}
							var txt_contatc_em_contact = $('#txt_contatc_em_contact').val();
							var txt_contatc_mob = $('#txt_contatc_mob').val();
							var txt_contatc_altmob = $('#txt_contatc_altmob').val();
							if (txt_contatc_em_contact === txt_contatc_mob) {
								$('#txt_contatc_em_contact').addClass('has-error');
								alert_msg = 'Emergency contact number should not be same as contact number';
								validate = 1;


							} else {
								$('#txt_contatc_em_contact').removeClass('has-error').addClass('has-success');

							}
							if (txt_contatc_altmob != "") {

								if ((txt_contatc_altmob == txt_contatc_em_contact || txt_contatc_altmob == txt_contatc_mob)) {
									$('#txt_contatc_altmob').addClass('has-error');
									alert_msg = 'Alternate contact number should not be same as contact number / emergency contact number';
									validate = 1;
								}

							} else {
								$('#txt_contatc_em_contact').removeClass('has-error').addClass('has-success');

							}

							if (validate == 1) {
								if (alert_msg != "") {
									$(function() {
										toastr.info(alert_msg);
									});
								}

								return false;
							}

							var aa = confirm('Do you want to save?');
							if (aa) {

								return true;
							} else {
								return false;
							}
						});
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