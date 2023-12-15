<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$myDB =  new MysqliDb();
//print_r($Data);
if(isset($Data) && count($Data) > 0 )
	{
             if(isset($Data['appkey']) && $Data['appkey']=='GetLeaveRequest')
		        {
		        	 $emp_id=$Data['EmployeeID'];
	                 $query = 'call App_get_pendingleave_emp("'.$emp_id.'")';
                     $res =$myDB->query($query);
                    // var_dump($res);				
				    if($res)
				      {
					     $response['msg']="Got Data.";
					     $response['status']=1;
					     $response['Leave Request Details']=$res;

				      }
				   else
				      {
					     $response['msg']="Don't have any request.";
					     $response['status']=2;

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

