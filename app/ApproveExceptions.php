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
if(isset($Data['appkey']) && $Data['appkey']=='AprroveExceptions')
 {
	    $ExpID=$Data['ID'];	
		$DateFrom=$Data['DateFrom'];	
		$DateTo=$Data['DateTo'];	
		$Exception=$Data['Exception'];	
		$EmployeeComment=$Data['Comments'];	
		$MngrStatusID='Approve';	
		$HeadStatusID='Approve';	
		$ModifiedBy=$Data['ModifiedBy'];		
		$DateModified=$Data['ModifiedOn']=date('Y-m-d H:i:s');		
		$IssueType=$Data['IssueType'];		
		$CurrAtt=$Data['Current_Att'];		
		$UpdateAtt=$Data['Update_Att'];		
		$ShiftIn=$Data['ShiftIn'];		
		$ShiftOut=$Data['ShiftOut'];		
		$LeaveType=$Data['LeaveType'];		
		if($ExpID!="")
		{
			 $QueryUpdate = 'call UpdateRequestDetailsManager("'.$ExpID.'","'.$DateFrom.'","'.$DateTo.'","'.$Exception.'","'.$EmployeeComment.'","'.$MngrStatusID.'","'.$HeadStatusID.'","'.$ModifiedBy.'","'.$DateModified.'","'.$IssueType.'","'.$CurrAtt.'","'.$UpdateAtt.'","'.$ShiftIn.'","'.$ShiftOut.'","'.$LeaveType.'")' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryUpdate);
			 $result=array();
			   if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Update Exception Successfully.";
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

