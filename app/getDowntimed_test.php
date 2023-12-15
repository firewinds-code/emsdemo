<?php
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
//$Data=array();
$flag=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);	
ini_set('display_errors', '1');
//var_dump($Data);

$EmployeeID=$Data['EmployeeID'];
$processName=$Data['process'];
$subprocessName=$Data['subprocess'];
$key=$Data['appkey'];
$query='';
$dataArray=array();
if($key=='ddata')
{
	$myDB = new MysqliDb();
	$rst_req1 = $myDB->query('select cm_id from employee_map where EmployeeID="'.$EmployeeID.'"');
 	if(count($rst_req1) >= 1)
 	{
 		
		 $myDB = new MysqliDb();
		$rst_req = $myDB->query('call sp_GetDTReqID1_test("'.$rst_req1[0]["cm_id"].'")');
	//print_r($rst_req); die;
 	if(count($rst_req) >= 1)
	{
		foreach($rst_req as $key => $value)
		{
		
			if($value['text'] == "OPS")
			{
				
				if($Data['status'] == 6)
				{
					
					$myDB = new MysqliDb();
					 $dt_client_training  =$myDB->query('select distinct downtime_time_master.client_training from downtime_time_master where  client_training ="Yes" and cm_id in (select cm_id from employee_map where EmployeeID ="'.$EmployeeID.'")');
																		
					if(count($dt_client_training) > 0 && $dt_client_training )
					{
						$dataArray['Request_type'][]='Client Training';		
						$dataArray['FAID'][]=	$value['value'];
					}
					
					$myDB = new MysqliDb();
					$result_qp  =$myDB->query('select EmployeeID from tbl_nestor where EmployeeID = "'.$EmployeeID.'" limit 1');
					if(count($result_qp)>0)
					{
						if(isset($result_qp[0]['EmployeeID']) && !empty($result_qp[0]['EmployeeID']))
						{
							//echo '<option value="'.$value['value'].'">Nestor</option>';
					
							$dataArray['Request_type'][]='Nestor';	
							$dataArray['FAID'][]=	$value['value'];
						}
					}
					$myDB = new MysqliDb();
					$result_qp  =$myDB->query('select EmployeeID from tbl_bqm where EmployeeID = "'.$EmployeeID.'" limit 1');
					if(isset($result_qp[0]['EmployeeID']) && !empty($result_qp[0]['EmployeeID']))
					{
						//echo '<option value="'.$value['value'].'">BQM</option>';
						
						$dataArray['Request_type'][]='BQM';
						
						$dataArray['FAID'][]=$value['value'];
					}
					
					$myDB = new MysqliDb();
					$result_qp  =$myDB->query('select EmployeeID from tbl_buddy where EmployeeID = "'.$EmployeeID.'" and cast(now() as date) between Buddy_Start and Buddy_End');
					if(isset($result_qp[0]['EmployeeID']) && !empty($result_qp[0]['EmployeeID']))
					{
						$myDB = new MysqliDb();
						
						$dt_validate  =$myDB->query('select distinct cm_id from buddy_dtmatrix where cm_id in (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'")');
						if(count($dt_validate) > 0 )
						{
							//echo '<option value="'.$value['value'].'">Buddy Support</option>';
							$dataArray['Request_type'][]='Buddy Support';
							$dataArray['FAID'][]=	$value['value'];	
						}
						
					}
				}
					
			  }
			else if($value['text'] == "Quality")
			{
				if($Data['status'] == 5)
				{
					//echo '<option value="'.$value['value'].'">OJT</option>';
					$dataArray['Request_type'][]='OJT';
					$dataArray['FAID'][]=	$value['value'];
					
				}
					
			}
			elseif($value['text'] != "ER/HR" && $value['text'] != "Training")
			{
				//echo '<option value="'.$value['value'].'">'.$value['text'].'</option>';
				
					$dataArray['Request_type'][]=$value['text'];
					$dataArray['FAID'][]=	$value['value'];
					
					
			}
			else
			{
					$flag['status']=0;
					$flag['msg']='Data not found';		
			}
			
			$flag['data']=$dataArray;
			$flag['status']=1;
			$flag['msg']='Data found';
			
			$str= "select ReportsTo,EmployeeName from downtimereqid1 inner join personal_details on EmployeeID = ReportsTo where process ='".$processName."' and SubProcess ='".$subprocessName."' limit 1";
		 	$myDB=new MysqliDb();	
			$result = $myDB->query($str);
			$error = $myDB->getLastError();
		  	if(count($result) > 0)
		  	{
				foreach($result as $key => $value)
				{
					
					$flag['ReportsTo']=$value['ReportsTo'];
					
				}
			}else{
				$flag['ReportsTo']='';
			}
		}
	}
	else
	{
		
			$flag['status']=0;
			$flag['msg']='Data not found ';
	}
	}
	else
	{
		
			$flag['status']=0;
			$flag['msg']='Data not found ';
	}
 	
	
}
else{
		$flag['status']=0;
		$flag['msg']='Data not set ';
	}									 
	
	
if(count($dataArray)>0){
	$dataarray=json_encode($flag);
	print_r($dataarray);
					
}
else
 {
	echo json_encode($flag);
	
}
?>