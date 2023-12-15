<?php
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

class rest_API
{	
function insert_data($data)
{	
		header('Content-type: application/json');
		$responseDataGet = json_decode($data, TRUE);
		$counter=0;
		$counter_err=0;
		$counter_suc=0;
		$counter_loc=0;
		$counter_uid=0;
		$counter = sizeof($responseDataGet['data']);
	if($counter<=1000)
	{
		$myDB =  new MysqliDb();
	foreach($responseDataGet['data'] as $responseData)
	{		
		if($responseData["uid"]=='bachan' && $responseData["pwd"]=='bachan@123')
		{
			if(!empty($responseData['Location']))
			{
			$EmployeeID=$responseData['EmployeeID']."GN";
			$datasentdt=date("yyyy-mm-dd hh:mm:ss");
		$Query="Call InsertBiopunch_temp('".$EmployeeID."','".$responseData['PunchTime']."','".$responseData['DateOn']."','".$responseData['EmpID']."','".$responseData['Location']."','".$datasentdt."')"; 					
					$result = $myDB->query($Query);	 
					$mysql_error = $myDB->getLastError();
					if(empty($mysql_error))
					{
						$counter_suc++;						
					}
					else
					{
						$counter_err++;
					}
			}
			else
			{
				$counter_err++;
				$counter_loc++;
				//echo '{"status":"fail","Msg":"Location Missing for "'.$responseData['EmployeeID'].'}';
			}  
		}
		else
		{
				$counter_err++;	
				$counter_uid++;
				//echo '{"status":"fail","Msg":"Invalid UserID or Password for "'.$responseData['EmployeeID'].'}';
		}
	}
	
	//	
	if($counter_suc>0)
	echo '{"status":"success","Msg":"'.$counter_suc.' Records Inserted"}';
if($counter_err>0)
	echo '{"status":"fail","Msg":"'.$counter_err.' Records Not Inserted"}';
	//echo '{"status":"fail","Msg":"Location Missing for "'.$responseData['EmployeeID'].'}';
	if($counter_loc>0)
	echo '{"status":"fail","Msg":"Location Missing for '.$counter_loc.' Records"}';
if($counter_uid>0)
	echo '{"status":"fail","Msg":"Invalid UserID or Password for '.$counter_uid.' Records"}';	
	//echo '{"status":"fail","Msg":"Invalid UserID or Password for "'.$responseData['EmployeeID'].'}';
	//
	}
	else
	{
		echo '{"status":"fail","Msg":"data crosss the limit of 1000 Records"}';
		die;
	}
}	
}

$_POST = file_get_contents('php://input');
if(isset($_POST) && !empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST')
{
	$obj = new rest_API();
	$obj->insert_data($_POST);
}
else
{
	echo '{"status":"fail","Msg":"Unauthorized request"}';
	die;
}
//
    
?> 


