<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors',0);
$EmployeeID = $_REQUEST['ID'];
$type = $_REQUEST['type'];

$year = 0;
if($type == '12th')
{
	$myDB = new MysqliDb();
	$result = $myDB->query('select passing_year from education_details where EmployeeID="'.$EmployeeID.'" and  edu_name="10th"');	
	foreach($result  as $key=>$value)
	{
		 $year = $value['passing_year'];
	}
	

}
else if($type == '10th')
{
	$myDB = new MysqliDb();
	$result = $myDB->query('select passing_year from education_details where EmployeeID="'.$EmployeeID.'" and  edu_name="12th"');	
	foreach($result  as $key=>$value)
	{
		 $year = $value['passing_year'];
	}

}
echo $year;
?>

