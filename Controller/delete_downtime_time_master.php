<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
$id = explode('_',$_REQUEST['ID']);
if(isset($_REQUEST['ID']) && is_numeric($id[2]))
{
$sql='delete from downtime_time_master where ID='.$id[2];
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error))
	{
		echo "data deleted";
		
	}
	else
	{
		echo "Row Not Deleted Try Again :".$mysql_error;
	}
}
?>

