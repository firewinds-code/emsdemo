<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';
if(isset($Data['appkey']) && $Data['appkey']=='levcom')
{
	if(isset($Data['LeaveID']) && $Data['LeaveID']!="")
	{
		$LeaveID=$Data['LeaveID'];	
             $getComment = 'select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id ="'.$LeaveID.'" order by createdon desc' ;  
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($getComment);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($myDB->getLastError()) && count($response)>0)
				{
					$result['status']=1;
					$result['msg']='Data found';
					$result['Comments']=$response;
				}	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Data not found.';
			  }
	}
	  else
    {
	    $result['status']=0;
		$result['msg']="LeaveID is blank.";
    }       
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);
?>

