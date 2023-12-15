<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//$EmployeeID = 'CMK052381607';

$date	= date('Y-m-d', time());
//$date	= '2023-06-12';
//die;
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$sq1 = "insert into scheduler(modulename,type)values('" . $module . "','" . $type . "');";
	$myDB->query($sq1);
}

settimestamp('Auto OJT Downtime', 'Start');

$sql = "select t1.EmployeeID,cast(InOJT as date) as inojt,DATEDIFF(cast(now() as date), cast(InOJT as date))+1 as dd from status_table t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID where t2.emp_status='Active' and t1.Status=5  and t2.df_id in (74,77,146,147,148,149) and cast(t1.InQAOJT as date)<=cast(now() as date);";


$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (empty($mysql_error) && count($result) > 0) {
	foreach ($result as $key => $value) {
		//echo $value['inojt'];
		$myDB = new MysqliDb();
		$sql_bio = "select id from biopunchcurrentdata where empid='" . $value['EmployeeID'] . "' and dateon=cast(now() as date) limit 1";
		$result_bio = $myDB->query($sql_bio);
		if (count($result_bio) > 0) {
			$sql_roster = "select count(*) as `count`  from roster_temp where EmployeeID = '" . $value['EmployeeID'] . "' and (InTime like '%WO%' or OutTime like '%WO%') and  DateOn between '" . $value['inojt'] . "' and cast(now() as date)  limit 1";


			$dt_days = $myDB->query($sql_roster);
			$dt_days = $dt_days[0]['count'];
			if (empty($dt_days) || !$dt_days) {
				$dt_days = 0;
			}
			//echo $value['dd'] + $dt_days;
			$day = $value['dd'] - $dt_days;
			if ($day <= 20) {
				echo $sql_bio = "call ojtdaysfordowntime('" . $value['EmployeeID'] . "','" . $day . "','" . $date . "')";
				$flag = $myDB->query($sql_bio);
			}
		}
	}
}

settimestamp('Auto OJT Downtime', 'End');
