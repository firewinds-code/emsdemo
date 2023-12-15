<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='select id from corrective_action_formemp where corrective_Formid="'.$_REQUEST['ID'].'" order by id desc limit 1';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			echo '1';
		}
		//echo '<br />';
	}
	else
	{
		echo '0';
		
	}
?>

