<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$alert_msg = '';
$imsrc = URL . 'Style/images/agent-icon.png';
$EmployeeID = $btnShow = '';
$file = $CancelledCheque = '';

//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$txt_bank_bankname = cleanUserInput($_POST['txt_bank_bankname']);
	$bankName = (isset($txt_bank_bankname) ? $txt_bank_bankname : null);
	$txt_bank_location = cleanUserInput($_POST['txt_bank_location']);
	$location = (isset($txt_bank_location) ? $txt_bank_location : null);
	$txt_bank_account = cleanUserInput($_POST['txt_bank_account']);
	$account = (isset($txt_bank_account) ? $txt_bank_account : null);
	$txt_bank_branch = cleanUserInput($_POST['txt_bank_branch']);
	$branch = (isset($txt_bank_branch) ? $txt_bank_branch : null);
	$txt_bank_active = cleanUserInput($_POST['txt_bank_active']);
	$active = (isset($txt_bank_active) ? $txt_bank_active : null);
	$txt_name_asper_banks = cleanUserInput($_POST['txt_name_asper_bank']);
	$txt_name_asper_bank = (isset($txt_name_asper_banks) ? $txt_name_asper_banks : null);
	$txt_bank_ifscs = cleanUserInput($_POST['txt_bank_ifsc']);
	$txt_bank_ifsc = (isset($txt_bank_ifscs) ? $txt_bank_ifscs : null);
	$target_dir = ROOT_PATH . 'Docs/BankDocs/';;
	if (isset($_FILES["File3"]["name"]) and $_FILES["File3"]["name"] != "") {
		$target_file3 = $target_dir . basename($_FILES["File3"]["name"]);
		$FileType = pathinfo($target_file3, PATHINFO_EXTENSION);
		$CancelledCheque = $_POST['EmployeeID'] . '_CancelledCheque.' . $FileType;
		if (move_uploaded_file($_FILES["File3"]["tmp_name"], $target_file3)) {
			$file = rename($target_file3, $target_dir . $CancelledCheque);
		}
	} else
		if (isset($_POST['hiddenFile3']) and $_POST['hiddenFile3'] != "") {
		$CancelledCheque = cleanUserInput($_POST['hiddenFile3']);
	}
} else {
	$branch = $active = $bankName = $location = $account = $txt_name_asper_bank = '';
}

$user_logid = clean($_SESSION['__user_logid']);

if (isset($user_logid)) {
	$EmployeeID = $user_logid;
}

if (isset($_POST['btn_experice_Add']) && $EmployeeID != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		$createBy = $user_logid;
		// $count_array = $myDB->query("select EmployeeID from bank_details_temp where hr_status='Pending' and EmployeeID='" . $EmployeeID . "'");
		$count_arrayQry = "select EmployeeID from bank_details_temp where hr_status='Pending' and EmployeeID=?";
		$stmt = $conn->prepare($count_arrayQry);
		$stmt->bind_param("s", $EmployeeID);
		$stmt->execute();
		$count_array = $stmt->get_result();
		if ($count_array->num_rows < 1) {
			$myDB = new MysqliDb();
			$sqlInsertBank = 'call add_bankdetails_temp("' . $EmployeeID . '","' . $bankName . '","' . $location . '","' . $account . '","' . $branch . '","' . $active . '","' . $createBy . '","' . $txt_bank_ifsc . '","' . $txt_name_asper_bank . '","' . $CancelledCheque . '","")';
			$result = $myDB->query($sqlInsertBank);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Bank Details is added Successfully') });</script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Your request is already submitted') });</script>";
		}
	}
}

if (isset($_POST['btn_experice_Save']) && $_POST['bankSelect'] != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		$myDB = new MysqliDb();
		$createBy = $user_logid;
		$bank_id = cleanUserInput($_POST['bankSelect']);

		$sqlInsertDoc = 'call save_bankdetails_temp("' . $bank_id . '","' . $EmployeeID . '","' . $bankName . '","' . $location . '","' . $account . '","' . $branch . '","' . $active . '","' . $createBy . '","' . $txt_bank_ifsc . '","' . $txt_name_asper_bank . '","' . $CancelledCheque . '")';
		$result = $myDB->query($sqlInsertDoc);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Bank Details is Saved Successfully') });</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data Not Addedd " . $mysql_error . "') });</script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {

		$('#txt_bank_account').keydown(function(event) {
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
		<?php // include('shortcutLinkEmpProfile.php'); 
		?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Bank Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				// $sqlBankQuery = "select * from bank_details where EmployeeID='" . $EmployeeID . "' ";
				$sqlBankQuery = "select * from bank_details where EmployeeID=? ";
				$stmt = $conn->prepare($sqlBankQuery);
				$stmt->bind_param("s", $EmployeeID);
				$stmt->execute();
				$data_array = $stmt->get_result();
				$data_arrayRow = $data_array->fetch_row();
				// $myDB = new MysqliDb();
				// $data_array = $myDB->query($sqlBankQuery);
				$bankname = '';
				$location = '';
				$accountnum = '';
				$branch = '';
				$nameAsperBank = '';
				$ifscCode = '';
				$cheque_book = '';
				$txt_Comment = '';
				if ($data_array->num_rows > 0) {
					$bankname = clean($data_arrayRow[4]); //['BankName'];
					$location = clean($data_arrayRow[7]); //['Location'];
					$accountnum = clean($data_arrayRow[5]); //['AccountNo'];
					$branch = clean($data_arrayRow[6]); //['Branch'];
					$nameAsperBank = clean($data_arrayRow[10]); //['name_asper_bank'];
					$ifscCode = clean($data_arrayRow[9]); //['IFSC_code'];
					$cheque_book = clean($data_arrayRow[11]); //['cheque_book'];
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />

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
								if ($bankname == $val['BankName']) {
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

				<div class="input-field col s6 m6">
					<select id="txt_bank_active" name="txt_bank_active" required>
						<option value="Active" selected="selected">Active</option>
					</select>
					<label for="txt_bank_active" class="active-drop-down active">Active *</label>
				</div>
				<!-- <div class="input-field col s6 m6">
					<select id="txt_hr_status" name="txt_hr_status" required>
						<option value="Pending">Pending</option>
						<option value="Selected">Selected</option>
						<option value="Rejected">Rejected</option>
					</select>
					<label for="txt_hr_status" class="active-drop-down active">Active *</label>
			    </div>-->
				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>File</span>
						<input type="file" name="File3" id="File3">
						<br>
						<span class="file-size-text help-block" id="fileid"><a onclick="javascript:return Download(this);" id="bankimage" data="<?php echo $cheque_book; ?>">Download Cancelled Cheque</a></span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<div id="comment_div" class="hidden input-field col s6 m6 ">

					<textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment" maxlength="255" readonly></textarea>
					<label for="txt_Comment">Comment</label>

				</div>
				<input type="hidden" name="hiddenFile3" id="hiddenFile3" value="<?php echo $cheque_book; ?>" />
				<input type="hidden" name="bankSelect" id="bankSelect" />
				<div class="input-field col s12 m12 right-align">
					<button type="submit" title="Update Details" name="btn_experice_Save" id="btn_experice_Save" class="btn waves-effect waves-green  hidden">Save</button>
					<?php
					//if (count($result)<1) {
					?>
					<button type="submit" title="Add Details" name="btn_experice_Add" id="btn_experice_Add" class="btn waves-effect waves-green  ">Add</button> <?php // } 
																																								?>
					<!--	 <button type="submit" title="Cancle Details" name="btn_experice_Can" id="btn_experice_Can" class="btn waves-effect modal-action modal-close waves-red close-btn  hidden">Cancle</button>-->
				</div>
				<div class="pnlTable">
					<?php
					$sqlConnect = "select * from bank_details_temp where EmployeeID='" . $EmployeeID . "' order by bank_id desc ";
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>
						<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="hidden">bank_id</th>
											<th>EmployeeID</th>
											<th>BankName</th>
											<th>Branch</th>
											<th>AccountNo</th>
											<th>IFSC Code</th>
											<th>Name asper Bank</th>
											<th>Location</th>
											<th>Active</th>
											<th>HR Status</th>
											<th class='hidden'>HR Comment</th>
											<th style="width:100px;">Manage Doc </th>
											<th class='hidden'>cheque_book</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($result as $key => $value) {
											echo '<tr>';
											echo '<td class="BankID hidden">' . $value['bank_id'] . '</td>';
											echo '<td class="EmployeeID">' . $value['EmployeeID'] . '</td>';
											echo '<td class="BankName">' . $value['BankName'] . '</td>';
											echo '<td class="Branch">' . $value['Branch'] . '</td>';
											echo '<td class="AccountNo">' . $value['AccountNo'] . '</td>';
											echo '<td class="IFSC_code">' . $value['IFSC_code'] . '</td>';
											echo '<td class="name_asper_bank">' . $value['name_asper_bank'] . '</td>';
											echo '<td class="Location">' . $value['Location'] . '</td>';
											echo '<td class="Active">' . $value['Active'] . '</td>';
											echo '<td class="hr_status">' . $value['hr_status'] . '</td>';
											echo '<td class="hr_comment hidden ">' . $value['hr_comment'] . '</td>';
											echo '<td>';
											if ($value['hr_status'] == 'Pending') {
												echo '<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return Edit(this);" data="' . $value['bank_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i>';
											} else {
												echo '<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped"  data="' . $value['bank_id'] . '"   data-position="left" data-tooltip="can\'t Edit">ohrm_edit</i>';
											}
											echo '</td>';
											echo '<td class="hidden cheque_book" >' . $value['cheque_book'] . '</td>';
											echo '</tr>';
										}
										?>

										<!--			<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return Delete(this);" data="'.$value['bank_id'].'" data-position="left" data-tooltip="Delete">ohrm_delete</i>	-->
									</tbody>
								</table>
							</div>
						</div>
					<?php
					}
					?>
				</div>





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
							})
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
					/*function Delete(el)
					{
						if(confirm('You want to delete This Bank Details '))
								{
									$item=$(el);
									
									$.ajax({url: "../Controller/deleteBank.php?ID="+$item.attr("data"), success: function(result){
					                    $var=result.split('|');
					                    
					                    if($var[0]=="Done")
					                    {
					                    	$item.closest("tr").remove();
										}
					                    $('#alert_msg').html($var[1]);
							      		$('#alert_message').show().attr("class","SlideInRight animated");
							      		$('#alert_message').delay(5000).fadeOut("slow");                        
					                                                        
					                }});
								}
					}*/
					function Edit(el) {
						$('#comment_div').removeClass('hidden');
						var tr = $(el).closest('tr');
						var BankID = tr.find('.BankID').text();
						var BankName = tr.find('.BankName').text();
						var Location = tr.find('.Location').text();
						var Branch = tr.find('.Branch').text();
						var Active = tr.find('.Active').text();
						var AccountNo = tr.find('.AccountNo').text();
						var IFSC_code = tr.find('.IFSC_code').text();
						var cheque_book = tr.find('.cheque_book').text();
						var hr_comment = tr.find('.hr_comment').text();
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
						$('#txt_Comment').val(hr_comment);
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
						$('select').formSelect();
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
								url: "../Docs/BankDocs/" + $(el).attr("data"),
								type: 'HEAD',
								error: function() {
									alert('No File Exist');
								},
								success: function() {
									imgcheck = function(filename) {
										return (filename).split('.').pop();
									}
									imgchecker = imgcheck("../Docs/BankDocs/" + $(el).attr("data"));

									if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
										getImageDimensions("../Docs/BankDocs/" + $(el).attr("data"), function(data) {
											var img = data;

											$('<img>', {
												src: "../Docs/BankDocs/" + $(el).attr("data")
											}).watermark({
												//text: 'ⓒ For Cogent E Services Pvt. Ltd.',
												text: 'Cogent E Services Pvt. Ltd.',
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
										window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../Docs/BankDocs/" + $(el).attr("data"));
									} else {
										window.open("../Docs/BankDocs/" + $(el).attr("data"));
									}

								}
							});

							/*$('.schema-form-section img').watermark({
					    
				  	});*/

						} else {
							alert('No File Exist');
						}
					}

					function isNumber(evt) {
						evt = (evt) ? evt : window.event;
						var charCode = (evt.which) ? evt.which : evt.keyCode;
						if (charCode > 31 && (charCode < 48 || charCode > 57)) {
							return false;
						}
						return true;
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