<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
include_once("../Services/sendsms_API1.php");
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid']) && ($_SESSION['__user_logid'] != 'CE03070003' && $_SESSION['__user_logid'] != 'CE09134997' && $_SESSION['__user_logid'] != 'CE10091236')) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
		exit;
	} else {
	}
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	exit;
}

$msgFile = '';
$insert_row = $btnUploadCheck = 0;
?>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload ESIC Card</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload ESIC Card</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="panel panel-default" style="margin-top: 10px;">
					<div class="panel-body">
						<div class="form-inline">
							<div class="form-inline">

								<!--<div class="input-field col s12 m12">
					            <input type='text' id="EmployeeID" name="EmployeeID" maxlength="20">
					            	
				         	    <label for="EmployeeID" class="active-drop-down active">Employee ID</label>
				      </div>-->

								<div class="file-field input-field col s12 m12">
									<div class="btn">
										<span>Upload File</span>
										<input type="file" id="esicard" multiple="multiple" name="esicard[]" style="text-indent: -99999em;">
										<br>
										<span class="file-size-text">Accepts up to 2MB</span>
									</div>
									<div class="file-path-wrapper">
										<input class="file-path" type="text" style="">
									</div>
								</div>

								<div class="input-field col s12 m12 right-align">
									<input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />

								</div>
							</div>
						</div>


						<?php
						$EmployeeID = '';
						$loc = '';
						$target_dir = $EmployeeName = $mobile = '';
						if (isset($_POST['UploadBtn'])) {
							$btnUploadCheck = 1;

							$totalfile = count($_FILES["esicard"]["name"]);
							for ($i = 0; $i < $totalfile; $i++) {
								$filename = $_FILES["esicard"]["name"][$i];
								$filesize = $_FILES["esicard"]["size"][$i];
								$farray = explode('.', $filename);
								$EmployeeID = $farray[0];
								$filetype = $farray[1];
								$filetemp = $_FILES["esicard"]["tmp_name"][$i];


								$myDB = new MysqliDb();
								//$EmployeeID=$_POST['EmployeeID'];
								//$sql='select location from personal_details where EmployeeID = "'.$EmployeeID.'"';
								$sql = 'select p.location,p.EmployeeName,c.mobile from personal_details p  inner join  contact_details c  on p.EmployeeID=c.EmployeeID where p.EmployeeID = "' . $EmployeeID . '"';
								$result = $myDB->rawQuery($sql);
								$mysql_error = $myDB->getLastError();
								if (count($result) > 0) {
									$loc = $result[0]['location'];
									$EmployeeName = $result[0]['EmployeeName'];
									$mobile = $result[0]['mobile'];
								}
								if ($loc == "1" || $loc == "2") {
									$target_dir = ROOT_PATH . "esicard/";
								}
								if ($loc == "3") {

									$target_dir = ROOT_PATH . "Meerut/esicard/";
								} else if ($loc == "4") {

									$target_dir = ROOT_PATH . "Bareilly/esicard/";
								} else if ($loc == "5") {
									$target_dir = ROOT_PATH . "Vadodara/esicard/";
								} else if ($loc == "6") {
									$target_dir = ROOT_PATH . "Manglore/esicard/";
								} else if ($loc == "7") {
									$target_dir = ROOT_PATH . "Bangalore/esicard/";
								} else if ($loc == "8") {
									$target_dir = ROOT_PATH . "Nashik/esicard/";
								} else if ($loc == "9") {
									$target_dir = ROOT_PATH . "Anantapur/esicard/";
								} else if ($loc == "10") {
									$target_dir = ROOT_PATH . "Gurgaon/esicard/";
								} else if ($loc == "11") {
									$target_dir = ROOT_PATH . "Hyderabad/esicard/";
								}

								if (!is_dir($target_dir)) {
									@mkdir($target_dir, 0777, true);
								}

								if ($loc != "") {


									//$target_file = $target_dir . basename($_FILES["esicard"]["name"]);
									$target_file = $target_dir . basename($filename);
									$uploadOk = 1;
									$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
									if ($filesize > 2000000) {
										echo "<script>$(function(){ toastr.error('Sorry, your file is too large'); }); </script>";
										$uploadOk = 0;
									}
									// Allow certain file formats
									//if(!($FileType == "jpg" || $FileType == "jpeg" || $FileType == "pdf" || $FileType == "png") )
									if (!($filetype == "pdf")) {
										echo "<script>$(function(){ toastr.error('Sorry, only pdf file is allowed'); }); </script>";
										$uploadOk = 0;
									}
									// Check if $uploadOk is set to 0 by an error
									if ($uploadOk == 0) {
										echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded'); }); </script>";
										// if everything is ok, try to upload file
									} else {
										if (move_uploaded_file($filetemp, $target_file)) {
											$ext = $FileType;
											$filename = $EmployeeID . '_esicard.' . $ext;
											$file = rename($target_file, $target_dir . $filename);

											/* SMS on mobile */

											if (!empty($mobile)) {

												$templateid = '1707161526672964423';
												//$msg="Hi $EmployeeName, your ESIC card is available in EMS. Now you can download the same. Thanks!";
												$msg = "Hi $EmployeeName, your ESIC card is available in EMS. Now you can download the same. Thanks! - Cogent E Services";
												$url = SMS_URL;
												$token = SMS_TOKEN;
												$credit = SMS_CREDIT;
												$sender = SMS_SENDER;
												$message = $msg;
												$number = $mobile;
												$sendsms = new sendsms($url, $token);
												$message_responce = $sendsms->sendmessage($credit, $sender, $message, $number, $templateid);
											}
											$myDB = new MysqliDb();
											$data = $myDB->rawQuery("select EmployeeID from esicard where EmployeeID='" . $EmployeeID . "' ");
											if (count($data) > 0) {
												$query = "Update esicard set  filename='" . $filename . "', updatedBy='" . $_SESSION['__user_logid'] . "',sms_status='" . $message_responce . "', updatedOn=now(),status='0',source_from='web' where EmployeeID='" . $EmployeeID . "'";
											} else {

												$query = "insert into esicard set EmployeeID='" . $EmployeeID . "', filename='" . $filename . "', createdBy='" . $_SESSION['__user_logid'] . "',sms_status='" . $message_responce . "',source_from='web'";
											}
											if ($query != "") {
												$myDB = new MysqliDb();
												$myDB->rawQuery($query);
												if ($myDB->count > 0) {


													echo "<script>$(function(){ toastr.success('ESI card uploaded of " . $EmployeeID . "'); }); </script>";
												}
											}
										} else {
											echo "<script>$(function(){ toastr.error('Sorry, there was an error to uploading your file.'); }); </script>";
										}
									}
								} else {
									echo "<script>$(function(){ toastr.error('Sorry, Employee location not found.'); }); </script>";
								}
							}
						}


						?>

					</div>
				</div>


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
			$('#esicard').closest('div').removeClass('has-error');

			if ($('#esicard').val() == '') {
				validate = 1;
				$(function() {
					toastr.error('Please select esicard')
				});
				return false;
			}


		});


	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>