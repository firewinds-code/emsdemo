<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['locid']) && $_REQUEST['locid']!='' && isset($_REQUEST['conid']) && $_REQUEST['conid']!='')
{
    $loc=$_REQUEST['locid'];	
    $conid=$_REQUEST['conid'];	
}
else
{
	$loc='';
	$conid='';
}
$sql="select t1.cm_id,concat(t3.client_name,'|',t2.process,'|',t2.sub_process) as Process from manage_consultancy t1 join new_client_master t2 on t1.cm_id=t2.cm_id join client_master t3 on t2.client_name=t3.client_id where locid='".$loc."' and consultancy_id='".$conid."' and Active=1 and (cast(now() as date) between t1.start_date and t1.end_date);";
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if( count($result) > 0 && $result)
	{
		echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value){
				echo '<option value="'.$value['cm_id'].'" >'.$value['Process'].'</option>';
			}
		
	}
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
?>

