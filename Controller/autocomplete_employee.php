<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors',0);
$term = $_REQUEST['term'];
$myDB = new MysqliDb();
$result = $myDB->query('select distinct EmployeeID,EmployeeName from personal_details where EmployeeID like "%'.$term.'%" or EmployeeName like "%'.$term.'%"');
foreach($result  as $key=>$value)
{
	 $data[] = $value['EmployeeName'].'('.$value['EmployeeID'].')';
}
echo json_encode($data);
?>

