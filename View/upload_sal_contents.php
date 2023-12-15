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
		}
		if (!($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE12102224')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$msgFile = $mysql_error = '';
$insert_row = $btnUploadCheck = $count = 0;
function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Payroll Adjustment</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Payroll Adjustment<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Payroll"><i class="material-icons">add</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<a href="../FileContainer/upload_format_Incentive.xlsx" target="_blank" class="btn btn-default btn-danger">
					<i class="fa fa-download"></i> Upload Format Incentive</a>

				<a href="../FileContainer/upload_format_Deduction.xlsx" target="_blank" class="btn btn-default btn-warning">
					<i class="fa fa-download"></i> Upload Format Deduction</a>

				<a href="../FileContainer/upload_status_payroll_fl_Status.xlsx" target="_blank" class="btn btn-default btn-primary">
					<i class="fa fa-download"></i> Upload Format Final Status</a>
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Announcement Master</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="input-field col s6 m6">
								<select id="txt_Type_Upload" name="txt_Type_Upload" required>
									<option value="NA">---Select---</option>
									<option>Incentive</option>
									<option>Deduction</option>
									<option>Status</option>
								</select>
								<label for="txt_Type_Upload" class="active-drop-down active">Upload For :</label>
							</div>

							<div class="file-field input-field col s6 m6">
								<div class="btn"><span>Upload File</span>
									<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
									<br>
									<span class="file-size-text">Accepts up to 2MB</span>

								</div>
								<div class="file-path-wrapper">
									<input class="file-path" type="text" style="">
								</div>

							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" class="btn waves-effect waves-green" />
								<input type="button" value="Upload Again" name="UploadAgain" id="UploadAgain" class="btn waves-effect waves-green hidden" />
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>


							<?php
							if (isset($_POST['UploadBtn'])) {
								$btnUploadCheck = 1;
								$target_dir = ROOT_PATH . 'Upload/';
								$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
								$uploadOk = 1;
								$uploader = $_SESSION['__user_logid'];
								$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
								// Check if file already exists
								/*if (file_exists($target_file)) {
				    $msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
				    $uploadOk = 0;
				}*/
								// Check file size
								if ($_FILES["fileToUpload"]["size"] > 5000000) {
									echo "<script>$(function(){ toastr.error('Sorry, your file is too large of Size " . $_FILES["fileToUpload"]["size"] . "'); }); </script>";
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
										echo "<script>$(function(){ toastr.success('The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. '); }); </script>";
										$document = PHPExcel_IOFactory::load($target_file);
										// Get the active sheet as an array
										$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
										//print_r($activeSheetData.'<br/>');
										echo "<script>$(function(){ toastr.success('Rows available In Sheet : <code>" . (count($activeSheetData) - 1) . "</code>'); }); </script>";
										$row_counter = 0;
										$flag = 0;
										$row_counter = 0;
										if (count($activeSheetData) > 0 && $activeSheetData) {
											foreach ($activeSheetData as $row) {
												if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
													$sql_insert = '';
													if ($_POST['txt_Type_Upload'] === 'Incentive') {
														$sql_insert =  'INSERT INTO payroll_incentive_upld(EmployeeID,month,year,ClientIncentive,Arrears,OtherIncentive,OtherNarration,createdby,ExtraPayDay,Ref_Inct,OT_Inct,PLI,split_inc,shift_inc,ExtraTPayDay)VALUES("' . $row['A'] . '","' . $row['B'] . '","' . $row['C'] . '","' . $row['D'] . '","' . $row['E'] . '","' . $row['F'] . '","' . $row['G'] . '","' . $_SESSION['__user_logid'] . '","' . $row['H'] . '","' . $row['I'] . '","' . $row['J'] . '","' . $row['K'] . '","' . $row['L'] . '","' . $row['M'] . '","' . $row['N'] . '")';
													} elseif ($_POST['txt_Type_Upload'] === 'Deduction') {
														$sql_insert =  'INSERT INTO payroll_deduction_upld(EmployeeID,month,year,AssetDamage,Id_Access_Card_Damage,Guest_House_Rent,TDS,OtherLess,OtherNarration,createdby,NoticeRecovery)VALUES("' . $row['A'] . '","' . $row['B'] . '","' . $row['C'] . '","' . $row['D'] . '","' . $row['E'] . '","' . $row['F'] . '","' . $row['G'] . '","' . $row['H'] . '","' . $row['I'] . '","' . $_SESSION['__user_logid'] . '","' . $row['J'] . '")';
													} elseif ($_POST['txt_Type_Upload'] === 'Status') {
														$sql_insert =  'INSERT INTO payroll_final_sl_status (EmployeeID,month,year,SalaryStatus,Remarks,createdby)VALUES("' . $row['A'] . '","' . $row['B'] . '","' . $row['C'] . '","' . $row['D'] . '","' . mysql_real_escape_string($row['E']) . '","' . $_SESSION['__user_logid'] . '")';
													}
													if (!empty($sql_insert)) {
														$myDB = new MysqliDb();

														$result = $myDB->rawQuery($sql_insert);
														$mysql_error = $myDB->getLastError();
														$rowCount = $myDB->count;
														if (empty($mysql_error)) {
															$count++;
														}
													}
												}
												$row_counter++;
											}
										}
										if ($count > 0)
											echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.'); }); </script>";
										else
											echo "<script>$(function(){ toastr.success('No Data Updated '.$mysql_error) }); </script>";

										if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
											$ext = pathinfo($target_file, PATHINFO_EXTENSION);
											rename($target_file, $target_dir . time() . '_' . $uploader . "_PayrollNarration_" . $_POST['txt_Type_Upload'] . "." . $ext);
										}
									} else {

										echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
									}
								}
							}
							?>


						</div>
					</div>
				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(function() {

		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
			}
		});

		// This code for remove error span from all element contain .has-error class on listed events
		$(document).on("click blur focus change", ".has-error", function() {
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
				}
			});
		});

		// This code for cancel button trigger click and also for model close
		$('#btn_Can').on('click', function() {

			$('#txt_Type_Upload').val('NA');
			$('#fileToUpload').val('');
			// This code for remove error span from input text on model close and cancel
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}

				}
			});
			// This code active label on value assign when any event trigger and value assign by javascript code.

			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();

		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#UploadBtn').on('click', function() {
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
			})

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}

		});



		$('#UploadAgain').click(function() {
			$('.pannel_upload').removeClass('hidden');
			$('#UploadAgain').addClass('hidden');
			$('#txt_Type_Upload').val('NA');
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