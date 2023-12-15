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
$file = $CancelledCheque = $loc = $loc_dir = '';
$getDetails = 'call get_personal("' . $_REQUEST['empid'] . '")';
$myDB = new MysqliDb();
$result_all = $myDB->query($getDetails);
if ($result_all) {
	$loc = $result_all[0]['location'];

	if ($loc == "1" || $loc == "2") {
		$loc_dir = 'Docs/BankDocs/';
	} else if ($loc == "3") {
		$loc_dir = 'Meerut/Docs/BankDocs/';
	} else if ($loc == "4") {
		$loc_dir = 'Bareilly/Docs/BankDocs/';
	} else if ($loc == "5") {
		$loc_dir = 'Vadodara/Docs/BankDocs/';
	} else if ($loc == "6") {
		$loc_dir = 'Manglore/Docs/BankDocs/';
	} else if ($loc == "7") {
		$loc_dir = 'Bangalore/Docs/BankDocs/';
	} else if ($loc == "8") {
		$loc_dir = 'Nashik/Docs/BankDocs/';
	} else if ($loc == "9") {
		$loc_dir = 'Anantapur/Docs/BankDocs/';
	} else if ($loc == "10") {
		$loc_dir = 'Gurgaon/Docs/BankDocs/';
	} else if ($loc == "11") {
		$loc_dir = 'Hyderabad/Docs/BankDocs/';
	}
}
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$bankName = (isset($_POST['txt_bank_bankname']) ? $_POST['txt_bank_bankname'] : null);
	$location = (isset($_POST['txt_bank_location']) ? $_POST['txt_bank_location'] : null);
	$account = (isset($_POST['txt_bank_account']) ? $_POST['txt_bank_account'] : null);
	$branch = (isset($_POST['txt_bank_branch']) ? $_POST['txt_bank_branch'] : null);
	$active = (isset($_POST['txt_bank_active']) ? $_POST['txt_bank_active'] : null);
	$txt_name_asper_bank = (isset($_POST['txt_name_asper_bank']) ? $_POST['txt_name_asper_bank'] : null);
	$txt_bank_ifsc = (isset($_POST['txt_bank_ifsc']) ? $_POST['txt_bank_ifsc'] : null);
	$dir_location = $_POST['center_location'];

	$target_dir = ROOT_PATH . $dir_location;



	if (isset($_FILES["File3"]["name"]) and $_FILES["File3"]["name"] != "") {
		$target_file3 = $target_dir . basename($_FILES["File3"]["name"]);
		$FileType = pathinfo($target_file3, PATHINFO_EXTENSION);
		$CancelledCheque = $_POST['EmployeeID'] . '_CancelledCheque.' . $FileType;
		if (move_uploaded_file($_FILES["File3"]["tmp_name"], $target_file3)) {
			$file = rename($target_file3, $target_dir . $CancelledCheque);
		}
	} else
		if (isset($_POST['hiddenFile3']) and $_POST['hiddenFile3'] != "") {
		$CancelledCheque = $_POST['hiddenFile3'];
	}
} else {
	$branch = $active = $bankName = $location = $account = $txt_name_asper_bank = '';
}
//Check Employee is exist or not
if (isset($_REQUEST['empid']) && $EmployeeID == '' && !isset($_POST['txt_bank_account'])) {
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

if (isset($_POST['btn_experice_Add']) && $EmployeeID != '') {
	$myDB = new MysqliDb();
	$createBy = $_SESSION['__user_logid'];
	$selectBank = $myDB->query("select EmployeeID from bank_details where EmployeeID='" . $EmployeeID . "' ");
	if (count($selectBank) < 1) {

		$myDB = new MysqliDb();
		$sqlInsertBank = 'call add_bankdetails("' . $EmployeeID . '","' . $bankName . '","' . $location . '","' . $account . '","' . $branch . '","' . $active . '","' . $createBy . '","' . $txt_bank_ifsc . '","' . $txt_name_asper_bank . '","' . $CancelledCheque . '")';
		$result = $myDB->query($sqlInsertBank);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Bank Details is added Successfully') });</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
		}
	} else {
		//echo "<script>$(function(){ toastr.error('Data Addedd') });</script>";
	}
}

if (isset($_POST['btn_experice_Save']) && $_POST['bankSelect'] != '') {
	$myDB = new MysqliDb();
	$createBy = $_SESSION['__user_logid'];
	$bank_id = $_POST['bankSelect'];

	$sqlInsertDoc = 'call save_bankdetails("' . $bank_id . '","' . $EmployeeID . '","' . $bankName . '","' . $location . '","' . $account . '","' . $branch . '","' . $active . '","' . $createBy . '","' . $txt_bank_ifsc . '","' . $txt_name_asper_bank . '","' . $CancelledCheque . '")';

	$result = $myDB->query($sqlInsertDoc);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		echo "<script>$(function(){ toastr.success('Bank Details is Saved Successfully') });</script>";
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
		$('#txt_bank_account,#txt_contatc_altmob').keydown(function(event) {
			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

				// Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) ||

				// Allow: Ctrl+V
				(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

				// Allow: Ctrl+c
				(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

				// Allow: Ctrl+x
				(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

				// Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			} else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
					event.preventDefault();
				}
			}
		});

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Bank Details</span>
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Bank Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$sqlBankQuery = "select * from bank_details where EmployeeID='" . $EmployeeID . "' ";
				$myDB = new MysqliDb();
				$data_array = $myDB->query($sqlBankQuery);
				$bankname = '';
				$location = '';
				$accountnum = '';
				$branch = '';
				$nameAsperBank = '';
				$ifscCode = '';
				$cheque_book = '';
				$txt_Comment = '';
				$bankid = '';
				if (count($data_array) > 0) {
					$bankname = $data_array[0]['BankName'];
					$location = $data_array[0]['Location'];
					$accountnum = $data_array[0]['AccountNo'];
					$branch = $data_array[0]['Branch'];
					$nameAsperBank = $data_array[0]['name_asper_bank'];
					$ifscCode = $data_array[0]['IFSC_code'];
					$cheque_book = $data_array[0]['cheque_book'];
					$bankid = $data_array[0]['bank_id'];
				}
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" name="center_location" id="center_location" value="<?php echo $loc_dir; ?>" />

				<?php
				$sqlConnect = "select * from bank_master ";
				$myDB = new MysqliDb();
				$bankresult = $myDB->query($sqlConnect);

				?>
				<div class="input-field col s6 m6">
					<select id="txt_bank_bankname" name="txt_bank_bankname" required>
						<option value="NA">---Select---</option>
						<?php if ($bankresult) {
							foreach ($bankresult as $val) {
								echo "<option value='" . $val['BankName'] . "' ";
								if (strtoupper(trim($bankname)) == strtoupper(trim($val['BankName']))) {
									echo "selected";
								}
								echo ">" . $val['BankName'] . "</option>";
							}
						} ?>
					</select>
					<label for="txt_bank_bankname" class="active-drop-down active">Bank Name *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txt_bank_location" name="txt_bank_location" maxlength="100" value="<?php echo $location; ?>" required />
					<label for="txt_bank_location">Location *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txt_bank_account" name="txt_bank_account" maxlength="16" value="<?php echo $accountnum; ?>" required />
					<label for="txt_bank_account">Account No *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txt_bank_branch" name="txt_bank_branch" maxlength="100" value="<?php echo $branch; ?>" required />
					<label for="txt_bank_branch">Branch *</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" id="txt_name_asper_bank" name="txt_name_asper_bank" maxlength="100" value="<?php echo $nameAsperBank; ?>" required />
					<label for="txt_name_asper_bank">Name asper Bank *</label>
				</div>
				<div class="input-field col s6 m6">
					<input type="text" id="txt_bank_ifsc" name="txt_bank_ifsc" maxlength="11" value="<?php echo $ifscCode; ?>" required />
					<label for="txt_bank_ifsc">IFSC Code *</label>
				</div>

				<input type='hidden' id="txt_bank_active" name="txt_bank_active" value="Active">
				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="File3" id="File3">
						<br>
						<span class="file-size-text help-block" id="fileid">
							<a onclick="javascript:return Download(this);" id="bankimage" data="<?php echo $cheque_book; ?>">Cancelled Cheque Image.</a></span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<input type="hidden" name="hiddenFile3" id="hiddenFile3" value="<?php echo $cheque_book; ?>" />
				<input type="hidden" name="bankSelect" id="bankSelect" value="<?php echo $bankid; ?>" />
				<div class="input-field col s12 m12 right-align">

					<?php
					if (count($data_array) < 1) {
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
										if ($('#' + spanID).size() == 0) {
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
								var bank_account = $('#txt_bank_account').val();
								var accnum = /^\d{10,16}$/;
								if (bank_account != '') {
									if (!bank_account.match(accnum)) {

										validate = 1;
										$(function() {
											toastr.error('Please enter 10 to 16 digit for Account Number ')
										});
										$('#txt_bank_account').focus();

										return false;
									}
								}
								if ($('#txt_bank_ifsc').val().length < 11) {

									$(function() {
										toastr.error('Enter 11 alphanumeric value for IFSC Code')
									});
									return false;
								} else {
									var regex = /^[A-Za-z]{4}0[A-Z0-9a-z]{6}$/;
									var bank_ifsc = $('#txt_bank_ifsc').val();
									if (regex.test(bank_ifsc)) {
										$('#txt_bank_ifsc').css('border-color', '');

									} else {
										$(function() {
											toastr.error('IFSC Code value in not correct')
										});
										return false;
									}
								}

								var fileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
								if ($('#File3').val() == '' && $('#hiddenFile3').val() == '') {
									$(function() {
										toastr.error('Please upload cancel cheque')
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

						$('#txt_name_asper_bank').keydown(function(e) {
							if (e.shiftKey || e.ctrlKey || e.altKey) {
								e.preventDefault();
							} else {
								var key = e.keyCode;
								if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
									e.preventDefault();
								}

							}
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
									//url:"../Docs/BankDocs/"+$(el).attr("data"),
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