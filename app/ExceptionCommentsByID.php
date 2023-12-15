<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
             if(isset($Data['appkey']) && $Data['appkey']=='ExceptionCommentByID')
		        {
		        	 $exp_id=$Data['exp_id'];
		        	 if($exp_id!="")
		        	 {
					      $query = 'select CreatedBy, Comments,CreatedOn from exceptioncomments where ExpID="'.$exp_id.'" ';
                          $res =$myDB->query($query);			
				          if($res)
				             {
					             $response['msg']="Got Data.";
					             $response['status']=1;
					             $response['ExceptionCommentByID']=$res;
				             }
				         else
				             {
				             	 $response['status']=2;
					             $response['msg']="Don't have any Exception Request.";
				             }
					 }
					 else
					 {
					 	 $response['msg']="EmployeeID is blank.";
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
echo  json_encode($response);
?>

