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

		if ($isPostBack && isset($_POST['txt_dateMonth'])) {
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
	<span id="PageTittle_span" class="hidden">Attendance Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Attendance Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="col s5 m5">
					<Select name="txt_dateMonth" style="min-width: 125px;" id="txt_dateMonth">
						<option <?php if ($date_To == 'Jan') echo ' selected '; ?>>Jan</option>
						<option <?php if ($date_To == 'Feb') echo ' selected '; ?>>Feb</option>
						<option <?php if ($date_To == 'Mar') echo ' selected '; ?>>Mar</option>
						<option <?php if ($date_To == 'Apr') echo ' selected '; ?>>Apr</option>
						<option <?php if ($date_To == 'May') echo ' selected '; ?>>May</option>
						<option <?php if ($date_To == 'Jun') echo ' selected '; ?>>Jun</option>
						<option <?php if ($date_To == 'Jul') echo ' selected '; ?>>Jul</option>
						<option <?php if ($date_To == 'Aug') echo ' selected '; ?>>Aug</option>
						<option <?php if ($date_To == 'Sep') echo ' selected '; ?>>Sep</option>
						<option <?php if ($date_To == 'Oct') echo ' selected '; ?>>Oct</option>
						<option <?php if ($date_To == 'Nov') echo ' selected '; ?>>Nov</option>
						<option <?php if ($date_To == 'Dec') echo ' selected '; ?>>Dec</option>

					</Select>
				</div>
				<div class="col s5 m5">
					<Select name="txt_dateYear" style="min-width: 175px;" id="txt_dateYear">

						<option <?php if ($date_From == '2020') echo ' selected '; ?>>2020</option>
						<option <?php if ($date_From == '2021') echo ' selected '; ?>>2021</option>
						<option <?php if ($date_From == '2022') echo ' selected '; ?>>2022</option>
						<option <?php if ($date_From == '2023') echo ' selected '; ?>>2023</option>
					</Select>
				</div>

				<div class="col s2 m2">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<!--<button type="submit" class="button button-3d-action" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>

				<?php

				$myDB = new MysqliDb();
				$type = '';
				$type = $_SESSION['__user_type'];
				$chk_task = $myDB->query('call sp_get_atnd_team_report("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '")');
				$my_error = $myDB->getLastError();

				if (count($chk_task) > 0 && $chk_task) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
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
					$table .= '<th>DOJ</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Dept Name</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '<th>function</th>';
					$table .= '<th>DOD</th>';
					$table .= '<th>LWD</th>';
					$table .= '<th>Employee Status</th><th>Employee Stage</th></tr></thead><tbody>';

					foreach ($chk_task as $key => $value) {


						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['Month'] . '</td>';
						$table .= '<td>' . $value['Year'] . '</td>';
						$myBD_tmp = new MysqliDb();
						$temp_result  = $myBD_tmp->query('select rsnofleaving,dol,disposition from exit_emp where EmployeeID  = "' . $value['EmployeeID'] . '" order by id desc limit 1');

						if (!empty($temp_result[0]['rsnofleaving']) && $value['emp_status'] == 'InActive' && !empty($temp_result[0]['dol'])) {
							$i = 1;
							while ($i <= 31) {
								$date_1 = date('Y-m-d', strtotime($temp_result[0]['dol']));
								$date_2 = date('Y-m-d', strtotime($value['Year'] . '/' . $value['Month'] . '/' . $i));


								if ($date_2 == $date_1) {
									if (strtoupper($temp_result[0]['disposition']) == 'RES' || strtoupper($temp_result[0]['rsnofleaving']) == 'RES') {
										$table .= '<td>' . $value['D' . $i] . '</td>';
									} else if (!empty($temp_result[0]['disposition'])) {
										$table .= '<td>' . $temp_result[0]['disposition'] . '</td>';
									} else {
										$table .= '<td>' . $temp_result[0]['rsnofleaving'] . '</td>';
									}
								} else if ($date_2 > $date_1) {
									if (!empty($temp_result[0]['disposition'])) {
										$table .= '<td>' . $temp_result[0]['disposition'] . '</td>';
									} else {
										$table .= '<td>' . $temp_result[0]['rsnofleaving'] . '</td>';
									}
								} else {
									$table .= '<td>' . $value['D' . $i] . '</td>';
								}

								$i++;
							}
						} else {
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
						}


						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['dept_name'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['Supervisor'] . '</td>';
						$table .= '<td>' . $value['function'] . '</td>';
						$table .= '<td>' . date('Y-m-d', strtotime($value['DOD'])) . '</td>';

						if (!empty($temp_result[0]['rsnofleaving']) && $value['emp_status'] == 'InActive' && !empty($temp_result[0]['dol'])) {
							$table .= '<td>' . date('Y-m-d', strtotime($temp_result[0]['dol'])) . '</td>';
						} else {
							$table .= '<td></td>';
						}



						$table .= '<td>' . $value['emp_status'] . '</td><td>' . $value['EmployeeLevel'] . '</td></tr>';
					}

					$table .= '</tbody></table></div></div>';
					echo $table;

					echo '<div class="col s12 m12">
					 	<div class="col s5 m5">
					 		<input type="text" id="txt_process" placeholder="Process" value=""/>
					 	</div>
					 	<div class="col s5 m5">
					 		<input type="text" id="txt_Subproc" placeholder="Sub Process"/>
					 	</div>
				 	   </div>';
				} else {
					echo "<script>$(function(){ toastr.info('No Data Found." . $my_error . " '); }); </script>";
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