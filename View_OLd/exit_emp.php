<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$txt_exit_empname = cleanUserInput($_POST['txt_exit_empname']);
	$EmpName = (isset($txt_exit_empname) ? $txt_exit_empname : null);
	$txt_exit_dtLeave = cleanUserInput($_POST['txt_exit_dtLeave']);
	$dol = (isset($txt_exit_dtLeave) ? $txt_exit_dtLeave : null);
	$txt_exit_rsnleave = cleanUserInput($_POST['txt_exit_rsnleave']);
	$rsnofleaving = (isset($txt_exit_rsnleave) ? $txt_exit_rsnleave : null);
	$txt_exit_hrcmt = cleanUserInput($_POST['txt_exit_hrcmt']);
	$hrcmt = (isset($txt_exit_hrcmt) ? $txt_exit_hrcmt : null);
	$txt_exit_opcmt = cleanUserInput($_POST['txt_exit_opcmt']);
	$optcmt = (isset($txt_exit_opcmt) ? $txt_exit_opcmt : null);
} else {
	$EmpName = $dol = $rsnofleaving = $hrcmt = $optcmt = '';
}


$alert_msg = $EmployeeID = $showbutton = '';
$disposition = 'NA';
if (isset($_POST['btn_Exit_Edit'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_exit_empid = cleanUserInput($_POST['txt_exit_empid']);
		$_empid = (isset($txt_exit_empid) ? $txt_exit_empid : null);
		$txt_exit_rsnleave = cleanUserInput($_POST['txt_exit_rsnleave']);
		$_rsnleave = (isset($txt_exit_rsnleave) ? $txt_exit_rsnleave : null);
		$txt_exit_dtLeave = cleanUserInput($_POST['txt_exit_dtLeave']);
		$_dol = (isset($txt_exit_dtLeave) ? $txt_exit_dtLeave : null);
		$txt_exit_hrcmt = cleanUserInput($_POST['txt_exit_hrcmt']);
		$_hrcmt = (isset($txt_exit_hrcmt) ? $txt_exit_hrcmt : null);
		$txt_exit_opcmt = cleanUserInput($_POST['txt_exit_opcmt']);
		$_opscmt = (isset($txt_exit_opcmt) ? $txt_exit_opcmt : null);
		$_disposition = cleanUserInput($_POST['txt_disposition']);
		if (strlen($_rsnleave) < 50) {
			echo "<script>$(function(){ toastr.info('Reason For Leave should be 50 characters'); }); </script>";
		} else 
	if (trim($_disposition) == 'NA' || trim($_disposition) == '') {
			echo "<script>$(function(){ toastr.info('Disposition should not be blank'); }); </script>";
		} else {
			$createBy = clean($_SESSION['__user_logid']);
			$Insert = 'call exit_employee("' . $_empid . '","' . $_dol . '","' . $_rsnleave . '","' . $_hrcmt . '","' . $_opscmt . '","' . $createBy . '","' . $_disposition . '")';
			$myDB = new MysqliDb();
			$result = $myDB->query($Insert);
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			$mailler_msg = '';
			if (empty($mysql_error)) {
				$EmployeeID = $_empid;

				if (substr($_empid, 0, 2) == 'CE') {

					$pagename = 'DeleteEmail';
					// $select_email_array = $myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'");
					$select_email_arrayQry = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=?";
					$stmt = $conn->prepare($select_email_arrayQry);
					$stmt->bind_param("s", $pagename);
					$stmt->execute();
					$select_email_array = $stmt->get_result();
					// print_r($select_email_array);
					// die;
					// $getDetails = $myDB->query("select a.EmployeeName,a.Process,a.sub_process,a.clientname,a.designation,a.dept_name,b.ofc_emailid from whole_dump_emp_data a INNER Join contact_details b on a.EmployeeID=b.EmployeeID  where a.EmployeeID='" . $EmployeeID . "'");
					$getDetailsQry = "select a.EmployeeName,a.Process,a.sub_process,a.clientname,a.designation,a.dept_name,b.ofc_emailid from whole_dump_emp_data a INNER Join contact_details b on a.EmployeeID=b.EmployeeID  where a.EmployeeID=?";
					$stmt1 = $conn->prepare($getDetailsQry);
					$stmt1->bind_param("s", $EmployeeID);
					$stmt1->execute();
					$getDetails = $stmt1->get_result();

					if ($getDetails->num_rows > 0) {
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST;
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;
						$mail->Password = EMAIL_PASS;
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT;
						$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
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

						$mail->Subject = 'Email ID Deletion Request ' . EMS_CenterName . '[' . date('d M,Y', time()) . ']';
						$mail->isHTML(true);

						$Body = "Hello sir,<br>Please remove / delete bellow listed Employee ID(s) <br><br>
					        <table border='1'>";
						$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Official EmailID</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
						$Body .= "<tr><td>" . $EmployeeID . "</td><td>" . $getDetails[0]['EmployeeName'] . "</td><td>" . $getDetails[0]['ofc_emailid'] . "</td><td>" . $getDetails[0]['clientname'] . "</td><td>" . $getDetails[0]['Process'] . "</td><td>" . $getDetails[0]['sub_process'] . "</td><td>" . $getDetails[0]['designation'] . "</td><td>" . $getDetails[0]['dept_name'] . "</td></tr>";

						$Body .= "</table><br><br>Thanks EMS Team";
						$mail->Body = $Body;

						// if (!$mail->send()) {
						// 	$mailler_msg = 'Mailer Error:' . $mail->ErrorInfo;
						// } else {

						// 	$mailler_msg =   'and Email Id(s) deletion request raised.';
						// }
					}
				}
				echo "<script>$(function(){ toastr.success('Employee InActive Successfully " . $mailler_msg . "'); }); </script>";
				$showbutton = ' hidden';
			} else {
				echo "<script>$(function(){ toastr.error('Record not updated " . $mysql_error . " '); }); </script>";
			}
		}
	}
}


if (isset($_REQUEST['empid']) && $EmployeeID == '') {
	$EmployeeID = clean($_REQUEST['empid']);
	$getDetails = 'call get_exitemp("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	foreach ($result_all as $key => $value) {
		$EmpName = $value['EmployeeName'];
		$dol = $value['dol'];
		$rsnofleaving = $value['rsnofleaving'];
		$hrcmt = $value['hrcmt'];
		$optcmt = $value['optcmt'];
		$disposition = $value['disposition'];
	}

	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
	} else {
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search.') }); window.location='" . URL . "'</script>";
	}
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = cleanUserInput($_POST['EmployeeID']);
	}
}

?>

<script>
	$(document).ready(function() {
		var usrtype = <?php echo "'" . clean($_SESSION["__user_type"]) . "'"; ?>;
		var usrtype_tmp = <?php echo "'" . clean($_SESSION["__ut_temp_check"]) . "'"; ?>;
		if ((usrtype === 'ADMINISTRATOR' && usrtype_tmp == 'ADMINISTRATOR') || (usrtype === 'CENTRAL MIS' && usrtype_tmp == 'COMPLIANCE')) {

		} else {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		}
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollY: 195,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'csv',
					text: 'CSV',
					extension: '.csv',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				},
				'print',
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
				}, 'copy', 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('#txt_exit_dtLeave').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Exit</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Exit</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<div class="input-field col s6 m6">
					<input class="" id="txt_exit_empname" name="txt_exit_empname" readonly="true" value="<?php echo $EmpName; ?>" />
				</div>

				<div class="input-field col s6 m6">
					<div><input class="" id="txt_exit_empid" name="txt_exit_empid" readonly="true" value="<?php echo $EmployeeID; ?>" /></div>
				</div>

				<div class="input-field col s6 m6">

					<?php
					if ($dol != '' && $dol != null) {
						$dol = explode(' ', $dol);
						$dol = $dol[0];
					}
					?>
					<input type="text" id="txt_exit_dtLeave" value="<?php echo $dol; ?>" name="txt_exit_dtLeave" required />
					<label for="txt_exit_dtLeave">Date of leaving</label>
				</div>


				<div class="input-field col s6 m6">
					<select id="txt_disposition" name="txt_disposition" required>
						<option value='NA' <?php if ($disposition == 'NA' || $disposition == '') {
												echo "selected";
											} ?>>Select</option>
						<option value='RES' <?php if ($disposition == 'RES') {
												echo "selected";
											} ?>>RES</option>
						<option value='ABSC' <?php if ($disposition == 'ABSC') {
													echo "selected";
												} ?>>ABSC</option>
						<option value='IR' <?php if ($disposition == 'IR') {
												echo "selected";
											} ?>>IR</option>
						<option value='TER' <?php if ($disposition == 'TER') {
												echo "selected";
											} ?>>TER</option>
						<option value='DCR' <?php if ($disposition == 'DCR') {
												echo "selected";
											} ?>>DCR</option>
						<option value='TRFR' <?php if ($disposition == 'TRFR') {
													echo "selected";
												} ?>>TRFR</option>
					</select>
					<label for="txt_disposition" class="active-drop-down active">Disposition</label>
				</div>

				<div class="input-field col s12 m12">
					<textarea id="txt_exit_rsnleave" name="txt_exit_rsnleave" class="materialize-textarea" required><?php echo $rsnofleaving; ?></textarea>
					<label for="txt_exit_rsnleave">Reason of leaving</label>
				</div>

				<div class="input-field col s12 m12">
					<textarea id="txt_exit_hrcmt" name="txt_exit_hrcmt" class="materialize-textarea" required><?php echo $hrcmt; ?></textarea>
					<label for="txt_exit_hrcmt">HR Comments</label>
				</div>

				<div class="input-field col s12 m12">
					<textarea id="txt_exit_opcmt" name="txt_exit_opcmt" class="materialize-textarea" required><?php echo $optcmt; ?></textarea>
					<label for="txt_exit_opcmt">OPS Comments</label>
				</div>


				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btn_Exit_Edit" id="btn_Exit_Edit" class="btn waves-effect waves-green <?php echo $showbutton; ?>"> Save </button>
					<button type="button" name="btn_Exit_Can" id="btn_Exit_Can" class="btn waves-effect modal-action modal-close waves-red close-btn hidden">Cancel</button>
				</div>

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
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}

		$('#btn_Exit_Can').on('click', function() {

			$('#txt_exit_empname').val('');
			$('#txt_exit_empid').val('');
			$('#txt_exit_dtLeave').val('');
			$('#txt_exit_rsnleave').val('');
			$('#txt_exit_hrcmt').val('');
			$('#txt_exit_opcmt').val('');

			$('#btn_df_Save').removeClass('hidden');
			$('#btn_Exit_Edit').addClass('hidden');
			$('#btn_Exit_Can').addClass('hidden');

		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#btn_Exit_Edit,#btn_df_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
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

			if (validate == 1) {

				return false;
			}
		});


	});

	function EditData(el) {
		var tr = $(el).closest('tr');
		var EmployeeID = tr.find('.EmployeeID').text();
		var EmployeeName = tr.find('.EmployeeName').text();

		$('#txt_exit_empname').val('');
		$('#txt_exit_empid').val('');
		$('#txt_exit_dtLeave').val('');
		$('#txt_exit_rsnleave').val('');
		$('#txt_exit_hrcmt').val('');
		$('#txt_exit_opcmt').val('');

		$('#txt_exit_empname').val('');
		$('#txt_exit_empid').val('');

		$('#txt_exit_empid').val(EmployeeID);
		$('#txt_exit_empname').val(EmployeeName);

		$('#btn_df_Save').addClass('hidden');
		$('#btn_Exit_Edit').removeClass('hidden');
		$('#btn_Exit_Can').removeClass('hidden');
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>