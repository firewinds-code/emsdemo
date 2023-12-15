<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$myDB =  new MysqliDb();
$result=array();
$token=$EmployeeID=$passwod=$passwod1=$source=$sysip=$url='';
if(isset($Data['appkey']) && $Data['appkey']=='checkauth')
{ 
	$EmployeeID=$Data['EmployeeID'];
	$url=$Data['url'];
	if(isset($Data['token']) && trim($Data['token'])!="" && trim($EmployeeID)!="")
	{
		$token=$Data['token'];
		
		$myDB =  new MysqliDb();
		$Query="Select EmployeeID,token from login_history where EmployeeID='".$EmployeeID."'  order by ID desc limit 1";
		//and token='".$token."'
		$response =$myDB->query($Query);
		$mysql_error=$myDB->getLastError();
		if($mysql_error=="" and count($response)>0)
		{
			$rtoken=$response[0]['token'];
			if($token==$rtoken){
				$result['status']=1;
				$result['msg']="Token valid";
			}else{
				$result['status']=0;
				$result['msg']="Token not valid";
			}
			 
		}
	}
	else if(isset($Data['passwod']) && trim($Data['passwod'])!="" && trim($EmployeeID)!="")
	{
		$passwod=md5($Data['passwod']);
		$passwod1= $Data['passwod'];
		//echo $passwod='c4ca4238a0b923820dcc509a6f75849b' for 1;
		$myDB =  new MysqliDb();
		echo $Query="Select EmployeeID from employee_map where EmployeeID='".$EmployeeID."' and password='".$passwod."'";
		$response =$myDB->query($Query);
		$mysql_error=$myDB->getLastError();
		if($mysql_error=="" and count($response)>0)
		{
			 $result['status']=1;
			 $result['msg']="Valid";
		}
		else{
			$result['status']=0;
			$result['msg']="Invalid User";
		}
	}
	else
	{
		$result['status']=0;
		$result['msg']="Invalid User";
	}
	if(isset($Data['source']))
	{
		$source=$Data['source'];
	}
	if(isset($Data['sysip']))
	{
		$sysip=$Data['sysip'];
	}
	if($EmployeeID!=""){
		$inserQuery="Insert into dashboard_check_auth set  EmployeeID='".$EmployeeID."', password='".$passwod1."', token='".$token."', source='".$source."', ip_address='".$sysip."', durl='".$url."'";
		$myDB =  new MysqliDb();
	   $response =$myDB->rawQuery($inserQuery);
		$mysql_error=$myDB->getLastError();
	}
	
	/*if($mysql_error==""){
		$result['capture']=1;
	}*/
}	
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);	

?>