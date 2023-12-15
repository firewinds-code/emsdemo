<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call sp_getDTMsgTrail("'.$_REQUEST['ID'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	
	if( count($result) > 0 && $result)
	{
		
		foreach($result as $key => $value)
		{
			echo '<p style="padding: 5px;border: 1px solid gray;background: white;box-shadow: 0px 0px 1px 1px rgba(128, 128, 128, 0.21),0px 0px 1px 1px rgba(128, 128, 128, 0.21) inset;border-radius: 4px;"><span style="color:green;">'.$value['CreatedBy'].'</span> <kbd>'.$value['CreatedOn'].'</kbd> : '.$value['Comments'].'</p>';
		}
		echo '<br />';
	}
	else
	{
		echo 'No Comment ';
		
	}
?>

