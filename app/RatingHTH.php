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
if(isset($Data['appkey']) && $Data['appkey']=='RatingHappyToHelp' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="")
 {
 	  $EmployeeID=$Data['EmployeeID'];	
 	  $checkActiveID="SELECT cm_id FROM ems.activeempid where EmployeeID='".$EmployeeID."' ";
 	  $myDB =  new MysqliDb();
	  $response =$myDB->rawQuery($checkActiveID);
	  $error = $myDB->getLastError();
 	  if(empty($error) && count($response)!=0)
 	  {
	    $reqid=$Data['id'];	
		$rating=$Data['rating'];	
		$feedback=$Data['feedback'];	
		if($reqid!="")
		{
			$QueryUpdate = "update issue_tracker set  rating='".$rating."' ,feedback='".$feedback."',feedback_date=now()  where id='".$reqid."'" ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryUpdate);
			 $result=array();
			   if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Request Rated Successfully.";
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

