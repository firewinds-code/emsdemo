<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='ncns' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="")
{
	$EmployeeID=$Data['EmployeeID'];	
$QuerySelect = 'select  count(*)ncnscount,  w.sub_process,w.Process,w.clientname,l.location ,reporttoname(w.account_head)as vhName
from login_ncns_smsmail left join whole_details_peremp w on login_ncns_smsmail.employeeid=w.EmployeeID left join location_master  l on w.location=l.id  where cast(login_ncns_smsmail.createdOn as date)=cast(now() as date) and clientname is not null and w.account_head="'.$EmployeeID.'" group by w.sub_process,w.Process,w.clientname,w.location , reporttoname(account_head)'  ;  
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QuerySelect);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($myDB->getLastError()) && count($response)>0)
				{
					$result['status']=1;
					$result['msg']='Data found';
					$result['records']=$response;
				}	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Data not found.';
			  }
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);

?>

