<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='select statusHr from corrective_action_formhr where corrective_Formid="'.$_REQUEST['ID'].'" order by id desc limit 1';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			echo $value['statusHr'];
			/*foreach($value as $k => $v)
			{
				if(trim($v)!='')
				{
					echo $v.'|$|';
				}
				else
				{
					echo 'Not Exist';
				}
					
				
				
			}*/
		}
		//echo '<br />';
	}
	else
	{
		echo 'Not Exist';
		
	}
?>

