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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
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
	$empStatus = $_POST['emp_status'];
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
			"sScrollY": "192",
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
	<span id="PageTittle_span" class="hidden">Leave Status Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Leave Status Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12">
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<Select name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php
													$emp_status = cleanUserInput($_POST['emp_status']);
													if (isset($emp_status) && $emp_status == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php
														$emp_status = cleanUserInput($_POST['emp_status']);
														if (isset($emp_status) && $emp_status == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>

				</div>
				<?php
				$btn_view = isset($_POST['btn_view']);
				if ($btn_view) {
					$userLogID = clean($_SESSION['__user_logid']);
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();

					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					$chktask = "select LeaveID,leavehistry.EmployeeID,$tablename.EmployeeName,cast(DateFrom as date) as DateFrom,cast(DateTo as date) as DateTo,DateCreated,leavehistry.ModifiedBy,EmployeeComment as FinalStatus,personal_details.EmployeeName as Supervisor,Designation,dept_name,DOJ,clientname,process,sub_process,leavehistry.LeaveType,$tablename.account_head,leavehistry.DateModified,leavehistry.TotalLeaves,$tablename.oh ,leavehistry.HRComents,leavehistry.ManagerComment,leavehistry.HRStatusID,leavehistry.MngrStatusID , GROUP_CONCAT(CONCAT_WS(' ',concat(pd1.EmployeeName,' [',leave_comment.CreatedBy,']','(',leave_comment.user_type,')'),'(',leave_comment.CreatedOn,')' , `Comment`) SEPARATOR ' | ') AS Comments FROM (select leave_id, createdby, comment,createdon,user_type from leave_comment order by leave_id,createdon)  leave_comment inner join leavehistry on leavehistry.LeaveID = leave_comment.leave_id inner join $tablename on $tablename.EmployeeID = leavehistry.EmployeeID left outer join personal_details on personal_details.EmployeeID = ReportTo left outer join personal_details pd1 on leave_comment.CreatedBy = pd1.EmployeeID where ($tablename.EmployeeID in (select t1.EmployeeID from (select EmployeeID from  status_table where ReportTo in(select EmployeeID from  status_table where ReportTo in(select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?))) union select EmployeeID from  status_table where  ReportTo in(select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?)) union select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?) union select EmployeeID from status_table where   ReportTo = ? or EmployeeID = ? or Qa_ops = ?) t1) ) and  ((cast(leavehistry.DateFrom as date) between cast(? as date) and cast(? as date)) or (cast(leavehistry.DateTo as date) between cast(? as date) and cast(? as date))) and leavehistry.ReasonofLeave !='Back Dated Leave' GROUP BY leave_comment.leave_id;";
					$selectQ = $conn->prepare($chktask);
					$selectQ->bind_param("ssssssssss", $userLogID, $userLogID, $userLogID, $userLogID, $userLogID, $userLogID, $date_From, $date_To, $date_From, $date_To);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;border: none !important;">
						<div class=""  >
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>OPS Status</th>';
						$table .= '<th>Account Head Status</th>';
						$table .= '<th>FinalStatus</th>';
						$table .= '<th>DateCreated</th>';
						$table .= '<th>DateFrom</th>';
						$table .= '<th>DateTo</th>';

						$table .= '<th>Leave Status</th>';
						$table .= '<th>Count Of Leave</th>';

						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Supervisor</th>';

						$table .= '<th>Account Head</th>';
						$table .= '<th>Ops Head</th>';

						$table .= '<th>ModifiedBy</th>';
						$table .= '<th>ModifiedOn</th>';
						$table .= '<th>Approved By</th>';

						$table .= '<th>Comments</th><thead><tbody>';


						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['HRStatusID'] . '</td>';
							$table .= '<td>' . $value['MngrStatusID'] . '</td>';
							$table .= '<td>' . $value['FinalStatus'] . '</td>';
							$table .= '<td>' . $value['DateCreated'] . '</td>';
							$table .= '<td>' . $value['DateFrom'] . '</td>';
							$table .= '<td>' . $value['DateTo'] . '</td>';
							$table .= '<td>' . $value['LeaveType'] . '</td>';
							if ($value['LeaveType'] == 'Leave') {
								$table .= '<td>' . $value['TotalLeaves'] . '</td>';
							} else {
								$table .= '<td>' . round(intval($value['TotalLeaves']) / 2, 1) . '</td>';
							}

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							if ($value['ManagerComment'] == ' Approved By SERVER ') {
								$table .= '<td>SERVER</td>';
							} elseif ($value['FinalStatus'] == 'Pending' || $value['FinalStatus'] == '') {
								$table .= '<td></td>';
							} else {
								$table .= '<td>ACCOUNT HEAD</td>';
							}
							if ($value['HRComents'] == ' Approved By SERVER ') {
								$table .= '<td>SERVER</td>';
							} else {
								$table .= '<td>OPS HEAD</td>';
							}

							$table .= '<td>' . $value['account_head'] . '</td>';
							$table .= '<td>' . $value['DateModified'] . '</td>';
							$comment = explode('|', $value['Comments']);

							$string1 = 'Ops Head By Server';
							$string2 = 'Account Head By Server';
							$modify = (empty($value['DateModified'])) ? '' : '(' . date('Y-m-d', strtotime($value['DateModified'])) . ')';
							$attr_val = $modify . ' ' . $value['ModifiedBy'];
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
						echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
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