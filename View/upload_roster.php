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
		} else if (!($_SESSION["__ut_temp_check"] == 'COMPLIANCE' || $_SESSION["__user_type"] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224')) {
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
	<span id="PageTittle_span" class="hidden">Upload Roster</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Roster<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="#myModal_content" data-position="bottom" data-tooltip="Download Formate"><i class="material-icons">file_download</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s6 m6">
					<select id="txt_Type_Upload" name="txt_Type_Upload">
						<?php

						$date = date("l", time());
						$date = strtolower($date);
						if ($date == "saturday" || $date == "sunday" || $date == "friday") {
							echo  "<option>Next Week</option><option>Current Week</option>";
						} else {
							echo "<option>Current Week</option>";
						}
						if ($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE01145570') {
							echo '<option>Back Date Roster</option>';
						}
						?>
					</select>
					<label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
				</div>

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
					<input type="button" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green hidden" />
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
							if ($_POST['txt_Type_Upload'] == 'Next Week') {
								$dt_First = date('Y-m-d', strtotime('next monday'));
								$dt_Last = date('Y-m-d', strtotime('next monday +6 days'));
								$row_counter = 0;
								foreach ($activeSheetData as $row) {

									if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

										$begin = new DateTime($dt_First);
										$end   = new DateTime($dt_Last);
										$weekOFF = 0;
										$j = 1;
										$jj = 1;
										$daycount = 1;
										$date1 = date('Y-m-d');
										for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
											$col = "D" . intval($i->format('d'));
											$val = trim($row[coordinates($jj)]);
											//$dateT_ins = $i->format('Y').'-'.intval($i->format('m')).'-'.intval($i->format('d'));
											$dateT_ins = $i->format('Y-m-d');
											$Time_inout = explode('-', $val);
											$work_type = '';
											if ($row['L'] != 'WFOB' && $row['L'] != 'WFO' && $row['L'] != 'WFH') {
												$work_type = 'WFOB';
											} else {
												$work_type = $row['L'];
											}

											$myDB = new MysqliDb();
											$flag = $myDB->query('call insert_roster_tmp("' . strtoupper($row['A']) . '","' . $Time_inout[0] . '","' . $Time_inout[1] . '","' . $dateT_ins . '","' . $row['K'] . '","' . $work_type . '")');
											$mysql_error = $myDB->getLastError();
											if ($flag != 0) {
												$count++;
											}
											$j++;
											$jj++;
										}
									}
									$row_counter++;
								}
							} else if ($_POST['txt_Type_Upload'] == "Current Week") {
								$dt_First = date("Y-m-d", strtotime('today'));
								$dt_Last = date("Y-m-d", strtotime('today'));
								if (strtolower(date('l', time())) == 'monday') {
									$dt_First = date("Y-m-d", strtotime('today'));
									$dt_Last = date("Y-m-d", strtotime('next sunday'));
								} elseif (strtolower(date('l', time())) == 'sunday') {
									$dt_First = date("Y-m-d", strtotime('previous monday'));
									$dt_Last = date("Y-m-d", strtotime('today'));
								} else {
									$dt_First = date("Y-m-d", strtotime('previous monday'));
									$dt_Last = date("Y-m-d", strtotime('next sunday'));
								}
								$row_counter = 0;
								foreach ($activeSheetData as $row) {

									if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

										$begin = new DateTime($dt_First);
										$end   = new DateTime($dt_Last);
										$weekOFF = 0;
										$j = 1;
										$jj = ($begin->format('w') == 0) ? 7 : intval($begin->format('w'));
										$daycount = 1;

										for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
											$col = "D" . intval($i->format('d'));
											$val = trim($row[coordinates($jj)]);
											// $dateT_ins = $i->format('Y').'-'.intval($i->format('m')).'-'.intval($i->format('d'));
											$dateT_ins = $i->format('Y-m-d');
											$Time_inout = explode('-', $val);
											$time_inout1 = '';
											$time_inout2 = '';
											if (isset($Time_inout[0])) {
												$time_inout1 = $Time_inout[0];
											}
											if (isset($Time_inout[1])) {
												$time_inout2 = $Time_inout[1];
											}
											if ($time_inout1 != '' &&  $time_inout2 != '') {
												if ($row['L'] != 'WFOB' && $row['L'] != 'WFO' && $row['L'] != 'WFH') {
													$work_type = 'WFOB';
												} else {
													$work_type = $row['L'];
												}
												// echo 'call insert_roster_tmp("' . strtoupper($row['A']) . '","' . $time_inout1 . '","' . $time_inout2 . '","' . $dateT_ins . '","' . $row['K'] . '","' . $work_type . '")' . '<br/>';
												$myDB = new MysqliDb();
												$flag = $myDB->query('call insert_roster_tmp("' . strtoupper($row['A']) . '","' . $time_inout1 . '","' . $time_inout2 . '","' . $dateT_ins . '","' . $row['K'] . '","' . $work_type . '")');
												$mysql_error = $myDB->getLastError();
											}

											if ($flag != 0) {
												$count++;

												$date1 = date('Y-m-d');
												$date2 = $dateT_ins;
												if (strtotime($date1) > strtotime($date2)) {
													$ds_APR = $myDB->query('select t1.EmployeeID, designation, windowstart,windowend,t3.work_from,des_id from whole_dump_emp_data t1 inner join process_window t2 on t1.cm_id=t2.cm_id inner join roster_temp t3 on t1.EmployeeID=t3.EmployeeID
	where t1.EmployeeID= "' . strtoupper($row['A']) . '" and t3.DateOn= "' . $date2 . '" ');

													if (count($ds_APR) > 0 && $ds_APR) {
														if (($ds_APR[0]['des_id'] == '9' || $ds_APR[0]['des_id'] == '12' || $ds_APR[0]['des_id'] == '33' || $ds_APR[0]['des_id'] == '34' || $ds_APR[0]['des_id'] == '35' || $ds_APR[0]['des_id'] == '36') && $ds_APR[0]['work_from'] != '') {
															$url = 'http://10.147.20.14/apr_processing/user_apr.php?apr_date=' . $date2 . '&username=' . strtoupper($row['A']) . '&intime=' . $time_inout1 . '&outtime=' . $time_inout2 . '&WorkFrom=' . $ds_APR[0]['work_from'] . '&windowstart=' . $ds_APR[0]['windowstart'] . '&windowend=' . $ds_APR[0]['windowend'];
															//echo $url;
															$curl = curl_init();
															curl_setopt($curl, CURLOPT_URL, $url);
															curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
															curl_setopt($curl, CURLOPT_HEADER, false);
															$data = curl_exec($curl);
															curl_close($curl);
														}
													}
												}
											}
											$j++;
											$jj++;
										}
									}
									$row_counter++;
								}
							} else if ($_POST['txt_Type_Upload'] == "Back Date Roster") {
								$row_counter = 0;
								foreach ($activeSheetData as $row) {
									if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
										$EmployeeID = trim($row['A']);
										$DateOn = trim($row['B']);
										$InTime  = trim($row['C']);
										$OutTime = trim($row['D']);
										$Type = trim($row['E']);
										$work_from = trim($row['F']);
										if ($work_from != 'WFOB' && $work_from != 'WFO' && $work_from != 'WFH') {
											$work_from = 'WFOB';
										}

										$myDB = new MysqliDb();

										$rst_insert_bkd = $myDB->query('call  sp_insert_roster_backdate("' . $EmployeeID . '","' . $DateOn . '","' . $InTime . '","' . $OutTime . '","' . $Type . '","' . $work_from . '")');

										if ($rst_insert_bkd != 0) {
											$count++;

											$date1 = date('Y-m-d');
											$date2 = $DateOn;
											if (strtotime($date1) > strtotime($date2)) {
												$ds_APR = $myDB->query('select t1.EmployeeID, designation, windowstart,windowend,t3.work_from,des_id from whole_dump_emp_data t1 inner join process_window t2 on t1.cm_id=t2.cm_id inner join roster_temp t3 on t1.EmployeeID=t3.EmployeeID
where t1.EmployeeID= "' . $EmployeeID . '" and t3.DateOn= "' . $date2 . '" ');

												if (count($ds_APR) > 0 && $ds_APR) {
													if (($ds_APR[0]['des_id'] == '9' || $ds_APR[0]['des_id'] == '12' || $ds_APR[0]['des_id'] == '33' || $ds_APR[0]['des_id'] == '34' || $ds_APR[0]['des_id'] == '35' || $ds_APR[0]['des_id'] == '36') && $ds_APR[0]['work_from'] != '') {
														$url = 'http://10.147.20.14/apr_processing/user_apr.php?apr_date=' . $date2 . '&username=' . $EmployeeID . '&intime=' . $InTime . '&outtime=' . $OutTime . '&WorkFrom=' . $ds_APR[0]['work_from'] . '&windowstart=' . $ds_APR[0]['windowstart'] . '&windowend=' . $ds_APR[0]['windowend'];
														//echo $url;
														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);
														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($curl, CURLOPT_HEADER, false);
														$data = curl_exec($curl);
														curl_close($curl);
													}
												}
											}
										}
									}
									$row_counter++;
								}
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