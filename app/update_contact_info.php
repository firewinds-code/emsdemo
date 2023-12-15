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
if(isset($Data['appkey']) && $Data['appkey']=='updatemobileno')
{
		        $empid=$Data['EmployeeID'];
		        $mobile=$Data['mobile'];
		        $altmobile=$Data['altmobile'];
		        $em_contact=$Data['em_contact'];
		        $relation=$Data['em_relation'];
		        $emailid=$Data['emailid'];
	            $query = "call Update_mobile_number('".$empid."','".$mobile."','".$altmobile."','".$em_contact."','".$relation."','".$emailid."')";
                $myDB->query($query);
                $mysql_error = $myDB->getLastError();
                if(empty($mysql_error))
				{
					$result['msg']="Contact Updated Successfully.";
			        $result['status']=1;
				}
				else
				{
				     $result['msg']="Contact Not Update.'".$mysql_error."";
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

