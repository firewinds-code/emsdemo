<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$DateTo = '';
if (isset($_REQUEST['date'])) {
	$DateTo = 	clean($_REQUEST['date']);
}

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
if (isset($_REQUEST['empid']) && !empty($DateTo) && !empty($_REQUEST['cm_id'])) {
	$empid = clean($_REQUEST['empid']);
	$cm_id = clean($_REQUEST['cm_id']);
	if (empty($DateTo)) {
		$DateTo = date('Y-m-d', strtotime("today"));
	}
	$query = "select status_table.EmployeeID ,wh.des_id,wh.ReportTo,
case 
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
 where ( wh.ReportTo = ?) and wh.EmployeeID != ? and wh.cm_id =?";

	$selectQ = $conn->prepare($query);
	$selectQ->bind_param("ssi", $empid, $empid, $cm_id);
	$selectQ->execute();
	$chk_task = $selectQ->get_result();
	// $chk_task = $myDB->query($query);
	$counter = 0;
	$my_error = $myDB->getLastError();
	if ($chk_task->num_rows > 0 && $chk_task) {
		$monday = '';
		if (strtolower(date('l', strtotime($DateTo))) == 'monday') {
			$monday =  date('Y-m-d', strtotime($DateTo));
		} else {
			$monday =  date('Y-m-d', strtotime($DateTo . ' last monday'));
		}
		$DateFrom = date('Y-m-d', strtotime($monday));
		$last_date = date('Y-m-d', strtotime($monday . ' +6 days'));

		$table = '<div class="col-sm-12" style="overflow:auto;width: 100%;height: 350px;margin-top:10px;" id="tbl_div"><div class="" style=""><table id="myTable_ttnp" class="data"><thead><tr rowspan="2">';
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
			$EmployeeID = $value['EmployeeID'];
			$table .= '<tr>';
			/*$table .='<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><b>'.$value['EmployeeID'].'</b></td>';		*/

			if ($value['des_id'] != '9' && $value['des_id'] != '12' && $value['EmployeeID'] != clean($_SESSION['__user_logid']) && $value['EmployeeID'] != clean($_REQUEST['empid'])) {
				$table .= '<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><a onclick="javascript:return calc_Team(this);" data="' . $value['EmployeeID'] . '" date="' . $DateTo . '" cm_id="' . clean($_REQUEST['cm_id']) . '" class="btn"><i class="fa fa-plus"></i> ' . $value['EmployeeID'] . '</a></td>';
			} else {
				$table .= '<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><a class="btn">' . $value['EmployeeID'] . '</a></td>';
			}

			$table .= '<td class="EmployeeDetail" empid="' . $value['EmployeeID'] . '" style="font-weight: bold;cursor: pointer;color: royalblue;    text-transform: uppercase;">' . $value['EmployeeName'] . '</td>';
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['Employee Level'] . '</td>';
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['designation'] . '</td>';

			$roster = 'select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID =? and DateOn between ? and ?';
			$select = $conn->prepare($roster);
			$select->bind_param("sss", $EmployeeID, $DateFrom, $last_date);
			$select->execute();
			$ds_roster = $select->get_result();
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
				$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month =? and year = ? limit 1";
				$sel = $conn->prepare($strsql);
				$sel->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$sel->execute();
				$dshr = $sel->get_result();
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
			} elseif (date('Y-m', strtotime($DateFrom)) != date('Y-m', strtotime($i_datefrom))) {


				$date_range = getDatesFromRange($DateFrom, date('Y-m-t', strtotime($DateFrom)));
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);
				$h_month = date('m', strtotime($DateFrom));
				$h_year = date('Y', strtotime($DateFrom));

				$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month =? and year = ? limit 1";
				$sel = $conn->prepare($strsql);
				$sel->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$sel->execute();
				$dshr = $sel->get_result();
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
				$sel = $conn->prepare($strsql);
				$sel->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$sel->execute();
				$dshr = $sel->get_result();
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

				if ($i->format('Y-m-d') == date('Y-m-d', time())) {
					// $myDB = new MysqliDb();
					$bioMetric_Data  = "select PunchTime from biopunchcurrentdata where EmpID = ? and DateOn = ? order by DateOn,str_to_date(PunchTime,'%k:%i:%s') desc limit 1";
					$selQry = $conn->prepare($bioMetric_Data);
					$selQry->bind_param("ss", $EmployeeID, date('Y-m-d', time()));
					$selQry->execute();
					$result = $selQry->get_result();
					$bioMetricData = $result->fetch_row();
					$biodata = clean($bioMetricData[0]);
					if (isset($biodata)) {

						$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
						$table .= '<td style="background-color: #9885ff;color: white;font-weight: bold;text-shadow: 1px 1px 1px black;">' . $biodata . '</td>';
					} else {

						$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
						$table .= '<td>-</td>';
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

				$table .= '<td>' . $RosterIn[$i->format('Y-m-d')] . '-' . $RosterOut[$i->format('Y-m-d')] . '</td>';
				$table .= '<td>-</td>';
			}



			$table .= '<td style="cursor: pointer;color: black; ">' . $value['Process'] . '</td>';
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['sub_process'] . '</td>';
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['clientname'] . '</td>';
			/*$table .='<td style="cursor: pointer;color: black; ">'.$value['DOJ'].'</td>';*/
			/*$table .='<td style="cursor: pointer;color: black; ">'.$value['pdt']['Trainer'].'</td>';
						$table .='<td style="cursor: pointer;color: black; ">'.$value['pdth']['TH'].'</td>';
						$table .='<td style="cursor: pointer;color: black; ">'.$value['pdq']['QA_OJT'].'</td>';
						$table .='<td style="cursor: pointer;color: black; ">'.$value['pdqaops']['QA_OPS'].'</td>';					
						$table .='<td style="cursor: pointer;color: black; ">'.$value['pdqh']['QH'].'</td>';					
						$table .='<td style="cursor: pointer;color: black; ">'.$value['pdah']['AH'].'</td>';*/
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['RT'] . '</td>';
			$table .= '<td style="cursor: pointer;color: black; ">' . $value['ReportTo'] . '</td>';
			$table .= '</tr>';
		}
		$table .= '</tbody></table></div></div>';
		echo $table;
	} else {
		$alert_msg = '<span class="text-danger">No data found</span>';
	}
}
