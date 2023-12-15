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
$user_id = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_id)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		//print_r($_POST);
		$createBy = clean($_SESSION['__user_logid']);
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'ch' && $_POST['emptype'] == 's') {
			$myDB = new MysqliDb();
			$employeeID = trim(cleanUserInput($_POST['employeeID']));
			$reportsto = trim(cleanUserInput($_POST['reportsto']));
			$update_report = "call update_reports_alteration('" . $employeeID . "','" . $reportsto . "','" . $createBy . "')";
			$result = $myDB->query($update_report);
			echo "<script>$(function(){ toastr.success('Reports To of Employee Updated Successfully'); }); </script>";
		}
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'rea' && $_POST['emptype'] == 's') {
			$myDB = new MysqliDb();
			$employeeID = trim(cleanUserInput($_POST['employeeID']));
			$empstatus = trim(cleanUserInput($_POST['empstatus']));
			$reactive_report = "call update_status_alteration('" . $employeeID . "','" . $empstatus . "','" . $createBy . "')";
			$result = $myDB->query($reactive_report);
			echo "<script>$(function(){ toastr.success('Status of Employee Updated Successfully'); }); </script>";
		}
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'chQA' && $_POST['emptype'] == 's') {
			$myDB = new MysqliDb();
			$employeeID = trim(cleanUserInput($_POST['employeeID']));
			$reportstoQA = trim(cleanUserInput($_POST['reportstoQA']));
			$QAReportsto = "call updateQAReportsto_alteration('" . $employeeID . "','" . $reportstoQA . "','" . $createBy . "')";
			$result = $myDB->query($QAReportsto);
			echo "<script>$(function(){ toastr.error('QAReportsTo of Employee Updated Successfully'); }); </script>";
		}
		/**
		 * 
		 * following statement for change Designation
		 * 
		 */
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'chDe' && $_POST['emptype'] == 's') {
			$myDB = new MysqliDb();
			$employeeID = trim(cleanUserInput($_POST['employeeID']));
			$df_id = cleanUserInput($_POST['txt_df_id']);
			$QAReportsto = "call updateDesignation_alteration('" . $df_id . "','" . $employeeID . "','" . $createBy . "')";
			$result = $myDB->query($QAReportsto);
			echo "<script>$(function(){ toastr.success('Designation of Employee Updated Successfully'); }); </script>";
		}

		if (isset($_POST['change_report']) && $_POST['change_report'] == 'rea' && $_POST['emptype'] == 'm') {
			if (isset($_FILES['empfile']['name']) && $_FILES['empfile']['name'] != "") {
				$imageFileType = strtolower(pathinfo($_FILES['empfile']['name'], PATHINFO_EXTENSION));
				if ($imageFileType == 'csv') {
					$handle = fopen($_FILES['empfile']['tmp_name'], "r");
					$empstatus = trim(cleanUserInput($_POST['empstatus']));
					$myDB = new MysqliDb();
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$employeeID = trim($data[0]);
						if ($employeeID != "" && $empstatus != "") {
							$reactive_report = "call update_status_alteration('" . $employeeID . "','" . $empstatus . "','" . $createBy . "')";
							$result = $myDB->query($reactive_report);
						}
					}
					fclose($handle);
					echo "<script>$(function(){ toastr.success('Status of Employee Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Upload / Browse .csv file Only'); }); </script>";
				}
			}
		}
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'ch' && $_POST['emptype'] == 'm') {
			if (isset($_FILES['empfile']['name']) and $_FILES['empfile']['name'] != "") {
				$imageFileType = strtolower(pathinfo($_FILES['empfile']['name'], PATHINFO_EXTENSION));
				if ($imageFileType == 'csv') {
					$handle = fopen($_FILES['empfile']['tmp_name'], "r");
					$myDB = new MysqliDb();
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$employeeID = trim($data[0]);
						$reportsto = trim($data[1]);
						if ($employeeID != "" && $reportsto != "") {
							$update_report = "call update_reports_alteration('" . $employeeID . "','" . $reportsto . "','" . $createBy . "')";
							$result = $myDB->query($update_report);
						}
					}
					fclose($handle);
					echo "<script>$(function(){ toastr.success('Reports To of Employee Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Upload / Browse .csv file Only'); }); </script>";
				}
			}
		}
		if (isset($_POST['change_report']) && $_POST['change_report'] == 'chQA' && $_POST['emptype'] == 'm') {
			if (isset($_FILES['empfile']['name']) and $_FILES['empfile']['name'] != "") {
				$imageFileType = strtolower(pathinfo($_FILES['empfile']['name'], PATHINFO_EXTENSION));
				if ($imageFileType == 'csv') {
					$handle = fopen($_FILES['empfile']['tmp_name'], "r");
					$myDB = new MysqliDb();
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$employeeID = trim($data[0]);
						$QAreportsto = trim($data[1]);
						if ($employeeID != "" && $QAreportsto != "") {
							$update_report = "call  updateQAReportsto_alteration('" . $employeeID . "','" . $QAreportsto . "','" . $createBy . "')";
							$result = $myDB->query($update_report);
						}
					}
					fclose($handle);
					echo "<script>$(function(){ toastr.success('QAReports To of Employee Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Upload / Browse .csv file Only'); }); </script>";
				}
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
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="input-field col s12 m12">
					<!-- <input type="radio"  id="change_report"  name="change_report"   value="ch"/> -->
					<select id="updateAction" name="change_report">
						<option value=''>---Select---</option>
						<option value='ch'>Change ReportsTo</option>
						<option value='rea'>Reactive Employee</option>
						<option value='chQA'>Change QA ReportsTo</option>
						<option value='chDe'>Change Designation</option>
					</select>
					<label for="txt_Client_ach" class="active-drop-down active">Action</label>
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
					<div class="input-field col s6 m6 " id='statusdiv'>
						<input type="text" id="empstatus" name="empstatus" value='Active' />
						<label for="empstatus">Status</label>
					</div>
					<div class="input-field col s6 m6" id='desig_div'>

						<select class="form-control clsInput" id="txt_empmap_desg" name="txt_df_id">
							<option value="">----Select----</option>
							<?php
							$myDB = new MysqliDb();
							$desi_select_query = "SELECT a.df_id,a.function_id,b.ID,CONCAT(f.function,' | ',b.Designation) as Designation from df_master a  INNER JOIN designation_master b ON a.des_id=b.ID  INNER JOIN function_master f ON f.id=a.function_id  order by CONCAT(f.function,' | ',b.Designation)";
							$stmt = $conn->prepare($desi_select_query);
							$stmt->execute();
							$resultBy = $stmt->get_result();
							// $mysql_error = $myDB->getLastError();
							if ($resultBy) {
								// $resultBy = $myDB->rawQuery($desi_select_query);
								// $mysql_error = $myDB->getLastError();
								// $rowCount = $myDB->count;
								// if (empty($mysql_error)) {
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['df_id'] . '"  >' . $value['Designation'] . '</option>';
								}
							}
							?>
						</select>
						<label for="txt_empmap_desg" class="active-drop-down active">Designation</label>
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
				var eid = $('#employeeID').val().replace(/^\s+|\s+$/g, '');
				if (eid == "") {
					validate = 1;
					alert_msg += '<li> Employee ID should not be empty</li>';
				}

				if (updateAction == 'rea') {
					var status = $('#empstatus').val();
					if (status == "") {
						validate = 1;
						alert_msg += '<li> Employee Status should not be empty</li>';
					}
				}
				if (updateAction == 'ch') {
					var reportsto = $('#reportsto').val().replace(/^\s+|\s+$/g, '');
					if (reportsto == "") {
						validate = 1;
						alert_msg += '<li> Employee Reportsto should not be empty</li>';
					}
				}
				if (updateAction == 'chQA') {
					var reportsto = $('#reportstoQA').val().replace(/^\s+|\s+$/g, '');
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
				if (updateAction == 'rea') {

					var status = $('#empstatus').val();
					if (status == "") {
						validate = 1;
						alert_msg += '<li> Employee Status should not be empty</li>';
					}

				}
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

			var updateAction = $("#updateAction").val();
			if (updateAction != "") {
				$('#empt').show();

				if (updateAction == 'rea') {
					$('#empt').show();
					$('#statusdiv').show();
				} else
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
			}

		});

		$('#multie').on('click', function() {
			$('#statusdiv').show();
			$('#updateid').show();
			$('#rptoQA').hide();
			$('#desig_div').hide();
			if ($("#multie").is(":checked")) {
				var updateAction = $("#updateAction").val();
				if (updateAction != 'rea') {
					$('#statusdiv').hide();
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