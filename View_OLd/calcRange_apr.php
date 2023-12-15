<?php
// Server Config file

//    http://localhost/ems_noida/branches/dev/View/calcRange.php?from=2019-08-01&to=2019-08-01&empid=AE09189009&type=ONE
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

ini_set('max_execution_time', 300);
ini_set('display_errors', 1);
// error_reporting(E_ERROR | E_PARSE);

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$sq1 = "insert into scheduler(modulename,type)values(?,?);";
	$insert = $conn->prepare($sq1);
	$insert->bind_param("ss", $module, $type);
	$insert->execute();
	$result = $insert->get_result();
	// $myDB->query($sq1);
}
$date1_calc  = null;
$date2_calc = null;
$Type = cleanUserInput($_REQUEST['type']);
if ($Type == "ALL") {
	settimestamp('CalcRange_apr' . $Type, 'Start');
}
$From = isset($_REQUEST['from']);
if (null == $date1_calc && $From) {

	$date1_calc = cleanUserInput($_REQUEST['from']);
} else if (null == $date1_calc) {
	$date1_calc = date('Y-m-d', strtotime('-10 days'));
	if ($date1_calc < date('Y-m-01', time()) && date('Y-m-d', time()) > date('Y-m-05', time())) {
		$date1_calc =  date('Y-m-01', time());
	}
}

$To = isset($_REQUEST['to']);
if (null == $date2_calc && $To) {
	$date2_calc = cleanUserInput($_REQUEST['to']);
} else if (null == $date2_calc) {
	$date2_calc = date('Y-m-d', strtotime('-1 days'));
}

if ($date1_calc > $date2_calc) {
	echo 'Wrong Range given';
	exit;
} else {

	$date_tmp1 = date_create($date1_calc);
	$date_tmp2 = date_create($date2_calc);
	$diff_tmp = date_diff($date_tmp1, $date_tmp2);
	$days_calc = intval($diff_tmp->format("%R%a days"));
	$month_calc = intval($diff_tmp->format("%m months"));
	$d_tmp = new DateTime(date('Y-m-d', time()));

	$d_tmp1 = new DateTime($date_tmp1->format('Y-m-d'));
	$prev_month = $d_tmp->modify('first day of previous month');
	$tmp_month = $d_tmp1->modify('first day of this month');

	if ($days_calc > 40 || $month_calc > 1 || $prev_month > $tmp_month) {
		echo 'Days not be grater than 40 days and month diffrence not grater than 1';
		exit;
	}
}
class BioMetric
{
	private $RosterIn = array();
	private $RosterOut = array();
	private $Roster_type = array();
	private $APR = array();
	private $InTime = array();
	private $OutTime = array();
	private $dsUpdatedAtt = array();
	private $pl_adjusted = array();
	private $co_adjusted = array();

	private $nonAPR_Employee_status = 0;
	private $ModuleChange = '';
	private $onFloor = '';

	private $iPL = 0;
	private $iCO = 0;
	private $finalAtnd = '-';
	private $paid_leave_urned = 0;
	private $UpdateInOut = 0;
	private $ATND_cur = array();
	private $ATND_prev = array();

	private $i_cur_ShortLeave = 0;
	private $i_cur_ShortLogin = 0;
	private $i_prev_ShortLeave = 0;
	private $i_prev_ShortLogin = 0;


	private $pt_bio = array();
	//////////mithilehs/////////////////////////////////
	private $pt_aprIn = array();
	private $pt_aprOut = array();
	//////////mithilehs/////////////////////////////////

	public function __construct($EmployeeID, $DateFrom, $DateTo, $Emd_des, $Emp_status, $emp_dod, $emp_module)
	{
		// Fetch data for Roster in given range;


		// Sevrer 
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		/*$ds_roster = $myDB->query('select str_to_date(DateOn,"%Y-%c-%e") as DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID ="'.$EmployeeID.'" and cast(str_to_date(DateOn,"%Y-%c-%e") as  date) between cast("'.$DateFrom.'" as  date) and cast("'.$DateTo.'" as  date)');
            */
		$select = 'select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID =? and DateOn between ? and ?';
		$selectQury = $conn->prepare($select);
		$selectQury->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
		$selectQury->execute();
		$ds_roster = $selectQury->get_result();

		if ($ds_roster->num_rows > 0 && $ds_roster) {

			foreach ($ds_roster as $key => $val) {
				$date_for = $val['DateOn'];
				$this->Roster_type[$date_for] = trim($val['type_']);

				if ($val['type_'] == "4") {
					$temp_shft_1 =  explode("|", $val['InTime']);
					$temp_shft_2 =  explode("|", $val['OutTime']);
					$this->RosterIn[$date_for][0] = trim($temp_shft_1[0]);
					$this->RosterOut[$date_for][0] = trim($temp_shft_1[1]);

					$this->RosterIn[$date_for][1] = trim($temp_shft_2[0]);
					$this->RosterOut[$date_for][1] = trim($temp_shft_2[1]);
				} else {
					$this->RosterIn[$date_for] = trim($val['InTime']);
					$this->RosterOut[$date_for] = trim($val['OutTime']);
				}
			}

			unset($ds_roster);
		}
		$date = date('Y-m-d', (strtotime($DateTo . ' +1 days')));
		$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID=? and DateOn between cast(? as date) and cast(? as date) order by DateOn,PunchTime';
		$selectQuy = $conn->prepare($str_capping);
		$selectQuy->bind_param("sss", $EmployeeID, $DateFrom, $date);
		$selectQuy->execute();
		$ds_punchtime = $selectQuy->get_result();
		// $ds_punchtime = $myDB->query($str_capping);

		// Fetch data for APR  in given range; 
		if ($ds_punchtime->num_rows > 0 && $ds_punchtime) {
			foreach ($ds_punchtime as $key => $value) {
				$this->pt_bio[$value['DateOn']][] = $value['PunchTime'];
			}
		}
		///mihtilesh///////////////////////////////

		$str_aprInOut = 'select employeeid,date,logged_in,logged_out from cosmo_apr where employeeid=? and date between cast(? as date) and cast(? as date) order by date;';
		$selectQry = $conn->prepare($str_aprInOut);
		$selectQry->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
		$selectQry->execute();
		$ds_aprInOut = $selectQry->get_result();
		//	$ds_aprInOut = $myDB->query($str_aprInOut); // Fetch data for APR  in given range; 
		// var_dump($ds_aprInOut);
		if ($ds_aprInOut->num_rows > 0 && $ds_aprInOut) {
			foreach ($ds_aprInOut as $key => $value) {
				$this->pt_aprIn[$value['date']][] = $value['logged_in'];
				$this->pt_aprOut[$value['date']][] = $value['logged_out'];
			}
		}


		// var_dump($pt_aprIn[]);
		/////

		// Only for CSA and Sr CSA

		if ($Emd_des == "CSE" || $Emd_des == "C.S.E." || $Emd_des == "Sr. C.S.E" || $Emd_des == "C.S.E" || $Emd_des == "Senior Customer Care Executive" || $Emd_des == "Customer Care Executive" || $Emd_des == "CSA" || $Emd_des == "Senior CSA") {

			$select = 'select sum(time_to_sec(TotalDT)) sec,LoginDate from downtime where EmpID =? and FAStatus ="Approve" and RTStatus ="Approve" and LoginDate between ? and ? group by LoginDate';

			$selectQ = $conn->prepare($select);
			$selectQ->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
			$selectQ->execute();
			$ds_downtime = $selectQ->get_result();
			$DTHour = array();
			if ($ds_downtime->num_rows > 0 && $ds_downtime) {

				foreach ($ds_downtime as $key => $val) {
					$date_for = $val['LoginDate'];
					$minute = intval(($val['sec'] % 3600) / 60);
					if ($minute <= 9) {
						$minute = '0' . $minute;
					}
					$DTHour[$date_for] = intval($val['sec'] / 3600) . ':' . $minute;
					unset($date_for);
				}

				unset($ds_downtime);
			}
			if (date('Y-m', strtotime($DateFrom)) == date('Y-m', strtotime($DateTo))) {
				$h_month = date('m', strtotime($DateTo));
				$h_year = date('Y', strtotime($DateTo));
				$date_range = $this->getDatesFromRange($DateFrom, $DateTo);
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);
				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month = ? and year = ? order by id desc limit 1";
				$selectQry = $conn->prepare($strsql);
				$selectQry->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$selectQry->execute();
				$dshr = $selectQry->get_result();
				// $dshr = $myDB->query($strsql);
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
									$this->APR[$date_for] = $DTHour[$date_for];
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
									$this->APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$this->APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}
			} elseif (date('Y-m', strtotime($DateFrom)) != date('Y-m', strtotime($DateTo))) {


				$date_range = $this->getDatesFromRange($DateFrom, date('Y-m-t', strtotime($DateFrom)));
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);
				$h_month = date('m', strtotime($DateFrom));
				$h_year = date('Y', strtotime($DateFrom));

				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID =? and  Type = 'Hours' and month = ? and year = ? order by id desc limit 1";
				$selectQry = $conn->prepare($strsql);
				$selectQry->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$selectQry->execute();
				$dshr = $selectQry->get_result();
				// $myDB = new MysqliDb();
				// $dshr = $myDB->query($strsql);
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
									$this->APR[$date_for] = $DTHour[$date_for];
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
									$this->APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$this->APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}

				$date_range = $this->getDatesFromRange(date('Y-m-01', strtotime($DateTo)), $DateTo);
				foreach ($date_range as &$value_dc) {
					$value_dc = 'D' . $value_dc;
				}
				unset($value_dc);
				$str_t = implode(',', $date_range);

				$h_month = date('m', strtotime($DateTo));
				$h_year = date('Y', strtotime($DateTo));

				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID = ? and  Type = 'Hours' and month = ? and year = ? order by id desc limit 1";
				$selectQry = $conn->prepare($strsql);
				$selectQry->bind_param("sii", $EmployeeID, $h_month, $h_year);
				$selectQry->execute();
				$dshr = $selectQry->get_result();
				// $myDB = new MysqliDb();
				// $dshr = $myDB->query($strsql);
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
									$this->APR[$date_for] = $DTHour[$date_for];
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
									$this->APR[$date_for] = $dataTime1 . ':' . $dataTime2;
								}
							} else {
								$this->APR[$date_for] = $value;
							}
						}
					}
					unset($dshr);
				}
			}

			// $myDB = new MysqliDb();
			$sel = 'select EmployeeID from nonapr_employee  where EmployeeID= ? and flag=0';
			$selectQR = $conn->prepare($sel);
			$selectQR->bind_param("s", $EmployeeID);
			$selectQR->execute();
			$res = $selectQR->get_result();
			$nonAPR_emp = $res->fetch_row();
			if (!empty($nonAPR_emp[0])) {
				$this->nonAPR_Employee_status = 1;
			}
			$this->onFloor = $emp_dod;
			$this->ModuleChange = $emp_module;
		}

		// Get all Biometric Exceptions 

		// $myDB = new MysqliDb();
		$query = 'select EmployeeID,Exception,DateFrom,DateTo,HeadStatus,IssueType, dispo.Update_Att from exception join exceptiondispo dispo on exception.ID=dispo.expid where Employeeid=? and (exception="Biometric issue") and (DateFrom between ? and ?) and MgrStatus="Approve" and HeadStatus !="Decline" order by datefrom;';
		$SelectQry = $conn->prepare($query);
		$SelectQry->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
		$SelectQry->execute();
		$ds_exception = $SelectQry->get_result();
		if ($ds_exception->num_rows > 0 && $ds_exception) {

			foreach ($ds_exception as $key => $val) {
				$date_for = $val['DateFrom'];
				$this->dsUpdatedAtt[$date_for] = $val;
				unset($date_for);
			}

			unset($ds_exception);
		}

		// adjusted paidleave ;

		// $myDB = new MysqliDb();
		$sql = 'select cast(date_used as date) as DateOn from paidleave where EmployeeID = ? and cast(str_to_date(date_used,"%Y-%c-%e") as  date) between cast( ? as  date) and cast( ? as  date)';
		$SelQry = $conn->prepare($sql);
		$SelQry->bind_param("sss", $EmployeeID, $DateFrom, $DateTo);
		$SelQry->execute();
		$ds_pl_adj = $SelQry->get_result();

		if ($ds_pl_adj->num_rows > 0 && $ds_pl_adj) {

			foreach ($ds_pl_adj as $key => $val) {
				$date_for = $val['DateOn'];
				$this->pl_adjusted[$date_for] = (empty($val['Paid_Leave']) ? 0 : $val['Paid_Leave']);
				unset($date_for);
			}
			unset($ds_pl_adj);
		}


		/********************* PRIVATE **********************/
		/**
		 * Previous Calculation Data.
		 */

		if (date('Y-m', strtotime($DateFrom)) == date('Y-m', strtotime($DateTo))) {
			$h_month = date('m', strtotime($DateTo));
			$h_year = date('Y', strtotime($DateTo));
			$date_range = $this->getDatesFromRange($DateFrom, $DateTo);
			foreach ($date_range as &$value_dc) {
				$value_dc = 'D' . $value_dc;
			}
			unset($value_dc);
			$str_t = implode(',', $date_range);
			$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID =? and month = ? and year = ? limit 1";
			$SelQ = $conn->prepare($strsql);
			$SelQ->bind_param("sii", $EmployeeID, $h_month, $h_year);
			$SelQ->execute();
			$dshr = $SelQ->get_result();
			// $myDB = new MysqliDb();
			// $dshr = $myDB->query($strsql);
			if ($dshr->num_rows > 0) {
				foreach ($dshr as $key => $val) {
					foreach ($val as $keys => $value) {
						$keyDate = substr($keys, 1, strlen($keys));

						if ($keyDate < 10) {
							$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
						} else {
							$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
						}
						if (date('m', strtotime($date_for)) == date('m', time())) {
							$this->ATND_cur[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_cur_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_cur_ShortLeave++;
								}
							}
						} else {
							$this->ATND_prev[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_prev_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_prev_ShortLeave++;
								}
							}
						}
					}
				}
				unset($dshr);
			}
		} elseif (date('Y-m', strtotime($DateFrom)) != date('Y-m', strtotime($DateTo))) {


			$date_range = $this->getDatesFromRange($DateFrom, date('Y-m-t', strtotime($DateFrom)));
			foreach ($date_range as &$value_dc) {
				$value_dc = 'D' . $value_dc;
			}
			unset($value_dc);
			$str_t = implode(',', $date_range);
			$h_month = date('m', strtotime($DateFrom));
			$h_year = date('Y', strtotime($DateFrom));

			$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID = ? and month = ? and year = ? limit 1";
			$SelQ = $conn->prepare($strsql);
			$SelQ->bind_param("sii", $EmployeeID, $h_month, $h_year);
			$SelQ->execute();
			$dshr = $SelQ->get_result();
			// $myDB = new MysqliDb();
			// $dshr = $myDB->query($strsql);
			if ($dshr->num_rows > 0) {
				foreach ($dshr as $key => $val) {
					foreach ($val as $keys => $value) {
						$keyDate = substr($keys, 1, strlen($keys));

						if ($keyDate < 10) {
							$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
						} else {
							$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
						}
						if (date('m', strtotime($date_for)) == date('m', time())) {
							$this->ATND_cur[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_cur_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_cur_ShortLeave++;
								}
							}
						} else {
							$this->ATND_prev[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_prev_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_prev_ShortLeave++;
								}
							}
						}
					}
				}
				unset($dshr);
			}
			unset($dshr);
			$date_range = $this->getDatesFromRange(date('Y-m-01', strtotime($DateTo)), $DateTo);
			foreach ($date_range as &$value_dc) {
				$value_dc = 'D' . $value_dc;
			}
			unset($value_dc);
			$str_t = implode(',', $date_range);

			$h_month = date('m', strtotime($DateTo));
			$h_year = date('Y', strtotime($DateTo));

			$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID = ? and month = ? and year = ? limit 1";
			$SelQ = $conn->prepare($strsql);
			$SelQ->bind_param("sii", $EmployeeID, $h_month, $h_year);
			$SelQ->execute();
			$dshr = $SelQ->get_result();
			// $myDB = new MysqliDb();
			// $dshr = $myDB->query($strsql);
			if ($dshr->num_rows > 0) {
				foreach ($dshr as $key => $val) {
					foreach ($val as $keys => $value) {
						$keyDate = substr($keys, 1, strlen($keys));

						if ($keyDate < 10) {
							$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
						} else {
							$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
						}
						if (date('m', strtotime($date_for)) == date('m', time())) {
							$this->ATND_cur[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_prev_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_prev_ShortLeave++;
								}
							}
						} else {
							$this->ATND_prev[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_prev_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_prev_ShortLeave++;
								}
							}
						}
					}
				}
				unset($dshr);
			}
		}
		/********************* PRIVATE **********************/
		/**
		 * Date day calculation is here
		 */

		$start_date  = new DateTime($DateFrom);
		$end_date  = new DateTime($DateTo);
		echo $EmployeeID . '=>';
		for ($i_date = $start_date; $start_date <= $end_date; $i_date->modify('+1 day')) {
			$date__ = $i_date->format('Y-m-d');
			if (!empty($date__) && $date__ != '1970-01-01' && $date__ < date('Y-m-d', time())) {
				$return_val = $this->__calculation_for_day($date__, $EmployeeID, $Emd_des, $Emp_status);
				echo $return_val;
				unset($return_val);
			}
		}
		echo '</br>';
	}
	/********************* PRIVATE **********************/
	/**
	 * Main day calculation function
	 */

	private function __calculation_for_day($__date, $__EmpID, $__des, $__status)
	{
		if (strtotime($__date) > strtotime('2019-08-09')) {


			if ($__date != null && $__date <= date('Y-m-d', time())) {
				$this->finalAtnd = '-';
				$i_rosterIN[0] = '';
				$i_rosterOut[0] = '';

				$i_rosterIN[1] = '';
				$i_rosterOut[1] = '';

				///Calculate split shift attendance

				if ($this->Roster_type[$__date] == "4") {
					if (isset($this->RosterIn[$__date][0]) && isset($this->RosterIn[$__date][1])) {
						$i_rosterattr1 = $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0];
						$i_rosterattr2 = $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1];

						$i_rosterIN[0] = $this->get_timestring_fortype4($__date, $this->RosterIn[$__date][0]);
						$i_rosterOUT[0] = $this->get_timestring_fortype4($__date, $this->RosterOut[$__date][0]);

						$i_rosterIN[1] = $this->get_timestring_fortype4($__date, $this->RosterIn[$__date][1]);
						$i_rosterOUT[1] = $this->get_timestring_fortype4($__date, $this->RosterOut[$__date][1]);
					} else {
						if (isset($this->RosterIn[$__date])) {
							$i_rosterIN[0] = $this->get_timestring_fortype4($__date, $this->RosterIn[$__date]);
							$i_rosterOUT[0] = $this->get_timestring_fortype4($__date, $this->RosterOut[$__date]);

							$i_rosterIN[1] = '-';
							$i_rosterOUT[1] = '-';
						} else {
							$i_rosterIN[0] = '-';
							$i_rosterOUT[0] = '-';

							$i_rosterIN[1] = '-';
							$i_rosterOUT[1] = '-';
						}
					}

					$i_bioATND = 'P';
					$i_aprATND = 'P';
					$this->calcCOPL($__date, $__EmpID);
					$myDB = new MysqliDb();
					$db_app = $myDB->query("call sp_AppLeave_Main_check('" . $__EmpID . "','Leave','" . $__date . "')");
					if (count($db_app) > 0) {
						foreach ($db_app as $key => $val) {
							foreach ($val as $k => $v) {
								$app_key_main = $v;
							}
						}
					}
					unset($db_app);
					if ($app_key_main == "Approved" && $i_rosterattr != 'WO-WO') {
						if ($this->iPL < 1 && $this->iCO < 1)
							$this->finalAtnd = "LWP";
						else {
							if ($this->iCO >= 1) {
								$this->finalAtnd = 'CO';
								$myDB = new MysqliDb();
								$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
							} else {
								$this->finalAtnd = 'L';
								$this->iPL = $this->iPL - 1;
								$this->paid_leave_urned++;
							}
						}
					} elseif (!is_numeric($this->RosterIn[$__date][0][0]) || !is_numeric($this->RosterOut[$__date][0][0])) {
						if ($this->RosterIn[$__date][0] == 'L') {
							if ($this->iPL < 1 && $this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'L';
									$this->iPL = $this->iPL - 1;
									$this->paid_leave_urned++;
								}
							}
						} elseif ($this->RosterIn[$__date][0] == 'CO') {
							if ($this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'LWP';
								}
							}
						} elseif ($this->RosterIn[$__date][0] == 'H') {
							if ($this->iPL == 0)
								$this->finalAtnd = "HWP";
							else {
								$this->iPL = $this->iPL - .5;
								$this->paid_leave_urned =  $this->paid_leave_urned + .5;
							}
						} else {
							$this->finalAtnd = strtoupper($this->RosterIn[$__date][0]);
						}
					} else {
						$ExpAtt = array();
						$ExpAtt = $this->getSeniorUpdatedAttd($__date);
						if ($ExpAtt[0] != '') {

							if ($ExpAtt[0] == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$ExpAtt[0] = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
									}
								}
							} elseif ($ExpAtt[0] == "H") {
								if ($this->iPL == 0)
									$ExpAtt[0] = "HWP";
								else {
									$this->iPL = $this->iPL - .5;
									$this->paid_leave_urned =  $this->paid_leave_urned + .5;
								}
							}

							$this->finalAtnd = $ExpAtt[0] . '(' . $ExpAtt[1] . ')';
						} else {
							$bioTemp[$__date] = $this->pt_bio[$__date];
							//$bioTemp[date('Y-m-d',(strtotime($__date.' +1 days')))] = $this->pt_bio[date('Y-m-d',(strtotime($__date.' +1 days')))];

							$i_bioIN1 = null;
							$i_bioOUT1 = null;

							$i_bioIN2 = null;
							$i_bioOUT2 = null;
							$i_bioATND1 = '';
							$i_bioATND2 = '';
							if (count($bioTemp) > 0) {
								$inflag = 0;
								$pt_temp = array();
								if (count($bioTemp[$__date]) > 0) {
									foreach ($bioTemp[$__date] as $K => $V) {
										$pt_temp[] = $__date . ' ' . $V;
									}
								}



								if (is_numeric($i_rosterIN[0][0]) && is_numeric($i_rosterOUT[0][0])) {
									$i = 0;
									if (intval(date('H', strtotime($i_rosterIN[0]))) < intval(date('H', strtotime($i_rosterOUT[0])))) {
										if (count($bioTemp[$__date]) > 0) {
											$pt_temp = array();
											foreach ($bioTemp[$__date] as $K => $V) {
												$pt_temp[] = $__date . ' ' . $V;
											}

											$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN[0])));
											$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT[0])));
											$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN[0] . '-2 hours'));
											$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN[0] . '+2 hours'));

											$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT[0] . '-2 hours'));
											$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT[0] . '+2 hours'));

											for (; $i < count($pt_temp) && $punchTime <= $rosterOut_Capping_P; $i++) {

												$punchTime = "";
												if (strtotime($pt_temp[$i])) {
													$punchTime = $pt_temp[$i];
												}
												if (!empty($punchTime)) {
													if ($inflag == 0) {
														if ($i == 0 &&  $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN1 = $punchTime;
															$inflag = 1;
														} else if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN1 = $punchTime;
															$inflag = 1;
														} else if ($punchTime >= $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT1 = $punchTime;
														}
													} else {
														if ($punchTime >= $rosterOut_Capping && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT1 = $punchTime;
														}
													}
												}
											}
										}
									}
									if (empty($i_bioIN1) && empty($i_bioOUT1)) {
										$i_bioATND1 = 'L';
									} elseif (empty($i_bioIN1) || empty($i_bioOUT1)) {
										$i_bioATND1 = 'HNS';
									} elseif ($i_bioIN1  > $i_bioOUT1) {
										$i_bioOUT1 = null;
										$i_bioATND1 = 'HNS';
									} elseif ($this->check_itime_diffrence($i_bioIN1, $i_bioOUT1) <= "01:00:00") {
										$i_bioOUT1 = null;
										$i_bioATND1 = 'HNS';
									} else {
										$b_tt1 = $this->check_itime_diffrence($i_bioIN1, $i_bioOUT1);
										$tt1 = $this->get_inshift_time($i_rosterIN[0], $i_rosterOUT[0], $i_bioIN1, $i_bioOUT1, 4);
										$i_bioATND1  = $this->get_bio_ATND($tt1, 4, $b_tt1);
									}


									if (intval(date('H', strtotime($i_rosterIN[1]))) < intval(date('H', strtotime($i_rosterOUT[1])))) {
										if (count($bioTemp[$__date]) > 0) {
											$pt_temp = array();
											foreach ($bioTemp[$__date] as $K => $V) {
												$pt_temp[] = $__date . ' ' . $V;
											}

											$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN[1])));
											$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT[1])));
											$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN[1] . '-2 hours'));
											$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN[1] . '+2 hours'));

											$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT[1] . '-2 hours'));
											$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT[1] . '+2 hours'));
											$inflag = 0;
											$i--;

											for (; $i < count($pt_temp); $i++) {

												$punchTime = "";
												if (strtotime($pt_temp[$i])) {
													$punchTime = $pt_temp[$i];
												}
												if (!empty($punchTime)) {
													if ($inflag == 0) {
														if ($i == 0 && $punchTime >= $rosterIN_Capping &&  $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN2 = $punchTime;
															$inflag = 1;
														} else if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN2 = $punchTime;
															$inflag = 1;
														} elseif ($punchTime > $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT2 = $punchTime;
														}
													} else {
														if ($punchTime >= $rosterOut_Capping && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT2 = $punchTime;
														}
													}
												}
											}
										}
									}
									if (empty($i_bioIN2) && empty($i_bioOUT2)) {
										$i_bioATND2 = 'L';
									} elseif (empty($i_bioIN2) || empty($i_bioOUT2)) {
										$i_bioATND2 = 'HNS';
									} elseif ($i_bioIN2  > $i_bioOUT2) {
										$i_bioOUT2 = null;
										$i_bioATND2 = 'HNS';
									} elseif ($this->check_itime_diffrence($i_bioIN2, $i_bioOUT2) <= "01:00:00") {
										$i_bioOUT2 = null;
										$i_bioATND2 = 'HNS';
									} else {
										$b_tt2 = $this->check_itime_diffrence($i_bioIN2, $i_bioOUT2);
										$tt2 = $this->get_inshift_time($i_rosterIN[1], $i_rosterOUT[1], $i_bioIN2, $i_bioOUT2, 4);
										$i_bioATND2  = $this->get_bio_ATND($tt2, 4, $b_tt2);
									}
								}
							} else {
								$i_bioATND1 = "L";
								$i_bioATND2 = "L";
							}


							$i_bioATND_temp1 = $i_bioATND1;
							if ($i_bioATND1 == "HNS") {
								$i_bioATND1 = 'H';
							}
							$i_bioATND_temp2 = $i_bioATND2;
							if ($i_bioATND2 == "HNS") {
								$i_bioATND2 = 'H';
							}

							$i_bioATND = $this->calcType4Atnd($i_bioATND1, $i_bioATND2, $__des);

							$hour_attr = $this->calcAPR($__status, $__des, $__date);
							$i_aprATND = $this->get_apr_ATND($hour_attr, 4);

							$i_sub_atnd = $this->get_apr_bio_ATND($i_bioATND, $i_aprATND, $__des);
							$this->finalAtnd = $this->get_Final_atnd($i_sub_atnd, $__EmpID, $__date, $__des);





							/*$ATND1 = $this->get_Final_atnd($i_bioATND1,$__EmpID,$__date,$__des);
						$ATND2 = $this->get_Final_atnd($i_bioATND2,$__EmpID,$__date,$__des);
						
						$i_bioATND = $this->calcType4Atnd($ATND1,$ATND2);*/

							/*$ATND1 = $this->calcType4Atnd($i_bioATND1,$__EmpID,$__date,$__des);
						$ATND2 = $this->calcType4Atnd($i_bioATND2,$__EmpID,$__date,$__des);
						
						$i_bioATND = $this->get_Final_atnd($ATND1,$ATND2);
						
						$i_sub_atnd = $this->get_apr_bio_ATND($i_bioATND,$i_aprATND,$__des);
						$this->finalAtnd = $this->get_Final_atnd($i_sub_atnd,$__EmpID,$__date,$__des);*/


							if (($this->finalAtnd == "H" || $this->finalAtnd == "HWP") && $i_bioATND_temp == "HNS") {
								$this->finalAtnd = "H";
							} else {
								$this->finalAtnd = $this->check_leave($__EmpID, $__date, $this->finalAtnd);
							}



							if (($this->finalAtnd == 'H' || $this->finalAtnd == 'HWP') && $this->Roster_type[$__date] == 3) {
								$this->finalAtnd = 'HWP';

								/*if($this->finalAtnd ==  'H')
							{
								$this->finalAtnd = 'A';
							}
							if($this->finalAtnd ==  'HWP')
							{
								$this->finalAtnd = 'A';
							}*/
							}


							if ($this->finalAtnd == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$this->finalAtnd = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
										$this->finalAtnd = "CO";
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
										$this->finalAtnd = "L";
									}
								}
							} elseif ($this->finalAtnd == "H") {
								if ($this->iPL == 0)
									$this->finalAtnd = "HWP";
								else {
									$this->iPL = $this->iPL - .5;
									$this->paid_leave_urned =  $this->paid_leave_urned + .5;
									$this->finalAtnd = "H";
								}
							}
						}
					}
				}
				///Calculate PartTimer New attendance
				elseif ($this->Roster_type[$__date] == "3") {


					if (isset($this->RosterIn[$__date])) {
						$i_rosterattr = $this->RosterIn[$__date] . '-' . $this->RosterOut[$__date];
					} else {
						$this->RosterIn[$__date] = '-';
					}


					$i_rosterIN = $this->get_timestring($__date, $this->RosterIn);
					$i_rin_tmp = date('H:i:s', strtotime($this->RosterIn[$__date]));
					$i_rout_tmp = date('H:i:s', strtotime($this->RosterOut[$__date]));
					if ($i_rin_tmp >= "15:00:00" && $this->Roster_type[$__date] == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);
						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} elseif ($i_rin_tmp >= "13:00:00" && $this->Roster_type[$__date] == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);

						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} elseif ($i_rin_tmp >= "15:00:00" && $this->Roster_type[$__date] == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);

						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} else {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);
					}




					$i_bioATND = 'HWP';
					$i_aprATND = 'HWP';
					$this->calcCOPL($__date, $__EmpID);
					$myDB = new MysqliDb();
					$db_app = $myDB->query("call sp_AppLeave_Main_check('" . $__EmpID . "','Leave','" . $__date . "')");
					if (count($db_app) > 0) {
						foreach ($db_app as $key => $val) {
							foreach ($val as $k => $v) {
								$app_key_main = $v;
							}
						}
					}
					unset($db_app);
					if ($app_key_main == "Approved" && $i_rosterattr != 'WO-WO') {
						if ($this->iPL < 1 && $this->iCO < 1)
							$this->finalAtnd = "LWP";
						else {
							if ($this->iCO >= 1) {
								$this->finalAtnd = 'CO';
								$myDB = new  MysqliDb();
								$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
							} else {
								$this->finalAtnd = 'L';
								$this->iPL = $this->iPL - 1;
								$this->paid_leave_urned++;
							}
						}
					} elseif (!is_numeric($this->RosterIn[$__date][0]) || !is_numeric($this->RosterOut[$__date][0])) {
						if ($this->RosterIn[$__date] == 'L') {
							if ($this->iPL < 1 && $this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'L';
									$this->iPL = $this->iPL - 1;
									$this->paid_leave_urned++;
								}
							}
						} elseif ($this->RosterIn[$__date] == 'CO') {
							if ($this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'LWP';
								}
							}
						} else {
							$this->finalAtnd = strtoupper($this->RosterIn[$__date]);
						}
					} else {
						$ExpAtt = array();
						$ExpAtt = $this->getSeniorUpdatedAttd($__date);
						if ($ExpAtt[0] != '') {

							if ($ExpAtt[0] == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$ExpAtt[0] = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
									}
								}
							}
							$this->finalAtnd = $ExpAtt[0] . '(' . $ExpAtt[1] . ')';
						} else {
							$bioTemp[$__date] = $this->pt_bio[$__date];
							$bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))] = $this->pt_bio[date('Y-m-d', (strtotime($__date . ' +1 days')))];

							$i_bioIN = null;
							$i_bioOUT = null;

							if (count($bioTemp) > 0) {
								$inflag = 0;
								$pt_temp = array();
								if (count($bioTemp[$__date]) > 0) {
									foreach ($bioTemp[$__date] as $K => $V) {
										$pt_temp[] = $__date . ' ' . $V;
									}
								}
								if (count($bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))]) > 0) {
									foreach ($bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))] as $K => $V) {
										$pt_temp[] = date('Y-m-d', (strtotime($__date . ' +1 days'))) . ' ' . $V;
									}
								}


								if (is_numeric($i_rosterIN[0]) && is_numeric($i_rosterOUT[0])) {
									if (intval(date('H', strtotime($i_rosterIN))) < intval(date('H', strtotime($i_rosterOUT)))) {
										if (count($bioTemp[$__date]) > 0) {
											$pt_temp = array();
											foreach ($bioTemp[$__date] as $K => $V) {
												$pt_temp[] = $__date . ' ' . $V;
											}

											for ($i = 0; $i < count($pt_temp); $i++) {
												$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
												$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
												$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '-4 hours'));
												$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+4 hours'));

												$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '-7 hours'));
												$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+4 hours'));
												$punchTime = "";
												if (strtotime($pt_temp[$i])) {
													$punchTime = $pt_temp[$i];
												}
												if (!empty($punchTime)) {
													if ($inflag == 0) {
														if ($i == 0 &&  $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN = $punchTime;
															$inflag = 1;
														} else if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN = $punchTime;
															$inflag = 1;
														} elseif ($punchTime > $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT = $punchTime;
														}
													} else {
														if ($punchTime >= $rosterOut_Capping && ($i == count($pt_temp) - 1) && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT = $punchTime;
														}
													}
												}
											}
										}
									} else {
										for ($i = 0; $i < count($pt_temp); $i++) {
											$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
											$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
											$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '-4 hours'));
											$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+4 hours'));

											$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '-7 hours'));
											$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+4 hours'));
											$punchTime = "";
											if (strtotime($pt_temp[$i])) {
												$punchTime = $pt_temp[$i];
											}
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
									}
								}

								if (empty($i_bioIN) && empty($i_bioOUT)) {
									$i_bioATND = 'L';
								} elseif (empty($i_bioIN) || empty($i_bioOUT)) {
									$i_bioATND = 'HNS';
								} elseif ($i_bioIN  > $i_bioOUT) {
									$i_bioOUT = null;
									$i_bioATND = 'HNS';
								} elseif ($this->check_itime_diffrence($i_bioIN, $i_bioOUT) <= "01:00:00") {
									$i_bioOUT = null;
									$i_bioATND = 'HNS';
								} else {
									$b_tt = $this->check_itime_diffrence($i_bioIN, $i_bioOUT);
									$tt = $this->get_inshift_time($i_rosterIN, $i_rosterOUT, $i_bioIN, $i_bioOUT, $this->Roster_type[$__date]);
									$i_bioATND  = $this->get_bio_ATND($tt, $this->Roster_type[$__date], $b_tt);
								}
							} else {
								$i_bioATND = "L";
							}


							$i_bioATND_temp = $i_bioATND;
							if ($i_bioATND == "HNS") {
								$i_bioATND = 'A';
							}
							$hour_attr = $this->calcAPR($__status, $__des, $__date);
							$i_aprATND = $this->get_apr_ATND($hour_attr, $this->Roster_type[$__date]);
							$i_sub_atnd = $this->get_apr_bio_ATND($i_bioATND, $i_aprATND, $__des);
							$this->finalAtnd = $this->get_Final_atnd($i_sub_atnd, $__EmpID, $__date, $__des);
							if (($this->finalAtnd == "H" || $this->finalAtnd == "HWP") && $i_bioATND_temp == "HNS") {
								$this->finalAtnd = "HWP";
							} else {
								$this->finalAtnd = $this->check_leave($__EmpID, $__date, $this->finalAtnd);
							}



							if (($this->finalAtnd == 'H' || $this->finalAtnd == 'HWP') && $this->Roster_type[$__date] == 3) {
								$this->finalAtnd = 'HWP';

								/*if($this->finalAtnd ==  'H')
						{
							$this->finalAtnd = 'A';
						}
						if($this->finalAtnd ==  'HWP')
						{
							$this->finalAtnd = 'A';
						}*/
							}


							if ($this->finalAtnd == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$this->finalAtnd = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
										$this->finalAtnd = "CO";
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
										$this->finalAtnd = "L";
									}
								}
							}
							/*elseif ($this->finalAtnd == "H")
	                {
	                    if ($this->iPL == 0)
	                    $this->finalAtnd = "HWP";
	                    else
	                    {
	                        $this->iPL = $this->iPL - .5;
	                        $this->paid_leave_urned =  $this->paid_leave_urned + .5;
	                        $this->finalAtnd = "H";
	                    }
	                }*/
						}
					}
				}

				///Calculate rest employee attendance

				else {


					if (isset($this->RosterIn[$__date])) {
						$i_rosterattr = $this->RosterIn[$__date] . '-' . $this->RosterOut[$__date];
					} else {
						$this->RosterIn[$__date] = '-';
					}


					$i_rosterIN = $this->get_timestring($__date, $this->RosterIn);
					$i_rin_tmp = date('H:i:s', strtotime($this->RosterIn[$__date]));
					$i_rout_tmp = date('H:i:s', strtotime($this->RosterOut[$__date]));
					if ($i_rin_tmp >= "15:00:00" && $this->Roster_type[$__date] == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);
						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} elseif ($i_rin_tmp >= "13:00:00" && $this->Roster_type[$__date] == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);

						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} elseif ($i_rin_tmp >= "19:00:00" && $this->Roster_type[$__date] == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);

						$i_rosterOUT = date('Y-m-d H:i:s', strtotime($i_rosterOUT . ' +1 days'));
					} else {
						$i_rosterOUT = $this->get_timestring($__date, $this->RosterOut);
					}




					$i_bioATND = 'P';
					$i_aprATND = 'P';
					$this->calcCOPL($__date, $__EmpID);
					$myDB = new MysqliDb();
					$db_app = $myDB->query("call sp_AppLeave_Main_check('" . $__EmpID . "','Leave','" . $__date . "')");
					if (count($db_app) > 0) {
						foreach ($db_app as $key => $val) {
							foreach ($val as $k => $v) {
								$app_key_main = $v;
							}
						}
					}
					unset($db_app);
					//echo(RosterIn[$__date]);
					if ($app_key_main == "Approved" && $i_rosterattr != 'WO-WO') {
						if ($this->iPL < 1 && $this->iCO < 1)
							$this->finalAtnd = "LWP";
						else {
							if ($this->iCO >= 1) {
								$this->finalAtnd = 'CO';
								$myDB = new MysqliDb();
								$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
							} else {
								$this->finalAtnd = 'L';
								$this->iPL = $this->iPL - 1;
								$this->paid_leave_urned++;
							}
						}
					} elseif (!is_numeric($this->RosterIn[$__date][0]) || !is_numeric($this->RosterOut[$__date][0])) {
						if ($this->RosterIn[$__date] == 'L') {
							if ($this->iPL < 1 && $this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'L';
									$this->iPL = $this->iPL - 1;
									$this->paid_leave_urned++;
								}
							}
						} elseif ($this->RosterIn[$__date] == 'CO') {
							if ($this->iCO < 1)
								$this->finalAtnd = "LWP";
							else {
								if ($this->iCO >= 1) {
									$this->finalAtnd = 'CO';
									$myDB = new MysqliDb();
									$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
								} else {
									$this->finalAtnd = 'LWP';
								}
							}
						} elseif ($this->RosterIn[$__date] == 'H') {
							if ($this->iPL == 0)
								$this->finalAtnd = "HWP";
							else {
								$this->iPL = $this->iPL - .5;
								$this->paid_leave_urned =  $this->paid_leave_urned + .5;
							}
						} else {
							$this->finalAtnd = strtoupper($this->RosterIn[$__date]);
						}
					} else {
						$ExpAtt = array();
						$ExpAtt = $this->getSeniorUpdatedAttd($__date);
						if ($ExpAtt[0] != '') {

							if ($ExpAtt[0] == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$ExpAtt[0] = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
									}
								}
							} elseif ($ExpAtt[0] == "H") {
								if ($this->iPL == 0)
									$ExpAtt[0] = "HWP";
								else {
									$this->iPL = $this->iPL - .5;
									$this->paid_leave_urned =  $this->paid_leave_urned + .5;
								}
							}

							$this->finalAtnd = $ExpAtt[0] . '(' . $ExpAtt[1] . ')';
						} else {
							$bioTemp[$__date] = $this->pt_bio[$__date];
							$bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))] = $this->pt_bio[date('Y-m-d', (strtotime($__date . ' +1 days')))];

							$i_bioIN = null;
							$i_bioOUT = null;

							if (count($bioTemp) > 0) {
								$inflag = 0;
								$pt_temp = array();
								if (count($bioTemp[$__date]) > 0) {
									foreach ($bioTemp[$__date] as $K => $V) {
										$pt_temp[] = $__date . ' ' . $V;
									}
								}
								if (count($bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))]) > 0) {
									foreach ($bioTemp[date('Y-m-d', (strtotime($__date . ' +1 days')))] as $K => $V) {
										$pt_temp[] = date('Y-m-d', (strtotime($__date . ' +1 days'))) . ' ' . $V;
									}
								}


								if (is_numeric($i_rosterIN[0]) && is_numeric($i_rosterOUT[0])) {
									if (intval(date('H', strtotime($i_rosterIN))) < intval(date('H', strtotime($i_rosterOUT)))) {
										if (count($bioTemp[$__date]) > 0) {
											$pt_temp = array();
											foreach ($bioTemp[$__date] as $K => $V) {
												$pt_temp[] = $__date . ' ' . $V;
											}

											for ($i = 0; $i < count($pt_temp); $i++) {
												$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
												$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
												$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '-4 hours'));
												$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+4 hours'));

												$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '-7 hours'));
												$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+4 hours'));
												$punchTime = "";
												if (strtotime($pt_temp[$i])) {
													$punchTime = $pt_temp[$i];
												}
												if (!empty($punchTime)) {
													if ($inflag == 0) {
														if ($i == 0 &&  $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN = $punchTime;
															$inflag = 1;
														} else if ($punchTime >= $rosterIN_Capping && $punchTime <= $rosterIN_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioIN = $punchTime;
															$inflag = 1;
														} elseif ($punchTime > $rosterIN_Capping_P && $punchTime <= $rosterOut_Capping_P && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT = $punchTime;
														}
													} else {
														if ($punchTime >= $rosterOut_Capping && ($i == count($pt_temp) - 1) && date('Y-m-d', strtotime($punchTime)) == $__date) {
															$i_bioOUT = $punchTime;
														}
													}
												}
											}
										}
									} else {
										for ($i = 0; $i < count($pt_temp); $i++) {
											$rosterIN = date('Y-m-d H:i:s', (strtotime($i_rosterIN)));
											$rosterOut = date('Y-m-d H:i:s', (strtotime($i_rosterOUT)));
											$rosterIN_Capping = date('Y-m-d H:i:s', strtotime($i_rosterIN . '-4 hours'));
											$rosterIN_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterIN . '+4 hours'));

											$rosterOut_Capping = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '-7 hours'));
											$rosterOut_Capping_P = date('Y-m-d H:i:s', strtotime($i_rosterOUT . '+4 hours'));
											$punchTime = "";
											if (strtotime($pt_temp[$i])) {
												$punchTime = $pt_temp[$i];
											}
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
									}
								}

								if (empty($i_bioIN) && empty($i_bioOUT)) {
									$i_bioATND = 'L';
								} elseif (empty($i_bioIN) || empty($i_bioOUT)) {
									$i_bioATND = 'HNS';
								} elseif ($i_bioIN  > $i_bioOUT) {
									$i_bioOUT = null;
									$i_bioATND = 'HNS';
								} elseif ($this->check_itime_diffrence($i_bioIN, $i_bioOUT) <= "01:00:00") {
									$i_bioOUT = null;
									$i_bioATND = 'HNS';
								} else {
									$b_tt = $this->check_itime_diffrence($i_bioIN, $i_bioOUT);
									$tt = $this->get_inshift_time($i_rosterIN, $i_rosterOUT, $i_bioIN, $i_bioOUT, $this->Roster_type[$__date]);
									$i_bioATND  = $this->get_bio_ATND($tt, $this->Roster_type[$__date], $b_tt);
								}
							} else {
								$i_bioATND = "L";
							}


							$i_bioATND_temp = $i_bioATND;
							if ($i_bioATND == "HNS") {
								$i_bioATND = 'H';
							}


							$hour_attr = $this->calcAPR($__status, $__des, $__date);
							$i_aprATND = $this->get_apr_ATND($hour_attr, $this->Roster_type[$__date]);
							///////////////////////MITHILESH/////////////////////////////////////////////////////////////////////////////
							$i_aprIN = $this->pt_aprIn[$__date][0]; // 11:01:16
							$i_aprIN = $__date . ' ' . $i_aprIN;
							$i_aprOUT = $this->pt_aprOut[$__date][0];	// 20:02:47
							$i_aprOUT = $__date . ' ' . $i_aprOUT;
							$hour_attr = $this->calcAPR($__status, $__des, $__date);
							$i_apr_atnd = $this->get_apr_ATND($hour_attr, $this->Roster_type[$__date]);
							$tt3 = $this->get_inshift_time($i_rosterIN, $i_rosterOUT, $i_aprIN, $i_aprOUT, $this->Roster_type[$__date]);
							$apr_tt = $this->check_itime_diffrence($i_aprIN, $i_aprOUT);
							$i_aprw_atnd = $this->get_apr_window_ATND($tt3, $this->Roster_type[$__date], $apr_tt);


							$i_apr2_atnd = $this->get_final_apr_ATND($i_apr_atnd, $i_aprw_atnd, $__des);
							////////////////////////////////////////////////////////////////////////////////////////////////////////
							//$tt = $this->get_inshift_time($i_rosterIN,$i_rosterOUT,$i_bioIN,$i_bioOUT,$this->Roster_type[$__date]);	

							$i_sub_atnd = $this->get_apr_bio_ATND($i_bioATND, $i_apr2_atnd, $__des);

							$this->finalAtnd = $this->get_Final_atnd($i_sub_atnd, $__EmpID, $__date, $__des);

							if (($this->finalAtnd == "H" || $this->finalAtnd == "HWP") && $i_bioATND_temp == "HNS") {
								$this->finalAtnd = "H";
							} else {
								$this->finalAtnd = $this->check_leave($__EmpID, $__date, $this->finalAtnd);
							}

							if (($this->finalAtnd == 'H' || $this->finalAtnd == 'HWP') && $this->Roster_type[$__date] == 3) {
								$this->finalAtnd = 'A';

								/*if($this->finalAtnd ==  'H')
						{
							$this->finalAtnd = 'A';
						}
						if($this->finalAtnd ==  'HWP')
						{
							$this->finalAtnd = 'A';
						}*/
							}


							if ($this->finalAtnd == "L") {
								if ($this->iPL < 1 && $this->iCO < 1)
									$this->finalAtnd = "LWP";
								else {
									if ($this->iCO >= 1) {
										$myDB = new MysqliDb();
										$myDB->query('call update_CO("' . $__EmpID . '","' . $this->iCO . '","' . $__date . '")');
										$this->finalAtnd = "CO";
									} else {
										$this->iPL = $this->iPL - 1;
										$this->paid_leave_urned++;
										$this->finalAtnd = "L";
									}
								}
							} elseif ($this->finalAtnd == "H") {
								if ($this->iPL == 0)
									$this->finalAtnd = "HWP";
								else {
									$this->iPL = $this->iPL - .5;
									$this->paid_leave_urned =  $this->paid_leave_urned + .5;
									$this->finalAtnd = "H";
								}
							}
						}
					}
				}

				///Close attendence calculation as per roster type
				if (trim($this->finalAtnd) == '') {
					$this->finalAtnd = '-';
				}
				if ($this->finalAtnd == 'A') {
					$this->finalAtnd = $this->check_lana($__EmpID, $__date, $this->finalAtnd);
				}


				$lastATDN = '';
				if (date('m', strtotime($__date)) == date('m', time())) {
					$lastATDN = $this->ATND_cur[$__date];
				} else {
					$lastATDN = $this->ATND_prev[$__date];
				}
				if ($this->finalAtnd != $lastATDN && $lastATDN != 'WONA') {
					$str_to_insert = 'call inserCalcAtnd("' . $__EmpID . '","' . $this->finalAtnd . '","' . intval(date('m', strtotime($__date))) . '","' . intval(date('Y', strtotime($__date))) . '","D' . intval(date('d', strtotime($__date))) . '")';

					$myDB = new MysqliDb();
					$fllf  = $myDB->query($str_to_insert);
				} elseif ($lastATDN == 'WONA') {
					if ($this->finalAtnd != 'WONA' &&  strtoupper($this->finalAtnd) != 'WO') {
						$str_to_insert = 'call inserCalcAtnd("' . $__EmpID . '","' . $this->finalAtnd . '","' . intval(date('m', strtotime($__date))) . '","' . intval(date('Y', strtotime($__date))) . '","D' . intval(date('d', strtotime($__date))) . '")';

						$myDB = new MysqliDb();
						$fllf  = $myDB->query($str_to_insert);
					} else {
						$this->finalAtnd = 'WONA';
					}
				} else {
					$fllf = 0;
				}

				if (date('m', strtotime($__date)) == date('m', time())) {
					$this->ATND_cur[$__date] = $this->finalAtnd;
				} else {
					$this->ATND_prev[$__date] = $this->finalAtnd;
				}

				if ($this->paid_leave_urned == 0) {
					if (isset($this->pl_adjusted[$__date])) {
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$SQL = 'delete from paidleave where EmployeeID = ? and cast(date_used as date) = ?';
						$del = $conn->prepare($SQL);
						$del->bind_param("ss", $__EmpID, $__date);
						$del->execute();
						$rst = $del->get_result();
					}
				} else {
					$myDB = new MysqliDb();
					$rst = $myDB->query('call manage_ernedpaidleave("' . $__EmpID . '","' . $this->paid_leave_urned . '","' . date('m', strtotime($__date)) . '","' . date('Y', strtotime($__date)) . '","' . $__date . '")');
				}

				return '<td>' . '&nbsp;' . $fllf . '&nbsp;&brvbar;&nbsp;' . $this->finalAtnd . '[' . $i_bioATND . ',' . $i_aprATND . ']</td>';
			}
		}
	}

	private function calcType4Atnd($atnd1, $atnd2, $des)
	{
		if (($des == "CSE" || $des == "C.S.E." || $des == "Sr. C.S.E" || $des == "C.S.E" || $des == "Senior Customer Care Executive" || $des == "Customer Care Executive" || $des == "CSA" || $des == "Senior CSA")) {
			if ($atnd1 == "P(Short Leave)") {
				$atnd1 = "H";
			}
			if ($atnd2 == "P(Short Leave)") {
				$atnd2 = "H";
			}
		}

		if ($atnd1 == " " && $atnd2 == " ") {
			$FinalAtt = " ";
		} else if ($atnd1 == "P" && $atnd2 == "P") {
			$FinalAtt = "P";
		} else if ($atnd1 == "A" && $atnd2 == "A") {
			$FinalAtt = "A";
		} else if ($atnd1 == "H" && $atnd2 == "H") {
			$FinalAtt = "H";
		} else if ($atnd1 == "HWP" && $atnd2 == "HWP") {
			$FinalAtt = "HWP";
		} else if ($atnd1 == "H" && $atnd2 == "HWP") {
			$FinalAtt = $atnd2;
		} else if ($atnd1 == "HWP" && $atnd2 == "H") {
			$FinalAtt = $atnd1;
		} else if ($atnd1 == "P" && $atnd2 == "HWP") {
			$FinalAtt = "HWP";
		} else if ($atnd1 == "HWP" && $atnd2 == "P") {
			$FinalAtt = "HWP";
		} else if ($atnd1 == "P" && $atnd2 == "A") {
			$FinalAtt = "H";
		} else if ($atnd1 == "A" && $atnd2 == "P") {
			$FinalAtt = "H";
		} else if ($atnd1 == "P" && $atnd2 == "L") {
			$FinalAtt = "H";
		} else if ($atnd1 == "L" && $atnd2 == "P") {
			$FinalAtt = "H";
		} else if (($atnd1 == "H" || $atnd1 == "HWP") && $atnd2 == "P") {
			$FinalAtt = $atnd1;
		} else if (($atnd2 == "H" || $atnd2 == "HWP") && $atnd1 == "P") {
			$FinalAtt = $atnd2;
		} else if (($atnd1 == "H" || $atnd1 == "HWP") && $atnd2 == "A") {
			$FinalAtt = $atnd2;
		} else if (($atnd2 == "H" || $atnd2 == "HWP") && $atnd1 == "A") {
			$FinalAtt = $atnd1;
		} else if (($atnd1 == "H" || $atnd1 == "HWP") && $atnd2 == "L") {
			$FinalAtt = $atnd2;
		} else if (($atnd2 == "H" || $atnd2 == "HWP") && $atnd1 == "L") {
			$FinalAtt = $atnd1;
		} else if ($atnd1 == "P(Short Login)" && $atnd2 == "P(Short Login)") {
			$FinalAtt = "P(Short Login)(2)";
		} else if ($atnd1 == "P(Short Leave)" && $atnd2 == "P(Short Leave)") {
			$FinalAtt = "P(Short Leave)(2)";
		} else if ($atnd1 == "P(Short Login)" && $atnd2 == "P") {
			$FinalAtt = $atnd1;
		} else if ($atnd2 == "P(Short Login)" && $atnd1 == "P") {
			$FinalAtt = $atnd2;
		} else if ($atnd1 == "P(Short Login)" && $atnd2 != "P") {
			$FinalAtt = $atnd2;
		} else if ($atnd2 == "P(Short Login)" && $atnd1 != "P") {
			$FinalAtt = $atnd1;
		} else if ($atnd1 == "P(Short Leave)" && $atnd2 == "P") {
			$FinalAtt = $atnd1;
		} else if ($atnd2 == "P(Short Leave)" && $atnd1 == "P") {
			$FinalAtt = $atnd2;
		} else {
			$FinalAtt = $atnd1;
		}



		return $FinalAtt;
	}

	/********************* PRIVATE **********************/
	/**
	 * APR  calculation 
	 */

	private function calcAPR($status, $des, $date)
	{
		if (($des == "CSE" || $des == "C.S.E." || $des == "Sr. C.S.E" || $des == "C.S.E" || $des == "Senior Customer Care Executive" || $des == "Customer Care Executive" || $des == "CSA" || $des == "Senior CSA")) {

			if (($status < 5  &&  $date >= date('Y-m-d', strtotime($this->ModuleChange))) || $this->nonAPR_Employee_status == 1) {
				$hour_attr = "08:10";
			} else {
				if (!empty($this->onFloor) && !empty($this->ModuleChange) &&   $date >= date('Y-m-d', strtotime($this->ModuleChange)) && $date < date('Y-m-d', strtotime($this->onFloor)) && strtotime($this->ModuleChange) && strtotime($this->onFloor)) {

					$hour_attr = '08:10';
				} else {
					$hour_attr = $this->APR[$date];
				}
			}
		} else {
			return '-';
		}

		return ($hour_attr);
	}

	private function check_leave($EmpID, $date, $type)
	{
		if ($type == "H" || $type == "HWP") {
			$myDB = new MysqliDb();
			$app = '';
			$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Half Day','" . $date . "')");
			if (count($db_app) > 0) {
				foreach ($db_app as $key => $val) {
					foreach ($val as $k => $v) {
						$app = $v;
					}
				}
			}
			unset($db_app);
			if ($app == "Approved") {
				$att = "H";
			} else {
				$att = "HWP";
			}
		} elseif ($type == "L") {
			$myDB = new MysqliDb();
			$app = '';
			$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Leave','" . $date . "')");
			if (count($db_app) > 0) {
				foreach ($db_app as $key => $val) {
					foreach ($val as $k => $v) {
						$app = $v;
					}
				}
			}
			unset($db_app);
			if ($app == "Approved") {
				$att = "L";
			} else {
				$att = "A";
			}
		} else {
			$att = $type;
		}

		return ($att);
	}


	private function check_lana($EmpID, $date, $type)
	{
		if ($type == "A") {
			if ($this->iPL >= 1 || $this->iCO > 0) {
				$myDB = new MysqliDb();
				$db_app = $myDB->query("call sp_All_appliend_Leave('" . $EmpID . "','Leave','" . $date . "')");
				$app = 'Not Exists';
				if (count($db_app) > 0) {
					foreach ($db_app as $key => $val) {
						foreach ($val as $k => $v) {
							$app = $v;
						}
					}
				}
				unset($db_app);
				if ($app == "Exists") {
					$att = "LANA";
				} else {
					$att = "A";
				}
			} else {
				$att = $type;
			}
		} else {
			$att = $type;
		}

		return ($att);
	}
	/********************* PRIVATE **********************/
	/**
	 * CO and PL Calculation
	 */
	private function calcCOPL($date, $EmpID)
	{
		$myDB = new MysqliDb();
		$pd_current = $myDB->query('call get_paidleave_current("' . $date . '","' . $EmpID . '")');
		$myDB = new MysqliDb();
		$pd_urned = $myDB->query('call get_paidleave_urned("' . $date . '","' . $EmpID . '")');
		$mysql_error = $myDB->getLastError();
		$this->iPL = 0;
		$paid_urned = 0;
		if ($pd_current) {
			if (count($pd_current) > 0) {
				$this->iPL = $pd_current[0]['paidleave'];
				if (count($pd_urned) > 0) {

					$paid_urned = $pd_urned[0]['paidleave'];
					if ($paid_urned == null) {
						$paid_urned = 0;
					}
				}

				if ($paid_urned > 0) {
					$this->iPL = $this->iPL - $paid_urned;
				}
				if ($this->iPL <= 0) {
					$this->iPL = 0;
				}
			}
		}
		$this->paid_leave_urned = 0;
		$this->iCO = 0;
		$myDB = new MysqliDb();
		$co_current = $myDB->query('call get_combooff("' . $EmpID . '","' . $date . '")');

		if ($co_current) {
			if (count($co_current) > 0) {
				$this->iCO = $co_current[0]['id'];
			} else {
				$this->iCO = 0;
			}
		} else {
			$this->iCO = 0;
		}
	}

	/********************* PRIVATE **********************/
	/**
	 * Convert time to date time string 
	 */
	private function get_timestring($date, $__iTime)
	{
		if (isset($__iTime[$date])) {
			if (strtotime($__iTime[$date])) {
				return date('Y-m-d H:i:s', strtotime($date . ' ' . $__iTime[$date]));
			}
		} else {
			return ('');
		}
		return ('');
	}

	private function get_timestring_fortype4($date, $__iTime)
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

	/********************* PRIVATE **********************/
	/**
	 * get Time Diffrence for two date time string 
	 */
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

	/********************* PRIVATE **********************/
	/**
	 * Get biometrci travel time 
	 */

	function get_inshift_time($r1, $r2, $b1, $b2, $type)
	{
		$tbin = new DateTime(date('Y-m-d H:i', strtotime($b1)));
		$tbout = new DateTime(date('Y-m-d H:i', strtotime($b2)));
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

	/********************* PRIVATE **********************/
	/**
	 * Calculation of Biometric Attendance by Travel time and Roster Type
	 */
	function get_bio_ATND($travelTime, $roster_type, $att_bio)
	{

		if (strtotime($travelTime)) {
			$att = 'P';
			if ($roster_type == 1) {

				if ($travelTime < "06:30" && $travelTime >= "04:30") {
					$att = "H";
				} else if ($travelTime < "04:30") {
					$att = "A";
				} else if ($travelTime < "09:00" && $travelTime >= "08:45") {
					if ($att_bio < "09:00") {
						$att = "P(Short Leave)";
					} else {
						$att = "P(Short Login)";
					}
				} else if ($travelTime < "08:45" && $travelTime >= "06:30") {
					$att = "P(Short Leave)";
				}
			} else if ($roster_type == 2) {
				if ($travelTime < "08:30" && $travelTime >= "05:00") {
					$att = "H";
				} else if ($travelTime < "05:00") {
					$att = "A";
				} else if ($travelTime < "11:00" && $travelTime >= "10:45") {
					if ($att_bio < "11:00") {
						$att = "P(Short Leave)";
					} else {
						$att = "P(Short Login)";
					}
				} else if ($travelTime < "10:45" && $travelTime >= "08:30") {
					$att = "P(Short Leave)";
				}
			} else if ($roster_type == 3) {
				$att = 'H';
				if ($travelTime < "04:30") {
					$att = "A";
				}
			} else if ($roster_type == 4) {

				if ($travelTime < "03:15" && $travelTime >= "02:15") {
					$att = "H";
				} else if ($travelTime < "02:15") {
					$att = "A";
				} else if ($travelTime < "04:30" && $travelTime >= "04:15") {
					if ($att_bio < "04:30") {
						$att = "P(Short Leave)";
					} else {
						$att = "P(Short Login)";
					}
				} else if ($travelTime < "04:15" && $travelTime >= "03:15") {
					$att = "P(Short Leave)";
				}
			}
		} else {
			$att = "A";
		}


		return ($att);
	}

	/********************* PRIVATE **********************/
	/**
	 * Calculation of APR Attendance by Travel time and Roster Type
	 */

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
					$LoginAtt = "HWP";
				} else {
					$LoginAtt = "A";
				}
			} else if ($roster_type == 1) {
				if (strtotime($travelTime) >= strtotime("8:00")) {
					$LoginAtt = "P";
				} else if (strtotime($travelTime) < strtotime("8:00") && strtotime($travelTime) >= strtotime("4:30")) {
					$LoginAtt = "HWP";
				} else {
					$LoginAtt = "A";
				}
			} else if ($roster_type == 3) {
				if (strtotime($travelTime) >= strtotime("4:30")) {
					$LoginAtt = "H";
				} else {
					$LoginAtt = "A";
				}
			} else if ($roster_type == 4) {
				if (strtotime($travelTime) >= strtotime("8:00")) {
					$LoginAtt = "P";
				} else if (strtotime($travelTime) < strtotime("8:00") && strtotime($travelTime) >= strtotime("4:30")) {
					$LoginAtt = "HWP";
				} else {
					$LoginAtt = "A";
				}
			}
		} else {
			$LoginAtt = "A";
		}

		return $LoginAtt;
	}

	function get_apr_window_ATND($travelTime, $roster_type, $att_apr)
	{
		echo "att_apr" . $att_apr;
		echo "travelTime" . $travelTime;
		if ($roster_type == '1') {
			if ($travelTime >=  "09:00") {
				$att = "P";
			} else if ($travelTime < "09:00" && $travelTime >= "08:45") {
				if ($att_apr < "09:00") {
					$att = "P(Short Leave)";
				} else {
					$att = "P(Short Login)";
				}
			} else if ($travelTime >= "04:30" && $travelTime < "08:45") {
				$att = "HWP";
			} else {
				$att = "A";
			}
		} else {
			$att = "A";
		}
		echo "att" . $att;
		return $att;
	}
	function get_final_apr_ATND($apr_atnd, $aprw_atnd, $des)
	{
		if (($des == "CSE" || $des == "C.S.E." || $des == "Sr. C.S.E" || $des == "C.S.E" || $des == "Senior Customer Care Executive" || $des == "Customer Care Executive" || $des == "CSA" || $des == "Senior CSA")) {
			if ($apr_atnd == "A" || $apr_atnd == " ") {
				$FinalAtt = $apr_atnd;
			} else {
				if ($apr_atnd == "P" && $aprw_atnd == "P(Short Login)") {
					$FinalAtt = $aprw_atnd;
				} else if ($apr_atnd == "P" && $aprw_atnd == "P(Short Leave)") {
					$FinalAtt = $aprw_atnd;
				} else if ($apr_atnd == "P" && $aprw_atnd == "HWP") {
					$FinalAtt = $aprw_atnd;
				} else if ($apr_atnd == "HWP" && $aprw_atnd == "A") {
					$FinalAtt = $aprw_atnd;
				} else if ($apr_atnd == "HWP" && $aprw_atnd == "P(Short Login)") {
					$FinalAtt = $apr_atnd;
				} else if ($apr_atnd == "P" && $aprw_atnd == "A") {
					$FinalAtt = "A";
				} else {
					$FinalAtt = $apr_atnd;
				}
			}
		} else {
			$FinalAtt = $apr_atnd;
		}
		return $FinalAtt;
	}
	/********************* PRIVATE **********************/
	/**
	 * Calculation of Biometric & APR Final Attendance 
	 */
	function get_apr_bio_ATND($bio_atnd, $apr_atnd, $des)
	{
		if (($des == "CSE" || $des == "C.S.E." || $des == "Sr. C.S.E" || $des == "C.S.E" || $des == "Senior Customer Care Executive" || $des == "Customer Care Executive" || $des == "CSA" || $des == "Senior CSA")) {
			if ($bio_atnd == "L" || $bio_atnd == "A") {
				$FinalAtt = $bio_atnd;
			} else {

				if ($bio_atnd == " " && $apr_atnd == " ") {
					$FinalAtt = " ";
				} else if ($bio_atnd == "P" && $apr_atnd == "P") {
					$FinalAtt = "P";
				} else if ($bio_atnd == "P" && $apr_atnd == "HWP") {
					$FinalAtt = "HWP";
				} else if ($bio_atnd == "P" && $apr_atnd == "A") {
					$FinalAtt = "A";
				} else if (($bio_atnd == "H" || $bio_atnd == "HWP") && $apr_atnd == "P") {
					$FinalAtt = $bio_atnd;
				} else if (($bio_atnd == "H" || $bio_atnd == "HWP") && $apr_atnd == "A") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P(Short Login)" && $apr_atnd == "P") {
					$FinalAtt = $bio_atnd;
				}
				/////////////////MITHILESH///////////////////////////////////////////////////////////////////////	            
				else if ($bio_atnd == "P" && $apr_atnd == "P(Short Login)") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "H" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $bio_atnd; //H
				} else if ($bio_atnd == "HWP" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $bio_atnd;
				} else if ($bio_atnd == "P(Short Login)" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P(Short Login)" && $apr_atnd == "P(Short Login)") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P(Short Leave)" && $apr_atnd == "P(Short Leave)") {
					$FinalAtt = $bio_atnd;
				}
				//////////////////////////////////////////////////////////////////////////////////////////////////
				else if ($bio_atnd == "P(Short Login)" && $apr_atnd != "P") {
					$FinalAtt = $apr_atnd;
				} else if ($bio_atnd == "P(Short Leave)" && $apr_atnd == "P") {
					$FinalAtt = $bio_atnd;
				} else if ($bio_atnd == "P(Short Leave)" && $apr_atnd != "P") {
					if ($bio_atnd == "P(Short Leave)" && $apr_atnd == "HWP") {
						$FinalAtt = 'H';
					} else {
						$FinalAtt = $apr_atnd;
					}
				} else {
					$FinalAtt = $bio_atnd;
				}
			}
		} else {
			$FinalAtt = $bio_atnd;
		}
		return $FinalAtt;
	}

	/********************* PRIVATE **********************/
	/**
	 * Calculation of final adjusted Attendace Calculation
	 */
	function get_Final_atnd($atnd, $EmpID, $date, $des)
	{
		if ($atnd == "H" || $atnd == "HWP") {
			$finalAtnd = $atnd;
		} else if ($atnd == "L" || $atnd == "A") {
			$finalAtnd = $atnd;
		} else if ($atnd == "P(Short Login)" || $atnd == "P(Short Leave)" || $atnd == "P(Short Login)(2)" || $atnd == "P(Short Leave)(2)") {
			// For per day check
			$iShortLogin = 0;
			$iShortLeave = 0;
			$myDB = new MysqliDb();
			$conn = $myDB->dbConnect();
			$month = intval(date('m', strtotime($date)));
			$year = intval(date('Y', strtotime($date)));
			$select = 'select D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master where EmployeeID = ? and month= ? and Year = ?  limit 1';
			$selectQury = $conn->prepare($select);
			$selectQury->bind_param("sii", $EmpID, $month, $year);
			$selectQury->execute();
			$ds_shcount = $selectQury->get_result();
			if ($ds_shcount->num_rows > 0 && $ds_shcount) {
				foreach ($ds_shcount as $key => $val) {
					if (intval(substr($key, 1, strlen($key))) < intval(date('d', strtotime($date)))) {

						if (strpos($val, 'P(Short Leave)') !== false) {
							$str_temp_check = explode(")(", $val);
							$str_temp_check = explode(")", $str_temp_check[1]);
							$iShortLeave = intval($str_temp_check[0]);
							//$iShortLeave ++;
						}
						if (strpos($val, 'P(Short Login)') !== false) {

							//$iShortLogin++;
							$str_temp_check = explode(")(", $val);
							$str_temp_check = explode(")", $str_temp_check[1]);
							$iShortLogin = intval($str_temp_check[0]);
						}
					}
				}
			}
			/*$iShortLogin = 0;
			$iShortLeave = 0;
			
			if(date('m',strtotime($date)) == date('m',time()))							
			{
				$iShortLogin = $this->i_cur_ShortLogin;
				$iShortLeave = $this->i_cur_ShortLeave;
			}
			else
			{
				$iShortLogin = $this->i_prev_ShortLogin;
				$iShortLeave = $this->i_prev_ShortLeave;
			}*/
			if (($des == "CSE" || $des == "C.S.E." || $des == "Sr. C.S.E" || $des == "C.S.E" || $des == "Senior Customer Care Executive" || $des == "Customer Care Executive" || $des == "CSA" || $des == "Senior CSA")) {
				if ($atnd == "P(Short Login)") {
					$iShortLogin++;
					if ($iShortLogin <= 5) {
						$finalAtnd = $atnd . '(' . $iShortLogin . ')';
					} else {
						$finalAtnd = $atnd = "H";
					}
				} else if ($atnd == "P(Short Login)(2)") {
					if ($iShortLogin >= 4) {
						$finalAtnd = $atnd = "H";
					} else {
						$iShortLogin = $iShortLogin + 2;
						if ($iShortLogin <= 5) {
							//$finalAtnd = $atnd.'('.$iShortLogin.')';
							$finalAtnd = $atnd = "P(Short Login)" . '(' . $iShortLogin . ')';
						} else {
							$finalAtnd = $atnd = "H";
						}
					}
				} else {
					$finalAtnd = $atnd = "H";
				}
			} else {
				if ($atnd == "P(Short Login)") {
					$iShortLogin++;

					if ($iShortLogin <= 3) {
						$finalAtnd = $atnd . '(' . $iShortLogin . ')';
					} else {
						if ($iShortLeave == 0) {
							$iShortLeave++;
							$atnd = "P(Short Leave)";

							$finalAtnd = $atnd . '(' . $iShortLeave . ')';
						} else if ($iShortLeave >= 1) {
							$finalAtnd = $atnd = 'H';
						}
					}
				} else if ($atnd == "P(Short Login)(2)") {
					if ($iShortLogin >= 3) {
						$finalAtnd = $atnd = 'H';
					} else {
						$iShortLogin = $iShortLogin + 2;

						if ($iShortLogin <= 3) {
							//$finalAtnd = $atnd.'('.$iShortLogin.')';
							$finalAtnd = $atnd = "P(Short Login)" . '(' . $iShortLogin . ')';
						} else {
							if ($iShortLeave == 0) {
								$iShortLeave++;
								$atnd = "P(Short Leave)";

								$finalAtnd = $atnd . '(' . $iShortLeave . ')';
							} else if ($iShortLeave >= 1) {
								$finalAtnd = $atnd = 'H';
							}
						}
					}
				} else if ($atnd == "P(Short Leave)") {
					$iShortLeave++;
					if ($iShortLeave == 1) {

						$finalAtnd = $atnd . '(' . $iShortLeave . ')';
					} else if ($iShortLeave > 1) {
						$finalAtnd = $atnd = 'H';
					}
				} else if ($atnd == "P(Short Leave)(2)") {
					$finalAtnd = $atnd = 'H';
				}
			}
			if (date('m', strtotime($date)) == date('m', time())) {
				$this->i_cur_ShortLogin = $iShortLogin;
				$this->i_cur_ShortLeave = $iShortLeave;
			} else {

				$this->i_prev_ShortLogin = $iShortLogin;
				$this->i_prev_ShortLeave = $iShortLeave;
			}
		} else {
			$finalAtnd = $atnd;
		}
		return $finalAtnd;
	}


	/********************* PRIVATE **********************/
	/**
	 * Shift calculation for 2 month if not found
	 */
	private function ShiftCalc($roster)
	{
		$shift = $rin = $rout = "";

		$tbin = "00:00";
		$tbout = "00:00";
		$flag = 0;

		if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2" || $roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9") {
			$rin = trim(substr($roster, 0, strpos($roster, '-')));
			$rout = trim(substr($roster, strpos($roster, '-') + 1, (strlen($roster) - (strpos($roster, '-') + 1))));
		} else {
			$rin = "";
			$rout = "";
		}



		if ($rin == "" || $rout == "") {
			$shift = "Day";
		} else {

			$trin = date('H:i', strtotime($rin));
			$trout = date('H:i', strtotime($rout));


			if ($trin > $trout)
				$shift = "Night";
			else
				$shift = "Day";
		}

		return $shift;
	}
	/********************* PRIVATE **********************/
	/**
	 * Conver a Date Range to an Array
	 */
	private function getDatesFromRange($start, $end, $format = 'd')
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



	/********************* PRIVATE **********************/
	/**
	 * Helping Calculation for In and Out Biometric for Nigth Shift Employee
	 */


	private function ShiftInOurOut($roster, $shift, $time, $date)
	{

		if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2" || $roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9") {
			$hr =  $min = $sec = "";
			$rin = date('H:i:s', strtotime(substr($roster, 0, strpos($roster, '-'))));
			$rout = date('H:i:s', strtotime(substr($roster, strpos($roster, '-') + 1, (strlen($roster) - (strpos($roster, '-') + 1)))));
			$time = date('H:i:s', strtotime($time));
			$flag = 0;
			if ($shift == "In") {
				if ($rin == $time) {
					$flag = 1;
					$res = $time;
				} else {

					$tt = date('Y-m-d H:i:s', strtotime($date . ' ' . $rin . ' + 4 hours'));
					$tt1 = date('Y-m-d H:i:s', strtotime($date . ' ' . $rin . ' - 4 hours'));
					$time = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
					if ($time >=  $tt1 && $time <= $tt) {
						$res = $time;
						$flag = 1;
					}
				}
			} else if ($shift == "Out") {
				if ($rout == $time) {
					$flag = 1;
					$res = $time;
				} else {

					$tt = date('Y-m-d H:i:s', strtotime($date . ' ' . $rout . ' + 4 hours'));
					$tt1 = date('Y-m-d H:i:s', strtotime($date . ' ' . $rout . ' - 4 hours'));
					$time = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
					if ($time >=  $tt1 && $time <= $tt) {
						$res = $time;
						$flag = 1;
					}
				}
			}
			#endregion
		}
		return $res;
	}

	/********************* PRIVATE **********************/
	/**
	 * Calculation for In and Out Biometric for Nigth Shift Employee
	 */

	private function calcAtnd($date, $__RosterArr, $__Login, $__LogOut)
	{

		if ($__Login[$date] != "" && $__LogOut[$date] != "") {
			$login = $this->get_timestring($date, $__LogOut);
		} else if ($__Login[$date] == "" && $__LogOut[$date] == "") {
			$login = $this->get_timestring($date, $__LogOut);
		} else {
			$login = $this->ShiftInOurOut($__RosterArr, "In", $__Login[$date], $date);
		}

		$ligalCounter = date('Y-m-d', strtotime($date . ' +1 days'));
		if (strlen($ligalCounter) <= 1)
			$ligalCounter = '0' . $ligalCounter;

		if ($__Login[$ligalCounter] != "" && $__LogOut[$ligalCounter] != "") {
			$logout = $this->get_timestring($ligalCounter, $__Login);
		} else if ($__Login[$ligalCounter] == "" && $__LogOut[$ligalCounter] == "") {
			$logout =  $this->get_timestring($ligalCounter, $__Login);
		} else {
			$logout = $this->ShiftInOurOut($__RosterArr, "Out", $__Login[$ligalCounter], $ligalCounter);
		}
		return array($login, $logout);
	}

	protected function getSeniorUpdatedAttd($dt)
	{
		$res = $exp = "";

		if (count($this->dsUpdatedAtt) > 0) {
			//$fltr = "'" + dt + "'" + " >= DateFrom and '" + dt + "' <= DateTo";
			// dr = dsUpdatedAtt.Tables[0].Select(fltr);

			foreach ($this->dsUpdatedAtt as $key => $value) {
				$date_from = $value['DateFrom'];
				$date_to  = $value['DateTo'];
				$dateFrom = date('Y-m-d', strtotime($date_from));
				$dateTo = date('Y-m-d', strtotime($date_to));
				if ($dt >= $dateFrom && $dt <= $dateTo) {
					if ($value['Exception'] == "Biometric issue") {
						$res = $value['Update_Att'];
						$exp = "Biometric Issue";
					}
				}
			}
			/*if (dr.Length > 0)
                {
                    
                }*/
		}



		return array($res, $exp);
	}
}
$cal_total_user = 0;
$time_start = microtime(true);
$Type = isset($_REQUEST['type']);
$Type2 = cleanUserInput($_REQUEST['type']);
if ($Type) {
	if (strtoupper($Type2) == 'ONE') {
		$empid = isset($_REQUEST['empid']);
		if ($empid) {

			$cal_total_user = 0;
			$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where whole_details_peremp.EmployeeID = ? and emp_status = 'Active'";
			$myDB = new  MysqliDb();
			$conn = $myDB->dbConnect();
			$selectQury = $conn->prepare($strt);
			$selectQury->bind_param("s", $Type2);
			$selectQury->execute();
			$rstl_employees = $selectQury->get_result();
			// $rstl_employees = $myDB->query($strt);
			if ($rstl_employees && $rstl_employees->num_rows > 0) {
				foreach ($rstl_employees as $glob_key => $glob_val) {

					if (isset($glob_val['EmployeeID'])) {


						$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
						$cal_total_user++;
						/*$arr = get_defined_vars();
						print_r($arr);*/
					}
				}
			}
		}
	} elseif (strtoupper($Type2) == 'ALL') {
		$cal_total_user = 0;
		$dt = date('d') / 1;
		$dt = $dt - 2;
		$dt = 'D' . $dt;
		$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID inner join calc_atnd_master cl on whole_details_peremp.EmployeeID = cl.EmployeeID where  $dt is null and Month=Month(curdate()) and cm_id not in (88) and cm_id in (27,58,83,45,46,126,90)
	  union
select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,
status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join 
status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID 
 where
whole_details_peremp.EmployeeID not in (select EmployeeID from calc_atnd_master where  Month=Month(curdate()))  and  cm_id not in (88) and cm_id in (27,58,83,45,46,126,90)";
		/*$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where  emp_status = 'Active' and (cm_id not in (88)) or (cm_id = 88 and df_id!=74)";*/
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {

					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	} elseif (strtoupper($Type2) == 'ALLSR') {
		$cal_total_user = 0;
		$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where  emp_status = 'Active' and des_id not in (9,12) and cm_id not in (88)";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {

					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	} elseif (strtoupper($Type2) == 'ALLCSA') {
		$cal_total_user = 0;
		$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where  emp_status = 'Active' and des_id in (9,12) and cm_id not in (88)";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {

					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	} elseif (strtoupper($Type2) == 'BKTBL') {
		$cal_total_user = 0;
		$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join backdated_calc_tab on whole_details_peremp.EmployeeID = backdated_calc_tab.EmployeeID inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where  emp_status = 'Active'";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {

					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	} elseif (strtoupper($Type2) == 'EXCTBL') {
		$cal_total_user = 0;
		$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT from whole_details_peremp inner join exception_calculation_biometric on whole_details_peremp.EmployeeID = exception_calculation_biometric.EmployeeID inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where  emp_status = 'Active'";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {
					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	} elseif (strtoupper($Type2) == 'INRES') {
		$cal_total_user = 0;
		$strt = "select distinct whole_dump_emp_data.EmployeeID,whole_dump_emp_data.designation,whole_dump_emp_data.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT,dol from whole_dump_emp_data inner join status_table on whole_dump_emp_data.EmployeeID = status_table.EmployeeID inner join exit_emp on whole_dump_emp_data.EmployeeID = exit_emp.EmployeeID where  emp_status = 'InActive' and cast(dol as date)>= date_sub(curdate(), interval 2 day)  and cast(dol as date)< curdate() and disposition = 'RES'";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {
					$date1_calc = date('Y-m-d', strtotime('-3 days'));
					$date2_calc = date('Y-m-d', time());

					$cal = new BioMetric($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['designation'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
					$cal_total_user++;
					/*$arr = get_defined_vars();
					print_r($arr);*/
				}
			}
		}
	}
}


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
if ($Type2 == "ALL") {
	settimestamp('CalcRange_apr' . $Type2, 'END');
}
//execution time of the script
echo '<bbr /><b>Total Execution Time:</b> ' . round($execution_time, 2) . ' Seconds and Total User are = ' . $cal_total_user;
