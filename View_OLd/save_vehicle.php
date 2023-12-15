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
$alert_msg = '';
$EmployeeID = $btnShow = '';
$file = $CancelledCheque1 = $CancelledCheque2 = $CancelledCheque3 = $loc = $loc_dir = '';
$empid = clean($_REQUEST['empid']);
$getDetails = 'call get_personal("' . $empid . '")';
$myDB = new MysqliDb();
$result_all = $myDB->query($getDetails);
if ($result_all) {
	$loc = $result_all[0]['location'];

	if ($loc == "1" || $loc == "2") {
		$loc_dir = 'Docs/VehicleDocs/';
	} else if ($loc == "3") {
		$loc_dir = 'Meerut/Docs/VehicleDocs/';
	} else if ($loc == "4") {
		$loc_dir = 'Bareilly/Docs/VehicleDocs/';
	} else if ($loc == "5") {
		$loc_dir = 'Vadodara/Docs/VehicleDocs/';
	} else if ($loc == "6") {
		$loc_dir = 'Manglore/Docs/VehicleDocs/';
	} else if ($loc == "7") {
		$loc_dir = 'Bangalore/Docs/VehicleDocs/';
	} else if ($loc == "8") {
		$loc_dir = 'Nashik/Docs/VehicleDocs/';
	} else if ($loc == "9") {
		$loc_dir = 'Anantapur/Docs/VehicleDocs/';
	}
}
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_dl_no = cleanUserInput($_POST['txt_dl_no']);
		$dl_no = (isset($txt_dl_no) ? $txt_dl_no : null);
		$txt_dl_name = cleanUserInput($_POST['txt_dl_name']);
		$dl_name = (isset($txt_dl_name) ? $txt_dl_name : null);
		$txt_rc_no = cleanUserInput($_POST['txt_rc_no']);
		$rc_no = (isset($txt_rc_no) ? $txt_rc_no : null);
		$txt_rc_name = cleanUserInput($_POST['txt_rc_name']);
		$rc_name = (isset($txt_rc_name) ? $txt_rc_name : null);
		$dir_location = cleanUserInput($_POST['center_location']);

		$target_dir = ROOT_PATH . $dir_location;

		if (isset($_FILES["File1"]["name"]) and $_FILES["File1"]["name"] != "") {
			$target_file3 = $target_dir . basename($_FILES["File1"]["name"]);
			$FileType = pathinfo($target_file3, PATHINFO_EXTENSION);
			$CancelledCheque1 = cleanUserInput($_POST['EmployeeID']) . '_dl_docs.' . $FileType;
			if (move_uploaded_file($_FILES["File1"]["tmp_name"], $target_file3)) {
				$file = rename($target_file3, $target_dir . $CancelledCheque1);
			}
		} else if (isset($_POST['hiddenFile1']) and $_POST['hiddenFile1'] != "") {
			$CancelledCheque1 = cleanUserInput($_POST['hiddenFile1']);
		}

		if (isset($_FILES["File2"]["name"]) and $_FILES["File2"]["name"] != "") {
			$target_file3 = $target_dir . basename($_FILES["File2"]["name"]);
			$FileType = pathinfo($target_file3, PATHINFO_EXTENSION);
			$CancelledCheque2 = cleanUserInput($_POST['EmployeeID']) . '_rc_docs.' . $FileType;
			if (move_uploaded_file($_FILES["File2"]["tmp_name"], $target_file3)) {
				$file = rename($target_file3, $target_dir . $CancelledCheque2);
			}
		} else if (isset($_POST['hiddenFile2']) and $_POST['hiddenFile2'] != "") {
			$CancelledCheque2 = cleanUserInput($_POST['hiddenFile2']);
		}

		if (isset($_FILES["File3"]["name"]) and $_FILES["File3"]["name"] != "") {
			$target_file3 = $target_dir . basename($_FILES["File3"]["name"]);
			$FileType = pathinfo($target_file3, PATHINFO_EXTENSION);
			$CancelledCheque3 = cleanUserInput($_POST['EmployeeID']) . '_vehicle_docs.' . $FileType;
			if (move_uploaded_file($_FILES["File3"]["tmp_name"], $target_file3)) {
				$file = rename($target_file3, $target_dir . $CancelledCheque3);
			}
		} else if (isset($_POST['hiddenFile3']) and $_POST['hiddenFile3'] != "") {
			$CancelledCheque3 = cleanUserInput($_POST['hiddenFile3']);
		}
	}
} else {
	$dl_no = $dl_name = $rc_no = $rc_name = '';
}
//Check Employee is exist or not
if (isset($_REQUEST['empid']) && $EmployeeID == '' && !isset($_POST['txt_dl_no'])) {
	$EmployeeID = clean($_REQUEST['empid']);
	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
	} else {
		echo "<script type='text/javascript'> alert('Wrong Employee To Search ....');window.location='" . URL . "'</script>";
	}
}
if (isset($_REQUEST['empid']) && $EmployeeID == '') {
	$EmployeeID = clean($_REQUEST['empid']);
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = cleanUserInput($_POST['EmployeeID']);
	}
}

if (isset($_POST['btn_experice_Add']) && $EmployeeID != '') {

	$createBy = clean($_SESSION['__user_logid']);
	$select_Bank = "select EmployeeID from vehicle_details where EmployeeID=? ";
	$insQu = $conn->prepare($select_Bank);
	$insQu->bind_param("s", $EmployeeID);
	$insQu->execute();
	$selectBank = $insQu->get_result();
	if ($selectBank->num_rows < 1) {

		$myDB = new MysqliDb();
		$sqlInsertBank = 'call add_vehicledetails("' . $dl_no . '","' . $dl_name . '","' . $CancelledCheque1 . '","' . $rc_no . '","' . $rc_name . '","' . $CancelledCheque2 . '","' . $CancelledCheque3 . '","' . $EmployeeID . '",)';
		$result = $myDB->query($sqlInsertBank);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Vehicle Details is added Successfully') });</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
		}
	} else {
		//echo "<script>$(function(){ toastr.error('Data Addedd') });</script>";
	}
}

$btn_experice_Save = isset($_POST['btn_experice_Save']);
if ($btn_experice_Save && $_POST['vehicleSelect'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$myDB = new MysqliDb();
		$createBy = clean($_SESSION['__user_logid']);
		$vehicle_id = cleanUserInput($_POST['vehicleSelect']);

		$sqlInsertDoc = 'call save_vehicledetails("' . $vehicle_id . '","' . $dl_no . '","' . $dl_name . '","' . $CancelledCheque1 . '","' . $rc_no . '","' . $rc_name . '","' . $CancelledCheque2 . '","' . $CancelledCheque3 . '","' . $EmployeeID . '","' . $createBy . '")';

		$result = $myDB->query($sqlInsertDoc);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Vehicle Details is Saved Successfully') });</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		var usrtype = <?php echo "'" . clean($_SESSION["__user_type"]) . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR') {} else if (usrtype === 'AUDIT') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();

			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS') {

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
	<span id="PageTittle_span" class="hidden">Vehicle Details</span>
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Vehicle Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">


				<?php
				$sqlBankQuery = "select * from vehicle_details where EmployeeID=? ";
				$myDB = new MysqliDb();
				$conn = $myDB->dbConnect();
				$sel = $conn->prepare($sqlBankQuery);
				$sel->bind_param("s", $EmployeeID);
				$sel->execute();
				$res = $sel->get_result();
				$data_array = $res->fetch_row();

				// $data_array = $myDB->query($sqlBankQuery);
				$dl_no = $dl_name = $CancelledCheque1 = $rc_no = $rc_name = $CancelledCheque2 = $CancelledCheque3 = $vehicelid = '';

				if ($res->num_rows > 0) {
					$dl_no = $data_array[3];
					$dl_name = $data_array[4];
					$CancelledCheque1 = $data_array[5];
					$rc_no = $data_array[6];
					$rc_name = $data_array[7];
					$CancelledCheque2 = $data_array[8];
					$CancelledCheque3 = $data_array[9];
					$vehicelid = $data_array[0];
				}
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" name="center_location" id="center_location" value="<?php echo $loc_dir; ?>" />


				<div class="input-field col s6 m6">
					<input type="text" id="txt_dl_no" name="txt_dl_no" maxlength="100" value="<?php echo $dl_no; ?>" required />
					<label for="txt_dl_no">Driving Licence No *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txt_dl_name" name="txt_dl_name" value="<?php echo $dl_name; ?>" required />
					<label for="txt_dl_name">Name As Per Driving Licence *</label>
				</div>


				<div class="input-field col s6 m6">
					<input type="text" id="txt_rc_no" name="txt_rc_no" value="<?php echo $rc_no; ?>" required />
					<label for="txt_rc_no">Registration Certificate(RC) No *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txt_rc_name" name="txt_rc_name" value="<?php echo $rc_name; ?>" required />
					<label for="txt_rc_name">Name As Per Registration Certificate(RC) *</label>
				</div>

				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="File1" id="File1">
						<br>
						<span class="file-size-text help-block" id="fileid">
							<a onclick="javascript:return Download(this);" id="File1image" data="<?php echo $CancelledCheque1; ?>">Driving Licence</a></span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>

				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="File2" id="File2">
						<br>
						<span class="file-size-text help-block" id="fileid">
							<a onclick="javascript:return Download(this);" id="File2image" data="<?php echo $CancelledCheque2; ?>">Registration Certificate(RC)</a></span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>

				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="File3" id="File3">
						<br>
						<span class="file-size-text help-block" id="fileid">
							<a onclick="javascript:return Download(this);" id="File3image" data="<?php echo $CancelledCheque3; ?>">Vehicle Image</a></span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>

				<input type="hidden" name="hiddenFile1" id="hiddenFile1" value="<?php echo $CancelledCheque1; ?>" />
				<input type="hidden" name="hiddenFile2" id="hiddenFile2" value="<?php echo $CancelledCheque2; ?>" />
				<input type="hidden" name="hiddenFile3" id="hiddenFile3" value="<?php echo $CancelledCheque3; ?>" />

				<input type='hidden' id="txt_bank_active" name="txt_bank_active" value="Active">

				<input type="hidden" name="vehicleSelect" id="vehicleSelect" value="<?php echo $vehicelid; ?>" />
				<div class="input-field col s12 m12 right-align">

					<?php
					if ($res->num_rows < 1) {
					?>
						<button type="submit" title="Add Details" name="btn_experice_Add" id="btn_experice_Add" class="btn waves-effect waves-green  ">Add</button> <?php } else { ?>
						<button type="submit" title="Update Details" name="btn_experice_Save" id="btn_experice_Save" class="btn waves-effect waves-green ">Save</button> <?php } ?>

					<div class="hidden modelbackground" id="myDiv"></div>
					<script>
						$(document).ready(function() {
							$('#btn_experice_Save,#btn_experice_Add').on('click', function() {
								var validate = 0;
								var alert_msg = '';
								// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
								$("input,select,textarea").each(function() {
									var spanID = "span" + $(this).attr('id');
									$(this).removeClass('has-error');
									if ($(this).is('select')) {
										$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
									}
									var attr_req = $(this).attr('required');
									if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
										validate = 1;
										$(this).addClass('has-error');
										if ($(this).is('select')) {
											$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
										}
										if ($('#' + spanID).length == 0) {
											$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
										}
										var attr_error = $(this).attr('data-error-msg');
										if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
											$('#' + spanID).html('Required *');
										} else {
											$('#' + spanID).html($(this).attr("data-error-msg"));
										}
									}

								});

								var fileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
								if ($('#File1').val() == '' && $('#hiddenFile1').val() == '') {
									$(function() {
										toastr.error('Please upload driving licence')
									});
									validate = 1;
								} else {
									if ($('#File1').val() != '') {

										if ($.inArray($('#File1').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
											// alert("Image Only formats are allowed : "+fileExtension.join(', '));
											$(function() {
												toastr.error("Image Only formats are allowed : " + fileExtension.join(', '))
											});
											//  $('#File3').css('border-color','red');
											$('#File1').focus();
											return false;
										} else {
											var file_size = $('#File1')[0].files[0].size;
											var calf1 = file_size / 1024;
											if (calf1 >= "2001") {
												$(function() {
													toastr.error("File size is greater than 2MB")
												});
												$('#File1').focus();
												return false;
											}
										}
									}
								}

								if ($('#File2').val() == '' && $('#hiddenFile2').val() == '') {
									$(function() {
										toastr.error('Please upload RC')
									});
									validate = 1;
								} else {
									if ($('#File2').val() != '') {

										if ($.inArray($('#File2').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
											// alert("Image Only formats are allowed : "+fileExtension.join(', '));
											$(function() {
												toastr.error("Image Only formats are allowed : " + fileExtension.join(', '))
											});
											//  $('#File2').css('border-color','red');
											$('#File2').focus();
											return false;
										} else {
											var file_size = $('#File2')[0].files[0].size;
											var calf1 = file_size / 1024;
											if (calf1 >= "2001") {
												$(function() {
													toastr.error("File size is greater than 2MB")
												});
												$('#File2').focus();
												return false;
											}
										}
									}
								}

								if ($('#File3').val() == '' && $('#hiddenFile3').val() == '') {
									$(function() {
										toastr.error('Please upload vehicle cheque')
									});
									validate = 1;
								} else {
									if ($('#File3').val() != '') {

										if ($.inArray($('#File3').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
											// alert("Image Only formats are allowed : "+fileExtension.join(', '));
											$(function() {
												toastr.error("Image Only formats are allowed : " + fileExtension.join(', '))
											});
											//  $('#File3').css('border-color','red');
											$('#File3').focus();
											return false;
										} else {
											var file_size = $('#File3')[0].files[0].size;
											var calf1 = file_size / 1024;
											if (calf1 >= "2001") {
												$(function() {
													toastr.error("File size is greater than 2MB")
												});
												$('#File3').focus();
												return false;
											}
										}
									}
								}

								if (validate == 1) {
									return false;
								}
							});


						});



						function Delete(el) {
							if (confirm('You want to delete This Bank Details ')) {
								$item = $(el);

								$.ajax({
									url: "../Controller/deleteBank.php?ID=" + $item.attr("data"),
									success: function(result) {
										$var = result.split('|');

										if ($var[0] == "Done") {
											$item.closest("tr").remove();
										}
										$('#alert_msg').html($var[1]);
										$('#alert_message').show().attr("class", "SlideInRight animated");
										$('#alert_message').delay(5000).fadeOut("slow");

									}
								});
							}
						}

						function Edit(el) {
							var tr = $(el).closest('tr');
							var BankID = tr.find('.BankID').text();
							var BankName = tr.find('.BankName').text();
							var Location = tr.find('.Location').text();
							var Branch = tr.find('.Branch').text();
							var Active = tr.find('.Active').text();
							var AccountNo = tr.find('.AccountNo').text();
							var IFSC_code = tr.find('.IFSC_code').text();
							var cheque_book = tr.find('.cheque_book').text();
							$('#bankimage').attr('data', cheque_book);
							$('select').formSelect();
							$('#hiddenFile3').val(cheque_book);
							var name_asper_bank = tr.find('.name_asper_bank').text();
							$('#txt_name_asper_bank').val(name_asper_bank);
							$('#txt_bank_ifsc').val(IFSC_code);
							$('#txt_bank_bankname').val(BankName);
							$('select').formSelect();
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$('#txt_bank_location').val(Location);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$('#txt_bank_account').val(AccountNo);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$('#txt_bank_branch').val(Branch);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$('#txt_bank_active').val(Active);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$('#bankSelect').val(BankID);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();


							$('#btn_experice_Add').addClass('hidden');
							$('#btn_experice_Save').removeClass('hidden');
							$('#btn_experice_Can').removeClass('hidden');
						}

						function Download(el) {

							if ($(el).attr("data") != '') {
								var file = $(el).attr("data");
								var filepath = $('#center_location').val();
								//alert(filepath);
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
									url: "../" + filepath + "/" + file,
									//url:"../Docs/VehicleDocs/"+$(el).attr("data"),
									type: 'HEAD',
									error: function() {
										alert('No File Exist');
									},
									success: function() {
										imgcheck = function(filename) {
											return (filename).split('.').pop();
										}
										imgchecker = imgcheck("../" + filepath + "/" + file);

										if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
											getImageDimensions("../" + filepath + "/" + file, function(data) {
												var img = data;

												$('<img>', {
													src: "../" + filepath + "/" + file
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
														link.download = $(el).attr("data");
														document.body.appendChild(link);
														link.click();

													}
												});




											});
										} else if (imgchecker.match(/(pdf)$/i)) {
											window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../" + filepath + "/" + file);
										} else {
											window.open("../" + filepath + "/" + file);
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