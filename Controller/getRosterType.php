<?php

require_once(__dir__.'/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

ini_set('display_errors',0);
if($_REQUEST['Date'] != '' && $_REQUEST['Date'] != null)
{
	$dd = date('Y-n-j',strtotime($_REQUEST['Date']));
}
else
{
	$dd = date('Y-n-j',time());
}
//$dd = date('Y-n-j',strtotime($_REQUEST['Date']));
$EmpID = $_REQUEST['EmpID'];
if(isset($_REQUEST))
{
$myDB=new MysqliDb();
$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$EmpID.'" and DateOn ="'.$dd.'" order by id desc limit 1');
$error = $myDB->getLastError();
if(count($rst) > 0 && $rst)
{
	if(intval($rst[0]['type_']) != 0)
	{
		echo $rst[0]['type_'];
	}
	else
	{
		echo 1;
	}
	
}
else
{
	echo 1;
}
}
else
{
	echo 1;
}

?>

