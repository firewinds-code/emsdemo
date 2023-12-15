<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
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
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
			$date_To = $_POST['txt_dateMonth'];
			$date_From = $_POST['txt_dateYear'];
			if (!empty($_POST['txt_dept'])) {
				$dept = $_POST['txt_dept'];
			}
		} else {
			$date_To = date('M', time());
			$date_From = date('Y', time());
			$dept = $_SESSION['__user_process'];
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//print_r($_POST);
//die;
?>

<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/

		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex) {
				var proc = $('#txt_process').val().toLowerCase();
				var sproc = $('#txt_Subproc').val().toLowerCase();
				var process = data[39]; // use data for the age column
				var subprocess = data[40]; // use data for the age column


				if (process.toLowerCase().indexOf(proc) >= 0 && subprocess.toLowerCase().indexOf(sproc) >= 0) {

					return true;

				} else {
					return false;
				}




			}
		);

		// DataTable
		var table = $('#myTable').DataTable({
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
		});;
		$('#txt_Subproc, #txt_process').keyup(function() {
			table.draw();
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
	<span id="PageTittle_span" class="hidden">Roster Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Roster Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="col s4 m4">
					<Select name="txt_dateMonth" id="txt_dateMonth">
						<option id='01' <?php if ($date_To == 'Jan') echo ' selected '; ?>>Jan</option>
						<option id='02' <?php if ($date_To == 'Feb') echo ' selected '; ?>>Feb</option>
						<option id='03' <?php if ($date_To == 'Mar') echo ' selected '; ?>>Mar</option>
						<option id='04' <?php if ($date_To == 'Apr') echo ' selected '; ?>>Apr</option>
						<option id='05' <?php if ($date_To == 'May') echo ' selected '; ?>>May</option>
						<option id='06' <?php if ($date_To == 'Jun') echo ' selected '; ?>>Jun</option>
						<option id='07' <?php if ($date_To == 'Jul') echo ' selected '; ?>>Jul</option>
						<option id='08' <?php if ($date_To == 'Aug') echo ' selected '; ?>>Aug</option>
						<option id='09' <?php if ($date_To == 'Sep') echo ' selected '; ?>>Sep</option>
						<option id='10' <?php if ($date_To == 'Oct') echo ' selected '; ?>>Oct</option>
						<option id='11' <?php if ($date_To == 'Nov') echo ' selected '; ?>>Nov</option>
						<option id='12' <?php if ($date_To == 'Dec') echo ' selected '; ?>>Dec</option>
					</Select>
				</div>
				<div class="col s4 m4">
					<Select name="txt_dateYear" id="txt_dateYear">

						<option <?php if ($date_From == '2020') echo ' selected '; ?>>2020</option>
						<option <?php if ($date_From == '2021') echo ' selected '; ?>>2021</option>
						<option <?php if ($date_From == '2022') echo ' selected '; ?>>2022</option>
						<option <?php if ($date_From == '2023') echo ' selected '; ?>>2023</option>
					</Select>
					<input type='hidden' name='monthCount' id='monthCount'>
				</div>

				<div class="col s4 m4">
					<Select name="emp_status" id="status">
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


				<?php
				$empStatus = "";
				if (!empty($_POST['emp_status'])) {
					$empStatus = $_POST['emp_status'];
					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
				}

				$curMonth = date('m');
				$curYear = date('Y');
				if (!empty($_POST['monthCount'])) {
					$monthCount = $_POST['monthCount']; //seleceted month(01,02....,10,11)
					$date_selected = $date_From . '-' . $monthCount . '-01'; //selected month and year(2017,2018...)
				}

				$dateLimit = date('Y-m-01', strtotime('-3 months'));


				if ($empStatus != "") {

					if (($dateLimit < $date_selected)) {
						$viewTable = 'roster_master';
					} else {
						$viewTable = 'roster_master';
					}
					$selectQuery = ' select ' . $viewTable . '.EmployeeID,' . $tablename . '.EmployeeName,' . $viewTable . '.D1,' . $viewTable . '.D2,' . $viewTable . '.D3,' . $viewTable . '.D4,' . $viewTable . '.D5,' . $viewTable . '.D6,' . $viewTable . '.D7,' . $viewTable . '.D8,' . $viewTable . '.D9,' . $viewTable . '.D10,' . $viewTable . '.D11,' . $viewTable . '.D12,' . $viewTable . '.D13,' . $viewTable . '.D14,' . $viewTable . '.D15,' . $viewTable . '.D16,' . $viewTable . '.D17,' . $viewTable . '.D18,' . $viewTable . '.D19,' . $viewTable . '.D20,' . $viewTable . '.D21,' . $viewTable . '.D22,' . $viewTable . '.D23,' . $viewTable . '.D24,' . $viewTable . '.D25,' . $viewTable . '.D26,' . $viewTable . '.D27,' . $viewTable . '.D28,' . $viewTable . '.D29,' . $viewTable . '.D30,' . $viewTable . '.D31 ,' . $viewTable . '.Month as Month,' . $viewTable . '.Year, personal_details.EmployeeName as Supervisor,Designation,dept_name,' . $tablename . '.process,sub_process,personal_details.EmployeeName as Supervisor,DOJ,clientname from ' . $viewTable . ' inner join ' . $tablename . ' on ' . $tablename . '.EmployeeID = ' . $viewTable . '.EmployeeID left outer join personal_details on personal_details.EmployeeID = ReportTo where ' . $viewTable . '.Month ="' . $date_To . '" and ' . $viewTable . '.Year ="' . $date_From . '" and   ' . $tablename . '.EmployeeID in ( select t1.EmployeeID from (select EmployeeID from  status_table where  ReportTo in(select EmployeeID from  status_table where  ReportTo in(select EmployeeID from  status_table where  ReportTo in (select EmployeeID from status_table where  ReportTo = "' . $_SESSION['__user_logid'] . '"))) union select EmployeeID from  status_table where  ReportTo in(select EmployeeID from  status_table where  ReportTo in (select EmployeeID from status_table where  ReportTo = "' . $_SESSION['__user_logid'] . '")) union  select EmployeeID from  status_table where  ReportTo in (select EmployeeID from status_table where  ReportTo = "' . $_SESSION['__user_logid'] . '") union select EmployeeID from status_table where  ReportTo = "' . $_SESSION['__user_logid'] . '" or EmployeeID = "' . $_SESSION['__user_logid'] . '" or Qa_ops = "' . $_SESSION['__user_logid'] . '") t1) ;';
					//echo $selectQuery;
					//die;
					$myDB = new MysqliDb();
					$chk_task = $myDB->query($selectQuery);
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>Employee ID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>Month</th>';
						$table .= '<th>Year</th>';
						$table .= '<th>D1</th>';
						$table .= '<th>D2</th>';
						$table .= '<th>D3</th>';
						$table .= '<th>D4</th>';
						$table .= '<th>D5</th>';
						$table .= '<th>D6</th>';
						$table .= '<th>D7</th>';
						$table .= '<th>D8</th>';
						$table .= '<th>D9</th>';
						$table .= '<th>D10</th>';
						$table .= '<th>D11</th>';
						$table .= '<th>D12</th>';
						$table .= '<th>D13</th>';
						$table .= '<th>D14</th>';
						$table .= '<th>D15</th>';
						$table .= '<th>D16</th>';
						$table .= '<th>D17</th>';
						$table .= '<th>D18</th>';
						$table .= '<th>D19</th>';
						$table .= '<th>D20</th>';
						$table .= '<th>D21</th>';
						$table .= '<th>D22</th>';
						$table .= '<th>D23</th>';
						$table .= '<th>D24</th>';
						$table .= '<th>D25</th>';
						$table .= '<th>D26</th>';
						$table .= '<th>D27</th>';
						$table .= '<th>D28</th>';
						$table .= '<th>D29</th>';
						$table .= '<th>D30</th>';
						$table .= '<th>D31</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept. Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Supervisor</th></tr></thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Month'] . '</td>';
							$table .= '<td>' . $value['Year'] . '</td>';
							$table .= '<td>' . $value['D1'] . '</td>';
							$table .= '<td>' . $value['D2'] . '</td>';
							$table .= '<td>' . $value['D3'] . '</td>';
							$table .= '<td>' . $value['D4'] . '</td>';
							$table .= '<td>' . $value['D5'] . '</td>';
							$table .= '<td>' . $value['D6'] . '</td>';
							$table .= '<td>' . $value['D7'] . '</td>';
							$table .= '<td>' . $value['D8'] . '</td>';
							$table .= '<td>' . $value['D9'] . '</td>';
							$table .= '<td>' . $value['D10'] . '</td>';
							$table .= '<td>' . $value['D11'] . '</td>';
							$table .= '<td>' . $value['D12'] . '</td>';
							$table .= '<td>' . $value['D13'] . '</td>';
							$table .= '<td>' . $value['D14'] . '</td>';
							$table .= '<td>' . $value['D15'] . '</td>';
							$table .= '<td>' . $value['D16'] . '</td>';
							$table .= '<td>' . $value['D17'] . '</td>';
							$table .= '<td>' . $value['D18'] . '</td>';
							$table .= '<td>' . $value['D19'] . '</td>';
							$table .= '<td>' . $value['D20'] . '</td>';
							$table .= '<td>' . $value['D21'] . '</td>';
							$table .= '<td>' . $value['D22'] . '</td>';
							$table .= '<td>' . $value['D23'] . '</td>';
							$table .= '<td>' . $value['D24'] . '</td>';
							$table .= '<td>' . $value['D25'] . '</td>';
							$table .= '<td>' . $value['D26'] . '</td>';
							$table .= '<td>' . $value['D27'] . '</td>';
							$table .= '<td>' . $value['D28'] . '</td>';
							$table .= '<td>' . $value['D29'] . '</td>';
							$table .= '<td>' . $value['D30'] . '</td>';
							$table .= '<td>' . $value['D31'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;

						echo '<div class="col s12 m12">
					 	<div class="col s6 m6">
					 		<input type="text" id="txt_process" placeholder="Process"/>
					 	</div>
					 	<div class="col s6 m6">	
					 		<input type="text" id="txt_Subproc" placeholder="Sub Process"/>
					 	</div>
				 	  </div>';
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . " '); }); </script>";
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

		$('#txt_dateMonth').on('change', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
		})
		$('#btn_view').on('click', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
		})
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