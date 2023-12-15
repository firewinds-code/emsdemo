<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;
if(isset($_REQUEST['cmid']) && trim($_REQUEST['cmid'])!="" && isset($_REQUEST['desig']) && trim($_REQUEST['desig'])!=""){
	$cmid=trim($_REQUEST['cmid']);
	$desig=trim($_REQUEST['desig']);
	if($desig !='CSA' && $desig != 'FIELD EXECUTIVE')
	{
		$Query="select 'P Map Test' as cert_name,'PMapTest' as filename;";
	}
	else
	{
		
		$Query="select a.ID, a.cm_id, a.cert_name, a.filename from certification_require_by_cmid a where a.cm_id='".$cmid."'";
	}
	$myDB=new MysqliDb();
	$res =$myDB->query($Query);
	if($res)
	{
		foreach($res as $key=>$value)																															{
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	}
	else
	{
		echo NULL;
	}
}	
	?>