<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='select corrective_action_form_id,file_path from corrective_action_form_files where corrective_action_form_id="'.$_REQUEST['ID'].'"';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		$i=1;
		foreach($result as $key => $value)
		{
			//echo '<p><span><b>File '.$i.'</b><b>'.$value['created_by'].'</b> </span> <span class="blue-text">'.$value['created_at'].'</span> : '.$value['comment'].'</p>';
			echo'<a href="../corrective_action_form/'.$value['file_path'].'" target="_blank"><b>File'.$i.'</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$i++;
		}
		echo '<br />';
	}
	else
	{
		echo 'Not Exist';
		
	}
?>

