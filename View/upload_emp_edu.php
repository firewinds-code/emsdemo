<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		if ($_SESSION['__user_logid'] == '' || $_SESSION['__user_logid'] == null) {
			echo '<a href="' . URL . 'Login" >Go To Login </a>';
			exit();
		} else if (!($_SESSION["__ut_temp_check"] == 'COMPLIANCE' || $_SESSION["__user_type"] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE03146043')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$msgFile = '';
$insert_row = 0;
$btnUploadCheck = 0;
$count = 0;
$mysql_error = '';
function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Data</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Data </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">


				<div class="file-field input-field col s6 m6">
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
					<input type="submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />

				</div>


				<?php
				if (isset($_POST['UploadBtn'])) {
					//echo $_SESSION["token"];
					//echo $_POST["token"];

					//if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
					$btnUploadCheck = 1;
					//echo 'there';
					$target_dir = ROOT_PATH . 'Upload/';
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$uploadOk = 1;
					$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

					$date1_ = new DateTime();
					$date2 = $date1_->format('YmdHis');
					$str = 'Emp_edu';
					$string = str_replace(' ', '', $str);
					$FileName = $string . '_' . $date2 . '.' . $FileType;
					$target_file = $target_dir . basename($FileName);

					// Check if file already exists
					/*if (file_exists($target_file)) {
													$msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
														$uploadOk = 0;
													}*/
					// Check file size
					if ($_FILES["fileToUpload"]["size"] > 5000000) {
						echo "<script>$(function(){ toastr.info('Sorry, your file is too large.'); }); </script>";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ($FileType != "xlsx") {
						echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.'); }); </script>";
						$uploadOk = 0;
					}

					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						echo "<script>$(function(){ toastr.info('Sorry, your file was not uploaded.'); }); </script>";
						// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

							//echo "<script>$(function(){ toastr.success('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded'); }); </script>";
							$document = PHPExcel_IOFactory::load($target_file);

							// Get the active sheet as an array
							$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
							//var_dump($activeSheetData);
							echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "'); }); </script>";
							$row_counter = 0;
							foreach ($activeSheetData as $row) {

								if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
									$myDB = new MysqliDb();
									$flag = $myDB->query('insert into emp_edu (EmpID,createdby) value ("' . strtoupper($row['A']) . '","' . clean($_SESSION['__user_logid']) . '")');
									$mysql_error = $myDB->getLastError();
									//print_r($flag);
									if (empty($mysql_error)) {
										$count++;
									}
								}
								$row_counter++;
							}
							// die;
							$MSG = " toastr.success('" . $count . " Records Uploaded Sucessfully!');";
							//$MSG="<script>$(function(){ toastr.success('No of Row Uploaded - ".$insert_row." has been uploaded'); }); </script>";
						} else {
							$MSG = "toastr.error('Sorry, there was an error uploading your file.');";
							//echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
						}
					}
					//}
				}
				echo "<script>$(function(){  $MSG }); </script>";
				?>
				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(function() {
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
				alert_msg += '<li> First Select Upload For  </li>';
			}
			if ($('#fileToUpload').val() == '') {
				validate = 1;
				alert_msg += '<li> First Choose File  </li>';
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(10000).fadeOut("slow");
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