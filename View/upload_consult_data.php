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
		} else if (!($_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE0820912500' || $_SESSION['__user_logid'] == 'MU03200198' || $_SESSION['__user_logid'] == 'CE04159316' || $_SESSION['__user_logid'] == 'CEK07120002' || $_SESSION['__user_logid'] == 'CFK08190181' || $_SESSION['__user_logid'] == 'CMK052277987' || $_SESSION['__user_logid'] == 'CEV102073966' || $_SESSION['__user_logid'] == 'CEK031925550' || $_SESSION['__user_logid'] == 'MU01221218' || $_SESSION['__user_logid'] == 'CMK092279225' || $_SESSION['__user_logid'] == 'CEV072176972' || $_SESSION['__user_logid'] == 'CEB112112076' || $_SESSION['__user_logid'] == 'CEV042382253' || $_SESSION['__user_logid'] == 'CEK052385371' || $_SESSION['__user_logid'] == 'CEK092281591' || $_SESSION['__user_logid'] == 'CEG08230001')) {
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
	<span id="PageTittle_span" class="hidden">Upload Consultancy Data</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Consultancy Data</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">



				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>Browse File</span>
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
					$btnUploadCheck = 1;
					$target_dir = ROOT_PATH . 'Upload/';
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$uploadOk = 1;
					$uploader = $_SESSION['__user_logid'];
					$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

					// Check file size
					if ($_FILES["fileToUpload"]["size"] > 5000000) {
						echo "<script>$(function(){ toastr.error('Sorry, your file is too large " . $_FILES["fileToUpload"]["size"] . " ') }); </script>";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ($FileType != "xlsx") {
						echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.') }); </script>";
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
						// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							echo "<script>$(function(){ toastr.error('The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded') }); </script>";
							$document = PHPExcel_IOFactory::load($target_file);
							// Get the active sheet as an array
							$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

							//print_r($activeSheetData.'<br/>');
							echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "') }); </script>";
							$row_counter = 0;
							$flag = 0;

							$row_counter = 0;
							foreach ($activeSheetData as $row) {

								if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
									$myDB = new MysqliDb();
									//echo 'call insert_consultancy_data("'.$row['A'].'","'.$row['B'].'","'.$row['C'].'","'.$row['D'].'","'.$row['E'].'")' .'<br/>';
									$flag = $myDB->query('call insert_consultancy_data("' . $row['A'] . '","' . $_SESSION["__location"] . '","' . $row['B'] . '","' . $row['C'] . '","' . $row['D'] . '","' . $uploader . '")');

									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										$count++;
									}
								}
								$row_counter++;
							}

							if ($count > 0)
								echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.') }); </script>";
							else
								echo "<script>$(function(){ toastr.error('No Data Updated " . $mysql_error . " ') }); </script>";

							if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
								$ext = pathinfo($target_file, PATHINFO_EXTENSION);
								rename($target_file, $target_dir . time() . '_' . $uploader . "_Roster_" . $_POST['txt_Type_Upload'] . "." . $ext);
							}
						} else {
							echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
						}
					}
				}

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

		$('#UploadBtn').click(function() {
			var validate = 0;
			var alert_msg = '';
			$('#txt_Type_Upload').closest('div').removeClass('has-error');


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