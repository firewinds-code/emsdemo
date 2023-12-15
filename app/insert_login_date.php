<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']=$date=$ipAddress=$locationid=$token='';
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey']=='insert_login_date')
{
	if(isset($Data['designation']) &&  $Data['designation']!="" && isset($Data['EmployeeID']) &&  $Data['EmployeeID']!="")
	{
		     $EmployeeID=$Data['EmployeeID'];
		     $designation=$Data['designation'];	
		     $date=$Data['date'];
		     $ipAddress=$Data['ipAddress'];
		     $locationid=$Data['locationid'];
		     $QueryInsert = '';
			if($designation=='CSA')
			{
				$QueryInsert = 'Insert into login_history set  EmployeeID="'.$EmployeeID.'", IP="'.$ipAddress.'", location="'.$locationid.'", source="App" , CreatedOn ="'.$date.'";' ;
			}
			else
			{
				$string1 = str_shuffle('abcdefghijklmnopqrstuvwxyz');
			    $random1 = substr($string1,0,3);
			    $string2 = str_shuffle('1234567890');
			    $random2 = substr($string2,0,3);
			    $random = time().$random1.$random2;
				$token=md5($random);
				$QueryInsert = 'Insert into login_history set  EmployeeID="'.$EmployeeID.'", IP="'.$ipAddress.'", location="'.$locationid.'",token="'.$token.'", source="App" , CreatedOn ="'.$date.'";' ;
				
			}
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryInsert);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['token']=$token;
					$result['msg']="Insert Successfully";
				} 
				else
				{
					$result['status']=0;
					$result['msg']=getLastError();
				}	
	}
	else
	{
		$result['status']=0;
		$result['msg']="Set employeeid.";
	}     
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);
?>

