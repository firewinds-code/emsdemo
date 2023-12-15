<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;
if($_REQUEST)
	{
		$myDB=new MysqliDb();
		$Query="call GetCmIdByEmpId('".$_REQUEST['EmployeeID']."')";
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
				echo 'CMID NOT EXIST';
			}
	}
else
	{
		echo 'EmployeeID PLEASE !';
	}	
?>