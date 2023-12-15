<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_REQUEST['getr']) && $_REQUEST['getr']=='getremark' &&  $_REQUEST['rygstatus']!=""){
	$rygstatus=$_REQUEST['rygstatus'];
	$myDB=new MysqliDb();
	$sql="select id,substatus from ryg_substatus_master where RYG='".$rygstatus."'";
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error) && count($result)>0){
		if(count($result)>1){
			$option= "<option value=''  >Select</option>";
		}
		foreach($result  as $val){
			$option .= "<option value='".$val['id']."'  >".$val['substatus']."</option>";
		}
		
	}
	echo $option;
}
?>

