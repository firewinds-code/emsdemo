<?php  
// Server Config file  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
//$Data=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);

//print_r($Data);
$alert_msg['msg']='';
ini_set('display_errors', '1');
if(isset($Data['Exception']) and $Data['Exception']=='Back Dated Leave')
{             
$ShiftIn = "NA";
$ShiftOut = "NA";
$LeaveType = $Data['LeaveType'];
$IssueType = "NA";
$CurrAtt = "NA";
$UpdateAtt = "NA";
}
else if (($Data['Exception'] == "Working on WeekOff" || $Data['Exception'] == "Working on Leave") && ($Data['ShiftIn']!='NA' && $Data['ShiftOut']!='NA' ))  
{
    $ShiftIn = $Data['ShiftIn'];
    $ShiftOut = $Data['ShiftOut'];
    $LeaveType = "NA";
    $IssueType = "NA";
    $CurrAtt = "NA";
    $UpdateAtt = "P";
}
$EmployeeID=$Data['EmployeeID'];
$Name=$Data['name'];
$EmployeeComment=$Data['ecomment'];
$DateFrom=$Data['DateFrom'];
$DateTo=$Data['DateTo'];
$Exception=$Data['Exception'];
$MngrStatusID = "Pending";
$HeadStatusID = "Pending";
	if($EmployeeID!="" && $Exception!="" && $ShiftIn!="" && $ShiftOut!="" && $DateFrom!="" && $DateTo!="" )
	{
		$checkActiveID="SELECT cm_id FROM ems.ActiveEmpID where EmployeeID='".$EmployeeID."' ";
	 	  $myDB =  new MysqliDb();
		  $response =$myDB->rawQuery($checkActiveID);
		  $error = $myDB->getLastError();
	 	  if(empty($error) && count($response)!=0)
	 	  {
			  $sqlInsertException = 'call sp_InsertException("'.$EmployeeID.'","'.$Name.'","'.addslashes($Exception).'","'.$EmployeeComment.'","'.$DateFrom.'","'.$DateTo.'","'.$MngrStatusID.'","'.$HeadStatusID.'","'.$IssueType.'","'.$CurrAtt.'","'.$UpdateAtt.'","'.$ShiftIn.'","'.$ShiftOut.'","'.$LeaveType.'","App")';
			$myDB = new MysqliDb();
			$flag = $myDB->query($sqlInsertException);
			$error = $myDB->getLastError();
				if(empty($error))
				{
					$result['status']=1;
					$result['msg']="Request submitted successfully";
				}
				else
				{
					$result['status']=0;
					$result['msg']='Request not submitted ';
				}
		}
        else
		{
		  $response['status']=0;
		  $response['msg']="You are inactive.";
		 }
	}
	else
	{
			$result['status']=0;
			$result['msg']='Blank input not valid ';
	}				     	
echo json_encode($result);
?>


