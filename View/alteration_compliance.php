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
$btnUploadCheck = 0;
$count = 0;
$mysql_error = '';
function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}

if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}
// Global variable used in Page Cycle
$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";
$rt_type = 1;

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btnSave'])) {
	//print_r($_POST);
	$createBy = $_SESSION['__user_logid'];
	if (isset($_POST['change_report']) && $_POST['change_report'] == 'ch' && $_POST['emptype'] == 's') {
		$myDB = new MysqliDb();
		$employeeID = trim($_POST['employeeID']);
		$reportsto = trim($_POST['reportsto']);
		$update_report = "call update_reports_alteration('" . $employeeID . "','" . $reportsto . "','" . $createBy . "')";
		$result = $myDB->rawQuery($update_report);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			if ($myDB->count > 0) {
				echo "<script>$(function(){ toastr.success('Reports To of Employee Updated Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Record not update. Please try again.'); }); </script>";
			}
		}
	}

	if (isset($_POST['change_report']) && $_POST['change_report'] == 'chQA' && $_POST['emptype'] == 's') {
		$myDB = new MysqliDb();
		$employeeID = trim($_POST['employeeID']);
		$reportstoQA = trim($_POST['reportstoQA']);
		$QAReportsto = "call updateQAReportsto_alteration('" . $employeeID . "','" . $reportstoQA . "','" . $createBy . "')";
		$result = $myDB->rawQuery($QAReportsto);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			if ($myDB->count > 0) {
				echo "<script>$(function(){ toastr.success('QAReportsTo of Employees Updated Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Record not update. Please try again.'); }); </script>";
			}
		}
	}
	/**
	 * 
	 * following statement for change Designation
	 * 
	 */


	if (isset($_POST['change_report']) && $_POST['change_report'] == 'ch' && $_POST['emptype'] == 'm') {
		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'alteration_upload/';
		$target_file = $target_dir . basename($_FILES["empfile"]["name"]);
		$uploadOk = 1;
		$uploader = $_SESSION['__user_logid'];
		$date = date('Y-m-d h:i:s');
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
		if ($FileType != "csv") {
			echo "<script>$(function(){ toastr.error('Sorry, only csv and xlsx files are allowed.') }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["empfile"]["tmp_name"], $target_file)) {
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true, true, true, true, true);
				echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "') }); </script>";
				$row_counter = 0;
				$flag = 0;
				$row_counter = 0;
				foreach ($activeSheetData as $row) {
					if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
						$myDB = new MysqliDb();
						$update_report = "call update_reports_alteration('" . $row['A'] . "','" . $row['B'] . "','" . $createBy . "')";
						$result = $myDB->query($update_report);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							$count++;
						}
					}
					$row_counter++;
				}
				if ($count > 0) {
					echo "<script>$(function(){ toastr.error('The file " . basename($_FILES["empfile"]["name"]) . " has been uploaded') }); </script>";
					echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.') }); </script>";
				} else
					echo "<script>$(function(){ toastr.error('No Data Updated " . $mysql_error . " ') }); </script>";
				if (file_exists($target_dir . basename($_FILES["empfile"]["name"]))) {
					$ext = pathinfo($target_file, PATHINFO_EXTENSION);
					rename($target_file, $target_dir . time() . '_' . $uploader . "_alt_ReportsTo." . $ext);
				}
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
			}
		}
	}
	if (isset($_POST['change_report']) && $_POST['change_report'] == 'chQA' && $_POST['emptype'] == 'm') {
		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'alteration_upload/';
		$target_file = $target_dir . basename($_FILES["empfile"]["name"]);
		$uploadOk = 1;
		$uploader = $_SESSION['__user_logid'];
		$date = date('Y-m-d h:i:s');
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
		if ($FileType != "csv") {
			echo "<script>$(function(){ toastr.error('Sorry, only csv and xlsx files are allowed.') }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["empfile"]["tmp_name"], $target_file)) {
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true, true, true, true, true);
				echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "') }); </script>";
				$row_counter = 0;
				$flag = 0;
				$row_counter = 0;
				foreach ($activeSheetData as $row) {
					if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
						$myDB = new MysqliDb();
						$update_report = "call  updateQAReportsto_alteration('" . $row['A'] . "','" . $row['B'] . "','" . $createBy . "')";
						$result = $myDB->query($update_report);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							$count++;
						}
					}
					$row_counter++;
				}
				if ($count > 0) {
					echo "<script>$(function(){ toastr.error('The file " . basename($_FILES["empfile"]["name"]) . " has been uploaded') }); </script>";
					echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.') }); </script>";
				} else
					echo "<script>$(function(){ toastr.error('No Data Updated " . $mysql_error . " ') }); </script>";
				if (file_exists($target_dir . basename($_FILES["empfile"]["name"]))) {
					$ext = pathinfo($target_file, PATHINFO_EXTENSION);
					rename($target_file, $target_dir . time() . '_' . $uploader . "_alt_QA_ReportsTo." . $ext);
				}
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
			}
		}
	}
}
?>


<script>
	$(document).ready(function() {
		$('.statuscheck').addClass('hidden');
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Status</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Employee Status</h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<a href="../FileContainer/upload_change_reportsto.csv" id='change_reportto' target="_blank" class="btn btn-default btn-danger">
					<i class="fa fa-download"></i> Upload Change ReportsTo Format </a>

				<a href="../FileContainer/upload_QAreports.csv" id='change_QAReportTo' target="_blank" class="btn btn-default btn-danger">
					<i class="fa fa-download"></i> Upload Change QA ReportsTo Format </a>

				<div class="input-field col s12 m12">
					<!-- <input type="radio"  id="change_report"  name="change_report"   value="ch"/> -->
					<select id="updateAction" name="change_report">
						<option value=''>---Select---</option>
						<option value='ch'>Change ReportsTo</option>

						<option value='chQA'>Change QA ReportsTo</option>

					</select>
					<label for="updateAction" class="active-drop-down active">Action</label>
				</div>
				<div class="input-field col s12 m12" id="empt">
					<div class="input-field col s6 m6">
						<input type="radio" id="single" name="emptype" value="s" />
						<label for="single">Single Employee</label>
					</div>
					<div class="input-field col s6 m6">
						<input type="radio" id="multie" name="emptype" value="m" />
						<label for="multie">Multiple Employee</label>
					</div>
				</div>
				<div class="input-field col s12 m12" id='for_formattr'>
					<div class="input-field col s6 m6" id='eid'>
						<input type="text" id="employeeID" name="employeeID" />
						<label for="employeeID">Employee ID</label>
					</div>
					<div class="file-field input-field col s6 m6" id='for_multi'>
						<div class="btn"><span>Upload File</span>
							<input type="file" id="empfile" name="empfile" style="text-indent: -99999em;">
							<br>
							<span class="file-size-text">Accepts up to 2MB</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path" type="text" style="">
						</div>
					</div>
					<div class="input-field col s6 m6" id='rpto'>
						<input type="text" id="reportsto" name="reportsto" />
						<label for="reportsto">Reports To</label>
					</div>
					<div class="input-field col s6 m6 " id='rptoQA'>
						<input type="text" id="reportstoQA" name="reportstoQA" />
						<label for="reportstoQA">Reports To QA</label>
					</div>



				</div>
				<div class="input-field col s12 m12 right-align" id='updateid'>
					<button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green">Update</button>
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
		$('#btnSave1').click(function() {
			validate = 0;
			alert_msg = '';
			var updateAction = $("#updateAction").val();
			if ($("#single").is(":checked")) {
				var eid = $('#employeeID').val().trim();
				if (eid == "") {
					validate = 1;
					alert_msg += '<li> Employee ID should not be empty</li>';
				}

				if (updateAction == 'ch') {
					var reportsto = $('#reportsto').val().trim();
					if (reportsto == "") {
						validate = 1;
						alert_msg += '<li> Employee Reportsto should not be empty</li>';
					}
				}
				if (updateAction == 'chQA') {
					var reportsto = $('#reportstoQA').val().trim();
					if (reportsto == "") {
						validate = 1;
						alert_msg += '<li> Employee ReportstoQA should not be empty</li>';
					}
				}
			}
			if ($("#multie").is(":checked")) {
				var empfile = $('#empfile').val();
				if (empfile == "") {
					validate = 1;
					alert_msg += '<li> Please Upload / Browse .csv file</li>';
				} else {
					fileExtension = empfile.substr((empfile.lastIndexOf('.') + 1));
					//alert('jjjj '+fileExtension);
					//
					if (fileExtension != 'csv') {
						validate = 1;
						alert_msg += '<li> Please Upload / Browse .csv file</li>';
					}
				}
				// if (updateAction == 'rea') {

				// 	var status = $('#empstatus').val();
				// 	if (status == "") {
				// 		validate = 1;
				// 		alert_msg += '<li> Employee Status should not be empty</li>';
				// 	}

				// }
			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");

				return false;
			}
		});

		$('#empt').hide();
		$('#for_formattr').hide();
		$('#for_multi').hide();
		$('#statusdiv').hide();
		$('#updateid').hide();
		$('#rptoQA').hide();
		$('#change_reportto').hide();
		$('#change_Reactive').hide();
		$('#change_rtType').hide();
		$('#change_QAReportTo').hide();
		$('#rt_type').hide();
		$("#updateAction").change(function() {
			//alert('onchange');
			$("#single").prop('checked', false);
			$("#multie").prop('checked', false);
			$('#updateid').hide();
			$('#for_formattr').hide();
			$('#for_multi').hide();
			//	$('#statusdiv').hide();		
			$('#rptoQA').hide();
			$('#rpto').hide();
			$('#desig_div').hide();
			$('#empstatus').hide();

			$('#change_reportto').hide();
			$('#change_Reactive').hide();
			$('#change_QAReportTo').hide();
			$('#change_rtType').hide();

			$('#employeeID').val('');
			$('#reportsto').val('');
			$('#empstatus').val('');
			$('#reportstoQA').val('');
			$('#txt_empmap_desg').val('NA');
			$('#rt_type').val('NA');

			var updateAction = $("#updateAction").val();
			if (updateAction != "") {
				$('#empt').show();

				if (updateAction == 'rea') {
					$('#empt').show();
					$('#statusdiv').show();

				}

				if (updateAction == 'chDe') {
					$('#statusdiv').hide();
					//$("#multie").prop('checked', disabled);
					$("#multie").attr('disabled', true);
					$("#single").prop('checked', true);
					$('#for_formattr').show();
					$('#empt').show();
					$('#updateid').show();

					$('#desig_div').show();
					$('#eid').show();


				} else {
					$("#multie").attr('disabled', false);
				}
			} else {
				$('#empt').hide();
			}

		})

		$('#single').on('click', function() {
			$('#updateid').show();
			$('#rt_type').hide();
			$('#change_reportto').hide();
			$('#change_Reactive').hide();
			$('#change_QAReportTo').hide();
			$('#change_rtType').hide();
			$('#employeeID').val('');
			$('#reportsto').val('');
			$('#empstatus').val('');
			$('#reportstoQA').val('');
			$('#txt_empmap_desg').val('NA');
			$('#rt_type').val('NA');
			var updateAction = $("#updateAction").val();
			if ($("#single").is(":checked")) {
				$('#for_formattr').show();
				$('#eid').show();
				$('#for_multi').hide();

				if (updateAction == 'chQA') {

					$('#rptoQA').show();
					$('#rpto').hide();
					$('#statusdiv').hide();
				}
				if (updateAction == 'rea') {
					$('#empstatus').show();
					$('#statusdiv').show();
					$('#rpto').hide();
					$('#rptoQA').hide();
				}
				if (updateAction == 'ch') {
					$('#rpto').show();
					$('#statusdiv').hide();
					$('#rptoQA').hide();

				}
				if (updateAction == 'rt') {
					//$('#rpto').show();
					$('#rt_type').show();
					//$('#rptoQA').hide();

				}
			}

		});

		$('#multie').on('click', function() {
			$('#rt_type').hide();
			$('#statusdiv').hide();
			$('#updateid').show();
			$('#rptoQA').hide();
			$('#desig_div').hide();
			$('#change_reportto').hide();
			$('#change_Reactive').hide();
			$('#change_QAReportTo').hide();
			$('#change_rtType').hide();
			if ($("#multie").is(":checked")) {
				var updateAction = $("#updateAction").val();
				if (updateAction != 'rea') {
					$('#statusdiv').hide();

				}
				if (updateAction == 'rt') {
					$('#change_rtType').show();
					$('#change_Reactive').hide();
					$('#change_reportto').hide();
					$('#change_QAReportTo').hide();
				}
				if (updateAction == 'rea') {
					$('#change_Reactive').show();
					$('#change_reportto').hide();
					$('#change_QAReportTo').hide();
				}
				if (updateAction == 'ch') {
					$('#change_reportto').show();
					$('#change_Reactive').hide();
					$('#change_QAReportTo').hide();
				}
				if (updateAction == 'chQA') {
					$('#change_reportto').hide();
					$('#change_Reactive').hide();
					$('#change_QAReportTo').show();
				}
				if (updateAction == 'chDe') {
					$('#change_reportto').hide();
					$('#change_Reactive').hide();
					$('#change_QAReportTo').hide();
				}

				$('#for_formattr').show();
				$('#eid').hide();
				$('#for_multi').show();
				$('#rpto').hide();

			}

		});

	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>