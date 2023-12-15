<?php
require_once(__dir__.'/../Config/init.php');
date_default_timezone_set('Asia/Kolkata');
require_once(CLS.'MysqliDb.php');
if(isset($_REQUEST['cm_id']))
{
	
	$sql = "select count(*) from employee_map inner join (select *,max(createdon) from nww_csa_ranking where Type = 'MIS' and  month(DateFor) = month(curdate()) and year(DateFor) = year(curdate()) group by EmployeeID) as t1 on t1.EmployeeID = employee_map.EmployeeID where emp_status ='Active' and cm_id = '".$_REQUEST['cm_id']."' and df_id in (74,77)";
	
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	if(count($result) > 0 && $result)
	{
		echo 'done|'.$result[0]['count'];
	}
	else
	{
		echo 'no '.$drt;
	}
}
?>