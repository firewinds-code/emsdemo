<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$sq1 = "insert into scheduler(modulename,type)values('" . $module . "','" . $type . "');";
	$myDB->query($sq1);
}
settimestamp('auto_calc_Exception', 'Start');

$myDB = new MysqliDb();
$data_exp = $myDB->query('call get_exceed_exp_data()');
echo $myDB->getLastError();
function dt_diff($d1, $d2)
{

	$datetime1 = new DateTime($d1);

	$datetime2 = new DateTime($d2);

	$difference = $datetime1->diff($datetime2);

	return ($difference->d);
}
$alert_msg = '';
if (count($data_exp) > 0) {
	foreach ($data_exp as $exp_key => $exp_val) {

		$myDB = new MysqliDb();
		#$test = 'call get_account_head("'.$exp_val['EmployeeID'].'")';
		$data_ah = $myDB->rawQuery('call get_account_head("' . $exp_val['EmployeeID'] . '")');
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if ($rowCount > 0 && isset($data_ah[0]['account_head'])) {

			$myDB = new MysqliDb();
			#$test2 = 'call get_calcAtnd_fromDate("'.$data_ah[0][0]['account_head'].'","'.$exp_val['CreatedOn'].'")';
			#echo $test2;
			#$PDay_dt= $myDB->query($test2);

			//$PDay_dt= $myDB->query('call get_calcAtnd_fromDate("'.$data_ah[0]['account_head'].'","'.$exp_val['CreatedOn'].'")');

			#$test3 = $PDay_dt[0][0]['PDay'];
			if ($data_ah[0]['account_head'] != 'CE07147134') {


				if ($exp_val['ID'] > 1) {


					$myDB = new MysqliDb();
					$sqlGetData = 'call GetRequestDetailsByID("' . $exp_val['ID'] . '")';
					$result_byID = $myDB->query($sqlGetData);

					$ExpID = $result_byID[0]['ID'];
					$cm_id = $result_byID[0]['cm_id'];
					$EmployeeID = $result_byID[0]['EmployeeID'];
					$Name = $result_byID[0]['EmployeeName'];
					$Exception = $result_byID[0]['Exception'];
					$EmployeeComment = "Approved by  SERVER";
					$DateFrom = $result_byID[0]['DateFrom'];
					$DateTo = $result_byID[0]['DateTo'];

					$ShiftIn = $result_byID[0]['ShiftIn'];
					$ShiftOut = $result_byID[0]['ShiftOut'];
					$IssueType = $result_byID[0]['IssueType'];
					$CurrAtt = $result_byID[0]['Current_Att'];
					$UpdateAtt = $result_byID[0]['Update_Att'];
					$LeaveType = $result_byID[0]['LeaveType'];
					$txtApprovedBy = $result_byID[0]['account_head'];
					$MngrStatusID = "Approve";
					$txtApprovedByName = $result_byID[0]['ReportTo'];
					$HeadStatusID = "Pending";
					$ModifiedBy = " SERVER";
					$DateModified = date('Y-m-d', time());

					$myDB = new MysqliDb();
					$sqlInsertException = 'call UpdateRequestDetailsManager("' . $ExpID . '","' . $DateFrom . '","' . $DateTo . '","' . $Exception . '","' . $EmployeeComment . '","' . $MngrStatusID . '","' . $HeadStatusID . '","' . $ModifiedBy . '","' . $DateModified . '","' . $IssueType . '","' . $CurrAtt . '","' . $UpdateAtt . '","' . $ShiftIn . '","' . $ShiftOut . '","' . $LeaveType . '","web-auto_calc_Exception")';
					//echo $ExpID.'</br>';
					$flag =  $myDB->rawQuery($sqlInsertException);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {
						$count = 0;
						if ($MngrStatusID == "Approve") {
							if ($Exception == "Roster Change" || $Exception == "Shift Change") {
								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateTo);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster('" . $EmployeeID . "','" . $month . "','" . $year . "','" . $day . "','" . $shift . "')";
									//echo $query;
									$myDB = new MysqliDb();
									$flag = $myDB->rawQuery($query);
									$error = $myDB->getLastError();
									$rowCount = $myDB->count;
									if ($rowCount > 0) {
										$count++;
										$date1 = date('Y-m-d');
										$date2 = $year . '-' . $month . '-' . $day; //2020-11-28';
										if (strtotime($date1) > strtotime($date2)) {
											$ds_APR = $myDB->query('select t1.EmployeeID, designation,des_id, windowstart,windowend,t3.work_from from whole_dump_emp_data t1 inner join process_window t2 on t1.cm_id=t2.cm_id inner join roster_temp t3 on t1.EmployeeID=t3.EmployeeID
where t1.EmployeeID= "' . $EmployeeID . '" and t3.DateOn= "' . $date2 . '" ');

											if (count($ds_APR) > 0 && $ds_APR) {
												if ($ds_APR[0]['des_id'] == '9' && $ds_APR[0]['des_id'] == '12' && $ds_APR[0]['des_id'] == '33' && $ds_APR[0]['des_id'] == '34' && $ds_APR[0]['des_id'] == '35' && $ds_APR[0]['des_id'] == '36' && $ds_APR[0]['work_from'] != '') {
													$url = 'http://192.168.202.130/apr_processing/user_apr.php?apr_date=' . $date2 . '&username=' . $EmployeeID . '&intime=' . $ShiftIn . '&outtime=' . $ShiftOut . '&WorkFrom=' . $ds_APR[0]['work_from'] . '&windowstart=' . $ds_APR[0]['windowstart'] . '&windowend=' . $ds_APR[0]['windowend'];
													//echo $url;
													$curl = curl_init();
													curl_setopt($curl, CURLOPT_URL, $url);
													curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
													curl_setopt($curl, CURLOPT_HEADER, false);
													$data = curl_exec($curl);
													curl_close($curl);
												}
											}
										}
										/*if($cm_id=='27' || $cm_id=='58' || $cm_id=='83' || $cm_id=='45' || $cm_id=='46' || $cm_id=='126' || $cm_id=='90')
			                        {
										$url = URL.'View/calc_apr_one.php?empid='.$EmployeeID.'&date='.date('Y-m-d',strtotime($dt1));
										$curl = curl_init();
										curl_setopt($curl, CURLOPT_URL, $url);
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($curl, CURLOPT_HEADER, false);
										$data = curl_exec($curl);
										curl_close($curl);
									}*/
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

										$myDB = new MysqliDb();
										$flag = $myDB->rawQuery($query);
										$error = $myDB->getLastError();
										$rowCount = $myDB->count;
										if ($rowCount > 0) {
											$count++;

											$date1 = date('Y-m-d');
											$date2 = $year . '-' . $month . '-' . $day; //2020-11-28';
											if (strtotime($date1) > strtotime($date2)) {
												$ds_APR = $myDB->query('select t1.EmployeeID, designation,des_id, windowstart,windowend,t3.work_from from whole_dump_emp_data t1 inner join process_window t2 on t1.cm_id=t2.cm_id inner join roster_temp t3 on t1.EmployeeID=t3.EmployeeID
	where t1.EmployeeID= "' . $EmployeeID . '" and t3.DateOn= "' . $date2 . '" ');

												if (count($ds_APR) > 0 && $ds_APR) {
													if ($ds_APR[0]['des_id'] == '9' && $ds_APR[0]['des_id'] == '12'  && $ds_APR[0]['des_id'] == '33' && $ds_APR[0]['des_id'] == '34' && $ds_APR[0]['des_id'] == '35' && $ds_APR[0]['des_id'] == '36' && $ds_APR[0]['work_from'] != '') {
														$url = 'http://192.168.202.130/apr_processing/user_apr.php?apr_date=' . $date2 . '&username=' . $EmployeeID . '&intime=' . $ShiftIn . '&outtime=' . $ShiftOut . '&WorkFrom=' . $ds_APR[0]['work_from'] . '&windowstart=' . $ds_APR[0]['windowstart'] . '&windowend=' . $ds_APR[0]['windowend'];
														//echo $url;
														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);
														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($curl, CURLOPT_HEADER, false);
														$data = curl_exec($curl);
														curl_close($curl);
													}
												}
											}

											/*if($cm_id=='27' || $cm_id=='58' || $cm_id=='83' || $cm_id=='45' || $cm_id=='46' || $cm_id=='126' || $cm_id=='90')
				                        {
											$url = URL.'View/calc_apr_one.php?empid='.$EmployeeID.'&date='.$i->format('Y-m-d');
											$curl = curl_init();
											curl_setopt($curl, CURLOPT_URL, $url);
											curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($curl, CURLOPT_HEADER, false);
											$data = curl_exec($curl);
											curl_close($curl);
										}*/
										}
									}
									unset($begin);
									unset($end);
								}

								if ($count == 0)
									$count++;

								$alert_msg = '<span class="text-success"><b>Message :</b> ' . $Exception . ' Request for ' . $count . ' Day is ' . $MngrStatusID . ' <b> by ' . ' SERVER' . '</b> for Employee <b>' . $EmployeeID . '</b></span>';
							} else if ($Exception == "Back Dated Leave") {
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
								$ModifiedBy = " SERVER";

								$query = 'INSERT INTO leavehistry(EmployeeID,DateFrom,DateTo,ReasonofLeave,LeaveOnDate,TotalLeaves,EmployeeComment,MngrStatusID,HRStatusID,ManagerComment,HRComents,CreatedBy,HOD,LeaveType) VALUES ("' . $EmployeeID . '","' . $DateFrom1 . '","' . $DateTo1 . '","' . $ReasonofLeave . '","' . date('Y-m-d') . '","' . $TotalLeaves1 . '","Approve","Approve","Approve","' . $ManagerComment . '","","' . $CreatedBy . '","' . ' SERVER' . '","' . $LeaveType . '")';
								$myDB = new MysqliDb();
								$flag = $myDB->rawQuery($query);
								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;
								if ($rowCount > 0) {
									$alert_msg = '<span class="text-success"><b>Message :</b> ' . $Exception . " Request for " . $TotalLeaves1 . " day is " . $MngrStatusID . ' by' . ' SERVER' . '  for Employee <b>' . $EmployeeID . '</b></span>';
								}
							} else if ($Exception == "Working on Holiday" || $Exception == "Working on WeekOff") {

								$RequestType = $Exception;
								$DateCreated = $DateFrom;


								$shift = $ShiftIn . "-" . $ShiftOut;
								$dt1 = date($DateFrom);
								$dt2 = date($DateFrom);
								if ($dt1 == $dt2) {
									$month = intval(date('m', strtotime($dt1)));
									$year = intval(date('Y', strtotime($dt1)));
									$day  = intval(date('d', strtotime($dt1)));
									$query = "call sp_UpdateRoaster('" . $EmployeeID . "','" . $month . "','" . $year . "','" . $day . "','" . $shift . "')";
									// echo $query;
									$myDB = new MysqliDb();
									$flag = $myDB->rawQuery($query);

									$date1 = date('Y-m-d');
									$date2 = $year . '-' . $month . '-' . $day; //2020-11-28';
									if (strtotime($date1) > strtotime($date2)) {
										$ds_APR = $myDB->query('select t1.EmployeeID,des_id, designation, windowstart,windowend,t3.work_from from whole_dump_emp_data t1 inner join process_window t2 on t1.cm_id=t2.cm_id inner join roster_temp t3 on t1.EmployeeID=t3.EmployeeID
where t1.EmployeeID= "' . $EmployeeID . '" and t3.DateOn= "' . $date2 . '" ');

										if (count($ds_APR) > 0 && $ds_APR) {
											if ($ds_APR[0]['des_id'] == '9' && $ds_APR[0]['des_id'] == '12' && $ds_APR[0]['des_id'] == '33' && $ds_APR[0]['des_id'] == '34' && $ds_APR[0]['des_id'] == '35' && $ds_APR[0]['des_id'] == '36' && $ds_APR[0]['work_from'] != '') {
												$url = 'http://192.168.202.130/apr_processing/user_apr.php?apr_date=' . $date2 . '&username=' . $EmployeeID . '&intime=' . $ShiftIn . '&outtime=' . $ShiftOut . '&WorkFrom=' . $ds_APR[0]['work_from'] . '&windowstart=' . $ds_APR[0]['windowstart'] . '&windowend=' . $ds_APR[0]['windowend'];
												//echo $url;
												$curl = curl_init();
												curl_setopt($curl, CURLOPT_URL, $url);
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($curl, CURLOPT_HEADER, false);
												$data = curl_exec($curl);
												curl_close($curl);
											}
										}
									}
									/*if($cm_id=='27' || $cm_id=='58' || $cm_id=='83' || $cm_id=='45' || $cm_id=='46' || $cm_id=='126' || $cm_id=='90')
		                        {
									$url = URL.'View/calc_apr_one.php?empid='.$EmployeeID.'&date='.date('Y-m-d',strtotime($dt1));
									$curl = curl_init();
									curl_setopt($curl, CURLOPT_URL, $url);
									curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($curl, CURLOPT_HEADER, false);
									$data = curl_exec($curl);
									curl_close($curl);
								}*/
								}
								$alert_msg = '<span class="text-success"><b>Message :</b> ' . $Exception . " Request for " . $count . " day is " . $MngrStatusID . '</b>  by ' . ' SERVER' . '  for Employee <b>' . $EmployeeID . '</span>';
							}

							if ($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445" || $cm_id == "471" || $cm_id == "472" || $cm_id == "473" || $cm_id == "474") {
								$url = URL . 'View/calcRange_zomato.php?empid=' . $EmployeeID . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));
							}
							/*else if($cm_id=='27' || $cm_id=='58' || $cm_id=='83' || $cm_id=='45' || $cm_id=='46' || $cm_id=='126' || $cm_id=='90')
						{
							$url = URL.'View/calcRange_apr.php?empid='.$EmployeeID.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));
						}*/ else {
								$url = URL . 'View/calcRange.php?empid=' . $EmployeeID . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));
							}

							$curl = curl_init();
							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_HEADER, false);
							$data = curl_exec($curl);
							curl_close($curl);
						}
					}
				}
			}
		}
	}
}
#call get_exceed_exp_data('CE07147134');

settimestamp('auto_calc_Exception', 'END');
echo "<script>$(function(){ toastr.success($alert_msg) }); </script>";
echo '<br /> Run for ' . count($data_exp) . ' Employee';
