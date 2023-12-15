<?php
require_once(__dir__.'/../Config/init.php');
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$myDB =  new MysqliDb();
if(count($Data) && count($Data) > 0)
{
	if(isset($Data['appkey']) && $Data['appkey']=='DeleteDowntime')
	{
		     $downtime_id=$Data['ID'];
			 $QueryDelete = 'call sp_delete_dt('.$downtime_id.')';
			 $res =$myDB->rawQuery($QueryDelete);
			 $row_count = $myDB->count;
	         $mysql_error=$myDB->getLastError();
	        
			   if(empty($mysql_error))
				{
					  
					$response['status']=1;
					$response['msg']="Downtime Request Delete Successfully.";
				} 
				else
				{
					$response['status']=0;
					$response['msg']="Data not Deleted Try Again :".$mysql_error;
				}
		}
   else
        {
	
	            $response['status']=0;
				$response['msg']="Appkey not found.";
        }

	}
else
	{
	  $response['msg']="Data not found.";
	  $response['status']=0;
	}
echo  json_encode($response);

?>

