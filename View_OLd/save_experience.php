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

$EmployeeID = $btnShow = $exp_id = '';
$exp_type = '';
$designation = "";
$discription = "";
$contact_person = "";
$contact_no = "";
$clientindustry = '';
$sqlInsertSave = '';
$sqlInsertDoc = '';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
$txt_salaryslip_bankstatement_doc = '';
$txt_appointment_offerletter_doc = '';
$txt_releiving_experience_doc = '';
$ClientIndustry = '';
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_exp_employer = cleanUserInput($_POST['txt_exp_employer']);
		$employer = (isset($clean_exp_employer) ? $clean_exp_employer : null);
		$clean_exp_employer  = cleanUserInput($_POST['txt_exp_location']);
		$location = (isset($clean_loc) ? $clean_loc : null);
		$clean_from = cleanUserInput($_POST['txt_exp_from']);
		$from = (isset($clean_from) ? $clean_from : null);
		$cleam_to = cleanUserInput($_POST['txt_exp_to']);
		$to = (isset($cleam_to) ? $cleam_to : null);
		$clean_desg = cleanUserInput($_POST['txt_exp_desg']);
		$desg = (isset($clean_desg) ? $clean_desg : null);
		$clean_disc = cleanUserInput($_POST['txt_exp_disc']);
		$disc = (isset($clean_disc) ? $clean_disc : null);
		$clean_exprience = cleanUserInput($_POST['txt_exp_experience']);
		$experience = (isset($clean_exprience) ? $clean_exprience : null);
		$clean_cnp = cleanUserInput($_POST['txt_exp_cnp']);
		$contperson = (isset($clean_cnp) ? $clean_cnp : null);
		$clean_cno = cleanUserInput($_POST['txt_exp_cno']);
		$contactno = (isset($clean_cno) ? $clean_cno : null);
		$clean_client_industry = cleanUserInput($_POST['clientindustry']);
		$ClientIndustry = (isset($clean_client_industry) ? $clean_client_industry : null);
	}
} else {
	$experience = $contactno = $contperson = $to = $desg = $disc = $employer = $location = $from = $ClientIndustry = '';
}
//Check Employee is exist or not
$clean_empid = cleanUserInput($_REQUEST['empid']);
$clean_desg = cleanUserInput($_POST['txt_exp_desg']);
if (isset($clean_empid) && $EmployeeID == '' && !isset(($clean_desg))) {
	$EmployeeID = $clean_empid;
	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
	} else {
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') }); window.location='" . URL . "'</script>";
	}
}
$clean_employee_id = cleanUserInput($_POST['EmployeeID']);
if (isset($clean_empid) && $EmployeeID == '') {
	$EmployeeID = $clean_empid;
} elseif (isset($clean_employee_id) && $clean_employee_id != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = $clean_employee_id;
	}
}

$ofc_loc = $loc = '';
$EmployeeID = strtoupper(cleanUserInput($clean_empid));
// $sql = 'select location from personal_details where EmployeeID = "' . $EmployeeID . '"';
$sql = 'select location from personal_details where EmployeeID =?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $EmployeeID);
$stmt->execute();
$result = $stmt->get_result();
$resultRow = $result->fetch_row();
// print_r($resultRow);
// die;

if ($result->num_rows > 0) {
	$loc = is_numeric($resultRow[0]); //['location'];
}

if ($loc == "1" || $loc == "2") {
	$ofc_loc = 'Docs/';
} else if ($loc == "3") {
	$ofc_loc = 'Meerut/Docs/';
} else if ($loc == "4") {
	$ofc_loc = 'Bareilly/Docs/';
} else if ($loc == "5") {
	$ofc_loc = 'Vadodara/Docs/';
} else if ($loc == "6") {
	$ofc_loc = 'Manglore/Docs/';
} else if ($loc == "7") {
	$ofc_loc = 'Bangalore/Docs/';
} else if ($loc == "8") {
	$ofc_loc = 'Nashik/Docs/';
} else if ($loc == "9") {
	$ofc_loc = 'Anantapur/Docs/';
}

if (isset($_FILES['txt_releiving_experience_doc']['name'])) {
	if (is_uploaded_file($_FILES['txt_releiving_experience_doc']['tmp_name']) && is_uploaded_file($_FILES['txt_appointment_offerletter_doc']['tmp_name']) && is_uploaded_file($_FILES['txt_salaryslip_bankstatement_doc']['tmp_name'])) {
		$sourcePath = $_FILES['txt_releiving_experience_doc']['tmp_name'];
		$sourcePath2 = $_FILES['txt_appointment_offerletter_doc']['tmp_name'];
		$sourcePath3 = $_FILES['txt_salaryslip_bankstatement_doc']['tmp_name'];

		$targetPath = ROOT_PATH . $ofc_loc . "Experience/" . basename($_FILES['txt_releiving_experience_doc']['name']);
		$targetPath2 = ROOT_PATH . $ofc_loc . "offerletter/" . basename($_FILES['txt_appointment_offerletter_doc']['name']);
		$targetPath3 = ROOT_PATH . $ofc_loc . "salaryslip/" . basename($_FILES['txt_salaryslip_bankstatement_doc']['name']);


		$uploadOk = 1;
		$FileType = pathinfo($targetPath, PATHINFO_EXTENSION);
		$FileType2 = pathinfo($targetPath2, PATHINFO_EXTENSION);
		$FileType3 = pathinfo($targetPath3, PATHINFO_EXTENSION);
		// Check file size
		if ($_FILES['txt_releiving_experience_doc']['size'] > 1000000) {
			echo "<script>$(function(){ toastr.error('Sorry, releiving / experience_doc file is too large. Upto 1MB acceptable'); }); </script>";
			$uploadOk = 0;
		}
		if ($_FILES['txt_appointment_offerletter_doc']['size'] > 1000000) {
			echo "<script>$(function(){ toastr.error('Sorry, appointment / offerletter file is too large. Upto 1MB acceptable');'); }); </script>";
			$uploadOk = 0;
		}
		if ($_FILES['txt_salaryslip_bankstatement_doc']['size'] > 1000000) {
			echo "<script>$(function(){ toastr.error('Sorry, salaryslip / bank-statement file is too large. Upto 1MB acceptable');'); }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if (strtolower($FileType) != "jpg" && strtolower($FileType) != "png" && strtolower($FileType) != "jpeg" && strtolower($FileType) != "pdf" && strtolower($FileType) != "msg") {
			echo "<script>$(function(){ toastr.error('Sorry, releiving / experience_doc only jpg, jpeg,pdf,msg and png files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		if (strtolower($FileType2) != "jpg" && strtolower($FileType2) != "png" && strtolower($FileType2) != "jpeg" && strtolower($FileType2) != "pdf" && strtolower($FileType2) != "msg") {
			echo "<script>$(function(){ toastr.error('Sorry, appointment / offerletter only jpg, jpeg ,png, pdf and msg files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		if (strtolower($FileType3) != "jpg" && strtolower($FileType3) != "png" && strtolower($FileType3) != "jpeg" && strtolower($FileType3) != "pdf" && strtolower($FileType3) != "msg") {
			echo "<script>$(function(){ toastr.error('Sorry, salaryslip / bank-statement experience_doc only jpg, jpeg,pdf,msg and png files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		if ($uploadOk == 1) {
			if (move_uploaded_file($sourcePath, $targetPath)) {
				$ext = pathinfo(basename($_FILES['txt_releiving_experience_doc']['name']), PATHINFO_EXTENSION);
				//$filename=$EmployeeID.'_Experince_'.preg_replace('/\s+/','',$desg).'_'.date("mdYhis").'.'.$ext;
				$filename = $EmployeeID . '_Experince.' . $ext;

				$files = rename($targetPath, ROOT_PATH . $ofc_loc . 'Experience/' . $filename);
			}
			if (move_uploaded_file($sourcePath2, $targetPath2)) {
				$ext2 = pathinfo(basename($_FILES['txt_appointment_offerletter_doc']['name']), PATHINFO_EXTENSION);
				//$filename2=$EmployeeID.'OfferL_'.preg_replace('/\s+/','',$desg).'_'.date("mdYhis").'.'.$ext2;
				$filename2 = $EmployeeID . '_OfferLetter.' . $ext2;

				$files2 = rename($targetPath2, ROOT_PATH . $ofc_loc . 'offerletter/' . $filename2);
			}
			if (move_uploaded_file($sourcePath3, $targetPath3)) {
				$ext3 = pathinfo(basename($_FILES['txt_salaryslip_bankstatement_doc']['name']), PATHINFO_EXTENSION);
				//$filename3=$EmployeeID.'SalaryS_'.preg_replace('/\s+/','',$desg).'_'.date("mdYhis").'.'.$ext3;
				$filename3 = $EmployeeID . '_SalarySlip.' . $ext3;
				$files3 = rename($targetPath3, ROOT_PATH . $ofc_loc . 'salaryslip/' . $filename3);
			}
		}

		if (file_exists(ROOT_PATH . $ofc_loc . 'Experience/' . $filename) && file_exists(ROOT_PATH . $ofc_loc . 'offerletter/' . $filename2) && file_exists(ROOT_PATH . $ofc_loc . 'salaryslip/' . $filename3)) {
			$txt_releiving_experience_doc = $filename;
			$txt_appointment_offerletter_doc = $filename2;
			$txt_salaryslip_bankstatement_doc = $filename3;
		} else {
			echo "<script>$(function(){ toastr.error('Sorry, Files not uploaded.'); }); </script>";
			$txt_releiving_experience_doc = '';
			$txt_appointment_offerletter_doc = '';
			$txt_salaryslip_bankstatement_doc = '';
		}
	} else {
		echo "<script>$(function(){ toastr.error('Sorry, Files upload again.'); }); </script>";
		$txt_releiving_experience_doc = '';
		$txt_appointment_offerletter_doc = '';
		$txt_salaryslip_bankstatement_doc = '';
	}
} else {
	$txt_releiving_experience_doc = '';
	$txt_appointment_offerletter_doc = '';
	$txt_salaryslip_bankstatement_doc = '';
}



if (isset($_POST['btn_experice_Add']) && $EmployeeID != '') {
	$myDB = new MysqliDb();
	$file = '';

	$createBy = clean($_SESSION['__user_logid']);
	if ($experience == 'Fresher') {
		$contactno = $contperson = $to = $desg = $disc = $employer = $location = $from = $txt_releiving_experience_doc = $txt_appointment_offerletter_doc = $txt_salaryslip_bankstatement_doc = '';
	}
	$myDB = new MysqliDb();
	// $select_exist_data = $myDB->query('select exp_id from experince_details where employer = "' . $employer . '" and location= "' . $location . '" and EmployeeID = "' . $EmployeeID . '"  ');
	$select_exist_dataQry = 'select exp_id from experince_details where employer =? and location= ? and EmployeeID = ? ';
	$stmt = $conn->prepare($select_exist_dataQry);
	$stmt->bind_param("sis", $employer, $location, $EmployeeID);
	$stmt->execute();
	$select_exist_data = $stmt->get_result();
	//$counter_check = 0;
	if ($select_exist_data && $select_exist_data->num_rows > 0) {

		if ($experience == "Experienced") {
			$exp_id = $select_exist_data[0]['exp_id'];
			$sqlInsertSave = 'call save_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $exp_id . '","' . $contperson . '","' . $contactno . '","' . $experience . '","' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
		} else
		if ($experience == 'Fresher') {
			$exp_id = $select_exist_data[0]['exp_id'];
			$sqlInsertSave = 'call save_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $exp_id . '","' . $contperson . '","' . $contactno . '","' . $experience . '","' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
		}
		//$myDB = new MysqliDb();	
		//$result=$myDB->query($sqlInsertSave);
		//$mysql_error =$myDB->getLastError();
	} else {
		if ($experience == "Experienced" and $txt_releiving_experience_doc != "" and $txt_appointment_offerletter_doc != "" and $txt_salaryslip_bankstatement_doc != "") {
			$sqlInsertDoc = 'call add_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $contperson . '","' . $contactno . '","' . $experience . '", "' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
		} else
		if ($experience == 'Fresher') {
			$sqlInsertDoc = 'call add_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $contperson . '","' . $contactno . '","' . $experience . '", "' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
		}
		if ($sqlInsertDoc != "") {

			$result = $myDB->query($sqlInsertDoc);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Experience is Saved Successfully') });</script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
			}
		}
	}
}
$clean_expselect = cleanUserInput($_POST['expselect']);
if (isset($_POST['btn_experice_Save']) && $clean_expselect != '') {

	$myDB = new MysqliDb();

	if ($txt_releiving_experience_doc == '') {
		$txt_releiving_experience_doc = clean($_POST['fileselect']);
	}
	if ($txt_appointment_offerletter_doc == '') {
		$txt_appointment_offerletter_doc = clean($_POST['fileselect2']);
	}
	if ($txt_salaryslip_bankstatement_doc == '') {
		$txt_salaryslip_bankstatement_doc = clean($_POST['fileselect3']);
	}

	$createBy = clean($_SESSION['__user_logid']);
	if ($experience == 'Fresher') {
		$contactno = $contperson = $to = $desg = $disc = $employer = $location = $from = '';
	}
	if (isset($clean_expselect) &&  $experience == "Experienced" && $txt_releiving_experience_doc != "" && $txt_appointment_offerletter_doc != "" && $txt_salaryslip_bankstatement_doc != "") {
		$exp_id = $clean_expselect;
		$sqlInsertDoc = 'call save_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $exp_id . '","' . $contperson . '","' . $contactno . '","' . $experience . '","' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
	} else
	if (isset($clean_expselect) && $experience == 'Fresher') {
		$exp_id = $clean_expselect;
		$txt_releiving_experience_doc = '';
		$txt_appointment_offerletter_doc = '';
		$txt_salaryslip_bankstatement_doc = '';
		$sqlInsertDoc = 'call save_experince("' . $employer . '","' . $location . '","' . $from . '","' . $to . '","' . $desg . '","' . $disc . '","' . $ClientIndustry . '","' . $createBy . '","' . $EmployeeID . '","' . $exp_id . '","' . $contperson . '","' . $contactno . '","' . $experience . '","' . $txt_releiving_experience_doc . '", "' . $txt_appointment_offerletter_doc . '", "' . $txt_salaryslip_bankstatement_doc . '")';
	}
	if ($sqlInsertDoc != "") {
		$result = $myDB->query($sqlInsertDoc);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Experience is Saved Successfully') });</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
		}
	}
}

$employer = "";
$location = "";
$from = "";
$to = "";
$designation = "";
$clientindustry = '';
$discription = "";
$contact_person = "";
$contact_no = "";
$exp_id = "";
// $sqlConnect = "select * from experince_details where EmployeeID='" . $EmployeeID . "' ";
$sqlConnect = "select * from experince_details where EmployeeID=? ";
$stm = $conn->prepare($sqlConnect);
$stm->bind_param("s", $EmployeeID);
$stm->execute();
$result = $stm->get_result();
// print_r($result);
// die;
?>

<script>
	$(document).ready(function() {
		var usrtype = <?php $clean_ut = clean($_SESSION["__user_type"]);
						echo "'" . $clean_ut . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR') {} else if (usrtype === 'AUDIT') {
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

		$('#txt_exp_disc,#txt_exp_cno').keydown(function(event) {
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

	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Experience Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Experience Details</h4>

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

				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" name="dir" id="dir" value="<?php echo $ofc_loc; ?>" />
				<input type="hidden" name="loc" id="loc" value="<?php echo is_numeric($loc); ?>" />
				<div class="input-field col s6 m6">

					<select id="txt_exp_experience" name="txt_exp_experience" required>
						<option value="NA" selected>---Select---</option>
						<option <?php if (trim($exp_type) == 'Experienced') echo "selected"; ?>>Experienced</option>
						<option <?php if (trim($exp_type) == 'Fresher') echo "selected"; ?>>Fresher</option>
					</select>
					<label for="txt_exp_experience" class="active-drop-down active">Experience *</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_employer" name="txt_exp_employer" value="<?php echo $employer; ?>" />
					<label for="txt_exp_employer">Previous Organization *</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_location" name="txt_exp_location" value="<?php echo $location; ?>" />
					<label for="txt_exp_location">Location *</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_from" name="txt_exp_from" value="<?php echo $from; ?>" />
					<label for="txt_exp_from">From *</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_to" name="txt_exp_to" value="<?php echo $to; ?>" />
					<label for="txt_exp_to">To *</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_desg" name="txt_exp_desg" value="<?php echo $designation; ?>" />
					<label for="txt_exp_desg">Designation</label>
				</div>

				<div class="input-field col s12 m12 exp_div">
					<div class="input-field col s6 m6 exp_div">
						<input type="text" id="txt_exp_disc" name="txt_exp_disc" value="<?php echo $discription; ?>" />
						<label for="txt_exp_disc">Drawn CTC (Monthly)</label>
					</div>
					<div class="input-field col s6 m6 exp_div">
						<input type="text" id="clientindustry" name="clientindustry" value="<?php echo $clientindustry; ?>" />
						<label for="clientindustry">Client Industry *</label>
					</div>
				</div>
				<div class="input-field col s12 m12 exp_div">
					<div class="file-field input-field col s4 m4 exp_div" style="margin-top: 62px;">
						<span>Relieving / Experience Certificate *</span><br>
						<div class="btn">
							Document1
							<input type="file" id="txt_releiving_experience_doc" name="txt_releiving_experience_doc">
							<br>
							<span class="file-size-text help-block" id="fdoc1">Accepts up to 1 MB File only.</span><br>
							<!--<span id="fdoc1" class="file-size-text help-block" >gffthfy yhiyhu </span>-->
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
					<div class="file-field input-field col s4 m4 exp_div" style="margin-top: 62px;">
						<span>Appointment / Offer Letter *</span><br>
						<div class="btn">
							Document2
							<input type="file" id="txt_appointment_offerletter_doc" name="txt_appointment_offerletter_doc">
							<br>
							<span class="file-size-text help-block" id="fdoc2">Accepts up to 1 MB File only.</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
					<div class="file-field input-field col s4 m4 exp_div" style="margin-top: 62px;">
						<span> Salary Slip / Bank Statement *</span> <br>
						<div class="btn">
							Document3
							<input type="file" id="txt_salaryslip_bankstatement_doc" name="txt_salaryslip_bankstatement_doc">
							<br>
							<span class="file-size-text help-block" id="fdoc3">Accepts up to 1 MB File only.</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
				</div>
				<div class="input-field col s12 m12 exp_div">


				</div>

				<div class="input-field col s12 m12 exp_div">
					<hr style="margin-top: 10px;margin-bottom: 10px;" />
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_cnp" name="txt_exp_cnp" maxlength='50' value="<?php echo $contact_person; ?>" />
					<label for="txt_exp_cnp">Contact Person</label>
				</div>

				<div class="input-field col s6 m6 exp_div">
					<input type="text" id="txt_exp_cno" maxlength='10' name="txt_exp_cno" value="<?php echo $contact_no; ?>" />
					<label for="txt_exp_cno">Contact No</label>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" title="Update Details" name="btn_experice_Save" id="btn_experice_Save" class="btn waves-effect waves-green  hidden">Save</button>
					<button type="submit" title="Add Details" name="btn_experice_Add" id="btn_experice_Add" class="btn waves-effect waves-green  ">Add</button>
					<button type="submit" title="Cancel Details" name="btn_experice_Can" id="btn_experice_Can" class="btn waves-effect modal-action modal-close waves-red close-btn  hidden">Cancel</button>
				</div>

				<input type="hidden" name="fileselect" id="fileselect" />
				<input type="hidden" name="fileselect2" id="fileselect2" />
				<input type="hidden" name="fileselect3" id="fileselect3" />
				<input type="hidden" name="expselect" id="expselect" value="<?php echo $exp_id; ?>" />



				<div id="pnlTable">
					<?php
					// $sqlConnect = "select * from experince_details where EmployeeID='" . $EmployeeID . "' ";
					$sqlConnect = "select * from experince_details where EmployeeID=? ";
					$sts = $conn->prepare($sqlConnect);
					$sts->bind_param("s", $EmployeeID);
					$sts->execute();
					$result = $sts->get_result();
					// $result = $myDB->query($sqlConnect);
					if ($result) { ?>
						<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							<div style="overflow: auto;">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="hidden">Exp_id</th>
											<th>Experience</th>
											<th>Organization</th>
											<th>Location</th>
											<th>From</th>
											<th>To</th>
											<th>Designation</th>
											<th>Drawn CTC</th>
											<th>ContactPerson</th>
											<th>ContactNo</th>
											<th>File</th>
											<th>File2</th>
											<th>File3</th>
											<th style="width:100px;">Manage Doc </th>
											<th class="hidden">clientindustry </th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($result as $key => $value) {
											echo '<tr>';
											echo '<td class="ExpID hidden">' . $value['exp_id'] . '</td>';
											echo '<td class="exp_type">' . $value['exp_type'] . '</td>';
											echo '<td class="employer">' . $value['employer'] . '</td>';
											echo '<td class="location">' . $value['location'] . '</td>';
											echo '<td class="from">' . $value['from'] . '</td>';
											echo '<td class="to">' . $value['to'] . '</td>';
											echo '<td class="designation">' . $value['designation'] . '</td>';
											echo '<td class="discription">' . $value['discription'] . '</td>';
											echo '<td class="contact_person">' . $value['contact_person'] . '</td>';
											echo '<td class="contact_no">' . $value['contact_no'] . '</td>';
											echo '<td class="file">' . $value['releiving_experience_doc'];
											if ($value['releiving_experience_doc'] != "") {
												echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $value['releiving_experience_doc'] . '|Experience"  data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
											}
											echo '</td>';
											echo '<td class="file2">' . $value['appointment_offerletter_doc'];
											if ($value['appointment_offerletter_doc'] != "") {
												echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $value['appointment_offerletter_doc'] . '|offerletter"  data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
											}
											echo '</td>';
											echo '<td class="file3">' . $value['salaryslip_bankstatement_doc'];
											if ($value['salaryslip_bankstatement_doc'] != "") {
												echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $value['salaryslip_bankstatement_doc'] . '|salaryslip"  data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
											}
											echo '</td>';

											echo '<td>
								
								
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return Edit(this);" data="' . $value['exp_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i>
								
								<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return Delete(this);" data-file="' . $value['releiving_experience_doc'] . '" id="' . $value['exp_id'] . '" data-position="left" data-tooltip="Delete">ohrm_delete</i>
								
								</td>
								<td class="clientindustry hidden">' . $value["ClientIndustry"] . '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
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

<script>
	$(document).ready(function() {
		$('#txt_exp_from').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			maxDate: 0,
			onChangeDateTime: function(dp, $input) {
				if ($('#txt_exp_to').val() != '') {
					if ($input.val() > $('#txt_exp_to').val()) {
						$(function() {
							toastr.error('Start Date Should be less then End date')
						});
					}
				}
			}
		});




		$('#txt_exp_to').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			maxDate: 0,
			onChangeDateTime: function(dp, $input) {
				if ($input.val() < $('#txt_exp_from').val()) {
					$(function() {
						toastr.error('Start Date Should be less then End date')
					});
					$('#txt_exp_to').val('');
				}
			}
		});
		$('#doc_child').val($(".trdoc").length);

		$('input[type="text"]').click(function() {
			$(this).removeClass('has-error');
		});
		$('select').click(function() {
			$(this).removeClass('has-error');
		});
		$('#btn_experice_Save,#btn_experice_Add').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				console.log($(this).attr('id'));
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

		$('#btn_experice_Save,#btn_experice_Add').on('click', function() {
			var validate = 0;
			var alert_msg = '';

			if ($('#txt_exp_experience').val() == 'Experienced') {
				if ($('#txt_exp_employer').val() == '') {
					$('#txt_exp_employer').addClass('has-error');
					if ($('#stxt_exp_employer').size() == 0) {
						$('<span id="stxt_exp_employer" class="help-block">Employer Name can not be Empty</span>').insertAfter('#txt_exp_employer');
					}
					validate = 1;
				}
				if ($('#txt_exp_location').val() == '') {
					$('#txt_exp_location').addClass('has-error');
					if ($('#stxt_exp_location').size() == 0) {
						$('<span id="stxt_exp_location" class="help-block">Location can not be Empty.</span>').insertAfter('#txt_exp_location');
					}
					validate = 1;
				}

				if ($('#txt_exp_from').val() == '') {
					$('#txt_exp_from').addClass('has-error');
					if ($('#stxt_exp_from').size() == 0) {
						$('<span id="stxt_exp_from" class="help-block">Date From can not be Empty.</span>').insertAfter('#txt_exp_from');
					}
					validate = 1;
				}
				if ($('#txt_exp_to').val() == '') {
					$('#txt_exp_to').addClass('has-error');
					if ($('#stxt_exp_to').size() == 0) {
						$('<span id="stxt_exp_to" class="help-block">Date To can not be Empty.</span>').insertAfter('#txt_exp_to');
					}
					validate = 1;
				} else {
					if ($('#txt_exp_from').val() > $('#txt_exp_to').val() && $('#txt_exp_from').val() != '') {
						$('#txt_exp_to').addClass('has-error');
						if ($('#stxt_exp_to').size() == 0) {
							$('<span id="stxt_exp_to" class="help-block">Date To can not Less then From Date.</span>').insertAfter('#txt_exp_to');
						}
						validate = 1;
					}
				}

				if ($('#clientindustry').val() == '') {
					$('#clientindustry').addClass('has-error');
					if ($('#sclientindustry').size() == 0) {
						$('<span id="sclientindustry" class="help-block">Client Industry can not be Empty.</span>').insertAfter('#clientindustry');
					}
					validate = 1;
				}
				if ($('#txt_releiving_experience_doc').val() == '' && $('#fileselect').val() == '') {

					if ($('#stxt_releiving_experience_doc').size() == 0) {
						$('<span id="stxt_releiving_experience_doc" class=" file-size-text help-block help-block"><br><b>Releiving / Experience Letter can not be Empty.</b></span>').insertAfter('#fdoc1');
					}
					validate = 1;
				} else {
					$('#stxt_releiving_experience_doc').empty();
				}
				if ($('#txt_appointment_offerletter_doc').val() == '' && $('#fileselect2').val() == '') {
					$('#txt_appointment_offerletter_doc').addClass('has-error');
					if ($('#stxt_appointment_offerletter_doc').size() == 0) {
						$('<span id="stxt_appointment_offerletter_doc" class=" file-size-text help-block"><br><b>Appointment / Offerletter can not be Empty.</b></span>').insertAfter('#fdoc2');
					}
					validate = 1;
				} else {
					$('#stxt_appointment_offerletter_doc').empty();
				}
				if ($('#txt_salaryslip_bankstatement_doc').val() == '' && $('#fileselect3').val() == '') {
					$('#txt_salaryslip_bankstatement_doc').addClass('has-error');
					if ($('#stxt_salaryslip_bankstatement_doc').size() == 0) {
						$('<span id="stxt_salaryslip_bankstatement_doc" class="file-size-text help-block"><br><b>Salaryslip / Bankstatement can not be Empty.</b></span>').insertAfter('#fdoc3');
					}
					validate = 1;
				} else {
					$('#stxt_salaryslip_bankstatement_doc').empty();
				}

			}
			if (validate == 1) {
				return false;
			}
		});


		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#txt_exp_experience').change(function() {
			if ($('#txt_exp_experience').val() == 'Experienced') {
				$('.exp_div').removeClass('hidden');
			} else {
				$('.exp_div').addClass('hidden');
			}
		});
		$('#txt_exp_experience').trigger('change');

	});

	function Delete(el) {
		if (confirm('You want to delete This Doc ')) {
			$item = $(el);
			$.ajax({
				url: "../Controller/deleteExp.php?ID=" + $item.attr("id") + "&file=" + $item.attr('data-file'),
				success: function(result) {
					$var = result.split('|');
					//alert($var[0]=="done");
					if ($var[0] == "done") {
						$item.closest("tr").remove();
					}
					$(function() {
						toastr.success($var[1])
					});

				}
			});
		}
	}

	function Edit(el) {
		$('.exp_div').removeClass('hidden');
		$('#txt_exp_experience').trigger('change');
		var tr = $(el).closest('tr');
		var ExpID = tr.find('.ExpID').text();
		var employer = tr.find('.employer').text();
		var location = tr.find('.location').text();
		var from = tr.find('.from').text();
		var to = tr.find('.to').text();
		var designation = tr.find('.designation').text();
		var discription = tr.find('.discription').text();
		var exp_type = tr.find('.exp_type').text();
		var contact_person = tr.find('.contact_person').text();
		var contact_no = tr.find('.contact_no').text();
		var clientindustry = tr.find('.clientindustry').text();

		var file = tr.find('.file').text();
		if ((file.indexOf("ohrm_file_download")) > 0) {
			//alert('t');
			file = file.substring(0, file.indexOf("ohrm_file_download")).trim();
		}

		var file2 = tr.find('.file2').text();
		var file3 = tr.find('.file3').text();

		if ((file2.indexOf("ohrm_file_download")) > 0) {
			//alert('t');
			file2 = file2.substring(0, file2.indexOf("ohrm_file_download")).trim();
		}
		//alert(file3);
		if ((file3.indexOf("ohrm_file_download")) > 0) {
			//alert('t');
			file3 = file3.substring(0, file3.indexOf("ohrm_file_download")).trim();
		}


		$('#txt_exp_experience').val(exp_type).trigger('change');
		$('#txt_exp_employer').val(employer);
		$('#txt_exp_location').val(location);
		$('#txt_exp_from').val(from);
		$('#txt_exp_to').val(to);
		$('#txt_exp_desg').val(designation);
		$('#txt_exp_disc').val(discription);
		$('#fileselect').val(file);
		$('#fileselect2').val(file2);
		$('#fileselect3').val(file3);
		$('#clientindustry').val(clientindustry);
		$('#expselect').val(ExpID);

		$('#txt_exp_cnp').val(contact_person);
		$('#txt_exp_cno').val(contact_no);

		$('#btn_experice_Add').addClass('hidden');
		$('#btn_experice_Save').removeClass('hidden');
		$('#btn_experice_Can').removeClass('hidden');
	}

	function Download(el) {
		var datafile = $(el).attr("data");
		datafile = datafile.split('|');

		var file = datafile[0];
		var filepath = datafile[1];

		if (file != '') {
			function getImageDimensions(path, callback) {
				var img = new Image();
				img.onload = function() {
					callback({
						width: img.width,
						height: img.height,
						srcsrc: img.src
					});
				}
				img.src = path;
			}
			var dirpath = $('#dir').val();
			//alert(filepath);
			//alert(dirpath+filepath+file);
			$.ajax({
				url: "../" + dirpath + filepath + "/" + file,
				type: 'HEAD',
				error: function() {
					alert('No File Exist');
				},
				success: function() {
					imgcheck = function(filename) {
						return (filename).split('.').pop();
					}
					imgchecker = imgcheck("../" + dirpath + filepath + "/" + file);

					if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
						getImageDimensions("../" + dirpath + filepath + "/" + file, function(data) {
							var img = data;

							$('<img>', {
								src: "../" + dirpath + filepath + "/" + file
							}).watermark({
								//text: 'ⓒ For Cogent E Services Ltd.',
								text: 'Cogent E Services Ltd.',
								//path:'../Style/images/cogent-logobkp.png',
								textWidth: 370,
								opacity: 1,
								textSize: (img.height / 15),
								nH: img.height,
								nW: img.width,
								textColor: "rgb(0,0,0,0.4)",
								outputType: 'jpeg',
								gravity: 'sw',
								done: function(imgURL) {
									var link = document.createElement('a');
									link.href = imgURL;
									link.download = file;
									document.body.appendChild(link);
									link.click();

								}
							});

						});
					} else if (imgchecker.match(/(pdf)$/i)) {

						window.open("../" + dirpath + filepath + "/" + file);
						//window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../../"+dirpath+filepath+"/"+file);
					} else {
						window.open("../" + dirpath + filepath + "/" + file);
					}

				}
			});

		} else {
			alert('No File Exist');
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>