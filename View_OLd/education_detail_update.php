<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$imsrc = URL . 'Style/images/agent-icon.png';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = $btnShow = '';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$txt_edu_college_1 = cleanUserInput($_POST['txt_edu_college_1']);
	$college_1 = (isset($txt_edu_college_1) ? $txt_edu_college_1 : null);
	$txt_edu_bu_1 = cleanUserInput($_POST['txt_edu_bu_1']);
	$bu_1 = (isset($txt_edu_bu_1) ? $txt_edu_bu_1 : null);
	$txt_edu_Spc_1 = cleanUserInput($_POST['txt_edu_Spc_1']);
	$Spc_1 = (isset($txt_edu_Spc_1) ? $txt_edu_Spc_1  : null);
	$txt_edu_Name_1 = cleanUserInput($_POST['txt_edu_Name_1']);
	$Name_1 = (isset($txt_edu_Name_1) ? $txt_edu_Name_1 : null);
	$txt_edu_lvl_1 = cleanUserInput($_POST['txt_edu_lvl_1']);
	$lvl_1 = (isset($txt_edu_lvl_1) ? $txt_edu_lvl_1 : null);
	if ($lvl_1 == '12th Pass' || $lvl_1 == 'Pursuing Graduation') {
		$lvl_1 = "Basic";
		$Name_1 = '12th';
	}
	$txt_edu_percentage_1 = cleanUserInput($_POST['txt_edu_percentage_1']);
	$percentage_1 = (isset($txt_edu_percentage_1) ? $txt_edu_percentage_1 : null);
	$txt_edu_division_1 = cleanUserInput($_POST['txt_edu_division_1']);
	$division_1 = (isset($txt_edu_division_1) ? $txt_edu_division_1 : null);
	$txt_edu_othlvl_1 = cleanUserInput($_POST['txt_edu_othlvl_1']);
	$othlvl_1 = (isset($txt_edu_othlvl_1) ? $txt_edu_othlvl_1 : null);
	$txt_edu_othrName_1 = cleanUserInput($_POST['txt_edu_othrName_1']);
	$othrName_1 = (isset($txt_edu_othrName_1) ? $txt_edu_othrName_1 : null);
	$txt_edu_othrSpc_1 = cleanUserInput($_POST['txt_edu_othrSpc_1']);
	$othrSpc_1 = (isset($txt_edu_othrSpc_1) ? $txt_edu_othrSpc_1 : null);
	$txt_edu_othrbu_1 = cleanUserInput($_POST['txt_edu_othrbu_1']);
	$othrbu_1 = (isset($txt_edu_othrbu_1) ? $txt_edu_othrbu_1 : null);
	$txt_edu_type_1 = cleanUserInput($_POST['txt_edu_type_1']);
	$type_1 = (isset($txt_edu_type_1) ? $txt_edu_type_1 : null);
	$txt_edu_pass_y = cleanUserInput($_POST['txt_edu_pass_y']);
	$pass = (isset($txt_edu_pass_y) ? $txt_edu_pass_y : null);
} else {
	$type_1 = $othrbu_1 = $othrSpc_1 = $othrName_1 = $othlvl_1 = $division_1 = $percentage_1 = $lvl_1 = $Name_1 = $college_1 = $bu_1 = $Spc_1 = $pass = '';
}

$user_logid = clean($_SESSION['__user_logid']);

if (isset($user_logid) && $user_logid != '') {
	$EmployeeID = $user_logid;
}

if (isset($_POST['btn_education_Add']) && $EmployeeID != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$myDB = new MysqliDb();
		$file = '';
		$uploadOk = 1;
		$sourcePath = $_FILES['txt_edu_file']['tmp_name'];
		$targetPath = ROOT_PATH . "EducationTemp/" . basename($_FILES['txt_edu_file']['name']);
		$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
		// Check file size
		// if ($_FILES["txt_edu_file"]["size"] > 400000) {
		// 	echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
		// 	$uploadOk = 0;
		// }
		// Allow certain file formats
		// if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg") {
		// 	echo "<script>$(function(){ toastr.error('Sorry, only jpg and png files are allowed.'); }); </script>";
		// 	$uploadOk = 0;
		// }
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1) {

			if (is_uploaded_file($_FILES['txt_edu_file']['tmp_name'])) {
				if (move_uploaded_file($sourcePath, $targetPath)) {
					$ext = pathinfo(basename($_FILES['txt_edu_file']['name']), PATHINFO_EXTENSION);
					if ($lvl_1 != "Basic") {
						$filename = $EmployeeID . 'Education_' . preg_replace('/\s+/', '', $lvl_1) . '.' . $ext;
					} else {
						$filename = $EmployeeID . 'Education_' . preg_replace('/\s+/', '', $Name_1) . '.' . $ext;
					}
					$files = rename($targetPath, ROOT_PATH . 'EducationTemp/' . $filename);
					if (file_exists(ROOT_PATH . 'EducationTemp/' . $filename)) {

						$file = $filename;
					} else {
						$file = '';
					}
				} else {
					$file = '';
				}
			} else {
				$file = '';
			}

			$createBy = $user_logid;
			//echo  'select * from education_details where edu_level = "'.$Name_1.'"  and EmployeeID = "'.$EmployeeID.'"';
			$myDB = new MysqliDb();
			$conn = $myDB->dbConnect();
			if ($lvl_1 != "Basic") {
				// $selectQuery = 'select * from education_details where edu_level = "' . $lvl_1 . '" and EmployeeID = "' . $EmployeeID . '"';
				$selectQuery = 'select * from education_details where edu_level = ? and EmployeeID = ?';
				$stmt = $conn->prepare($selectQuery);
				$stmt->bind_param("ss", $lvl_1, $EmployeeID);
				$stmt->execute();
			} else {
				// $selectQuery = 'select * from education_details where edu_name = "' . $Name_1 . '" and edu_level = "' . $lvl_1 . '" and EmployeeID = "' . $EmployeeID . '"';
				$selectQuery = 'select * from education_details where edu_name = ? and edu_level =? and EmployeeID = ?';
				$stmt = $conn->prepare($selectQuery);
				$stmt->bind_param("sss", $Name_1, $lvl_1, $EmployeeID);
				$stmt->execute();
			}
			//echo $selectQuery;
			$select_exist_data = $stmt->get_result();
			$select_exist_dataRow = $select_exist_data->fetch_row();
			// $select_exist_data = $myDB->query($selectQuery);
			$counter_check = 0;
			if ($select_exist_data) {
				if (isset($select_exist_dataRow[9]) && $select_exist_dataRow[9] != "") {
					$counter_check = 1;
				}
			}

			if ($counter_check == 0) {
				$sqlInsertDoc = 'call add_education_temp("' . $lvl_1 . '","' . $Name_1 . '","' . $Spc_1 . '","' . $bu_1 . '","' . $college_1 . '","' . $type_1 . '","' . $division_1 . '","' . $EmployeeID . '","' . $file . '","' . $createBy . '","' . $percentage_1 . '","' . $pass . '")';
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlInsertDoc);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Education is Saved Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.info('Data allready exists " . $lvl_1 . " and " . $Name_1 . "'); }); </script>";
			}
		}
	}
}
if (isset($_POST['btn_education_Save']) && $_POST['eduselect'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$myDB = new MysqliDb();
		$file = $_POST['fileselect'];

		$uploadOk = 1;
		$sourcePath = $_FILES['txt_edu_file']['tmp_name'];
		$targetPath = ROOT_PATH . "EducationTemp/" . basename($_FILES['txt_edu_file']['name']);
		$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
		// Check file size
		if ($_FILES["txt_edu_file"]["size"] > 400000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($FileType != "jpg" && $FileType != "jpeg" && $FileType != "png") {
			if ($_FILES["txt_edu_file"]["size"] != 0) {
				echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg and png files are allowed.'); }); </script>";
			}

			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1 || ($_FILES["txt_edu_file"]["size"] == 0 && !empty($file))) {



			if (is_uploaded_file($_FILES['txt_edu_file']['tmp_name'])) {

				if (move_uploaded_file($sourcePath, $targetPath)) {
					$ext = pathinfo(basename($_FILES['txt_edu_file']['name']), PATHINFO_EXTENSION);
					if ($lvl_1 != "Basic") {
						$filename = $EmployeeID . 'Education_' . preg_replace('/\s+/', '', $lvl_1) . '.' . $ext;
					} else {
						$filename = $EmployeeID . 'Education_' . preg_replace('/\s+/', '', $Name_1) . '.' . $ext;
					}
					$files = rename($targetPath, ROOT_PATH . 'EducationTemp/' . $filename);
					if (file_exists(ROOT_PATH . 'EducationTemp/' . $filename)) {
						$file = $filename;
					} else {
						$file = cleanUserInput($_POST['fileselect']);
					}
				} else {
					$file = cleanUserInput($_POST['fileselect']);
				}
			} else {
				$file = cleanUserInput($_POST['fileselect']);
			}

			$createBy = $user_logid;
			$eduselect = cleanUserInput($_POST['eduselect']);
			$sqlInsertDoc = 'call save_education_temp("' . $lvl_1 . '","' . $Name_1 . '","' . $Spc_1 . '","' . $bu_1 . '","' . $college_1 . '","' . $type_1 . '","' . $division_1 . '","' . $EmployeeID . '","' . $file . '","' . $createBy . '","' . $percentage_1 . '","' . $eduselect . '","' . $pass . '")';
			$myDB = new MysqliDb();
			$result = $myDB->query($sqlInsertDoc);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Education is Saved Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "'); }); </script>";
			}
		}
	}
}
?>
<script>
	$(document).ready(function() {
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
		$('#txt_edu_percentage_1').keyup(function(event) {
			var input = parseInt(this.value);
			$('#txt_edu_percentage_1').removeClass('has-error');
			if (input < 0 || input > 100) {
				$('#txt_edu_percentage_1').addClass('has-error');

			} else if (isNaN(input)) {
				$('#txt_edu_percentage_1').addClass('has-error');
			}
		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Education Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Education Details</h4>

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
				<div class="container col s12 m12" id="childtables">
					<input type="hidden" id="Document Details" name="doc_child" value="1" />


					<?php
					$edu_id = "";
					$edu_level = "";
					$edu_name = "";
					$specialization = "";
					$board = "";
					$college = "";
					$edu_type = "";
					$division  = "";
					$passing_year  = "";
					$percentage  = "";
					$education_doc = '';

					// $sqlBy = "SELECT * from education_details where EmployeeID='" . $EmployeeID . "' ";
					$sqlBy = "SELECT * from education_details where EmployeeID=? ";
					$stmt = $conn->prepare($sqlBy);
					$stmt->bind_param("s", $EmployeeID);
					$stmt->execute();
					$resultBy = $stmt->get_result();
					// $resultBy = $myDB->rawQuery($sqlBy);
					// $mysql_error = $myDB->getLastError();
					$empid = clean($_REQUEST['empid']);
					if (($resultBy) and isset($empid)) {
						foreach ($resultBy as $key => $value) {
							$edu_id = $value['edu_id'];
							$edu_level = $value['edu_level'];
							$edu_name = $value['edu_name'];
							$specialization = $value['specialization'];
							$board = $value['board'];
							$college = $value['college'];
							$edu_type = $value['edu_type'];
							$division  = $value['division'];
							$passing_year  = $value['passing_year'];
							$percentage  = $value['percentage'];
							if ($edu_level == '12th Pass' || $edu_level == 'Pursuing Graduation') {
								$edu_level = 'Basic';
								$edu_name = '12th';
							}

							//echo  ROOT_PATH."Education/".$value['edu_file'];
						}
					}
					?>
					<div class="input-field col s6 m6">
						<select id="txt_edu_lvl_1" name="txt_edu_lvl_1">
							<option value="NA">---Select---</option>
							<?php
							$myDB = new MysqliDb();
							$rst_level = $myDB->query('select * from education_level');
							if ($rst_level) {
								foreach ($rst_level as $kye => $value) {
							?>
									<option <?php if ($edu_level == $value['level']) {
												echo 'selected';
											} ?>><?php echo $value['level']; ?></option>
							<?php
								}
							}
							?>
						</select>
						<label for="txt_edu_lvl_1" class="active-drop-down active">Education Level *</label>
					</div>

					<div class="input-field col s6 m6">
						<input type="hidden" id="txt_eduName" />

						<select id="txt_edu_Name_1" name="txt_edu_Name_1">
							<?php


							// $sql = 'SELECT * FROM education_name where edu_lvl ="' . $edu_level . '"';
							$sql = 'SELECT * FROM education_name where edu_lvl =?';
							$stm = $conn->prepare($sql);
							$stm->bind_param("s", $edu_level);
							$stm->execute();
							$result = $stm->get_result();
							// $myDB = new MysqliDb();
							// $result = $myDB->query($sql);
							// $mysql_error = $myDB->getLastError();
							if ($result) {
								echo '<option value="NA" >---Select---</option>';
								foreach ($result as $key => $value) { ?>
									<option <?php if ($edu_name == $value['edu_name']) {
												echo "selected";
											} ?>><?php echo $value['edu_name']; ?></option>
							<?php }
							} else {
								echo '<option value="NA" >---Select---</option>';
							}
							?>

						</select>
						<label for="txt_edu_Name_1" class="active-drop-down active">Education Name *</label>
					</div>

					<div class="input-field col s6 m6">
						<select id="txt_edu_Spc_1" name="txt_edu_Spc_1">
							<option value="NA" selected="true">---Select---</option>
							<?php
							$myDB = new MysqliDb();
							$rst_level = $myDB->query('select * from education_specilization');
							if ($rst_level) {
								foreach ($rst_level as $kye => $value) {
							?>
									<option <?php if ($specialization != "" and $specialization == $value['specilization']) echo "selected"; ?>><?php echo $value['specilization']; ?></option>
							<?php
								}
							}
							?>
						</select>
						<label for="txt_edu_Spc_1" class="active-drop-down active">Specialization *</label>
					</div>
					<div class="input-field col s6 m6">
						<select id="txt_edu_bu_1" name="txt_edu_bu_1">
							<option value="NA">---Select---</option>
							<?php
							$myDB = new MysqliDb();
							$rst_level = $myDB->query('select * from education_board');
							if ($rst_level) {
								foreach ($rst_level as $kye => $value) {
							?>
									<option <?php if ($board == $value['board']) {
												echo "selected";
											} ?>> <?php echo $value['board']; ?></option>
							<?php
								}
							}
							?>
						</select>
						<label for="txt_edu_bu_1" class="active-drop-down active">Board / University *</label>
					</div>
					<!--	
				<input type="text" class="form-control clsInput hidden" id="txt_edu_othrbu_1" name="txt_edu_othrbu_1" />-->


					<div class="input-field col s6 m6">
						<input type="text" id="txt_edu_college_1" name="txt_edu_college_1" value="<?php echo $college; ?>" />
						<label for="txt_edu_college_1">College / School *</label>
					</div>

					<div class="input-field col s6 m6">
						<?php
						if ($edu_type == 'Regular') {
							$edu_type = 'Reguler learning';
						} else
				if ($edu_type == 'correspondence') {
							$edu_type = 'Correspondence';
						}
						?>
						<select id="txt_edu_type_1" name="txt_edu_type_1">
							<option value="NA">---Select---</option>
							<option <?php if ($edu_type == 'Reguler learning') {
										echo "selected";
									} ?> value="Reguler learning">Reguler learning</option>
							<option <?php if ($edu_type == 'Open learning') {
										echo 'selected';
									} ?> value="Open learning">Open learning</option>
							<option <?php if ($edu_type == 'Correspondence') {
										echo 'selected';
									} ?> value="Correspondence">Correspondence</option>
							<option <?php if ($edu_type == 'e learning/Online learning') {
										echo 'selected';
									} ?> value='e learning/Online learning'>e learning/Online learning</option>
						</select>
						<label for="txt_edu_type_1" class="active-drop-down active">Education Type *</label>
					</div>

					<div class="input-field col s6 m6 hidden">
						<select id="txt_edu_division_1" name="txt_edu_division_1">
							<option value="NA">---Select---</option>
							<option>First Division</option>
							<option>Second Division</option>
							<option>Third Division</option>
						</select>
						<label for="txt_edu_division_1" class="active-drop-down active">Division *</label>
					</div>

					<div class="input-field col s6 m6">
						<select id="txt_edu_pass_y" name="txt_edu_pass_y">
							<option value="NA">---Select---</option>
							<?php for ($i = 1; $i < 100; $i++) { ?>
								<option <?php if (1950 + $i == $passing_year) {
											echo "selected";
										} ?>><?php echo 1950 + $i; ?></option>
							<?php  } ?>
						</select>
						<label for="txt_edu_pass_y" class="active-drop-down active">Passing Year *</label>
					</div>

					<div class="input-field col s6 m6">
						<input type="text" maxlength="5" title="Only Number Percentage" id="txt_edu_percentage_1" name="txt_edu_percentage_1" value="<?php echo $percentage; ?>" />
						<label for="txt_edu_percentage_1">Percentage *</label>
					</div>

					<div class="file-field input-field col s6 m6">
						<div class="btn">
							<span>File</span>
							<input type="file" id="txt_edu_file" name="txt_edu_file">
							<br>
							<span class="file-size-text help-block" id="fileid">Accepts up to 400KB File only.</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
					<input type="hidden" name="fileselect" id="fileselect" />
					<div id="comment_div" class="hidden input-field col s6 m6 ">

						<textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment" maxlength="255" readonly></textarea>
						<label for="txt_Comment">HR Comment</label>

					</div>
					<input type="hidden" name="eduselect" id="eduselect" value="<?php echo $edu_id; ?>" />

					<div class="input-field col s12 m12 right-align">
						<button type="submit" title="Update Details" name="btn_education_Save" id="btn_education_Save" class="btn waves-effect waves-green  hidden">Save</button>
						<button type="submit" title="Add Details" name="btn_education_Add" id="btn_education_Add" class="btn waves-effect waves-green">Add</button>
						<button type="submit" title="Cancle Details" name="btn_education_Can" id="btn_education_Can" class="btn waves-effect modal-action modal-close waves-red close-btn  hidden">Cancle</button>
					</div>

					<div id="pnlTable">
						<?php
						// $sqlConnectTemp = "select * from education_details_temp where EmployeeID='" . $EmployeeID . "' and hr_status!='Approved' order by edu_id desc ";
						$sqlConnectTemp = "select * from education_details_temp where EmployeeID=? and hr_status!='Approved' order by edu_id desc ";
						$sts = $conn->prepare($sqlConnectTemp);
						$sts->bind_param("s", $EmployeeID);
						$sts->execute();
						$resultTemp = $sts->get_result();
						// $resultTemp = $myDB->query($sqlConnectTemp);
						// $mysql_error = $myDB->getLastError();

						$sqlConnect = "select * from education_details where EmployeeID=? ";
						$stss = $conn->prepare($sqlConnect);
						$stss->bind_param("s", $EmployeeID);
						$stss->execute();
						$result = $stss->get_result();
						// $result = $myDB->query($sqlConnect);
						// $mysql_error = $myDB->getLastError();
						if ($result) { ?>
							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<div style="overflow: auto;">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th class="hidden">Edu ID</th>
												<th>Education Level</th>
												<th>Education Name</th>
												<th>Specialization</th>
												<th>Board/University</th>
												<th>College</th>
												<th>Type</th>
												<th>Passing Year</th>
												<th class="hidden">Division</th>
												<th class="hidden">Percentage</th>
												<th class="hidden">File</th>

												<th class="">HR Status</th>

												<th class="hidden">HR comment</th>
												<th>Manage Details </th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($resultTemp->num_rows > 0) {
												foreach ($resultTemp as $key => $value) {
													echo '<tr>';
													echo '<td class="edu_id hidden">' . $value['edu_id'] . '</td>';
													echo '<td class="edu_level">' . $value['edu_level'] . '</td>';
													echo '<td class="edu_name">' . $value['edu_name'] . '</td>';
													echo '<td class="specialization">' . $value['specialization'] . '</td>';
													echo '<td class="board">' . $value['board'] . '</td>';
													echo '<td class="college">' . $value['college'] . '</td>';
													echo '<td class="edu_type">' . $value['edu_type'] . '</td>';
													echo '<td class="passing_year">' . $value['passing_year'] . '</td>';
													echo '<td class="division hidden">' . $value['division'] . '</td>';
													echo '<td class="percentage hidden">' . $value['percentage'] . '</td>';
													echo '<td class="edu_file hidden">' . $value['edu_file'] . '</td>';
													echo '<td class="hr_status ">' . $value['hr_status'] . '</td>';
													//echo '<td class="hr_comment hidden">'.$value['hr_comment'].'</td>';	

													echo '<td class="manage_item" >';
													if ($value['hr_status'] == 'Pending') {
														echo '<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return Edit(this);" data="' . $value['edu_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i>';


														echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="EducationTemp/' . $value['edu_file'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
													}

													echo '</td>';
													echo '</tr>';
												}
											}
											foreach ($result as $key => $value) {
												echo '<tr>';
												echo '<td class="edu_id hidden">' . $value['edu_id'] . '</td>';
												echo '<td class="edu_level">' . $value['edu_level'] . '</td>';
												echo '<td class="edu_name">' . $value['edu_name'] . '</td>';
												echo '<td class="specialization">' . $value['specialization'] . '</td>';
												echo '<td class="board">' . $value['board'] . '</td>';
												echo '<td class="college">' . $value['college'] . '</td>';
												echo '<td class="edu_type">' . $value['edu_type'] . '</td>';
												echo '<td class="passing_year">' . $value['passing_year'] . '</td>';
												echo '<td class="division hidden">' . $value['division'] . '</td>';
												echo '<td class="percentage hidden">' . $value['percentage'] . '</td>';
												echo '<td class="edu_file hidden">' . $value['edu_file'] . '</td>';
												echo '<td class="hr_status">&nbsp;</td>';
												echo '<td class="hidden ">&nbsp;</td>';
												echo '<td class="manage_item" >&nbsp;';

												/*<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return Edit(this);" data="'.$value['edu_id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i>
								
								
								<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return Delete(this);" data-file="'.$value['edu_file'].'" id="'.$value['edu_id'].'" data-position="left" data-tooltip="Delete">ohrm_delete</i>*/

												/*echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="Education/'.$value['edu_file'].'" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';*/


												echo '</td>';
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


			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {

		$('#txt_eduName').val('NA');
		$('input[type="text"]').click(function() {
			$(this).removeClass('has-error');
		});
		$('select').click(function() {
			$(this).removeClass('has-error');
		});
		$('#btn_education_Save,#btn_education_Add').on('click', function() {

			var validate = 0;
			var alert_msg = '';

			if ($('#txt_edu_type_1').val() == 'NA') {
				$('#txt_edu_type_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				if ($('#stxt_edu_type_1').length == 0) {
					$('<span id="stxt_edu_type_1" class="help-block">Education Type can not be Empty</span>').insertAfter('#txt_edu_type_1');
				}
			}
			if ($('#txt_edu_lvl_1').val() == 'NA') {
				$('#txt_edu_lvl_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				if ($('#stxt_edu_lvl_1').length == 0) {
					$('<span id="stxt_edu_lvl_1" class="help-block">Education Level can not be Empty</span>').insertAfter('#txt_edu_lvl_1');
				}
			}
			if ($('#txt_edu_college_1').val() == 'NA' || $('#txt_edu_college_1').val() == '') {
				validate = 1;
				$('#txt_edu_college_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				if ($('#stxt_edu_college_1').length == 0) {
					$('<span id="stxt_edu_college_1" class="help-block">School / College  can not be Empty</span>').insertAfter('#txt_edu_college_1');
				}
			} else {
				$('#txt_edu_college_1').removeClass('has-error');
				$('#stxt_edu_college_1').html('');
			}
			if ($('#txt_edu_bu_1').val() == 'NA') {
				$('#txt_edu_bu_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				if ($('#stxt_edu_bu_1').length == 0) {
					$('<span id="stxt_edu_bu_1" class="help-block">Board / Univercity can not be Empty</span>').insertAfter('#txt_edu_bu_1');
				}
				validate = 1;
			}
			if ($('#txt_edu_Name_1').val() == '' || $('#txt_edu_Name_1').val() == 'NA') {
				$('#txt_edu_Name_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				if ($('#stxt_edu_Name_1').length == 0) {
					$('<span id="stxt_edu_Name_1" class="help-block">Education Name can not be Empty</span>').insertAfter('#txt_edu_Name_1');
				}
			}
			if ($('#txt_edu_pass_y').val() == 'NA') {
				$('#txt_edu_pass_y').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				if ($('#stxt_edu_pass_y').length == 0) {
					$('<span id="stxt_edu_pass_y" class="help-block">Passing Year can not be Empty</span>').insertAfter('#txt_edu_pass_y');
				}
			}

			if ($('#txt_edu_Spc_1').val() == '' || $('#txt_edu_Spc_1').val() == 'NA') {
				$('#txt_edu_Spc_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				if ($('#stxt_edu_Spc_1').length == 0) {
					$('<span id="stxt_edu_Spc_1" class="help-block">Specilization can not be Empty</span>').insertAfter('#txt_edu_Spc_1');
				}
			}
			var input = parseFloat($('#txt_edu_percentage_1').val());
			if ($('#txt_edu_percentage_1').val() == '' || input > 100 || input < 1 || isNaN(input)) {
				$('#txt_edu_percentage_1').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				if ($('#stxt_edu_percentage_1').length == 0) {
					$('<span id="stxt_edu_percentage_1" class="help-block">Percentage must be between 1 to 100 or can not be Empty</span>').insertAfter('#txt_edu_percentage_1');
				}
			} else {
				$('#txt_edu_percentage_1').removeClass('has-error');
				$('#stxt_edu_percentage_1').html('');
			}
			var fileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
			if ($('#fileselect').val() == "" && $('#txt_edu_file').val() == "") {
				$('#fileid').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				validate = 1;
				$('#fileid').html('Please attach educational document');
				// $(function(){ toastr.error('Please attach educational document'); });

			} else {
				if ($('#txt_edu_file').val() != '') {

					if ($.inArray($('#txt_edu_file').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
						$(function() {
							toastr.error("Image Only formats are allowed : " + fileExtension.join(', '))
						});
						$('#txt_edu_file').focus();
						validate = 1;
						return false;
					} else {
						var file_size = $('#txt_edu_file')[0].files[0].size;
						var calf1 = file_size / 1024;
						if (calf1 >= "2001") {
							$(function() {
								toastr.error("File size is greater than 2MB")
							});
							$('#txt_edu_file').focus();
							return false;
						}
					}
				}
			}

			if (validate == 1) {
				return false;
			}

		});



		function changeevent() {
			$('#txt_edu_lvl_1').change(function() {
				$.ajax({
					url: "../Controller/getEducationName.php?ID=" + $(this).val(),
					success: function(result) {
						$('#txt_edu_Name_1').empty().append(result);
						$('select').formSelect();
						$('#txt_edu_Name_1').val($('#txt_eduName').val());
						$('#txt_eduName').val('NA');

						if ($('#txt_edu_lvl_1').val() == 'Other') {
							$('#txt_edu_othlvl_1').removeClass('hidden');
						} else if ($('#txt_edu_lvl_1').val() == 'NA') {

							$('#txt_edu_othlvl_1').addClass('hidden').val('');
							$('#txt_edu_othrName_1').addClass('hidden').val('');

						}

					}
				});


			});
		}

		function changebind() {
			$('select').change(function() {
				if ($(this).val() == 'Other') {
					$(this).children('*[id^=txt_edu_oth]').removeClass('hidden');
				} else {
					$(this).children('*[id^=txt_edu_oth]').addClass('hidden').val('');
				}
			});
		}
		changebind();
		changeevent();

		$('#txt_edu_pass_y').change(function() {

			$.ajax({
				url: "../Controller/get_Education_details.php?ID=" + $('#EmployeeID').val() + '&type=' + $('#txt_edu_Name_1').val(),
				success: function(result) {
					if ($('#txt_edu_Name_1').val() == '10th' || $('#txt_edu_Name_1').val() == '12th') {

						if (Math.abs($('#txt_edu_pass_y').val() - parseInt(result)) < 2) {
							$('#alert_msg').html('<ul class="text-danger"><li>Please fill correct Passing year in basic study must have a difference of two years  </li></ul>');
							$('#alert_message').show().attr("class", "SlideInRight animated");
							$('#alert_message').delay(5000).fadeOut("slow");
							$('#txt_edu_pass_y').val('NA');
						}

					}

				}
			});

		});

	});

	function Edit(el) {
		var tr = $(el).closest('tr');
		var edu_id = tr.find('.edu_id').text();
		var edu_level = tr.find('.edu_level').text();
		var edu_name = tr.find('.edu_name').text();
		if (edu_level == '12th Pass' || edu_level == 'Pursuing Graduation') {
			edu_level = 'Basic';
			edu_name = '12th';
		}
		var specialization = tr.find('.specialization').text();
		var board = tr.find('.board').text();
		var college = tr.find('.college').text();
		var edu_type = tr.find('.edu_type').text();
		if (edu_type == 'Regular') {
			edu_type = 'Reguler learning';
		}

		var division = tr.find('.division').text();
		var percentage = tr.find('.percentage').text();
		var hr_comment = tr.find('.hr_comment').text();
		if (hr_comment != "") {
			$('#comment_div').removeClass('hidden');
		}
		var edu_file = tr.find('.edu_file').text();
		var passing_year = tr.find('.passing_year').text();
		if (passing_year == '' || passing_year == null)
			passing_year = 'NA';

		$('#txt_edu_pass_y').val(passing_year);
		$('#eduselect').val(edu_id);
		$('#txt_edu_type_1').val(edu_type);
		$('#txt_edu_college_1').val(college);
		$('#txt_edu_bu_1').val(board);
		$('#txt_edu_Spc_1').val(specialization);
		$('#txt_eduName').val($.replace(/^\s+|\s+$/g, (edu_name)));
		$('#txt_Comment').val($.replace(/^\s+|\s+$/g, (hr_comment)));

		$('#txt_edu_lvl_1').val(edu_level).trigger('change');

		$('#txt_edu_percentage_1').val(percentage);
		$('#txt_edu_division_1').val(division);
		$('#fileselect').val(edu_file);
		$('#btn_education_Add').addClass('hidden');
		$('#btn_education_Save').removeClass('hidden');
		$('#btn_education_Can').removeClass('hidden');
	}

	function Download(el) {
		if ($(el).attr("data") != '') {
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

			$.ajax({
				url: "../" + $(el).attr("data"),
				type: 'HEAD',
				error: function() {
					alert('No File Exist');
				},
				success: function() {
					imgcheck = function(filename) {
						return (filename).split('.').pop();
					}
					imgchecker = imgcheck("../" + $(el).attr("data"));

					if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
						getImageDimensions("../" + $(el).attr("data"), function(data) {
							var img = data;

							$('<img>', {
								src: "../" + $(el).attr("data")
							}).watermark({
								//text: 'ⓒ For Cogent E Services Pvt. Ltd.',
								text: 'Cogent E Services Pvt. Ltd.',
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
									link.download = $(el).attr("data");
									document.body.appendChild(link);
									link.click();

								}
							});




						});
					} else if (imgchecker.match(/(pdf)$/i)) {
						window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../" + $(el).attr("data"));
					} else {
						window.open("../" + $(el).attr("data"));
					}

				}
			});

			/*$('.schema-form-section img').watermark({
					    
				  	});*/

		} else {
			alert('No File Exist');
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>