<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
if($_REQUEST['proc'] == 'MIS ')
$process = 'MIS & WFM';
else
$process =  $_REQUEST['proc'];

if(isset($_REQUEST['loc']) && $_REQUEST['loc']!=""){
	$loc=$_REQUEST['loc'];
}

$sql='call get_subprocess_byclient("'.$_REQUEST['id'].'","'.$process.'","'.$loc.'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $mysql_error){
		echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value){
				echo '<option id="'.$value['cm_id'].'">'.$value['sub_process'].'</option>';
			}
		
	}
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
?>
