<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
if($_REQUEST)
{
	$myDB=new MysqliDb();
	$Query="select distinct cm_id from new_client_master where account_head = '".$_REQUEST['EmployeeID']."';";
	$res =$myDB->query($Query);
	if($res)
	{
		foreach($res as $key=>$value)
		{
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	}
	else
	{
		echo '';
	}
}
else
{
	echo '';
}	
?>