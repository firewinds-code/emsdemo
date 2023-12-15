<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');

$sql='call remove_leave('.$_REQUEST['ID'].')';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error))
		{
			
		
		echo "Done|Row Deleted Successfully";
		/*if(mysql_affected_rows()>0)
		{
			echo "Done|Row Deleted Affected Row are :".mysql_affected_rows();
		}
		else
		{
			echo "No|Row Not Deleted:<code> May be action taken on it </code>";
		}*/
		
		 
	}
	else
	{
		echo "No|Row Not Deleted Try Again :".$mysql_error;
	}
?>

