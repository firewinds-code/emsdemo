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

		if(isset($Data['appkey']) && $Data['appkey']=="getSingleBriefing" && isset($Data['briefingId']) && !empty($Data['briefingId']) &&  isset($Data['EmployeeID']) && !empty($Data['EmployeeID']))
		{
			
			$bId = $Data['briefingId'];
			
			$employeeId = $Data['EmployeeID'];
			
			
			
		 	$sqlConnect2 = "select id, heading, remark1, remark2, remark3, cm_id, uploaded_file, fromdate, view_for, emp_status from brf_briefing where id = '".$bId."';";

			$myDB=new MysqliDb();
			$result2=$myDB->rawQuery($sqlConnect2);
			$error=$myDB->getLastError();
			$rowCount = $myDB->count;
					
	 		 $mysql_error= $myDB->getLastError();
	      if(empty($mysql_error) && count($result2) > 0 )
	         {
	         	
		        $response['status']=1;
		        $response['msg']='Data Got Successfully';
		        $response['data']= $result2;
	         }
	     else
	         {
	         	$response['data']='Not Found';
		        $response['status']=0;
		        $response['msg']='Data Not Found';

	         }
	
        }else{
        	$response['data']='Not Found';
        	$response['status']=0;
		    $response['msg']='Bad Request';
        }
  
 echo json_encode($response);       

?>