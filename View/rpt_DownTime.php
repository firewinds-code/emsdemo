<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
require_once(CLS1 . 'MysqliDb_replica1.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
			$date_To = $_POST['txt_dateTo'];
			$date_From = $_POST['txt_dateFrom'];
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('#myTable').DataTable({
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
						modifier: {
							page: 'all'
						}
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
	<span id="PageTittle_span" class="hidden">Down Time Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Down Time Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateFrom" style="min-width: 225px;" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateTo" style="min-width: 225px;" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">

						<Select class="form-control" name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
					</div>

				</div>


				<?php
				if (isset($_POST['btn_view'])) {

					$myDB = new MysqliDb1();
					$empStatus = $_POST['emp_status'];
					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					$chk_task  =  $myDB->query('call sp_getDawnTime_Report("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '","' . $empStatus . '","' . $_SESSION["__location"] . '")');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						  <thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Request_Type</th>';
						$table .= '<th>FA EmployeeID</th>';
						$table .= '<th>FA Status</th>';
						$table .= '<th>FA Comment</th>';
						$table .= '<th>RT EmployeeID</th>';
						$table .= '<th>RT Status</th>';
						$table .= '<th>RT Comment</th>';
						$table .= '<th>From</th>';
						$table .= '<th>To</th>';
						$table .= '<th>Total Hour</th>';
						$table .= '<th>BillableType</th>';
						$table .= '<th>IT Ticket ID</th>';
						$table .= '<th>Createdon</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Status</th>';

						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Supervisor</th>';
						$table .= '<th>Approved By</th>';
						$table .= '<th>Comments</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmpID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							if ($value['Request_type'] == 'Refreshment') {
								$table .= '<td>Refresh</td>';
							} else {
								$table .= '<td>' . $value['Request_type'] . '</td>';
							}

							$table .= '<td>' . $value['FAID'] . '</td>';
							$table .= '<td>' . $value['FAStatus'] . '</td>';
							$table .= '<td>' . $value['FAComment'] . '</td>';
							$table .= '<td>' . $value['RTID'] . '</td>';
							$table .= '<td>' . $value['RTStatus'] . '</td>';
							$table .= '<td>' . $value['RTComment'] . '</td>';
							$table .= '<td>' . $value['DTFrom'] . '</td>';
							$table .= '<td>' . $value['DTTo'] . '</td>';
							$table .= '<td>' . $value['TotalDT'] . '</td>';
							$table .= '<td>' . $value['BillableType'] . '</td>';
							$table .= '<td>' . $value['IT_ticketid'] . '</td>';
							$table .= '<td>' . $value['createdon'] . '</td>';
							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['emp_status'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							$comment = explode('|', $value['Comments']);

							$string1 = 'Approved by Server at First Level';
							$string2 = 'Approved by Server at Final Level';
							$modify = (empty($value['modifiedon'])) ? '' : '(' . date('Y-m-d', strtotime($value['modifiedon'])) . ')';
							$attr_val = $modify . ' ' . $value['modifiedby'];
							$attr = '';
							foreach ($comment as $url) {

								if (preg_match("/\b$string1\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
								if (preg_match("/\b$string2\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
							}
							if (!empty($attr))
								$attr_val = $attr;
							$table .= '<td>' . $attr_val . '</td>';
							$table .= '<td>' . $value['Comments'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				}

				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>