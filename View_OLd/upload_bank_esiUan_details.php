<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
//Upload Excel
require_once(LIB . 'PHPExcel/IOFactory.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
	$clean_u_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_u_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		if ($clean_u_logid == '' || $clean_u_logid == null) {
			echo '<a href="' . URL . 'Login" >Go To Login </a>';
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$msgFile = $mysql_error = '';
$insert_row = $btnUploadCheck = $count = 0;
function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}
if (isset($_POST['UploadBtn'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		//ALTER TABLE `ems`.`bank_details`
		//ADD COLUMN `IFSC_code` VARCHAR(45) NULL AFTER `Active`,
		//ADD COLUMN `name_asper_bank` VARCHAR(45) NULL AFTER `IFSC_code`;


		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'Upload/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$clean_u_logid = clean($_SESSION['__user_logid']);
		$uploader = $clean_u_logid;
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);


		// Check if file already exists
		/*if (file_exists($target_file)) {
		$msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
		$uploadOk = 0;
	}*/
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large" . $_FILES["fileToUpload"]["size"] . " '); }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($FileType != "xlsx") {
			echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.'); }); </script>";
			// if everything is ok, try to upload file
		} else {
			$date = date('Y=m-d h:i:s');
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "<script>$(function(){ toastr.success('The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded'); }); </script>";
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

				//print_r($activeSheetData.'<br/>');
				echo "<script>$(function(){ toastr.success('Rows available In Sheet : " . (count($activeSheetData) - 1) . "'); }); </script>";
				$row_counter = 0;
				$flag = 0;
				$row_counter = 0;
				$count = 0;
				$update_Query = 0;
				$Insert_query = 0;
				$clean_type_upload = cleanUserInput($_POST['txt_Type_Upload']);
				if (count($activeSheetData) > 0 && $activeSheetData && $clean_type_upload == 'Bank Details') {
					foreach ($activeSheetData as $row) {
						if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

							$EmployeeID = clean($row['A']);
							$account_num = clean($row['B']);
							//echo "<br>";
							$bank_Branch_name  = clean($row['C']);
							$bank_name  = clean($row['D']);
							$name_in_bank = clean($row['E']);
							$ifsc_code = clean($row['F']);


							$selectBank = "select bank_id from bank_details where EmployeeID=?";
							$selectQury = $connn->prepare($selectBank);
							$selectQury->bind_param("s", $EmployeeID);
							$selectQury->execute();
							$select_query = $selectQury->get_result();
							$clean_u_logid = clean($_SESSION['__user_logid']);
							if ($select_query->num_rows > 0) {
								//	echo "Update bank_details set BankName='".$bank_name."',AccountNo='".$account_num."',Active='Active',IFSC_code='".$ifsc_code."',name_asper_bank='".$name_in_bank."',Branch='".$bank_Branch_name ."' ,Location='Noida' ,modifiedby='".$clean_u_logid."', modifiedon='".$date."'  where EmployeeID='".$EmployeeID."' ";
								$query = "Update bank_details set BankName=?,AccountNo=?,Active='Active',IFSC_code=?,name_asper_bank=?,Branch=? ,Location='Noida' ,modifiedby=?, modifiedon=?  where EmployeeID=? ";
								$selectQuery = $conn->prepare($query);

								$selectQuery->bind_param("sissssss", $bank_name, $account_num, $ifsc_code, $name_in_bank, $bank_Branch_name, $clean_u_logid, $message_responce, $date, $EmployeeID);
								$selectQuery->execute();
								$update_Query = $selectQuery->get_result();

								if ($selectQuery->affected_rows === 1) {
									//if ($update_Query != 0) {
									$count++;
								}
							} else {
								$Query = " INSERT into bank_details set EmployeeID='" . $EmployeeID . "', BankName='" . $bank_name . "',AccountNo='" . $account_num . "',Active='Active',IFSC_code='" . $ifsc_code . "',name_asper_bank='" . $name_in_bank . "',Branch='" . $bank_Branch_name . "' ,Location='Noida',createdon='" . $date . "', createdby='" . $clean_u_logid . "' ";
								$selectQu = $conn->prepare($Query);
								$selectQu->bind_param("ssisssss", $EmployeeID, $bank_name, $account_num, $ifsc_code, $name_in_bank, $bank_Branch_name, $date, $clean_u_logid);
								$selectQu->execute();
								$Insert_query = $selectQu->get_result();

								//if ($Insert_query != 0) {
								if ($selectQu->affected_rows === 1) {
									$count++;
								}
							}
						}
						$row_counter++;
					}
				}
				$clean_type_upload = cleanUserInput($_POST['txt_Type_Upload']);
				if (count($activeSheetData) > 0 && $activeSheetData && $clean_type_upload == 'UAN Number') {
					foreach ($activeSheetData as $row) {
						if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

							$EmployeeID = clean($row['A']);
							$uan_num = clean($row['B']);


							//echo "select sal_id from salary_details where EmployeeID='".$EmployeeID."' and uan_no='".$uan_num."'";
							$select = "select sal_id from salary_details where EmployeeID=? and uan_no=?";
							$selectQury = $conn->prepare($select);
							$selectQury->bind_param("ss", $EmployeeID, $uan_num);
							$selectQury->execute();
							$select_query = $selectQury->get_result();
							if ($select_query->num_rows < 1) {
								//	echo "Update salary_details set uan_no='".$uan_num."' ,modifiedby='".$clean_u_logid."', modifiedon='".$date."'  where EmployeeID='".$EmployeeID."' ";
								$Querys = "Update salary_details set uan_no=? ,modifiedby=?, modifiedon=?  where EmployeeID=?";
								$selectQuery = $conn->prepare($Querys);
								$clean_u_logid = clean($_SESSION['__user_logid']);
								$selectQuery->bind_param("ssss", $uan_num, $clean_u_logid, $date, $EmployeeID);
								$selectQuery->execute();
								$update_Query = $selectQuery->get_result();

								if ($selectQuery->affected_rows === 1) {
									// if ($update_Query != 0) {
									$count++;
								}
							}
						}
						$row_counter++;
					}
				}
				$clean_type_upload = cleanUserInput($_POST['txt_Type_Upload']);
				if (count($activeSheetData) > 0 && $activeSheetData && $clean_type_upload == 'ESI Number') {
					foreach ($activeSheetData as $row) {
						if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

							$EmployeeID = clean($row['A']);
							$ESI_num = clean($row['B']);
							$query = "select sal_id from salary_details where EmployeeID=? and esi_no=?";
							$selectQ = $conn->prepare($query);
							$selectQ->bind_param("ss", $EmployeeID, $ESI_num);
							$selectQ->execute();
							$select_query = $selectQ->get_result();
							if ($select_query->num_rows < 1) {
								$Query = "Update salary_details set esi_no=? ,modifiedby=?, modifiedon=?  where EmployeeID=?";
								$selectQuery = $conn->prepare($Query);
								$clean_u_logid = clean($_SESSION['__user_logid']);
								$selectQuery->bind_param("ssss", $ESI_num, $clean_u_logid, $date, $EmployeeID);
								$selectQuery->execute();
								$update_Query = $selectQuery->get_result();
								if ($selectQuery->affected_rows === 1) {
									// if ($update_Query != 0) {
									$count++;
								}
							}
						}
						$row_counter++;
					}
				}
				if ($count > 0) {
					echo "<script>$(function(){ toastr.success('Total " . $count . "  Records execued Sucessfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('No Data Updated'); }); </script>";
				}
				if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
					$ext = pathinfo($target_file, PATHINFO_EXTENSION);
					$clean_type_upload = cleanUserInput($_POST['txt_Type_Upload']);
					rename($target_file, $target_dir . time() . '_' . $uploader . "_Quality_" . $clean_type_upload . "." . $ext);
				}
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file'); }); </script>";
			}
		}
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Bank Detail/ UAN/ ESI Number</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<!-- Header for Form If any -->
			<h4>Upload Bank Detail/ UAN/ ESI Number <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../FileContainer/upload_format_bank_detail.xlsx" data-position="bottom" data-tooltip="Bank Detail Formate">
					<i class="material-icons">file_download</i></a>
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../FileContainer/upload_format_uan_detail.xlsx" data-position="bottom" data-tooltip="UAN Number Formate">
					<i class="material-icons">file_download</i></a>
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../FileContainer/upload_format_ESI_detail.xlsx" data-position="bottom" data-tooltip="ESI Number Formate">
					<i class="material-icons">file_download</i></a>
			</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5">
					<select class="form-control" id="txt_Type_Upload" name="txt_Type_Upload">
						<option>Bank Details</option>
						<option>UAN Number</option>
						<option>ESI Number</option>
					</select>
					<label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
				</div>

				<div class="file-field input-field col s5 m5">
					<div class="btn">
						<span>Upload File</span>
						<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
						<br>
						<span class="file-size-text">Accepts up to 2MB</span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" style="">
					</div>
				</div>

				<div class="input-field col s12 m12 right-align">
					<input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
					<input type="button" value="Upload Again" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green hidden" />
				</div>

				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>

	<script>
		$(function() {
			$('#alert_msg_close').click(function() {
				$('#alert_message').hide();
			});
			if ($('#alert_msg').text() == '') {
				$('#alert_message').hide();
			} else {
				$('#alert_message').delay(10000).fadeOut("slow");
			}

			$('#UploadAgain').click(function() {
				$('.pannel_upload').removeClass('hidden');
				$('#UploadAgain').addClass('hidden');
				$('#txt_Type_Upload').val('Current Week');
			});
			$('#UploadBtn').click(function() {
				var validate = 0;
				var alert_msg = '';
				$('#txt_Type_Upload').closest('div').removeClass('has-error');

				if ($('#txt_Type_Upload').val() == 'NA') {
					$('#txt_Type_Upload').closest('div').addClass('has-error');
					validate = 1;
					alert_msg = 'First Select Upload For ';
				}
				if ($('#fileToUpload').val() == '') {
					validate = 1;
					alert_msg = ' Please select/ choose File ';
				}

				if (validate == 1) {
					$(function() {
						toastr.error(alert_msg);
					});

					return false;
				} else {
					$('#UploadBtn').hide();
					$('#alert_msg').html('<ul class="text-warning"> Wait ! Data Uploading In Process Please Do not Skip or shut down the page ...</ul>');
					$('#alert_message').show();
				}

			});
			<?php

			if ($btnUploadCheck > 0) {
			?>
				$('.pannel_upload').addClass('hidden');
				$('#UploadAgain').removeClass('hidden');
			<?php
			}

			?>

		});
	</script>
	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>