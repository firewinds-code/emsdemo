<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;
$state_id='';
	if(isset($_REQUEST['sid']) and trim($_REQUEST['sid'])!="")
	{
		$state_id=trim($_REQUEST['sid']);
		$myDB=new MysqliDb();
		$Query="call `getDist`('".$state_id."')";
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
	else
	{
		echo NULL;
	}	
	?>