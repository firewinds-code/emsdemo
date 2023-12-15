<?php
require_once(__dir__.'/../Config/init.php');
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
//print_r($Data);
$result['msg']='';
$myDB =  new MysqliDb();
if(count($Data) && count($Data) > 0)
{
/*if(isset($Data['appkey']) && $Data['appkey']=='ReopenHappyToHelp' &&isset($Data['EmployeeID']) && $Data['EmployeeID']!="")*/
if(isset($Data['appkey']) && $Data['appkey']=='ReopenHappyToHelp' )
 {
 
 	 $getEmpId="select requestby,bt from issue_tracker  where id ='".$Data['id']."' ";
 	  $myDB =  new MysqliDb();
 	   $responseEmp =$myDB->rawQuery($getEmpId);
	  $errorEmp = $myDB->getLastError();
 	  if(empty($errorEmp) && count($responseEmp)> 0)
 	  {
 		
 	  $EmployeeID=$responseEmp[0]['requestby'];	
 	  $checkActiveID="SELECT cm_id FROM ems.ActiveEmpID where EmployeeID='".$EmployeeID."' ";
 	  $myDB =  new MysqliDb();
	  $response =$myDB->rawQuery($checkActiveID);
	  $error = $myDB->getLastError();
 	  if(empty($error) && count($response)!=0)
 	  {
 	  	
 	  	exit ;
	  	$reqid=$Data['id'];	
		$oldremark=$Data['old_requester_remark'];	
		$remark=$Data['requester_remark'];	
		if($reqid!="")
		{
			$QueryUpdate = 'call open_issueticket("'.$oldremark.' | '.$remark.'","'.$reqid.'")' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryUpdate);
			 $result=array();
			   if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Request Re Opened Successfully.";
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
			$result['msg']="ID is not found.";
		}
	  }
	  else
	  {
	  	$result['status']=0;
		$result['msg']="You are inactive.";
	  }
	  }else{
			$result['status']=0;
			$result['msg']="Invalid request.";
			
		}
 	   
  }
 else
  {
	 $result['status']=0;
	 $result['msg']="AppKey is not found.";		
  }
}
else
{
	$result['status']=0;
	$result['msg']="Data not found.";
}
echo  json_encode($result);

?>

