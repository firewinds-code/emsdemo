<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$sq1 = "insert into scheduler(modulename,type)values('" . $module . "','" . $type . "');";
	$myDB->query($sq1);
}
settimestamp('Calc_Leave', 'Start');
$myDB = new MysqliDb();
//$rs_emp =$myDB->query("select des_id,DOJ,whole_details_peremp.EmployeeID,salary_details.rt_type from whole_details_peremp inner join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID ");//where cm_id not in (88) or (cm_id = 88 and df_id!=74)

try {
	//$rs_emp = $myDB->query("select des_id,DOJ,whole_details_peremp.EmployeeID,salary_details.rt_type from whole_details_peremp inner join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID");

	$rs_emp = $myDB->query("select des_id,dateofjoin as DOJ,t1.EmployeeID,t2.rt_type from employee_map t1 inner join salary_details t2 on t1.EmployeeID = t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id where t1.emp_status='Active' and cm_id not in (520,521,535,252)
	Union
	select des_id,dateofjoin as DOJ,t1.EmployeeID,t2.rt_type from employee_map t1 inner join salary_details t2 on t1.EmployeeID = t2.EmployeeID join df_master t3 on t1.df_id=t3.df_id where t1.emp_status='Active' and cm_id in (520,521,535,252) and t1.df_id not in (74, 77, 146, 147, 148, 149)");

	//$date = '2016-09-01';//date('Y-m-d',time());
	ini_set('display_errors', 0);
	$staff = 0;
	$other = 0;
	if (count($rs_emp) > 0) {
		foreach ($rs_emp as $rs_key => $rs_val) {
			$var_desg_id = intval($rs_val['des_id']);
			$EmpID = $rs_val['EmployeeID'];
			$rt_type = $rs_val['rt_type'];
			$now = time();
			if ((int)date('d', $now) <= 6) {
				$now = strtotime('last day of previous month');
			}

			$date	= date('Y-m-d', $now);
			//$date = '2022-09-11';

			if (in_array($var_desg_id, array(1, 2, 3, 4, 6, 9, 11, 12, 17, 18, 19, 20, 25, 26, 27, 28, 30, 33, 34, 35, 36, 31, 38, 39, 40, 41))) {
				// or your date as well
				$doj = $rs_val['DOJ'];
				$your_date = strtotime($rs_val['DOJ']);
				$day = date('d', $your_date);
				if ($day <= 15) {
					$your_date  = date('Y-m-', $your_date) . '01';
				} else {
					$your_date = date('Y-m-01', strtotime(date('Y-m-01', $your_date) . ' +1 months'));
				}

				$iTime_in = new DateTime($your_date);
				$iTime_out = new DateTime($date);
				$interval = $iTime_in->diff($iTime_out);
				$day_count = $interval->format('%r%m');
				if ($interval->format('%r%y') > 0) {
					$day_count = $day_count  + (12 * $interval->format('%r%y'));
				}

				if ($day_count > 3 || ($day_count >= 3 && $interval->format('%r%d') > 4)) {
					//$myDB = new MysqliDb();

					$result = $myDB->query('select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID = "' . $EmpID . '" and Month=' . date('n', strtotime($date)) . ' and Year=' . date('Y', strtotime($date)) . '');
					if ($result) {
						$other++;
						$calc = 0;
						$counter = 1;
						$date_counter = intval(date('j', strtotime($date)));
						//var_dump($result[0]); die;
						foreach ($result as $key => $value) {

							foreach ($value as $k => $val) {
								if ($counter <= $date_counter) {
									if (strtoupper($val)  == 'P' || strtoupper($val) == 'L' || strtoupper($val) == 'HO' || strtoupper($val) == 'CO' || strtoupper($val) == 'WO') {
										if (strtoupper($val) == 'P' && $rt_type == '3') {
										} else {
											$calc++;
										}
									} else if (strtoupper($val[0]) == 'P') {
										if ($rt_type == '3') {
										} else {
											$calc++;
										}
									} else if (strtoupper($val) == 'H') {
										if ($rt_type == '3') {
										} else {
											$calc++;
										}
									} else if (strtoupper($val[0]) == 'H' && strtoupper($val) != 'HWP') {
										if ($rt_type == '3') {
										} else {
											$calc++;
										}
									} else if (strtoupper($val) == 'HWP') {

										if ($rt_type == '3') {
											$calc = $calc + 1;
										} else {
											$calc = $calc + 0.5;
										}
									} else if ($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP') {
										if ($rt_type == '3') {
											$calc = $calc + 1;
										} else {
											$calc = $calc + 0.5;
										}
									} else if ($val == 'L(Biometric Issue)' || substr($val, 0, 3) == 'L(B') {
										$calc = $calc + 1;
									}
								}
								$counter++;
							}
						}
						$leave = 0;
						if ($calc >= 25) {
							$leave = 1;
						} else if ($calc < 25 && $calc >= 15) {
							$leave = 0.5;
						} else if ($calc >= 10 && $calc < 15) {
							$leave = 0.5;
						} else {
							$leave = 0;
						}



						$getMonth = date('n', strtotime($date));
						$getYear  = date('Y', strtotime($date));

						//$myDB = new MysqliDb();
						$last_remains = 0;
						$result = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="' . $EmpID . '" and Month(date_paid)=' . $getMonth . ' and Year(date_paid)=' . $getYear . ' order by id limit 1;');

						$sum_release = 0;
						if (count($result) > 0) {
							$sum_release = $result[0]['paidleave'];
						}

						if ($sum_release == 0) {
							$leave = $leave + 0;
						} else {


							$leave = $leave + $sum_release;
						}

						//$myDB = new MysqliDb();
						$dataPL1 = $myDB->query('call get_paidleave_urned("' . $date . '", "' . $EmpID . '");');

						if ($dataPL1[0]['paidleave'] == '') {
							$pl = $leave - 0;
						} else {
							$pl = $leave - $dataPL1[0]['paidleave'];
						}



						if ($pl > 12) {
							$leave = 12 + $dataPL1[0]['paidleave'];
						}


						//$myDB = new MysqliDb();
						$result1 = $myDB->query('call save_paidleave("' . $leave . '","' . $date . '","' . $EmpID . '")');
						echo $my_error = $myDB->getLastError();
						echo $EmpID . ' : ' . $leave . ' and Days ' . $calc . '<br/>';
					}
				}
			} else if (in_array($var_desg_id, array(5, 7, 8, 10, 13, 15, 16, 22, 23, 29, 32, 37))) {
				//$myDB = new MysqliDb();
				$result = $myDB->query('select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID = "' . $EmpID . '" and Month=' . date('n', strtotime($date)) . ' and Year=' . date('Y', strtotime($date)) . '');
				if ($result) {
					$calc = 0;
					$staff++;
					$counter = 1;
					$date_counter = intval(date('j', strtotime($date)));
					foreach ($result as $key => $value) {

						foreach ($value as $k => $val) {
							if ($counter <= $date_counter) {
								if (strtoupper($val)  == 'P' || strtoupper($val) == 'L' || strtoupper($val) == 'HO' || strtoupper($val) == 'CO' || strtoupper($val) == 'WO') {
									if (strtoupper($val) == 'P' && $rt_type == '3') {
									} else {
										$calc++;
									}
								} else if (strtoupper($val[0]) == 'P') {
									if ($rt_type == '3') {
									} else {
										$calc++;
									}
								} else if (strtoupper($val) == 'H') {
									if ($rt_type == '3') {
									} else {
										$calc++;
									}
								} else if (strtoupper($val[0]) == 'H' && strtoupper($val) != 'HWP') {
									if ($rt_type == '3') {
									} else {
										$calc++;
									}
								} else if (strtoupper($val) == 'HWP') {
									if ($rt_type == '3') {
										$calc = $calc + 1;
									} else {
										$calc = $calc + 0.5;
									}
								} else if ($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP') {
									if ($rt_type == '3') {
										$calc = $calc + 1;
									} else {
										$calc = $calc + 0.5;
									}
								} else if ($val == 'L(Biometric Issue)' || substr($val, 0, 3) == 'L(B') {
									$calc = $calc + 1;
								}
							}
							$counter++;
						}
					}
					$leave = 0;
					if ($calc >= 25) {
						$leave = 1.5;
					} else if ($calc < 25 && $calc >= 15) {
						$leave = 1.0;
					} else if ($calc >= 10 && $calc < 15) {
						$leave = 0.5;
					} else {
						$leave = 0;
					}

					$getMonth = date('n', strtotime($date));
					$getYear  = date('Y', strtotime($date));

					//$myDB = new MysqliDb();
					$last_remains = 0;
					$result = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="' . $EmpID . '" and Month(date_paid)=' . $getMonth . ' and Year(date_paid)=' . $getYear . ' order by id limit 1;');

					$sum_release = 0;
					if (count($result) > 0) {
						$sum_release = $result[0]['paidleave'];
					}

					if ($sum_release == 0) {
						$leave = $leave + 0;
					} else {


						$leave = $leave + $sum_release;
					}
				}

				//$myDB = new MysqliDb();
				$dataPL1 = $myDB->query('call get_paidleave_urned("' . $date . '", "' . $EmpID . '");');

				if ($dataPL1[0]['paidleave'] == '') {
					$pl = $leave - 0;
				} else {
					$pl = $leave - $dataPL1[0]['paidleave'];
				}


				if ($pl > 18) {

					$leave = 18 + $dataPL1[0]['paidleave'];
				}


				//$myDB = new MysqliDb();
				$result1 = $myDB->query('call save_paidleave("' . $leave . '","' . $date . '","' . $EmpID . '")');
				echo $my_error = $myDB->getLastError();
				echo $EmpID . ' : ' . $leave . '<br/>';
			}
		}
	}

	settimestamp('Calc_Leave', 'END');
	echo 'Staff  ::  => ' . $staff;
	echo '<br /> <br />Other  ::  => ' . $other;
} catch (Exception $ex) {
	echo $ex->getMessage();
}
