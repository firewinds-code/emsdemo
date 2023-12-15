<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$user_logid = isset($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!$user_logid) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$Request_Emp = '';
$_Description = $_Name = $alert_msg = '';

$userempid = clean($_SESSION['__user_logid']);

if (isset($_POST['btn_change_password'])) {
	// echo "dfdfdfdfd";
	// die;
	// if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$sqlQry = "SELECT * FROM employee_map WHERE EmployeeID= ?";
	$stmt = $conn->prepare($sqlQry);
	$stmt->bind_param('s', $userempid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	// print_r($row);
	// die;
	if (!empty($row)) {
		$txt_chg_pwd = cleanUserInput($_POST['txt_chg_pwd']);
		$txt_old_pwd = cleanUserInput(md5($_POST['txt_old_pwd']));
		// Validate password strength
		$uppercase = preg_match('@[A-Z]@', $txt_chg_pwd);
		$lowercase = preg_match('@[a-z]@', $txt_chg_pwd);
		$number    = preg_match('@[0-9]@', $txt_chg_pwd);
		$specialChars = preg_match('@[^\w]@', $txt_chg_pwd);

		if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($txt_chg_pwd) < 8) {
			echo "<script>$(function(){ toastr.error('Password contain 8 characters in length and should include at least one upper case letter, one number, and one special character.'); }); </script>";
		} else {
			// echo "<script>$(function(){ toastr.success('Strong password.'); }); </script>";
			$oldpassword = $row["password"];
			$password_hash = md5($txt_chg_pwd);
			// $password = PASSWORD_HASH($_POST["newPassword"], PASSWORD_DEFAULT);
			if ($txt_old_pwd != $oldpassword) {
				echo "<script>$(function(){ toastr.error('OLd Password Mismatch '); }); </script>";
			} else {
				if ($oldpassword != $password_hash) {
					$chng_pwd = 'call change_pwd("' . $password_hash . '","' . $userempid . '")';
					$myDB = new MysqliDb();
					$myDB->rawQuery($chng_pwd);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
						echo "<script>$(function(){ toastr.success('Password Changed Successfully'); }); </script>";
						// echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
						$sqlQry = "update emp_auth set flag=0 where EmployeeID= ?";
						$stmt = $conn->prepare($sqlQry);
						$stmt->bind_param('s', $userempid);
						$stmt->execute();
						$res_sqlQry = $stmt->get_result();
						// $row = $result->fetch_assoc();
						// echo "<pre>";
						// print_r($res_sqlQry);
						// die;
						if ($stmt->affected_rows === 1) {
							echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
						} else {
							echo "<script>$(function(){ toastr.error('Some Error Occured'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Data not updated'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Old and New Password Should not be same'); }); </script>";
				}
			}
		}
	}
	// }
}

?>
<script src="<?php echo SCRIPT . 'pwdchk.js'; ?>"></script>
<script>
	$(document).ready(function() {
		$('input').blur(function() {
			$('#txt_chg_pwd1').removeClass('has-error');
			$('#txt_chg_pwd1').removeClass('has-success');
			if ($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val()) {
				$('#txt_chg_pwd1').addClass('has-success');
			} else {
				$('#txt_chg_pwd1').addClass('has-error');
				toastr.info("Password Not Matched");
			}
		});
	});
</script>
<style>
	.short,
	.weak {
		color: red;
	}

	.good {
		color: #e66b1a;
	}

	.strong {
		color: green;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Change Password</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Change Password</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<form name="form1" action="#">
					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
					<div class="row">
						<div class="input-field col s4 m4">
							<input type="password" id="txt_old_pwd" name="txt_old_pwd" placeholder="****" />
							<label for="txt_old_pwd">Old Password</label>
							<!-- <span id="result"></span> -->
						</div>

						<div class="input-field col s4 m4">
							<input type="password" id="txt_chg_pwd" name="txt_chg_pwd" placeholder="****" />
							<label for="txt_chg_pwd">New Password</label>
							<span id="result"></span>
						</div>

						<div class="input-field col s4 m4">
							<input type="password" id="txt_chg_pwd1" name="txt_chg_pwd1" placeholder="****" />
							<label for="txt_chg_pwd1">Confirm Password</label>
						</div>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="btn_change_password" id="btn_change_password" class="btn waves-effect waves-green">Change Password</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#btn_change_password').on('click', function() {

			var password = $("#txt_chg_pwd").val();
			var confirmPassword = $("#txt_chg_pwd1").val();
			if (password != confirmPassword) {
				alert("Passwords do not match.");
				return false;
			}
			return true;

			var validate = 0;
			var alert_msg = '';
			$('#txt_chg_pwd').removeClass('has-error');
			$('#txt_chg_pwd').removeClass('has-error');

			if ($('#txt_old_pwd').val().replace(/^\s+|\s+$/g) == '') {
				$('#txt_old_pwd').addClass('has-error');
				if ($('#spantxt_old_pwd').length == 0) {
					$('<span id="spantxt_old_pwd" class="help-block">Required *</span>').insertAfter('#txt_old_pwd');
				}
				validate = 1;
			}

			if ($('#txt_chg_pwd').val().replace(/^\s+|\s+$/g) == '') {
				$('#txt_chg_pwd').addClass('has-error');
				if ($('#spantxt_chg_pwd').length == 0) {
					$('<span id="spantxt_chg_pwd" class="help-block">Required *</span>').insertAfter('#txt_chg_pwd');
				}
				validate = 1;
			}

			if ($('#txt_chg_pwd1').val().replace(/^\s+|\s+$/g) == '') {
				$('#txt_chg_pwd1').addClass('has-error');
				if ($('#spantxt_chg_pwd1').length == 0) {
					$('<span id="spantxt_chg_pwd1" class="help-block">Required *</span>').insertAfter('#txt_chg_pwd1');
				}
				validate = 1;
			}
			// if ($('#txt_chg_pwd').val() == '' || !($('#result').hasClass('strong') || $('#result').hasClass('good'))) {
			// 	$('#txt_chg_pwd').addClass('has-error');
			// 	validate = 1;
			// 	toastr.error("Password Should not be empty or Too Short");
			// }
			// if (!($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val())) {
			// 	return false;
			// }
			if (validate == 1) {
				return false;
			}

		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>