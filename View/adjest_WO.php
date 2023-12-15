<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$day_array = array();
$weekOFF_date = array();
$myDB = new MysqliDb();
//$rs_emp =$myDB->query("select des_id,DOJ,whole_details_peremp.EmployeeID,salary_details.rt_type from whole_details_peremp inner join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID where cm_id not in (88) or (cm_id = 88 and df_id!=74)");

$rs_emp = $myDB->query("select des_id,dateofjoin as DOJ,t1.EmployeeID,t2.rt_type from employee_map t1 inner join salary_details t2 on t1.EmployeeID = t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id where t1.emp_status='Active' and cm_id not in (520,521,535,252) Union select des_id,dateofjoin as DOJ,t1.EmployeeID,t2.rt_type from employee_map t1 inner join salary_details t2 on t1.EmployeeID = t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id where t1.emp_status='Active' and cm_id in (520,521,535,252) and t1.df_id not in (74, 77, 146, 147, 148, 149)");

//$rs_emp =$myDB->query("select des_id,DOJ,whole_details_peremp.EmployeeID,salary_details.rt_type from whole_details_peremp inner join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID where whole_details_peremp.EmployeeID='AE041917337'");

//$date = '2016-09-01';//date('Y-m-d',time());
try {
	$monday_counter = array();
	$rosterType = array();
	$val = '';
	//$myDB = new MysqliDb();
	if (count($rs_emp) > 0) {
		foreach ($rs_emp as $rs_key => $rs_val) {
			$EmpID = '';
			$rosterType = array();
			$monday_counter = array();
			$day_array = array();
			$weekOFF_date = array();
			$var_desg_id = intval($rs_val['des_id']);
			$EmpID = $rs_val['EmployeeID'];
			$cm_id = "";
			$des_id = "";
			$now = date('Y-m-d', strtotime('last monday'));
			$date	= date('Y-m-d', strtotime($now . ' -14 days'));
			$monday_counter[1] = $now;
			$monday_counter[0] = date('Y-m-d', (strtotime($now . ' -7 days')));
			$counter = 1;

			$rs_type = 	$myDB->query("select type_ from roster_temp where EmployeeID ='" . $EmpID . "' and DateOn = '" . $date . "'");
			if (count($rs_type) > 0) {
				$rosterType[0] = $rs_type[0]['type_'];
			} else {
				$rosterType[0] = 1;
			}
			unset($rs_type);
			//$myDB = new MysqliDb();
			$rs_type = 	$myDB->query("select type_ from roster_temp where  EmployeeID ='" . $EmpID . "' and DateOn = '" . date('Y-m-d', (strtotime($now . ' -2 days'))) . "'");
			if (count($rs_type) > 0) {
				$rosterType[1] = $rs_type[0]['type_'];
			} else {
				$rosterType[1] = 1;
			}

			//$myDB = new MysqliDb();
			$checkdes = $myDB->query("select cm_id,des_id from whole_details_peremp where EmployeeID ='" . $EmpID . "'");
			if (count($checkdes) > 0) {
				$des_id = $checkdes[0]['des_id'];
				$cm_id = $checkdes[0]['cm_id'];
			}

			unset($rs_type);
			if (date('m', strtotime($date)) != date('m', strtotime($now))) {
				$getLastDay =  date('t', strtotime($date));
				$string = 'select ';
				for ($i = intval(date('d', strtotime($date))); $i <= $getLastDay; $i++) {
					if ($i == $getLastDay) {
						$string .= 'D' . intval($i);
					} else {
						$string .= 'D' . intval($i) . ',';
					}
				}

				$string .= ' from calc_atnd_master where EmployeeID="' . $EmpID . '" and `Month`=' . intval(date('m', strtotime($date))) . ' and `Year`=' . intval(date('Y', strtotime($date))) . ' limit 1';


				//$myDB = new MysqliDb();
				$temp_ds1 = $myDB->query($string);
				//echo $string;
				for ($i = date('d', strtotime($date)); $i <= $getLastDay; $i++) {

					$day_array[$counter] = (empty($temp_ds1[0]['D' . $i])) ? '-' : $temp_ds1[0]['D' . $i];

					if (strtoupper($day_array[$counter]) == 'WO' || strtoupper($day_array[$counter]) == 'WONA') {
						if ($i < 10) {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($date))) . '0' . $i;
							$date_of_WO = date('Y-m-', (strtotime($date))) . '0' . $i;
						} else {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($date))) . $i;
							$date_of_WO = date('Y-m-', (strtotime($date))) . $i;
						}
						if (strtoupper($day_array[$counter]) == 'WONA') {
							//$myDB = new MysqliDb();
							$myDB->query('call changeWONA_to_WO("' . $EmpID . '","' . $date_of_WO . '")');
						}
					}
					if (strtoupper($day_array[$counter]) == 'HWP') {
						$myDB = new MysqliDb();
						$app = '';
						$dt = 0;
						if ($i < 10) {
							$dt = '0' . $i;
						} else {
							$dt = $i;
						}
						$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Half Day','" . date('Y-m-', (strtotime($date))) . $dt . "')");
						if (count($db_app) > 0) {
							foreach ($db_app as $key => $val) {
								foreach ($val as $k => $v) {
									$app = $v;
								}
							}
						}
						unset($db_app);
						if ($app == "Approved") {
							$day_array[$counter] = 'H';
						}
					} else if (strtoupper($day_array[$counter]) == 'LWP') {
						$day_array[$counter] = 'LWP';
					}
					$counter++;
				}
				unset($temp_ds1);
				$string1 = 'select ';
				for ($i = 1; $i <= date('d', strtotime($now)); $i++) {
					if ($i == date('d', strtotime($now))) {
						$string1 .= 'D' . intval($i);
					} else {
						$string1 .= 'D' . intval($i) . ',';
					}
				}
				$string1 .= ' from calc_atnd_master where EmployeeID="' . $EmpID . '" and `Month`=' . intval(date('m', strtotime($now))) . ' and `Year`=' . intval(date('Y', strtotime($now))) . ' limit 1';
				//$myDB = new MysqliDb();
				$temp_ds2 = $myDB->query($string1);
				for ($i = 1; $i <= date('d', strtotime($now)); $i++) {
					$day_array[$counter] = (empty($temp_ds2[0]['D' . $i])) ? '-' : $temp_ds2[0]['D' . $i];
					if (strtoupper($day_array[$counter]) == 'WO' || strtoupper($day_array[$counter]) == 'WONA') {
						if ($i < 10) {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($now))) . '0' . $i;
							$date_of_WO = date('Y-m-', (strtotime($now))) . '0' . $i;
						} else {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($now))) . $i;
							$date_of_WO = date('Y-m-', (strtotime($now))) . $i;
						}

						if (strtoupper($day_array[$counter]) == 'WONA') {
							//$myDB = new MysqliDb();
							$myDB->query('call changeWONA_to_WO("' . $EmpID . '","' . $date_of_WO . '")');
						}
					}
					if (strtoupper($day_array[$counter]) == 'HWP') {
						$myDB = new MysqliDb();
						$app = '';
						$dt = 0;
						if ($i < 10) {
							$dt = '0' . $i;
						} else {
							$dt = $i;
						}
						$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Half Day','" . date('Y-m-', (strtotime($now))) . $dt . "')");
						if (count($db_app) > 0) {
							foreach ($db_app as $key => $val) {
								foreach ($val as $k => $v) {
									$app = $v;
								}
							}
						}
						unset($db_app);
						if ($app == "Approved") {
							$day_array[$counter] = 'H';
						}
					} else if (strtoupper($day_array[$counter]) == 'LWP') {
						$day_array[$counter] = 'LWP';
					}
					$counter++;
				}
				unset($temp_ds2);
				//echo $string1;
				//var_dump($day_array);
			} else {
				$string = 'select ';
				for ($i = intval(date('d', strtotime($date))); $i <= date('d', strtotime($now)); $i++) {
					if ($i == date('d', strtotime($now))) {
						$string .= 'D' . intval($i);
					} else {
						$string .= 'D' . intval($i) . ',';
					}
				}
				$string .= ' from calc_atnd_master where EmployeeID="' . $EmpID . '" and `Month`=' . intval(date('m', strtotime($now))) . ' and `Year`=' . intval(date('Y', strtotime($now))) . ' limit 1';
				//echo $string;			
				//$myDB = new MysqliDb();
				$temp_ds2 = $myDB->query($string);
				for ($i = intval(date('d', strtotime($date))); $i <= date('d', strtotime($now)); $i++) {
					$day_array[$counter] = (empty($temp_ds2[0]['D' . $i])) ? '-' : $temp_ds2[0]['D' . $i];
					if (strtoupper($day_array[$counter]) == 'WO' || strtoupper($day_array[$counter]) == 'WONA') {
						if ($i < 10) {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($now))) . '0' . $i;
							$date_of_WO = date('Y-m-', (strtotime($now))) . '0' . $i;
						} else {
							$weekOFF_date[$counter] = date('Y-m-', (strtotime($now))) . $i;
							$date_of_WO = date('Y-m-', (strtotime($date))) . $i;
						}

						if (strtoupper($day_array[$counter]) == 'WONA') {
							//$myDB = new MysqliDb();
							$myDB->query('call changeWONA_to_WO("' . $EmpID . '","' . $date_of_WO . '")');
						}
					}
					if (strtoupper($day_array[$counter]) == 'HWP') {
						$myDB = new MysqliDb();
						$app = '';
						$dt = 0;
						if ($i < 10) {
							$dt = '0' . $i;
						} else {
							$dt = $i;
						}
						$db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Half Day','" . date('Y-m-', (strtotime($date))) . $dt . "')");
						if (count($db_app) > 0) {
							foreach ($db_app as $key => $val) {
								foreach ($val as $k => $v) {
									$app = $v;
								}
							}
						}
						unset($db_app);
						if ($app == "Approved") {
							$day_array[$counter] = 'H';
						}
					} else if (strtoupper($day_array[$counter]) == 'LWP') {
						$day_array[$counter] = 'LWP';
					}
					$counter++;
				}
				unset($temp_ds2);
				//echo $string;
			}

			if ($day_array) {
				$count_level = 1;
				$weekOFF = '';
				$WO = 0;
				$calc = 0;
				$day = 0;
				$co_calc = 0;
				for ($i = 1; $i <= 7; $i++) {
					if (strtoupper($day_array[$i])  == 'P' || strtoupper($day_array[$i]) == 'L' || strtoupper($day_array[$i]) == 'HO' || strtoupper($day_array[$i]) == 'CO' || strtoupper($day_array[$i]) == 'WO' || strtoupper($day_array[$i]) == "WONA") {
						if (((strtoupper($day_array[$i])  == 'P' || strtoupper($day_array[$i]) == 'HO') && $rosterType[0] != 3)) {
							$co_calc++;
						}

						if (strtoupper($day_array[$i]) == "WO" || strtoupper($day_array[$i]) == "WONA") {
							$WO++;
							$weekOFF .= $i . ',';
						} else {
							if ($rosterType[0] == 3 && strtoupper($day_array[$i])  == 'P') {
							} else {
								$calc++;
							}
						}
						$day++;
					} else if (strtoupper($day_array[$i][0]) == 'P') {
						if ($rosterType[0] == 3) {
						} else {
							if (strtoupper($day_array[$i][0])  == 'P') {
								$co_calc++;
							}
							$calc++;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'H') {
						if ($rosterType[0] == 3) {
						} else {
							$calc++;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'HWP') {
						if ($rosterType[0] == 3) {
							$calc = $calc + 1;
							$co_calc++;
						} else {
							$calc = $calc + 0.5;
						}
						$day++;
					}
					//else if(strtoupper($day_array[$i][0]) == 'H' && strtoupper($day_array[$i]) != "HWP" && strtoupper($day_array[$i]) != "HO")
					else if (strtoupper($day_array[$i][0]) == 'H' && strtoupper($day_array[$i]) != "HWP" && strtoupper($day_array[$i]) != "HO" && substr($val, 0, 3) != 'HWP') {
						if ($rosterType[0] == 3) {
						} else {
							$calc++;
						}
						$day++;
					}
					//else if(strtoupper($day_array[$i][3]) == 'HWP')
					else if ($val != '' && ($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP')) {
						if ($rosterType[0] == 3) {
							$calc = $calc + 1;
							$co_calc++;
						} else {
							$calc = $calc + 0.5;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'L' || strtoupper($day_array[$i]) == 'L(Biometric Issue)') {
						$calc = $calc + 1;
						$day++;
					} else if (strtoupper($day_array[$i][0]) == '-' || empty($day_array[$i])) {
					} else {
						$day++;
					}
				}

				$unvelidWeekOf = 0;
				if (($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445") && $day == 7 && ($var_desg_id == 9 || $var_desg_id == 12)) {
					//$myDB = new MysqliDb(); 

					$flag = $myDB->query('call totalday_excWO("' . $EmpID . '","' . $date . '","' . date('Y-m-d', strtotime($date . ' +6 days')) . '")');

					if (count($flag) > 0) {
						$day = $flag[0]['day'];
					}

					if ($day == 6) {
						if ($calc < 4 && $WO > 0) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();
								}
							}
						} else if ($calc >= 4 && $WO == 1) {
							$unvelidWeekOf  = 1; //0;
						} else if ($calc >= 4 && $WO >= 2) {
							$unvelidWeekOf = 1; //0;	
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$week_ch++;
									if ($week_ch > 1) {
										$date_part = explode('-', $weekOFF_date[$val]);
										$day = $date_part[2];
										$month = $date_part[1];
										$year = $date_part[0];
										$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

										//$myDB = new MysqliDb();
										$myDB->query($uodateWeekOFF);
										$error = $myDB->getLastError();

										//$unvelidWeekOf++;
									}
								}
							}
						} else if ($calc >= 4 && $WO == 0) {
							$unvelidWeekOf = "NO";
							if ($calc >= 7 && $co_calc == 7) {
								//$myDB = new MysqliDb();
								$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",1)');
								$unvelidWeekOf = 0;
								$error = $myDB->getLastError();
							} else {
								$unvelidWeekOf = 1;
							}
						}
					} else if ($day == 5) {
						if ($calc < 3 && $WO > 0) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();
								}
							}
						} else if ($calc >= 3 && $calc < 4 && $WO > 1) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$week_ch++;
									if ($week_ch > 1) {
										$date_part = explode('-', $weekOFF_date[$val]);
										$day = $date_part[2];
										$month = $date_part[1];
										$year = $date_part[0];
										$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

										//$myDB = new MysqliDb();
										$myDB->query($uodateWeekOFF);
										$error = $myDB->getLastError();
									}
								}
							}
						} else if ($calc >= 4 && $WO == 2) {
							$unvelidWeekOf  = 1; //0;					
						}
					}
				} else if ($rosterType[0] == 2 && $day == 7) {
					if ($calc < 2 && $WO > 0) {
						$unvelidWeekOf  = 'all';
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$date_part = explode('-', $weekOFF_date[$val]);
								$day = $date_part[2];
								$month = $date_part[1];
								$year = $date_part[0];
								$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";
								//$myDB = new MysqliDb();
								$myDB->query($uodateWeekOFF);
								$error = $myDB->getLastError();
							}
						}
					} else if ($calc >= 2 && $calc < 4 && $WO == 1) {
						$unvelidWeekOf  = 0;
					} else if ($calc >= 2 && $calc < 4 && $WO == 0) {
						$unvelidWeekOf = "NO_1";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",1)');
							$error = $myDB->getLastError();
						}
					} else if ($calc >= 2 && $calc < 4 && $WO > 1) {
						$unvelidWeekOf = 0;
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$week_ch++;
								if ($week_ch > 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();

									$unvelidWeekOf++;
								}
							}
						}
					} else if ($calc >= 4 && $WO == 2) {
						$unvelidWeekOf  = 0;
					} else if ($calc >= 4 && $WO == 1) {
						$unvelidWeekOf = "NO_1";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",1)');
							$error = $myDB->getLastError();
							echo $EmpID . ',' . $error;
						}
					} else if ($calc >= 4 && $WO > 2) {
						$unvelidWeekOf = 0;
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$week_ch++;
								if ($week_ch > 2) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();

									$unvelidWeekOf++;
								}
							}
						}
					} else if ($calc >= 4 && $WO == 0) {
						$unvelidWeekOf = "NO2";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",1)');
							$error = $myDB->getLastError();

							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",2)');
							$error = $myDB->getLastError();
						}
					}
				} elseif ($day == 7) {
					if ($calc < 4 && $WO > 0) {
						$unvelidWeekOf  = 1; //'all';
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$date_part = explode('-', $weekOFF_date[$val]);
								$day = $date_part[2];
								$month = $date_part[1];
								$year = $date_part[0];
								$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

								//$myDB = new MysqliDb();
								$myDB->query($uodateWeekOFF);
								$error = $myDB->getLastError();
							}
						}
					} else if ($calc >= 4 && $WO == 1) {
						$unvelidWeekOf  = 1; //0;
					} else if ($calc >= 4 && $WO >= 2) {
						$unvelidWeekOf = 1; //0;	
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$week_ch++;
								if ($week_ch > 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();

									//$unvelidWeekOf++;
								}
							}
						}
					} else if ($calc >= 4 && $WO == 0) {
						$unvelidWeekOf = "NO";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[0] . '",1)');
							$unvelidWeekOf = 0;
							$error = $myDB->getLastError();
						} else {
							$unvelidWeekOf = 1;
						}
					}
				}
				//echo  $unvelidWeekOf;

				//echo $unvelidWeekOf.','.$weekOFF.'<br />';

				$count_level = 2;
				$weekOFF = '';
				$WO = 0;
				$calc = 0;
				$day = 0;
				$co_calc = 0;

				for ($i = 8; $i < 15; $i++) {
					if (strtoupper($day_array[$i])  == 'P' || strtoupper($day_array[$i]) == 'L' || strtoupper($day_array[$i]) == 'HO' || strtoupper($day_array[$i]) == 'CO' || strtoupper($day_array[$i]) == 'WO' || strtoupper($day_array[$i]) == "WONA") {
						if (((strtoupper($day_array[$i])  == 'P' || strtoupper($day_array[$i]) == 'HO') && $rosterType[1] != 3)) {
							$co_calc++;
						}

						if (strtoupper($day_array[$i]) == "WO" || strtoupper($day_array[$i]) == "WONA") {
							$WO++;
							$weekOFF .= $i . ',';
						} else {
							if ($rosterType[1] == 3 && strtoupper($day_array[$i])  == 'P') {
							} else {
								$calc++;
							}
						}
						$day++;
					} else if (strtoupper($day_array[$i][0]) == 'P') {
						if ($rosterType[1] == 3) {
						} else {
							if (strtoupper($day_array[$i][0])  == 'P') {
								$co_calc++;
							}
							$calc++;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'H') {
						if ($rosterType[1] == 3) {
						} else {
							$calc++;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'HWP') {
						if ($rosterType[1] == 3) {
							$calc++;
							$co_calc++;
						} else {
							$calc = $calc + 0.5;
						}
						$day++;
					}
					//else if(strtoupper($day_array[$i][0]) == 'H' && strtoupper($day_array[$i]) != "HWP" && strtoupper($day_array[$i]) != "HO")
					else if ($val != '' && (strtoupper($day_array[$i][0]) == 'H' && strtoupper($day_array[$i]) != "HWP" && strtoupper($day_array[$i]) != "HO" && substr($val, 0, 3) != 'HWP')) {
						if ($rosterType[1] == 3) {
						} else {
							$calc++;
						}
						$day++;
					}
					//else if(strtoupper($day_array[$i][3]) == 'HWP')
					else if ($val != '' && ($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP')) {
						if ($rosterType[1] == 3) {
							$calc++;
							$co_calc++;
						} else {
							$calc = $calc + 0.5;
						}
						$day++;
					} else if (strtoupper($day_array[$i]) == 'L' || strtoupper($day_array[$i]) == 'L(Biometric Issue)') {
						$calc = $calc + 1;
						$day++;
					} else if (strtoupper($day_array[$i][0]) == '-' || empty($day_array[$i])) {
					} else {
						$day++;
					}
				}

				$unvelidWeekOf = 0;

				if (($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445") && $day == 7 && ($var_desg_id == 9 || $var_desg_id == 12)) {
					//$myDB = new MysqliDb(); 

					$flag = $myDB->query('call totalday_excWO("' . $EmpID . '","' . date('Y-m-d', strtotime($date . ' +7 days')) . '","' . date('Y-m-d', strtotime($date . ' +13 days')) . '")');

					if (count($flag) > 0) {
						$day = $flag[0]['day'];
					}

					if ($day == 6) {
						if ($calc < 4 && $WO > 0) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();
								}
							}
						} else if ($calc >= 4 && $WO == 1) {
							$unvelidWeekOf  = 1; //0;
						} else if ($calc >= 4 && $WO >= 2) {
							$unvelidWeekOf = 1; //0;	
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$week_ch++;
									if ($week_ch > 1) {
										$date_part = explode('-', $weekOFF_date[$val]);
										$day = $date_part[2];
										$month = $date_part[1];
										$year = $date_part[0];
										$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

										//$myDB = new MysqliDb();
										$myDB->query($uodateWeekOFF);
										$error = $myDB->getLastError();

										//$unvelidWeekOf++;
									}
								}
							}
						} else if ($calc >= 4 && $WO == 0) {
							//$unvelidWeekOf = "NO";
							if ($calc >= 7 && $co_calc == 7) {
								//$myDB = new MysqliDb();
								$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
								$error = $myDB->getLastError();
								$unvelidWeekOf = 0;
							} else {
								$unvelidWeekOf = 1;
							}
						}
					} else if ($day == 5) {
						if ($calc < 3 && $WO > 0) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();
								}
							}
						} else if ($calc >= 3 && $calc < 4 && $WO > 1) {
							$unvelidWeekOf  = 1; //'all';
							$counter_weekOFF = explode(',', $weekOFF);
							$week_ch = 0;
							foreach ($counter_weekOFF as $val) {
								if ($val != '' && !empty($val) && intval($val) >= 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();
								}
							}
						} else if ($calc >= 4 && $WO == 2) {
							$unvelidWeekOf  = 1; //0;					
						}
					}

					if ($unvelidWeekOf == 1) {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
					}

					if ($calc < 7) {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
					}
				} else if ($rosterType[1] == 2 && $day == 7) {
					if ($calc < 2 && $WO > 0) {
						$unvelidWeekOf  = 'all';
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$date_part = explode('-', $weekOFF_date[$val]);
								$day = $date_part[2];
								$month = $date_part[1];
								$year = $date_part[0];
								$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

								//$myDB = new MysqliDb();
								$myDB->query($uodateWeekOFF);
								$error = $myDB->getLastError();
							}
						}
					} else if ($calc >= 2 && $calc < 4 && $WO == 1) {
						$unvelidWeekOf  = 0;
					} else if ($calc >= 2 && $calc < 4 && $WO == 0) {
						$unvelidWeekOf = "NO_1";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
							$error = $myDB->getLastError();
						}
					} else if ($calc >= 4 && $WO == 2) {
						$unvelidWeekOf  = 0;
					} else if ($calc >= 4 && $WO == 1) {
						$unvelidWeekOf = "NO_1";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
							$error = $myDB->getLastError();
						}
					} else if ($calc >= 4 && $WO > 2) {
						$unvelidWeekOf = 0;
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$week_ch++;
								if ($week_ch > 2) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);
									$error = $myDB->getLastError();

									$unvelidWeekOf++;
								}
							}
						}
					} else if ($calc >= 4 && $WO == 0) {
						$unvelidWeekOf = "NO2";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
							$error = $myDB->getLastError();

							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",2)');
							$error = $myDB->getLastError();
						}
					}

					if ($unvelidWeekOf == 'all') {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
						$error = $myDB->getLastError();

						//$myDB = new MysqliDb();				    
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",2)');
					} elseif ($unvelidWeekOf == 'NO_1') {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",2)');
					}
					if ($calc < 7) {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",2)');
					}
				} elseif ($day == 7) {
					if ($calc < 4 && $WO > 0) {
						$unvelidWeekOf  = 1; // 'all';
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$date_part = explode('-', $weekOFF_date[$val]);
								$day = $date_part[2];
								$month = $date_part[1];
								$year = $date_part[0];
								$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

								//$myDB = new MysqliDb();
								$myDB->query($uodateWeekOFF);
							}
						}
					} else if ($calc >= 4 && $WO == 1) {
						$unvelidWeekOf  = 1; //0;
					} else if ($calc >= 4 && $WO >= 2) {
						$unvelidWeekOf = 1;
						$counter_weekOFF = explode(',', $weekOFF);
						$week_ch = 0;
						foreach ($counter_weekOFF as $val) {
							if ($val != '' && !empty($val) && intval($val) >= 1) {
								$week_ch++;
								if ($week_ch > 1) {
									$date_part = explode('-', $weekOFF_date[$val]);
									$day = $date_part[2];
									$month = $date_part[1];
									$year = $date_part[0];
									$uodateWeekOFF = "call updated_calcWeekOFF('" . $EmpID . "','D" . intval($day) . "'," . intval($month) . "," . intval($year) . ")";

									//$myDB = new MysqliDb();
									$myDB->query($uodateWeekOFF);

									//$unvelidWeekOf++;
								}
							}
						}
					} else if ($calc >= 4 && $WO == 0) {
						//$unvelidWeekOf = "NO";
						if ($calc >= 7 && $co_calc == 7) {
							//$myDB = new MysqliDb();
							$myDB->query('call insert_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
							$unvelidWeekOf = 0;
						} else {
							$unvelidWeekOf = 1;
						}
					}

					if ($unvelidWeekOf == 1) {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
					}

					if ($calc < 7) {
						//$myDB = new MysqliDb();
						$myDB->query('call delete_CO("' . $EmpID . '","' . $monday_counter[1] . '",1)');
					}
				}
			}
		}
	}
} catch (Exception $ex) {
	echo $ex->getMessage();
}

echo 'complete';
