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
	$rs_emp = $myDB->query("select des_id,DOJ,whole_details_peremp.EmployeeID,salary_details.rt_type from whole_details_peremp inner join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID where whole_details_peremp.EmployeeID in ('AE021914065',
	'AE032133920',
	'AE05185697',
	'AE061920874',
	'AE062029812',
	'AE111811340',
	'AE111924537',
	'AE112032682',
	'CE0121935847',
	'CE0123949663',
	'CE0123949746',
	'CE0123949872',
	'CE0123949917',
	'CE0123949932',
	'CE0123950127',
	'CE0222943146',
	'CE0223950309',
	'CE0223950413',
	'CE0223950669',
	'CE0223950692',
	'CE0223950718',
	'CE0223950755',
	'CE03070014',
	'CE03146043',
	'CE0321936780',
	'CE0322943585',
	'CE0323950786',
	'CE0323950801',
	'CE0323951136',
	'CE0323951138',
	'CE0323951295',
	'CE0323951423',
	'CE04159316',
	'CE0422944404',
	'CE0423951426',
	'CE0522945211',
	'CE0522945339',
	'CE0622945536',
	'CE0622945558',
	'CE0622945880',
	'CE0622945917',
	'CE0721939169',
	'CE0722946111',
	'CE0722946265',
	'CE0722946271',
	'CE0821939477',
	'CE0822947163',
	'CE09134997',
	'CE0921940204',
	'CE0922947699',
	'CE1020935316',
	'CE1020935328',
	'CE1022947902',
	'CE1022948248',
	'CE11091308',
	'CE1121941344',
	'CE1122948392',
	'CE1221941606',
	'CE1222949085',
	'CE1222949125',
	'CE1222949298',
	'CE1222949303',
	'CE1222949367',
	'CE1222949468',
	'CE1222949481',
	'CEA01230212',
	'CEB022212860',
	'CEB042316087',
	'CEB042316093',
	'CEB052213696',
	'CEK012278769',
	'CEK012383530',
	'CEK012383536',
	'CEK012383712',
	'CEK022384041',
	'CEK032036155',
	'CEK032384176',
	'CEK032384179',
	'CEK032384190',
	'CEK032384320',
	'CEK041926681',
	'CEK042175708',
	'CEK042384668',
	'CEK042384676',
	'CEK042384714',
	'CEK042384744',
	'CEK052280121',
	'CEK052280327',
	'CEK062176316',
	'CEK111933457',
	'CEK112282883',
	'CEK122074339',
	'CEK122283053',
	'CEK122283188',
	'CEK122283255',
	'CEK122283267',
	'CEN01230595',
	'CEN01230632',
	'CEN01230650',
	'CEN01230706',
	'CEN02230775',
	'CEN02230833',
	'CEN02230844',
	'CEN02231003',
	'CEN02231017',
	'CEN02231022',
	'CEN03231046',
	'CEN03231111',
	'CEN03231113',
	'CEN03231128',
	'CEN03231136',
	'CEN03231142',
	'CEN04231305',
	'CEN04231309',
	'CEN04231313',
	'CEN11220146',
	'CEN11220151',
	'CEN11220201',
	'CEN11220260',
	'CEN12220309',
	'CEN12220318',
	'CEN12220328',
	'CEN12220386',
	'CEN12220407',
	'CEN12220518',
	'CEN12220525',
	'CEN12220526',
	'CEV042382360',
	'CFK08190103',
	'CFK12191474',
	'CMK032174648',
	'CMK052278016',
	'CMK082175866',
	'CMK092176260',
	'CMK092279225',
	'CMK102279587',
	'CMK112073496',
	'MU01221216',
	'MU03232961',
	'MU03232968',
	'MU03233040',
	'MU04221546',
	'MU04221617',
	'MU04233145',
	'MU06221855',
	'MU07221900',
	'MU07221902',
	'MU07221904',
	'MU08210680',
	'MU08222001',
	'MU08222008',
	'MU08222013',
	'MU09200317',
	'MU09222128',
	'MU09222153',
	'MU09222187',
	'MU10222264',
	'MU10222293',
	'MU10222301',
	'MU10222331',
	'MU10222342',
	'MU10222343',
	'MU11222458',
	'MU11222479',
	'MU11222513',
	'MU12222605',
	'RS012220318',
	'RS012332848',
	'RS012332873',
	'RS022333324',
	'RS022333549',
	'RS032221260',
	'RS032221523',
	'RS032334017',
	'RS032334297',
	'RS042222045',
	'RS042222049',
	'RS042222463',
	'RS042334540',
	'RS052222928',
	'RS082224268',
	'RS092224365',
	'RS092224530',
	'RSM01232890',
	'RSM11222314',
	'RSM11222421',
	'RSM12222673')");


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

			//$date	= date('Y-m-d', $now);
			$date = '2023-08-22';

			if (in_array($var_desg_id, array(1, 2, 3, 4, 6, 9, 11, 12, 17, 18, 19, 20, 25, 26, 27, 28, 30, 33, 34, 35, 36, 31, 38, 39, 40))) {
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

				echo 'call save_paidleave("' . $leave . '","' . $date . '","' . $EmpID . '")';
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
