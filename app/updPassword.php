<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
//print_r($Data);
$result['msg']='';
$myDB =  new MysqliDb();
if(count($Data) >0)
{
	
	if(isset($Data['appkey']) && $Data['appkey']=='ces')
	{
			if(isset($Data['emp_id']) &&  $Data['emp_id']!="")
			{
			 $empid=$Data['emp_id'];	
			}
			if(isset($Data['newpassword']) &&  $Data['newpassword']!="")
			{
				 $newpassword=md5($Data['newpassword']);	
			}
		
			 $QueryUpdate = 'UPDATE employee_map SET password ="'.$newpassword.'", password_updated_time=now() where EmployeeID="'.$empid.'"' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryUpdate);
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Update Successfully";
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
				$result['msg']="Bad Request";
			
}
echo  json_encode($result);
}
?>

