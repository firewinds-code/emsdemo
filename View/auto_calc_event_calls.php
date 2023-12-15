<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

// Resign Event

$myDB=new MysqliDb();
$flag = $myDB->query("call scd_res_inactive();");
$flag = $myDB->query("call inactive_consultancy();");

$date = strtotime(date("Y-m-d")." -1 day");
$query = "update combo_off_master set usedOn  =null , flag=0 where id in (select t1.id from 
(select id,EmployeeID,cast(UsedOn as date) UsedOn from combo_off_master where 
month(usedOn) = ".date("n",$date)." and year(usedOn) = ".date("Y",$date)." and UsedOn is not null) t1
inner join 
(select EmployeeID,str_to_date(DateOn,'%d-%c-%Y') as DateOn from calcAtnd_column_total where
Val !='L' and Val != 'L(Attendance Change)' and Val != 'CO' and Val != 'L(Biometric issue)'  and month(str_to_date(DateOn,'%d-%c-%Y'))
= ".date("n",$date)." and year(str_to_date(DateOn,'%d-%c-%Y')) = ".date("Y",$date).") t2 on 
t1.EmployeeID = t2.EmployeeID and t1.UsedOn = t2.DateOn and t1.UsedOn  < (curdate()-1));";

// Recycle Event

$myDB=new MysqliDb();
$flag = $myDB->query($query);


if(intval(date('d')) >= 2 && intval(date('d')) <= 5)
{
	$date = strtotime('last day of previous month');
	$query = "update combo_off_master set usedOn  =null , flag=0 where id in (select t1.id from 
	(select id,EmployeeID,cast(UsedOn as date) UsedOn from combo_off_master where 
	month(usedOn) = ".date("n",$date)." and year(usedOn) = ".date("Y",$date)." and UsedOn is not null) t1
	inner join 
	(select EmployeeID,str_to_date(DateOn,'%d-%c-%Y') as DateOn from calcAtnd_column_total where
	Val !='L' and Val != 'L(Attendance Change)' and Val != 'CO' and Val != 'L(Biometric issue)'  and month(str_to_date(DateOn,'%d-%c-%Y'))
	= ".date("n",$date)." and year(str_to_date(DateOn,'%d-%c-%Y')) = ".date("Y",$date).") t2 on 
	t1.EmployeeID = t2.EmployeeID and t1.UsedOn = t2.DateOn and t1.UsedOn  < (curdate()-1));";

	// Recycle Event

$myDB=new MysqliDb();
	$flag = $myDB->query($query);
}
