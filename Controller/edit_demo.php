<?php
require_once(__dir__.'/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
	$condition='id='.$_REQUEST['ID'];
	unset($_REQUEST['ID']);
	$myDB=new MysqliDb();
	//$q1="update loginfo set id='".$_REQUEST['ID']."'where id='".$_REQUEST['ID']."'";
	//$result=$myDB->query($sql);
	$result = $myDB->update('loginfo',$_REQUEST,$condition);
	$mysql_error=$myDB->getLastError();
	if(empty($mysql_error))	
	{
		echo  '<span class="text-success"><b>Message :</b> Info Updated Successfully ...</span>';
	}
	else
	{
		echo '<span class="text-danger"><b>Message :</b> Info not Updated :: '.$mysql_error.'</span>';
	}
	
?>

