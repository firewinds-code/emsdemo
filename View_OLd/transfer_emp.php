<?php

// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
// ini_set('error_reporting', E_ALL);

// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$user_type = clean($_SESSION['__user_type']);
$EmployeeID = clean($_SESSION['__user_logid']);

if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224') {
	// proceed further
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
$last_to = $last_from = $last_to = $emp_nam = $emp_empname =  $searchBy =  '';
$classvarr = "'.byID'";
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_ED_Search'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_ed_emp_name = cleanUserInput($_POST['ddl_ED_Emp_Name']);
		$emp_nam = (isset($clean_ed_emp_name) ? $clean_ed_emp_name : null);

		$searchBy = cleanUserInput($_POST['ddl_ED_Emp_Name']);
	}
}

if (isset($_POST['update'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmpId = cleanUserInput($_POST['ddl_ED_Emp_Name']);
		$location = cleanUserInput($_POST['location1']);
		$client_name = cleanUserInput($_POST['client_name1']);
		$process = cleanUserInput($_POST['process1']);
		$sub_process = cleanUserInput($_POST['sub_process1']);
		$reports_to = cleanUserInput($_POST['reports_to1']);
		$transfer_date = cleanUserInput($_POST['txt_dateFrom']);
		$date = date('Y-m-d h:i:s');

		$insert = 'insert into transfer_emp(EmployeeID,location,client_name,process,sub_process,reports_to,transfer_date,createdon) values(?,?,?,?,?,?,?,?) ';

		//  $updatelocation = 'update personal_details,employee_map,status_table set personal_details.location="'.$location.'", employee_map.cm_id="'.$sub_process.'" ,status_table.ReportTo="'.$reports_to.'" where personal_details.EmployeeID="'.$EmpId.'" and employee_map.EmployeeID="'.$EmpId.'" and status_table.EmployeeID="'.$EmpId.'"';  
		$stmt = $conn->prepare($insert);
		$stmt->bind_param("sissssss", $EmpId, $location, $client_name, $process, $sub_process, $reports_to, $transfer_date, $date);
		$resultBy = $stmt->execute();
		if ($stmt->affected_rows > 0) {
			echo "<script>$(function(){toastr.success('Transfer Successfully');});</script>";
		} else {
			echo "<script>$(function(){toastr.error('Failed');});</script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		// $('.byName').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			$('#ddl_ED_Emp_Name').val('');

			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
				$('.byStatus').addClass('hidden');

			}

		});
	});
</script>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Employee Tansfer</span>
	<div class="pim-container">
		<div class="form-div">
			<h4>Employee Tansfer</h4>
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class=" byID">
					<div class="input-field col s6 m6 8">
						<input type="text" id="ddl_ED_Emp_Name" name="ddl_ED_Emp_Name" title="Enter Employee ID Must Start With CE and Not Less Then 10 Char" value="<?php echo $emp_nam; ?>">
						<label for="ddl_ED_Emp_Name"> Employee ID</label>
					</div>
				</div>
				<div class="input-field col s2 m2 right-align">
					<button type="submit" name="btn_ED_Search" title="Click Here To Get Search Result" id="btn_ED_Search" class="btn waves-effect waves-green">Search</button>
				</div>
				<div id="pnlTable">
					<?php
					if (isset($_POST['btn_ED_Search'])) {
						$name = cleanUserInput($_POST['ddl_ED_Emp_Name']);
						$sqlConnect = 'select e.EmployeeID, e.EmployeeName, e.designation, e.process, e.subprocess, e.ReportTo,l.location from emp_details  as e join location_master as l on l.id=e.location where EmployeeId=?';
						$stmt = $conn->prepare($sqlConnect);
						$stmt->bind_param("s", $name);
						if (!$stmt) {
							echo "failed to run";
							die;
						}
						$stmt->execute();
						$result = $stmt->get_result();
						$count = $result->num_rows;

						if ($result->num_rows > 0) { ?>

							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th> Employee ID </th>
												<th> Name </th>
												<th> Designation </th>
												<th> Process </th>
												<th> Sub Process </th>
												<th> Location</th>
												<th> Reports To </th>

											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($result as $key => $value) {
												echo '<tr>';
												if (isset($_POST['btn_ED_Search'])) {
													echo '<td class="EmployeeID">' . $value['EmployeeID'] . '</td>';
													echo '<td class="EmployeeName">' . $value['EmployeeName'] . '</td>';
													echo '<td class="designation">' . $value['designation'] . '</td>';
													echo '<td class="process">' . $value['process'] . '</td>';
													echo '<td class="subprocess">' . $value['subprocess'] . '</td>';
													echo '<td class="location">' . $value['location'] . '</td>';
													echo '<td class="ReportTo">' . $value['ReportTo'] . '</td>';
												}

												echo '</tr>';
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						<?php
						} else {
							echo "<script>$(function(){ toastr.info('No Records Found " . $error . "'); }); </script>";
						} ?>


						<div class=" bylocation">
							<div class="input-field col s6 m6 8">
								<select class="" id="location1" name="location1" title="Select Location">
									<option id="location1" value="NA">Select Location</option>
									<?php
									$sqlBy = 'select id,location from location_master where id !=(select location from emp_details where  EmployeeId=?); ';
									$sql = $conn->prepare($sqlBy);
									$sql->bind_param("s", $name);

									$sql->execute();
									$resultBy = $sql->get_result();
									$count = $resultBy->num_rows;

									foreach ($resultBy as $key => $value) { ?>
										<option value="<?php echo base64_encode($value['id']); ?>"><?php echo $value['location']; ?></option>
									<?php
									}
									?>
								</select>
								<label title="" for="loction" class="active-drop-down active">Location</label>
							</div>
						</div>
						<div class=" byclient">
							<div class="input-field col s6 m6 8">
								<div class="form-group">
									<select class="form-control" name="client_name1" id="client_name1">
										<option value="NA">select Client</option>
									</select>
									<label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
								</div>
							</div>
						</div>
						<div class=" byprocess">
							<div class="input-field col s6 m6 8">
								<div class="form-group">
									<select class="form-control" name="process1" id="process1">
										<option value="NA">select Process</option>
									</select>
									<label title="Select Process" for="process" class="active-drop-down active">Process</label>
								</div>
							</div>
						</div>
						<div class=" bysubprocess">
							<div class="input-field col s6 m6 8">
								<div class="form-group">
									<select class="form-control" name="sub_process1" id="sub_process1">
										<option value="NA">select Sub Process</option>
									</select>
									<label title="Select Sub_process" for="sub_process" class="active-drop-down active">Sub Process</label>
								</div>
							</div>
						</div>
						<div class=" byreportto">
							<div class="input-field col s6 m6 8">
								<div class="form-group">
									<select class="form-control" name="reports_to1" id="reports_to1">
										<option value="NA">Select Reports_to</option>
									</select>
									<label title="Select Reports_to" for="reports_to" class="active-drop-down active">Reports_to</label>
								</div>
							</div>
						</div>
						<div class=" byreportto">
							<div class="input-field col s6 m6 8">
								<div class="form-group">
									<label class="active-drop-down active">Transfer Date</label>
									<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="" />
								</div>
							</div>
						</div>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" name="update" id="update" class="btn waves-effect waves-green">Transfer</button>
						</div>
					<?php
					}
					?>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		$('#txt_dateFrom').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			scrollMonth: false,
			minDate: '-1969/12/31'
		});
	});
</script>

<script>
	$(document).ready(function() {

		$('#btn_ED_Search').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('#ddl_ED_Emp_Name').removeClass('has-error');
			// $('#ddl_ED_Emp_EmpName').removeClass('has-error');
			if ($('#ddl_ED_Emp_Name').val() == '') {
				$('#ddl_ED_Emp_Name').addClass('has-error');
				if ($('#spanMessage_empid').size() == 0) {
					$('<span id="spanMessage_empid" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Name');
				}
				$('#spanMessage_empid').html('Employee Id can not be Empty');
				validate = 1;

			}


			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});
		$('#div_error').removeClass('hidden');

	});
</script>



<script>
	$(document).ready(function() {
		$('#update').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('#location1').val() == 'NA') {
				$('#location1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanlocation').size() == 0) {
					$('<span id="spanlocation" class="help-block">Required *</span>').insertAfter('#location1');
				}
				validate = 1;
			}
			if ($('#client_name1').val() == 'NA') {
				$('#client_name1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanclient_name').size() == 0) {
					$('<span id="spanclient_name" class="help-block">Required *</span>').insertAfter('#client_name1');
				}
				validate = 1;
			}
			if ($('#process1').val() == 'NA') {
				$('#process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanprocess1').size() == 0) {
					$('<span id="spanprocess1" class="help-block">Required *</span>').insertAfter('#process1');
				}
				validate = 1;
			}
			if ($('#sub_process1').val() == 'NA') {
				$('#sub_process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spansub_process1').size() == 0) {
					$('<span id="spansub_process1" class="help-block">Required *</span>').insertAfter('#sub_process1');
				}
				validate = 1;
			}
			if ($('#reports_to1').val() == 'NA') {
				$('#reports_to1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanreports_to').size() == 0) {
					$('<span id="spanreports_to" class="help-block">Required *</span>').insertAfter('#reports_to1');
				}
				validate = 1;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});
	});
</script>
<script>
	$("#location1").change(function() {
		// $('#reports_to1').val('NA');
		var location1 = $(this).val();
		$.ajax({
			url: '../Controller/get_client.php',
			type: 'GET',
			data: {
				location1: location1,
			},
			dataType: 'json',
			success: function(response) {
				//alert(response);
				$("#client_name1").html(response.client_name1);
				$("#process1").html(response.process1);
				$("#sub_process1").html(response.sub_process1);
				$("#reports_to1").html(response.reports_to1);

			}
		});

		$("#client_name1").change(function() {
			var client_name1 = $(this).val();




			$.ajax({
				url: '../Controller/get_process.php',
				type: 'GET',
				data: {
					location1: location1,
					client_name1: client_name1,
				},
				dataType: 'json',
				success: function(response) {
					//   alert(response);
					$("#process1").html(response.process1);
					$("#sub_process1").html(response.sub_process1);
					$("#reports_to1").html(response.reports_to1);
				}
			});

			$("#process1").change(function() {
				var process1 = $(this).val();
				$.ajax({
					url: '../Controller/get_subprocess.php',
					type: 'GET',
					data: {
						client_name1: client_name1,
						location1: location1,
						process1: process1,
					},
					dataType: 'json',
					success: function(response) {
						// alert(response);
						$("#sub_process1").html(response.sub_process1);
						$("#reports_to1").html(response.reports_to1);

					}
				});

				$("#sub_process1").change(function() {
					var sub_process1 = $(this).val();
					$.ajax({
						url: '../Controller/get_reports_to.php',
						type: 'GET',
						data: {
							location1: location1,
							client_name1: client_name1,
							process1: process1,
							sub_process1: sub_process1,
						},
						dataType: 'json',
						success: function(response) {
							// alert(response);
							$("#reports_to1").html(response.EmployeeID);

						}
					});
				});
			});
		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>