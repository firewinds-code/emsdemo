
<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');



$myDB = new MysqliDb();

//$chk_task= $myDB->query("select EmployeeID from exit_emp where cast(createdon as date)=cast(now() as date) and createdby='SEREVER'");
$chk_task = $myDB->query("select EmployeeID from exit_emp where EmployeeID='CMK062175380' and createdby='SEREVER'");

$my_error = $myDB->getLastError();;
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$EmployeeID = $value['EmployeeID'];
		$resdata = $myDB->query("select t1.nt_start,t1.nt_end,t2.rt_type from resign_details t1 join salary_details t2 on t1.EmployeeID = t2.EmployeeID where t1.EmployeeID='" . $EmployeeID . "'");
		if (count($resdata) > 0 && $resdata) {
			/*$resstart=$resdata[0]['nt_start'];
				$resend=$resdata[0]['nt_end'];*/
			$result_array = getRecoveryDay($value['EmployeeID'], $resdata[0]['nt_start'], $resdata[0]['nt_end'], $resdata[0]['rt_type']);
			$myDB = new MysqliDb();

			$sqlInsertrecday = 'call sp_InsertRecovery("' . $value['EmployeeID'] . '","' . $resdata[0]['nt_start'] . '","' . $resdata[0]['nt_end'] . '","' . $result_array[1] . '","' . $result_array[0] . '","' . $result_array[2] . '","' . date('m', strtotime($resdata[0]['nt_end'])) . '","' . date('Y', strtotime($resdata[0]['nt_end'])) . '","' . $resdata[0]['rt_type'] . '")';

			$flag = $myDB->query($sqlInsertrecday);
			$error = $myDB->getLastError();
			//echo($result_array[0]);

		}
	}
}


function getRecoveryDay($EmpID, $startDate, $endDate, $rt_type)
{
	$startday = date('j', strtotime($startDate));
	$startDay2 = 0;
	$payday = 0;
	$iPL = $iPLused = 0;

	if (date('m', strtotime($startDate)) != date('m', strtotime($endDate))) {
		$startDay2 = 1;
		$lastday = date('t', strtotime($startDate));
		$query = 'select ';
		if ($startday != $lastday) {
			while ((int)$startday <= (int)$lastday) {
				$query = $query . 'D' . $startday;
				if ($startday != $lastday) {
					$query = $query . ', ';
				}
				$startday++;
			}
		} else {
			$query = $query . 'D' . $startday;
		}

		$query = $query . ' from calc_atnd_master where EmployeeID="' . $EmpID . '" and month=' . date('m', strtotime($startDate)) . ' and year=' . date('Y', strtotime($startDate));
		$payday = getPaybleDay($query, $rt_type);
	}
	if ($startDay2 == 0) {
		$startDay2 = date('j', strtotime($startDate));
	}

	//$startday = date('j',strtotime($endDate));
	$lastday = date('j', strtotime($endDate));
	$query = 'select ';
	if ($startDay2 != $lastday) {
		while ((int)$startDay2 <= (int)$lastday) {
			$query = $query . 'D' . $startDay2;
			if ($startDay2 != $lastday) {
				$query = $query . ', ';
			}
			$startDay2++;
		}
	} else {
		$query = $query . 'D' . $startDay2;
	}

	$query = $query . ' from calc_atnd_master where EmployeeID="' . $EmpID . '" and month=' . date('m', strtotime($endDate)) . ' and year=' . date('Y', strtotime($endDate));
	$payday = $payday + getPaybleDay($query, $rt_type);

	$d1 = new DateTime($startDate);
	$d2 = new DateTime($endDate);
	$totalresday = $d2->diff($d1)->format('%a') + 1;
	//$recday = $totalresday - $payday;	
	$recday = $payday - $totalresday;
	$iPL = calcCOPL($endDate, $EmpID);

	// if ($iPL > 0)
	// {

	// }


	// if ($recday > 0) {
	// 	if ($iPL > 0) {

	// 		if ($iPL > $recday) {
	// 			$iPLused = $recday;
	// 			$recday = $iPLused - $recday;
	// 		} else {
	// 			$recday = $recday - $iPL;
	// 			$iPLused = $iPL;
	// 		}
	// 	}
	// }


	//return array(0 => $recday, 1 => $payday, 2 => $iPLused);
	return array(0 => $recday, 1 => $payday, 2 => $iPL);
}
function calcCOPL($date, $EmpID)
{
	$date = date('Y-m-d', strtotime($date . ' + 1 days'));
	$myDB = new MysqliDb();
	$pd_current = $myDB->query('call get_paidleave_current("' . $date . '","' . $EmpID . '")');
	$myDB = new MysqliDb();
	$pd_urned = $myDB->query('call get_paidleave_urned("' . $date . '","' . $EmpID . '")');
	$mysql_error = $myDB->getLastError();
	$iPL = 0;
	$paid_urned = 0;
	if ($pd_current) {
		if (count($pd_current) > 0) {
			$iPL = $pd_current[0]['paidleave'];
			if (count($pd_urned) > 0) {

				$paid_urned = $pd_urned[0]['paidleave'];
				if ($paid_urned == null) {
					$paid_urned = 0;
				}
			}

			if ($paid_urned > 0) {
				$iPL = $iPL - $paid_urned;
			}
			if ($iPL <= 0) {
				$iPL = 0;
			}
		}
	}
	return $iPL;
}


function getPaybleDay($query, $rt_type)
{
	$myDB = new MysqliDb();
	$result = $myDB->query($query);
	$calc = 0;
	$my_error = $myDB->getLastError();;
	if (count($result) > 0 && $result) {
		foreach ($result as $key => $value) {
			foreach ($value as $k => $val) {
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
		}
	}

	return $calc;
}

?>

