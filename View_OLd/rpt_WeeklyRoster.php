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

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	$clean_user_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
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
if (isset($clean_date_for) && strtotime($clean_date_for)) {
	$DateTo = $clean_date_for;
} else {
	$DateTo = date('Y-m-d', strtotime("today"));
}
$process = 'NA';
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_process = cleanUserInput($_POST['txt_Process']);
	$clean_pro = cleanUserInput($_POST['txt_Process']);
}
if (!empty($clean_process)) {
	$process = $clean_process;
}
$clean_user_logid = clean($_SESSION['__user_logid']);
$clean_ut = clean($_SESSION['__user_type']);



?>

<script>
	$(function() {
		$('#txt_dateFor').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			maxDate: '+1970/01/00'
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
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Roster vs Present Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Roster vs Present Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Roster vs Present Report</h4>
						<div class="modal-body">

							<div class="col s12 m12" id="rpt_container">
								<div class="input-field col s6 m6">
									<input type="text" name="txt_dateFor" readonly="true" id="txt_dateFor" value="<?php echo $DateTo; ?>" />
									<label for="txt_dateFor">Date</label>
								</div>

								<div class="input-field col s6 m6">
									<select name="txt_Process" id="txt_Process" required>
										<option value="NA">---Select---</option>
										<?php
										$empid = $clean_user_logid;
										$sqlBy = "select distinct Process,clientname,sub_process,cm_id from whole_details_peremp  where (ReportTo = ? or whole_details_peremp.EmployeeID = ? or whole_details_peremp.account_head = ? or whole_details_peremp.oh = ? or whole_details_peremp.qh = ? or whole_details_peremp.th = ?) order by clientname";
										$selectQ = $conn->prepare($sqlBy);
										$selectQ->bind_param("ssssss", $empid, $empid, $empid, $empid, $empid, $empid);
										$selectQ->execute();
										$resultBy = $selectQ->get_result();

										if ($clean_ut == 'ADMINISTRATOR' || $clean_ut == 'CENTRAL MIS') {
											$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
											$resultBy = $myDB->query($sqlBy);
										}



										// $mysql_error = $myDB->getLastError();
										// if ($resultBy->num_rows > 0) {
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
										// }
										?>
									</select>
								</div>

								<div class="input-field col s12 m12 right-align">

									<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
									<!--<button type="button" class="btn waves-effect waves-green" name="btnExport" id="btnExport"><i class="fa fa-download"></i> Export</button>-->
									<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
								</div>

							</div>

						</div>
					</div>
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

				if (!empty($clean_pro) && $clean_pro != 'NA') {
					if (empty($DateTo)) {
						$DateTo = date('Y-m-d', strtotime("today"));
					}
					$query = "";
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					if ($clean_ut == 'ADMINISTRATOR' || $clean_ut == 'CENTRAL MIS') {
						$query = "select status_table.EmployeeID ,wh.des_id,wh.ReportTo, case when status_table.status = 1 and status_table.InTraining is not null then concat( 'Refer to HR') when status_table.status = 2 then concat( 'Mapped and Align to TH' ) when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0 then concat( 'In Training' ) when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1 then concat( 'In RE-Training' ) when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) when status_table.status = 4 then concat( 'Align To QH' ) when status_table.status = 5 and status_quality.ojt_status = 0 then concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process, wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH, pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS from status_table inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID left outer join status_training on status_training.EmployeeID = status_table.EmployeeID left outer join status_quality on status_quality.EmployeeID = status_table.EmployeeID left outer join personal_details pdt on wh.Trainer = pdt.EmployeeID left outer join personal_details pdth on wh.TH = pdth.EmployeeID left outer join personal_details pdah on wh.account_head = pdah.EmployeeID left outer join personal_details pdq on wh.Quality = pdq.EmployeeID left outer join personal_details pdqh on wh.QH = pdqh.EmployeeID left outer join personal_details pdrt on wh.ReportTo = pdrt.EmployeeID left outer join personal_details pdqaops on wh.Qa_ops = pdqaops.EmployeeID where wh.cm_id = ?";
						$select = $conn->prepare($query);
						$clean_proo = cleanUserInput($_POST['txt_Process']);
						$select->bind_param("i", $clean_proo);
						$select->execute();
						$chk_task = $select->get_result();
					} else {
						$emp = $clean_user_logid;
						$query = "select status_table.EmployeeID ,wh.des_id,wh.ReportTo, case when status_table.status = 1 and status_table.InTraining is not null then concat( 'Refer to HR') when status_table.status = 2 then concat( 'Mapped and Align to TH' ) when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0 then concat( 'In Training' ) when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1 then concat( 'In RE-Training' ) when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) when status_table.status = 4 then concat( 'Align To QH' ) when status_table.status = 5 and status_quality.ojt_status = 0 then concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process, wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH, pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS from status_table inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID left outer join status_training on status_training.EmployeeID = status_table.EmployeeID left outer join status_quality on status_quality.EmployeeID = status_table.EmployeeID left outer join personal_details pdt on wh.Trainer = pdt.EmployeeID left outer join personal_details pdth on wh.TH = pdth.EmployeeID left outer join personal_details pdah on wh.account_head = pdah.EmployeeID left outer join personal_details pdq on wh.Quality = pdq.EmployeeID left outer join personal_details pdqh on wh.QH = pdqh.EmployeeID left outer join personal_details pdrt on wh.ReportTo = pdrt.EmployeeID left outer join personal_details pdqaops on wh.Qa_ops = pdqaops.EmployeeID where ( wh.ReportTo = ? or wh.EmployeeID = ? or wh.account_head = ? or wh.oh =?  or wh.qh = ? or wh.th = ? ) and wh.cm_id =?";
						$select = $conn->prepare($query);
						$clean_proc = cleanUserInput($_POST['txt_Process']);
						$select->bind_param("ssssssi", $emp, $emp, $emp, $emp, $emp, $emp, $clean_proc);
						$select->execute();
						$chk_task = $select->get_result();
					}

					//echo $query;

					// $chk_task = $myDB->query($query);
					$counter = 0;
					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0) {
						$monday = '';
						if (strtolower(date('l', strtotime($DateTo))) == 'monday') {
							$monday =  date('Y-m-d', strtotime($DateTo));
						} else {
							$monday =  date('Y-m-d', strtotime($DateTo . ' last monday'));
						}
						$DateFrom = date('Y-m-d', strtotime($monday));
						$last_date = date('Y-m-d', strtotime($monday . ' +6 days'));

						$table = '<div ><table id="myTable_ttnp" class="data dataTable no-footer row-border centered">
					<thead><tr rowspan="2">';

						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;" id="tbl_div"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
						$table .= '<th rowspan="2">EmployeeID</th>';
						$table .= '<th rowspan="2">EmployeeName</th>';

						$table .= '<th  rowspan="2">Employee Stage</th>';
						$table .= '<th  rowspan="2">Designation</th>';
						$table .= '<th  rowspan="2">Week</th>';

						$begin = new DateTime($DateFrom);
						$end   = new DateTime($last_date);

						for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

							$table .= '<th colspan="2">' . strtoupper($i->format('l')) . ' <b>[' . $i->format('d-M') . ']</b></th>';
						}
						$table .= '<th colspan="5">Employee Information</th>';
						$table .= '</tr>';
						$table .= '<tr>';
						$begin = new DateTime($DateFrom);
						$end   = new DateTime($last_date);

						for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {

							$table .= '<th>Roster</th>';
							$table .= '<th>Attendance</th>';
						}
						$table .= '<th >Process</th>';
						$table .= '<th >Sub Process</th>';
						$table .= '<th >Client</th>';
						/*$table .='<th rowspan="2">Date of Join</th>';*/
						/*$table .='<th rowspan="2">Trainer</th>';
					$table .='<th rowspan="2">Training Head</th>';
					$table .='<th rowspan="2">Quality Analyst (OJT)</th>';
					$table .='<th rowspan="2">Quality Analyst (OPS)</th>';
					$table .='<th rowspan="2">Quality Head</th>';
					$table .='<th rowspan="2">Account Head</th>';*/
						$table .= '<th >Supervisor</th>';
						$table .= '<th >Supervisor ID</th>';
						$table .= '</tr>';
						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$EmployeeID = clean($value['EmployeeID']);
							$table .= '<tr>';
							/*$table .='<td ><b>'.$value['EmployeeID'].'</b></td>';		*/
							$clean_proce = cleanUserInput($_POST['txt_Process']);
							if ($value['des_id'] != '9' && $value['des_id'] != '12' && $value['EmployeeID'] != $clean_user_logid) {
								$table .= '<td><a onclick="javascript:return calc_Team(this);" data="' . $value['EmployeeID'] . '" date="' . $DateTo . '" cm_id="' . $clean_proce . '" class="btn"><i class="fa fa-plus"></i> ' . $value['EmployeeID'] . '</a></td>';
							} else {
								$table .= '<td>' . $value['EmployeeID'] . '</td>';
							}

							$table .= '<td class="EmployeeDetail" empid="' . base64_encode($value['EmployeeID']) . '" style="font-weight: bold;cursor: pointer;color: royalblue;    text-transform: uppercase;">' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Employee Level'] . '</td>';
							$table .= '<td>' . $value['designation'] . '</td>';

							$myDB = new MysqliDb();
							$conn = $myDB->dbConnect();
							$selQury = 'select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID =? and DateOn between ? and ?';
							$selectQ = $conn->prepare($selQury);
							$selectQ->bind_param("sss", $EmployeeID, $DateFrom, $last_date);
							$selectQ->execute();
							$ds_roster = $selectQ->get_result();

							$RosterIn = array();
							$RosterOut = array();
							$Roster_type = array();
							if ($ds_roster->num_rows > 0 && $ds_roster) {

								foreach ($ds_roster as $keyr => $valr) {
									$date_for = $valr['DateOn'];
									$RosterIn[$date_for] = $valr['InTime'];
									$RosterOut[$date_for] = $valr['OutTime'];
									$Roster_type[$date_for] = $valr['type_'];
									unset($date_for);
								}
								unset($ds_roster);
							}

							$ATND_cur = array();
							$ATND_prev = array();
							$i_datefrom = '';

							if ($DateTo < date('Y-m-d', time())) {
								if ($last_date <= date('Y-m-d', time())) {
									$i_datefrom   = $last_date;
								} else {
									$i_datefrom   = date('Y-m-d', time());
								}
							} else {
								$i_datefrom   = $DateTo;
							}
							if (date('Y-m', strtotime($DateFrom)) == date('Y-m', strtotime($i_datefrom))) {
								$h_month = date('m', strtotime($i_datefrom));
								$h_year = date('Y', strtotime($i_datefrom));
								$date_range = getDatesFromRange($DateFrom, $i_datefrom);
								foreach ($date_range as &$value_dc) {
									$value_dc = 'D' . $value_dc;
								}
								unset($value_dc);
								$str_t = implode(',', $date_range);
								$SQL = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month =? and year = ? limit 1";
								$selectQry = $conn->prepare($SQL);
								$selectQry->bind_param("sss", $EmployeeID, $h_month, $h_year);
								$selectQry->execute();
								$dshr = $selectQry->get_result();
								// $dshr = $myDB->rawQuery($strsql);
								// $mysql_error = $myDB->getLastError();
								if ($dshr->num_rows > 0) {
									foreach ($dshr as $ke => $vals) {
										foreach ($vals as $keys => $val) {
											$keyDate = substr($keys, 1, strlen($keys));

											if ($keyDate < 10) {
												$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
											} else {
												$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
											}
											if (date('m', strtotime($date_for)) == date('m', time())) {
												$ATND_cur[$date_for] = $val;
											} else {
												$ATND_prev[$date_for] = $val;
											}
										}
									}
									unset($dshr);
								}
							} elseif (date('Y-m', strtotime($DateFrom)) != date('Y-m', strtotime($i_datefrom))) {


								$date_range = getDatesFromRange($DateFrom, date('Y-m-t', strtotime($DateFrom)));
								foreach ($date_range as &$value_dc) {
									$value_dc = 'D' . $value_dc;
								}
								unset($value_dc);
								$str_t = implode(',', $date_range);
								$h_month = date('m', strtotime($DateFrom));
								$h_year = date('Y', strtotime($DateFrom));

								$strSql = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month =? and year = ? limit 1";
								$select_Qry = $conn->prepare($strSql);
								$select_Qry->bind_param("sss", $EmployeeID, $h_month, $h_year);
								$select_Qry->execute();
								$dshr = $select_Qry->get_result();
								// $myDB = new MysqliDb();
								// $dshr = $myDB->query($strsql);
								if ($dshr->num_rows > 0) {
									foreach ($dshr as $ke => $vals) {
										foreach ($vals as $keys => $val) {
											$keyDate = substr($keys, 1, strlen($keys));

											if ($keyDate < 10) {
												$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
											} else {
												$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
											}
											if (date('m', strtotime($date_for)) == date('m', time())) {
												$ATND_cur[$date_for] = $val;
											} else {
												$ATND_prev[$date_for] = $val;
											}
										}
									}
									unset($dshr);
								}
								unset($dshr);
								$date_range = getDatesFromRange(date('Y-m-01', strtotime($i_datefrom)), $i_datefrom);
								foreach ($date_range as &$value_dc) {
									$value_dc = 'D' . $value_dc;
								}
								unset($value_dc);
								$str_t = implode(',', $date_range);

								$h_month = date('m', strtotime($i_datefrom));
								$h_year = date('Y', strtotime($i_datefrom));

								$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month =? and year = ? limit 1";
								$selectQry = $conn->prepare($strsql);
								$selectQry->bind_param("sss", $EmployeeID, $h_month, $h_year);
								$selectQry->execute();
								$dshr = $selectQry->get_result();
								// $myDB = new MysqliDb();
								// $dshr = $myDB->rawQuery($strsql);
								// $mysql_error = $myDB->getLastError();
								if ($dshr->num_rows > 0) {
									foreach ($dshr as $ke => $vals) {
										foreach ($vals as $keys => $val) {
											$keyDate = substr($keys, 1, strlen($keys));

											if ($keyDate < 10) {
												$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
											} else {
												$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
											}
											if (date('m', strtotime($date_for)) == date('m', time())) {
												$ATND_cur[$date_for] = $val;
											} else {
												$ATND_prev[$date_for] = $val;
											}
										}
									}
									unset($dshr);
								}
							}

							$table .= '<td><b>' . $DateFrom . '&nbsp;|&nbsp;' . $last_date . '</b></td>';
							$begin = new DateTime($DateFrom);

							if ($DateTo < date('Y-m-d', time())) {
								if ($last_date <= date('Y-m-d', time())) {
									$end   = new DateTime($last_date);
								} else {
									$end   = new DateTime(date('Y-m-d', time()));
								}
							} else {
								$end   = new DateTime($DateTo);
							}

							for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
								if (!isset($RosterIn[$i->format('Y-m-d')])) {
									$RosterIn[$i->format('Y-m-d')] = '';
								}
								if (!isset($RosterOut[$i->format('Y-m-d')])) {
									$RosterOut[$i->format('Y-m-d')] = '';
								}
								if (!isset($Roster_type[$i->format('Y-m-d')])) {
									$Roster_type[$i->format('Y-m-d')] = '';
								}
								if ($i->format('Y-m-d') == date('Y-m-d', time())) {
									$sqlCheck = "select LeaveID from leavehistry where EmployeeID = ? and MngrStatusID='Approve' and LeaveType = 'Leave' and ? between DateFrom and DateTo limit 1;";
									$sel = $conn->prepare($sqlCheck);
									$sel->bind_param("ss", $EmployeeID, date('Y-m-d', time()));
									$sel->execute();
									$leaveCheck = $sel->get_result();
									if ($leaveCheck->num_rows > 0 && $leaveCheck) {
										$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
										$table .= '<td style="background-color:green;color:white;">L </td>';
									} else {
										$bioMetricDatas  = "select PunchTime from biopunchcurrentdata where EmpID =? and DateOn = ? order by DateOn,str_to_date(PunchTime,'%k:%i:%s') desc limit 1";
										$SelQry = $conn->prepare($bioMetricDatas);
										$SelQry->bind_param("ss", $EmployeeID, date('Y-m-d', time()));
										$SelQry->execute();
										$result = $SelQry->get_result();
										$bioMetricData = $result->fetch_row();
										$biodata = clean($bioMetricData[0]);
										if (isset($biodata)) {

											$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
											$table .= '<td style="background-color: #9885ff;color: white;font-weight: bold;text-shadow: 1px 1px 1px black;">' . $biodata . '</td>';
										} else {

											$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
											$table .= '<td>-</td>';
										}
									}
								} elseif (isset($ATND_cur[$i->format('Y-m-d')])) {

									$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
									$table .= '<td>' . $ATND_cur[$i->format('Y-m-d')] . '</td>';
								} elseif (isset($ATND_prev[$i->format('Y-m-d')])) {

									$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
									$table .= '<td>' . $ATND_prev[$i->format('Y-m-d')] . '</td>';
								} else {

									$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
									$table .= '<td>-</td>';
								}
							}
							$i_first_date = '';
							if ($DateTo < date('Y-m-d', time())) {
								if ($last_date <= date('Y-m-d', time())) {

									$i_first_date   = date('Y-m-d', strtotime('+1 days' . $last_date));
								} else {
									$i_first_date   = date('Y-m-d', strtotime("tomorrow"));
								}
							} else {

								$i_first_date   = date('Y-m-d', strtotime('+1 days' . $DateTo));
							}


							$end   = new DateTime($last_date);
							if ($DateTo < date('Y-m-d', time())) {
								if ($last_date <= date('Y-m-d', time())) {

									$begin   = new DateTime(date('Y-m-d', strtotime('+1 days' . $last_date)));
								} else {
									$begin   = new DateTime(date('Y-m-d', strtotime("tomorrow")));
								}
							} else {
								$begin   = new DateTime(date('Y-m-d', strtotime('+1 days' . $DateTo)));
							}
							for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
								//if($i->format('Y-m-d') != $DateTo)
								if (!isset($RosterIn[$i->format('Y-m-d')])) {
									$RosterIn[$i->format('Y-m-d')] = '';
								}
								if (!isset($RosterOut[$i->format('Y-m-d')])) {
									$RosterOut[$i->format('Y-m-d')] = '';
								}
								if (!isset($Roster_type[$i->format('Y-m-d')])) {
									$Roster_type[$i->format('Y-m-d')] = '';
								}
								$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
								$table .= '<td>-</td>';
							}

							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							/*$table .='<td>'.$value['DOJ'].'</td>';*/
							/*$table .='<td>'.$value['pdt']['Trainer'].'</td>';
						$table .='<td>'.$value['pdth']['TH'].'</td>';
						$table .='<td>'.$value['pdq']['QA_OJT'].'</td>';
						$table .='<td>'.$value['pdqaops']['QA_OPS'].'</td>';					
						$table .='<td>'.$value['pdqh']['QH'].'</td>';					
						$table .='<td>'.$value['pdah']['AH'].'</td>';*/
							$table .= '<td>' . $value['RT'] . '</td>';
							$table .= '<td>' . $value['ReportTo'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.info('No data found') }); </script>";
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
	cell_tcss();

	function cell_tcss() {
		$('.data td').each(function() {
			if ($(this).text() == 'WO' || $(this).text() == 'CO') {
				$(this).css('color', '#3c763d').css('background', 'rgb(204, 239, 150)');
			}
			if ($(this).text() == 'HWP' || $(this).text() == 'LWP') {
				$(this).css('color', '#a94442').css('background', 'rgb(255, 187, 118)');
			}
			if ($(this).text() == 'H' || $(this).text() == 'L') {
				$(this).css('color', 'rgb(10, 87, 111)').css('background', '#9bf1ff');
			}
			if ($(this).text() == 'P') {
				$(this).css('color', '#3c763d').css('background', '#fff');
			}
			if ($(this).text() == 'A' || $(this).text() == 'LANA' || $(this).text() == 'WONA') {
				$(this).css('color', '#b10101').css('background', '#fc6d6d');
			}
			if ($(this).text().indexOf('P(Short Leave)') >= 0) {
				$(this).css('color', '#8f2525').css('background', '#fff');
			}
			if ($(this).text().indexOf('P(Short Login)') >= 0) {
				$(this).css('color', '#6d502e').css('background', '#fff');
			}
			if ($(this).text().indexOf('-') >= 0) {
				$(this).css('color', 'rgb(12, 33, 113)');
			}

			if ($(this).text() == 'WO' || $(this).text() == 'HO' || $(this).text() == 'CO') {
				$(this).css('color', '#3c763d').css('background', 'rgb(204, 239, 150)');
			}
			if ($(this).text() == 'LWP') {
				$(this).css('color', 'rgba(160, 8, 8, 0.98)').css('background', 'rgb(255, 123, 7)');
			}
			if ($(this).text() == 'CO') {
				$(this).css('color', 'rgb(10, 87, 111)').css('background', '#9bf1ff');
			}



			/*if(monthNames[date.getMonth()] == datePart[0])
			{
				alert(datePart[0]+','+datePart[1]);
			}
			else
			{
				alert(datePart[0]+','+datePart[1]);
			}
		*/
		});
		$('.tbl_holdingtr > td').each(function() {
			$(this).css('color', 'gray').css('background', '#fff');
		});
		$('thead tr').each(function() {

			if ($(this).children('th').length == 19) {
				var i = 1;
				for (; i <= 19; i++) {

					if (i > 0 && i <= 14) {
						$(this).children('th:nth-child(' + i + ')').css('color', '#005A95').css('background', 'rgb(189, 215, 238)').css('text-shadow', '0px 0px 1px white, 1px 1px 1px grey');
					}
					/*else if(i > 8 && i <= 14)
					{
						$(this).children('th:nth-child('+i+')').css('color','rgb(4, 136, 10)').css('background','rgb(169, 208, 142)').css('text-shadow','0px 0px 1px white, 1px 1px 1px grey');
					}*/
					else if (i > 14 && i <= 19) {
						$(this).children('th:nth-child(' + i + ')').css('color', '#000').css('background', 'rgb(251, 156, 39)').css('text-shadow', 'white 0px 0px 1px, #cac9c9 1px 1px 1px');
					}

				}
			}

		});
		$('.EmployeeDetail').on('click', function() {

			var tval = $(this).attr('empid');

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

	function calc_Team(el) {
		if ($(el).closest('td').closest('tr').next('tr').attr('class') != 'tbl_holdingtr') {
			$('#overlay').removeClass('hidden');
			$.ajax({
				url: "../Controller/calculate_team_weeklyrpt.php?cm_id=" + $(el).attr('cm_id') + "&empid=" + $(el).attr('data') + "&date=" + $(el).attr('date'),
				success: function(result) {
					if (result != '') {
						$(el).closest('td').closest('tr').after('<tr class="tbl_holdingtr"><td colspan="' + $(el).closest('td').closest('tr').find('td').length + '"><div><hr style="width:100%;margin:0px;"/>' + result + '<br/><hr  style="width:100%;margin:0px;"/></div></td></tr>');
						$(el).find('i').removeClass('fa-plus').addClass('fa-minus');
					} else {
						$(el).closest('td').closest('tr').after('<tr class="tbl_holdingtr"><td colspan="' + $(el).closest('td').closest('tr').find('td').length + '"><div>No data Found</div></td></tr>');
						$(el).find('i').removeClass('fa-plus').addClass('fa-minus');
					}
					cell_tcss();
					$('#overlay').addClass('hidden');
				}
			});


		} else {
			if ($(el).closest('td').closest('tr').next('tr').hasClass('tbl_holdingtr')) {
				$(el).closest('td').closest('tr').next('tr').remove();
				$(el).find('i').removeClass('fa-minus').addClass('fa-plus');
			}
		}

	}

	$(document).ready(function() {
		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
			}
		});

		// This code for cancel button trigger click and also for model close
		$('#btn_Can').on('click', function() {

			$('#txt_Process').val('NA');
			// This code for remove error span from input text on model close and cancel
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}

				}
			});

			// This code active label on value assign when any event trigger and value assign by javascript code.
			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
		$('#btn_view').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
					validate = 1;
					$(this).addClass('has-error');
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#' + spanID).length == 0) {
						$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
					}
					var attr_error = $(this).attr('data-error-msg');
					if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
						$('#' + spanID).html('Required *');
					} else {
						$('#' + spanID).html($(this).attr("data-error-msg"));
					}
				}
			})

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg)
					});
				}
				return false;

			}
		});

		$('#btn_Excel').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
					validate = 1;
					$(this).addClass('has-error');
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#' + spanID).size() == 0) {
						$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
					}
					var attr_error = $(this).attr('data-error-msg');
					if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
						$('#' + spanID).html('Required *');
					} else {
						$('#' + spanID).html($(this).attr("data-error-msg"));
					}
				}
			})

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg)
					});
				}
				return false;

			}
		});


		// This code for remove error span from input text on model close and cancel
		$(".has-error").each(function() {
			if ($(this).hasClass("has-error")) {
				$(this).removeClass("has-error");
				$(this).next("span.help-block").remove();
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				if ($(this).hasClass('select-dropdown')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}

			}
		});

	});

	function downloadexcel(el) {

		if ($('#txt_Process').val() == 'NA') {
			$('#txt_Process').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
			if ($('#spantxt_Process').size() == 0) {
				$('<span id="spantxt_Process" class="help-block">Required *</span>').insertAfter('#txt_Process');
			}
			return false;
		}
		$item = $(el);
		//call sp_get_atnd_Report("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $dept . '","' . $type . '","' . $loc . '")
		var usrid = <?php echo "'" . $_SESSION['__user_logid'] . "'"; ?>;
		var usertype = <?php echo "'" . $_SESSION['__user_type'] . "'"; ?>;
		var process = $('#txt_Process').val();

		//$query =  'call sp_get_atnd_Report("' . $userid . '","' . $month . '","' . $year . '","' . $dept . '","' . $type . '","' . $loc . '")';
		//var sp = "call sp_get_atnd_Report('" + usrid + "','" + date_to + "','" + date_from + "','" + dept + "','" + type + "','" + loc + "')";
		var url = "../Export_Report/auto_roster_present_report.php?usertype=" + usertype + "&userid=" + usrid + "&processid=" + process;
		//alert(url);
		window.location.href = url;


	}
</script>