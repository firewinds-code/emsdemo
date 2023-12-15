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

if (isset($_SESSION)) {
	$clean_u_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_u_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		if (!($clean_u_logid == 'CE03070003' || $clean_u_logid == 'CE10091236' || $clean_u_logid == 'CE09134997')) {
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
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_date_for = cleanUserInput($_POST['txt_dateFor']);
}
if (isset($clean_date_for)) {
	$DateTo = $clean_date_for;
} else {
	$DateTo = date('F Y', strtotime("previous month"));
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_clf_proces = cleanUserInput($_POST['ddl_clfs_Process']);
}
if (isset($clean_clf_proces)) {
	$process = $clean_clf_proces;
}


function _daysInMonth($month = null, $year = null)
{

	if (null == ($year))
		$year =  date("Y", time());

	if (null == ($month))
		$month = date("m", time());

	return date('t', strtotime($year . '-' . $month . '-01'));
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
	<span id="PageTittle_span" class="hidden">Locked PF Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Locked PF Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5">
					<input name="txt_dateFor" id="txt_dateFor" value="<?php echo $DateTo; ?>" />
				</div>

				<div class="input-field col s5 m5">
					<select id="ddl_clfs_Process" name="ddl_clfs_Process">
						<option value="NA">----Select----</option>
						<option value="ALL">ALL</option>
						<?php
						$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
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
						?>
					</select>
				</div>

				<div class="input-field col s2 m2">
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
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					$chk_task = array();
					// $clean_clf_proces = cleanUserInput($_POST['ddl_clfs_Process']);
					if ($clean_clf_proces == 'ALL') {
						$select = "select * from ( select 	rpt.EmployeeID `EmployeeID`,sd.pf_account `PF Number`,EmployeeName,`Date Of Joining`,GENDER,`Father Name`,rpt.`Date Of Birth`,`Mother Name`,`uan_no` as `UAN NO`,mobile as `Mobile No`,dc.`dov_value` as `Aadhar Card`,dc1.`dov_value` as `PAN Card` ,`Bank Name`,`Bank Account Number`, `Name as per Bank`,`IFSC Code`,sd.`CTC`,sd.`takehome` as `Takehome`, rpt.`Basic`,rpt.`Payable Basic`, `EMP PF 12`,`EMPR PF 3_67`,`EMPR PF 8_33` from rpt_payroll_locked rpt inner join salary_details  sd on sd.EmployeeID = rpt.EmployeeID  left outer join (select dov_value,EmployeeID from doc_details where doc_stype = 'Aadhar Card') dc on  dc.EmployeeID = rpt.EmployeeID left outer join (select dov_value,EmployeeID from doc_details where doc_stype = 'PAN Card') dc1 on  dc1.EmployeeID = rpt.EmployeeID inner join contact_details  cd on cd.EmployeeID = rpt.EmployeeID where rpt.`Salary Month` = ? ) t1;";
						$selectQury = $conn->prepare($select);
						$selectQury->bind_param("s", date("F Y", strtotime($DateTo)));
						$selectQury->execute();
						$chk_task = $selectQury->get_result();
					} else {
						$select = "select * from ( select 	rpt.EmployeeID `EmployeeID`,sd.pf_account `PF Number`,EmployeeName,`Date Of Joining`,GENDER,`Father Name`,rpt.`Date Of Birth`,`Mother Name`,`uan_no` as `UAN NO`,mobile as `Mobile No`,dc.`dov_value` as `Aadhar Card`,dc1.`dov_value` as `PAN Card` ,`Bank Name`,`Bank Account Number`, `Name as per Bank`,`IFSC Code`,sd.`CTC`,sd.`takehome` as `Takehome`, rpt.`Basic`,rpt.`Payable Basic`, `EMP PF 12`,`EMPR PF 3_67`,`EMPR PF 8_33` from rpt_payroll_locked rpt inner join salary_details  sd on sd.EmployeeID = rpt.EmployeeID  left outer join (select dov_value,EmployeeID from doc_details where doc_stype = 'Aadhar Card') dc on  dc.EmployeeID = rpt.EmployeeID left outer join (select dov_value,EmployeeID from doc_details where doc_stype = 'PAN Card') dc1 on  dc1.EmployeeID = rpt.EmployeeID inner join contact_details  cd on cd.EmployeeID = rpt.EmployeeID where  (select cm_id from new_client_master nt inner join client_master on client_master.client_id = nt.client_name where rpt.`Sub Process` = nt.sub_process and rpt.`process` = nt.`process` and client_master.client_name = rpt.`Client`)= '" . $clean_clf_proces . "' and rpt.`Salary Month` = '" . date("F Y", strtotime($DateTo)) . "' ) t1;";
						$selectQury = $conn->prepare($select);
						$selectQury->bind_param("ss", $clean_clf_proces, date("F Y", strtotime($DateTo)));
						$selectQury->execute();
						$chk_task = $selectQury->get_result();
					}
					$counter = 0;

					if ($chk_task->num_rows > 0 && $chk_task) {
						$date_first = date("Y-m-01", strtotime($DateTo));
						$date_last = date("Y-m-t", strtotime($DateTo));

						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';

						foreach ($chk_task[0] as $key_kl => $value_kl) {
							$table .= '<th>' . $key_kl . '</th>';
						}
						$table .= '</tr>';
						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$table .= '<tr>';
							foreach ($value as $key_kl => $value_kl) {
								if ($key_kl == 'Bank Account Number') {
									$table .= '<td>\'' . $value_kl . '</td>';
								} else {
									$table .= '<td>' . $value_kl . '</td>';
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