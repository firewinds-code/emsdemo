<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['id']) && $_REQUEST['id']!='')
{
    $loc=$_REQUEST['id'];	
}
else
{
	$loc='';
}
$sql='select distinct concat(t2.client_name,"|",t1.process,"|",t1.sub_process) as Process,t1.cm_id from new_client_master t1 join client_master t2 on t1.client_name = t2.client_id left join client_status_master t3 on t1.cm_id=t3.cm_id where t1.location="'.$loc.'" and t3.cm_id is null order by process';
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

