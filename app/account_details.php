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
$result=array();
$myDB =  new MysqliDb();
             if(isset($Data['appkey']) && $Data['appkey']=='account_details')
		        {
		         $emp_id=$Data['EmployeeID'];	
	             $Queryaccountdetails = 'call get_salarydetails("'.$emp_id.'")';
	             $res =$myDB->query($Queryaccountdetails);				
				    if($res)
		             {        
		                 $result['msg']="Got Data.";
					     $result['status']=1;
					     $result['AccountDetails']=$res;

				     }
				   else
				     {
					     $result['msg']="Data Not Found";
				         $result['status']=0;	
				     }
		        
               }
            else
               {
               	     $result['msg']="Bad Request";
		             $result['status']=0;
			   }

echo  json_encode($result);
?>

