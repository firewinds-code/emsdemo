<?php

require_once(__dir__.'/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call get_secques("'.$_REQUEST['id'].'","'.$_REQUEST['dob'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	
	if(count($result) > 0 && $result)
	{
		foreach($result as $key=>$value){
			echo $value['secques'];
				
			}
		 	}
	
?>

