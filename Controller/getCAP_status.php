<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='select t2.statusHead,t3.statusHr from corrective_action_form t1 left join ( select * from corrective_action_formhead where corrective_Formid="'.$_REQUEST['ID'].'" order by id desc limit 1) t2 on t1.id = t2.corrective_Formid left join (select * from corrective_action_formhr where corrective_Formid="'.$_REQUEST['ID'].'" order by id desc limit 1) t3 on t1.id=t3.corrective_Formid where t1.id="'.$_REQUEST['ID'].'"';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			foreach($value as $k => $v)
			{
				if(trim($v)!='')
					echo $v.'|$|';
				
				
			}
		}
		//echo '<br />';
	}
	else
	{
		echo '';
		
	}
?>

