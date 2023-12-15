<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='aadharnum' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="")
{
	$EmployeeID=$Data['EmployeeID'];	
$QuerySelect = "select dov_value from doc_details where EmployeeID='".$EmployeeID."' and  (doc_type='Proof of Address' OR doc_type='Proof of Identity') and doc_stype='Aadhar Card'"  ;  
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QuerySelect);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($myDB->getLastError()) && count($response)>0 && $response[0]['dov_value']!="")
				{
					$result['status']=1;
					$result['msg']='Data found';
					$result['aadharnum']=$response[0]['dov_value'];
				}	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Data not found.';
					$result['aadharnum']='';
			  }
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		$result['aadharnum']='';
}
echo  json_encode($result);

?>

