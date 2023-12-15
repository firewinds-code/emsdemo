<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '1');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';
$myDB=new MysqliDb();
$emp_id=$Data['EmployeeID'];	
$leaveStatus='Pending';
$RsnLeave=$Data['reason_leave'];
$dateon=$Data['dateon'];
$Leavecount=$Data['leave_count'];
$DateTo=$Data['dateto'];
$DateFrom=$Data['datefrom'];
$createBy=$Data['EmployeeName'];
$LeaveType =$Data['leave_type'];
$flag=1;
	if(isset($Data) && count($Data) > 0   )
	{
		if(date('Y-m-d',strtotime($DateFrom))<date('Y-m-d'))
		{
			$flag = 0;
		}
		if($flag == 1)
		{
			
		
		if(isset($Data['appkey']) && $Data['appkey']=="leave" && $emp_id!="")
		{
		  $checkActiveID="SELECT cm_id FROM ems.ActiveEmpID where EmployeeID='".$emp_id."' ";
	 	  $myDB =  new MysqliDb();
		  $response =$myDB->rawQuery($checkActiveID);
		  $error = $myDB->getLastError();
	 	  if(empty($error) && count($response)!=0)
	 	  {
		   $Inser_Branch='call addLeave("'.$emp_id.'","'.$DateFrom.'","'.$DateTo.'","'.$RsnLeave.'","'.$dateon.'","'.$Leavecount.'","'.$leaveStatus.'","'.$createBy.'","'.$LeaveType.'","App")'; 
		   	$result = $myDB->query($Inser_Branch);
	      	$error = $myDB->getLastError();
			if(empty($error))
	         {
		        $response['status']=1;
		        $response['msg']='Leave added successfully';
		       
	         }
	     	else
	         {
		        $response['status']=0;
		        $response['msg']='Leave request not added '.$error;

	         }
			
        }
         else
		  {
		  	$response['status']=0;
			$response['msg']="You are inactive.";
		  }
	}
    	}
    	else
    	{
			$response['status']=0;
			$response['msg']='Please check device date';
		}
    }
    
    else
    {
		$response['status']=0;
		$response['msg']='Invallid request.';
	}
    echo json_encode($response);
?>
