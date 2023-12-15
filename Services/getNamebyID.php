<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');

$EMPID=$Query= null;	
	if($_REQUEST)
	{
	$EMPID=$_REQUEST["empid"];
	$myDB=new MysqliDb();
$Query="select EmployeeName,EmployeeID from personal_details where EmployeeID='".$EMPID."'";
  $dataarray["Status"]="0";
				$res =$myDB->query($Query);
				if($res)
				{	$dataarray["data"]=$res;
					$dataarray["Status"]="1";
				}
				else
				{
					$dataarray["data"]="Employee NOT EXIST";
					$dataarray["Status"]="0";
				}
		}
			else
			{
							$dataarray["data"]="invalid request";
							$dataarray["Status"]="2";
			}	
	echo  json_encode($dataarray);
	?>