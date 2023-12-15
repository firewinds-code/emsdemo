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
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$clientID = $process = $subprocess = $bheading = $remark1 = $searchBy = $remark2 = $remark3 = '';
$classvarr = "'.byID'";

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$fromdate = cleanUserInput($_POST['from_date']);
	$to_date = cleanUserInput($_POST['to_date']);
}
?>

<script>
	$(document).ready(function() {
		$('#from_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#to_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			scrollCollapse: true,
			"iDisplayLength": 25,
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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		$('.byProc').addClass('hidden');
		$('.byName').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Process To Process Movement Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Process To Process Movement Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php

				// $fromdate = cleanUserInput($_POST['from_date']);
				// $to_date = cleanUserInput($_POST['to_date']);
				?>
				<div class="input-field col s5 m5 clsIDHome">
					<input type='text' name='from_date' id='from_date' <?php if (isset($fromdate)) { ?> value="<?php echo $fromdate; ?>" <?php } ?>>
					<label for="from_date">From Date</label>
				</div>
				<div class="input-field col s5 m5 clsIDHome">
					<input type='text' name='to_date' id='to_date' <?php if (isset($to_date)) { ?> value="<?php echo $to_date; ?>" <?php } ?>>
					<label for="to_date">To Date</label>
				</div>
				<div class="input-field col s2 m2 clsIDHome">
					<input type="submit" value="Go" name="send" id="send" class="btn waves-effect waves-green" />
				</div>

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					function getEmpName($empID)
					{

						$myDB = new MysqliDb();
						$myDB->where("EmployeeID", $empID);
						$select_empname_query = $myDB->getOne("personal_details", "EmployeeName");
						$empname_array = $select_empname_query['EmployeeName'];
						return $empname_array;
					}
					if (isset($fromdate, $to_date) and $fromdate != "") {
						$to_date = $to_date . ' 23:59:59';
						// echo "$to_date";
						// exit;
						// exit;
						//tbl_oh_tooh_move
						// $sqlConnect = "select a.EmployeeID,a.created_on,a.OH_updated_on,a.AH_updated_on,a.updated_on,a.move_date, b.process,b.sub_process,b.account_head,c.client_name, d.EmployeeName,d.cm_id,d.DOJ,d.clientname,d.Process,d.sub_process,d.account_head ,a.flag,a.status from tbl_oh_tooh_move a INNER JOIN new_client_master b on a.cm_id=b.cm_id INNER JOIN  client_master c ON c.client_id=b.client_name INNER JOIN whole_details_peremp d ON a.EmployeeID=d.EmployeeID where  ";
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$sqlConnect = "SELECT a.EmployeeID,a.created_on,a.OH_updated_on,a.AH_updated_on,a.updated_on,a.move_date, b.process,b.sub_process,b.account_head,c.client_name, d.EmployeeName,d.cm_id,d.DOJ,e.process as process_o ,e.sub_process as sub_process_o,d.account_head as account_head_o,d.clientname as clientname_o ,a.flag,a.status, l1.location from tbl_oh_tooh_move a INNER JOIN new_client_master b on a.new_cm_id=b.cm_id INNER JOIN client_master c ON c.client_id=b.client_name INNER JOIN whole_details_peremp d ON a.EmployeeID=d.EmployeeID INNER JOIN new_client_master e on a.cm_id=e.cm_id join location_master l1 on l1.id = d.location where (a.created_on BETWEEN ? AND ? )";
						//$sqlConnect .= "  (a.created_on BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']." 23:59:59')";
						/*if($_SESSION["__location"]=="1")
					 {
					 	$sqlConnect .= "  (a.created_on BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']." 23:59:59') and d.location in (1,2) ";
					 }
					 else
					 {
					 	$sqlConnect .= "  (a.created_on BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']." 23:59:59') and d.location in ('".$_SESSION["__location"]."')";
					 }*/


						//echo  $sqlConnect;
						$selectQury = $conn->prepare($sqlConnect);
						$selectQury->bind_param("ss", $fromdate, $to_date);
						$selectQury->execute();
						$result = $selectQury->get_result();
						// $result = $myDB->query($sqlConnect);
						// print_r($result);
						// exit;
						// $error = $myDB->getLastError();
						if ($result->num_rows > 0) { ?>
							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Employee ID </th>
											<th>Employee Name</th>
											<th style="vertical-align:top;text-align:center;">DOJ</th>
											<th style="vertical-align:top;text-align:center;">Previous Client</th>
											<th style="vertical-align:top;text-align:center;">Previous Process</th>
											<th style="vertical-align:top;text-align:center;">Previous Sub Process</th>
											<th style="vertical-align:top;text-align:center;">New Client</th>
											<th style="vertical-align:top;text-align:center;">New Process</th>
											<th style="vertical-align:top;text-align:center;">New Sub Process</th>
											<th style="vertical-align:top;text-align:center;">Move Date</th>
											<th style="vertical-align:top;text-align:center;">Movement Initiate Date By AH</th>
											<th style="vertical-align:top;text-align:center;">Movement Accepted Date By OH</th>
											<th style="vertical-align:top;text-align:center;">Movement Accepted By AH Date</th>
											<th style="vertical-align:top;text-align:center;">Flag</th>
											<th style="vertical-align:top;text-align:center;">Status</th>
											<th style="vertical-align:top;text-align:center;">UpdatedOn</th>
											<th style="vertical-align:top;text-align:center;">Total Days</th>
											<th style="vertical-align:top;text-align:center;">Previous Account Head</th>
											<th style="vertical-align:top;text-align:center;">New Account Head</th>
											<th style="vertical-align:top;text-align:center;">Location</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;

										foreach ($result as $key => $value) {
											$str = $value['created_on'];
											$updated_on = "";
											if ($value['updated_on'] == '' || $value['updated_on'] == NULL) {
												$updated_on = date('Y-m-d');
											} else {
												$updated_on = $value['updated_on'];
											}
											//echo "updated on=".$updated_on;
											$diff = $str = strtotime($updated_on) - (strtotime($str));
											$newdate = floor($str / 3600 / 24);
											$moveDate = date('Y-m-d', strtotime($value['move_date']));
											echo '<tr style="vertical-align:top;">';
											echo '<td>' . $value['EmployeeID'] . '</td>';
											echo '<td>' . $value['EmployeeName'] . '</td>';
											echo '<td>' . $value['DOJ'] . '</td>';

											echo '<td>' . $value['clientname_o'] . '</td>';
											echo '<td>' . $value['process_o'] . '</td>';
											echo '<td>' . $value['sub_process_o'] . '</td>';

											echo '<td>' . $value['client_name'] . '</td>';
											echo '<td>' . $value['process'] . '</td>';
											echo '<td>' . $value['sub_process'] . '</td>';

											echo '<td>' . $moveDate . '</td>';
											echo '<td>' . $value['created_on'] . '</td>';
											echo '<td>' . $value['AH_updated_on'] . '</td>';
											echo '<td>' . $value['OH_updated_on'] . '</td>';
											echo '<td>' . $value['flag'] . '</td>';
											echo '<td>' . $value['status'] . '</td>';
											echo '<td>' . $value['updated_on'] . '</td>';
											echo '<td>' . $newdate . '</td>';
											echo '<td>' . getEmpName($value['account_head_o']) . '</td>';
											echo '<td>' . getEmpName($value['account_head']) . '</td>';
											echo '<td>' . $value['location'] . '</td>';

											echo '</tr>';
											$i++;
										}
										?>
									</tbody>
								</table>
							</div>

					<?php
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
						}
					}
					?>

				</div>

				<!--Reprot / Data Table End -->

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	$(document).ready(function() {

		$('#div_error').click(function() {
			$('#div_error').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}

		$('#div_error').removeClass('hidden');
		$('#send').on('click', function() {

			validate = 0;
			alert_msg = "";
			var from_date = $('#from_date').val().trim();
			var to_date = $('#to_date').val().trim();
			if (from_date == "" || to_date == "") {
				validate = 1;
				alert_msg += '<li> Please select both date  from and to </li>';
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