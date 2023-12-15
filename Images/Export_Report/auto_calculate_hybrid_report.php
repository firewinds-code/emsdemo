<?php
require_once(__dir__.'/../Config/init.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
require_once(CLS.'MysqliDb.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
$modulename="hybrid_data_calculation";
$myDB=new MysqliDb();
$myDB->query("Insert into scheduler set modulename='".$modulename."',type='Start' ");
$myDB=new MysqliDb();	
$chk_task=$myDB->query('call proc_brada()');
$my_error= $myDB->getLastError();			
$table = '';
if(count($chk_task) > 0 && $chk_task)
{  
	$myDB=new MysqliDb();
	$myDB->query("Insert into scheduler set modulename='".$modulename."',type='End' ");
echo "Query executed successfully";
}
else
{
	echo $table="No Data Found  ... ".$my_error."";
	
}

	
?>