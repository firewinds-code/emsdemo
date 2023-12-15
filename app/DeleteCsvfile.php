<?php
ini_set('display_errors', 0); 
ini_set('display_startup_errors', 0); 
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$myDB =  new MysqliDb();
if(isset($Data) && count($Data) > 0 )
{
             if(isset($Data['appkey']) && $Data['appkey']=='FileDelete')
		        {
		        	 $filename=$Data['filename'];
		        	 if($filename!="")
		        	 {
					 	unlink('/ems/branches/PublicApp/' . $filename);
					 	$response['File']="File Deleted Successfully.";
					    $response['status']=1;
					 }
					 else
					 {
					 	$response['File']="File Not Found.";
					    $response['status']=0;
					 } 
			   }
			  else
			   {
					$response['msg']="Appkey Not found.";
					$response['status']=0;
			   }
	   }
else
{
		$response['msg']="Data not found.";
		$response['status']=0;
}
echo  json_encode($response);


?>

