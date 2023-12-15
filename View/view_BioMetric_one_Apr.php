<style>
	{
		box-sizing: border-box;
	}

	.dateli_inner {
		padding: 0px;
		background-color: #ffffff;
		transition: transform .1s;
		overflow: hidden !important;

	}

	.dateli_inner:hover {
		-ms-transform: scale(1.5);
		-webkit-transform: scale(1.5);
		transform: scale(1.5);
		width: 200px !important;
		height: 250px !important;
		border: #847b82 1px solid !important;
		position: absolute !important;
	}

	form p {
		margin-bottom: -5px;
		text-align: left;
		padding-left: 5px;
	}
</style>

<?php

$year = $month = $resultBio = 0;
// Server Config file
require_once(__dir__ . '/../Config/init.php');
error_reporting(0);
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid']) || empty($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	}

	if ($_SESSION["__cm_id"] == '27' || $_SESSION["__cm_id"] == '58' || $_SESSION["__cm_id"] == '83' || $_SESSION["__cm_id"] == '45' || $_SESSION["__cm_id"] == '46' || $_SESSION["__cm_id"] == '126' || $_SESSION["__cm_id"] == '90' || $_SESSION["__cm_id"] == '47') {
	} else {
		if (!isset($_POST['p_EmpID'])) {
			if ($_SESSION["__view_Bio"] == "") {
				$location = URL . 'View/atnd';
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
//echo 'test page';
$sr_d_startDay = date('Y-m-d', strtotime('next monday'));
$sr_d_EndDay = date('Y-m-d', strtotime($sr_d_startDay . ' +6 days'));
if (isset($_POST['btnSavePref'])) {
	$EmpID = $_SESSION['__user_logid'];
	$Week = $sr_d_startDay . ' To ' . $sr_d_EndDay; // ddWeek.SelectedValue;
	$First =  $_POST['txtweekoff_1'];
	$Second = $_POST['txtweekoff_2'];
	$wf = $sr_d_startDay;
	$wt = $sr_d_EndDay;
	$validate = 0;
	if ($First == "No Weekoff Required") {
		$count_wpf = 0;
		$myDB = new MysqliDb();
		$rstl = $myDB->query('select count(*) counts  from rosterpref where month(WF) =  month("' . $wf . '") and  year(WF) =  year("' . $wf . '")    and FirstPre = "No Weekoff Required" and EmpID = "' . $EmpID . '"');
		if (count($rstl) > 0 && $rstl) {
			$count_wpf = intval($rstl[0]['counts']);
		}

		$myDB = new MysqliDb();
		$rstl = $myDB->query('select count(*) counts  from exception where month(DateFrom) =  ' . intval(date('m', strtotime($sr_d_startDay))) . ' and  year(DateFrom) =  ' . intval(date('Y', strtotime($sr_d_startDay))) . ' and Exception = "Working on Weekoff" and EmployeeID = "' . $EmpID . '" and  "Decline"  not in(MgrStatus, HeadStatus)');
		if (count($rstl) > 0 && $rstl) {
			$count_wpf += intval($rstl[0]['counts']);
		}

		if ($count_wpf >= 2) {
			$validate = 1;
		}
	}
	if ($validate == 0) {
		$myDB = new MysqliDb();
		$res = $myDB->query('call sp_InsertRosterPref("' . $EmpID . '","' . $Month . '","' . $Week . '","' . $First . '","' . $Second . '","' . $wf . '","' . $wt . '")');
		$mysqlError = $myDB->getLastError();
		if (empty($mysqlError)) {
			echo "<script>$(function(){ toastr.success('Request Submited Successfuly'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.error('Request Not  Submited. $mysqlError'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Request Not  Submited. You already cross the limit for <b>No Week Off Required </b> preference. Select other option and try again.'); }); </script>";
	}
}
$year = null;
$month = null;

if (null == $year && isset($_POST['year'])) {

	$year = $_POST['year'];
} else if (null == $year) {

	$year = date("Y", time());
}
if (null == $month && isset($_POST['date'])) {
	$getDate  =  explode('%', $_POST['date']);

	$month = date('m', strtotime($getDate[0]));
	$year = date('Y', strtotime($getDate[0]));
}
if (null == $month && isset($_POST['month'])) {

	$month = $_POST['month'];
} else if (null == $month) {

	$month = date("m", time());
}
$date1_calc  = null;
$date2_calc = null;

if (isset($_POST['p_EmpID'])) {

	$myDB = new MysqliDb();
	//echo "select * from whole_details_peremp where EmployeeID = '".$_POST['p_EmpID']."'"
	$getUserDetails = $myDB->query("select * from whole_details_peremp where EmployeeID = '" . $_POST['p_EmpID'] . "'");
	if ($getUserDetails) {
		foreach ($getUserDetails as $data_key => $data_value) {
			define("EmployeeID_forPage", $data_value['EmployeeID']);

			define("EmployeeName_forPage", $data_value['EmployeeName']);
			define("EmployeeDesign_forPage", $data_value['designation']);
			define("Employeestatus_forPage", $data_value['status']);
			define("Employeecm_id_forPage", $data_value['cm_id']);
			$_SESSION["__view_Bio"] = $_POST['p_EmpID'];
			if ($data_value['cm_id'] == '27' || $data_value['cm_id'] == '58' || $data_value['cm_id'] == '83' || $data_value['cm_id'] == '45' || $data_value['cm_id'] == '46' || $data_value['cm_id'] == '126' || $data_value['cm_id'] == '90') {
			} else {
				$location = URL . 'View/atnd';
				//header("Location: $location");
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		}
	} else {
		define("EmployeeID_forPage", '');

		define("EmployeeName_forPage", '');
		define("EmployeeDesign_forPage", '');
	}
} else {
	$myDB = new MysqliDb();
	if ($_SESSION["__view_Bio"] == "") {
		$getUserDetails = $myDB->query("select * from whole_details_peremp where EmployeeID = '" . $_SESSION['__user_logid'] . "'");
	} else {
		$getUserDetails = $myDB->query("select * from whole_details_peremp where EmployeeID = '" . $_SESSION['__view_Bio'] . "'");
	}

	if ($getUserDetails) {
		foreach ($getUserDetails as $data_key => $data_value) {
			define("EmployeeID_forPage", $data_value['EmployeeID']);

			define("EmployeeName_forPage", $data_value['EmployeeName']);
			define("EmployeeDesign_forPage", $data_value['designation']);
			define("Employeestatus_forPage", $data_value['status']);
		}
	}
}

class BioMetric
{
	/////////////////MITHILESH START/////////////////////////////////////
	private $APR_In = "not in ";
	private $APR_Out = "not out";
	/////////////////MITHILESH START/////////////////////////////////////	

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
	public $monthCalendar = array();

	private $pt_bio = array();

	public function __construct($EmployeeID, $DateFrom, $DateTo, $Emd_des, $Emp_status, $emp_dod, $emp_module)
	{
		// Fetch data for Roster in given range;

		// Sevrer 
		$myDB = new MysqliDb();
		/*$ds_roster = $myDB->query('select str_to_date(DateOn,"%Y-%c-%e") as DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID ="'.$EmployeeID.'" and cast(str_to_date(DateOn,"%Y-%c-%e") as  date) between cast("'.$DateFrom.'" as  date) and cast("'.$DateTo.'" as  date)');
            */
		$ds_roster = $myDB->query('select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID ="' . $EmployeeID . '" and DateOn between "' . $DateFrom . '" and "' . $DateTo . '"');

		if (count($ds_roster) > 0 && $ds_roster) {

			foreach ($ds_roster as $key => $val) {
				$date_for = $val['DateOn'];
				$this->Roster_type[$date_for] = trim($val['type_']);

				if ($val['type_'] == "4") {
					$temp_shft_1 =  explode("|", $val['InTime']);
					$temp_shft_2 =  explode("|", $val['OutTime']);
					$this->RosterIn[$date_for][0] = $temp_shft_1[0];
					$this->RosterOut[$date_for][0] = $temp_shft_1[1];

					$this->RosterIn[$date_for][1] = $temp_shft_2[0];
					$this->RosterOut[$date_for][1] = $temp_shft_2[1];
				} else {
					$this->RosterIn[$date_for] = trim($val['InTime']);
					$this->RosterOut[$date_for] = trim($val['OutTime']);
				}
				unset($date_for);
			}
			unset($ds_roster);
		}
		// Fetch data for BioInOut in given range;
		/*$myDB = new MysqliDb();
            $ds_bioinout = $myDB->query('select DateOn,InTime,OutTime from bioinout where EmpID ="'.$EmployeeID.'" and DateOn between "'.date('Y-m-d',strtotime($DateFrom.' -1 days')).'" and "'.date('Y-m-d',strtotime($DateTo.' +1 days')).'"');
            if (count($ds_bioinout) > 0 && $ds_bioinout)
            {
            	 
                foreach($ds_bioinout as $key => $val)
                {
                	$date_for = $val['bioinout']['DateOn'];
                	$this->InTime[$date_for] = $val['bioinout']['InTime'];
                	$this->OutTime[$date_for] = $val['bioinout']['OutTime'];
					unset($date_for);
					
                }
                
                unset($ds_bioinout);
                
            }*/

		$myDB = new MysqliDb();

		$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . $DateTo . '" as date) Union select EmpID,PunchTime, DateOn from biopunchcurrentdata_pre where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . $DateTo . '" as date)';
		$ds_punchtime = $myDB->query($str_capping);

		// Fetch data for APR  in given range; 
		if (count($ds_punchtime) > 0 && $ds_punchtime) {
			foreach ($ds_punchtime as $key => $value) {
				$this->pt_bio[$value['DateOn']][] = $value['PunchTime'];
			}
		} else {
			$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata_history_new where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . $DateTo . '" as date)';
			$ds_punchtime = $myDB->query($str_capping);

			// Fetch data for APR  in given range; 
			if (count($ds_punchtime) > 0 && $ds_punchtime) {
				foreach ($ds_punchtime as $key => $value) {
					$this->pt_bio[$value['DateOn']][] = $value['PunchTime'];
				}
			}
		}

		// Only for CSA and Sr CSA

		if ($Emd_des == "9" || $Emd_des == "12" || $Emd_des == "33" || $Emd_des == "34" || $Emd_des == "35" || $Emd_des == "36") {

			$myDB = new MysqliDb();
			$ds_downtime = $myDB->query('select sum(time_to_sec(TotalDT)) sec,LoginDate from downtime where EmpID ="' . $EmployeeID . '" and FAStatus ="Approve" and RTStatus ="Approve" and LoginDate between "' . $DateFrom . '" and "' . $DateTo . '" group by LoginDate');
			$DTHour = array();
			if (count($ds_downtime) > 0 && $ds_downtime) {

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
				$strsql = "select " . $str_t . " from hours_hlp where EmployeeID ='" . $EmployeeID . "' and  Type = 'Hours' and month ='" . $h_month . "' and year = '" . $h_year . "' order by id desc limit 1";
				//echo($strsql);
				$myDB = new MysqliDb();
				$dshr = $myDB->query($strsql);
				$error = $myDB->getLastError();

				if (count($dshr) > 0 && $dshr) {
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

			$myDB = new MysqliDb();
			$nonAPR_emp = $myDB->query('select EmployeeID from nonapr_employee  where EmployeeID="' . $EmployeeID . '" and flag=0');
			if (!empty($nonAPR_emp[0]['EmployeeID'])) {
				$this->nonAPR_Employee_status = 1;
			}
			$this->onFloor = $emp_dod;
			$this->ModuleChange = $emp_module;
		}

		// Get all Biometric Exceptions 

		$myDB = new MysqliDb();
		$ds_exception = $myDB->query('select EmployeeID,Exception,DateFrom,DateTo,HeadStatus,IssueType, dispo.Update_Att from exception join exceptiondispo dispo on exception.ID=dispo.expid where Employeeid="' . $EmployeeID . '" and (exception="Biometric issue") and (DateFrom between "' . $DateFrom . '" and "' . $DateTo . '") and MgrStatus="Approve" and HeadStatus !="Decline" order by datefrom;');

		if (count($ds_exception) > 0 && $ds_exception) {

			foreach ($ds_exception as $key => $val) {
				$date_for = $val['DateFrom'];
				$this->dsUpdatedAtt[$date_for] = $val;
				unset($date_for);
			}

			unset($ds_exception);
		}

		// adjusted paidleave ;

		$myDB = new MysqliDb();
		$ds_pl_adj = $myDB->query('select cast(date_used as date) as DateOn from paidleave where EmployeeID ="' . $EmployeeID . '" and cast(str_to_date(date_used,"%Y-%c-%e") as  date) between cast("' . $DateFrom . '" as  date) and cast("' . $DateTo . '" as  date)');
		if (count($ds_pl_adj) > 0 && $ds_pl_adj) {

			foreach ($ds_pl_adj as $key => $val) {
				$date_for = $val['DateOn'];
				$this->pl_adjusted[$date_for] = (empty($va['Paid_Leave']) ? 0 : $val['Paid_Leave']);
				unset($date_for);
			}
			unset($ds_pl_adj);
		}

		// adjusted compensation off;

		/*$myDB = new MysqliDb();
            $ds_co_adj = $myDB->query('select cast(usedOn as date) as DateOn,id from combo_off_master where EmployeeID ="'.$EmployeeID.'" and cast(str_to_date(DateOn,"%Y-%c-%e") as  date) between cast("'.$DateFrom.'" as  date) and cast("'.$DateTo.'" as  date)');
            if (count($ds_co_adj) > 0 && $ds_co_adj)
            {
            	 
                foreach($ds_co_adj as $key => $val)
                {
                	$date_for = $val[0]['DateOn'];
                	$this->co_adjusted[$date_for] = $val['combo_off_master']['id'];
					unset($date_for);
					
                }
                unset($ds_co_adj);
                
            }*/

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
			$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID ='" . $EmployeeID . "' and month ='" . $h_month . "' and year = '" . $h_year . "' limit 1";
			$myDB = new MysqliDb();
			$dshr = $myDB->rawQuery($strsql);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				foreach ($dshr as $key => $val) {
					foreach ($val as $keys => $value) {
						$keyDate = substr($keys, 1, strlen($keys));

						if ($keyDate < 10) {
							$date_for = $h_year . '-' . $h_month . '-0' . $keyDate;
						} else {
							$date_for = $h_year . '-' . $h_month . '-' . $keyDate;
						}
						if (date('m', strtotime($date_for)) == date('m', strtotime($DateFrom))) {
							$this->ATND_cur[$date_for] = $value;
							if ($date_for < $DateFrom) {
								if ($value == "P(Short Login)") {
									$this->i_cur_ShortLogin++;
								} elseif ($value == "P(Short Leave)") {
									$this->i_cur_ShortLeave++;
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
		//echo $EmployeeID.'=>';
		for ($i_date = $start_date; $start_date <= $end_date; $i_date->modify('+1 day')) {
			$date__ = $i_date->format('Y-m-d');
			if (!empty($date__) && $date__ != '1970-01-01' && $date__ <= date('Y-m-d', strtotime($DateTo))) {
				$return_val = $this->__calculation_for_day($date__, $EmployeeID, $Emd_des, $Emp_status);
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
		if ($__date != null) {

			////////////////MITHILESH START//////////////////////////////////
			$myDB = new MysqliDb();
			$qq = "select date,logged_in,logged_out from cosmo_apr where employeeid ='" . $__EmpID . "' and cast(date as date)=cast('" . $__date . "' as date)";
			$ds_APR = $myDB->query($qq);
			//var_dump($ds_APR);
			if (count($ds_APR) > 0 && $ds_APR) {

				foreach ($ds_APR as $key => $val) {
					$date_for = $val['date'];
					$APR_In = trim($val['logged_in']);
					$APR_Out = trim($val['logged_out']);
					//unset($date_for);

				}
				//unset($ds_APR);
			}
			if (!empty($APR_In) || $APR_In != "") {
				$APR_In  = $APR_In;
			} else {
				$APR_In = 'Not Sign In';
			}
			if (!empty($APR_Out)  || $APR_Out != "") {
				$APR_Out  = $APR_Out;
			} else {
				$APR_Out = 'Not Sign Out';
			}
			////////////////MITHILESH END//////////////////////////////////


			//For split shift employee biometric calculation
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

				$i_bioATND = '';
				$i_aprATND = '';
				$i_bioIN1 = null;
				$i_bioOUT1 = null;

				$i_bioIN2 = null;
				$i_bioOUT2 = null;
				$i_bioATND1 = '';
				$i_bioATND2 = '';
				$b_tt1 = '';
				$b_tt2 = '';

				if (!is_numeric($this->RosterIn[$__date][0][0]) || !is_numeric($this->RosterOut[$__date][0][0])) {
					$i_rosterIN[0] = $this->RosterIn[$__date][0];
					$i_rosterOUT[0] = $this->RosterOut[$__date][1];

					$i_rosterIN[1] = $this->RosterIn[$__date][0];
					$i_rosterOUT[1] = $this->RosterOut[$__date][1];
				} else {
					//$bioTemp[$__date] = $this->pt_bio[$__date];
					//$bioTemp[date('Y-m-d',(strtotime($__date.' +1 days')))] = $this->pt_bio[date('Y-m-d',(strtotime($__date.' +1 days')))];


					$bioTemp[$__date] = $this->pt_bio[$__date];
					//$bioTemp[date('Y-m-d',(strtotime($__date.' +1 days')))] = $this->pt_bio[date('Y-m-d',(strtotime($__date.' +1 days')))];


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
				}
				if (!empty($i_rosterIN[0]) && trim($i_rosterIN[0]) != '') {
					if ($i_rosterIN[0] == "WO") {
						$bioTemp[$__date] = $this->pt_bio[$__date];
						if (count($bioTemp) > 0) {
							$i_bioIN1 = $__date . ' ' . $bioTemp[$__date][0];
							/*foreach($bioTemp[$__date] as $K=>$V)
						{
							$pt_temp[] = $__date.' '.$V;
	 					}*/
						}
					}
				}

				$hour_attr = $this->calcAPR($__status, $__des, $__date);
				$atnd_day = $this->ATND_cur[$__date];


				if (!empty($i_bioIN1)) {
					$i_bioIN1  = date('H:i:s', strtotime($i_bioIN1));
				} else {
					$i_bioIN1 = 'Not Sign In';
				}
				if (!empty($i_bioOUT1)) {
					$i_bioOUT1  = date('H:i:s', strtotime($i_bioOUT1));
				} else {
					$i_bioOUT1 = 'Not Sign Out';
				}
				if (empty($b_tt1)) {
					$b_tt1 = "00:00";
				}

				if (!empty($i_bioIN2)) {
					$i_bioIN2  = date('H:i:s', strtotime($i_bioIN2));
				} else {
					$i_bioIN2 = 'Not Sign In';
				}
				if (!empty($i_bioOUT2)) {
					$i_bioOUT2  = date('H:i:s', strtotime($i_bioOUT2));
				} else {
					$i_bioOUT2 = 'Not Sign Out';
				}
				if (empty($b_tt2)) {
					$b_tt2 = "00:00";
				}

				if ($hour_attr == null || $hour_attr == '-' || $hour_attr == '00:00') {
					$hour_attr == '00:00';
				}
				if (empty($hour_attr)) {
					$hour_attr = '00:00';
				}



				if (!empty($atnd_day)) {
					if ($__des == "9" || $__des == "12" || $__des == "33" || $__des == "34" || $__des == "35" || $__des == "36") {
						$cellContent_Day = '<p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>Roster1  <kbd> ' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '</kbd></p><p>In Time <kbd>' . $i_bioIN1 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT1 . '</kbd></p><p> Login <kbd>' . $b_tt1 . '</kbd></p><p>Roster2  <kbd>' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '</kbd></p><p>In Time <kbd>' . $i_bioIN2 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT2 . '</kbd></p><p> Login <kbd>' . $b_tt2 . '</kbd></p>';
					} else {
						$cellContent_Day = '<p>Roster1  <kbd> ' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '</kbd></p><p>In Time <kbd>' . $i_bioIN1 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT1 . '</kbd></p><p> Login <kbd>' . $b_tt1 . '</kbd></p><p>Roster2  <kbd>' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '</kbd></p><p>In Time <kbd>' . $i_bioIN2 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT2 . '</kbd></p><p> Login <kbd>' . $b_tt2 . '</kbd></p>';
					}


					//$cellContent = '<span class="lbl_date" data-toggle="tooltip"  '.$cellContent_Day.' >'.$atnd_day.'</span>';
					$cellContent_etrs = '';
					if ($__date <= date('Y-m-d')) {
						$cellContent_etrs = '<section class="excep_alrt"></section>' . '<span class="dt_all hidden" data-date="' . $__date . '" data-atnd="' . $atnd_day . '" data-roster="' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '|' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '" data-rtype="' . $this->Roster_type[$__date] . '" data-apr="' . $hour_attr . '" data-des="' . $__des . '" data-in="' . $i_bioIN1 . '|' . $i_bioIN2 . '" data-out="' . $i_bioOUT1 . '|' . $i_bioOUT2 . '" data-biohour="' . $b_tt1 . '|' . $b_tt2 . '"></span>';
					}

					$cellContent = '<span class="lbl_date" data-toggle="tooltip"  >' . $atnd_day . '</span><p><kbd>' . $cellContent_Day . ' </kbd></p>' . $cellContent_etrs;
				} else {
					if ($__des == "9" || $__des == "12" || $__des == "33" || $__des == "34" || $__des == "35" || $__des == "36") {
						if (empty($hour_attr))
							$hour_attr = '-';

						$cellContent_Day = '<p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>Roster1  <kbd> ' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '</kbd></p><p>In Time <kbd>' . $i_bioIN1 . '</kbd></p><p> OutTime <kbd>' . $i_bioOUT1 . '</kbd></p><p> Login <kbd>' . $b_tt1 . '</kbd></p><p>Roster2  <kbd>' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '</kbd></p><p>In Time <kbd>' . $i_bioIN2 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT2 . '</kbd></p><p> Login <kbd>' . $b_tt2 . '</kbd></p>';
					} else {
						$cellContent_Day = '<p>Roster1  <kbd> ' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '</kbd></p><p>In Time <kbd>' . $i_bioIN1 . '</kbd></p><p> OutTime <kbd>' . $i_bioOUT1 . '</kbd></p><p> Login <kbd>' . $b_tt1 . '</kbd></p><p>Roster2  <kbd>' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '</kbd></p><p>In Time <kbd>' . $i_bioIN2 . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT2 . '</kbd></p><p> Login <kbd>' . $b_tt2 . '</kbd></p>';
					}


					//$cellContent = '<span class="lbl_date" data-toggle="tooltip"  >'.$atnd_day.'</span><p><kbd>'.$cellContent_Day.' </kbd></p>';
					$cellContent_etrs = '';
					if ($__date <= date('Y-m-d')) {
						$cellContent_etrs = '<section class="excep_alrt"></section>' . '<span class="dt_all hidden" data-date="' . $__date . '" data-atnd="' . $atnd_day . '" data-roster="' . $this->RosterIn[$__date][0] . '-' . $this->RosterOut[$__date][0] . '|' . $this->RosterIn[$__date][1] . '-' . $this->RosterOut[$__date][1] . '" data-rtype="' . $this->Roster_type[$__date] . '" data-apr="' . $hour_attr . '" data-des="' . $__des . '" data-in="' . $i_bioIN1 . '|' . $i_bioIN2 . '" data-out="' . $i_bioOUT1 . '|' . $i_bioOUT2 . '" data-biohour="' . $b_tt1 . '|' . $b_tt2 . '"></span>';
					}

					$cellContent = '<span class="lbl_date" data-toggle="tooltip"  >' . $atnd_day . '</span><p><kbd>' . $cellContent_Day . '</kbd></p>' . $cellContent_etrs;
				}
			}

			//For rest employee biometric calculation

			else {


				$this->finalAtnd = '-';
				$i_rosterattr = '-';
				if (isset($this->RosterIn[$__date])) {
					$i_rosterattr = $this->RosterIn[$__date] . '-' . $this->RosterOut[$__date];
				} else {
					$this->RosterIn[$__date] = '-';
					$i_rosterattr = "-";
				}

				if (!is_numeric($this->RosterIn[$__date][0]) || !is_numeric($this->RosterOut[$__date][0])) {
					$i_rosterIN = $this->RosterIn[$__date][0];
					$i_rosterOUT = $this->RosterOut[$__date][0];
				} else {
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
				}


				if (!empty($i_rosterIN) && trim($i_rosterIN) != '') {
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
						} else {
							$pt_temp = array();
							if (count($bioTemp[$__date]) > 0) {
								foreach ($bioTemp[$__date] as $K => $V) {
									$pt_temp[] = $__date . ' ' . $V;
								}
							}
							for ($i = 0; $i < count($pt_temp); $i++) {

								$punchTime = "";
								if (strtotime($pt_temp[$i])) {
									$punchTime = $pt_temp[$i];
								}
								if (!empty($punchTime)) {
									if ($inflag == 0) {
										if (date('Y-m-d', strtotime($punchTime)) == $__date) {
											$i_bioIN = $punchTime;
											$inflag = 1;
										}
									} else {
										if (date('Y-m-d', strtotime($punchTime)) == $__date) {
											if ($i == (count($pt_temp) - 1)) {
												$i_bioOUT = $punchTime;
											}
										}
									}
								}
							}
						}

						if (empty($i_bioIN) && empty($i_bioOUT)) {
						} elseif (empty($i_bioIN) || empty($i_bioOUT)) {
						} elseif ($i_bioIN  > $i_bioOUT) {
							$i_bioOUT = null;
						} elseif ($this->check_itime_diffrence($i_bioIN, $i_bioOUT) <= "01:00:00") {
							$i_bioOUT = null;
						} else {
							$b_tt = $this->check_itime_diffrence($i_bioIN, $i_bioOUT);
							//$tt = $this->get_inshift_time($i_rosterIN,$i_rosterOUT,$i_bioIN,$i_bioOUT,$this->Roster_type[$__date]);	

						}
					}

					$hour_attr = $this->calcAPR($__status, $__des, $__date);
					if (!empty($hour_attr) || $hour_attr != "") {
						$hour_attr = $hour_attr;
					} else {
						$hour_attr = "00:00";
					}
					$hour_attr = $hour_attr . " Hr";
					if (!empty($i_bioIN)) {
						$i_bioIN  = date('H:i:s', strtotime($i_bioIN));
					} else {
						$i_bioIN = 'Not Sign In';
					}
					if (!empty($i_bioOUT)) {
						$i_bioOUT  = date('H:i:s', strtotime($i_bioOUT));
					} else {
						$i_bioOUT = 'Not Sign Out';
					}
					if (empty($b_tt)) {
						$b_tt = "00:00";
					}
					$atnd_day = $this->ATND_cur[$__date];

					if (!empty($atnd_day)) {
						if ($__des == "9" || $__des == "12" || $__des == "33" || $__des == "34" || $__des == "35" || $__des == "36") {
							$cellContent_Day = '<p>Roster  <kbd>' . $i_rosterattr . '</kbd></p><p>APR In<kbd> ' . $APR_In . '</kbd></p><p>APR Out<kbd> ' . $APR_Out . '</kbd></p><p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> Out Time <kbd>' . $i_bioOUT . '</kbd></p>';
						} else {
							$cellContent_Day = '<p>Roster  <kbd> ' . $i_rosterattr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT . '</kbd></p>';
						}

						$cellContent = '<span class="lbl_date" data-toggle="tooltip"  >' . $atnd_day . '</span>' . $cellContent_Day . '<p> Login <kbd>' . $b_tt . ' Hr</kbd></p>';
					} else {
						if ($__des == "9" || $__des == "12" || $__des == "33" || $__des == "34" || $__des == "35" || $__des == "36") {
							if (empty($hour_attr))
								$hour_attr = '-';

							$cellContent_Day = '<p>Roster  <kbd> ' . $i_rosterattr . '</kbd></p><p>APR In<kbd> ' . $APR_In . '</kbd></p><p>APR Out<kbd> ' . $APR_Out . '</kbd></p><p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> Out Time <kbd>' . $i_bioOUT . '</kbd></p>';
						} else {
							$cellContent_Day = '<p>Roster  <kbd> ' . $i_rosterattr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT . '</kbd></p>';
						}

						$cellContent = '<span class="lbl_date" data-toggle="tooltip"  >' . $atnd_day . '</span>' . $cellContent_Day . '<p> Login <kbd>' . $b_tt . ' Hr</kbd></p>';
					}
					$content_temp = '';
					if ($__date <= date('Y-m-d')) {
						$content_temp = '<section class="excep_alrt"></section>' . '<span class="dt_all hidden" data-date="' . $__date . '" data-atnd="' . $atnd_day . '" data-roster="' . $i_rosterattr . '" data-rtype="' . $this->Roster_type[$__date] . '" data-apr="' . $hour_attr . '" data-des="' . $__des . '" data-in="' . $i_bioIN . '" data-out="' . $i_bioOUT . '" data-biohour="' . $b_tt . '"></span>';
					}
					$cellContent = $cellContent . $content_temp;
				}
			}

			$this->monthCalendar[$__date] = $cellContent;
		}
	}


	/********************* PRIVATE **********************/
	/**
	 * APR  calculation 
	 */

	private function calcAPR($status, $des, $date)
	{
		if (($des == "9" || $des == "12" || $des == "33" || $des == "34" || $des == "35" || $des == "36")) {

			if (($status < 5  &&  $date >= date('Y-m-d', strtotime($this->ModuleChange))) || $this->nonAPR_Employee_status == 1) {
				$hour_attr = "--:--";
			} else {
				if (!empty($this->onFloor) && !empty($this->ModuleChange) &&   $date >= date('Y-m-d', strtotime($this->ModuleChange)) && $date < date('Y-m-d', strtotime($this->onFloor)) && strtotime($this->ModuleChange) && strtotime($this->onFloor)) {

					$hour_attr = '--:--';
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
		if ($type == "H") {
			$myDB = new MysqliDb();
			$app = '';
			$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Half Day','" . $date . "')");
			if (count($db_app) > 0) {
				foreach ($db_app as $key => $val) {
					foreach ($val as $k => $v) {
						foreach ($v as $val_l)
							$app = $val_l;
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
						foreach ($v as $val_l)
							$app = $val_l;
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
							foreach ($v as $val_l)
								$app = $val_l;
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
				if ($travelTime < "04:45") {
					$att = "A";
				} else if ($travelTime < "05:00" && $travelTime >= "04:45") {
					if ($att_bio < "05:00") {
						$att = "H";
					} else {
						$att = "P(Short Login)";
					}
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
					$LoginAtt = "P";
				} else {
					$LoginAtt = "A";
				}
			}
		} else {
			$LoginAtt = "A";
		}

		return $LoginAtt;
	}

	/********************* PRIVATE **********************/
	/**
	 * Calculation of Biometric & APR Final Attendance 
	 */
	function get_apr_bio_ATND($bio_atnd, $apr_atnd, $des)
	{
		if (($des == "9" || $des == "12" || $des == "33" || $des == "34" || $des == "35" || $des == "36")) {
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
				} else if ($bio_atnd == "P(Short Login)" && $apr_atnd != "P") {
					$FinalAtt = $apr_atnd;
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
		} else if ($atnd == "P(Short Login)" || $atnd == "P(Short Leave)") {
			// For per day check
			$iShortLogin = 0;
			$iShortLeave = 0;
			$myDB = new MysqliDb();
			$ds_shcount = $myDB->query('select D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master where EmployeeID = "' . $EmpID . '" and month="' . intval(date('m', strtotime($date))) . '" and Year = "' . intval(date('Y', strtotime($date))) . '"  limit 1');
			if (count($ds_shcount) > 0 && $ds_shcount) {
				foreach ($ds_shcount[0]['calc_atnd_master'] as $key => $val) {
					if (intval(substr($key, 1, strlen($key))) < intval(date('d', strtotime($date)))) {

						if (strpos($val, 'P(Short Leave)') !== false) {
							$iShortLeave++;
						}
						if (strpos($val, 'P(Short Login)') !== false) {
							$iShortLogin++;
						}
					}
				}
			}

			if (($des == "9" || $des == "12" || $des == "33" || $des == "34" || $des == "35" || $des == "36")) {
				if ($atnd == "P(Short Login)") {
					$iShortLogin++;
					if ($iShortLogin <= 5) {
						$finalAtnd = $atnd . '(' . $iShortLogin . ')';
					} else {
						$finalAtnd = $atnd = "H";
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
				} else if ($atnd == "P(Short Leave)") {
					$iShortLeave++;
					if ($iShortLeave == 1) {

						$finalAtnd = $atnd . '(' . $iShortLeave . ')';
					} else if ($iShortLeave > 1) {
						$finalAtnd = $atnd = 'H';
					}
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
						$res = $value['dispo']['Update_Att'];
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
class Calendar
{
	/**
	 * Constructor
	 */
	private $cal_EmployeeID;
	private $cal_DateFrom;
	private $cal_DateTo;
	private $cal_Designation;
	private $cal_Status;
	private $cal_InOJT;
	private $cal_MappedDate;

	public function __construct()
	{
		$this->naviHref = htmlentities($_SERVER['PHP_SELF']);
	}

	/********************* PROPERTY ********************/
	private $dayLabels = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");

	private $currentYear = 0;

	private $currentMonth = 0;

	private $currentDay = 0;

	private $currentDate = null;

	private $daysInMonth = 0;

	private $naviHref = null;


	public $Roaster_data = array();
	public $Roaster_Typ = array();
	public $AttDay_data = array();
	public $Login_data = array();
	public $Logout_data = array();
	public $Hour_data = array();
	public $Punch_Data = array();
	private $countAtt = 0;
	private $EmpID = '';
	private $iPL = 0;
	private $iCO = 0;
	private $iShortLogin = 0;
	private $iShortLeave = 0;
	public $dsUpdatedAtt = array();
	private $UpdateInOut = 0;
	private $bioAtnd = array();
	private $nonAPR_Employee_status = 0;
	private $ModuleChange = '';
	private $onFloor = '';
	/********************* PUBLIC **********************/

	/**
	 * print out the calendar
	 */
	public function show($EmployeeID, $DateFrom, $DateTo, $Emd_des, $Emp_status, $emp_dod, $emp_module)
	{
		$year  == null;
		$month == null;
		$resultBio = null;
		if (null == $month && isset($_POST['date'])) {
			$getDate  =  explode('%', $_POST['date']);

			$month = date('m', strtotime($getDate[0]));
			$year = date('Y', strtotime($getDate[0]));
		}
		if (null == $year && isset($_POST['year'])) {

			$year = $_POST['year'];
		} else if (null == $year) {

			$year = date("Y", time());
		}

		if (null == $month && isset($_POST['month'])) {

			$month = $_POST['month'];
		} else if (null == $month) {

			$month = date("m", time());
		}


		$dat = date("d", time());
		//if((abs($month - date("m",time())) > 2 ) || ($month > date("m",time()) && 28 > date("d",time())))
		if ((abs($month - date("m", time())) > 2  && $month < date("m", time())) || ($month > date("m", time()) && date("d", time()) <= 27 && (abs($month - date("m", time())) > 1))) {
			if (($month == '01' && date("m", time()) == '12') || (($month == '12' || $month == '11')  && date("m", time()) == '01') || ($month == '12' && date("m", time()) == '02')) {
			} else {
				return '<div class="row" style="padding-top: 7px;padding-left: 10px;"><b style="color: #e80000;text-shadow: 0px 1px 2px white,1px 1px 1px rgba(0, 0, 0, 0.73);"> Exceed The Limit </b>      ! For Current Month <a class="btn" href="atnd"> Click Here...</a></div>';
			}
		}
		$this->currentYear = $year;

		$this->currentMonth = $month;

		$this->daysInMonth = $this->_daysInMonth($month, $year);


		$this->cal_EmployeeID = $EmployeeID;
		$this->cal_DateFrom = $DateFrom;
		$this->cal_DateTo = $DateTo;
		$this->cal_Designation = $Emd_des;
		$this->cal_Status = $Emp_status;
		$this->cal_InOJT = $emp_dod;
		$this->cal_MappedDate = $emp_module;

		$biometric = new BioMetric($this->cal_EmployeeID, $this->cal_DateFrom, $this->cal_DateTo, $this->cal_Designation, $this->cal_Status, $this->cal_InOJT, $this->cal_MappedDate);
		// Call Biometric Class 
		$content = '<div id="calendar" class="col s12 m12">' .
			'<div class="box">' .
			$this->_createNavi() .
			'</div>' .
			'<div class="box-content">' .
			'<div class="label">' . $this->_createLabels() . '</div>';
		$content .= '<div class="clear"></div>';
		$content .= '<div class="dates">';

		$weeksInMonth = $this->_weeksInMonth($month, $year);
		// Create weeks in a month
		for ($i = 0; $i < $weeksInMonth; $i++) {

			//Create days in a week
			for ($j = 1; $j <= 7; $j++) {
				$content .= $this->_showDay($i * 7 + $j, $biometric->monthCalendar);
			}
		}

		$content .= '</div>';

		$content .= '<div class="clear"></div>';

		$content .= '</div>';

		$content .= '</div>';
		return $content;
	}


	private function _showDay($cellNumber, $bioCal)
	{

		if ($this->currentDay == 0) {

			$firstDayOfTheWeek = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));

			if (intval($cellNumber) == intval($firstDayOfTheWeek)) {

				$this->currentDay = 1;
			}
		}

		if (($this->currentDay != 0) && ($this->currentDay <= $this->daysInMonth)) {

			$this->currentDate = date('Y-m-d', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . ($this->currentDay)));

			$cellContent = '<span class="date_span">' . $this->currentDay . '</span>';
			$this->currentDay++;
		} else {

			$this->currentDate = null;

			$cellContent = null;
		}


		if ($this->currentDate != null) {
			if (!empty($bioCal[$this->currentDate])) {
				$cellContent .= $bioCal[$this->currentDate];
			} else {
				$cellContent = $cellContent . '<span class="text-warning" data-toggle="tooltip"    title="Data Not Exist" >-</span>';
			}
		} else {
			$cellContent = $cellContent . '<span class="text-warning" data-toggle="tooltip"    title="Data Not Exist" >-</span>';
		}
		/*if($cellContent!=null)
			    {
			    	if($cellContent_Day=='')
			    	{
						$cellContent = $cellContent.'<span class="text-warning" data-toggle="tooltip"    title="Data Not Exist" >-</span>';
					}
									
				}*/
		if ($this->currentDate == date("Y-m-d")) {
			return '<div id="li-' . $this->currentDate . '" class="dateli current ' . ($cellNumber % 7 == 1 ? ' start ' : ($cellNumber % 7 == 0 ? ' end ' : ' ')) .
				($cellContent == null ? 'mask' : '') . '"><div class="dateli_inner"><a class="datelbl" href="javascript:void(0);">' . $cellContent . '</a></div></div>';
		} else {
			return '<div id="li-' . $this->currentDate . '" class="dateli ' . ($cellNumber % 7 == 1 ? ' start ' : ($cellNumber % 7 == 0 ? ' end ' : ' ')) .
				($cellContent == null ? 'mask' : '') . '"><div class="dateli_inner"><a class="datelbl" href="javascript:void(0);">' . $cellContent . '</a></div></div>';
		}
	}


	private function _createNavi()
	{

		$nextMonth = $this->currentMonth == 12 ? 1 : intval($this->currentMonth) + 1;

		$nextYear = $this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;

		$preMonth = $this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;

		$preYear = $this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;

		return
			'<div class="header">' .
			'<a class="prev material-icons"  onclick=" return nextPrePage(' . sprintf('%02d', $preMonth) . ',' . $preYear . '); ">keyboard_arrow_left</a>' .
			'<a class="next material-icons" onclick=" return nextPrePage(' . sprintf('%02d', $nextMonth) . ',' . $nextYear . '); " >keyboard_arrow_right</a>' .
			'<span class="title">' . date('F Y', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</span>' .

			'</div>';
	}

	/**
	 * 
	 * href="'.$this->naviHref.'?p_EmpID='.$_POST['p_EmpID'].'&month='.sprintf('%02d',$preMonth).'&year='.$preYear.'" 
	 * 
	 * href="'.$this->naviHref.'?p_EmpID='.$_POST['p_EmpID'].'&month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'"
	 * create calendar week labels
	 */

	private function _createLabels()
	{

		$content = '';

		foreach ($this->dayLabels as $index => $label) {

			$content .= '<div class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</div>';
		}

		return $content;
	}



	/**
	 * calculate number of weeks in a particular month
	 */
	private function _weeksInMonth($month = null, $year = null)
	{

		if (null == ($year)) {
			$year =  date("Y", time());
		}

		if (null == ($month)) {
			$month = date("m", time());
		}

		// find number of days in this month
		$daysInMonths = $this->_daysInMonth($month, $year);

		$numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);

		$monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));

		$monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

		if ($monthEndingDay < $monthStartDay) {

			$numOfweeks++;
		}

		return $numOfweeks;
	}

	/**
	 * calculate number of days in a particular month
	 */
	private function _daysInMonth($month = null, $year = null)
	{

		if (null == ($year))
			$year =  date("Y", time());

		if (null == ($month))
			$month = date("m", time());

		return date('t', strtotime($year . '-' . $month . '-01'));
	}
}


?>
<style>
	div#calendar {
		margin: 0px auto;
		padding: 0px;
		width: 100%;

	}

	div#calendar div.box {
		position: relative;
		top: 0px;
		left: 0px;
		width: 100%;
		height: 65px;
	}

	div#calendar div.header {
		line-height: 65px;
		vertical-align: middle;
		position: absolute;
		left: 0;
		top: -5px;
		width: 100%;
		height: 65px;
		text-align: left;
	}

	div#calendar div.header a.prev,
	div#calendar div.header a.next {
		/*position:absolute;
    top:0px;   
    height: 17px;
    display:block;
    cursor:pointer;
    text-decoration:none;
    padding-left: 10px;
    padding-right: 10px;*/
		font-size: 40px;
		color: gray;
	}

	div#calendar div.header span.title {

		line-height: 65px;
		letter-spacing: 0;
		font-size: 24px;
		position: relative;
		top: -9px;
	}


	div#calendar div.header a.prev {
		left: 0px;
	}

	div#calendar div.header a.next {
		right: 0px;
	}




	/*******************************Calendar Content Cells*********************************/
	div#calendar div.box-content {
		border: none;
		border-top: #e0e0e0 1px solid;
	}



	div#calendar div.label {
		float: left;
		margin: 0px;
		padding: 0px;
		margin-top: 0px;
		margin-left: 0;
		border: none;
		width: 100%;
	}

	div#calendar div.label div {
		margin: 0px;
		padding: 0px;
		margin-right: 0;
		float: left;
		list-style-type: none;
		width: 14.285%;
		height: 30px;
		line-height: 30px;
		vertical-align: middle;
		text-align: left;
		font-size: 16;
		padding-left: 5px;
		border-left: #e0e0e0 1px solid;
	}

	div#calendar div.label div:first-child {
		border-left: 0px solid #2D2C2C
	}

	div#calendar div.dates {
		float: left;
		margin: 0px;
		padding: 0px;
		width: 100%;
	}

	/** overall width = width+padding-right**/
	div#calendar div.dates div {
		margin: 0px;
		padding: 0px;
		line-height: 30px;
		vertical-align: middle;
		float: left;
		list-style-type: none;
		width: 14.285%;
		height: 170px;
		overflow: hidden;
		color: #000;
		border-left: #e0e0e0 1px solid;
		border-bottom: #e0e0e0 1px solid;

	}

	div#calendar div.dates div.dateli_inner {
		width: calc(100% + 20px);
		height: 100%;
		border: 0px;
		overflow: auto;
		background: transparent;


	}

	:focus {
		outline: none;
	}

	div#calendar div.dates div.start {
		border-left: none;
	}

	div.clear {
		clear: both;
	}

	div#calendar div.dates div.current,
	div#calendar div.dates div:hover {
		background: white;
		cursor: pointer;
	}

	a.datelbl {
		color: #337ab7;
		clear: both;

		display: inline-block;
		width: 100%;
		min-height: 100%;
		padding-left: 0px;
	}

	a.datelbl>span[title="Data Not Exist"] {
		width: 100%;
		float: left;
		padding-left: 5px;
		/*background-color: #c1c1c1;*/
	}

	div#calendar div.dates div.current>a.datelbl {
		color: black;
	}

	span.lbl_date {
		float: left;
		width: calc(100% - 22px);
		font-weight: bold;
		font-size: 14px;
		text-align: center;
	}

	span.date_span {
		float: left;
		height: 20px;
		padding: 5px;
		line-height: 20px;
		width: 20px;
		color: #212121;
		font-size: 14px;
		font-family: inherit;
	}

	.tooltip-arrow {
		position: absolute;
		width: 0px;
		height: 0;
		border-color: transparent;
		border-style: solid;
		border-top: 10px solid green;
		bottom: -12px;
		border-right: 10px solid transparent;
		right: 45%;
		border-left: 10px solid transparent;
		z-index: 1000000000;
	}

	.tooltip-inner {
		background: white;
		border: 1px solid green;
		color: green;
	}

	p {
		margin: 0 0 1px;
		width: 100%;
		float: left;
		font-size: .8em;
		line-height: 20px;
	}

	p>kbd {
		width: 65%;
		float: right;
		text-align: left;
		margin: 0px 8px;
		padding: 0px;
		margin: 0px;
		line-height: 20px;
		background: transparent;
		font-size: 10px;
		font-weight: bold;
		text-shadow: none;
		;
		font-family: Roboto;
	}

	p>kbd:hover {
		width: 65%;
		float: right;
		text-align: left;
		margin: 0px 8px;
		padding: 0px;
		margin: 0px;
		line-height: 15px;
		background: transparent;
		font-size: 15px;
		font-weight: bold;
		text-shadow: none;
		;
		font-family: Roboto;
	}

	@media only screen and (max-width : 1200px) {


		span.lbl_date {
			width: 60%;
			text-align: left;
		}

		p>kbd {
			width: 100%;
			float: left;
		}
	}

	@media only screen and (max-width : 750px) {

		span.date_span {
			height: 20px;
			width: 12px;
			font-size: 10px;
		}

		span.lbl_date {
			width: 20px;

		}
	}


	@media only screen and (max-width : 520px) {


		span.date_span {
			height: 20px;
			width: 12px;
			font-size: 10px;

		}

		span.lbl_date {
			width: 20px;

		}
	}


	span.text-success.lbl_date {
		font-size: 12px;
	}

	a.datelbl {
		color: #4e4e4e;
		font-weight: 400;
		font-size: 14px;
		cursor: default;
	}

	span.text-success.lbl_date.exception_span {
		color: #051982;
		font-weight: bold;
		text-shadow: 1px 1px 1px #616060;
	}

	div#calendar div.dates div.dateli_inner {
		width: calc(100% + 20px);
		overflow-y: scroll;
	}

	div#calendar div.dates div.current span.date_span {
		width: 27px;
		height: 27px;
		text-align: center;
		color: #fff;
		background-color: #4285f4;
		-webkit-border-radius: 50%;
		border-radius: 50%;
		margin-top: 5px;
		margin-left: 5px;
		padding-right: 5px;
	}

	div#calendar div.dates span.date_span+span.lbl_date {
		width: 100%;
		padding-left: 5px;
		margin-top: 5px;
		text-align: left;
		height: 28px;
		/*background: #1dadc4;*/
		height: auto;
		min-height: 28px;
	}

	div#calendar div.dates span.date_span+span.lbl_date+p {
		margin-top: 0px;
	}

	.infinite_ammount_Text {
		-webkit-animation: color-change1 2s infinite;
		-moz-animation: color-change1 2s infinite;
		-o-animation: color-change1 2s infinite;
		-ms-animation: color-change1 2s infinite;
		animation: color-change1 2s infinite;
	}

	@-webkit-keyframes color-change1 {
		0% {
			color: red;
		}

		25% {
			color: #27b3e7;
		}

		50% {
			color: #1c4209;
		}

		75% {
			color: #aca50f;
		}

		100% {
			color: #944db3;
		}
	}

	@-moz-keyframes color-change1 {
		0% {
			color: red;
		}

		25% {
			color: #27b3e7;
		}

		50% {
			color: #1c4209;
		}

		75% {
			color: #aca50f;
		}

		100% {
			color: #944db3;
		}
	}

	@-ms-keyframes color-change1 {
		0% {
			color: red;
		}

		25% {
			color: #27b3e7;
		}

		50% {
			color: #1c4209;
		}

		75% {
			color: #aca50f;
		}

		100% {
			color: #944db3;
		}
	}

	@-o-keyframes color-change1 {
		0% {
			color: red;
		}

		25% {
			color: #27b3e7;
		}

		50% {
			color: #1c4209;
		}

		75% {
			color: #aca50f;
		}

		100% {
			color: #944db3;
		}
	}

	@keyframes color-change1 {
		0% {
			color: red;
		}

		25% {
			color: #27b3e7;
		}

		50% {
			color: #1c4209;
		}

		75% {
			color: #aca50f;
		}

		100% {
			color: #944db3;
		}
	}

	.infinite_ammount {
		-webkit-animation: color-change 2s infinite;
		-moz-animation: color-change 2s infinite;
		-o-animation: color-change 2s infinite;
		-ms-animation: color-change 2s infinite;
		animation: color-change 2s infinite;
	}

	@-webkit-keyframes color-change {
		0% {
			color: red;
			background-color: #f2ed0d;
		}

		50% {
			color: #1c4209;
			background-color: #f4570b;
		}

		100% {
			color: #944db3;
			background-color: #3ae21d;
		}
	}

	@-moz-keyframes color-change {
		0% {
			color: red;
			background-color: #f2ed0d;
		}

		50% {
			color: #1c4209;
			background-color: #f4570b;
		}

		100% {
			color: #944db3;
			background-color: #3ae21d;
		}
	}

	@-ms-keyframes color-change {
		0% {
			color: red;
			background-color: #f2ed0d;
		}

		50% {
			color: #1c4209;
			background-color: #f4570b;
		}

		100% {
			color: #944db3;
			background-color: #3ae21d;
		}
	}

	@-o-keyframes color-change {
		0% {
			color: red;
			background-color: #f2ed0d;
		}

		50% {
			color: #1c4209;
			background-color: #f4570b;
		}

		100% {
			color: #944db3;
			background-color: #3ae21d;
		}
	}

	@keyframes color-change {
		0% {
			color: red;
			background-color: #f2ed0d;
		}

		50% {
			color: #1c4209;
			background-color: #f4570b;
		}

		100% {
			color: #944db3;
			background-color: #3ae21d;
		}
	}

	form p {
		margin-bottom: 3px;
		text-align: left;
		padding-left: 5px;
	}

	div#calendar div.dates div.current span.date_span+span.lbl_date:empty {

		max-height: 10px !important;
		min-height: 15px;

	}

	div#calendar div.dates div.current span.date_span {

		width: 28px;
		height: 28px;
		text-align: center;
		color: #fff;
		background-color: #4285f4;
		-webkit-border-radius: 50%;
		border-radius: 50%;
		margin-top: 5px;
		margin-left: 5px;
		text-align: left;

	}

	span.lbl_date:empty {

		margin-bottom: 2px;

	}
</style>
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Attendance</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<?php
			$p_EmpID = "";
			if (isset($_POST['p_EmpID']) && trim($_POST['p_EmpID']) != "") {
				$p_EmpID = trim($_POST['p_EmpID']);
			} else {
				if ($_SESSION["__view_Bio"] != "") {
					$p_EmpID = $_SESSION["__view_Bio"];
				} else {
					$p_EmpID = $_SESSION['__user_logid'];
				}
			}
			?>
			<input type='hidden' name='month' id='newMonth' value="<?php echo  date('m'); ?>">

			<input type='hidden' name='year' id='newYear' value="<?php echo  date('Y'); ?>">
			<?php


			$myDB = new MysqliDb();
			$tmpcheck = $myDB->query('select EmployeeID from whole_details_peremp Where ("' . EmployeeID_forPage . '" = "' . $_SESSION['__user_logid'] . '" or account_head ="' . $_SESSION['__user_logid'] . '" or oh ="' . $_SESSION['__user_logid'] . '" or ReportTo = "' . $_SESSION['__user_logid'] . '" or th = "' . $_SESSION['__user_logid'] . '" or qh = "' . $_SESSION['__user_logid'] . '" or "CE03070003" = "' . $_SESSION['__user_logid'] . '" or "CE061510258" = "' . $_SESSION['__user_logid'] . '" or "CE06080411" = "' . $_SESSION['__user_logid'] . '" or "CE04159316" = "' . $_SESSION['__user_logid'] . '" or "CE12102224" = "' . $_SESSION['__user_logid'] . '" or "CE04146339" = "' . $_SESSION['__user_logid'] . '" or "CE121513899" = "' . $_SESSION['__user_logid'] . '" or "CE10091236" = "' . $_SESSION['__user_logid'] . '" or "CE01145570" = "' . $_SESSION['__user_logid'] . '" or "CE08134828"= "' . $_SESSION['__user_logid'] . '" or
"CE09134997"= "' . $_SESSION['__user_logid'] . '" or
"CE03146043"= "' . $_SESSION['__user_logid'] . '" or
"CE011614463"= "' . $_SESSION['__user_logid'] . '" or
"CE121622102"= "' . $_SESSION['__user_logid'] . '" ) and EmployeeID="' . EmployeeID_forPage . '"');



			if (count($tmpcheck) > 0  && $tmpcheck) {
			?>


				<h4><b><?php echo EmployeeName_forPage . '</b> ( ' . EmployeeID_forPage . ' ) '; ?>

						<?php
						$myDB = new MysqliDb();
						$dataCO = $myDB->query('call sp_getComboCount("' . EmployeeID_forPage . '")');
						if ($dataCO) {
							echo '<span class="pull-right" style="margin-left: 10px;">CO <span>' . $dataCO[0]['CO'] . '</span></span>';
						} else {
							echo '<span class="pull-right" style="margin-left: 10px;">CO <span>0</span></span>';
						}
						$myDB = new MysqliDb();
						$dataPL = $myDB->query('call get_paidleave_current(curdate(), "' . EmployeeID_forPage . '");');
						$pl = 0;
						if ($dataPL) {
							if (count($dataPL) > 0) {
								$myDB = new MysqliDb();
								$dataPL1 = $myDB->query('call get_paidleave_urned(curdate(), "' . EmployeeID_forPage . '");');
								if (isset($dataPL[0]['paidleave'])) {

									$pl = $dataPL[0]['paidleave'] - $dataPL1[0]['paidleave'];
								}
							}
							echo '<span class="pull-right">PL <span>' . $pl . '</span></span>';
						} else {
							echo '<span class="pull-right">PL <span>0</span></span>';
						}


						?>

				</h4>
				<div class="schema-form-section row">

					<?php
					//href="view_BioMetric_one.php?p_EmpID=<?php echo $_SESSION['__user_logid']; " 
					if (date('N', time()) >= 5 || $_SESSION['__user_logid'] != $p_EmpID) {
						$rd_hide = ' hidden ';
					} else {
						$rd_hide = ' ';
					}
					?>
					<div class="card left col s12 m12 <?php echo $rd_hide; ?>">
						<?php
						/*echo "<p style='margin-top: 10px;font-weight: bold;font-size: small;' class='lighten-3 grey-text'>Week Off Preference From Monday To Thursday  Start Date: <b>".$sr_d_startDay."</b> End Date:<b> ".$sr_d_EndDay."</b></p>";*/
						echo "<h4 class='no-padding' style='color: gray !important;'>Week Off Preference From Monday To Thursday  Start Date: <b>" . $sr_d_startDay . "</b> End Date:<b> " . $sr_d_EndDay . "</b></h4>";
						?>
						<div class="input-field col s5 m5">

							<select name="txtweekoff_1" id="txtweekoff_1">

								<option>Monday</option>
								<option>Tuesday</option>
								<option>Wednesday</option>
								<option>Thursday</option>
								<option>Friday</option>
								<option selected="true">Saturday</option>
								<option>Sunday</option>
								<option value="NoPreference">NoPreference</option>
								<option>No Weekoff Required</option>
							</select>
							<label for="txtweekoff_1" class="active-drop-down active">Preference One</label>
						</div>
						<div class="input-field col s5 m5">

							<select name="txtweekoff_2" id="txtweekoff_2">

								<option>Monday</option>
								<option>Tuesday</option>
								<option>Wednesday</option>
								<option>Thursday</option>
								<option>Friday</option>
								<option>Saturday</option>
								<option selected="true">Sunday</option>
								<option value="NoPreference">NoPreference</option>
								<option>No Weekoff Required</option>

							</select>
							<label for="txtweekoff_2" class="active-drop-down active">Preference Two</label>
						</div>
						<div class="input-field col s2 m2 right-align no-padding">
							<button type="submit" id="btnSavePref" class="btn waves-effect waves-light" name="btnSavePref">Request</button>
							<p></p>
						</div>
					</div>
					<div class="card left col s12 m12">




						<div class="input-field col s10 m10">
							<input type='text' name="p_EmpID" id="p_EmpID" value="<?php echo $p_EmpID; ?>">
							<label for="p_EmpID"> Employee ID</label>
						</div>
						<div class="input-field col s2 m2 right-align no-padding">
							<button type="submit" id="btnSearch" class="btn waves-effect waves-green" name="btnSearch"> Search </button>
						</div>


					</div>
					<div class="col s12 m12" id="calendar_container" style="padding: 0px;border: #e0e0e0 1px solid;">
						<?php
						if (EmployeeID_forPage != '' || EmployeeID_forPage != null) {
							$date1_calc = date('Y-m-01', strtotime($year . '-' . $month . '-01'));
							$date2_calc = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
							$strt = "select whole_details_peremp.EmployeeID,whole_details_peremp.designation,whole_details_peremp.status,status_table.mapped_date,status_table.OnFloor,status_table.InQAOJT,des_id from whole_details_peremp inner join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where whole_details_peremp.EmployeeID = '" . EmployeeID_forPage . "' and emp_status = 'Active'";
							$myDB = new  MysqliDb();
							$rstl_employees = $myDB->rawQuery($strt);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								foreach ($rstl_employees as $glob_key => $glob_val) {

									if (isset($glob_val['EmployeeID'])) {


										$cal = new Calendar();
										echo $cal->show($glob_val['EmployeeID'], $date1_calc, $date2_calc, $glob_val['des_id'], $glob_val['status'], $glob_val['InQAOJT'], $glob_val['mapped_date']);
										/*$arr = get_defined_vars();
										print_r($arr);*/
									}
								}
							}
						}


						?>
					</div>


				</div>
			<?php
			} else {
			?>
				<div class="card col s12 m12" style="background-image: url('../Style/img/no access.jpg');background-position: 50% 50%;background-repeat: no-repeat;height: 300px;"></div>



			<?php
			}
			$_SESSION["__view_Bio"] = "";
			?>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function() {
		$("#calendar_container").find("br").remove();
		var weekday = new Array(7);
		weekday[0] = "Sunday";
		weekday[1] = "Monday";
		weekday[2] = "Tuesday";
		weekday[3] = "Wednesday";
		weekday[4] = "Thursday";
		weekday[5] = "Friday";
		weekday[6] = "Saturday";
		$('#txtweekoff_2 ,#txtweekoff_1 ').change(function() {
			var dt = new Date();
			var dayOfWeek = weekday[parseInt(dt.getDay())];

			/*if(dayOfWeek == $(this).val())
	 	{
	 		
			alert('Not a right choice you Select, Please try again');
			$(this).val('NoPreference');
		}*/

			if ($('#txtweekoff_2').val() == $('#txtweekoff_1').val()) {
				if ($('#txtweekoff_1').val() != 'NoPreference' && $('#txtweekoff_1').val() != 'No Weekoff Required') {
					alert('Please select different day in Choice.');
					$(this).val('NoPreference');
				}
			}
			$('select').formSelect();
		});
		$('select').formSelect();
		var count = 1;
		$('.lbl_date').each(function() {
			/*	if($(this).text()=='WO' || $(this).text()=='CO')
	 	{
			$(this).css('color','#fff').css('background','#8cc63e');
		}
		if($(this).text()=='HWP' || $(this).text()=='LWP')
	 	{
			$(this).css('color','#fff').css('background','#FFC510');
		}
		if($(this).text()=='H' || $(this).text()=='L')
	 	{
			$(this).css('color','#fff').css('border','#ffc41e');
		}
		if($(this).text()=='P')
	 	{
			$(this).css('color','#fff').css('background','#19AFC5');
		}
		if($(this).text()=='A' || $(this).text()=='LANA'|| $(this).text()=='WONA')
	 	{
			$(this).css('color','#fff').css('background','#d2060c');
		}
		if($(this).text().indexOf('P(Short Leave)')>=0)
	 	{
			$(this).css('color','#fff').css('background','#ff7800');
		}
		if($(this).text().indexOf('P(Short Login)')>=0)
	 	{
			$(this).css('color','#fff').css('background','#B06D20');
		}
		if($(this).text().indexOf('-')>=0)
	 	{
			$(this).css('color','#0C2171');
		}
		
		if($(this).text().indexOf('WO')>=0 || $(this).text().indexOf('CO')>=0 || $(this).text().indexOf('HO')>=0)
	 	{
			$(this).css('color','#fff').css('background','#8DC73A');
		}
		if($(this).text()=='LWP')
	 	{
			$(this).css('color','#fff').css('background','#ff2020');
		}
		if($(this).text()=='CO')
	 	{
			$(this).css('background','#4f63af');
		}
		if($(this).text()=='HO')
	 	{
			$(this).css('background','#4CAF50');
		}
		if($(this).text() =='P(Biometric Issue)')
	 	{
			$(this).css('color','#fff').css('background','#818181');
		}
		if($(this).text() =='H(Biometric Issue)')
	 	{
			$(this).css('color','#fff').css('background','#2196F3');
		}
		if($(this).text() =='HWP(Biometric Issue)')
	 	{
			$(this).css('color','#fff').css('background','#818181').css('font-size','12px');
		}
		if($(this).text() =='LWP(Biometric Issue)')
	 	{
			$(this).css('color','#fff').css('background','#818181').css('font-size','12px');
		}*/

			if ($(this).text() == 'WO' || $(this).text() == 'CO' || $(this).text() == 'L' || $(this).text() == 'HO') {
				$(this).css('color', '#1d1d1d').css('background', '#8cc63e').css('border', '1px solid #8cc63e');
			}
			if ($(this).text() == 'HWP' || $(this).text() == 'LWP' || $(this).text() == 'A' || $(this).text() == 'LANA' || $(this).text() == 'WONA' || $(this).text() == 'LWP(Biometric Issue)' || $(this).text() == 'HWP(Biometric Issue)' || $(this).text() == 'H(Biometric Issue)' || $(this).text() == 'P(Biometric Issue)' || $(this).text() == 'H') {
				$(this).css('color', '#1d1d1d').css('border', '1px solid #ffc41e').css('border-right', '4px solid #ffc41e');
			}
			if ($(this).text() == 'P') {
				$(this).css('color', '#1d1d1d').css('background', '#19AFC5').css('border', '1px solid #19AFC5');
			}
			if ($(this).text().indexOf('P(Short Leave)') >= 0) {
				$(this).css('color', '#1d1d1d').css('border', '1px solid #ffc41e').css('border-right', '4px solid #ffc41e');
			}
			if ($(this).text().indexOf('P(Short Login)') >= 0) {
				$(this).css('color', '#1d1d1d').css('border', '1px solid #ffc41e').css('border-right', '4px solid #ffc41e');
			}
			if ($(this).text().indexOf('-') >= 0) {
				$(this).css('color', '#1d1d1d').css('border', '1px solid #ffc41e').css('border-right', '4px solid #ffc41e');
			}

			if ($(this).text().indexOf('WO') >= 0 || $(this).text().indexOf('CO') >= 0 || $(this).text().indexOf('HO') >= 0) {
				$(this).css('color', '#1d1d1d').css('background', '#8cc63e').css('border', '1px solid #8cc63e');
			}

			var datePart = $('.header span.title').text().split(',');
			var date = new Date();
			var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

			if (date.getMonth() < monthNames.indexOf(datePart[0])) {
				if ($(this).text() == 'HWP' || $(this).text() == 'LWP') {
					//$(this).text('-');
				}
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
	});

	function nextPrePage(nmonth, nyear) {
		$('#newMonth').val(nmonth);
		$('#newYear').val(nyear);
		document.getElementById('indexForm').submit();
	}
	<?php

	if ($_SESSION['__user_logid'] == $p_EmpID && false) {
	?>
		$(function() {
			var EmpID_check_alert_exception_tmp = <?php echo '"' . $_SESSION['__user_logid'] . '";'; ?>;

			/*$('.excep_alrt').each(function(index){
				var date_on = $(this).next("span.dt_all").attr("data-date");
				var atnd = $(this).next("span.dt_all").attr("data-atnd");
				var roster = $(this).next("span.dt_all").attr("data-roster");
				
				var rtype = $(this).next("span.dt_all").attr("data-rtype");
				var apr = $(this).next("span.dt_all").attr("data-apr");
				var des = $(this).next("span.dt_all").attr("data-des");
				var bin = $(this).next("span.dt_all").attr("data-in");
				var bout = $(this).next("span.dt_all").attr("data-out");
				var biohour = $(this).next("span.dt_all").attr("data-biohour");
				
				if(parseInt(rtype) == 4)
				{
					
					
				}
				else
				{
					$dt_on  = new Date(date_on);
					$now = new Date();
					$today = new Date($now.getFullYear(),$now.getMonth(),$now.getDate());
					var timeDiff = Math.abs($dt_on.getTime() - $today.getTime());
					var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
					
					var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(bin);
					
					//Shift Change
					//Roster Change
					//Back Dated Leave
					//Biometric issue
					//Working on WeekOff
					//Working on Leave
					if((atnd.toUpperCase() == 'WO' || atnd.toUpperCase() == 'WONA') && diffDays <= 1 && isValid) 
					{
						$(this).html("<a href='addReq'><span class='text-danger'>Working on WeekOff</span></a>");
						
					}
					if((atnd.toUpperCase() == 'L' || atnd.toUpperCase().slice(0,3) == 'L(B' || atnd.toUpperCase() == 'CO' || atnd.toUpperCase().slice(0,4) == 'CO(B') && diffDays <= 1 && isValid) 
					{
						$(this).html("<a href='addReq'><span class='text-danger'>Working On WeekOff</span></a>");
					} 
				}
			});
			*/
			<?php
			$myDB = new MysqliDb();
			$rt_rtyp_fvalcalc = $myDB->query("select rt_type from salary_details where EmployeeID = '" . $_SESSION['__user_logid'] . "'");
			echo 'var rt_rtyp_fvalcalc = ' . intval($rt_rtyp_fvalcalc[0]['rt_type']) . ';';
			?>
			var link_to_ref = '#';

			if (rt_rtyp_fvalcalc == 4) {
				link_to_ref = 'addReq1';
			} else {
				link_to_ref = 'addReq';
			}
			$.ajax({
				url: "../Controller/cappingDate.php?EmpID=" + EmpID_check_alert_exception_tmp + '&Exception=Working on WeekOff',
				success: function(result) {
					if (result != '' && result != undefined) {
						var dates = result.split(',');
						for (var dt in dates) {
							var $dt_up = (dates[dt]);
							var p_html = $("#li-" + $dt_up + "").children().find(".excep_alrt").html();
							$("#li-" + $dt_up + "").children().find(".excep_alrt").html("<p><a href='" + link_to_ref + "' style='float: left;text-align: center;width: 100%;'><span class='infinite_ammount'>Working on WeekOff</span></a></p>" + p_html).css({
								"float": "left",
								"width": "100%"
							});



						}
					}
				}
			});
			$.ajax({
				url: "../Controller/cappingDate.php?EmpID=" + EmpID_check_alert_exception_tmp + '&Exception=Shift Change',
				success: function(result) {

					if (result != '' && result != undefined) {
						var dates = result.split(',');
						for (var dt in dates) {
							var $dt_up = (dates[dt]);
							var p_html = $("#li-" + $dt_up + "").children().find(".excep_alrt").html();
							$("#li-" + $dt_up + "").children().find(".excep_alrt").html("<p><a href='" + link_to_ref + "' style='float: left;text-align: center;width: 100%;'><span class='infinite_ammount'>Shift Change</span></a></p>" + p_html).css({
								"float": "left",
								"width": "100%"
							});


						}
					}
				}
			});
			$.ajax({
				url: "../Controller/cappingDate.php?EmpID=" + EmpID_check_alert_exception_tmp + '&Exception=Back Dated Leave',
				success: function(result) {

					if (result != '' && result != undefined) {
						var dates = result.split(',');
						for (var dt in dates) {
							var $dt_up = (dates[dt]);
							var p_html = $("#li-" + $dt_up + "").children().find(".excep_alrt").html();
							$("#li-" + $dt_up + "").children().find(".excep_alrt").html("<p><a href='" + link_to_ref + "' style='float: left;text-align: center;width: 100%;'><span class='infinite_ammount'>Back Dated Leave</span></a></p>" + p_html).css({
								"float": "left",
								"width": "100%"
							});


						}
					}
				}
			});
			$.ajax({
				url: "../Controller/cappingDate.php?EmpID=" + EmpID_check_alert_exception_tmp + '&Exception=Working on Leave',
				success: function(result) {

					if (result != '' && result != undefined) {
						var dates = result.split(',');
						for (var dt in dates) {
							var $dt_up = (dates[dt]);
							var p_html = $("#li-" + $dt_up + "").children().find(".excep_alrt").html();
							$("#li-" + $dt_up + "").children().find(".excep_alrt").html("<p><a href='" + link_to_ref + "'  style='float: left;text-align: center;width: 100%;'><span class='infinite_ammount'>Working on Leave</span></a></p>" + p_html).css({
								"float": "left",
								"width": "100%"
							});


						}
					}
				}
			});

		});
	<?php
	}
	?>
</script>