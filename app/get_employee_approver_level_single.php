<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$myDB =  new MysqliDb();
//print_r($Data);

 if(isset($Data['appkey']) && $Data['appkey']=='getEmpApprover' &&  isset($Data['EmployeeID']) && !empty($Data['EmployeeID']) &&  isset($Data['ApproverID']) && !empty($Data['ApproverID']) )
	{
		 $employee=$Data['EmployeeID'];
		 $approver=$Data['ApproverID'];
		 $ReqType=$Data['ReqType'];
			
			$query = "select level, case when l1empid ='".$approver."' then 'YES' else 'NO' end as Level1, case when l2empid ='".$approver."' then 'YES' else 'NO' end as Level2  from module_master_new where EmployeeID = '".$employee."' and module_name='".$ReqType."' order by id desc limit 1;";
			$result =$myDB->query($query);				
		   if(empty($myDB->getLastError()) && count($result) > 0 ){
			
				$response['level1']=$result[0]['Level1'];
				$response['level2']=$result[0]['Level2'];
				$response['level']=$result[0]['level'];
				$response['msg']='Data Found.';
				$response['status']=1;
			}
		   else
		   {
			 $response['msg']="Don't have any Request.";
			 $response['status']=0;
		   }
					   
   }
  else
   {
		$response['msg']="Invalid Data.";
		$response['status']=0;
   }
	
	
echo  json_encode($response);


?>

