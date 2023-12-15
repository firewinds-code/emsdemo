<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select count(*) as count from corrective_action_form where employee_id='".$_REQUEST['EmpID']."' and (cast(created_at as date) between '".$_REQUEST['Date1']."' and '".$_REQUEST['Date2']."');";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
	//echo $result[0]['id'].'|$|'.$result[0]['created_at'];
	echo $result[0]['count'].'|$|';
    /*foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }*/	
}

$myDB=new MysqliDb();
//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select id from corrective_action_form where employee_id='".$_REQUEST['EmpID']."' and (cast(created_at as date) between '".$_REQUEST['Date1']."' and '".$_REQUEST['Date2']."') order by id desc limit 3;";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
	//echo $result[0]['id'].'|$|'.$result[0]['created_at'];
	//echo $result[0]['id'].'|$|';
    foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }
}

?>