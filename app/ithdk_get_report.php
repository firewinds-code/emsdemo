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

	
if(isset($Data['appkey']) && $Data['appkey']=="getITHDkReport" && isset($Data['EmployeeID']) && !empty($Data['EmployeeID']))
{
			
	 $employeeId = $Data['EmployeeID'];

	 
	 	$myDB=new MysqliDb();
	 	
			
			$sql="SELECT id, ticket_id, process_client, process, priorty, category, issue_type, issue_disc, agent_impacted, total_agents, requester_empId, requester_name, requester_email, requester_mobile, location, tat, exten_tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email, inprogress_remark, inprogress_date, closing_remark, closing_date, rca_text, rca_attachement, rca_date, created_date FROM ems.ithdk_ticket_details where handler_empId = '".$employeeId."' and rca_date is null order by id desc";
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