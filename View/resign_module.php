<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
if (!empty($_POST['txt_search_empid'])) {
	(isset($_POST['txt_search_empid'])) ? $EmployeeID = $_POST['txt_search_empid'] : $EmployeeID = '';
}
if (isset($_POST['txt_search_empid'])) {

	$txt_empid = $_POST['txt_empid'];
	$nt_start  = $_POST['txt_nt_start'];
	$nt_end  = $_POST['txt_nt_end'];
	$remark = $_POST['txt_search_remark'];
	$filename = '';

	$myDB = new MysqliDb();
	$srtddo = $myDB->rawQuery('select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where rg_status > 0 and rg_status < 9 and employee_map.emp_status = "Active" and resign_details.EmployeeID = "' . $txt_empid . '" and final_acceptance is null;');
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;
	if ($rowCount > 0 && $srtddo) {
		echo "<script>$(function(){ toastr.error('Resign request already in queue. System can\'t submited this request.'); }); </script>";
	} else {
		$myDB = new MysqliDb();
		$srtddo = $myDB->rawQuery('select resign_details.EmployeeID from resign_details inner join employee_map on employee_map.EmployeeID = resign_details.EmployeeID where employee_map.emp_status = "Active" and rg_status >= 9 and resign_details.EmployeeID = "' . $txt_empid . '" and last_day(resign_details.modifiedon) >= date_sub(date_format(NOW() ,\'%Y-%m-01\'), interval 3 month) and resign_details.modifiedon is not null');
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if ($rowCount > 0) {
			echo "<script>$(function(){ toastr.error('Resign request should be older than 3 months. System can\'t submited this request'); }); </script>";
		} else {

			if (isset($_FILES["fileToUpload"]) && !empty($_FILES["fileToUpload"]["name"])) {

				$target_dir = ROOT_PATH . 'Resign/';
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$filename = "";
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$ext = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
					$filename = $EmployeeID . '_' . time() . '.' . $ext;
					$file = rename($target_file, $target_dir . '' . $filename);
				}
				if (file_exists(ROOT_PATH . 'Resign/' . $filename) && $filename != '') {
					$date1 = date('Y-m-d');
					$date2 = $nt_start;
					$diff = abs(strtotime($date2) - strtotime($date1));
					$years = floor($diff / (365 * 60 * 60 * 24));
					$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
					$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));


					if ($years == 0 && $months == 0) {
						if ($days == 0 || $days == 1 || $days == 2 || $days == 3) {
							$sql = 'call manage_resign("' . $txt_empid . '","' . $remark . '","' . $filename . '","0","' . $nt_start . '","' . $nt_end . '","' . $_SESSION['__user_logid'] . '")';
							$myDB = new MysqliDb();
							$rstl = $myDB->rawQuery($sql);
							$mError = $myDB->getLastError();
							$rowCount = $myDB->count;
							if ($rowCount > 0) {
								echo "<script>$(function(){ toastr.success('Resign request submited successfully.'); }); </script>";
							} else {
								echo "<script>$(function(){ toastr.error('Resign request not saved." . $mError . "'); }); </script>";
							}
						} else {
							echo "<script>$(function(){ toastr.error('Start date is incorrect.'); }); </script>";
						}
					}
				} else {
					echo "<script>$(function(){ toastr.error('File not uploaded  [ file size should not be greater than 2 MB ],try again.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('File not selected.'); }); </script>";
			}
		}
	}
}
?>
<script>
	$(document).ready(function() {
		$('#txt_nt_start').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '-1970/01/04',
			maxDate: '+1970/01/01',
			onChangeDateTime: function(dp, $input) {

				$('#txt_nt_end').val(geFutureDate(dp, 30));
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}

				});
			}
		});

		function geFutureDate(date_input, no_of_days) {
			var from_date = new Date(date_input);
			var time_after_7_days = new Date(from_date).setDate(from_date.getDate() + no_of_days);
			return new Date(time_after_7_days).toISOString().substring(0, 10);

		}
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee transfer to HR - Resigned </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee transfer to HR - Resigned</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<input type="hidden" id="txt_empid" name="txt_empid" />
				<div class="input-field col s10 m10">
					<input type="text" id="txt_search_empid" name="txt_search_empid" />
					<label for="txt_search_empid"> Employee ID</label>
				</div>
				<div class="input-field col s2 m2 right-align">
					<button type="button" name="btn_search" id="btn_search" class="btn waves-effect waves-green">Search</button>
				</div>

				<div id="div_Employee_details" class="col s12 m12"></div>

				<div class="input-field col s6 m6  hidden" id="div_nt_start">
					<input type="text" id="txt_nt_start" name="txt_nt_start" readonly="true" />
					<label for="txt_nt_start"> Notice Start </label>
				</div>

				<div class="input-field col s6 m6 hidden" id="div_nt_end">
					<input type="text" id="txt_nt_end" name="txt_nt_end" readonly="true" />
					<label for="txt_nt_end"> Notice End </label>
				</div>
				<div class="input-field col s12 m12 hidden" id="div_remak">
					<textarea id="txt_search_remark" name="txt_search_remark" class="materialize-textarea"></textarea>
					<label for="txt_search_remark"> Remark </label>
				</div>

				<div class="file-field input-field col  s12 m12 l12 hidden " id="div_file">
					<div class="btn"><span>Upload File</span>
						<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
						<br>
						<span class="file-size-text">Accepts up to 2MB</span>
					</div>
				</div>
				<div class="input-field col s12 m12 right-align">
					<button type="submit" id="btn_save" name="btn_save" class="btn waves-effect waves-green hidden ">Submit</button>
				</div>


			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		$('#btn_search').click(function() {
			$('#txt_empid').val($('#txt_search_empid').val());
			$.ajax({
				url: "../Controller/getEmployeebyID.php?ID=" + $('#txt_search_empid').val(),
				success: function(result) {
					if (result.trim() == 'Asset') {
						toastr.error('Employee assigned asset')

						$('#div_remak').addClass('hidden');
						$('#div_file').addClass('hidden');
						$('#btn_save').addClass('hidden');

						$('#div_nt_start').addClass('hidden');
						$('#div_nt_end').addClass('hidden');


					} else if (result.trim() == 'nodata') {
						toastr.error('No Employee found by this EmployeeID ,try agian.')
						//$('#div_Employee_details').html('<span class="text-danger">No Employee found by this EmployeeID ,try agian.</span>');
						$('#div_remak').addClass('hidden');
						$('#div_file').addClass('hidden');
						$('#btn_save').addClass('hidden');

						$('#div_nt_start').addClass('hidden');
						$('#div_nt_end').addClass('hidden');


					} else {
						$('#div_Employee_details').html(result);
						$('#div_remak').removeClass('hidden');
						$('#div_file').removeClass('hidden');
						$('#btn_save').removeClass('hidden');

						$('#div_nt_start').removeClass('hidden');
						$('#div_nt_end').removeClass('hidden');
					}
					$('#txt_nt_start,#txt_nt_end,#txt_search_remark').val('');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}

					});
				}
			});
		});
		$('input[type=file]').change(function(e) {
			/* $in=$(this);
			 if($in.val() != '')
			 {
			 	$('#div_file label').html($in.val());
			 		
			 }
			 else
			 {
			 	$('#div_file label').html('<i class="fa fa-upload"></i>  Choose File :');
			 	
			 }*/
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
		});
		$('#btn_save').click(function() {
			var validate = 0;
			var validate = 0;
			var alert_msg = '';
			if ($('#txt_empid').val() == '' || $('#txt_empid').val() == 'NA') {
				$('#txt_empid').addClass('has-error');
				validate = 1;
				//alert_msg+='<li> Employee ID Should not be empty  </li>';
				if ($('#span_txt_empid').size() == 0) {
					$('<span id="span_txt_empid" class="help-block"> Employee ID Should not be empty</span>').insertAfter('#txt_empid');
				}
			}
			if ($('#txt_nt_start').val() == '' || $('#txt_nt_start').val() == 'NA') {
				$('#txt_nt_start').addClass('has-error');
				validate = 1;
				//alert_msg+='<li> Notice Start Date Should not be empty  </li>';
				if ($('#span_txt_nt_start').size() == 0) {
					$('<span id="span_txt_nt_start" class="help-block"> Employee ID Should not be empty</span>').insertAfter('#txt_nt_start');
				}
			}
			if ($('#txt_nt_end').val() == '' || $('#txt_nt_end').val() == 'NA') {
				$('#txt_nt_end').addClass('has-error');
				validate = 1;
				//alert_msg+='<li> Notice End Date Should not be empty  </li>';
				if ($('#span_txt_nt_endt').size() == 0) {
					$('<span id="span_txt_nt_end" class="help-block"> Employee ID Should not be empty</span>').insertAfter('#txt_nt_end');
				}
			}
			if ($('#txt_search_remark').val() == '' || $('#txt_search_remark').val() == 'NA') {
				$('#txt_search_remark').addClass('has-error');
				validate = 1;
				//alert_msg+='<li> Remarks Should not be empty  </li>';
				if ($('#span_txt_search_remark').size() == 0) {
					$('<span id="span_txt_search_remark" class="help-block"> Employee ID Should not be empty</span>').insertAfter('#txt_search_remark');
				}
			}
			if ($('#fileToUpload').val() == '' || $('#fileToUpload').val() == 'NA') {

				validate = 1;
				alert_msg += '<li> Upload file Should not be empty  </li>';
				/*if($('#span_fileToUpload').size() == 0)
				{
					$('<span id="span_fileToUpload" class="help-block">  Upload file Should not be empty </span>').insertAfter('#fileToUpload');
				}*/
			}


			if (validate == 1) {
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg)
					});
				}

				return false;
			} else {
				if (confirm("Are you sure to generate this request")) {
					return true;
				} else {
					return false;
				}
			}
		});

	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>