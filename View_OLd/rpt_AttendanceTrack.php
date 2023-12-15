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
	$user_logID = clean($_SESSION['__user_logid']);
	if (!isset($user_logID)) {
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
if (isset($_POST['txt_dateFor']) && strtotime($_POST['txt_dateFor'])) {
	$DateTo = cleanUserInput($_POST['txt_dateFor']);
} else {
	$DateTo = date('Y-m-d', strtotime("previous day"));
}
$process = 'NA';
if (!empty($_POST['txt_Process'])) {
	$process = cleanUserInput($_POST['txt_Process']);
}

$__user_logid = clean($_SESSION['__user_logid']);
$__user_type = clean($_SESSION['__user_type']);

?>

<script>
	$(function() {
		$('#txt_dateFor').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '-1970/02/01',
			maxDate: '-1970/01/02'
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
			}, 'pageLength'],
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Attendance Track Report </span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Attendance Track Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Attendance Track Report</h4>
						<div class="modal-body">

							<div class="input-field col s6 m6">
								<input type="text" name="txt_dateFor" id="txt_dateFor" value="<?php echo $DateTo; ?>" />
							</div>

							<div class="input-field col s6 m6">
								<select name="txt_Process" id="txt_Process" required>
									<option value="NA">---Select---</option>
									<?php
									if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
										if ($process == 'ALL') {
											echo '<option value="ALL" selected >---ALL---</option>';
										} else {
											echo '<option value="ALL" >---ALL---</option>';
										}
									}

									$sqlBy = "SELECT distinct Process,clientname,sub_process,cm_id from whole_details_peremp  where (ReportTo = ? or whole_details_peremp.EmployeeID = ? or whole_details_peremp.account_head = ? or whole_details_peremp.oh = ? or whole_details_peremp.qh = ? or whole_details_peremp.th = ?) order by clientname";
									$selectQ = $conn->prepare($sqlBy);
									$selectQ->bind_param("ssssss", $__user_logid, $__user_logid, $__user_logid, $__user_logid, $__user_logid, $__user_logid);
									$selectQ->execute();
									$resultBy = $selectQ->get_result();
									if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
										$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
										$resultBy = $myDB->query($sqlBy);
									}
									// print_r($resultBy);
									// exit;

									// $my_error = $myDB->getLastError();
									// if (empty($my_error)) {
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
								<!--<button type="button" class="btn waves-effect waves-green" name="btnExport" id="btnExport"> Export</button>-->
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
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
				if (!empty($process) && $process != 'NA') {
					if (empty($DateTo)) {
						$DateTo = date('Y-m-d', strtotime("today"));
					}
					$query = "";
					$date_check = $DateTo;
					if ($__user_type == 'ADMINISTRATOR' || $__user_type == 'CENTRAL MIS') {
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						if ($process == 'ALL') {
							$query = "select status_table.EmployeeID ,wh.des_id,case when status_table.status = 1 and status_table.InTraining is not null  then concat( 'Refer to HR') when status_table.status = 2 then concat( 'Mapped and Align to TH' ) 
							when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0  then concat( 'In Training' ) 
							when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1  then concat( 'In RE-Training' ) 
							when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) 
							when status_table.status = 4 then concat( 'Align To QH' ) when status_table.status = 5 and status_quality.ojt_status = 0 then 
							concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) 
							when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') 
							when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process,
							wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH,
							pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS 
							from  status_table 
							inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID 
							left outer join status_training on  status_training.EmployeeID = status_table.EmployeeID 
							left outer join status_quality on  status_quality.EmployeeID = status_table.EmployeeID 
							left outer join personal_details pdt on  wh.Trainer = pdt.EmployeeID 
							left outer join personal_details pdth on  wh.TH = pdth.EmployeeID 
							left outer join personal_details pdah on  wh.account_head = pdah.EmployeeID 
							left outer join personal_details pdq on  wh.Quality = pdq.EmployeeID 
							left outer join personal_details pdqh on  wh.QH = pdqh.EmployeeID 
							left outer join personal_details pdrt on  wh.ReportTo = pdrt.EmployeeID 
							left outer join personal_details pdqaops on  wh.Qa_ops = pdqaops.EmployeeID
							where 1";
							$selectQry = $conn->prepare($query);
							$selectQry->execute();
							$chk_task = $selectQry->get_result();
							// $chk_task = $myDB->query($query);
						} else {
							$query = "select status_table.EmployeeID ,wh.des_id,case 
							when status_table.status = 1 and status_table.InTraining is not null  then concat( 'Refer to HR') 
							when status_table.status = 2 then concat( 'Mapped and Align to TH' ) 
							when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0  then concat( 'In Training' ) 
							when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1  then concat( 'In RE-Training' ) 
							when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) 
							when status_table.status = 4 then concat( 'Align To QH' ) 
							when status_table.status = 5 and status_quality.ojt_status = 0 then 
							concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) 
							when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') 
							when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process,
							wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH,
							pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS 
							from  status_table 
							inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID 
							left outer join status_training on  status_training.EmployeeID = status_table.EmployeeID 
							left outer join status_quality on  status_quality.EmployeeID = status_table.EmployeeID 
							left outer join personal_details pdt on  wh.Trainer = pdt.EmployeeID 
							left outer join personal_details pdth on  wh.TH = pdth.EmployeeID 
							left outer join personal_details pdah on  wh.account_head = pdah.EmployeeID 
							left outer join personal_details pdq on  wh.Quality = pdq.EmployeeID 
							left outer join personal_details pdqh on  wh.QH = pdqh.EmployeeID 
							left outer join personal_details pdrt on  wh.ReportTo = pdrt.EmployeeID 
							left outer join personal_details pdqaops on  wh.Qa_ops = pdqaops.EmployeeID
							where  wh.cm_id =?";
							$selectQry = $conn->prepare($query);
							$selectQry->bind_param("i", $process);
							$selectQry->execute();
							$chk_task = $selectQry->get_result();
							// print_r($chk_task);
						}
					} else {
						$query = "select status_table.EmployeeID ,wh.des_id,case when status_table.status = 1 and status_table.InTraining is not null  then concat( 'Refer to HR') 	when status_table.status = 2 then concat( 'Mapped and Align to TH' ) 
						when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0  then concat( 'In Training' ) 
						when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1  then concat( 'In RE-Training' ) 
						when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) 
						when status_table.status = 4 then concat( 'Align To QH' ) 
						when status_table.status = 5 and status_quality.ojt_status = 0 then 
						concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) 
						when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') 
						when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process,
						wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH,
						pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS 
						from  status_table 
						inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID 
						left outer join status_training on  status_training.EmployeeID = status_table.EmployeeID 
						left outer join status_quality on  status_quality.EmployeeID = status_table.EmployeeID 
						left outer join personal_details pdt on  wh.Trainer = pdt.EmployeeID 
						left outer join personal_details pdth on  wh.TH = pdth.EmployeeID 
						left outer join personal_details pdah on  wh.account_head = pdah.EmployeeID 
						left outer join personal_details pdq on  wh.Quality = pdq.EmployeeID 
						left outer join personal_details pdqh on  wh.QH = pdqh.EmployeeID 
						left outer join personal_details pdrt on  wh.ReportTo = pdrt.EmployeeID 
						left outer join personal_details pdqaops on  wh.Qa_ops = pdqaops.EmployeeID
						where ( wh.ReportTo = ? or wh.EmployeeID = ? or wh.account_head = ? or wh.oh = ?  or wh.qh = ? or wh.th = ? ) and wh.cm_id =?";
						$selectQy = $conn->prepare($query);
						$selectQy->bind_param("ssssssi",  $__user_logid, $__user_logid, $__user_logid, $__user_logid, $__user_logid, $__user_logid, $process);
						$selectQy->execute();
						$chk_task = $selectQy->get_result();
					}

					$counter = 0;

					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0 && $chk_task) {

						$table = '<table id="myTable_ttnp" class="data"><thead><tr>';
						$table .= '<th >EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';

						$table .= '<th>Date</th>';
						$table .= '<th>Biometric Hours</th>';
						$table .= '<th>APR</th>';
						$table .= '<th>Attendance</th>';

						$table .= '<th >Employee Stage</th>';
						$table .= '<th >Designation</th>';
						$table .= '<th >Process</th>';
						$table .= '<th >Sub Process</th>';
						$table .= '<th >Client</th>';
						$table .= '<th >Supervisor</th>';
						$table .= '</tr>';
						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {
							$EmployeeID = $value['EmployeeID'];
							$table .= '<tr>';
							/*$table .='<td ><b>'.$value['EmployeeID'].'</b></td>';		*/

							if ($value['des_id'] != '9' && $value['des_id'] != '12' && $value['EmployeeID'] != $__user_logid && $process != 'ALL') {
								$table .= '<td ><a onclick="javascript:return calc_Team(this);" data="' . $value['EmployeeID'] . '" date="' . $DateTo . '" cm_id="' . $process . '" class="btn"><i class="fa fa-plus"></i> ' . $value['EmployeeID'] . '</a></td>';
							} else {
								$table .= '<td >' . $value['EmployeeID'] . '</td>';
							}

							$table .= '<td class="EmployeeDetail" empid="' . base64_encode($value['EmployeeID']) . '" style="font-weight: bold;cursor: pointer;color: royalblue;    text-transform: uppercase;">' . $value['EmployeeName'] . '</td>';

							$table .= '<td><b>' . $DateTo . '</b></td>';


							$biometric = 'SELECT EmpID,CAST(MIN(`biopunchcurrentdata`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biopunchcurrentdata`.`PunchTime`), MIN(`biopunchcurrentdata`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biopunchcurrentdata`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` from biopunchcurrentdata where EmpID =? and DateOn = ? group by EmployeeID,DateOn';
							$selectQ = $conn->prepare($biometric);
							$selectQ->bind_param("ss", $EmployeeID, $DateTo);
							$selectQ->execute();
							$ds_biometric = $selectQ->get_result();
							$dsmetric = $ds_biometric->fetch_row();

							if ($ds_biometric->num_rows > 0 && $ds_biometric) {
								$i_bioATND1 = '00:00:00';
								$i_bioIN1 = clean($dsmetric[1]);
								$i_bioOUT1  = clean($dsmetric[2]);
								if (empty($i_bioIN1) || empty($i_bioOUT1) || !strtotime($i_bioIN1) || !strtotime($i_bioOUT1)) {
								} else {
									$str1 = $DateTo . ' ' . $i_bioIN1;
									$str2 = $DateTo . ' ' . $i_bioOUT1;

									$iTime_in = new DateTime($str1);
									$iTime_out = new DateTime($str2);
									if ($str1 <= $str2) {
										$interval = $iTime_in->diff($iTime_out);
										$i_bioATND1 = date('H:i:s', strtotime($interval->format('%H') . ':' . $interval->format('%i') . ':' . $interval->format('%s')));
									}
								}
								$table .= '<td><b>' . $i_bioATND1 . '</b></td>';
							} else {
								$table .= '<td><b>-</b></td>';
							}

							if ($value['des_id'] != '9' && $value['des_id'] != '12') {
								$table .= '<td style="cursor: pointer;color: black; ">-</td>';
							} else {
								$downtime = 'SELECT sum(time_to_sec(TotalDT)) sec,LoginDate from downtime where EmpID =? and FAStatus ="Approve" and RTStatus ="Approve" and LoginDate = ? group by LoginDate';
								$DTHour = '00:00';
								$selectQ = $conn->prepare($downtime);
								$selectQ->bind_param("ss", $EmployeeID, $DateTo);
								$selectQ->execute();
								$ds_downtime = $selectQ->get_result();

								if ($ds_downtime->num_rows > 0 && $ds_downtime) {

									foreach ($ds_downtime as $key => $val) {
										$date_for = $val['LoginDate'];
										$minute = intval(($val['sec'] % 3600) / 60);
										if ($minute <= 9) {
											$minute = '0' . $minute;
										}
										$DTHour = intval($val['sec'] / 3600) . ':' . $minute;
										unset($date_for);
									}

									unset($ds_downtime);
								}


								$dtapr = "select D" . intval(date('d', strtotime($date_check))) . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month =? and year = ? order by id desc limit 1";
								$value1 = "";
								$selectQ = $conn->prepare($dtapr);
								$selectQ->bind_param("sii", $EmployeeID,  intval(date('m', strtotime($date_check))), intval(date('Y', strtotime($date_check))));
								$selectQ->execute();
								$dt_apr = $selectQ->get_result();

								if ($dt_apr->num_rows > 0) {
									$value1 = $dt_apr[0]['D' . intval(date('d', strtotime($date_check)))];
								}

								if ($DTHour != '00:00') {

									if ($value1 == '-' || $value1 == '' || $value1 == null) {
										$APR = $DTHour;
									} else {
										$v1 = explode(':', $value1);
										$t1 = explode(':', $DTHour);
										$dataTime1 = $v1[0] + $t1[0];
										$dataTime2 = $v1[1] + $t1[1];
										if ($dataTime2 >= 60) {
											$dataTime1 = $dataTime1 + intval($dataTime2 / 60);
											$dataTime2 = ($dataTime2 % 60);
										}
										if (intval($dataTime2) <= 9) {
											$dataTime2 = '0' . $dataTime2;
										}
										$APR = $dataTime1 . ':' . $dataTime2;
									}
								} else {
									$APR = $value1;
								}
								$table .= '<td style="cursor: pointer;color: black; ">' . $APR . '</td>';
							}

							$dtatnd = "select D" . intval(date('d', strtotime($date_check))) . " from calc_atnd_master where EmployeeID =? and month ='" . intval(date('m', strtotime($date_check))) . "' and year = '" . intval(date('Y', strtotime($date_check))) . "' order by id desc limit 1";
							$selectQ = $conn->prepare($dtatnd);
							$selectQ->bind_param("s", $EmployeeID);
							$selectQ->execute();
							$dt_atnd = $selectQ->get_result();
							$Dt_Atnd = $dt_atnd->fetch_row();
							$ATND = '';
							if ($dt_atnd->num_rows > 0 && $dt_atnd) {
								$ATND = clean($Dt_Atnd[0]);
							}

							$table .= '<td style="cursor: pointer;color: black; ">' . $ATND . '</td>';

							$table .= '<td style="cursor: pointer;color: black; ">' . $value['Employee Level'] . '</td>';
							$table .= '<td style="cursor: pointer;color: black; ">' . $value['designation'] . '</td>';
							$table .= '<td style="cursor: pointer;color: black; ">' . $value['Process'] . '</td>';
							$table .= '<td style="cursor: pointer;color: black; ">' . $value['sub_process'] . '</td>';
							$table .= '<td style="cursor: pointer;color: black; ">' . $value['clientname'] . '</td>';
							$table .= '<td style="cursor: pointer;color: black; ">' . $value['RT'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No record found.'); }); </script>";
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
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
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
			if ($(this).children('th').length == 23) {
				var i = 1;
				for (; i <= 23; i++) {

					if (i > 3 && i <= 9) {
						$(this).children('th:nth-child(' + i + ')').css('color', '#005A95').css('background', 'rgb(189, 215, 238)').css('text-shadow', '0px 0px 1px white, 1px 1px 1px grey');
					} else if (i > 9 && i <= 17) {
						$(this).children('th:nth-child(' + i + ')').css('color', 'rgb(4, 136, 10)').css('background', 'rgb(169, 208, 142)').css('text-shadow', '0px 0px 1px white, 1px 1px 1px grey');
					} else if (i > 17 && i <= 23) {
						$(this).children('th:nth-child(' + i + ')').css('color', '#000').css('background', 'rgb(251, 156, 39)').css('text-shadow', 'white 0px 0px 1px, #cac9c9 1px 1px 1px');
					}

				}
			} else if ($(this).children('th').length == 20) {
				var i = 1;
				for (; i <= 20; i++) {

					if (i > 0 && i <= 14) {
						$(this).children('th:nth-child(' + i + ')').css('color', '#005A95').css('background', 'rgb(189, 215, 238)').css('text-shadow', '0px 0px 1px white, 1px 1px 1px grey');
					}
					/*else if(i > 8 && i <= 14)
					{
						$(this).children('th:nth-child('+i+')').css('color','rgb(4, 136, 10)').css('background','rgb(169, 208, 142)').css('text-shadow','0px 0px 1px white, 1px 1px 1px grey');
					}*/
					else if (i > 14 && i <= 20) {
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
				url: "../Controller/calculate_team_atndtrack.php?cm_id=" + $(el).attr('cm_id') + "&empid=" + $(el).attr('data') + "&date=" + $(el).attr('date'),
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
</script>