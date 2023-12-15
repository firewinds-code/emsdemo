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
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='covid_dec')
{
		        $EmployeeID=$Data['EmployeeID'];
		        $Employeename=$Data['Employeename'];
		        $mobilenum=$Data['mobilenum'];
		        $address=$Data['address'];
	            $query = "Insert into `ack_covid_weekly_form` set  EmployeeID='".$EmployeeID."', Employeename='".$Employeename."', EmpMobile='".$mobilenum."', empAddress='".addslashes($address)."'";
                $res =$myDB->rawQuery($query);
                if (empty($myDB->getLastError()))
				 {
					$result['msg']="Acknowledged successfully. ";
			        $result['status']=1;
				}
				else
				{
				     $result['msg']=getLastError();
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

