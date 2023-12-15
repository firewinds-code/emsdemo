<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {

		if (isset($_POST['txt_dateTo'])) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$txt_location = cleanUserInput($_POST['txt_location']);
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('#myTable').DataTable({
			//columnDefs: [
			//hide the second & fourth column
			//{ 'visible': false, 'targets': [28] }],
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 29]
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 25,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Grievance Reports</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Grievance Reports</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s3 m3">

						<select id="txt_location" name="txt_location" required>
							<option value="NA">----Select----</option>
							<?php
							$sqlBy = 'select id,location from location_master;';
							$myDB = new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								echo '<option value="ALL"  >ALL</option>';
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
								}
							}
							?>
						</select>
						<label for="txt_location" class="active-drop-down active">Location</label>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<button type="button" class="btn waves-effect waves-green" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>
					</div>
				</div>
				<?php
				$myDB = new MysqliDb();

				$_location = (isset($txt_location) ? $txt_location : null);
				// echo 'call get_issueReport("' . $date_From . '","' . $date_To . '", "' . $_location . '")';
				$chk_task = $myDB->query('call get_issueReport("' . $date_From . '","' . $date_To . '", "' . $_location . '")');
				$my_error = $myDB->getLastError();

				if (count($chk_task) > 0 && $chk_task) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>Case ID</th>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>Location</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>Mobile No.</th>';
					$table .= '<th>Issue</th>';
					$table .= '<th>Belongs To</th>';
					$table .= '<th>Status</th>';
					$table .= '<th>Request On</th>';
					$table .= '<th>Last Updated by Requester</th>';
					$table .= '<th>Last Updated by Handler</th>';
					$table .= '<th>Concern off</th>';
					$table .= '<th>Employee Status</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Dept Name</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '<th>Type</th>';
					$table .= '<th>Contact Status</th>';
					$table .= '<th>Disposition</th>';
					$table .= '<th>Handler</th>';
					$table .= '<th>Refer To</th>';
					$table .= '<th>Requester Comments</th>';
					$table .= '<th>Handler Comments</th>';
					$table .= '<th>Rating</th>';
					$table .= '<th style="display:none;">Rating</th>';
					$table .= '<th>Feedback</th>';
					$table .= '<thead><tbody>';

					foreach ($chk_task as $key => $value) {
						$requester_remark = $value['requester_remark'];
						if (strstr($requester_remark, '>')) {
							$requester_remark = str_replace('>', 'greater than', $requester_remark);
						}
						if (strstr($requester_remark, '<')) {
							$requester_remark = str_replace('<', 'less than', $requester_remark);
						}
						$handler_remark = $value['handler_remark'];
						if (strstr($requester_remark, '>')) {
							$handler_remark = str_replace('>', 'greater than', $handler_remark);
						}
						if (strstr($handler_remark, '<')) {
							$handler_remark = str_replace('<', 'less than', $handler_remark);
						}
						$table .= '<tr><td>' . $value['id'] . '</td>';
						$table .= '<td>' . $value['requestby'] . '</td>';
						$table .= '<td>' . $value['location'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['mobileNo'] . '</td>';
						$table .= '<td>' . $value['queary'] . '</td>';
						$table .= '<td>' . $value['bt'] . '</td>';
						$table .= '<td>' . $value['status'] . '</td>';
						$table .= '<td>' . $value['request_date'] . '</td>';
						$table .= '<td>' . $value['updateby_requester'] . '</td>';
						$table .= '<td>' . $value['updateby_handler'] . '</td>';
						$table .= '<td>' . $value['concern_off'] . '</td>';
						$table .= '<td>' . $value['emp_status'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['dept_name'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['Suppervisor'] . '</td>';
						$table .= '<td>' . $value['type'] . '</td>';
						$table .= '<td>' . $value['contatc_staus'] . '</td>';
						$table .= '<td>' . $value['disposition'] . '</td>';
						$table .= '<td>' . $value['issue_handler'] . '</td>';
						$table .= '<td>' . $value['lo1'] . '</td>';
						$table .= '<td style="max-width: 50px;overflow: hidden;">' . $requester_remark . '</td>';
						$table .= '<td  style="max-width: 50px;overflow: hidden;">' . $handler_remark . '</td>';
						$table .= '<td>';
						if (is_numeric($value['rating'])) {
							if ($value['rating'] < 10) {

								$table .= '<i class="fa fa-thumbs-down fa-lg" style="font-size: 1.3rem; color:red;"></i>';
							} else {
								$table .= '<i class="fa fa-thumbs-up fa-lg" style="font-size: 1.3rem; color:green"></i>';
							}
						} else {
							$table .= 'NA';
						}
						$table .= '</td>';
						$table .= '<td style="display:none";>';


						if (is_numeric($value['rating'])) {
							if ($value['rating'] < 10) {

								$table .= 'Down';
							} else {
								$table .= 'Up';
							}
						} else {
							$table .= 'NA';
						}


						$table .= '</td>';
						$table .= '<td  style="max-width: 50px;overflow: hidden;">' . $value['feedback'] . '</td></tr>';
					}
					$table .= '</tbody></table></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
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

<script>
	$("#btn_view").click(function() {
		//alert($("#txt_location").val());
		if ($('#txt_location').val() == "NA") {
			alert('Please select location');
			return false;
		}
	});

	$('#btn_export').on('click', function() {
		var date_f = $('#txt_dateFrom').val();
		var date_t = $('#txt_dateTo').val();
		var loc = $('#txt_location').val();
		var sp = 'call get_issueReport("' + date_f + '","' + date_t + '","' + loc + '")';
		// alert(sp)
		var url = "textExport.php?sp=" + sp;
		// alert(url)
		window.location.href = url;
		return false;
	})
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>