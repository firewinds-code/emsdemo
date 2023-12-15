<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select t1.statusHead,t2.account_head from corrective_action_form t1 join whole_dump_emp_data t2 on t1.employee_id=t2.EmployeeID where t1.id= '".$_REQUEST['id']."';";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
	foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }	
}


?>