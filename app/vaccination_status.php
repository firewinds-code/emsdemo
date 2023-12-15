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

	
if(isset($Data['appkey']) && $Data['appkey']=="vaccination" && isset($Data['EmployeeID']) && !empty($Data['EmployeeID']) )
{
			
	 $empId = $Data['EmployeeID'];
	 
				
				//AH/////////////////
				$myDB=new MysqliDb();
				$getQ="select id, EmpID,  Vac1, Vac2 from vaccination_data where EmpID='".$empId."';";	
				$result=$myDB->rawQuery($getQ);
				
				
				if(empty($myDB->getLastError()) && count($result) > 0 ){
					
					//When Bothe Dose Are Completed
					///** 0 --> No
					///** 1 --> 1St Dose Complete
					///** 2 --> 2 Nd Dose Complete
					
					if($result[0]['Vac2'] == 'Yes'){
						$response['vStatus']=2;
					}else{
						$response['vStatus']=1;
					}
					
					
					$response['msg']='Not Vaccinated.';
					$response['status']=1;
					
					
				}else{
					$response['msg']='Not Vaccinated.';
					$response['status']=1;
					$response['vStatus']=0;
				}
		
	
	
}else{
	
	$response['status']=0;
	$response['msg']='Bad Request';
}
  
 echo json_encode($response);       

?>