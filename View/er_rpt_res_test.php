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
// echo $_SESSION['__cm_id'];
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
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

		if ($isPostBack && isset($_POST['txt_dateTo'])) {
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

/*if(file_exists('../Vacination/NA'))
{
	echo 'Yes';
}
else
{
	echo 'No';
}*/
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
	<span id="PageTittle_span" class="hidden">ER Resign Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>ER Resign Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" readonly="true" value="<?php echo $date_From; ?>" />
					</div>

					<div class="input-field col s3 m3">
						<input type="text" name="txt_dateTo" id="txt_dateTo" readonly="true" value="<?php echo $date_To; ?>" />
					</div>

					<!-- <div class="input-field col s3 m3">
						<select id="er_location" name="er_location" required>
							<option value=" NA">Select Location</option>
							<?php
							// $sqlBy = 'select id,location from location_master';
							// $stmt = $conn->prepare($sqlBy);
							// $stmt->execute();
							// $resultBy = $stmt->get_result();
							// if ($resultBy->num_rows > 0) {
							// 	foreach ($resultBy as $key => $value) {
							// 		echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
							// 	}
							// }
							?>
						</select>
						<label for="er_location" class="active-drop-down active">Location</label>
					</div> -->


					<div class="input-field col s2 m2">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					$sqlstr = '';
					$user_type = clean($_SESSION['__user_type']);
					$status_ah = clean($_SESSION['__status_ah']);
					$user_logID = clean($_SESSION['__user_logid']);
					$location = clean($_SESSION["__location"]);
					$status_er = clean($_SESSION['__status_er']);
					$__cm_id = clean($_SESSION['__cm_id']);
					if ($user_type == 'HR' && ($status_ah != 'No' && $status_ah == $user_logID) && $status_ah != '') {
						$sqlstr = "select resign_details.*,Comments,whole_dump_emp_data.EmployeeName,pd1.EmployeeName as 'account_head',pd.EmployeeName as 'supervisor',designation,DOJ,clientname,dept_name,Process,sub_process,l1.location from resign_details  left outer join (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd on pd.EmployeeID = ReportTo join location_master l1 on l1.id= pd.location where  ((resign_details.nt_end between ? and ?) or (resign_details.nt_start between ? and ?)) and whole_dump_emp_data.location=?";
						$selectQ = $conn->prepare($sqlstr);
						$selectQ->bind_param("ssssi", $date_From, $date_To, $date_From, $date_To, $location);
						$selectQ->execute();
						// $chk_task = $selectQ->get_result();
					} else if ($status_er != 'No' && $status_er == $user_logID && $status_er != '') {
						if ($user_logID == "CE021929762") {
							$sqlstr = "select resign_details.*,Comments,whole_dump_emp_data.EmployeeName,pd1.EmployeeName as 'account_head',pd.EmployeeName as 'supervisor',designation,DOJ,clientname,dept_name,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,l1.location from resign_details  left outer join (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd on pd.EmployeeID = ReportTo join location_master l1 on l1.id= pd.location join new_client_master nw on whole_dump_emp_data.cm_id=nw.cm_id where  ((resign_details.nt_end between ? and ?) or (resign_details.nt_start between ? and ?)) ";
							$selectQ = $conn->prepare($sqlstr);
							$selectQ->bind_param("ssss", $date_From, $date_To, $date_From, $date_To);
							$selectQ->execute();
						} else if ($status_er == $user_logID) {
							$sqlstr = "select resign_details.*,Comments,whole_dump_emp_data.EmployeeName,pd1.EmployeeName as 'account_head',pd.EmployeeName as 'supervisor',designation,DOJ,clientname,dept_name,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,l1.location from resign_details  left outer join (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd on pd.EmployeeID = ReportTo join location_master l1 on l1.id= pd.location join new_client_master nw on whole_dump_emp_data.cm_id=nw.cm_id where  ((resign_details.nt_end between ? and ?) or (resign_details.nt_start between ? and ?)) and whole_dump_emp_data.location=? and nw.er_scop= ?";


							$selectQ = $conn->prepare($sqlstr);
							$selectQ->bind_param("ssssis", $date_From, $date_To, $date_From, $date_To, $location, $user_logID);

							$selectQ->execute();
						}
					} else if ($user_logID == "CE0122942656" || $user_logID == "CE12102224") {
						$sqlstr = "select resign_details.*,Comments,whole_dump_emp_data.EmployeeName,pd1.EmployeeName as 'account_head',pd.EmployeeName as 'supervisor',designation,DOJ,clientname,dept_name,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,l1.location from resign_details  left outer join (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd on pd.EmployeeID = ReportTo join location_master l1 on l1.id= pd.location join new_client_master nw on whole_dump_emp_data.cm_id=nw.cm_id where  ((resign_details.nt_end between ? and ?) or (resign_details.nt_start between ? and ?)) ";
						$selectQ = $conn->prepare($sqlstr);
						$selectQ->bind_param("ssss", $date_From, $date_To, $date_From, $date_To);
						$selectQ->execute();
					} else {
						$sqlstr = "select resign_details.*,Comments,whole_dump_emp_data.EmployeeName,pd1.EmployeeName as 'account_head',pd.EmployeeName as 'supervisor',designation,DOJ,clientname,dept_name,Process,sub_process,l1.location from resign_details  left outer join (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd on pd.EmployeeID = ReportTo join location_master l1 on l1.id= pd.location where  ((resign_details.nt_end between ? and ?) or (resign_details.nt_start between ? and ?)) and whole_dump_emp_data.location=? and whole_dump_emp_data.cm_id=?";
						$selectQ = $conn->prepare($sqlstr);
						$selectQ->bind_param("ssssii", $date_From, $date_To, $date_From, $date_To, $location, $__cm_id);
						$selectQ->execute();
						// $chk_task = $selectQ->get_result();
					}
					$chk_task = $selectQ->get_result();
					// print_r($chk_task);
					// die;
					if ($chk_task->num_rows > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Notice Start</th>';
						$table .= '<th>Notice End</th>';
						$table .= '<th>Final Status</th>';

						$table .= '<th>Revoke Status</th>';
						$table .= '<th>Revoke Date</th>';
						$table .= '<th>Revoke Remark</th>';
						$table .= '<th>Revoke Accept Date (AH)</th>';
						$table .= '<th>Revoke Remark (AH)</th>';
						$table .= '<th>Revoke Accept Date (HR)</th>';
						$table .= '<th>Revoke Remark (HR)</th>';


						$table .= '<th>HR Status</th>';
						$table .= '<th>Requester Remark</th>';
						$table .= '<th>HR Remark</th>';
						$table .= '<th>File</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Supervisor</th>';

						$table .= '<th>Account Head</th>';
						$table .= '<th>Comments</th>';
						$table .= '<th>Location</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';

							$table .= '<td>' . $value['nt_start'] . '</td>';
							$table .= '<td>' . $value['nt_end'] . '</td>';
							if ($value['rg_status'] == '1') {
								$table .= '<td>accepted</td>';
							} elseif ($value['rg_status'] == '0' || empty($value['rg_status'])) {
								$table .= '<td>pending</td>';
							} elseif ($value['rg_status'] == '9') {
								$table .= '<td>decline</td>';
							} else {
								$table .= '<td></td>';
							}


							if ($value['revoke_status'] == 1 && empty($value['revoke_accept']) && $value['rg_status'] != 1) {
								$table .= '<td>Revoke Request to AH by Employee</td>';
							} elseif ($value['revoke_status'] == 1 && $value['revoke_accept'] == 0) {
								$table .= '<td>Revoke Request cancel by AH</td>';
							} elseif ($value['revoke_status'] == 1 && $value['revoke_accept'] == 1 && $value['rg_status'] != 1) {
								$table .= '<td>Revoke Request accept by AH and refer to HR</td>';
							} elseif ($value['revoke_status'] == 1 && $value['revoke_accept'] == 2 && $value['rg_status'] != 1) {
								$table .= '<td>Revoke Request accept by AH and HR</td>';
							} elseif ($value['revoke_status'] == 1 && $value['revoke_accept'] == 3) {
								$table .= '<td>Revoke Request accept by AH and cancel by HR</td>';
							} elseif ($value['rg_status'] == 1 && $value['revoke_status'] == 1) {
								$table .= '<td>Decline by server</td>';
							} else {
								$table .= '<td></td>';
							}
							$table .= '<td>' . $value['revoke_on'] . '</td>';
							$table .= '<td>' . $value['revoke_comment'] . '</td>';

							$table .= '<td>' . $value['revoke_ah'] . '</td>';

							$table .= '<td>' . $value['rv_ah_remark'] . '</td>';

							$table .= '<td>' . $value['revoke_hr'] . '</td>';

							$table .= '<td>' . $value['rv_hr_remark'] . '</td>';

							if ($value['accept'] == '1') {
								$table .= '<td>accepted</td>';
							} elseif ($value['accept'] == '0' || empty($value['accept'])) {
								$table .= '<td>pending</td>';
							} else {
								$table .= '<td></td>';
							}

							$table .= '<td>' . $value['remark'] . '</td>';
							$table .= '<td>' . $value['accepter_remark'] . '</td>';

							if (!empty($value['file'])) {
								$table .= '<td><a href="../Resign/' . $value['file'] . '" target="_blank">' . $value['file'] . '</a></td>';
							} else {
								$table .= '<td>No File</td>';
							}
							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['supervisor'] . '</td>';
							$table .= '<td>' . $value['account_head'] . '</td>';
							$table .= '<td>' . $value['Comments'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td></tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
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

<script>
	$("#btn_view").click(function() {
		//alert($("#er_location").val());

	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>