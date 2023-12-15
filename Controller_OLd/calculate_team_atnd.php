<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$DateTo = '';
$DateTo = 	clean($_REQUEST['date']);
if (isset($DateTo)) {
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
if (isset($_REQUEST['empid']) && !empty($DateTo)) {
	if (empty($DateTo)) {
		$DateTo = date('Y-m-d', strtotime("today"));
	}

	$emp = clean($_REQUEST['empid']);
	$chktask = "select whole_details_peremp.EmployeeID,whole_details_peremp.EmployeeName,des_id from whole_details_peremp where ReportTo =? or Qa_ops =?";
	$selectQury = $conn->prepare($chktask);
	$selectQury->bind_param("ss", $emp, $emp);
	$selectQury->execute();
	$chk_task = $selectQury->get_result();
	$counter = 0;
	// $my_error = $myDB->getLastError();
	if ($chk_task->num_rows > 0 && $chk_task) {


		$monday = '';
		if (strtolower(date('l', strtotime($DateTo))) == 'monday') {
			$monday =  date('Y-m-d', strtotime($DateTo));
		} else {
			$monday =  date('Y-m-d', strtotime($DateTo . ' last monday'));
		}
		$DateFrom = date('Y-m-d', strtotime($monday . ' -7 days'));
		$last_date = date('Y-m-d', strtotime($monday . ' +13 days'));

		$table = '<div class="col-sm-12" style="overflow:auto;width: 100%;height: 400px;margin-top:10px;" id="tbl_div"><div class="" style=""><table id="myTable_ttnp" class="data"><thead><tr>';
		$table .= '<th rowspan="2">EmployeeID</th>';
		$table .= '<th rowspan="2">EmployeeName</th>';
		$table .= '<th rowspan="2">Date</th>';
		$begin = new DateTime($DateFrom);
		$end   = new DateTime($last_date);

		for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
			$table .= '<th>' . strtoupper($i->format('l')) . '</th>';
		}
		$table .= '<tr>';
		$begin = new DateTime($DateFrom);
		$end   = new DateTime($last_date);

		for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
			$table .= '<th>' . $i->format('d-M') . '</th>';
		}


		$table .= '</thead><tbody>';
		foreach ($chk_task as $key => $value) {
			$EmployeeID = $value['EmployeeID'];
			$table .= '<tr>';
			if ($value['des_id'] != '9' && $value['des_id'] != '12' && $value['EmployeeID'] != clean($_SESSION['__user_logid'])) {
				$table .= '<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><a onclick="javascript:return calc_Team(this);" data="' . $value['EmployeeID'] . '" date="' . $DateTo . '" class="btn"><i class="fa fa-plus"></i> ' . $value['EmployeeID'] . '</a></td>';
			} else {
				$table .= '<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><a class="btn">' . $value['EmployeeID'] . '</a></td>';
			}
			$table .= '<td class="EmployeeDetail" style="font-weight: bold;cursor: pointer;color: royalblue;    text-transform: uppercase;" empid="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '</td>';

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
				$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID ='" . $EmployeeID . "' and month ='" . $h_month . "' and year = '" . $h_year . "' limit 1";
				$myDB = new MysqliDb();
				$dshr = $myDB->query($strsql);
				if ($dshr > 0) {
					foreach ($dshr[0] as $ke => $vals) {
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

				$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID ='" . $EmployeeID . "' and month ='" . $h_month . "' and year = '" . $h_year . "' limit 1";
				$myDB = new MysqliDb();
				$dshr = $myDB->query($strsql);
				if ($dshr > 0) {
					foreach ($dshr[0] as $ke => $vals) {
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

				$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID ='" . $EmployeeID . "' and month ='" . $h_month . "' and year = '" . $h_year . "' limit 1";
				$myDB = new MysqliDb();
				$dshr = $myDB->query($strsql);
				if ($dshr > 0) {
					foreach ($dshr[0] as $ke => $vals) {
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




			$table .= '<td>' . $DateTo . '</td>';
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
					$myDB = new MysqliDb();
					$bioMetricData  = $myDB->query("select PunchTime from biopunchcurrentdata where EmpID = '" . $EmployeeID . "' and DateOn = '" . date('Y-m-d', time()) . "' order by DateOn,str_to_date(PunchTime,'%k:%i:%s') desc limit 1");
					if (isset($bioMetricData[0]['PunchTime'])) {
						$table .= '<td style="background-color: #9885ff;color: white;font-weight: bold;text-shadow: 1px 1px 1px black;">' . $bioMetricData[0]['PunchTime'] . '</td>';
					} else {
						$myDB = new MysqliDb();
						$ds_roster_i = $myDB->query('select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID ="' . $EmployeeID . '" and DateOn = "' . date('Y-m-d', time()) . '"');
						if (count($ds_roster_i) > 0 && $ds_roster_i) {
							$table .= '<td>' . $ds_roster_i[0]['InTime'] . '-' . $ds_roster_i[0]['OutTime'] . '</td>';
						} else {
							$table .= '<td>-</td>';
						}
					}
				} elseif (isset($ATND_cur[$i->format('Y-m-d')])) {
					$table .= '<td>' . $ATND_cur[$i->format('Y-m-d')] . '</td>';
				} elseif (isset($ATND_prev[$i->format('Y-m-d')])) {
					$table .= '<td>' . $ATND_prev[$i->format('Y-m-d')] . '</td>';
				} else {
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
			// $myDB = new MysqliDb();
			$dsroster = 'select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID =? and DateOn between ? and ?';
			$selectQury = $conn->prepare($dsroster);
			$selectQury->bind_param("sss", $EmployeeID, $i_first_date, $last_date);
			$selectQury->execute();
			$ds_roster = $selectQury->get_result();

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
			}
			$table .= '</tr>';
		}
		$table .= '</tbody></table></div></div>';
		echo $table;
	} else {
		echo '<span class="text-danger">No data found</span>';
	}
}
