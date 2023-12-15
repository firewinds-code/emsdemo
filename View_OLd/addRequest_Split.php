<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmplID = clean($_SESSION['__user_logid']);

if (isset($_SESSION)) {
	if (!isset($EmplID)) {
		$location = URL . 'Login';
		//header("Location: $location");
		echo "<script>location.href='" . $location . "'</script>";
		exit();
	}
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
	exit();
}
$OutTime = "";
$order_len = 5;
function dt_diff($d1, $d2)
{
	$datetime1 = new DateTime($d1);
	$datetime2 = new DateTime($d2);
	$difference = $datetime1->diff($datetime2);
	return ($difference->d);
}

function get_timestring($date, $__iTime)
{
	if (isset($__iTime)) {
		if (strtotime($__iTime)) {
			return date('Y-m-d H:i:s', strtotime($date . ' ' . $__iTime));
		}
	} else {
		return ('');
	}
	return ('');
}
function get_inshift_time($r1, $r2, $b1, $b2, $type)
{
	$tbin = new DateTime(date('Y-m-d H:i:s', strtotime($b1)));
	$tbout = new DateTime(date('Y-m-d H:i:s', strtotime($b2)));
	$trin = new DateTime($r1);
	$trout = new DateTime($r2);
	if ($tbin <= $trin && $tbout >= $trout) {
		$tt = $trout->diff($trin);
	} else if ($tbin <= $trin && $tbout <= $trout) {
		$tt = $tbout->diff($trin);
	} else if ($tbin >= $trin && $tbout <= $trout) {
		$tt = $tbout->diff($tbin);
	} else if ($tbin >= $trin && $tbout >= $trout && $tbin < $trout) {
		$tt = $trout->diff($tbin);
	} else {
		$tt = $tbin->diff($tbin);
	}
	return date('H:i', strtotime($tt->format('%H:%i:%s')));
}
function check_itime_diffrence($str1, $str2)
{
	if ($str1 > $str2) {
		return '00:00:00';
	} else {
		$iTime_in = new DateTime($str1);
		$iTime_out = new DateTime($str2);
		$interval = $iTime_in->diff($iTime_out);
		return date('H:i:s', strtotime($interval->format('%H') . ':' . $interval->format('%i') . ':' . $interval->format('%s')));
	}
}
function Calculaion_Biometric($empid, $date)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	// $sql_bioinout = "select InTime, DateOn, OutTime from bioinout where EmpID = '" . $empid . "' and (DateOn ='" . $date . "' or DateOn ='" . date('Y-m-d', strtotime('+1 days ' . $date)) . "')";

	$sql_roster = "select InTime, DateOn, OutTime, type_ from roster_temp where EmployeeID = ? and DateOn = ? limit 1";
	$selectQury = $conn->prepare($sql_roster);
	$selectQury->bind_param("ss", $empid, $date);
	$selectQury->execute();
	$result = $selectQury->get_result();
	$result_roster = $result->fetch_row();
	//$result_bioinout = $myDB->query($sql_bioinout);

	// $result_roster = $myDB->query($sql_roster);


	if ($result->num_rows > 0 /*&& count($result_bioinout) && $result_bioinout*/ && $result) {
		$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID=? and DateOn between cast("' . $date . '" as date) and cast("' . date('Y-m-d', (strtotime($date . ' +1 days'))) . '" as date) order by DateOn,str_to_date(PunchTime,"%k:%i:%s")';
		$select = $conn->prepare($str_capping);
		$select->bind_param("s", $empid);
		$select->execute();
		$result = $select->get_result();
		$ds_punchtime = $result->fetch_row();
		$str_capping = "";

		$Shift1 = explode("|", $result_roster[0]);
		$Shift2 = explode("|", $result_roster[2]);

		if ($result->num_rows > 0 && $result) {
			$InTime = $Shift1[0];
			if (isset($Shift1[1])) {
				$OutTime = $Shift1[1];
			}

			$inflag = 0;

			$i_rosterIN = get_timestring($date, $InTime);
			$i_rosterOUT = get_timestring($date, $OutTime);

			$i_bioIN = '';
			$i_bioOUT = '';
			//	for ($i = 0; $i < count($ds_punchtime); $i++) {
			while ($ds_punchtime = mysqli_fetch_assoc($result)) {
				$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
				$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
				$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '- 2 hours'));
				$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+ 2 hours'));

				$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '- 2 hours'));
				$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+ 2 hours'));

				$punchTime =  date('Y-m-d H:i:s', strtotime($ds_punchtime['DateOn'] . ' ' . $ds_punchtime['PunchTime']));

				$cappingTime = date('Y-m-d H:i:s', (strtotime($date . " " . '03:00:00' . ' +1 days')));
				if (!empty($punchTime)) {
					if ($inflag == 0) {
						if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P) {
							$i_bioIN = $punchTime;
							$inflag = 1;
						} elseif ($punchTime > $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P) {
							$i_bioOUT = $punchTime;
						}
					} else {
						if ($punchTime >= $rosterOut_Capping && $punchTime <= $rosterOut_Capping_P) {
							$i_bioOUT = $punchTime;
						}
					}
				}
			}

			if (check_itime_diffrence($i_bioIN, $i_bioOUT) <= "01:00:00") {
				return true;
			}
			if (empty($i_bioIN) && empty($i_bioOUT)) {
				return true;
			} elseif (empty($i_bioIN) || empty($i_bioOUT)) {
				return true;
			} else {
				$time = get_inshift_time($rosterIN_Capping, $rosterOut_Capping_P, $i_bioIN, $i_bioOUT, $result_roster[3]);
				if ($result_roster[3] == 1 && $time < '03:00') {
					return true;
				} elseif ($result_roster[3] == 2 && $time < '03:00') {
					return true;
				} elseif ($result_roster[3] == 3 && $time < '03:00') {
					return true;
				}
				return false;
			}
		} else {
			return true;
		}
	} else {
		return true;
	}
}
function Calculaion_Biometric1($empid, $date)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	// $sql_bioinout = "select InTime, DateOn, OutTime from bioinout where EmpID = '" . $empid . "' and (DateOn ='" . $date . "' or DateOn ='" . date('Y-m-d', strtotime('+1 days ' . $date)) . "')";

	$sql_roster = "select InTime, DateOn, OutTime, type_ from roster_temp where EmployeeID = ? and DateOn = ? limit 1";
	$selectQury = $conn->prepare($sql_roster);
	$selectQury->bind_param("ss", $empid, $date);
	$selectQury->execute();
	$result = $selectQury->get_result();
	$result_roster = $result->fetch_row();

	if ($result->num_rows > 0) {
		$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID=? and DateOn between cast("' . $date . '" as date) and cast("' . date('Y-m-d', (strtotime($date . ' +1 days'))) . '" as date) order by DateOn,str_to_date(PunchTime,"%k:%i:%s")';
		$select = $conn->prepare($str_capping);
		$select->bind_param("s", $empid);
		$select->execute();
		$result = $select->get_result();
		$ds_punchtime = $result->fetch_row();

		$str_capping = "";
		$Shift1 = explode("|", $result_roster[0]);
		$Shift2 = explode("|", $result_roster[2]);

		if ($result->num_rows > 0) {
			$InTime = $Shift2[0];
			if (isset($Shift2[1])) {
				$OutTime = $Shift2[1];
			}

			$inflag = 0;

			$i_rosterIN = get_timestring($date, $InTime);
			$i_rosterOUT = get_timestring($date, $OutTime);

			$i_bioIN = '';
			$i_bioOUT = '';
			//for ($i = 0; $i < count($ds_punchtime); $i++) {
			while ($ds_punchtime = mysqli_fetch_assoc($result)) {
				$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
				$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
				$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '- 2 hours'));
				$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+ 2 hours'));

				$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '- 2 hours'));
				$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+ 2 hours'));

				$punchTime =  date('Y-m-d H:i:s', strtotime($ds_punchtime['DateOn'] . ' ' . $ds_punchtime['PunchTime']));

				$cappingTime = date('Y-m-d H:i:s', (strtotime($date . " " . '03:00:00' . ' +1 days')));

				if (!empty($punchTime)) {
					if ($inflag == 0) {
						if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P) {
							$i_bioIN = $punchTime;
							$inflag = 1;
						} elseif ($punchTime > $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P) {
							$i_bioOUT = $punchTime;
						}
					} else {
						if ($punchTime >= $rosterOut_Capping && $punchTime <= $rosterOut_Capping_P) {
							$i_bioOUT = $punchTime;
						}
					}
				}
			}

			if (check_itime_diffrence($i_bioIN, $i_bioOUT) <= "01:00:00") {
				return true;
			}
			if (empty($i_bioIN) && empty($i_bioOUT)) {
				return true;
			} elseif (empty($i_bioIN) || empty($i_bioOUT)) {
				return true;
			} else {
				$time = get_inshift_time($rosterIN_Capping, $rosterOut_Capping_P, $i_bioIN, $i_bioOUT, $result_roster[3]);
				if ($result_roster[3] == 1 && $time < '03:00') {
					return true;
				} elseif ($result_roster[3] == 2 && $time < '03:00') {
					return true;
				} elseif ($result_roster[3] == 3 && $time < '03:00') {
					return true;
				}
				return false;
			}
		} else {
			return true;
		}
	} else {
		return true;
	}
}
function get_apr_ATND($travelTime, $roster_type)
{
	$v = explode(":", $travelTime);
	$v1 = (int)$v[0];


	if ($v1 > 24) {
		$travelTime = "08:00";
	}

	if (strtotime($travelTime)) {
		if ($roster_type == 2) {

			if (strtotime($travelTime) >= strtotime("09:45")) {
				$LoginAtt = "P";
			} else if (strtotime($travelTime) < strtotime("09:45") && strtotime($travelTime) >= strtotime("05:00")) {
				$LoginAtt = "H";
			} else {
				$LoginAtt = "A";
			}
		} else if ($roster_type == 1) {
			if (strtotime($travelTime) >= strtotime("8:00")) {
				$LoginAtt = "P";
			} else if (strtotime($travelTime) < strtotime("8:00") && strtotime($travelTime) >= strtotime("4:30")) {
				$LoginAtt = "H";
			} else {
				$LoginAtt = "A";
			}
		} else if ($roster_type == 3) {
			if (strtotime($travelTime) >= strtotime("4:30")) {
				$LoginAtt = "P";
			} else {
				$LoginAtt = "A";
			}
		} else if ($roster_type == 4) {
			if (strtotime($travelTime) >= strtotime("8:00")) {
				$LoginAtt = "P";
			} else if (strtotime($travelTime) < strtotime("8:00") && strtotime($travelTime) >= strtotime("4:30")) {
				$LoginAtt = "H";
			} else {
				$LoginAtt = "A";
			}
		}
	} else {
		$LoginAtt = "A";
	}

	return $LoginAtt;
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
function check_validate($Exception, $EmployeeID, $DateFrom, $DateTo, $hd_check)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	if ($_POST['txtSupervisorApproval'] == 'Decline' || $_POST['txtSiteHeadApproval'] == 'Decline') {
		return array(0 => 0, 1 => '');
	}

	if ($Exception == 'Roster Change' || $Exception == 'Shift Change' || $Exception == 'Biometric issue') {

		$data_count_WO = $myDB->query("select count(*) as `count` from roster_temp where EmployeeID = '" . $EmployeeID . "' and DateOn between '" . $DateFrom . "' and '" . $DateTo . "' and (InTime like '%WO%' or OutTime like '%WO%')");
		$myError = $myDB->getLastError();
		if (count($data_count_WO) > 0 && $data_count_WO) {
			$data_count_WO = $data_count_WO[0]['count'];
			if ($data_count_WO > 0) {
				return array(0 => 1, 1 => 'Request Not Submitted : found rostered <b>weekoff (WO)</b> between selected dates.');
			}
		} else {
			return array(0 => 1, 1 => 'Request Not Submitted : Something went wrong ' . $myError . '.');
		}
	}

	$query = 'SELECT count(*) `Count` FROM exception where EmployeeID =? and Exception = ? and date_format(DateFrom,"%Y-%m") = "' . date('Y-m', strtotime($DateFrom)) . '" and MgrStatus != "Decline" and HeadStatus !="Decline";';
	$select = $conn->prepare($query);
	$select->bind_param("ss", $EmployeeID, $Exception);
	$select->execute();
	$check_count = $select->get_result();
	$check_counts = $check_count->fetch_row();
	if ($check_count->num_rows > 0 && $check_count && $hd_check) {
		$exc_count = $check_counts[0];
		$txt_Request = cleanUserInput($_POST['txt_Request']);
		if (($txt_Request == "Shift Change") && $exc_count >= 2) {
			return array(0 => 1, 1 => 'Request Not Submitted : The ' . $txt_Request . ' request count exceeded the maximum limit.');
		} else if ($txt_Request == "Back Dated Leave" && $exc_count >= 1) {
			return array(0 => 1, 1 => 'Request Not Submitted : The ' . $txt_Request . ' request count exceeded the maximum limit.');
		} else if (($txt_Request == "Working on Holiday" || $txt_Request == "Working on WeekOff") && $exc_count >= 2) {
			return array(0 => 1, 1 => 'Request Not Submitted : The ' . $txt_Request . ' request count exceeded the maximum limit.');
		}
	}



	$select_subprocess = "select sub_process from whole_dump_emp_data where EmployeeID = ?";
	$selectQury = $conn->prepare($select_subprocess);
	$selectQury->bind_param("s", $EmployeeID);
	$selectQury->execute();
	$get_subProcess = $selectQury->get_result();
	$sub_processes = $get_subProcess->fetch_row();
	$sub_process = $sub_processes[0];

	$txtRequest = cleanUserInput($_POST['txt_Request']);
	if ($txtRequest == "Roster Change" || $txtRequest == "Shift Change") {
		$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
		$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
		$ShiftIn2 = cleanUserInput($_POST['txt_ShiftIn2']);
		$ShiftOut2 = cleanUserInput($_POST['txt_ShiftOut2']);
		$IssueType = "NA";
		$CurrAtt = "NA";
		$UpdateAtt = "NA";
		$LeaveType = "NA";


		$Query = 'select InTime,OutTime from roster_temp where EmployeeID = ? and DateOn =? order by id desc limit 1';
		$selectQ = $conn->prepare($Query);
		$selectQ->bind_param("ss", $EmployeeID, $DateFrom);
		$selectQ->execute();
		$rst_shift = $selectQ->get_result();
		$shift = $rst_shift->fetch_row();
		if ($rst_shift->num_rows > 0 && $rst_shift) {
			$shift1 = $shift[0];
			$shift1 = explode('|', $shift1);
			$InTime  = $shift1[0];
			if (isset($shift1[1])) {
				$OutTime  = $shift1[1];
			}
			if (strtotime($InTime)) {
				$shift_temp_old = $DateFrom . ' ' . $InTime;
				$shift_temp_current = $DateFrom . ' ' . $ShiftIn;


				$gender = 'select gender from personal_details where EmployeeID= ?';
				$selectQury = $conn->prepare($gender);
				$selectQury->bind_param("s", $EmployeeID);
				$selectQury->execute();
				$res = $selectQury->get_result();
				$rst_gender = $res->fetch_row();

				if (strtoupper($rst_gender[0]) == 'FEMALE') {
					$roster_old = date('Y-m-d H:i:s', (strtotime($shift_temp_old)));
					$roster_cur = date('Y-m-d H:i:s', (strtotime($shift_temp_current)));
					$roster_out = date('Y-m-d H:i:s', (strtotime($DateFrom . ' ' . cleanUserInput($_POST['txt_ShiftOut']))));
					$roster_out_lim = date('Y-m-d H:i:s', (strtotime($DateFrom . ' 19:00')));
					$roster_diffrence = '';
					$roster_limit1 = date('Y-m-d H:i:s', strtotime($roster_old . '-30 minutes'));
					$roster_limit2 = date('Y-m-d H:i:s', strtotime($roster_old . '+30 minutes'));
					$roster_diffrence = "30 Minutes";

					if (($roster_cur > $roster_limit1 &&  $roster_cur < $roster_limit2) || $roster_out > $roster_out_lim || $roster_out < $roster_cur) {
						return array(0 => 1, 1 => "Request Not Submitted :Roster selection should be greater then " . $roster_diffrence . " from current shift.");
					}
				} else {
					$roster_old = date('Y-m-d H:i:s', (strtotime($shift_temp_old)));
					$roster_cur = date('Y-m-d H:i:s', (strtotime($shift_temp_current)));
					$roster_diffrence = '';

					$roster_limit1 = date('Y-m-d H:i:s', strtotime($roster_old . '-30 minutes'));
					$roster_limit2 = date('Y-m-d H:i:s', strtotime($roster_old . '+30 minutes'));
					$roster_diffrence = "30 Minutes";

					if ($roster_cur > $roster_limit1 &&  $roster_cur < $roster_limit2) {
						return array(0 => 1, 1 => 'Request Not Submitted :Roster selection should be greater then <b>' . $roster_diffrence . '</b> from current shift.');
					}
				}
			}
			$shift1 = $shift[1];
			$shift1 = explode('|', $shift1);
			$InTime  = $shift1[0];
			if (isset($shift1[1])) {
				$OutTime  = $shift1[1];
			}
			if (strtotime($InTime)) {
				$shift_temp_old = $DateFrom . ' ' . $InTime;
				$shift_temp_current = $DateFrom . ' ' . $ShiftIn2;


				$gender = 'select gender from personal_details where EmployeeID=?';
				$selectQury = $conn->prepare($gender);
				$selectQury->bind_param("s", $EmployeeID);
				$selectQury->execute();
				$res = $selectQury->get_result();
				$rst_gender = $res->fetch_row();

				if (strtoupper($rst_gender[0]) == 'FEMALE') {
					$roster_old = date('Y-m-d H:i:s', (strtotime($shift_temp_old)));
					$roster_cur = date('Y-m-d H:i:s', (strtotime($shift_temp_current)));
					$roster_out = date('Y-m-d H:i:s', (strtotime($DateFrom . ' ' . cleanUserInput($_POST['txt_ShiftOut2']))));
					$roster_out_lim = date('Y-m-d H:i:s', (strtotime($DateFrom . ' 19:00')));
					$roster_diffrence = '';

					$roster_limit1 = date('Y-m-d H:i:s', strtotime($roster_old . '-30 minutes'));
					$roster_limit2 = date('Y-m-d H:i:s', strtotime($roster_old . '+30 minutes'));
					$roster_diffrence = "30 Minutes";

					if (($roster_cur > $roster_limit1 &&  $roster_cur < $roster_limit2) || $roster_out > $roster_out_lim || $roster_out < $roster_cur) {
						return array(0 => 1, 1 => "Request Not Submitted :Roster selection should be greater then " . $roster_diffrence . " from current shift.");
					}
				} else {
					$roster_old = date('Y-m-d H:i:s', (strtotime($shift_temp_old)));
					$roster_cur = date('Y-m-d H:i:s', (strtotime($shift_temp_current)));
					$roster_diffrence = '';

					$roster_limit1 = date('Y-m-d H:i:s', strtotime($roster_old . '-30 minutes'));
					$roster_limit2 = date('Y-m-d H:i:s', strtotime($roster_old . '+30 minutes'));
					$roster_diffrence = "30 Minutes";

					if ($roster_cur > $roster_limit1 &&  $roster_cur < $roster_limit2) {

						return array(0 => 1, 1 => 'Request Not Submitted :Roster selection should be greater then <b>' . $roster_diffrence . '</b> from current shift.');
					}
				}
			}
			$shift1 = $shift[0];
			$shift1 = explode('|', $shift1);
			$InTime1  = $shift1[0];
			//$OutTime1  = $shift1[1];
			$OutTime1 = "";
			if (isset($shift1[1])) {
				$OutTime1  = $shift1[1];
			}
			$shift2 = $shift[1];
			$shift2 = explode('|', $shift2);
			$InTime2  = $shift2[0];
			$OutTime2 = "";
			if (isset($shift2[1])) {
				$OutTime2  = $shift2[1];
			}

			if (strtotime($InTime1) && strtotime($InTime2)) {
				if ($InTime1 == $InTime2 || $OutTime1 == $OutTime2) {
					return array(0 => 1, 1 => 'Request Not Submitted :Current shift not a valid time value to change. First Shift time should not be equal to second shift.');
				} else {

					$shiftTime1 =  $DateFrom . ' ' . $InTime1;
					$shiftTime12 =  $DateFrom . ' ' . $OutTime1;
					$shiftTime2 =  $DateFrom . ' ' . $InTime2;
					$shiftTime22 =  $DateFrom . ' ' . $OutTime2;
					if (strtotime($shiftTime1) >= strtotime($shiftTime2) || strtotime($shiftTime12) >= strtotime($shiftTime2)) {
						return array(0 => 1, 1 => 'Request Not Submitted :Current shift not a valid time value to change.');
					} else {
						$date1 = new DateTime($shiftTime12);
						$date2 = new DateTime($shiftTime2);
						$interval = $date1->diff($date2);
						if ($interval->h < 4) {
							return array(0 => 1, 1 => 'Request Not Submitted :Current shift not a valid time value to change. First and second shift difference should be 4 hour.');
						}
					}
				}
			} else {
				return array(0 => 1, 1 => 'Request Not Submitted :Current shift not a valid time value to change. May be it\'s a <i>`WO`</i> or <i>`HO`</i> which couldn\'t be change to a time stamp.');
			}
		} else {
			return array(0 => 1, 1 => 'Request Not Submitted :No shift value found to change.');
		}
	} else if ($txtRequest == "Biometric issue") {
		$APR = array();
		$ShiftIn = "NA";
		$ShiftOut = "NA";
		$LeaveType = "NA";
		$IssueType = 'Biomertic Issue';
		$CurrAtt = cleanUserInput($_POST['txt_curatnd']);
		$UpdateAtt = cleanUserInput($_POST['txt_updateatnd']);
		if (!Calculaion_Biometric($EmployeeID, $DateFrom) && !Calculaion_Biometric1($EmployeeID, $DateFrom)) {
			return array(0 => 1, 1 => 'Request Not Submitted :Biometric hour are according to shift.');
		}

		$check_request = $UpdateAtt;

		$des = 'select designation from whole_dump_emp_data where EmployeeID =?';
		$sel = $conn->prepare($des);
		$sel->bind_param("s", $EmployeeID);
		$sel->execute();
		$Emd_des = $sel->get_result();
		$Emd_des = $Emd_des[0];

		if ($Emd_des == "CSE" || $Emd_des == "C.S.E." || $Emd_des == "Sr. C.S.E" || $Emd_des == "C.S.E" || $Emd_des == "Senior Customer Care Executive" || $Emd_des == "Customer Care Executive" || $Emd_des == "CSA" || $Emd_des == "Senior CSA") {


			$downtime = 'select sum(time_to_sec(TotalDT)) sec,LoginDate from downtime where EmpID =? and FAStatus ="Approve" and RTStatus ="Approve" and LoginDate between ? and ? group by LoginDate';
			$select = $conn->prepare($downtime);
			$select->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
			$select->execute();
			$ds_downtime = $select->get_result();
			$DTHour = array();
			if ($ds_downtime->num_rows > 0 && $ds_downtime) {

				foreach ($ds_downtime as $key => $val) {
					$date_for = $val['LoginDate'];
					//$minute = intval(($val[0]['sec'] % 3600) / 60);
					$minute = intval(($val['sec'] % 3600) / 60);
					if ($minute <= 9) {
						$minute = '0' . $minute;
					}
					//$DTHour[$date_for] = intval($val[0]['sec']/3600).':'.$minute;
					$DTHour[$date_for] = intval($val['sec'] / 3600) . ':' . $minute;
					unset($date_for);
				}

				unset($ds_downtime);
			}
			if (date('Y-m', strtotime($DateFrom)) == date('Y-m', strtotime($DateTo))) {
				$h_month = date('m', strtotime($DateTo));
				$h_year = date('Y', strtotime($DateTo));
				$date_range = getDatesFromRange($DateFrom, $DateTo);
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);
				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month =? and year = ? order by id desc limit 1";
				$selectq = $conn->prepare($strsql);
				$selectq->bind_param("sss", $EmployeeID, $h_month, $h_year);
				$selectq->execute();
				$dshr = $selectq->get_result();
				if ($dshr->num_rows > 0) {
					foreach ($dshr as $key => $val) {
						foreach ($val as $keys => $value) {
							$keyDate = substr($keys, 1, strlen($keys));

							if ($keyDate < 10) {
								$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
							} else {
								$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
							}
							if (isset($DTHour[$date_for])) {

								if ($value == '-' || $value == '' || $value == null) {
									$APR[$date_for] = $DTHour[$date_for];
								} else {
									$v1 = explode(':', $value);
									$t1 = explode(':', $DTHour[$date_for]);
									$dataTime1 = $v1[0] + $t1[0];
									$dataTime2 = $v1[1] + $t1[1];
									if ($dataTime2 >= 60) {
										$dataTime1 = $dataTime1 + intval($dataTime2 / 60);
										$dataTime2 = ($dataTime2 % 60);
									}
									if (intval($dataTime2) <= 9) {
										$dataTime2 = '0' . $dataTime2;
									}
									$APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}
			} elseif (date('Y-m', strtotime($DateFrom)) != date('Y-m', strtotime($DateTo))) {


				$date_range = getDatesFromRange($DateFrom, date('Y-m-t', strtotime($DateFrom)));
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);
				$h_month = date('m', strtotime($DateFrom));
				$h_year = date('Y', strtotime($DateFrom));

				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month =? and year = ? order by id desc limit 1";
				$selectq = $conn->prepare($strsql);
				$selectq->bind_param("sss", $EmployeeID, $h_month, $h_year);
				$selectq->execute();
				$dshr = $selectq->get_result();
				if ($dshr->num_rows > 0) {
					foreach ($dshr as $key => $val) {
						foreach ($val as $keys => $value) {
							$keyDate = substr($keys, 1, strlen($keys));

							if ($keyDate < 10) {
								$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
							} else {
								$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
							}
							if (isset($DTHour[$date_for])) {

								if ($value == '-' || $value == '' || $value == null) {
									$APR[$date_for] = $DTHour[$date_for];
								} else {
									$v1 = explode(':', $value);
									$t1 = explode(':', $DTHour[$date_for]);
									$dataTime1 = $v1[0] + $t1[0];
									$dataTime2 = $v1[1] + $t1[1];
									if ($dataTime2 >= 60) {
										$dataTime1 = $dataTime1 + intval($dataTime2 / 60);
										$dataTime2 = ($dataTime2 % 60);
									}
									if (intval($dataTime2) <= 9) {
										$dataTime2 = '0' . $dataTime2;
									}
									$APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}

				$date_range = getDatesFromRange(date('Y-m-01', strtotime($DateTo)), $DateTo);
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);

				$h_month = date('m', strtotime($DateTo));
				$h_year = date('Y', strtotime($DateTo));

				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month =? and year = ? order by id desc limit 1";
				$selectq = $conn->prepare($strsql);
				$selectq->bind_param("sss", $EmployeeID, $h_month, $h_year);
				$selectq->execute();
				$dshr = $selectq->get_result();
				if ($dshr->num_rows > 0) {
					foreach ($dshr as $key => $val) {
						foreach ($val as $keys => $value) {
							$keyDate = substr($keys, 1, strlen($keys));

							if ($keyDate < 10) {
								$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
							} else {
								$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
							}
							if (isset($DTHour[$date_for])) {

								if ($value == '-' || $value == '' || $value == null) {
									$APR[$date_for] = $DTHour[$date_for];
								} else {
									$v1 = explode(':', $value);
									$t1 = explode(':', $DTHour[$date_for]);
									$dataTime1 = $v1[0] + $t1[0];
									$dataTime2 = $v1[1] + $t1[1];
									if ($dataTime2 >= 60) {
										$dataTime1 = $dataTime1 + intval($dataTime2 / 60);
										$dataTime2 = ($dataTime2 % 60);
									}
									if (intval($dataTime2) <= 9) {
										$dataTime2 = '0' . $dataTime2;
									}
									$APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}
			}


			$sql = 'select type_ from roster_temp where EmployeeID =? and DateOn =? order by id desc limit 1';
			$selectqury = $conn->prepare($sql);
			$selectqury->bind_param("ss", $EmployeeID, $DateFrom);
			$selectqury->execute();
			$res = $selectqury->get_result();
			$rst_shift = $res->fetch_row();
			if (empty($rst_shift[0])) {
				$rst_shift[0] = 1;
			}
			$nonAPR_Employee_status = 0;

			$non_emp = 'select EmployeeID from nonapr_employee  where EmployeeID=? and flag=0';
			$selects = $conn->prepare($non_emp);
			$selects->bind_param("s", $EmployeeID);
			$selects->execute();
			$resu = $selects->get_result();
			$nonAPR_emp = $resu->fetch_row();
			if (!empty($nonAPR_emp[0])) {
				$nonAPR_Employee_status = 1;
			}
			$ModuleChange_date = '';
			$onFloorDate = '';
			$status = 1;

			$sel = 'select InQAOJT,OnFloor,Status,mapped_date from status_table where EmployeeID=?';
			$selectSql = $conn->prepare($sel);
			$selectSql->bind_param("s", $EmployeeID);
			$selectSql->execute();
			$result = $selectSql->get_result();
			$ModuleChange = $result->fetch_row();
			if ($result->num_rows > 0 && $result) {
				$ModuleChange_date = $ModuleChange[3];
				$onFloorDate = $ModuleChange[0];
				$status = $ModuleChange[2];
				if (!strtotime($ModuleChange_date)) {
					$ModuleChange_date = NULL;
				}
				if (!strtotime($onFloorDate)) {
					$onFloorDate = NULL;
				}
			}

			$hour_attr = '00:00';

			if (($status < 5  &&  $DateFrom >= date('Y-m-d', strtotime($ModuleChange_date))) || $nonAPR_Employee_status == 1) {
				$hour_attr = "08:10";
			} else {
				if (!empty($onFloorDate) && !empty($ModuleChange_date) &&   $DateFrom >= date('Y-m-d', strtotime($ModuleChange_date)) && $DateFrom < date('Y-m-d', strtotime($onFloorDate)) && strtotime($ModuleChange_date) && strtotime($onFloorDate)) {

					$hour_attr = '08:10';
				} else {
					$hour_attr = $APR[$DateFrom];
				}
			}

			$getAPRATND = get_apr_ATND($hour_attr, $rst_shift[0]);
			if ($getAPRATND != $check_request) {
				$hour = (empty($hour_attr) ? '00:00' : $hour_attr);
				return array(0 => 1, 1 => 'Request Not Submitted : Wrong ' . $txtRequest . ' request. System found <b>' . $hour . ' Hours APR</b> for requested date. </b>.');
			}
		}
	} else if ($txtRequest == "Working on Holiday" || $txtRequest == "Working on WeekOff" || $txtRequest == "Working on Leave") {
		$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
		$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
		$LeaveType = "NA";
		$IssueType = "NA";
		$CurrAtt = "NA";
		$UpdateAtt = "P";

		if ($txtRequest == "Working on WeekOff") {

			$Qur = 'select Status  from status_table where EmployeeID  = ?';
			$selectQuey = $conn->prepare($Qur);
			$selectQuey->bind_param("s", $EmployeeID);
			$selectQuey->execute();
			$resul = $selectQuey->get_result();
			$check_status = $resul->fetch_row();
			$check_status = $check_status[0];
			if ($check_status == 5) {
				return array(0 => 1, 1 => 'Request Not Submitted : System not have permission to accept <b>Working on Week-Off Request </b> for <b>In-OJT</b> Employees.');
			}
			$validate = 0;
			$count_wpf = 0;


			$Query = 'select count(*) counts  from rosterpref where month(WF) =  ' . intval(date('m', strtotime($DateFrom))) . ' and year(WF) =  ' . intval(date('Y', strtotime($DateFrom))) . ' and FirstPre = "No Weekoff Required" and EmpID = ?';
			$selectQu = $conn->prepare($Query);
			$selectQu->bind_param("s", $EmployeeID);
			$selectQu->execute();
			$results = $selectQu->get_result();
			$rstl = $results->fetch_row();
			if ($results->num_rows > 0 && $results) {
				$count_wpf = intval($rstl[0]);
			}


			$Query = 'select count(*) counts  from exception where month(DateFrom) =  ' . intval(date('m', strtotime($DateFrom))) . ' and year(DateFrom) =  ' . intval(date('Y', strtotime($DateFrom))) . ' and Exception = "Working on Weekoff" and EmployeeID = ? and  "Decline"  not in(MgrStatus, HeadStatus)';
			$selectQu = $conn->prepare($Query);
			$selectQu->bind_param("s", $EmployeeID);
			$selectQu->execute();
			$results = $selectQu->get_result();
			$rstl = $results->fetch_row();
			if ($results->num_rows > 0 && $results) {
				$count_wpf += intval($rstl[0]);
			}

			if ($count_wpf >= 2 && $hd_check) {
				return array(0 => 1, 1 => 'Request Not Submitted : You already cross the limit for <b>Working on Week-Off Request </b>.');
			}
			if ($count_wpf > 2 && !$hd_check) {
				return array(0 => 1, 1 => 'Request Not Submitted : You already cross the limit for <b>Working on Week-Off Request </b>.');
			}
		}
	}
	return array(0 => 0, 1 => '');
}

$btnSave = 'hidden';
$btnAdd = '';
$AccountHeadName = $AccountHead = '';
$SiteHead = 'CE03070003';
$SiteHeadName = 'SACHIN SIWACH';
$alert_msg = '';

$readonly_ah = ' readonly="true" ';
$readonly_sh = ' readonly="true" ';

$txtsrchDateFrom = isset($_POST['txt_srch_DateFrom']);
$txtsrchDateFrom2 = cleanUserInput($_POST['txt_srch_DateFrom']);
$txtsrchDateTo = isset($_POST['txt_srch_DateTo']);
$txtsrchDateTo2 = cleanUserInput($_POST['txt_srch_DateTo']);
($txtsrchDateFrom) ? $date_srch_from = $txtsrchDateFrom2 : $date_srch_from = date('Y-m-01');
($txtsrchDateTo) ? $date_srch_to = $txtsrchDateTo2 : $date_srch_to = date('Y-m-t');


$str = "select account_head, EmployeeName from new_client_master inner join personal_details on EmployeeID = account_head where cm_id =( select cm_id from employee_map where EmployeeID=?)";
$selectQury = $conn->prepare($str);
$selectQury->bind_param("s", $EmplID);
$selectQury->execute();
$result = $selectQury->get_result();


if ($result->num_rows > 0) {
	foreach ($result as $key => $value) {
		$AccountHead = $value['account_head'];
		$AccountHeadName = $value['EmployeeName'];
	}
}

$str = "select ReportTo,personal_details.EmployeeName from employee_map inner join new_client_master on new_client_master.cm_id = employee_map.cm_id inner join status_table on employee_map.EmployeeID = status_table.EmployeeID left outer join personal_details on personal_details.EmployeeID = status_table.ReportTo where employee_map.EmployeeID = ? and new_client_master.account_head = ? ";
$selectQ = $conn->prepare($str);
$selectQ->bind_param("ss", $EmplID, $EmplID);
$selectQ->execute();
$result = $selectQ->get_result();

// if (empty($error)) {
if ($result->num_rows > 0) {
	foreach ($result as $key => $value) {
		$AccountHead = $value['ReportTo'];
		$AccountHeadName = $value['EmployeeName'];
	}
}
// }


if (clean($_SESSION['__user_logid']) == $AccountHead) {
	$readonly_ah = '';
}

if (clean($_SESSION['__user_logid']) == $SiteHead) {
	$readonly_sh = '';
}
$btnLeaveAdd = isset($_POST['btn_Leave_Add']);
$txtRequest = cleanUserInput($_POST['txt_Request']);
if ($btnLeaveAdd) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = clean($_SESSION['__user_logid']);
		$Name = clean($_SESSION['__user_Name']);
		$Exception = $txtRequest;
		$EmployeeComment = cleanUserInput($_POST['txt_Comment']);
		$DateFrom = cleanUserInput($_POST['txt_DateFrom']);
		$DateTo = cleanUserInput($_POST['txt_DateTo']);
		$MngrStatusID = "Pending";
		$HeadStatusID = "Pending";
		$validation = 0;
		$validate = 0;
		if ($txtRequest == "Roster Change" || $txtRequest == "Shift Change") {
			$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
			$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
			$ShiftIn2 = cleanUserInput($_POST['txt_ShiftIn2']);
			$ShiftOut2 = cleanUserInput($_POST['txt_ShiftOut2']);
			$IssueType = "NA";
			$CurrAtt = "NA";
			$UpdateAtt = "NA";
			$LeaveType = "NA";
		} else if ($txtRequest == "Biometric issue") {
			$ShiftIn = cleanUserInput($_POST['txt_access_in']);
			$ShiftOut = cleanUserInput($_POST['txt_access_out']);
			$ShiftIn2 = "NA";
			$ShiftOut2 = "NA";
			$LeaveType = cleanUserInput($_POST['txt_descheck']);
			$IssueType = 'Biomertic Issue';
			$CurrAtt = cleanUserInput($_POST['txt_curatnd']);
			$UpdateAtt = cleanUserInput($_POST['txt_updateatnd']);
			if (!Calculaion_Biometric($EmployeeID, $DateFrom) || !Calculaion_Biometric1($EmployeeID, $DateFrom)) {
				$validation = 1;
			}
		} else if ($txtRequest == "Back Dated Leave") {
			$ShiftIn = "NA";
			$ShiftOut = "NA";
			$ShiftIn2 = "NA";
			$ShiftOut2 = "NA";
			$LeaveType = cleanUserInput($_POST['txt_LeaveType']);
			$IssueType = "NA";
			$CurrAtt = "NA";
			$UpdateAtt = "NA";
		} else if ($txtRequest == "Working on Holiday" || $txtRequest == "Working on WeekOff" || $txtRequest == "Working on Leave") {
			$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
			$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
			$ShiftIn2 = cleanUserInput($_POST['txt_ShiftIn2']);
			$ShiftOut2 = cleanUserInput($_POST['txt_ShiftOut2']);
			$LeaveType = "NA";
			$IssueType = "NA";
			$CurrAtt = "NA";
			$UpdateAtt = "P";

			if ($txtRequest == "Working on WeekOff") {
				$validate = 0;
				$count_wpf = 0;
				$month = intval(date('m', strtotime($DateFrom)));
				$year = intval(date('Y', strtotime($DateFrom)));
				$query = 'select count(*) counts  from rosterpref where month(WF) =  ? and year(WF) =  ? and FirstPre = "No Weekoff Required" and EmpID = ?';
				$select = $conn->prepare($query);
				$select->bind_param("sss", $month, $year, $EmplID);
				$select->execute();
				$res = $select->get_result();
				$rstl = $res->fetch_row();
				if ($res->num_rows > 0 && $res) {
					$count_wpf = intval($rstl[0]);
				}

				$query = 'select count(*) counts  from exception where month(DateFrom) =  ? and year(DateFrom) =  ? and Exception = "Working on Weekoff" and EmployeeID = ? and MgrStatus != "Decline"';
				$select = $conn->prepare($query);
				$select->bind_param("sss", $month, $year, $EmplID);
				$select->execute();
				$res = $select->get_result();
				$rstl = $res->fetch_row();
				if ($res->num_rows > 0 && $res) {
					$count_wpf += intval($rstl[0]);
				}
				if ($count_wpf >= 2) {
					$validate = 1;
				}
			}
		} else {
			$ShiftIn = "NA";
			$ShiftOut = "NA";
			$ShiftIn2 = "NA";
			$ShiftOut2 = "NA";
			$IssueType = "NA";
			$CurrAtt = "NA";
			$UpdateAtt = "NA";
			$LeaveType = "NA";
		}

		$validate_array = check_validate($Exception, $EmployeeID, $DateFrom, $DateTo, true);
		$validate = $validate_array[0];


		if ($validation === 0 && $validate === 0) {
			if ($ShiftIn != "NA") {
				$ShiftIn = $ShiftIn . "|" .  $ShiftOut;
				$ShiftOut = $ShiftIn2 . "|" .  $ShiftOut2;
			}

			$sqlInsertException = 'call sp_InsertException_split("' . $EmployeeID . '","' . $Name . '","' . $Exception . '","' . $EmployeeComment . '","' . $DateFrom . '","' . $DateTo . '","' . $MngrStatusID . '","' . $HeadStatusID . '","' . $IssueType . '","' . $CurrAtt . '","' . $UpdateAtt . '","' . $ShiftIn . '","' . $ShiftOut . '","' . $LeaveType . '")';
			$flag = $myDB->rawQuery($sqlInsertException);
			$error = $myDB->getLastError();
			if (empty($mysql_error)) {
				$alert_msg = 'Request Submitted and Sent To <b>' . cleanUserInput($_POST['txtApprovedBy']) . '</b>';
				echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
			} else {
				$alert_msg = 'Request Not Submitted ' . $error;
				echo "<script>$(function(){ toastr.error('" . $alert_msg . "') }); </script>";
			}
		} else {
			$alert_msg = $validate_array[1];
			echo "<script>$(function(){ toastr.error('" . $alert_msg . "') }); </script>";
		}
	}
}
$btnLeaveSave = isset($_POST['btn_Leave_Save']);
if ($btnLeaveSave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$count = 0;
		$len_check = 0;
		$checkValUp = isset($_POST['check_val_up']);
		$txtRequest = cleanUserInput($_POST['txt_Request']);
		if ($checkValUp && $txtRequest == 'NA' && cleanUserInput($_POST['txt_DateFrom']) == '' && cleanUserInput($_POST['txt_DateTo']) == '' && cleanUserInput($_POST['txtEmpID']) == '') {
			if ($txtRequest == 'NA' && cleanUserInput($_POST['txt_DateFrom']) == '' && cleanUserInput($_POST['txt_DateTo']) == '' && cleanUserInput($_POST['txtEmpID']) == '') {
				$len_check = 1;
			} else {
				$len_check = 2;
			}
		} else if ($txtRequest != 'NA' && cleanUserInput($_POST['txt_DateFrom']) != '' && cleanUserInput($_POST['txt_DateTo']) != '' && cleanUserInput($_POST['txtEmpID']) != '') {
			$len_check = 0;
		} else {
			$len_check = 2;
		}
		if ($len_check === 1) {

			foreach ($_POST['check_val_up'] as $checkboxs) {

				$checkbox = cleanUserInput($checkboxs);
				$sqlGetData = 'call GetRequestDetailsByID("' . $checkbox . '")';
				$result_byID = $myDB->query($sqlGetData);
				if ($result_byID[0]['MgrStatus'] == 'Approve') {

					$ExpID = $result_byID[0]['ID'];
					$EmployeeID = $result_byID[0]['EmployeeID'];
					$Name = $result_byID[0]['EmployeeName'];
					$Exception = $result_byID[0]['Exception'];
					$EmployeeComment = cleanUserInput($_POST['txt_common_comment']);
					$DateFrom = $result_byID[0]['DateFrom'];
					$DateTo = $result_byID[0]['DateTo'];

					$ShiftIn = $result_byID[0]['ShiftIn'];
					$ShiftOut = $result_byID[0]['ShiftOut'];
					$IssueType = $result_byID[0]['IssueType'];
					$CurrAtt = $result_byID[0]['Current_Att'];
					$UpdateAtt = $result_byID[0]['Update_Att'];
					$LeaveType = $result_byID[0]['LeaveType'];
					$txtApprovedBy = $result_byID[0]['account_head'];
					$MngrStatusID = $result_byID[0]['MgrStatus'];
					$txtApprovedByName = $result_byID[0]['ReportTo'];
					$HeadStatusID = cleanUserInput($_POST['txtSiteHeadApproval']);
					$ModifiedBy = clean($_SESSION['__user_logid']);
					$DateModified = date('Y-m-d H:i:s');

					$sqlInsertException = 'call UpdateRequestDetailsManager("' . $ExpID . '","' . $DateFrom . '","' . $DateTo . '","' . $Exception . '","' . $EmployeeComment . '","' . $MngrStatusID . '","' . $HeadStatusID . '","' . $ModifiedBy . '","' . $DateModified . '","' . $IssueType . '","' . $CurrAtt . '","' . $UpdateAtt . '","' . $ShiftIn . '","' . $ShiftOut . '","' . $LeaveType . '","web-addRequest_Split1256")';
					$flag = $myDB->rawQuery($sqlInsertException);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {

						if (clean($_SESSION['__user_logid']) != "CE03070003" && $MngrStatusID == "Approve") {
							if ($Exception == "Roster Change" || $Exception == "Shift Change") {
								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateTo);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster('" . $EmployeeID . "','" . $month . "','" . $year . "','" . $day . "','" . $shift . "')";

									$flag = $myDB->rawQuery($query);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									if ($rowCount > 0) {
										$count++;
									} else {
										$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='Roster or shift change for split one day is not updated'";
										$selectQu = $conn->prepare($logQuery);
										$selectQu->bind_param("s", $ExpID);
										$selectQu->execute();
										$result = $selectQu->get_result();
									}
								} else {
									$begin = new DateTime($DateFrom);
									$end   = new DateTime($DateTo);

									for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
										$month = intval($i->format('m'));
										$year = intval($i->format('Y'));
										$day  =  intval($i->format('d'));
										$val = $shift;
										$Time_inout = explode('-', $val);
										$query = "call sp_UpdateRoaster('" . $EmployeeID . "','" . $month . "','" . $year . "','" . $day . "','" . $shift . "')";


										$flag = $myDB->rawQuery($query);
										$error = $myDB->getLastError();
										$rowCount = $myDB->count;
										if ($rowCount > 0) {
											$count++;
										} else {
											$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='Roster or shift change for split is not updated'";
											$selectQu = $conn->prepare($logQuery);
											$selectQu->bind_param("s", $ExpID);
											$selectQu->execute();
											$result = $selectQu->get_result();
										}
									}
									unset($begin);
									unset($end);
								}

								if ($count == 0)
									$count++;

								$alert_msg = $Exception . ' Request for ' . $count . ' Day has been ' . $HeadStatusID . ' By <b>' . cleanUserInput($_POST['txtApprovedBy_sh']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($Exception == "Back Dated Leave") {
								$EmployeeID = $EmployeeID;
								$DateFrom1 = $DateFrom;
								$DateTo1 = $DateTo;

								$ReasonofLeave = $Exception;
								$datetime1 = new DateTime($DateFrom);
								$datetime2 = new DateTime($DateTo);
								$difference_calc = $datetime1->diff($datetime2);
								$TotalLeaves1 = $difference_calc->d + 1;
								$MngrStatusID = $MngrStatusID;
								$ManagerComment = $EmployeeComment;
								$CreatedBy = $EmployeeID;
								$ModifiedBy = cleanUserInput($_POST['txtApprovedBy_sh']);

								$query = 'INSERT INTO leavehistry(EmployeeID,DateFrom,DateTo,ReasonofLeave,LeaveOnDate,TotalLeaves,EmployeeComment,MngrStatusID,HRStatusID,ManagerComment,HRComents,CreatedBy,HOD,LeaveType) VALUES (?,?,?,?,"' . date('Y-m-d') . '",?,"Approve","Approve","Approve","NA","",?,?,?)';

								$select_qu = $conn->prepare($query);
								$select_qu->bind_param("ssssssss", $EmployeeID, $DateFrom1, $DateTo1, $ReasonofLeave, $TotalLeaves1, $CreatedBy, $txtApprovedBy, $LeaveType);
								$select_qu->execute();
								$flag = $select_qu->get_result();

								if ($flag->num_rows > 0) {
									$alert_msg = $Exception . " Request for " . $TotalLeaves1 . " day is " . $HeadStatusID . ' By <b>' . cleanUserInput($_POST['txtApprovedBy_sh']) . '</b>';
									echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
								} else {

									$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='back dated leave split is not updated'";
									$selectQu = $conn->prepare($logQuery);
									$selectQu->bind_param("s", $ExpID);
									$selectQu->execute();
									$result = $selectQu->get_result();
								}
							} else if ($Exception == "Working on Holiday" || $Exception == "Working on WeekOff" || $Exception == "Working on Leave") {
								$EmployeeID = $EmployeeID;
								$RequestType = $Exception;
								$DateCreated = $DateFrom;
								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateFrom);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster_Split('" . $EmployeeID . "','" . $month . "','" . $year . "','" . "" . $day . "','" . $ShiftIn . "','" . $ShiftOut . "')";
									$flag = $myDB->rawQuery($query);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									if ($rowCount > 0) {
										$count++;
									} else {

										$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='Working on Holiday split is not updated'";
										$selectQu = $conn->prepare($logQuery);
										$selectQu->bind_param("s", $ExpID);
										$selectQu->execute();
										$result = $selectQu->get_result();
									}
								}

								$alert_msg = $Exception . " Request for " . $count . " day is " . $HeadStatusID . ' By <b>' . cleanUserInput($_POST['txtApprovedBy_sh']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($Exception == "Biometric issue") {
								$alert_msg = $Exception . ' Request for 1 Day has been ' . $HeadStatusID . ' By <b>' .  cleanUserInput($_POST['txtApprovedBy_sh']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							}
						} else {
							if ($MngrStatusID == "Approve") {
								$alert_msg = 'Request submitted and sent to <b>' .  cleanUserInput($_POST['txtApprovedBy_sh']) . ' </b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($MngrStatusID == "Pending") {
								$alert_msg = 'Request submitted and sent to <b>' . $txtApprovedByName . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							}
						}
					} else {

						$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error=?";
						$selectQu = $conn->prepare($logQuery);
						$selectQu->bind_param("ss", $ExpID, $error);
						$selectQu->execute();
						$result = $selectQu->get_result();
						$alert_msg = 'Request Not submitted :' . $error;
						echo "<script>$(function(){ toastr.error('" . $alert_msg . "') }); </script>";
					}
				}
			}
		} else if (cleanUserInput($_POST['txtID']) > 0 && $len_check === 0) {
			$ExpID = cleanUserInput($_POST['txtID']);
			$EmployeeID = cleanUserInput($_POST['txtEmpID']);
			$Name = clean($_SESSION['__user_Name']);
			$Exception = cleanUserInput($_POST['txt_Request']);


			$sqlGetData = 'call GetRequestDetailsByID("' . $ExpID . '")';
			$result_byID = $myDB->query($sqlGetData);
			$empID = $result_byID[0]['EmployeeID'];


			$EmployeeComment = cleanUserInput($_POST['txt_common_comment']);
			$DateFrom = cleanUserInput($_POST['txt_DateFrom']);
			$DateTo = cleanUserInput($_POST['txt_DateTo']);


			if ($txtRequest == "Roster Change" || $txtRequest == "Shift Change") {
				$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
				$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
				$ShiftIn2 = cleanUserInput($_POST['txt_ShiftIn2']);
				$ShiftOut2 = cleanUserInput($_POST['txt_ShiftOut2']);
				$IssueType = "NA";
				$CurrAtt = "NA";
				$UpdateAtt = "NA";
				$LeaveType = "NA";
			} else if ($txtRequest == "Biometric issue") {
				$ShiftIn = cleanUserInput($_POST['txt_access_in']);
				$ShiftOut = cleanUserInput($_POST['txt_access_out']);
				$LeaveType = cleanUserInput($_POST['txt_descheck']);
				$ShiftIn2 = "NA";
				$ShiftOut2 = "NA";
				$IssueType = 'Biomertic Issue';
				$CurrAtt = cleanUserInput($_POST['txt_curatnd']);
				$UpdateAtt = cleanUserInput($_POST['txt_updateatnd']);
			} else if ($txtRequest == "Back Dated Leave") {
				$ShiftIn = "NA";
				$ShiftOut = "NA";
				$ShiftIn2 = "NA";
				$ShiftOut2 = "NA";
				$LeaveType = cleanUserInput($_POST['txt_LeaveType']);
				$IssueType = "NA";
				$CurrAtt = "NA";
				$UpdateAtt = "NA";
			} else if ($txtRequest == "Working on Holiday" || $txtRequest == "Working on WeekOff" || $txtRequest == "Working on Leave") {
				$ShiftIn = cleanUserInput($_POST['txt_ShiftIn']);
				$ShiftOut = cleanUserInput($_POST['txt_ShiftOut']);
				$ShiftIn2 = cleanUserInput($_POST['txt_ShiftIn2']);
				$ShiftOut2 = cleanUserInput($_POST['txt_ShiftOut2']);
				$LeaveType = "NA";
				$IssueType = "NA";
				$CurrAtt = "NA";
				$UpdateAtt = "P";
			} else {
				$ShiftIn = "NA";
				$ShiftOut = "NA";
				$ShiftIn2 = "NA";
				$ShiftOut2 = "NA";
				$IssueType = "NA";
				$CurrAtt = "NA";
				$UpdateAtt = "NA";
				$LeaveType = "NA";
			}
			$MngrStatusID = cleanUserInput($_POST['txtSupervisorApproval']);
			$HeadStatusID = cleanUserInput($_POST['txtSiteHeadApproval']);
			$ModifiedBy = clean($_SESSION['__user_logid']);
			$DateModified = date('Y-m-d H:i:s');

			$Shift1 = $ShiftIn . "|" . $ShiftOut;
			$Shift2 = $ShiftIn2 . "|" . $ShiftOut2;
			$validate_array = check_validate($Exception, cleanUserInput($_POST['txtEmpID']), $DateFrom, $DateTo, FALSE);
			$validate = $validate_array[0];
			$validation = 0;

			if ($validation === 0 && $validate === 0) {

				try {

					$sqlInsertException = 'call UpdateRequestDetailsManager("' . $ExpID . '","' . $DateFrom . '","' . $DateTo . '","' . $Exception . '","' . $EmployeeComment . '","' . $MngrStatusID . '","' . $HeadStatusID . '","' . $ModifiedBy . '","' . $DateModified . '","' . $IssueType . '","' . $CurrAtt . '","' . $UpdateAtt . '","' . $Shift1 . '","' . $Shift2 . '","' . $LeaveType . '","web-addRequest_Split1525")';
					$flag = $myDB->rawQuery($sqlInsertException);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {

						if ($MngrStatusID == "Approve") {
							if ($Exception == "Roster Change" || $Exception == "Shift Change") {
								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateTo);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster_Split('" . cleanUserInput($_POST['txtEmpID']) . "','" . $month . "','" . $year . "','" . $day . "','" . $Shift1 . "','" . $Shift2 . "')";

									$flag = $myDB->rawQuery($query);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									if ($rowCount > 0) {
										$count++;
									} else {

										$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='Split " . $count . $error . "'";
										$selectQu = $conn->prepare($logQuery);
										$selectQu->bind_param("s", $ExpID);
										$selectQu->execute();
										$result = $selectQu->get_result();
									}
								} else {

									$begin = new DateTime($DateFrom);
									$end   = new DateTime($DateTo);

									for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
										$month = intval($i->format('m'));
										$year = intval($i->format('Y'));
										$day  =  intval($i->format('d'));
										$val = $shift;
										$Time_inout = explode('-', $val);
										$query = "call sp_UpdateRoaster_Split('" . cleanUserInput($_POST['txtEmpID']) . "','" . $month . "','" . $year . "','" . $day . "','" . $Shift1 . "','" . $Shift2 . "')";
										$flag = $myDB->rawQuery($query);
										$error = $myDB->getLastError();
										$rowCount = $myDB->count;
										if ($rowCount > 0) {
											$count++;
										} else {
											$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='split " . $count . "'";
											$selectQu = $conn->prepare($logQuery);
											$selectQu->bind_param("s", $ExpID);
											$selectQu->execute();
											$result = $selectQu->get_result();
										}
									}
									unset($begin);
									unset($end);
								}

								if ($count == 0)
									$count++;
								$txtApprovedBy = cleanUserInput($_POST['txtApprovedBy']);
								$alert_msg = $Exception . ' Request for ' . $count . ' Day is ' . $MngrStatusID . ' <b> by ' . $txtApprovedBy . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($Exception == "Back Dated Leave") {
								$EmployeeID = cleanUserInput($_POST['txtEmpID']);
								$DateFrom1 = $DateFrom;
								$DateTo1 = $DateTo;
								$LeaveType = cleanUserInput($_POST['txt_LeaveType']);
								$ReasonofLeave = $Exception;
								$datetime1 = new DateTime($DateFrom);
								$datetime2 = new DateTime($DateTo);
								$difference_calc = $datetime1->diff($datetime2);
								$TotalLeaves1 = $difference_calc->d + 1;
								$MngrStatusID = $MngrStatusID;
								$ManagerComment = $EmployeeComment;
								$CreatedBy = cleanUserInput($_POST['txtEmpID']);
								$ModifiedBy = cleanUserInput($_POST['txtApprovedBy_sh']);
								$txtApprovedBy = cleanUserInput($_POST['txtApprovedBy']);
								$query = 'INSERT INTO leavehistry(EmployeeID,DateFrom,DateTo,ReasonofLeave,LeaveOnDate,TotalLeaves,EmployeeComment,MngrStatusID,HRStatusID,ManagerComment,HRComents,CreatedBy,HOD,LeaveType) VALUES (?,?,?,?,"' . date('Y-m-d') . '",?,"Approve","Approve","Approve","NA","",?,?,?)';
								$select_qu = $conn->prepare($query);
								$select_qu->bind_param("ssssssss", $EmployeeID, $DateFrom1, $DateTo1, $ReasonofLeave, $TotalLeaves1, $CreatedBy, $txtApprovedBy, $LeaveType);
								$select_qu->execute();
								$flag = $select_qu->get_result();

								if ($flag->num_rows > 0) {

									if ((int)date('d', time()) > 5) {
										if (date('m', strtotime($DateFrom)) == date('m', strtotime(date('Y-m-d', time()))) - 1) {

											// $flag1 = 'SELECT * FROM paid_leave_all where EmployeeID="' . $EmployeeID . '" and Month(date_paid)=Month("' . date('Y-m-d', time()) . '") and Year(date_paid)=Year("' . date('Y-m-d', time()) . '") order by id limit 1;';

											$query = 'SELECT * FROM paid_leave_all where EmployeeID=? and Month(date_paid)=Month("' . date('Y-m-d', time()) . '") and Year(date_paid)=Year("' . date('Y-m-d', time()) . '") order by id limit 1;';
											$selectQu = $conn->prepare($query);
											$selectQu->bind_param("s", $EmployeeID);
											$selectQu->execute();
											$flag = $selectQu->get_result();
											$flags = $flag->fetch_row();
											if ($flag->num_rows > 0) {
												/*foreach($flag as $key => $val)
									            	{
														foreach($val as $k=>$v)
														{
															$app_key_main = $v;
														}
													}*/
												$sum_release = $flags[2];
												$rem_leave = $sum_release - $TotalLeaves1;
												if ($rem_leave < 0) {
													$rem_leave = 0;
												}


												$flag = $myDB->query('call save_paidleave("' . $rem_leave . '","' . date('Y-m-d', strtotime(date('Y-m-01', time()))) . '","' . $EmployeeID . '")');
											}
										}
									}

									$alert_msg = $Exception . " Request for " . $TotalLeaves1 . " day is " . $MngrStatusID . ' by ' . cleanUserInput($_POST['txtApprovedBy']) . ' </b>';
									echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
								} else {
									$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='split" . $flag . "'";
									$selectQu = $conn->prepare($logQuery);
									$selectQu->bind_param("s", $ExpID);
									$selectQu->execute();
									$result = $selectQu->get_result();
								}
							} else if ($Exception == "Working on Holiday" || $Exception == "Working on WeekOff" || $Exception == "Working on Leave") {
								$EmployeeID = cleanUserInput($_POST['txtEmpID']);
								$RequestType = $Exception;
								$DateCreated = $DateFrom;
								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateFrom);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster_Split('" . cleanUserInput($_POST['txtEmpID']) . "','" . $month . "','" . $year . "','" . $day . "','" . $Shift1 . "','" . $Shift2 . "')";

									$flag = $myDB->rawQuery($query);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									if ($rowCount > 0) {
										$count++;
									} else {

										$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error='split " . $count . "'";
										$selectQu = $conn->prepare($logQuery);
										$selectQu->bind_param("s", $ExpID);
										$selectQu->execute();
										$result = $selectQu->get_result();
									}
								}
								$alert_msg = $Exception . " Request for " . $count . " day is " . $MngrStatusID . '</b> by ' . cleanUserInput($_POST['txtApprovedBy']);
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($Exception == "Biometric issue") {
								$alert_msg = $Exception . ' Request for 1 Day is ' . $MngrStatusID . ' <b> by ' . cleanUserInput($_POST['txtApprovedBy']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							}

							$url = '';
							//$url = URL.'View/calcAtnd_for_empid.php?empid='.$EmpID.'&month='.date('m',strtotime($DateFrom)).'&year='.date('Y',strtotime($DateFrom));
							$iTime_in = new DateTime($DateFrom);
							$iTime_out = new DateTime();
							$interval = $iTime_in->diff($iTime_out);
							/*if($interval->format("%a") <= 10)
									{
										$url = URL.'View/calcRange.php?empid='.$_POST['txtEmpID'].'&type=one';
										

									}
									else
									{
										$url = URL.'View/calcRange.php?empid='.$_POST['txtEmpID'].'&type=one&from='.date('Y-m-d',strtotime($DateFrom));

									}*/

							$url = URL . 'View/calcRange.php?empid=' . cleanUserInput($_POST['txtEmpID']) . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));

							$curl = curl_init();
							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_HEADER, false);
							$data = curl_exec($curl);
							curl_close($curl);
						} else {
							if ($MngrStatusID == "Approve") {
								$alert_msg = 'Request submitted and sent To <b>' . cleanUserInput($_POST['txtApprovedBy_sh']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else if ($MngrStatusID == "Pending") {
								$alert_msg = 'Request submitted and sent To <b>' . cleanUserInput($_POST['txtApprovedBy']) . '</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							} else  if ($MngrStatusID == "Decline") {
								$alert_msg = 'Request submitted as not Valid for next Level.</b>';
								echo "<script>$(function(){ toastr.success('" . $alert_msg . "') }); </script>";
							}
						}
					}
				} catch (Exception $ex) {
					$erre = $ex->getMessage();
					$logQuery = "INSERT INTO exception_log_error set exp_id=? ,exp_error=?";
					$selectQu = $conn->prepare($logQuery);
					$selectQu->bind_param("ss", $ExpID, $erre);
					$selectQu->execute();
					$result = $selectQu->get_result();
					$alert_msg = 'Request not submitted :' . $error;
					echo "<script>$(function(){ toastr.info('" . $alert_msg . "') }); </script>";
				}
			} else {
				$alert_msg = $validate_array[1];
				echo "<script>$(function(){ toastr.error('" . $alert_msg . "') }); </script>";
			}
		} else {
			$alert_msg = 'Request not submitted :Try Again';
			echo "<script>$(function(){ toastr.error('" . $alert_msg . "') }); </script>";
		}
	}
}
$search = 'Pending';
$txtSearch = isset($_POST['txt_search']);

if ($txtSearch) {
	$txt_search = cleanUserInput($_POST['txt_search']);
	$search = $txt_search;
}
$orderText = isset($_POST['order_text']);
if ($orderText) {
	//echo $_POST['order_text'];
	$orderText = cleanUserInput($_POST['order_text']);
	switch ($orderText) {
		case '10 rows': {
				//echo $_POST['order_text'];
				$order_len = 10;
				break;
			}
		case '25 rows': {
				//echo $_POST['order_text'];
				$order_len = 25;
				break;
			}
		case '50 rows': {
				$order_len = 50;
				break;
			}
		case '50 rows': {
				$order_len = 50;
				break;
			}
		case 'Show all': {
				$order_len = 2500;
				break;
			}
		case '2500': {
				$order_len = 2500;
				break;
			}
		default: {
				$order_len = 5;
				break;
			}
	}
}
?>


<script>
	$(document).ready(function() {
		function eventFired_order(el) {
			//alert($('#order_text').val());
			$('#order_text').val($('.dt-button.active>span').text());
			//alert($('#order_text').val()+','+$('.dt-button.active>span').text());
		}
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			"iDisplayLength": $('#order_text').val(),
			buttons: [

				/*{
				    extend: 'csv',
				    text: 'CSV',
				    extension: '.csv',
				    exportOptions: {
				        modifier: {
				            page: 'all'
				        }
				    },
				    title: 'table'
				}, 						         
				'print',
				{
				    extend: 'excel',
				    text: 'EXCEL',
				    extension: '.xlsx',
				    exportOptions: {
				        modifier: {
				            page: 'all'
				        }
				    },
				    title: 'table'
				},'copy',*/
				'pageLength'

			],
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
		}).search($('#txt_search').val()).draw().on('order.dt', function() {
			eventFired_order();
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('input[type="search"]').change(function() {
			// alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
			$('#ctl00_ContentPlaceHolder1_txt_search').val($('input[type="search"]').val());
			$('#ctl00_ContentPlaceHolder1_lblmsg2').text("Search Data  :: " + $('input[type="search"]').val());
			$('#ctl00_ContentPlaceHolder1_GridView1 input[type="checkbox"]').prop("checked", false);

		});
		$('input[type="search"]').blur(function() {
			// alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
			$('#txt_search').val($('input[type="search"]').val());

		});
		$('input[type="search"]').keyup(function() {
			// alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
			$('#txt_search').val($('input[type="search"]').val());

		});
		/*$('dt-button-collection.dt-button').click(function(){
			var data_label = $(this).children('span').text();
			alert(data_label);
		});*/
	});
</script>
<style>
	.ui-datepicker-calendar tbody {

		border: 1px solid #bdbdbd;

	}

	/* DatePicker Container */
	.ui-datepicker {
		width: 250px;
		height: auto;
		margin: 5px auto 0;
		font: 9pt Arial, sans-serif;
		-webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		-moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
	}

	.ui-datepicker a {
		text-decoration: none;
	}

	/* DatePicker Table */
	.ui-datepicker table {
		width: 100%;
	}

	.ui-datepicker-header {
		background: #1daec5;
		color: #e0e0e0;
		font-weight: bold;
		-webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 1);
		-moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
		box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
		text-shadow: 1px -1px 0px #1daec5;
		filter: dropshadow(color=#337ab7, offx=1, offy=-1);
		line-height: 30px;
		border-width: 1px 0 0 0;
		border-style: solid;
		border-color: #20c9e4;
	}

	.ui-datepicker-title {
		text-align: center;
	}

	.ui-datepicker-prev,
	.ui-datepicker-next {
		display: inline-block;
		width: 30px;
		height: 30px;
		text-align: center;
		cursor: pointer;
		background-repeat: no-repeat;
		line-height: 600%;
		overflow: hidden;
	}

	.ui-datepicker-prev {
		float: left;
		background-position: center -30px;
	}

	.ui-datepicker-next {
		float: right;
		background-position: center 0px;
	}

	.ui-datepicker thead {
		background-color: #f7f7f7;
		background-image: -moz-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f7f7f7), color-stop(100%, #f1f1f1));
		background-image: -webkit-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -o-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -ms-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f7f7f7', endColorstr='#f1f1f1', GradientType=0);
		border-bottom: 1px solid #bbb;
	}

	.ui-datepicker th {
		text-transform: uppercase;
		font-size: 6pt;
		padding: 5px 0;
		color: #666666;
		text-shadow: 1px 0px 0px #fff;
		filter: dropshadow(color=#fff, offx=1, offy=0);
	}

	.ui-datepicker tbody td {
		padding: 0;
		border-right: 1px solid #bbb;
	}

	.ui-datepicker tbody td:last-child {
		border-right: 0px;
	}

	.ui-datepicker tbody tr {
		border-bottom: 1px solid #bbb;
	}

	.ui-datepicker tbody tr:last-child {
		border-bottom: 0px;
	}

	.ui-datepicker td span,
	.ui-datepicker td a {
		display: inline-block;
		font-weight: bold;
		text-align: center;
		width: 100%;
		line-height: 30px;
		color: #666666;
		text-shadow: 1px 1px 0px #fff;
		filter: dropshadow(color=#fff, offx=1, offy=1);
	}

	.ui-datepicker-calendar .ui-state-default {
		background: #ededed;
		background: -moz-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ededed), color-stop(100%, #dedede));
		background: -webkit-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -o-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -ms-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: linear-gradient(top, #ededed 0%, #dedede 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dedede', GradientType=0);
		-webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
		-moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
		box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
	}

	.ui-datepicker-calendar .ui-state-hover {
		background: #f7f7f7;
	}

	.ui-datepicker-calendar .ui-state-active {
		background: #6eafbf;
		-webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		-moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		color: #e0e0e0;
		text-shadow: 0px 1px 0px #4d7a85;
		filter: dropshadow(color=#4d7a85, offx=0, offy=1);
		border: 1px solid #55838f;
		position: relative;
		margin: 0px;
	}

	.ui-datepicker-unselectable .ui-state-default {
		background: #f4f4f4;
		color: #b4b3b3;
	}

	.ui-datepicker-calendar td:first-child .ui-state-active {

		margin-left: 0;
	}

	.ui-datepicker-calendar td:last-child .ui-state-active {

		margin-right: 0;
	}

	.ui-datepicker-calendar tr:last-child .ui-state-active {

		margin-bottom: 0;
	}

	.ui-datepicker-month,
	.ui-datepicker-year {
		color: #fff;
		font-weight: bold;
	}

	.ui-datepicker select.ui-datepicker-month,
	.ui-datepicker select.ui-datepicker-year {
		font-size: 16px;
		border-radius: 15px;
		border: 1px solid #0070d0;
		padding-left: 15px;
	}

	.ui-state-default,
	.ui-widget-content .ui-state-default,
	.ui-widget-header .ui-state-default {
		border: none;
	}

	.ui-state-highlight {
		color: #1c94c4 !important;
	}
</style>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Exception</span>
	<div class="pim-container row" id="div_main">
		<div>
			<?php
			if (!empty($alert_msg))
				//echo "<script>$(function(){ toastr.success('".$alert_msg."') }); </script>";
			?>
			<div class="hid-container">

				<?php
			$link_to_report = '';
			if ((clean($_SESSION['__status_th']) == clean($_SESSION['__user_logid']) || clean($_SESSION['__status_oh']) == clean($_SESSION['__user_logid']) || clean($_SESSION['__status_qh']) == clean($_SESSION['__user_logid'])) || (((clean($_SESSION['__status_ah']) != 'No' && clean($_SESSION['__status_ah']) == clean($_SESSION['__user_logid'])) && clean($_SESSION['__status_ah']) != '') || clean($_SESSION['__user_type']) == 'ADMINISTRATOR' || clean($_SESSION['__user_type']) == 'CENTRAL MIS' || clean($_SESSION['__user_type'] == 'HR'))) {
				/*$link_to_report = '<a href="ex_rpt" target="_blank" class=""><i class="material-icons tiny">dashboard</i>Exception Report</a> ';*/

				$link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="ex_rpt" data-position="bottom" data-tooltip="Exception Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';
			} elseif (!(clean($_SESSION["__user_Desg"]) == "CSE" || clean($_SESSION["__user_Desg"]) == "C.S.E." || clean($_SESSION["__user_Desg"]) == "Sr. C.S.E" || clean($_SESSION["__user_Desg"]) == "C.S.E" || clean($_SESSION["__user_Desg"]) == "Senior Customer Care Executive" || clean($_SESSION["__user_Desg"]) == "Customer Care Executive" || clean($_SESSION["__user_Desg"]) == "CSA" || clean($_SESSION["__user_Desg"]) == "Senior CSA")) {
				/*$link_to_report = '<a href="rpt_Exception_for_rt.php" target="_blank" class="" ><i class="material-icons tiny">dashboard</i> Exception Report</a> ';	*/
				$link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="rpt_Exception_for_rt.php" data-position="bottom" data-tooltip="Exception Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';
			}

				?>
				<h4>Raise Request <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Search Request"><i class="material-icons">ohrm_filter</i></a> <?php echo $link_to_report; ?></h4>
				<div class="form-div">

					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
					<input type="hidden" name="txt_cur_empid" id="txt_cur_empid" value="<?php echo clean($_SESSION['__user_logid']); ?>" />
					<input type="hidden" name="txtID" id="txtID" value="" />
					<input type="hidden" name="txt_search" id="txt_search" value="<?php echo $search; ?>" />
					<input type="hidden" id="order_text" name="order_text" value="<?php echo $order_len ?>" />
					<div class="schema-form-section row">
						<div>
							<div id="app_link1" class="hid-container col s12 m12 no-padding card"></div>

							<div class="input-field col s6 m6 clsIDHome">

								<input type="text" readonly="true" id="txtEmpName" name="txtEmpName" value="<?php echo clean($_SESSION['__user_Name']); ?>" />
								<label for="txtEmpName">Employee Name</label>
								<input type="hidden" readonly="true" id="txtEmpName1" name="txtEmpName1" value="<?php echo clean($_SESSION['__user_Name']); ?>" />
							</div>

							<div class="input-field col s6 m6 clsIDHome">
								<input type="text" readonly="true" id="txtEmpID" name="txtEmpID" value="<?php echo clean($_SESSION['__user_logid']); ?>" />
								<label for="txtEmpID">Employee ID</label>

								<input type="hidden" readonly="true" id="txtEmpID1" name="txtEmpID1" value="<?php echo clean($_SESSION['__user_logid']); ?>" />
							</div>


							<div class="input-field col s6 m6 8 clsIDHome">
								<select class="" id="txt_Request" name="txt_Request">
									<option Selected="True" Value="NA">---Select---</option>
									<option>Back Dated Leave</option>
									<option>Roster Change</option>
									<option>Shift Change</option>
									<option>Biometric issue</option>
									<!--<option>Working on Holiday</option>-->
									<option>Working on WeekOff</option>
									<option>Working on Leave</option>
								</select>
								<label title="" for="txt_Request" class="active-drop-down active">Raise Request</label>
							</div>


							<div class="input-field col s6 m6 8">
								<div class="input-field col s6 m6 8" style="margin: 0px;">
									<input id="txt_DateFrom" value="" name="txt_DateFrom" title="Select Join  From Date" type="text">
									<label for="txt_DateFrom" class="activeted_al active"> Date From</label>
								</div>
								<div class="input-field col s6 m6 8" style="margin: 0px;">
									<input id="txt_DateTo" value="" name="txt_DateTo" title="Select Join Date To " type="text">
									<label for="txt_DateTo" class="activeted_al active"> Date To</label>
								</div>
							</div>


							<div class="hidden input-field col s6 m6 8 clsIDHome" id="backleave">
								<select class="input-field col s12 m12 l6" id="txt_LeaveType" name="txt_LeaveType">
									<option Selected="True" Value="NA">---Select---</option>
									<option>Leave</option>
								</select>
								<label title="" for="txt_LeaveType" class="active-drop-down active">Leave Type</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="shif_div1">
								<select class="form-control clsInput" id="txt_ShiftIn" name="txt_ShiftIn">
									<option Selected="True" Value="NA">---Select---</option>
									<option>0:00</option>
									<option>0:30</option>
									<option>1:00</option>
									<option>1:30</option>
									<option>2:00</option>
									<option>2:30</option>
									<option>3:00</option>
									<option>3:30</option>
									<option>4:00</option>
									<option>4:30</option>
									<option>5:00</option>
									<option>5:30</option>
									<option>6:00</option>
									<option>6:30</option>
									<option>7:00</option>
									<option>7:30</option>
									<option>8:00</option>
									<option>8:30</option>
									<option>9:00</option>
									<option>9:30</option>
									<option>10:00</option>
									<option>10:30</option>
									<option>11:00</option>
									<option>11:30</option>
									<option>12:00</option>
									<option>12:30</option>
									<option>13:00</option>
									<option>13:30</option>
									<option>14:00</option>
									<option>14:30</option>
									<option>15:00</option>
									<option>15:30</option>
									<option>16:00</option>
									<option>16:30</option>
									<option>17:00</option>
									<option>17:30</option>
									<option>18:00</option>
									<option>18:30</option>
									<option>19:00</option>
									<option>19:30</option>
									<option>20:00</option>
									<option>20:30</option>
									<option>21:00</option>
									<option>21:30</option>
									<option>22:00</option>
									<option>22:30</option>
									<option>23:00</option>
									<option>23:30</option>
									<!--<option>WO</option>-->
								</select>
								<label for="txt_ShiftIn" class="active-drop-down active">1- Shift IN</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="shif_div2">
								<select class="form-control clsInput" id="txt_ShiftOut" name="txt_ShiftOut" disabled="true">
									<option Selected="True" Value="NA">---Select---</option>
									<option>0:00</option>
									<option>0:30</option>
									<option>1:00</option>
									<option>1:30</option>
									<option>2:00</option>
									<option>2:30</option>
									<option>3:00</option>
									<option>3:30</option>
									<option>4:00</option>
									<option>4:30</option>
									<option>5:00</option>
									<option>5:30</option>
									<option>6:00</option>
									<option>6:30</option>
									<option>7:00</option>
									<option>7:30</option>
									<option>8:00</option>
									<option>8:30</option>
									<option>9:00</option>
									<option>9:30</option>
									<option>10:00</option>
									<option>10:30</option>
									<option>11:00</option>
									<option>11:30</option>
									<option>12:00</option>
									<option>12:30</option>
									<option>13:00</option>
									<option>13:30</option>
									<option>14:00</option>
									<option>14:30</option>
									<option>15:00</option>
									<option>15:30</option>
									<option>16:00</option>
									<option>16:30</option>
									<option>17:00</option>
									<option>17:30</option>
									<option>18:00</option>
									<option>18:30</option>
									<option>19:00</option>
									<option>19:30</option>
									<option>20:00</option>
									<option>20:30</option>
									<option>21:00</option>
									<option>21:30</option>
									<option>22:00</option>
									<option>22:30</option>
									<option>23:00</option>
									<option>23:30</option>
									<!--<option>WO</option>-->
								</select>

								<label for="txt_ShiftOut" class="active-drop-down active">1- Shift Out</label>
							</div>
							<br />

							<div class="input-field col s6 m6 8 hidden" id="shif_div11">
								<select class="form-control clsInput" id="txt_ShiftIn2" name="txt_ShiftIn2">
									<option Selected="True" Value="NA">---Select---</option>
									<option>0:00</option>
									<option>0:30</option>
									<option>1:00</option>
									<option>1:30</option>
									<option>2:00</option>
									<option>2:30</option>
									<option>3:00</option>
									<option>3:30</option>
									<option>4:00</option>
									<option>4:30</option>
									<option>5:00</option>
									<option>5:30</option>
									<option>6:00</option>
									<option>6:30</option>
									<option>7:00</option>
									<option>7:30</option>
									<option>8:00</option>
									<option>8:30</option>
									<option>9:00</option>
									<option>9:30</option>
									<option>10:00</option>
									<option>10:30</option>
									<option>11:00</option>
									<option>11:30</option>
									<option>12:00</option>
									<option>12:30</option>
									<option>13:00</option>
									<option>13:30</option>
									<option>14:00</option>
									<option>14:30</option>
									<option>15:00</option>
									<option>15:30</option>
									<option>16:00</option>
									<option>16:30</option>
									<option>17:00</option>
									<option>17:30</option>
									<option>18:00</option>
									<option>18:30</option>
									<option>19:00</option>
									<option>19:30</option>
									<option>20:00</option>
									<option>20:30</option>
									<option>21:00</option>
									<option>21:30</option>
									<option>22:00</option>
									<option>22:30</option>
									<option>23:00</option>
									<option>23:30</option>
									<!--<option>WO</option>-->
								</select>
								<label for="txt_ShiftIn" class="active-drop-down active">2- Shift IN</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="shif_div22">
								<select class="form-control clsInput" id="txt_ShiftOut2" name="txt_ShiftOut2" disabled="true">
									<option Selected="True" Value="NA">---Select---</option>
									<option>0:00</option>
									<option>0:30</option>
									<option>1:00</option>
									<option>1:30</option>
									<option>2:00</option>
									<option>2:30</option>
									<option>3:00</option>
									<option>3:30</option>
									<option>4:00</option>
									<option>4:30</option>
									<option>5:00</option>
									<option>5:30</option>
									<option>6:00</option>
									<option>6:30</option>
									<option>7:00</option>
									<option>7:30</option>
									<option>8:00</option>
									<option>8:30</option>
									<option>9:00</option>
									<option>9:30</option>
									<option>10:00</option>
									<option>10:30</option>
									<option>11:00</option>
									<option>11:30</option>
									<option>12:00</option>
									<option>12:30</option>
									<option>13:00</option>
									<option>13:30</option>
									<option>14:00</option>
									<option>14:30</option>
									<option>15:00</option>
									<option>15:30</option>
									<option>16:00</option>
									<option>16:30</option>
									<option>17:00</option>
									<option>17:30</option>
									<option>18:00</option>
									<option>18:30</option>
									<option>19:00</option>
									<option>19:30</option>
									<option>20:00</option>
									<option>20:30</option>
									<option>21:00</option>
									<option>21:30</option>
									<option>22:00</option>
									<option>22:30</option>
									<option>23:00</option>
									<option>23:30</option>
									<!--<option>WO</option>-->
								</select>
								<label for="txt_ShiftOut" class="active-drop-down active">2- Shift Out</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="attendance_div1">
								<select class="form-control clsInput" id="txt_curatnd" name="txt_curatnd">
									<option Selected="True" Value="NA">---Select---</option>
									<option>A</option>

									<?php
									$date = date('Y-m-d', time());
									$query = 'select type_ from roster_temp where EmployeeID = ? and DateOn =? order by id desc limit 1';
									$select = $conn->prepare($query);
									$select->bind_param("ss", $EmplID, $date);
									$select->execute();
									$rst = $select->get_result();
									$type = $rst->fetch_row();
									// print_r($rst);
									// die;
									if ($rst->num_rows > 0) {

										if (intval($type[0]) != 3) {
									?>
											<option>H</option>
											<option>HWP</option>

									<?php
										}
									}

									?>

								</select>
								<label for="txt_curatnd" class="active-drop-down active">Current</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="attendance_div2">
								<label for="txt_updateatnd" class="active-drop-down active"> Updated :</label>
								<select class="form-control clsInput" id="txt_updateatnd" name="txt_updateatnd">
									<option Selected="True" Value="NA">---Select---</option>

								</select>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="access_div1">
								<?php
								$val_check_des = 0;
								$userDesg = clean($_SESSION['__user_Desg']);
								if ($userDesg == "CSE" || $userDesg == "C.S.E." || $userDesg == "Sr. C.S.E" || $userDesg == "C.S.E" || $userDesg == "Senior Customer Care Executive" || $userDesg == "Customer Care Executive" || $userDesg == "CSA" || $userDesg == "Senior CSA") {
									$val_check_des = 9;
								} else {
									$val_check_des = 1;
								}
								?>
								<input type="hidden" id="txt_descheck" name="txt_descheck" value="<?php echo $val_check_des; ?>" />
								<label for="txt_access_in"> Access In:</label>
								<input type="text" class="form-control clsInput" id="txt_access_in" name="txt_access_in" />
							</div>

							<div class="input-field col s6 m6 8 hidden" id="access_div2">
								<label for="txt_access_out"> Access Out:</label>
								<input type="text" class="form-control clsInput" id="txt_access_out" name="txt_access_out" />
							</div>

							<div class="input-field col s12 m12 16" id="user_div">
								<textarea class="materialize-textarea" id="txt_Comment" name="txt_Comment"></textarea>
								<label for="txt_Comment" class="active-drop-down active">Comment</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="super_div1">
								<select class="form-control clsInput" id="txtSupervisorApproval" name="txtSupervisorApproval" <?php echo $readonly_ah; ?>>
									<option>Pending</option>
									<option>Approve</option>
									<option>Decline</option>
								</select>
								<label for="txtSupervisorApproval" class="active-drop-down active">Supervisor</label>
							</div>

							<div class="input-field col s6 m6 8 hidden" id="super_div2">
								<input type="text" class="form-control clsInput" id="txtApprovedBy" name="txtApprovedBy" readonly="true" value="<?php echo $AccountHeadName; ?>" />
								<input type="hidden" class="form-control clsInput" id="txtApprovedByID" name="txtApprovedByID" value="<?php echo $AccountHead; ?>" />
								<label for="txtApprovedBy" class="active-drop-down active">Approved By</label>
							</div>

							<div id="super_div" class="input-field col s12 m12 16 hidden">
								<textarea class="form-control clsInput" id="txt_Comment_sp" name="txt_Comment_sp" <?php echo $readonly_ah; ?>></textarea>
								<label for="txt_Comment_sp" class="active-drop-down active">Enter Comment</label>
							</div>

							<div class="input-field col s12 m12 16 hidden" id="sitehead_div1">
								<select class="form-control clsInput" id="txtSiteHeadApproval" name="txtSiteHeadApproval" <?php echo $readonly_sh; ?>>
									<option>Pending</option>
									<option>Approve</option>
									<option>Decline</option>
								</select>
								<label for="txtSiteHeadApproval" class="active-drop-down active">Site Head</label>
							</div>

							<div class="input-field col s12 m12 16 hidden" id="sitehead_div2">
								<input type="text" class="form-control clsInput" id="txtApprovedBy_sh" name="txtApprovedBy_sh" readonly="true" value="<?php echo $SiteHeadName; ?>" />
								<input type="hidden" class="form-control clsInput" id="txtApprovedBy_shID" name="txtApprovedBy_shID" readonly="true" value="<?php echo $SiteHead; ?>" />
								<label for="txtApprovedBy_sh" class="active-drop-down active">Approved By</label>
							</div>

							<div id="sitehead_div" class="input-field col s12 m12 16  hidden">
								<textarea class="materialize-textarea" id="txt_Comment_sh" name="txt_Comment_sh" <?php echo $readonly_sh; ?>></textarea>
								<label for="txt_Comment_sh" class="active-drop-down active">Comment</label>
							</div>

							<div id="comment_box" class="input-field col s12 m12 16 hidden">
								<div>
									<div id="commentSection">
										<div class="col s12 m12 card" id="comment_container" style="margin: 0px;max-height: 150px;overflow: auto;">
										</div>
									</div>
									<div class="input-field col s12 m12 16">
										<textarea class="materialize-textarea" id="txt_common_comment" name="txt_common_comment"></textarea>
										<label for="txt_common_comment">Comment</label>
									</div>
								</div>
							</div>

							<div class="input-field col s12 m12 16 right-align">
								<button type="submit" name="btn_Leave_Add" id="btn_Leave_Add" class="btn waves-effect waves-green ">Raise Request</button>
								<button type="submit" name="btn_Leave_Save" id="btn_Leave_Save" class="btn waves-effect waves-green  hidden"> Update Request</button>
								<input id="hiddenval" type="hidden" disabled="true" readonly="true" />
							</div>


							<div class="col s12 m12 16" style="">
								<div class="input-field col s12 m12 16" id="app_link"></div>

								<?php
								$dateon = date('Y-m-d', time());
								$roster_type_tempp = 9;
								$query = 'select type_ from roster_temp where EmployeeID = ? and DateOn =? order by id desc limit 1';
								$select = $conn->prepare($query);
								$select->bind_param("ss", $EmplID, $dateon);
								$select->execute();
								$rst = $select->get_result();
								$res = $rst->fetch_row();
								if ($rst->num_rows > 0) {
									//var_dump($rst);
									if (intval($res[0]) == 2) {
										$roster_type_tempp = '11';
									} else if (intval($res[0]) == 3) {
										$roster_type_tempp = '5';
									} else {
										$roster_type_tempp = '9';
									}
								}
								?>
								<input type="hidden" value="<?php echo $roster_type_tempp; ?>" name="txtShifDiff" id="txtShifDiff" />

								<div id="myModal_content" class="modal">
									<!-- Modal content-->
									<div class="modal-content">
										<h4 class="col s12 m12 model-h4">Search Request</h4>
										<div class="modal-body">
											<div class="had-container pull-left col s12 m12 16" id="search_field1">
												<div class="input-field col s6 m6">
													<input type="text" readonly="true" id="txt_srch_DateFrom" name="txt_srch_DateFrom" value="<?php echo $date_srch_from; ?>" />
												</div>
												<div class="input-field col s6 m6">
													<input type="text" readonly="true" id="txt_srch_DateTo" name="txt_srch_DateTo" value="<?php echo $date_srch_to; ?>" />
												</div>
												<div class="input-field col s12 m12 right-align">

													<input type="submit" class="btn waves-effect waves-green" id="btn_srch_submit" name="btn_srch_submit" value="Search">
													<button type="button" name="btn__Can" id="btn__Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
												</div>
											</div>

										</div>
									</div>
								</div>


								<div id="pnlTable">
									<div class="had-container pull-left card col s12 m12">
										<div class=" ">
											<?php
											$sqlConnect = 'call GetRequestDetails4("' . clean($_SESSION['__user_logid']) . '","' . $date_srch_from . '","' . $date_srch_to . '")';
											$myDB = new MysqliDb();
											$result = $myDB->query($sqlConnect);
											$error = $myDB->getLastError();
											if ($myDB->count > 0) { ?>
												<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
													<thead>
														<tr>
															<?php
															if (clean($_SESSION['__user_logid']) == "CE03070003") {
																echo '<th class="tbl__ALL_for_check"><input type="checkbox" name="check_ALL_up" id="chkAll" value="ALL" onclick="checkItem_All(this);" /> </th>';
															}
															?>
															<th>Edit </th>
															<th>EmployeeID</th>
															<th>EmployeeName</th>
															<th>Designation</th>
															<th>ReportTo</th>
															<th>Process</th>
															<th>Exception</th>
															<th>DateFrom</th>
															<th>DateTo</th>
															<th>MgrStatus</th>
															<!--<th>HeadStatus</th>-->
															<th>CreatedOn</th>
															<th>ModifiedOn</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$td_counter = 0;
														foreach ($result as $key => $value) {
															echo '<tr>';
															$td_counter++;
															if (clean($_SESSION['__user_logid']) == "CE03070003") {
																echo '<td class="tbl__ID_for_check"><input type="checkbox" class="check_val_" style="    margin-left: 40%;" name="check_val_up[]" id="chkitem_' . $td_counter . '" value="' . $value['ID'] . '" onclick="checkAll();" /></td>';
															}
															if ($value['MgrStatus'] == 'Pending' && $value['HeadStatus'] == 'Pending' && clean($_SESSION['__user_logid']) == $value['EmployeeID']) {
																echo '<td class="tbl__ID">
								                            <a href="#" data-ID="' . $value['ID'] . '" class="a__ID" onclick="javascript:return EditData(this);">
															<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i>
															</a>
															<a href="#" data-ID="' . $value['ID'] . '" class="a__ID" onclick="javascript:return DeleteReq(this);">
															<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="right" data-tooltip="Delete">ohrm_delete</i></a></td>';
															} else {
																echo '<td class="tbl__ID">
															<a href="#" data-ID="' . $value['ID'] . '" class="a__ID" onclick="javascript:return EditData(this);">
															<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i>
															</a></td>';
															}

															echo '<td class="tbl__EmployeeID">' . $value['EmployeeID'] . '</a></td>';
															echo '<td class="tbl__EmployeeName">' . $value['EmployeeName'] . '</td>';
															echo '<td class="tbl__designation">' . $value['designation'] . '</td>';
															echo '<td class="tbl__ReportTo">' . $value['ReportTo'] . '</td>';
															echo '<td class="tbl__process">' . $value['process'] . '</td>';
															echo '<td class="tbl__Exception">' . $value['Exception'] . '</td>';
															echo '<td class="tbl__DateFrom">' . $value['DateFrom'] . '</td>';
															echo '<td class="tbl__DateTo">' . $value['DateTo'] . '</td>';
															echo '<td class="tbl__MgrStatus">' . $value['MgrStatus'] . '</td>';
															//echo '<td class="tbl__HeadStatus">'.$value['HeadStatus'].'</td>';	
															echo '<td class="tbl__CreatedOn">' . $value['CreatedOn'] . '</td>';
															echo '<td class="tbl__ModifiedOn">' . $value['ModifiedOn'] . '</td>';
															echo '</tr>';
														}
														?>
													</tbody>
												</table><?php
													} else {
														echo "<script>$(function(){ toastr.info('No Data Found' " . $error . ") }); </script>";
													} ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>






<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	$(document).ready(function() {


		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Desg_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn__Can').on('click', function() {

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
		});

		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});

		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
		$('input[type="text"]').click(function() {
			$(this).closest('div').removeClass('has-error');
		});
		$('select,textarea').click(function() {
			$(this).closest('div').removeClass('has-error');
		});
		$('#btn_Leave_Add,#btn_Leave_Save').click(function() {

			var validate = 0;
			var alert_msg = '';
			$('#txt_DateFrom').removeAttr('disabled');
			$('#txt_DateTo').removeAttr('disabled');
			$('#txt_Request').closest('div').removeClass('has-error');
			$('#txt_DateFrom').closest('div').removeClass('has-error');
			$('#txt_DateTo').closest('div').removeClass('has-error');
			$('#txt_Comment').closest('div').removeClass('has-error');

			$('#txt_LeaveType').closest('div').removeClass('has-error');
			$('#txt_ShiftIn').closest('div').removeClass('has-error');
			$('#txt_ShiftOut').closest('div').removeClass('has-error');

			$('#txt_curatnd').closest('div').removeClass('has-error');
			$('#txt_updateatnd').closest('div').removeClass('has-error');

			$('#txt_access_in').closest('div').removeClass('has-error');
			$('#txt_access_out').closest('div').removeClass('has-error');
			if ($(this).attr('id') == 'btn_Leave_Save') {

				if ($('#txtID').val() == '') {
					$('#txtID').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxtID').size() == 0) {
						$('<span id="spantxtID" class="help-block">Request ID can not be Empty ,Please Select First</span>').insertAfter('#txtID');
					}
				}
			}
			if ($('#txt_Request').val() == 'NA') {
				$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Request').size() == 0) {
					$('<span id="spantxt_Request" class="help-block">Request Exception can not be Empty</span>').insertAfter('#txt_Request');
				}
				validate = 1;
			} else {
				if ($('#txt_Request').val() == 'Back Dated Leave') {
					if ($('#txt_LeaveType').val() == 'NA') {
						validate = 1;
						$('#txt_LeaveType').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_LeaveType').size() == 0) {
							$('<span id="spantxt_LeaveType" class="help-block">LeaveType can not be Empty</span>').insertAfter('#txt_LeaveType');
						}
					}

				} else if ($('#txt_Request').val() == 'Roster Change' || $('#txt_Request').val() == 'Shift Change' || $('#txt_Request').val() == 'Working on WeekOff' || $('#txt_Request').val() == 'Working on Leave') {
					if ($('#txt_ShiftIn').val() == 'NA') {
						validate = 1;
						$('#txt_ShiftIn').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_ShiftIn').size() == 0) {
							$('<span id="spantxt_ShiftIn" class="help-block">Shift In can not be Empty</span>').insertAfter('#txt_ShiftIn');
						}
					}
					if ($('#txt_ShiftOut').val() == 'NA') {
						validate = 1;
						$('#txt_ShiftOut').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_ShiftOut').size() == 0) {
							$('<span id="spantxt_ShiftOut" class="help-block">Shift Out can not be Empty</span>').insertAfter('#txt_ShiftOut');
						}
					}
					if ($('#txt_ShiftIn2').val() == 'NA') {
						validate = 1;
						$('#txt_ShiftIn2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_ShiftIn2').size() == 0) {
							$('<span id="spantxt_ShiftIn2" class="help-block">Second Shift In can not be Empty</span>').insertAfter('#txt_ShiftIn2');
						}
					}
					if ($('#txt_ShiftOut2').val() == 'NA') {
						validate = 1;
						$('#txt_ShiftOut2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_ShiftOut2').size() == 0) {
							$('<span id="spantxt_ShiftOut2" class="help-block">Second Shift Out can not be Empty</span>').insertAfter('#txt_ShiftOut2');
						}
					}
				} else if ($('#txt_Request').val() == 'Biometric issue') {
					if ($('#txt_curatnd').val() == 'NA' || $('#txt_curatnd').val() == null || $('#txt_curatnd').val() == undefined) {
						validate = 1;
						$('#txt_curatnd').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_curatnd').size() == 0) {
							$('<span id="spantxt_curatnd" class="help-block">Current Attendance can not be Empty</span>').insertAfter('#txt_curatnd');
						}
					}
					if ($('#txt_updateatnd').val() == 'NA' || $('#txt_updateatnd').val() == null || $('#txt_updateatnd').val() == undefined) {
						validate = 1;
						$('#txt_updateatnd').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#spantxt_updateatnd').size() == 0) {
							$('<span id="spantxt_updateatnd" class="help-block">Updated Attendance can not be Empty</span>').insertAfter('#txt_updateatnd');
						}
					}
					if ($('#txt_descheck').val() == 1) {
						if ($('#txt_access_in').val() == 'NA' || $('#txt_access_in').val() == null || $('#txt_access_in').val() == undefined || $('#txt_access_in').val() == '') {
							validate = 1;
							$('#txt_access_in').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
							if ($('#spantxt_access_in').size() == 0) {
								$('<span id="spantxt_access_in" class="help-block">Access In Time can not be Empty</span>').insertAfter('#txt_access_in');
							}
						}
						if ($('#txt_access_out').val() == 'NA' || $('#txt_access_out').val() == null || $('#txt_access_out').val() == undefined || $('#txt_access_out').val() == '') {
							validate = 1;
							$('#txt_access_out').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
							if ($('#spantxt_access_out').size() == 0) {
								$('<span id="spantxt_access_out" class="help-block">Access Out Time can not be Empty</span>').insertAfter('#txt_access_out');
							}
						}

					}


				}
			}
			if ($('#txt_DateFrom').val() == '') {
				$('#txt_DateFrom').addClass("has-error");
				if ($('#spantxt_DateFrom').size() == 0) {
					$('<span id="spantxt_DateFrom" class="help-block">Date From can not be Empty</span>').insertAfter('#txt_DateFrom');
				}
				validate = 1;
			}
			if ($('#txt_DateTo').val() == '') {
				$('#txt_DateTo').addClass("has-error");
				if ($('#spantxt_DateTo').size() == 0) {
					$('<span id="spantxt_DateTo" class="help-block">Date To can not be Empty</span>').insertAfter('#txt_DateTo');
				}
				validate = 1;
			}
			if (Date.parse($('#txt_DateTo').val()) < Date.parse($('#txt_DateFrom').val())) {
				$('#txt_DateFrom').addClass('has-error');
				$('#txt_DateTo').addClass('has-error');
				validate = 1;
				$('<span id="spantxt_DateTo" class="help-block">DateTo can not be less then DateFrom</span>').insertAfter('#txt_DateTo');
			}
			if ($('input.check_val_:checked').length > 0) {
				validate = 0;
				alert_msg = '';
			}
			if ($(this).attr('id') != 'btn_Leave_Save') {
				if ($('#txt_Comment').val() == '') {
					$('#txt_Comment').addClass("has-error");
					if ($('#spantxt_Comment').size() == 0) {
						$('<span id="spantxt_Comment" class="help-block">Comment can not be Empty</span>').insertAfter('#txt_Comment');
					}
					validate = 1;
				}
			} else {
				if ($('#txt_common_comment').val() == '') {
					$('#txt_common_comment').addClass("has-error");
					if ($('#spantxt_common_comment').size() == 0) {
						$('<span id="spantxt_common_comment" class="help-block">Comment can not be Empty</span>').insertAfter('#txt_common_comment');
					}
					validate = 1;
					//$('select').formSelect();
				}
			}

			if ($(this).attr('id') == 'btn_Leave_Add') {
				//alert(hidval);
				var availableDates = $("#hiddenval").val();
				if (availableDates.indexOf($('#txt_DateFrom').val()) == -1 || availableDates.indexOf($('#txt_DateTo').val()) == -1) {
					validate = 1;
					toastr.info('Wrong Request Raise...');
				}
			}

			if (validate == 1) {

				$(function() {
					toastr.error(alert_msg)
				});
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");*/

				$('#txt_DateFrom').attr('disabled', 'true');
				$('#txt_DateTo').attr('disabled', 'true');
				return false;
			}

		});
		$("form").submit(function() {
			var validate = 0;
			var alert_msg = '';
			var $btn = $(document.activeElement);
			if ($btn.is("#btn_srch_submit")) {

				return true;
			}
			$('#txt_Request').closest('div').removeClass('has-error');
			$('#txt_DateFrom').closest('div').removeClass('has-error');
			$('#txt_DateTo').closest('div').removeClass('has-error');
			$('#txt_Comment').closest('div').removeClass('has-error');

			$('#txt_LeaveType').closest('div').removeClass('has-error');
			$('#txt_ShiftIn').closest('div').removeClass('has-error');
			$('#txt_ShiftOut').closest('div').removeClass('has-error');

			$('#txt_curatnd').closest('div').removeClass('has-error');
			$('#txt_updateatnd').closest('div').removeClass('has-error');

			$('#txt_access_in').closest('div').removeClass('has-error');
			$('#txt_access_out').closest('div').removeClass('has-error');


			if ($(this).attr('id') == 'btn_Leave_Save') {

				if ($('#txtID').val() == '') {
					validate = 1;
					alert_msg += '<li> Request ID can not be Empty ,Please Select First </li>';
				}
			}
			if ($('#txt_Request').val() == 'NA') {
				$('#txt_Request').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> Request Exception can not be Empty </li>';
			} else {
				if ($('#txt_Request').val() == 'Back Dated Leave') {
					if ($('#txt_LeaveType').val() == 'NA') {
						$('#txt_LeaveType').closest('div').addClass('has-error');
						validate = 1;
						alert_msg += '<li> LeaveType can not be Empty </li>';
					}

				} else if ($('#txt_Request').val() == 'Roster Change' || $('#txt_Request').val() == 'Shift Change' || $('#txt_Request').val() == 'Working on WeekOff' || $('#txt_Request').val() == 'Working on Leave') {
					if ($('#txt_ShiftIn').val() == 'NA') {
						$('#txt_ShiftIn').closest('div').addClass('has-error');
						validate = 1;
						alert_msg += '<li> Shift IN can not be Empty </li>';
					}
					if ($('#txt_ShiftOut').val() == 'NA') {
						$('#txt_ShiftOut').closest('div').addClass('has-error');
						validate = 1;
						alert_msg += '<li> Shift Out can not be Empty </li>';
					}
				} else if ($('#txt_Request').val() == 'Biometric issue') {
					if ($('#txt_curatnd').val() == 'NA' || $('#txt_curatnd').val() == null || $('#txt_curatnd').val() == undefined || $('#txt_curatnd').val() == '') {
						$('#txt_curatnd').closest('div').addClass('has-error');
						validate = 1;
						alert_msg += '<li> Current Attendance can not be Empty </li>';
					}
					if ($('#txt_updateatnd').val() == 'NA' || $('#txt_updateatnd').val() == null || $('#txt_updateatnd').val() == undefined || $('#txt_curatnd').val() == '') {
						$('#txt_updateatnd').closest('div').addClass('has-error');
						validate = 1;
						alert_msg += '<li> Updated Attendance can not be Empty </li>';
					}
					if ($('#txt_descheck').val() == 1) {
						if ($('#txt_access_in').val() == 'NA' || $('#txt_access_in').val() == null || $('#txt_access_in').val() == undefined || $('#txt_access_in').val() == '') {
							$('#txt_access_in').closest('div').addClass('has-error');
							validate = 1;
							alert_msg += '<li> Access In Time can not be Empty </li>';
						}
						if ($('#txt_access_out').val() == 'NA' || $('#txt_access_out').val() == null || $('#txt_access_out').val() == undefined || $('#txt_access_out').val() == '') {
							$('#txt_access_out').closest('div').addClass('has-error');
							validate = 1;
							alert_msg += '<li> Updated Attendance can not be Empty </li>';
						}
					}

				}
			}
			if ($('#txt_DateFrom').val() == '') {
				$('#txt_DateFrom').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> DateFrom can not be Empty </li>';
			}
			if ($('#txt_DateTo').val() == '') {
				$('#txt_DateTo').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> DateTo can not be Empty </li>';
			}

			if (Date.parse($('#txt_DateTo').val()) < Date.parse($('#txt_DateFrom').val())) {
				$('#txt_DateFrom').closest('div').addClass('has-error');
				$('#txt_DateTo').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> DateTo can not be less then DateFrom Empty </li>';
			}
			if ($('input.check_val_:checked').length > 0) {
				validate = 0;
				alert_msg = '';
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				$('select').formSelect();
				return false;
			} else {
				$('#btn_Leave_Add,#btn_Leave_Save').addClass('hidden');
				$('input,select,textarea').prop("disabled", false);
				$('input,select,textarea').removeAttr("disabled");
			}
		});
		/*$('#search_field,#comment_box,#commentSection').accordion({
	      collapsible: true,
			      heightStyle: "content" 
	    });*/
		$('#txt_ShiftIn').change(function() {
			if ($('#txt_DateFrom').val() == '') {

				$('#txt_ShiftIn').val('NA');
				$('#txt_ShiftOut').val('NA');
				$('select').formSelect();
				return false;
			}

			if ($('#txt_ShiftIn2').val() != 'NA' && (Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn').val()) >= Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val()))) {
				alert('Fist shift always start first')
				$('#txt_ShiftIn').val('NA');
				$('#txt_ShiftOut').val('NA');
				$('select').formSelect();
				return false;
			}
			if ($('#txt_ShiftIn').val() == 'NA') {

				$('#txt_ShiftIn').val('NA');
				$('#txt_ShiftOut').val('NA');
				return false;
			}
			$.ajax({
				url: "../Controller/getRosterType.php?EmpID=" + $('#txtEmpID').val() + '&Date=' + $('#txt_DateFrom').val(),
				success: function(result21) {

					if (result21 == 2) {
						$('#txtShifDiff').val('11');
					} else if (result21 == 3) {
						$('#txtShifDiff').val('5');
					} else if (result21 == 4) {

						$('#txtShifDiff').val('4:30');
					} else {
						$('#txtShifDiff').val('9');
					}
					if ($("#txt_Request").val() == 'Roster Change' || $("#txt_Request").val() == 'Shift Change') {
						$.ajax({
							url: "../Controller/getRosterData.php?EmpID=" + $('#txtEmpID').val() + '&Date=' + $('#txt_DateFrom').val(),
							success: function(result) {
								var arr = result.split('|');
								var d2 = new Date($('#txt_DateFrom').val() + ' ' + arr[0]);
								var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn').val());


								var seconds = Math.abs((d2 - d1) / 1000);

								<?php
								if ($_SESSION["__user_subprocess"] ==  'Information Technology') {
								?>

									if (parseInt(seconds) < 1800 || $('#txt_DateFrom').val() == '') {
										alert('Requested Shift should have difference of half hour from roster shift');
										//$('#txt_ShiftIn').val(result);
										$('#txt_ShiftIn').val('NA');
										$('#txt_ShiftOut').val('NA');

									}
								<?php
								} else {
								?>
									if (parseInt(seconds) < 3600 || $('#txt_DateFrom').val() == '') {
										alert('Requested Shift should have difference of one hour from roster shift');
										//$('#txt_ShiftIn').val(result);
										$('#txt_ShiftIn').val('NA');
										$('#txt_ShiftOut').val('NA');

									}
								<?php
								}


								?>

								if ($('#txt_ShiftIn').val() == "WO") {
									$("#txt_ShiftOut").val("WO");
									//ddlShiftOut.Enabled = false;
								} else if ($("#txt_ShiftIn option:selected").index() == 0) {
									$("#txt_ShiftOut option:selected").index(0);
								} else if ($("#txt_ShiftIn option:selected").index() != 0) {
									if ($('#txt_ShiftIn').val() == "00:00") {
										$('#txt_ShiftIn').val("00:01");
									}

									var time = $('#txt_ShiftIn').val();
									var startTime = new Date();
									var parts = time.match(/(\d+):(\d+)/);
									if (parts) {
										var hours = parseInt(parts[1]),
											minutes = parseInt(parts[2])

										startTime.setHours(hours, minutes, 0, 0);
									}

									startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes() + 30, 0, 0);

									var minute = '00';
									if (startTime.getMinutes() < 10) {
										minute = '0' + startTime.getMinutes();
									} else {
										minute = startTime.getMinutes();
									}
									//alert(startTime.getHours() + ':' + minute);
									$('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);


								}

								if ($('#txt_ShiftIn').val() != 'NA' && $('#txt_ShiftIn2').val() != 'NA') {

									var d2 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val());
									var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut').val());

									var seconds = Math.abs((d2 - d1) / 1000);
									//alert(parseInt(seconds));
									if (parseInt(seconds) < 14400) {
										alert('Difference of 4 hour in both shift are mandatory');
										$('#txt_ShiftIn').val('NA');
										$('#txt_ShiftOut').val('NA');
									}
								}
								$('select').formSelect();
							}
						});

					} else {
						if ($('#txt_ShiftIn').val() == "WO") {
							$("#txt_ShiftOut").val("WO");
							//ddlShiftOut.Enabled = false;
						} else if ($("#txt_ShiftIn option:selected").index() == 0) {
							$("#txt_ShiftOut option:selected").index(0);
						} else if ($("#txt_ShiftIn option:selected").index() != 0) {
							if ($('#txt_ShiftIn').val() == "00:00") {
								$('#txt_ShiftIn').val("00:01");
							}

							var time = $('#txt_ShiftIn').val();

							var startTime = new Date();
							var parts = time.match(/(\d+):(\d+)/);
							if (parts) {
								var hours = parseInt(parts[1]),
									minutes = parseInt(parts[2])

								startTime.setHours(hours, minutes, 0, 0);
							}

							startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes() + 30, 0, 0);

							var minute = '00';
							if (startTime.getMinutes() < 10) {
								minute = '0' + startTime.getMinutes();
							} else {
								minute = startTime.getMinutes();
							}
							//alert(startTime.getHours() + ':' + minute);
							$('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);


						}

						if ($('#txt_ShiftIn').val() != 'NA' && $('#txt_ShiftIn2').val() != 'NA') {

							var d2 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val());
							var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut').val());

							var seconds = Math.abs((d2 - d1) / 1000);
							//alert(parseInt(seconds));
							if (parseInt(seconds) < 14400) {
								alert('Difference of 4 hour in both shift are mandatory');
								$('#txt_ShiftIn').val('NA');
								$('#txt_ShiftOut').val('NA');
							}
						}

					}


					$('select').formSelect();
				}
			});


			/* if($("#txt_Request").val() == 'Roster Change' || $("#txt_Request").val() == 'Shift Change')
	        {
				 $.ajax({url: "../Controller/getRosterData.php?EmpID="+$('#txtEmpID').val() + '&Date='+$('#txt_DateFrom').val(), success: function(result){
				 	var d2 = new Date($('#txt_DateFrom').val()+' '+result);
					var d1 = new Date($('#txt_DateFrom').val()+' '+$('#txt_ShiftIn').val());
				 	 //alert($('#txt_DateFrom').val());

						var seconds =  Math.abs((d2- d1)/1000);
				 	if(parseInt(seconds) < 7200 || $('#txt_DateFrom').val() == '')
				 	{
				 		alert('Requested Shift should have difference of two hour from roster shift');
						//$('#txt_ShiftIn').val(result);
						$('#txt_ShiftIn').val('NA');
						$('#txt_ShiftOut').val('NA');
						
					}
					 
					    if($('#txt_ShiftIn').val() == "WO")
				        {
				            $("#txt_ShiftOut").val("WO");
				            //ddlShiftOut.Enabled = false;
				        }
				        else if($("#txt_ShiftIn option:selected").index() == 0)
				        {
				            $("#txt_ShiftOut option:selected").index(0);
				        }

				        else if ($("#txt_ShiftIn option:selected").index() != 0)
				        {
				        	if($('#txt_ShiftIn').val() =="00:00")
				        	{
								$('#txt_ShiftIn').val("00:01");
							}
							
				            var time = $('#txt_ShiftIn').val();
							var startTime = new Date();
							var parts = time.match(/(\d+):(\d+)/);
							if (parts) {
							    var hours = parseInt(parts[1]),
							        minutes = parseInt(parts[2])
							  
							    startTime.setHours(hours, minutes, 0, 0);
							}
							
							startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
							
							var minute ='00';
							if(startTime.getMinutes() < 10)
							{
								minute = '0' +startTime.getMinutes();
							} 
							else
							{
								minute =startTime.getMinutes();
							}
							//alert(startTime.getHours() + ':' + minute);
						    $('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);
						    
						    
				        }
				 }});
       
			}
			*/
			/*else
			{
				if($('#txt_ShiftIn').val() == "WO")
		        {
		            $("#txt_ShiftOut").val("WO");
		            //ddlShiftOut.Enabled = false;
		        }
		        else if($("#txt_ShiftIn option:selected").index() == 0)
		        {
		            $("#txt_ShiftOut option:selected").index(0);
		        }

		        else if ($("#txt_ShiftIn option:selected").index() != 0)
		        {
		        	if($('#txt_ShiftIn').val() =="00:00")
		        	{
						$('#txt_ShiftIn').val("00:01");
					}
					
		            var time = $('#txt_ShiftIn').val();
					var startTime = new Date();
					var parts = time.match(/(\d+):(\d+)/);
					if (parts) {
					    var hours = parseInt(parts[1]),
					        minutes = parseInt(parts[2])
					  
					    startTime.setHours(hours, minutes, 0, 0);
					}
					
					startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
					
					var minute ='00';
					if(startTime.getMinutes() < 10)
					{
						minute = '0' +startTime.getMinutes();
					} 
					else
					{
						minute =startTime.getMinutes();
					}
					//alert(startTime.getHours() + ':' + minute);
				    $('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);
				    
				    
		        }
			}
			*/


			$('select').formSelect();

		});

		$('#txt_ShiftIn2').change(function() {

			if ($('#txt_DateFrom').val() == '') {

				$('#txt_ShiftIn2').val('NA');
				$('select').formSelect();
				return false;

			}

			if ($('#txt_ShiftIn').val() != 'NA' && (Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn').val()) >= Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val()))) {
				alert('Fist shift always start first')
				$('#txt_ShiftIn2').val('NA');
				$('#txt_ShiftOut2').val('NA');
				$('select').formSelect();
				return false;
			}



			if ($('#txt_ShiftIn2').val() == 'NA') {

				$('#txt_ShiftIn2').val('NA');
				$('#txt_ShiftOut2').val('NA');
			}

			$.ajax({
				url: "../Controller/getRosterType.php?EmpID=" + $('#txtEmpID').val() + '&Date=' + $('#txt_DateFrom').val(),
				success: function(result21) {

					if (result21 == 2) {
						$('#txtShifDiff').val('11');
					} else if (result21 == 3) {
						$('#txtShifDiff').val('5');
					} else if (result21 == 4) {

						$('#txtShifDiff').val('4:30');
					} else {
						$('#txtShifDiff').val('9');
					}




					if ($("#txt_Request").val() == 'Roster Change' || $("#txt_Request").val() == 'Shift Change') {
						$.ajax({
							url: "../Controller/getRosterData1.php?EmpID=" + $('#txtEmpID').val() + '&Date=' + $('#txt_DateFrom').val(),
							success: function(result) {

								var arr = result.split('|');
								var d2 = new Date($('#txt_DateFrom').val() + ' ' + arr[0]);
								var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val());
								//alert($('#txt_DateFrom').val());

								var seconds = Math.abs((d2 - d1) / 1000);

								<?php
								if (clean($_SESSION["__user_subprocess"]) ==  'Information Technology') {
								?>
									if (parseInt(seconds) < 1800 || $('#txt_DateFrom').val() == '') {
										alert('Requested Shift should have difference of half hour from roster shift');
										//$('#txt_ShiftIn').val(result);
										$('#txt_ShiftIn2').val('NA');
										$('#txt_ShiftOut2').val('NA');

									}
								<?php
								} else {
								?>
									if (parseInt(seconds) < 3600 || $('#txt_DateFrom').val() == '') {
										alert('Requested Shift should have difference of one hour from roster shift');
										//$('#txt_ShiftIn').val(result);
										$('#txt_ShiftIn2').val('NA');
										$('#txt_ShiftOut2').val('NA');

									}
								<?php
								}


								?>

								if ($('#txt_ShiftIn2').val() == "WO") {
									$("#txt_ShiftOut2").val("WO");
									//ddlShiftOut.Enabled = false;
								} else if ($("#txt_ShiftIn2 option:selected").index() == 0) {
									$("#txt_ShiftOut2 option:selected").index(0);
								} else if ($("#txt_ShiftIn2 option:selected").index() != 0) {
									if ($('#txt_ShiftIn2').val() == "00:00") {
										$('#txt_ShiftIn2').val("00:01");
									}

									var time = $('#txt_ShiftIn2').val();
									var startTime = new Date();
									var parts = time.match(/(\d+):(\d+)/);
									if (parts) {
										var hours = parseInt(parts[1]),
											minutes = parseInt(parts[2])

										startTime.setHours(hours, minutes, 0, 0);
									}

									startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes() + 30, 0, 0);

									var minute = '00';
									if (startTime.getMinutes() < 10) {
										minute = '0' + startTime.getMinutes();
									} else {
										minute = startTime.getMinutes();
									}
									//alert(startTime.getHours() + ':' + minute);
									$('#txt_ShiftOut2').val(startTime.getHours() + ':' + minute);


								}


								if ($('#txt_ShiftIn').val() != 'NA' && $('#txt_ShiftIn2').val() != 'NA') {

									var d2 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val());
									var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut').val());

									var seconds = Math.abs((d2 - d1) / 1000);
									//alert(parseInt(seconds));
									if (parseInt(seconds) < 14400) {
										alert('Difference of 4 hour in both shift are mandatory');
										$('#txt_ShiftIn2').val('NA');
										$('#txt_ShiftOut2').val('NA');
									}
								}

								if ($('#txt_ShiftIn2').val() != 'NA') {

									//alert(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()));
									//alert(Date.parse($('#txt_DateFrom').val() +' '+'13:00'));
									if (Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut2').val()) < Date.parse($('#txt_DateFrom').val() + ' ' + '13:00') || $('#txt_ShiftOut2').val() == '0:00') {
										alert('Second shift is not correct');

										$('#txt_ShiftIn2').val('NA');
										$('#txt_ShiftOut2').val('NA');
										return false;
									}
									$('select').formSelect();
								}

							}
						});

					} else {
						if ($('#txt_ShiftIn2').val() == "WO") {
							$("#txt_ShiftOut2").val("WO");
							//ddlShiftOut.Enabled = false;
						} else if ($("#txt_ShiftIn2 option:selected").index() == 0) {
							$("#txt_ShiftOut2 option:selected").index(0);
						} else if ($("#txt_ShiftIn2 option:selected").index() != 0) {
							if ($('#txt_ShiftIn2').val() == "00:00") {
								$('#txt_ShiftIn2').val("00:01");
							}

							var time = $('#txt_ShiftIn2').val();

							var startTime = new Date();
							var parts = time.match(/(\d+):(\d+)/);
							if (parts) {
								var hours = parseInt(parts[1]),
									minutes = parseInt(parts[2])

								startTime.setHours(hours, minutes, 0, 0);
							}

							startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes() + 30, 0, 0);

							var minute = '00';
							if (startTime.getMinutes() < 10) {
								minute = '0' + startTime.getMinutes();
							} else {
								minute = startTime.getMinutes();
							}
							//alert(startTime.getHours() + ':' + minute);
							$('#txt_ShiftOut2').val(startTime.getHours() + ':' + minute);


						}

						if ($('#txt_ShiftIn').val() != 'NA' && $('#txt_ShiftIn2').val() != 'NA') {

							var d2 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftIn2').val());
							var d1 = new Date($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut').val());

							var seconds = Math.abs((d2 - d1) / 1000);
							//alert(parseInt(seconds));
							if (parseInt(seconds) < 14400) {
								alert('Difference of 4 hour in both shift are mandatory');
								$('#txt_ShiftIn2').val('NA');
								$('#txt_ShiftOut2').val('NA');
							}
						}

						if ($('#txt_ShiftIn2').val() != 'NA') {

							//alert(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()));
							//alert(Date.parse($('#txt_DateFrom').val() +' '+'13:00'));
							if (Date.parse($('#txt_DateFrom').val() + ' ' + $('#txt_ShiftOut2').val()) < Date.parse($('#txt_DateFrom').val() + ' ' + '13:00') || $('#txt_ShiftOut2').val() == '0:00') {
								alert('Second shift is not correct');

								$('#txt_ShiftIn2').val('NA');
								$('#txt_ShiftOut2').val('NA');
								return false;
							}
						}

						$('select').formSelect();
					}




				}
			});






			/*if($("#txt_Request").val() == 'Roster Change' || $("#txt_Request").val() == 'Shift Change')
	        {
				 $.ajax({url: "../Controller/getRosterData.php?EmpID="+$('#txtEmpID').val() + '&Date='+$('#txt_DateFrom').val(), success: function(result){
				 	var d2 = new Date($('#txt_DateFrom').val()+' '+result);
					var d1 = new Date($('#txt_DateFrom').val()+' '+$('#txt_ShiftIn2').val());
				 	 //alert($('#txt_DateFrom').val());

						var seconds =  Math.abs((d2- d1)/1000);
				 	if(parseInt(seconds) < 7200 || $('#txt_DateFrom').val() == '')
				 	{
				 		alert('Requested Shift should have difference of two hour from roster shift');
						//$('#txt_ShiftIn2').val(result);
						$('#txt_ShiftIn2').val('NA');
						$('#txt_ShiftOut2').val('NA');
						
					}
					 
					    if($('#txt_ShiftIn2').val() == "WO")
				        {
				            $("#txt_ShiftOut2").val("WO");
				            //ddlShiftOut.Enabled = false;
				        }
				        else if($("#txt_ShiftIn2 option:selected").index() == 0)
				        {
				            $("#txt_ShiftOut2 option:selected").index(0);
				        }

				        else if ($("#txt_ShiftIn2 option:selected").index() != 0)
				        {
				        	if($('#txt_ShiftIn2').val() =="00:00")
				        	{
								$('#txt_ShiftIn2').val("00:01");
							}
							
				            var time = $('#txt_ShiftIn2').val();
							var startTime = new Date();
							var parts = time.match(/(\d+):(\d+)/);
							if (parts) {
							    var hours = parseInt(parts[1]),
							        minutes = parseInt(parts[2])
							  
							    startTime.setHours(hours, minutes, 0, 0);
							}
							
							startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
							
							var minute ='00';
							if(startTime.getMinutes() < 10)
							{
								minute = '0' +startTime.getMinutes();
							} 
							else
							{
								minute =startTime.getMinutes();
							}
							//alert(startTime.getHours() + ':' + minute);
						    $('#txt_ShiftOut2').val(startTime.getHours() + ':' + minute);
						    
						    
				        }
				 }});
       
			}
			*/
			/*else
			{
				if($('#txt_ShiftIn2').val() == "WO")
		        {
		            $("#txt_ShiftOut2").val("WO");
		            //ddlShiftOut.Enabled = false;
		        }
		        else if($("#txt_ShiftIn2 option:selected").index() == 0)
		        {
		            $("#txt_ShiftOut2 option:selected").index(0);
		        }

		        else if ($("#txt_ShiftIn2 option:selected").index() != 0)
		        {
		        	if($('#txt_ShiftIn2').val() =="00:00")
		        	{
						$('#txt_ShiftIn2').val("00:01");
					}
					
		            var time = $('#txt_ShiftIn2').val();
					var startTime = new Date();
					var parts = time.match(/(\d+):(\d+)/);
					if (parts) {
					    var hours = parseInt(parts[1]),
					        minutes = parseInt(parts[2])
					  
					    startTime.setHours(hours, minutes, 0, 0);
					}
					
					startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
					
					var minute ='00';
					if(startTime.getMinutes() < 10)
					{
						minute = '0' +startTime.getMinutes();
					} 
					else
					{
						minute =startTime.getMinutes();
					}
					//alert(startTime.getHours() + ':' + minute);
				    $('#txt_ShiftOut2').val(startTime.getHours() + ':' + minute);
				    
				    
		        }
			
						//alert($('#txt_ShiftOut2').val());
	       
				       if($('#txt_ShiftIn2').val() != 'NA')
						{
							
							//alert(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()));
							//alert(Date.parse($('#txt_DateFrom').val() +' '+'13:00'));
							if(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()) < Date.parse($('#txt_DateFrom').val() +' '+'13:00') || $('#txt_ShiftOut2').val()=='0:00')
							{
								alert('Second shift is not correct');
								
								$('#txt_ShiftIn2').val('NA');
								$('#txt_ShiftOut2').val('NA');
								return false;
							}
						}
						
			}
			*/



			//alert($('#txt_ShiftOut2').val());

			/*if($('#txt_ShiftIn2').val() != 'NA')
			{
				
				//alert(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()));
				//alert(Date.parse($('#txt_DateFrom').val() +' '+'13:00'));
				if(Date.parse($('#txt_DateFrom').val() +' '+$('#txt_ShiftOut2').val()) < Date.parse($('#txt_DateFrom').val() +' '+'13:00') || $('#txt_ShiftOut2').val()=='0:00')
				{
					alert('Second shift is not correct');
					
					$('#txt_ShiftIn2').val('NA');
					$('#txt_ShiftOut2').val('NA');
					return false;
				}
			}*/

			$('select').formSelect();
		});

		$('#txt_Request').change(function() {


			$("#app_link").html('');
			$('#backleave').addClass('hidden');
			$('#shif_div1').addClass('hidden');
			$('#shif_div2').addClass('hidden');
			$('#shif_div11').addClass('hidden');
			$('#shif_div22').addClass('hidden');
			$('#attendance_div1').addClass('hidden');
			$('#attendance_div2').addClass('hidden');
			$('#access_div1').addClass('hidden');
			$('#access_div2').addClass('hidden');

			$('#super_div_hr').addClass('hidden');
			$('#super_div1').addClass('hidden');
			$('#super_div2').addClass('hidden');
			$('#super_div').addClass('hidden');
			$('#sitehead_hr').addClass('hidden');
			$('#sitehead_div1').addClass('hidden');
			$('#sitehead_div2').addClass('hidden');
			$('#sitehead_div').addClass('hidden');

			$('#txt_DateFrom').val('');
			$('#txt_DateTo').val('');
			$('#txt_LeaveType').val('NA');
			$('#txt_ShiftIn').val('NA');
			$('#txt_ShiftOut').val('NA');
			$('#txt_curatnd').val('NA');
			$('#txt_updateatnd').val('NA');

			$('#txt_access_in').val('');
			$('#txt_access_out').val('');

			$('#txt_Comment').val('');
			$('#txt_Comment_sh').val('');
			$('#txt_Comment_sp').val('');


			$('#user_div').removeClass('hidden');
			$('#txtEmpName').val($('#txtEmpName1').val());
			$('#txtEmpID').val($('#txtEmpID1').val());
			$('#txt_DateFrom').attr('disabled', 'true');
			$('#txt_DateTo').attr('disabled', 'true');


			if ($('#txtID').val() != '') {
				$('#user_div').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#super_div').addClass('hidden');
			}
			if ($(this).val() == 'Back Dated Leave') {
				$('#backleave').removeClass('hidden');

			} else if ($(this).val() == 'Roster Change' || $(this).val() == 'Shift Change' || $(this).val() == 'Working on WeekOff' || $(this).val() == 'Working on Holiday' || $(this).val() == 'Working on Leave') {
				$('#shif_div1').removeClass('hidden');
				$('#shif_div2').removeClass('hidden');
				$('#shif_div11').removeClass('hidden');
				$('#shif_div22').removeClass('hidden');
			} else if ($(this).val() == 'Biometric issue') {


				$('#attendance_div1').removeClass('hidden');
				$('#attendance_div2').removeClass('hidden');
				if ($('#txt_descheck').val() == 1) {
					$('#access_div1').removeClass('hidden');
					$('#access_div2').removeClass('hidden');

				}


			}


			bindate($(this).val());

			$.ajax({
				url: "../Controller/cappingDate.php?EmpID=" + $('#txtEmpID').val() + '&Exception=' + $(this).val(),
				success: function(result) {
					$("#hiddenval").val(result);

					binddate1(result.trim());
					$('select').formSelect();
				}
			});
			$('select').formSelect();
		});
		$('#txt_curatnd').change(function() {
			$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option>');
			if ($(this).val() == 'H') {
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
			} else if ($(this).val() == 'L') {
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
			} else if ($(this).val() == 'LWP') {
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
			} else if ($(this).val() == 'A') {
				<?php

				$myDB = new MysqliDb();
				$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "' . clean($_SESSION['__user_logid']) . '" and DateOn ="' . date('Y-m-d', time()) . '" order by id desc limit 1');
				if (count($rst) > 0) {

					if (intval($rst[0]['type_']) != 3) {
				?>
						$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
					<?php
					} else {
					?>
						$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
					<?php
					}
				} else {
					?>
					$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
				<?php
				}
				?>


			} else if ($(this).val() == 'HWP') {
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
			}
			$('select').formSelect();
		});
		getCalforsrch();

	});

	function getCalforsrch() {

		var currentTime = new Date();
		var minDate = '-2M';
		var maxDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 2, 0); // one day before next month
		var firstDay = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
		var lastDay = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);

		function getFormattedDate(date) {
			var year = date.getFullYear();
			var month = (1 + date.getMonth()).toString();
			month = month.length > 1 ? month : '0' + month;
			var day = date.getDate().toString();
			day = day.length > 1 ? day : '0' + day;
			return month + '/' + day + '/' + year;

		}

		$("#txt_srch_DateTo").datepicker({
			minDate: minDate,
			maxDate: maxDate,
			dateFormat: 'yy-mm-dd',

			onSelect: function(dateStr) {
				var max = $(this).datepicker('getDate'); // Get selected date
				var start = $("#txt_srch_DateFrom").datepicker("getDate");

				var end = $("#txt_srch_DateTo").datepicker("getDate");

				if (start != null) {
					var days = (end - start) / (1000 * 60 * 60 * 24);
					//$("#ctl00_ContentPlaceHolder1_txtdays").val(days + 1);



					if (days < 0) {

						alert("To Date should be greater then From Date");
						$("#txt_srch_DateTo").val('');
						return false;
					}
				} else {
					alert("Select From Date First...");
					$("#txt_srch_DateTo").val('');
				}

			}
		});
		$("#txt_srch_DateFrom").datepicker({
			minDate: minDate,
			maxDate: maxDate,
			dateFormat: 'yy-mm-dd'
		});
	}

	function bindate(el) {

		$('#txt_DateFrom').datepicker("destroy");
		$('#txt_DateTo').datepicker("destroy");
		$('#txt_DateFrom').prop('disabled', false);
		$('#txt_DateTo').prop('disabled', false);
		$('#txt_DateFrom').attr('readonly', 'true');
		$('#txt_DateTo').attr('readonly', 'true');
		var txtDays = '';
		//$('#ctl00_ContentPlaceHolder1_txtsdate').removeAttr('disabled');
		//$('#ctl00_ContentPlaceHolder1_txtedate').removeAttr('disabled');

		//              $("#ctl00_ContentPlaceHolder1_txtsdate").val()='';
		//              $("#ctl00_ContentPlaceHolder1_txtedate").val()='';
		var minDate = '-0D';
		var maxDate = '+0D';
		if (el == 'Back Dated Leave' || el == 'Biometric issue') {
			var dt = new Date();

			if (dt.getDate() > 2) {
				//alert(date.getMonth()+'/'+date.getDate()+'/'+date.getYear());
				//alert(dt);
				var mm = dt.getMonth();
				mm = mm + 1;

				minDate = '0' + mm + '/' + '1' + '/' + dt.getFullYear();
				maxDate = '-1D';
				// alert(minDate);


			} else {
				minDate = '-2D';
				maxDate = '-1D';
			}

		} else if (el == 'Working on Holiday' || el == 'Working on WeekOff') {
			var dt = new Date();

			if (dt.getDate() > 2) {
				//alert(date.getMonth()+'/'+date.getDate()+'/'+date.getYear());
				//alert(dt);
				var mm = dt.getMonth();
				mm = mm + 1;

				minDate = '0' + mm + '/' + '1' + '/' + dt.getFullYear();
				maxDate = '-0D';
				// alert(minDate);


			} else {
				minDate = '-2D';
				maxDate = '-0D';
			}

		}

		//              else if(el=='Biometric issue' || el=='Working on Holiday' || el=='Working on WeekOff')
		//              {
		//                    minDate='-1D';
		//                    maxDate = '0D';
		//                    
		//              }
		else if (el == 'Roster Change') {
			minDate = '+1D';
			maxDate = '+10D';

		} else if (el == 'Shift Change') {
			minDate = '+1D';
			maxDate = '-0D';

		} else if (el == 'NA' || el == 0) {
			$('#txt_DateFrom').attr('disabled', 'true');
			$('#txt_DateTo').attr('disabled', 'true');


		}
		if (el == 'Shift Change') {
			minDate = '+1D';
			maxDate = '-0D';

		} else {
			var minDate = '+1D';
			var maxDate = '-1D';

		}


		$('#txt_DateTo').datepicker({

			minDate: minDate,
			//maxDate: '+0Y+2M',
			maxDate: maxDate,
			dateFormat: 'yy-mm-dd',

			onSelect: function(dateStr) {
				var max = $('#txt_DateTo').datepicker('getDate'); // Get selected date
				var start = $("#txt_DateFrom").datepicker("getDate");
				var end = $("#txt_DateTo").datepicker("getDate");

				if (start != null) {
					var days = (end - start) / (1000 * 60 * 60 * 24);
					txtDays = days + 1;



					if (days < 0) {

						alert("To Date should be greater then From Date");
						$("#txt_DateTo").val('');
						return false;
					}
				} else {
					alert("Select From Date First...");
					$("#txt_DateTo").val('');
				}

			}
		});

		$('#txt_DateFrom').datepicker({
			minDate: minDate,
			maxDate: maxDate,
			dateFormat: 'yy-mm-dd'
		});

	}

	function binddate1(eldate) {

		$('#txt_DateFrom').attr('readonly', 'true');
		$('#txt_DateTo').attr('readonly', 'true');
		$('#txt_DateFrom').datepicker("destroy");
		$('#txt_DateTo').datepicker("destroy");
		var availableDates = eldate.split(',');
		//alert($.inArray('6/26/2016', availableDates));

		function available(date) {

			var d = date.getDate();
			var m = (date.getMonth() + 1);
			var y = date.getFullYear();
			if (d <= 9) {
				d = '0' + d;
			}
			if (m <= 9) {
				m = '0' + m;
			}
			dmy = y + '-' + m + '-' + d;

			if ($.inArray(dmy, availableDates) != -1) {
				return [true, "", "Available"];
			} else {
				return [false, "", "unAvailable"];
			}
		}

		$('#txt_DateTo,#txt_DateFrom').datepicker({
			beforeShowDay: available,
			minDate: '-30D',
			maxDate: '+7D',
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateStr) {

				$.ajax({
					url: "../Controller/getRosterType.php?EmpID=" + $('#txtEmpID').val() + '&Date=' + dateStr,
					success: function(result) {

						if (result == 2) {
							$('#txtShifDiff').val('11');
						} else {
							$('#txtShifDiff').val('9');
						}
					}
				});

				if ($('#txt_Request').val() == 'Biometric issue' || $('#txt_Request').val() == 'Working on Leave')
				//|| $('#txt_Request').val() == 'Shift Change' || $('#txt_Request').val() == 'Working on WeekOff'//
				{
					$("#txt_DateTo").attr('disabled', true);
					$("#txt_DateTo").val($("#txt_DateFrom").val());
				} else {
					$("#txt_DateTo").removeAttr('disabled');
				}

			}
		});


	}


	function EditData(el) {

		$('#comment_box').addClass('hidden');
		$('#btn_Leave_Add').addClass('hidden');
		$('#btn_Leave_Save').addClass('hidden');
		$item = $(el);
		$.ajax({
			url: "../Controller/getComment.php?ID=" + $item.attr("Data-ID"),
			success: function(result) {

				if (result != '') {

					$('#comment_box').removeClass('hidden');
					$('#comment_container').empty().append(result);
					/*$('#comment_box,#commentSection').accordion({
			      collapsible: true,
			      heightStyle: "content" 
			    });*/
				}
			}
		});
		$('#user_div').addClass('hidden');
		$('#txtID').val($item.attr("Data-ID"));
		$('select').formSelect();
		//alert('hii');
		$.ajax({
			url: "../Controller/getDataForRequest.php?ID=" + $item.attr("Data-ID"),
			success: function(result) {

				if (result != '') {

					var Data = result.split('|$|');


					var Exception = Data[2];
					var DateFrom = Data[3];
					var DateTo = Data[4];
					var EmployeeID = Data[1];
					var EmployeeName = Data[10];
					var AccountHead = Data[16];
					var AccountHeadName = Data[17];
					var CenterHead = 'CE03070003';
					var CenterHeadName = 'SACHIN SIWACH';
					var MgnrStatus = Data[5];
					var HeadStatus = Data[6];
					var StatusHead = Data[12];
					var LeaveType = Data[23];
					var ShiftIn = Data[21];
					var ShiftOut = Data[22];
					var CurrentAtnd = Data[19];
					var UpdateAtnd = Data[20];
					var Mobile = Data[14] + ' / ' + Data[15];
					var ReportTo = Data[24];
					if (Exception != '') {

						$('.clsIDHome').removeClass('hidden');

						$('#txt_Request').val(Exception).attr('disabled', 'true'); //.trigger('change');
						$('#backleave').addClass('hidden');
						$('#shif_div1').addClass('hidden');
						$('#shif_div2').addClass('hidden');
						$('#shif_div11').addClass('hidden');
						$('#shif_div22').addClass('hidden');
						$('#attendance_div1').addClass('hidden');
						$('#attendance_div2').addClass('hidden');
						$('#access_div1').addClass('hidden');
						$('#access_div2').addClass('hidden');

						$('#super_div_hr').addClass('hidden');
						$('#super_div1').addClass('hidden');
						$('#super_div2').addClass('hidden');
						$('#super_div').addClass('hidden');
						$('#sitehead_hr').addClass('hidden');
						$('#sitehead_div1').addClass('hidden');
						$('#sitehead_div2').addClass('hidden');
						$('#sitehead_div').addClass('hidden');

						$('#txt_DateFrom').val('');
						$('#txt_DateTo').val('');
						$('#txt_LeaveType').val('NA');
						$('#txt_ShiftIn').val('NA');
						$('#txt_ShiftOut').val('NA');
						$('#txt_ShiftIn2').val('NA');
						$('#txt_ShiftOut2').val('NA');
						$('#txt_curatnd').val('NA');
						$('#txt_updateatnd').val('NA');

						$('#txt_access_in').val('');
						$('#txt_access_out').val('');


						$('#txt_Comment').val('');
						$('#txt_Comment_sh').val('');
						$('#txt_Comment_sp').val('');


						$('#user_div').removeClass('hidden');
						$('#txt_DateFrom').attr('disabled', 'true');
						$('#txt_DateTo').attr('disabled', 'true');


						if ($('#txtID').val() != '') {
							$('#user_div').addClass('hidden');
							$('#sitehead_div').addClass('hidden');
							$('#super_div').addClass('hidden');
						}
						if (Exception == 'Back Dated Leave') {
							$('#backleave').removeClass('hidden');

						} else if (Exception == 'Roster Change' || Exception == 'Shift Change') {
							$('#shif_div1').removeClass('hidden');
							$('#shif_div2').removeClass('hidden');
							$('#shif_div11').removeClass('hidden');
							$('#shif_div22').removeClass('hidden');
						} else if (Exception == 'Biometric issue') {

							$('#attendance_div1').removeClass('hidden');
							$('#attendance_div2').removeClass('hidden');

							if (LeaveType == 1) {
								$('#access_div1').removeClass('hidden');
								$('#access_div2').removeClass('hidden');
							}
							$('#txt_descheck').val(LeaveType);

						}
						$('#txtEmpID').val(EmployeeID);
						$('#super_div_hr').removeClass('hidden');
						$('#super_div1').removeClass('hidden');
						$('#super_div2').removeClass('hidden');
						$('#super_div').addClass('hidden');
						$('#sitehead_hr').removeClass('hidden');
						$('#sitehead_div1').removeClass('hidden');
						$('#sitehead_div2').removeClass('hidden');
						$('#sitehead_div').addClass('hidden');

						$('#user_div').addClass('hidden');
						var DatFrom = DateFrom.split(' ');
						var DatTo = DateTo.split(' ');
						$('#txt_DateFrom').val(DatFrom[0]);
						$('#txt_DateTo').val(DatTo[0]);

						$.ajax({
							url: "../Controller/cappingDate.php?EmpID=" + EmployeeID + '&Exception=' + Exception,
							success: function(result) {
								$("#hiddenval").val(result);
							}
						});

						if (Exception == 'Back Dated Leave') {
							$('#backleave').removeClass('hidden');
							$('#txt_LeaveType').val(LeaveType);
							if (MgnrStatus != 'Pending') {
								$('#txt_LeaveType').attr('disabled', 'true');
							} else {
								$('#txt_LeaveType').removeAttr('disabled');
							}

						} else if (Exception == 'Roster Change' || Exception == 'Shift Change' || Exception == 'Working on WeekOff' || Exception == 'Working on Holiday' || Exception == 'Working on Leave') {
							$('#shif_div1').removeClass('hidden');
							$('#shif_div2').removeClass('hidden');
							$('#shif_div11').removeClass('hidden');
							$('#shif_div22').removeClass('hidden');

							if (ShiftIn != 'NA') {
								var shift = Data[21].split("|");
								$('#txt_ShiftIn').val(shift[0]);
								$('#txt_ShiftOut').val(shift[1]);
								shift = Data[22].split("|");
								$('#txt_ShiftIn2').val(shift[0]);
								$('#txt_ShiftOut2').val(shift[1]);
							} else {
								$('#txt_ShiftIn').val('NA');
								$('#txt_ShiftOut').val('NA');
								$('#txt_ShiftIn2').val('NA');
								$('#txt_ShiftOut2').val('NA');
							}


							if (MgnrStatus != 'Pending') {
								$('#txt_ShiftIn').attr('disabled', 'true');
								$('#txt_ShiftIn2').attr('disabled', 'true');
							} else {
								$('#txt_ShiftIn').removeAttr('disabled');
								$('#txt_ShiftIn2').removeAttr('disabled');

							}

						} else if (Exception == 'Biometric issue') {
							$('#attendance_div1').removeClass('hidden');
							$('#attendance_div2').removeClass('hidden');
							if (LeaveType == 1) {
								$('#access_div1').removeClass('hidden');
								$('#access_div2').removeClass('hidden');
							}
							$('#txt_descheck').val(LeaveType);

							$('#txt_curatnd').html('<option>' + CurrentAtnd + '</option>');
							$('#txt_curatnd').val(CurrentAtnd).trigger('change');
							$('#txt_updateatnd').html('<option>' + UpdateAtnd + '</option>');
							$('#txt_updateatnd').val(UpdateAtnd);
							$('#txt_access_in').val(ShiftIn);
							$('#txt_access_out').val(ShiftOut);

							if (MgnrStatus != 'Pending') {

								$('#txt_curatnd').attr('disabled', 'true');
								$('#txt_updateatnd').attr('disabled', 'true');
								$('#txt_access_in').attr('readonly', 'true');
								$('#txt_access_out').attr('readonly', 'true');

							} else {
								$('#txt_curatnd').removeAttr('disabled');
								$('#txt_updateatnd').removeAttr('disabled');
								$('#txt_access_in').removeAttr('readonly');
								$('#txt_access_out').removeAttr('readonly');
							}
						}
						$('select').formSelect();

						if ($('#txtEmpID').val() == $('#txtEmpID1').val() && $('#txtEmpID').val() != $('#txtApprovedByID').val()) {

							if (MgnrStatus == 'Pending') {
								$('#super_div_hr').addClass('hidden');
								$('#super_div1').addClass('hidden');
								$('#super_div2').addClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').removeAttr('disabled', 'true');
								$('#txt_DateFrom').removeAttr('disabled', 'true');
								$('#txt_DateTo').removeAttr('disabled', 'true');

								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending' && CenterHead == <?php echo '"' . clean($_SESSION['__user_logid']) . '"'; ?>) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');

							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus != 'Pending' && AccountHead == EmployeeID) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(StatusHead);
								$('#txtApprovedByID').val(ReportTo);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus != 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							}


						} else {

							$('#txtEmpName').val(EmployeeName + '  ( CAll :- ' + Mobile + ')').css('min-width', '70%');
							if (MgnrStatus == 'Pending' && ((AccountHead == <?php echo '"' . clean($_SESSION['__user_logid']) . '"'; ?> && AccountHead != $('#txtEmpID').val()) || (AccountHead == $('#txtEmpID').val() && ReportTo == <?php echo '"' . clean($_SESSION['__user_logid']) . '"'; ?>))) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');

								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txtApprovedBy').val(StatusHead);
								$('#txtApprovedByID').val(ReportTo);
								$('#txtSupervisorApproval').val(MgnrStatus).removeAttr('disabled');
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus == 'Pending' && (AccountHead == <?php echo '"' . clean($_SESSION['__user_logid']) . '"'; ?> && AccountHead == $('#txtEmpID').val())) {
								$('#super_div_hr').addClass('hidden');
								$('#super_div1').addClass('hidden');
								$('#super_div2').addClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').removeAttr('disabled', 'true');
								$('#txt_DateFrom').removeAttr('disabled', 'true');
								$('#txt_DateTo').removeAttr('disabled', 'true');
								//alert(StatusHead);
								$('#txtApprovedBy').val(StatusHead);
								$('#txtApprovedByID').val(ReportTo);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus == 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtSupervisorApproval').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
							} else if (MgnrStatus != 'Pending' && AccountHead == <?php echo '"' . clean($_SESSION['__user_logid']) . '"'; ?>) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txtSupervisorApproval').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending' && AccountHead == EmployeeID) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(StatusHead);
								$('#txtApprovedByID').val(ReportTo);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								if ($('#txtEmpID1').val() == CenterHead) {
									$('#txtSiteHeadApproval').removeAttr('disabled');
									$('#btn_Leave_Save').removeClass('hidden');
								}

							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								if ($('#txtEmpID1').val() == CenterHead) {
									$('#txtSiteHeadApproval').removeAttr('disabled');
									$('#btn_Leave_Save').removeClass('hidden');
								}
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus != 'Pending' && AccountHead == EmployeeID) {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(StatusHead);
								$('#txtApprovedByID').val(ReportTo);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus != 'Pending') {
								$("#app_link1").html('<a onclick="submitform(\'' + EmployeeID + '\',\'' + DatFrom[0] + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('disabled', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
							}
						}

						if (MgnrStatus == 'Approve') {
							//$('#comment_box').addClass('hidden');
							$('#btn_Leave_Add').addClass('hidden');
							$('#btn_Leave_Save').addClass('hidden');
						}
						$('select').formSelect();
					}
					$('select').formSelect();
				}
			}
		});
		$(".check_val_").prop('checked', false);
		$("#chkAll").prop('checked', false);
		$('#txt_common_comment').focus();
		$('select').formSelect();
	}

	function DeleteReq(el) {
		if (confirm("Do you Want to Delete Request")) {
			$item = $(el);
			$.ajax({
				url: "../Controller/deleteRequest.php?ID=" + $item.attr("Data-ID"),
				success: function(result) {

					var data = result.split('|');
					toastr.info(data[1]);
					$('select').formSelect();
					if (data[0] == 'Done') {

						$item.closest('td').parent('tr').remove();
					}
				}
			});
		}
		$('select').formSelect();

	}

	function checkItem_All(el) {
		$('#comment_container').empty().append('');
		$(".check_val_").prop('checked', $(el).prop('checked'));
		if ($('input.check_val_:checked').length == $('input.check_val_').length) {
			$('#txt_Request').val('NA').trigger('change');
			$('#comment_box').removeClass('hidden');
			$('#btn_Leave_Add').addClass('hidden');
			$('#btn_Leave_Save').addClass('hidden');
			$('#user_div').addClass('hidden');
			$('#txtID').val('');
			$('#txtSiteHeadApproval').removeAttr('readonly');
			$('#btn_Leave_Save').removeClass('hidden');
			$('.clsIDHome').addClass('hidden');
			$('#sitehead_div1').removeClass('hidden');
			$('#sitehead_div2').removeClass('hidden');
			$('#sitehead_div').addClass('hidden');
			$('#txtEmpID').val('');
			$('#txtEmpName').val('');
		} else {
			$('#txt_Request').val('NA').trigger('change');
			$('#comment_box').addClass('hidden');
			$('#btn_Leave_Add').removeClass('hidden');
			$('#btn_Leave_Save').addClass('hidden');

			$('#txtID').val('');
			$('#txtSiteHeadApproval').addClass('readonly');
			$('#btn_Leave_Save').addClass('hidden');
			$('.clsIDHome').removeClass('hidden');
			$('#sitehead_div1').addClass('hidden');
			$('#sitehead_div2').addClass('hidden');
			$('#sitehead_div').addClass('hidden');
			$('#txtEmpID').val($('#txtEmpID1').val());
			$('#txtEmpName').val($('#txtEmpName1').val());
		}

		$('select').formSelect();
	}

	function checkAll() {
		$('#comment_container').empty().append('');
		if ($('input.check_val_:checked').length == $('input.check_val_').length) {


		} else {
			$("#chkAll").prop('checked', false);

		}
		if ($('input.check_val_:checked').length > 0) {
			$('#txt_Request').val('NA').trigger('change');
			$('#comment_box').removeClass('hidden');
			$('#btn_Leave_Add').addClass('hidden');
			$('#btn_Leave_Save').addClass('hidden');
			$('#user_div').addClass('hidden');
			$('#txtID').val('');
			$('#txtSiteHeadApproval').removeAttr('readonly');
			$('#btn_Leave_Save').removeClass('hidden');
			$('.clsIDHome').addClass('hidden');
			$('#sitehead_div1').removeClass('hidden');
			$('#sitehead_div2').removeClass('hidden');
			$('#sitehead_div').addClass('hidden');
			$('#txtEmpID').val('');
			$('#txtEmpName').val('');
		} else {
			$('#txt_Request').val('NA').trigger('change');
			$('#comment_box').addClass('hidden');
			$('#btn_Leave_Add').removeClass('hidden');
			$('#btn_Leave_Save').addClass('hidden');

			$('#txtID').val('');
			$('#txtSiteHeadApproval').addClass('readonly');
			$('#btn_Leave_Save').addClass('hidden');
			$('.clsIDHome').removeClass('hidden');
			$('#sitehead_div1').addClass('hidden');
			$('#sitehead_div2').addClass('hidden');
			$('#sitehead_div').addClass('hidden');
			$('.clsIDHome').removeClass('hidden');
			$('#txtEmpID').val($('#txtEmpID1').val());
			$('#txtEmpName').val($('#txtEmpName1').val());
		}
		$('select').formSelect();
	}
</script>
<link rel="stylesheet" href="<?php echo STYLE . 'jquery.datetimepicker.css'; ?>" />
<script src="<?php echo SCRIPT . 'jquery.datetimepicker.full.min.js'; ?>"></script>
<script>
	$('#txt_access_out,#txt_access_in').prop('readonly', true);
	$('#txt_access_out,#txt_access_in').datetimepicker({
		format: 'h:i A',
		datepicker: false,
		step: 1
	});

	function submitform(emp_id, DateTo) {
		$('#p_EmpID').val(emp_id);
		$('#pdate').val(DateTo);
		document.getElementById('sendID').submit();
	}
</script>
<form target='_blank' id='sendID' name='sendID' method='post' action='view_BioMetric_one.php' style="min-height: 5px;height: 5px;">

	<input type='hidden' name='p_EmpID' id='p_EmpID'>
	<input type='hidden' name='date' id='pdate'>
</form>