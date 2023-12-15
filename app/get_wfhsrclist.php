<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$myDB =  new MysqliDb(); 
if(isset($Data['appkey']) && $Data['appkey']!='' && $Data['appkey']=='wfhsrclist')
{
		$response1=array("CE031929841","CE10091236","CE03070003");
		$result['status']=1;
		$result['msg']="Data found.";
		$result['Data']=$response1;
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request.";
}
echo  json_encode($result);
?>

