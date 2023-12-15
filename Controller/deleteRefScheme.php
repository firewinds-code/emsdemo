<?php

require_once(__dir__.'/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call sp_delete_refscheme("'.$_REQUEST['ID'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	//$row_count = mysql_affected_rows();
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error)){
		
		echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Reference Scheme is Deleted successfuly  </span></span>';
		
		/*if(count($result) > 0)
		{
			echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
		}
		else
		{
			echo 'Data Not Deleted ...';
		}*/
	}
	else
	{
		echo 'Data Not Deleted ..'.$mysql_error;
		
	}
?>

