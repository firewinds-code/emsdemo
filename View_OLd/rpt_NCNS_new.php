<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$value = $counEmployee = $countProcess = $countClient = $process = $countSubproc = $queryncns = 0;
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$alert_msg = '';
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
?>

<script>
	$(function() {
		var table = $('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			xScroll: '100%',
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
			"bScrollCollapse": true,
			"bLengthChange": false
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
	<span id="PageTittle_span" class="hidden">NCNS Report - LIST</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>NCNS Report - LIST</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s10 m10">
					<select class="form-control" id="ddl_clfs_Process" name="ddl_clfs_Process">
						<option value="NA">----Select----</option>
						<option value="ALL">ALL</option>
						<?php
						// $sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp where cm_id not in (28,19,20,22,21,50,53) order by clientname';
						// $myDB = new MysqliDb();

						$ids = [28, 19, 20, 22, 21, 50, 53];
						$count = count($ids);
						$placeholders = implode(',', array_fill(0, $count, '?'));
						$bindStr = str_repeat('i', $count);

						$stmt = $conn->prepare("select distinct Process,clientname,sub_process,cm_id from whole_details_peremp where cm_id not in ($placeholders) order by clientname");
						$stmt->bind_param($bindStr, ...$ids);
						$stmt->execute();

						$resultBy = $stmt->get_result();

						//$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
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
				<div class="input-field col s2 m2">
					<input type="submit" name="btn_search" id="btn_search" value="View" class="btn waves-effect waves-green" />
				</div>


				<?php
				//$myDB = new MysqliDb();

				$queryncns = "";
				$current_date_ncnc = date('Y-m-d', strtotime("yesterday"));
				$date_4day_prev =  date('Y-m-d', strtotime($current_date_ncnc . " -3 days"));

				//if(isset($_POST['btn_search'])){
				$empID = $_SESSION['__user_logid'];

				if (date('Y-m', strtotime($current_date_ncnc)) == date('Y-m', strtotime($date_4day_prev))) {
					$dt_ncns_1  = "D" . date('j', strtotime($date_4day_prev));
					$dt_ncns_2  = "D" . date('j', strtotime($date_4day_prev . " +1 days"));
					$dt_ncns_3  = "D" . date('j', strtotime($date_4day_prev . " +2 days"));
					$dt_ncns_4  = "D" . date('j', strtotime($current_date_ncnc));

					//$queryncns =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH, case when w.location=1 then 'Noida' when w.location=2 then 'Mumbai' when w.location=3 then 'Meerut' when w.location=4 then 'Bareilly' when w.location=5 then 'Vadodara' when w.location=6 then 'Mangalore' when w.location=7 then 'Bangalore' when w.location=8 then 'Bangalore_Flipkart' end as 'location' from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left join personal_details p on w.account_head = p.EmployeeID where ('A' in ($dt_ncns_1,$dt_ncns_2,$dt_ncns_3,$dt_ncns_4) and year='" . date('Y', strtotime($date_4day_prev)) . "' and Month='" . date('n', strtotime($date_4day_prev)) . "')";

					$D1234 = array($dt_ncns_1, $dt_ncns_2, $dt_ncns_3, $dt_ncns_4);
					$D1234 = implode(",", $D1234);


					$Year = date('Y', strtotime($date_4day_prev));
					$Month = date('n', strtotime($date_4day_prev));
					$queryncnsQuery =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH, case when w.location=1 then 'Noida' when w.location=2 then 'Mumbai' when w.location=3 then 'Meerut' when w.location=4 then 'Bareilly' when w.location=5 then 'Vadodara' when w.location=6 then 'Mangalore' when w.location=7 then 'Bangalore' when w.location=8 then 'Bangalore_Flipkart' end as 'location' from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left join personal_details p on w.account_head = p.EmployeeID where ('A' in (?) and year=? and Month=?)";

					$queryncnsQuery .= ' and w.cm_id in (select distinct processID  from report_map where empid=? and reportID=5)';

					$stmt = $conn->prepare($queryncnsQuery);
					$stmt->bind_param("ssss", $D1234, $Year, $Month, $empID);
					$stmt->execute();
					// $queryncns = $stmt->get_result();
				} else {
					$dt_ncns_endprev = date('Y-m-t', strtotime($date_4day_prev));
					$dt_ncns_firstcur = date('Y-m-01', strtotime($current_date_ncnc));

					$date_f_loop_ncns = $date_4day_prev;
					$prev_col_string = array();
					while (strtotime($date_f_loop_ncns) <= strtotime($dt_ncns_endprev)) {
						$prev_col_string[] = "D" . date('j', strtotime($date_f_loop_ncns));
						$date_f_loop_ncns = date("Y-m-d", strtotime("+1 day", strtotime($date_f_loop_ncns)));
					}

					$date_l_loop_ncns = $dt_ncns_firstcur;
					$cur_col_string = array();
					while (strtotime($date_l_loop_ncns) <= strtotime($current_date_ncnc)) {
						$cur_col_string[] = "D" . date('j', strtotime($date_l_loop_ncns));
						$date_l_loop_ncns = date("Y-m-d", strtotime("+1 day", strtotime($date_l_loop_ncns)));
					}


					// $queryncns =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH, case when w.location=1 then 'Noida' when w.location=2 then 'Mumbai' when w.location=3 then 'Meerut' when w.location=4 then 'Bareilly' when w.location=5 then 'Vadodara' when w.location=6 then 'Mangalore' when w.location=7 then 'Bangalore' when w.location=8 then 'Bangalore_Flipkart' end as 'location' from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left join personal_details p on w.account_head = p.EmployeeID where (('A' in (" . implode(",", $prev_col_string) . ") and year='" . date('Y', strtotime($date_4day_prev)) . "' and Month='" . date('n', strtotime($date_4day_prev)) . "') or ('A' in (" . implode(",", $cur_col_string) . ") and year='" . date('Y', strtotime($current_date_ncnc)) . "' and Month='" . date('n', strtotime($current_date_ncnc)) . "'))";

					$PrevCol = implode(",", $prev_col_string);
					$Year = date('Y', strtotime($date_4day_prev));
					$Month = date('n', strtotime($date_4day_prev));
					$CurCol = implode(",", $cur_col_string);
					$currentDate = date('Y', strtotime($current_date_ncnc));
					$currentDate2 = date('n', strtotime($current_date_ncnc));

					$queryncnsQuery =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH, case when w.location=1 then 'Noida' when w.location=2 then 'Mumbai' when w.location=3 then 'Meerut' when w.location=4 then 'Bareilly' when w.location=5 then 'Vadodara' when w.location=6 then 'Mangalore' when w.location=7 then 'Bangalore' when w.location=8 then 'Bangalore_Flipkart' end as 'location' from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left join personal_details p on w.account_head = p.EmployeeID where (('A' in (?) and year=? and Month='? or ('A' in (?) and year=? and Month=?))";

					$queryncnsQuery .= ' and w.cm_id in (select distinct processID  from report_map where empid=? and reportID=5)';

					$stmt = $conn->prepare($queryncnsQuery);
					$stmt->bind_param("sssssss", $PrevCol, $Year, $Month, $CurCol, $currentDate, $currentDate2, $empID);
					$stmt->execute();
				}


				/*if($process != 'ALL')// || $process != 0)
			{
				$queryncns .= ' and w.cm_id =\''.$process.'\'';
			}*/
				//echo  $queryncnsQuery;

				//$queryncns .= ' and w.cm_id in (select distinct processID  from report_map where empid="' . $_SESSION['__user_logid'] . '" and reportID=5)';

				// $empID = $_SESSION['__user_logid'];
				// $queryncnsQuery .= ' and w.cm_id in (select distinct processID  from report_map where empid=? and reportID=5)';

				// $stmt = $conn->prepare($queryncnsQuery);
				// $stmt->bind_param("s", $empID);
				// if (!$stmt) {
				// 	echo "failed to run";
				// 	die;
				// }
				// $stmt->execute();
				$chk_task = $stmt->get_result();
				// print_r($chk_task);
				// $count = $chk_task->num_rows;

				//echo $queryncns;
				// $chk_task = $myDB->query($queryncns);
				// $my_error = $myDB->getLastError();

				// if (count($chk_task) > 0 && $chk_task) {
				if ($chk_task->num_rows > 0 && $chk_task) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					/*$table .='<th>Remark</th>';*/
					$table .= '<th>Total</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '<th>Location</th>';
					$table .= '</tr></thead><tbody>';

					foreach ($chk_task as $key => $value) {

						if (true) {
							// $result_all = $myDB->query('select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t1 where EmployeeID = "' . $value['EmployeeID'] . '" and  month=' . date('m', time()) . ' and Year =' . date('Y', time()) . ' union all select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t2 where EmployeeID = "' . $value['EmployeeID'] . '" and  month=' . date('m', strtotime("-1 month " . date('Y-m-01', time()))) . ' and Year =' . date('Y', strtotime("-1 month " . date('Y-m-01', time()))));

							$EmpID = $value['EmployeeID'];
							$Month = date('m', time());
							$Year = date('Y', time());
							$resultallQuery = 'select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t1 where EmployeeID = ? and  month=? and Year =? union all select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t2 where EmployeeID = ? and  month=? and Year =? ';

							$stmt = $conn->prepare($resultallQuery);
							$stmt->bind_param("ssssss", $EmpID, $Month, $Year, $EmpID, $Month, $Year);
							if (!$stmt) {
								echo "failed to run";
								die;
							}
							$stmt->execute();
							$result_all = $stmt->get_result();
							$row = $result_all->fetch_row();
							$count = $result_all->num_rows;

							//echo $value['EmployeeID'];
							//if (count($result_all) == 2) {
							if ($result_all->num_rows == 2) {
								$result_prev = $result_all[1];
								$result_cur = $result_all[0];
							} else {
								if ((intval(date('Y', time())) ==  intval($row[2])) && (intval(date('m', time())) ==  intval($row[1]))) {

									$result_prev = array();
									$result_cur = $result_all[0];
								} else {
									$result_prev = $result_all[0];
									$result_cur = array();
								}
							}

							$count_prev = $count_abc =  0;
							$a_counter = 0;
							$inactiveThat = 0;
							$counter_check = 0;
							if (count($result_prev) > 0 && $result_prev) {
								$begin  =  new DateTime(date('Y-m-01', strtotime("-1 month " . date('Y-m-01', time()))));
								$end =  new DateTime(date('Y-m-t', strtotime("-1 month " . date('Y-m-01', time()))));

								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

									$col = "D" . intval($i->format('d'));
									$val = $result_prev[$col];
									if ($i->format('Y-m-d') < date('Y-m-d', time())) {
										$val_calc = $val;

										if (intval($i->format('d')) == 1) {
											$val_calc_prev = '-';
										} else {
											$val_calc_prev = $result_prev['D' . (intval($i->format('d')) - 1)];
										}

										if ($i->format('Y-m-d') == $i->format('Y-m-t')) {
											$val_calc_next = '-';
										} else {
											$val_calc_next = $result_prev['D' . (intval($i->format('d')) + 1)];
										}


										if ($val_calc == 'A' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A'))) {
											if ($inactiveThat > 0 || $val_calc == 'A') {
												$count_prev++;
											}
											$inactiveThat++;
											if ($val_calc == 'A') {
												$counter_check++;
											}
										} elseif ($val_calc == '-' || empty($val_calc) || $val_calc == 'HO') {
										} else {
											$count_prev = 0;
											$inactiveThat = 0;
											$counter_check = 0;
										}

										if ($val_calc == 'A') {
											$a_counter++;
										} else {
											$a_counter = 0;
										}
									}
								}
							}
							if (count($result_cur) > 0 && $result_cur) {
								for ($j = 1; $j <= 31; $j++) {
									if ($j < intval(date('d', time()))) {
										$val_calc = $result_cur['D' . $j];

										if ($j < 31) {
											$val_calc_next = $result_cur['D' . ($j + 1)];
										} else {
											$val_calc_next = '-';
										}

										if ($j > 1) {
											$val_calc_prev = $result_cur['D' . ($j - 1)];
										} else {
											$val_calc_prev = '-';
										}


										if ($val_calc == 'A' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A'))) {

											if ($inactiveThat > 0 || $val_calc == 'A') {
												$count_abc++;
											}
											if ($val_calc == 'A') {
												$counter_check++;
											}
											$inactiveThat++;
										} elseif ($val_calc == '-' || empty($val_calc) || $val_calc == 'HO') {
										} else {
											$count_abc = 0;
											$count_prev = 0;
											$inactiveThat = 0;
											$counter_check = 0;
										}

										if ($val_calc == 'A') {
											$a_counter++;
										} else {
											$a_counter = 0;
										}
									}
								}
							}

							$final_counter  = $count_abc + $count_prev;


							if ($counter_check >= 3 || $a_counter >= 3) {
								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '<input type="hidden" id="txt_empname_' . $value['EmployeeID'] . '" name="txt_empname_' . $value['EmployeeID'] . '" value="' . $value['EmployeeName'] . '"/></td>';
								/*$table .='<td style="padding:0px;"><textarea id="txt_remark_'.$value['EmployeeID'].'" name="txt_remark_'.$value['EmployeeID'].'" class="form-control" style="margin: 0px;height: 30px;"></textarea></td>';
							*/
								$table .= '<td>' . $final_counter . '</td>';
								$table .= '<td>' . $value['DOJ'] . '</td>';
								$table .= '<td>' . $value['Designation'] . '</td>';
								$table .= '<td>' . $value['clientname'] . '</td>';
								$table .= '<td>' . $value['process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['AH'] . '</td>';
								$table .= '<td>' . $value['location'] . '</td>';
								$table .= '</tr>';
							}
						}
					}
					$table .= '</tbody></table></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.info('No Data Found '); }); </script>";
				}
				//}
				?>
			</div>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	function checkbox_click() {
		$('input[type="checkbox"]:checked').each(function() {
			/*alert($(this).val());*/
		});

		if ($('input[type="checkbox"]:checked').length > 0) {
			$('#btn_inactive').removeClass('hidden');
		} else {
			$('#btn_inactive').addClass('hidden');
		}
	}
	$('#btn_search').on('click', function() {
		var validate = 0;

		if ($('#ddl_clfs_Process').val() == 'NA') {
			$('#ddl_clfs_Process').addClass('has-error');
			validate = 1;
			if ($('#sddl_clfs_Process').length == 0) {
				$('<span id="sddl_clfs_Process" class="help-block">Process is mandatory.</span>').insertAfter('#ddl_clfs_Process');
			}
		}
		if (validate == 1) {
			return false;
		}
	});
</script>