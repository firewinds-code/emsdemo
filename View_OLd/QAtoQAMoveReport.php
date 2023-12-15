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
	$from_date = cleanUserInput($_POST['from_date']);
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
			"iDisplayLength": 25,
			scrollX: '100%',
			scrollCollapse: true,
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
			}, 'pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">QA To QA Movement Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>QA To QA Movement Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5 clsIDHome">
					<input type='text' name='from_date' id='from_date' <?php if (isset($from_date)) { ?> value="<?php echo $from_date; ?>" <?php } ?>>
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
					// function getEmpName($empID)
					// {
					// 	$myDB = new MysqliDb();
					// 	$select_empname_query = mysql_query("SELECT EmployeeName from personal_details where EmployeeID='" . $empID . "'");
					// 	$empname_array = mysql_fetch_array($select_empname_query);
					// 	return $empname_array['EmployeeName'];
					// }
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					if (isset($from_date, $to_date) and $from_date != "") {
						$todate = $to_date . ' 23:59:59';
						$sqlConnect = "SELECT a.*, d.EmployeeName,d.cm_id,d.DOJ,d.clientname,d.Process,d.sub_process,d.qh ,a.Status, l1.location from tbl_qa_to_qa_movement a INNER JOIN  whole_details_peremp d ON a.EmployeeID=d.EmployeeID join location_master l1 on l1.id = d.location where a.createdon BETWEEN ? AND ? ";
						$selectQury = $conn->prepare($sqlConnect);
						$selectQury->bind_param("ss", $from_date, $todate);
						// echo "dsds";
						$selectQury->execute();
						$result = $selectQury->get_result();
						// print_r($result);
						// exit;
						/*if($_SESSION["__location"]=="1")
					 {
					 	$sqlConnect .= "  (a.createdon BETWEEN '".$from_date."' AND '".$to_date." 23:59:59') and d.location in (1,2)";
					 }
					 else
					 {
					 	$sqlConnect .= "  (a.createdon BETWEEN '".$from_date."' AND '".$to_date." 23:59:59') and d.location in ('".$_SESSION["__location"]."')";
					 }*/

						//echo $sqlConnect;

						// $result = $myDB->rawQuery($sqlConnect);
						// $error = $myDB->getLastError();
						if ($result->num_rows > 0) { ?>
							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Employee ID </th>
											<th>Employee Name</th>
											<th>DOJ</th>
											<th>Client</th>
											<th>Process</th>
											<th>Sub Process</th>
											<th>Quality Head</th>
											<th>Previous ReportsTo</th>
											<th>Current ReportsTo</th>
											<th>Move Date</th>
											<th>Movement Initiated Date By QA</th>
											<th>Status</th>
											<th>Location</th>
											<th>Updated By</th>

											<th>UpdatedOn</th>
											<!-- <th>Total Days</th>-->
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										foreach ($result as $key => $value) {
											$str = $value['createdon'];
											if ($value['modifiedon'] != "") {
												$str = strtotime($value['modifiedon']) - (strtotime($str));
												$newdate = floor($str / 3600 / 24);
											} else {
												$newdate = "";
											}
											$status = '';
											$moveDate = date('Y-m-d', strtotime($value['MovementOn']));
											if ($value['Status'] == 1) {
												$status = 'Initiated';
											} else
								if ($value['Status'] == 2) {
												$status = 'Approvr By QH And Align New QA';
											} else
								if ($value['Status'] == 3) {
												$status = 'Decline By QH ';
											} else
								if ($value['Status'] == 4) {
												$status = 'Approvr By New QA';
											} else
								if ($value['Status'] == 5) {
												$status = 'Decline By New QA';
											}
											echo '<tr style="vertical-align:top;">';
											echo '<td>' . $value['EmployeeID'] . '</td>';
											echo '<td>' . $value['EmployeeName'] . '</td>';
											echo '<td>' . $value['DOJ'] . '</td>';
											echo '<td>' . $value['clientname'] . '</td>';
											echo '<td>' . $value['Process'] . '</td>';
											echo '<td>' . $value['sub_process'] . '</td>';

											echo '<td>' . $value['qh'] . '</td>';
											echo '<td>' . $value['CreatedBy'] . '</td>';
											echo '<td>' . $value['NewQA'] . '</td>';
											echo '<td>' . $moveDate . '</td>';
											echo '<td>' . $value['createdon'] . '</td>';
											echo '<td>' . $status . '</td>';
											echo '<td>' . $value['location'] . '</td>';
											echo '<td>' . $value['ModifiedBy'] . '</td>';
											echo '<td>' . $value['modifiedon'] . '</td>';
											//echo '<td>'.$newdate.'</td>';
											echo '</tr>';
											$i++;
										}
										?>
									</tbody>
								</table>
							</div>
					<?php
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $error . "'); }); </script>";
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
		$('#send').on('click', function() {

			validate = 0;
			alert_msg = "";
			var from_date = $('#from_date').val().trim();
			var to_date = $('#to_date').val().trim();
			if (from_date == "" || to_date == "") {
				validate = 1;
				alert_msg = 'Please select  from and to date both';
			}

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");*/
				$(function() {
					toastr.error("No Data Found " + alert_msg);
				});
				return false;
			}
		});

	});
</script>