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
$response['level1']='';
$response['level2']='';

	
if(isset($Data['appkey']) && $Data['appkey']=="getApproverLevels" && isset($Data['EmployeeID']) && !empty($Data['EmployeeID']) )
{
			
	 $empId = $Data['EmployeeID'];
	 
				
				//AH/////////////////
				
				//Sir We Want That This Employye can Approve LEave or Exception or Both ?
				//So Please write a querry so that we can pass as response .ok
				$myDB=new MysqliDb();
			//	$getQ="select case when l1empid ='".$empId."' then 'YES' else 'NO' end as Level1, case when l2empid //='".$empId."' then 'YES' else 'NO' end as Level2  from module_master_new ";	
				$getQ="select distinct module_name from module_master_new where '".$empId."' in (l1empid,l2empid) ; ";	
				$result=$myDB->rawQuery($getQ);
				
				
				
				if(empty($myDB->getLastError()) ){
					
					 $isExpception = 'NO';
					 $isLeave = 'NO';
					if( count($result) > 0 ){
						//For Row 1
						if($result[0]['module_name'] == 'Leave'){
							$isLeave = 'YES';
						}
						
						if($result[0]['module_name'] == 'Exception'){
							$isExpception = 'YES';
						}
					 
					//For Row 2
						if(count($result) > 1){
							if($result[1]['module_name'] == 'Leave'){
							$isLeave = 'YES';
							}
						
							if($result[1]['module_name'] == 'Exception'){
								$isExpception = 'YES';
							}
						}
						
						$response['level1']='Yes';
						$response['level2']='Yes';
						$response['isLeave']=$isLeave;
						$response['isException']=$isExpception;
						$response['msg']='Data Found.';
						$response['status']=1;
					}else{
					$response['level1']='No';
					$response['level2']='No';
					$response['isLeave']=$isLeave;
					$response['isException']=$isExpception;
					$response['msg']='Data Found.';
					$response['status']=1;
					}
					
				}else{
					$response['msg']='Data Not Found.';
					$response['status']=1;
				}
		
	
	
}
else{
	
	$response['status']=0;
	$response['msg']='Bad Request';
}
  
 echo json_encode($response);       

?>