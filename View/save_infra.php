<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$alert_msg = '';
$EmployeeID = $btnShow = '';
$file = $CancelledCheque = '';
if (isset($_REQUEST['empid']) && $_REQUEST['empid'] != "") {
	$EmployeeID = $_REQUEST['empid'];
}

$dir_location = $loc = '';
$myDB = new MysqliDb();
$EmployeeID = strtoupper($EmployeeID);
$sql = 'select location from personal_details where EmployeeID = "' . $EmployeeID . '"';
$result = $myDB->rawQuery($sql);
$mysql_error = $myDB->getLastError();
if (empty($mysql_error)) {
	$loc = $result[0]['location'];
}
if ($loc == "1" || $loc == "2") {
	$dir_location = '';
} else if ($loc == "3") {
	$dir_location = 'Meerut/';
} else if ($loc == "4") {
	$dir_location = "Bareilly/";
} else if ($loc == "5") {
	$dir_location = "Vadodara/";
} else if ($loc == "6") {
	$dir_location = "Manglore/";
} else if ($loc == "7") {
	$dir_location = "Bangalore/";
} else if ($loc == "8") {
	$dir_location = "Nashik/";
} else if ($loc == "9") {
	$dir_location = "Anantapur/";
} else if ($loc == "10") {
	$dir_location = "Gurgaon/";
} else if ($loc == "11") {
	$dir_location = "Hyderabad/";
}
$sys_img = '';
$inlanproof = '';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$sys_name = (isset($_POST['sys_name']) ? $_POST['sys_name'] : null);
	$sys_available = (isset($_POST['sys_available']) ? $_POST['sys_available'] : null);
	$sys_processor = (isset($_POST['sys_processor']) ? $_POST['sys_processor'] : null);
	$internet_avail = (isset($_POST['internet_avail']) ? $_POST['internet_avail'] : null);
	$internet_type = (isset($_POST['internet_type']) ? $_POST['internet_type'] : null);
	$service_provider = (isset($_POST['service_provider']) ? $_POST['service_provider'] : null);
	$internet_plan = (isset($_POST['internet_plan']) ? $_POST['internet_plan'] : null);
} else {
	$branch = $active = $InfraName = $location = $account = $txt_name_asper_Infra = '';
}
$target_dir2 = $dir_location . 'Docs/InternetDocs/';
$target_dir1 = $dir_location . 'Docs/InfraDocs/';
//Check Employee is exist or not
if (isset($_REQUEST['empid']) && $EmployeeID == '' && !isset($_POST['txt_Infra_account'])) {
	$EmployeeID = $_REQUEST['empid'];
	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
	} else {
		echo "<script type='text/javascript'> alert('Wrong Employee To Search ....');window.location='" . URL . "'</script>";
	}
}
if (isset($_REQUEST['empid']) && $EmployeeID == '') {
	$EmployeeID = $_REQUEST['empid'];
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	$EmployeeID = $_POST['EmployeeID'];
}

if ((isset($_POST['btn_infra_Add']) || isset($_POST['btn_infra_Save'])) && $EmployeeID != '') {
	$username = $EmployeeID;
	$datetime = date("Y-m-d h:i:s");

	if (isset($_FILES["sys_img"]["name"]) and $_FILES["sys_img"]["name"] != "") {
		$directory1 = ROOT_PATH . $dir_location . 'Docs/InfraDocs/';
		$sys_path = $directory1 . basename($_FILES["sys_img"]["name"]);
		$FileType = pathinfo($sys_path, PATHINFO_EXTENSION);

		if (move_uploaded_file($_FILES["sys_img"]["tmp_name"], $sys_path)) {
			$sys_img = $EmployeeID . '_infra.' . $FileType;
			$file = rename($sys_path, $directory1 . $sys_img);

			$myDB =  new MysqliDb();
			$uploadOk = 0;
		}
	} else {
		$sys_img = $_POST['hsys_img'];
	}
	if (isset($_FILES["inlanproof"]["name"]) and $_FILES["inlanproof"]["name"] != "") {
		$directory2 = ROOT_PATH . $dir_location . 'Docs/InternetDocs/';
		$sys_path2 = $directory2 . basename($_FILES["inlanproof"]["name"]);
		$FileType = pathinfo($sys_path2, PATHINFO_EXTENSION);
		if (move_uploaded_file($_FILES["inlanproof"]["tmp_name"], $sys_path2)) {
			$inlanproof = $EmployeeID . '_planproof.' . $FileType;
			$file = rename($sys_path2, $directory2 . $inlanproof);
			$myDB =  new MysqliDb();
			$uploadOk = 0;
		}
	} else {
		$inlanproof = $_POST['hinlanproof'];
	}
	$sqlInsertInfra = 'call insert_infradetails("' . $EmployeeID . '","' . addslashes($sys_name) . '","' . addslashes($sys_available) . '","' . addslashes($sys_processor) . '","' . addslashes($sys_img) . '","' . addslashes($internet_avail) . '","' . addslashes($internet_type) . '","' . addslashes($service_provider) . '","' . addslashes($internet_plan) . '","' . addslashes($inlanproof) . '")';
	$result = $myDB->query($sqlInsertInfra);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		echo "<script>$(function(){ toastr.success('Infra Details updated Successfully') });</script>";
	} else {
		echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
	}
}


?>

<script>
	$(document).ready(function() {

		var usrtype = <?php echo "'" . $_SESSION["__user_type"] . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR') {} else if (usrtype === 'AUDIT') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();

			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS' || usrid == 'CE05070035') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		} else {
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"' . URL . '/undefined"'; ?>;
		}



	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Infra Details</span>
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Infra Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$sqlInfraQuery = "select * from infra_details where EmployeeID='" . $EmployeeID . "' ";
				$myDB = new MysqliDb();
				$data_array = $myDB->query($sqlInfraQuery);
				$sys_name = '';
				$sys_available = '';
				$sys_processor = '';
				$sys_img = '';
				$internet_avail = '';
				$internet_type = '';
				$service_provider = '';
				$internet_plan = '';
				$inlanproof_doc = '';
				$Infraid = '';
				if (count($data_array) > 0) {
					$sys_name = $data_array[0]['sys_name'];
					$sys_available = $data_array[0]['sys_available'];
					$sys_processor = $data_array[0]['sys_processor'];
					$sys_img = $data_array[0]['sys_img'];
					$internet_avail = $data_array[0]['internet_avail'];
					$internet_type = $data_array[0]['internet_type'];
					$service_provider = $data_array[0]['service_provider'];
					$internet_plan = $data_array[0]['internet_plan'];
					$inlanproof_doc = $data_array[0]['inlanproof_doc'];
					$Infraid = $data_array[0]['ID'];
				}
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />

				<div class="input-field col s6 m6">
					<select id="sys_available" name="sys_available" required>
						<option value="">---Select---</option>
						<option value='No' <?php if ($sys_available == 'No') {
												echo "Selected";
											} ?>>No</option>
						<option value='Yes' <?php if ($sys_available == 'Yes') {
												echo "Selected";
											} ?>>Yes</option>

					</select>
					<label for="txt_Infra_Infraname" class="active-drop-down active">System Availability *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="sys_name" name="sys_name">
						<option value="">---Select---</option>
						<option value='Laptop' <?php if ($sys_name == 'Laptop') {
													echo "Selected";
												} ?>>Laptop</option>
						<option value='Desktop' <?php if ($sys_name == 'Desktop') {
													echo "Selected";
												} ?>>Desktop</option>
					</select>
					<label for="sys_name" class="active-drop-down active">System Type *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="sys_processor" maxlength="100" name="sys_processor" value="<?php echo $sys_processor; ?>">
					<label for="sys_processor">System Processor *</label>
				</div>
				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="sys_img" id="sys_img">
						<br>
						<a onclick="javascript:return Download(this);" id="<?php echo $sys_img; ?>" data="<?php echo $target_dir1; ?>">System Proof</a><br>
						<span class="file-size-text help-block" id="sysimg">&nbsp;</span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>

				<input type="text" id="hsys_img" maxlength="100" name="hsys_img" value="<?php echo $sys_img; ?>">
				<div class="input-field col s6 m6">
					<select id="internet_avail" name="internet_avail">
						<option value='Yes' <?php if ($internet_avail == 'Yes') {
												echo "Selected";
											} ?>>Yes</option>
						<option value='No' <?php if ($internet_avail == 'No') {
												echo "Selected";
											} ?>>No</option>

					</select>
					<label for="internet_avail" class="active-drop-down active">Internet Availability *</label>
				</div>
				<div class="input-field col s6 m6">
					<select id="internet_type" name="internet_type">
						<option value="">---Select---</option>
						<option value="Wired Broadband" <?php if ($internet_type == 'Wired Broadband') {
															echo "Selected";
														} ?>>Wired Broadband</option>
						<option value="Mobile Internet" <?php if ($internet_type == 'Mobile Internet') {
															echo "Selected";
														} ?>>Mobile Internet</option>
					</select>
					<label for="internet_type" class="active-drop-down active">Internet Type *</label>
				</div>
				<div class="input-field col s6 m6">

					<select name="service_provider" id="service_provider">
						<option value="">--Select---</option>
						<option value="Airtel" <?php if ($service_provider == 'Airtel') {
													echo "Selected";
												} ?>>Airtel</option>
						<option value="BSNL" <?php if ($service_provider == 'BSNL') {
													echo "Selected";
												} ?>>BSNL</option>
						<option value="Vodafone Idea" <?php if ($service_provider == 'Vodafone Idea') {
															echo "Selected";
														} ?>>Vodafone Idea</option>
						<option value="JIO" <?php if ($service_provider == 'JIO') {
												echo "Selected";
											} ?>>JIO</option>
						<option value="Reliance" <?php if ($service_provider == 'Reliance') {
														echo "Selected";
													} ?>>Reliance</option>
						<option value="Tata" <?php if ($service_provider == 'Tata') {
													echo "Selected";
												} ?>>Tata</option>
						<option value="Other" <?php if ($service_provider == 'Other') {
													echo "Selected";
												} ?>>Other</option>
					</select>
					<label for="service_provider" class="active-drop-down active">Service Provider *</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" id="internet_plan" maxlength="100" name="internet_plan" value="<?php echo $internet_plan; ?>">

					<label for="internet_plan">Internet Plan *</label>
				</div>

				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="inlanproof" id="inlanproof">
						<br>
						<a onclick="javascript:return Download(this);" id="<?php echo $inlanproof_doc; ?>" data="<?php echo $target_dir2; ?>">Internet Proof</a><br>
						<span class="file-size-text help-block" id="itpnane">&nbsp;</span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<input type="hidden" name="hinlanproof" id="hinlanproof" value="<?php echo $inlanproof_doc; ?>">
				<div class="input-field col s12 m12 right-align">

					<?php
					if (count($data_array) < 1) {
					?>
						<button type="submit" title="Add Details" name="btn_infra_Add" id="sys_img" class="btn waves-effect waves-green  ">Add</button> <?php } else { ?>
						<button type="submit" title="Update Details" name="btn_infra_Save" id="btn_infra_Save" class="btn waves-effect waves-green ">Save</button> <?php } ?>

					<div class="hidden modelbackground" id="myDiv"></div>
					<script>
						$(document).ready(function() {

							$('#internet_avail').change(function() {
								if ($('#internet_avail').val() == 'No') {

									$('#internet_type').val('');
									$('#service_provider').val('');
									$('#internet_plan').val();
									$('#hinlanproof').val();
									form.select();

								}

							});
							$('#sys_available').change(function() {
								if ($('#sys_available').val() == 'No') {
									$('#sys_name').val('');
									$('#sys_processor').val();
									$('#hsys_img').val();

								}

							});

							$('#btn_infra_Add,#btn_infra_Save').on('click', function() {
								validate = 0;

								if ($('#sys_available').val() == 'Yes') {

									if ($('#sys_name').val() == '') {
										$('#sys_name').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#ssys_name').size() == 0) {
											$('<span id="ssys_name" class="help-block">Please select system type</span>').insertAfter('#sys_name');
											$('#sys_name').focus();
										}
									}
									if ($('#sys_processor').val() == '') {
										$('#sys_processor').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#ssys_processor').size() == 0) {
											$('<span id="ssys_processor" class="help-block">Please enter your processor</span>').insertAfter('#sys_processor');
											$('#sys_processor').focus();
										}
									}

									var fileExtension = ['jpeg', 'jpg', 'png'];
									if ($('#sys_img').val() == '' && $('#hsys_img').val() == '') {

										$('#sysimg').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#sssys_img').size() == 0) {

											$('#sysimg').html('Please enter your system image');
											$('#sys_img').focus();
										}

									} else {
										if ($('#sys_img').val() != "") {


											if ($.inArray($('#sys_img').val().split('.').pop().toLowerCase(), fileExtension) == -1) {

												$('#sysimg').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
												validate = 1;
												if ($('#ssys_img').size() == 0) {
													$('#sysimg').html('Image Only formats are allowed : ' + fileExtension.join(', '));
													$('#sys_img').focus();
												}

											} else {
												var file_size = $('#sys_img')[0].files[0].size;
												var calf1 = file_size / 1024;
												if (calf1 >= "1000") {
													$('#sysimg').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
													validate = 1;
													if ($('#ssys_img').size() == 0) {
														$('#sysimg').html('Image File is greater than 1MB');
														$('#sys_img').focus();
													}
												}
											}
										}
									}
								}
								if ($('#internet_avail').val() == 'Yes') {


									if ($('#internet_type').val() == '') {
										$('#internet_type').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#sinternet_type').size() == 0) {
											$('<span id="sinternet_type" class="help-block">Please enter your processor</span>').insertAfter('#internet_type');
											$('#internet_type').focus();
										}

									}

									if ($('#service_provider').val() == '') {
										$('#service_provider').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#sservice_provider').size() == 0) {
											$('<span id="sservice_provider" class="help-block">Please select your service provider</span>').insertAfter('#service_provider');
											$('#service_provider').focus();
										}
									}
									if ($('#internet_plan').val() == '') {
										$('#internet_plan').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#sinternet_plan').size() == 0) {
											$('<span id="sinternet_plan" class="help-block">Please select your service provider</span>').insertAfter('#internet_plan');
											$('#internet_plan').focus();
										}
									}
									if ($('#inlanproof').val() == '' && $('#hinlanproof').val() == '') {
										$('#itpnane').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
										validate = 1;
										if ($('#sinlanproof').size() == 0) {
											$('#itpnane').html('Please select your service provider');

											$('#inlanproof').focus();
										}
									} else {
										if ($('#inlanproof').val() != "") {
											if ($.inArray($('#inlanproof').val().split('.').pop().toLowerCase(), fileExtension) == -1) {

												$('#itpnane').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
												validate = 1;
												if ($('#sinlanproof').size() == 0) {
													$('#itpnane').html('Image Only formats are allowed : ' + fileExtension.join(', '));
													$('#inlanproof').focus();
												}
											} else {
												var file_size = $('#inlanproof')[0].files[0].size;
												var calf1 = file_size / 1024;
												if (calf1 >= "1000") {
													$('#itpnane').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
													validate = 1;
													if ($('#sinlanproof').size() == 0) {
														$('#itpnane').html('Image File is greater than 1MB');
														$('#inlanproof').focus();
													}
												}
											}

										}
									}
								}

								if (validate == 1) {
									return false;
								}
							});


						});

						function Download(el) {
							var ipath = $(el).attr("data");
							if ($(el).attr("id") != '') {
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
									url: "../" + ipath + $(el).attr("id"),
									type: 'HEAD',
									error: function() {
										alert('No File Exist');
									},
									success: function() {
										imgcheck = function(filename) {
											return (filename).split('.').pop();
										}
										imgchecker = imgcheck("../" + ipath + $(el).attr("id"));

										if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
											getImageDimensions("../" + ipath + $(el).attr("id"), function(data) {
												var img = data;

												$('<img>', {
													src: "../" + ipath + $(el).attr("id")
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
														link.download = $(el).attr("id");
														document.body.appendChild(link);
														link.click();

													}
												});




											});
										} else if (imgchecker.match(/(pdf)$/i)) {
											window.open("../" + ipath + $(el).attr("id"));
											//window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../../"+ipath+$(el).attr("id"));
										} else {
											window.open("../" + ipath + $(el).attr("id"));
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
				</div>
				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>
	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>