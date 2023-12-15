<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$dept = $OutOJT = '';
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

		if ($isPostBack && isset($_POST['txt_dept'])) {
			$dept = $_POST['txt_dept'];
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}


?>

<script>
	$(function() {
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
	<span id="PageTittle_span" class="hidden">Roster</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Roster</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s10 m10">
						<Select name="txt_dept" id="txt_dept">
							<?php
							$myDB = new MysqliDb();
							$rowData = $myDB->query('select distinct Process,sub_process from new_client_master where account_head="' . $_SESSION['__user_logid'] . '"');
							if (count($rowData) > 0) {
								if ($dept == 'ALL Process') {
									echo '<option selected>ALL Process</option>';
								} else {
									echo '<option>ALL Process</option>';
								}
								foreach ($rowData as $key => $value) {
									if ($dept == $value['Process'] . '-' . $value['sub_process']) {
										echo '<option selected>' . $value['Process'] . '-' . $value['sub_process'] . '</option>';
									} else {
										echo '<option>' . $value['Process'] . '-' . $value['sub_process'] . '</option>';
									}
								}
							}
							?>
						</Select>
					</div>
					<div class="input-field col s2 m2">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>
				</div>


				<?php
				$dt_First = date('Y-m-d', strtotime('next monday'));
				$dt_Last = date('Y-m-d', strtotime('next monday +6 days'));

				$myDB = new mysqliDb();
				//echo "call rosterFor_accounthead_between('".$_SESSION['__user_logid']."','".date('Y-m-d',strtotime($dt_First.' -7days'))."','".date('Y-m-d',strtotime($dt_First.' -1days'))."','".$dept."')";
				$roster_account = $myDB->query("call rosterFor_accounthead_between('" . $_SESSION['__user_logid'] . "','" . date('Y-m-d', strtotime($dt_First . ' -7days')) . "','" . date('Y-m-d', strtotime($dt_First . ' -1days')) . "','" . $dept . "')");
				//echo "<br>";
				$myDB = new mysqliDb();
				$rst_emp = $myDB->query("call activeFor_accounthead('" . $_SESSION['__user_logid'] . "','" . $dept . "')");
				//echo "call activeFor_accounthead('".$_SESSION['__user_logid']."','".$dept."')";
				$date_ho_list = array();
				$myDB = new mysqliDb();
				$date_ho_list_db = $myDB->query("SELECT distinct DateOn FROM ho_list_admin where location='" . $_SESSION['__location'] . "'");
				if (count($date_ho_list_db) > 0 && $date_ho_list_db) {
					foreach ($date_ho_list_db as $key_ho => $val_ho) {
						$date_ho_list[] = $val_ho['DateOn'];
					}
				}

				if (count($rst_emp) > 0) {

					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						<div class=""  >
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>Monday</th>';
					$table .= '<th>Tuesday</th>';
					$table .= '<th>Wednesday</th>';
					$table .= '<th>Thursday</th>';
					$table .= '<th>Friday</th>';
					$table .= '<th>Saturday</th>';
					$table .= '<th>Sunday</th>';
					$table .= '<th>Month</th>';
					$table .= '<th>Year</th>';
					$table .= '<th>Type</th>';
					$table .= '<th>Work From</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>Employee Stage</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<thead><tbody>';
					foreach ($rst_emp as $key => $values) {

						$table .= '<tr><td>' . $values['EmployeeID'] . '</td>';
						$begin = new DateTime($dt_First);
						$end   = new DateTime($dt_Last);
						$myDB = new mysqliDb();
						$weekoff_pref = $myDB->query("select FirstPre,SecondPre from rosterpref where EmpID ='" . $values['EmployeeID'] . "' and WeekNo ='" . $dt_First . " To " . $dt_Last . "' order by id desc ");

						$weekoff_pref_1 = '';
						$weekoff_pref_2 = '';
						if (count($weekoff_pref) > 0) {
							$weekoff_pref_1 = $weekoff_pref[0]['FirstPre'];
							$weekoff_pref_2 = $weekoff_pref[0]['SecondPre'];
						} else {
							/*
						$myDB = new MysqliDb();
						$weekoff_pref = $myDB->query("select FirstPre,SecondPre from rosterpref where EmpID ='".$values['EmployeeID']."' and WeekNo ='".date('Y-m-d',strtotime($dt_First.' -1 days'))." To ".date('Y-m-d',strtotime($dt_First.' -7 days'))."' order by id desc ");
						$weekoff_pref_1='';
						$weekoff_pref_2='';
						if(count($weekoff_pref) > 0 )
						{
							$weekoff_pref_1=$weekoff_pref[0]['rosterpref']['FirstPre'];
							$weekoff_pref_2=$weekoff_pref[0]['rosterpref']['SecondPre'];
						}
						else
						{
							
							$weekoff_pref_1 = 'Sunday';
						}*/
							//$weekoff_pref_1 = '';
						}
						$roster_time = array('09:00-18:00');
						$intime = array();
						$outtime = array();
						$outtime = array();
						$type = array(1);
						$work_from = array();
						$inactive = '';
						foreach ($roster_account as $key_ros => $value_ros) {
							if ($values['EmployeeID'] == $value_ros['EmployeeID']) {
								$roster_values = $value_ros['InTime'] . '-' . $value_ros['OutTime'];
								if (is_numeric($roster_values[0])) {
									$intime[] = $value_ros['InTime'];
									$outtime[] = $value_ros['OutTime'];
									$roster_time[] = $value_ros['InTime'] . '-' . $value_ros['OutTime'];
									$type[] = $value_ros['type_'];
									if ($value_ros['work_from'] == "") {
										$work_from[] = "WFO";
									} else {
										$work_from[] = $value_ros['work_from'];
									}
								}

								if (strtoupper($value_ros['InTime']) == 'DCR' || strtoupper($value_ros['InTime']) == 'IR' || strtoupper($value_ros['InTime']) == 'TER' || strtoupper($value_ros['InTime']) == 'ABSC'  || strtoupper($value_ros['InTime']) == 'RES') {
									$inactive = strtoupper($value_ros['InTime']);
								}
							}
						}
						$myDB = new MysqliDb();
						$extraDates = $myDB->query("select cast(Final_OJT_date as date) OutOJT from status_quality where  EmployeeID ='" . $values['EmployeeID'] . "' limit 1");

						$OutOJT = "";
						if (count($extraDates) > 0 && $extraDates) {
							$OutOJT = $extraDates[0]['OutOJT'];
						}
						if (empty($roster_time)) {
							$roster_time[] = '09:00-18:00';
						}
						if (empty($work_from)) {
							$work_from[] = 'WFO';
						}

						$c = array_count_values($roster_time);
						$val = array_search(max($c), $c);

						$c = array_count_values($type);
						$typeval = array_search(max($c), $c);

						$c = array_count_values($work_from);
						$workfrom = array_search(max($c), $c);

						if (empty($val) && $val == '0') {
							$val = '09:00-18:00';
						}
						if ($val == 'WO-WO' || $val == 'L') {
							$val = '09:00-18:00';
						}

						$Ojt_date = '';
						//$val =explode('-',$val);
						for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
							if ($OutOJT == $i->format('Y-m-d')) {
								$Ojt_date = ' In OJT ';
							} else {
								$Ojt_date = '';
							}
							if (!empty($inactive)) {
								$table .= '<td>' . $inactive . '</td>';
							} else {
								$strLeave = 'select EmployeeID from leavehistry where EmployeeID = "' . $values['EmployeeID'] . '"and EmployeeComment !="DECLINE"  and "' . $i->format('Y-m-d') . '" between DateFrom and DateTo';

								$myDB = new MysqliDb();
								$myLeave = $myDB->query($strLeave);

								if (!empty($myLeave[0]['EmployeeID'])) {

									//$table .='<td style="padding: 0px;min-width: 200px;max-width: 200px;" ><input type="text" class="text_td_roster" name="txtIntime'.$values['EmployeeID'].'_'.$i->format('d').'"  id="txtIntime'.$values['EmployeeID'].'_'.$i->format('d').'" value="L" /><input type="text" class="text_td_roster" name="txtOuttime'.$values['EmployeeID'].'_'.$i->format('d').'"  id="txtOuttime'.$values['EmployeeID'].'_'.$i->format('d').'" value="L" /></td>';

									$table .= '<td>L' . $Ojt_date . '</td>';
								} else {
									$dayinstr = $i->format('l');
									if (strtoupper($weekoff_pref_1) == strtoupper($i->format('l')) && (strtoupper($weekoff_pref_2) != strtoupper('No Weekoff Required') && strtoupper($weekoff_pref_1) != strtoupper('No Weekoff Required'))) {
										$table .= '<td>P1' . $Ojt_date . '</td>';
									} elseif (strtoupper($weekoff_pref_2) == strtoupper($i->format('l')) && (strtoupper($weekoff_pref_2) != strtoupper('No Weekoff Required') && strtoupper($weekoff_pref_1) != strtoupper('No Weekoff Required'))) {
										$table .= '<td>P2' . $Ojt_date . '</td>';
									} elseif ((strtoupper($weekoff_pref_2) == strtoupper('No Weekoff Required') || strtoupper($weekoff_pref_1) == strtoupper('No Weekoff Required')) && strtoupper('Monday') == strtoupper($i->format('l'))) {
										//$table .='<td>'.$val.'</td>';
										$table .= '<td>NWR' . $Ojt_date . '</td>';
									} else {
										$explod = explode('-', $val);

										if ($typeval == "4") {
											$temp_shft_1 =  explode("|", $explod[0]);

											if (strlen($temp_shft_1[0]) == '4') {
												$temp_shft_1[0] = '0' . $temp_shft_1[0];
											}
											if (strlen($temp_shft_1[1]) == '4') {
												$temp_shft_1[1] = '0' . $temp_shft_1[1];
											}

											//$intimew = $explod[0];
											$intimew = $temp_shft_1[0] . '|' . $temp_shft_1[1];


											$temp_shft_1 =  explode("|", $explod[1]);
											$temp1 = $temp_shft_1[0];
											$temp1 = $temp_shft_1[1];

											if (strlen($temp_shft_1[0]) == '4') {
												$temp_shft_1[0] = '0' . $temp_shft_1[0];
											}
											if (strlen($temp_shft_1[1]) == '4') {
												$temp_shft_1[1] = '0' . $temp_shft_1[1];
											}

											//$intimew = $explod[0];
											$outtimew = $temp_shft_1[0] . '|' . $temp_shft_1[1];


											/*$outtimew = $explod[1];
										if(strlen($intimew) == '4')
										{
											$intimew = '0'.$intimew;
										}
										if(strlen($outtimew) == '4')
										{
											$outtimew = '0'.$outtimew;
										}*/
										} else {
											$intimew = $explod[0];
											$outtimew = $explod[1];
											if (strlen($intimew) == '4') {
												$intimew = '0' . $intimew;
											}
											if (strlen($outtimew) == '4') {
												$outtimew = '0' . $outtimew;
											}
										}

										$val = $intimew . '-' . $outtimew;
										if (!in_array($i->format('Y-m-d'), $date_ho_list)) {
											$table .= '<td>' . $val . '' . $Ojt_date . '</td>';
										} else {
											if ($typeval == "4") {
												$table .= '<td>HO|HO' . $Ojt_date . '</td>';
											} else {
												$table .= '<td>HO-HO' . $Ojt_date . '</td>';
											}
										}

										//$table .='<td></td>';
									}

									//$table .='<td style="padding: 0px;min-width: 200px;max-width: 200px;" ><input type="text" class="text_td_roster" name="txtIntime'.$values['EmployeeID'].'_'.$i->format('d').'"  id="txtIntime'.$values['EmployeeID'].'_'.$i->format('d').'" value="'.$val[0].'" /><input type="text" class="text_td_roster" name="txtOuttime'.$values['EmployeeID'].'_'.$i->format('d').'"  id="txtOuttime'.$values['EmployeeID'].'_'.$i->format('d').'" value="'.$val[1].'" /></td>';

								}
							}
						}

						$table .= '<td>' . $begin->format('M') . '</td>';
						$table .= '<td>' . $begin->format('Y') . '</td>';
						$myDB = new MysqliDb();
						$sd_rtype = $myDB->query("select rt_type from salary_details where  EmployeeID ='" . $values['EmployeeID'] . "' limit 1;");
						if (count($sd_rtype) > 0 && $sd_rtype) {
							$table .= '<td>' . $sd_rtype[0]['rt_type'] . '</td>';
						} else {
							$table .= '<td>NA</td>';
						}
						$table .= '<td>' . $workfrom . '</td>';

						/*if($val !='L' && $val != 'WO-WO')
					{
						$roster_inout = explode('-',$val);
						$tmmp = $roster_inout[0][0];
							if(is_numeric($tmmp))
							{
							if (strpos($roster_inout[0], '|') !== false)
							{
								$table .='<td>4</td>';
							}
							else
							{
							
							
							if($roster_inout[0] < $roster_inout[1])
							{
							$start_date = new DateTime($begin->format('Y-m-d').' '.$roster_inout[0]);
							$since_start = $start_date->diff(new DateTime($begin->format('Y-m-d').' '.$roster_inout[1]));
							if($since_start->h == 5)
							{
								$table .='<td>3</td>';	
							}
							elseif($since_start->h == 11)
							{
								$table .='<td>2</td>';	
							}
							else
							{
								$table .='<td>1</td>';	
							}
						}
							else
							{
							$start_date = new DateTime($begin->format('Y-m-d').' '.$roster_inout[0]);
							$since_start = $start_date->diff(new DateTime($begin->modify('+1 day')->format('Y-m-d').' '.$roster_inout[1]));
							if($since_start->h == 5)
							{
								$table .='<td>3</td>';	
							}
							elseif($since_start->h == 11)
							{
								$table .='<td>2</td>';	
							}
							else
							{
								$table .='<td>1</td>';	
							}
						}
						}
					}
					else
					{
						$table .='<td>1</td>';	
					}
						
					}
					else
					{
						$table .='<td>1</td>';	
					}*/

						$table .= '<td>' . $values['EmployeeName'] . '</td>';
						$sd_stage = $myDB->query("select status from status_table where EmployeeID ='" . $values['EmployeeID'] . "' limit 1;");
						$emp_stage = '';
						if (count($sd_stage) > 0 && $sd_stage) {
							if ($sd_stage[0]['status'] == '1') {
								$emp_stage = 'To HR';
							} else if ($sd_stage[0]['status'] == '2') {
								$emp_stage = 'To Training Head';
							} else if ($sd_stage[0]['status'] == '3') {
								$emp_stage = 'In Training';
							} else if ($sd_stage[0]['status'] == '4') {
								$emp_stage = 'To Quality Head';
							} else if ($sd_stage[0]['status'] == '5') {
								$emp_stage = 'In OJT';
							} else if ($sd_stage[0]['status'] == '6') {
								$emp_stage = 'On Floor';
							}
							$table .= '<td>' . $emp_stage . '</td>';
						} else {
							$table .= '<td>NA</td>';
						}
						$table .= '<td>' . $values['designation'] . '</td>';
						$table .= '<td>' . $values['Process'] . '</td>';
						$table .= '<td>' . $values['sub_process'] . '</td>';
						$table .= '</tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
					echo "<script>$(function(){ toastr.success('Roster Generated for " . count($rst_emp) . " Employees.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.info('No Data Found.'); }); </script>";
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