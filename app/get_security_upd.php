<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']=$secques=$secans=$EmployeeID='';
/*Raw Data for select :-{
	"EmployeeID":"CE031929855",
	"secques":"xyz",
	"secans":"xyz",
	"appkey":"select_security"
	
} 
Raw Data for update :-{
	"EmployeeID":"CE031929855",
	"appkey":"select_security"
	
} 
Link:-localhost/ems/branches/app/get_security_upd.php*/
if(isset($Data['appkey']) && $Data['appkey']=='select_security')
{
			 $EmployeeID=$Data['EmployeeID'];	
			 $QuerySelect = 'select secques,secans  from employee_map where employeeid="'.$EmployeeID.'"' ; 
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QuerySelect);
			//var_dump($response);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($myDB->getLastError()))
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

elseif(isset($Data['appkey']) && $Data['appkey']=='update_security')
{
			 $EmployeeID=$Data['EmployeeID'];
			 $secques=$Data['secques'];
			 $secans=$Data['secans'];
			 $QueryUpdate = 'update employee_map set password_updated_time=now() , secques="'.$secques.'" ,secans="'.$secans.'" where employeeid="'.$EmployeeID.'";' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->query($QueryUpdate);
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']='Changed Successfully.';
				} 
				else
				{
					$result['status']=0;
					$result['msg']=getLastError();
				}
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);

?>

