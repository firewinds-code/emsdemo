<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$user_logid = isset($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!$user_logid) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = $searchBy = '';
$classvarr = "'.byID'";
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$from_date = cleanUserInput($_POST['from_date']);
	$to_date = cleanUserInput($_POST['to_date'] . " " . '23:59:59');
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
	<span id="PageTittle_span" class="hidden">Client To Client Movement Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Client To Client Movement Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="input-field col s5 m5 clsIDHome">
					<?php
					$from_dates = isset($_POST['from_date']);
					if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
						$from_date = cleanUserInput($_POST['from_date']);
					}
					?>
					<input type='text' name='from_date' id='from_date' <?php if ($from_dates) { ?> value="<?php echo $from_date; ?>" <?php } ?> readonly="true">
					<label for="from_date">From</label>
				</div>

				<div class="input-field col s5 m5 clsIDHome">
					<?php
					$to_dates = isset($_POST['to_date']);
					if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
						$to_date = cleanUserInput($_POST['to_date']);
					}
					?>
					<input type='text' name='to_date' id='to_date' <?php if ($to_dates) { ?> value="<?php echo $to_date; ?>" <?php } ?>>
					<label for="to_date">To</label>
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
					$from_date_to_date = isset($_POST['from_date'], $_POST['to_date']);
					// $from_date = cleanUserInput($_POST['from_date']);
					// $to_date = cleanUserInput($_POST['to_date'] . " " . '23:59:59');
					if ($from_date_to_date and $from_date != "") {

						$sqlConnect = "select a.old_cm_id,a.new_cm_id,a.status,a.flag,  a.EmployeeID,a.move_date,w.EmployeeName,w.DOJ, a.created_on,a.AH_updated_on,a.HR_updated_on,a.updated_on,b.cm_id,c.client_name,b.process,b.sub_process,b.account_head,e.client_name as client_name_o ,d.process as process_o,d.sub_process as sub_process_o ,d.account_head as account_head_o,l1.location FROM tbl_client_toclient_move a inner join whole_details_peremp w on a.EmployeeID=w.EmployeeID INNER JOIN new_client_master b on a.new_cm_id=b.cm_id INNER JOIN client_master c ON c.client_id=b.client_name INNER JOIN new_client_master d on a.old_cm_id=d.cm_id INNER JOIN client_master e ON e.client_id=d.client_name join location_master l1 on l1.id = w.location where a.created_on BETWEEN ? AND ?  order by a.id desc";

						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$selectQury = $conn->prepare($sqlConnect);
						$selectQury->bind_param("ss", $from_date, $to_date);
						$selectQury->execute();
						$result = $selectQury->get_result();
						// $result = $myDB->query($sqlConnect);
						// $error = $myDB->getLastError();
						if ($result->num_rows > 0) { ?>

							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Employee ID </th>
											<th>Employee Name</th>
											<th>DOJ</th>
											<th>Previous Client</th>
											<th>Previous Process</th>
											<th>Previous Sub Process</th>
											<th>New Client</th>
											<th>New Process</th>
											<th>New Sub Process</th>
											<th>Move Date</th>
											<th>Movement Initiate Date By Account Head</th>
											<th>Movement Accepted Date By HR</th>
											<th>Movement Accepted By Account Head Date</th>
											<th>Flag</th>
											<th>Status</th>
											<th>UpdatedOn</th>
											<th>Total Days</th>
											<th>Previous Account Head</th>
											<th>New Account Head</th>
											<th>Location</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;

										foreach ($result as $key => $value) {
											$str = $value['created_on'];
											$str = (strtotime($value['updated_on']) - (strtotime($str)));
											$newdate = floor($str / 3600 / 24);
											$moveDate = date('Y-m-d', strtotime($value['move_date']));

											echo '<tr>';
											echo '<td class="client_name">' . $value['EmployeeID'] . '</td>';
											echo '<td class="process" >' . $value['EmployeeName'] . '</td>';
											echo '<td class="process" >' . $value['DOJ'] . '</td>';

											echo '<td class="process" >' . $value['client_name_o'] . '</td>';
											echo '<td class="subprocess" >' . $value['process_o'] . '</td>';
											echo '<td class="subprocess" >' . $value['sub_process_o'] . '</td>';

											echo '<td class="subprocess" >' . $value['client_name'] . '</td>';
											echo '<td class="subprocess" >' . $value['process'] . '</td>';
											echo '<td class="subprocess" >' . $value['sub_process'] . '</td>';

											echo '<td class="subprocess" >' . $moveDate . '</td>';
											echo '<td class="subprocess" >' . $value['created_on'] . '</td>';
											echo '<td class="subprocess" >' . $value['HR_updated_on'] . '</td>';

											echo '<td class="subprocess" >' . $value['AH_updated_on'] . '</td>';
											echo '<td class="subprocess" >' . $value['flag'] . '</td>';
											echo '<td class="subprocess" >' . $value['status'] . '</td>';
											echo '<td class="subprocess" >' . $value['updated_on'] . '</td>';
											echo '<td class="subprocess" >' . $newdate . '</td>';


											echo '<td class="subprocess" >' . getEmpName($value['account_head_o']) . '</td>';
											echo '<td class="subprocess" >' . getEmpName($value['account_head']) . '</td>';
											echo '<td class="subprocess" >' . $value['location'] . '</td>';

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