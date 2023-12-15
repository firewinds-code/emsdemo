<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$rs_emp =$myDB->query("select des_id,DOJ,EmployeeID from whole_details_peremp  ");
//$date = '2016-09-01';//date('Y-m-d',time());
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$staff = 0;
$other = 0;
if(count($rs_emp) > 0 && $rs_emp)
{
	foreach($rs_emp as $rs_key=>$rs_val)
	{
		$EmpID = $rs_val['EmployeeID'];	
		if($EmpID1!=$EmpID)
		{
			
		
		$var_desg_id = intval($rs_val['des_id']);
		
		$now =  strtotime(date('Y-m-01',time()));  // time();
		$date	=date('Y-m-d', strtotime('last day of previous month'));
		$getMonth = date('n',strtotime($date));
		$getYear  = date('Y',strtotime($date));
		$myDB = new MysqliDb();
		$last_drawn =0;
		
		$result0 = $myDB->query('call get_paidleave_urned_for_1day("'.$date.'","'.$EmpID.'");');
		
		if($result0)
		{
			$last_drawn = $result0[0]['paidleave'];
		}
		$myDB = new MysqliDb();
		$last_remains =0;
		$result0 = $myDB->query('call get_paidleave_current("'.$date.'","'.$EmpID.'");');
		
		if($result0)
		{
			$last_remains = $result0[0]['paidleave'];
		}
		$sum_release = $last_remains - $last_drawn;
		if(intval($sum_release) >= 12 && in_array($var_desg_id,array(1,2,3,4,6,9,11,12,17,18,19,20,25,26,27,28,30)))
		{
			$sum_release = 12;
		}
		elseif(intval($sum_release) >= 18 && in_array($var_desg_id,array(5,7,8,10,13,15,16,22,23,29)))
		{
			$sum_release = 18;
		}
		$myDB = new MysqliDb();
		$result1 = $myDB->query('call save_paidleave("'.$sum_release.'","'.date('Y-m-d',$now).'","'.$EmpID.'")');
		echo $myDB->getLastError();
		echo $EmpID.' : '.$sum_release.'<br/>';
		}
		$EmpID1 = $rs_val['EmployeeID'];
	}
}

echo 'Staff  ::  => '.$staff;
echo '<br /> <br />Other  ::  => '.$other;
