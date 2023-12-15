<?php


require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

//$biometric = new BioMetric($this->cal_EmployeeID, $this->cal_DateFrom, $this->cal_DateTo, $this->cal_Designation, $this->cal_Status, $this->cal_InOJT, $this->cal_MappedDate);

$cal_EmployeeID = 'CEB122112572';
$cal_DateFrom = '2023-05-09';
$cal_DateTo = '2023-05-09';
$cal_Designation = '25';
$cal_Status = '6';
$cal_InOJT = '2021-12-23';
$cal_MappedDate = '2021-12-11';

$biometric = new BioMetric($this->$cal_EmployeeID, $this->$cal_DateFrom, $this->$cal_DateTo, $this->$cal_Designation, $this->$cal_Status, $this->$cal_InOJT, $this->$cal_MappedDate);

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
    public $monthCalendar = array();

    private $pt_bio = array();
    private $abc = array();

    public function __construct($EmployeeID, $DateFrom, $DateTo, $Emd_des, $Emp_status, $emp_dod, $emp_module)
    {
        // Fetch data for Roster in given range;



        // Sevrer 
        $myDB = new MysqliDb1();
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
        } else {
            $ds_roster = $myDB->query('select DateOn,InTime,OutTime,type_ from roster_temp_history_new where EmployeeID ="' . $EmployeeID . '" and DateOn between "' . $DateFrom . '" and "' . $DateTo . '"');

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

        $myDB = new MysqliDb1();

        $str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . $DateTo . '" as date) Union select EmpID,PunchTime, DateOn from biopunchcurrentdata_pre where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . $DateTo . '" as date)';
        $ds_punchtime1 = $myDB->query($str_capping);
        if (count($ds_punchtime1) > 0 && $ds_punchtime1) {
            $str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . date('Y-m-d', (strtotime($DateTo . ' +1 days'))) . '" as date) Union select EmpID,PunchTime, DateOn from biopunchcurrentdata_pre where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . date('Y-m-d', (strtotime($DateTo . ' +1 days'))) . '" as date)';
            $ds_punchtime = $myDB->query($str_capping);

            // Fetch data for APR  in given range; 

            if (count($ds_punchtime) > 0 && $ds_punchtime) {
                foreach ($ds_punchtime as $key => $value) {
                    $this->abc[$value['DateOn']][] = $value['PunchTime'];
                }
            }
        } else {
            $str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata_history_new where EmpID="' . $EmployeeID . '" and DateOn between cast("' . $DateFrom . '" as date) and cast("' . date('Y-m-d', (strtotime($DateTo . ' +1 days'))) . '" as date) ';
            $ds_punchtime = $myDB->query($str_capping);
            if (count($ds_punchtime) > 0 && $ds_punchtime) {
                foreach ($ds_punchtime as $key => $value) {
                    $this->abc[$value['DateOn']][] = $value['PunchTime'];
                }
            }
        }



        $abc = $this->abc;
        foreach ($abc as $key => $row) {
            sort($row);
            $arrlength = count($row);
            for ($x = 0; $x < $arrlength; $x++) {

                $this->pt_bio[$key][] = $row[$x];
            }
        }



        // Only for CSA and Sr CSA

        if ($Emd_des == "9" || $Emd_des == "12" || $Emd_des == "33" || $Emd_des == "34" || $Emd_des == "35" || $Emd_des == "36") {

            $myDB = new MysqliDb1();
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
                $myDB = new MysqliDb1();
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

            $myDB = new MysqliDb1();
            $nonAPR_emp = $myDB->query('select EmployeeID from nonapr_employee  where EmployeeID="' . $EmployeeID . '" and flag=0');
            if (!empty($nonAPR_emp[0]['EmployeeID'])) {
                $this->nonAPR_Employee_status = 1;
            }
            $this->onFloor = $emp_dod;
            $this->ModuleChange = $emp_module;
        }

        // Get all Biometric Exceptions 

        $myDB = new MysqliDb1();
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

        $myDB = new MysqliDb1();
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
            $myDB = new MysqliDb1();
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
                    } else if ($i_rin_tmp >= "13:30:00" && $this->Roster_type[$__date] == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
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
                            $cellContent_Day = '<p>Roster  <kbd>' . $i_rosterattr . '</kbd></p><p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT . '</kbd></p>';
                        } else {
                            $cellContent_Day = '<p>Roster  <kbd> ' . $i_rosterattr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT . '</kbd></p>';
                        }

                        $cellContent = '<span class="lbl_date" data-toggle="tooltip"  >' . $atnd_day . '</span>' . $cellContent_Day . '<p> Login <kbd>' . $b_tt . ' Hr</kbd></p>';
                    } else {
                        if ($__des == "9" || $__des == "12" || $__des == "33" || $__des == "34" || $__des == "35" || $__des == "36") {
                            if (empty($hour_attr))
                                $hour_attr = '-';

                            $cellContent_Day = '<p>Roster  <kbd> ' . $i_rosterattr . '</kbd></p><p>Net Hour <kbd> ' . $hour_attr . '</kbd></p><p>In Time <kbd>' . $i_bioIN . '</kbd></p> <p> OutTime <kbd>' . $i_bioOUT . '</kbd></p>';
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
}
