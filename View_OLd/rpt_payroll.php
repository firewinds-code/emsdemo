<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Only for user type administrator
$clean_user_type = clean($_SESSION['__user_type']);
if ($clean_user_type != 'ADMINISTRATOR') // || $clean_user_logid == 'CE10091236')
{
	$location = URL . 'Error';
	//header("Location: $location");
	echo "<script>location.href='" . $location . "'</script>";
	exit();
}

// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$error_def = $process = '';



if (isset($_SESSION)) {
	$clean_user_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_logid)) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
		exit();
	} else {
		if (!($clean_user_logid == 'CE03070003' || $clean_user_logid == 'CE10091236')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	exit();
}

$DateTo = '';
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_datefor = cleanUserInput($_POST['txt_dateFor']);
}
if (isset($clean_datefor)) {
	$DateTo = $clean_datefor;
} else {
	$DateTo = date('F Y', strtotime("previous month"));
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_clfs = cleanUserInput($_POST['ddl_clfs_Process']);
}
if (isset($clean_clfs)) {
	$process = $clean_clfs;
}
function _daysInMonth($month = null, $year = null)
{

	if (null == ($year))
		$year =  date("Y", time());

	if (null == ($month))
		$month = date("m", time());

	return date('t', strtotime($year . '-' . $month . '-01'));
}
function getMax($array, $key)
{
	$max = 0;
	foreach ($array as $k => $v) {
		$max = max(array($max, $v[$key]));
	}
	return $max;
}
?>

<script>
	$(function() {
		$('#txt_dateFor').datepicker_M({
			format: 'mmmm yyyy'
		});
		$(document).on("click blur focus change", ".pika-select", function() {
			$(".datepicker_M-day-button[data-pika-day='1']").trigger("click");
			$('select').formSelect();

		});
		$('select').formSelect();
		$(".datepicker_M-cancel").removeClass("btn-flat").addClass("btn close-btn").css("margin-right", "10px");
		$(".datepicker_M-done").removeClass("btn-flat").addClass("btn");

		$(".datepicker_M-done").click(function() {
			var month = $("select.pika-select.pika-select-month :selected").val();
			var year = $("select.pika-select.pika-select-year :selected").val();
			var month_text = $("select.pika-select.pika-select-month option:selected").text();
			$('#txt_dateFor').val(month_text + " " + year);
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 13, 15, 25, 50, -1],
				['10 rows', '13 rows', '15 rows', '25 rows', '50 rows', 'Show all']
			],
			"pageLength": 13,
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
<style>
	.ui-accordion .ui-accordion-icons {
		text-align: center;
	}

	.datepicker_M-table-wrapper,
	.datepicker_M-date-display {
		display: none;
	}

	.datepicker_M-calendar-container {
		overflow: hidden;
		padding: 0px !important;
	}

	.datepicker_M-modal {
		max-width: 350px;
	}
</style>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Dynamic Payroll Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Dynamic Payroll Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="col s12 m12" id="rpt_container">
					<div class="input-field col s6 m6">
						<input type="text" name="txt_dateFor" id="txt_dateFor" select-year="15" value="<?php echo $DateTo; ?>" />
						<label for="txt_dateFor">Date For</label>
					</div>
					<div class="input-field col s6 m6">


						<select id="ddl_clfs_Process" name="ddl_clfs_Process">
							<option value="0">Select Process</option>
							<option value="ALL">All Process</option>
							<?php
							$sqlBy = 'SELECT distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
							$myDB = new MysqliDb();

							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							if (empty($mysql_error)) {
								foreach ($resultBy as $key => $value) {
									if ($process == $value['cm_id']) {
										if ($value['Process'] == $value['sub_process']) {
											echo '<option value="' . $value['cm_id'] . '"  selected> ' . $value['clientname'] . ' | ' . $value['sub_process'] . '</option>';
										} else {
											echo '<option value="' . $value['cm_id'] . '"  selected>' . $value['clientname'] . ' | ' . $value['Process'] . ' | ' . $value['sub_process'] . '</option>';
										}
									} else {
										if ($value['Process'] == $value['sub_process']) {
											echo '<option value="' . $value['cm_id'] . '"  >' . $value['clientname'] . ' | ' . $value['sub_process'] . '</option>';
										} else {
											echo '<option value="' . $value['cm_id'] . '"  >' . $value['clientname'] . ' | ' . $value['Process'] . ' | ' . $value['sub_process'] . '</option>';
										}
									}
								}
							}
							?>
						</select>
						<label for="ddl_clfs_Process" class="dropdown-active active">Process</label>
					</div>


					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>
						<button type="button" class="btn waves-effect waves-green hidden" name="btnExport" id="btnExport">
							<i class="fa fa-download"></i> Export</button>
						<?php if (in_array(date('d', time()), array(10, 11, 12, 13, 30))) { ?>
							<button type="submit" class="btn waves-effect waves-green" name="btn_lock" id="btn_lock">
								<i class="fa fa-lock"></i> Lock Payroll</button>
						<?php } ?>
					</div>
				</div>
				<?php
				function getDatesFromRange($start, $end, $format = 'd')
				{
					$array = array();
					$interval = new DateInterval('P1D');
					$realEnd = new DateTime($end);
					$realEnd->add($interval);
					$period = new DatePeriod(new DateTime($start), $interval, $realEnd);
					foreach ($period as $date) {
						$array[] = intval($date->format($format));
					}
					sort($array);
					return $array;
				}

				$clean_user_logid = clean($_SESSION['__user_logid']);
				// $clean_clfs = cleanUserInput($_POST['ddl_clfs_Process']);
				if (isset($_POST['btn_view']) || isset($_POST['btn_lock'])) {
					$myDB = new MysqliDb();
					$chk_task = array();
					if ($clean_clfs == 'ALL') {
						// echo '<script>alert("dddd")</script>';
						$chk_taskq = ("SELECT whole_dump_emp_data.EmployeeID,whole_dump_emp_data.FatherName , whole_dump_emp_data.EmployeeName, whole_dump_emp_data.DOB, whole_dump_emp_data.MotherName, whole_dump_emp_data.Gender, whole_dump_emp_data.BloodGroup,whole_dump_emp_data.emp_status, whole_dump_emp_data.cm_id, whole_dump_emp_data.df_id,whole_dump_emp_data.DOJ, whole_dump_emp_data.Process, whole_dump_emp_data.sub_process, whole_dump_emp_data.account_head,whole_dump_emp_data.oh, whole_dump_emp_data.qh, whole_dump_emp_data.th, whole_dump_emp_data.client_name, whole_dump_emp_data.clientname, whole_dump_emp_data.function,whole_dump_emp_data.des_id,whole_dump_emp_data.designation, whole_dump_emp_data.dept_id,whole_dump_emp_data.dept_name, whole_dump_emp_data.status,whole_dump_emp_data.ReportTo,whole_dump_emp_data.Qa_ops, whole_dump_emp_data.BatchID,whole_dump_emp_data.Quality, whole_dump_emp_data.DOD,whole_dump_emp_data.Trainer,salary_details.sal_id,salary_details.pf_account,salary_details.esi_no,salary_details.esi_file,salary_details.ctc,salary_details.pf,salary_details.esis,salary_details.takehome,salary_details.emptype,salary_details.payrolltype,salary_details.hra,salary_details.convence,salary_details.bonus,salary_details.sp_allow,salary_details.gross_sal,salary_details.basic,salary_details.professional_tex,salary_details.pf_employer,salary_details.esi_employer, salary_details.min_wages,salary_details.pli_ammount,salary_details.pli_percent,salary_details.net_takehome,salary_details.pli_status,salary_details.pf_status,salary_details.pli_mode,salary_details.rt_type,salary_details.pf_employer_8_33,salary_details.pf_employer_3_67,salary_details.pf_employer_1_36,salary_details.uan_no,salary_details.pli_effected,bank_details.bank_id,bank_details.BankName,bank_details.AccountNo,bank_details.Branch,bank_details.Location,bank_details.Active,calc_atnd_master.D1,calc_atnd_master.D2,calc_atnd_master.D3,calc_atnd_master.D4,calc_atnd_master.D5,calc_atnd_master.D6,calc_atnd_master.D7,calc_atnd_master.D8,calc_atnd_master.D9,calc_atnd_master.D10,calc_atnd_master.D11,calc_atnd_master.D12,calc_atnd_master.D13,calc_atnd_master.D14,calc_atnd_master.D15,calc_atnd_master.D16,calc_atnd_master.D17,calc_atnd_master.D18,calc_atnd_master.D19,calc_atnd_master.D20,calc_atnd_master.D21,calc_atnd_master.D22,calc_atnd_master.D23,calc_atnd_master.D24,calc_atnd_master.D25,calc_atnd_master.D26,calc_atnd_master.D27,calc_atnd_master.D28,calc_atnd_master.D29,calc_atnd_master.D30,calc_atnd_master.D31  from whole_dump_emp_data inner join  calc_atnd_master on calc_atnd_master.EmployeeID = whole_dump_emp_data.EmployeeID and month=? and year =? inner join  salary_details on whole_dump_emp_data.EmployeeID = salary_details.EmployeeID left outer join bank_details on whole_dump_emp_data.EmployeeID = bank_details.EmployeeID and bank_details.Active = 'Active' where  whole_dump_emp_data.DOJ<=? and case when whole_dump_emp_data.emp_status = 'InActive' then  whole_dump_emp_data.EmployeeID in (select distinct EmployeeID from exit_emp  inner join (select max(id) id from exit_emp group by EmployeeID) t1 on t1.id = exit_emp.id where exit_emp.dol >= ?)  else true end ;");
						$stmt = $conn->prepare($chk_taskq);
						$stmt->bind_param("ssss",  date("m", strtotime($DateTo)), date("Y", strtotime($DateTo)), date("Y-m-t", strtotime($DateTo)), date("Y-m-01", strtotime($DateTo)));
						$stmt->execute();
						$chk_task = $stmt->get_result();
						// echo '<script>alert(' . $chk_task->num_rows . ')</script>';
						// die;


						// 
					} else {

						$chk_taskq = ("SELECT whole_dump_emp_data.EmployeeID,whole_dump_emp_data.FatherName , whole_dump_emp_data.EmployeeName, whole_dump_emp_data.DOB, whole_dump_emp_data.MotherName, whole_dump_emp_data.Gender, whole_dump_emp_data.BloodGroup,whole_dump_emp_data.emp_status, whole_dump_emp_data.cm_id, whole_dump_emp_data.df_id,whole_dump_emp_data.DOJ, whole_dump_emp_data.Process, whole_dump_emp_data.sub_process, whole_dump_emp_data.account_head,whole_dump_emp_data.oh, whole_dump_emp_data.qh, whole_dump_emp_data.th, whole_dump_emp_data.client_name, whole_dump_emp_data.clientname, whole_dump_emp_data.function,whole_dump_emp_data.des_id,whole_dump_emp_data.designation, whole_dump_emp_data.dept_id,whole_dump_emp_data.dept_name, whole_dump_emp_data.status,whole_dump_emp_data.ReportTo,whole_dump_emp_data.Qa_ops, whole_dump_emp_data.BatchID,whole_dump_emp_data.Quality, whole_dump_emp_data.DOD,whole_dump_emp_data.Trainer,salary_details.sal_id,salary_details.pf_account,salary_details.esi_no,salary_details.esi_file,salary_details.ctc,salary_details.pf,salary_details.esis,salary_details.takehome,salary_details.emptype,salary_details.payrolltype,salary_details.hra,salary_details.convence,salary_details.bonus,salary_details.sp_allow,salary_details.gross_sal,salary_details.basic,salary_details.professional_tex,salary_details.pf_employer,salary_details.esi_employer, salary_details.min_wages,salary_details.pli_ammount,salary_details.pli_percent,salary_details.net_takehome,salary_details.pli_status,salary_details.pf_status,salary_details.pli_mode,salary_details.rt_type,salary_details.pf_employer_8_33,salary_details.pf_employer_3_67,salary_details.pf_employer_1_36,salary_details.uan_no,salary_details.pli_effected,bank_details.bank_id,bank_details.BankName,bank_details.AccountNo,bank_details.Branch,bank_details.Location,bank_details.Active,calc_atnd_master.D1,calc_atnd_master.D2,calc_atnd_master.D3,calc_atnd_master.D4,calc_atnd_master.D5,calc_atnd_master.D6,calc_atnd_master.D7,calc_atnd_master.D8,calc_atnd_master.D9,calc_atnd_master.D10,calc_atnd_master.D11,calc_atnd_master.D12,calc_atnd_master.D13,calc_atnd_master.D14,calc_atnd_master.D15,calc_atnd_master.D16,calc_atnd_master.D17,calc_atnd_master.D18,calc_atnd_master.D19,calc_atnd_master.D20,calc_atnd_master.D21,calc_atnd_master.D22,calc_atnd_master.D23,calc_atnd_master.D24,calc_atnd_master.D25,calc_atnd_master.D26,calc_atnd_master.D27,calc_atnd_master.D28,calc_atnd_master.D29,calc_atnd_master.D30,calc_atnd_master.D31  from whole_dump_emp_data inner join  calc_atnd_master on calc_atnd_master.EmployeeID = whole_dump_emp_data.EmployeeID and month=? and year =? inner join  salary_details on whole_dump_emp_data.EmployeeID = salary_details.EmployeeID left outer join bank_details on whole_dump_emp_data.EmployeeID = bank_details.EmployeeID and bank_details.Active = 'Active'  where whole_dump_emp_data.cm_id = ? and whole_dump_emp_data.DOJ<=? and  case when whole_dump_emp_data.emp_status = 'InActive' then  whole_dump_emp_data.EmployeeID in (select distinct EmployeeID from exit_emp  inner join (select max(id) id from exit_emp group by EmployeeID) t1 on t1.id = exit_emp.id where exit_emp.dol >= ?)  else true end;");
						$stmt = $conn->prepare($chk_taskq);
						$stmt->bind_param("sssss", date("m", strtotime($DateTo)), date("Y", strtotime($DateTo)), $clean_clfs, date("Y-m-t", strtotime($DateTo)), date("Y-m-01", strtotime($DateTo)));
						$stmt->execute();
						$chk_task = $stmt->get_result();
						// echo '<script>alert(' . $chk_task->num_rows . ')</script>';


						// 
					}
					$counter = 0;
					$my_error = $myDB->getLastError();;
					if ($chk_task->num_rows > 0 && $chk_task) {
						$date_first = date("Y-m-01", strtotime($DateTo));
						$date_last = date("Y-m-t", strtotime($DateTo));
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;" id="tbl_div"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Employee ID</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Employee Name</th>';
						$table .= '<th style="background-color:#cc99ff;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Salary Month</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">DOJ</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">DOD</th>';
						$table .= '<th style="color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Calculated Deployment</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Designation</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Client</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Process</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Sub Process</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Emp. Type</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Payroll Type</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Roster Type</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Gender</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Father\'s Name</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Mother\'s Name</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">DOB</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Bank Name</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Bank Account Number</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Name as per Bank</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">IFSC Code</th>';
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Emp. Status</th>';


						$table .= '<th style="background-color:#cc99ff;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Total Floor Days</th>';
						$table .= '<th style="background-color:#cc99ff;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">CTC</th>';
						$table .= '<th style="background-color:#cc99ff;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Take Home</th>';




						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Leave Opening Balance</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">P</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">A</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">L</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">H</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">LWP</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">HWP</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">WO</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">LANA</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">WONA</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Total Leaves</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Leave Adjusted</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Pay Days</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">CO Paid</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">CO Less</th>';
						$table .= '<th style="background-color:#ffa906;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Total Pay Days</th>';


						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Attendnace Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Split Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">OT Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Night/Mor Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Ref Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Lylty/PLI Bonus</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Trainig Stipend</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Client Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Arrears</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Other Inct.</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Other Narration</th>';
						$table .= '<th style="background-color:#2ace0d;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Total Add</th>';


						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Asset Damage Deduction</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Id/Access Card Damage Deduction</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Guest House Rent</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">TDS</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Recovery Day</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Recovery Amount</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Other Less</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Other Narration</th>';
						$table .= '<th style="background-color:#f1571a;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Total Less</th>';


						$table .= '<th style="background-color:#6dffc3;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Salary</th>';
						$table .= '<th style="background-color:#6dffc3;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Payable Salary</th>';
						$table .= '<th style="background-color:#6dffc3;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">By Bank</th>';
						$table .= '<th style="background-color:#6dffc3;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">By Cash</th>';

						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Basic</th>';
						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Payable Basic</th>';
						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">EMP PF 12%</th>';
						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">EMPR PF 3.67%</th>';
						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">EMPR PF 8.33%</th>';
						$table .= '<th style="background-color:#7eb216;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">EMPR PF 1.36%</th>';

						$table .= '<th style="background-color:#9c5e00;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Gross</th>';
						$table .= '<th style="background-color:#9c5e00;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Payable Gross</th>';
						$table .= '<th style="background-color:#9c5e00;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">ESIC 1.75%</th>';
						$table .= '<th style="background-color:#9c5e00;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">ESIC 4.75%</th>';



						$table .= '<th style="background-color:#0facd2;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Resignation Date</th>';
						$table .= '<th style="background-color:#0facd2;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Last Working Date</th>';
						$table .= '<th style="background-color:#0facd2;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Reason of Leaving</th>';
						$table .= '<th style="background-color:#0facd2;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Salary Status</th>';
						$table .= '<th style="background-color:#0facd2;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Remarks If Any</th>';

						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$array_to_insert = array();

							$EmployeeID = $value['EmployeeID'];
							$table .= '<tr>';
							$table .= '<td class="EmployeeDetail" style="font-weight: bold;cursor: pointer;color: royalblue;    text-transform: uppercase;" empid="' . base64_encode($value['EmployeeID']) . '" onclick="javascript:return emp_detials(this);">' . $value['EmployeeID'] . '</td>';
							$array_to_insert['EmployeeID'] = $EmployeeID;

							$myDB = new MysqliDb();
							$data_rt_type = $myDB->query("SELECT sf_payroll_rt_type('" . $EmployeeID . "','" . $date_last . "') rt_type limit 1;");
							if (count($data_rt_type) > 0 && $data_rt_type) {
								$tmp_val_sal = $data_rt_type[0]['rt_type'];
								$value['rt_type'] = $data_rt_type[0]['rt_type'];
							}



							$table .= '<td><b>' . $value['EmployeeName'] . '</b></td>';
							$table .= '<td>' . date('F Y', strtotime($date_first)) . '</td>';
							$array_to_insert['EmployeeName'] = $value['EmployeeName'];
							$array_to_insert['Salary Month'] = date('F Y', strtotime($date_first));

							$DOJ = $value['DOJ'];
							$myDB  = new MysqliDb();
							// $data_dod = $myDB->query("select first_dod,day_stpn from personal_details where EmployeeID ='" . $EmployeeID . "'");
							$data_dodq = "SELECT first_dod,day_stpn from personal_details where EmployeeID =?";
							// echo '<script> alert("dfdfdf")</script>';
							$stmt = $conn->prepare($data_dodq);
							$stmt->bind_param("s", $EmployeeID);
							$stmt->execute();
							$data_dod = $stmt->get_result();
							$data_dodcount = $data_dod->fetch_row();
							// print_r($data_dodcount);
							$DOD = $value['DOD'];
							$date_fd  = date('Y-m-d', strtotime('-1 days ' . $DOJ));
							$Stipend_pd_amt = 0;

							if (isset($data_dodcount[0]) && ($value['df_id'] == 74 || $value['df_id'] == 77)) {
								// echo '<script> alert("dfdfdf")</script>';
								if (!empty($data_dodcount[0]) && strtotime($data_dodcount[0])) {
									// echo '<script> alert("dfdfdf")</script>';
									$date_fd = date('Y-m-d', strtotime('-1 days ' . $data_dodcount[0]));
									$Stipend_pd_amt = $data_dodcount[1];
								}
							}
							$table .= '<td>' . date('Y-m-d', strtotime($value['DOJ'])) . '</td>';
							$array_to_insert['Date Of Joining'] = date('Y-m-d', strtotime($value['DOJ']));
							if ($DOD == 'NA' || empty($DOD)) {
								$table .= '<td>-</td>';
								$array_to_insert['Date Of Deployment'] = '-';
							} else {
								$table .= '<td>' . date('Y-m-d', strtotime($value['DOD'])) . '</td>';
								$array_to_insert['Date Of Deployment']	= date('Y-m-d', strtotime($value['DOD']));
							}


							$table .= '<td>' . date('Y-m-d', strtotime('+1 days ' . $date_fd)) . '</td>';
							$array_to_insert['Calculated Deployment'] = date('Y-m-d', strtotime('+1 days ' . $date_fd));

							$array_to_insert['Designation'] = $value['designation'];
							$array_to_insert['Client'] = $value['clientname'];
							$array_to_insert['Process'] = $value['Process'];
							$array_to_insert['Sub Process'] = $value['sub_process'];

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';

							if ($value['emptype'] == 'FT') {
								$table .= '<td>Full Time</td>';
								$array_to_insert['Emp Type'] = 'Full Time';
							} elseif ($value['emptype'] == '') {
								$table .= '<td></td>';
								$array_to_insert['Emp Type'] = '';
							} else {
								$table .= '<td>Part Time</td>';
								$array_to_insert['Emp Type'] = 'Part Time';
							}
							if ($value['payrolltype'] == 'RT') {
								$table .= '<td>Retainership</td>';
								$array_to_insert['Payroll Type'] = 'Retainership';
							} elseif ($value['payrolltype'] == '') {
								$table .= '<td></td>';
								$array_to_insert['Payroll Type'] = '';
							} elseif ($value['payrolltype'] == 'INPE') {
								$table .= '<td>On Roll Under PF & ESI Slab</td>';
								$array_to_insert['Payroll Type'] = 'On Roll Under PF & ESI Slab';
							} else {
								$table .= '<td>On Roll Above PF & ESI Slab</td>';
								$array_to_insert['Payroll Type'] = 'On Roll Above PF & ESI Slab';
							}

							if ($value['rt_type'] == 3) {
								$array_to_insert['Roster Type'] = 'Part Time';
							} else if ($value['rt_type'] == 4) {
								$array_to_insert['Roster Type'] = 'Split Time';
							} else {
								$array_to_insert['Roster Type'] = 'Full Time';
							}
							$table .= '<td>' . $array_to_insert['Roster Type'] . '</td>';

							$table .= '<td>' . $value['Gender'] . '</td>';
							$table .= '<td>' . $value['FatherName'] . '</td>';
							$table .= '<td>' . $value['MotherName'] . '</td>';
							$table .= '<td>' . date('Y-m-d', strtotime($value['DOB'])) . '</td>';
							$table .= '<td>' . $value['BankName'] . '</td>';
							$table .= '<td>\'' . $value['AccountNo'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>IFSC COL</td>';	//$table .='<td>'.$value['IFSC'].'</td>';	
							$table .= '<td>' . $value['emp_status'] . '</td>';
							$array_to_insert['GENDER'] = $value['Gender'];
							$array_to_insert['Father Name'] = $value['FatherName'];
							$array_to_insert['Mother Name'] = $value['MotherName'];
							$array_to_insert['Date Of Birth'] = date('Y-m-d', strtotime($value['DOB']));
							$array_to_insert['Bank Name'] = $value['BankName'];
							$array_to_insert['Bank Account Number'] = $value['AccountNo'];
							$array_to_insert['Name as per Bank'] = $value['EmployeeName'];
							$array_to_insert['IFSC Code'] = 'IFSC COL';
							$array_to_insert['Emp Status'] = $value['emp_status'];
							$data_status_dsp = array();
							$till_date = $date_last;

							if (strtoupper($value['emp_status']) == 'INACTIVE') {

								$myDB = new MysqliDb();
								$data_status_dspq = ('SELECT rsnofleaving,disposition,dol from exit_emp where EmployeeID = ? order by id desc limit 1;');
								$stmt1 = $conn->prepare($data_status_dspq);
								$stmt1->bind_param("s", $EmployeeID);
								$stmt1->execute();
								$data_status_dsp = $stmt1->get_result();
								// echo '<script> alert("dfdfdf")</script>';
								// 
								if (strtotime($data_status_dsp[0]['dol'])) {
									if (date('Y-m', strtotime($data_status_dsp[0]['dol'])) ==  date('Y-m', strtotime($date_last))) {
										if (strtoupper($data_status_dsp[0]['disposition']) == 'RES' || strtoupper($data_status_dsp[0]['rsnofleaving']) == 'RES') {
											$till_date = date('Y-m-d', strtotime($data_status_dsp[0]['dol']));
										} else {
											$till_date = date('Y-m-d', strtotime('-1 days ' . $data_status_dsp[0]['dol']));
										}
									}
								}
							}

							$tfd = '0';
							if ($date_first == date("Y-m-01", strtotime("previous day"))) {
								if ($till_date > date("Y-m-d", strtotime("previous day"))) {
									$till_date = date("Y-m-d", strtotime("previous day"));
								}
							}

							if (strtotime($date_fd) >= strtotime($DOJ)) {
								if (strtotime($date_fd) <= strtotime($till_date) && strtotime($date_fd) >= strtotime($date_first)) {
									$check_date_ttl = '';
									$check_date_ttl = date('Y-m-d', strtotime('+1 days ' . $date_fd));
									$tmp_date1 = date_create($check_date_ttl);
									$tmp_date2 = date_create($till_date);
									$diff = date_diff($tmp_date1, $tmp_date2);
									$tfd = $diff->format("%r%a") + 1;
								} else if (strtotime($date_fd) <= strtotime($till_date) && strtotime($date_fd) < strtotime($date_first)) {

									$tmp_date1 = date_create($date_first);
									$tmp_date2 = date_create($till_date);
									$diff = date_diff($tmp_date1, $tmp_date2);
									$tfd = $diff->format("%r%a") + 1;
								}
							} else {
								if (strtotime($DOJ) <= strtotime($till_date) && strtotime($DOJ) >= strtotime($date_first)) {

									$tmp_date1 = date_create($DOJ);
									$tmp_date2 = date_create($till_date);
									$diff = date_diff($tmp_date1, $tmp_date2);
									$tfd = $diff->format("%r%a") + 1;
								} else if (strtotime($DOJ) <= strtotime($till_date) && strtotime($DOJ) < strtotime($date_first)) {

									$tmp_date1 = date_create($date_first);
									$tmp_date2 = date_create($till_date);
									$diff = date_diff($tmp_date1, $tmp_date2);
									$tfd = $diff->format("%r%a") + 1;
								}
							}

							$table .= '<td>' . $tfd . '</td>';
							$table .= '<td>' . round($value['ctc'], 0) . '</td>';
							$table .= '<td>' . round($value['net_takehome'], 0) . '</td>';

							$array_to_insert['Total Floor Days'] = $tfd;
							$array_to_insert['CTC'] = round($value['ctc'], 0);
							$array_to_insert['TK Home'] = round($value['net_takehome'], 0);

							$salary_takehome = round($value['net_takehome'], 2);

							$data_plq = "SELECT paidleave FROM paid_leave_all where date_format(date_paid,'%Y-%M') = date_format(?,'%Y-%M') and date_paid <= curdate() and EmployeeID= ? order by id desc,date_paid desc limit 1";
							$stmt2 = $conn->prepare($data_plq);
							$stmt2->bind_param("ss", $date_first, $EmployeeID);
							$stmt2->execute();
							$data_pl = $stmt2->get_result();
							$data_pl = mysqli_fetch_array($data_pl);
							// echo '<script> alert(' . print_r($data_pl) . ')</script>';


							//$data_pl = $myDB->query('select paidleave,date_paid from paid_leave_all where EmployeeID = "'.$EmployeeID.'" and cast(date_paid as date) = "'.$date_first.'" order by id'); 

							$total_leave_start = 0;

							if ($data_pl) {
								$total_leave_start  = $data_pl['paidleave'];
							} else {
								$total_leave_start  = '0';
							}


							/*$myDB = new MysqliDb();
						$data_atnd = $myDB->query('select D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master where EmployeeID = "'.$EmployeeID.'" and `Month` = "'.intval(date('m',strtotime($date_first))).'" order by modifiedon limit 1'); 
						*/

							$data_atnd = array();
							for ($i = 1; $i <= 31; $i++) {
								$data_atnd['D' . $i] = $value['D' . $i];
							}
							$atnd_A = 0;
							$atnd_P = 0;
							$atnd_H = 0;
							$atnd_L = 0;
							$atnd_LWP = 0;
							$atnd_HWP = 0;
							$atnd_WO = 0;
							$atnd_HO = 0;
							$atnd_LANA = 0;
							$atnd_WONA = 0;
							$atnd_CO = 0;
							if (count($data_atnd) > 0) {

								foreach ($data_atnd as $key => $value_atnd) {
									$date_day = explode('D', $key);
									$date_day = ($date_day[1] < 10) ? '0' . $date_day[1] : $date_day[1];
									$date_val = date("Y-m-" . $date_day, strtotime($date_first));
									if (strtotime($date_val) > strtotime($date_fd) && strtotime($date_val) <= strtotime($till_date) && strtotime($date_val) >= strtotime($DOJ)) {
										//if(isset($value_atnd) and  $value_atnd== 'P')
										if (isset($value_atnd) && $value_atnd != '' && $value_atnd[0] == 'P') {
											$atnd_P++;
										} else if ($value_atnd == 'LWP' || strtoupper($value_atnd) == strtoupper('LWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('LWP(Attendance Change')) {
											$atnd_LWP++;
										} else if ($value_atnd == 'HWP' || strtoupper($value_atnd) == strtoupper('HWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('HWP(Attendance Change')) {
											$atnd_HWP++;
										} else if ($value_atnd == 'L' || strtoupper($value_atnd) == strtoupper('L(Biometric issue)') || strtoupper($value_atnd) == strtoupper('L(Attendance Change)')) {
											$atnd_L++;
										} else if ($value_atnd == 'H' || strtoupper($value_atnd) == strtoupper('H(Biometric issue)') || strtoupper($value_atnd) == strtoupper('H(Attendance Change)')) {
											$atnd_H++;
										} else if (strtoupper(trim($value_atnd)) == 'HO') {
											$atnd_HO++;
										} else if (strtoupper(trim($value_atnd)) == 'WO') {
											$atnd_WO++;
										} else if (strtoupper(trim($value_atnd)) == 'LANA') {
											$atnd_LANA++;
										} else if (strtoupper(trim($value_atnd)) == 'WONA') {
											$atnd_WONA++;
										} else if (strtoupper(trim($value_atnd)) == 'CO') {
											$atnd_CO++;
										} else {
											$atnd_A++;
										}
									}
								}
							}
							$atnd_CO_Less = 0;
							$table .= '<td>' . $total_leave_start . '</td>';
							$array_to_insert['Leave Opening Balance'] = $total_leave_start;

							if ($value['rt_type']  == 3) {
								$table .= '<td>0</td>';
								$array_to_insert['P'] = '0';
							} else {
								$table .= '<td>' . $atnd_P . '</td>';
								$array_to_insert['P'] = $atnd_P;
							}
							$table .= '<td>' . $atnd_A . '</td>';
							$array_to_insert['A'] = $atnd_A;

							if ($value['rt_type']  == 3) {
								$table .= '<td>' . round($atnd_L / 2, 1) . '</td>';
								$array_to_insert['L'] = round($atnd_L / 2, 1);
							} else {
								$table .= '<td>' . $atnd_L . '</td>';
								$array_to_insert['L'] = $atnd_L;
							}
							if ($value['rt_type']  == 3) {
								$table .= '<td>0</td>';
								$array_to_insert['H'] = 0;
							} else {
								$table .= '<td>' . $atnd_H . '</td>';
								$array_to_insert['H'] = $atnd_H;
							}
							$table .= '<td>' . $atnd_LWP . '</td>';
							$table .= '<td>' . $atnd_HWP . '</td>';
							$table .= '<td>' . $atnd_WO . '</td>';
							$table .= '<td>' . $atnd_LANA . '</td>';
							$table .= '<td>' . $atnd_WONA . '</td>';

							$array_to_insert['LWP'] = $atnd_LWP;
							$array_to_insert['HWP'] = $atnd_HWP;
							$array_to_insert['WO'] = $atnd_WO;
							$array_to_insert['LANA'] = $atnd_LANA;
							$array_to_insert['WONA'] = $atnd_WONA;


							if ($value['rt_type']  == 3) {
								$table .= '<td>' . round(($atnd_L / 2), 2) . '</td>';
								$array_to_insert['Total Leaves'] = round(($atnd_L / 2), 2);
							} else {
								$table .= '<td>' . round(($atnd_L + $atnd_H / 2), 2) . '</td>';
								$array_to_insert['Total Leaves'] = round(($atnd_L + $atnd_H / 2), 2);
							}
							if ($value['rt_type']  == 3) {
								$table .= '<td>' . round(($atnd_L / 2), 2) . '</td>';
								$array_to_insert['Leave Adjusted'] = round(($atnd_L / 2), 2);
							} else {
								$table .= '<td>' . round(($atnd_L + $atnd_H / 2), 2) . '</td>';
								$array_to_insert['Leave Adjusted'] = round(($atnd_L + $atnd_H / 2), 2);
							}
							if ($value['rt_type']  == 3) {
								$table .= '<td>' . round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO), 2) . '</td>';
								$array_to_insert['Pay Days'] = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO), 2);
							} else {
								$table .= '<td>' . round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO), 2) . '</td>';
								$array_to_insert['Pay Days'] = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO), 2);
							}



							$table .= '<td>CO Paid</td>';
							$array_to_insert['CO Paid'] = 'CO Paid';

							if ($value['rt_type']  == 3) {
								$table .= '<td>' . round($atnd_CO / 2) . '</td>';
								$array_to_insert['CO Less'] = round($atnd_CO / 2);
							} else {
								$table .= '<td>' . $atnd_CO . '</td>';
								$array_to_insert['CO Less'] = $atnd_CO;
							}

							if ($value['rt_type']  == 3) {
								$table .= '<td><b>' . round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO / 2 + $atnd_CO_Less / 2), 2) . '</b></td>';
								$array_to_insert['Total Pay Days'] = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO / 2 + $atnd_CO_Less / 2), 2);
							} else {
								$table .= '<td><b>' . round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less), 2) . '</b></td>';
								$array_to_insert['Total Pay Days'] = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less), 2);
							}

							$total_day_paid = 0;
							if ($value['rt_type']  == 3) {
								$total_day_paid = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO / 2 + $atnd_CO_Less / 2), 2);
							} else {
								$total_day_paid = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less), 2);
							}



							$auth_calc_P = $atnd_P;
							$auth_calc_A = $atnd_A;

							$atnd_A = 0;
							$atnd_P = 0;
							$atnd_H = 0;
							$atnd_L = 0;
							$atnd_LWP = 0;
							$atnd_HWP = 0;
							$atnd_WO = 0;
							$atnd_HO = 0;
							$atnd_LANA = 0;
							$atnd_WONA = 0;
							$atnd_CO = 0;
							if (count($data_atnd) > 0) {

								foreach ($data_atnd as $key => $value_atnd) {
									$date_day = explode('D', $key);
									$date_day = ($date_day[1] < 10) ? '0' . $date_day[1] : $date_day[1];
									$date_val = date("Y-m-" . $date_day, strtotime($date_first));
									if (strtotime($date_val) >= strtotime($DOJ) && strtotime($date_val) <= strtotime($date_fd) && strtotime($date_fd) >= strtotime($DOJ) && strtotime($date_val) <= strtotime($till_date)) {

										if (isset($value_atnd) && $value_atnd != '' && $value_atnd[0] == 'P') {
											$atnd_P++;
										} else if ($value_atnd == 'LWP' || strtoupper($value_atnd) == strtoupper('LWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('LWP(Attendance Change')) {
											$atnd_LWP++;
										} else if ($value_atnd == 'HWP' || strtoupper($value_atnd) == strtoupper('HWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('HWP(Attendance Change')) {
											$atnd_HWP++;
										} else if ($value_atnd == 'L' || strtoupper($value_atnd) == strtoupper('L(Biometric issue)') || strtoupper($value_atnd) == strtoupper('L(Attendance Change)')) {
											$atnd_L++;
										} else if ($value_atnd == 'H' || strtoupper($value_atnd) == strtoupper('H(Biometric issue)') || strtoupper($value_atnd) == strtoupper('H(Attendance Change)')) {
											$atnd_H++;
										} else if (strtoupper(trim($value_atnd)) == 'HO') {
											$atnd_HO++;
										} else if (strtoupper(trim($value_atnd)) == 'WO') {
											$atnd_WO++;
										} else if (strtoupper(trim($value_atnd)) == 'LANA') {
											$atnd_LANA++;
										} else if (strtoupper(trim($value_atnd)) == 'WONA') {
											$atnd_WONA++;
										} else if (strtoupper(trim($value_atnd)) == 'CO') {
											$atnd_CO++;
										} else {
											$atnd_A++;
										}
									}
								}
							}
							$atnd_CO_Less = 0;

							$atnd_tr = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less), 2);

							$Stipend_pd = round($Stipend_pd_amt, 2);

							// Calculation for Inct for selected month.

							$Attendnace_inc = 0;
							$Split_inc = 0;
							$OT_inc = 0;
							$Night_Mor_inc = 0;

							$Ref_inc = 0;
							$Lylty_inc = 0;


							// Calculation for Split

							$myDB = new MysqliDb();
							$Split_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = ?) and Incentive_Type = "Split"  and Request_Status = "Approved" order by CreatedOn , id limit 1;');
							// echo '<script> alert("dsdsds")</script>';
							$stmt3 = $conn->prepare($Split_dataq);
							$stmt3->bind_param("ss", $date_first, $EmployeeID);
							$stmt3->execute();
							$Split_data = $stmt3->get_result();
							$Split_data = mysqli_fetch_array($Split_data);
							// echo '<script> alert(' . print_r($Split_data) . ')</script>';


							if (count($Split_data) > 0 && $Split_data) {
								$begin = new DateTime($date_first);
								$end = new DateTime($date_last);

								$range_date_1 = new DateTime($Split_data[0]['StartDate']);
								$range_date_2 = new DateTime($Split_data[0]['EndDate']);
								$Rate_split = floatval($Split_data[0]['Rate']);
								$BaseCriteria =  $Split_data[0]['BaseCriteria'];
								$criteria1 = $Split_data[0]['criteria1'];
								$criteria2 = $Split_data[0]['criteria2'];

								$myDB = new MysqliDb();

								$str_capping = 'SELECT EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID=? and DateOn between cast(? as date) and cast(? as date) order by DateOn,PunchTime';
								$stmt4 = $conn->prepare($str_capping);
								$stmt4->bind_param("sss", $EmployeeID, $range_date_1->format('Y-m-d'), date('Y-m-d', (strtotime($range_date_2->format('Y-m-d') . ' +1 days'))));
								$stmt4->execute();
								$ds_punchtime = $stmt4->get_result();
								$ds_punchtime = mysqli_fetch_array($ds_punchtime);



								// $ds_punchtime = $myDB->query($str_capping);
								$bioinout = array();
								// Fetch data for APR  in given range; 
								if (count($ds_punchtime) > 0 && $ds_punchtime) {
									foreach ($ds_punchtime as $key_bio => $value_bio) {
										$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'] . ' ' . $value_bio['PunchTime'];
									}
								}
								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

									$dat_this =  $i->format('Y-m-d');
									if ($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D' . $i->format('j')][0] == 'P') {
										$bio_data = $bioinout[$dat_this];
										$validate = 0;
										foreach ($bio_data as $bio_key => $bio_value) {

											if (strtotime($bio_value) <=  strtotime($dat_this . ' ' . $criteria1) && $validate === 0) {
												$validate = 1;
											} elseif ((strtotime($bio_value) >=  strtotime($dat_this . ' ' . $criteria2)) && $validate === 1) {
												$Split_inc = $Split_inc + $Rate_split;
												$validate = 2;
											} else {
												$Split_inc = $Split_inc  + 0;
											}
										}
									}
								}
							}


							// Calculation for Night_Mor_inc
							$myDB = new MysqliDb();
							$Night_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = ?) and Incentive_Type = "Night/Late Evening" and Request_Status = "Approved" order by CreatedOn , id limit 1;');
							$stmt5 = $conn->prepare($Night_dataq);
							$stmt5->bind_param("sss", $date_first, $EmployeeID);
							$stmt5->execute();
							$Night_data = $stmt5->get_result();
							$Night_data = mysqli_fetch_array($Night_data);


							if (count($Night_data) > 0 && $Night_data) {
								$begin = new DateTime($date_first);
								$end = new DateTime($date_last);

								$range_date_1 = new DateTime($Night_data[0]['StartDate']);
								$range_date_2 = new DateTime($Night_data[0]['EndDate']);
								$Rate_Night = floatval($Night_data[0]['Rate']);
								$BaseCriteria =  $Night_data[0]['BaseCriteria'];
								$criteria1 = $Night_data[0]['criteria1'];
								$criteria2 = $Night_data[0]['criteria2'];

								$myDB = new MysqliDb();

								$str_capping = 'SELECT EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $range_date_1->format('Y-m-d') . '" as date) and cast("' . date('Y-m-d', (strtotime($range_date_2->format('Y-m-d') . ' +1 days'))) . '" as date) order by DateOn,PunchTime';

								$stmt6 = $conn->prepare($str_capping);
								$stmt6->bind_param("sss", $date_first, $EmployeeID);
								$stmt6->execute();
								$ds_punchtime = $stmt6->get_result();
								$ds_punchtime = mysqli_fetch_array($ds_punchtime);

								// $ds_punchtime = $myDB->query($str_capping);
								$bioinout = array();
								// Fetch data for APR  in given range; 
								if (count($ds_punchtime) > 0 && $ds_punchtime) {
									foreach ($ds_punchtime as $key_bio => $value_bio) {
										$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'] . ' ' . $value_bio['PunchTime'];
									}
								}
								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

									$dat_this =  $i->format('Y-m-d');
									if ($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D' . $i->format('j')][0] == 'P') {
										$bio_data = array_merge($bioinout[$dat_this], $bioinout[$dat_this]);
										$validate = 0;
										foreach ($bio_data as $bio_key => $bio_value) {
											$timeStamp = '';
											if (strtotime($criteria1) >= strtotime('00:00') && strtotime($criteria1) <= strtotime('09:00')) {
												$timeStamp = strtotime($dat_this . ' ' . $criteria1);
												$timeStamp = strtotime('+1 days ' . date('Y-m-d H:i:s', $timeStamp));
											} else {
												$timeStamp = strtotime($dat_this . ' ' . $criteria1);
											}


											$timeStamp1 = '';
											if (strtotime($criteria2) >= strtotime('00:00') && strtotime($criteria2) <= strtotime('09:00')) {
												$timeStamp1 = strtotime($dat_this . ' ' . $criteria2);
												$timeStamp1 = strtotime('+1 days ' . date('Y-m-d H:i:s', $timeStamp));
											} else {
												$timeStamp1 = strtotime($dat_this . ' ' . $criteria2);
											}


											if (strtotime($bio_value) <=  $timeStamp && $validate === 0) {
												$validate = 1;
											} elseif ((strtotime($bio_value) >=  $timeStamp1) && $validate === 1) {
												$Night_Mor_inc = $Night_Mor_inc + $Rate_Night;
												$validate = 2;
											} else {
												$Night_Mor_inc = $Night_Mor_inc  + 0;
											}
										}
									}
								}
							}


							// Calculation for Day

							$myDB = new MysqliDb();
							$Day_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = ?) and Incentive_Type = "Morning" and Request_Status = "Approved" order by CreatedOn , id limit 1;');

							$stmt7 = $conn->prepare($Day_dataq);
							$stmt7->bind_param("ss", $date_first, $EmployeeID);
							$stmt7->execute();
							$Day_data = $stmt7->get_result();
							$Day_data = mysqli_fetch_array($Day_data);


							if (count($Day_data) > 0 && $Day_data) {
								$begin = new DateTime($date_first);
								$end = new DateTime($date_last);

								$range_date_1 = new DateTime($Day_data[0]['StartDate']);
								$range_date_2 = new DateTime($Day_data[0]['EndDate']);
								$Rate_Day = floatval($Day_data[0]['Rate']);
								$BaseCriteria =  $Day_data[0]['BaseCriteria'];
								$criteria1 = $Day_data[0]['criteria1'];
								$criteria2 = $Day_data[0]['criteria2'];

								$myDB = new MysqliDb();

								$str_cappingq = 'SELECT EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID=? and DateOn between cast(? as date) and cast(? as date) order by DateOn,PunchTime';

								$stmt8 = $conn->prepare($str_cappingq);
								$stmt8->bind_param("sss", $EmployeeID,  $range_date_1->format('Y-m-d'), date('Y-m-d', (strtotime($range_date_2->format('Y-m-d') . ' +1 days'))));
								$stmt8->execute();
								$ds_punchtime = $stmt8->get_result();
								$ds_punchtime = mysqli_fetch_array($ds_punchtime);


								// $ds_punchtime = $myDB->query($str_capping);
								$bioinout = array();
								// Fetch data for APR  in given range; 
								if (count($ds_punchtime) > 0 && $ds_punchtime) {
									foreach ($ds_punchtime as $key_bio => $value_bio) {
										$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'] . ' ' . $value_bio['PunchTime'];
									}
								}
								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

									$dat_this =  $i->format('Y-m-d');
									if ($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D' . $i->format('j')][0] == 'P') {
										$bio_data = $bioinout[$dat_this];
										$validate = 0;
										foreach ($bio_data as $bio_key => $bio_value) {

											if (strtotime($bio_value) <=  strtotime($dat_this . ' ' . $criteria1) && $validate === 0) {
												$validate = 1;
											} elseif ((strtotime($bio_value) >=  strtotime($dat_this . ' ' . $criteria2)) && $validate === 1) {
												$Split_inc = $Split_inc + $Rate_Day;
												$validate = 2;
											} else {
												$Split_inc = $Split_inc  + 0;
											}
										}
									}
								}
							}



							// Calculation for Attendance
							$Attendnace_inc = 0;
							$Attendance_data = null;
							if ($value['df_id'] == 74 || $value['df_id'] == 77) {
								$myDB = new MysqliDb();
								$Attendance_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = ? ) and Incentive_Type = "Attendance" and Request_Status = "Approved" and ApplicableFor="CSA" order by CreatedOn , id limit 1;');
								$stmt9 = $conn->prepare($Attendance_dataq);
								$stmt9->bind_param("ss", $date_first, $EmployeeID);
								$stmt9->execute();
								$Attendance_data = $stmt9->get_result();
								$Attendance_data = mysqli_fetch_array($Attendance_data);
							} else {
								$myDB = new MysqliDb();
								$Attendance_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = ? ) and Incentive_Type = "Attendance" and Request_Status = "Approved" and ApplicableFor="Support" order by CreatedOn , id limit 1;');
								$stmt9 = $conn->prepare($Attendance_dataq);
								$stmt9->bind_param("ss", $date_first, $EmployeeID);
								$stmt9->execute();
								$Attendance_data = $stmt9->get_result();
								$Attendance_data = mysqli_fetch_array($Attendance_data);
							}

							if (count($Attendance_data) > 0 && $Attendance_data) {
								$begin = new DateTime($date_first);
								$end = new DateTime($date_last);

								$range_date_1 = new DateTime($Attendance_data[0]['StartDate']);
								$range_date_2 = new DateTime($Attendance_data[0]['EndDate']);

								$BaseCriteria =  $Attendance_data[0]['BaseCriteria'];
								$criteria11 = $Attendance_data[0]['criteria1'];
								$criteria12 = $Attendance_data[0]['criteria2'];
								$Rate_Attendance1 = floatval($Attendance_data[0]['Rate']);

								$criteria21 = $Attendance_data[0]['criteria12'];
								$criteria22 = $Attendance_data[0]['criteria22'];
								$Rate_Attendance2 = floatval($Attendance_data[0]['Rate2']);

								$criteria31 = $Attendance_data[0]['criteria13'];
								$criteria32 = $Attendance_data[0]['criteria23'];
								$Rate_Attendance3 = floatval($Attendance_data[0]['Rate3']);

								$tmp_Attendnace_inc = array();
								if (!empty($criteria11)) {
									if ($auth_calc_P > $criteria11 && $auth_calc_A <= $criteria12) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria11, "rate" => $Rate_Attendance1);
									}
								}
								if (!empty($criteria21)) {
									if ($auth_calc_P > $criteria21 && $auth_calc_A <= $criteria22) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria21, "rate" => $Rate_Attendance2);
									}
								}
								if (!empty($criteria31)) {
									if ($auth_calc_P > $criteria31 && $auth_calc_A <= $criteria32) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria31, "rate" => $Rate_Attendance3);
									}
								}
								if (count($tmp_Attendnace_inc) > 0) {
									$tmp_Attendnace_inc_val = array();
									$tmp_Attendnace_inc_val = getMax($tmp_Attendnace_inc, "days");
									foreach ($tmp_Attendnace_inc as $val_inc_atnd_check) {
										if ($val_inc_atnd_check["days"] == $tmp_Attendnace_inc_val) {
											$Attendnace_inc = $Attendnace_inc + ((is_numeric($val_inc_atnd_check["rate"])) ? $val_inc_atnd_check["rate"] : 0);
										}
									}
								}
							}


							// Calculation for Woman Attendance

							$Attendance_data = null;
							if ($value['df_id'] == 74 || $value['df_id'] == 77) {
								// $myDB = new MysqliDb();
								$Attendance_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from whole_dump_emp_data where EmployeeID = ? and Gender = "Female") and Incentive_Type = "Woman" and Request_Status = "Approved" and ApplicableFor="CSA" order by CreatedOn , id limit 1;');
								$stmt10 = $conn->prepare($Attendance_dataq);
								$stmt10->bind_param("ss", $date_first, $EmployeeID);
								$stmt10->execute();
								$Attendance_data = $stmt10->get_result();
								$Attendance_data = mysqli_fetch_array($Attendance_data);
							} else {
								// $myDB = new MysqliDb();
								$Attendance_dataq = ('SELECT * FROM inc_incentive_criteria where (? between StartDate and EndDate) and cm_id = (select cm_id from whole_dump_emp_data where EmployeeID = ? and Gender = "Female") and Incentive_Type = "Woman" and Request_Status = "Approved" and ApplicableFor="Support" order by CreatedOn , id limit 1;');
								$stmt10 = $conn->prepare($Attendance_dataq);
								$stmt10->bind_param("ss", $date_first, $EmployeeID);
								$stmt10->execute();
								$Attendance_data = $stmt10->get_result();
								$Attendance_data = mysqli_fetch_array($Attendance_data);
							}

							if (count($Attendance_data) > 0 && $Attendance_data) {
								$begin = new DateTime($date_first);
								$end = new DateTime($date_last);

								$range_date_1 = new DateTime($Attendance_data[0]['StartDate']);
								$range_date_2 = new DateTime($Attendance_data[0]['EndDate']);

								$BaseCriteria =  $Attendance_data[0]['BaseCriteria'];
								$criteria11 = $Attendance_data[0]['criteria1'];
								$criteria12 = $Attendance_data[0]['criteria2'];
								$Rate_Woman_Attendance1 = floatval($Attendance_data[0]['Rate']);

								$criteria21 = $Attendance_data[0]['criteria12'];
								$criteria22 = $Attendance_data[0]['criteria22'];
								$Rate_Woman_Attendance2 = floatval($Attendance_data[0]['Rate2']);

								$criteria31 = $Attendance_data[0]['criteria13'];
								$criteria32 = $Attendance_data[0]['criteria23'];
								$Rate_Woman_Attendance3 = floatval($Attendance_data[0]['Rate3']);

								$tmp_Attendnace_inc = array();
								if (!empty($criteria11)) {
									if ($auth_calc_P > $criteria11 && $auth_calc_A <= $criteria12) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria11, "rate" => $Rate_Woman_Attendance1);
									}
								}
								if (!empty($criteria21)) {
									if ($auth_calc_P > $criteria21 && $auth_calc_A <= $criteria22) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria21, "rate" => $Rate_Woman_Attendance2);
									}
								}
								if (!empty($criteria31)) {
									if ($auth_calc_P > $criteria31 && $auth_calc_A <= $criteria32) {
										$validate = 1;
										$tmp_Attendnace_inc[] = array("days" => $criteria31, "rate" => $Rate_Woman_Attendance3);
									}
								}
								if (count($tmp_Attendnace_inc) > 0) {
									$tmp_Attendnace_inc_val = array();
									$tmp_Attendnace_inc_val = getMax($tmp_Attendnace_inc, "days");
									foreach ($tmp_Attendnace_inc as $val_inc_atnd_check) {
										if ($val_inc_atnd_check["days"] == $tmp_Attendnace_inc_val) {
											$Attendnace_inc = $Attendnace_inc + ((is_numeric($val_inc_atnd_check["rate"])) ? $val_inc_atnd_check["rate"] : 0);
										}
									}
								}
							}
							$table .= '<td>' . $Attendnace_inc . '</td>';
							$table .= '<td>' . $Split_inc . '</td>';
							$table .= '<td>OT Inct.</td>';
							$table .= '<td>' . $Night_Mor_inc . '</td>';
							$table .= '<td>Ref Inct.</td>';
							$table .= '<td>Lylty/PLI Bonus</td>';

							$array_to_insert['Attendnace Inct'] = $Attendnace_inc;
							$array_to_insert['Split Inct'] = $Split_inc;
							$array_to_insert['OT Inct'] = 'OT Inct';
							$array_to_insert['Night-Mor Inct'] = $Night_Mor_inc;
							$array_to_insert['Ref Inct'] = 'Ref Inct';
							$array_to_insert['Lylty-PLI Bonus'] = 'Lylty/PLI Bonus';



							$df_id = $value['df_id'];
							$df_id_NTE = array(67, 68, 69, 71, 74, 76, 77, 81, 82, 83, 88, 103, 104, 105, 110);

							if (in_array($df_id, $df_id_NTE)) {

								$table .= '<td>' . round($Stipend_pd * $atnd_tr, 0) . '</td>';
								$array_to_insert['Trainig Stipend'] = round($Stipend_pd * $atnd_tr, 0);
							} else {
								$table .= '<td>-</td>';
								$array_to_insert['Trainig Stipend'] = 0;
							}


							//$table .='<td>'.round( $Stipend_pd * $atnd_tr,0).'</td>';

							if (!is_numeric($Split_inc)) {
								$Split_inc = 0;
							}
							if (!is_numeric($Night_Mor_inc)) {
								$Night_Mor_inc = 0;
							}
							if (!is_numeric($Attendnace_inc)) {
								$Attendnace_inc = 0;
							}




							// $myDB = new MysqliDb();
							$data_Incentiveq = ('SELECT ClientIncentive, Arrears, OtherIncentive, OtherNarration FROM payroll_incentive_upld where  EmployeeID = ? and month=? and year=?  order by createdon desc limit 1;');
							$stmt11 = $conn->prepare($data_Incentiveq);
							$stmt11->bind_param("sss", $EmployeeID, date("m", strtotime($date_first)), date("Y", strtotime($date_first)));
							$stmt11->execute();
							$data_Incentive = $stmt11->get_result();
							$data_Incentive = mysqli_fetch_array($data_Incentive);

							if (count($data_Incentive) > 0) {
								$ClientIncentive = $data_Incentive[0]['ClientIncentive'];
								$Arrears = $data_Incentive[0]['Arrears'];
								$OtherIncentive = $data_Incentive[0]['OtherIncentive'];
								$Other_Incentive_Narration = $data_Incentive[0]['OtherNarration'];
								$total_Incentive = trim($ClientIncentive) + trim($Arrears) + trim($OtherIncentive) + trim($Other_Incentive_Narration);
							} else {
								$ClientIncentive = 0;
								$Arrears = 0;
								$OtherIncentive = 0;
								$Other_Incentive_Narration = 0;
								$total_Incentive = 0;
							}


							$table .= '<td>' . $ClientIncentive . '</td>';
							$table .= '<td>' . $Arrears . '</td>';
							$table .= '<td>' . $OtherIncentive . '</td>';
							$table .= '<td>' . $Other_Incentive_Narration . '</td>';
							$array_to_insert['Client Incentive'] = $ClientIncentive;
							$array_to_insert['Arrears'] = $Arrears;
							$array_to_insert['Other Incentive'] = $OtherIncentive;
							$array_to_insert['Other Narration'] = $Other_Incentive_Narration;


							$total_Incentive = $total_Incentive + $Split_inc + $Night_Mor_inc + $Attendnace_inc;

							$table .= '<td><b>' . $total_Incentive . '</b></td>';
							$array_to_insert['Total Add'] = $total_Incentive;

							$df_id = $value['df_id'];
							$df_id_NTE = array(67, 68, 69, 71, 74, 76, 77, 81, 82, 83, 88, 103, 104, 105, 110);

							if (in_array($df_id, $df_id_NTE)) {

								$total_add = ($Stipend_pd * $atnd_tr) +  ($total_Incentive);
							} else {
								$total_add = (0) +  ($total_Incentive);
							}



							$myDB = new MysqliDb();
							$data_Deductionq = ('SELECT AssetDamage, Id_Access_Card_Damage, Guest_House_Rent, TDS, NoticeRecovery, OtherLess, OtherNarration FROM payroll_deduction_upld where  EmployeeID = ? and month= ? and year= ?  order by createdon desc limit 1;');

							$stmt12 = $conn->prepare($data_Deductionq);
							$stmt12->bind_param("sss", $EmployeeID, date("m", strtotime($date_first)), date("Y", strtotime($date_first)));
							$stmt12->execute();
							$data_Deduction = $stmt12->get_result();
							$data_Deduction = mysqli_fetch_array($data_Deduction);

							if (count($data_Deduction) > 0) {
								$AssetDamage = $data_Deduction[0]['AssetDamage'];
								$CardDamage = $data_Deduction[0]['Id_Access_Card_Damage'];
								$GuestHouseRent = $data_Deduction[0]['Guest_House_Rent'];
								$TDS = $data_Deduction[0]['TDS'];
								#$NoticeRecovery = $data_Deduction[0]['NoticeRecovery'];
								$OtherLess = $data_Deduction[0]['OtherLess'];
								$Other_Deduction_Narration = $data_Deduction[0]['OtherNarration'];
								$TotalLess = trim($AssetDamage) + trim($CardDamage) + trim($GuestHouseRent) + trim($TDS) + trim($OtherLess) + trim($Other_Deduction_Narration);
							} else {
								$AssetDamage = 0;
								$CardDamage = 0;
								$GuestHouseRent = 0;
								$TDS = 0;
								#$NoticeRecovery = 0;
								$OtherLess = 0;
								$Other_Deduction_Narration = 0;
								$TotalLess = 0;
							}

							//////////////// Calculate Recovery Day and Recovery Amount //////////////////////////////////////////////////////////////////

							$myDB = new MysqliDb();
							$recdayq = ('SELECT RecoveryDay from tbl_recovery where EmployeeID=? and payroll_month= ? and payroll_year= ?');
							$stmt13 = $conn->prepare($recdayq);
							$stmt13->bind_param("sss", $EmployeeID, date("m", strtotime($date_first)), date("Y", strtotime($date_first)));
							$stmt13->execute();
							$recday = $stmt13->get_result();
							$recday = mysqli_fetch_array($recday);


							if (count($recday) > 0) {
								$NoticeRecovery = $recday[0]['RecoveryDay'];
							} else {
								$NoticeRecovery = 0;
							}

							if ($NoticeRecovery < 0) {
								$NoticeRecoverytemp = (-1) * $NoticeRecovery;
								$Recovery_Amount  = ($NoticeRecoverytemp * ($salary_takehome / $days_in_month) + $total_add) - $TotalLess;
								$Recovery_Amount = number_format((float)$Recovery_Amount, 2, '.', '');
								if ($Recovery_Amount > 0) {
									$TotalLess = $TotalLess + $Recovery_Amount;
									$TotalLess = number_format((float)$TotalLess, 2, '.', '');
								}
							} else {
								$Recovery_Amount = 0;
							}


							///////////////////////////////////////////////////////////////////////////////////////////////////////////////

							$table .= '<td>' . $AssetDamage . '</td>';
							$table .= '<td>' . $CardDamage . '</td>';
							$table .= '<td>' . $GuestHouseRent . '</td>';
							$table .= '<td>' . $TDS . '</td>';
							$table .= '<td>' . $NoticeRecovery . '</td>';
							$table .= '<td>' . $Recovery_Amount . '</td>';
							$table .= '<td>' . $OtherLess . '</td>';
							$table .= '<td>' . $Other_Deduction_Narration . '</td>';
							$table .= '<td><b>' . $TotalLess . '</b></td>';

							$array_to_insert['Asset Damage Deduction'] = $AssetDamage;
							$array_to_insert['Card Damage Deduction'] = $CardDamage;
							$array_to_insert['Guest House Rent'] = $GuestHouseRent;
							$array_to_insert['TDS'] = $TDS;
							$array_to_insert['Recovery Day'] = $NoticeRecovery;
							$array_to_insert['Recovery Amount'] = $Recovery_Amount;
							$array_to_insert['Other Less'] = $OtherLess;
							$array_to_insert['Other Narration Less'] = $Other_Deduction_Narration;
							$array_to_insert['Total Less'] = $TotalLess;


							$total_less = 0;

							$days_in_month = _daysInMonth(date("m", strtotime($date_first)), date("Y", strtotime($date_first)));

							$salary_to_carry  = ($total_day_paid * ($salary_takehome / $days_in_month) + $total_add) - $TotalLess;
							if ($salary_to_carry < 0) {
								$salary_to_carry = 0;
							}

							$status_dsp = 0;
							$rsn_date = null;
							$last_working_date = null;
							$rsn_of_leaving = '';
							if (strtoupper($value['emp_status']) == 'INACTIVE') {


								if (count($data_status_dsp) > 0) {
									if (strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'NCNS REQUEST (ABSC)' || strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'ABSC' || strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'DCR' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'NCNS REQUEST (ABSC)' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'ABSC' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'DCR') {
										$status_dsp = 1;
										$last_working_date = date('Y-m-d', strtotime($data_status_dsp[0]['dol']));
									}

									if (strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'RES' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'RES') {
										$rsn_date = $data_status_dsp[0]['dol'];
										$last_working_date = date('Y-m-d', strtotime($data_status_dsp[0]['dol']));
									} else {
										$last_working_date = date('Y-m-d', strtotime($data_status_dsp[0]['dol']));
									}

									if (!empty($data_status_dsp[0]['disposition'])) {
										$rsn_of_leaving = $data_status_dsp[0]['disposition'];
									} else {
										$rsn_of_leaving = $data_status_dsp[0]['rsnofleaving'];
									}
								}
							}
							$final_payable_sal = 0;
							$final_payable_sal = (($status_dsp === 1) ? 0 : round($salary_to_carry, 0));
							if ($final_payable_sal < 0) {
								$final_payable_sal = 0;
							}

							$table .= '<td>' . round($salary_to_carry, 0) . '</td>';
							$table .= '<td>' . $final_payable_sal . '</td>';


							$array_to_insert['Salary'] = round($salary_to_carry, 0);
							$array_to_insert['Payable Salary'] = $final_payable_sal;

							// Bank Status Details ... 
							if (!empty($value['AccountNo']) && is_numeric($value['AccountNo'])) {
								$table .= '<td>' . $final_payable_sal . '</td>';
								$table .= '<td>0</td>';
								$array_to_insert['By Bank'] = $final_payable_sal;
								$array_to_insert['By Cash'] = 0;
							} else {
								$table .= '<td>0</td>';
								$table .= '<td>' . $final_payable_sal . '</td>';

								$array_to_insert['By Bank'] = 0;
								$array_to_insert['By Cash'] = $final_payable_sal;
							}

							$gross_sal = round($value['gross_sal'], 2);
							$gross_sal_asper = round((($gross_sal / $days_in_month) * $total_day_paid), 2);

							$basic_sal = round($value['basic'], 2);
							$basic_sal_asper = round((($basic_sal / $days_in_month) * $total_day_paid), 2);
							//$total_day_paid
							$table .= '<td>' . $basic_sal . '</td>';
							$table .= '<td>' . $basic_sal_asper . '</td>';
							$array_to_insert['Basic'] = $basic_sal;
							$array_to_insert['Payable Basic'] = $basic_sal_asper;

							if ($value['pf'] > 0  && $final_payable_sal > 0) {
								$table .= '<td>' . round((($basic_sal_asper * 12) / 100), 2) . '</td>';
								$table .= '<td>' . round((($basic_sal_asper * 3.67) / 100), 2) . '</td>';
								$table .= '<td>' . round((($basic_sal_asper * 8.33) / 100), 2) . '</td>';
								$table .= '<td>' . round((($basic_sal_asper * 1.36) / 100), 2) . '</td>';
								$array_to_insert['EMP PF 12'] = round((($basic_sal_asper * 12) / 100), 2);
								$array_to_insert['EMPR PF 3_67'] = round((($basic_sal_asper * 3.67) / 100), 2);
								$array_to_insert['EMPR PF 8_33'] = round((($basic_sal_asper * 8.33) / 100), 2);
								$array_to_insert['EMPR PF 1_36'] = round((($basic_sal_asper * 1.36) / 100), 2);
							} else {
								$table .= '<td>0</td><td>0</td><td>0</td><td>0</td>';
								$array_to_insert['EMP PF 12'] = 0;
								$array_to_insert['EMPR PF 3_67'] = 0;
								$array_to_insert['EMPR PF 8_33'] = 0;
								$array_to_insert['EMPR PF 1_36'] = 0;
							}

							$table .= '<td>' . $gross_sal . '</td>';
							$table .= '<td>' . $gross_sal_asper . '</td>';
							$array_to_insert['Gross'] = $gross_sal;
							$array_to_insert['Payable Gross'] = $gross_sal_asper;

							if ($value['esis'] > 0  && $final_payable_sal > 0) {
								$table .= '<td>' . round((($gross_sal_asper * 1.75) / 100), 2) . '</td>';
								$table .= '<td>' . round((($gross_sal_asper * 4.75) / 100), 2) . '</td>';
								$array_to_insert['ESIC 1_75'] = round((($gross_sal_asper * 1.75) / 100), 2);
								$array_to_insert['ESIC 4_75'] = round((($gross_sal_asper * 4.75) / 100), 2);
							} else {
								$table .= '<td>0</td><td>0</td>';
								$array_to_insert['ESIC 1_75'] = 0;
								$array_to_insert['ESIC 4_75'] = 0;
							}
							$table .= '<td>' . ((strtotime($rsn_date)) ? date('Y-m-d', strtotime($rsn_date)) : 'NA') . '</td>';
							$table .= '<td>' . ((strtotime($last_working_date)) ? date('Y-m-d', strtotime($last_working_date)) : 'NA') . '</td>';
							$table .= '<td>' . $rsn_of_leaving . '</td>';


							$array_to_insert['Resignation Date'] = ((strtotime($rsn_date)) ? date('Y-m-d', strtotime($rsn_date)) : 'NA');
							$array_to_insert['Last Working Date'] = ((strtotime($last_working_date)) ? date('Y-m-d', strtotime($last_working_date)) : 'NA');
							$array_to_insert['Reason of Leaving'] = $rsn_of_leaving;



							// $myDB = new MysqliDb();
							$data_final_statusq = ('SELECT SalaryStatus, Remarks FROM payroll_final_sl_status where  EmployeeID = ? and month= ? and year= ?  order by createdon desc limit 1;');
							$stmt114 = $conn->prepare($data_final_statusq);
							$stmt114->bind_param("sss", $EmployeeID, date("m", strtotime($date_first)), date("Y", strtotime($date_first)));
							$stmt114->execute();
							$data_final_status = $stmt114->get_result();
							$data_final_status = mysqli_fetch_array($data_final_status);


							if ($data_final_status && count($data_final_status)) {
								$table .= '<td>' . $data_final_status[0]['SalaryStatus'] . '</td>';
								$table .= '<td>' . $data_final_status[0]['Remarks'] . '</td>';
								$array_to_insert['Salary Status'] = $data_final_status[0]['SalaryStatus'];
								$array_to_insert['Remarks If Any'] = $data_final_status[0]['Remarks'];
							} else {
								$table .= '<td></td>';
								$table .= '<td></td>';
								$array_to_insert['Salary Status'] = '';
								$array_to_insert['Remarks If Any'] = '';
							}

							$array_to_insert['createdby'] = $clean_user_logid;

							if (isset($_POST['btn_lock']) && $date_first == date("Y-m-01", strtotime("first day of previous month"))) {
								$myDB = new MysqliDb();
								$check_prl_dataq = ("SELECT count(*) count FROM rpt_payroll_locked where EmployeeID =? and `Salary Month` = ?;");
								$stmt15 = $conn->prepare($check_prl_dataq);
								$stmt15->bind_param("ss", $EmployeeID, $array_to_insert['Salary Month']);
								$stmt15->execute();
								$check_prl_data = $stmt15->get_result();
								$check_prl_data = mysqli_fetch_array($check_prl_data);

								if (count($check_prl_data) > 0  && $check_prl_data) {
									if ($check_prl_data[0]['count'] == 0) {
										$myDB = new MysqliDb();
										$inserted_prl = $myDB->insert("rpt_payroll_locked", $array_to_insert);
										if ($inserted_prl === false) {
											echo "<script>$(function(){ toastr.error('Payroll not locked for $EmployeeID error : $mysql_error'); }); </script>";
										}
									} else {

										echo "<script>$(function(){ toastr.error('Payroll not locked for $EmployeeID error : already done'); }); </script>";
									}
								} else {

									echo "<script>$(function(){ toastr.error('Payroll not locked for $EmployeeID error : already done'); }); </script>";
								}
							} else if (isset($_POST['btn_lock'])) {
								echo "<script>$(function(){ toastr.error('Payroll not locked for $EmployeeID Only for Previous month data could be locked!'); }); </script>";
							}

							$table .= '</tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No data found'); }); </script>";
					}
				}
				?>
				<div id="overlay" class="hidden">
					<div id="modal_div">
						<div id="loader_content"></div> Loading team data,please wait.
					</div>
				</div>
				<div class="hidden modelbackground" id="myDiv"></div>
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
		$("button[type='submit']").click(function() {
			$(this).hide();
		});
	});

	function emp_detials(ek) {

		var tval = $(ek).attr('empid');

		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/GetEmployee.php?empid=" + tval
		}).done(function(data) { // data what is sent back by the php page
			$('#myDiv').html(data).removeClass('hidden');
			$('#imgBtn_close').on('click', function() {
				var el = $(this).parent('div').parent('div');
				el.addClass('hidden');
			});
			// display data
		});

	}

	$("#btnExport").on('click', function(e) {
		//getting values of current time for generating the file name
		var dt = new Date();
		var day = dt.getDate();
		var month = dt.getMonth() + 1;
		var year = dt.getFullYear();
		var hour = dt.getHours();
		var mins = dt.getMinutes();
		var sec = dt.getSeconds();
		var postfix = day + "." + month + "." + year + "_" + hour + "." + mins + "." + sec;
		//creating a temporary HTML link element (they support setting file names)
		var a = document.createElement('a');
		//getting data from our div that contains the HTML table
		var data_type = 'data:application/vnd.ms-excel';
		var table_div = document.getElementById('tbl_div');
		var table_html = table_div.outerHTML.replace(/ /g, '%20');
		a.href = data_type + ', ' + table_html;
		//setting the file name
		a.download = 'exported_table_' + postfix + '.xls';
		//triggering the function
		a.click();
		//just in case, prevent default behaviour
		e.preventDefault();
	});
</script>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>