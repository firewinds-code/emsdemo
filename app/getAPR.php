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
$getQuery='';
$error='';
$myDB=new MysqliDb();
$emp_id=$Data['EmployeeID'];	
$AprDate=$Data['aprdate'];//date(yyyy-mm-dd)
if(isset($Data) && count($Data) > 0)
	{
		if(isset($Data['appkey']) && $Data['appkey']=="getApr")
		{
			$day='D'.date('j',strtotime($AprDate));
		     $getQuery="select time_to_sec(".$day.") as APR ,d.sec  from   hours_hlp h left Join(select sum(time_to_sec(TotalDT)) sec,LoginDate,EmpID from downtime where EmpID ='".$emp_id."' and FAStatus ='Approve' and RTStatus ='Approve' and LoginDate ='".$AprDate."') d on h.EmployeeID=d.EmpID where h.EmployeeID='".$emp_id."' and h.Year=Year('".$AprDate."') and  h.Month=Month('".$AprDate."')";
		   	$result = $myDB->query($getQuery);
		   	if(count($result)>0)
		   	{
		   		$seconds=$result[0]['APR']+$result[0]['sec'];
				$tspr= gmdate('H:i', $seconds);
				$response['status']=1;
	        	$response['APRh']=$tspr;
	         	$response['msg']='Got data';
		   	}
		   	else
	        {
		        $response['status']=0;
		        $response['msg']='Data not found';
		        $response['APRh']='';

	         }
      		$error = $myDB->getLastError();
		}
		else
		{
        	 	$response['status']=0;
		        $response['msg']='Key not match';
		        $response['APRh']='';
        }
   
    }
    else
    {
    	$response['status']=0;
        $response['msg']='Input data not found';
        $response['APRh']='';
    }
    echo json_encode($response);
?>
