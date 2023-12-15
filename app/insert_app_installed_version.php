<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']=$date=$ipAddress=$locationid='';
$myDB =  new MysqliDb();
	
if(isset($Data['appkey']) && $Data['appkey']=='insert_app_installed' && isset($Data['version']) &&  !empty($Data['version']) && isset($Data['EmployeeID']) &&  !empty($Data['EmployeeID']) )
{
			
		     $empId=$Data['EmployeeID'];
		     $version_number=$Data['version'];
		   
		     $QueryInsert = "INSERT INTO `ems`.`app_installed` (`EmployeeID`, `version`) VALUES ('".$empId."', '".$version_number."');
" ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryInsert);
//			 echo $response;
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Insert Successfully";
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

