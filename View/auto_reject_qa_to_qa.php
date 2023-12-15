<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB=new MysqliDb();
$data_qa_ops = $myDB->query('SELECT EmployeeID FROM tbl_qa_to_qa_movement where Status in (0,1,2,4);');
if(count($data_qa_ops) > 0 && $data_qa_ops)
{
	foreach($data_qa_ops as $key=>$Employee)
	{
		$EmployeeID = $Employee['EmployeeID'];
		
		if(!empty($EmployeeID))
		{
			$query_update = "update tbl_qa_to_qa_movement set Status = '7' where EmployeeID = '".$EmployeeID."' and Status in (0,1,2,4);";
			$myDB=new MysqliDb();
			$flag = $myDB->rawQuery($query_update);
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			echo $flag.'|'.$query_update.$mysql_error.'<br />';
		}
		else
		{
			echo $EmployeeID.' Not Updated<br />';
		}
	}
}


?>