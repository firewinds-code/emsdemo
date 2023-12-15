<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='delete from question_bank where id = '.$_REQUEST['ID'];
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error)){
		
		if($row_count > 0)
		{
			echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
		}
		else
		{
			echo 'No|Data Not Deleted ...';
		}
	}
	else
	{
		echo 'No|Data Not Deleted ..'.$mysql_error;
		
	}
?>

