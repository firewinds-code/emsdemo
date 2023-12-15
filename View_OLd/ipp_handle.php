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
// Only for user type administrator
if (isset($_SESSION)) {
	$userlogid = clean($_SESSION['__user_logid']);
	if (!isset($userlogid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
	$user_type = clean($_SESSION["__user_type"]);
	if ($user_type != 'ADMINISTRATOR' &&  $userlogid != 'CE10091236') {
		$location = URL . 'Login';
		$_SESSION['MsgLg'] = "You are not allowed to acces this page.";
		echo "<script>location.href='" . $location . "'</script>";
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
/*
ALTER TABLE `new_client_master` 
ADD COLUMN `VH` VARCHAR(45) NULL DEFAULT NULL AFTER `days_of_rotation`;

*/


//Trigger On Delete Btn Clicked


// Trigger Button-Save Click Event and Perform DB Action
$btn_add_new = isset($_POST['btn_add_new']);
if (($btn_add_new)) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$filesCount = count($_FILES['fileToUpload']['name']);

		$file_For = cleanUserInput($_POST['fileFor']);
		$fileFor = (isset($file_For) ? $file_For : null);
		$subProcess = cleanUserInput($_POST['subProcess']);
		$processesSelected = (isset($subProcess) ? $subProcess : null);
		// $Client = cleanUserInput($_POST['Client']);
		$clientSelected = (isset($_POST['Client']) ? $_POST['Client'] : null);
		$LocationID = cleanUserInput($_POST['LocationID']);
		$LocationIDSelected = (isset($LocationID) ? $LocationID : null);
		$file_Type = cleanUserInput($_POST['fileType']);
		$fileType = (isset($file_Type) ? $file_Type : null); //Banner Type
		$Banner_Type = cleanUserInput($_POST['BannerType']);
		$BannerType = (isset($Banner_Type) ? $Banner_Type : null);

		$dirPath_web =  __DIR__ . '/../IndexEditPage/content_current/';
		$dirPath_app =  __DIR__ . '/../IndexEditPage/appimg/';
		$dirPath_disApp =  __DIR__ . '/../IndexEditPage/emp_discount_app/';
		$dirPath_disWeb =  __DIR__ . '/../IndexEditPage/emp_discount_web/';
		$dirPath_recogApp =  __DIR__ . '/../IndexEditPage/emp_recognition_app/';
		$dirPath_recogWeb =  __DIR__ . '/../IndexEditPage/emp_recognition_web/';
		$fileUploded = 0;



		if ($filesCount > 0 && $fileType != "" && $fileType != null &&  $BannerType != "" && $BannerType != null && $fileFor != null) {

			if (($fileFor == "Client" && $clientSelected != null) || ($fileFor == "LocationID" && $LocationIDSelected != null) ||  ($fileFor == "SubProcess" && $processesSelected != null) || $fileFor == "All") {

				if ($fileFor == "Client" && $clientSelected != null) {
					//Get All the Sub Processes Depending upon clients
					// $myDB = new MysqliDb();
					$ids = implode("','", $clientSelected);
					$id2 = explode(',', $ids);

					$count = count($id2);
					$placeholders = implode(',', array_fill(0, $count, '?'));
					$bindStr = str_repeat('i', $count);


					$sqlBy = "SELECT process, sub_process ,cm_id from new_client_master where client_name in ($placeholders);";
					// $qRes = $myDB->rawQuery($sqlBy);

					$stmt = $conn->prepare($sqlBy);
					$stmt->bind_param("$bindStr", ...$id2);
					$stmt->execute();
					$qRes = $stmt->get_result();
					// $mysql_error = $myDB->getLastError();



					$newSubProcessList = array();
					//Creating Subprocess List from Client list
					foreach ($qRes as $qRow) {

						array_push($newSubProcessList, $qRow['process'] . '|' . $qRow['sub_process'] . '|' . $qRow['cm_id']);
					}

					//Now Assign This New Array list to our Process Selected LIST.
					$processesSelected = $newSubProcessList;
				} else if ($fileFor == "All") {
					$newSubProcessList = array();
					array_push($newSubProcessList, 'All|All|All');
					//Now Assign This New Array list to our Process Selected LIST.
					$processesSelected = $newSubProcessList;
				} else if ($fileFor == "LocationID") {
					$newSubProcessList = array();

					//array_push($newSubProcessList ,'All|All|'.$_SESSION["__location"] );
					array_push($newSubProcessList, 'All|All|' . $_POST['LocationID'][0]);
					//Now Assign This New Array list to our Process Selected LIST.
					$processesSelected = $newSubProcessList;
				}

				for ($i = 0; $i < $filesCount; $i++) {
					$fileName  =  $_FILES['fileToUpload']['name'][$i];
					$tempPath  =  $_FILES['fileToUpload']['tmp_name'][$i];
					$fileSize  =  $_FILES['fileToUpload']['size'][$i];


					if ($fileName != "") {
						$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
						if ($fileExt == 'png' || $fileExt == 'jpg' || $fileExt == 'jpeg') {
							if ($fileSize != "" && $fileSize < 2000000) {

								if ($fileType == 'WEB') {
									$direPathToUpload = "";

									//Choosing Directory to Upload.
									if ($BannerType == "Banner") {
										$direPathToUpload = $dirPath_web;
									} else if ($BannerType == "Discount") {
										$direPathToUpload = $dirPath_disWeb;
									} else if ($BannerType == "Recognition") {
										$direPathToUpload = $dirPath_recogWeb;
									}



									if (!empty($direPathToUpload)) {

										//Another Inner Loop for Uploading/ Inserting data for all selected Processes.
										for ($j = 0; $j < count($processesSelected); $j++) {

											//For File Naming
											// $FourDigitRandomNumber = rand(1231, 7879);
											$FourDigitRandomNumber = generateRandomNumber();

											$date = date('YmdHis');
											$fName = $FourDigitRandomNumber . '_' . $date . '.' . $fileExt;
											$isUploaded = 0;

											///////FILE MOVEMENT///
											if ($j == (count($processesSelected) - 1)) {
												//Move Lat File
												move_uploaded_file($tempPath, $direPathToUpload . $fName); // move file from system temporary 	path
												$isUploaded = 1;
											} else {
												copy($tempPath, $direPathToUpload . $fName);  // Copy file from system temporary 	path
												$isUploaded = 1;
											}
											/////


											if ($isUploaded == 1) {
												$fileUploded++;
												echo "<script>$(function(){ toastr.success('File Added Successfully. Total files added : " . $fileUploded . "'); }); </script>";
												//Seperate the Client Value to Get CmId and process
												$processSetailArray = explode("|", $processesSelected[$j]);


												if (count($processSetailArray) == 3) {

													$str = $processSetailArray[0] . '|' . $processSetailArray[1];
													$myDB = new MysqliDb();
													$sqlBy = "INSERT INTO ipp_details(cmid, process_name, banner_type, platform, file_name) VALUES (?, ?, ?, 'WEB' , ?);";
													// $resultBy = $myDB->rawQuery($sqlBy);
													$stmt1 = $conn->prepare($sqlBy);
													$stmt1->bind_param("ssss", $processSetailArray[2], $str, $BannerType, $fName);
													$resultBy = $stmt1->execute();
													$mysql_error = $myDB->getLastError();
													/* if(empty($mysql_error)){		
															} */
												}
											} else {
												echo "<script>$(function(){ toastr.error('Error, File not added.'); }); </script>";
											}
										}
									} else {
										echo "<script>$(function(){ toastr.error('Error, Invalid request.'); }); </script>";
									}
								} else {
									$direPathToUpload = "";
									//Choosing Directory to Upload.
									if ($BannerType == "Banner") {
										$direPathToUpload = $dirPath_app;
									} else if ($BannerType == "Discount") {
										$direPathToUpload = $dirPath_disApp;
									} else if ($BannerType == "Recognition") {
										$direPathToUpload = $dirPath_recogApp;
									}

									if (!empty($direPathToUpload)) {

										//Another Inner Loop for Uploading/ Inserting data for all selected Processes.
										for ($j = 0; $j < count($processesSelected); $j++) {
											//For File Naming
											// $FourDigitRandomNumber = rand(1231, 7879);
											$FourDigitRandomNumber = generateRandomNumber();
											$date = date('YmdHis');
											$fName = $FourDigitRandomNumber . '_' . $date . '.' . $fileExt;
											$isUploaded = 0;

											///////FILE MOVEMENT///
											if ($j == (count($processesSelected) - 1)) {
												//Move Lat File
												move_uploaded_file($tempPath, $direPathToUpload . $fName); // move file from system temporary 	path
												$isUploaded = 1;
											} else {
												copy($tempPath, $direPathToUpload . $fName);  // Copy file from system temporary 	path
												$isUploaded = 1;
											}
											/////


											if ($isUploaded == 1) {
												$fileUploded++;
												echo "<script>$(function(){ toastr.success('File Added Successfully. Total files added : " . $fileUploded . "'); }); </script>";
												//Seperate the Client Value to Get CmId and process
												$processSetailArray = explode("|", $processesSelected[$j]);


												if (count($processSetailArray) == 3) {

													// $myDB = new MysqliDb();
													// $sqlBy = "INSERT INTO ipp_details(cmid, process_name, banner_type, platform, file_name) VALUES ('" . $processSetailArray[2] . "', '" . $processSetailArray[0] . '|' . $processSetailArray[1] . "', '" . $BannerType . "', 'APP' , '" . $fName . "');";
													// $resultBy = $myDB->rawQuery($sqlBy);
													// $mysql_error = $myDB->getLastError();
													$str = $processSetailArray[0] . '|' . $processSetailArray[1];
													$myDB = new MysqliDb();
													$sqlBy = "INSERT INTO ipp_details(cmid, process_name, banner_type, platform, file_name) VALUES (?, ?, ?, 'APP' , ?);";
													// $resultBy = $myDB->rawQuery($sqlBy);
													$stmt1 = $conn->prepare($sqlBy);
													$stmt1->bind_param("ssss", $processSetailArray[2], $str, $BannerType, $fName);
													$resultBy = $stmt1->execute();
													$mysql_error = $myDB->getLastError();
												}
											} else {
												echo "<script>$(function(){ toastr.error('Error, File not added.'); }); </script>";
											}
										}
									} else {
										echo "<script>$(function(){ toastr.error('Error, Invalid request.'); }); </script>";
									}
								}
							} else {
								echo "<script>$(function(){ toastr.error('File size should not exceed 2 MB.'); }); </script>";
							}
						} else {
							echo "<script>$(function(){ toastr.error('File extension error.'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Please select a file first.'); }); </script>";
					}
				}
			} else {
				echo "<script>$(function(){ toastr.error('All fields are required.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('File for, Photo, its type and platform should not be empty.'); }); </script>";
		}
	}
}
// Trigger Button-Edit Click Event and Perform DB Action

?>

<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}
				/*,'copy'*/
				, 'pageLength'
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');

	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Index Page Photos</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Handle Photos
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" onclick="javascript:return openModelToAdd();" data-position="bottom" data-tooltip="Add Photo"><i class="material-icons">add</i></a>
			</h4>


			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal modal_small">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Add Photo</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">


							<div class="col s12 m12">

								<label for="fileForr" class="active-drop-down active" style="color: #19aec4;">File For</label>
								<input type="radio" id="forAll" name="fileFor" value="All" onclick="handleChange1('All');">
								  <label for="forAll">All</label>
								<input type="radio" id="forLocation" name="fileFor" value="LocationID" onclick="handleChange1('LocationID');">
								  <label for="forLocation">Location</label>
								  <input type="radio" id="forClient" name="fileFor" value="Client" onclick="handleChange1('Client');">
								  <label for="forClient">Client</label>
								  <input type="radio" id="forSubProcess" name="fileFor" value="SubProcess" onclick="handleChange1('SubProcess');">
								  <label for="forSubProcess">Sub Process</label>
								<br>
								<br>
							</div>

							<div class="col s12 m12" id="LocationIDDropDown" style="display: none">
								<label for="Client" class="active-drop-down active" style="color: #19aec4;">Location</label>
								<Select name="LocationID[]" id="Location" multiple>
									<option value=''>Select</option>
									<!-- <option value='All|All|All'>All|All|All</option> -->
									<?php
									$sqlBy = "select id,location from location_master";
									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['id'] . '">' . $value['location'] . '</option>';
										}
									}
									?>
								</Select>
							</div>

							<div class="col s12 m12" id="clientDropDown" style="display: none">

								<label for="Client" class="active-drop-down active" style="color: #19aec4;">Client</label>
								<Select name="Client[]" id="Client" multiple>
									<option value=''>Select</option>
									<!-- <option value='All|All|All'>All|All|All</option> -->
									<?php
									$sqlBy = "SELECT distinct cm.client_name,cm.client_id from client_master cm inner join new_client_master nc on cm.client_id = nc.client_name left join  client_status_master cs on nc.cm_id = cs.cm_id where cs.cm_id is null;";
									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['client_id'] . '">' . $value['client_name'] . '</option>';
										}
									}
									?>
								</Select>

							</div>

							<div class="col s12 m12" id="subProcessDropdown" style="display: none">

								<label for="subProcess" class="active-drop-down active" style="color: #19aec4;">Sub Process</label>
								<Select name="subProcess[]" id="subProcess" multiple>
									<option value=''>Select</option>
									<!-- <option value='All|All|All'>All|All|All</option> -->
									<?php
									$sqlBy = "SELECT nc.location,nc.process,nc.sub_process,nc.cm_id FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join client_status_master cm on nc.cm_id=cm.cm_id where cm.cm_id is null order by c.client_name;";
									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['process'] . '|' . $value['sub_process'] . '|' . $value['cm_id'] . '">' . $value['process'] . '|' . $value['sub_process'] . '|' . $value['cm_id'] . '</option>';
										}
									}
									?>
								</Select>

							</div>


							<div class="input-field col s12 m12" id="divStatus">
								<Select name="BannerType" id="BannerType">
									<option value=''>Select</option>
									<option value='Banner'>Banner</option>
									<option value='Discount'>Employee Discount</option>
									<option value='Recognition'>Employee Recognition</option>

								</Select>
								<label for="BannerType" class="active-drop-down active">Banner Type</label>
							</div>

							<div class="input-field col s12 m12" id="divStatus">
								<Select name="fileType" id="fileType">
									<option value=''>Select</option>
									<option value='WEB'>WEB</option>
									<option value='APP'>APP</option>

								</Select>
								<label for="fileType" class="active-drop-down active">Platform</label>
							</div>
							<div class="file-field input-field col s5 m5">
								<div class="btn">
									<span>Photo</span>
									<input type="file" id="fileToUpload" name="fileToUpload[]" style="text-indent: -99999em;" multiple>
									<br>
									<span class="file-size-text">Accepts up to 2MB (.png,.jpg,.jpeg)</span>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path" type="text" style="">
								</div>
							</div>


							<div class="input-field col s12 m12 right-align">
								<!--	<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green hidden">Add</button> -->
								<button type="submit" name="btn_add_new" id="btn_add_new" class="btn waves-effect waves-green " value="Submit">Submit</button>
								<button type="button" name="btn_add_new_Can" id="btn_add_new_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>

						</div>
					</div>


				</div>
			</div>
			<!--Form element model popup End-->


			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div id="myModal_preview" class="modal modal_small">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Photo Preview</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">


							<div class="input-field col s12 m12" id="divStatus">
								<div align="center">
									<img id="imgshow" width="600" height="400" src="" alt="image2" />
								</div>
							</div>




							<div class="input-field col s12 m12 right-align">
								<!--	<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green hidden">Add</button> -->

								<button type="button" name="btn_add_new_Can" id="btn_add_new_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
							</div>

						</div>

					</div>
				</div>
			</div>
			<!--Form element model popup End-->


			<!--Reprot / Data Table start -->
			<div id="pnlTable">
				<?php
				//For Server
				$dirPath_web =  '/../IndexEditPage/content_current/';
				$dirPath_app =  '/../IndexEditPage/appimg/';
				$dirPath_disApp =  '/../IndexEditPage/emp_discount_app/';
				$dirPath_disWeb =  '/../IndexEditPage/emp_discount_web/';
				$dirPath_recogApp =  '/../IndexEditPage/emp_recognition_app/';
				$dirPath_recogWeb =  '/../IndexEditPage/emp_recognition_web/';
				/* $dirPath_web =  __DIR__.'/../IndexEditPage/content_current/';
			$dirPath_app =  __DIR__.'/../IndexEditPage/appimg/';
			$dirPath_disApp =  __DIR__.'/../IndexEditPage/emp_discount_app/';
			$dirPath_disWeb =  __DIR__.'/../IndexEditPage/emp_discount_web/';
			$dirPath_recogApp =  __DIR__.'/../IndexEditPage/emp_recognition_app/';
			$dirPath_recogWeb =  __DIR__.'/../IndexEditPage/emp_recognition_web/';
			$web_files = array_filter(glob( $dirPath_web.'*'), 'is_file');
			$app_files = array_filter(glob( $dirPath_app.'*'), 'is_file');
			$disApp_files = array_filter(glob( $dirPath_disApp.'*'), 'is_file');
			$disWeb_files = array_filter(glob( $dirPath_disWeb.'*'), 'is_file');
			$recogApp_files = array_filter(glob( $dirPath_recogApp.'*'), 'is_file');
			$recogWeb_files = array_filter(glob( $dirPath_recogWeb.'*'), 'is_file'); */

				$sqlBy = "SELECT id, cmid, process_name, banner_type, platform, file_name, created_date from ipp_details;";
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($sqlBy);

				?>
				<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>S No</th>
							<th>ID</th>
							<th>Process</th>
							<th>Banner Type</th>
							<th>PlatForm</th>
							<th>File PreView</th>
							<th>File Name</th>
							<th>Date</th>
							<th>Delete</th>

						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						//   href="../IndexEditPage/content_current/'.basename($file).'";
						if (count($result) > 0) {
							foreach ($result as $row) {



								//	echo $imgPathN.$row['file_name'];
								$pName = $row['process_name'] . '|' . $row['cmid'];

								echo '<tr>';
								echo '<td >' . $i . '</td>';
								echo '<td  class="rowId">' . $row['id'] . '</td>';
								echo '<td class="processName">' . $pName . '</td>';
								echo '<td class="BannerType">' . $row['banner_type'] . '</td>';
								echo '<td class="Platform">' . $row['platform'] . '</td>';
								if ($row['banner_type'] == "Banner" && $row['platform'] == "WEB") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/content_current/' . $row['file_name'] . '" ><img  width="42" height="42" src="../IndexEditPage/content_current/' . $row['file_name'] . '" alt="image2" onclick=""/></a></td>';
								} elseif ($row['banner_type'] == "Banner" && $row['platform'] == "APP") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/appimg/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/appimg/' . $row['file_name'] . '" alt="image2"/></a></td>';
								} elseif ($row['banner_type'] == "Discount" && $row['platform'] == "WEB") {
									echo '<td class="FilePreview"><a class="photoView"  imgPath="../IndexEditPage/emp_discount_web/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/emp_discount_web/' . $row['file_name'] . '" alt="image2"/></a></td>';
								} elseif ($row['banner_type'] == "Discount" && $row['platform'] == "APP") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/emp_discount_app/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/emp_discount_app/' . $row['file_name'] . '" alt="image2"/></a></td>';
								} elseif ($row['banner_type'] == "Recognition" && $row['platform'] == "WEB") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/emp_recognition_web/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/emp_recognition_web/' . $row['file_name'] . '" alt="image2"/></a></td>';
								} elseif ($row['banner_type'] == "Recognition" && $row['platform'] == "APP") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/emp_recognition_app/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/emp_recognition_app/' . $row['file_name'] . '" alt="image2"/></a></td>';
								} elseif ($row['banner_type'] == "LifeCogent" && $row['platform'] == "APP") {
									echo '<td class="FilePreview"><a class="photoView" imgPath="../IndexEditPage/emp_lifecogent_app/' . $row['file_name'] . '"><img  width="42" height="42" src="../IndexEditPage/emp_lifecogent_app/' . $row['file_name'] . '" alt="image2"/></a></td>';
								}

								echo '<td class="FileName">' . $row['file_name'] . '</td>';
								echo '<td class="FileDate">' . $row['created_date'] . '</td>';
								echo '<td class="delete_verifier" ><i class="material-icons delete_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return DeletePhoto(this);" id="' . $i . '"   data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>';

								echo '</tr>';
								$i++;
							}
						}

						?>
					</tbody>
				</table>

			</div>
			<!--Reprot / Data Table End -->
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
<script>
	$(document).ready(function() {

		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Verifier_Can').trigger("click");
			}
		});




	});



	$('.photoView').click(function() {
		//alert('hohoho');


		$('#imgshow').attr('src', $(this).attr('imgPath'));
		$('#myModal_preview').modal('open');

	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function openModelToAdd() {
		$('#myModal_content').modal('open');

	}



	function isValid(str) {
		return (!str || str.length === 0 || str == 'na');
	}



	function handleChange1(fileFor) {

		//Hide All
		$('#clientDropDown').hide(300);
		$('#subProcessDropdown').hide(300);
		$('#LocationIDDropDown').hide(300);

		switch (fileFor) {

			case "LocationID":
				//show 
				$('#LocationIDDropDown').show(300);

				break;

			case "Client":
				//show 
				$('#clientDropDown').show(300);

				break;
			case "SubProcess":

				$('#subProcessDropdown').show(300);
				break;
			default:
				break;
		}
	}




	// This code for trigger del*t*

	function DeletePhoto(el) {
		////alert(el);
		//var currentUrl = window.location.href;
		var Cnfm = confirm("Do You Want To Delete This Photo ? ");
		if (Cnfm) {

			var tr = $(el).closest('tr');
			var rowId = tr.find('.rowId').text();
			var fileType = tr.find('.Platform').text();
			var bannerType = tr.find('.BannerType').text();
			var fileName = tr.find('.FileName').text();


			var xmlhttp;
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var Resp = xmlhttp.responseText;
					alert(Resp);
					$(function() {
						toastr.success(Resp);
					});
					window.location.replace("../View/ipp_handle.php");
					//window.location.href = currentUrl;

				}
			}


			xmlhttp.open("GET", "../Controller/ipp_delete_photo.php?type=" + fileType + "&name=" + fileName + "&bannerType=" + bannerType + "&rowId=" + rowId, true);
			xmlhttp.send();

		}
	}





	function isNumber(evt) {
		var iKeyCode = (evt.which) ? evt.which : evt.keyCode
		if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
			return false;
		return true;
	}


	//On Click SAve Status Btn
</script>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>