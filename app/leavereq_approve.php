<?php  
// Server Config file  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$Data=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$alert_msg['msg']='';
$myDB= new MysqliDb();
ini_set('display_errors', '1');
if(isset($Data['appkey']) && $Data['appkey']=="leaveApp" )
{				
	
	$leaveMgrStatus= $Data['leaveMgrStatus'];
	$leaveHrStatus= $Data['leaveHrStatus'];
	$isAH= $Data['isAH'];
    $leaveID = $Data['leaveID'];
	$comment = $Data['comment'];
	$approvedBy =$Data['approvedBy'];// which one want to approve that Id
	
	$level = $Data['level'];
	if($level == 1)
	{
		$leaveMgrStatus = $leaveHrStatus;
		$leaveUpdate = 'call update_leave("'.$approvedBy.'","'.$comment.'","'.$leaveMgrStatus.'","'.$leaveID.'","'.$leaveHrStatus.'","AppAH")';
	}
	
	else
	{
		if($isAH=='YES')
		{
			$leaveUpdate = 'call update_leave("'.$approvedBy.'","'.$comment.'","'.$leaveMgrStatus.'","'.$leaveID.'","'.$leaveHrStatus.'","AppAH")';
		}
		else
		{
			$leaveUpdate='call update_oh_leave("'.$approvedBy.'","'.$comment.'","'.$leaveID.'","'.$leaveHrStatus.'","AppOH")';	
		}
	}
	
	/*if($isAH=='YES')
	{
		$leaveUpdate = 'call update_leave("'.$approvedBy.'","'.$comment.'","'.$leaveMgrStatus.'","'.$leaveID.'","'.$leaveHrStatus.'","AppAH")';
	}
	else
	{
		$leaveUpdate='call update_oh_leave("'.$approvedBy.'","'.$comment.'","'.$leaveID.'","'.$leaveHrStatus.'","AppOH")';	
	}*/
		
	$flag = $myDB->rawQuery($leaveUpdate);
	$error = $myDB->getLastError();
	if($error=="")
	{
		$result['status']=1;
		$result['msg']="Leave updated";
		
	}
	else
	{
		$result['status']=0;
		$result['msg']="Leave not updated";
	}		
}  
else
{
	$result['status']=0;
	$result['msg']="Appkey not define.";
}
echo json_encode($result);
?>