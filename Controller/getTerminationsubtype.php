<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$sql='select title from termination_description;';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if( count($result) > 0 && $result)
	{
		echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value){
				echo '<option value="'.$value['title'].'" >'.$value['title'].'</option>';
			}
	}
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
?>

