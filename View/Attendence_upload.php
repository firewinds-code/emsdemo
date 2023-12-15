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
$msgFile = '';
$MSG = "";
$insert_row = $btnUploadCheck = 0;

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	} else if ($_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE12102224') {
		// proceed further
	} else {
		$location = URL;
		echo "<script>location.href='" . $location . "'</script>";
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Attendence</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Attendence<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../FileContainer/upload_format_Attendence.xlsx" data-position="bottom" data-tooltip="Download Formate"><i class="material-icons">file_download</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="file-field input-field col s12 m12">
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

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">

					<?php
					if (isset($_POST['UploadBtn'])) {
						$btnUploadCheck = 1;
						$target_dir = ROOT_PATH . 'UploadManualatnd/';
						$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
						$uploadOk = 1;
						$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

						$date1_ = new DateTime();
						$date2 = $date1_->format('YmdHis');
						$str = 'Attendence';
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
								//echo "<script>$(function(){ toastr.info('Rows available In Sheet : ".(count($activeSheetData)-1)."'); }); </script>";
								$row_counter = 0;
								foreach ($activeSheetData as $row) {
									$d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d31 = '';
									if ($row_counter > 0 && !empty($row['A']) && $row['A'] != ''  && !empty($row['B']) && $row['B'] != '' && !empty($row['C']) && $row['C'] != '' && !empty($row['D']) && $row['D'] != ''  && !empty($row['E']) && $row['E'] != '') {
										$sql_p_inert = "select EmployeeID from calc_atnd_master where EmployeeID='" . $row['A'] . "' and Month='" . $row['D'] . "' and Year='" . $row['E'] . "' ";

										$myDB = new MysqliDb();
										$rst = $myDB->rawQuery($sql_p_inert);
										$mysqlerror = $myDB->getLastError();

										if ($myDB->count > 0) {

											$sql_p_inert = "update calc_atnd_master set " . $row['B'] . "='" . $row['C'] . "',createdby='Uploded',modifiedon=now() where EmployeeID='" . $row['A'] . "' and Month='" . $row['D'] . "' and Year='" . $row['E'] . "' ";

											$myDB = new MysqliDb();
											$rst = $myDB->rawQuery($sql_p_inert);
											$mysqlerror = $myDB->getLastError();
										} else {


											//echo $sql_p_inert;
											if ($row['B'] == "D1") {
												$d1 = $row['C'];
											} elseif ($row['B'] == "D2") {
												$d2 = $row['C'];
											} elseif ($row['B'] == "D3") {
												$d3 = $row['C'];
											} elseif ($row['B'] == "D4") {
												$d4 = $row['C'];
											} elseif ($row['B'] == "D5") {
												$d5 = $row['C'];
											} elseif ($row['B'] == "D6") {
												$d6 = $row['C'];
											} elseif ($row['B'] == "D7") {
												$d7 = $row['C'];
											} elseif ($row['B'] == "D8") {
												$d8 = $row['C'];
											} elseif ($row['B'] == "D9") {
												$d9 = $row['C'];
											} elseif ($row['B'] == "D10") {
												$d10 = $row['C'];
											} elseif ($row['B'] == "D11") {
												$d11 = $row['C'];
											} elseif ($row['B'] == "D12") {
												$d12 = $row['C'];
											} elseif ($row['B'] == "D13") {
												$d13 = $row['C'];
											} elseif ($row['B'] == "D14") {
												$d14 = $row['C'];
											} elseif ($row['B'] == "D15") {
												$d15 = $row['C'];
											} elseif ($row['B'] == "D16") {
												$d16 = $row['C'];
											} elseif ($row['B'] == "D17") {
												$d17 = $row['C'];
											} elseif ($row['B'] == "D18") {
												$d18 = $row['C'];
											} elseif ($row['B'] == "D19") {
												$d19 = $row['C'];
											} elseif ($row['B'] == "D20") {
												$d20 = $row['C'];
											} elseif ($row['B'] == "D21") {
												$d21 = $row['C'];
											} elseif ($row['B'] == "D22") {
												$d22 = $row['C'];
											} elseif ($row['B'] == "D23") {
												$d23 = $row['C'];
											} elseif ($row['B'] == "D24") {
												$d24 = $row['C'];
											} elseif ($row['B'] == "D25") {
												$d25 = $row['C'];
											} elseif ($row['B'] == "D26") {
												$d26 = $row['C'];
											} elseif ($row['B'] == "D27") {
												$d27 = $row['C'];
											} elseif ($row['B'] == "D28") {
												$d28 = $row['C'];
											} elseif ($row['B'] == "D29") {
												$d29 = $row['C'];
											} elseif ($row['B'] == "D30") {
												$d30 = $row['C'];
											} elseif ($row['B'] == "D31") {
												$d31 = $row['C'];
											}

											$sql = "Insert into calc_atnd_master(EmployeeID, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31, Month, Year, createdon, createdby) values ('" . $row['A'] . "', '" . $d1 . "', '" . $d2 . "', '" . $d3 . "', '" . $d4 . "', '" . $d5 . "', '" . $d6 . "', '" . $d7 . "', '" . $d8 . "', '" . $d9 . "', '" . $d10 . "', '" . $d11 . "', '" . $d12 . "', '" . $d13 . "', '" . $d14 . "', '" . $d15 . "', '" . $d16 . "', '" . $d17 . "', '" . $d18 . "', '" . $d19 . "', '" . $d20 . "', '" . $d21 . "', '" . $d22 . "', '" . $d23 . "', '" . $d24 . "', '" . $d25 . "', '" . $d26 . "', '" . $d27 . "', '" . $d28 . "', '" . $d29 . "', '" . $d30 . "', '" . $d31 . "', '" . $row['D'] . "', '" . $row['E'] . "', now(), 'Uploded')";
											$myDB = new MysqliDb();
											$rst = $myDB->rawQuery($sql);
											$mysqlerror = $myDB->getLastError();
										}

										if (count($rst) > 0) {
											echo "<script>$(function(){ toastr.error('Error In Query " . $mysqlerror . "'); }); </script>";
										}
										if ($myDB->count > 0)
											$insert_row = $insert_row + $myDB->count;
									}
									$row_counter++;
								}
								// die;
								$MSG = " toastr.success('" . $insert_row . " Records Uploaded Sucessfully!');";
								//$MSG="<script>$(function(){ toastr.success('No of Row Uploaded - ".$insert_row." has been uploaded'); }); </script>";
							} else {
								$MSG = "toastr.error('Sorry, there was an error uploading your file.');";
								//echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
							}
						}
					}
					echo "<script>$(function(){  $MSG }); </script>";

					?>
				</div>
				<!--Reprot / Data Table End -->

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php
include(ROOT_PATH . 'AppCode/footer.mpt');

?>