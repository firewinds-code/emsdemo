<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call sp_getMsgTrail("'.$_REQUEST['ID'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			if($_REQUEST['Emp']==$_REQUEST['Emp1'])
			{
				if($_REQUEST['Emp'] != $value['CreatedBy'])
				{
					echo '<p><span><b>Handler Remarks</b> </span> <span class="blue-text">'.$value['CreatedOn'].'</span> : '.$value['Comments'].'</p>';
				}
				else
				{
					echo '<p><span><b>'.$value['EmployeeName'].' ('.$value['CreatedBy'].')</b> </span> <span class="blue-text">'.$value['CreatedOn'].'</span> : '.$value['Comments'].'</p>';	
				}
				
			}
			else
			{
				echo '<p><span><b>'.$value['EmployeeName'].' ('.$value['CreatedBy'].')</b> </span> <span class="blue-text">'.$value['CreatedOn'].'</span> : '.$value['Comments'].'</p>';	
			}
			
		}
		echo '<br />';
	}
	else
	{
		echo 'No Comment ';
		
	}
?>

