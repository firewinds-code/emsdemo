<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '1');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$myDB =  new MysqliDb();
if(isset($Data) && count($Data) > 0 )
	{
             if(isset($Data['appkey']) && $Data['appkey']=='getdtc')
		        {
		        	 $dt_id=$Data['DTID'];
	                 $query = 'call sp_getDTMsgTrail("'.$dt_id.'")';
                     $res =$myDB->query($query);				
				    if($res)
				      {
					     $response['msg']="Got Data.";
					     $response['status']=1;
					     $response['Comment']=$res;

				      }
				   else
				      {
					     $response['msg']="Don't have any comment.";
					     $response['status']=0;

				       }
			   }
			  else
			   {
					$response['msg']="Appkey is not found.";
					$response['status']=0;
			   }
	
	   }
	else
		{
				$response['msg']="Data not found.";
				$response['status']=0;
		}
echo json_encode($response);		
?>

