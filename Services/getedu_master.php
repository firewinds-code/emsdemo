<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;
if(isset($_REQUEST['cmid']) && trim($_REQUEST['cmid'])!=""){
	$cmid=trim($_REQUEST['cmid']);
	
	
		
		$Query="select edu_master from new_client_master where cm_id='".$cmid."'";
	
	$myDB=new MysqliDb();
	$res =$myDB->query($Query);
	if($res)
	{
		echo $res[0]['edu_master'];
	}
	else
	{
		echo NULL;
	}
}
