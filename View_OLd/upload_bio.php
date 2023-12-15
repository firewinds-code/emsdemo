<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
//Upload Excel
require_once(LIB . 'PHPExcel/IOFactory.php');
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$msgFile = '';
$insert_row = $btnUploadCheck = 0;
function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}
$UploadBtn = isset($_POST['UploadBtn']);
if ($UploadBtn) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'Upload/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);


		// Check if file already exists
		/*if (file_exists($target_file)) {
					    $msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
					    $uploadOk = 0;
						}*/
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
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
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "<script>$(function(){ toastr.success('The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded'); }); </script>";
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
				//var_dump($activeSheetData);

				echo "<script>$(function(){ toastr.success('Rows available In Sheet : " . (count($activeSheetData) - 1) . "'); }); </script>";
				$row_counter = 0;
				if (isset($_POST['txt_Type_Upload'])) {

					foreach ($activeSheetData as $row) {
						if (!empty($row['A']) && $row['A'] != '') {
							//$date_month = date_parse($row['AG']);
							$sql_p_inert = 'CALL insert_bio("' . strtoupper($row['A']) . '","' . $row['B'] . '","' . $row['C'] . '");';
							//$sql_p_inert='insert into biopunchcurrentdata (EmpID, Dateon, PunchTime) values ("'.strtoupper($row['A']).'","'.$row['B'].'","'.$row['C'].'")';
							$myDB = new MysqliDb();
							$rst = $myDB->rawQuery($sql_p_inert);
							$mysqlerror = $myDB->getLastError();
							if (!empty($mysqlerror)) {
								echo "<script>$(function(){ toastr.error('Error In Query " . $mysqlerror . "'); }); </script>";
							}
							if ($myDB->count > 0) {
								$insert_row++;
							}

							// APR in new table for revenue report purpuse
							/*
											if($myDB->count > 0)
											{
												$insert_row = $insert_row + $myDB->count;
												$date_month = date_parse($row['AG']);
												$date_month = intval($date_month['month']);
												$date_year = intval($row['AH']);
												$st_date = date("Y-m-01",strtotime($date_year.'-'.$date_month.'-1'));
												$ed_date = date("Y-m-t",strtotime($date_year.'-'.$date_month.'-1'));
												$start_date  = new DateTime($st_date);
            									$end_date  = new DateTime($ed_date);
            									$jj = 0;
            									for($i_date = $start_date; $start_date <= $end_date; $i_date->modify('+1 day'))
           										{
           											$date__ = $i_date->format('Y-m-d');
           											$jj++;
           											
									            	if(!empty($date__) && $date__ != '1970-01-01')
									            	{
									            		$val =trim($row[coordinates($jj)]);
									            		$insert_apr_l = "call `manage_emp_apr`('".$row['A']."','".$date__."','".$val."','APR');";
									            		$myDB = new MysqliDb();
									            		$flag_apr = $myDB->rawQuery($insert_apr_l);
									            		echo $myDB->getLastError();
									            		
													}
												}	
											}
								 			*/
							//end of apr in new table

						}
					}
					echo "<script>$(function(){ toastr.success('No. of Row Uploaded :: " . $insert_row . "'); }); </script>";
					if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
						$ext = pathinfo($target_file, PATHINFO_EXTENSION);
						rename($target_file, $target_dir . time() . "BIO." . $ext);
					}
				} else {
					echo "<script>$(function(){ toastr.error('Please Select Upload Type'); }); </script>";
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
	<span id="PageTittle_span" class="hidden">Upload Biometric</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Biometric
				<!--<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="#myModal_content" data-position="bottom" data-tooltip="Download Formate"><i class="material-icons">file_download</i></a>-->
			</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5">
					<select class="form-control" id="txt_Type_Upload" name="txt_Type_Upload">
						<option value="Hours">Biometric</option>
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
				<!--Form element model popup End-->

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
				$('#txt_Type_Upload').val('NA');
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