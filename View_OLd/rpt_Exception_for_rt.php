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
$EmployeeID = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
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
	$empStatus = cleanUserInput($_POST['emp_status']);
}

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Exception Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Exception Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

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

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<Select name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php $emp_status = cleanUserInput($_POST['emp_status']);
													if (isset($emp_status) && $emp_status == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php $emp_status = cleanUserInput($_POST['emp_status']);
														if (isset($emp_status) && $emp_status == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<?php
				if (isset($_POST['btn_view'])) {

					// $myDB = new MysqliDb();

					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					$Select = "SELECT $tablename.EmployeeID,$tablename.EmployeeName,exception.CreatedOn,exception.Exception,exception.MgrStatus,exception.HeadStatus,exception.DateFrom,exception.DateTo,exceptiondispo.Current_Att,exceptiondispo.Update_Att,exceptiondispo.ShiftIn,exceptiondispo.ShiftOut,exception.ModifiedOn,$tablename.designation,$tablename.dept_name ,$tablename.DOJ,$tablename.clientname,$tablename.Process,$tablename.sub_process,personal_details.EmployeeName `Supervisor`,exception.ModifiedBy,concat(case when exception.ModifiedBy=' SERVER' then 'SERVER' else personal_details.EmployeeName end,':',cast(exception.ModifiedOn as date)) as ApprovedBy, GROUP_CONCAT(CONCAT_WS(' ',concat(pd1.EmployeeName,' [',exceptioncomments.CreatedBy,']'),'(',exceptioncomments.CreatedOn,')',Comments) SEPARATOR ' | ') AS Comments FROM (select ExpID, CreatedBy, CreatedOn, Comments FROM exceptioncomments order by exceptioncomments.ExpID,exceptioncomments.CreatedOn) exceptioncomments inner join exception on exception.ID = exceptioncomments.ExpID inner join exceptiondispo on exceptiondispo.ExpID = exceptioncomments.ExpID inner join $tablename on $tablename.EmployeeID = exception.EmployeeID left outer join personal_details on personal_details.EmployeeID = ReportTo left outer join personal_details pd1 on exceptioncomments.CreatedBy = pd1.EmployeeID where ((cast(exception.DateFrom as date) between cast(? as date) and cast(? as date)) or  (cast(exception.DateTo as date) between cast(? as date) and cast(? as date)))  and  $tablename.EmployeeID in ( select t1.EmployeeID from (select EmployeeID from  status_table where ReportTo in(select EmployeeID from  status_table where ReportTo in(select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?))) union select EmployeeID from  status_table where ReportTo in(select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?)) union select EmployeeID from  status_table where ReportTo in (select EmployeeID from status_table where  ReportTo = ?) union select EmployeeID from status_table where   ReportTo = ? or EmployeeID = ? or Qa_ops = ? ) t1)  GROUP BY exceptioncomments.ExpID;";
					$selectQ = $conn->prepare($Select);
					$selectQ->bind_param("ssssssssss", $date_From, $date_To, $date_From, $date_To, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();

					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Exception</th>';
						$table .= '<th>MgrStatus</th>';
						/*$table .='<th>HeadStatus</th>';*/
						$table .= '<th>DateOn</th>';
						$table .= '<th>DateFrom</th>';
						$table .= '<th>DateTo</th>';
						$table .= '<th>Current Attendance</th>';
						$table .= '<th>Updated Attendance</th>';
						$table .= '<th>Roster In</th>';
						$table .= '<th>Roster Out</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Supervisor</th>';
						$table .= '<th>ModifiedBy</th>';
						$table .= '<th>Approved By</th>';
						$table .= '<th>ModifiedOn</th>';
						$table .= '<th>Comments</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Exception'] . '</td>';
							$table .= '<td>' . $value['MgrStatus'] . '</td>';
							/*$table .='<td>'.$value['HeadStatus'].'</td>';*/
							$table .= '<td>' . $value['CreatedOn'] . '</td>';
							$table .= '<td>' . $value['DateFrom'] . '</td>';
							$table .= '<td>' . $value['DateTo'] . '</td>';
							if ($value['Exception'] == 'Biometric issue' || $value['Exception'] == 'Biometric Issue') {
								$table .= '<td>' . $value['Current_Att'] . '</td>';
								$table .= '<td>' . $value['Update_Att'] . '</td>';
							} else {
								$table .= '<td>NA</td>';
								$table .= '<td>NA</td>';
							}
							$table .= '<td>' . $value['ShiftIn'] . '</td>';
							$table .= '<td>' . $value['ShiftOut'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							if ($value['ModifiedBy'] == ' SERVER') {
								$table .= '<td>SERVER</td>';
							} else {
								$table .= '<td>ACCOUNT HEAD</td>';
							}
							$comment = explode('|', $value['Comments']);
							$string = 'Approved by  SERVER';
							$modify = (empty($value['ModifiedOn'])) ? '' : '(' . date('Y-m-d', strtotime($value['ModifiedOn'])) . ')';
							$attr_val = $modify . ' ' . $value['ModifiedBy'];
							foreach ($comment as $url) {

								if (preg_match("/\b$string\b/i", $url)) {
									$attr_val  =  $url;
								}
							}
							$table .= '<td>' . $attr_val . '</td>';
							$table .= '<td>' . $modify . '</td>';
							$table .= '<td>' . $value['Comments'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
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
<script>
	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>