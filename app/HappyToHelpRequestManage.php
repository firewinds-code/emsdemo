<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$locationid='';
$myDB =  new MysqliDb();
if(isset($Data) && count($Data) > 0 )
	{
             if(isset($Data['appkey']) && $Data['appkey']=='HappyToHelpRequestManage')
		        {
		        	 $emp_id=$Data['EmployeeID'];
		        	 $locationid=$Data['locationid'];
		        	 if($emp_id!="")
		        	 {
					      $query = 'call App_get_issuetracker_migration("'.$emp_id.'","'.$locationid.'")';
                          $res =$myDB->query($query);
                          //$row_count = $myDB->count;			
				          if($res)
				             {
					             $response['msg']="Got Data.";
					             $response['status']=1;
					             $response['HappyToHelp Request Details']=$res;

				             }
				         else
				             {
					             $response['msg']="Don't have any request.";
					             $response['status']=2;

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

