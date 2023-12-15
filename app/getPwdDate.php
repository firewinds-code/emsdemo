<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey']=='getPwdDate')
{
		        $emp_id=$Data['EmployeeID'];
	            $query = 'select DATE_FORMAT(password_updated_time,"%Y-%m-%d") as password_updated_time from employee_map where employeeid="'.$emp_id.'"; ';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msg']="Data  Found";
			        $result['status']=1;
			        $result['PasswordUpdatedTime']=$res;
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

