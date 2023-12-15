<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
if($_REQUEST['proc'] == 'MIS ')
$process = 'MIS & WFM';
else
$process =  $_REQUEST['proc'];
$dept=$loc= $sql = '';
if(isset($_REQUEST['dept']) && $_REQUEST['dept']!=""){
	$dept=$_REQUEST['dept'];
}

if(isset($_REQUEST['loc']) && $_REQUEST['loc']!=""){
	$loc=$_REQUEST['loc'];
}
if($_REQUEST['lvl']=='2')
	{
		$sql='call get_subprocess_byclient_new("'.$_REQUEST['id'].'","'.$process.'","'.$dept.'","'.$loc.'")';
	}
	else
	{
		$sql='call get_subprocess_byclient("'.$_REQUEST['id'].'","'.$process.'","'.$dept.'","'.$loc.'")';
	}
 	//echo $sql;
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result)
	{
		echo '<option value="NA" >Select Subprocess</option>';
		foreach($result as $key=>$value){
				echo '<option value="'.$value['cm_id'].'">'.$value['sub_process'].'</option>';
			}
		
	}
	else
	{
		echo '<option value="NA" >Select Subprocess</option>';
		
	}
?>

