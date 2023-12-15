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

if(isset($Data['EmployeeID']) and $Data['Exception']=='Biometric issue')
{
 	$EmployeeID=$Data['EmployeeID'];
	$Name=$Data['name'];
	$EmployeeComment=$Data['ecomment'];
	$DateFrom=$Data['DateFrom'];
	$DateTo=$Data['DateTo'];
	$ShiftIn=$Data['AccessIn'];
	$ShiftOut=$Data['AccessOut'];
	//$Exception=$Data['Exception'];
	$Exception='Biometric issue';
	$MngrStatusID = "Pending";
	$HeadStatusID = "Pending";
	$IssueType = 'Biomertic Issue';
    $CurrAtt = $Data['CurrAtt'];
    $UpdateAtt = $Data['UpdateAtt'];
	$LeaveType = 'NA';
	if($EmployeeID!="" && $Exception!=""  && $DateFrom!="" && $DateTo!="" )
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
		$result = array();
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
	  	$result['status']=0;
		$result['msg']="You are inactive.";
	  }
	}
	else
	{
			$result['status']=0;
			$result['msg']='Blank input not valid ';
	}				     	
}
else
{
		$result['status']=0;
		$result['msg']='Blank input not valid ';
}
print_r(json_encode($result));
?>


