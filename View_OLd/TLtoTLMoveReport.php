<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	$clean_user_log_in = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_log_in)) {
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
$clean_from_date = cleanUserInput($_POST['from_date']);
$clean_to_date = cleanUserInput($_POST['to_date']);
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
	<span id="PageTittle_span" class="hidden">TL To TL Movement Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>TL To TL Movement Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5 clsIDHome">
					<input type='text' name='from_date' id='from_date' <?php if (isset($clean_from_date)) { ?> value="<?php echo $clean_from_date; ?>" <?php } ?>>
					<label for="from_date">From Date</label>
				</div>
				<div class="input-field col s5 m5 clsIDHome">
					<input type='text' name='to_date' id='to_date' <?php if (isset($clean_to_date)) { ?> value="<?php echo $clean_to_date; ?>" <?php } ?>>
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
					if (isset($clean_from_date, $clean_to_date) and $clean_from_date != "") {
						//tbl_oh_tooh_move
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$sqlConnect = "select a.*, d.EmployeeName,d.cm_id,d.DOJ,d.clientname,d.Process,d.sub_process,d.account_head ,a.flag,a.status,l1.location from tbl_tl2_tl_movement a INNER JOIN  whole_details_peremp d ON a.EmployeeID=d.EmployeeID join location_master l1 on l1.id = d.location where a.created_on BETWEEN ? AND '" . $clean_to_date . " 23:59:59' ";
						$selectQury = $conn->prepare($sqlConnect);
						$selectQury->bind_param("s", $clean_from_date);
						$selectQury->execute();
						$result = $selectQury->get_result();
						/*if($_SESSION["__location"]=="1")
					 {
					 	$sqlConnect .= "  (a.created_on BETWEEN '".$clean_from_date."' AND '".$clean_to_date." 23:59:59') and d.location in (1,2) ";
					 }
					 else
					 {
					 	$sqlConnect .= "  (a.created_on BETWEEN '".$clean_from_date."' AND '".$clean_to_date." 23:59:59') and d.location in ('".$_SESSION["__location"]."')";
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
											<th>Account Head</th>
											<th>Previous ReportsTo</th>
											<th>Current ReportsTo</th>
											<th>Move Date</th>
											<th>Movement Initiated Date By ReportsTo</th>
											<th>Assigned new ReportsTo Date By OH</th>
											<th>Movement Accepted By New ReportsTo Date</th>
											<th>Flag</th>
											<th>Status</th>
											<th>UpdatedOn</th>
											<th>Total Days</th>
											<th>Location</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;

										foreach ($result as $key => $value) {
											$str = $value['created_on'];
											if ($value['updated_on'] != "") {
												$str = strtotime($value['updated_on']) - (strtotime($str));
												$newdate = floor($str / 3600 / 24);
											} else {
												$newdate = "";
											}
											$moveDate = date('Y-m-d', strtotime($value['move_date']));

											echo '<tr style="vertical-align:top;">';
											echo '<td>' . $value['EmployeeID'] . '</td>';
											echo '<td>' . $value['EmployeeName'] . '</td>';
											echo '<td>' . $value['DOJ'] . '</td>';
											echo '<td>' . $value['clientname'] . '</td>';
											echo '<td>' . $value['Process'] . '</td>';
											echo '<td>' . $value['sub_process'] . '</td>';

											echo '<td>' . $value['account_head'] . '</td>';
											echo '<td>' . $value['old_ReportsTo'] . '</td>';
											echo '<td>' . $value['new_ReportsTo'] . '</td>';
											echo '<td>' . $moveDate . '</td>';

											echo '<td>' . $value['created_on'] . '</td>';
											echo '<td>' . $value['OH_UpdatedOn'] . '</td>';

											echo '<td>' . $value['NRT_updated_on'] . '</td>';
											echo '<td>' . $value['flag'] . '</td>';
											echo '<td>' . $value['status'] . '</td>';
											echo '<td>' . $value['updated_on'] . '</td>';
											echo '<td>' . $newdate . '</td>';
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
							echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
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