<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';

	
if(isset($Data['appkey']) && $Data['appkey']=="getBriefingReport" && isset($Data['EmployeeID']) && !empty($Data['EmployeeID']))
{
			
	 $employeeId = $Data['EmployeeID'];

	 
	 	$myDB=new MysqliDb();
	 	
			
			$sql="select a.*,b.heading,b.id,b.quiz, at.AttemptedDate  from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id 
left JOIN (select AttemptedDate , BriefingID from brf_quiz_attempted where EmployeeID = '".$employeeId."' ) as at on b.id = at.BriefingID  where a.EmployeeID='".$employeeId."' group by a.BriefingId  order by a.id desc limit 10;";
			$result=$myDB->rawQuery($sql);
		if (empty($myDB->getLastError()) && count($result) > 0 )
		{
		////////////////////////
			$response['status']=1;
	   		$response['msg']='Report Data Found.';
	   		$response['data']=$result;
		
		
	}else{
		$response['status']=0;
	    $response['msg']='Bad Request';
	}
		
		
	
	
    }else{
    	
    	$response['status']=0;
	    $response['msg']='Bad Request';
    }
  
 echo json_encode($response);       

?>