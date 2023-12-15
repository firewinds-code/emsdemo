<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']=$date=$ipAddress=$locationid='';
$myDB =  new MysqliDb();
	
if(isset($Data['appkey']) && $Data['appkey']=='insert_version_app')
{
			if(isset($Data['version']) &&  $Data['version']!="")
			{
			 $version=$Data['version'];	
			}
		     $description=$Data['description'];
		     $version_number=$Data['version_number'];
		     $mandatory=$Data['mandatory'];
		     $QueryInsert = 'Insert into app_maintenance set  version="'.$version.'", description="'.$description.'", version_number="'.$version_number.'",mandatory="'.$mandatory.'";' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryInsert);
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

