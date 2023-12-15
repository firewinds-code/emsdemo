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
$myDB=new MysqliDb();
$emp_id=$Data['EmployeeID'];	
$DateTo=$Data['dateto'];
$DateFrom=$curDate='';
if(isset($Data['datefrom']))
{
	$DateFrom=$Data['datefrom'];
}
	if(isset($Data) && count($Data) > 0 )
	{
		if(isset($Data['appkey']) && $Data['appkey']=="getw")
		{
		    $getQuery="select count(InTime) as tWO from roster_temp where EmployeeID='".$emp_id."' and InTime='WO' and DateOn between '".$DateTo."' and '".$DateFrom."'"; 
		}
		elseif(isset($Data['appkey']) && $Data['appkey']=="getExep")
		{
			 $excption=$Data['excption'];
			 $getQuery="select count(*) counts  from exception where month(DateFrom) =  ".intval(date('m',strtotime($DateFrom)))." and year(DateFrom) =  ".intval(date('Y',strtotime($DateFrom)))." and Exception = '".$excption."' and EmployeeID = '".$emp_id."' and MgrStatus != 'Decline'";
			
		}
		elseif(isset($Data['appkey']) && $Data['appkey']=="getWOd")
		{
			 $curDate=date('Y-m-d');
			 $getQuery="select DateOn,InTime,OutTime from   roster_temp where EmployeeID='".$emp_id."' and dateOn>'".$curDate."' order by DateOn asc ";
		}
		else
		{
        	 	$response['status']=0;
		        $response['msg']='Key not match';
		        $response['data']='';
        }
	   if($getQuery!="")
	   {
	   		$result = $myDB->query($getQuery);
      		$error = $myDB->getLastError();
      		if(empty($error))
	         {
		        $response['status']=1;
		        $response['data']=$result;
		         $response['msg']='Got data'; 
	         }
	     	else
	         {
		        $response['status']=0;
		        $response['msg']='Data not found';
		        $response['data']='';
	         }
	   }
    }
    else
    {
    	$response['status']=0;
        $response['msg']='Input data not found.';
        $response['data']='';
    }
    echo json_encode($response);
?>
