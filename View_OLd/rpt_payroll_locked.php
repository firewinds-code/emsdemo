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
		if (!($user_logid == 'CE03070003' || $user_logid == 'CE10091236' || $user_logid == 'CE09134997')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}

$DateTo = '';
if (isset($_POST['txt_dateFor'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$DateTo = cleanUserInput($_POST['txt_dateFor']);
	}
} else {
	$DateTo = date('F Y', strtotime("previous month"));
}
if (isset($_POST['ddl_clfs_Process'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$process = cleanUserInput($_POST['ddl_clfs_Process']);
	}
}
function _daysInMonth($month = null, $year = null)
{

	if (null == ($year))
		$year =  date("Y", time());

	if (null == ($month))
		$month = date("m", time());

	return date('t', strtotime($year . '-' . $month . '-01'));
}

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$ddl_clfs_Process = cleanUserInput($_POST['ddl_clfs_Process']);
}
?>
<style>
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
<script>
	$(function() {
		/*$('#txt_dateFor').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
    */

		$('#txt_dateFor').datepicker_M({
			format: 'mmmm yyyy'
		});
		$(document).on("click blur focus change", ".pika-select,.datepicker_M-done", function() {
			$(".datepicker_M-day-button[data-pika-day='1']").trigger("click");
		});
		$(".datepicker_M-cancel").removeClass("btn-flat").addClass("btn close-btn").css("margin-right", "10px");
		$(".datepicker_M-done").removeClass("btn-flat").addClass("btn");

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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Locked Payroll Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Locked Payroll Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5">
					<input class="form-control" name="txt_dateFor" id="txt_dateFor" value="<?php echo $DateTo; ?>" />
				</div>
				<div class="input-field col s5 m5">
					<select class="form-control" id="ddl_clfs_Process" name="ddl_clfs_Process">
						<option value="NA">----Select----</option>
						<option value="ALL">ALL</option>
						<?php
						$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
						$myDB = new MysqliDb();
						$resultBy = $myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
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
				</div>

				<div class="input-field col s2 m2 right-align">

					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<button type="button" class="btn waves-effect waves-green hidden" name="btnExport" id="btnExport"> Export</button>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
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
				$btn_view = isset($_POST['btn_view']);
				if ($btn_view) {

					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					$chk_task = array();
					$dateto = date("F Y", strtotime($DateTo));

					if ($ddl_clfs_Process == 'ALL') {
						$select = "select rpt_payroll_locked.* from rpt_payroll_locked where  rpt_payroll_locked.`Salary Month` = ?;";
						$selectQury = $conn->prepare($select);
						$selectQury->bind_param("i", $dateto);
						$selectQury->execute();
						$chk_task = $selectQury->get_result();
					} else {
						$dateto = date("F Y", strtotime($DateTo));
						$select = "select rpt_payroll_locked.* from rpt_payroll_locked where  (select cm_id from new_client_master nt inner join client_master on client_master.client_id = nt.client_name where rpt_payroll_locked.`Sub Process` = nt.sub_process and rpt_payroll_locked.`process` = nt.`process` and client_master.client_name = rpt_payroll_locked.`Client`)= ? and rpt_payroll_locked.`Salary Month` = ? ;";
						$selectQury = $conn->prepare($select);
						$selectQury->bind_param("si", $ddl_clfs_Process, $dateto);
						$selectQury->execute();
						$chk_task = $selectQury->get_result();
					}
					// print_r($chk_task);
					$counter = 0;
					$my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0 && $chk_task) {
						$date_first = date("Y-m-01", strtotime($DateTo));
						$date_last = date("Y-m-t", strtotime($DateTo));

						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Salary Month</th>';
						$table .= '<th>Date Of Joining</th>';
						$table .= '<th>Date Of Deployment</th>';
						$table .= '<th>Calculated Deployment</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Emp Type</th>';
						$table .= '<th>Payroll Type</th>';
						$table .= '<th>Roster Type</th>';
						$table .= '<th>GENDER</th>';
						$table .= '<th>Father\'s Name</th>';
						$table .= '<th>Mother\'s Name</th>';
						$table .= '<th>Date Of Birth</th>';
						$table .= '<th>Bank Name</th>';
						$table .= '<th>Bank Account Number</th>';
						$table .= '<th>Name as per Bank</th>';
						$table .= '<th>IFSC Code</th>';
						$table .= '<th>Emp Status</th>';

						$table .= '<th>Total Floor Days</th>';
						$table .= '<th>CTC</th>';
						$table .= '<th>TK Home</th>';

						$table .= '<th>Leave Opening Balance</th>';
						$table .= '<th>P</th>';
						$table .= '<th>A</th>';
						$table .= '<th>L</th>';
						$table .= '<th>H</th>';
						$table .= '<th>LWP</th>';
						$table .= '<th>HWP</th>';
						$table .= '<th>WO</th>';
						$table .= '<th>LANA</th>';
						$table .= '<th>WONA</th>';
						$table .= '<th>Total Leaves</th>';
						$table .= '<th>Leave Adjusted</th>';
						$table .= '<th>Pay Days</th>';
						$table .= '<th>CO Paid</th>';
						$table .= '<th>CO Less</th>';
						$table .= '<th>Total Pay Days</th>';

						$table .= '<th>Attendnace Inct.</th>';
						$table .= '<th>Split Inct.</th>';
						$table .= '<th>OT Inct.</th>';
						$table .= '<th>Night/Mor Inct.</th>';
						$table .= '<th>Ref Inct.</th>';
						$table .= '<th>Lylty/PLI Bonus</th>';
						$table .= '<th>Trainig Stipend</th>';
						$table .= '<th>Client Incentive</th>';
						$table .= '<th>Arrears</th>';
						$table .= '<th>Other Incentive</th>';
						$table .= '<th>Other Narration</th>';
						$table .= '<th>Total Add</th>';

						$table .= '<th>Asset Damage Deduction</th>';
						$table .= '<th>Id/Access Card Damage Deduction</th>';
						$table .= '<th>Guest House Rent</th>';
						$table .= '<th>TDS</th>';
						$table .= '<th>Recovery Day</th>';
						$table .= '<th>Recovery Amount</th>';
						$table .= '<th>Other Less</th>';
						$table .= '<th>Other Narration</th>';
						$table .= '<th>Total Less</th>';

						$table .= '<th>Salary</th>';
						$table .= '<th>Payable Salary</th>';
						$table .= '<th>By Bank</th>';
						$table .= '<th>By Cash</th>';

						$table .= '<th>Basic</th>';
						$table .= '<th>Payable Basic</th>';
						$table .= '<th>EMP PF 12%</th>';
						$table .= '<th>EMPR PF 3.67%</th>';
						$table .= '<th>EMPR PF 8.33%</th>';
						$table .= '<th>EMPR PF 1.36%</th>';

						$table .= '<th>Gross</th>';
						$table .= '<th>Payable Gross</th>';
						$table .= '<th>ESIC 1.75%</th>';
						$table .= '<th>ESIC 4.75%</th>';

						$table .= '<th>Resignation Date</th>';
						$table .= '<th>Last Working Date</th>';
						$table .= '<th>Reason of Leaving</th>';
						$table .= '<th>Salary Status</th>';
						$table .= '<th>Remarks If Any</th>';
						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$table .= '<tr>';
							foreach ($value as $key_kl => $value_kl) {
								//createdon, createdby, modifiedon, modifiedbyfrom
								if ($key_kl != 'id' && $key_kl != 'createdon' && $key_kl != 'createdby' && $key_kl != 'modifiedon' && $key_kl != 'modifiedby') {
									if ($key_kl == 'Bank Account Number') {
										$table .= '<td>\'' . $value_kl . '</td>';
									} else {
										$table .= '<td>' . $value_kl . '</td>';
									}
								}
							}
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
					}
				}

				?>

				<div id="overlay" class="hidden">
					<div id="modal_div">
						<div id="loader_content"></div> Loading team data,please wait.
					</div>
				</div>

				<div class="hidden modelbackground" id="myDiv">

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

	// function emp_detials(ek) {

	// 	var tval = $(ek).attr('empid');

	// 	$.ajax({
	// 		url: <?php echo '"' . URL . '"'; ?> + "Controller/GetEmployee.php?empid=" + tval
	// 	}).done(function(data) { // data what is sent back by the php page
	// 		$('#myDiv').html(data).removeClass('hidden');
	// 		$('.imgBtn_close').on('click', function() {
	// 			var el = $('.imgBtn_close').parent('div').parent('div');
	// 			el.addClass('hidden');
	// 		});
	// 		// display data
	// 	});

	// }

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