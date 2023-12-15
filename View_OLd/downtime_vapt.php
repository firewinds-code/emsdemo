<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
/*require_once(__dir__.'/../Config/DBConfig - LocalNEtwork.php');*/
require(ROOT_PATH . 'AppCode/nHead.php');
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
// ini_set('error_reporting', E_ALL);

// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$EmployeeID = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//$db_config_for_locale_mysql
$btnSave = 'hidden';
$btnAdd = '';
$AccountHeadName = $AccountHead = '';
$SiteHead = '';
$SiteHeadName = '';
$alert_msg = '';
$i_rosterIN = '';
$readonly_ah = ' readonly="true" ';
$readonly_sh = ' readonly="true" ';

(isset($_POST['txt_srch_DateFrom'])) ? $date_srch_from = $_POST['txt_srch_DateFrom'] : $date_srch_from = date('Y-m-01');
(isset($_POST['txt_srch_DateTo'])) ? $date_srch_to = $_POST['txt_srch_DateTo'] : $date_srch_to = date('Y-m-t');


//$str= "select ReportsTo,EmployeeName from downtimereqid1 inner join personal_details on EmployeeID = ReportsTo where process ='".$_SESSION['__user_process']."' and SubProcess ='".$_SESSION['__user_subprocess']."' limit 1";
$cm_id = clean($_SESSION["__cm_id"]);

$str = "select ReportsTo,EmployeeName from downtimereqid1 inner join personal_details on EmployeeID = ReportsTo where cm_id =? limit 1";
$selectQury = $conn->prepare($str);
$selectQury->bind_param("i", $cm_id);
$selectQury->execute();
$result = $selectQury->get_result();

// $result = $myDB->query($str);
// $error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {
	foreach ($result as $key => $value) {
		$AccountHead = $value['ReportsTo'];
		$AccountHeadName = $value['EmployeeName'];
	}
}

function getRoster($dt, $empID)
{

	$sql = "call sp_GetRoasterDataByDate('" . $empID . "','" . $dt . "')";
	$myDB = new MysqliDb();
	$roster_Data = $myDB->query($sql);
	$roster = '';
	if (count($roster_Data) > 0 && $roster_Data) {
		$roster = $roster_Data[0]['Shift'];
	}
	$i_rosterIN = $i_rosterOUT = '';
	if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2" || $roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9") {
		$rin = trim(substr($roster, 0, strpos($roster, '-')));
		$rout = trim(substr($roster, strpos($roster, '-') + 1, (strlen($roster) - (strpos($roster, '-') + 1))));
		$i_rosterIN = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rin));

		$i_rin_tmp = date('H:i:s', strtotime($rin));
		$i_rout_tmp = date('H:i:s', strtotime($rout));
		$i_rosterOUT = '';
		if ($i_rin_tmp > $i_rout_tmp) {
			$i_rosterOUT = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
			$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
		} else {
			$i_rosterOUT = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
		}
	}

	return array($roster, $i_rosterIN, $i_rosterOUT);
}

function calcDTTime($dt, $empID)
{
	$DTStart = "";
	$DTEnd = "";
	$ADT = "";
	$ADT1 = "";
	$ADT2 = "";

	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$select = 'select type_ from roster_temp where EmployeeID = ? and DateOn =cast(? as date) order by id desc limit 1';
	$selectQ = $conn->prepare($select);
	$selectQ->bind_param("ss", $empID, $dt);
	$selectQ->execute();
	$result = $selectQ->get_result();
	$rst = $result->fetch_row();
	// print_r($result);
	// die;
	$rowrst = clean($rst[0]);
	$exp1 = $exp2 = 0;
	if ($result->num_rows > 0) {
		if (intval($rowrst) != 0) {
			if ($rowrst != '4') {
				$roster = getRoster($dt, $empID);

				if ($roster[0] != 'WO-WO') {
					$DTStart = date('Y-m-d H:i:s', strtotime($_POST['txt_DateFrom']));
					$DTEnd =  date('Y-m-d H:i:s', strtotime($_POST['txt_DateTo']));


					if (($DTStart >= $roster[1] && $DTStart <= $roster[2]) || ($DTEnd >= $roster[1] && $DTEnd <= $roster[2])) {
						$ADT  = get_inshift_time($roster[1], $roster[2], $DTStart, $DTEnd);
					} else {
						$roster = getRoster(date('Y-m-d', strtotime($dt . ' -1 days')), $empID);
						$dt = date('Y-m-d', strtotime($dt . ' -1 days'));
						if ($roster[0] != 'WO-WO') {
							$ADT  = get_inshift_time($roster[1], $roster[2], $DTStart, $DTEnd);
						} else if ($roster[0] == 'WO-WO') {

							$valT1 = $DTStart;
							$valT2 = $DTEnd;
							// Conver create date as new DateTime object

							$st = new DateTime($valT1);
							$et = new DateTime($valT2);


							$diff_tt = date_diff($st, $et);
							$alt_tt = $diff_tt->format('%H:%I');
							$ADT = $alt_tt;
						}
					}
				} else if ($roster[0] == 'WO-WO') {
					$DTStart = $valT1 = date('Y-m-d H:i:s', strtotime($_POST['txt_DateFrom']));
					$DTEnd = $valT2 =  date('Y-m-d H:i:s', strtotime($_POST['txt_DateTo']));

					// Conver create date as new DateTime object

					$st = new DateTime($valT1);
					$et = new DateTime($valT2);


					$diff_tt = date_diff($st, $et);
					$alt_tt = $diff_tt->format('%H:%I');

					$ADT = $alt_tt;
				}
			} else {
				$sql = 'select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID =? and DateOn between ? and ?';
				//$sql = "call sp_GetRoasterDataByDate('".$empID."','".$dt."')";
				$selectQuy = $conn->prepare($sql);
				$selectQuy->bind_param("sss", $empID, $dt, $dt);
				$selectQuy->execute();
				$res = $selectQuy->get_result();
				$roster_Data = $res->fetch_row();
				// $roster_Data = $myDB->query($sql);
				if ($res->num_rows > 0 && $res) {
					$shift = clean($roster_Data[1]);
					$In1 = substr($shift, 0, strpos($shift, '|'));
					$Out1 = substr($shift, strpos($shift, '|') + 1, strlen($shift));

					$shift = clean($roster_Data[2]);
					$In2 = substr($shift, 0, strpos($shift, '|'));
					$Out2 = substr($shift, strpos($shift, '|') + 1, strlen($shift));
				}

				$roster = '';
				if ($res->num_rows > 0) {
					$roster = $In1;
				}

				if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2" || $roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9") {
					$rin = $In1;
					$rout = $Out1;
					$i_rosterIN = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rin));

					$i_rin_tmp = date('H:i:s', strtotime($rin));
					$i_rout_tmp = date('H:i:s', strtotime($rout));
					$i_rosterOUT = '';
					$i_rosterOUT = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));

					if ($i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} else {
						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
					}

					$DTStart = date('Y-m-d H:i:s', strtotime($_POST['txt_DateFrom']));
					$DTEnd =  date('Y-m-d H:i:s', strtotime($_POST['txt_DateTo']));


					$rin = $In2;
					$rout = $Out2;
					$i_rosterIN2 = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rin));

					$i_rin_tmp = date('H:i:s', strtotime($rin));
					$i_rout_tmp = date('H:i:s', strtotime($rout));
					$i_rosterOUT2 = '';
					$i_rosterOUT2 = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));

					if ($i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT2 = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
						$i_rosterOUT2 = date('Y-m-d H:i:s', strtotime($i_rosterOUT2 . ' +1 days'));
					} else {
						$i_rosterOUT2 = date('Y-m-d H:i:s', strtotime($dt . ' ' . $rout));
					}

					if (($DTStart >= $i_rosterIN && $DTStart <= $i_rosterOUT) && ($DTEnd >= $i_rosterIN2 && $DTEnd <= $i_rosterOUT2)) {
						$ADT1  = get_inshift_time($i_rosterIN, $i_rosterOUT, $DTStart, $DTEnd);
						$ADT2  = get_inshift_time($i_rosterIN2, $i_rosterOUT2, $DTStart, $DTEnd);

						$parsed = date_parse($ADT2);
						$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
						$tt = strtotime("+" . $seconds . " seconds " . $ADT1);
						$ADT = date("H:i", $tt);
					} else if ($DTStart >= $i_rosterIN && $DTEnd <= $i_rosterOUT) {
						$ADT  = get_inshift_time($i_rosterIN, $i_rosterOUT, $DTStart, $DTEnd);
					} else if ($DTStart >= $i_rosterIN2 && $DTEnd <= $i_rosterOUT2) {
						$ADT  = get_inshift_time($i_rosterIN2, $i_rosterOUT2, $DTStart, $DTEnd);
					} else if (($DTStart >= $i_rosterIN && $DTStart <= $i_rosterOUT) && $DTEnd < $i_rosterIN2) {
						$ADT  = get_inshift_time($i_rosterIN, $i_rosterOUT, $DTStart, $DTEnd);
					} else if ($DTStart < $i_rosterIN && ($DTEnd >= $i_rosterIN && $DTEnd <= $i_rosterOUT)) {
						$ADT  = get_inshift_time($i_rosterIN, $i_rosterOUT, $DTStart, $DTEnd);
					} else if ($DTStart < $i_rosterIN2 && ($DTEnd >= $i_rosterIN2 && $DTEnd <= $i_rosterOUT2)) {
						$ADT  = get_inshift_time($i_rosterIN2, $i_rosterOUT2, $DTStart, $DTEnd);
					} else if (($DTStart >= $i_rosterIN2 && $DTStart <= $i_rosterOUT2) && $DTEnd > $i_rosterOUT2) {
						$ADT  = get_inshift_time($i_rosterIN2, $i_rosterOUT2, $DTStart, $DTEnd);
					}





					//$ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
				} else if ($roster == 'WO|WO-WO|WO') {
					$DTStart = $valT1 = date('Y-m-d H:i:s', strtotime($_POST['txt_DateFrom']));
					$DTEnd = $valT2 =  date('Y-m-d H:i:s', strtotime($_POST['txt_DateTo']));

					// Conver create date as new DateTime object

					$st = new DateTime($valT1);
					$et = new DateTime($valT2);


					$diff_tt = date_diff($st, $et);
					$alt_tt = $diff_tt->format('%H:%I');

					$ADT = $alt_tt;
				}
			}
		}
	}

	if (strtotime($ADT) > strtotime("08:00")) {
		$ADT = "08:00";
	}

	return array($DTStart, $DTEnd, $ADT, $dt);
}

function get_inshift_time($r1, $r2, $b1, $b2)
{
	$tbin = new DateTime($b1);
	$tbout = new DateTime($b2);
	$trin = new DateTime($r1);
	$trout = new DateTime($r2);


	if ($tbin <= $trin && $tbout >= $trout) {
		$tt = $trout->diff($trin);
	} else if ($tbin <= $trin && $tbout <= $trout && $tbout > $trin) {
		$tt = $tbout->diff($trin);
	} else if ($tbin <= $trin && $tbout <= $trout && $tbout <= $trin) {
		$tt = $tbin->diff($tbin);
	} else if ($tbin >= $trin && $tbout <= $trout) {
		$tt = $tbout->diff($tbin);
	} else if ($tbin >= $trin && $tbout >= $trout && $tbin < $trout) {
		$tt = $trout->diff($tbin);
	} else if ($tbin >= $trout) {
		$tt = $tbin->diff($tbin);
	} else {
		if ($tbin < $tbout) {
			$tt = $tbin->diff($tbout);
		}
		if (date('H:i', strtotime($tt->format('%H:%i:%s'))) > '10:00') {
			if ($tbin < $tbout) {
				$tt = $tbin->diff($tbin);
			}
		}
	}



	return date('H:i', strtotime($tt->format('%H:%i:%s')));
}

function check_validation($EmployeeID, $RequestType, $LoginDate, $adt, $dt_id)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	if ($RequestType == 'Client Training') {
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$dtvalidate  = 'select distinct client_training,client_time_ttl,client_time_min,client_time_max from downtime_time_master where  client_training ="Yes" and cm_id in (select cm_id from employee_map where EmployeeID =?)';
		$selectQ = $conn->prepare($dtvalidate);
		$selectQ->bind_param("s", $EmployeeID);
		$selectQ->execute();
		$dt_validate = $selectQ->get_result();
		$dt_validates = $dt_validate->fetch_row();

		if ($dt_validate->num_rows > 0 && $dt_validate) {
			// get total Month Downtime
			// $myDB = new MysqliDb();
			$Date = date("Y-m", strtotime($LoginDate));
			$dttime = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and Request_type = ?  and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m')= ? and id not in (?)";
			$selectQr = $conn->prepare($dttime);
			$selectQr->bind_param("sssi", $EmployeeID, $RequestType, $Date, $dt_id);
			$selectQr->execute();
			$dt_time = $selectQr->get_result();
			$dt_times = $dt_time->fetch_row();
			$dt_totaltime = 0;
			if ($dt_time->num_rows > 0 && $dt_time) {
				$dt_time = clean($dt_times[0]);
			}
			if (empty($dt_time)) {
				$dt_time = 0;
			}
			// calculation current downtime in seconds
			$seconds = 0;
			$time = date('H:i:s', strtotime($adt));
			$parsed = date_parse($time);
			$error = $parsed['errors'];
			if (empty($error)) {
				$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
			} else {
				return array(1, '<span class="text-danger"><b>Message :</b> There we get a invalid Downtime value,Try agian.</span>');
			}


			if (empty($seconds)) {
				$seconds = 0;
			}
			// sum current downtime to total;
			$dt_time = $dt_time + $seconds;

			//convert total limit to seconds
			$dt_lim = 0;
			if (!empty(clean($dt_validates[1]))) {
				$parsed = explode(":", clean($dt_validates[1]));;
				$dt_lim = $parsed[0] * 3600 + $parsed[1] * 60 + $parsed[2];

				if (empty($dt_lim)) {
					$dt_lim = 0;
				}
			}

			if ($dt_time <= $dt_lim) {
				$DAte = date("Y-m-d", strtotime($LoginDate));
				// $myDB = new MysqliDb();
				$query = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and Request_type = ? and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= ? and id not in (?)";
				$select = $conn->prepare($query);
				$select->bind_param("sssi", $EmployeeID, $RequestType, $DAte, $dt_id);
				$select->execute();
				$res = $select->get_result();
				$dt_time = $res->fetch_row();

				$dt_totaltime = 0;
				if ($res->num_rows > 0 && $res) {
					$dt_time = clean($dt_time[0]);
				}
				if (empty($dt_time)) {
					$dt_time = 0;
				}

				$adt = date('H:i:s', strtotime('+' . $dt_time . ' seconds ' . $adt));
				$ct_min = date('H:i:s', strtotime(clean($dt_validates[2])));
				$ct_max = date('H:i:s', strtotime(clean($dt_validates[3])));
				if ($adt >= $ct_min  && $adt <= $ct_max) { //now getting error here 
					// this 1 is work with time silo ,ok

				} else {
					return array(1, '<span class="text-danger"><b>Message :</b> Downtime should be between client permitted time <b>[ MINIMUM - ' . $ct_min . ' HOURS, MAXIMUM - ' . $ct_max . ' HOURS ]</b> for the day.</span>');
				}
			} else {
				return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime is more <b>(' . ($dt_time - $dt_lim) . ' Seconds)</b> then permitted time by client.</span>');
			}
		} else {
			return array(1, '<span class="text-danger"><b>Message :</b> Downtime request type is not valid for your client.</span>');
		}
	} elseif ($RequestType == 'OJT') {
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$query = "select count(*) as `count` from roster_temp where EmployeeID = ? and DateOn = ? and (InTime like '%WO%' or OutTime like '%WO%')";
		$select = $conn->prepare($query);
		$select->bind_param("ss", $EmployeeID, $LoginDate);
		$select->execute();
		$res = $select->get_result();
		$data_count_WO = $res->fetch_row();

		if ($res->num_rows > 0 && $res) {
			$data_count_WO = clean($data_count_WO[0]);
			if ($data_count_WO > 0) {
				return array(1, '<span class="text-danger"><b>Message :</b>Request Not Submitted : found rostered <b>weekoff (WO)</b> on Login Date.</span>');
			}
		}


		$dtvalidate  = 'select distinct ojt_days, ojt_day_1, ojt_day_2, ojt_day_3, ojt_day_4, ojt_day_5, ojt_day_6, ojt_day_7, ojt_day_8, ojt_day_9, ojt_day_10, ojt_day_11, ojt_day_12, ojt_day_13, ojt_day_14, ojt_day_15, ojt_day_16, ojt_day_17, ojt_day_18, ojt_day_19, ojt_day_20 from downtime_time_master where  cm_id in (select cm_id from employee_map where EmployeeID =?)';
		$selectQR = $conn->prepare($dtvalidate);
		$selectQR->bind_param("s", $EmployeeID);
		$selectQR->execute();
		$res = $selectQR->get_result();
		$dt_validate = $res->fetch_row();

		if ($res->num_rows > 0 && $res) {
			$OJTdate = 'select cast(InOJT as date) InOJT from status_table where EmployeeID = ?';
			$selectQRY = $conn->prepare($OJTdate);
			$selectQRY->bind_param("s", $EmployeeID);
			$selectQRY->execute();
			$reslt = $selectQRY->get_result();
			$OJT_date = $reslt->fetch_row();

			if ($reslt->num_rows > 0 && date('Y-m-d', strtotime(clean($OJT_date[0]))) <= date('Y-m-d', strtotime($LoginDate))) {

				$OJT_date = clean($OJT_date[0]);
				$datetime1 = new DateTime($OJT_date);
				$datetime2 = new DateTime($LoginDate);
				$interval = $datetime1->diff($datetime2);
				$diffdays = $interval->format('%R%a') + 1;

				$sql_roster = "select count(*) as `count`  from roster_temp where EmployeeID = ? and (InTime like '%WO%' or OutTime like '%WO%') and  DateOn between ? and ?  limit 1";
				$selectQR = $conn->prepare($sql_roster);
				$selectQR->bind_param("sss", $EmployeeID, $OJT_date, $LoginDate);
				$selectQR->execute();
				$reslts = $selectQR->get_result();
				$dt_days = $reslts->fetch_row();

				// $dt_days = $myDB->query($sql_roster);
				$dt_days = clean($dt_days[0]);
				if (empty($dt_days) || !$dt_days) {
					$dt_days = 0;
				}

				if ($diffdays > 0) {
					if ($diffdays >= 20) {
						$diffdays = 20;
					}
					$diffdays = intval($diffdays) - intval($dt_days);
					// echo ($dt_validate[0]['ojt_day_' . $diffdays]);
					// die;
					$timeValue = date("H:i:s", strtotime($dt_validate[0]['ojt_day_' . $diffdays]));
					$actualTime  = date("H:i:s", strtotime($adt));
					if ($actualTime > $timeValue) {
						return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime should not be more then <b>' . $timeValue . '</b>.</span>');
					} else {
						// get total Day Downtime
						$date = date("Y-m-d", strtotime($LoginDate));
						$dttime = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and Request_type = ? and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= ? and id not in (?)";
						$sel = $conn->prepare($dttime);
						$sel->bind_param("sssi", $EmployeeID, $RequestType, $date, $dt_id);
						$sel->execute();
						$results = $sel->get_result();
						$dt_time = $results->fetch_row();

						$dt_totaltime = 0;
						if ($results > 0 && $results) {
							$dt_time = clean($dt_time[0]);
						}
						if (empty($dt_time)) {
							$dt_time = 0;
						}
						// calculation current downtime in seconds
						$seconds = 0;
						$time = date('H:i:s', strtotime($adt));
						$parsed = date_parse($time);
						$error = $parsed['errors'];
						if (empty($error)) {
							$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
						} else {
							return array(1, '<span class="text-danger"><b>Message :</b> There we get a invalid Downtime value,Try agian.</span>');
						}
						if (empty($seconds)) {
							$seconds = 0;
						}
						// sum current downtime to total;
						$dt_time = $dt_time + $seconds;

						//convert total limit to seconds
						$dt_lim = 0;

						if (strtotime($timeValue)) {
							$time = date('H:i:s', strtotime($timeValue));
							$parsed = date_parse($time);
							$error = $parsed['errors'];
							if (empty($error))
								$dt_lim = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
						}
						if (empty($dt_lim)) {
							$dt_lim = 0;
						}
						if ($dt_time > $dt_lim) {
							return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime is more <b>(' . gmdate('H:i:s', ($dt_time - $dt_lim)) . ' HOURS)</b> then permitted time by client.</span>');
						}
					}
				} else {
					return array(1, '<span class="text-danger"><b>Message :</b> Downtime request type is not valid for your client .</span>');
				}
			} else {
				return array(1, '<span class="text-danger"><b>Message :</b> Downtime request type is not valid for your client .</span>');
			}
		} else {
			return array(1, '<span class="text-danger"><b>Message :</b> Downtime request type is not valid for your client.</span>');
		}
	} elseif ($RequestType == 'Buddy Support') {

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$dtvalidate  = 'select Min_time,Max_Time from buddy_dtmatrix where cm_id in (select cm_id from employee_map where employeeID=?)';
		$selectQury = $conn->prepare($dtvalidate);
		$selectQury->bind_param("s", $EmployeeID);
		$selectQury->execute();
		$dt_validate = $selectQury->get_result();
		$dt_val = $dt_validate->fetch_row();

		if ($dt_validate->num_rows > 0 && $dt_validate) {
			$datess = date("Y-m-d", strtotime($LoginDate));
			$dttime = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and Request_type = ? and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= ?  and id not in (?)";
			$selectQury = $conn->prepare($dttime);
			$selectQury->bind_param("sssi", $EmployeeID, $RequestType, $datess, $dt_id);
			$selectQury->execute();
			$validate = $selectQury->get_result();
			$dt_time = $validate->fetch_row();

			$dt_totaltime = 0;
			if ($validate->num_rows > 0 && $validate) {
				$dt_time = clean($dt_time[0]);
			}
			if (empty($dt_time)) {
				$dt_time = 0;
			}
			// calculation current downtime in seconds
			$seconds = 0;
			$time = date('H:i:s', strtotime($adt));
			$parsed = date_parse($time);
			$error = $parsed['errors'];
			if (empty($error)) {
				$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
			} else {
				return array(1, '<span class="text-danger"><b>Message :</b> There we get a invalid Downtime value,Try agian.</span>');
			}
			if (empty($seconds)) {
				$seconds = 0;
			}
			// sum current downtime to total;
			$dt_time = $dt_time + $seconds;

			$MinValue = date("H:i:s", strtotime(clean($dt_val[0])));
			$MaxValue = date("H:i:s", strtotime(clean($dt_val[1])));


			//convert total limit to seconds
			$dt_lim_min = 0;
			if (strtotime($MinValue)) {
				$time = date('H:i:s', strtotime($MinValue));
				$parsed = date_parse($time);
				$error = $parsed['errors'];
				if (empty($error))
					$dt_lim_min = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
			}
			if (empty($dt_lim_min)) {
				$dt_lim_min = 0;
			}





			if ($dt_time < $dt_lim_min) {
				return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime for Buddy Support should not be less then <b>' . $MinValue . '</b>.</span>');
			} else {
				$dt_lim_max = 0;
				if (strtotime($MaxValue)) {
					$time = date('H:i:s', strtotime($MaxValue));
					$parsed = date_parse($time);
					$error = $parsed['errors'];
					if (empty($error))
						$dt_lim_max = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				}
				if (empty($dt_lim_max)) {
					$dt_lim_max = 0;
				}

				if ($dt_time > $dt_lim_max) {
					return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime for Buddy Support should not be more then <b>' . $MaxValue . '</b>.</span>');
				}
			}
		}
	} elseif ($RequestType == 'Nestor') {
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$dateform = date("Y-m-d", strtotime($LoginDate));
		$select = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and Request_type = ? and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= ? and id not in (?)";
		$selectQury = $conn->prepare($select);
		$selectQury->bind_param("sssi", $EmployeeID, $RequestType, $dateform, $dt_id);
		$selectQury->execute();
		$result = $selectQury->get_result();
		$dt_time = $result->fetch_row();
		$dt_totaltime = 0;
		if ($result->num_rows > 0 && $result) {
			$dt_time = clean($dt_time[0]);
		}
		if (empty($dt_time)) {
			$dt_time = 0;
		}
		// calculation current downtime in seconds
		$seconds = 0;
		$time = date('H:i:s', strtotime($adt));
		$parsed = date_parse($time);
		$error = $parsed['errors'];
		if (empty($error)) {
			$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
		} else {
			return array(1, '<span class="text-danger"><b>Message :</b> There we get a invalid Downtime value,Try agian.</span>');
		}
		if (empty($seconds)) {
			$seconds = 0;
		}
		$dt_lim = 25200;
		$dt_time = $dt_time + $seconds;
		if ($dt_time > $dt_lim) {
			return array(1, '<span class="text-danger"><b>Message :</b> Current Downtime is more <b>(' . ($dt_time - $dt_lim) . ' Seconds)</b> then permitted time (7 Hours ) by client.</span>');
		}
	} elseif ($RequestType == '' || empty($RequestType) || $RequestType == 'NA') {
		return array(1, '<span class="text-danger"><b>Message :</b> Request Type should not be blank.</span>');
	}

	$dates = date("Y-m-d", strtotime($LoginDate));
	echo $select = "select sum(time_to_sec(TotalDT)) sec from downtime where EmpID =? and (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= ?  and id not in (?)";
	$selectQury = $conn->prepare($select);
	$selectQury->bind_param("ssi", $EmployeeID, $dates, $dt_id);
	$selectQury->execute();
	echo 'dfds';
	$results = $selectQury->get_result();
	$dt_time = $results->fetch_row();
	// print_r($results);
	// die;
	$dt_totaltime = 0;
	if ($results->num_rows > 0 && $results) {
		$dt_time = clean($dt_time[0]);
	}
	if (empty($dt_time)) {
		$dt_time = 0;
	}

	$adt = date('H:i:s', strtotime('+' . $dt_time . ' seconds' . $adt));
	$ct_max = date('H:i:s', strtotime('08:00:00'));
	if ($adt <= $ct_max) {
		return array(0, null);
	} else {
		return array(1, '<span class="text-danger"><b>Message :</b> Downtime for today should not be more then <b>' . $ct_max . ' HOURS</b>.</span>');
	}

	if (empty($RequestType) ||  strtoupper($RequestType) == 'NA') {
		return array(1, '<span class="text-danger"><b>Message :</b> There must be a downtime Type.We found a invalid value in this field.</b>.</span>');
	}
	return array(0, null);
}

if (isset($_POST['btn_Leave_Add'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmpID = clean($_SESSION['__user_logid']);
		$Process = clean($_SESSION['__user_process']);

		$RequestType = cleanUserInput($_POST['txt_Request_text']);
		$LoginDateTime = date('Y-m-d');
		$EmployeeComment = cleanUserInput($_POST['txt_Comment']);
		$FAID = cleanUserInput($_POST['txt_Request']);
		$Status = "Pending";
		$ReportsTo = cleanUserInput($_POST['hiddenReprtingToID']);
		$RTStatus = "Pending";
		$res = calcDTTime(date('Y-m-d'), $EmpID);

		// print_r($res);
		// die;
		$ITticketID = cleanUserInput($_POST['txt_it_ticketID']);
		$DT_Start = date('Y-m-d H:i:s', strtotime($_POST['txt_DateFrom']));
		$DT_End =  date('Y-m-d H:i:s', strtotime($_POST['txt_DateTo']));
		$validate = check_validation($EmpID, $RequestType, $LoginDateTime, $res[2], 0);
		// print_r($validate);
		// die;

		if ($validate[0] === 0) {

			if ($res[3] == date('Y-m-d', strtotime($_POST['txt_DateFrom'])) || $res[3] <  date('Y-m-d', strtotime($_POST['txt_DateTo']))) {

				if (!empty($ReportsTo)) {
					if (!empty($res[2]) && $DT_Start < $DT_End && date('Y-m-d', strtotime($DT_End)) <= date('Y-m-d', strtotime($DT_Start . ' +1 days'))) {

						$DateFrom = $res[0];
						$DateTo = $res[1];
						$TotalDT = $res[2];

						if (strtotime($TotalDT) > strtotime('00:00') && $RequestType != 'NA') {
							$myDB = new MysqliDb();
							echo $sqlInsertDT = 'call sp_InsertDTReq("' . $EmpID . '","' . $Process . '","' . $DateFrom . '","' . $DateTo . '","' . $TotalDT . '","' . $FAID . '","' . $res[3] . '","' . $EmployeeComment . '","' . $FAID . '","' . $Status . '","' . $ReportsTo . '","' . $RTStatus . '","' . $RequestType . '","' . $ITticketID . '","web-Downtime848")';
							$myDB->query($sqlInsertDT);
							$error = $myDB->getLastError();
							if (empty($error)) {
								$myDB = new MysqliDb();
								$reqName_byID = '';
								$ReqName = $myDB->query('call get_empNamebyID("' . $FAID . '")');
								if (count($ReqName) > 0) {
									foreach ($ReqName as $Key => $val) {
										foreach ($val as $k => $v) {
											$reqName_byID = $v;
										}
									}
								}
								echo "<script>$(function(){ toastr.success('Request Saved and Sended To <b>" . $reqName_byID . "</b>'); }); </script>";
							} else {
								echo "<script>$(function(){ toastr.error('Request Not Saved <b>" . $error . "</b>'); }); </script>";
							}
						} else {
							echo "<script>$(function(){ toastr.error('Request Not Saved <b>Wrong request or time value</b>'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Wrong Downtime value,From Datetime should not be greater then To Datetime and make sure your roster is available'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Account Head not found by process and subprocess'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Downtime Time value should be equal to Login Date'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('" . $validate[1] . "'); }); </script>";
		}
	}
}

if (isset($_POST['btn_Leave_Save'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$len_check = 0;
		$ExpID = cleanUserInput($_POST['txtID']);
		if ($ExpID > 0 && $len_check === 0) {


			$EmpID = cleanUserInput($_POST['txtEmpID']);
			$Process = clean($_SESSION['__user_process']);
			$RequestType = cleanUserInput($_POST['txt_Request_text']);
			$LoginDateTime = cleanUserInput($_POST['hiddenLoginDate']);
			$EmployeeComment = cleanUserInput($_POST['txt_common_comment']);
			$FAID = cleanUserInput($_POST['txt_Request']);
			$Status = "Pending";
			$ReportsTo = cleanUserInput($_POST['hiddenReprtingToID']);
			$RTStatus = "Pending";
			$res = calcDTTime($LoginDateTime, $EmpID);
			$billable = '';
			$ITticketID = cleanUserInput($_POST['txt_it_ticketID']);
			$validate = check_validation($EmpID, $RequestType, $LoginDateTime, $res[2], $ExpID);

			if ($validate[0] === 0 || ($_POST['txtSupervisorApproval'] == 'Decline' || $_POST['txtSiteHeadApproval'] == 'Decline')) {
				if ($res[3] == date('Y-m-d', strtotime($res[0])) || $res[3] <  date('Y-m-d', strtotime($res[1]))) {

					if (strlen($res[0]) > 0) {
						$DateFrom = $res[0];
						$DateTo = $res[1];
						$TotalDT = $res[2];
						$MngrStatusID = cleanUserInput($_POST['txtSupervisorApproval']);
						$HeadStatusID = cleanUserInput($_POST['txtSiteHeadApproval']);
						$billable = cleanUserInput($_POST['txtHeadType']);
						if ($RequestType == "Client Training") {
							$billable = "Billable";
						}
						if ($_POST['txtSupervisorApproval'] == 'Decline' || $_POST['txtSiteHeadApproval'] == 'Decline') {
							$TotalDT = cleanUserInput($_POST['hiddenf_TotalDT']);
						}
						//echo $TotalDT.' - '. $_POST['hiddenf_TotalDT'];
						if ($TotalDT <= $_POST['hiddenf_TotalDT']) {
							$myDB = new MysqliDb();
							$__user_Name = cleanUserInput($_SESSION['__user_Name']);
							$sqlUpdatereq = 'call UpdateDTRequest("' . $ExpID . '","' . $DateFrom . '","' . $DateTo . '","' . $TotalDT . '","' . $FAID . '","' . $EmployeeComment . '","' . $FAID . '","' . $MngrStatusID . '","","' . $HeadStatusID . '","","' . $__user_Name . '","' . $EmployeeID . '","' . $RequestType . '","' . $billable . '","' . $ITticketID . '","web-Downtime972")';
							$flag = $myDB->query($sqlUpdatereq);
							$error = $myDB->getLastError();
							if (empty($error)) {

								if ($ReportsTo == $EmployeeID && $HeadStatusID != 'Pending') {
									$alert_msg = '<span class="text-success"><b>Message :</b> Request Saved and Closed</b></span>';
									if ($ReportsTo == $EmployeeID && $HeadStatusID == 'Approve') {
										//UpdateDT($EmpID,$res);
										$url = '';
										//$url = URL.'View/calcAtnd_for_empid.php?empid='.$EmpID.'&month='.date('m',strtotime($DateFrom)).'&year='.date('Y',strtotime($DateFrom));
										$iTime_in = new DateTime($DateFrom);
										$iTime_out = new DateTime();
										$interval = $iTime_in->diff($iTime_out);
										if ($interval->format("%a") <= 10) {
											$url = URL . 'View/calcRange.php?empid=' . $EmpID . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));
										} else {
											$url = URL . 'View/calcRange.php?empid=' . $EmpID . '&type=one';
										}

										$curl = curl_init();
										curl_setopt($curl, CURLOPT_URL, $url);
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($curl, CURLOPT_HEADER, false);
										$data = curl_exec($curl);
										curl_close($curl);
										$txtApprovedBy_sh = clean($_POST['txtApprovedBy_sh']);
										echo "<script>$(function(){ toastr.success('Request Saved by <b>" . $txtApprovedBy_sh . "</b>'); }); </script>";
									}
								} else if ($FAID == $EmployeeID && $MngrStatusID != 'Approve') {
									$txtApprovedBy = clean($_POST['txtApprovedBy']);
									echo "<script>$(function(){ toastr.success('Request Saved and Sended To <b>" . $txtApprovedBy . "</b>'); }); </script>";
								} else if ($FAID == $EmployeeID && $MngrStatusID == 'Approve') {
									$txtApprovedBy_sh = clean($_POST['txtApprovedBy_sh']);
									echo "<script>$(function(){ toastr.success('Request Saved and Sended To <b>" . $txtApprovedBy_sh . "</b>'); }); </script>";
								} else {
									echo "<script>$(function(){ toastr.success('Request Saved and Sended To <b>" . $txtApprovedBy . "</b>'); }); </script>";
								}
							} else {
								echo "<script>$(function(){ toastr.error('Request Not Saved <b>" . $error . "</b>'); }); </script>";
							}
						} else {
							echo "<script>$(function(){ toastr.error('Request Not Saved :system not allowed to add time in downtime.'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Roster Not Avalable.'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Downtime Time value should be equal to Login Date.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('" . $validate[1] . "'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Request Not Saved :Try Again'); }); </script>";
		}
	}
}
$search = '';
if (isset($_POST['txt_search'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$search = clean($_POST['txt_search']);
	}
}

$order_len = 5;
if (isset($_POST['order_text'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		//echo $_POST['order_text'];
		$order_text = clean($_POST['order_text']);
		switch ($order_text) {
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
}
?>
<script>
	$(document).ready(function() {
		function eventFired_order(el) {
			//alert($('#order_text').val());
			$('#order_text').val($('.dt-button.active>span').text());
			//alert($('#order_text').val()+','+$('.dt-button.active>span').text());
		}
		$('#order_text').val($('.dt-button.active>span').text());
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			"iDisplayLength": <?php echo $order_len; ?>,
			buttons: [

				{
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
				}, 'copy', 'pageLength'

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
	});
</script>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Downtime</span>
	<div class="pim-container">

		<div class="form-div">
			<?php
			$link_to_report = '';
			$status_th = clean($_SESSION['__status_th']);
			$USerID = clean($_SESSION['__user_logid']);
			$status_oh = clean($_SESSION['__status_oh']);
			$status_qh = clean($_SESSION['__status_qh']);
			$status_ah = clean($_SESSION['__status_ah']);
			$user_type = clean($_SESSION['__user_type']);
			$user_Desg = clean($_SESSION["__user_Desg"]);
			if (($status_th == $USerID || $status_oh == $USerID || $status_qh == $USerID) || ((($status_ah != 'No' && $status_ah == $USerID) && $status_ah != '') || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS' || $user_type == 'HR')) {
				$link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="dt_rpt" data-position="bottom" data-tooltip="DownTime Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';
			} elseif (!($user_Desg == "CSE" || $user_Desg == "C.S.E." || $user_Desg == "Sr. C.S.E" || $user_Desg == "C.S.E" || $user_Desg == "Senior Customer Care Executive" || $user_Desg == "Customer Care Executive" || $user_Desg == "CSA" || $user_Desg == "Senior CSA")) {
				$link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="rpt_DownTime_for_it.php" data-position="bottom" data-tooltip="DownTime Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';
			}

			?>
			<h4>Raise Downtime Request <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add DownTime"><i class="material-icons">add</i></a><?php echo $link_to_report; ?></h4>
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div id="myModal_content" class="modal">
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Raise Downtime Request </h4>
						<div class="modal-body">



							<input type="hidden" name="txt_cur_empid" id="txt_cur_empid" value="<?php echo $EmployeeID; ?>" />
							<input type="hidden" name="txtID" id="txtID" value="" />

							<input type="hidden" name="txt_search" id="txt_search" value="<?php echo $search; ?>" />
							<input type="hidden" id="order_text" name="order_text" value="<?php echo $order_len ?>" />
							<input type="hidden" name="txt_Request_text" id="txt_Request_text" value="" />
							<input type="hidden" name="hiddenLoginDate" id="hiddenLoginDate" value="" />
							<input type="hidden" name="hiddenf_TotalDT" id="hiddenf_TotalDT" value="" />
							<input type="hidden" name="hiddenReprtingToID" id="hiddenReprtingToID" value="<?php echo $AccountHead; ?>" />
							<?php
							$emp = clean($_SESSION['__user_logid']);
							$sql_trs = "call sp_GetRoasterDataByDate('" . $emp . "','" . date('Y-m-d', time()) . "')";

							$myDB = new MysqliDb();
							$data_trs = $myDB->query($sql_trs);
							$roster_trs = '';
							if (count($data_trs) > 0 && $data_trs) {
								foreach ($data_trs as $Key => $val) {
									foreach ($val as $k => $v) {
										$roster_trs = $v;
									}
								}
							}

							?>
							<input type="hidden" name="hiddenRosterValue" id="hiddenRosterValue" value="<?php echo $roster_trs; ?>" />
							<div class="col s12 m12" id="app_link"></div>

							<div class="input-field col s6 m6 clsIDHome">

								<input type="text" readonly="true" id="txtEmpName" name="txtEmpName" style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $_SESSION['__user_Name']; ?>" />
								<label for="txtEmpName"> Employee Name</label>
								<input type="hidden" readonly="true" id="txtEmpName1" name="txtEmpName1" style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $_SESSION['__user_Name']; ?>" />

							</div>
							<div class="input-field col s6 m6 clsIDHome">

								<input type="text" readonly="true" id="txtEmpID" name="txtEmpID" style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $EmployeeID; ?>" />
								<label for="txtEmpID"> Employee ID</label>
								<input type="hidden" readonly="true" id="txtEmpID1" name="txtEmpID1" style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $EmployeeID; ?>" />
							</div>

							<div class="input-field col s4 m4 clsIDHome">

								<select id="txt_Request" name="txt_Request">
									<option Selected="True" Value="NA">---Select---</option>
									<?php
									$myDB = new MysqliDb();
									//echo('call sp_GetDTReqID1("'.$_SESSION['__user_process'].'","'.$_SESSION["__user_subprocess"].'")');
									$cm = clean($_SESSION['__cm_id']);
									$rst_req = $myDB->query('call sp_GetDTReqID1("' . $cm . '")');
									//echo 'call sp_GetDTReqID1("'.$_SESSION['__user_process'].'")';
									if (count($rst_req) > 0) {
										foreach ($rst_req as $key => $value) {
											//foreach($value as $k => $v)
											//{
											if ($value['text'] == "OPS") {
												$user_status = clean($_SESSION['__user_status']);
												if ($user_status == 6) {
													$EMp = clean($_SESSION['__user_logid']);
													$dt_training  = 'select distinct downtime_time_master.client_training from downtime_time_master where  client_training ="Yes" and cm_id in (select cm_id from employee_map where EmployeeID =?)';
													$selectQury = $conn->prepare($dt_training);
													$selectQury->bind_param("s", $EMp);
													$selectQury->execute();
													$dt_client_training = $selectQury->get_result();
													if ($dt_client_training->num_rows > 0 && $dt_client_training) {
														echo '<option value="' . $value['value'] . '">Client Training</option>';
													}

													//echo '<option value="'.$v['value'].'">Floor Support</option>';

													$results  = 'select EmployeeID from tbl_nestor where EmployeeID =? limit 1';
													$selectQu = $conn->prepare($results);
													$selectQu->bind_param("s", $EMp);
													$selectQu->execute();
													$resu = $selectQu->get_result();
													$result_qp = $resu->fetch_row();
													if (!empty(clean($result_qp[0]))) {
														echo '<option value="' . $value['value'] . '">Nestor</option>';
													}


													$selQry  = 'select EmployeeID from tbl_bqm where EmployeeID = ? limit 1';
													$sel = $conn->prepare($selQry);
													$sel->bind_param("s", $EMp);
													$sel->execute();
													$resu = $sel->get_result();
													$result_qp = $resu->fetch_row();
													if (!empty(clean($result_qp[0]))) {
														echo '<option value="' . $value['value'] . '">BQM</option>';
													}


													$sql  = 'select EmployeeID from tbl_buddy where EmployeeID = ? and cast(now() as date) between Buddy_Start and Buddy_End';
													$selQ = $conn->prepare($sql);
													$selQ->bind_param("s", $EMp);
													$selQ->execute();
													$resp = $selQ->get_result();
													$result_qp = $resp->fetch_row();

													if (!empty(clean($result_qp[0]))) {
														$sqlQ  = 'select distinct cm_id from buddy_dtmatrix where cm_id in (select cm_id from employee_map where EmployeeID = ? )';
														$sel = $conn->prepare($sqlQ);
														$sel->bind_param("s", $EMp);
														$sel->execute();
														$dt_validate = $sel->get_result();
														if ($dt_validate->num_rows > 0 && $dt_validate) {
															echo '<option value="' . $value['value'] . '">Buddy Support</option>';
														}
													}
												}
											} else if ($value['text'] == "Quality") {
												if (clean($_SESSION['__user_status']) == 5) {
													echo '<option value="' . $value['value'] . '">OJT</option>';
												}
											} elseif ($value['text'] == "ER/HR") {
												//echo '<option value="'.$value['value'].'">ER Activity</option>';
											} else if ($value['text'] == "Training") {
											} else {
												echo '<option value="' . $value['value'] . '">' . $value['text'] . '</option>';
											}

											//}

										}
									}
									?>
								</select>
								<label for="txt_Request" class="active-drop-down active">DownTime Reason</label>
							</div>
							<div class="input-field col s4 m4 clsIDHome">
								<input type="text" readonly="true" id="txt_DateFrom" name="txt_DateFrom" />
								<label for="txt_DateFrom">Time From</label>
							</div>
							<div class="input-field col s4 m4 clsIDHome">
								<label for="txt_DateTo">Time To</label>
								<input type="text" readonly="true" id="txt_DateTo" name="txt_DateTo" />
							</div>
							<div class="input-field col s4 m4 clsIDHome hidden ITticket">

								<input type="text" id="txt_it_ticketID" name="txt_it_ticketID" />
								<label for="txt_it_ticketID">IT Ticket ID</label>
							</div>


							<div id="user_div" class="input-field col s12 m12">

								<textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment"></textarea>
								<label for="txt_Comment">Enter Comment</label>
							</div>

							<div class="input-field col s4 m4 hidden" id="super_div1">

								<select id="txtSupervisorApproval" name="txtSupervisorApproval" <?php echo $readonly_ah; ?>>
									<option>Pending</option>
									<option>Approve</option>
									<option>Decline</option>
								</select>
								<label for="txtSupervisorApproval" class="active-drop-down active ">Supervisor Approval</label>
							</div>
							<div class="input-field col s4 m4 hidden" id="super_div2">

								<input type="text" id="txtApprovedBy" name="txtApprovedBy" readonly="true" value="<?php echo $AccountHeadName; ?>" />
								<label for="txtApprovedBy">Approved By</label>
								<input type="hidden" id="txtApprovedByID" name="txtApprovedByID" value="<?php echo $AccountHead; ?>" />
							</div>
							<div id="super_div" class="hidden input-field col s12 m12">

								<textarea id="txt_Comment_sp" class="materialize-textarea" name="txt_Comment_sp" <?php echo $readonly_ah; ?>></textarea>
								<label for="txt_Comment_sp">Enter Comment</label>

							</div>

							<div class="input-field col s4 m4 hidden" id="sitehead_div1">

								<select id="txtSiteHeadApproval" name="txtSiteHeadApproval" <?php echo $readonly_sh; ?>>
									<option>Pending</option>
									<option>Approve</option>
									<option>Decline</option>
								</select>
								<label for="txtSiteHeadApproval" class="active-drop-down active">Head Approval</label>
							</div>
							<div class="input-field col s4 m4 hidden" id="typeHeadDiv">

								<select id="txtHeadType" name="txtHeadType" <?php echo $readonly_sh; ?>>
									<option value="NA">---Select---</option>
									<option>Billable</option>
									<option>Non Billable</option>

								</select>
								<label for="txtHeadType" class="active-drop-down active">Type</label>
							</div>
							<div class="input-field col s4 m4 hidden" id="sitehead_div2">

								<input type="text" id="txtApprovedBy_sh" name="txtApprovedBy_sh" readonly="true" value="<?php echo $SiteHeadName; ?>" />
								<label for="txtApprovedBy_sh">Approved By</label>
								<input type="hidden" id="txtApprovedBy_shID" name="txtApprovedBy_shID" readonly="true" value="<?php echo $SiteHead; ?>" />
							</div>
							<div id="sitehead_div" class="hidden input-field col s12 m12">

								<textarea id="txt_Comment_sh" name="txt_Comment_sh" class="materialize-textarea" <?php echo $readonly_sh; ?>></textarea>
								<label for="txt_Comment_sh">Enter Comment</label>

							</div>

							<div id="comment_box" class="hidden">
								<div id="commentSection">
									<div class="col s12 m12 card" id="comment_container">
									</div>
								</div>
								<div class="input-field col s12 m12">

									<textarea class="materialize-textarea" id="txt_common_comment" name="txt_common_comment"></textarea>
									<label for="txt_srch_DateFrom">Enter Comment</label>
								</div>
							</div>
						</div>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" name="btn_Leave_Add" id="btn_Leave_Add" class="btn waves-effect waves-green  <?php echo $btnAdd; ?>"> Raise Request</button>

							<button type="submit" name="btn_Leave_Save" id="btn_Leave_Save" class="btn waves-effect waves-green  <?php echo $btnSave; ?>"> Update Request</button>
						</div>
					</div>
				</div>

				<div id="pnlTable" style="float: left;width: 100%;margin: 10px 0px;">
					<?php

					$EMP = clean($_SESSION['__user_logid']);
					$sqlConnect = 'call GetDTRequestDetails1_chk("' . $EMP . '")';
					$myDB = new MysqliDb();
					//echo $sqlConnect ;
					$result = $myDB->query($sqlConnect);

					$error = $myDB->getLastError();
					if (count($result) > 0 && $result) { ?>

						<div class="panel panel-default" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>
											<?php
											/*if ($_SESSION['__user_logid'] == "CE03070003" )
								{
									echo '<th class="tbl__ALL_for_check"><input type="checkbox" name="check_ALL_up" id="chkAll" value="ALL" onclick="checkItem_All(this);" /> </th>';
								}*/
											?>
											<th>Edit </th>
											<th>EmployeeID</th>
											<th>EmployeeName</th>
											<th>Process</th>
											<th>From</th>
											<th>To</th>
											<th>Downtime</th>
											<th>Request To</th>
											<th>Login Date</th>
											<th>FA EmployeeID</th>
											<th>FA Name</th>
											<th>FA Status</th>
											<th>RT EmployeeID</th>
											<th>ReportsTo</th>
											<th>RT Status</th>
											<th>CreatedOn</th>

										</tr>
									</thead>
									<tbody>
										<?php
										$td_counter = 0;
										foreach ($result as $key => $value) {
											echo '<tr>';
											$td_counter++;

											/*if ($_SESSION['__user_logid'] == "CE03070003" )
							{
								echo '<td class="tbl__ID_for_check"><input type="checkbox" class="check_val_" style="    margin-left: 40%;" name="check_val_up[]" id="chkitem_'.$td_counter.'" value="'.$value['dt']['ID'].'" onclick="checkAll();" /></td>';
							}*/
											if ($value['FAStatus'] == 'Pending' && $value['RTStatus'] == 'Pending' && $EmployeeID == $value['EmpID']) {
												echo '<td class="tbl__ID"><a href="#" data-ID="' . base64_encode($value['ID']) . '" class="a__ID" onclick="javascript:return EditData(this);"><img class="imgBtn imgBtnEdit" src="../Style/images/users_edit.png"/></a><a href="#" data-ID="' . $value['ID'] . '" class="a__ID" onclick="javascript:return DeleteReq(this);"><img class="imgBtn imgBtnEdit" src="../Style/images/users_delete.png"/></a></td>';
											} else {
												echo '<td class="tbl__ID"><a href="#" data-ID="' . base64_encode($value['ID']) . '" class="a__ID" onclick="javascript:return EditData(this);"><img class="imgBtn imgBtnEdit" src="../Style/images/users_edit.png"/></a></td>';
											}

											echo '<td class="tbl__EmployeeID">' . $value['EmpID'] . '</a></td>';
											echo '<td class="tbl__EmployeeName">' . $value['EmployeeName'] . '</td>';
											echo '<td class="tbl__Process">' . $value['Process'] . '</td>';
											echo '<td class="tbl__DTFrom">' . $value['From'] . '</td>';
											echo '<td class="tbl__DTTo">' . $value['To'] . '</td>';
											echo '<td class="tbl__TotalDT">' . $value['Total DownTime'] . '</td>';
											echo '<td class="tbl__ReqTo">' . $value['ReqTo'] . '</td>';
											echo '<td class="tbl__LoginDate">' . $value['LoginDate'] . '</td>';
											echo '<td class="tbl__FAID">' . $value['FAID'] . '</td>';
											echo '<td class="tbl__FAN">' . $value['FAN'] . '</td>';
											echo '<td class="tbl__FAStatus">' . $value['FAStatus'] . '</td>';
											echo '<td class="tbl__RTID">' . $value['RTID'] . '</td>';
											echo '<td class="tbl__ReportsTo">' . $value['ReportsTo'] . '</td>';
											echo '<td class="tbl__RTStatus">' . $value['RTStatus'] . '</td>';
											echo '<td class="tbl__CreatedOn">' . $value['CreatedOn'] . '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						echo "<script>$(function(){ toastr.info('Record not found.'); }); </script>";
					}
					?>
				</div>



			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.modal').modal({
			onOpenStart: function(elm) {
				console.log(elm);
				$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});

			},
			onCloseEnd: function(elm) {

				$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
			}
		});
		$('input[type="text"]').click(function() {
			$(this).removeClass('has-error');
		});
		$('select,textarea').click(function() {
			$(this).removeClass('has-error');
		});
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
		/*$('#txt_DateFrom').wickedpicker({title: 'DownTime From', //hh:mm 24 hour format only, defaults to current time
        twentyFour: true}).val('');
        $('#txt_DateTo').wickedpicker({ title: 'DownTime To', //hh:mm 24 hour format only, defaults to current time
        twentyFour: true}).val('');*/
		<?php
		if (date('Y-m-d H:i:s') >= date('Y-m-d 00:00:00') && date('Y-m-d H:i:s') <= date('Y-m-d 07:00:00')) {
		?>
			$('#txt_DateFrom').datetimepicker({
				format: 'Y-m-d h:i A',
				minDate: '-1970/01/02',
				maxDate: '+1970/01/01',
				step: 1,
				beforeShowDay: disableTabs
			});
			$('#txt_DateTo').datetimepicker({
				format: 'Y-m-d h:i A',
				minDate: '-1970/01/02',
				maxDate: '+1970/01/01',
				step: 1,
				beforeShowDay: disableTabs
			});
		<?php
		} else {
		?>
			$('#txt_DateFrom').datetimepicker({
				format: 'Y-m-d h:i A',
				minDate: '-1970/01/01',
				maxDate: '+1970/01/01',
				step: 1,
				beforeShowDay: disableTabs
			});
			$('#txt_DateTo').datetimepicker({
				format: 'Y-m-d h:i A',
				minDate: '-1970/01/01',
				maxDate: '+1970/01/02',
				step: 1,
				beforeShowDay: disableTabs
			});

		<?php
		}
		?>
		$('.ITticket').addClass("hidden");
		$('#txt_DateFrom').attr('disabled', true);
		$('#txt_DateTo').attr('disabled', true);

		$('#btn_Leave_Add,#btn_Leave_Save').click(function() {

			var validate = 0;
			var alert_msg = '';

			$('#txt_DateFrom').removeAttr('disabled');
			$('#txt_DateTo').removeAttr('disabled');
			$('#txt_Request').removeClass('has-error');
			$('#txt_DateFrom').removeClass('has-error');
			$('#txt_DateTo').removeClass('has-error');
			$('#txt_it_ticketID').removeClass('has-error');

			if ($(this).attr('id') == 'btn_Leave_Save') {

				if ($('#txtID').val() == '') {
					$('#txtID').addClass("has-error");
					if ($('#spantxtID').length == 0) {
						$('<span id="spantxtID" class="help-block">ID can not be Empty</span>').insertAfter('#txtID');
					}
					validate = 1;
				}
			} else if ($(this).attr('id') == 'btn_Leave_Add') {

				if ($('#txt_Request option:selected').text() == 'Nestor') {
					var d2 = new Date(string_date($('#txt_DateFrom').val()));
					var d1 = new Date(string_date($('#txt_DateTo').val()));
					var diftime = Math.abs((d2 - d1) / 1000).toString();

					if (parseInt(diftime) < 10800) {
						validate = 1;
						$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");

						$('<span id="spantxt_Request" class="help-block">downtime should be greater or equal to 3 Hours.</span>').insertAfter('#txt_Request');
					}

				} else if ($('#txt_Request option:selected').text() == 'OJT' || $('#txt_Request option:selected').text() == 'Floor Support') {

					var d2 = new Date(string_date($('#txt_DateFrom').val()));
					var d1 = new Date(string_date($('#txt_DateTo').val()));
					var diftime = Math.abs((d2 - d1) / 1000).toString();

					if (parseInt(diftime) < 3600) {
						validate = 1;
						$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						$('<span id="spantxt_Request" class="help-block">downtime should be greater or equal to 1 Hours.</span>').insertAfter('#txt_Request');

					}

				} else if ($('#txt_Request option:selected').text() == 'IT') {

					if ($('#txt_it_ticketID').val() == "" || $('#txt_it_ticketID').val() == "NA") {
						$('#txt_it_ticketID').addClass("has-error");
						if ($('#spantxt_it_ticketID').length == 0) {
							$('<span id="spantxt_it_ticketID" class="help-block">downtime should contain a Ticket ID.</span>').insertAfter('#txt_it_ticketID');
						}
						validate = 1;

					}

				}


			}


			if ($('#txt_Request').val() == 'NA') {
				validate = 1;
				$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Request').length == 0) {
					$('<span id="spantxt_Request" class="help-block"> DownTime Reason can not be Empty </span>').insertAfter('#txt_Request');
				}

			}

			if ($('#txt_DateFrom').val() == '') {
				validate = 1;
				$('#txt_DateFrom').addClass("has-error");
				if ($('#spantxt_DateFrom').length == 0) {
					$('<span id="spantxt_DateFrom" class="help-block"> Time From can not be Empty </span>').insertAfter('#txt_DateFrom');
				}

			}
			if ($('#txt_DateTo').val() == '') {
				validate = 1;
				$('#txt_DateTo').addClass("has-error");
				if ($('#spantxt_DateTo').length == 0) {
					$('<span id="spantxt_DateTo" class="help-block"> Time To can not be Empty </span>').insertAfter('#txt_DateTo');
				}
			}

			if ($(this).attr('id') != 'btn_Leave_Save') {
				if ($('#txt_Comment').val() == '') {
					validate = 1;
					$('#txt_Comment').addClass("has-error");
					if ($('#spantxt_Comment').length == 0) {
						$('<span id="spantxt_Comment" class="help-block">Comment can not be Empty</span>').insertAfter('#txt_Comment');
					}

				}
			} else {
				if ($('#txt_common_comment').val() == '') {
					validate = 1;
					$('#txt_common_comment').addClass("has-error");
					if ($('#spantxt_common_comment').length == 0) {
						$('<span id="spantxt_common_comment" class="help-block">Comment can not be Empty</span>').insertAfter('#txt_common_comment');
					}

				}
			}

			if ($('#txtSiteHeadApproval').val() == 'Approve') {
				if ($('#txtHeadType').val() == "NA") {
					validate = 1;
					$('#txtHeadType').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#spantxtHeadType').length == 0) {
						$('<span id="spantxtHeadType" class="help-block"> Billing Type not be Unselected </span>').insertAfter('#txtHeadType');
					}

				}


			}

			var d2 = new Date(string_date($('#txt_DateFrom').val()));
			var d1 = new Date(string_date($('#txt_DateTo').val()));
			var diftime = Math.abs((d2 - d1) / 1000).toString();

			if (parseInt(diftime) > 86400) {
				validate = 1;
				$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				$('<span id="spantxt_Request" class="help-block"> downtime not greater than 24 hour.</span>').insertAfter('#txt_Request');

			}
			/*if($('#hiddenRosterValue').val() != '' && validate != 1 && $(this).attr('id') == 'btn_Leave_Add')
			{
				var roster = $('#hiddenRosterValue').val().split('-');
				var rosterIn = roster[0];
				var rosterOut = roster[1];
				var sl_down_IN = $('#txt_DateFrom').val().replace(" ", "");
				var sl_down_Out = $('#txt_DateTo').val().replace(" ", "");
				
				sl_down_IN = sl_down_IN.replace(" ", "");
				sl_down_Out = sl_down_Out.replace(" ", "");
				if(isNaN(rosterIn.charAt(0))) 
				{
					if( rosterIn.toUpperCase() == "WO")
					{
						
						sl_down_IN_tr = sl_down_IN.split(':');
						sl_down_Out_tr = sl_down_Out.split(':');
						if(parseInt(sl_down_Out_tr[0]) < parseInt(sl_down_IN_tr[0]) || parseInt(sl_down_Out_tr[1]) < parseInt(sl_down_IN_tr[1]))
						{
							validate=1;							
							alert_msg+='<li> Downtime selection is not Valid </li>';
						}
					}
					else
					{
						validate=1;							
						alert_msg+='<li> Not a valid roster value found (<b> Roster ['+roster+']</b>),Please try again </li>';
					}
					
				}
				else if(! isNaN(rosterIn.charAt(0))) 
				{
					rosterIn_tr  = rosterIn.split(':');
					rosterOut_tr  = rosterOut.split(':');
					sl_down_IN_tr = sl_down_IN.split(':');
					sl_down_Out_tr = sl_down_Out.split(':');
					if(parseInt(rosterIn_tr[0]) > parseInt(rosterOut_tr[0]))
					{
						if((parseInt(sl_down_IN_tr[0]) <  parseInt(rosterIn_tr[0]) && parseInt(sl_down_IN_tr[0]) > parseInt(rosterOut_tr[0])) || ( parseInt(sl_down_Out_tr[0]) >  parseInt(rosterOut_tr[0]) && parseInt(sl_down_Out_tr[0]) < parseInt(rosterIn_tr[0]) ))
						{
							validate=1;							
							alert_msg+='<li> Downtime selection is Not according to roster </li>';
						}
						
						
					}
					else if(parseInt(rosterIn_tr[0]) < parseInt(rosterOut_tr[0]))
					{
						if(parseInt(sl_down_IN_tr[0]) <  parseInt(rosterIn_tr[0]) || parseInt(sl_down_Out_tr[0]) >  parseInt(rosterOut_tr[0]))
						{
							validate=1;							
							alert_msg+='<li> Downtime selection is Not according to roster </li>';
						}
						else if(parseInt(sl_down_Out_tr[0]) < parseInt(sl_down_IN_tr[0]))
						{
							validate=1;							
							alert_msg+='<li> Downtime selection is not Valid </li>';
						}
					}
				}
				
				
			}*/


			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(10000).fadeOut("slow");

				$('#txt_DateFrom').attr('disabled', 'true');
				$('#txt_DateTo').attr('disabled', 'true');
				return false;
			}

		});



		$('#txt_Request').change(function() {
			$("#app_link").html('');
			$('#txt_Request_text').val($('#txt_Request option:selected').text());
			$('#backleave').addClass('hidden');
			$('#shif_div1').addClass('hidden');
			$('#shif_div2').addClass('hidden');
			$('#attendance_div1').addClass('hidden');
			$('#attendance_div2').addClass('hidden');
			$('#hiddenLoginDate').val('');
			$("#hiddenf_TotalDT").val('');
			$('#super_div_hr').addClass('hidden');
			$('#super_div1').addClass('hidden');
			$('#super_div2').addClass('hidden');
			$('#super_div').addClass('hidden');
			$('#sitehead_hr').addClass('hidden');
			$('#typeHeadDiv').addClass('hidden');

			$('#sitehead_div1').addClass('hidden');
			$('#sitehead_div2').addClass('hidden');
			$('#sitehead_div').addClass('hidden');

			$('.ITticket').addClass("hidden");
			$('#txt_it_ticketID').val('');

			$('#txt_DateFrom').val('');
			$('#txt_DateTo').val('');
			$('#txt_LeaveType').val('NA');
			$('#txt_ShiftIn').val('NA');
			$('#txt_ShiftOut').val('NA');
			$('#txt_curatnd').val('NA');
			$('#txt_updateatnd').val('NA');
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
			if ($('#txt_Request_text').val() == 'IT') {
				$('#txt_it_ticketID').val('');
				$('.ITticket').removeClass("hidden");
			}

			if ($(this).val() != 'NA') {
				$('#txt_DateFrom').removeAttr('disabled');
				$('#txt_DateTo').removeAttr('disabled');
			} else {
				$('#txt_DateFrom').attr('disabled', true);
				$('#txt_DateTo').attr('disabled', true);
			}

			$('select').formSelect();
		});
		$('#txtSiteHeadApproval').change(function() {

			if ($('#txtApprovedByID').val() == $('#txtApprovedBy_shID').val()) {
				$('#txtSupervisorApproval').val($('#txtSiteHeadApproval').val());
			}
			$('select').formSelect();
		});

	});

	function string_date(dt) {

		var dt_tmp = dt.split(' ');

		if (dt_tmp.length == 3) {
			var date_str = dt_tmp[0];
			var time_str = dt_tmp[1];
			var ap_str = dt_tmp[2];

			var time_temp = time_str.split(":");
			var time_temp_hour = parseInt(time_temp[0]);

			if (ap_str.toUpperCase() == "PM") {
				time_temp_hour = 12 + time_temp_hour;
			}
			if (time_temp_hour < 10) {
				time_temp_hour = "0" + time_temp_hour;
			}

			var newTimeString = time_temp_hour + ":" + time_temp[1];

			return date_str + " " + newTimeString;
		} else {
			return dt;
		}




	}

	function EditData(el) {
		$("#ContentAdd").remove();
		$('#comment_box').addClass('hidden');
		$('#btn_Leave_Add').addClass('hidden');
		$('#btn_Leave_Save').addClass('hidden');
		$('#txt_it_ticketID').prop("readonly", true);
		$item = $(el);
		$.ajax({
			url: "../Controller/getCommentDownTime.php?ID=" + $item.attr("Data-ID"),
			success: function(result) {

				if ($.replace(/^\s+|\s+$/g, result).length > 0 && $.replace(/^\s+|\s+$/g, result) != 'No Comment') {

					$('#comment_box').removeClass('hidden');
					$('#comment_container').empty().append(result);

				}
				$('select').formSelect();
			}
		});
		$('#user_div').addClass('hidden');
		$('#txtID').val($item.attr("Data-ID"));
		$.ajax({
			url: "../Controller/getDataForDownTime.php?ID=" + $item.attr("Data-ID"),
			success: function(result) {

				// if ($.trim(result).length > 0) {
				if ($.replace(/^\s+|\s+$/g, result).length > 0) {
					console.log(result);
					//EmpID, EmployeeName, process, DTFrom, DTTo, TotalDT, ReqTo, FAID, Approver, FAStatus, RTStatus, RTID, ReportTo, LoginDate, Request_type, trim(IT_ticketid)

					var Data = result.split('|$|');
					/*var EmployeeID = Data[0];
		        var EmployeeName =Data[11];
		        var DateFrom =Data[1];
		        var DateTo =Data[2];
		        var TotalTime =Data[3];
		        var AccountHead =Data[4];
		        var AccountHeadName = Data[13];
		        var CenterHead =Data[8];
		        var CenterHeadName =Data[14];
		        var MgnrStatus =Data[6];
		        var HeadStatus =Data[7];	
		        var LogInDate = Data[9];
		        var ReqType = Data[10];
		        var TicketID = Data[15];*/


					var EmployeeID = Data[0];
					var EmployeeName = Data[1];
					var DateFrom = Data[3];
					var DateTo = Data[4];
					var TotalTime = Data[5];
					var AccountHead = Data[6];
					var AccountHeadName = Data[8];
					var CenterHead = Data[11];
					var CenterHeadName = Data[12];
					var MgnrStatus = Data[9];
					var HeadStatus = Data[10];
					var LogInDate = Data[13];
					var ReqType = Data[14];
					var TicketID = Data[15];
					// if ($.trim(Data).length > 0) {
					if ($.replace(/^\s+|\s+$/g, Data).length > 0) {

						$('.clsIDHome').removeClass('hidden');
						$('#txt_Request').empty().append('<option value="' + AccountHead + '">' + ReqType + '</option>');
						$('#txt_Request').val(AccountHead).trigger('change');
						$('#txtEmpID').val(EmployeeID);
						$('#super_div_hr').removeClass('hidden');
						$('#super_div1').removeClass('hidden');
						$('#super_div2').removeClass('hidden');
						$('#super_div').addClass('hidden');
						$('#sitehead_hr').removeClass('hidden');
						$('#typeHeadDiv').removeClass('hidden');
						$('#sitehead_div1').removeClass('hidden');
						$('#sitehead_div2').removeClass('hidden');
						$('#sitehead_div').addClass('hidden');
						$('#user_div').addClass('hidden');
						$('#txt_DateFrom').val(DateFrom);
						$('#txt_DateTo').val(DateTo);
						$('#txtApprovedBy').val(AccountHeadName);
						$('#txtApprovedByID').val(AccountHead);

						$('#txtApprovedBy_sh').val(CenterHeadName);
						$('#txtApprovedBy_shID').val(CenterHead);
						$('#hiddenLoginDate').val(LogInDate);
						$('#txt_it_ticketID').val(TicketID);
						$('#hiddenf_TotalDT').val(TotalTime);

						$('#hiddenReprtingToID').val(CenterHead);
						//alert(ReqType);
						if (ReqType == 'IT') {
							$('.ITticket').removeClass("hidden");
						} else {
							$('#txt_it_ticketID').val('');
							$('.ITticket').addClass("hidden");
						}
						if ($('#txtEmpID').val() == $('#txtEmpID1').val()) {
							$('#txt_DateFrom').datetimepicker('destroy');
							$('#txt_DateTo').datetimepicker('destroy');

							if (MgnrStatus == 'Pending') {
								$('#super_div_hr').addClass('hidden');
								$('#super_div1').addClass('hidden');
								$('#super_div2').addClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#typeHeadDiv').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').removeAttr('readonly');
								$('#txt_DateFrom').removeAttr('disabled');
								$('#txt_DateTo').removeAttr('disabled');
								$('#txtHeadType').attr('readonly', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');

							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending') {
								// href="view_BioMetric_one.php?p_EmpID='+$('#txtEmpID').val()+'&date='+LogInDate+'" target="_blank" 
								$("#app_link").html('<a  onclick="submitform(\'' + $('#txtEmpID').val() + '\',\'' + LogInDate + '\');"> Check Biometric and Roster</a>');
								$('#txt_Request').attr('readonly', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');

								$('#txtHeadType').removeAttr('readonly');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								if (MgnrStatus == 'Decline') {
									$('#sitehead_hr').addClass('hidden');
									$('#typeHeadDiv').addClass('hidden');
									$('#sitehead_div1').addClass('hidden');
									$('#sitehead_div2').addClass('hidden');
									$('#sitehead_div').addClass('hidden');
								}
							} else if (MgnrStatus != 'Pending' && HeadStatus != 'Pending') {
								$('#txt_Request').attr('readonly', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtHeadType').attr('readonly', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
							}


						} else {
							$('#txt_DateFrom').datetimepicker('destroy');
							$('#txt_DateTo').datetimepicker('destroy');

							var minDate_data = new Date(LogInDate + " " + DateFrom, -1);
							var minDate_data = new Date(LogInDate + " " + DateTo, -1);
							if (DateFrom.length < 6) {

								var minDate_data = new Date(LogInDate + " " + DateFrom);
								var maxDate_data = new Date(LogInDate + " " + DateTo);
								var minCap = new Date();
								minCap.setDate(minDate_data.getDate() - 1);
								var maxCap = new Date();
								maxCap.setDate(maxDate_data.getDate() + 1);

								$('#txt_DateFrom').datetimepicker({
									format: 'Y-m-d h:i A',
									value: new Date(LogInDate + " " + DateFrom),
									minDate: minCap,
									maxDate: maxCap,
									step: 1,
									beforeShowDay: disableTabs
								});
								$('#txt_DateTo').datetimepicker({
									format: 'Y-m-d h:i A',
									value: new Date(LogInDate + " " + DateTo),
									minDate: minCap,
									maxDate: maxCap,
									step: 1,
									beforeShowDay: disableTabs
								});

							} else {
								var minDate_data = new Date(DateFrom);
								var maxDate_data = new Date(DateTo);
								var minCap = new Date();
								minCap.setDate(minDate_data.getDate() - 1);
								var maxCap = new Date();
								maxCap.setDate(maxDate_data.getDate() + 1);



								$('#txt_DateFrom').datetimepicker({
									format: 'Y-m-d h:i A',
									value: new Date(DateFrom),
									minDate: minCap,
									maxDate: maxCap,
									step: 1,
									beforeShowDay: disableTabs
								});
								$('#txt_DateTo').datetimepicker({
									format: 'Y-m-d h:i A',
									value: new Date(DateTo),
									minDate: minCap,
									maxDate: maxCap,
									step: 1,
									beforeShowDay: disableTabs
								});

							}

							$('#txtEmpName').val(EmployeeName);
							$('#txt_Request').attr('readonly', true);
							if (MgnrStatus == 'Pending' && $('#txtEmpID1').val() == AccountHead && $('#txtEmpID1').val() == CenterHead && AccountHead == CenterHead) {
								$("#app_link").html('<a  onclick="submitform(\'' + $('#txtEmpID').val() + '\',\'' + LogInDate + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#typeHeadDiv').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('readonly', 'true');

								$('#txtHeadType').removeAttr('readonly');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								if ($('#txtEmpID1').val() == CenterHead) {
									$('#txtSiteHeadApproval').removeAttr('readonly');
									$('#btn_Leave_Save').removeClass('hidden');
								}
							} else if (MgnrStatus == 'Pending' && $('#txtEmpID1').val() == AccountHead) {
								$("#app_link").html('<a onclick="submitform(\'' + $('#txtEmpID').val() + '\',\'' + LogInDate + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').addClass('hidden');
								$('#typeHeadDiv').addClass('hidden');
								$('#sitehead_div1').addClass('hidden');
								$('#sitehead_div2').addClass('hidden');
								$('#sitehead_div').addClass('hidden');

								$('#txtHeadType').attr('readonly', 'true');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus).removeAttr('readonly');
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Save').removeClass('hidden');
							} else if (MgnrStatus != 'Pending' && HeadStatus == 'Pending' && $('#txtEmpID1').val() == CenterHead) {
								$("#app_link").html('<a onclick="submitform(\'' + $('#txtEmpID').val() + '\',\'' + LogInDate + '\');"  > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#typeHeadDiv').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('readonly', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');
								$('#txtHeadType').removeAttr('readonly');
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								if ($('#txtEmpID1').val() == CenterHead) {
									$('#txtSiteHeadApproval').removeAttr('readonly');
									$('#btn_Leave_Save').removeClass('hidden');
								}

							} else //if(MgnrStatus != 'Pending' && HeadStatus !='Pending' )
							{
								$("#app_link").html('<a onclick="submitform(\'' + $('#txtEmpID').val() + '\',\'' + LogInDate + '\');" > Check Biometric and Roster</a>');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#super_div_hr').removeClass('hidden');
								$('#super_div1').removeClass('hidden');
								$('#super_div2').removeClass('hidden');
								$('#super_div').addClass('hidden');
								$('#sitehead_hr').removeClass('hidden');
								$('#typeHeadDiv').removeClass('hidden');
								$('#sitehead_div1').removeClass('hidden');
								$('#sitehead_div2').removeClass('hidden');
								$('#sitehead_div').addClass('hidden');
								$('#txt_Request').attr('readonly', 'true');
								$('#txt_DateFrom').attr('disabled', 'true');
								$('#txt_DateTo').attr('disabled', 'true');

								$('#txtHeadType').attr('readonly', true);
								$('#txtApprovedBy').val(AccountHeadName);
								$('#txtSupervisorApproval').val(MgnrStatus);
								$('#txtSiteHeadApproval').val(HeadStatus);
								$('#btn_Leave_Add').addClass('hidden');
							}
						}
						$('#myModal_content').modal('open');
						$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}

						});
						$('select').formSelect();
					}
				}
				$('select').formSelect();
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
				url: "../Controller/deleteDownTime.php?ID=" + $item.attr("Data-ID"),
				success: function(result) {

					var data = result.split('|');
					/*$('#alert_msg').html('<ul class="text-danger">'+data[1]+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(10000).fadeOut("slow");*/
					toastr.success(data[1]);
					if (data[0] == 'Done') {

						$item.closest('td').parent('tr').remove();
					}
					$('select').formSelect();
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
	$(function() {
		$('#txt_Comment').blur(function() {



		});


	});

	function disableTabs() {
		$('.xdsoft_mounthpicker').html('').css('height', "30px");

	}

	function submitform(emp_id, DateTo) {
		$('#p_EmpID').val(emp_id);
		$('#pdate').val(DateTo);
		document.getElementById('sendID').submit();
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<form target='_blank' id='sendID' name='sendID' method='post' action='view_BioMetric_one.php' style="min-height: 5px;height: 5px;">
	<input type='hidden' name='p_EmpID' id='p_EmpID'>
	<input type='hidden' name='date' id='pdate'>
</form>