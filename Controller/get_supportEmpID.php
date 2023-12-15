<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql="select emp_status,df_id from employee_map where EmployeeID='".$_REQUEST['id']."' ";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
$res=0;
if(count($result) > 0 && $result)
{
    if(strtolower($result[0]['emp_status'])=='inactive')
    {
		$res=1;
	}
	else if($result[0]['df_id']=='74' || $result[0]['df_id']=='77')
	{
		$res=2;
	}	
}
else
{
	$res=3;
}

echo $res;
?>