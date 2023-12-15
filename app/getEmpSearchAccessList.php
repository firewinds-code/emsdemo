<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
 $myDB=new MysqliDb();
 //$connection = $mysqli->mysqli();
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
date_default_timezone_set('Asia/Kolkata');
$accessList = array('CE03070003','CE10091236');
$response = array();


	if(isset($Data) && count($Data) > 0)
	{
		if(isset($Data['appkey']) && $Data['appkey']=='isEmpCanAccessSearch' && isset($Data['empId']) && !empty($Data['empId']))
		{
			
			
				 $emp_id=($Data['empId']);
		
							
				if(in_array(strtoupper($emp_id), $accessList))
				{
					$response['msg']="Sucessfully login";
					$response['status']=1;
					$response['access']='yes';
				}
				else
				{
					$response['msg']="Not allowed To access";
					$response['status']=0;

				}
		}
		else{
				$response['msg']="Bad Request";
				$response['status']=0;
		}
	
	}	
	else
	{
			$response['msg']="Invalid Request";
			$response['status']=0;
	}	
		
		echo json_encode($response);
	?>