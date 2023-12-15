<?php
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['getuser']) && $_REQUEST['getuser']=='rto'){
	

$query="SELECT distinct(ep.EmployeeID),st.ReportTo  FROM ems.status_table st inner join employee_map ep on st.EmployeeID=ep.EmployeeID inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id inner Join client_master cm on cm.client_id=ncm.client_name   where ep.emp_status='Active' and ep.EmployeeID NOT IN ( select EmployeeID from ryg_reportto  where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) ";

$myDB=new MysqliDb();
$result=$myDB->query($query);
//print_r($result);
echo count($result);
echo "<br>";
if(count($result)>0){
	foreach($result as $val){
		$EmployeeID=$val['EmployeeID'];
		$ReportTo=$val['ReportTo'];
		$ryg_status='Green';
		$ryg_substatus='3';
		$ryg_remark='Auto close remark';
		 echo $insertQuery="insert into ryg_reportto set EmployeeID='".$EmployeeID."',reportto_id='".$ReportTo."',ryg_status='".$ryg_status."',ryg_substatus='".$ryg_substatus."',ryg_remark='".$ryg_remark."' ";
		$myDB=new MysqliDb();
		$insresult=$myDB->query($insertQuery);
		echo "<br>";
	}
}
}else
if(isset($_REQUEST['getuser']) && $_REQUEST['getuser']=='oh')
{
	$query="SELECT distinct(ep.EmployeeID),ncm.oh from employee_map ep  inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id  where  ep.emp_status='Active' and ep.EmployeeID NOT IN  ( select EmployeeID from ryg_oh    where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) ";
	
	$myDB=new MysqliDb();
	$result=$myDB->query($query);
	echo count($result);
	echo "<br>";
	if(count($result)>0)
	{
		foreach($result as $val){
			$EmployeeID=$val['EmployeeID'];
			$ohid=$val['oh'];
			$ryg_status='Green';
			$ryg_substatus='3';
			$ryg_remark='Auto close remark';
			$insertQuery="insert into ryg_oh set EmployeeID='".$EmployeeID."',oh_id='".$ohid."',ryg_status='".$ryg_status."',ryg_substatus='".$ryg_substatus."',ryg_remark='".$ryg_remark."' ";
			$myDB=new MysqliDb();
			$insresult=$myDB->query($insertQuery);
			echo "<br>";
		}
	}
}
else
if(isset($_REQUEST['getuser']) && $_REQUEST['getuser']=='ah')
{
	$query="SELECT distinct(ep.EmployeeID),ncm.account_head from employee_map ep  inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id  where  ep.emp_status='Active' and ep.EmployeeID NOT IN  ( select EmployeeID from ryg_ah    where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) )";
	$myDB=new MysqliDb();
	$result=$myDB->query($query);
	echo count($result);
	echo "<br>";
	
	if(count($result)>0)
	{
		foreach($result as $val)
		{
			$EmployeeID=$val['EmployeeID'];
			$ahid=$val['account_head'];
			$ryg_status='Green';
			$ryg_substatus='3';
			$ryg_remark='Auto close remark';
			$insertQuery="insert into ryg_ah set EmployeeID='".$EmployeeID."',ah_id='".$ahid."',ryg_status='".$ryg_status."',ryg_substatus='".$ryg_substatus."',ryg_remark='".$ryg_remark."' ";
			$myDB=new MysqliDb();
			$insresult=$myDB->query($insertQuery);
			//echo "<br>";
		}
	}
}

?>