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
if(isset($Data['appkey']) && $Data['appkey']=='IssueResolvedHappyToHelp')
 {
	    $reqid=$Data['id'];	
		$remark=$Data['CloseDate']='('.date('Y-m-d h:i:s').')  : '.'Thank You Sir Issue is Resolved.';	
		$oldremark=$Data['requester_remark'];	
		if($reqid!="")
		{
			$QueryUpdate = 'call close_issueticket("'.$oldremark.' | '.$remark.'","'.$reqid.'")' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryUpdate);
			 $result=array();
			   if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Issue Resolved  Successfully.";
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

